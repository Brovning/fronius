# Fronius
IP-Symcon (IPS) Modul für Fronius Inverter (Wechselrichter) und SmartMeter (Energiezähler) mit ModBus TCP Unterstützung (bspw. Galvo, Primo, Symo, Symo Hybrid, Primo GEN24 Plus, Symo GEN24 Plus, Tauro,...).


### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)
8. [Versionshistorie](#8-versionshistorie)


### 1. Funktionsumfang

Dieses Modul erstellt anhand der Konfiguration der Fronius Instanz den nötigen Client Socket und das dazugehörige ModBus Gateway. Sofern diese bereits vorhanden sind, werden keine weiteren Client Sockets oder ModBus Gateways erstellt.
Unterhalb der Fronius Instanz werden die Modbus Adressen des Modells Inverter und optional der erweiterterten Inverter Modelle erstellt oder es wird alternativ das Meter Modell erstellt.


### 2. Voraussetzungen

- IP-Symcon ab Version 5.0
- Der Fronius Wechselrichter oder SmartMeter muss Modbus TCP unterstützen!
- Im Konfigurationsmenü des Fronius Wechselrichters muss unter dem Menüpunkt 'Modbus' die Datenausgabe per 'TCP' und der Sunspec Model Type 'float' aktiviert werden.
![alt text](https://github.com/Brovning/fronius/blob/master/docs/Fronius%20-%20Einstellungen%20-%20Modbus.JPG "Fronius - Einstellungen - Modbus")
- Die Modbus Geräte-ID des Wechselrichters entspricht seiner Wechselrichter-Nummer, welche nur über das Bedienpanel des Wechselrichters eingestellt werden kann und nicht per Weboberfläche. Zu finden unter Setup > DATCOM > Wechselrichter-Nr. Die Werkseinstellung ist "01", was der Modbus Geräte-ID "1" entspricht. Hierbei gibt es nur eine einzige Ausnahme: Die Wechselrichter-Nummer "00" wird auf Modbus Geräte-ID "100" umgelegt, da bei Modbus die Geräte-ID "0" für Broadcast Nachrichten reserviert ist. Der SmartMeter hat standardmäßig die Geräte-ID "240".
![alt text](https://github.com/Brovning/fronius/blob/master/docs/Fronius%20-%20Setup%20-%20DATCOM%20-%20WechselrichterNr.jpg "Fronius - Setup - DATCOM - Wechselrichter-Nr.")


### 3. Software-Installation

* Über den Module Store das 'Fronius'-Modul installieren.
* Alternativ über das Module Control folgende URL hinzufügen: https://github.com/Brovning/fronius


### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' ist das 'Fronius'-Modul unter dem Hersteller 'Fronius' aufgeführt.

__Konfigurationsseite__:

Name     | Beschreibung
-------- | ------------------
Open | Schalter zum aktivieren und deaktivieren der Instanz
IP | IP-Adresse des Fronius-Wechselrichters im lokalen Netzwerk
Port | Port, welcher im Wechselrichter unter dem Menüpunkt Modbus angegeben wurde. Default: 502
Geräte Id | Modbus Geräte ID, welche im Fronius Menü gesetzt werden kann. Default für Inverter: 1, Default für SmartMeter: 240
IC120 Nameplate | Soll das Nameplate Modell IC120 angezeigt werden? Default: false
IC121 Basic Settings | Soll das Basic Settings Modell IC121 angezeigt werden? Default: false
IC122 Extended Measurements & Status | Soll das Extended Measurements & Status Modell IC122 angezeigt werden? Default: false
IC123 Immediate Controls | Soll das Immediate Controls Modell IC123 angezeigt werden? Default: false
I160 Multiple MPPT Inverter Extension | Soll das Multiple MPPT Inverter Extension Modell I160 angezeigt werden? Default: false
IC124 Basic Storage Control | Soll das Basic Storage Modell IC124 angezeigt werden? Achtung: Nur für Hybrid-Wechselrichter gültig! Default: false
1-phasiger Wechselrichter | Wird anstatt einens 3-phasigen Wechselrichters (Symo) ein 1-phasiger Wechselrichter (Primo) verwendet? Default: false
Abfrage-Intervall | Intervall (in Sekunden) in welchem die Modbus-Adressen abgefragt werden sollen. Achtung: Abfrage-Intervall nicht zu klein wählen, um die Systemlast und auch die Archiv-Größe bei Logging nicht unnötig zu erhöhen! Default: 60


### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.


#### Statusvariablen

##### Inverter
Für die Wechselrichter-Daten werden zwei verschiedene SunSpec Models unterstützt:
- das standardmäßig eingestellte Inverter Model mit Gleitkomma-Darstellung (Einstellung „float“; I111, I112 oder I113)
- das Inverter Model mit ganzen Zahlen und Skalierungsfaktoren (Einstellung „int+SF“; I101, I102 oder I103)
HINWEIS! Die Registeranzahl der beiden Model-Typen ist unterschiedlich!

###### Inverter Model

StartRegister | Size | RW | FunctionCode | Name | Type | Units | Description
------------- | ---- | -- | ------------ | ---- | ---- | ----- | -----------
40070 | 1 | R | 3 | ID |  uint16 |   |  Uniquely identifies this as a SunSpec Inverter Modbus Map (111: single phase, 112: split phase, 113: three phase)
40071 | 1 | R | 3 | L |  uint16 |   |  Registers, Length of inverter model block
40072 | 2 | R | 3 | A |  float32 |  A |  AC Total Current value
40074 | 2 | R | 3 | AphA |  float32 |  A |  AC Phase-A Current value
40076 | 2 | R | 3 | AphB |  float32 |  A |  AC Phase-B Current value
40078 | 2 | R | 3 | AphC |  float32 |  A |  AC Phase-C Current value
40080 | 2 | R | 3 | PPVphAB |  float32 |  V |  AC Voltage Phase-AB value
40082 | 2 | R | 3 | PPVphBC |  float32 |  V |  AC Voltage Phase-BC value
40084 | 2 | R | 3 | PPVphCA |  float32 |  V |  AC Voltage Phase-CA value
40086 | 2 | R | 3 | PhVphA |  float32 |  V |  AC Voltage Phase-A-toneutral value
40088 | 2 | R | 3 | PhVphB |  float32 |  V |  AC Voltage Phase-B-toneutral value
40090 | 2 | R | 3 | PhVphC |  float32 |  V |  AC Voltage Phase-C-toneutral value
40092 | 2 | R | 3 | W |  float32 |  W |  AC Power value
40094 | 2 | R | 3 | Hz |  float32 |  Hz |  AC Frequency value
40096 | 2 | R | 3 | VA |  float32 |  VA |  Apparent Power
40098 | 2 | R | 3 | VAr |  float32 |  VAr |  Reactive Power
40100 | 2 | R | 3 | PF |  float32 |  % |  Power Factor
40102 | 2 | R | 3 | WH |  float32 |  Wh |  AC Lifetime Energy production
40108 | 2 | R | 3 | DCW |  float32 |  W |  DC Power value
40110 | 2 | R | 3 | TmpCab |  float32 |  ° C |  Cabinet Temperature
40112 | 2 | R | 3 | TmpSnk |  float32 |  ° C |  Coolant or Heat Sink Temperature
40114 | 2 | R | 3 | TmpTrns |  float32 |  ° C |  Transformer Temperature
40116 | 2 | R | 3 | TmpOt |  float32 |  ° C |  Other Temperature
40118 | 1 | R | 3 | St |  enum16 |  Enumerated |  Operating State (SunSpec State Codes)
40119 | 1 | R | 3 | StVnd |  enum16 |  Enumerated |  Vendor Defined Operating State (Fronius State Codes)
40120 | 2 | R | 3 | Evt1 |  uint32 |  Bitfield |  Event Flags (bits 0-31)
40122 | 2 | R | 3 | Evt2 |  uint32 |  Bitfield |  Event Flags (bits 32-63)
40124 | 2 | R | 3 | EvtVnd1 |  uint32 |  Bitfield |  Vendor Defined Event Flags (bits 0-31)
40126 | 2 | R | 3 | EvtVnd2 |  uint32 |  Bitfield |  Vendor Defined Event Flags (bits 32-63)
40128 | 2 | R | 3 | EvtVnd3 |  uint32 |  Bitfield |  Vendor Defined Event Flags (bits 64-95)
40130 | 2 | R | 3 | EvtVnd4 |  uint32 |  Bitfield |  Vendor Defined Event Flags (bits 96-127)


###### optional: Nameplate Model (IC120)
Dieses Modell entspricht einem Leistungsschild. Folgende Daten können ausgelesen werden:
- DERType (3): Art des Geräts. Das Register liefert den Wert 4 zurück (PV-Gerät)
- WRtg (4): Nennleistung des Wechselrichters
- VARtg (6): Nenn-Scheinleistung des Wechselrichters
- VArRtgQ1 (8) - VArRtgQ4 (11): Nenn-Blindleistungswerte für die vier Quadranten
- ARtg (13): Nennstrom des Wechselrichters
- PFRtgQ1 (15) – PFRtgQ4 (18): Minimale Werte für den Power Factor für die vier Quadranten

StartRegister | Size | RW | FunctionCode | Name | Type | Units | Description
------------- | ---- | -- | ------------ | ---- | ---- | ----- | -----------
40135 | 1 | R | 3 | WRtg |  uint16 |  W |  WRtg_SF Continuous power output capability of the inverter.
40136 | 1 | R | 3 | WRtg_SF |  sunssf |   |  Scale factor 1
40137 | 1 | R | 3 | VARtg |  uint16 |  VA |  VARtg_SF Continuous Volt-Ampere capability of the inverter.
40138 | 1 | R | 3 | VARtg_SF |  sunssf |   |  Scale factor 1
40139 | 1 | R | 3 | VArRtgQ1 |  int16 |  var |  VArRtg_SF Continuous VAR capability of the inverter in quadrant 1.
40140 | 1 | R | 3 | VArRtgQ2 |  int16 |  var |  VArRtg_SF Continuous VAR capability of the inverter in quadrant 2.
40141 | 1 | R | 3 | VArRtgQ3 |  int16 |  var |  VArRtg_SF Continuous VAR capability of the inverter in quadrant 3.
40142 | 1 | R | 3 | VArRtgQ4 |  int16 |  var |  VArRtg_SF Continuous VAR capability of the inverter in quadrant 4.
40143 | 1 | R | 3 | VArRtg_SF |  sunssf |   |  Scale factor 1
40144 | 1 | R | 3 | ARtg |  uint16 |  A |  ARtg_SF Maximum RMS AC current level capability of the inverter.
40145 | 1 | R | 3 | ARtg_SF |  sunssf |   |  Scale factor -2
40146 | 1 | R | 3 | PFRtgQ1 |  int16 |  cos() |  PFRtg_SF Minimum power factor capability of the inverter in quadrant 1.
40147 | 1 | R | 3 | PFRtgQ2 |  int16 |  cos() |  PFRtg_SF Minimum power factor capability of the inverter in quadrant 2.
40148 | 1 | R | 3 | PFRtgQ3 |  int16 |  cos() |  PFRtg_SF Minimum power factor capability of the inverter in quadrant 3.
40149 | 1 | R | 3 | PFRtgQ4 |  int16 |  cos() |  PFRtg_SF Minimum power factor capability of the inverter in quadrant 4.
40150 | 1 | R | 3 | PFRtg_SF |  sunssf |   |  Scale factor -3
40151 | 1 | R | 3 | WHRtg |  uint16 |  Wh |  WHRtg_SF Nominal energy rating of storage device.
40152 | 1 | R | 3 | WHRtg_SF |  sunssf |   |  Scale factor 0*
40153 | 1 | R | 3 | AhrRtg |  uint16 |  AH |  AhrRtg_SF The useable capacity of the battery. Maximum charge minus minimum charge from a technology capability perspective (Amp-hour capacity rating).
40154 | 1 | R | 3 | AhrRtg_SF |  sunssf |   |  Scale factor for amphour rating.
40155 | 1 | R | 3 | MaxChaRte |  uint16 |  W |  MaxChaRte_SF Maximum rate of energy transfer into the storage device.
40156 | 1 | R | 3 | MaxChaRte_SF |  sunssf |   |  Scale factor 0*
40157 | 1 | R | 3 | MaxDisChaRte |  uint16 |  W |  Max-DisChaRte_SF Maximum rate of energy transfer out of the storage device.
40158 | 1 | R | 3 | MaxDisChaRte_SF |  sunssf |   |  Scale factor 0*

###### optional: Basic Settings Model (IC121)

StartRegister | Size | RW | FunctionCode | Name | Description | Type | Units
------------- | ---- | -- | ------------ | ---- | ----------- | ---- | -----
40162 | 1 | RW | 0x03 0x06 0x10 | WMax | Setting for maximum power output. Default to I_WRtg. | uint16 | W | VAMax_SF | 
40163 | 1 | RW | 0x03 0x06 0x10 | VRef | Voltage at the PCC. | uint16 | V | VAMax_SF | 
40164 | 1 | RW | 0x03 0x06 0x10 | VRefOfs | Offset  from PCC to inverter. | int16 | V | VRefOfs_SF | 
40167 | 1 | RW | 0x03 | VAMax | Setpoint for maximum apparent power. Default to I_VARtg. | uint16 | VA | VAMax_SF | 
40168 | 1 | R | 0x03 | VARMaxQ1 | Setting for maximum reactive power in quadrant 1. Default to VArRtgQ1. | int16 | var | VARMax_SF | 
40171 | 1 | R | 0x03 | VARMaxQ4 | Setting for maximum reactive power in quadrant 4 Default to VArRtgQ4. | int16 | var | VARMax_SF | 
40173 | 1 | R | 0x03 | PFMinQ1 | Setpoint for minimum power factor value in quadrant 1. Default to PFRtgQ1. | int16 | cos() | PFMin_SF | 
40176 | 1 | R | 0x03 | PFMinQ4 | Setpoint for minimum power factor value in quadrant 4. Default to PFRtgQ4. | int16 | cos() | PFMin_SF | 


###### optional: Extended Measurements & Status Model (IC122)

Allgemeines:
Dieses Modell liefert einige zusätzliche Mess- und Statuswerte | die das normale Inverter Model nicht abdeckt:
- PVConn (3)
Dieses Bitfeld zeigt den Status des Wechselrichter an
- Bit 0: Verbunden
- Bit 1: Ansprechbar
- Bit 2: Arbeitet (Wechselrichter speist ein)
- ECPConn (5)
Dieses Register zeigt den Verbindungsstatus zum Netz an
- ECPConn = 1: Wechselrichter speist gerade ein
- ECPConn = 0: Wechselrichter speist nicht ein
- ActWH (6 - 9)
Wirkenergiezähler
- StActCtl (36 - 37)
Bitfeld für zurzeit aktive Wechselrichter-Modi
- Bit 0: Leistungsreduktion (FixedW; entspricht WMaxLimPct Vorgabe)
- Bit 1: konstante Blindleistungs-Vorgabe (FixedVAR; entspricht VArMaxPct)
- Bit 2: Vorgabe eines konstanten Power Factors (FixedPF; entspricht OutPFSet)
- TmSrc (38 - 41)
Quelle für die Zeitsynchronisation. Das Register liefert den String „RTC“ zurück.
- Tms (42 - 43)
Aktuelle Uhrzeit und Datum der RTC
Angegeben werden die Sekunden vom 1. Jänner 2000 00:00 (UTC) bis zur aktuellen Zeit

StartRegister | Size | RW | FunctionCode | Name | Description | Type | Units
------------- | ---- | -- | ------------ | ---- | ----------- | ---- | -----
40194 | 1 | R | 0x03 | PVConn | PV inverter present/available status. Enumerated value. | uint16 | bitfield16 |  | Bit 0: Connected | Bit 1: Available | Bit 2: Operating | Bit 3: Test
40195 | 1 | R | 0x03 | StorConn | Storage inverter present/available status. Enumerated value. | uint16 | bitfield16 |  | bit 0: CONNECTED | bit 1: AVAILABLE | bit 2: OPERATING | bit 3: TEST
40196 | 1 | R | 0x03 | ECPConn | ECP connection status: disconnected=0  connected=1. | uint16 | bitfield16 |  | 0: Disconnected | 1: Connected
40197 | 4 | R | 0x03 | ActWh | AC lifetime active (real) energy output. | acc64 | Wh |  | 
40227 | 2 | R | 0x03 | StActCtl | Bit Mask indicating which inverter controls are currently active. | uint32 | bitfield32 |  | Bit 0: FixedW | Bit 1: FixedVAR | Bit 2: FixedPF
40233 | 2 | R | 0x03 | Tms | Seconds since 01-01-2000 00:00 UTC | uint32 | Secs |  | 


###### optional: Immediate Controls Model (IC123)

Allgemeines:
Mit den Immediate Controls können folgende Einstellungen am Wechselrichter vorgenommen werden:
- Unterbrechung des Einspeisebetriebs des Wechselrichters (Standby)
- Konstante Reduktion der Ausgangsleistung
- Vorgabe eines konstanten Power Factors
- Vorgabe einer konstanten relativen Blindleistung

StartRegister | Size | RW | FunctionCode | Name | Description | Type | Units
------------- | ---- | -- | ------------ | ---- | ----------- | ---- | -----
40240 | 1 | RW | 0x03 0x06 0x10 | Conn_WinTms | Time window for connect/disconnect. | uint16 | Secs |  | 
40241 | 1 | RW | 0x03 0x06 0x10 | Conn_RvrtTms | Timeout period for connect/disconnect. | uint16 | Secs |  | 
40242 | 1 | RW | 0x03 0x06 0x10 | Conn | Enumerated valued.  Connection control. | uint16 | bitfield16 |  | 0: Disconnected | 1: Connected
40243 | 1 | RW | 0x03 0x06 0x10 | WMaxLimPct | Set power output to specified level. (% WMax) | uint16 | % | WMaxLimPct_SF | 
40244 | 1 | RW | 0x03 0x06 0x10 | WMaxLimPct_WinTms | Time window for power limit change. | uint16 | Secs |  | 0 – 300
40245 | 1 | RW | 0x03 0x06 0x10 | WMaxLimPct_RvrtTms | Timeout period for power limit. | uint16 | Secs |  | 0 – 28800
40246 | 1 | RW | 0x03 | WMaxLimPct_RmpTms | Ramp time for moving from current setpoint to new setpoint. | uint16 | Secs |  | 0 - 65534 (0xFFFF has the same effect as 0x0000)
40247 | 1 | RW | 0x03 0x06 0x10 | WMaxLim_Ena | Enumerated valued.  Throttle enable/disable control. | enum16 |  |  | 0: Disabled | 1: Enabled
40248 | 1 | RW | 0x03 0x06 0x10 | OutPFSet | Set power factor to specific value - cosine of angle. | int16 | cos() | OutPFSet_SF | 
40249 | 1 | RW | 0x03 0x06 0x10 | OutPFSet_WinTms | Time window for power factor change. | uint16 | Secs |  | 0 – 300
40250 | 1 | RW | 0x03 0x06 0x10 | OutPFSet_RvrtTms | Timeout period for power factor. | uint16 | Secs |  | 0 – 28800
40251 | 1 | RW | 0x03 0x06 0x10 | OutPFSet_RmpTms | Ramp time for moving from current setpoint to new setpoint. | uint16 | Secs |  | 0 - 65534 (0xFFFF has the same effect as 0x0000)
40252 | 1 | RW | 0x03 0x06 0x10 | OutPFSet_Ena | Enumerated valued.  Fixed power factor enable/disable control. | enum16 |  |  | 0: Disabled | 1: Enabled
40254 | 1 | RW | 0x03 0x06 0x10 | VArMaxPct | Reactive power in percent of I_VArMax. (% VArMax) | int16 | % | VArPct_SF | 
40256 | 1 | RW | 0x03 0x06 0x10 | VArPct_WinTms | Time window for VAR limit change. | uint16 | Secs |  | 0 – 300
40257 | 1 | RW | 0x03 0x06 0x10 | VArPct_RvrtTms | Timeout period for VAR limit. | uint16 | Secs |  | 0 – 28800
40258 | 1 | RW | 0x03 0x06 0x10 | VArPct_RmpTms | Ramp time for moving from current setpoint to new setpoint. | uint16 | Secs |  | 0 - 65534 (0xFFFF has the same effect as 0x0000)
40259 | 1 | R | 0x03 | VArPct_Mod | Enumerated value. VAR limit mode. | enum16 |  |  | 2: VAR limit as a % of VArMax
40260 | 1 | RW | 0x03 0x06 0x10 | VArPct_Ena | Enumerated valued.  Fixed VAR enable/disable control. | enum16 |  |  | 0: Disabled | 1: Enabled


###### optional: Multiple MPPT Inverter Extension Model (I160)

Allgemeines:
Das Multiple MPPT Inverter Extension Model beinhaltet die Werte von bis zu zwei DC Eingängen
des Wechselrichters.
Verfügt der Wechselrichter über zwei DC Eingänge | so werden Strom | Spannung | Leistung,
Energie und Statusmeldungen der einzelnen Eingänge hier aufgelistet. Im Inverter
Model (101 -103 oder 111 - 113) wird in diesem Fall nur die gesamte DC Leistung beider
Eingänge ausgegeben. DC Strom und DC Spannung werden als "not implemented" angezeigt.
Sollte der Wechselrichter nur über einen DC Eingang verfügen | werden alle Werte des
zweiten Strings auf "not implemented" gesetzt (ab Register 2_DCA). Die Bezeichnung des
zweiten Eingangs (Register 2_IDStr) lautet in diesem Fall "Not supported". Die Werte des
ersten (und einzigen) Eingangs werden normal angezeigt.

StartRegister | Size | RW | FunctionCode | Name | Description | Type | Units
------------- | ---- | -- | ------------ | ---- | ----------- | ---- | -----
40266 | 1 | R | 0x03 | DCA_SF | Current Scale Factor | sunssf |  |  | 
40267 | 1 | R | 0x03 | DCV_SF | Voltage Scale Factor | sunssf |  |  | 
40268 | 1 | R | 0x03 | DCW_SF | Power Scale Factor | sunssf |  |  | 
40269 | 1 | R | 0x03 | DCWH_SF | Energy Scale Factor | sunssf |  |  | 
40270 | 2 | R | 0x03 | Evt | Global Events | uint32 | bitfield32 |  | 
40272 | 1 | R | 0x03 | N | Number of Modules | uint16 |  |  | 2
40283 | 1 | R | 0x03 | 1_DCA | DC Current | uint16 | A | DCA_SF | 
40284 | 1 | R | 0x03 | 1_DCV | DC Voltage | uint16 | V | DCV_SF | 
40285 | 1 | R | 0x03 | 1_DCW | DC Power | uint16 | W | DCW_SF | 
40286 | 2 | R | 0x03 | 1_DCWH | Lifetime Energy | acc32 | Wh | DCWH_SF | 
40288 | 2 | R | 0x03 | 1_Tms | Timestamp | uint32 | Secs |  | 
40290 | 1 | R | 0x03 | 1_Tmp | Temperature | int16 | C |  | 
40291 | 1 | R | 0x03 | 1_DCSt | Operating State | enum16 |  |  | 
40292 | 2 | R | 0x03 | 1_DCEvt | Module Events | uint32 | bitfield32 |  | 
40303 | 1 | R | 0x03 | 2_DCA | DC Current | uint16 | A | DCA_SF | Not supported if only one DC input.
40304 | 1 | R | 0x03 | 2_DCV | DC Voltage | uint16 | V | DCV_SF | Not supported if only one DC input.
40305 | 1 | R | 0x03 | 2_DCW | DC Power | uint16 | W | DCW_SF | Not supported if only one DC input.
40306 | 2 | R | 0x03 | 2_DCWH | Lifetime Energy | acc32 | Wh | DCWH_SF | Not supported if only one DC input.
40308 | 2 | R | 0x03 | 2_Tms | Timestamp | uint32 | Secs |  | Not supported if only one DC input.
40310 | 1 | R | 0x03 | 2_Tmp | Temperature | int16 | C |  | Not supported if only one DC input.
40311 | 1 | R | 0x03 | 2_DCSt | Operating State | enum16 |  |  | Not supported if only one DC input.
40312 | 2 | R | 0x03 | 2_DCEvt | Module Events | uint32 | bitfield32 |  | Not supported if only one DC input.


###### optional: Basic Storage Control Model (IC124)

Allgemeines:
Dieses Model ist nur für Fronius Hybrid Wechselrichter verfügbar.
Mit dem Basic Storage Control Model können folgende Einstellungen am Wechselrichter
vorgenommen werden:
- Vorgabe eines Leistungsfensters | in dem sich die Lade-/Entladeleistung vom Energiespeicher bewegen soll.
- Vorgabe eines minimalen Ladestandes | den der Energiespeicher nicht unterschreiten soll
- Ladung des Energiespeichers vom Netz erlauben/verbieten

StartRegister | Size | RW | FunctionCode | Name | Description | Type | Units
------------- | ---- | -- | ------------ | ---- | ----------- | ---- | -----
40316 | 1 | R | 0x03 | WchaMax | Setpoint for maximum charge. Additional Fronius description: Reference Value for maximum Charge and Discharge. Multiply this value by InWRte to define maximum charging and OutWRte to define maximum discharging. Every rate between this two limits is allowed. Note that  InWRte and OutWRte can be negative to define ranges for charging and discharging only. | uint16 | W | WChaMax_SF | 
40317 | 1 | R | 0x03 | WchaGra | Setpoint for maximum charging rate. Default is MaxChaRte. (% WChaMax/sec) | uint16 | % | WChaDisChaGra_SF | 100
40318 | 1 | R | 0x03 | WdisChaGra | Setpoint for maximum discharge rate. Default is MaxDisChaRte. (% WChaMax/sec) | uint16 | % | WChaDisChaGra_SF | 100
40319 | 1 | RW | 0x03 0x06 0x10 | StorCtl_Mod | Activate hold/discharge/charge storage control mode. Bitfield value. Additional Fronius description: Active hold/discharge/charge storage control mode. Set the charge field to enable charging and the discharge field to enable discharging. Bitfield value. | uint16 | bitfield16 |  | bit 0: CHARGE | bit 1: DiSCHARGE
40321 | 1 | RW | 0x03 0x06 0x10 | MinRsvPct | Setpoint for minimum reserve for storage as a percentage of the nominal maximum storage. (% WChaMax) | uint16 | % | MinRsvPct_SF | 
40322 | 1 | R | 0x03 | ChaState | Currently available energy as a percent of the capacity rating. (% AhrRtg) | uint16 | % | ChaState_SF | 
40323 | 1 | R | 0x03 | StorAval | State of charge (ChaState) minus storage reserve (MinRsvPct) times capacity rating (AhrRtg). | uint16 | AH | StorAval_SF | 
40324 | 1 | R | 0x03 | InBatV | Internal battery voltage. | uint16 | V | InBatV_SF | 
40325 | 1 | R | 0x03 | ChaSt | Charge status of storage device. Enumerated value. | enum16 |  |  | 1: OFF | 2: EMPTY | 3: DISCHAGING | 4: CHARGING | 5: FULL | 6: HOLDING | 7: TESTING
40326 | 1 | RW | 0x03 0x06 0x10 | OutWRte | Percent of max discharge rate. Additional Fronius description: Defines maximum Discharge rate. If not used than the default is 100 and wChaMax defines max. Discharge rate. See wChaMax for details. (% WChaMax) | int16 | % | InOutWRte_SF | 
40327 | 1 | RW | 0x03 0x06 0x10 | InWRte | Percent of max charging rate. Additional Fronius description: Defines maximum Charge rate. If not used than the default is 100 and wChaMax defines max. Charge rate. See wChaMax for details. (% WChaMax) | int16 | % | InOutWRte_SF | 
40331 | 1 | RW | 0x03 0x06 0x10 | ChaGriSet | Setpoint to enable/disable charging from grid | enum16 |  |  | 0: PV (Charging from grid disabled) | 1: GRID (Charging from grid enabled)


##### SmartMeter:
Ähnlich wie bei den Inverter Models gibt es auch für SmartMeter zwei verschiedene SunSpec Models:
- das Meter Model mit Gleitkommadarstellung (Einstellung „float“; 211, 212 oder 213)
- das Meter Model mit ganzen Zahlen und Skalierungsfaktoren (Einstellung „int+SF“; 201, 202 oder 203)
Die Registeranzahl der beiden Model-Typen ist unterschiedlich!

###### Meter Model

StartRegister | Size | RW | FunctionCode | Name | Description | Type | Units
------------- | ---- | -- | ------------ | ---- | ----------- | ---- | -----
40070 | 1 | R | 3 | ID | Uniquely identifies this as a SunSpec Meter Modbus Map (float); 211: single phase, 212: split phase, 213: three phase | uint16 | 
40071 | 1 | R | 3 | L - Registers | Registers | Length of inverter model block: 124 | uint16 | 
40072 | 2 | R | 3 | A - AC Total Current | AC Total Current value | float32 | A
40074 | 2 | R | 3 | AphA - AC Phase-A Current | AC Phase-A Current value | float32 | A
40076 | 2 | R | 3 | AphB - AC Phase-B Current | AC Phase-B Current value | float32 | A
40078 | 2 | R | 3 | AphC - AC Phase-C Current | AC Phase-C Current value | float32 | A
40080 | 2 | R | 3 | PhV - AC Voltage Average | AC Voltage Average Phase-to-neutral value | float32 | V
40082 | 2 | R | 3 | PhVphA - AC Voltage Phase-A-to-neutral | AC Voltage Phase-A-to-neutral value | float32 | V
40084 | 2 | R | 3 | PhVphB - AC Voltage Phase-B-to-neutral | AC Voltage Phase-B-to-neutral value | float32 | V
40086 | 2 | R | 3 | PhVphC - AC Voltage Phase-C-to-neutral | AC Voltage Phase-C-to-neutral value | float32 | V
40088 | 2 | R | 3 | PPV - AC Voltage Average Phase-to-phase | AC Voltage Average Phase-to-phase value | float32 | V
40090 | 2 | R | 3 | PPVphAB - AC Voltage Phase-AB | AC Voltage Phase-AB value | float32 | V
40092 | 2 | R | 3 | PPVphBC - AC Voltage Phase-BC | AC Voltage Phase-BC value | float32 | V
40094 | 2 | R | 3 | PPVphCA - AC Voltage Phase-CA | AC Voltage Phase-CA value | float32 | V
40096 | 2 | R | 3 | Hz - AC Frequency | AC Frequency value | float32 | Hz
40098 | 2 | R | 3 | W - AC Power | AC Power value | float32 | W
40100 | 2 | R | 3 | WphA - AC Power Phase A | AC Power Phase A value | float32 | W
40102 | 2 | R | 3 | WphB - AC Power Phase B | AC Power Phase B value | float32 | W
40104 | 2 | R | 3 | WphC - AC Power Phase C | AC Power Phase C value | float32 | W
40106 | 2 | R | 3 | VA - AC Apparent Power | AC Apparent Power value | float32 | VA
40108 | 2 | R | 3 | VAphA - AC Apparent Power Phase A | AC Apparent Power Phase A value | float32 | VA
40110 | 2 | R | 3 | VAphB - AC Apparent Power Phase B | AC Apparent Power Phase B value | float32 | VA
40112 | 2 | R | 3 | VAphC - AC Apparent Power Phase C | AC Apparent Power Phase C value | float32 | VA
40114 | 2 | R | 3 | VAR - AC Reactive Power | AC Reactive Power value | float32 | VAr
40116 | 2 | R | 3 | VARphA - AC Reactive Power Phase A | AC Reactive Power Phase A value | float32 | VAr
40118 | 2 | R | 3 | VARphB - AC Reactive Power Phase B | AC Reactive Power Phase B value | float32 | VAr
40120 | 2 | R | 3 | VARphC - AC Reactive Power Phase C | AC Reactive Power Phase C value | float32 | VAr
40122 | 2 | R | 3 | PF - Power Factor | Power Factor value | float32 | cos()
40124 | 2 | R | 3 | PFphA - Power Factor Phase A | Power Factor Phase A value | float32 | cos()
40126 | 2 | R | 3 | PFphB - Power Factor Phase B | Power Factor Phase B value | float32 | cos()
40128 | 2 | R | 3 | PFphC - Power Factor Phase C | Power Factor Phase C value | float32 | cos()
40130 | 2 | R | 3 | TotWhExp - Total Wh Exported | Total Watt-hours Exported | float32 | Wh
40132 | 2 | R | 3 | TotWhExpPhA - Total Wh Exported phase A | Total Watt-hours Exported phase A | float32 | Wh
40134 | 2 | R | 3 | TotWhExpPhB - Total Wh Exported phase B | Total Watt-hours Exported phase B | float32 | Wh
40136 | 2 | R | 3 | TotWhExpPhC - Total Wh Exported phase C | Total Watt-hours Exported phase C | float32 | Wh
40138 | 2 | R | 3 | TotWhImp - Total Wh Imported | Total Watt-hours Imported | float32 | Wh
40140 | 2 | R | 3 | TotWhImpPhA - Total Wh Imported phase A | Total Watt-hours Imported phase A | float32 | Wh
40142 | 2 | R | 3 | TotWhImpPhB - Total Wh Imported phase B | Total Watt-hours Imported phase B | float32 | Wh
40144 | 2 | R | 3 | TotWhImpPhC - Total Wh Imported phase C | Total Watt-hours Imported phase C | float32 | Wh
40146 | 2 | R | 3 | TotVAhExp - Total VAh Exported | Total VA-hours Exported | float32 | VAh
40148 | 2 | R | 3 | TotVAhExpPhA - Total VAh Exported phase A | Total VA-hours Exported phase A | float32 | VAh
40150 | 2 | R | 3 | TotVAhExpPhB - Total VAh Exported phase B | Total VA-hours Exported phase B | float32 | VAh
40152 | 2 | R | 3 | TotVAhExpPhC - Total VAh Exported phase C | Total VA-hours Exported phase C | float32 | VAh
40154 | 2 | R | 3 | TotVAhImp - Total VAh Imported | Total VA-hours Imported | float32 | VAh
40156 | 2 | R | 3 | TotVAhImpPhA - Total VAh Imported phase A | Total VA-hours Imported phase A | float32 | VAh
40158 | 2 | R | 3 | TotVAhImpPhB - Total VAh Imported phase B | Total VA-hours Imported phase B | float32 | VAh
40160 | 2 | R | 3 | TotVAhImpPhC - Total VAh Imported phase C | Total VA-hours Imported phase C | float32 | VAh
40194 | 2 | R | 3 | Evt - Events | Events (bits 1-19) | uint32 | bitfield32


#### Profile

Name   | Typ
------ | -------
SunSpec.ChaSt.Int | Integer
SunSpec.ID.Int | Integer
SunSpec.StateCodes.Int | Integer
Fronius.StateCodes.Int | Integer
Fronius.AmpereHour.Int | Integer
Fronius.AmpereHour.Float | Float
Fronius.Ampere.Int | Integer
Fronius.Ampere.Float | Float
Fronius.Angle.Int | Integer
Fronius.Blindleistung.Int | Integer
Fronius.Blindleistung.Float | Float
Fronius.Electricity.Int | Integer
Fronius.Electricity.Float | Float
Fronius.Hertz.Int | Integer
Fronius.Ohm.Int | Integer
Fronius.Scheinleistung.Int | Integer
Fronius.Scheinleistung.Float | Float
Fronius.Temperature.Int | Integer
Fronius.Volt.Int | Integer
Fronius.Watt.Int | Integer


### 6. WebFront

Aktuell kein WebFront umgesetzt.


### 7. PHP-Befehlsreferenz

Aktuell keine PHP-Funktionen verfügbar.


### 8. Versionshistorie

#### v1.1
- Fehler #11: Nach IPS Neustart wird je ein weiteres FroniusModbusGateway und FroniusClientSocket erstellt
- Feature Request #10: ScaleFactor (SF) berücksichtigen und Werte entsprechend umrechnen
- Berechnung der Bits PVConn, StorConn, StActCtl und der UTC-Time Tms hinzugefügt
- Profile SunSpec.ChaSt.Int und SunSpec.ID.Int hinzugefügt
- interne Optimierungen

#### v1.0
- Feature Request #6: Unterstützung für 1-phasige Wechselrichter
- Feature Request #7: Erweiterte Wechselrichter Modelle
- Feature Request #8: SmartMeter hinzugefügt
- interne Umstellungen auf gemeinsame Funktionen
- von byName auf byIdent umgestellt
- Profile für Erweiterete Modelle und SmartMeter hinzugefügt

#### v0.5 beta
- Behobene Fehler: #9
- Variable für hostSwapWords für ModBusGateway hinzugefügt
- KR_READY check des KernelRunLevel hinzugefügt

#### v0.4 beta
- Behobene Fehler: #5

#### v0.3
- "Open" Schalter hinzugefügt
- Statusmeldungen "Instanz aktiviert", "Instanz deaktiviert" und "IP oder Port sind nicht erreichbar" hinzugefügt
- Überprüfung von IP und Port hinzugefügt
- Auswertung der Bitfelder Evt1, EvtVnd1, EvtVnd2, EvtVnd3 hinzugefügt
- Einschänkung für eine Fronius-Instanz entfernt
- Tmp Register entfernt (not supported)
- Beschreibenden Text zu den Variablennamen hinzugefügt
- Postfix zu allen Variablen-Profilen hinzugefügt
- Performance-Optimierung
- alte ClientSockets und Modbus-Gateways werden beim Ändern der IP oder Port gelöscht
- Modbus Geräte ID zu Konfigurationsformular hinzugefügt
- Behobene Fehler: #1, #2, #4
