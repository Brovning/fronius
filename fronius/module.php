<?php

if (!defined('DEBUG'))
{
	define("DEBUG", false);
}

// ModBus RTU TCP
if (!defined('modbusInstances'))
{
	define("modbusInstances", "{A5F663AB-C400-4FE5-B207-4D67CC030564}");
}
if (!defined('clientSockets'))
{
	define("clientSockets", "{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}");
}
if (!defined('modbusAddresses'))
{
	define("modbusAddresses", "{CB197E50-273D-4535-8C91-BB35273E3CA5}");
}
if (!defined('froniusInstances'))
{
	define("froniusInstances", "{850BFB11-5B5F-4A86-76D9-C3DDFDFF055C}");
}


	class Fronius extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			//Properties
			$this->RegisterPropertyString('hostIp', '');
			$this->RegisterPropertyInteger('hostPort', '502');
			$this->RegisterPropertyInteger('pollCycle', '60000');
			$this->RegisterPropertyBoolean('readNameplate', 'false');

			$this->checkProfiles();
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

			//Properties
			$hostIp = $this->ReadPropertyString('hostIp');
			$hostPort = $this->ReadPropertyInteger('hostPort');
			$pollCycle = $this->ReadPropertyInteger('pollCycle');
			$readNameplate = $this->ReadPropertyBoolean('readNameplate');

			$portOpen = false;
			$waitTimeoutInSeconds = 1; 
			if($fp = @fsockopen($hostIp, $hostPort, $errCode, $errStr, $waitTimeoutInSeconds))
			{   
				// It worked
				$portOpen = true;
				fclose($fp);
			}
	
			if($portOpen)
			{
				$this->checkProfiles();
				
				// Splitter-Instance
				$gatewayId = 0;//36219;
				// I/O Instance
				$interfaceId = 0;//38897;

				foreach(IPS_GetInstanceListByModuleID(modbusInstances) AS $modbusInstanceId)
				{
					$connectionInstanceId = IPS_GetInstance($modbusInstanceId)['ConnectionID'];

					if($hostIp == IPS_GetProperty($connectionInstanceId, "Host") && $hostPort == IPS_GetProperty($connectionInstanceId, "Port"))
					{
						if(DEBUG) echo "ModBus Instance and ClientSocket found: ".$modbusInstanceId.", ".$connectionInstanceId."\n";

						$gatewayId = $modbusInstanceId;
						$interfaceId = $connectionInstanceId;
						break;
					}
				}

				if(0 == $gatewayId)
				{
					if(DEBUG) echo "ModBus Instance not found!\n";

					// ModBus Gateway erstellen
					$gatewayId = IPS_CreateInstance(modbusInstances); 
					IPS_SetName($gatewayId, "FroniusModbusGateway");
					IPS_SetProperty($gatewayId, "GatewayMode", 0);
					IPS_SetProperty($gatewayId, "DeviceID", 1);
					IPS_SetProperty($gatewayId, "SwapWords", 0);
					IPS_ApplyChanges($gatewayId);
					IPS_Sleep(100);
				}

				if(0 == $interfaceId)
				{
					if(DEBUG) echo "Client Socket not found!\n";

					// Client Soket erstellen
					$interfaceId = IPS_CreateInstance(clientSockets);
					IPS_SetName($interfaceId, "FroniusClientSoket");
					IPS_SetProperty($interfaceId, "Host", $hostIp);
					IPS_SetProperty($interfaceId, "Port", $hostPort);
					IPS_SetProperty($interfaceId, "Open", true);
					IPS_ApplyChanges($interfaceId);
					IPS_Sleep(100);

					// Client Socket mit Gateway verbinden
					IPS_DisconnectInstance($gatewayId);
					IPS_ConnectInstance($gatewayId, $interfaceId);
				}


				$parentId = IPS_GetInstance(IPS_GetInstanceListByModuleID(froniusInstances)[0])['InstanceID'];



				$inverterModelRegister_array = array(
				/* ********** Common Model **************************************************************************
					Die Beschreibung des Common Block inklusive der SID Register (Register 40001-40002)
					zur Identifizierung als SunSpec Gerät gilt für jeden Gerätetyp (Wechselrichter, String Control,
					Energiezähler). Jedes Gerät besitzt einen eigenen Common Block, in dem Informationen
					über das Gerät (Modell, Seriennummer, SW Version, etc.) aufgeführt sind.
				   ************************************************************************************************** */
//				    array(40001, 2, "R", 3, "SID", "uint32", "", "Well-known value. Uniquely identifies this as a SunSpec Modbus Map"),
//				    array(40003, 1, "R", 3, "ID", "uint16", "", "Well-known value. Uniquely identifies this as a SunSpec Common Model block"), // = 1
				//    array(40004, 1, "R", 3, "L", "uint16", "", "Registers Length of Common Model block"), // = 65
/*					array(40005, 16, "R", 3, "Mn", "String32", "", "Manufacturer z.B. Fronius"),
					array(40021, 16, "R", 3, "Md", "String32", "", "Device model z.B. IG+150V"),
					array(40037, 8, "R", 3, "Opt", "String16", "", "SW version of datamanager z.B. 3.3.6-13"),
					array(40045, 8, "R", 3, "Vr", "String16", "", "SW version of inverter"),
					array(40053, 16, "R", 3, "SN", "String32", "", "Serialnumber of inverter, string control or energy meter"),
*/				//  array(40069, 1, "R", 3, "DA", "uint16", "", "Modbus Device Address 1 - 247"), // = 1
				);


				$inverterModelRegister_array = array(
				/* ********** Inverter Model ************************************************************************
					Für die Wechselrichter-Daten werden zwei verschiedene SunSpec Models unterstützt:
						- das standardmäßig eingestellte Inverter Model mit Gleitkomma-Darstellung (Einstellung „float“; I111, I112 oder I113)
					HINWEIS! Die Registeranzahl der beiden Model-Typen ist unterschiedlich!
				   ************************************************************************************************** */
					array(40070, 1, "R", 3, "ID", "uint16", "", "Uniquely identifies this as a SunSpec Inverter Modbus Map (111: single phase, 112: split phase, 113: three phase)"),
					array(40071, 1, "R", 3, "L", "uint16", "", "Registers, Length of inverter model block"),
					array(40072, 2, "R", 3, "A", "float32", "A", "AC Total Current value"),
					array(40074, 2, "R", 3, "AphA", "float32", "A", "AC Phase-A Current value"),
					array(40076, 2, "R", 3, "AphB", "float32", "A", "AC Phase-B Current value"),
					array(40078, 2, "R", 3, "AphC", "float32", "A", "AC Phase-C Current value"),
					array(40080, 2, "R", 3, "PPVphAB", "float32", "V", "AC Voltage Phase-AB value"),
					array(40082, 2, "R", 3, "PPVphBC", "float32", "V", "AC Voltage Phase-BC value"),
					array(40084, 2, "R", 3, "PPVphCA", "float32", "V", "AC Voltage Phase-CA value"),
					array(40086, 2, "R", 3, "PhVphA", "float32", "V", "AC Voltage Phase-A-toneutral value"),
					array(40088, 2, "R", 3, "PhVphB", "float32", "V", "AC Voltage Phase-B-toneutral value"),
					array(40090, 2, "R", 3, "PhVphC", "float32", "V", "AC Voltage Phase-C-toneutral value"),
					array(40092, 2, "R", 3, "W", "float32", "W", "AC Power value"),
					array(40094, 2, "R", 3, "Hz", "float32", "Hz", "AC Frequency value"),
					array(40096, 2, "R", 3, "VA", "float32", "VA", "Apparent Power"),
					array(40098, 2, "R", 3, "VAr", "float32", "VAr", "Reactive Power"),
					array(40100, 2, "R", 3, "PF", "float32", "%", "Power Factor"),
					array(40102, 2, "R", 3, "WH", "float32", "Wh", "AC Lifetime Energy production"),
				//    array(40104, 2, "R", 3, "DCA", "float32", "A", "DC Current value (DC current only if one MPPT available; with multiple MPPT 'not implemented')"),
				//    array(40106, 2, "R", 3, "DCV", "float32", "V", "DC Voltage value (DC voltage only if one MPPT available; with multiple MPPT 'not implemented')"),
					array(40108, 2, "R", 3, "DCW", "float32", "W", "DC Power value"),
					array(40110, 2, "R", 3, "TmpCab", "float32", "° C", "Cabinet Temperature"),
					array(40112, 2, "R", 3, "TmpSnk", "float32", "° C", "Coolant or Heat Sink Temperature"),
					array(40114, 2, "R", 3, "TmpTrns", "float32", "° C", "Transformer Temperature"),
					array(40116, 2, "R", 3, "TmpOt", "float32", "° C", "Other Temperature"),
					array(40118, 1, "R", 3, "St", "enum16", "Enumerated", "Operating State (SunSpec State Codes)"),
					array(40119, 1, "R", 3, "StVnd", "enum16", "Enumerated", "Vendor Defined Operating State (Fronius State Codes)"),
					array(40120, 2, "R", 3, "Evt1", "uint32", "Bitfield", "Event Flags (bits 0-31)"),
					array(40122, 2, "R", 3, "Evt2", "uint32", "Bitfield", "Event Flags (bits 32-63)"),
					array(40124, 2, "R", 3, "EvtVnd1", "uint32", "Bitfield", "Vendor Defined Event Flags (bits 0-31)"),
					array(40126, 2, "R", 3, "EvtVnd2", "uint32", "Bitfield", "Vendor Defined Event Flags (bits 32-63)"),
					array(40128, 2, "R", 3, "EvtVnd3", "uint32", "Bitfield", "Vendor Defined Event Flags (bits 64-95)"),
					array(40130, 2, "R", 3, "EvtVnd4", "uint32", "Bitfield", "Vendor Defined Event Flags (bits 96-127)"),
				);

				$categoryId = $parentId;
/*				$categoryName = "Inverter";
				$categoryId = @IPS_GetCategoryIDByName($categoryName, $parentId);
				if(false === $categoryId)
				{
					$categoryId = IPS_CreateCategory();
					IPS_SetParent($categoryId, $parentId);
					IPS_SetName($categoryId, $categoryName);
				}
				IPS_SetInfo($categoryId, "Für die Wechselrichter-Daten werden zwei verschiedene SunSpec Models unterstützt:
						- das standardmäßig eingestellte Inverter Model mit Gleitkomma-Darstellung (Einstellung „float“; I111, I112 oder I113)
					HINWEIS! Die Registeranzahl der beiden Model-Typen ist unterschiedlich!");
*/
				$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);


				$categoryName = "Nameplate";
				$categoryId = @IPS_GetCategoryIDByName($categoryName, $parentId);
				if($readNameplate)
				{
					$inverterModelRegister_array = array(
					/* ********** Nameplate Model (IC120) ***************************************************************
						Dieses Modell entspricht einem Leistungsschild. Folgende Daten können ausgelesen werden:
							- DERType (3): Art des Geräts. Das Register liefert den Wert 4 zurück (PV-Gerät)
							- WRtg (4): Nennleistung des Wechselrichters
							- VARtg (6): Nenn-Scheinleistung des Wechselrichters
							- VArRtgQ1 (8) - VArRtgQ4 (11): Nenn-Blindleistungswerte für die vier Quadranten
							- ARtg (13): Nennstrom des Wechselrichters
							- PFRtgQ1 (15) – PFRtgQ4 (18): Minimale Werte für den Power Factor für die vier Quadranten
						Startadresse: - bei Einstellung „float“: 40131
					   ************************************************************************************************** */
					//    array(40132, 1, "R", 3, "ID", "uint16", "", "A well-known value 120. Uniquely identifies this as a SunSpec Nameplate Model"), // = 120
					//    array(40133, 1, "R", 3, "L", "uint16", "Registers", "Length of Nameplate Model"), // = 26
					//    array(40134, 1, "R", 3, "DERTyp", "enum16", "", "Type of DER device. Default value is 4 to indicate PV device."), // = 4
						array(40135, 1, "R", 3, "WRtg", "uint16", "W", "WRtg_SF Continuous power output capability of the inverter."),
						array(40136, 1, "R", 3, "WRtg_SF", "sunssf", "", "	Scale factor 1"),
						array(40137, 1, "R", 3, "VARtg", "uint16", "VA", "VARtg_SF Continuous Volt-Ampere capability of the inverter."),
						array(40138, 1, "R", 3, "VARtg_SF", "sunssf", "", "	Scale factor 1"),
						array(40139, 1, "R", 3, "VArRtgQ1", "int16", "var", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 1."),
						array(40140, 1, "R", 3, "VArRtgQ2", "int16", "var", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 2."),
						array(40141, 1, "R", 3, "VArRtgQ3", "int16", "var", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 3."),
						array(40142, 1, "R", 3, "VArRtgQ4", "int16", "var", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 4."),
						array(40143, 1, "R", 3, "VArRtg_SF", "sunssf", "", "Scale factor 1"),
						array(40144, 1, "R", 3, "ARtg", "uint16", "A", "ARtg_SF Maximum RMS AC current level capability of the inverter."),
						array(40145, 1, "R", 3, "ARtg_SF", "sunssf", "", "Scale factor -2"),
						array(40146, 1, "R", 3, "PFRtgQ1", "int16", "cos()", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 1."),
						array(40147, 1, "R", 3, "PFRtgQ2", "int16", "cos()", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 2."),
						array(40148, 1, "R", 3, "PFRtgQ3", "int16", "cos()", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 3."),
						array(40149, 1, "R", 3, "PFRtgQ4", "int16", "cos()", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 4."),
						array(40150, 1, "R", 3, "PFRtg_SF", "sunssf", "", "Scale factor -3"),
						array(40151, 1, "R", 3, "WHRtg", "uint16", "Wh", "WHRtg_SF Nominal energy rating of storage device."),
						array(40152, 1, "R", 3, "WHRtg_SF", "sunssf", "", "Scale factor 0*"),
						array(40153, 1, "R", 3, "AhrRtg", "uint16", "AH", "AhrRtg_SF The useable capacity of the battery. Maximum charge minus minimum charge from a technology capability perspective (Amp-hour capacity rating)."),
						array(40154, 1, "R", 3, "AhrRtg_SF", "sunssf", "", "Scale factor for amphour rating."),
						array(40155, 1, "R", 3, "MaxChaRte", "uint16", "W", "MaxChaRte_SF Maximum rate of energy transfer into the storage device."),
						array(40156, 1, "R", 3, "MaxChaRte_SF", "sunssf", "", "Scale factor 0*"),
						array(40157, 1, "R", 3, "MaxDisChaRte", "uint16", "W", "Max-DisChaRte_SF Maximum rate of energy transfer out of the storage device."),
						array(40158, 1, "R", 3, "MaxDisChaRte_SF", "sunssf", "", "Scale factor 0*"),
	//					array(40159, 1, "R", 3, "Pad", "", "", "	Pad register"),
					);

					if(false === $categoryId)
					{
						$categoryId = IPS_CreateCategory();
						IPS_SetParent($categoryId, $parentId);
						IPS_SetName($categoryId, $categoryName);
					}
					IPS_SetInfo($categoryId, "Dieses Modell entspricht einem Leistungsschild. Folgende Daten können ausgelesen werden:
							- DERType (3): Art des Geräts. Das Register liefert den Wert 4 zurück (PV-Gerät)
							- WRtg (4): Nennleistung des Wechselrichters
							- VARtg (6): Nenn-Scheinleistung des Wechselrichters
							- VArRtgQ1 (8) - VArRtgQ4 (11): Nenn-Blindleistungswerte für die vier Quadranten
							- ARtg (13): Nennstrom des Wechselrichters
							- PFRtgQ1 (15) – PFRtgQ4 (18): Minimale Werte für den Power Factor für die vier Quadranten");

					$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);
				}
				else
				{
					if(false !== $categoryId)
					{
						foreach(IPS_GetChildrenIDs($categoryId) AS $childId)
						{
							foreach(IPS_GetChildrenIDs($childId) AS $childChildId)
							{
								IPS_DeleteVariable($childChildId);
							}
							IPS_DeleteInstance($childId);
						}
						IPS_DeleteCategory($categoryId);
					}
				}
			}
		}

		private function createModbusInstances($inverterModelRegister_array, $parentId, $gatewayId, $pollCycle)
		{
			// Offset von Register (erster Wert 1) zu Adresse (erster Wert 0) ist -1
			$registerToAdressOffset = -1;

			// ArrayOffsets
			$IMR_StartRegister = 0;
			//$IMR_EndRegister = 1;
			$IMR_Size = 1;
			$IMR_RW = 2;
			$IMR_FunctionCode = 3;
			$IMR_Name = 4;
			$IMR_Type = 5;
			$IMR_Units = 6;
			$IMR_Description = 7;

			// Erstelle Modbus Instancen
			foreach($inverterModelRegister_array AS $inverterModelRegister)
			{
				if(DEBUG) echo "REG_".$inverterModelRegister[$IMR_StartRegister]. " - ".$inverterModelRegister[$IMR_Name]."\n";
				// Datentyp ermitteln
				// 0=Bit, 1=Byte, 2=Word, 3=DWord, 4=ShortInt, 5=SmallInt, 6=Integer, 7=Real
				if("uint16" == $inverterModelRegister[$IMR_Type]
					|| "enum16" == $inverterModelRegister[$IMR_Type])
				{
					$datenTyp = 2;
				}
				elseif("int16" == $inverterModelRegister[$IMR_Type]
					|| "sunssf" == $inverterModelRegister[$IMR_Type])
				{
					$datenTyp = 4;
				}
				elseif("uint32" == $inverterModelRegister[$IMR_Type])
				{
					$datenTyp = 6;
				}
				elseif("float32" == $inverterModelRegister[$IMR_Type])
				{
					$datenTyp = 7;
				}
				elseif("String32" == $inverterModelRegister[$IMR_Type] || "String16" == $inverterModelRegister[$IMR_Type])
				{
					echo "Datentyp ".$inverterModelRegister[$IMR_Type]." wird von Modbus nicht unterstützt! --> skip\n";
					continue;
				}
				else
				{
					echo "Fehler: Unbekannter Datentyp ".$inverterModelRegister[$IMR_Type]."! --> skip\n";
					continue;
				}

				// Profil ermitteln
				if("A" == $inverterModelRegister[$IMR_Units] && "uint16" == $inverterModelRegister[$IMR_Type])
				{
					$profile = "Fronius.Ampere.Int";
				}
				elseif("A" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "~Ampere";
				}
				elseif("AH" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "Fronius.AmpereHour.Int";
				}
				elseif("V" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "~Volt";
				}
				elseif("W" == $inverterModelRegister[$IMR_Units] && "uint16" == $inverterModelRegister[$IMR_Type])
				{
					$profile = "Fronius.Watt.Int";
				}
				elseif("W" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "~Watt.14490";
				}
				elseif("Hz" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "~Hertz";
				}
				// Voltampere für elektrische Scheinleistung
				elseif("VA" == $inverterModelRegister[$IMR_Units] && "float32" == $inverterModelRegister[$IMR_Type])
				{
					$profile = "Fronius.Scheinleistung.Float";
				}
				// Voltampere für elektrische Scheinleistung
				elseif("VA" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "Fronius.Scheinleistung";
				}
				// Var für elektrische Blindleistung
				elseif(("VAr" == $inverterModelRegister[$IMR_Units] || "var" == $inverterModelRegister[$IMR_Units]) && "float32" == $inverterModelRegister[$IMR_Type])
				{
					$profile = "Fronius.Blindleistung.Float";
				}
				// Var für elektrische Blindleistung
				elseif("VAr" == $inverterModelRegister[$IMR_Units] || "var" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "Fronius.Blindleistung";
				}
				elseif("%" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "~Valve.F";
				}
				elseif("Wh" == $inverterModelRegister[$IMR_Units] && "uint16" == $inverterModelRegister[$IMR_Type])
				{
					$profile = "Fronius.Electricity.Int";
				}
				elseif("Wh" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "~Electricity.HM";
				}
				elseif("° C" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "~Temperature";
				}
				elseif("cos()" == $inverterModelRegister[$IMR_Units])
				{
					$profile = "Fronius.Angle";
				}
				elseif("Enumerated" == $inverterModelRegister[$IMR_Units] && "St" == $inverterModelRegister[$IMR_Name])
				{
					$profile = "SunSpec.StateCodes";
				}
				elseif("Enumerated" == $inverterModelRegister[$IMR_Units] && "StVnd" == $inverterModelRegister[$IMR_Name])
				{
					$profile = "Fronius.StateCodes";
				}
				elseif("Bitfield" == $inverterModelRegister[$IMR_Units])
				{
					$profile = false;
				}
				else
				{
					$profile = false;
					if("" != $inverterModelRegister[$IMR_Units])
					{
						echo "Profil '".$inverterModelRegister[$IMR_Units]."' unbekannt.\n";
					}
				}


				$instanceId = @IPS_GetInstanceIDByName(/*"REG_".$inverterModelRegister[$IMR_StartRegister]. " - ".*/$inverterModelRegister[$IMR_Name], $parentId);
				if(false === $instanceId)
				{
					$instanceId = IPS_CreateInstance(modbusAddresses);
					IPS_SetParent($instanceId, $parentId);
					IPS_SetName($instanceId, /*"REG_".$inverterModelRegister[$IMR_StartRegister]. " - ".*/$inverterModelRegister[$IMR_Name]);

					// Gateway setzen
					IPS_DisconnectInstance($instanceId);
					IPS_ConnectInstance($instanceId, $gatewayId);
				}
				IPS_SetInfo($instanceId, $inverterModelRegister[$IMR_Description]);

				IPS_SetProperty($instanceId, "DataType",  $datenTyp);
				IPS_SetProperty($instanceId, "EmulateStatus", false);
				IPS_SetProperty($instanceId, "Poller", $pollCycle);
			//    IPS_SetProperty($instanceId, "Factor", 0);
				IPS_SetProperty($instanceId, "ReadAddress", $inverterModelRegister[$IMR_StartRegister] + $registerToAdressOffset);
				IPS_SetProperty($instanceId, "ReadFunctionCode", $inverterModelRegister[$IMR_FunctionCode]);
			//    IPS_SetProperty($instanceId, "WriteAddress", );
				IPS_SetProperty($instanceId, "WriteFunctionCode", 0);

				IPS_ApplyChanges($instanceId);

				IPS_Sleep(100);

				// Profil der Child-Variable zuweisen
				if(false != $profile)
				{
					$variableId = IPS_GetChildrenIDs($instanceId)[0];
					IPS_SetVariableCustomProfile($variableId, $profile);
				}
			}
		}
		
		private function checkProfiles()
		{
			// profileAssociation Offsets
			$PAO_name = 0;
			$PAO_value = 1;
			$PAO_description = 2;
			$PAO_color = 3;

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "SunSpec.StateCodes";
			if(!IPS_VariableProfileExists($profileName))
			{
				$profileAssociation_array = array(
					array("N/A", 0, "Unbekannter Status", "-1"),
					array("OFF", 1, "Wechselrichter ist aus", "-1"),
					array("SLEEPING", 2, "Auto-Shutdown", "-1"),
					array("STARTING", 3, "Wechselrichter startet", "-1"),
					array("MPPT", 4, "Wechselrichter arbeitet normal", 65280),
					array("THROTTLED", 5, "Leistungsreduktion aktiv", 16744448),
					array("SHUTTING_DOWN", 6, "Wechselrichter schaltet ab", "-1"),
					array("FAULT", 7, "Ein oder mehr Fehler existieren, siehe St *oder Evt * Register", 16711680),
					array("STANDBY", 8, "Standby", "-1"),
				);

				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);

				foreach($profileAssociation_array AS $profileAssociation)
				{
					IPS_SetVariableProfileAssociation($profileName, $profileAssociation[$PAO_value], $profileAssociation[$PAO_name], "", $profileAssociation[$PAO_color]);
				}

				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}


			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.StateCodes";
			if(!IPS_VariableProfileExists($profileName))
			{
				$profileAssociation_array = array(
					array("N/A", 0, "Unbekannter Status", "-1"),
					array("OFF", 1, "Wechselrichter ist aus", "-1"),
					array("SLEEPING", 2, "Auto-Shutdown", "-1"),
					array("STARTING", 3, "Wechselrichter startet", "-1"),
					array("MPPT", 4, "Wechselrichter arbeitet normal", 65280),
					array("THROTTLED", 5, "Leistungsreduktion aktiv", 16744448),
					array("SHUTTING_DOWN", 6, "Wechselrichter schaltet ab", "-1"),
					array("FAULT", 7, "Ein oder mehr Fehler existieren, siehe St * oder Evt * Register", 16711680),
					array("STANDBY", 8, "Standby", "-1"),
					array("NO_BUSINIT", 9, "Keine SolarNet Kommunikation", "-1"),
					array("NO_COMM_INV", 10, "Keine Kommunikation mit Wechselrichter möglich", "-1"),
					array("SN_OVERCURRENT", 11, "Überstrom an SolarNet Stecker erkannt", "-1"),
					array("BOOTLOAD", 12, "Wechselrichter wird gerade upgedatet", "-1"),
					array("AFCI", 13, "AFCI Event (Arc-Erkennung)", "-1"),
				);

				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);

				foreach($profileAssociation_array AS $profileAssociation)
				{
					IPS_SetVariableProfileAssociation($profileName, $profileAssociation[$PAO_value], $profileAssociation[$PAO_name], "", $profileAssociation[$PAO_color]);
				}

				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}


			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.Scheinleistung";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " VA");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}


			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.Scheinleistung.Float";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 2);
				IPS_SetVariableProfileText($profileName, "", " VA");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}


			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.Blindleistung";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " Var");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.Blindleistung.Float";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 2);
				IPS_SetVariableProfileText($profileName, "", " Var");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			
			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.Angle";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " °");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.Watt.Int";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " W");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.Ampere.Int";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " A");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.Electricity.Int";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " A");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "Fronius.AmpereHour.Int";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " Ah");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
		}
	}