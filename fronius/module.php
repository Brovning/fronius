<?php

require_once __DIR__ . '/../libs/myFunctions.php';  // globale Funktionen

if (!defined('DEBUG'))
{
	define("DEBUG", false);
}

// Modul Prefix
if (!defined('MODUL_PREFIX'))
{
	define("MODUL_PREFIX", "Fronius");
}

// ArrayOffsets
if (!defined('IMR_START_REGISTER'))
{
	define("IMR_START_REGISTER", 0);
//	define("IMR_END_REGISTER", 3);
	define("IMR_SIZE", 1);
	define("IMR_RW", 2);
	define("IMR_FUNCTION_CODE", 3);
	define("IMR_NAME", 4);
	define("IMR_DESCRIPTION", 5);
	define("IMR_TYPE", 6);
	define("IMR_UNITS", 7);
}

	class Fronius extends IPSModule
	{
		use myFunctions;

		public function Create()
		{
			//Never delete this line!
			parent::Create();


			// *** Properties ***
			$this->RegisterPropertyBoolean('active', 'true');
			$this->RegisterPropertyString('hostIp', '');
			$this->RegisterPropertyInteger('hostPort', '502');
			$this->RegisterPropertyInteger('hostmodbusDevice', '1');
			$this->RegisterPropertyBoolean('readIC120', 'false');
            $this->RegisterPropertyBoolean('readIC121', 'false');
            $this->RegisterPropertyBoolean('readIC122', 'false');
            $this->RegisterPropertyBoolean('readIC123', 'false');
            $this->RegisterPropertyBoolean('readIC124', 'false');
			$this->RegisterPropertyBoolean('readI160', 'false');
			$this->RegisterPropertyBoolean('readOnePhaseInverter', 'false');
			$this->RegisterPropertyInteger('pollCycle', '60');

			// *** Inverter - Erstelle deaktivierte Timer ***
			// I11X model (Evt1, EvtVnd1, EvtVnd2, EvtVnd3)
			$this->RegisterTimer("Update-I11X", 0, "\$instanceId = IPS_GetObjectIDByIdent(\"40120\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"I_EVENT_GROUND_FAULT\", \"I_EVENT_DC_OVER_VOLT\", \"I_EVENT_AC_DISCONNECT\", \"I_EVENT_DC_DISCONNECT\", \"I_EVENT_GRID_DISCONNECT\", \"I_EVENT_CABINET_OPEN\", \"I_EVENT_MANUAL_SHUTDOWN\", \"I_EVENT_OVER_TEMP\", \"I_EVENT_OVER_FREQUENCY\", \"I_EVENT_UNDER_FREQUENCY\", \"I_EVENT_AC_OVER_VOLT\", \"I_EVENT_AC_UNDER_VOLT\", \"I_EVENT_BLOWN_STRING_FUSE\", \"I_EVENT_UNDER_TEMP\", \"I_EVENT_MEMORY_LOSS\", \"I_EVENT_HW_TEST_FAILURE\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

\$instanceId = IPS_GetObjectIDByIdent(\"40124\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"INSULATION_FAULT\", \"GRID_ERROR\", \"AC_OVERCURRENT\", \"DC_OVERCURRENT\", \"OVER_TEMP\", \"POWER_LOW\", \"DC_LOW\", \"INTERMEDIATE_FAULT\", \"FREQUENCY_HIGH\", \"FREQUENCY_LOW\", \"AC_VOLTAGE_HIGH\", \"AC_VOLTAGE_LOW\", \"DIRECT_CURRENT\", \"RELAY_FAULT\", \"POWER_STAGE_FAULT\", \"CONTROL_FAULT\", \"GC_GRID_VOLT_ERR\", \"GC_GRID_FREQU_ERR\", \"ENERGY_TRANSFER_FAULT\", \"REF_POWER_SOURCE_AC\", \"ANTI_ISLANDING_FAULT\", \"FIXED_VOLTAGE_FAULT\", \"MEMORY_FAULT\", \"DISPLAY_FAULT\", \"COMMUNICATION_FAULT\", \"TEMP_SENSORS_FAULT\", \"DC_AC_BOARD_FAULT\", \"ENS_FAULT\", \"FAN_FAULT\", \"DEFECTIVE_FUSE\", \"OUTPUT_CHOKE_FAULT\", \"CONVERTER_RELAY_FAULT\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

\$instanceId = IPS_GetObjectIDByIdent(\"40126\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"NO_SOLARNET_COMM\", \"INV_ADDRESS_FAULT\", \"NO_FEED_IN_24H\", \"PLUG_FAULT\", \"PHASE_ALLOC_FAULT\", \"GRID_CONDUCTOR_OPEN\", \"SOFTWARE_ISSUE\", \"POWER_DERATING\", \"JUMPER_INCORRECT\", \"INCOMPATIBLE_FEATURE\", \"VENTS_BLOCKED\", \"POWER_REDUCTION_ERROR\", \"ARC_DETECTED\", \"AFCI_SELF_TEST_FAILED\", \"CURRENT_SENSOR_ERROR\", \"DC_SWITCH_FAULT\", \"AFCI_DEFECTIVE\", \"AFCI_MANUAL_TEST_OK\", \"PS_PWR_SUPPLY_ISSUE\", \"AFCI_NO_COMM\", \"AFCI_MANUAL_TEST_FAILED\", \"AC_POLARITY_REVERSED\", \"FAULTY_AC_DEVICE\", \"FLASH_FAULT\", \"GENERAL_ERROR\", \"GROUNDING_ISSUE\", \"LIMITATION_FAULT\", \"OPEN_CONTACT\", \"OVERVOLTAGE_PROTECTION\", \"PROGRAM_STATUS\", \"SOLARNET_ISSUE\", \"SUPPLY_VOLTAGE_FAULT\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

\$instanceId = IPS_GetObjectIDByIdent(\"40128\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"TIME_FAULT\", \"USB_FAULT\", \"DC_HIGH\", \"INIT_ERROR\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

function removeInvalidChars(\$input)
{
	return preg_replace( '/[^a-z0-9]/i', '', \$input);
}");

			// IC120 model
			$this->RegisterTimer("Update-IC120", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC120 Nameplate")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40135, 40136), array(40137, 40138), array(40139, 40143), array(40140, 40143), array(40141, 40143), array(40142, 40143), array(40144, 40145), array(40146, 40150, \"cos()\"), array(40147, 40150, \"cos()\"), array(40148, 40150, \"cos()\"), array(40149, 40150, \"cos()\"), array(40151, 40152), array(40153, 40154), array(40155, 40156), array(40157, 40158));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}

	if(isset(\$inverterModelRegister[2]) && \"cos()\" == \$inverterModelRegister[2])
	{
		\$targetId = IPS_GetObjectIDByIdent(\"Value_cos\", \$instanceId);
		\$newValue = cos(\$newValue);

		if(GetValue(\$targetId) != \$newValue)
		{
			SetValue(\$targetId, \$newValue);
		}
	}
}");

			// IC121 model
			$this->RegisterTimer("Update-IC121", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC121 Basic Settings")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40162, 40182), array(40163, 40183), array(40164, 40184), array(40167, 40186), array(40168, 40187), array(40171, 40187), array(40173, 40189, \"cos()\"), array(40176, 40189, \"cos()\"));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}

	if(isset(\$inverterModelRegister[2]) && \"cos()\" == \$inverterModelRegister[2])
	{
		\$targetId = IPS_GetObjectIDByIdent(\"Value_cos\", \$instanceId);
		\$newValue = cos(\$newValue);

		if(GetValue(\$targetId) != \$newValue)
		{
			SetValue(\$targetId, \$newValue);
		}
	}
}");

			// IC122 model (PVConn, StorConn, StActCtl, Tms)
			$this->RegisterTimer("Update-IC122", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC122 Extended Measurements & Status")."\", ".$this->InstanceID.");
//PVConn
\$instanceId = IPS_GetObjectIDByIdent(\"40194\", \$parentId);
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"Connected\",  \"Responsive\", \"Operating\", \"Testing\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
	\$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

//StorConn
\$instanceId = IPS_GetObjectIDByIdent(\"40195\", \$parentId);
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"Connected\",  \"Responsive\", \"Operating\", \"Testing\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
	\$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

//StActCtl
\$instanceId = IPS_GetObjectIDByIdent(\"40227\", \$parentId);
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"FixedW - Leistungsreduktion\", \"FixedVAR - konstante Blindleistungs-Vorgabe\",  \"FixedPF - konstanter Power-Factor\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
	\$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

//Tms
\$instanceId = IPS_GetObjectIDByIdent(\"40233\", \$parentId);
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\"UTC\"), \$instanceId);
\$bitValue = \$varValue + 946681200;

if(GetValue(\$bitId) != \$bitValue)
{
	SetValue(\$bitId, \$bitValue);
}

function removeInvalidChars(\$input)
{
	return preg_replace( '/[^a-z0-9]/i', '', \$input);
}");

			// IC123 model
			$this->RegisterTimer("Update-IC123", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC123 Immediate Controls")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40243, 40261), array(40248, 40262, \"cos()\"));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}

	if(isset(\$inverterModelRegister[2]) && \"cos()\" == \$inverterModelRegister[2])
	{
		\$targetId = IPS_GetObjectIDByIdent(\"Value_cos\", \$instanceId);
		\$newValue = cos(\$newValue);

		if(GetValue(\$targetId) != \$newValue)
		{
			SetValue(\$targetId, \$newValue);
		}
	}
}");

			// IC124 model
			$this->RegisterTimer("Update-IC124", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("IC124 Basic Storage Control")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40316, 40332), array(40317, 40333), array(40318, 40333), array(40321, 40335), array(40322, 40336), array(40323, 40337), array(40324, 40338), array(40326, 40339), array(40327, 40339));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}
}");
			// I160 model
			$this->RegisterTimer("Update-I160", 0, "\$parentId = IPS_GetObjectIDByIdent(\"".$this->removeInvalidChars("I160 Multiple MPPT Inverter Extension")."\", ".$this->InstanceID.");
// Inverter - SF Variablen erstellen
\$inverterModelRegister_array = array(array(40283, 40266), array(40284, 40267), array(40285, 40268), array(40286, 40269), 
array(40303, 40266), array(40304, 40267), array(40305, 40268), array(40306, 40269));
foreach(\$inverterModelRegister_array AS \$inverterModelRegister)
{
	\$instanceId = IPS_GetObjectIDByIdent(\$inverterModelRegister[0], \$parentId);
	\$targetId = IPS_GetObjectIDByIdent(\"Value_SF\", \$instanceId);
	\$sourceValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", \$instanceId));
	\$sfValue = GetValue(IPS_GetObjectIDByIdent(\"Value\", IPS_GetObjectIDByIdent(\$inverterModelRegister[1], \$parentId)));
	\$newValue = \$sourceValue * pow(10, \$sfValue);

	if(GetValue(\$targetId) != \$newValue)
	{
		SetValue(\$targetId, \$newValue);
	}
}");

			// *** SmartMeter - Erstelle deaktivierte Timer ***
			// Evt
			$this->RegisterTimer("SM_Update-Evt", 0, "\$instanceId = IPS_GetObjectIDByIdent(\"40194\".\"SmartMeter\", ".$this->InstanceID.");
\$varId = IPS_GetObjectIDByIdent(\"Value\", \$instanceId);
\$varValue = GetValue(\$varId);

\$bitArray = array(\"LOW_VOLTAGE\", \"LOW_POWER\", \"LOW_EFFICIENCY\", \"CURRENT\", \"VOLTAGE\", \"POWER\", \"PR\", \"DISCONNECTED\", \"FUSE_FAULT\", \"COMBINER_FUSE_FAULT\", \"COMBINER_CABINET_OPEN\", \"TEMP\", \"GROUNDFAULT\", \"REVERSED_POLARITY\", \"INCOMPATIBLE\", \"COMM_ERROR\", \"INTERNAL_ERROR\", \"THEFT\", \"ARC_DETECTED\");

for(\$i = 0; \$i < count(\$bitArray); \$i++)
{
	\$bitId = IPS_GetObjectIDByIdent(removeInvalidChars(\$bitArray[\$i]), \$instanceId);
    \$bitValue = (\$varValue >> \$i ) & 0x1;

	if(GetValue(\$bitId) != \$bitValue)
	{
		SetValue(\$bitId, \$bitValue);
	}
}

function removeInvalidChars(\$input)
{
	return preg_replace( '/[^a-z0-9]/i', '', \$input);
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
			$hostmodbusDevice = $this->ReadPropertyInteger('hostmodbusDevice');
			$hostSwapWords = 0; // Fronius = false
            $readIC120 = $this->ReadPropertyBoolean('readIC120');
            $readIC121 = $this->ReadPropertyBoolean('readIC121');
            $readIC122 = $this->ReadPropertyBoolean('readIC122');
            $readIC123 = $this->ReadPropertyBoolean('readIC123');
            $readIC124 = $this->ReadPropertyBoolean('readIC124');
			$readI160 = $this->ReadPropertyBoolean('readI160');
			$readOnePhaseInverter = $this->ReadPropertyBoolean('readOnePhaseInverter');
			$pollCycle = $this->ReadPropertyInteger('pollCycle') * 1000;

			// SmartMeter nutzen immer die GeraeteID=240
			$readSmartmeter = (240 == $hostmodbusDevice);

			$inverterCategoryArray = array(
				"IC120" => "IC120 Nameplate",
				"IC121" => "IC121 Basic Settings",
				"IC122" => "IC122 Extended Measurements & Status",
				"IC123" => "IC123 Immediate Controls",
				"I160" => "I160 Multiple MPPT Inverter Extension",
				"IC124" => "IC124 Basic Storage Control",
			);

			$archiveId = $this->getArchiveId();
			if (false === $archiveId)
			{
				// no archive found
				$this->SetStatus(201);
			}

			// Workaround für "InstanceInterface not available" Fehlermeldung beim Server-Start...
			if (KR_READY != IPS_GetKernelRunlevel())
			{
				// --> do nothing
			}
			// Instanzen nur mit Konfigurierter IP erstellen
			else if("" != $hostIp)
			{
				$this->checkProfiles();
				list($gatewayId_Old, $interfaceId_Old) = $this->readOldModbusGateway();
				list($gatewayId, $interfaceId) = $this->checkModbusGateway($hostIp, $hostPort, $hostmodbusDevice, $hostSwapWords);

				$parentId = $this->InstanceID;

				/* ****** Fronius Register **************************************************************************
					HINWEIS! Diese Register gelten nur für Wechselrichter. Für Fronius String Controls und Energiezähler sind diese Register nicht relevant
					************************************************************************************************** */
				$inverterModelRegister_array = array(
/*					array(212, 1, "RW", "0x03 0x06 0x10", "F_Delete_Data", "Delete stored ratings of the current inverter by writing 0xFFFF.", "uint16", "", "", "0xFFFF"),
					array(213, 1, "RW", "0x03 0x06 0x10", "F_Store_Data", "Rating data of all inverters connected to the Fronius Datamanager are persistently stored by writing 0xFFFF.", "uint16", "", "", "0xFFFF"),
					array(214, 1, "R", "0x03", "F_Active_State_Code", "Current active state code of inverter - Description can be found in inverter manual: Status-Code des Wechselrichters: Das Register F_Active_State_Code (214) zeigt den Status-Code des Wechselrichter an der gerade aufgetreten ist. Dieser wird eventuell auch am Display des Wechselrichter angezeigt. Dieser Code wird auch als Event Flag im Inverter Modell dargestellt. Der angezeigte Code bleibt so lange aktiv bis der entsprechende Status nicht mehr am Wechselrichter anliegt. Alternativ kann der Status auch per Register F_Reset_All_Event_ Flags gelöscht werden.", "uint16", "", "", "not supported for Fronius Hybrid inverters (because of this inverter status maybe reported differently during nighttime compared to other inverter types)"),
					array(215, 1, "RW", "0x03 0x06 0x10", "F_Reset_All_Event_Flags", "Write 0xFFFF to reset all event flags and active state code.", "uint16", "", "", "0xFFFF"),
					array(216, 1, "RW", "0x03 0x06 0x10", "F_ModelType", "Type of SunSpec models used for inverter and meter data. Write 1 or 2 and then immediately 6 to acknowledge setting.", "uint16", "", "", "1: Floating point, 2: Integer & SF"),
					array(217, 1, "RW", "0x03 0x06 0x10", "F_Storage_Restrictions_View_Mode", "Type of Restrictions reported in BasicStorageControl Model (IC124). Local restrictions are those that are set by Modbus Interface. Global restrictions are those that are set system wide.", "uint16", "", "", "0: local (default); 1: global"),
*/					array(500, 2, "R", 3, "F_Site_Power", "Total power (site sum) of all connected inverters.", "uint32", "W"),
					array(502, 4, "R", 3, "F_Site_Energy_Day", "Total energy for current day of all connected inverters.", "uint64", "Wh"),
					array(506, 4, "R", 3, "F_Site_Energy_Year", "Total energy for last year of all connected inverters.", "uint64", "Wh"),
					array(510, 4, "R", 3, "F_Site_Energy_Total", "Total energy of all connected inverters.", "uint64", "Wh"),
//				);


				/* ********** Common Model **************************************************************************
					Die Beschreibung des Common Block inklusive der SID Register (Register 40001-40002)
					zur Identifizierung als SunSpec Gerät gilt für jeden Gerätetyp (Wechselrichter, String Control,
					Energiezähler). Jedes Gerät besitzt einen eigenen Common Block, in dem Informationen
					über das Gerät (Modell, Seriennummer, SW Version, etc.) aufgeführt sind.
					************************************************************************************************** */
/*				$inverterModelRegister_array = array(
					// array(40001, 2, "R", 3, "SID", "uint32", "", "Well-known value. Uniquely identifies this as a SunSpec Modbus Map"),
					// array(40003, 1, "R", 3, "ID", "uint16", "", "Well-known value. Uniquely identifies this as a SunSpec Common Model block"), // = 1
					// array(40004, 1, "R", 3, "L", "uint16", "", "Registers Length of Common Model block"), // = 65
					// array(40005, 16, "R", 3, "Mn", "String32", "", "Manufacturer z.B. Fronius"),
					// array(40021, 16, "R", 3, "Md", "String32", "", "Device model z.B. IG+150V"),
					// array(40037, 8, "R", 3, "Opt", "String16", "", "SW version of datamanager z.B. 3.3.6-13"),
					// array(40045, 8, "R", 3, "Vr", "String16", "", "SW version of inverter"),
					// array(40053, 16, "R", 3, "SN", "String32", "", "Serialnumber of inverter, string control or energy meter"),
					//  array(40069, 1, "R", 3, "DA", "uint16", "", "Modbus Device Address 1 - 247"), // = 1
				);
*/

//				$inverterModelRegister_array = array(
					/* ********** Inverter Model I11X ************************************************************************
				Für die Wechselrichter-Daten werden zwei verschiedene SunSpec Models unterstützt:
					- das standardmäßig eingestellte Inverter Model mit Gleitkomma-Darstellung (Einstellung „float“; I111, I112 oder I113)
				HINWEIS! Die Registeranzahl der beiden Model-Typen ist unterschiedlich!
						************************************************************************************************** */
					array(40070, 1, "R", 3, "ID", "Uniquely identifies this as a SunSpec Inverter Modbus Map (111: single phase, 112: split phase, 113: three phase)", "uint16", "Enumerated_ID"),
					array(40071, 1, "R", 3, "L - Registers", "Registers, Length of inverter model block", "uint16", ""),
					array(40072, 2, "R", 3, "A - AC Total Current", "AC Total Current value", "float32", "A"),
					array(40074, 2, "R", 3, "AphA - AC Phase-A Current", "AC Phase-A Current value", "float32", "A"),
					array(40086, 2, "R", 3, "PhVphA - AC Voltage Phase-A-toneutral", "AC Voltage Phase-A-toneutral value", "float32", "V"),
					array(40092, 2, "R", 3, "W - AC Power", "AC Power value", "float32", "W"),
					array(40094, 2, "R", 3, "Hz - AC Frequency", "AC Frequency value", "float32", "Hz"),
					array(40096, 2, "R", 3, "VA - Apparent Power", "Apparent Power", "float32", "VA"),
					array(40098, 2, "R", 3, "VAr - Reactive Power", "Reactive Power", "float32", "VAr"),
					array(40100, 2, "R", 3, "PF - Power Factor", "Power Factor", "float32", "%"),
					array(40102, 2, "R", 3, "WH - AC Lifetime Energy production", "AC Lifetime Energy production", "float32", "Wh"),
					//	array(40104, 2, "R", 3, "DCA", "DC Current value (DC current only if one MPPT available; with multiple MPPT 'not implemented')", "float32", "A"),
					//	array(40106, 2, "R", 3, "DCV", "DC Voltage value (DC voltage only if one MPPT available; with multiple MPPT 'not implemented')", "float32", "V"),
					array(40108, 2, "R", 3, "DCW - DC Power", "DC Power value", "float32", "W"),
					//	array(40110, 2, "R", 3, "TmpCab", "Cabinet Temperature", "float32", "° C"), // Not supported
					//	array(40112, 2, "R", 3, "TmpSnk", "Coolant or Heat Sink Temperature", "float32", "° C"), // Not supported
					//	array(40114, 2, "R", 3, "TmpTrns", "Transformer Temperature", "float32", "° C"), // Not supported
					//	array(40116, 2, "R", 3, "TmpOt", "Other Temperature", "float32", "° C"), // Not supported
					array(40118, 1, "R", 3, "St - Operating State", "Operating State (SunSpec State Codes)", "enum16", "Enumerated_St"),
					array(40119, 1, "R", 3, "StVnd - Vendor Operating State", "Vendor Defined Operating State (Fronius State Codes)", "enum16", "Enumerated_StVnd"),
					array(40120, 2, "R", 3, "Evt1 - Event Flags", "Event Flags (bits 0-31)", "uint32", "Bitfield"),
					array(40122, 2, "R", 3, "Evt2 - Event Flags", "Event Flags (bits 32-63)", "uint32", "Bitfield"),
					array(40124, 2, "R", 3, "EvtVnd1 - Vendor Event Flags", "Vendor Defined Event Flags (bits 0-31)", "uint32", "Bitfield"),
					array(40126, 2, "R", 3, "EvtVnd2 - Vendor Event Flags", "Vendor Defined Event Flags (bits 32-63)", "uint32", "Bitfield"),
					array(40128, 2, "R", 3, "EvtVnd3 - Vendor Event Flags", "Vendor Defined Event Flags (bits 64-95)", "uint32", "Bitfield"),
					array(40130, 2, "R", 3, "EvtVnd4 - Vendor Event Flags", "Vendor Defined Event Flags (bits 96-127)", "uint32", "Bitfield"),
				);

				$inverterModel3pRegister_array = array(
					/* ********** Inverter Model (3-phase) *********************************************************************** */
					array(40076, 2, "R", 3, "AphB - AC Phase-B Current", "AC Phase-B Current value", "float32", "A"),
					array(40078, 2, "R", 3, "AphC - AC Phase-C Current", "AC Phase-C Current value", "float32", "A"),
					array(40080, 2, "R", 3, "PPVphAB - AC Voltage Phase-AB", "AC Voltage Phase-AB value", "float32", "V"),
					array(40082, 2, "R", 3, "PPVphBC - AC Voltage Phase-BC", "AC Voltage Phase-BC value", "float32", "V"),
					array(40084, 2, "R", 3, "PPVphCA - AC Voltage Phase-CA", "AC Voltage Phase-CA value", "float32", "V"),
					array(40088, 2, "R", 3, "PhVphB - AC Voltage Phase-B-toneutral", "AC Voltage Phase-B-toneutral value", "float32", "V"),
					array(40090, 2, "R", 3, "PhVphC - AC Voltage Phase-C-toneutral", "AC Voltage Phase-C-toneutral value", "float32", "V"),
				);

					/* ********** Meter Model ************************************************************************
				Ähnlich wie bei den Inverter Models gibt es auch für SmartMeter zwei verschiedene SunSpec Models:
					- das Meter Model mit Gleitkommadarstellung (Einstellung „float“; 211, 212 oder 213)
					- das Meter Model mit ganzen Zahlen und Skalierungsfaktoren (Einstellung „int+SF“; 201, 202 oder 203)
				Die Registeranzahl der beiden Model-Typen ist unterschiedlich!
						************************************************************************************************** */
				$meterModelRegister_array = array(
					array(40070, 1, "R", 3, "ID", "Uniquely identifies this as a SunSpec Meter Modbus Map (float); 211: single phase, 212: split phase, 213: three phase", "uint16", ""),
					array(40071, 1, "R", 3, "L - Registers", "Registers, Length of inverter model block: 124", "uint16", ""),
					array(40072, 2, "R", 3, "A - AC Total Current", "AC Total Current value", "float32", "A"),
					array(40074, 2, "R", 3, "AphA - AC Phase-A Current", "AC Phase-A Current value", "float32", "A"),
					array(40076, 2, "R", 3, "AphB - AC Phase-B Current", "AC Phase-B Current value", "float32", "A"),
					array(40078, 2, "R", 3, "AphC - AC Phase-C Current", "AC Phase-C Current value", "float32", "A"),
					array(40080, 2, "R", 3, "PhV - AC Voltage Average", "AC Voltage Average Phase-to-neutral value", "float32", "V"),
					array(40082, 2, "R", 3, "PhVphA - AC Voltage Phase-A-to-neutral", "AC Voltage Phase-A-to-neutral value", "float32", "V"),
					array(40084, 2, "R", 3, "PhVphB - AC Voltage Phase-B-to-neutral", "AC Voltage Phase-B-to-neutral value", "float32", "V"),
					array(40086, 2, "R", 3, "PhVphC - AC Voltage Phase-C-to-neutral", "AC Voltage Phase-C-to-neutral value", "float32", "V"),
					array(40088, 2, "R", 3, "PPV - AC Voltage Average Phase-to-phase", "AC Voltage Average Phase-to-phase value", "float32", "V"),
					array(40090, 2, "R", 3, "PPVphAB - AC Voltage Phase-AB", "AC Voltage Phase-AB value", "float32", "V"),
					array(40092, 2, "R", 3, "PPVphBC - AC Voltage Phase-BC", "AC Voltage Phase-BC value", "float32", "V"),
					array(40094, 2, "R", 3, "PPVphCA - AC Voltage Phase-CA", "AC Voltage Phase-CA value", "float32", "V"),
					array(40096, 2, "R", 3, "Hz - AC Frequency", "AC Frequency value", "float32", "Hz"),
					array(40098, 2, "R", 3, "W - AC Power", "AC Power value", "float32", "W"),
					array(40100, 2, "R", 3, "WphA - AC Power Phase A", "AC Power Phase A value", "float32", "W"),
					array(40102, 2, "R", 3, "WphB - AC Power Phase B", "AC Power Phase B value", "float32", "W"),
					array(40104, 2, "R", 3, "WphC - AC Power Phase C", "AC Power Phase C value", "float32", "W"),
					array(40106, 2, "R", 3, "VA - AC Apparent Power", "AC Apparent Power value", "float32", "VA"),
					array(40108, 2, "R", 3, "VAphA - AC Apparent Power Phase A", "AC Apparent Power Phase A value", "float32", "VA"),
					array(40110, 2, "R", 3, "VAphB - AC Apparent Power Phase B", "AC Apparent Power Phase B value", "float32", "VA"),
					array(40112, 2, "R", 3, "VAphC - AC Apparent Power Phase C", "AC Apparent Power Phase C value", "float32", "VA"),
					array(40114, 2, "R", 3, "VAR - AC Reactive Power", "AC Reactive Power value", "float32", "VAr"),
					array(40116, 2, "R", 3, "VARphA - AC Reactive Power Phase A", "AC Reactive Power Phase A value", "float32", "VAr"),
					array(40118, 2, "R", 3, "VARphB - AC Reactive Power Phase B", "AC Reactive Power Phase B value", "float32", "VAr"),
					array(40120, 2, "R", 3, "VARphC - AC Reactive Power Phase C", "AC Reactive Power Phase C value", "float32", "VAr"),
					array(40122, 2, "R", 3, "PF - Power Factor", "Power Factor value", "float32", "cos()"),
					array(40124, 2, "R", 3, "PFphA - Power Factor Phase A", "Power Factor Phase A value", "float32", "cos()"),
					array(40126, 2, "R", 3, "PFphB - Power Factor Phase B", "Power Factor Phase B value", "float32", "cos()"),
					array(40128, 2, "R", 3, "PFphC - Power Factor Phase C", "Power Factor Phase C value", "float32", "cos()"),
					array(40130, 2, "R", 3, "TotWhExp - Total Wh Exported", "Total Watt-hours Exported", "float32", "Wh"),
					array(40132, 2, "R", 3, "TotWhExpPhA - Total Wh Exported phase A", "Total Watt-hours Exported phase A", "float32", "Wh"),
					array(40134, 2, "R", 3, "TotWhExpPhB - Total Wh Exported phase B", "Total Watt-hours Exported phase B", "float32", "Wh"),
					array(40136, 2, "R", 3, "TotWhExpPhC - Total Wh Exported phase C", "Total Watt-hours Exported phase C", "float32", "Wh"),
					array(40138, 2, "R", 3, "TotWhImp - Total Wh Imported", "Total Watt-hours Imported", "float32", "Wh"),
					array(40140, 2, "R", 3, "TotWhImpPhA - Total Wh Imported phase A", "Total Watt-hours Imported phase A", "float32", "Wh"),
					array(40142, 2, "R", 3, "TotWhImpPhB - Total Wh Imported phase B", "Total Watt-hours Imported phase B", "float32", "Wh"),
					array(40144, 2, "R", 3, "TotWhImpPhC - Total Wh Imported phase C", "Total Watt-hours Imported phase C", "float32", "Wh"),
					array(40146, 2, "R", 3, "TotVAhExp - Total VAh Exported", "Total VA-hours Exported", "float32", "VAh"),
					array(40148, 2, "R", 3, "TotVAhExpPhA - Total VAh Exported phase A", "Total VA-hours Exported phase A", "float32", "VAh"),
					array(40150, 2, "R", 3, "TotVAhExpPhB - Total VAh Exported phase B", "Total VA-hours Exported phase B", "float32", "VAh"),
					array(40152, 2, "R", 3, "TotVAhExpPhC - Total VAh Exported phase C", "Total VA-hours Exported phase C", "float32", "VAh"),
					array(40154, 2, "R", 3, "TotVAhImp - Total VAh Imported", "Total VA-hours Imported", "float32", "VAh"),
					array(40156, 2, "R", 3, "TotVAhImpPhA - Total VAh Imported phase A", "Total VA-hours Imported phase A", "float32", "VAh"),
					array(40158, 2, "R", 3, "TotVAhImpPhB - Total VAh Imported phase B", "Total VA-hours Imported phase B", "float32", "VAh"),
					array(40160, 2, "R", 3, "TotVAhImpPhC - Total VAh Imported phase C", "Total VA-hours Imported phase C", "float32", "VAh"),
/*					array(40162, 2, "R", 3, "TotVArhImpQ1 - Total VARh Imported Q1", "Total VAR-hours Imported Q1", "float32", "VArh"),
					array(40164, 2, "R", 3, "TotVArhImpQ1phA - Total VARh Imported Q1 phase A", "Total VAR-hours Imported Q1 phase A", "float32", "VArh"),
					array(40166, 2, "R", 3, "TotVArhImpQ1phB - Total VARh Imported Q1 phase B", "Total VAR-hours Imported Q1 phase B", "float32", "VArh"),
					array(40168, 2, "R", 3, "TotVArhImpQ1phC - Total VARh Imported Q1 phase C", "Total VAR-hours Imported Q1 phase C", "float32", "VArh"),
					array(40170, 2, "R", 3, "TotVArhImpQ2 - Total VArh Imported Q2", "Total VAr-hours Imported Q2", "float32", "VArh"),
					array(40172, 2, "R", 3, "TotVArhImpQ2phA - Total VARh Imported Q2 phase A", "Total VAR-hours Imported Q2 phase A", "float32", "VArh"),
					array(40174, 2, "R", 3, "TotVArhImpQ2phB - Total VARh Imported Q2 phase B", "Total VAR-hours Imported Q2 phase B", "float32", "VArh"),
					array(40176, 2, "R", 3, "TotVArhImpQ2phC - Total VARh Imported Q2 phase C", "Total VAR-hours Imported Q2 phase C", "float32", "VArh"),
					array(40178, 2, "R", 3, "TotVArhExpQ3 - Total VArh Imported Q3", "Total VAr-hours Exported Q3", "float32", "VArh"),
					array(40180, 2, "R", 3, "TotVArhExpQ3phA - Total VARh Imported Q3 phase A", "Total VAR-hours Exported Q3 phase A", "float32", "VArh"),
					array(40182, 2, "R", 3, "TotVArhExpQ3phB - Total VARh Imported Q3 phase B", "Total VAR-hours Exported Q3 phase B", "float32", "VArh"),
					array(40184, 2, "R", 3, "TotVArhExpQ3phC - Total VARh Imported Q3 phase C", "Total VAR-hours Exported Q3 phase C", "float32", "VArh"),
					array(40186, 2, "R", 3, "TotVArhExpQ4 - Total VArh Imported Q4", "Total VAr-hours Exported Q4", "float32", "VArh"),
					array(40188, 2, "R", 3, "TotVArhExpQ4phA - Total VARh Imported Q4 phase A", "Total VAR-hours Exported Q4 phase A", "float32", "VArh"),
					array(40190, 2, "R", 3, "TotVArhExpQ4phB - Total VARh Imported Q4 phase B", "Total VAR-hours Exported Q4 phase B", "float32", "VArh"),
					array(40192, 2, "R", 3, "TotVArhExpQ4phC - Total VARh Imported Q4 phase C", "Total VAR-hours Exported Q4 phase C", "float32", "VArh"),
*/					array(40194, 2, "R", 3, "Evt - Events", "Events (bits 1-19)", "uint32", "bitfield32"),
				);


				/*** Wechselrichter / Inverter ***/
				if (false == $readSmartmeter)
				{
					$categoryId = $parentId;

					// SmartMeter - Timer deaktivieren
					$this->SetTimerInterval("SM_Update-Evt", 0);

					$this->deleteModbusInstancesRecursive($meterModelRegister_array, $categoryId, "SmartMeter");
					$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);

					// 3-Phase Inverter
					if (false == $readOnePhaseInverter)
					{
						$this->createModbusInstances($inverterModel3pRegister_array, $categoryId, $gatewayId, $pollCycle);
					}
					// 1-Phase Inverter
					else
					{
						$this->deleteModbusInstancesRecursive($inverterModel3pRegister_array, $categoryId);
					}	

					// Inverter - Bit 0 - 15 für "Evt1 - Event Flags" erstellen
					$instanceId = IPS_GetObjectIDByIdent("40120", $categoryId);
					$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
					IPS_SetHidden($varId, true);

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

					foreach ($bitArray as $bit)
					{
						$varId = $this->MaintainInstanceVariable($this->removeInvalidChars($bit['varName']), $bit['varName'], VARIABLETYPE_BOOLEAN, $bit['varProfile'], 0, true, $instanceId, $bit['varInfo']);
					}

					// Inverter - Bit 0 - 15 für "EvtVnd1 - Vendor Event Flags" erstellen
					$instanceId = IPS_GetObjectIDByIdent("40124", $categoryId);
					$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
					IPS_SetHidden($varId, true);

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

					foreach ($bitArray as $bit)
					{
						$varId = $this->MaintainInstanceVariable($this->removeInvalidChars($bit['varName']), $bit['varName'], VARIABLETYPE_BOOLEAN, $bit['varProfile'], 0, true, $instanceId, $bit['varInfo']);
					}


					// Inverter - Bit 0 - 15 für "EvtVnd2 - Vendor Event Flags" erstellen
					$instanceId = IPS_GetObjectIDByIdent("40126", $categoryId);
					$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
					IPS_SetHidden($varId, true);

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

					foreach ($bitArray as $bit)
					{
						$varId = $this->MaintainInstanceVariable($this->removeInvalidChars($bit['varName']), $bit['varName'], VARIABLETYPE_BOOLEAN, $bit['varProfile'], 0, true, $instanceId, $bit['varInfo']);
					}


					// Inverter - Bit 0 - 15 für "EvtVnd3 - Vendor Event Flags" erstellen
					$instanceId = IPS_GetObjectIDByIdent("40128", $categoryId);
					$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
					IPS_SetHidden($varId, true);

					$bitArray = array(
						array('varName' => "TIME_FAULT", 'varProfile' => "~Alert", 'varInfo' => "Time error - StateCodes: 751;752;753;754;755;756;757;758;760"),
						array('varName' => "USB_FAULT", 'varProfile' => "~Alert", 'varInfo' => "USB error - StateCodes: 731;732;733;734;735;736;737;738;739;740;741;743;744;745;746;747;748;749;750"),
						array('varName' => "DC_HIGH", 'varProfile' => "~Alert", 'varInfo' => "DC high - StateCodes: 309;313"),
						array('varName' => "INIT_ERROR", 'varProfile' => "~Alert", 'varInfo' => "Init error - StateCodes: 482"),
					);

					foreach ($bitArray as $bit)
					{
						$varId = $this->MaintainInstanceVariable($this->removeInvalidChars($bit['varName']), $bit['varName'], VARIABLETYPE_BOOLEAN, $bit['varProfile'], 0, true, $instanceId, $bit['varInfo']);
					}



					$categoryName = $inverterCategoryArray['IC120'];
					$categoryId = @IPS_GetObjectIDByIdent($this->removeInvalidChars($categoryName), $parentId);
					if ($readIC120)
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
							//	array(40132, 1, "R", 3, "ID", "A well-known value 120. Uniquely identifies this as a SunSpec Nameplate Model", "uint16", ""), // = 120
							//	array(40133, 1, "R", 3, "L", "uint16", "Registers", "Length of Nameplate Model"), // = 26
							//	array(40134, 1, "R", 3, "DERTyp", "enum16", "", "Type of DER device. Default value is 4 to indicate PV device."), // = 4
							array(40135, 1, "R", 3, "WRtg", "WRtg_SF Continuous power output capability of the inverter.", "uint16", ""),
							array(40137, 1, "R", 3, "VARtg", "VARtg_SF Continuous Volt-Ampere capability of the inverter.", "uint16", ""),
							array(40139, 1, "R", 3, "VArRtgQ1", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 1.", "int16", ""),
							array(40140, 1, "R", 3, "VArRtgQ2", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 2.", "int16", ""),
							array(40141, 1, "R", 3, "VArRtgQ3", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 3.", "int16", ""),
							array(40142, 1, "R", 3, "VArRtgQ4", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 4.", "int16", ""),
							array(40144, 1, "R", 3, "ARtg", "ARtg_SF Maximum RMS AC current level capability of the inverter.", "uint16", ""),
							array(40146, 1, "R", 3, "PFRtgQ1", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 1.", "int16", ""),
							array(40147, 1, "R", 3, "PFRtgQ2", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 2.", "int16", ""),
							array(40148, 1, "R", 3, "PFRtgQ3", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 3.", "int16", ""),
							array(40149, 1, "R", 3, "PFRtgQ4", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 4.", "int16", ""),
							array(40151, 1, "R", 3, "WHRtg", "WHRtg_SF Nominal energy rating of storage device. (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "uint16", ""),
							array(40153, 1, "R", 3, "AhrRtg", "AhrRtg_SF The useable capacity of the battery. Maximum charge minus minimum charge from a technology capability perspective (Amp-hour capacity rating).", "uint16", ""),
							array(40155, 1, "R", 3, "MaxChaRte", "MaxChaRte_SF Maximum rate of energy transfer into the storage device.  (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "uint16", ""),
							array(40157, 1, "R", 3, "MaxDisChaRte", "Max-DisChaRte_SF Maximum rate of energy transfer out of the storage device.  (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "uint16", ""),
							array(40136, 1, "R", 3, "WRtg_SF", "	Scale factor 1", "sunssf", ""),
							array(40138, 1, "R", 3, "VARtg_SF", "	Scale factor 1", "sunssf", ""),
							array(40143, 1, "R", 3, "VArRtg_SF", "Scale factor 1", "sunssf", ""),
							array(40145, 1, "R", 3, "ARtg_SF", "Scale factor -2", "sunssf", ""),
							array(40150, 1, "R", 3, "PFRtg_SF", "Scale factor -3", "sunssf", ""),
							array(40152, 1, "R", 3, "WHRtg_SF", "Scale factor 0  (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "sunssf", ""),
							array(40154, 1, "R", 3, "AhrRtg_SF", "Scale factor for amphour rating.", "sunssf", ""),
							array(40156, 1, "R", 3, "MaxChaRte_SF", "Scale factor 0  (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "sunssf", ""),
							array(40158, 1, "R", 3, "MaxDisChaRte_SF", "Scale factor 0  (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "sunssf", ""),
							//	array(40159, 1, "R", 3, "Pad", "	Pad register", "", ""),
						);

						if (false === $categoryId)
						{
							$categoryId = IPS_CreateCategory();
							IPS_SetIdent($categoryId, $this->removeInvalidChars($categoryName));
							IPS_SetName($categoryId, $categoryName);
							IPS_SetParent($categoryId, $parentId);
							IPS_SetInfo($categoryId, "Dieses Modell entspricht einem Leistungsschild. Folgende Daten können ausgelesen werden:
- DERType (3): Art des Geräts. Das Register liefert den Wert 4 zurück (PV-Gerät)
- WRtg (4): Nennleistung des Wechselrichters
- VARtg (6): Nenn-Scheinleistung des Wechselrichters
- VArRtgQ1 (8) - VArRtgQ4 (11): Nenn-Blindleistungswerte für die vier Quadranten
- ARtg (13): Nennstrom des Wechselrichters
- PFRtgQ1 (15) – PFRtgQ4 (18): Minimale Werte für den Power Factor für die vier Quadranten");
						}

						$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);


						// Inverter - "SF" Variablen erstellen
						$inverterModelRegister_array = array(
							array(40135, 1, "R", 3, "WRtg", "WRtg_SF Continuous power output capability of the inverter.", "uint16", "W", 40136),
							array(40137, 1, "R", 3, "VARtg", "VARtg_SF Continuous Volt-Ampere capability of the inverter.", "uint16", "VA", 40138),
							array(40139, 1, "R", 3, "VArRtgQ1", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 1.", "int16", "var", 40143),
							array(40140, 1, "R", 3, "VArRtgQ2", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 2.", "int16", "var", 40143),
							array(40141, 1, "R", 3, "VArRtgQ3", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 3.", "int16", "var", 40143),
							array(40142, 1, "R", 3, "VArRtgQ4", "VArRtg_SF Continuous VAR capability of the inverter in quadrant 4.", "int16", "var", 40143),
							array(40144, 1, "R", 3, "ARtg", "ARtg_SF Maximum RMS AC current level capability of the inverter.", "uint16", "A", 40145),
							array(40146, 1, "R", 3, "PFRtgQ1", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 1.", "int16", "cos()", 40150),
							array(40147, 1, "R", 3, "PFRtgQ2", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 2.", "int16", "cos()", 40150),
							array(40148, 1, "R", 3, "PFRtgQ3", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 3.", "int16", "cos()", 40150),
							array(40149, 1, "R", 3, "PFRtgQ4", "PFRtg_SF Minimum power factor capability of the inverter in quadrant 4.", "int16", "cos()", 40150),
							array(40151, 1, "R", 3, "WHRtg", "WHRtg_SF Nominal energy rating of storage device. (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "uint16", "Wh", 40152),
							array(40153, 1, "R", 3, "AhrRtg", "AhrRtg_SF The useable capacity of the battery. Maximum charge minus minimum charge from a technology capability perspective (Amp-hour capacity rating).", "uint16", "AH", 40154),
							array(40155, 1, "R", 3, "MaxChaRte", "MaxChaRte_SF Maximum rate of energy transfer into the storage device.  (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "uint16", "W", 40156),
							array(40157, 1, "R", 3, "MaxDisChaRte", "Max-DisChaRte_SF Maximum rate of energy transfer out of the storage device.  (wird nur von Fronius Hybrid Wechselrichtern unterstützt)", "uint16", "W", 40158),
						);

						foreach($inverterModelRegister_array AS $inverterModelRegister)
						{
							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_START_REGISTER], $categoryId);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
							
							$dataType = 7;
							$profile = $this->getProfile($inverterModelRegister[IMR_UNITS], $dataType);

							$varId = $this->MaintainInstanceVariable("Value_SF", IPS_GetName($instanceId), VARIABLETYPE_FLOAT, $profile, 0, true, $instanceId, $inverterModelRegister[IMR_DESCRIPTION]);

							if("cos()" == $inverterModelRegister[IMR_UNITS])
							{
								$varId = $this->MaintainInstanceVariable("Value_cos", IPS_GetName($instanceId)."_cos", VARIABLETYPE_FLOAT, "", 0, true, $instanceId, $inverterModelRegister[IMR_DESCRIPTION]);
							}

							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_UNITS + 1], $categoryId);
							IPS_SetHidden($instanceId, true);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
						}
					}
					else
					{
						$this->SetTimerInterval("Update-IC120", 0);

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


					$categoryName = $inverterCategoryArray['IC121'];
					$categoryId = @IPS_GetObjectIDByIdent($this->removeInvalidChars($categoryName), $parentId);
					if ($readIC121)
					{
						$inverterModelRegister_array = array(
						/******* Basic Settings Model (IC121) */
//							array(40160, 1, "R", "0x03", "ID", "A well-known value 121.  Uniquely identifies this as a SunSpec Basic Settings Model", "uint16", "", "", "121"),
//							array(40161, 1, "R", "0x03", "L", "Registers, Length of Basic Settings Model", "uint16", "", "", "30"),
							array(40162, 1, "RW", "0x03 0x06 0x10", "WMax", "Setting for maximum power output. Default to I_WRtg.", "uint16", "", "WMax_SF", ""),
							array(40163, 1, "RW", "0x03 0x06 0x10", "VRef", "Voltage at the PCC.", "uint16", "", "VRef_SF", ""),
							array(40164, 1, "RW", "0x03 0x06 0x10", "VRefOfs", "Offset  from PCC to inverter.", "int16", "", "VRefOfs_SF", ""),
//							array(40165, 1, "RW", "0x03 0x06 0x10", "VMax", "Setpoint for maximum voltage.", "uint16", "V", "VMinMax_SF", "Not supported"),
//							array(40166, 1, "RW", "0x03 0x06 0x10", "VMin", "Setpoint for minimum voltage.", "uint16", "V", "VMinMax_SF", "Not supported"),
							array(40167, 1, "RW", "0x03", "VAMax", "Setpoint for maximum apparent power. Default to I_VARtg.", "uint16", "", "VAMax_SF", ""),
							array(40168, 1, "R", "0x03", "VARMaxQ1", "Setting for maximum reactive power in quadrant 1. Default to VArRtgQ1.", "int16", "", "VARMax_SF", ""),
//							array(40169, 1, "R", "0x03", "VARMaxQ2", "Setting for maximum reactive power in quadrant 2. Default to VArRtgQ2.", "int16", "var", "VARMax_SF", "Not supported"),
//							array(40170, 1, "R", "0x03", "VARMaxQ3", "Setting for maximum reactive power in quadrant 3 Default to VArRtgQ3.", "int16", "var", "VARMax_SF", "Not supported"),
							array(40171, 1, "R", "0x03", "VARMaxQ4", "Setting for maximum reactive power in quadrant 4 Default to VArRtgQ4.", "int16", "", "VARMax_SF", ""),
//							array(40172, 1, "R", "0x03", "WGra", "Default ramp rate of change of active power due to command or internal action. (% WMax/min)", "uint16", "%", "WGra_SF", "Not supported"),
							array(40173, 1, "R", "0x03", "PFMinQ1", "Setpoint for minimum power factor value in quadrant 1. Default to PFRtgQ1.", "int16", "", "PFMin_SF", ""),
//							array(40174, 1, "R", "0x03", "PFMinQ2", "Setpoint for minimum power factor value in quadrant 2. Default to PFRtgQ2.", "int16", "cos()", "PFMin_SF", "Not supported"),
//							array(40175, 1, "R", "0x03", "PFMinQ3", "Setpoint for minimum power factor value in quadrant 3. Default to PFRtgQ3.", "int16", "cos()", "PFMin_SF", "Not supported"),
							array(40176, 1, "R", "0x03", "PFMinQ4", "Setpoint for minimum power factor value in quadrant 4. Default to PFRtgQ4.", "int16", "", "PFMin_SF", ""),
//							array(40177, 1, "R", "0x03", "VArAct", "VAR action on change between charging and discharging: 1=switch 2=maintain VAR characterization.", "enum16", "", "", "Not supported"),
//							array(40178, 1, "R", "0x03", "ClcTotVA", "Calculation method for total apparent power. 1=vector 2=arithmetic.", "enum16", "", "", "Not supported"),
//							array(40179, 1, "R", "0x03", "MaxRmpRte", "Setpoint for maximum ramp rate as percentage of nominal maximum ramp rate. This setting will limit the rate that watts delivery to the grid can increase or decrease in response to intermittent PV generation. (% WGra)", "uint16", "%", "MaxRmpRte_SF", "Not supported"),
//							array(40180, 1, "R", "0x03", "ECPNomHz", "Setpoint for nominal frequency at the ECP.", "uint16", "Hz", "ECPNomHz_SF", "Not supported"),
//							array(40181, 1, "R", "0x03", "ConnPh", "Identity of connected phase for single phase inverters. A=1 B=2 C=3.", "enum16", "", "", "Not supported"),
							array(40182, 1, "R", "0x03", "WMax_SF", "Scale factor for maximum power output.", "sunssf", "", "", "1"),
							array(40183, 1, "R", "0x03", "VRef_SF", "Scale factor for voltage at the PCC.", "sunssf", "", "", "0"),
							array(40184, 1, "R", "0x03", "VRefOfs_SF", "Scale factor for offset voltage.", "sunssf", "", "", "0"),
//							array(40185, 1, "R", "0x03", "VMinMax_SF", "Scale factor for min/max voltages.", "sunssf", "", "", "0"),
							array(40186, 1, "R", "0x03", "VAMax_SF", "Scale factor for voltage at the PCC.", "sunssf", "", "", "1"),
							array(40187, 1, "R", "0x03", "VARMax_SF", "Scale factor for reactive power.", "sunssf", "", "", "1"),
//							array(40188, 1, "R", "0x03", "WGra_SF", "Scale factor for default ramp rate.", "sunssf", "", "", "Not supported"),
							array(40189, 1, "R", "0x03", "PFMin_SF", "Scale factor for minimum power factor.", "sunssf", "", "", "-3"),
//							array(40190, 1, "R", "0x03", "MaxRmpRte_SF", "Scale factor for maximum ramp percentage.", "sunssf", "", "", "Not supported"),
//							array(40191, 1, "R", "0x03", "ECPNomHz_SF", "Scale factor for nominal frequency.", "sunssf", "", "", "Not supported"),
						);

						if (false === $categoryId)
						{
							$categoryId = IPS_CreateCategory();
							IPS_SetIdent($categoryId, $this->removeInvalidChars($categoryName));
							IPS_SetName($categoryId, $categoryName);
							IPS_SetParent($categoryId, $parentId);
							IPS_SetInfo($categoryId, "Dieses Modell liefert Standard Mess- und Statuswerte.");
						}

						$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);


						// Inverter - "SF" Variablen erstellen
						$inverterModelRegister_array = array(
							array(40162, 1, "RW", "0x03 0x06 0x10", "WMax", "Setting for maximum power output. Default to I_WRtg.", "uint16", "W", 40182),
							array(40163, 1, "RW", "0x03 0x06 0x10", "VRef", "Voltage at the PCC.", "uint16", "V", 40183),
							array(40164, 1, "RW", "0x03 0x06 0x10", "VRefOfs", "Offset  from PCC to inverter.", "int16", "V", 40184),
							array(40167, 1, "RW", "0x03", "VAMax", "Setpoint for maximum apparent power. Default to I_VARtg.", "uint16", "VA", 40186),
							array(40168, 1, "R", "0x03", "VARMaxQ1", "Setting for maximum reactive power in quadrant 1. Default to VArRtgQ1.", "int16", "var", 40187),
							array(40171, 1, "R", "0x03", "VARMaxQ4", "Setting for maximum reactive power in quadrant 4 Default to VArRtgQ4.", "int16", "var", 40187),
							array(40173, 1, "R", "0x03", "PFMinQ1", "Setpoint for minimum power factor value in quadrant 1. Default to PFRtgQ1.", "int16", "cos()", 40189),
							array(40176, 1, "R", "0x03", "PFMinQ4", "Setpoint for minimum power factor value in quadrant 4. Default to PFRtgQ4.", "int16", "cos()", 40189),
						);

						foreach($inverterModelRegister_array AS $inverterModelRegister)
						{
							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_START_REGISTER], $categoryId);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
							
							$dataType = 7;
							$profile = $this->getProfile($inverterModelRegister[IMR_UNITS], $dataType);

							$varId = $this->MaintainInstanceVariable("Value_SF", IPS_GetName($instanceId), VARIABLETYPE_FLOAT, $profile, 0, true, $instanceId, $inverterModelRegister[IMR_DESCRIPTION]);

							if("cos()" == $inverterModelRegister[IMR_UNITS])
							{
								$varId = $this->MaintainInstanceVariable("Value_cos", IPS_GetName($instanceId)."_cos", VARIABLETYPE_FLOAT, "", 0, true, $instanceId, $inverterModelRegister[IMR_DESCRIPTION]);
							}

							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_UNITS + 1], $categoryId);
							IPS_SetHidden($instanceId, true);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
						}
					}
					else
					{
						$this->SetTimerInterval("Update-IC121", 0);

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

	
					$categoryName = $inverterCategoryArray['IC122'];
					$categoryId = @IPS_GetObjectIDByIdent($this->removeInvalidChars($categoryName), $parentId);
					if ($readIC122)
					{
						$inverterModelRegister_array = array(
							/******* Extended Measurements & Status Model (IC122)
							Allgemeines: Dieses Modell liefert einige zusätzliche Mess- und Statuswerte, die das normale Inverter Model nicht abdeckt.
							*/
//							array(40192, 1, "R", "0x03", "ID", "A well-known value 122.  Uniquely identifies this as a SunSpec Measurements_Status Model", "uint16", "", "", "122"),
//							array(40193, 1, "R", "0x03", "L", "Registers, Length of Measurements_Status Model", "uint16", "", "", "44"),
							array(40194, 1, "R", "0x03", "PVConn - PV inverter status", "PV inverter present/available status. Enumerated value.", "uint16", "bitfield16", "", "Dieses Bitfeld zeigt den Status des Wechselrichter an: Bit 0: Verbunden, Bit 1: Ansprechbar, Bit 2: Arbeitet (Wechselrichter speist ein) / Bit 0: Connected, Bit 1: Available, Bit 2: Operating, Bit 3: Test"),
							array(40195, 1, "R", "0x03", "StorConn - storage inverter status", "Storage inverter present/available status. Enumerated value.", "uint16", "bitfield16", "", "bit 0: CONNECTED, bit 1: AVAILABLE, bit 2: OPERATING, bit 3: TEST"),
							array(40196, 1, "R", "0x03", "ECPConn - electrical connection point status", "Electrical Connection Point connection status: disconnected=0  connected=1. / Dieses Register zeigt den Verbindungsstatus zum Netz an: ECPConn = 1: Wechselrichter speist gerade ein, ECPConn = 0: Wechselrichter speist nicht ein", "uint16", "bitfield16", "", "0: Disconnected, 1: Connected"),
							array(40197, 4, "R", "0x03", "ActWh - AC energy output", "AC lifetime active (real) energy output. / Wirkenergiezähler", "acc64", "Wh", "", ""),
/*							array(40201, 4, "R", "0x03", "ActVAh", "AC lifetime apparent energy output.", "acc64", "VAh", "", "Not supported"),
							array(40205, 4, "R", "0x03", "ActVArhQ1", "AC lifetime reactive energy output in quadrant 1.", "acc64", "varh", "", "Not supported"),
							array(40209, 4, "R", "0x03", "ActVArhQ2", "AC lifetime reactive energy output in quadrant 2.", "acc64", "varh", "", "Not supported"),
							array(40213, 4, "R", "0x03", "ActVArhQ3", "AC lifetime negative energy output in quadrant 3.", "acc64", "varh", "", "Not supported"),
							array(40217, 4, "R", "0x03", "ActVArhQ4", "AC lifetime reactive energy output in quadrant 4.", "acc64", "varh", "", "Not supported"),
							array(40221, 1, "R", "0x03", "VArAval", "Amount of VARs available without impacting watts output.", "int16", "var", "VArAval_SF", "Not supported"),
							array(40222, 1, "R", "0x03", "VArAval_SF", "Scale factor for available VARs.", "sunssf", "", "", "Not supported"),
							array(40223, 1, "R", "0x03", "WAval", "Amount of Watts available.", "uint16", "W", "WAval_SF", "Not supported"),
							array(40224, 1, "R", "0x03", "WAval_SF", "Scale factor for available Watts.", "sunssf", "", "", "Not supported"),
							array(40225, 2, "R", "0x03", "StSetLimMsk", "Bit Mask indicating setpoint limit(s) reached. Bits are persistent and must be cleared by the controller.", "uint32", "bitfield32", "", "Not supported"),
*/							array(40227, 2, "R", "0x03", "StActCtl - inverter controls", "Bit Mask indicating which inverter controls are currently active. / Bitfeld für zurzeit aktive Wechselrichter-Modi: Bit 0: Leistungsreduktion (FixedW; entspricht WMaxLimPct Vorgabe), Bit 1: konstante Blindleistungs-Vorgabe (FixedVAR; entspricht VArMaxPct), Bit 2: Vorgabe eines konstanten Power Factors (FixedPF; entspricht OutPFSet)", "uint32", "bitfield32", "", "Bit 0: FixedW, Bit 1: FixedVAR, Bit 2: FixedPF"),
//							array(40229, 4, "R", "0x03", "TmSrc", "Source of time synchronization. / Quelle für die Zeitsynchronisation. Das Register liefert den String „RTC“ zurück.", "String8", "", "", "RTC"),
							array(40233, 2, "R", "0x03", "Tms - time", "Seconds since 01-01-2000 00:00 UTC / Sekunden vom 1. Januar 2000 00:00 (UTC) bis zur aktuellen Zeit", "uint32", "", "", ""),
/*							array(40235, 1, "R", "0x03", "RtSt", "Bit Mask indicating which voltage ride through modes are currently active.", "uint16", "bitfield16", "", "Not supported"),
							array(40236, 1, "R", "0x03", "Ris", "Isolation resistance", "uint16", "Ohm", "Ris_SF", "Not supported"),
							array(40237, 1, "R", "0x03", "Ris_SF", "Scale factor for Isolation resistance", "int16", "", "", "Not supported"),
*/						);

						if (false === $categoryId)
						{
							$categoryId = IPS_CreateCategory();
							IPS_SetIdent($categoryId, $this->removeInvalidChars($categoryName));
							IPS_SetName($categoryId, $categoryName);
							IPS_SetParent($categoryId, $parentId);
							IPS_SetInfo($categoryId, "Dieses Modell liefert einige zusätzliche Mess- und Statuswerte, die das normale Inverter Model nicht abdeckt.");
						}

						$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);


						// Inverter - Bit 0 - 3 für "PVConn" erstellen
						$instanceId = IPS_GetObjectIDByIdent("40194", $categoryId);
						$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
						IPS_SetHidden($varId, true);

						$bitArray = array(
							array('varName' => "Connected", 'varProfile' => "~Switch", 'varInfo' => "Verbunden"),
							array('varName' => "Responsive", 'varProfile' => "~Switch", 'varInfo' => "Ansprechbar"),
							array('varName' => "Operating", 'varProfile' => "~Switch", 'varInfo' => "Arbeitet (Wechselrichter speist ein)"),
							array('varName' => "Testing", 'varProfile' => "~Switch", 'varInfo' => "Test"),
						);

						foreach ($bitArray as $bit)
						{
							$varId = $this->MaintainInstanceVariable($this->removeInvalidChars($bit['varName']), $bit['varName'], VARIABLETYPE_BOOLEAN, $bit['varProfile'], 0, true, $instanceId, $bit['varInfo']);
						}

						// Inverter - Bit 0 - 3 für "StorConn" erstellen
						$instanceId = IPS_GetObjectIDByIdent("40195", $categoryId);
						$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
						IPS_SetHidden($varId, true);

						$bitArray = array(
							array('varName' => "Connected", 'varProfile' => "~Switch", 'varInfo' => "Verbunden"),
							array('varName' => "Responsive", 'varProfile' => "~Switch", 'varInfo' => "Ansprechbar"),
							array('varName' => "Operating", 'varProfile' => "~Switch", 'varInfo' => "Arbeitet (Wechselrichter lädt)"),
							array('varName' => "Testing", 'varProfile' => "~Switch", 'varInfo' => "Test"),
						);

						foreach ($bitArray as $bit)
						{
							$varId = $this->MaintainInstanceVariable($this->removeInvalidChars($bit['varName']), $bit['varName'], VARIABLETYPE_BOOLEAN, $bit['varProfile'], 0, true, $instanceId, $bit['varInfo']);
						}


						// Inverter - Bit 0 - 2 für "StActCtl" erstellen
						$instanceId = IPS_GetObjectIDByIdent("40227", $categoryId);
						$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
						IPS_SetHidden($varId, true);

						$bitArray = array(
							array('varName' => "FixedW - Leistungsreduktion", 'varProfile' => "~Switch", 'varInfo' => "Power reduction (FixedW; corresponds to WMaxLimPct specification) / Leistungsreduktion (FixedW; entspricht WMaxLimPct Vorgabe)"),
							array('varName' => "FixedVAR - konstante Blindleistungs-Vorgabe", 'varProfile' => "~Switch", 'varInfo' => "Constant reactive power specification (FixedVAR; corresponds to VArMaxPct) / konstante Blindleistungs-Vorgabe (FixedVAR; entspricht VArMaxPct)"),
							array('varName' => "FixedPF - konstanter Power-Factor", 'varProfile' => "~Switch", 'varInfo' => "Setting a constant power factor (FixedPF; corresponds to OutPFSet) / Vorgabe eines konstanten Power Factors (FixedPF; entspricht OutPFSet)"),
						);

						foreach ($bitArray as $bit)
						{
							$varId = $this->MaintainInstanceVariable($this->removeInvalidChars($bit['varName']), $bit['varName'], VARIABLETYPE_BOOLEAN, $bit['varProfile'], 0, true, $instanceId, $bit['varInfo']);
						}

						// Inverter - UTC für "Tms" erstellen
						$instanceId = IPS_GetObjectIDByIdent("40233", $categoryId);
						$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
						IPS_SetHidden($varId, true);

						$varId = $this->MaintainInstanceVariable($this->removeInvalidChars("UTC"), "UTC", VARIABLETYPE_INTEGER, "~UnixTimestamp", 0, true, $instanceId, "UTC time");
						
//						$this->SetTimerInterval("Update-IC122", 5000);
					}
					else
					{
						$this->SetTimerInterval("Update-IC122", 0);

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

	
					$categoryName = $inverterCategoryArray['IC123'];
					$categoryId = @IPS_GetObjectIDByIdent($this->removeInvalidChars($categoryName), $parentId);
					if ($readIC123)
					{
						$inverterModelRegister_array = array(
							/******* Immediate Controls Model (IC123)
							Allgemeines:
							Mit den Immediate Controls können folgende Einstellungen am Wechselrichter vorgenommen werden:
							- Unterbrechung des Einspeisebetriebs des Wechselrichters (Standby)
							- Konstante Reduktion der Ausgangsleistung
							- Vorgabe eines konstanten Power Factors
							- Vorgabe einer konstanten relativen Blindleistung */
//							array(40238, 1, "R", "0x03", "ID", "A well-known value 123.  Uniquely identifies this as a SunSpec Immediate Controls Model", "uint16", "", "", "123"),
//							array(40239, 1, "R", "0x03", "L", "Registers, Length of Immediate Controls Model", "uint16", "", "", "24"),
							array(40240, 1, "RW", "0x03 0x06 0x10", "Conn_WinTms", "Time window for connect/disconnect.", "uint16", "Secs", "", ""),
							array(40241, 1, "RW", "0x03 0x06 0x10", "Conn_RvrtTms", "Timeout period for connect/disconnect.", "uint16", "Secs", "", ""),
							array(40242, 1, "RW", "0x03 0x06 0x10", "Conn", "Enumerated valued.  Connection control.", "uint16", "bitfield16", "", "0: Disconnected, 1: Connected"),
							array(40243, 1, "RW", "0x03 0x06 0x10", "WMaxLimPct", "Set power output to specified level. (% WMax)", "uint16", "", "WMaxLimPct_SF", ""),
							array(40244, 1, "RW", "0x03 0x06 0x10", "WMaxLimPct_WinTms", "Time window for power limit change.", "uint16", "Secs", "", "0 – 300"),
							array(40245, 1, "RW", "0x03 0x06 0x10", "WMaxLimPct_RvrtTms", "Timeout period for power limit.", "uint16", "Secs", "", "0 – 28800"),
							array(40246, 1, "RW", "0x03", "WMaxLimPct_RmpTms", "Ramp time for moving from current setpoint to new setpoint.", "uint16", "Secs", "", "0 - 65534 (0xFFFF has the same effect as 0x0000)"),
							array(40247, 1, "RW", "0x03 0x06 0x10", "WMaxLim_Ena", "Enumerated valued.  Throttle enable/disable control.", "enum16", "", "", "0: Disabled, 1: Enabled"),
							array(40248, 1, "RW", "0x03 0x06 0x10", "OutPFSet", "Set power factor to specific value - cosine of angle.", "int16", "", "OutPFSet_SF", ""),
							array(40249, 1, "RW", "0x03 0x06 0x10", "OutPFSet_WinTms", "Time window for power factor change.", "uint16", "Secs", "", "0 – 300"),
							array(40250, 1, "RW", "0x03 0x06 0x10", "OutPFSet_RvrtTms", "Timeout period for power factor.", "uint16", "Secs", "", "0 – 28800"),
							array(40251, 1, "RW", "0x03 0x06 0x10", "OutPFSet_RmpTms", "Ramp time for moving from current setpoint to new setpoint.", "uint16", "Secs", "", "0 - 65534 (0xFFFF has the same effect as 0x0000)"),
							array(40252, 1, "RW", "0x03 0x06 0x10", "OutPFSet_Ena", "Enumerated valued.  Fixed power factor enable/disable control.", "enum16", "", "", "0: Disabled, 1: Enabled"),
//							array(40253, 1, "R", "0x03", "VArWMaxPct", "Reactive power in percent of I_WMax. (% WMax)", "int16", "%", "VArWMaxPct_SF", "Not supported"),
							array(40254, 1, "RW", "0x03 0x06 0x10", "VArMaxPct", "Reactive power in percent of I_VArMax. (% VArMax)", "int16", "%", "VArPct_SF", ""),
//							array(40255, 1, "R", "0x03", "VArAvalPct", "Reactive power in percent of I_VArAval. (% VArAval)", "int16", "%", "VArPct_SF", "Not supported"),
							array(40256, 1, "RW", "0x03 0x06 0x10", "VArPct_WinTms", "Time window for VAR limit change.", "uint16", "Secs", "", "0 – 300"),
							array(40257, 1, "RW", "0x03 0x06 0x10", "VArPct_RvrtTms", "Timeout period for VAR limit.", "uint16", "Secs", "", "0 – 28800"),
							array(40258, 1, "RW", "0x03 0x06 0x10", "VArPct_RmpTms", "Ramp time for moving from current setpoint to new setpoint.", "uint16", "Secs", "", "0 - 65534 (0xFFFF has the same effect as 0x0000)"),
							array(40259, 1, "R", "0x03", "VArPct_Mod", "Enumerated value. VAR limit mode.", "enum16", "", "", "2: VAR limit as a % of VArMax"),
							array(40260, 1, "RW", "0x03 0x06 0x10", "VArPct_Ena", "Enumerated valued.  Fixed VAR enable/disable control.", "enum16", "", "", "0: Disabled, 1: Enabled"),
							array(40261, 1, "R", "0x03", "WMaxLimPct_SF", "Scale factor for power output percent.", "sunssf", "", "", "-2"),
							array(40262, 1, "R", "0x03", "OutPFSet_SF", "Scale factor for power factor.", "sunssf", "", "", "-3"),
//							array(40263, 1, "R", "0x03", "VArPct_SF", "Scale factor for reactive power.", "sunssf", "", "", "0"),
						);

						if (false === $categoryId)
						{
							$categoryId = IPS_CreateCategory();
							IPS_SetIdent($categoryId, $this->removeInvalidChars($categoryName));
							IPS_SetName($categoryId, $categoryName);
							IPS_SetParent($categoryId, $parentId);
							IPS_SetInfo($categoryId, "Mit den Immediate Controls können folgende Einstellungen am Wechselrichter vorgenommen werden:
- Unterbrechung des Einspeisebetriebs des Wechselrichters (Standby)
- Konstante Reduktion der Ausgangsleistung
- Vorgabe eines konstanten Power Factors
- Vorgabe einer konstanten relativen Blindleistung");
						}

						$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);


						// Inverter - "SF" Variablen erstellen
						$inverterModelRegister_array = array(
							array(40243, 1, "RW", "0x03 0x06 0x10", "WMaxLimPct", "Set power output to specified level. (% WMax)", "uint16", "%", 40261),
							array(40248, 1, "RW", "0x03 0x06 0x10", "OutPFSet", "Set power factor to specific value - cosine of angle.", "int16", "cos()", 40262),
						);

						foreach($inverterModelRegister_array AS $inverterModelRegister)
						{
							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_START_REGISTER], $categoryId);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
							
							$dataType = 7;
							$profile = $this->getProfile($inverterModelRegister[IMR_UNITS], $dataType);

							$varId = $this->MaintainInstanceVariable("Value_SF", IPS_GetName($instanceId), VARIABLETYPE_FLOAT, $profile, 0, true, $instanceId, $inverterModelRegister[IMR_DESCRIPTION]);

							if("cos()" == $inverterModelRegister[IMR_UNITS])
							{
								$varId = $this->MaintainInstanceVariable("Value_cos", IPS_GetName($instanceId)."_cos", VARIABLETYPE_FLOAT, "", 0, true, $instanceId, $inverterModelRegister[IMR_DESCRIPTION]);
							}

							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_UNITS + 1], $categoryId);
							IPS_SetHidden($instanceId, true);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
						}
					}
					else
					{
						$this->SetTimerInterval("Update-IC123", 0);

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

	
					$categoryName = $inverterCategoryArray['I160'];
					$categoryId = @IPS_GetObjectIDByIdent($this->removeInvalidChars($categoryName), $parentId);
					if ($readI160)
					{
						$inverterModelRegister_array = array(
							/******* Multiple MPPT Inverter Extension Model (I160)
							Allgemeines:
							Das Multiple MPPT Inverter Extension Model beinhaltet die Werte von bis zu zwei DC Eingängen
							des Wechselrichters.
							Verfügt der Wechselrichter über zwei DC Eingänge, so werden Strom, Spannung, Leistung,
							Energie und Statusmeldungen der einzelnen Eingänge hier aufgelistet. Im Inverter
							Model (101 -103 oder 111 - 113) wird in diesem Fall nur die gesamte DC Leistung beider
							Eingänge ausgegeben. DC Strom und DC Spannung werden als "not implemented" angezeigt.
							Sollte der Wechselrichter nur über einen DC Eingang verfügen, werden alle Werte des
							zweiten Strings auf "not implemented" gesetzt (ab Register 2_DCA). Die Bezeichnung des
							zweiten Eingangs (Register 2_IDStr) lautet in diesem Fall "Not supported". Die Werte des
							ersten (und einzigen) Eingangs werden normal angezeigt. */
//							array(40264, 1, "R", "0x03", "ID", "A well-known value 160.  Uniquely identifies this as a SunSpec Multiple MPPT Inverter Extension Model Mode", "uint16", "", "", "160"),
//							array(40265, 1, "R", "0x03", "L", "Length of Multiple MPPT Inverter Extension Model", "uint16", "", "", "48"),
							array(40266, 1, "R", "0x03", "DCA_SF", "Current Scale Factor", "sunssf", "", "", "SF=-2"),
							array(40267, 1, "R", "0x03", "DCV_SF", "Voltage Scale Factor", "sunssf", "", "", "SF=-2"),
							array(40268, 1, "R", "0x03", "DCW_SF", "Power Scale Factor", "sunssf", "", "", "SF=-1"),
							array(40269, 1, "R", "0x03", "DCWH_SF", "Energy Scale Factor", "sunssf", "", "", "SF=0"),
							array(40270, 2, "R", "0x03", "Evt", "Global Events", "uint32", "bitfield32", "", ""),
							array(40272, 1, "R", "0x03", "N", "Number of Modules", "uint16", "", "", "2"),
//							array(40273, 1, "R", "0x03", "TmsPer", "Timestamp Period", "uint16", "", "", "Not supported"),
//							array(40274, 1, "R", "0x03", "1_ID", "Input ID", "uint16", "", "", "1"),
//							array(40275, 8, "R", "0x03", "1_IDStr", "Input ID Sting", "String16", "", "", "'String 1'"),
							array(40283, 1, "R", "0x03", "1_DCA", "DC Current", "uint16", "", "DCA_SF", ""),
							array(40284, 1, "R", "0x03", "1_DCV", "DC Voltage", "uint16", "", "DCV_SF", ""),
							array(40285, 1, "R", "0x03", "1_DCW", "DC Power", "uint16", "", "DCW_SF", ""),
							array(40286, 2, "R", "0x03", "1_DCWH", "Lifetime Energy", "acc32", "", "DCWH_SF", ""),
							array(40288, 2, "R", "0x03", "1_Tms", "Timestamp", "uint32", "Secs", "", ""),
							array(40290, 1, "R", "0x03", "1_Tmp", "Temperature", "int16", "C", "", ""),
							array(40291, 1, "R", "0x03", "1_DCSt", "Operating State", "enum16", "Enumerated_St", "", ""),
							array(40292, 2, "R", "0x03", "1_DCEvt", "Module Events", "uint32", "bitfield32", "", ""),
//							array(40294, 1, "R", "0x03", "2_ID", "Input ID", "uint16", "", "", "2"),
//							array(40295, 8, "R", "0x03", "2_IDStr", "Input ID Sting", "String16", "", "", "'String 2' or 'Not supported'"),
							array(40303, 1, "R", "0x03", "2_DCA", "DC Current", "uint16", "", "DCA_SF", "Not supported if only one DC input."),
							array(40304, 1, "R", "0x03", "2_DCV", "DC Voltage", "uint16", "", "DCV_SF", "Not supported if only one DC input."),
							array(40305, 1, "R", "0x03", "2_DCW", "DC Power", "uint16", "", "DCW_SF", "Not supported if only one DC input."),
							array(40306, 2, "R", "0x03", "2_DCWH", "Lifetime Energy", "acc32", "", "DCWH_SF", "Not supported if only one DC input."),
							array(40308, 2, "R", "0x03", "2_Tms", "Timestamp", "uint32", "Secs", "", "Not supported if only one DC input."),
							array(40310, 1, "R", "0x03", "2_Tmp", "Temperature", "int16", "C", "", "Not supported if only one DC input."),
							array(40311, 1, "R", "0x03", "2_DCSt", "Operating State", "enum16", "Enumerated_St", "", "Not supported if only one DC input."),
							array(40312, 2, "R", "0x03", "2_DCEvt", "Module Events", "uint32", "bitfield32", "", "Not supported if only one DC input."),
						);

						if (false === $categoryId)
						{
							$categoryId = IPS_CreateCategory();
							IPS_SetIdent($categoryId, $this->removeInvalidChars($categoryName));
							IPS_SetName($categoryId, $categoryName);
							IPS_SetParent($categoryId, $parentId);
							IPS_SetInfo($categoryId, "Das Multiple MPPT Inverter Extension Model beinhaltet die Werte von bis zu zwei DC Eingängen des Wechselrichters.
Verfügt der Wechselrichter über zwei DC Eingänge, so werden Strom, Spannung, Leistung, Energie und Statusmeldungen der einzelnen Eingänge hier aufgelistet. Im Inverter Model (101 -103 oder 111 - 113) wird in diesem Fall nur die gesamte DC Leistung beider Eingänge ausgegeben. DC Strom und DC Spannung werden als 'not implemented' angezeigt.
Sollte der Wechselrichter nur über einen DC Eingang verfügen, werden alle Werte des zweiten Strings auf 'not implemented' gesetzt (ab Register 2_DCA). Die Bezeichnung des zweiten Eingangs (Register 2_IDStr) lautet in diesem Fall 'Not supported'. Die Werte des ersten (und einzigen) Eingangs werden normal angezeigt.");
						}

						$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);


						// Inverter - "SF" Variablen erstellen
						$inverterModelRegister_array = array(
							array(40283, 1, "R", "0x03", "1_DCA", "DC Current", "uint16", "A", 40266),
							array(40284, 1, "R", "0x03", "1_DCV", "DC Voltage", "uint16", "V", 40267),
							array(40285, 1, "R", "0x03", "1_DCW", "DC Power", "uint16", "W", 40268),
							array(40286, 2, "R", "0x03", "1_DCWH", "Lifetime Energy", "acc32", "Wh", 40269),
							array(40303, 1, "R", "0x03", "2_DCA", "DC Current", "uint16", "A", 40266),
							array(40304, 1, "R", "0x03", "2_DCV", "DC Voltage", "uint16", "V", 40267),
							array(40305, 1, "R", "0x03", "2_DCW", "DC Power", "uint16", "W", 40268),
							array(40306, 2, "R", "0x03", "2_DCWH", "Lifetime Energy", "acc32", "Wh", 40269),
						);

						foreach($inverterModelRegister_array AS $inverterModelRegister)
						{
							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_START_REGISTER], $categoryId);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
							
							$dataType = 7;
							$profile = $this->getProfile($inverterModelRegister[IMR_UNITS], $dataType);

							$varId = $this->MaintainInstanceVariable("Value_SF", IPS_GetName($instanceId), VARIABLETYPE_FLOAT, $profile, 0, true, $instanceId, $inverterModelRegister[IMR_DESCRIPTION]);

							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_UNITS + 1], $categoryId);
							IPS_SetHidden($instanceId, true);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
						}
					}
					else
					{
						$this->SetTimerInterval("Update-I160", 0);

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

	
					$categoryName = $inverterCategoryArray['IC124'];
					$categoryId = @IPS_GetObjectIDByIdent($this->removeInvalidChars($categoryName), $parentId);
					if ($readIC124)
					{
						$inverterModelRegister_array = array(
							/******* Basic Storage Control Model (IC124) ***
							Allgemeines:
							Dieses Model ist nur für Fronius Hybrid Wechselrichter verfügbar.
							Mit dem Basic Storage Control Model können folgende Einstellungen am Wechselrichter
							vorgenommen werden:
							- Vorgabe eines Leistungsfensters, in dem sich die Lade-/Entladeleistung vom Energiespeicher bewegen soll.
							- Vorgabe eines minimalen Ladestandes, den der Energiespeicher nicht unterschreiten soll
							- Ladung des Energiespeichers vom Netz erlauben/verbieten */
//							array(40314, 1, "R", "0x03", "ID", "A well-known value 124.  Uniquely identifies this as a SunSpec Basic Storage Controls Model", "uint16", "", "", "124"),
//							array(40315, 1, "R", "0x03", "L", "Registers, Length of Basic Storage Controls", "uint16", "", "", "24"),
							array(40316, 1, "R", "0x03", "WchaMax", "Setpoint for maximum charge. Additional Fronius description: Reference Value for maximum Charge and Discharge. Multiply this value by InWRte to define maximum charging and OutWRte to define maximum discharging. Every rate between this two limits is allowed. Note that  InWRte and OutWRte can be negative to define ranges for charging and discharging only.", "uint16", "", "WChaMax_SF", ""),
							array(40317, 1, "R", "0x03", "WchaGra", "Setpoint for maximum charging rate. Default is MaxChaRte. (% WChaMax/sec)", "uint16", "", "WChaDisChaGra_SF", "100"),
							array(40318, 1, "R", "0x03", "WdisChaGra", "Setpoint for maximum discharge rate. Default is MaxDisChaRte. (% WChaMax/sec)", "uint16", "", "WChaDisChaGra_SF", "100"),
							array(40319, 1, "RW", "0x03 0x06 0x10", "StorCtl_Mod", "Activate hold/discharge/charge storage control mode. Bitfield value. Additional Fronius description: Active hold/discharge/charge storage control mode. Set the charge field to enable charging and the discharge field to enable discharging. Bitfield value.", "uint16", "bitfield16", "", "bit 0: CHARGE, bit 1: DiSCHARGE"),
//							array(40320, 1, "R", "0x03", "VAChaMax", "Setpoint for maximum charging VA.", "uint16", "VA", "VAChaMax_SF", "Not supported"),
							array(40321, 1, "RW", "0x03 0x06 0x10", "MinRsvPct", "Setpoint for minimum reserve for storage as a percentage of the nominal maximum storage. (% WChaMax)", "uint16", "", "MinRsvPct_SF", ""),
							array(40322, 1, "R", "0x03", "ChaState", "Currently available energy as a percent of the capacity rating. (% AhrRtg)", "uint16", "", "ChaState_SF", ""),
							array(40323, 1, "R", "0x03", "StorAval", "State of charge (ChaState) minus storage reserve (MinRsvPct) times capacity rating (AhrRtg).", "uint16", "", "StorAval_SF", ""),
							array(40324, 1, "R", "0x03", "InBatV", "Internal battery voltage.", "uint16", "", "InBatV_SF", ""),
							array(40325, 1, "R", "0x03", "ChaSt", "Charge status of storage device. Enumerated value.", "enum16", "Enumerated_ChaSt", "", "1: OFF, 2: EMPTY, 3: DISCHARGING, 4: CHARGING, 5: FULL, 6: HOLDING, 7: TESTING"),
							array(40326, 1, "RW", "0x03 0x06 0x10", "OutWRte", "Percent of max discharge rate. Additional Fronius description: Defines maximum Discharge rate. If not used than the default is 100 and wChaMax defines max. Discharge rate. See wChaMax for details. (% WChaMax)", "int16", "", "InOutWRte_SF", ""),
							array(40327, 1, "RW", "0x03 0x06 0x10", "InWRte", "Percent of max charging rate. Additional Fronius description: Defines maximum Charge rate. If not used than the default is 100 and wChaMax defines max. Charge rate. See wChaMax for details. (% WChaMax)", "int16", "", "InOutWRte_SF", ""),
//							array(40328, 1, "R", "0x03", "InOutWRte_WinTms", "Time window for charge/discharge rate change.", "uint16", "Secs", "", "Not supported"),
//							array(40329, 1, "R", "0x03", "InOutWRte_RvrtTms", "Timeout period for charge/discharge rate.", "uint16", "Secs", "", "Not supported"),
//							array(40330, 1, "R", "0x03", "InOutWRte_RmpTms", "Ramp time for moving from current setpoint to new setpoint.", "uint16", "Secs", "", "Not supported"),
							array(40331, 1, "RW", "0x03 0x06 0x10", "ChaGriSet", "Setpoint to enable/disable charging from grid", "enum16", "", "", "0: PV (Charging from grid disabled), 1: GRID (Charging from grid enabled)"),
							array(40332, 1, "R", "0x03", "WchaMax_SF", "Scale factor for maximum charge.", "sunssf", "", "", "0"),
							array(40333, 1, "R", "0x03", "WchaDisChaGra_SF", "Scale factor for maximum charge and discharge rate.", "sunssf", "", "", "0"),
//							array(40334, 1, "R", "0x03", "VAChaMax_SF", "Scale factor for maximum charging VA.", "sunssf", "", "", "Not supported"),
							array(40335, 1, "R", "0x03", "MinRsvPct_SF", "Scale factor for minimum reserve percentage.", "sunssf", "", "", "-2"),
							array(40336, 1, "R", "0x03", "ChaState_SF", "Scale factor for available energy percent.", "sunssf", "", "", "-2"),
							array(40337, 1, "R", "0x03", "StorAval_SF", "Scale factor for state of charge.", "sunssf", "", "", "-2"),
							array(40338, 1, "R", "0x03", "InBatV_SF", "Scale factor for battery voltage.", "sunssf", "", "", "-2"),
							array(40339, 1, "R", "0x03", "InOutWRte_SF", "Scale factor for percent charge/discharge rate.", "sunssf", "", "", "-2"),
//							array(40340, 1, "R", "0x03", "ID", "Identifies this as End block", "uint16", "", "", "0xFFFF"),
//							array(40341, 1, "R", "0x03", "L", "Registers, Length of model block", "uint16", "", "", "0"),
						);

						if (false === $categoryId)
						{
							$categoryId = IPS_CreateCategory();
							IPS_SetIdent($categoryId, $this->removeInvalidChars($categoryName));
							IPS_SetName($categoryId, $categoryName);
							IPS_SetParent($categoryId, $parentId);
							IPS_SetInfo($categoryId, "Dieses Model ist nur für Fronius Hybrid Wechselrichter verfügbar.
Mit dem Basic Storage Control Model können folgende Einstellungen am Wechselrichter vorgenommen werden:
- Vorgabe eines Leistungsfensters, in dem sich die Lade-/Entladeleistung vom Energiespeicher bewegen soll.
- Vorgabe eines minimalen Ladestandes, den der Energiespeicher nicht unterschreiten soll
- Ladung des Energiespeichers vom Netz erlauben/verbieten");
						}

						$this->createModbusInstances($inverterModelRegister_array, $categoryId, $gatewayId, $pollCycle);


						// Inverter - "SF" Variablen erstellen
						$inverterModelRegister_array = array(
							array(40316, 1, "R", "0x03", "WchaMax", "Setpoint for maximum charge. Additional Fronius description: Reference Value for maximum Charge and Discharge. Multiply this value by InWRte to define maximum charging and OutWRte to define maximum discharging. Every rate between this two limits is allowed. Note that  InWRte and OutWRte can be negative to define ranges for charging and discharging only.", "uint16", "W", 40332),
							array(40317, 1, "R", "0x03", "WchaGra", "Setpoint for maximum charging rate. Default is MaxChaRte. (% WChaMax/sec)", "uint16", "%", 40333),
							array(40318, 1, "R", "0x03", "WdisChaGra", "Setpoint for maximum discharge rate. Default is MaxDisChaRte. (% WChaMax/sec)", "uint16", "%", 40333),
							array(40321, 1, "RW", "0x03 0x06 0x10", "MinRsvPct", "Setpoint for minimum reserve for storage as a percentage of the nominal maximum storage. (% WChaMax)", "uint16", "%", 40335),
							array(40322, 1, "R", "0x03", "ChaState", "Currently available energy as a percent of the capacity rating. (% AhrRtg)", "uint16", "%", 40336),
							array(40323, 1, "R", "0x03", "StorAval", "State of charge (ChaState) minus storage reserve (MinRsvPct) times capacity rating (AhrRtg).", "uint16", "AH", 40337),
							array(40324, 1, "R", "0x03", "InBatV", "Internal battery voltage.", "uint16", "V", 40338),
							array(40326, 1, "RW", "0x03 0x06 0x10", "OutWRte", "Percent of max discharge rate. Additional Fronius description: Defines maximum Discharge rate. If not used than the default is 100 and wChaMax defines max. Discharge rate. See wChaMax for details. (% WChaMax)", "int16", "%", 40339),
							array(40327, 1, "RW", "0x03 0x06 0x10", "InWRte", "Percent of max charging rate. Additional Fronius description: Defines maximum Charge rate. If not used than the default is 100 and wChaMax defines max. Charge rate. See wChaMax for details. (% WChaMax)", "int16", "%", 40339),
						);

						foreach($inverterModelRegister_array AS $inverterModelRegister)
						{
							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_START_REGISTER], $categoryId);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
							
							$dataType = 7;
							$profile = $this->getProfile($inverterModelRegister[IMR_UNITS], $dataType);

							$varId = $this->MaintainInstanceVariable("Value_SF", IPS_GetName($instanceId), VARIABLETYPE_FLOAT, $profile, 0, true, $instanceId, $inverterModelRegister[IMR_DESCRIPTION]);

							$instanceId = IPS_GetObjectIDByIdent($inverterModelRegister[IMR_UNITS + 1], $categoryId);
							IPS_SetHidden($instanceId, true);
							$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
							IPS_SetHidden($varId, true);
						}
					}
					else
					{
						$this->SetTimerInterval("Update-IC124", 0);

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
				/*** SmartMeter ***/
				else
				{
					// Inverter - Timer deaktivieren
					$this->SetTimerInterval("Update-I11X", 0);
					$this->SetTimerInterval("Update-IC120", 0);
					$this->SetTimerInterval("Update-IC121", 0);
					$this->SetTimerInterval("Update-IC122", 0);
					$this->SetTimerInterval("Update-IC123", 0);
					$this->SetTimerInterval("Update-IC124", 0);
					$this->SetTimerInterval("Update-I160", 0);

					// Inverter spezifische Funktionen deaktivieren
/* verursacht aktuell noch Probleme, da der ClientSocket erstellt/gelöscht wird!
					if(true == IPS_GetProperty($parentId, "readIC120"))
					{
						IPS_SetProperty($parentId, "readIC120", false);
						IPS_ApplyChanges($parentId);
					}
*/

					// Inverter - Kategorien löschen
					foreach ($inverterCategoryArray as $categoryName)
					{
						$categoryId = @IPS_GetObjectIDByIdent($this->removeInvalidChars($categoryName), $parentId);
						if (false !== $categoryId)
						{
							foreach (IPS_GetChildrenIDs($categoryId) as $childId)
							{
								foreach (IPS_GetChildrenIDs($childId) as $childChildId)
								{
									IPS_DeleteVariable($childChildId);
								}
								IPS_DeleteInstance($childId);
							}
							IPS_DeleteCategory($categoryId);
						}
					}

					$categoryId = $parentId;
					$this->deleteModbusInstancesRecursive($inverterModelRegister_array, $categoryId);
					$this->deleteModbusInstancesRecursive($inverterModel3pRegister_array, $categoryId);
					$this->createModbusInstances($meterModelRegister_array, $categoryId, $gatewayId, $pollCycle, "SmartMeter");


					// SmartMeter - Bit 0 - 18 für "Evt - Events" erstellen
					$instanceId = IPS_GetObjectIDByIdent("40194"."SmartMeter", $categoryId);
					$varId = IPS_GetObjectIDByIdent("Value", $instanceId);
					IPS_SetHidden($varId, true);

					$bitArray = array(
						array('varName' => "LOW_VOLTAGE", 'varProfile' => "~Alert", 'varInfo' => "LOW_VOLTAGE 0x00000001"),
						array('varName' => "LOW_POWER", 'varProfile' => "~Alert", 'varInfo' => "LOW_POWER 0x00000002"),
						array('varName' => "LOW_EFFICIENCY", 'varProfile' => "~Alert", 'varInfo' => "LOW_EFFICIENCY 0x00000004"),
						array('varName' => "CURRENT", 'varProfile' => "~Alert", 'varInfo' => "CURRENT 0x00000008"),
						array('varName' => "VOLTAGE", 'varProfile' => "~Alert", 'varInfo' => "VOLTAGE 0x00000010"),
						array('varName' => "POWER", 'varProfile' => "~Alert", 'varInfo' => "POWER 0x00000020"),
						array('varName' => "PR", 'varProfile' => "~Alert", 'varInfo' => "PR 0x00000040"),
						array('varName' => "DISCONNECTED", 'varProfile' => "~Alert", 'varInfo' => "DISCONNECTED 0x00000080"),
						array('varName' => "FUSE_FAULT", 'varProfile' => "~Alert", 'varInfo' => "FUSE_FAULT 0x00000100"),
						array('varName' => "COMBINER_FUSE_FAULT", 'varProfile' => "~Alert", 'varInfo' => "COMBINER_FUSE_FAULT 0x00000200"),
						array('varName' => "COMBINER_CABINET_OPEN", 'varProfile' => "~Alert", 'varInfo' => "COMBINER_CABINET_OPEN 0x00000400"),
						array('varName' => "TEMP", 'varProfile' => "~Alert", 'varInfo' => "TEMP 0x00000800"),
						array('varName' => "GROUNDFAULT", 'varProfile' => "~Alert", 'varInfo' => "GROUNDFAULT 0x00001000"),
						array('varName' => "REVERSED_POLARITY", 'varProfile' => "~Alert", 'varInfo' => "REVERSED_POLARITY 0x00002000"),
						array('varName' => "INCOMPATIBLE", 'varProfile' => "~Alert", 'varInfo' => "INCOMPATIBLE 0x00004000"),
						array('varName' => "COMM_ERROR", 'varProfile' => "~Alert", 'varInfo' => "COMM_ERROR 0x00008000"),
						array('varName' => "INTERNAL_ERROR", 'varProfile' => "~Alert", 'varInfo' => "INTERNAL_ERROR 0x00010000"),
						array('varName' => "THEFT", 'varProfile' => "~Alert", 'varInfo' => "THEFT 0x00020000"),
						array('varName' => "ARC_DETECTED", 'varProfile' => "~Alert", 'varInfo' => "ARC_DETECTED 0x00040000"),
					);

					foreach ($bitArray as $bit)
					{
						$varId = $this->MaintainInstanceVariable($this->removeInvalidChars($bit['varName']), $bit['varName'], VARIABLETYPE_BOOLEAN, $bit['varProfile'], 0, true, $instanceId, $bit['varInfo']);
					}

				}


				if($active)
				{
					/*** Wechselrichter / Inverter ***/
					if (!$readSmartmeter)
					{
						// Inverter - Timer aktivieren
						$this->SetTimerInterval("Update-I11X", 5000);

						if ($readIC120)
						{
							$this->SetTimerInterval("Update-IC120", 5000);
						}
						if ($readIC121)
						{
							$this->SetTimerInterval("Update-IC121", 5000);
						}
						if ($readIC122)
						{
							$this->SetTimerInterval("Update-IC122", 5000);
						}
						if ($readIC123)
						{
							$this->SetTimerInterval("Update-IC123", 5000);
						}
						if ($readIC124)
						{
							$this->SetTimerInterval("Update-IC124", 5000);
						}
						if ($readI160)
						{
							$this->SetTimerInterval("Update-I160", 5000);
						}
					}
					else
					{
						// SmartMeter - Timer aktivieren
						$this->SetTimerInterval("SM_Update-Evt", 5000);
					}

					// Erreichbarkeit von IP und Port pruefen
					$portOpen = false;
					$waitTimeoutInSeconds = 1; 
					if($fp = @fsockopen($hostIp, $hostPort, $errCode, $errStr, $waitTimeoutInSeconds))
					{   
						// It worked
						$portOpen = true;
						fclose($fp);

						// Client Soket aktivieren
						if (false == IPS_GetProperty($interfaceId, "Open"))
						{
							IPS_SetProperty($interfaceId, "Open", true);
							IPS_ApplyChanges($interfaceId);
							//IPS_Sleep(100);
						}
						
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
					if (true == IPS_GetProperty($interfaceId, "Open"))
					{
						IPS_SetProperty($interfaceId, "Open", false);
						IPS_ApplyChanges($interfaceId);
						//IPS_Sleep(100);
					}
					
					// Inverter - Timer deaktivieren
					$this->SetTimerInterval("Update-I11X", 0);
					$this->SetTimerInterval("Update-IC120", 0);
					$this->SetTimerInterval("Update-IC121", 0);
					$this->SetTimerInterval("Update-IC122", 0);
					$this->SetTimerInterval("Update-IC123", 0);
					$this->SetTimerInterval("Update-IC124", 0);
					$this->SetTimerInterval("Update-I160", 0);

					// SmartMeter - Timer deaktivieren
					$this->SetTimerInterval("SM_Update-Evt", 0);

					// inaktiv
					$this->SetStatus(104);
				}


				// pruefen, ob sich ModBus-Gateway geaendert hat
				if(0 != $gatewayId_Old && $gatewayId != $gatewayId_Old)
				{
					$this->deleteInstanceNotInUse($gatewayId_Old, MODBUS_ADDRESSES);
				}

				// pruefen, ob sich ClientSocket Interface geaendert hat
				if(0 != $interfaceId_Old && $interfaceId != $interfaceId_Old)
				{
					$this->deleteInstanceNotInUse($interfaceId_Old, MODBUS_INSTANCES);
				}
			}
			else
			{
				// keine IP --> inaktiv
				$this->SetStatus(104);
			}
		}

		private function createModbusInstances($inverterModelRegister_array, $parentId, $gatewayId, $pollCycle, $uniqueIdent = "")
		{
			// Workaround für "InstanceInterface not available" Fehlermeldung beim Server-Start...
			if (KR_READY == IPS_GetKernelRunlevel())
			{
				// Erstelle Modbus Instancen
				foreach ($inverterModelRegister_array as $inverterModelRegister)
				{
					if (DEBUG)
					{
						echo "REG_".$inverterModelRegister[IMR_START_REGISTER]." - ".$inverterModelRegister[IMR_NAME]."\n";
					}

					$datenTyp = $this->getModbusDatatype($inverterModelRegister[IMR_TYPE]);
					if("continue" == $datenTyp)
					{
						continue;
					}

					$profile = $this->getProfile($inverterModelRegister[IMR_UNITS], $datenTyp);

					$instanceId = @IPS_GetObjectIDByIdent($inverterModelRegister[IMR_START_REGISTER].$uniqueIdent, $parentId);
					$initialCreation = false;

					// Modbus-Instanz erstellen, sofern noch nicht vorhanden
					if (false === $instanceId)
					{
						$instanceId = IPS_CreateInstance(MODBUS_ADDRESSES);

						IPS_SetParent($instanceId, $parentId);
						IPS_SetIdent($instanceId, $inverterModelRegister[IMR_START_REGISTER].$uniqueIdent);
						IPS_SetName($instanceId, $inverterModelRegister[IMR_NAME]);
						IPS_SetInfo($instanceId, $inverterModelRegister[IMR_DESCRIPTION]);

						$initialCreation = true;
					}

					// Gateway setzen
					if (IPS_GetInstance($instanceId)['ConnectionID'] != $gatewayId)
					{
						// sofern bereits eine Gateway verbunden ist, dieses trennen
						if (0 != IPS_GetInstance($instanceId)['ConnectionID'])
						{
							IPS_DisconnectInstance($instanceId);
						}

						// neues Gateway verbinden
						IPS_ConnectInstance($instanceId, $gatewayId);
					}


					// Modbus-Instanz konfigurieren
					if ($datenTyp != IPS_GetProperty($instanceId, "DataType"))
					{
						IPS_SetProperty($instanceId, "DataType", $datenTyp);
					}
					if (false != IPS_GetProperty($instanceId, "EmulateStatus"))
					{
						IPS_SetProperty($instanceId, "EmulateStatus", false);
					}
					if ($pollCycle != IPS_GetProperty($instanceId, "Poller"))
					{
						IPS_SetProperty($instanceId, "Poller", $pollCycle);
					}
/*
					if(0 != IPS_GetProperty($instanceId, "Factor"))
					{
						IPS_SetProperty($instanceId, "Factor", 0);
					}
*/

					// Read-Settings
					if ($inverterModelRegister[IMR_START_REGISTER] + MODBUS_REGISTER_TO_ADDRESS_OFFSET != IPS_GetProperty($instanceId, "ReadAddress"))
					{
						IPS_SetProperty($instanceId, "ReadAddress", $inverterModelRegister[IMR_START_REGISTER] + MODBUS_REGISTER_TO_ADDRESS_OFFSET);
					}
					if(6 == $inverterModelRegister[IMR_FUNCTION_CODE])
					{
						$ReadFunctionCode = 3;
					}
					else
					{
						$ReadFunctionCode = $inverterModelRegister[IMR_FUNCTION_CODE];
					}

					if ($ReadFunctionCode != IPS_GetProperty($instanceId, "ReadFunctionCode"))
					{
						IPS_SetProperty($instanceId, "ReadFunctionCode", $ReadFunctionCode);
					}

					// Write-Settings
					if (4 < $inverterModelRegister[IMR_FUNCTION_CODE] && $inverterModelRegister[IMR_FUNCTION_CODE] != IPS_GetProperty($instanceId, "WriteFunctionCode"))
					{
						IPS_SetProperty($instanceId, "WriteFunctionCode", $inverterModelRegister[IMR_FUNCTION_CODE]);
					}

					if (4 < $inverterModelRegister[IMR_FUNCTION_CODE] && $inverterModelRegister[IMR_START_REGISTER] + MODBUS_REGISTER_TO_ADDRESS_OFFSET != IPS_GetProperty($instanceId, "WriteAddress"))
					{
						IPS_SetProperty($instanceId, "WriteAddress", $inverterModelRegister[IMR_START_REGISTER] + MODBUS_REGISTER_TO_ADDRESS_OFFSET);
					}

					if (0 != IPS_GetProperty($instanceId, "WriteFunctionCode"))
					{
						IPS_SetProperty($instanceId, "WriteFunctionCode", 0);
					}

					if(IPS_HasChanges($instanceId))
					{
						IPS_ApplyChanges($instanceId);
					}

					// Statusvariable der Modbus-Instanz ermitteln
					$varId = IPS_GetObjectIDByIdent("Value", $instanceId);

					// Profil der Statusvariable initial einmal zuweisen
					if ($initialCreation && false != $profile)
					{
						// Justification Rule 11: es ist die Funktion RegisterVariable...() in diesem Fall nicht nutzbar, da die Variable durch die Modbus-Instanz bereits erstellt wurde
						// --> Custo Profil wird initial einmal beim Instanz-erstellen gesetzt
						IPS_SetVariableCustomProfile($varId, $profile);
					}
				}
			}
		}
		
		private function getModbusDatatype($type)
		{
			// Datentyp ermitteln
			// 0=Bit, 1=Byte, 2=Word, 3=DWord, 4=ShortInt, 5=SmallInt, 6=Integer, 7=Real
			if ("uint16" == strtolower($type)
				|| "enum16" == strtolower($type)
				|| "uint8+uint8" == strtolower($type)
			)
			{
				$datenTyp = 2;
			}
			elseif ("uint32" == strtolower($type)
				|| "acc32" == strtolower($type)
				|| "acc64" == strtolower($type)
			)
			{
				$datenTyp = 3;
			}
			elseif ("int16" == strtolower($type)
				|| "sunssf" == strtolower($type)
			)
			{
				$datenTyp = 4;
			}
			elseif ("int32" == strtolower($type))
			{
				$datenTyp = 6;
			}
			elseif ("float32" == strtolower($type))
			{
				$datenTyp = 7;
			}
			elseif ("uint64" == strtolower($type))
			{
				$datenTyp = 8;
			}
			elseif ("string32" == strtolower($type)
				|| "string16" == strtolower($type)
				|| "string8" == strtolower($type)
				|| "string" == strtolower($type)
			)
			{
				echo "Datentyp '".$type."' wird von Modbus in IPS nicht unterstützt! --> skip\n";
				return "continue";
			}
			else
			{
				echo "Fehler: Unbekannter Datentyp '".$type."'! --> skip\n";
				return "continue";
			}	

			return $datenTyp;
		}

		private function getProfile($unit, $datenTyp = -1)
		{
			// Profil ermitteln
			if ("a" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = "~Ampere";
			}
			elseif ("a" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Ampere.Int";
			}
			elseif (("ah" == strtolower($unit)
					|| "vah" == strtolower($unit))
				&& 7 == $datenTyp
			)
			{
						$profile = MODUL_PREFIX.".AmpereHour.Float";
			}
			elseif ("ah" == strtolower($unit)
				|| "vah" == strtolower($unit)
			)
			{
						$profile = MODUL_PREFIX.".AmpereHour.Int";
			}
			elseif ("v" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = "~Volt";
			}
			elseif("v" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Volt.Int";
			}
			elseif ("w" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = "~Watt.14490";
			}
			elseif ("w" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Watt.Int";
			}
			elseif ("hz" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = "~Hertz";
			}
			elseif ("hz" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Hertz.Int";
			}
			// Voltampere fuer elektrische Scheinleistung
			elseif ("va" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = MODUL_PREFIX.".Scheinleistung.Float";
			}
			// Voltampere fuer elektrische Scheinleistung
			elseif ("va" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Scheinleistung.Int";
			}
			// Var fuer elektrische Blindleistung
			elseif ("var" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = MODUL_PREFIX.".Blindleistung.Float";
			}
			// Var fuer elektrische Blindleistung
			elseif ("var" == strtolower($unit) || "var" == $unit)
			{
				$profile = MODUL_PREFIX.".Blindleistung.Int";
			}
			elseif ("%" == $unit && 7 == $datenTyp)
			{
				$profile = "~Valve.F";
			}
			elseif ("%" == $unit)
			{
				$profile = "~Valve";
			}
			elseif ("wh" == strtolower($unit) && (7 == $datenTyp || 8 == $datenTyp))
			{
				$profile = MODUL_PREFIX.".Electricity.Float";
			}
			elseif ("wh" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Electricity.Int";
			}
			elseif (("° C" == $unit
					|| "°C" == $unit
					|| "C" == $unit
				) && 7 == $datenTyp
			)
			{
				$profile = "~Temperature";
			}
			elseif ("° C" == $unit
				|| "°C" == $unit
				|| "C" == $unit
			)
			{
				$profile = MODUL_PREFIX.".Temperature.Int";
			}
			elseif ("cos()" == strtolower($unit) && 7 == $datenTyp)
			{
				$profile = MODUL_PREFIX.".Angle.Float";
			}
			elseif ("cos()" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Angle.Int";
			}
			elseif ("ohm" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Ohm.Int";
			}
			elseif ("enumerated_id" == strtolower($unit))
			{
				$profile = "SunSpec.ID.Int";
			}
			elseif ("enumerated_chast" == strtolower($unit))
			{
				$profile = "SunSpec.ChaSt.Int";
			}
			elseif ("enumerated_st" == strtolower($unit))
			{
				$profile = "SunSpec.StateCodes.Int";
			}
			elseif ("enumerated_stvnd" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".StateCodes.Int";
			}
			elseif ("" == $unit && "emergency-power" == strtolower($datenTyp))
			{
				$profile = MODUL_PREFIX.".Emergency-Power.Int";
			}
			elseif ("powermeter" == strtolower($unit))
			{
				$profile = MODUL_PREFIX.".Powermeter.Int";
			}
			elseif ("secs" == strtolower($unit))
			{
				$profile = "~UnixTimestamp";
			}
			elseif ("registers" == strtolower($unit)
				|| "bitfield" == strtolower($unit)
				|| "bitfield16" == strtolower($unit)
				|| "bitfield32" == strtolower($unit)
			)
			{
				$profile = false;
			}
			else
			{
				$profile = false;
				if ("" != $unit)
				{
					echo "Profil '".$unit."' unbekannt.\n";
				}
			}

			return $profile;			
		}


		private function checkProfiles()
		{
			$this->createVarProfile("SunSpec.ChaSt.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "N/A", 'Wert' => 0, "Unbekannter Status"),
					array('Name' => "OFF", 'Wert' => 1, "OFF: Energiespeicher nicht verfügbar"),
					array('Name' => "EMPTY", 'Wert' => 2, "EMPTY: Energiespeicher vollständig entladen"),
					array('Name' => "DISCHAGING", 'Wert' => 3, "DISCHARGING: Energiespeicher wird entladen"),
					array('Name' => "CHARGING", 'Wert' => 4, "CHARGING: Energiespeicher wird geladen"),
					array('Name' => "FULL", 'Wert' => 5, "FULL: Energiespeicher vollständig geladen"),
					array('Name' => "HOLDING", 'Wert' => 6, "HOLDING: Energiespeicher wird weder geladen noch entladen"),
					array('Name' => "TESTING", 'Wert' => 7, "TESTING: Energiespeicher wird getestet"),
				)
			);
			$this->createVarProfile("SunSpec.ID.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "single phase Inv (i)", 'Wert' => 101, "101: single phase Inverter (int)"),
					array('Name' => "split phase Inv (i)", 'Wert' => 102, "102: split phase Inverter (int)"),
					array('Name' => "three phase Inv (i)", 'Wert' => 103, "103: three phase Inverter (int)"),
					array('Name' => "single phase Inv (f)", 'Wert' => 111, "111: single phase Inverter (float)"),
					array('Name' => "split phase Inv (f)", 'Wert' => 112, "112: split phase Inverter (float)"),
					array('Name' => "three phase Inv (f)", 'Wert' => 113, "113: three phase Inverter (float)"),
					array('Name' => "single phase Meter (i)", 'Wert' => 201, "201: single phase Meter (int)"),
					array('Name' => "split phase Meter (i)", 'Wert' => 202, "202: split phase (int)"),
					array('Name' => "three phase Meter (i)", 'Wert' => 203, "203: three phase (int)"),
					array('Name' => "single phase Meter (f)", 'Wert' => 211, "211: single phase Meter (float)"),
					array('Name' => "split phase Meter (f)", 'Wert' => 212, "212: split phase Meter (float)"),
					array('Name' => "three phase Meter (f)", 'Wert' => 213, "213: three phase Meter (float)"),
					array('Name' => "string combiner (i)", 'Wert' => 403, "403: String Combiner (int)"),
				)
			);
			$this->createVarProfile("SunSpec.StateCodes.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "N/A", 'Wert' => 0, "Unbekannter Status"),
					array('Name' => "OFF", 'Wert' => 1, "Wechselrichter ist aus"),
					array('Name' => "SLEEPING", 'Wert' => 2, "Auto-Shutdown"),
					array('Name' => "STARTING", 'Wert' => 3, "Wechselrichter startet"),
					array('Name' => "MPPT", 'Wert' => 4, "Wechselrichter arbeitet normal", 'Farbe' => 65280),
					array('Name' => "THROTTLED", 'Wert' => 5, "Leistungsreduktion aktiv", 'Farbe' => 16744448),
					array('Name' => "SHUTTING_DOWN", 'Wert' => 6, "Wechselrichter schaltet ab"),
					array('Name' => "FAULT", 'Wert' => 7, "Ein oder mehr Fehler existieren, siehe St *oder Evt * Register", 'Farbe' => 16711680),
					array('Name' => "STANDBY", 'Wert' => 8, "Standby"),
				)
			);
			$this->createVarProfile(MODUL_PREFIX.".StateCodes.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "N/A", 'Wert' => 0, "Unbekannter Status"),
					array('Name' => "OFF", 'Wert' => 1, "Wechselrichter ist aus"),
					array('Name' => "SLEEPING", 'Wert' => 2, "Auto-Shutdown"),
					array('Name' => "STARTING", 'Wert' => 3, "Wechselrichter startet"),
					array('Name' => "MPPT", 'Wert' => 4, "Wechselrichter arbeitet normal", 'Farbe' => 65280),
					array('Name' => "THROTTLED", 'Wert' => 5, "Leistungsreduktion aktiv", 'Farbe' => 16744448),
					array('Name' => "SHUTTING_DOWN", 'Wert' => 6, "Wechselrichter schaltet ab"),
					array('Name' => "FAULT", 'Wert' => 7, "Ein oder mehr Fehler existieren, siehe St * oder Evt * Register", 'Farbe' => 16711680),
					array('Name' => "STANDBY", 'Wert' => 8, "Standby"),
					array('Name' => "NO_BUSINIT", 'Wert' => 9, "Keine SolarNet Kommunikation"),
					array('Name' => "NO_COMM_INV", 'Wert' => 10, "Keine Kommunikation mit Wechselrichter möglich"),
					array('Name' => "SN_OVERCURRENT", 'Wert' => 11, "Überstrom an SolarNet Stecker erkannt"),
					array('Name' => "BOOTLOAD", 'Wert' => 12, "Wechselrichter wird gerade upgedatet"),
					array('Name' => "AFCI", 'Wert' => 13, "AFCI Event (Arc-Erkennung)"),
				)
			);
/*
			$this->createVarProfile(MODUL_PREFIX.".Emergency-Power.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
					array('Name' => "nicht unterstützt", 'Wert' => 0, "Notstrom wird nicht von Ihrem Gerät unterstützt", 'Farbe' => 16753920),
					array('Name' => "aktiv", 'Wert' => 1, "Notstrom aktiv (Ausfall des Stromnetzes)", 'Farbe' => 65280),
					array('Name' => "nicht aktiv", 'Wert' => 2, "Notstrom nicht aktiv", 'Farbe' => -1),
					array('Name' => "nicht verfügbar", 'Wert' => 3, "Notstrom nicht verfügbar", 'Farbe' => 16753920),
					array('Name' => "Fehler", 'Wert' => 4, "Der Motorschalter des S10 E befindet sich nicht in der richtigen Position, sondern wurde manuell abgeschaltet oder nicht eingeschaltet.", 'Farbe' => 16711680),
				)
			);

			$this->createVarProfile(MODUL_PREFIX.".Powermeter.Int", VARIABLETYPE_INTEGER, '', 0, 0, 0, 0, 0, array(
				array('Name' => "N/A", 'Wert' => 0),
				array('Name' => "Wurzelleistungsmesser", 'Wert' => 1, "Dies ist der Regelpunkt des Systems. Der Regelpunkt entspricht üblicherweise dem Hausanschlusspunkt."),
				array('Name' => "Externe Produktion", 'Wert' => 2),
				array('Name' => "Zweirichtungszähler", 'Wert' => 3),
				array('Name' => "Externer Verbrauch", 'Wert' => 4),
				array('Name' => "Farm", 'Wert' => 5),
				array('Name' => "Wird nicht verwendet", 'Wert' => 6),
				array('Name' => "Wallbox", 'Wert' => 7),
				array('Name' => "Externer Leistungsmesser Farm", 'Wert' => 8),
				array('Name' => "Datenanzeige", 'Wert' => 9, "Wird nicht in die Regelung eingebunden, sondern dient nur der Datenaufzeichnung des Kundenportals."),
				array('Name' => "Regelungsbypass", 'Wert' => 10, "Die gemessene Leistung wird nicht in die Batterie geladen, aus der Batterie entladen."),
				)
			);
*/
			$this->createVarProfile(MODUL_PREFIX.".Ampere.Int", VARIABLETYPE_INTEGER, ' A');
			$this->createVarProfile(MODUL_PREFIX.".AmpereHour.Float", VARIABLETYPE_FLOAT, ' Ah');
			$this->createVarProfile(MODUL_PREFIX.".AmpereHour.Int", VARIABLETYPE_INTEGER, ' Ah');
			$this->createVarProfile(MODUL_PREFIX.".Angle.Float", VARIABLETYPE_FLOAT, ' °');
			$this->createVarProfile(MODUL_PREFIX.".Angle.Int", VARIABLETYPE_INTEGER, ' °');
			$this->createVarProfile(MODUL_PREFIX.".Blindleistung.Float", VARIABLETYPE_FLOAT, ' Var');
			$this->createVarProfile(MODUL_PREFIX.".Blindleistung.Int", VARIABLETYPE_INTEGER, ' Var');
			$this->createVarProfile(MODUL_PREFIX.".Electricity.Float", VARIABLETYPE_FLOAT, ' Wh');
			$this->createVarProfile(MODUL_PREFIX.".Electricity.Int", VARIABLETYPE_INTEGER, ' Wh');
			$this->createVarProfile(MODUL_PREFIX.".Hertz.Int", VARIABLETYPE_INTEGER, ' Hz');
			$this->createVarProfile(MODUL_PREFIX.".Ohm.Int", VARIABLETYPE_INTEGER, ' Ohm');
			$this->createVarProfile(MODUL_PREFIX.".Scheinleistung.Float", VARIABLETYPE_FLOAT, ' VA');
			$this->createVarProfile(MODUL_PREFIX.".Scheinleistung.Int", VARIABLETYPE_INTEGER, ' VA');
			// Temperature.Float: ~Temperature
			$this->createVarProfile(MODUL_PREFIX.".Temperature.Int", VARIABLETYPE_INTEGER, ' °C');
			// Volt.Float: ~Volt
			$this->createVarProfile(MODUL_PREFIX.".Volt.Int", VARIABLETYPE_INTEGER, ' V');
			$this->createVarProfile(MODUL_PREFIX.".Watt.Int", VARIABLETYPE_INTEGER, ' W');
		}

		private function GetVariableValue($instanceIdent, $variableIdent = "Value")
		{
			$instanceId = IPS_GetObjectIDByIdent($this->removeInvalidChars($instanceIdent), $this->InstanceID);
			$varId = IPS_GetObjectIDByIdent($this->removeInvalidChars($variableIdent), $instanceId);

			return GetValue($varId);
		}

		private function GetVariableId($instanceIdent, $variableIdent = "Value")
		{
			$instanceId = IPS_GetObjectIDByIdent($this->removeInvalidChars($instanceIdent), $this->InstanceID);
			$varId = IPS_GetObjectIDByIdent($this->removeInvalidChars($variableIdent), $instanceId);

			return $varId;
		}

		private function GetLoggedValuesInterval($id, $minutes)
		{
			$archiveId = IPS_GetInstanceListByModuleID("{43192F0B-135B-4CE7-A0A7-1475603F3060}");
			if (isset($archiveId[0]))
			{
				$archiveId = $archiveId[0];

				$returnValue = $this->getArithMittelOfLog($archiveId, $id, $minutes);
			}
			else
			{
				$archiveId = false;

				// no archive found
				$this->SetStatus(201);
				echo MODUL_PREFIX."_GetBatteryPowerW(): Archive not found!";

				$returnValue = GetValue($id);
			}

			return $returnValue;
		}

	}
