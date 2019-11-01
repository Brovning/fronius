# Fronius
IP-Symcon (IPS) Modul für Fronius Wechselrichter mit TCP ModBus Unterstützung (bspw. Symo, Symo Hybrid,...).


### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

Dieses Modul erstellt anhand der Konfiguration der Fronius Instanz den nötigen Client Socket und das dazugehörige ModBus Gateway. Sofern diese bereits vorhanden sind, werden keine weiteren Client Sockets oder ModBus Gateways erstellt.
Unterhalb der Fronius Instanz werden die Modbus Adressen der Modells Inverter und optional des Modells Nameplate erstellt.

Einschränkung: Aktuell kann nur eine Instanz des Fronius-Moduls erstellt werden!


### 2. Vorraussetzungen

- IP-Symcon ab Version 5.0
- Der Fronius Wechselrichter muss Modbus TCP unterstützen!
- Im Konfigurationsmenü des Fronius Wechselrichters muss unter dem Menüpunkt 'Modbus' die Datenausgabe per 'TCP' und der Sunspec Model Type 'float' aktiviert werden.


### 3. Software-Installation

* Über den Module Store das 'Fronius'-Modul installieren.
* Alternativ über das Module Control folgende URL hinzufügen: https://github.com/Brovning/fronius


### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' ist das 'Fronius'-Modul unter dem Hersteller 'Fronius' aufgeführt.

__Konfigurationsseite__:

Name     | Beschreibung
-------- | ------------------
IP | IP-Adresse des Fronius-Wechselrichters im lokalen Netzwerk
Port | Port, welcher im Wechselrichter unter dem Menüpunkt Modbus angegeben wurde. Default: 502
Nameplate Modell | Sollen die erweiterte Leistungsdaten (bspw. Scheinleistung, Blindleistung, cos(),...) des Nameplate Modell angezeigt werden? Default: false
Abfrage-Intervall | Intervall (in ms) in welchem die Modbus-Adressen abgefragt werden sollen. Achtung: Abfrage-Intervall nicht zu klein wählen, um die Systemlast und auch die Archiv-Größe bei Logging nicht unnötig zu erhöhen! Default: 60000 (=60 Sekunden)


### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen
##### Inverter Model:
Für die Wechselrichter-Daten werden zwei verschiedene SunSpec Models unterstützt:
- das standardmäßig eingestellte Inverter Model mit Gleitkomma-Darstellung (Einstellung „float“; I111, I112 oder I113)

HINWEIS: Die Registeranzahl der beiden Model-Typen ist unterschiedlich!

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

##### optional: Nameplate Model (IC120):
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


#### Profile

Name   | Typ
------ | -------
SunSpec.StateCodes | Integer
Fronius.StateCodes | Integer
Fronius.Scheinleistung | Integer
Fronius.Scheinleistung.Float | Float
Fronius.Blindleistung | Integer
Fronius.Blindleistung.Float | Float
Fronius.Angle | Integer
Fronius.Watt.Int | Integer
Fronius.Ampere.Int | Integer
Fronius.Electricity.Int | Integer
Fronius.AmpereHour.Int | Integer

### 6. WebFront

Aktuell kein WebFront umgesetzt.


### 7. PHP-Befehlsreferenze

Aktuell keine PHP-Funktionen verfügbar.
