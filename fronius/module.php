<?php

if (!defined('DEBUG'))
{
	define("DEBUG", false);
}

// ModBus RTU TCP
if (!defined('MODBUS_INSTANCES'))
{
	define("MODBUS_INSTANCES", "{A5F663AB-C400-4FE5-B207-4D67CC030564}");
}
if (!defined('CLIENT_SOCKETS'))
{
	define("CLIENT_SOCKETS", "{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}");
}
if (!defined('MODBUS_ADDRESSES'))
{
	define("MODBUS_ADDRESSES", "{CB197E50-273D-4535-8C91-BB35273E3CA5}");
}

// Modul Prefix
if (!defined('MODUL_PREFIX'))
{
	define("MODUL_PREFIX", "Fronius");
}

// Offset von Register (erster Wert 1) zu Adresse (erster Wert 0) ist -1
if (!defined('REGISTER_TO_ADDRESS_OFFSET'))
{
	define("REGISTER_TO_ADDRESS_OFFSET", -1);
}

// ArrayOffsets
if (!defined('IMR_START_REGISTER'))
{
	define("IMR_START_REGISTER", 0);
}
/*if (!defined('IMR_END_REGISTER'))
{
	define("IMR_END_REGISTER", 3);
}*/
if (!defined('IMR_SIZE'))
{
	define("IMR_SIZE", 1);
}
if (!defined('IMR_RW'))
{
	define("IMR_RW", 2);
}
if (!defined('IMR_FUNCTION_CODE'))
{
	define("IMR_FUNCTION_CODE", 3);
}
if (!defined('IMR_NAME'))
{
	define("IMR_NAME", 4);
}
if (!defined('IMR_TYPE'))
{
	define("IMR_TYPE", 5);
}
if (!defined('IMR_UNITS'))
{
	define("IMR_UNITS", 6);
}
if (!defined('IMR_DESCRIPTION'))
{
	define("IMR_DESCRIPTION", 7);
}

// profileAssociation Offsets
if (!defined('PAO_NAME'))
{
	define("PAO_NAME", 0);
}
if (!defined('PAO_VALUE'))
{
	define("PAO_VALUE", 1);
}
if (!defined('PAO_DESCRIPTION'))
{
	define("PAO_DESCRIPTION", 2);
}
if (!defined('PAO_COLOR'))
{
	define("PAO_COLOR", 3);
}



	class Fronius extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();


			// *** Properties ***
			$this->RegisterPropertyBoolean('active', 'true');
			$this->RegisterPropertyString('hostIp', '');
			$this->RegisterPropertyInteger('hostPort', '502');
			$this->RegisterPropertyBoolean('readNameplate', 'false');
			$this->RegisterPropertyInteger('pollCycle', '60000');


			// *** Erstelle deaktivierte Timer ***
			// Evt1
			$this->RegisterTimer("Update-Evt1", 0, "\$instanceId = IPS_GetInstanceIDByName(\"Evt1 - Event Flags\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Statusvariable\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"I_EVENT_GROUND_FAULT\", \"I_EVENT_DC_OVER_VOLT\", \"I_EVENT_AC_DISCONNECT\", \"I_EVENT_DC_DISCONNECT\", \"I_EVENT_GRID_DISCONNECT\", \"I_EVENT_CABINET_OPEN\", \"I_EVENT_MANUAL_SHUTDOWN\", \"I_EVENT_OVER_TEMP\", \"I_EVENT_OVER_FREQUENCY\", \"I_EVENT_UNDER_FREQUENCY\", \"I_EVENT_AC_OVER_VOLT\", \"I_EVENT_AC_UNDER_VOLT\", \"I_EVENT_BLOWN_STRING_FUSE\", \"I_EVENT_UNDER_TEMP\", \"I_EVENT_MEMORY_LOSS\", \"I_EVENT_HW_TEST_FAILURE\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetVariableIDByName(\$bitArray[\$i], \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}");


			// EvtVnd1
			$this->RegisterTimer("Update-EvtVnd1", 0, "\$instanceId = IPS_GetInstanceIDByName(\"EvtVnd1 - Vendor Event Flags\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Statusvariable\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"INSULATION_FAULT\", \"GRID_ERROR\", \"AC_OVERCURRENT\", \"DC_OVERCURRENT\", \"OVER_TEMP\", \"POWER_LOW\", \"DC_LOW\", \"INTERMEDIATE_FAULT\", \"FREQUENCY_HIGH\", \"FREQUENCY_LOW\", \"AC_VOLTAGE_HIGH\", \"AC_VOLTAGE_LOW\", \"DIRECT_CURRENT\", \"RELAY_FAULT\", \"POWER_STAGE_FAULT\", \"CONTROL_FAULT\", \"GC_GRID_VOLT_ERR\", \"GC_GRID_FREQU_ERR\", \"ENERGY_TRANSFER_FAULT\", \"REF_POWER_SOURCE_AC\", \"ANTI_ISLANDING_FAULT\", \"FIXED_VOLTAGE_FAULT\", \"MEMORY_FAULT\", \"DISPLAY_FAULT\", \"COMMUNICATION_FAULT\", \"TEMP_SENSORS_FAULT\", \"DC_AC_BOARD_FAULT\", \"ENS_FAULT\", \"FAN_FAULT\", \"DEFECTIVE_FUSE\", \"OUTPUT_CHOKE_FAULT\", \"CONVERTER_RELAY_FAULT\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetVariableIDByName(\$bitArray[\$i], \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}");


			// EvtVnd2
			$this->RegisterTimer("Update-EvtVnd2", 0, "\$instanceId = IPS_GetInstanceIDByName(\"EvtVnd2 - Vendor Event Flags\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Statusvariable\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"NO_SOLARNET_COMM\", \"INV_ADDRESS_FAULT\", \"NO_FEED_IN_24H\", \"PLUG_FAULT\", \"PHASE_ALLOC_FAULT\", \"GRID_CONDUCTOR_OPEN\", \"SOFTWARE_ISSUE\", \"POWER_DERATING\", \"JUMPER_INCORRECT\", \"INCOMPATIBLE_FEATURE\", \"VENTS_BLOCKED\", \"POWER_REDUCTION_ERROR\", \"ARC_DETECTED\", \"AFCI_SELF_TEST_FAILED\", \"CURRENT_SENSOR_ERROR\", \"DC_SWITCH_FAULT\", \"AFCI_DEFECTIVE\", \"AFCI_MANUAL_TEST_OK\", \"PS_PWR_SUPPLY_ISSUE\", \"AFCI_NO_COMM\", \"AFCI_MANUAL_TEST_FAILED\", \"AC_POLARITY_REVERSED\", \"FAULTY_AC_DEVICE\", \"FLASH_FAULT\", \"GENERAL_ERROR\", \"GROUNDING_ISSUE\", \"LIMITATION_FAULT\", \"OPEN_CONTACT\", \"OVERVOLTAGE_PROTECTION\", \"PROGRAM_STATUS\", \"SOLARNET_ISSUE\", \"SUPPLY_VOLTAGE_FAULT\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetVariableIDByName(\$bitArray[\$i], \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}");


			// EvtVnd3
			$this->RegisterTimer("Update-EvtVnd3", 0, "\$instanceId = IPS_GetInstanceIDByName(\"EvtVnd3 - Vendor Event Flags\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Statusvariable\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"TIME_FAULT\", \"USB_FAULT\", \"DC_HIGH\", \"INIT_ERROR\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetVariableIDByName(\$bitArray[\$i], \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}");

			// *** Erstelle Variablen-Profile ***
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
			$active = $this->ReadPropertyBoolean('active');
			$hostIp = $this->ReadPropertyString('hostIp');
			$hostPort = $this->ReadPropertyInteger('hostPort');
			$readNameplate = $this->ReadPropertyBoolean('readNameplate');
			$pollCycle = $this->ReadPropertyInteger('pollCycle');

			if("" != $hostIp)
			{
				$this->checkProfiles();
				
				// Splitter-Instance
				$gatewayId = 0;
				// I/O Instance
				$interfaceId = 0;

				foreach(IPS_GetInstanceListByModuleID(MODBUS_INSTANCES) AS $modbusInstanceId)
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
					$gatewayId = IPS_CreateInstance(MODBUS_INSTANCES); 
					IPS_SetName($gatewayId, MODUL_PREFIX."ModbusGateway");
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
					$interfaceId = IPS_CreateInstance(CLIENT_SOCKETS);
					IPS_SetName($interfaceId, MODUL_PREFIX."ClientSocket");
					IPS_SetProperty($interfaceId, "Host", $hostIp);
					IPS_SetProperty($interfaceId, "Port", $hostPort);
					IPS_SetProperty($interfaceId, "Open", true);
					IPS_ApplyChanges($interfaceId);
					IPS_Sleep(100);

					// Client Socket mit Gateway verbinden
					IPS_DisconnectInstance($gatewayId);
					IPS_ConnectInstance($gatewayId, $interfaceId);
				}


				$parentId = $this->InstanceID;



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
					array(40071, 1, "R", 3, "L - Registers", "uint16", "", "Registers, Length of inverter model block"),
					array(40072, 2, "R", 3, "A - AC Total Current", "float32", "A", "AC Total Current value"),
					array(40074, 2, "R", 3, "AphA - AC Phase-A Current", "float32", "A", "AC Phase-A Current value"),
					array(40076, 2, "R", 3, "AphB - AC Phase-B Current", "float32", "A", "AC Phase-B Current value"),
					array(40078, 2, "R", 3, "AphC - AC Phase-C Current", "float32", "A", "AC Phase-C Current value"),
					array(40080, 2, "R", 3, "PPVphAB - AC Voltage Phase-AB", "float32", "V", "AC Voltage Phase-AB value"),
					array(40082, 2, "R", 3, "PPVphBC - AC Voltage Phase-BC", "float32", "V", "AC Voltage Phase-BC value"),
					array(40084, 2, "R", 3, "PPVphCA - AC Voltage Phase-CA", "float32", "V", "AC Voltage Phase-CA value"),
					array(40086, 2, "R", 3, "PhVphA - AC Voltage Phase-A-toneutral", "float32", "V", "AC Voltage Phase-A-toneutral value"),
					array(40088, 2, "R", 3, "PhVphB - AC Voltage Phase-B-toneutral", "float32", "V", "AC Voltage Phase-B-toneutral value"),
					array(40090, 2, "R", 3, "PhVphC - AC Voltage Phase-C-toneutral", "float32", "V", "AC Voltage Phase-C-toneutral value"),
					array(40092, 2, "R", 3, "W - AC Power", "float32", "W", "AC Power value"),
					array(40094, 2, "R", 3, "Hz - AC Frequency", "float32", "Hz", "AC Frequency value"),
					array(40096, 2, "R", 3, "VA - Apparent Power", "float32", "VA", "Apparent Power"),
					array(40098, 2, "R", 3, "VAr - Reactive Power", "float32", "VAr", "Reactive Power"),
					array(40100, 2, "R", 3, "PF - Power Factor", "float32", "%", "Power Factor"),
					array(40102, 2, "R", 3, "WH - AC Lifetime Energy production", "float32", "Wh", "AC Lifetime Energy production"),
//					array(40104, 2, "R", 3, "DCA", "float32", "A", "DC Current value (DC current only if one MPPT available; with multiple MPPT 'not implemented')"),
//					array(40106, 2, "R", 3, "DCV", "float32", "V", "DC Voltage value (DC voltage only if one MPPT available; with multiple MPPT 'not implemented')"),
					array(40108, 2, "R", 3, "DCW - DC Power", "float32", "W", "DC Power value"),
//					array(40110, 2, "R", 3, "TmpCab", "float32", "° C", "Cabinet Temperature"), // Not supported
//					array(40112, 2, "R", 3, "TmpSnk", "float32", "° C", "Coolant or Heat Sink Temperature"), // Not supported
//					array(40114, 2, "R", 3, "TmpTrns", "float32", "° C", "Transformer Temperature"), // Not supported
//					array(40116, 2, "R", 3, "TmpOt", "float32", "° C", "Other Temperature"), // Not supported
					array(40118, 1, "R", 3, "St - Operating State", "enum16", "Enumerated_St", "Operating State (SunSpec State Codes)"),
					array(40119, 1, "R", 3, "StVnd - Vendor Operating State", "enum16", "Enumerated_StVnd", "Vendor Defined Operating State (Fronius State Codes)"),
					array(40120, 2, "R", 3, "Evt1 - Event Flags", "uint32", "Bitfield", "Event Flags (bits 0-31)"),
					array(40122, 2, "R", 3, "Evt2 - Event Flags", "uint32", "Bitfield", "Event Flags (bits 32-63)"),
					array(40124, 2, "R", 3, "EvtVnd1 - Vendor Event Flags", "uint32", "Bitfield", "Vendor Defined Event Flags (bits 0-31)"),
					array(40126, 2, "R", 3, "EvtVnd2 - Vendor Event Flags", "uint32", "Bitfield", "Vendor Defined Event Flags (bits 32-63)"),
					array(40128, 2, "R", 3, "EvtVnd3 - Vendor Event Flags", "uint32", "Bitfield", "Vendor Defined Event Flags (bits 64-95)"),
					array(40130, 2, "R", 3, "EvtVnd4 - Vendor Event Flags", "uint32", "Bitfield", "Vendor Defined Event Flags (bits 96-127)"),
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


				// Bit 0 - 15 für "Evt1 - Event Flags" erstellen
				$bitArray = array(
					array('varName' => "I_EVENT_GROUND_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Ground fault - StateCodes: 471;472;474;475;494;502"),
					array('varName' => "I_EVENT_DC_OVER_VOLT", 'varProfile' => "~Alert", 'varInfo' => "DC over voltage - StateCodes: 309;313"),
					array('varName' => "I_EVENT_AC_DISCONNECT", 'varProfile' => "~Alert", 'varInfo' => "AC disconnect open - StateCodes: 107;117;127;137"),
					array('varName' => "I_EVENT_DC_DISCONNECT", 'varProfile' => "~Alert", 'varInfo' => "DC disconnect open - StateCodes: "),
					array('varName' => "I_EVENT_GRID_DISCONNECT", 'varProfile' => "~Alert", 'varInfo' => "Grid shutdown - StateCodes: "),
					array('varName' => "I_EVENT_CABINET_OPEN", 'varProfile' => "~Alert", 'varInfo' => "Cabinet open - StateCodes: "),
					array('varName' => "I_EVENT_MANUAL_SHUTDOWN", 'varProfile' => "~Alert", 'varInfo' => "Manual shutdown - StateCodes: "),
					array('varName' => "I_EVENT_OVER_TEMP", 'varProfile' => "~Alert", 'varInfo' => "Over temperature - StateCodes: 303;304;531"),
					array('varName' => "I_EVENT_OVER_FREQUENCY", 'varProfile' => "~Alert", 'varInfo' => "Frequency above limit - StateCodes: 104;105;115;125;135;203;204"),
					array('varName' => "I_EVENT_UNDER_FREQUENCY", 'varProfile' => "~Alert", 'varInfo' => "Frequency under limit - StateCodes: 104;106;116;126;136;203;204"),
					array('varName' => "I_EVENT_AC_OVER_VOLT", 'varProfile' => "~Alert", 'varInfo' => "AC voltage above limit - StateCodes: 101;102;112;122;132;201;202"),
					array('varName' => "I_EVENT_AC_UNDER_VOLT", 'varProfile' => "~Alert", 'varInfo' => "AC voltage under limit - StateCodes: 101;103;113;123;133;201;202"),
					array('varName' => "I_EVENT_BLOWN_STRING_FUSE", 'varProfile' => "~Alert", 'varInfo' => "Blown string fuse - StateCodes: 550;551"),
					array('varName' => "I_EVENT_UNDER_TEMP", 'varProfile' => "~Alert", 'varInfo' => "Under temperature - StateCodes: "),
					array('varName' => "I_EVENT_MEMORY_LOSS", 'varProfile' => "~Alert", 'varInfo' => "Generic Memory or Communication error (internal) - StateCodes: 401;402;403;404;405;414;416;417;419;421;425;431;451;452;453;454;460;461;464;465;466;467;476;477;490;491;504;505;506;507;508;510;511;514;516;517;519;541;553;558;711;712;713;714;715;716;721;722;723;724;725;726;727;728;729;730;799"),
					array('varName' => "I_EVENT_HW_TEST_FAILURE", 'varProfile' => "~Alert", 'varInfo' => "Hardware test failure - StateCodes: 245;406;407;457;469;478;515;532;533;535;555"),
				);

				$instanceId = IPS_GetInstanceIDByName("Evt1 - Event Flags", $categoryId);
				$varId = IPS_GetObjectIDByIdent("Statusvariable", $instanceId);
				IPS_SetHidden($varId, true);
				
				foreach($bitArray AS $bit)
				{
					$varName = $bit['varName'];
					$varId = @IPS_GetVariableIDByName($varName, $instanceId);
					if(false === $varId)
					{
						$varId = IPS_CreateVariable(0);
						IPS_SetName($varId, $varName);
						IPS_SetParent($varId, $instanceId);
					}
					IPS_SetVariableCustomProfile($varId, $bit['varProfile']);
					IPS_SetInfo($varId, $bit['varInfo']);
				}

				// Bit 0 - 15 für "EvtVnd1 - Vendor Event Flags" erstellen
				$bitArray = array(
					array('varName' => "INSULATION_FAULT", 'varProfile' => "~Alert", 'varInfo' => "DC Insulation fault - StateCodes: 447;459;474;475;502"),
					array('varName' => "GRID_ERROR", 'varProfile' => "~Alert", 'varInfo' => "Grid error - StateCodes: 101;104;107;108;109;117;127;137;205;206;305"),
					array('varName' => "AC_OVERCURRENT", 'varProfile' => "~Alert", 'varInfo' => "Overcurrent AC - StateCodes: 301;321"),
					array('varName' => "DC_OVERCURRENT", 'varProfile' => "~Alert", 'varInfo' => "Overcurrent DC - StateCodes: 302"),
					array('varName' => "OVER_TEMP", 'varProfile' => "~Alert", 'varInfo' => "Over-temperature - StateCodes: 303;304;322"),
					array('varName' => "POWER_LOW", 'varProfile' => "~Alert", 'varInfo' => "Power low - StateCodes: 306"),
					array('varName' => "DC_LOW", 'varProfile' => "~Alert", 'varInfo' => "DC low - StateCodes: 307;310;522;523"),
					array('varName' => "INTERMEDIATE_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Intermediate circuit error - StateCodes: 308;426"),
					array('varName' => "FREQUENCY_HIGH", 'varProfile' => "~Alert", 'varInfo' => "AC frequency too high - StateCodes: 105;115;125;135;203"),
					array('varName' => "FREQUENCY_LOW", 'varProfile' => "~Alert", 'varInfo' => "AC frequency too low - StateCodes: 106;116;126;136;204"),
					array('varName' => "AC_VOLTAGE_HIGH", 'varProfile' => "~Alert", 'varInfo' => "AC voltage too high - StateCodes: 102;112;122;132;201"),
					array('varName' => "AC_VOLTAGE_LOW", 'varProfile' => "~Alert", 'varInfo' => "AC voltage too low - StateCodes: 103;113;123;133;202"),
					array('varName' => "DIRECT_CURRENT", 'varProfile' => "~Alert", 'varInfo' => "Direct current feed in - StateCodes: 408"),
					array('varName' => "RELAY_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Relay problem - StateCodes: 207;208;457"),
					array('varName' => "POWER_STAGE_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Internal power stage error - StateCodes: 417;419;421;427;428;429;431;432;433;436;437;438;439;442;445;450;462;512;513;514;516;553"),
					array('varName' => "CONTROL_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Control problems - StateCodes: 409;413"),
					array('varName' => "GC_GRID_VOLT_ERR", 'varProfile' => "~Alert", 'varInfo' => "Guard Controller - AC voltage error - StateCodes: 453"),
					array('varName' => "GC_GRID_FREQU_ERR", 'varProfile' => "~Alert", 'varInfo' => "Guard Controller - AC Frequency Error - StateCodes: 454"),
					array('varName' => "ENERGY_TRANSFER_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Energy transfer not possible - StateCodes: 443"),
					array('varName' => "REF_POWER_SOURCE_AC", 'varProfile' => "~Alert", 'varInfo' => "Reference power source AC outside tolerances - StateCodes: 455"),
					array('varName' => "ANTI_ISLANDING_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Error during anti islanding test - StateCodes: 456"),
					array('varName' => "FIXED_VOLTAGE_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Fixed voltage lower than current MPP voltage - StateCodes: 412"),
					array('varName' => "MEMORY_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Memory fault - StateCodes: 403;414;451;505;506;507;510;511;711;712;713;714;715;716;721;722;723;724;725;726;727;728;729;730"),
					array('varName' => "DISPLAY_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Display - StateCodes: 464;465;466;467"),
					array('varName' => "COMMUNICATION_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Internal communication error - StateCodes: 401;402;416;425;452;490;491;519;799"),
					array('varName' => "TEMP_SENSORS_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Temperature sensors defective - StateCodes: 406;407;487;532;533"),
					array('varName' => "DC_AC_BOARD_FAULT", 'varProfile' => "~Alert", 'varInfo' => "DC or AC board fault - StateCodes: 460;461;518"),
					array('varName' => "ENS_FAULT", 'varProfile' => "~Alert", 'varInfo' => "ENS error - StateCodes: 248;404;405;415"),
					array('varName' => "FAN_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Fan error - StateCodes: 530;531;534;535;536;537;540;541;555;557"),
					array('varName' => "DEFECTIVE_FUSE", 'varProfile' => "~Alert", 'varInfo' => "Defective fuse - StateCodes: 471;472;551"),
					array('varName' => "OUTPUT_CHOKE_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Output choke connected to wrong poles - StateCodes: 469"),
					array('varName' => "CONVERTER_RELAY_FAULT", 'varProfile' => "~Alert", 'varInfo' => "The buck converter relay does not open at high DC voltage - StateCodes: 470"),
				);

				$instanceId = IPS_GetInstanceIDByName("EvtVnd1 - Vendor Event Flags", $categoryId);
				$varId = IPS_GetObjectIDByIdent("Statusvariable", $instanceId);
				IPS_SetHidden($varId, true);
				
				foreach($bitArray AS $bit)
				{
					$varName = $bit['varName'];
					$varId = @IPS_GetVariableIDByName($varName, $instanceId);
					if(false === $varId)
					{
						$varId = IPS_CreateVariable(0);
						IPS_SetName($varId, $varName);
						IPS_SetParent($varId, $instanceId);
					}
					IPS_SetVariableCustomProfile($varId, $bit['varProfile']);
					IPS_SetInfo($varId, $bit['varInfo']);
				}


				// Bit 0 - 15 für "EvtVnd2 - Vendor Event Flags" erstellen
				$bitArray = array(
					array('varName' => "NO_SOLARNET_COMM", 'varProfile' => "~Alert", 'varInfo' => "No SolarNet communication - StateCodes: 504"),
					array('varName' => "INV_ADDRESS_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Inverter address incorrect - StateCodes: 508"),
					array('varName' => "NO_FEED_IN_24H", 'varProfile' => "~Alert", 'varInfo' => "24h no feed in - StateCodes: 509"),
					array('varName' => "PLUG_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Faulty plug connections - StateCodes: 410;515"),
					array('varName' => "PHASE_ALLOC_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Incorrect phase allocation - StateCodes: 473"),
					array('varName' => "GRID_CONDUCTOR_OPEN", 'varProfile' => "~Alert", 'varInfo' => "Grid conductor open or supply phase has failed - StateCodes: 210"),
					array('varName' => "SOFTWARE_ISSUE", 'varProfile' => "~Alert", 'varInfo' => "Incompatible or old software - StateCodes: 558"),
					array('varName' => "POWER_DERATING", 'varProfile' => "~Alert", 'varInfo' => "Power Derating Due To Overtemperature - StateCodes: 517"),
					array('varName' => "JUMPER_INCORRECT", 'varProfile' => "~Alert", 'varInfo' => "Jumper set incorrectly - StateCodes: 550"),
					array('varName' => "INCOMPATIBLE_FEATURE", 'varProfile' => "~Alert", 'varInfo' => "Incompatible feature - StateCodes: 559"),
					array('varName' => "VENTS_BLOCKED", 'varProfile' => "~Alert", 'varInfo' => "Defective ventilator/air vents blocked - StateCodes: 501"),
					array('varName' => "POWER_REDUCTION_ERROR", 'varProfile' => "~Alert", 'varInfo' => "Power reduction on error - StateCodes: 560;561"),
					array('varName' => "ARC_DETECTED", 'varProfile' => "~Alert", 'varInfo' => "Arc Detected - StateCodes: 240"),
					array('varName' => "AFCI_SELF_TEST_FAILED", 'varProfile' => "~Alert", 'varInfo' => "AFCI Self Test Failed - StateCodes: 245"),
					array('varName' => "CURRENT_SENSOR_ERROR", 'varProfile' => "~Alert", 'varInfo' => "Current Sensor Error - StateCodes: 247"),
					array('varName' => "DC_SWITCH_FAULT", 'varProfile' => "~Alert", 'varInfo' => "DC switch fault - StateCodes: 492;493"),
					array('varName' => "AFCI_DEFECTIVE", 'varProfile' => "~Alert", 'varInfo' => "AFCI Defective - StateCodes: 249"),
					array('varName' => "AFCI_MANUAL_TEST_OK", 'varProfile' => "~Alert", 'varInfo' => "AFCI Manual Test Successful - StateCodes: 250"),
					array('varName' => "PS_PWR_SUPPLY_ISSUE", 'varProfile' => "~Alert", 'varInfo' => "Power Stack Supply Missing - StateCodes: 476"),
					array('varName' => "AFCI_NO_COMM", 'varProfile' => "~Alert", 'varInfo' => "AFCI Communication Stopped - StateCodes: 477"),
					array('varName' => "AFCI_MANUAL_TEST_FAILED", 'varProfile' => "~Alert", 'varInfo' => "AFCI Manual Test Failed - StateCodes: 478"),
					array('varName' => "AC_POLARITY_REVERSED", 'varProfile' => "~Alert", 'varInfo' => "AC polarity reversed - StateCodes: 463"),
					array('varName' => "FAULTY_AC_DEVICE", 'varProfile' => "~Alert", 'varInfo' => "AC measurement device fault - StateCodes: 488"),
					array('varName' => "FLASH_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Flash fault - StateCodes: 781;782;783;784;785;786;787;788;789;790;791;792;793;794"),
					array('varName' => "GENERAL_ERROR", 'varProfile' => "~Alert", 'varInfo' => "General error - StateCodes: 772;773;775;776"),
					array('varName' => "GROUNDING_ISSUE", 'varProfile' => "~Alert", 'varInfo' => "Grounding fault - StateCodes: 494"),
					array('varName' => "LIMITATION_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Power limitation fault - StateCodes: 761;762;763;764;765;766;767;768"),
					array('varName' => "OPEN_CONTACT", 'varProfile' => "~Alert", 'varInfo' => "External NO contact open - StateCodes: 486"),
					array('varName' => "OVERVOLTAGE_PROTECTION", 'varProfile' => "~Alert", 'varInfo' => "External overvoltage protection has tripped - StateCodes: 597;598;599"),
					array('varName' => "PROGRAM_STATUS", 'varProfile' => "~Alert", 'varInfo' => "Internal processor program status - StateCodes: 707;708;709;710;1000-1299"),
					array('varName' => "SOLARNET_ISSUE", 'varProfile' => "~Alert", 'varInfo' => "SolarNet issue - StateCodes: 701;702;703;704;705;706"),
					array('varName' => "SUPPLY_VOLTAGE_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Supply voltage fault - StateCodes: 495;496;497;498;499"),
				);

				$instanceId = IPS_GetInstanceIDByName("EvtVnd2 - Vendor Event Flags", $categoryId);
				$varId = IPS_GetObjectIDByIdent("Statusvariable", $instanceId);
				IPS_SetHidden($varId, true);
				
				foreach($bitArray AS $bit)
				{
					$varName = $bit['varName'];
					$varId = @IPS_GetVariableIDByName($varName, $instanceId);
					if(false === $varId)
					{
						$varId = IPS_CreateVariable(0);
						IPS_SetName($varId, $varName);
						IPS_SetParent($varId, $instanceId);
					}
					IPS_SetVariableCustomProfile($varId, $bit['varProfile']);
					IPS_SetInfo($varId, $bit['varInfo']);
				}


				// Bit 0 - 15 für "EvtVnd3 - Vendor Event Flags" erstellen
				$bitArray = array(
					array('varName' => "TIME_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Time error - StateCodes: 751;752;753;754;755;756;757;758;760"),
					array('varName' => "USB_FAULT", 'varProfile' => "~Alert", 'varInfo' => "USB error - StateCodes: 731;732;733;734;735;736;737;738;739;740;741;743;744;745;746;747;748;749;750"),
					array('varName' => "DC_HIGH", 'varProfile' => "~Alert", 'varInfo' => "DC high - StateCodes: 309;313"),
					array('varName' => "INIT_ERROR", 'varProfile' => "~Alert", 'varInfo' => "Init error - StateCodes: 482"),
				);

				$instanceId = IPS_GetInstanceIDByName("EvtVnd3 - Vendor Event Flags", $categoryId);
				$varId = IPS_GetObjectIDByIdent("Statusvariable", $instanceId);
				IPS_SetHidden($varId, true);
				
				foreach($bitArray AS $bit)
				{
					$varName = $bit['varName'];
					$varId = @IPS_GetVariableIDByName($varName, $instanceId);
					if(false === $varId)
					{
						$varId = IPS_CreateVariable(0);
						IPS_SetName($varId, $varName);
						IPS_SetParent($varId, $instanceId);
					}
					IPS_SetVariableCustomProfile($varId, $bit['varProfile']);
					IPS_SetInfo($varId, $bit['varInfo']);
				}



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


				if($active)
				{
					// Timer aktivieren
					$this->SetTimerInterval("Update-Evt1", 5000);
					$this->SetTimerInterval("Update-EvtVnd1", 5000);
					$this->SetTimerInterval("Update-EvtVnd2", 5000);
					$this->SetTimerInterval("Update-EvtVnd3", 5000);

					// Erreichbarkeit von IP und Port prüfen
					$portOpen = false;
					$waitTimeoutInSeconds = 1; 
					if($fp = @fsockopen($hostIp, $hostPort, $errCode, $errStr, $waitTimeoutInSeconds))
					{   
						// It worked
						$portOpen = true;
						fclose($fp);

						// Client Soket aktivieren
						IPS_SetProperty($interfaceId, "Open", true);
						IPS_ApplyChanges($interfaceId);
						IPS_Sleep(100);

						// aktiv
						$this->SetStatus(102);
					}
					else
					{
						// IP oder Port nicht erreichbar
						$this->SetStatus(200);
					}
				}
				else
				{
					// Client Soket deaktivieren
					IPS_SetProperty($interfaceId, "Open", false);
					IPS_ApplyChanges($interfaceId);
					IPS_Sleep(100);

					// inaktiv
					$this->SetStatus(104);
				}
			}
			else
			{
				// keine IP --> inaktiv
				$this->SetStatus(104);
			}
		}

		private function createModbusInstances($inverterModelRegister_array, $parentId, $gatewayId, $pollCycle)
		{
			// Erstelle Modbus Instancen
			foreach($inverterModelRegister_array AS $inverterModelRegister)
			{
				if(DEBUG) echo "REG_".$inverterModelRegister[IMR_START_REGISTER]. " - ".$inverterModelRegister[IMR_NAME]."\n";
				// Datentyp ermitteln
				// 0=Bit, 1=Byte, 2=Word, 3=DWord, 4=ShortInt, 5=SmallInt, 6=Integer, 7=Real
				if("uint16" == strtolower($inverterModelRegister[IMR_TYPE])
					|| "enum16" == strtolower($inverterModelRegister[IMR_TYPE])
					|| "uint8+uint8" == strtolower($inverterModelRegister[IMR_TYPE]))
				{
					$datenTyp = 2;
				}
				elseif("uint32" == strtolower($inverterModelRegister[IMR_TYPE]))
				{
					$datenTyp = 3;
				}
				elseif("int16" == strtolower($inverterModelRegister[IMR_TYPE])
					|| "sunssf" == strtolower($inverterModelRegister[IMR_TYPE]))
				{
					$datenTyp = 4;
				}
				elseif("int32" == strtolower($inverterModelRegister[IMR_TYPE]))
				{
					$datenTyp = 6;
				}
				elseif("float32" == strtolower($inverterModelRegister[IMR_TYPE]))
				{
					$datenTyp = 7;
				}
				elseif("string32" == strtolower($inverterModelRegister[IMR_TYPE])
					|| "string16" == strtolower($inverterModelRegister[IMR_TYPE])
					|| "string" == strtolower($inverterModelRegister[IMR_TYPE]))
				{
					echo "Datentyp '".$inverterModelRegister[IMR_TYPE]."' wird von Modbus in IPS nicht unterstützt! --> skip\n";
					continue;
				}
				else
				{
					echo "Fehler: Unbekannter Datentyp '".$inverterModelRegister[IMR_TYPE]."'! --> skip\n";
					continue;
				}

				// Profil ermitteln
				if("a" == strtolower($inverterModelRegister[IMR_UNITS]) && 7 == $datenTyp)
				{
					$profile = "~Ampere";
				}
				elseif("a" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = MODUL_PREFIX.".Ampere.Int";
				}
				elseif("ah" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = MODUL_PREFIX.".AmpereHour.Int";
				}
				elseif("v" == strtolower($inverterModelRegister[IMR_UNITS]) && 7 == $datenTyp)
				{
					$profile = "~Volt";
				}
				elseif("w" == strtolower($inverterModelRegister[IMR_UNITS]) && 7 == $datenTyp)
				{
					$profile = "~Watt.14490";
				}
				elseif("w" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = MODUL_PREFIX.".Watt.Int";
				}
				elseif("hz" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = "~Hertz";
				}
				// Voltampere für elektrische Scheinleistung
				elseif("va" == strtolower($inverterModelRegister[IMR_UNITS]) && 7 == $datenTyp)
				{
					$profile = MODUL_PREFIX.".Scheinleistung.Float";
				}
				// Voltampere für elektrische Scheinleistung
				elseif("va" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = MODUL_PREFIX.".Scheinleistung";
				}
				// Var für elektrische Blindleistung
				elseif("var" == strtolower($inverterModelRegister[IMR_UNITS]) && 7 == $datenTyp)
				{
					$profile = MODUL_PREFIX.".Blindleistung.Float";
				}
				// Var für elektrische Blindleistung
				elseif("var" == strtolower($inverterModelRegister[IMR_UNITS]) || "var" == $inverterModelRegister[IMR_UNITS])
				{
					$profile = MODUL_PREFIX.".Blindleistung";
				}
				elseif("%" == $inverterModelRegister[IMR_UNITS] && 7 == $datenTyp)
				{
					$profile = "~Valve.F";
				}
				elseif("%" == $inverterModelRegister[IMR_UNITS])
				{
					$profile = "~Valve";
				}
				elseif("wh" == strtolower($inverterModelRegister[IMR_UNITS]) && 7 == $datenTyp)
				{
					$profile = MODUL_PREFIX.".Electricity.Float";
				}
				elseif("wh" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = MODUL_PREFIX.".Electricity.Int";
				}
				elseif("° C" == $inverterModelRegister[IMR_UNITS])
				{
					$profile = "~Temperature";
				}
				elseif("cos()" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = MODUL_PREFIX.".Angle";
				}
				elseif("enumerated_st" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = "SunSpec.StateCodes";
				}
				elseif("enumerated_stvnd" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = MODUL_PREFIX.".StateCodes";
				}
				elseif("bitfield" == strtolower($inverterModelRegister[IMR_UNITS]))
				{
					$profile = false;
				}
				else
				{
					$profile = false;
					if("" != $inverterModelRegister[IMR_UNITS])
					{
						echo "Profil '".$inverterModelRegister[IMR_UNITS]."' unbekannt.\n";
					}
				}


				$instanceId = @IPS_GetInstanceIDByName(/*"REG_".$inverterModelRegister[IMR_START_REGISTER]. " - ".*/$inverterModelRegister[IMR_NAME], $parentId);
				if(false === $instanceId)
				{
					$instanceId = IPS_CreateInstance(MODBUS_ADDRESSES);
					IPS_SetParent($instanceId, $parentId);
					IPS_SetName($instanceId, /*"REG_".$inverterModelRegister[IMR_START_REGISTER]. " - ".*/$inverterModelRegister[IMR_NAME]);

					// Gateway setzen
					IPS_DisconnectInstance($instanceId);
					IPS_ConnectInstance($instanceId, $gatewayId);
				}
				IPS_SetInfo($instanceId, $inverterModelRegister[IMR_DESCRIPTION]);

				IPS_SetProperty($instanceId, "DataType",  $datenTyp);
				IPS_SetProperty($instanceId, "EmulateStatus", false);
				IPS_SetProperty($instanceId, "Poller", $pollCycle);
			//    IPS_SetProperty($instanceId, "Factor", 0);
				IPS_SetProperty($instanceId, "ReadAddress", $inverterModelRegister[IMR_START_REGISTER] + REGISTER_TO_ADDRESS_OFFSET);
				IPS_SetProperty($instanceId, "ReadFunctionCode", $inverterModelRegister[IMR_FUNCTION_CODE]);
			//    IPS_SetProperty($instanceId, "WriteAddress", );
				IPS_SetProperty($instanceId, "WriteFunctionCode", 0);

				IPS_ApplyChanges($instanceId);

				IPS_Sleep(100);


				$variableId = IPS_GetChildrenIDs($instanceId)[0];

				// Ident der Statusvariable setzen
				IPS_SetIdent($variableId, "Statusvariable");

				// Profil der Statusvariable zuweisen
				if(false != $profile)
				{
					IPS_SetVariableCustomProfile($variableId, $profile);
				}
			}
		}
		
		private function checkProfiles()
		{
			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = "SunSpec.StateCodes";
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

			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);

				foreach($profileAssociation_array AS $profileAssociation)
				{
					IPS_SetVariableProfileAssociation($profileName, $profileAssociation[PAO_VALUE], $profileAssociation[PAO_NAME], "", $profileAssociation[PAO_COLOR]);
				}

				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				foreach($profileAssociation_array AS $profileAssociation)
				{
					IPS_SetVariableProfileAssociation($profileName, $profileAssociation[PAO_VALUE], $profileAssociation[PAO_NAME], "", $profileAssociation[PAO_COLOR]);
				}
			}


			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".StateCodes";
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
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);

				foreach($profileAssociation_array AS $profileAssociation)
				{
					IPS_SetVariableProfileAssociation($profileName, $profileAssociation[PAO_VALUE], $profileAssociation[PAO_NAME], "", $profileAssociation[PAO_COLOR]);
				}

				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				foreach($profileAssociation_array AS $profileAssociation)
				{
					IPS_SetVariableProfileAssociation($profileName, $profileAssociation[PAO_VALUE], $profileAssociation[PAO_NAME], "", $profileAssociation[PAO_COLOR]);
				}
			}



			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Scheinleistung";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " VA");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " VA");
			}


			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Scheinleistung.Float";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 2);
				IPS_SetVariableProfileText($profileName, "", " VA");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " VA");
			}


			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Blindleistung";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " Var");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " Var");
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Blindleistung.Float";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 2);
				IPS_SetVariableProfileText($profileName, "", " Var");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " Var");
			}
			
			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Angle";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " °");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " °");
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Watt.Int";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " W");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " W");
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Ampere.Int";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " A");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " A");
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Electricity.Float";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 2);
				IPS_SetVariableProfileText($profileName, "", " Wh");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " Wh");
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".Electricity.Int";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " Wh");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " Wh");
			}

			// Erstelle Profil, sofern noch nicht vorhanden
			$profileName = MODUL_PREFIX.".AmpereHour.Int";
			if(!IPS_VariableProfileExists($profileName))
			{
				// 	Wert: 0 Boolean, 1 Integer, 2 Float, 3 String
				IPS_CreateVariableProfile($profileName, 1);
				IPS_SetVariableProfileText($profileName, "", " Ah");
				
				if(DEBUG) echo "Profil ".$profileName." erstellt\n";
			}
			else
			{
				IPS_SetVariableProfileText($profileName, "", " Ah");
			}
		}
	}