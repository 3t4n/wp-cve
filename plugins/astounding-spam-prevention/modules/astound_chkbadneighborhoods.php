<?php
/**********************************************
Every few weeks I generate a list from the latest
Spam statistics and publish it in condensed form.
These are spammy neighborhoods. Mostly Russia,
India, China, Eastern Europe, and even some
US sites.
**********************************************/
if (!defined('ABSPATH')) exit;

class astound_chkbadneighborhoods extends astound_module { 
		public $searchName='Bad Neighborhood';
		public $searchlist=array(

# 001002003004 APNIC Debogon Project AU
'1.2.3.0/24 ',
# 001010184037 TOT Public Company Limited TH
'1.10.128.0/17 ',
# 001032219073 BGP CONSULTANCY PTE LTD KR
'1.32.216.0/21 ',
# 001036063238 Hong Kong Telecommunications (HKT) Limited Mass Internet HK
'1.36.32.0/19 ',
# 001055194055 FPT Telecom Company VN
'1.55.192.0/20 ',
# 001055240156 FPT Telecom Company VN
'1.55.240.0/20 ',
# 001119129002 BeiJing Wish Network Technology CO.,LTD. CN
'1.119.128.0/17 ',
# 001186041103 D-VoiS Broadband Private Limited IN
'1.186.41.0/24 ',
# 001186079034 D-VoiS Broadband Private Limited IN
'1.186.79.0/24 ',
# 001186109208 D-VoiS Broadband Private Limited IN
'1.186.109.0/24 ',
# 001186174221 D-VoiS Broadband Private Limited IN
'1.186.174.0/24 ',
# 001186195111 D-VoiS Broadband Private Limited IN
'1.186.195.0/24 ',
# 002013228079 BSREN654 Rennes Bloc 2 FR
'2.13.0.0/16 ',
# 002055144139 Cellucar subscribers for GGSN RH & YV IL
'2.55.0.0/16 ',
# 002062131220 OJSC "Sibirtelecom" RU
'2.62.128.0/17 ',
# 002064030190 3 Customer dynamic address pool SE
'2.64.0.0/13 ',
# 002088208150 SaudiNet DSL pool_Dynamic IPs SA
'2.88.192.0/19 ',
# 002092030096 Dynamic IP Pool for Broadband Customers RU
'2.92.30.0/24 ',
# 002092142156 Dynamic IP Pool for Broadband Customers RU
'2.92.142.0/24 ',
# 002092145043 Dynamic IP Pool for Broadband Customers RU
'2.92.145.0/24 ',
# 002092153227 Dynamic IP Pool for Broadband Customers RU
'2.92.153.0/24 ',
# 002092161023 Dynamic IP Pool for Broadband Customers RU
'2.92.161.0/24 ',
# 002092175214 Dynamic IP Pool for Broadband Customers RU
'2.92.175.0/24 ',
# 002092221053 Dynamic IP Pool for Broadband Customers RU
'2.92.221.0/24 ',
# 002093088087 Dynamic IP Pool for Broadband Customers RU
'2.93.88.0/24 ',
# 002093096017 Dynamic IP Pool for Broadband Customers RU
'2.93.96.0/24 ',
# 002093103050 Dynamic IP Pool for Broadband Customers RU
'2.93.103.0/24 ',
# 002093134007 Dynamic IP Pool for Broadband Customers RU
'2.93.134.0/24 ',
# 002093196076 Dynamic IP Pool for Broadband Customers RU
'2.93.196.0/24 ',
# 002094124008 Dynamic IP Pool for Broadband Customers RU
'2.94.124.0/24 ',
# 002094169112 Dynamic IP Pool for Broadband Customers RU
'2.94.169.0/24 ',
# 002094177244 Dynamic IP Pool for Broadband Customers RU
'2.94.177.0/24 ',
# 002150068038 Telenor Norge AS NO
'2.148.0.0/14 ',
# 005001088184 GaiacomLC GB
'5.1.88.0/24 ',
# 005002070013 LITESERVER-Route NL
'5.2.64.0/20 ',
# 005003114057 CJSC "ER-Telecom Holding" Tyumen' branch RU
'5.3.112.0/22 ',
# 005003117066 CJSC "ER-Telecom Holding" Tyumen' branch RU
'5.3.116.0/22 ',
# 005003127073 CJSC "ER-Telecom Holding" Tyumen' branch RU
'5.3.124.0/22 ',
# 005003130173 JSC "ER-Telecom Holding" Volgograd branch RU
'5.3.128.0/22 ',
# 005003132042 JSC "ER-Telecom Holding" Volgograd branch RU
'5.3.132.0/22 ',
# 005003136099 JSC "ER-Telecom Holding" Volgograd branch RU
'5.3.136.0/22 ',
# 005003173102 JSC "ER-Telecom Holding" Voronezh branch RU
'5.3.172.0/22 ',
# 005003186242 JSC "ER-Telecom Holding" Tyumen' branch RU
'5.3.184.0/22 ',
# 005003192133 JSC "ER-Telecom Holding" Nizhny Novgorod branch RU
'5.3.192.0/22 ',
# 005003196055 JSC "ER-Telecom Holding" Nizhny Novgorod branch RU
'5.3.196.0/22 ',
# 005003200019 JSC "ER-Telecom Holding" Nizhny Novgorod branch RU
'5.3.200.0/22 ',
# 005003204081 JSC "ER-Telecom Holding" Nizhny Novgorod branch RU
'5.3.204.0/22 ',
# 005003208003 JSC "ER-Telecom Holding" Nizhny Novgorod branch RU
'5.3.208.0/22 ',
# 005003212069 JSC "ER-Telecom Holding" Nizhny Novgorod branch RU
'5.3.212.0/22 ',
# 005003216018 JSC "ER-Telecom Holding" Nizhny Novgorod branch RU
'5.3.216.0/22 ',
# 005003220047 JSC "ER-Telecom Holding" Nizhny Novgorod branch RU
'5.3.220.0/22 ',
# 005016000047 Enforta RU
'5.16.0.0/21 ',
# 005028062085 BIGV1-YRK-VMACC1 GB
'5.28.56.0/21 ',
# 005028212226 IR
'5.28.208.0/21 ',
# 005029208049 BROADBAND IL
'5.29.208.0/20 ',
# 005034246020 InterConnects SE
'5.34.246.0/24 ',
# 005034247063 InterConnects IE
'5.34.247.0/24 ',
# 005042246046 e-Force-Est SA
'5.42.240.0/21 ',
# 005044170126 Sibirskie Seti RU
'5.44.170.0/23 ',
# 005055248208 Hellas On Line S.A. GR
'5.55.224.0/19 ',
# 005061243231 PRINTER-FAIR-NET-NS Subscribers HU
'5.61.240.0/21 ',
# 005062152072 Route RU
'5.62.152.0/24 ',
# 005062153057 Los Angeles Network US
'5.62.153.0/24 ',
# 005062154011 Riga Network LV
'5.62.154.0/24 ',
# 005062155012 Route RU
'5.62.155.0/24 ',
# 005062156042 Iowa Network US
'5.62.156.0/24 ',
# 005062157015 RUNet RU
'5.62.157.0/24 ',
# 005062158015 Ogden Utah Network US
'5.62.158.0/24 ',
# 005062159014 EUNet DE
'5.62.159.0/24 ',
# 005074080072 Telecommunication Company of Tehran IR
'5.74.0.0/17 ',
# 005075077222 Esfahan Telecom ADSL IR
'5.75.64.0/19 ',
# 005076225131 JSC Kazakhtelecom, KZ
'5.76.224.0/21 ',
# 005076237108 JSC Kazakhtelecom, KZ
'5.76.232.0/21 ',
# 005080226004 BT-Central-Plus GB
'5.80.0.0/15 ',
# 005083082227 Cable customers - Marchena ES
'5.83.64.0/19 ',
# 005100124087 VLADLINK-IPOE-SUBSCRIBERS RU
'5.100.124.0/24 ',
# 005101066106 public vlans of DC RU
'5.101.66.0/24 ',
# 005101122074 Network for dc infrastructure EE
'5.101.122.0/24 ',
# 005101213049 KerchNET-PLUS-7 Network RU
'5.101.212.0/22 ',
# 005101217017 UzbekistanNetwork UZ
'5.101.217.0/24 ',
# 005101218053 Rostov-Don-Network RU
'5.101.218.0/24 ',
# 005101219046 Koh Samui Network TH
'5.101.219.0/24 ',
# 005101220045 ZugdidiNet GE
'5.101.220.0/24 ',
# 005101221019 CyprusNetwork CY
'5.101.221.0/24 ',
# 005101222015 Yerevan Network AM
'5.101.222.0/24 ',
# 005112207055 Iran Cell Service and Communication Company IR
'5.112.192.0/20 ',
# 005113251097 Iran Cell Service and Communication Company IR
'5.113.240.0/20 ',
# 005114083097 Iran Cell Service and Communication Company IR
'5.114.80.0/20 ',
# 005115124149 Iran Cell Service and Communication Company IR
'5.115.112.0/20 ',
# 005132004130 T-Mobile-Thuis-BV NL
'5.132.0.0/17 ',
# 005133008143 VPNonline PL
'5.133.8.0/24 ',
# 005133011136 Edelino Commerce Inc. PL
'5.133.11.0/24 ',
# 005133012062 Artnet Sp. z o.o. PL
'5.133.12.32/27 ',
# 005134079126 TV-EURO-SAT Marek Gzowski subscribers PL
'5.134.72.0/21 ',
# 005134111028 RADIJUS VEKTOR network RS
'5.134.111.0/24 ',
# 005134119230 PRIVAX ES
'5.134.119.0/24 ',
# 005141008166 Dynamic distribution IP's for broadband services RU
'5.141.8.0/24 ',
# 005145170019 Comvive Servidores S.L. ES
'5.145.168.0/21 ',
# 005148119134 EE Customer GB
'5.148.0.0/17 ',
# 005148165013 Nine Internet Solutions AG CH
'5.148.160.0/19 ',
# 005149248036 HZ-HOSTING-LTD NL
'5.149.248.0/23 ',
# 005152142153 alternatYva S.r.l. IT
'5.152.140.0/22 ',
# 005153118097 BELPNET CH
'5.153.112.0/20 ',
# 005154190139 NAV Communications - nav.ro MD
'5.154.190.0/24 ',
# 005156023092 SAUDINET-STC SA
'5.156.23.0/24 ',
# 005157006051 Reverse-Proxy SE
'5.157.6.0/24 ',
# 005157007019 Reverse-Proxy SE
'5.157.7.0/24 ',
# 005157052049 SE
'5.157.52.0/24 ',
# 005157053110 Virtual Service Provider SE
'5.157.53.0/24 ',
# 005157055072 Ireland IE
'5.157.55.0/24 ',
# 005158099072 Pride Limited company RU
'5.158.99.0/24 ',
# 005160041162 Respina-Route IR
'5.160.40.0/23 ',
# 005160113205 Faratar Co. IR
'5.160.113.0/24 ',
# 005160114203 Faratar Co. IR
'5.160.114.0/24 ',
# 005160115137 Respina Co. IR
'5.160.115.0/24 ',
# 005160169129 ADSL Mashhad IR
'5.160.169.0/24 ',
# 005161058127 Asiatech Network IR
'5.161.32.0/19 ',
# 005165075240 CJSC "ER-Telecom Holding" Voronezh branch RU
'5.165.72.0/22 ',
# 005166083024 CJSC "ER-Telecom Holding" Barnaul branch RU
'5.166.80.0/22 ',
# 005166172246 Individual PPPoE customers RU
'5.166.172.0/22 ',
# 005167197226 JSC "ER-Telecom Holding" Volgograd branch RU
'5.167.196.0/22 ',
# 005167201134 JSC "ER-Telecom Holding" Volgograd branch RU
'5.167.200.0/22 ',
# 005167207106 JSC "ER-Telecom Holding" Volgograd branch RU
'5.167.204.0/22 ',
# 005167216048 JSC "ER-Telecom Holding" Volgograd branch RU
'5.167.216.0/22 ',
# 005167223032 JSC "ER-Telecom Holding" Volgograd branch RU
'5.167.220.0/22 ',
# 005175005162 Host Europe GmbH DE
'5.175.0.0/19 ',
# 005175229021 TIGONA GmbH - Colocation Serverhosting & more � www.tigona.de � DE
'5.175.229.0/24 ',
# 005176089001 AVEA Iletisim Hizmetleri A.S. TR
'5.176.0.0/15 ',
# 005186236054 FIBIA Broadband DHCP DK
'5.186.0.0/16 ',
# 005187021045 CSLI::NET GB
'5.187.21.0/24 ',
# 005187069092 Svyaz-Telecom RU
'5.187.68.0/22 ',
# 005187078091 Svyaz-Telecom RU
'5.187.78.0/24 ',
# 005188153228 NLS Networks for Clients KZ
'5.188.153.0/24 ',
# 005188211010 RU
'5.188.211.0/24 ',
# 005188216013 NL
'5.188.216.0/24 ',
# 005188219012 net for depo40.ru RU
'5.188.219.0/24 ',
# 005188232010 NL
'5.188.232.0/24 ',
# 005189145010 Contabo GmbH DE
'5.189.144.0/20 ',
# 005189165154 Contabo GmbH DE
'5.189.160.0/20 ',
# 005189205019 Canada Vancouver Network CA
'5.189.205.0/24 ',
# 005189206023 Network Alaska US US
'5.189.206.0/24 ',
# 005202184227 PTS-Network IR
'5.202.184.0/23 ',
# 005206231117 COLOCATION CUSTOMERS PT
'5.206.231.0/24 ',
# 005219077075 TCE ADSL Dynamic IR
'5.219.64.0/18 ',
# 005230031036 GHOSTnet Network used for VPS Hosting Services DE
'5.230.31.0/24 ',
# 005230133009 Alpha Geek Solutions, LLC GB
'5.230.133.0/24 ',
# 005230153012 Alpha Geek Solutions, LLC GB
'5.230.153.0/24 ',
# 005230154184 GHOSTnet Network used for VPS Hosting Services DE
'5.230.154.0/24 ',
# 005230195159 GHOSTnet Network used for VPS Hosting Services DE
'5.230.195.0/24 ',
# 005230208010 GHOSTnet Network used for VPS Hosting Services DE
'5.230.208.0/24 ',
# 005231079138 GHOSTnet Network used for VPS Hosting Services NL
'5.231.79.0/24 ',
# 005231088088 GHOSTnet Network used for VPS Hosting Services DE
'5.231.88.0/24 ',
# 005231237068 Alpha Geek Solutions, LLC GB
'5.231.237.0/24 ',
# 005254065015 IPs used by the customers of voxility.com RO
'5.254.65.0/24 ',
# 005254079066 IPs used by the customers of voxility.com RO
'5.254.79.0/24 ',
# 005254086213 IPs used by the customers of voxility.com RO
'5.254.86.0/24 ',
# 005254089171 IPs used by the customers of voxility.com RO
'5.254.88.0/23 ',
# 005254112154 Voxility SRL GB
'5.254.112.0/24 ',
# 014001101007 Angel Drops Ltd. BD
'14.1.101.0/24 ',
# 014003026117 ASAHI Net,Inc. JP
'14.3.0.0/16 ',
# 014096224200 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.96.224.0/20 ',
# 014097017166 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.97.16.0/20 ',
# 014097053039 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.97.48.0/20 ',
# 014097065028 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.97.64.0/20 ',
# 014097088254 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.97.80.0/20 ',
# 014097128248 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.97.128.0/20 ',
# 014097161031 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.97.160.0/20 ',
# 014097194209 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.97.192.0/20 ',
# 014097228055 TATA TELESERVICES LTD - TATA INDICOM - CDMA DIVISION IN
'14.97.224.0/20 ',
# 014192131186 Internet Service Provider PK
'14.192.131.0/24 ',
# 014194065130 Tata Teleservices Limited -GSM Division IN
'14.194.64.0/20 ',
# 014195224066 DELHI GSM IP POOL IN
'14.195.224.0/19 ',
# 014226086116 VietNam Post and Telecom Corporation VN
'14.224.0.0/11 ',
# 023090028228 ServerHub Dallas CUST-NETBLK-PHX-23-90-28-0-22 (NET-23-90-28-0-1) US
'23.90.28.0/22 ',
# 023090046226 Eonix Corporation US
'23.90.0.0/18 ',
# 023092009041 WireStar, Inc. US
'23.92.0.0/20 ',
# 023092112003 CachedNet LLC US
'23.92.112.0/20 ',
# 023225163030 CloudRadium L.L.C US
'23.224.0.0/15 ',
# 023226081133 Plexicomm, LLC US
'23.226.80.0/20 ',
# 023226184075 XeVPS L.L.C XEVPS-01 (NET-23-226-176-0-1) US
'23.226.176.0/20 ',
# 023248163151 Zenlayer Inc ZL-TTT-002 (NET-23-248-160-0-1) US
'23.248.160.0/19 ',
# 023252054178 Condointernet.net US
'23.252.48.0/20 ',
# 024051049219 Eagle Communications, Inc. US
'24.51.48.0/23 ',
# 024055136247 PenTeleData Inc. PENTEL-CABLE (NET-24-55-128-0-1) US
'24.55.128.0/19 ',
# 024132031147 CPE Customers NL NL
'24.132.0.0/17 ',
# 024133051026 TURKSAT Cable Internet TR
'24.133.48.0/22 ',
# 024144057056 Conway Corporation US
'24.144.0.0/18 ',
# 024170237246 Antietam Cable Television, Inc ANTIETAM-BLK-III (NET-24-170-224-0-1) US
'24.170.224.0/19 ',
# 024204055003 Network Communications NETWORK-COMMUNICATIONS (NET-24-204-48-0-1) US
'24.204.48.0/20 ',
# 024214133150 WideOpenWest Finance LLC WIDEOPENWEST (NET-24-214-74-0-1) US
'24.214.74.0/16 ',
# 024214217079 Wide Open West SC-CHAR (NET-24-214-216-0-1) US
'24.214.216.0/22 ',
# 024214241129 Wide Open West FL-PANA (NET-24-214-241-0-2) US
'24.214.241.0/24 ',
# 024239115059 Armstrong Cable Services ACS-BOARDMANOH (NET-24-239-115-0-1) US
'24.239.115.0/24 ',
# 024239116013 Armstrong Cable Services ACS-CRANBERRYPA (NET-24-239-116-0-1) US
'24.239.116.0/24 ',
# 024246255166 PenTeleData Inc. PENTEL-CABLE (NET-24-246-224-0-1) US
'24.246.224.0/19 ',
# 027000049068 Vasai Cable Pvt. Ltd. IN
'27.0.49.0/24 ',
# 027000136007 XENON COMMUNICATIONS IN
'27.0.136.0/22 ',
# 027000177131 Sikka Infratech Pvt. Ltd IN
'27.0.177.0/24 ',
# 027000232151 ONEPROVIDER HK
'27.0.232.0/24 ',
# 027000250216 NITISH TRADING HOUSE IN
'27.0.248.0/22 ',
# 027004032065 Hathway Cable and Datacom Pvt Ltd IN
'27.4.32.0/24 ',
# 027004069128 Hathway Cable and Datacom Pvt Ltd IN
'27.4.69.0/24 ',
# 027004162125 Hathway Cable and Datacom Pvt Ltd IN
'27.4.162.0/24 ',
# 027004163020 Hathway Cable and Datacom Pvt Ltd IN
'27.4.163.0/24 ',
# 027004225027 Hathway Cable and Datacom Pvt Ltd IN
'27.4.225.0/24 ',
# 027005004241 Hathway Cable and Datacom Pvt Ltd IN
'27.5.4.0/24 ',
# 027005005199 Hathway Cable and Datacom Pvt Ltd IN
'27.5.5.0/24 ',
# 027005008127 Hathway Cable and Datacom Pvt Ltd IN
'27.5.8.0/24 ',
# 027005023238 Hathway Cable and Datacom Pvt Ltd IN
'27.5.23.0/24 ',
# 027005024032 Hathway Cable and Datacom Pvt Ltd IN
'27.5.24.0/24 ',
# 027005025031 Hathway Cable and Datacom Pvt Ltd IN
'27.5.25.0/24 ',
# 027005026000 Hathway Cable and Datacom Pvt Ltd IN
'27.5.26.0/24 ',
# 027005027012 Hathway Cable and Datacom Pvt Ltd IN
'27.5.27.0/24 ',
# 027005028001 Hathway Cable and Datacom Pvt Ltd IN
'27.5.28.0/24 ',
# 027005029012 Hathway Cable and Datacom Pvt Ltd IN
'27.5.29.0/24 ',
# 027005030116 Hathway Cable and Datacom Pvt Ltd IN
'27.5.30.0/24 ',
# 027005031000 Hathway Cable and Datacom Pvt Ltd IN
'27.5.31.0/24 ',
# 027005032084 Hathway Cable and Datacom Pvt Ltd IN
'27.5.32.0/24 ',
# 027005132132 Hathway Cable and Datacom Pvt Ltd IN
'27.5.132.0/24 ',
# 027006041239 Hathway Cable and Datacom Pvt Ltd IN
'27.6.41.0/24 ',
# 027006248222 Hathway Cable and Datacom Pvt Ltd IN
'27.6.248.0/24 ',
# 027007001039 Hathway Cable and Datacom Pvt Ltd IN
'27.7.1.0/24 ',
# 027007194163 Hathway Cable and Datacom Pvt Ltd IN
'27.7.194.0/24 ',
# 027007195015 Hathway Cable and Datacom Pvt Ltd IN
'27.7.195.0/24 ',
# 027007243045 Hathway Cable and Datacom Pvt Ltd IN
'27.7.243.0/24 ',
# 027007247230 Hathway Cable and Datacom Pvt Ltd IN
'27.7.247.0/24 ',
# 027007253061 Hathway Cable and Datacom Pvt Ltd IN
'27.7.253.0/24 ',
# 027039150061 China Unicom Guangdong province network CN
'27.39.128.0/17 ',
# 027054178131 INPL'S IP POOL IN
'27.54.178.0/24 ',
# 027056128200 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.128.0/24 ',
# 027056129138 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.129.0/24 ',
# 027056132049 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.132.0/24 ',
# 027056134033 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.134.0/24 ',
# 027056135114 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.135.0/24 ',
# 027056136235 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.136.0/24 ',
# 027056137068 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.137.0/24 ',
# 027056138074 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.138.0/24 ',
# 027056139185 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.139.0/24 ',
# 027056144041 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.144.0/24 ',
# 027056145097 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.145.0/24 ',
# 027056146159 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.146.0/24 ',
# 027056147189 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.147.0/24 ',
# 027056149049 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.149.0/24 ',
# 027056152164 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.152.0/24 ',
# 027056155200 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.155.0/24 ',
# 027056156009 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.156.0/24 ',
# 027056158057 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.158.0/24 ',
# 027056159012 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.159.0/24 ',
# 027056160049 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.160.0/24 ',
# 027056161143 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.161.0/24 ',
# 027056162158 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.162.0/24 ',
# 027056163244 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.163.0/24 ',
# 027056164166 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.164.0/24 ',
# 027056167213 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.167.0/24 ',
# 027056168002 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.168.0/24 ',
# 027056170090 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.170.0/24 ',
# 027056173061 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.173.0/24 ',
# 027056176182 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.176.0/24 ',
# 027056177146 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.177.0/24 ',
# 027056178239 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.178.0/24 ',
# 027056180069 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.180.0/24 ',
# 027056181235 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.181.0/24 ',
# 027056184006 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.184.0/24 ',
# 027056185015 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.185.0/24 ',
# 027056186081 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.186.0/24 ',
# 027056187222 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.187.0/24 ',
# 027056188017 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.188.0/24 ',
# 027056189079 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.189.0/24 ',
# 027056190175 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.190.0/24 ',
# 027056191022 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'27.56.191.0/24 ',
# 027057133045 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.133.0/24 ',
# 027057135147 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.135.0/24 ',
# 027057136236 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.136.0/24 ',
# 027057145002 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.145.0/24 ',
# 027057146108 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.146.0/24 ',
# 027057149119 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.149.0/24 ',
# 027057165056 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.165.0/24 ',
# 027057170131 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.170.0/24 ',
# 027057172118 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.172.0/24 ',
# 027057180061 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.180.0/24 ',
# 027057182018 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.182.0/24 ',
# 027057191073 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'27.57.191.0/24 ',
# 027063129038 BCL EAST,7th Floor,Infinity Towers,salt Lake,Sector-V,Electronic Complex,Kolkata,WestBengal IN
'27.62.0.0/15 ',
# 027095017123 KDDI CORPORATION JP
'27.80.0.0/12 ',
# 027097159041 GPRS Karnataka Mobile Subscriber IP IN
'27.97.156.0/22 ',
# 027106108117 Syscon Infoway Pvt. Ltd. IN
'27.106.108.0/24 ',
# 027109019234 Blazenet Pvt Ltd IN
'27.109.19.0/24 ',
# 027113254230 SANCHARONOLINE IP POOL IN
'27.113.254.0/24 ',
# 027118026094 Hanel Communication JSC VN
'27.118.16.0/20 ',
# 027121212142 Rakuten Broadband Dynamic Endpoints JP
'27.121.192.0/18 ',
# 027123255130 Kazi Sazzad Hossain T/A Millennium Computers & Networking BD
'27.123.255.0/24 ',
# 027147142050 Dhanmondi-Area-Corporate-User BD
'27.147.142.0/24 ',
# 027147206039 Link3 Technologies Limited BD
'27.147.206.0/24 ',
# 027250014075 Aircel Limited DLF Cyber City Building No. 10 A, 5th Avenue Floor Gurgaon-122001 IN
'27.250.14.0/24 ',
# 027251192050 Dishnet Wireless Limited IN
'27.251.192.0/24 ',
# 027254153131 CSLOXINFO-IDC TH
'27.254.153.0/24 ',
# 027255018247 Fiberlink Pvt.Ltd PK
'27.255.18.0/24 ',
# 027255169104 Chandigarh IN
'27.255.169.0/24 ',
# 027255174090 Chandigarh IN
'27.255.174.0/24 ',
# 027255176118 Chandigarh IN
'27.255.176.0/24 ',
# 027255177231 Chandigarh IN
'27.255.177.0/24 ',
# 027255178107 Chandigarh IN
'27.255.178.0/24 ',
# 027255179161 Chandigarh IN
'27.255.179.0/24 ',
# 027255181209 Chandigarh IN
'27.255.181.0/24 ',
# 027255182056 Chandigarh IN
'27.255.182.0/24 ',
# 027255184013 Chandigarh IN
'27.255.184.0/24 ',
# 027255185043 Chandigarh IN
'27.255.185.0/24 ',
# 027255186150 Chandigarh IN
'27.255.186.0/24 ',
# 027255187250 Chandigarh IN
'27.255.187.0/24 ',
# 027255188028 Chandigarh IN
'27.255.188.0/24 ',
# 027255190066 Chandigarh IN
'27.255.190.0/24 ',
# 027255191116 Chandigarh IN
'27.255.191.0/24 ',
# 027255192005 Chandigarh IN
'27.255.192.0/24 ',
# 027255193191 Chandigarh IN
'27.255.193.0/24 ',
# 027255195227 Chandigarh IN
'27.255.195.0/24 ',
# 027255196171 Chandigarh IN
'27.255.196.0/24 ',
# 027255197209 Chandigarh IN
'27.255.197.0/24 ',
# 027255198091 Chandigarh IN
'27.255.198.0/24 ',
# 027255199073 Chandigarh IN
'27.255.199.0/24 ',
# 027255200184 Chandigarh IN
'27.255.200.0/24 ',
# 027255202114 Chandigarh IN
'27.255.202.0/24 ',
# 027255203087 Chandigarh IN
'27.255.203.0/24 ',
# 027255205061 Chandigarh IN
'27.255.205.0/24 ',
# 027255206230 Chandigarh IN
'27.255.206.0/24 ',
# 027255210220 Chandigarh IN
'27.255.210.0/23 ',
# 027255212109 Chandigarh IN
'27.255.212.0/24 ',
# 027255214017 Chandigarh IN
'27.255.214.0/24 ',
# 027255216205 Chandigarh IN
'27.255.216.0/24 ',
# 027255217017 Chandigarh IN
'27.255.217.0/24 ',
# 027255219130 Chandigarh IN
'27.255.219.0/24 ',
# 027255220079 Chandigarh IN
'27.255.220.0/24 ',
# 027255223001 Chandigarh IN
'27.255.223.0/24 ',
# 027255230026 Chandigarh IN
'27.255.230.0/24 ',
# 031002175229 new service for data IR
'31.2.128.0/18 ',
# 031006014005 RO Dynamic IPs RO
'31.6.14.0/24 ',
# 031006022016 IT Dynamic IPs IT
'31.6.22.0/24 ',
# 031006045103 SE Dynamic IPs SE
'31.6.45.0/24 ',
# 031007064200 IR
'31.7.64.0/19 ',
# 031013133114 High technology park IT-park RU
'31.13.133.0/24 ',
# 031014070170 XT GLOBAL NETWORKS LTD ES
'31.14.68.0/22 ',
# 031014146061 Telecommunication Company of Khorasan Razavi IR
'31.14.144.0/21 ',
# 031014255147 XT GLOBAL NETWORKS LTD IN
'31.14.255.0/24 ',
# 031022181015 MEO Mobile Customers PT
'31.22.128.0/17 ',
# 031024057091 Maardu LAN EE
'31.24.56.0/21 ',
# 031024148037 This is a tor node. https://www.torproject.org/ DE
'31.24.144.0/21 ',
# 031028101202 VPN (PPPoE) customers Sverdlovsk reg. "Interra" Ltd. RU
'31.28.96.0/21 ',
# 031028210163 OJSC Rostelecom, Vladimir branch RU
'31.28.192.0/19 ',
# 031041047022 RelinkRoute RU
'31.41.40.0/21 ',
# 031041219253 Besthosting IP block UA
'31.41.216.0/21 ',
# 031042122135 Customers UA
'31.42.112.0/20 ',
# 031043038155 UTG B1-SR1 UA
'31.43.32.0/21 ',
# 031043066112 KOM i TEX (Lviv Local Area Network) UA
'31.43.66.0/24 ',
# 031043068116 KOM i TEX (Lviv Local Area Network) UA
'31.43.68.0/24 ',
# 031043076010 KOM i TEX (Lviv Local Area Network) UA
'31.43.76.0/24 ',
# 031043079002 KOM i TEX (Lviv Local Area Network) UA
'31.43.79.0/24 ',
# 031043080222 KOM i TEX (Lviv Local Area Network) UA
'31.43.80.0/24 ',
# 031043084061 KOM i TEX (Lviv Local Area Network) UA
'31.43.84.0/24 ',
# 031043091119 KOM i TEX (Lviv Local Area Network) UA
'31.43.91.0/24 ',
# 031044180233 net Kometa RU
'31.44.180.0/23 ',
# 031044189102 net for Paylicense CH
'31.44.189.0/24 ',
# 031044190161 net for Paylicense UA
'31.44.190.0/24 ',
# 031047034038 AFranet Co IR
'31.47.34.0/24 ',
# 031047076014 Cloud Servers ES
'31.47.72.0/21 ',
# 031047160025 TTK-Baikal/BRAS in Irkutsk RU
'31.47.160.0/19 ',
# 031057215249 SHATEL DSL Network IR
'31.57.215.0/24 ',
# 031059018139 SHATEL DSL Network IR
'31.59.18.0/24 ',
# 031129244193 ISP Syndicate UA
'31.129.244.0/24 ',
# 031130000041 LLC "Group Tower Telecom" (Balancing pool) RU
'31.130.0.0/24 ',
# 031130001184 LLC "Group Tower Telecom" (Balancing pool) RU
'31.130.1.0/24 ',
# 031130002032 LLC "Group Tower Telecom" (Balancing pool) RU
'31.130.2.0/24 ',
# 031130006070 LLC "Group Tower Telecom" (Balancing pool) RU
'31.130.4.0/22 ',
# 031130009016 LLC "Group Tower Telecom" (Balancing pool) RU
'31.130.8.0/22 ',
# 031130016142 LLC "Group Tower Telecom" (Balancing pool) RU
'31.130.16.0/20 ',
# 031130156249 Starlink Yauza route RU
'31.130.128.0/19 ',
# 031131004167 IM LEVEL7 SRL MD
'31.131.4.0/23 ',
# 031134124238 FOP Demchuk Sergiy Olexandrovuch UA
'31.134.120.0/21 ',
# 031135224000 Trytek Internet provider RU
'31.135.224.0/20 ',
# 031145042190 BIZIMINTERNETBILISIM TR
'31.145.0.0/16 ',
# 031148029021 BershNet Ltd. UA
'31.148.28.0/23 ',
# 031148099123 PE Gornostay Mikhailo Ivanovich UA
'31.148.99.0/24 ',
# 031148122226 Dialog Ltd. RU
'31.148.120.0/21 ',
# 031167049100 Etihad Etisalat SA
'31.167.32.0/19 ',
# 031168217079 BEZEQ-INTERNATIONAL-LTD IL
'31.168.208.0/20 ',
# 031170104051 WB24.RU Servers DE
'31.170.104.0/21 ',
# 031171194019 ZAO EnergoGarantService-Samara RU
'31.171.192.0/21 ',
# 031172177149 Fibertech Networks Sp. z o.o. PL
'31.172.176.0/22 ',
# 031173069156 GPRS pool for APN fixedIP.msk RU
'31.173.64.0/21 ',
# 031173080013 Metropolitan branch of OJSC MegaFon AS25159 31.173.80.0/21 RU
'31.173.80.0/21 ',
# 031173218199 Caucasus Branch of OJSC MegaFon, Fixed Broabband RU
'31.173.218.0/24 ',
# 031173221140 Caucasus Branch of OJSC MegaFon, Fixed Broabband RU
'31.173.221.0/24 ',
# 031173241003 Siberian Branch of OJSC MegaFon - Mobile Clients RU
'31.173.240.0/23 ',
# 031184243234 net for takewyn RU
'31.184.243.0/24 ',
# 031185104019 Digitalcourage e.V. DE
'31.185.104.0/21 ',
# 031187066249 Purple RAIN Networks US
'31.187.66.0/24 ',
# 031192111191 abuse-mailbox: abuse@hostkey.com RU
'31.192.111.0/24 ',
# 031192202163 Zitius stadsnat SE
'31.192.192.0/20 ',
# 031192241006 abstationDC UK GB
'31.192.241.0/24 ',
# 031193090232 GORODOK-NETWORK UA
'31.193.90.0/24 ',
# 031200085013 Dogan Iletisim Elektronik Servis Hizmetleri A.S. DOL VAE TR
'31.200.64.0/19 ',
# 031202128245 Maxnet LLC, Kharkiv UA
'31.202.128.0/18 ',
# 031207033099 FR
'31.207.32.0/21 ',
# 031210091073 RadoreCloud - IPv4 Network TR
'31.210.91.0/24 ',
# 031214152205 MUVHost - DGN Route - 31.214.152.0/24 TR
'31.214.152.0/24 ',
# 031214244113 Serverdiscounter.com DE
'31.214.240.0/21 ',
# 031216161236 Closed Joint Stock Company SibTransTelecom. RU
'31.216.161.0/24 ',
# 031220015145 HostHatch Inc SE
'31.220.15.0/24 ',
# 036001190226 China Telecom CN
'36.1.128.0/17 ',
# 036066038186 PT TELKOM INDONESIA ID
'36.66.32.0/20 ',
# 036066055197 PT TELKOM INDONESIA ID
'36.66.48.0/20 ',
# 036066064074 PT TELKOM INDONESIA ID
'36.66.64.0/20 ',
# 036066083151 PT TELKOM INDONESIA ID
'36.66.80.0/20 ',
# 036066114225 PT TELKOM INDONESIA ID
'36.66.112.0/20 ',
# 036066134090 PT TELKOM INDONESIA ID
'36.66.128.0/20 ',
# 036066170127 PT TELKOM INDONESIA ID
'36.66.160.0/20 ',
# 036066203011 PT TELKOM INDONESIA ID
'36.66.192.0/20 ',
# 036066208010 PT TELKOM INDONESIA ID
'36.66.208.0/20 ',
# 036066234037 PT TELKOM INDONESIA ID
'36.66.224.0/20 ',
# 036066242074 PT TELKOM INDONESIA ID
'36.66.240.0/20 ',
# 036067020229 PT TELKOM INDONESIA ID
'36.67.16.0/20 ',
# 036067032209 PT TELKOM INDONESIA ID
'36.67.32.0/20 ',
# 036067050242 PT TELKOM INDONESIA ID
'36.67.48.0/20 ',
# 036067078225 PT TELKOM INDONESIA ID
'36.67.64.0/20 ',
# 036067084109 PT TELKOM INDONESIA ID
'36.67.80.0/20 ',
# 036067096217 PT TELKOM INDONESIA ID
'36.67.96.0/20 ',
# 036067128145 PT TELKOM INDONESIA ID
'36.67.128.0/20 ',
# 036067144161 PT TELKOM INDONESIA ID
'36.67.144.0/20 ',
# 036067161226 PT TELKOM INDONESIA ID
'36.67.160.0/20 ',
# 036068065140 PT TELKOM INDONESIA ID
'36.68.64.0/21 ',
# 036068076002 PT TELKOM INDONESIA ID
'36.68.72.0/21 ',
# 036069004113 PT TELKOM INDONESIA ID
'36.69.0.0/21 ',
# 036069013076 PT TELKOM INDONESIA ID
'36.69.8.0/21 ',
# 036069156051 PT TELKOM INDONESIA ID
'36.69.144.0/20 ',
# 036070089070 PT TELKOM INDONESIA ID
'36.70.88.0/22 ',
# 036070182049 PT TELKOM INDONESIA ID
'36.70.176.0/21 ',
# 036070215028 PT TELKOM INDONESIA ID
'36.70.208.0/20 ',
# 036071118004 PT TELKOM INDONESIA ID
'36.71.112.0/21 ',
# 036071244101 PT TELKOM INDONESIA ID
'36.71.240.0/21 ',
# 036071254168 PT TELKOM INDONESIA ID
'36.71.248.0/21 ',
# 036072180125 PT TELKOM INDONESIA ID
'36.72.180.0/23 ',
# 036073173111 PT TELKOM INDONESIA ID
'36.73.168.0/21 ',
# 036075066231 PT TELKOM INDONESIA ID
'36.75.64.0/22 ',
# 036075136246 PT TELKOM INDONESIA ID
'36.75.136.0/22 ',
# 036075141098 PT TELKOM INDONESIA ID
'36.75.140.0/22 ',
# 036075147056 PT TELKOM INDONESIA ID
'36.75.144.0/21 ',
# 036077167126 PT TELKOM INDONESIA ID
'36.77.160.0/20 ',
# 036077203162 PT TELKOM INDONESIA ID
'36.77.192.0/20 ',
# 036078034231 PT TELKOM INDONESIA ID
'36.78.32.0/20 ',
# 036079040102 PT TELKOM INDONESIA ID
'36.79.32.0/20 ',
# 036079054219 PT TELKOM INDONESIA ID
'36.79.48.0/20 ',
# 036079182066 PT TELKOM INDONESIA ID
'36.79.182.0/23 ',
# 036079206199 PT TELKOM INDONESIA ID
'36.79.200.0/21 ',
# 036081189058 PT TELKOM INDONESIA ID
'36.81.188.0/22 ',
# 036081197174 PT TELKOM INDONESIA ID
'36.81.192.0/21 ',
# 036082078061 PT TELKOM INDONESIA ID
'36.82.76.0/22 ',
# 036083157113 PT TELKOM INDONESIA ID
'36.83.144.0/20 ',
# 036083229210 PT TELKOM INDONESIA ID
'36.83.224.0/20 ',
# 036084029092 PT TELKOM INDONESIA ID
'36.84.29.0/24 ',
# 036084067123 PT TELKOM INDONESIA ID
'36.84.64.0/21 ',
# 036084190239 PT TELKOM INDONESIA ID
'36.84.184.0/21 ',
# 036085108005 PT TELKOM INDONESIA ID
'36.85.108.0/22 ',
# 036085114157 PT TELKOM INDONESIA ID
'36.85.112.0/21 ',
# 036085244008 PT TELKOM INDONESIA ID
'36.85.244.0/23 ',
# 036149000233 China Mobile Communications Corporation CN
'36.128.0.0/11 ',
# 036255098148 Kill Ping PK
'36.255.96.0/22 ',
# 036255158027 DISHAWAVES INFONET PVT. LTD IN
'36.255.158.0/24 ',
# 037008035092 Hadara Gaza BSA PS
'37.8.32.0/20 ',
# 037009040031 RUNet RU
'37.9.40.0/24 ',
# 037009041017 RUNet RU
'37.9.41.0/24 ',
# 037009044040 USNet US
'37.9.44.0/22 ',
# 037009061018 BIN001 GB
'37.9.61.0/24 ',
# 037018163178 Operateur de services internet et hebergement FR
'37.18.163.0/24 ',
# 037021024047 JSC Rostelecom regional branch "Siberia" RU
'37.21.0.0/18 ',
# 037021206046 JSC Rostelecom regional branch "Siberia" RU
'37.21.192.0/18 ',
# 037028159200 VPNonline PL
'37.28.159.0/24 ',
# 037029075192 Center Branch of OJSC MegaFon B2B pool RU
'37.29.75.0/24 ',
# 037029117170 Caucasus Branch of OJSC MegaFon, Mobile & Fixed Broabband RU
'37.29.117.0/24 ',
# 037029121093 Caucasus Branch of OJSC MegaFon, Mobile & Fixed Broabband RU
'37.29.121.0/24 ',
# 037032020225 Used for PPPoE Server and end users IR
'37.32.20.0/24 ',
# 037032029033 Used for PPPoE Server and end users IR
'37.32.29.0/24 ',
# 037034058229 TransIP BV NL
'37.34.56.0/21 ',
# 037037180130 ZAIN KW KW
'37.37.176.0/20 ',
# 037037215055 ZAIN KW KW
'37.37.208.0/20 ',
# 037046134243 JSC Server WebDC colocation RU
'37.46.134.0/23 ',
# 037047001048 Orange Mobile PL
'37.47.0.0/17 ',
# 037047133216 Oranage Mobile PL
'37.47.128.0/17 ',
# 037057015023 Triolan, Kharkiv UA
'37.57.15.0/24 ',
# 037057045247 Triolan, Kharkiv UA
'37.57.45.0/24 ',
# 037057145097 Triolan, Dnipro UA
'37.57.145.0/24 ',
# 037057147041 Triolan, Dnipro UA
'37.57.147.0/24 ',
# 037057179002 Triolan, Kharkiv UA
'37.57.179.0/24 ',
# 037057191018 Triolan, Kyiv UA
'37.57.191.0/24 ',
# 037061209150 BVBA LCD MEDIA BENELUX DE
'37.61.208.0/20 ',
# 037072035190 Netalia Core nw IT
'37.72.32.0/21 ',
# 037072186045 Global Ip Exchange SE
'37.72.186.0/24 ',
# 037072188005 InterConnects SE
'37.72.188.0/24 ',
# 037072191116 InterConnects SE
'37.72.191.0/24 ',
# 037097023100 Altibox Danmark Residential Customer Linknets DK
'37.97.0.0/18 ',
# 037097228159 TransIP BV NL
'37.97.128.0/17 ',
# 037098226082 Scopesky IQ
'37.98.226.0/24 ',
# 037105074150 DSL HOME Subscribers_Dynamic IPs SA
'37.105.64.0/19 ',
# 037111201078 Grameenphone Ltd. BD
'37.111.201.0/24 ',
# 037111233123 Grameenphone Ltd. BD
'37.111.233.0/24 ',
# 037112025089 JSC "ER-Telecom Holding" Penza branch RU
'37.112.24.0/22 ',
# 037112032079 CJSC "ER-Telecom Holding" Rostov-na-Donu branch RU
'37.112.32.0/23 ',
# 037112160073 JSC "ER-Telecom" Company" Samara RU
'37.112.160.0/22 ',
# 037112164006 JSC "ER-Telecom" Company" Samara RU
'37.112.164.0/22 ',
# 037112168010 JSC "ER-Telecom" Company" Samara RU
'37.112.168.0/22 ',
# 037112173102 JSC "ER-Telecom" Company" Samara RU
'37.112.172.0/22 ',
# 037112219101 CJSC "ER-Telecom Holding" Voronezh branch RU
'37.112.216.0/22 ',
# 037131166251 Komputerowe Studio Grafiki, Wojciech Lis PL
'37.131.166.0/24 ',
# 037139056177 public vlans of DC RU
'37.139.56.0/24 ',
# 037142020030 BROADBAND-CABLES IL
'37.142.16.0/20 ',
# 037151004181 JSC Kazakhtelecom KZ
'37.151.4.0/22 ',
# 037151146235 JSC Kazakhtelecom, South Kazakhstan Affiliate KZ
'37.151.144.0/21 ',
# 037153168008 Customer 1653 NL
'37.153.168.0/22 ',
# 037156074008 XT GLOBAL NETWORKS LTD GB
'37.156.72.0/21 ',
# 037157100134 Telia Eesti AS EE
'37.157.64.0/18 ',
# 037200019094 Norway NO
'37.200.0.0/18 ',
# 037200079014 ISP Podryad - VL - IPoE Pool - 9 RU
'37.200.78.0/23 ',
# 037209250058 IDM LB
'37.209.250.0/24 ',
# 037218240021 OTF Network NL
'37.218.240.0/24 ',
# 037218245025 Greenhost VOF NL
'37.218.245.0/24 ',
# 037222048236 GLOBAL MOBILE OPERATOR ES
'37.222.0.0/15 ',
# 037230135027 SOLOGIGABIT SLU, VALENCIA, SPAIN ES
'37.230.135.0/24 ',
# 037230136245 "Automated communications systems" Ltd. RU
'37.230.136.0/23 ',
# 037230138022 RocketTelecom LLC RU
'37.230.138.0/24 ',
# 037235042118 GB
'37.235.40.0/21 ',
# 037235052020 EDIS Infrastructure in Chile CL
'37.235.52.0/24 ',
# 037235065076 Limited Liability Company "MegaMax" RU
'37.235.64.0/21 ',
# 037244234201 DSL HR
'37.244.128.0/17 ',
# 037247105072 DGN Teknoloji Anonim Sirketi TR
'37.247.105.0/24 ',
# 039001036173 So-net Entertainment Taiwan Limited TW
'39.1.0.0/16 ',
# 039007050185 Korea Telecom KR
'39.4.0.0/14 ',
# 039108075016 Aliyun Computing Co., LTD CN
'39.108.0.0/16 ',
# 039109003181 RM C 21/F CMA BLDG NO.64 CONNAUGHT RD CENTRAL HONG KONG HK
'39.109.0.0/17 ',
# 039109172064 Starhub Internet Pte Ltd SG
'39.109.128.0/17 ',
# 042051010061 Henan Telcom Union Technology Co., LTD CN
'42.51.0.0/17 ',
# 042053056217 UNICOM Liaoning Province Network CN
'42.52.0.0/14 ',
# 042063252112 China Unicom Ningxia Province Network CN
'42.63.0.0/16 ',
# 042104097019 This space is statically assigned. IN
'42.104.0.0/17 ',
# 042106001009 Hutchison Max Telecom Limited IN
'42.106.0.0/17 ',
# 042109163039 Hutchison Max Telecom Limited IN
'42.109.128.0/17 ',
# 042110165077 Hutchison Max Telecom Limited IN
'42.110.128.0/17 ',
# 042111033178 Hutchison Max Telecom Limited IN
'42.111.32.0/19 ',
# 042111068117 Hutchison Max Telecom Limited IN
'42.111.0.0/17 ',
# 042113218150 FPT Telecom Company VN
'42.113.208.0/20 ',
# 042115002172 FPT Telecom Company VN
'42.115.0.0/20 ',
# 042115033002 FPT Telecom Company VN
'42.115.32.0/20 ',
# 042115088012 FPT Telecom Company VN
'42.115.80.0/20 ',
# 042115242116 FPT Telecom Company VN
'42.115.240.0/20 ',
# 042116099008 FPT Telecom Company VN
'42.116.96.0/20 ',
# 042116132195 FPT Telecom Company VN
'42.116.128.0/20 ',
# 042119134065 FPT Telecom Company VN
'42.119.128.0/20 ',
# 042119145245 FPT Telecom Company VN
'42.119.144.0/20 ',
# 042127245148 TOKAI Communications Corporation JP
'42.124.0.0/14 ',
# 042200200078 Hong Kong Telecommunications (HKT) Limited Business Internet HK
'42.200.192.0/19 ',
# 043224001141 Gtpl Broadband Pvt. Ltd. IN
'43.224.1.0/24 ',
# 043225023227 SDN TELECOM PVT LTD IN
'43.225.23.0/24 ',
# 043225192174 ANJANI BROADBAND SOLUTIONS PVT.LTD. IN
'43.225.192.0/22 ',
# 043226007043 Unit 1001, 10/F Infinitus Plaza, 199 Des Voeux Road Central PH
'43.226.7.0/24 ',
# 043226024122 NetX Network Solutions And Telecommunication Pvt Ltd IN
'43.226.24.0/24 ',
# 043226229008 GZ Systems Limited Australia Operations AU
'43.226.229.0/24 ',
# 043227133066 Power Grid Corporation of India Limited IN
'43.227.133.0/24 ',
# 043228108019 Bharti Airtel Lanka Pvt. Limited LK
'43.228.108.0/22 ',
# 043228156012 Network Operations Center NZ NZ
'43.228.156.0/25 ',
# 043228157021 Cyber-Security-SG SG
'43.228.157.0/24 ',
# 043228228010 Vihaan Telecommunication Pvt. Ltd. IN
'43.228.228.0/24 ',
# 043228229058 Vihaan Telecommunication Pvt. Ltd. IN
'43.228.229.0/24 ',
# 043229201118 SEVEN ELEVEN COMMUNICATION PRIVATE LIMITED IN
'43.229.201.0/24 ',
# 043229202003 SEVEN ELEVEN COMMUNICATION PRIVATE LIMITED IN
'43.229.202.0/24 ',
# 043229224130 Auspice Infratel Pvt. Ltd. IN
'43.229.224.0/24 ',
# 043230094071 Ebone Network (Pvt) Ltd. PK
'43.230.92.0/22 ',
# 043230141227 NUIT 04 7/F BRIGHT WAY TOWER,NO.33 MONG KOK ROAD,KOWLOON,HK HK
'43.230.140.0/22 ',
# 043230156074 ELXIRE DATA SERVICES PVT. LTD. IN
'43.230.156.0/24 ',
# 043230172074 Nextra IN
'43.230.172.0/24 ',
# 043230173073 Nextra IN
'43.230.173.0/24 ',
# 043230174080 Nextra IN
'43.230.174.0/24 ',
# 043230193161 IPBASE KH
'43.230.193.0/24 ',
# 043230198168 Precious netcom pvt ltd IN
'43.230.196.0/22 ',
# 043231023034 Radiant telecommunications BD
'43.231.23.0/24 ',
# 043231054160 STARNET IN
'43.231.54.0/24 ',
# 043231055009 STARNET IN
'43.231.55.0/24 ',
# 043231057179 MNR Broadband Services Pvt. Ltd. IN
'43.231.57.0/24 ',
# 043231076205 Gateway Online Access Limited BD
'43.231.76.0/22 ',
# 043231211149 Classic Tech Pvt. Ltd. 3rd Floor, AlfaBeta Complex New Baneshwore 10 NP
'43.231.208.0/22 ',
# 043239068007 Odeon Infrabuilders Private Limited IN
'43.239.68.0/24 ',
# 043239069130 Odeon Infrabuilders Private Limited IN
'43.239.69.0/24 ',
# 043239070013 Odeon Infrabuilders Private Limited IN
'43.239.70.0/24 ',
# 043239071209 Odeon Infrabuilders Private Limited IN
'43.239.71.0/24 ',
# 043239074214 Comilla Online BD
'43.239.74.0/24 ',
# 043239075066 Comilla Online BD
'43.239.75.0/24 ',
# 043239205026 Ganesha Internet Services India Private Limited IN
'43.239.204.0/22 ',
# 043240101066 Reve Systems Banasree BD
'43.240.101.0/24 ',
# 043240138031 Beijing Sheng Hexuan Culture Communication Co., Ltd. CN
'43.240.136.0/22 ',
# 043240226028 PT Norlec Telekomunikasi Indonesia ID
'43.240.226.0/24 ',
# 043242037220 509 Jaina tower 1st District Center IN
'43.242.36.0/22 ',
# 043242119226 Gtpl Broadband Pvt. Ltd. IN
'43.242.119.0/24 ',
# 043242175186 Room 501., Well Tech Centre, HK
'43.242.172.0/22 ',
# 043243081003 SAMPARK ESTATES PVT. LTD. IN
'43.243.81.0/24 ',
# 043243173054 Shah Solutions IN
'43.243.173.0/24 ',
# 043245120022 Tigers' Den, House#4(SW), Bir Uttam Mir Shawkat Sharak, Gulshan-1, Dhaka-1212, Bangladesh BD
'43.245.120.0/24 ',
# 043245121168 Tigers' Den, House#4(SW), Bir Uttam Mir Shawkat Sharak, Gulshan-1, Dhaka-1212, Bangladesh BD
'43.245.121.0/24 ',
# 043245137053 Nextra IN
'43.245.137.0/24 ',
# 043245139167 Nextra IN
'43.245.139.0/24 ',
# 043245151247 Nextra IN
'43.245.151.0/24 ',
# 043245156024 Nextra IN
'43.245.156.0/24 ',
# 043245157146 Nextra IN
'43.245.157.0/24 ',
# 043245158077 Nextra IN
'43.245.158.0/24 ',
# 043245159216 Nextra IN
'43.245.159.0/24 ',
# 043245208020 Nextra IN
'43.245.208.0/24 ',
# 043245211252 Nextra IN
'43.245.211.0/24 ',
# 043246143078 R P World Telecom Pvt Ltd IN
'43.246.143.0/24 ',
# 043246223154 Real Miles Communication PK
'43.246.223.0/24 ',
# 043247015216 PT. Maxindo Mitra Solusi ID
'43.247.12.0/22 ',
# 043248034092 Gtpl Broadband Pvt. Ltd. IN
'43.248.34.0/24 ',
# 043249039215 LeaseWeb Asia Pacific - Singapore SG
'43.249.38.0/23 ',
# 043249054162 Gigantic Network Solution IN
'43.249.54.0/24 ',
# 043249104107 BB BROADBAND CO., LTD. TH
'43.249.104.0/22 ',
# 043249131202 CUST ALLOC KR KR
'43.249.131.128/25 ',
# 043250080150 ICC Communication BD
'43.250.80.0/24 ',
# 043250164123 Gtpl Broadband Pvt. Ltd. IN
'43.250.164.0/24 ',
# 043250227094 Pipol Broadband and Telecommunications Corporation PH
'43.250.224.0/22 ',
# 043251093193 Ani Hi-Speed Broadband IN
'43.251.93.0/24 ',
# 043251099001 PT Netciti Persada ID
'43.251.96.0/22 ',
# 043251218200 EMC Square Teleinfra Pvt Ltd IN
'43.251.218.0/24 ',
# 043252017162 IP block for FTTH KH
'43.252.17.0/24 ',
# 043252024024 NEXTRA TELESERVICES IN
'43.252.24.0/24 ',
# 043252027023 NEXTRA TELESERVICES IN
'43.252.27.0/24 ',
# 043252028125 NEXTRA TELESERVICES IN
'43.252.28.0/24 ',
# 043252029064 NEXTRA TELESERVICES IN
'43.252.29.0/24 ',
# 043252032169 Nextra IN
'43.252.32.0/24 ',
# 043252034028 Nextra IN
'43.252.34.0/24 ',
# 043252035036 Nextra IN
'43.252.35.0/24 ',
# 043252142089 Siliguri Internet & Cable TV Pvt. Ltd. IN
'43.252.140.0/22 ',
# 043254028098 RackBank Datacenters Private Ltd IN
'43.254.28.0/24 ',
# 043255022186 Bright Technologies Limited BD
'43.255.20.0/22 ',
# 043255114187 Xinwei(Cambodia)Telecom Co,.Ltd #Building No.B3 and No.C31, Street 169, Sangkat Veal Vong , Khan 7 Makara, Phnom Penh. KH
'43.255.112.0/22 ',
# 043255142081 Decent Computers IN
'43.255.142.0/24 ',
# 045006037241 PJM TELECOM US
'45.6.36.0/22 ',
# 045032009040 Choopa, LLC CHOOPA (NET-45-32-0-0-1) US
'45.32.0.0/16 ',
# 045033144021 Rose Miller BWAY (NET-45-33-144-16-1) US
'45.33.144.16/29 ',
# 045033157230 BraveWay LLC US
'45.33.144.0/20 ',
# 045034023099 Psychz Networks US
'45.34.0.0/15 ',
# 045040143057 GoDaddy.com, LLC US
'45.40.128.0/18 ',
# 045041088188 HT US
'45.41.80.0/20 ',
# 045041135017 SimpleLink LLC SIMPLELINK (NET-45-41-128-0-1) US
'45.41.128.0/18 ',
# 045042085018 XIATIAN.LLC US
'45.42.80.0/20 ',
# 045042158119 Roya Hosting LLC ROYA (NET-45-42-128-0-1) US
'45.42.128.0/17 ',
# 045043025050 Garrison Network Solutions LLC GNS-5 (NET-45-43-0-0-1) US
'45.43.0.0/19 ',
# 045044042141 VMedia Inc CA
'45.44.0.0/16 ',
# 045045150015 Contina CONTINA (NET-45-45-128-0-1) US
'45.45.128.0/17 ',
# 045057136161 iTech Services NET-45-57-136-160-1 (NET-45-57-136-160-1) US
'45.57.136.160/27 ',
# 045057138193 Web Hosting Solutions NET-45-57-138-192-1 (NET-45-57-138-192-1) US
'45.57.138.192/28 ',
# 045057144051 B2 Net Solutions Inc. B2NETSOLUTIONS (NET-45-57-128-0-1) US
'45.57.128.0/17 ',
# 045058127114 Augusto Marmo NET-45-58-127-112-29 (NET-45-58-127-112-1) US
'45.58.127.112/29 ',
# 045059115210 ServerCrate US
'45.59.112.0/20 ',
# 045062232020 KW Datacenter CA
'45.62.192.0/18 ',
# 045064001113 PT Masterweb Network ID
'45.64.0.0/22 ',
# 045064011058 Shivansh Infotech pvt Ltd IN
'45.64.11.0/24 ',
# 045064186077 Bangmod IDC TH
'45.64.186.0/24 ',
# 045064190026 sky dot communication Limited IN
'45.64.190.0/24 ',
# 045072003205 Web Hosting Solutions NET-45-72-3-192-1 (NET-45-72-3-192-1) US
'45.72.3.192/28 ',
# 045072007157 Lixux OU NET-45-72-7-128-1 (NET-45-72-7-128-1) US
'45.72.7.128/25 ',
# 045072011145 Web Hosting Solutions NET-45-72-11-144-1 (NET-45-72-11-144-1) US
'45.72.11.144/28 ',
# 045072012097 Web Hosting Solutions NET-45-72-12-96-1 (NET-45-72-12-96-1) US
'45.72.12.96/28 ',
# 045072023124 Web Hosting Solutions NET-45-72-23-112-1 (NET-45-72-23-112-1) US
'45.72.23.112/28 ',
# 045072026003 Web Hosting Solutions NET-45-72-26-0-1 (NET-45-72-26-0-1) US
'45.72.26.0/28 ',
# 045072035179 Web Hosting Solutions NET-45-72-35-176-1 (NET-45-72-35-176-1) US
'45.72.35.176/28 ',
# 045072058033 ProxyVPN NET-45-72-58-0-1 (NET-45-72-58-0-1) US
'45.72.58.0/25 ',
# 045072067065 Web Hosting Solutions NET-45-72-67-64-1 (NET-45-72-67-64-1) US
'45.72.67.64/28 ',
# 045072070052 Web Hosting Solutions NET-45-72-70-48-1 (NET-45-72-70-48-1) US
'45.72.70.48/28 ',
# 045072071135 Web Hosting Solutions NET-45-72-71-128-1 (NET-45-72-71-128-1) US
'45.72.71.128/27 ',
# 045072073025 Web Hosting Solutions NET-45-72-73-16-1 (NET-45-72-73-16-1) US
'45.72.73.16/28 ',
# 045072074061 Web Hosting Solutions NET-45-72-74-32-1 (NET-45-72-74-32-1) US
'45.72.74.32/27 ',
# 045072076008 B2 Net Solutions Inc. CA
'45.72.0.0/17 ',
# 045073151242 Fibernet Direct FIBERNET-CUSTOMER-17 (NET-45-73-144-0-1) US
'45.73.144.0/20 ',
# 045074001007 Secure Internet LLC SECURE-INTERNET-16 (NET-45-74-0-0-1) US
'45.74.0.0/18 ',
# 045074090050 Hotwire Communications US
'45.74.80.0/20 ',
# 045076000022 Choopa, LLC CHOOPA (NET-45-76-0-0-1) US
'45.76.0.0/15 ',
# 045078008032 IT7 Networks Inc IT7NET (NET-45-78-0-0-1) US
'45.78.0.0/18 ',
# 045079000208 Linode US
'45.79.0.0/16 ',
# 045113064253 Rural Broadband Pvt. Ltd IN
'45.113.64.0/24 ',
# 045114048247 Wefe Technology Pvt Ltd IN
'45.114.48.0/22 ',
# 045114086132 Race Online Limited BD
'45.114.86.0/24 ',
# 045114088254 THE NET HEADS BD
'45.114.88.0/24 ',
# 045114146012 Uclix Infra Ltd IN
'45.114.144.0/22 ',
# 045114233002 Aalok IT Limited BD
'45.114.233.0/24 ',
# 045115087206 Nayatel (Pvt) Ltd PK
'45.115.84.0/22 ',
# 045115104018 ultranet services private limited IN
'45.115.104.0/24 ',
# 045115105061 ultranet services private limited IN
'45.115.105.0/24 ',
# 045115107073 ultranet services private limited IN
'45.115.107.0/24 ',
# 045115112060 DRIK ICT Limited BD
'45.115.112.0/22 ',
# 045115140106 Nextra IN
'45.115.140.0/24 ',
# 045115141220 Nextra IN
'45.115.141.0/24 ',
# 045115142196 Nextra IN
'45.115.142.0/24 ',
# 045115143095 Nextra IN
'45.115.143.0/24 ',
# 045115236080 Jiangsu Sanai Cloud Computing technology co ,LTD CN
'45.115.236.0/22 ',
# 045116180004 RINGROAD SERVICE STATION IN
'45.116.180.0/22 ',
# 045116232018 For ABC IGW PK
'45.116.232.0/24 ',
# 045116233043 For ABC IGW PK
'45.116.233.0/24 ',
# 045117065002 HIREACH BROADBAND PRIVATE LTD IN
'45.117.65.0/24 ',
# 045117066008 HIREACH BROADBAND PRIVATE LTD IN
'45.117.66.0/24 ',
# 045117170025 SUPERDATA VN
'45.117.168.0/22 ',
# 045118133058 Linode, LLC SG SG
'45.118.132.0/23 ',
# 045118244025 Stargate Communications Ltd. BD
'45.118.244.0/24 ',
# 045119082153 Long Van System Solution JSC VN
'45.119.80.0/22 ',
# 045119150105 NET SAMPARK IN
'45.119.148.0/22 ',
# 045120056186 ODEON INFRASTRUCTURE PRIVATE LIMITED IN
'45.120.56.0/24 ',
# 045120057129 ODEON INFRASTRUCTURE PRIVATE LIMITED IN
'45.120.57.0/24 ',
# 045120058160 ODEON INFRASTRUCTURE PRIVATE LIMITED IN
'45.120.58.0/24 ',
# 045121013149 INDIA TRADERS IN
'45.121.12.0/22 ',
# 045121109100 THE SPEEDNET IN
'45.121.109.0/24 ',
# 045121188016 Neo Suncity Private Limited IN
'45.121.188.0/24 ',
# 045121189006 Neo Suncity Private Limited IN
'45.121.189.0/24 ',
# 045121190022 Neo Suncity Private Limited IN
'45.121.190.0/24 ',
# 045121191033 Neo Suncity Private Limited IN
'45.121.191.0/24 ',
# 045123003105 Blue Lotus Support Services Pvt Ltd IN
'45.123.3.0/24 ',
# 045123008165 VINAYAK INFOTECH SERVICES IN
'45.123.8.0/22 ',
# 045123041074 Radisson Technologies BD
'45.123.41.0/24 ',
# 045123043010 Radisson Technologies BD
'45.123.43.0/24 ',
# 045123117016 Serverfield Ltd. TR
'45.123.117.0/24 ',
# 045124004192 CHANNEL DRISTI NETWORK IN
'45.124.4.0/22 ',
# 045124024221 Cloudie Limited HK
'45.124.24.0/22 ',
# 045124048066 Future Solutions IN
'45.124.48.0/22 ',
# 045125063195 Global Network IN
'45.125.63.0/24 ',
# 045125192210 Ready Server - Dedicated Server Hosting SG
'45.125.192.0/24 ',
# 045126041176 OM AGENCY IN
'45.126.40.0/22 ',
# 045126200087 CHAMUNDA SUPPLIERS IN
'45.126.200.0/22 ',
# 045127040077 Odeon Developers Private Limited IN
'45.127.40.0/24 ',
# 045127041175 Odeon Developers Private Limited IN
'45.127.41.0/24 ',
# 045127042003 Odeon Developers Private Limited IN
'45.127.42.0/24 ',
# 045127043156 Odeon Developers Private Limited IN
'45.127.43.0/24 ',
# 045127055110 GUNGUN ENTERPRISES IN
'45.127.52.0/22 ',
# 045127247014 Dhaka Fiber Net Limited BD
'45.127.247.0/24 ',
# 045210064166 Used for Airtel Ghana Use GH
'45.210.0.0/15 ',
# 045221064009 Hetzner-ZA ZA
'45.221.64.0/24 ',
# 045221065012 Web4Africa-NG NG
'45.221.65.0/24 ',
# 045221217197 Petprops 36 CC ZA
'45.221.216.0/21 ',
# 045242086097 Link Egypt (Link.NET) EG
'45.240.0.0/13 ',
# 045248007077 ANANDA AGENCIES IN
'45.248.4.0/22 ',
# 045248027171 vardha info tech private limited IN
'45.248.24.0/22 ',
# 045248042095 ANKIT WI-FI SOLUTION PRIVATE LIMITED IN
'45.248.42.0/23 ',
# 045248164111 Light Air Transmission Pvt. Ltd. IN
'45.248.164.0/22 ',
# 045248194094 SKYLINE INFONET PRIVATE LIMITED IN
'45.248.194.0/24 ',
# 045248195063 SKYLINE INFONET PRIVATE LIMITED IN
'45.248.195.0/24 ',
# 045249008011 Trans World Enterprise Services (Private) Limited PK
'45.249.8.0/24 ',
# 045249062081 UNIT 04,7/F BRIGHT WAY TOWER,33 MONG KOK ROAD HK
'45.249.60.0/22 ',
# 045249184006 Media Online BD
'45.249.184.0/24 ',
# 045250004194 BALAJI SALES IN
'45.250.4.0/22 ',
# 045250026077 CloudSRV Limited GB
'45.250.26.0/23 ',
# 045250246167 Futain Trading Co. IN
'45.250.246.0/24 ',
# 045251033080 K Net Solutions Pvt Ltd IN
'45.251.32.0/22 ',
# 045252180069 Nextra It Solutions Private Limited IN
'45.252.180.0/24 ',
# 045252181174 Nextra It Solutions Private Limited IN
'45.252.181.0/24 ',
# 045252182191 Nextra It Solutions Private Limited IN
'45.252.182.0/24 ',
# 046000184019 CJSC "ER-Telecom" Company" Samara RU
'46.0.184.0/22 ',
# 046000218096 CJSC "ER-Telecom Holding" Samara branch RU
'46.0.216.0/22 ',
# 046000220191 CJSC "ER-Telecom Holding" Samara branch RU
'46.0.220.0/22 ',
# 046000225095 CJSC "ER-Telecom Holding" Samara branch RU
'46.0.224.0/22 ',
# 046002160135 Koc.Net DSL Bodrum TR
'46.2.160.0/23 ',
# 046002214255 Koc.Net DSL Erzurum TR
'46.2.208.0/21 ',
# 046008029218 RU
'46.8.29.0/24 ',
# 046009026009 Fredrikstad, Norway NO
'46.9.0.0/16 ',
# 046017040220 LLC BAXET RU
'46.17.40.0/23 ',
# 046017043098 LLC BAXET RU
'46.17.42.0/23 ',
# 046019196003 GAMING POOL - DSL customers LB
'46.19.192.0/21 ',
# 046020014122 DGN Teknoloji Anonim Sirketi TR
'46.20.14.0/24 ',
# 046021072068 Torus Telecom ltd. RU
'46.21.72.0/22 ',
# 046029161164 LLC Baxet RU
'46.29.160.0/23 ',
# 046029197006 Siberia Branch of OJSC MegaFon RU
'46.29.197.0/25 ',
# 046029219215 Oneprovider NO
'46.29.216.0/21 ',
# 046029248238 Reverse-Proxy SE
'46.29.248.0/23 ',
# 046029250062 Reverse-Proxy SE
'46.29.250.0/23 ',
# 046029252059 InterConnects SE
'46.29.252.0/24 ',
# 046030041061 Eurobyte VPS RU
'46.30.41.0/24 ',
# 046030045005 Eurobyte VPS RU
'46.30.45.0/24 ',
# 046032126110 Zain Data-Jordan JO
'46.32.126.0/24 ',
# 046033033033 TRK BlackSea UA
'46.33.32.0/21 ',
# 046033059202 TRK "Chernoe More" UA
'46.33.56.0/22 ',
# 046036065010 KLI LT, UAB LT
'46.36.64.0/21 ',
# 046036107021 "Pirooz Leen" LLC IR
'46.36.104.0/21 ',
# 046036218053 FastVPS network for VPS EE
'46.36.218.0/24 ',
# 046037193074 ICN pppoe subcribers UA
'46.37.193.0/24 ',
# 046040047126 DHCP pool for broadband cable modem customers RS
'46.40.40.0/21 ',
# 046042014125 OAO KGTS ADSL/HFE Users RU
'46.42.8.0/21 ',
# 046042022168 OAO KGTS ADSL/HFE Users RU
'46.42.16.0/21 ',
# 046042025021 OAO KGTS ADSL/HFE Users RU
'46.42.24.0/22 ',
# 046042033038 OAO KGTS ADSL/HFE Users RU
'46.42.32.0/22 ',
# 046042049051 OAO KGTS ADSL/HFE Users RU
'46.42.48.0/22 ',
# 046042056104 OAO KGTS ADSL/HFE Users RU
'46.42.56.0/22 ',
# 046042060041 OAO KGTS ADSL/HFE Users RU
'46.42.60.0/22 ',
# 046043111027 Mada-Network- Saturn-Network PS
'46.43.110.0/23 ',
# 046045137071 Istanbul DC Customer TR
'46.45.137.0/24 ',
# 046053206182 FE "ALTERNATIVNAYA ZIFROVAYA SET" Minsk BY
'46.53.206.0/24 ',
# 046056188042 VELCOM inetnum #5 BY
'46.56.128.0/17 ',
# 046066184011 Telenor Norge AS NO
'46.66.0.0/15 ',
# 046070193038 "Armentel" CJSC . Armenia Telephone Company AM
'46.70.192.0/21 ',
# 046071162047 "Armentel" CJSC . Armenia Telephone Company AM
'46.71.160.0/21 ',
# 046102160027 XT GLOBAL NETWORKS LTD NL
'46.102.160.0/21 ',
# 046120169234 012 Smile IL
'46.120.168.0/21 ',
# 046129079061 CPE Customers NL NL
'46.129.0.0/17 ',
# 046133063074 PrJSC "MTS Ukraine" UA
'46.133.0.0/17 ',
# 046134030035 Orange Mobile PL
'46.134.0.0/18 ',
# 046140245082 cablecom GmbH CH
'46.140.128.0/17 ',
# 046143054088 Asiatech xDSL Network IR
'46.143.32.0/19 ',
# 046146032176 JSC "ER-Telecom" Perm' RU
'46.146.32.0/22 ',
# 046146052217 JSC "ER-Telecom" Perm' RU
'46.146.52.0/22 ',
# 046146059228 JSC "ER-Telecom" Perm' RU
'46.146.56.0/22 ',
# 046146084237 JSC "ER-Telecom" Perm' RU
'46.146.84.0/22 ',
# 046146116239 Individual PPPoE customers RU
'46.146.116.0/22 ',
# 046146179180 Individual PPPoE customers RU
'46.146.176.0/22 ',
# 046146193220 Individual PPPoE customers RU
'46.146.192.0/22 ',
# 046147012249 Individual PPPoE customers RU
'46.147.12.0/22 ',
# 046147103078 CJSC "ER-Telecom Holding" Rostov-na-Donu branch RU
'46.147.100.0/22 ',
# 046147115072 CJSC "ER-Telecom Holding" Rostov-na-Donu branch RU
'46.147.112.0/22 ',
# 046147119100 CJSC "ER-Telecom Holding" Rostov-na-Donu branch RU
'46.147.116.0/22 ',
# 046147124138 CJSC "ER-Telecom Holding" Rostov-na-Donu branch RU
'46.147.124.0/22 ',
# 046147216205 CJSC "ER-Telecom Holding" Rostov-na-Donu branch RU
'46.147.216.0/22 ',
# 046148020031 Infiumhost UA
'46.148.20.0/24 ',
# 046148021033 Infiumhost UA
'46.148.21.0/24 ',
# 046148026108 LT UA
'46.148.26.0/24 ',
# 046148112012 TRUSOV UA
'46.148.112.0/24 ',
# 046148120011 TRUSOV UA
'46.148.120.0/24 ',
# 046148127023 TRUSOV UA
'46.148.127.0/24 ',
# 046148132040 Trytek broadband pool RU
'46.148.128.0/21 ',
# 046148143250 Trytek broadband pool RU
'46.148.136.0/21 ',
# 046148180152 RU
'46.148.176.0/20 ',
# 046148193178 Wiland Network Russia RU
'46.148.192.0/21 ',
# 046150172128 Web Page: http://www.opticom.net RU
'46.150.172.0/24 ',
# 046151009025 Pioner-Lan Ltd. RU
'46.151.8.0/21 ',
# 046154197147 Vodafone Turkey 3G IP Pools TR
'46.154.196.0/22 ',
# 046160227242 MIR broadband customers RU
'46.160.224.0/21 ',
# 046161009003 seodedic RU
'46.161.9.0/24 ',
# 046161014099 dedicated server client RU
'46.161.14.0/23 ',
# 046161056013 India Delhi network IN
'46.161.56.0/24 ',
# 046161057012 Istanbul Network TR
'46.161.57.0/24 ',
# 046161058014 net for Saudi Arabia SA
'46.161.58.0/24 ',
# 046161059050 net for Sydney AU
'46.161.59.0/24 ',
# 046161060017 Spain Madrid Network ES
'46.161.60.0/24 ',
# 046161061019 Reykjavik Network IS
'46.161.61.0/24 ',
# 046166137193 Amsterdam Residential Television and Internet NL
'46.166.136.0/21 ',
# 046166145140 Customer 1946 NL
'46.166.144.0/21 ',
# 046166200049 JSC "Neotelecom" RU
'46.166.200.0/21 ',
# 046173124102 BUZHNET-CLIENTS More Specific Route UA
'46.173.112.0/20 ',
# 046173166083 PPP_BURSHTYN UA
'46.173.164.0/22 ',
# 046175182221 CZ
'46.175.176.0/21 ',
# 046182018029 NbIServ vServer DE
'46.182.16.0/21 ',
# 046183128189 REDCOM Broadband Block 4 for flat-rate access RU
'46.183.128.0/23 ',
# 046183169083 Z-Nett AS NO
'46.183.168.0/21 ',
# 046191156246 JSC "Ufanet" RU
'46.191.156.0/23 ',
# 046193064232 Wifirst end users FR
'46.193.64.0/19 ',
# 046193131213 Wifirst end users FR
'46.193.128.0/20 ',
# 046209055006 ADSL Customers in Zahedan IR
'46.209.52.0/22 ',
# 046219212136 o3_core UA
'46.219.212.0/24 ',
# 046219214231 o3_core UA
'46.219.214.0/24 ',
# 046219215039 o3_core UA
'46.219.215.0/24 ',
# 046219216135 o3_core UA
'46.219.216.0/24 ',
# 046219222111 o3_core UA
'46.219.222.0/24 ',
# 046219235138 o3_core UA
'46.219.235.0/24 ',
# 046231173131 CJSC "Oskolnet" RU
'46.231.168.0/21 ',
# 046235101130 "AVcom" d.o.o. RS
'46.235.101.0/24 ',
# 046237044195 Mobile network technical operation center RU
'46.237.40.0/21 ',
# 046243136013 GZ Systems Limited - Colocation in Malta. MT
'46.243.136.0/24 ',
# 046243143069 GZ Systems Limited - Colocation in Greece. GR
'46.243.143.0/24 ',
# 046243144010 GZ Systems Limited - Colocation in Isle of Man IM
'46.243.144.0/24 ',
# 046243145011 GZ Systems Limited - Colocation in Syria SY
'46.243.145.0/24 ',
# 046243146017 GZ Systems Limited - Colocation in Lebanon LB
'46.243.146.0/24 ',
# 046243147048 GZ Systems Limited - Colocation in Qatar QA
'46.243.147.0/24 ',
# 046243149020 GZ Systems Limited - Colocation in Yemen YE
'46.243.149.0/24 ',
# 046243150009 GZ Systems Limited BH
'46.243.150.0/24 ',
# 046243160139 RU
'46.243.160.0/24 ',
# 046243218167 GZ Systems Limited - Colocation in Brussels BE
'46.243.218.0/24 ',
# 046244029195 Tower B World Trade Center 10 PK
'46.244.0.0/19 ',
# 046248164024 IQ PL Sp. z o.o. PL
'46.248.160.0/19 ',
# 046250074010 JSC Regional Technical Centre RU
'46.250.72.0/22 ',
# 046251024173 ViDaNet Cabletelevision Provider Ltd. HU
'46.251.16.0/20 ',
# 046253095186 Zone Network PS
'46.253.95.0/24 ',
# 046254218101 IvLAN Internet Service Provider Ltd. RU
'46.254.218.0/23 ',
# 046255035240 For broadband users UA
'46.255.32.0/22 ',
# 047008008076 Reliance Jio infocomm ltd IN
'47.8.0.0/16 ',
# 047009001000 Reliance Jio infocomm ltd IN
'47.9.0.0/16 ',
# 047011015035 Reliance Jio infocomm ltd IN
'47.11.0.0/17 ',
# 047011132075 Reliance Jio infocomm ltd IN
'47.11.128.0/17 ',
# 047015012060 Reliance Jio Infocomm Limited IN
'47.15.0.0/16 ',
# 047029005047 Reliance Jio infocomm ltd IN
'47.29.0.0/17 ',
# 047029128143 Reliance Jio infocomm ltd IN
'47.29.128.0/18 ',
# 047029194072 Reliance Jio infocomm ltd IN
'47.29.192.0/18 ',
# 047030063105 Reliance Jio infocomm ltd IN
'47.30.0.0/16 ',
# 047031001200 Reliance Jio infocomm ltd IN
'47.31.0.0/16 ',
# 047052001038 Alibaba.com LLC AL-3 (NET-47-52-0-0-1) US
'47.52.0.0/16 ',
# 047074017121 Alibaba.com LLC AL-3 (NET-47-74-0-0-1) US
'47.74.0.0/11 ',
# 047088003220 Alibaba.com LLC US
'47.88.0.0/14 ',
# 047093006211 Aliyun Computing Co., LTD CN
'47.92.0.0/14 ',
# 047187215124 Frontier Communications Corporation US
'47.182.0.0/15 ',
# 047189179145 Frontier Communications Corporation US
'47.184.0.0/14 ',
# 047208029036 Suddenlink Communications US
'47.208.0.0/12 ',
# 047247001235 Reliance Jio Infocomm Limited IN
'47.247.0.0/16 ',
# 049015173063 GPRS Delhi Mobile Subscriber IP IN
'49.15.173.0/24 ',
# 049034067011 Reliance Jio Infocomm Limited IN
'49.34.0.0/16 ',
# 049035000222 Reliance Jio Infocomm Limited IN
'49.35.0.0/16 ',
# 049044051160 Reliance Jio Infocomm Limited IN
'49.44.48.0/20 ',
# 049051035115 Tencent cloud computing (Beijing) Co., Ltd. CN
'49.51.32.0/19 ',
# 049128062220 M1 NET LTD SG
'49.128.32.0/19 ',
# 049143180100 Asia Pacific Network Information Centre AU
'49.0.0.0/8 ',
# 050004024137 WideOpenWest Finance LLC WIDEOPENWEST (NET-50-4-0-0-1) US
'50.4.0.0/16 ',
# 050030113248 Russellville Electric Plant Board RUSSEPB-50-30-112-0-22 (NET-50-30-112-0-2) US
'50.30.112.0/22 ',
# 050030192141 PAVLOV MEDIA INC PAVLOVMEDIA-3 (NET-50-30-128-0-1) US
'50.30.128.0/17 ',
# 050093248164 US Internet Corp US
'50.93.240.0/20 ',
# 051015004132 NL
'51.15.0.0/18 ',
# 051015069003 Dedicated Servers and cloud assignment, abuse reports : http://abuse.online.net FR
'51.15.0.0/16 ',
# 051039196116 MTC KSA SA
'51.39.196.0/24 ',
# 051039246125 MTC KSA SA
'51.39.244.0/22 ',
# 051174060023 Altibox AS NO
'51.174.0.0/15 ',
# 051235200093 Saudi Arabia backbone and local registry address space / STC SA
'51.235.192.0/19 ',
# 051254016106 Dedicated Servers DE
'51.254.0.0/15 ',
# 058003065210 QTnet,Inc. JP
'58.3.0.0/16 ',
# 058027159103 National WiMAX/IMS environment PK
'58.27.156.0/22 ',
# 058027217075 National WiMAX/IMS environment PK
'58.27.208.0/20 ',
# 058038138148 CHINANET Shanghai province network CN
'58.38.0.0/16 ',
# 058065163117 Nayatel (Pvt) Ltd PK
'58.65.163.0/24 ',
# 058069139204 CBGPLA2013021819001_CITYSTATE SAVINGS BANK INC PH
'58.69.139.0/24 ',
# 058079107040 LG POWERCOMM KR
'58.78.0.0/15 ',
# 058084031190 Netsol Technologies Limited PK
'58.84.28.0/22 ',
# 058138196250 VTOPIA KR
'58.138.192.0/18 ',
# 058141204021 DLIVE KR
'58.140.0.0/14 ',
# 058153186116 Hong Kong Telecommunications (HKT) Limited Mass Internet HK
'58.153.160.0/19 ',
# 058182011088 StarHub Cable Vision Ltd Singapore Broadband Access Provider SG
'58.182.8.0/21 ',
# 058182028229 StarHub Cable Vision Ltd Singapore Broadband Access Provider SG
'58.182.24.0/21 ',
# 058182097015 StarHub Cable Vision Ltd Singapore Broadband Access Provider SG
'58.182.96.0/21 ',
# 058186011164 FPT Telecom Company VN
'58.186.0.0/20 ',
# 058186252157 FPT Telecom Company VN
'58.186.240.0/20 ',
# 058187200155 FPT Telecom Company VN
'58.187.192.0/20 ',
# 058187217189 FPT Telecom Company VN
'58.187.208.0/20 ',
# 059089128101 O/o DGM BB, NOC BSNL Bangalore IN
'59.89.128.0/20 ',
# 059089178094 O/o DGM BB, NOC BSNL Bangalore IN
'59.89.176.0/20 ',
# 059090028245 O/o DGM BB, NOC BSNL Bangalore IN
'59.90.28.0/24 ',
# 059090031236 O/o DGM BB, NOC BSNL Bangalore IN
'59.90.31.0/24 ',
# 059090037101 O/o DGM BB, NOC BSNL Bangalore IN
'59.90.37.0/24 ',
# 059090050173 O/o DGM BB, NOC BSNL Bangalore IN
'59.90.50.0/24 ',
# 059092084028 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'59.92.80.0/20 ',
# 059092216002 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'59.92.208.0/20 ',
# 059094090077 O/o DGM BB, NOC BSNL Bangalore IN
'59.94.80.0/20 ',
# 059094141161 O/o DGM BB, NOC BSNL Bangalore IN
'59.94.128.0/20 ',
# 059094238103 O/o DGM BB, NOC BSNL Bangalore IN
'59.94.224.0/20 ',
# 059095073097 O/o DGM BB, NOC BSNL Bangalore IN
'59.95.64.0/20 ',
# 059144005173 VMOKSHA TECHNOLOGIES PVT LTD IN
'59.144.5.0/24 ',
# 059144172014 BTNL Delhi IN
'59.144.172.0/24 ',
# 059152010154 ZIPNET Limited BD
'59.152.10.0/24 ',
# 059152015238 ZIPNET Limited BD
'59.152.15.0/24 ',
# 059153201010 Minara Firoz Infotech, Internet Service Provider BD
'59.153.201.0/24 ',
# 060013003026 China Unicom Gansu province network CN
'60.13.0.0/18 ',
# 061000132110 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'61.0.128.0/20 ',
# 061000245061 National Internet Backbone IN
'61.0.240.0/20 ',
# 061001070076 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'61.1.64.0/20 ',
# 061001225191 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'61.1.224.0/20 ',
# 061002018043 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'61.2.16.0/20 ',
# 061002244076 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'61.2.240.0/20 ',
# 061003211051 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'61.3.208.0/20 ',
# 061004099062 PenangFON Residential Broadband MY
'61.4.96.0/21 ',
# 061005091190 PT TELEKOMUNIKASI INDONESIA ID
'61.5.88.0/22 ',
# 061005106175 PT TELKOM INDONESIA ID
'61.5.104.0/22 ',
# 061006021005 TT DOTCOM SDN BHD MY
'61.6.0.0/16 ',
# 061012071242 TTSL-ISP DIVISION IN
'61.12.71.0/24 ',
# 061012092146 TTSL-ISP DIVISION IN
'61.12.92.0/24 ',
# 061176215007 China Unicom Liaoning province network CN
'61.176.0.0/16 ',
# 062013025071 Cramo AB SE
'62.13.0.0/17 ',
# 062039122138 Mairie FR
'62.39.0.0/16 ',
# 062045225201 CAIW Internet NL
'62.45.224.0/19 ',
# 062063100150 OOO "Innovative advertising systems" RU
'62.63.96.0/19 ',
# 062068136177 DOCSIS/ETHERNET network of CATV operator TOMTEL in TOMSK and SEVERSK RU
'62.68.128.0/19 ',
# 062076014076 OOO UK SBT RU
'62.76.14.0/24 ',
# 062076075179 OOO SERVERLAND RU
'62.76.75.0/24 ',
# 062076103210 Start LLC RU
'62.76.103.0/24 ',
# 062084074178 LYNX-74 LB
'62.84.74.0/24 ',
# 062085095230 Address pool for LTC-HOME customers LV
'62.85.0.0/17 ',
# 062102148067 TOR Network SE
'62.102.148.0/23 ',
# 062112010166 WORLDSTREAM-BLK-62-112-8-0 NL
'62.112.8.0/22 ',
# 062122088051 LLC "Group Tower Telecom" RU
'62.122.88.0/21 ',
# 062122203082 Uteam Ltd UA
'62.122.200.0/22 ',
# 062122208130 AsteisNet RU
'62.122.208.0/22 ',
# 062127194021 Used for Telenor AB SE
'62.127.0.0/16 ',
# 062128217070 Rapidswitch - Privax additional range GB
'62.128.208.0/20 ',
# 062133162170 Bashinformsvyaz company RU
'62.133.160.0/21 ',
# 062133172049 Bashinformsvyaz Company, RUMS, DSL POOL RU
'62.133.168.0/21 ',
# 062133186036 Bashinformsvyaz Company, RUMS, DSL POOL RU
'62.133.184.0/21 ',
# 062138000085 Host Europe Group DE
'62.138.0.0/24 ',
# 062140132014 Vodafone NL Infrastructure NL
'62.140.132.0/23 ',
# 062140137026 Vodafone NL Infrastructure NL
'62.140.128.0/20 ',
# 062149029035 Colocall Ltd. UA
'62.149.29.0/24 ',
# 062152059041 HOSTER-VDS-BL01 RU
'62.152.59.0/24 ',
# 062166045209 Versatel customers NL
'62.166.0.0/16 ',
# 062176098151 Krakra Internet provider BG
'62.176.98.0/23 ',
# 062183124058 OJSC Rostelecom Macroregional Branch South RU
'62.183.124.0/22 ',
# 062209009034 ZAIN Bahrain WiMax BH
'62.209.8.0/23 ',
# 062209146169 ISP "TPS": Jet service - home users UZ
'62.209.128.0/19 ',
# 062213077231 CARAVAN AERO network RU
'62.213.77.0/24 ',
# 062213079156 CARAVAN AERO network RU
'62.213.79.0/24 ',
# 062228241240 M. SEREDA NETWORK LTD CY
'62.228.241.0/24 ',
# 062235167099 Scarlet Belgium NV/SA BE
'62.235.0.0/16 ',
# 062250084188 VUURWERK.NL 2nd NL
'62.250.0.0/16 ',
# 063248052015 JAB Wireless, INC. US
'63.248.0.0/16 ',
# 064022160001 Citadel Investment Group, L.L.C. US
'64.22.160.0/20 ',
# 064030119134 Consolidated Communications, Inc. US
'64.30.96.0/19 ',
# 064090069139 CONSOLIDATED TELEPHONE CO US
'64.90.64.0/19 ',
# 064094157088 Internap Network Services Corporation PNAP-05-2000 (NET-64-94-0-0-1) US
'64.94.0.0/15 ',
# 064110132072 Subnet Labs LLC SLL-1 (NET-64-110-128-0-1) US
'64.110.128.0/21 ',
# 064110254190 Saskatchewan Telecommunications SASK005 (NET-64-110-192-0-1) US
'64.110.192.0/18 ',
# 064113106102 Consolidated Communications, Inc. US
'64.113.96.0/19 ',
# 064126060047 SureWest Kansas Operations, LLC SWKS-BLK01 (NET-64-126-0-0-1) US
'64.126.0.0/17 ',
# 064137169134 KW Datacenter CA
'64.137.192.0/18 ',
# 064188206129 Zito Media US
'64.188.128.0/17 ',
# 064203239052 Hargray Communications Group, Inc. US
'64.203.192.0/18 ',
# 064220233049 XO Communications XOXO-BLK-5 (NET-64-220-0-0-1) US
'64.220.0.0/15 ',
# 064250059205 PTL-67 PEOPLES-DIA-IPV4 (NET-64-250-56-0-1) US
'64.250.56.0/22 ',
# 065018170152 OLM, LLC US
'65.18.192.0/19 ',
# 065038018238 TulsaConnect US
'65.38.0.0/19 ',
# 065039109194 Pinpoint Communications, Inc. US
'65.39.96.0/19 ',
# 066011127198 Garrison Network Solutions LLC GNS-4 (NET-66-11-112-0-1) US
'66.11.112.0/20 ',
# 066018164204 TULAROSA COMMUNICATIONS, INC. US
'66.18.160.0/20 ',
# 066070160131 OVH Hosting, Inc. HO-2 (NET-66-70-128-0-1) US
'66.70.128.0/17 ',
# 066085075094 Joe's Datacenter, LLC US
'66.85.72.0/21 ',
# 066102086077 WTC Communications CA
'66.102.64.0/19 ',
# 066110216008 GEORGIA PUBLIC WEB, INC. US
'66.110.192.0/19 ',
# 066111041224 United Colo US
'66.111.32.0/19 ',
# 066115091208 Electric Plant Board of the City of Glasgow, Kentucky US
'66.115.80.0/20 ',
# 066118122003 Watch Communications US
'66.118.112.0/20 ',
# 066158204022 Omnispring, LLC OMNISPRING (NET-66-158-192-0-1) US
'66.158.192.0/18 ',
# 066172120077 LocalTel Communications NWI-66-172 (NET-66-172-96-0-1) US
'66.172.96.0/19 ',
# 066175083156 SystemMetrics Corporation SYSMETRICS-BLK-2 (NET-66-175-64-0-1) US
'66.175.64.0/19 ',
# 066181177163 Fixed network MN
'66.181.177.0/24 ',
# 066205146092 Consolidated Communications, Inc. US
'66.205.128.0/19 ',
# 066222019212 TDS TELECOM US
'66.222.0.0/17 ',
# 066241086052 Ashland Fiber Network US
'66.241.64.0/19 ',
# 066248173053 VI POWERNET, LLC VIPOWERNET (NET-66-248-160-0-1) US
'66.248.160.0/19 ',
# 066253130059 PAVLOV MEDIA INC US
'66.253.128.0/17 ',
# 067014236163 Hotwire Communications HOTWIRE-AQUISITION-4 (NET-67-14-224-0-1) US
'67.14.224.0/19 ',
# 067021033215 Vivid Hosting VIVID-HOSTING-7 (NET-67-21-32-0-1) US
'67.21.32.0/22 ',
# 067021071099 Sharktech SHARKTECH-INC (NET-67-21-64-0-1) US
'67.21.64.0/19 ',
# 067023144015 The City of Wadsworth US
'67.23.144.0/20 ',
# 067029219164 Level 3 Communications, Inc. US
'67.24.0.0/13 ',
# 067158024027 Clarity Telecom LLC CLARITY-TELECOM-LLC (NET-67-158-0-0-1) US
'67.158.0.0/19 ',
# 067158043008 KNOLOGY, Inc. KNOLOGY-NET-PWAVE (NET-67-158-32-0-2) US
'67.158.32.0/20 ',
# 067205129087 DigitalOcean, LLC US
'67.205.128.0/18 ',
# 067205217094 Hotwire Communications HOTWI (NET-67-205-192-0-1) US
'67.205.192.0/18 ',
# 067207081087 DigitalOcean, LLC US
'67.207.64.0/19 ',
# 067209200096 Plateau Telecommunications Incorporated US
'67.209.192.0/19 ',
# 067211156048 LUMOS Networks, Inc. LUMOS-BLK-8 (NET-67-211-128-0-1) US
'67.211.128.0/19 ',
# 067214199164 Eagle Communications, Inc. EAGLENET-RUSSELL-KS (NET-67-214-199-0-1) US
'67.214.199.0/24 ',
# 067220253037 SuperHeroes SUPERHEROES (NET-67-220-253-0-1) US
'67.220.253.0/24 ',
# 067220254043 Cubex CUBEX (NET-67-220-254-0-1) US
'67.220.254.0/24 ',
# 067231016203 Idigital Internet Inc. CA
'67.231.16.0/20 ',
# 068071168170 MI-Connection Communications System US
'68.71.160.0/19 ',
# 068168226246 C.R.S.T. Telephone Authority US
'68.168.224.0/20 ',
# 068234134050 PAVLOV MEDIA INC PAVLOVMEDIA-6 (NET-68-234-128-0-1) US
'68.234.128.0/17 ',
# 069001011204 WideOpenWest Finance LLC WIDEOPENWEST (NET-69-1-0-0-1) US
'69.1.0.0/18 ',
# 069004081051 Adam Bate B2NET-SOLUTIONS (NET-69-4-81-2-1) US
'69.4.81.2/26 ',
# 069004085171 Web Hosting Solutions NET-69-4-85-160-1 (NET-69-4-85-160-1) US
'69.4.85.160/28 ',
# 069004087050 Falcon IT Service NET-69-4-87-48-1 (NET-69-4-87-48-1) US
'69.4.87.48/28 ',
# 069004088126 Web Hosting Solutions NET-69-4-88-112-1 (NET-69-4-88-112-1) US
'69.4.88.112/28 ',
# 069004091166 B2 Net Solutions Inc. CA
'69.4.80.0/20 ',
# 069007046063 Orcas Power & Light Cooperative CSS-OPALCO-69-7-46 (NET-69-7-46-0-1) US
'69.7.46.0/24 ',
# 069028052082 CPIXEL CNA-LA-CPIXEL-01 (NET-69-28-52-0-1) US
'69.28.52.0/24 ',
# 069028058022 Los Angeles Internet Exchange CNA-LA-IX-005 (NET-69-28-58-0-1) US
'69.28.58.0/24 ',
# 069054248043 ILCS Inc. ILCS-NET-01 (NET-69-54-224-0-1) US
'69.54.224.0/19 ',
# 069058000161 Web Hosting Solutions NET-69-58-0-160-1 (NET-69-58-0-160-1) US
'69.58.0.160/28 ',
# 069058003236 Web Hosting Solutions NET-69-58-3-224-1 (NET-69-58-3-224-1) US
'69.58.3.224/28 ',
# 069058005170 Web Hosting Solutions NET-69-58-5-160-1 (NET-69-58-5-160-1) US
'69.58.5.160/28 ',
# 069058010092 B2 Net Solutions Inc. B2-NET-SOLUTIONS (NET-69-58-0-0-1) US
'69.58.0.0/20 ',
# 069059216114 Peak Internet, LLC US
'69.59.192.0/19 ',
# 069061079157 Cyber Wurx LLC US
'69.61.0.0/17 ',
# 069064251040 WANSecurity, Inc. US
'69.64.240.0/20 ',
# 069071001001 Total Highspeed LLC US
'69.71.0.0/20 ',
# 069077193136 Golden West Telecommunications Coop., Inc. GWTC-NET-3 (NET-69-77-192-0-1) US
'69.77.192.0/18 ',
# 069084068231 BEK Communications Cooperative US
'69.84.64.0/20 ',
# 069094195119 GigaMonster US
'69.94.192.0/19 ',
# 069163088125 Montana Opticom, LLC US
'69.163.80.0/20 ',
# 069167000078 Paradise Networks LLC US
'69.167.0.0/19 ',
# 069167032090 Paradise Networks LLC US
'69.167.0.0/19 ',
# 069167036021 Paradise Networks LLC US
'69.167.0.0/19 ',
# 069167037049 Paradise Networks LLC US
'69.167.32.0/20 ',
# 069169138180 Veracity Networks LLC US
'69.169.128.0/18 ',
# 070035195066 Fasthosts Internet Inc. US
'70.35.192.0/20 ',
# 072011026197 Metalink Technologies, Inc. US
'72.11.0.0/19 ',
# 072013089082 NetFronts Inc NETFRONTS (NET-72-13-89-64-1) US
'72.13.89.64/27 ',
# 072013135228 Hotwire Communications HOTWIRE-COMMUNICATIONS (NET-72-13-128-0-1) US
'72.13.128.0/19 ',
# 072023186153 Armstrong Cable Services ACS-BOARDMANOH (NET-72-23-186-0-1) US
'72.23.186.0/24 ',
# 074118115196 Fourway Computer Products, Inc. US
'74.118.112.0/21 ',
# 074118245070 Miles Technologies Inc US
'74.118.244.0/22 ',
# 074120056068 BWTelcom US
'74.120.56.0/21 ',
# 074199010215 WIDEOPENWEST MICHIGAN WOW-TR18-5-0-199-74 (NET-74-199-0-0-2) US
'74.199.0.0/20 ',
# 074209129138 Highwinds Network Group, Inc. HIGHWINDS3 (NET-74-209-128-0-1) US
'74.209.128.0/20 ',
# 074220131047 Ritter Communications, Inc. US
'74.220.128.0/20 ',
# 074222019204 Perfect International, Inc US
'74.222.0.0/19 ',
# 076010029118 University Village at Boulder Creek COBOUVA (NET-76-10-29-0-1) US
'76.10.29.0/24 ',
# 076010035236 PAVLOV MEDIA INC PAVLOVMEDIA-7 (NET-76-10-0-0-1) US
'76.10.0.0/18 ',
# 076010068017 North Dakota Telephone Co. NDTEL-6 (NET-76-10-64-0-1) US
'76.10.64.0/18 ',
# 076010132162 TekSavvy Solutions, Inc. CA
'76.10.128.0/18 ',
# 076163252068 Ecommerce Corporation US
'76.162.0.0/15 ',
# 076164072036 Consolidated Communications, Inc. US
'76.164.64.0/18 ',
# 077031237030 DSL HOME Subscribers SA
'77.31.224.0/19 ',
# 077035217232 Dynamic Broadband Clients RU
'77.35.192.0/18 ',
# 077040007008 dialup&wifi pools RU
'77.40.7.0/24 ',
# 077040062068 xDSL dynamic pools RU
'77.40.62.0/24 ',
# 077073064012 veesp.com clients RU
'77.73.64.0/21 ',
# 077073095051 ZAO Torgovye Rjady RU
'77.73.95.0/24 ',
# 077075126155 WUD001 GB
'77.75.120.0/21 ',
# 077079132139 JSC "Ufanet" RU
'77.79.132.0/23 ',
# 077079224050 ATM S.A. PL
'77.79.192.0/18 ',
# 077081021171 UPC Romania Bucuresti E320 RO
'77.81.16.0/20 ',
# 077081094068 XT GLOBAL NETWORKS LTD QA
'77.81.92.0/22 ',
# 077081098098 Clues IPs RO
'77.81.98.0/24 ',
# 077081104141 Virtono Networks SRL RO
'77.81.104.0/24 ',
# 077081107129 Virtono Networks SRL RO
'77.81.107.0/24 ',
# 077081109170 Virtono Networks SRL RO
'77.81.109.0/24 ',
# 077081110224 Virtono Networks SRL RO
'77.81.110.0/24 ',
# 077081234151 Aruba S.p.A. - Cloud Services Farm2 IT
'77.81.224.0/20 ',
# 077087113113 COUNTRY-TELECOM Autonomus System net 113 RU
'77.87.113.0/24 ',
# 077088021089 Yandex enterprise network RU
'77.88.21.0/24 ',
# 077093127066 Stream-TV Tambov RU
'77.93.124.0/22 ',
# 077095204178 JSC SC NTEL RU
'77.95.200.0/21 ',
# 077106147238 Broadband Access NO
'77.106.128.0/18 ',
# 077106221009 ex-ZAO Sochitelecom RU
'77.106.208.0/20 ',
# 077109184055 CPE Customer Range CH
'77.109.160.0/19 ',
# 077120023006 TRK TV-Service LLC UA
'77.120.16.0/20 ',
# 077120094010 Volia Rivne Network UA
'77.120.92.0/22 ',
# 077120122131 colocation segment #22 UA
'77.120.122.0/24 ',
# 077125005255 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.125.0.0/20 ',
# 077125016115 Smile 012 Ltd IL
'77.125.16.0/20 ',
# 077125040112 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.125.32.0/20 ',
# 077125064254 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.125.64.0/20 ',
# 077125080180 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.125.80.0/20 ',
# 077125128148 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.125.128.0/20 ',
# 077126006254 Please Send Abuse/SPAM complaints To Abuse@012.net.il IL
'77.126.0.0/20 ',
# 077126029177 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.126.16.0/20 ',
# 077126047057 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.126.32.0/20 ',
# 077126058192 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.126.48.0/20 ',
# 077126066177 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.126.64.0/20 ',
# 077126084002 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.126.80.0/20 ',
# 077126100144 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.126.96.0/20 ',
# 077126113053 Please Send Abuse/SPAM complaints To Abuse@012.net.il. IL
'77.126.112.0/20 ',
# 077127001172 Please Send Abuse/SPAM complaints To Abuse@012.net.il IL
'77.127.0.0/20 ',
# 077127048255 Please Send Abuse/SPAM complaints To Abuse@012.net.il IL
'77.127.48.0/20 ',
# 077127120250 Please Send Abuse/SPAM complaints To Abuse@012.net.il IL
'77.127.112.0/20 ',
# 077127133118 Please Send Abuse/SPAM complaints To Abuse@012.net.il IL
'77.127.128.0/20 ',
# 077178116032 NCC#2006071591 DE
'77.176.0.0/12 ',
# 077221076019 KAVA LT
'77.221.72.0/21 ',
# 077232185034 LightPort Ltd RU
'77.232.184.0/22 ',
# 077234046134 AVAST Software s.r.o. US
'77.234.46.0/24 ',
# 077237228000 LIMEIP NETWORKS US
'77.237.228.0/23 ',
# 077238111120 Asiatech IPv4 Route IR
'77.238.108.0/22 ',
# 078024041100 LTD Pokrovsky radiotelefon RU
'78.24.41.0/24 ',
# 078025098230 Zap.Dvina RU
'78.25.96.0/21 ',
# 078060046166 Telia Lietuva, AB LT
'78.60.32.0/20 ',
# 078063194059 Telia Lietuva, AB LT
'78.63.192.0/20 ',
# 078081169027 OJSC "Rostelecom" North-West RU
'78.81.160.0/20 ',
# 078087211176 PROVIDER GR
'78.87.192.0/19 ',
# 078090024151 Liulin 1-10 BG
'78.90.24.0/22 ',
# 078090141216 INFRA-AW BG
'78.90.141.0/24 ',
# 078090185253 Dianabad and Mladost quarters BG
'78.90.0.0/16 ',
# 078094131126 Unitymedia B2B StaticIP aggregate DE
'78.94.128.0/17 ',
# 078095015232 SAUDINET-STC SA
'78.95.15.0/24 ',
# 078108046047 ENAHOST s.r.o. GR
'78.108.46.0/24 ',
# 078108066110 LLC "Infocentr" RU
'78.108.66.0/24 ',
# 078108087093 Saint-Petersburg department Majordomo Llc RU
'78.108.87.0/24 ',
# 078109036079 Sibron ZAO Infrastructure RU
'78.109.36.0/23 ',
# 078111092059 Prometey Ltd RU
'78.111.92.0/23 ',
# 078136240042 BIKS+ Ltd RU
'78.136.240.0/22 ',
# 078138128063 Kazan Broad-band access pools RU
'78.138.128.0/21 ',
# 078153114183 OzdKabel-Tv-Docsis HU
'78.153.112.0/20 ',
# 078153148092 Krek Ltd. 2 RU
'78.153.148.0/22 ',
# 078155193226 Selectel Ltd. RU
'78.155.192.0/23 ',
# 078155194005 Selectel Ltd. RU
'78.155.194.0/24 ',
# 078155213111 SDN Customer's network RU
'78.155.212.0/23 ',
# 078155215254 LLC "ConceptGROUP" RU
'78.155.215.0/24 ',
# 078155222172 Selectel SPb RU
'78.155.220.0/22 ',
# 078156103113 Infrastructure EM - DHCP assignments residential users DK
'78.156.96.0/19 ',
# 078156250002 Open Joint-stock company "Dagsvyazinform" RU
'78.156.250.0/24 ',
# 078165071118 TT ADSL-TTnet_dynamic_ulus TR
'78.165.64.0/18 ',
# 079046064252 NAS DHCP Pool Verona IT
'79.46.0.0/15 ',
# 079104200099 LLC "EDN Sovintel" Krasnoyarsk Branch RU
'79.104.200.0/22 ',
# 079106035026 ALBTELECOM DEDICATED OPERATORS AL
'79.106.0.0/16 ',
# 079110025131 TRUSOV UA
'79.110.25.0/24 ',
# 079110028018 United Kingdom GB
'79.110.28.0/24 ',
# 079110031048 Moscow Network RU
'79.110.31.0/24 ',
# 079110206018 ul. Nabycinska 19 PL
'79.110.206.0/24 ',
# 079122217238 ENFORTA RU
'79.122.216.0/22 ',
# 079124059202 Tamatiya EOOD BG
'79.124.59.0/24 ',
# 079125163183 OnNet ADSL IP Subnet MK
'79.125.160.0/19 ',
# 079133033003 DE
'79.133.32.0/19 ',
# 079133106021 Trusov Ilya Igorevych RU
'79.133.106.0/23 ',
# 079134084242 DigitOne P Network RU
'79.134.64.0/19 ',
# 079134107139 Suomi Communications Oy FI
'79.134.96.0/19 ',
# 079134255200 DataCell ehf IS
'79.134.240.0/20 ',
# 079135195131 ICN Ltd. UA
'79.135.195.0/24 ',
# 079137067116 https://www.ovh.com FR
'79.137.64.0/18 ',
# 079140121238 Martens VOIP BB DE
'79.140.112.0/20 ',
# 079140159001 BBM ISP IP Network ME
'79.140.156.0/22 ',
# 079140231123 Asiabell Network 1 KZ
'79.140.231.0/24 ',
# 079142073014 AltusHost B.V. NL
'79.142.73.0/24 ',
# 079143175178 Elta Kabel d.o.o. BA
'79.143.172.0/22 ',
# 079143186123 Contabo GmbH DE
'79.143.186.0/23 ',
# 079156145082 Red de servicios IP ES
'79.156.0.0/16 ',
# 079169035179 NOS COMUNICACOES S.A. PT
'79.169.0.0/18 ',
# 079175231208 SMSNET Sosnowiec PL
'79.175.224.0/20 ',
# 079176073115 *SE5-PTK* IL
'79.176.72.0/22 ',
# 080012034252 Orange Mobile FR
'80.12.0.0/18 ',
# 080064105003 Life-Link RU
'80.64.96.0/20 ',
# 080067172162 Globenet network at Telehouse2 (Paris 11, France) FR
'80.67.160.0/19 ',
# 080071246068 Rial Com JSC clients network RU
'80.71.240.0/21 ',
# 080077126091 DoclerWeb Kft. HU
'80.77.112.0/20 ',
# 080078073116 Business clients at the HFC Abcom Infrastructure AL
'80.78.64.0/20 ',
# 080078253170 Reg.Ru Hosting RU
'80.78.253.0/24 ',
# 080080175034 Ipko Prizren PoP Clients servers and routers AL
'80.80.175.0/24 ',
# 080082024025 INTRINO SP. Z O.O. PL
'80.82.24.0/24 ',
# 080082067166 QUASI SC
'80.82.67.0/24 ',
# 080082095115 DSL clients of Infoseti RU
'80.82.80.0/20 ',
# 080083231051 Vladivostok (Far East) division of Mobile Telesystems OJSC RU
'80.83.228.0/22 ',
# 080085157170 RU
'80.85.157.0/24 ',
# 080085159169 RU
'80.85.159.0/24 ',
# 080087193245 JSC Server WebDC colocation RU
'80.87.192.0/23 ',
# 080087202227 JSC Server WebDC colocation RU
'80.87.202.0/24 ',
# 080088118159 Aselehus kabel-tv-nat SE
'80.88.96.0/19 ',
# 080103053217 Uni2 IP Data Network ES
'80.102.0.0/15 ',
# 080107117210 HELLENIC TELECOMMUNICATIONS ORGANIZATION GR
'80.107.116.0/22 ',
# 080187103111 Telekom Deutschland GmbH DE
'80.187.103.0/24 ',
# 080209252138 AbeloHost B.V. NL
'80.209.252.0/23 ',
# 080211224227 Aruba S.p.A. - Cloud Services Farm2 IT
'80.211.224.0/20 ',
# 080214151224 Bouygues Telecom RES Division for FR
'80.214.128.0/18 ',
# 080241060207 Heinlein Support GmbH DE
'80.241.56.0/21 ',
# 080241211014 Contabo GmbH DE
'80.241.210.0/23 ',
# 080241219083 Contabo GmbH DE
'80.241.218.0/23 ',
# 080244041087 JSC "SvyazTeleKom", Magnitogorsk, Russia RU
'80.244.41.0/24 ',
# 080244237022 Wireless Backbone Network RU
'80.244.236.0/23 ',
# 080245023179 Customer SDSL Delivery for Savoie - Haute-Savoie FR
'80.245.16.0/20 ',
# 080246139237 Mirs UMTS 3G network mobiles NAT IL
'80.246.139.0/24 ',
# 080249188100 NAT pool for homenet customers RU
'80.249.176.0/20 ',
# 080253235082 Company Atlantic Ltd., 13 Slavy street office 28, 660132, Krasnoyarsk, Russia RU
'80.253.224.0/20 ',
# 081001132202 CJSC Scientific-Production Association Progressive Technologies RU
'81.1.128.0/18 ',
# 081003139180 UFK INET LO RU
'81.3.128.0/18 ',
# 081010172142 Cable Customers AT
'81.10.128.0/17 ',
# 081014034139 Dynamic Pools FR
'81.14.0.0/17 ',
# 081016009192 WiMAX DHCP Pool AM
'81.16.8.0/23 ',
# 081017154120 DCTelecom RU
'81.17.152.0/22 ',
# 081021075142 DH-SR-WIN GB
'81.21.75.0/24 ',
# 081022208166 TELESET LLC RU
'81.22.208.0/24 ',
# 081023008161 OOO exat LLC RU
'81.23.8.0/23 ',
# 081024117014 Severen Telecom RU
'81.24.112.0/20 ',
# 081025019083 Cerberos s.r.o. CZ
'81.25.16.0/21 ',
# 081030195154 ZAO "Delovaja set" Internet provider, 902,17 Curupa str.,Ufa Russia RU
'81.30.194.0/23 ',
# 081088211180 PPPoE peers RU
'81.88.208.0/21 ',
# 081091188208 Gorodskie Seti Ltd. RU
'81.91.184.0/21 ',
# 081094074233 Soderhamn NARA AB SE
'81.94.64.0/19 ',
# 081095172090 Magticom Ltd. GE
'81.95.172.0/24 ',
# 081161203247 Oxylion S.A. PL
'81.161.200.0/21 ',
# 081162127116 YaltaRoute1 RU
'81.162.96.0/19 ',
# 081162224132 PP "TRC City TV center" UA
'81.162.224.0/20 ',
# 081169220186 Strato Rechenzentrum, Berlin DE
'81.169.220.0/24 ',
# 081171014151 NL
'81.171.0.0/19 ',
# 081186232110 Digital Media Technology Sp. z o.o. PL
'81.186.224.0/20 ',
# 081249202005 POP IDF3 FR
'81.249.128.0/17 ',
# 082097250082 Padidar-Asiatech Leased Prefix IR
'82.97.248.0/22 ',
# 082099216003 Parsonline-Dynamic-Pool IR
'82.99.216.0/24 ',
# 082114255015 "Saratov Digital Phone Network" ltd RU
'82.114.255.0/24 ',
# 082115013165 Ertebatat Faragostar Shargh ISDP, Mashhad IR
'82.115.12.0/23 ',
# 082116203114 University of Nicosia/Intercollege CY
'82.116.192.0/19 ',
# 082117162102 New Telesystems - TV, Ltd. RU
'82.117.162.0/24 ',
# 082126026019 BSROU156 Rouen Bloc 1 FR
'82.126.0.0/16 ',
# 082132240205 O2 Online (UK) GB
'82.132.240.0/23 ',
# 082140129097 PROVIDER Local Registry LT
'82.140.128.0/19 ',
# 082140181220 WiMAX address pool LT
'82.140.176.0/21 ',
# 082145093062 pl.amsk PL
'82.145.64.0/19 ',
# 082148097167 ISP infrastructure QA
'82.148.97.0/24 ',
# 082194018006 ENGINET LLC AZ
'82.194.18.0/24 ',
# 082196007210 Digital Ocean, Inc. NL
'82.196.0.0/21 ',
# 082199209151 Ucom GPON network AM
'82.199.209.0/24 ',
# 082200205049 Andrey Lorer KZ
'82.200.205.0/24 ',
# 082202205011 Selectel RU
'82.202.192.0/18 ',
# 082205073039 BSA Block #2 PS
'82.205.64.0/20 ',
# 082208129043 UPC Romania Cluj E320 RO
'82.208.128.0/19 ',
# 082209049200 Gymnazium, Ostrava-Zabreh, Volgogradska 6a, prispevkova or CZ
'82.209.0.0/18 ',
# 082209130162 MKB SE
'82.209.128.0/18 ',
# 082222152242 TELLCOM ILETISIM HIZMETLERI A.S. TR
'82.222.152.0/24 ',
# 083036190216 Telefonica de Espana SAU ES
'83.36.0.0/16 ',
# 083069010097 CJSC TransTeleCom RU
'83.69.10.0/24 ',
# 083097109254 Trytek Internet provider RU
'83.97.104.0/21 ',
# 083123070157 4G IR
'83.123.0.0/17 ',
# 083128164122 CAIW Internet NL
'83.128.160.0/19 ',
# 083131178008 Hrvatski Telekom d.d. HR
'83.131.176.0/22 ',
# 083142187250 HOR.NET PL
'83.142.184.0/21 ',
# 083147230080 IR-KavoshgarNovin IR
'83.147.224.0/21 ',
# 083166241077 LLC Management Company "Svyaz" RU
'83.166.240.0/23 ',
# 083169208218 Center Branch of OJSC MegaFon B2B pool 8 RU
'83.169.208.0/24 ',
# 083172000069 TomTrunk, ISP in Tomsk and Tomsk region RU
'83.172.0.0/23 ',
# 083198195101 BSLIL656 Lille Bloc 2 FR
'83.198.0.0/16 ',
# 083200059073 BSAUB651 Aubervilliers Bloc 1 FR
'83.200.0.0/17 ',
# 083205044190 BSBOR552 Bordeaux Bloc 1 FR
'83.205.0.0/16 ',
# 083217008067 Park-web Ltd. RU
'83.217.8.0/24 ',
# 083217009195 Park-web Ltd. RU
'83.217.9.0/24 ',
# 083217010031 Park-web Ltd. RU
'83.217.10.0/24 ',
# 083220174128 TheFirst-RU RU
'83.220.174.0/23 ',
# 083220185087 Rial Com JSC clients network RU
'83.220.184.0/21 ',
# 083221132154 Meebox ApS DK
'83.221.128.0/19 ',
# 083230042155 G-Net s.c. T. Serwatka, W. Rakoniewski PL
'83.230.40.0/21 ',
# 083239095062 OJSC Rostelecom Macroregional Branch South RU
'83.239.64.0/19 ',
# 083239232005 Joint Stock Company "Electrosvyaz of Adygheia Republic" 22A Zhukovskogo Str, Maikop,385000, Russia RU
'83.239.224.0/20 ',
# 083247007021 PROVIDER Local Registry NL
'83.247.0.0/17 ',
# 084016224001 Leaseweb Deutschland GmbH DE
'84.16.224.0/19 ',
# 084018100238 ISP TATTELECOM, Russian Federation, city Kazan RU
'84.18.100.0/23 ',
# 084023150006 FTTH users in Gavle, Sweden SE
'84.23.128.0/19 ',
# 084036024243 ADSL Service - Users EG
'84.36.1.0/19 ',
# 084039045127 Cloudwatt FR
'84.39.32.0/19 ',
# 084040084067 NET1 Ltd. BG
'84.40.84.0/24 ',
# 084051223252 PJSC Rostelecom Macroregional Branch South RU
'84.51.192.0/19 ',
# 084054112054 Department of Diplomatic Services UZ
'84.54.112.0/24 ',
# 084056076220 ARCOR AG DE
'84.56.0.0/15 ',
# 084094090197 Please Send Abuse/SPAM complaints To Abuse@012.net IL
'84.94.90.0/24 ',
# 084126183076 PROVIDER Local Registry ES
'84.126.176.0/20 ',
# 084205098189 City Stars EG
'84.205.96.0/19 ',
# 084212194082 Customers Asker NO
'84.212.128.0/17 ',
# 084219223168 Telenor Sverige AB SE
'84.216.0.0/14 ',
# 084228198170 Smile Internet Gold IL
'84.228.192.0/21 ',
# 084229058163 Smile Internet Gold IL
'84.229.56.0/21 ',
# 084229069144 Smile Internet Gold IL
'84.229.64.0/21 ',
# 084229075030 Smile Internet Gold IL
'84.229.72.0/21 ',
# 084229122148 Smile Internet Gold IL
'84.229.120.0/21 ',
# 084229136092 Smile Internet Gold IL
'84.229.136.0/21 ',
# 084229171073 Smile Internet Gold IL
'84.229.168.0/21 ',
# 084241025174 SHATEL DSL Network IR
'84.241.25.0/24 ',
# 084242196013 Novgorod Datacom RU
'84.242.192.0/18 ',
# 085008026022 AllTele SE
'85.8.0.0/18 ',
# 085009255242 DG-Starnet LV
'85.9.255.0/24 ',
# 085014243039 Public address space for webtropia.com servers DE
'85.14.192.0/18 ',
# 085029225239 Dynamic Links EE
'85.29.192.0/18 ',
# 085090244023 Linode, LLC DE
'85.90.244.0/23 ',
# 085090246006 DE
'85.90.244.0/22 ',
# 085091192135 CRELCOM broadband RU
'85.91.192.0/19 ',
# 085093143051 Internet clients pool RU
'85.93.136.0/21 ',
# 085093145049 JSC Planetahost RU
'85.93.144.0/20 ',
# 085113214202 Joint Stock Company "Rostelecom" RU
'85.113.214.0/24 ',
# 085130004026 Blizoo Media and Broadband EAD BG
'85.130.0.0/20 ',
# 085133141169 Sepanta ADSL Addresses IR
'85.133.140.0/22 ',
# 085133161046 Sepanta ADSL Users IR
'85.133.160.0/22 ',
# 085133207066 Sepanta Communication Development Co. Ltd IR
'85.133.204.0/22 ',
# 085158120058 GENERIXGROUP FR
'85.158.120.0/24 ',
# 085187109031 entry.bg PA space BG
'85.187.64.0/18 ',
# 085187246035 SKYNET BULGARIA Ltd. BG
'85.187.244.0/22 ',
# 085195255069 Fiber7 CH
'85.195.224.0/19 ',
# 085199071066 KabelSat Bergen GmbH DE
'85.199.64.0/18 ',
# 085202011047 aka SP-COM Ltd RU
'85.202.8.0/21 ',
# 085202233046 MTK MOSINTER INC. RU
'85.202.232.0/21 ',
# 085203007035 Falco IPR BV NL
'85.203.0.0/18 ',
# 085203115048 OZONE SAS FR
'85.203.64.0/18 ',
# 085204172052 XT GLOBAL NETWORKS LTD IN
'85.204.168.0/21 ',
# 085206185084 Telia Lietuva, AB LT
'85.206.184.0/21 ',
# 085214025039 Strato Rechenzentrum, Berlin DE
'85.214.25.0/24 ',
# 085214254227 Strato Rechenzentrum, Berlin DE
'85.214.254.0/24 ',
# 085216077155 Kabel Baden-Wuerttemberg GmbH & Co. KG DE
'85.216.64.0/18 ',
# 085239152186 Cable Television BAC BG
'85.239.152.0/22 ',
# 085248227163 Platon Technologies s.r.o SK
'85.248.0.0/16 ',
# 085254017164 IT MANAGEMENT GROUP SIA LV
'85.254.16.0/22 ',
# 085255000133 Cloud Services CZ1 CZ
'85.255.0.0/20 ',
# 086063039137 Cable Modem Customers EU
'86.63.0.0/18 ',
# 086100173013 Balticum TV network (Klaipeda) LT
'86.100.128.0/18 ',
# 086104077111 EliteWork LLC US
'86.104.76.0/22 ',
# 086105050203 Cloud Services DC04 DE
'86.105.48.0/22 ',
# 086105055106 Cloud Services DC04 DE
'86.105.52.0/22 ',
# 086106016197 Solid Seo VPS RO
'86.106.16.0/22 ',
# 086106201005 REAL NETWORK AND TEL SRL RO
'86.106.200.0/21 ',
# 086106254060 JSC "Moldtelecom" S.A. MD
'86.106.224.0/19 ',
# 086107020216 TELESON SRL RO
'86.107.20.128/25 ',
# 086109100080 ACENS-VCL-TP-1 ES
'86.109.96.0/19 ',
# 086110118106 TakeWYN "privately owned enterprise" takewyn.com RU
'86.110.116.0/22 ',
# 086161107152 IP pools GB
'86.128.0.0/10 ',
# 086219245248 BSMAR657 Marseille Bloc 2 FR
'86.219.0.0/16 ',
# 086228014239 BSAMI554 Amiens Bloc 1 FR
'86.228.14.0/24 ',
# 086236138072 POP Orleans FR
'86.236.136.0/21 ',
# 086239186032 POP POI FR
'86.239.184.0/21 ',
# 086244016141 POP ORL FR
'86.244.16.0/21 ',
# 086245053117 POP Puteaux FR
'86.245.48.0/21 ',
# 086250057093 POP BOR FR
'86.250.56.0/21 ',
# 087069037066 Please Send Abuse/SPAM complaints To Abuse@012.net.il IL
'87.69.32.0/21 ',
# 087069054113 Please Send Abuse/SPAM complaints To Abuse@012.net.il IL
'87.69.48.0/21 ',
# 087076073242 ANTIDOT Route object UA
'87.76.73.0/24 ',
# 087119064012 Digital cable Television Ltd. BG
'87.119.64.0/24 ',
# 087121000165 Neterra Ltd. BG
'87.121.0.0/23 ',
# 087122078070 Versatel Deutschland DE
'87.122.64.0/20 ',
# 087193138172 ISS Facility Services GmbH DE
'87.193.0.0/16 ',
# 087224032138 Integer Research GB
'87.224.0.0/17 ',
# 087227162113 Mobiltel_Region_Veliko_Turnovo_Networks BG
'87.227.128.0/17 ',
# 087228154088 Cyprus Telecommuncations Authority CY
'87.228.144.0/20 ',
# 087229077152 SzerverPlex.hu Kft HU
'87.229.77.0/24 ',
# 087236215160 1 Gbits Com LT
'87.236.215.0/24 ',
# 087239248226 HostRoyale Technologies Pvt Ltd ES
'87.239.248.0/24 ',
# 087239254009 ES
'87.239.254.0/23 ',
# 087241090113 AllTele Broadband BRF Yxan SE
'87.241.80.0/20 ',
# 087246165068 APOLLO-BRIDGE-GROUP-STATIC-IP-CUSTOMERS LV
'87.246.160.0/19 ',
# 087248032032 --------------- IT
'87.248.32.0/20 ',
# 087250250089 Yandex enterprise network RU
'87.250.250.0/24 ',
# 088001142079 Telefonica de Espana SAU ES
'88.1.0.0/16 ',
# 088003042115 Telefonica de Espana SAU ES
'88.3.0.0/16 ',
# 088080034132 LLC "Grazhdan-Servis", Russia, Izhevsk RU
'88.80.32.0/19 ',
# 088082086054 Fatum RU
'88.82.86.0/24 ',
# 088083040246 LAN clients in Hattfjalldal NO
'88.83.32.0/19 ',
# 088084014053 Elektrizitaets- und Wasserwerk der Stadt Buchs SG CH
'88.84.0.0/19 ',
# 088086010037 SCS-NET is an ISP based in Damascus Syria SY
'88.86.0.0/20 ',
# 088119114133 Telia Lietuva, AB LT
'88.119.64.0/18 ',
# 088133012129 amplus AG broadband access aggregation DE
'88.133.0.0/17 ',
# 088133133076 amplus AG broadband access aggregation DE
'88.133.128.0/20 ',
# 088151077154 static IP for WLAN customers AT
'88.151.72.0/21 ',
# 088151182074 Sky Silk Autonomous System KZ
'88.151.176.0/21 ',
# 088202178101 GB
'88.202.176.0/20 ',
# 088206242194 IP network Highlandnet SE, Savsjo-SE SE
'88.206.240.0/21 ',
# 088210048167 CITYLAN-NET-1 RU
'88.210.32.0/19 ',
# 088212235187 United Network LLC more-specific route RU
'88.212.232.0/21 ',
# 088237013238 TT ADSL-TTnet_dynamic_gay TR
'88.237.0.0/17 ',
# 089017042064 IntInform Network Route RU
'89.17.32.0/19 ',
# 089023243089 Fiberby ApS DK
'89.23.224.0/19 ',
# 089025022138 BG
'89.25.20.0/22 ',
# 089025028019 Internet Service Provider BG
'89.25.28.0/24 ',
# 089031044108 SMARTBOX CB s.r.o. CZ
'89.31.40.0/21 ',
# 089031096168 XL Internet Services Amsterdam Network NL
'89.31.96.0/21 ',
# 089032120160 XT GLOBAL NETWORKS LTD SE
'89.32.120.0/22 ',
# 089032127178 LcS Network Media Comunication SRL RO
'89.32.127.0/24 ',
# 089032134126 EliteWork LLC US
'89.32.132.0/22 ',
# 089032178025 Solid Seo VPS RO
'89.32.176.0/21 ',
# 089032195120 XT GLOBAL NETWORKS LTD SE
'89.32.192.0/22 ',
# 089032232058 IS Centrul de Telecomunicatii Speciale Moldova MD
'89.32.224.0/20 ',
# 089032250101 XT GLOBAL NETWORKS LTD CH
'89.32.248.0/22 ',
# 089033042070 XT GLOBAL NETWORKS LTD FI
'89.33.40.0/22 ',
# 089033068035 XT GLOBAL NETWORKS LTD FR
'89.33.68.0/22 ',
# 089033246199 M247 Europe SRL RO
'89.33.246.0/24 ',
# 089034096098 GB
'89.34.96.0/22 ',
# 089034166098 XT GLOBAL NETWORKS LTD AE
'89.34.164.0/22 ',
# 089034237101 NETACTION TELECOM SRL-D RO
'89.34.237.0/24 ',
# 089035030004 GB
'89.35.28.0/22 ',
# 089035198070 XT GLOBAL NETWORKS LTD GR
'89.35.196.0/22 ',
# 089036212004 Aruba Cloud FR
'89.36.212.0/22 ',
# 089036217192 Cloud Services DC05 DE
'89.36.216.0/22 ',
# 089036248035 Next Start SRL RO
'89.36.248.0/22 ',
# 089037064019 GB
'89.37.64.0/24 ',
# 089037065010 GB
'89.37.64.0/22 ',
# 089038090077 Telecommunication Company of Khorasan Shomali IR
'89.38.88.0/21 ',
# 089038096007 NL
'89.38.96.0/22 ',
# 089038148005 Aruba Cloud FR
'89.38.148.0/22 ',
# 089038208057 LIVEHOSTING DATACENTER SRL RO
'89.38.208.0/23 ',
# 089039106188 NL
'89.39.104.0/22 ',
# 089039136036 XT GLOBAL NETWORKS LTD GB
'89.39.136.0/21 ',
# 089040112015 Aruba Cloud FR
'89.40.112.0/22 ',
# 089040116171 Cloud Services DC05 DE
'89.40.116.0/22 ',
# 089040124065 Cloud Services DC05 DE
'89.40.124.0/22 ',
# 089040147146 Data Space PL
'89.40.147.0/24 ',
# 089041171123 SC IMPATT SRL RO
'89.41.170.0/23 ',
# 089042078253 JSC "Moldtelecom" S.A. MD
'89.42.72.0/21 ',
# 089042237061 Lixux OU EE
'89.42.236.0/22 ',
# 089043115025 XT GLOBAL NETWORKS LTD FR
'89.43.112.0/21 ',
# 089044043090 XT GLOBAL NETWORKS LTD QA
'89.44.40.0/22 ',
# 089045201082 SC FX SOFTWARE SRL RO
'89.45.200.0/21 ',
# 089045226028 CityCloud SE
'89.45.226.0/24 ',
# 089046242180 International Olisat S.R.L. RO
'89.46.242.0/24 ',
# 089047015233 VPN Services HK
'89.47.15.0/24 ',
# 089047200237 Sepehrava Data Processing co. IR
'89.47.200.0/22 ',
# 089047239116 Loja TV ES
'89.47.236.0/22 ',
# 089047252002 IT Assist Services SRL RO
'89.47.252.0/24 ',
# 089105255174 VEGA Kherson UA
'89.105.255.0/24 ',
# 089106041017 Hervanta district FI
'89.106.32.0/19 ',
# 089109209034 DSL access network in Moscow region RU
'89.109.208.0/21 ',
# 089121031134 Romtelecom Data Network RO
'89.121.0.0/18 ',
# 089131199177 Ya.com Internet Factory ES
'89.131.192.0/20 ',
# 089144175248 Andishe Sabz Khazar IP Block IR
'89.144.160.0/20 ',
# 089146080189 Dynamic address pool for PPPoE clients UZ
'89.146.64.0/18 ',
# 089146185150 BH Telecom PPPoE BRAS dynamic pool #2 Tuzla BA
'89.146.176.0/20 ',
# 089162007111 Signal Bredband AS NO
'89.162.0.0/17 ',
# 089165016053 Sabanet Tehran IR
'89.165.16.0/20 ',
# 089184010077 Kazan, Russia RU
'89.184.8.0/22 ',
# 089184072093 Internet Invest Ltd. UA
'89.184.72.0/24 ',
# 089188121126 C-BL008 RU
'89.188.121.0/24 ',
# 089188229014 Sakhalin Telecom network RU
'89.188.229.0/24 ',
# 089197022046 The UGLI Campus GB
'89.197.0.0/16 ',
# 089198168019 3G service IR
'89.198.128.0/17 ',
# 089205061136 SKOPJE CABLE MK
'89.205.56.0/21 ',
# 089207079190 JSC "Relax" RU
'89.207.79.0/24 ',
# 089210007004 Hellas On Line SA - DSL GR
'89.210.0.0/19 ',
# 089210046239 Hellas On Line SA - DSL GR
'89.210.32.0/19 ',
# 089210200065 Hellas On Line SA - DSL GR
'89.210.192.0/19 ',
# 089219114096 Telecommunication Company of Tehran IR
'89.219.64.0/18 ',
# 089219242209 Telecommunication Company of Tehran IR
'89.219.192.0/18 ',
# 089221082246 Fanava Tehran Network (Infrastructure) IR
'89.221.82.0/24 ',
# 089223027241 Comfortel Ltd. RU
'89.223.24.0/21 ',
# 089235086003 Asiatech xDSL Network IR
'89.235.80.0/21 ',
# 089236034117 Perspektiv Bredband AB SE
'89.236.0.0/18 ',
# 089237082043 Dynamic pools IL
'89.237.64.0/18 ',
# 089238229169 Bulevardul Tudor Vladimirescu, nr. 45, et. 5 RO
'89.238.192.0/18 ',
# 089244161254 Versatel Deutschland DE
'89.244.160.0/20 ',
# 089250153096 JSC "ER-Telecom Holding" Tyumen' branch RU
'89.250.152.0/22 ',
# 089250159118 JSC "ER-Telecom Holding" Tyumen' branch RU
'89.250.156.0/22 ',
# 089251028194 Metrocom UA
'89.251.24.0/21 ',
# 089253074058 CUSTOMERS-OWNIT-SE SE
'89.253.64.0/18 ',
# 089253161207 Blizoo Media and Broadband EAD BG
'89.253.160.0/21 ',
# 089254156017 OSTKOM SIA LV
'89.254.128.0/18 ',
# 090010037242 BSREU551 Reunion Bloc 1 RE
'90.10.0.0/16 ',
# 090038001066 BSBOR653 Bordeaux Bloc 1 FR
'90.38.0.0/16 ',
# 090054024111 France Telecom IP2000-ADSL-BAS FR
'90.54.0.0/16 ',
# 090063096075 BSORL656 Orleans Bloc 2 FR
'90.63.0.0/17 ',
# 090064049214 Orange Slovensko, a.s. SK
'90.64.0.0/17 ',
# 090096170119 Orange France - WFP FR
'90.96.128.0/18 ',
# 090110103189 POP Lille FR
'90.110.96.0/21 ',
# 090146171106 DOCSIS AT
'90.146.128.0/17 ',
# 090151032176 Dynamic distribution IP's for broadband services RU
'90.151.32.0/24 ',
# 090155166040 Iskratelecom CJSC RU
'90.155.128.0/18 ',
# 090158024181 KAREL TR
'90.158.0.0/16 ',
# 091075225174 Emirates Integrated Telecommunications Company PJSC (EITC-DU) AE
'91.75.224.0/19 ',
# 091088144098 Dynamic Pools FR
'91.88.0.0/16 ',
# 091090013067 Odessa, Ukraine UA
'91.90.13.0/24 ',
# 091090248041 Telenet LV
'91.90.224.0/19 ',
# 091093093014 IstanbulAvrupaVae TR
'91.93.93.0/24 ',
# 091093132138 Global Iletisim Net-6 TR
'91.93.132.0/24 ',
# 091093255213 SOL-Bireysel TR
'91.93.252.0/22 ',
# 091099253157 Static-Pool-PR00 IR
'91.99.248.0/21 ',
# 091103026189 Eurowagen Co.Ltd. AM
'91.103.26.0/24 ',
# 091107111063 RU
'91.107.111.0/24 ',
# 091107119019 MGNHost, 91.107.119.0 RU
'91.107.119.0/24 ',
# 091107161029 Asiatech xDSL Network IR
'91.107.128.0/18 ',
# 091108071104 IPFFM Internet Provider Frankfurt GmbH DE
'91.108.64.0/19 ',
# 091108176006 Reverse-Proxy SE
'91.108.176.0/24 ',
# 091108177085 Reverse-Proxy SE
'91.108.177.0/24 ',
# 091108178013 Reverse-Proxy SE
'91.108.178.0/24 ',
# 091122195202 ATNET-RU RU
'91.122.192.0/19 ',
# 091123157156 NashNet route UA
'91.123.156.0/22 ',
# 091123176033 GIGA Route #1 PL
'91.123.176.0/20 ',
# 091126129035 ES-ADAMO-CATALUNYA-CORP ES
'91.126.128.0/22 ',
# 091134123096 OVH Static IP FR
'91.134.0.0/16 ',
# 091135247249 Aztelekom.Net Ip Segment AZ
'91.135.240.0/20 ',
# 091136164202 Elementmedia GmbH DE
'91.136.128.0/17 ',
# 091142090109 Miran VDS client infrastructure RU
'91.142.90.0/24 ',
# 091142094218 Miran VDS client infrastructure RU
'91.142.94.0/24 ',
# 091142144179 Teledyne Systems Limited RU
'91.142.144.0/20 ',
# 091143034042 RADIONET,Telecoms RU
'91.143.32.0/22 ',
# 091149137146 Belarusian-American joint venture "Cosmos TV", Ltd. BY
'91.149.136.0/21 ',
# 091150029065 DHCP-addresses for KTAB customers FI
'91.150.0.0/18 ',
# 091160013020 Broadband Pool FR
'91.160.0.0/12 ',
# 091184155084 Nye ASP nett Drammen NO
'91.184.128.0/19 ',
# 091185065206 PJSC "Mobile TeleSystems", Izhevsk fix RU
'91.185.64.0/21 ',
# 091185185244 ECO-ATMAN-PL PL
'91.185.185.0/24 ',
# 091185190211 ECO-ATMAN-PL PL
'91.185.190.0/24 ',
# 091187093052 Andorra Telecom AD
'91.187.92.0/22 ',
# 091189034027 BIZNES-HOST.PL PL
'91.189.32.0/22 ',
# 091189036202 BIZNES-HOST.PL PL
'91.189.36.0/22 ',
# 091190077155 RU-INTELECOM-PHYS-POOL RU
'91.190.64.0/20 ',
# 091190143205 Customer Lidingo Gronbyv SE
'91.190.136.0/21 ',
# 091192075210 Commaster Ltd. RU
'91.192.75.0/24 ',
# 091192244050 Severnet Ltd. RU
'91.192.244.0/22 ',
# 091193128052 dts4 UA
'91.193.128.0/24 ',
# 091194042051 Castlewood House, 77-91 New Oxford Street, GB
'91.194.42.0/24 ',
# 091197207195 JSC Svyazinform RU
'91.197.207.0/24 ',
# 091197234102 Route object for 91.197.234.0/23 CZ
'91.197.234.0/23 ',
# 091198127076 Kaluga Data Center Depo RU
'91.198.127.0/24 ',
# 091199149227 ADMAN-NET RU
'91.199.149.0/24 ',
# 091200002238 Orion route-0 UA
'91.200.0.0/22 ',
# 091200052067 PP NVF Sistema i Tehnika UA
'91.200.52.0/24 ',
# 091200080057 TRUSOV UA
'91.200.80.0/24 ',
# 091200081013 TRUSOV UA
'91.200.81.0/24 ',
# 091200082026 TRUSOV UA
'91.200.82.0/24 ',
# 091200083028 New Jersey Network US
'91.200.83.0/24 ',
# 091200235040 Lead Telecom UA
'91.200.235.0/24 ',
# 091201191114 EKSI Ltd. UA
'91.201.188.0/22 ',
# 091201247017 91.201.247 route UA
'91.201.247.0/24 ',
# 091203060028 dts0 UA
'91.203.60.0/24 ',
# 091203107034 TriAr route UA
'91.203.107.0/24 ',
# 091204014018 Ogden Utah Network US
'91.204.14.0/25 ',
# 091204015051 Moscow Network RU
'91.204.15.0/25 ',
# 091205052234 REGSETI-NET route Saratov RU
'91.205.52.0/23 ',
# 091205129044 R-Line Ltd. RU
'91.205.129.0/24 ',
# 091206030012 Freehost.UA UA
'91.206.30.0/23 ',
# 091206062075 Pervaja Baza Ltd. RU
'91.206.62.0/24 ',
# 091206111142 KSTELECOM-ISP-UA UA
'91.206.110.0/23 ',
# 091209077161 HOST-TELECOM Route Object CZ
'91.209.77.0/24 ',
# 091210104143 abuse-mailbox: abuse@hostkey.ru RU
'91.210.104.0/24 ',
# 091210105101 abuse-mailbox: abuse@hostkey.ru RU
'91.210.105.0/24 ',
# 091212217027 RU
'91.212.217.0/24 ',
# 091213126149 RU
'91.213.126.0/24 ',
# 091213182030 Kyiv UA
'91.213.182.0/24 ',
# 091214119026 FastZONE.ru RU
'91.214.119.0/24 ',
# 091215154241 FRIENDHOSTING-BG BG
'91.215.154.0/24 ',
# 091215155229 FRIENDHOSTING-BG BG
'91.215.155.0/24 ',
# 091215157016 3W Infra 91.215.156.0/22 NL
'91.215.156.0/22 ',
# 091216250228 rtr 91.216.250.0 UA
'91.216.250.0/24 ',
# 091217254136 HostPark network UA
'91.217.254.0/24 ',
# 091218096204 SUBNET-11 UA
'91.218.96.0/24 ',
# 091218209236 Fiberway3 PL
'91.218.208.0/22 ',
# 091219028011 UKRSERVERS-NL-ALLOCATION UA
'91.219.28.0/24 ',
# 096009070086 SINET, Dedicated Fiber, Dedicated Speed, Dedicated Support, Dedicated Always. KH
'96.9.70.0/24 ',
# 096009076022 SINET, Dedicated Fiber, Dedicated Speed, Dedicated Support, Dedicated Always. KH
'96.9.76.0/24 ',
# 096009090090 SINET, Dedicated Fiber, Dedicated Speed, Dedicated Support, Dedicated Always. KH
'96.9.90.0/24 ',
# 096009248032 Nexeon Technologies, Inc. NEXEON-IPV4-4 (NET-96-9-192-0-1) US
'96.9.192.0/18 ',
# 096030234137 Transworld Network, Corp. TWN-01 (NET-96-30-224-0-1) US
'96.30.224.0/20 ',
# 096031247253 TPx Communications TELEPACIFIC-LA-BLK-3 (NET-96-31-224-0-1) US
'96.31.224.0/19 ',
# 096063249014 PAVLOV MEDIA INC PAVLOVMEDIA-8 (NET-96-63-192-0-1) US
'96.63.192.0/18 ',
# 098159099058 IDC, Inc US
'98.159.96.0/20 ',
# 098159233018 LogicWeb Inc. LOGICWEB (NET-98-159-224-0-1) US
'98.159.224.0/20 ',
# 101096009136 CHINANET FUJIAN PROVINCE NETWORK CN
'101.96.8.0/22 ',
# 101100138074 Static Pure Bundle MyRepublic NZ NZ
'101.100.136.0/22 ',
# 101100176053 Republic Telecom SG
'101.100.160.0/19 ',
# 101200151023 Aliyun Computing Co., LTD CN
'101.200.0.0/15 ',
# 101203174209 CHINANET FUJIAN PROVINCE NETWORK CN
'101.203.172.0/22 ',
# 101208176069 AIRCEL-Delhi-MobileBroadband-GPRS-Customer IN
'101.208.176.0/24 ',
# 101235118118 DLIVE KR
'101.235.0.0/16 ',
# 102248056187 Telkom SA Limited ZA
'102.248.0.0/16 ',
# 103001094144 ClassicTech Pvt. Ltd. NP
'103.1.94.0/24 ',
# 103001238051 Super Online Data Co., Ltd VN
'103.1.236.0/22 ',
# 103003046012 Universitas Lampung ID
'103.3.46.0/24 ',
# 103003060243 329 E. Jimmie Leeds Road SG
'103.3.60.0/23 ',
# 103003062231 329 E. Jimmie Leeds Road SG
'103.3.62.0/23 ',
# 103003120019 China Unicom Beijing province network CN
'103.3.120.0/22 ',
# 103003165146 ReadySpace Cloud Services - Singapore SG
'103.3.165.0/24 ',
# 103003206173 CJM CONSULTANCY SERVICES PVT LTD IN
'103.3.206.0/24 ',
# 103004179212 NETROPY CO.,Ltd KR
'103.4.176.0/22 ',
# 103005025008 DTAC Broadband IP for GN Path TH
'103.5.25.0/24 ',
# 103006090226 DNS Infonet Pvt Ltd IN
'103.6.90.0/23 ',
# 103007040053 SUPERDATA VN
'103.7.40.0/22 ',
# 103007131074 Power Grid Corporation of India Limited IN
'103.7.131.0/24 ',
# 103009013076 INFRANET SOLUTIONS IN
'103.9.13.0/24 ',
# 103009159199 VNSO TECHNOLOGY COMPANY VN
'103.9.156.0/22 ',
# 103010048222 Level 23, 28 Freshwater Place AU
'103.10.48.0/23 ',
# 103010134021 Vainavi Industries Ltd IN
'103.10.134.0/24 ',
# 103011067157 HostUS HOSTUS-IPV4-5 (NET-103-11-64-0-1) US
'103.11.64.0/22 ',
# 103012122005 Dynamic Assigniment of PPPoE Hub-3 PK
'103.12.122.0/24 ',
# 103012161035 Ip block for ADSL internet KH
'103.12.161.0/24 ',
# 103012162004 Ip block for ADSL internet KH
'103.12.162.0/24 ',
# 103012195030 Quest Consultancy Pvt Ltd IN
'103.12.195.0/24 ',
# 103013101150 Servers Australia Pty Ltd AU
'103.13.101.0/24 ',
# 103013240136 HK
'103.13.240.128/28 ',
# 103013241017 HostDime Data Centre Services PVT IN
'103.13.240.0/22 ',
# 103014027174 Cyber Communication Limited BD
'103.14.27.0/24 ',
# 103014062081 Bayan Telecommunications DSL Network PH
'103.14.62.0/24 ',
# 103014130065 Radiant telecommunications BD
'103.14.130.0/24 ',
# 103014197093 GCN BROADBAND PVT LTD IN
'103.14.197.0/24 ',
# 103015050157 MatBao network services VN
'103.15.48.0/22 ',
# 103015187116 BrainStorm Network JP
'103.15.187.112/29 ',
# 103015251075 PT. Jakarta International Container Terminal ID
'103.15.251.0/24 ',
# 103017253081 TechPath Pty Ltd - National AU
'103.17.253.0/24 ',
# 103018045023 Asia Pacific Network Information Centre AU
'103.0.0.0/8 ',
# 104036134095 Co-Mo Comm Inc US
'104.36.128.0/21 ',
# 104037187181 Interserver, Inc US
'104.37.184.0/21 ',
# 104128035050 Golden Belt Telephone US
'104.128.32.0/20 ',
# 104128116004 HT US
'104.128.112.0/20 ',
# 104128194240 FreedomNet US
'104.128.192.0/20 ',
# 104129001031 QuadraNet, Inc US
'104.129.0.0/18 ',
# 104129194069 ZSCALER, INC. ZSCALER-CLOUD (NET-104-129-192-0-1) US
'104.129.192.0/20 ',
# 104143076025 InstaVPS US
'104.143.64.0/20 ',
# 104145072027 Grove Asheville NCASGRO (NET-104-145-72-0-1) US
'104.145.72.0/22 ',
# 104145091200 Grove Denton TXDEGRO (NET-104-145-88-0-1) US
'104.145.88.0/22 ',
# 104145111165 Grove Ft Wayne INFWGRO (NET-104-145-108-0-1) US
'104.145.108.0/22 ',
# 104145113212 Grove Greeley COEVGRO-1 (NET-104-145-112-0-1) US
'104.145.112.0/22 ',
# 104145225178 TEFINCOM S.A. NVPN-PNJ-2 (NET-104-145-225-176-1) US
'104.145.225.176/29 ',
# 104145235038 TEFINCOM S.A. NVPN-NYC-1 (NET-104-145-235-32-1) US
'104.145.235.32/28 ',
# 104145239097 CLDR.eu CLDREU-104-145-239-0 (NET-104-145-239-0-1) US
'104.145.239.0/24 ',
# 104153072202 Sandra Esparon COLOAZ-NET01 (NET-104-153-72-200-1) US
'104.153.72.200/29 ',
# 104153218102 Co-Mo Comm Inc US
'104.153.216.0/21 ',
# 104160000002 CachedNet LLC US
'104.160.0.0/19 ',
# 104160181037 Sharktech SHARK-7 (NET-104-160-160-0-1) US
'104.160.160.0/19 ',
# 104160228004 EARTHMETA MULTIMEDIA STUDIOS US
'104.160.224.0/19 ',
# 104168145059 Hostwinds LLC. US
'104.168.128.0/17 ',
# 104192002210 DataWagon LLC US
'104.192.0.0/22 ',
# 104192169226 Joe's Datacenter, LLC US
'104.192.168.0/22 ',
# 104200154042 Hosting Services, Inc. HOSTINGSERVICES (NET-104-200-154-0-1) US
'104.200.154.0/24 ',
# 104200254014 Braveway NY DC BRAVEWAY-NYDC (NET-104-200-254-0-1) US
'104.200.254.0/24 ',
# 104205018197 TELUS Communications Inc. CA
'104.205.0.0/16 ',
# 104218063001 UnmeteredInternet.com UNMETERED-ZONE (NET-104-218-56-0-1) US
'104.218.56.0/21 ',
# 104219251135 Namecheap, Inc. US
'104.219.248.0/22 ',
# 104222242135 ABCDE Technologies LLC US
'104.222.224.0/19 ',
# 104223225002 Click Now Widget CLICK-NOW-WIDGET (NET-104-223-225-0-1) US
'104.223.225.0/28 ',
# 104232066026 HT US
'104.232.64.0/20 ',
# 104237003045 Hosteros LLC US
'104.237.0.0/20 ',
# 104237068194 Server Acceleration LLC US
'104.237.64.0/20 ',
# 104237201133 Nexeon Technologies, Inc. NEXEON-IPV4-3 (NET-104-237-192-0-1) US
'104.237.192.0/19 ',
# 104237225045 DedFiberCo US
'104.237.224.0/19 ',
# 104238046026 SimpleLink LLC SIMPLELINK (NET-104-238-32-0-1) US
'104.238.32.0/19 ',
# 104244074078 FranTech Solutions US
'104.244.72.0/21 ',
# 104245105018 Agave Networks, LLC US
'104.245.104.0/22 ',
# 104245223009 Co-Mo Comm Inc US
'104.245.216.0/21 ',
# 104247144055 KVCHOSTING.COM LLC US
'104.247.128.0/19 ',
# 104251100141 Allstream Corp. CA
'104.251.96.0/20 ',
# 105067132238 Dedicated to 2G/3G users for Marrakech platform MA
'105.67.0.0/16 ',
# 105071005221 This subnet is dedicated to 2G/3G/4G UGW users, managed by Marrakech Datacenter MA
'105.71.0.0/16 ',
# 105166118157 KE-SFC-GPRS-EDGE-3G-LTE-Mobile WiFi-SERVICE-IP KE
'105.164.0.0/14 ',
# 105190069110 3G and 2G Mobile Costumers MA
'105.190.0.0/15 ',
# 105200127023 Network used for 3G Web Users EG
'105.200.0.0/17 ',
# 105207055233 Network used for 3G Web Users EG
'105.207.0.0/17 ',
# 105207206013 Etisalat 2G/3G EG
'105.207.128.0/17 ',
# 105228183200 Addresses used to provide Broadband access to Telkom Internet customer ZA
'105.228.0.0/16 ',
# 105230059178 Airtel Broadband KE
'105.230.0.0/16 ',
# 105235188145 wap.telecelfaso.bf BF
'105.235.176.0/20 ',
# 105236114058 MTN Business SA Pool Region 7200 ZA
'105.236.114.0/26 ',
# 105244183229 Legal IP Block for Internet APN - Pretoria Silverton Northern Gauteng ZA
'105.244.0.0/16 ',
# 106000039170 SREE SAI SERVICES IN
'106.0.39.0/24 ',
# 106000056030 RI Networks Pvt. Ltd. IN
'106.0.56.0/24 ',
# 106010025217 LX KR
'106.10.0.0/19 ',
# 106014189088 Aliyun Computing Co., LTD CN
'106.14.0.0/15 ',
# 106066154030 GPRS Delhi Mobile Subscriber IP IN
'106.66.144.0/20 ',
# 106066170149 GPRS Delhi Mobile Subscriber IP IN
'106.66.160.0/20 ',
# 106075084007 Shanghai UCloud Information Technology Company Limited CN
'106.75.0.0/16 ',
# 106076003020 GPRS Delhi Mobile Subscriber IP IN
'106.76.3.0/24 ',
# 106076051119 GPRS Delhi Mobile Subscriber IP IN
'106.76.51.0/24 ',
# 106077160172 GPRS Delhi Mobile Subscriber IP IN
'106.77.160.0/24 ',
# 106198175155 Bharti Cellular Limited, C-34, Phase -2 Industrial Area , Ground floor, N/A, PUNJAB IN
'106.198.128.0/17 ',
# 106199018143 BCL EAST,Infinity Building, Tower One, 1st Floor, Sector- V,Salt Lake, Kolkata IN
'106.199.0.1/16 ',
# 106201016132 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'106.201.0.0/19 ',
# 106201089026 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'106.201.64.0/19 ',
# 106202065212 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi IN
'106.202.0.0/16 ',
# 106210132113 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'106.210.128.0/19 ',
# 106210195121 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'106.210.192.0/18 ',
# 106212135159 BCL SOUTH,No. 55, Divyashree Towers,Bannergatta Road,Bangalore,Karnataka IN
'106.212.128.0/18 ',
# 106222068118 BCL NORTH,D - 184, Okhla Industrial Estate,Phase - 1,Delhi, IN
'106.222.0.0/16 ',
# 107150064010 CachedNet LLC US
'107.150.64.0/19 ',
# 107151136098 Private Customer NET-107-151-128-0 (NET-107-151-136-0-1) US
'107.151.136.0/24 ',
# 107151146011 Private Customer NET-107-151-128-0 (NET-107-151-146-0-1) US
'107.151.146.0/24 ',
# 107151164072 Zenlayer Inc ZL-TTT-001 (NET-107-151-128-0-1) US
'107.151.128.0/18 ',
# 107167226022 HugeServer Networks, LLC HUGESERVER-NETWORKS-DC2 (NET-107-167-224-0-1) US
'107.167.224.0/19 ',
# 107178004109 Nextlink Broadband US
'107.178.0.0/20 ',
# 107178062066 Netrouting Inc. NETROUTING (NET-107-178-48-0-1) US
'107.178.48.0/20 ',
# 107181128028 EU
'107.181.128.0/19 ',
# 107182226004 Hosting Services, Inc. US
'107.182.224.0/20 ',
# 108161151098 M5 Computer Security US
'108.161.144.0/20 ',
# 108161217234 JMF Solutions, Inc US
'108.161.208.0/20 ',
# 108175052007 Altus Communications Inc. ACGI-NETBLK-013 (NET-108-175-48-0-1) US
'108.175.48.0/20 ',
# 108187218102 SpeedVM Network Group LLC US
'108.187.0.0/16 ',
# 108188064078 BRIGHT HOUSE NETWORKS, LLC BHN-12 (NET-108-188-0-0-1) US
'108.188.0.0/14 ',
# 109051148233 NOS WI-FI POWERED BY FON PT
'109.51.128.0/18 ',
# 109069067017 PLUTEX GmbH DE
'109.69.64.0/21 ',
# 109074075061 ISP Babilon-T TJ
'109.74.75.0/24 ',
# 109075205076 TRK Metro LLC UA
'109.75.192.0/20 ',
# 109075254139 Net By Net Holding LLC RU
'109.75.240.0/20 ',
# 109086178121 Triolan, Kharkiv UA
'109.86.178.0/24 ',
# 109086188247 Triolan, Kharkiv UA
'109.86.188.0/24 ',
# 109087127175 Triolan, Kharkiv UA
'109.87.127.0/24 ',
# 109089152124 Rue Louvrex, 95 BE
'109.89.144.0/20 ',
# 109094002016 Route for Quartz Telecom RU
'109.94.0.0/20 ',
# 109094095237 RU-MNT-ANNET-NET16 RU
'109.94.94.0/23 ',
# 109095051074 Closed JSC Ukrtelebud UA
'109.95.48.0/21 ',
# 109095081130 UnitTelecom LLC RU
'109.95.80.0/21 ',
# 109100254054 Romtelecom Data Network RO
'109.100.128.0/17 ',
# 109106073088 NordNet Satellite FR
'109.106.72.0/21 ',
# 109110096008 Virtual ISP networks LB
'109.110.96.0/24 ',
# 109110180163 Shabdiz Telecom Network JSC IR
'109.110.180.0/24 ',
# 109120018164 Omskie kabelnye seti Ltd. RU
'109.120.16.0/20 ',
# 109121160077 Customer connectivity link addresses MK
'109.121.160.0/22 ',
# 109121167229 Customer connectivity link addresses MK
'109.121.167.0/24 ',
# 109122122052 Radijus Vektor network RS
'109.122.122.0/23 ',
# 109125197024 "Domtel Telecom" Dariusz Dombek PL
'109.125.196.0/23 ',
# 109126009228 P2P subscriber connections RU
'109.126.9.0/24 ',
# 109126135136 BeST LIFE 3G User BY
'109.126.128.0/21 ',
# 109169152159 Dynamic pools for clients in the Samara branch RU
'109.169.152.0/21 ',
# 109172223218 Caucasus Online LLC GE
'109.172.192.0/18 ',
# 109183026189 T-Mobile Czech Republic a.s. CZ
'109.183.0.0/17 ',
# 109194059180 CJSC "ER-Telecom Holding" Kursk branch RU
'109.194.58.0/23 ',
# 109195066117 CJSC "ER-Telecom Holding" Krasnoyarsk branch RU
'109.195.66.0/24 ',
# 109195085122 CJSC "ER-Telecom Holding" Saint-Petersburg branch RU
'109.195.85.0/24 ',
# 109196066076 Trytek broadband pool RU
'109.196.64.0/20 ',
# 109196196074 Novaya Rossiya CTV networks RU
'109.196.196.0/24 ',
# 109201109043 Informatsionnye Tekhnologii LLC RU
'109.201.104.0/21 ',
# 109203159164 Mobinnet Wimax IR
'109.203.144.0/20 ',
# 109203207156 ENTER, LLC RU
'109.203.204.0/22 ',
# 109205115187 Scopesky IQ
'109.205.115.0/24 ',
# 109206171103 Serverel Data Center NL
'109.206.170.0/23 ',
# 109207046046 TRUFpool2 RS
'109.207.32.0/20 ',
# 109226191185 STADTWERKE SCHWEDT DOCSIS CUSTOMERS 3 DE
'109.226.128.0/18 ',
# 109226255017 Network for PPPoE links RU
'109.226.224.0/19 ',
# 109229170171 LLP Asket KZ
'109.229.168.0/21 ',
# 109230203002 GB
'109.230.203.0/24 ',
# 109230230209 serverdiscounter DE
'109.230.230.0/24 ',
# 109232106140 JSC "Thyphone Communications" Network Service Provider RU
'109.232.106.0/24 ',
# 109234015098 LLC "Gazprom telecom" RU
'109.234.15.0/24 ',
# 109234168066 Thales Gateways North GB
'109.234.168.0/21 ',
# 109237108201 Krek Ltd. RU
'109.237.108.0/22 ',
# 109241134079 VECTRA S.A. PL
'109.241.128.0/18 ',
# 109241193120 VECTRA S.A. PL
'109.241.192.0/18 ',
# 109245030203 Telenor doo Serbia address space for wholesale user KOPERNIKUS RS
'109.245.16.0/20 ',
# 109245068198 Telenor doo Serbia address space for mobile access to the Internet RS
'109.245.64.0/19 ',
# 109246048218 Keycom PLC GB
'109.246.48.0/20 ',
# 109247165243 Altibox Residential Customer Linknets NO
'109.247.0.0/16 ',
# 109248158065 Uplink LLC KZ
'109.248.156.0/22 ',
# 109248203190 Contel OOO RU
'109.248.200.0/22 ',
# 109248222075 Krek Ltd. RU
'109.248.222.0/24 ',
# 110034003180 SUBISU_Corporate_Pool16 NP
'110.34.0.0/22 ',
# 110036176216 National WiMAX/IMS environment PK
'110.36.176.0/23 ',
# 110036225138 National WiMAX/IMS environment PK
'110.36.225.0/24 ',
# 110036227219 National WiMAX/IMS environment PK
'110.36.226.0/23 ',
# 110037190155 National WiMAX/IMS environment PK
'110.37.176.0/20 ',
# 110037216158 National WiMAX/IMS environment PK
'110.37.216.0/24 ',
# 110037222178 National WiMAX/IMS environment PK
'110.37.222.0/23 ',
# 110044125028 Jawalakhel NP
'110.44.125.0/24 ',
# 110050084059 MNC Playmedia ID
'110.50.80.0/21 ',
# 110054192189 Liberty Broadcasting Network Inc. PH
'110.54.192.0/24 ',
# 110054222067 Liberty Broadcasting Network Inc. PH
'110.54.222.0/24 ',
# 110054223128 Liberty Broadcasting Network Inc. PH
'110.54.223.0/24 ',
# 110054228104 Liberty Broadcasting Network Inc. PH
'110.54.228.0/24 ',
# 110054229066 Liberty Broadcasting Network Inc. PH
'110.54.229.0/24 ',
# 110054231194 Liberty Broadcasting Network Inc. PH
'110.54.231.0/24 ',
# 110070054047 Korea Telecom KR
'110.68.0.0/14 ',
# 110076128178 CLIENT-DN-Zone-3 BD
'110.76.128.0/24 ',
# 110076129214 CLIENT-UT-Zone-5 BD
'110.76.129.0/24 ',
# 110093164017 HyosungITX KR
'110.93.160.0/19 ',
# 110136033171 PT TELKOM INDONESIA ID
'110.136.33.0/24 ',
# 110136193231 PT TELKOM INDONESIA ID
'110.136.192.0/22 ',
# 110137213234 PT TELKOM INDONESIA ID
'110.137.208.0/21 ',
# 110137253030 PT TELKOM INDONESIA ID
'110.137.248.0/21 ',
# 110138087097 PT TELKOM INDONESIA ID
'110.138.80.0/21 ',
# 110138202159 PT TELKOM INDONESIA ID
'110.138.200.0/22 ',
# 110139035127 PT TELKOM INDONESIA ID
'110.139.32.0/20 ',
# 110139072099 PT TELKOM INDONESIA ID
'110.139.72.0/21 ',
# 110172052178 WISH NET PRIVATE LIMITED IN
'110.172.52.0/22 ',
# 110224198174 BCL NORTH IN
'110.224.198.0/24 ',
# 110224210195 BCL NORTH IN
'110.224.210.0/24 ',
# 110225195020 BCL SOUTH IN
'110.225.195.0/24 ',
# 110225196008 BCL SOUTH IN
'110.225.196.0/24 ',
# 110225197229 BCL SOUTH IN
'110.225.197.0/24 ',
# 110225199000 BCL SOUTH IN
'110.225.199.0/24 ',
# 110225202089 BCL SOUTH IN
'110.225.202.0/24 ',
# 110225205162 BCL SOUTH IN
'110.225.205.0/24 ',
# 110225208249 BCL SOUTH IN
'110.225.208.0/24 ',
# 110225209250 BCL SOUTH IN
'110.225.209.0/24 ',
# 110225213037 BCL SOUTH IN
'110.225.213.0/24 ',
# 110225219089 BCL SOUTH IN
'110.225.219.0/24 ',
# 110225220058 BCL SOUTH IN
'110.225.220.0/24 ',
# 110227073118 BCL EAST IN
'110.227.73.0/24 ',
# 110227079164 BCL EAST IN
'110.227.79.0/24 ',
# 110227091245 BCL EAST IN
'110.227.91.0/24 ',
# 110227098073 BCL EAST IN
'110.227.98.0/24 ',
# 110227099209 BCL EAST IN
'110.227.99.0/24 ',
# 110227119133 BCL EAST IN
'110.227.119.0/24 ',
# 110227177233 BCL EAST IN
'110.227.177.0/24 ',
# 110227187214 BCL EAST IN
'110.227.187.0/24 ',
# 110227214006 BCL EAST IN
'110.227.214.0/24 ',
# 110227253104 BCL EAST IN
'110.227.253.0/24 ',
# 110232086021 PT. Media Antar Nusa ID
'110.232.86.0/24 ',
# 110232248237 SPACENET INTERNET SERVICES PVT LTD DISTRICT CENTRE JANAKPURI IN
'110.232.248.0/24 ',
# 110235148026 TULIP Telecom ltd. IN
'110.235.0.0/16 ',
# 111065255003 FPT ONLINE JSC VN
'111.65.240.0/20 ',
# 111090170148 INPL'S IP POOL IN
'111.90.170.0/24 ',
# 111090186154 #95, Preah Norodom Blvd, Beoung Raing, Khan Daun Penh, Phnom Penh, Cambodia. KH
'111.90.186.0/24 ',
# 111090190058 #95, Preah Norodom Blvd, Beoung Raing, Khan Daun Penh, Phnom Penh, Cambodia. KH
'111.90.190.0/24 ',
# 111092139205 Internet Service Provider PK
'111.92.139.0/24 ',
# 111093041214 Tata Teleservices ISP IN
'111.93.41.0/24 ',
# 111093050103 Tata Teleservices ISP IN
'111.93.50.0/24 ',
# 111093191254 Tata Teleservices ISP IN
'111.93.191.0/24 ',
# 111095109001 PT. First Media, Tbk. ID
'111.95.109.0/24 ',
# 111118249014 RAJESH PATEL NET SERVICES PVT LTD IN
'111.118.249.0/24 ',
# 111118250236 RAJESH PATEL NET SERVICES PVT LTD IN
'111.118.250.0/24 ',
# 111119190212 Metro Ethernet Network PK
'111.119.190.0/24 ',
# 111119206174 Syscon Infoway Pvt.Ltd. IN
'111.119.206.0/24 ',
# 111119210010 Syscon Infoway Pvt.Ltd. IN
'111.119.210.0/24 ',
# 111125104062 COMCLARK Cable Internet PH
'111.125.104.0/22 ',
# 111125109020 COMCLARK Cable Internet PH
'111.125.108.0/22 ',
# 111125143185 SPACENET INTERNET SERVICES PVT LTD DISTRICT CENTRE JANAKPURI IN
'111.125.143.0/24 ',
# 111221054049 V Telecoms Berhad MY
'111.221.48.0/21 ',
# 112033007009 China Mobile Communications Corporation CN
'112.32.0.0/13 ',
# 112073001020 FoShan RuiJiang Science and Tech Ltd. CN
'112.73.0.0/16 ',
# 112110017080 GPRS Delhi Mobile Subscriber IP IN
'112.110.17.0/24 ',
# 112110031030 GPRS Delhi Mobile Subscriber IP IN
'112.110.31.0/24 ',
# 112110049231 GPRS Delhi Mobile Subscriber IP IN
'112.110.49.0/24 ',
# 112110114114 GPRS Delhi Mobile Subscriber IP IN
'112.110.112.0/20 ',
# 112110128005 GPRS Delhi Mobile Subscriber IP IN
'112.110.128.0/24 ',
# 112133244105 Railwire Varanasi IN
'112.133.244.0/24 ',
# 112133251004 Railwire Jaipur IN
'112.133.251.0/24 ',
# 112134016002 ADSL - DYNAMIC POOL LK
'112.134.16.0/24 ',
# 112134017022 ADSL - DYNAMIC POOL LK
'112.134.17.0/24 ',
# 112134128015 ADSL - DYNAMIC POOL LK
'112.134.128.0/24 ',
# 112196012074 Chandigarh IN
'112.196.12.0/24 ',
# 112196025141 Quadrant Televentures Limited IN
'112.196.25.0/24 ',
# 112196044125 Mohali IN
'112.196.44.0/24 ',
# 112196055117 Chandigarh IN
'112.196.55.0/24 ',
# 112196061041 Quadrant Televentures Limited IN
'112.196.61.0/24 ',
# 112196075092 Chandigarh IN
'112.196.75.0/24 ',
# 112196087101 Chandigarh IN
'112.196.87.0/24 ',
# 112196098081 Chandigarh IN
'112.196.98.0/24 ',
# 112196102099 Chandigarh IN
'112.196.102.0/24 ',
# 112196103053 Chandigarh IN
'112.196.103.0/24 ',
# 112196105087 Chandigarh IN
'112.196.105.0/24 ',
# 112196106062 Chandigarh IN
'112.196.106.0/24 ',
# 112196107126 Chandigarh IN
'112.196.107.0/24 ',
# 112196110114 Chandigarh IN
'112.196.110.0/24 ',
# 112196111063 Chandigarh IN
'112.196.111.0/24 ',
# 112196112157 Chandigarh IN
'112.196.112.0/24 ',
# 112196113035 Chandigarh IN
'112.196.113.0/24 ',
# 112196121206 Chandigarh IN
'112.196.121.0/24 ',
# 112196123097 Chandigarh IN
'112.196.123.0/24 ',
# 112196144006 236 Okhla Industrial Estate IN
'112.196.144.0/24 ',
# 112196147159 236 Okhla Industrial Estate IN
'112.196.147.0/24 ',
# 112196160055 236 Okhla Industrial Estate IN
'112.196.160.0/24 ',
# 112196168018 236 Okhla Industrial Estate IN
'112.196.168.0/24 ',
# 112196169196 236 Okhla Industrial Estate IN
'112.196.169.0/24 ',
# 112196179167 236 Okhla Industrial Estate IN
'112.196.179.0/24 ',
# 112196181177 236 Okhla Industrial Estate IN
'112.196.181.0/24 ',
# 112196184047 236 Okhla Industrial Estate IN
'112.196.184.0/24 ',
# 112196204114 KOREA DATA KR
'112.196.192.0/19 ',
# 112211066126 PLDT_CDOHUBS002_DHCP PH
'112.211.64.0/19 ',
# 113020115190 CMC Telecom Infrastructure Company VN
'113.20.96.0/19 ',
# 113021030087 VTOPIA KR
'113.21.0.0/19 ',
# 113021122017 xDSL NC
'113.21.121.0/21 ',
# 113029230074 NewMedia Express Korea KR
'113.29.230.0/24 ',
# 113059214005 LankaBell (pvt) Limited, LK
'113.59.214.0/24 ',
# 113161034138 VietNam Post and Telecom Corporation VN
'113.161.32.0/19 ',
# 113167201160 ADSL VN
'113.167.192.0/19 ',
# 113178055004 ADSL VNPT HaNoi VN
'113.178.48.0/20 ',
# 113179028078 ADSL Service VN
'113.179.16.0/20 ',
# 113180138174 VietNam Post and Telecom Corporation VN
'113.180.0.0/16 ',
# 113183049095 VietNam Post and Telecom Corporation VN
'113.183.0.0/16 ',
# 113197054154 National Telecom Corporation PK
'113.197.54.0/24 ',
# 114029225025 MEGHBELA SKYWAVE CABLENET PRIVATE LIMITED IN
'114.29.225.0/24 ',
# 114031039201 HyosungITX KR
'114.31.32.0/19 ',
# 114055244155 Aliyun Computing Co., LTD CN
'114.55.0.0/16 ',
# 114129017250 PT. Hipernet Indodata ID
'114.129.16.0/21 ',
# 115041078094 CJ-HELLOVISION KR
'115.40.0.0/15 ',
# 115042068164 Metro Ethernet Network PK
'115.42.68.0/24 ',
# 115178235204 PT. Wireless Indonesia ID
'115.178.224.0/19 ',
# 115182255234 Longzang biological technology co.,LTD CN
'115.182.224.0/19 ',
# 115186010244 South LDI P2P Fiber Customer (karachi) PK
'115.186.10.0/24 ',
# 115186129058 Nayatel (Pvt) Ltd PK
'115.186.129.0/24 ',
# 115186147145 Nayatel (Pvt) Ltd PK
'115.186.147.0/24 ',
# 115186177156 Nayatel (Pvt) Ltd PK
'115.186.177.0/24 ',
# 115187136115 MNF Data customers AU
'115.187.128.0/18 ',
# 115192123134 CHINANET-ZJ Hangzhou node network CN
'115.192.0.0/17 ',
# 115216003007 CHINANET-ZJ Hangzhou node network CN
'115.216.0.0/17 ',
# 116058200093 Tigers' Den, House#4(SW), Bir Uttam Mir Shawkat Sharak, Gulshan-1, Dhaka-1212, Bangladesh BD
'116.58.200.0/24 ',
# 116058202015 Tigers' Den, House#4(SW), Bir Uttam Mir Shawkat Sharak, Gulshan-1, Dhaka-1212, Bangladesh BD
'116.58.202.0/24 ',
# 116058203078 Tigers' Den, House#4(SW), Bir Uttam Mir Shawkat Sharak, Gulshan-1, Dhaka-1212, Bangladesh BD
'116.58.203.0/24 ',
# 116062214039 Beijing Kuancom Network Technology Co.,Ltd. CN
'116.62.128.0/17 ',
# 116066197148 Pokhara ISP IPs NP
'116.66.197.0/24 ',
# 116066250220 LEEKIE ENTERPRISES INCORPORATED PH
'116.66.248.0/22 ',
# 116073209221 Hathway IP Over Cable Internet Access Service IN
'116.73.209.0/24 ',
# 116074016025 Hathway IP Over Cable Internet Access Service IN
'116.74.16.0/24 ',
# 116074017029 Hathway IP Over Cable Internet Access Service IN
'116.74.17.0/24 ',
# 116074018009 Hathway IP Over Cable Internet Access Service IN
'116.74.18.0/24 ',
# 116074019022 Hathway IP Over Cable Internet Access Service IN
'116.74.19.0/24 ',
# 116074020009 Hathway IP Over Cable Internet Access Service IN
'116.74.20.0/24 ',
# 116074021086 Hathway IP Over Cable Internet Access Service IN
'116.74.21.0/24 ',
# 116074022006 Hathway IP Over Cable Internet Access Service IN
'116.74.22.0/24 ',
# 116074023009 Hathway IP Over Cable Internet Access Service IN
'116.74.23.0/24 ',
# 116074043220 Hathway IP Over Cable Internet Access Service IN
'116.74.43.0/24 ',
# 116074045050 Hathway IP Over Cable Internet Access Service IN
'116.74.45.0/24 ',
# 116074060107 Hathway IP Over Cable Internet Access Service IN
'116.74.60.0/24 ',
# 116075209062 Hathway IP Over Cable Internet Access Service IN
'116.75.209.0/24 ',
# 116075231017 Hathway IP Over Cable Internet Access Service IN
'116.75.231.0/24 ',
# 116086031082 StarHub Cable Vision Ltd SG
'116.86.0.0/16 ',
# 116087077125 StarHub Cable Vision Ltd SG
'116.87.72.0/21 ',
# 116090165075 PT Graha Sarana Data ID
'116.90.165.0/24 ',
# 116206030039 PT Hutchison 3 Indonesia ID
'116.206.30.0/23 ',
# 116206196077 PT Biznet Data Center ID
'116.206.196.0/22 ',
# 116212137146 MEKONGNET INTERNET SERVICE PROVIDER KH
'116.212.137.0/24 ',
# 116212138210 MEKONGNET INTERNET SERVICE PROVIDER KH
'116.212.138.0/24 ',
# 116212141050 MEKONGNET INTERNET SERVICE PROVIDER KH
'116.212.141.0/24 ',
# 116242227201 Beijing Teletron Telecom Engineering Co., Ltd. CN
'116.242.0.0/16 ',
# 116251038134 Australian Private Networks Pty Ltd AU
'116.251.0.0/18 ',
# 117096226224 BHARTI TELENET LTD. NEW DELHI IN
'117.96.226.0/24 ',
# 117096229198 BHARTI TELENET LTD. NEW DELHI IN
'117.96.229.0/24 ',
# 117096230033 BHARTI TELENET LTD. NEW DELHI IN
'117.96.230.0/24 ',
# 117096232220 BHARTI TELENET LTD. NEW DELHI IN
'117.96.232.0/24 ',
# 117096236165 BHARTI TELENET LTD. NEW DELHI IN
'117.96.236.0/24 ',
# 117096240000 BHARTI TELENET LTD. NEW DELHI IN
'117.96.240.0/24 ',
# 117096248245 BHARTI TELENET LTD. NEW DELHI IN
'117.96.248.0/24 ',
# 117096249052 BHARTI TELENET LTD. NEW DELHI IN
'117.96.249.0/24 ',
# 117096250209 BHARTI TELENET LTD. NEW DELHI IN
'117.96.250.0/24 ',
# 117096251049 BHARTI TELENET LTD. NEW DELHI IN
'117.96.251.0/24 ',
# 117096253074 BHARTI TELENET LTD. NEW DELHI IN
'117.96.253.0/24 ',
# 117096254137 BHARTI TELENET LTD. NEW DELHI IN
'117.96.254.0/24 ',
# 117099177224 BHARTI AIRTEL LTD. IN
'117.99.177.0/24 ',
# 117099178183 BHARTI AIRTEL LTD. IN
'117.99.178.0/24 ',
# 117099180201 BHARTI AIRTEL LTD. IN
'117.99.180.0/24 ',
# 117099181250 BHARTI AIRTEL LTD. IN
'117.99.181.0/24 ',
# 117099182087 BHARTI AIRTEL LTD. IN
'117.99.182.0/24 ',
# 117099187017 BHARTI AIRTEL LTD. IN
'117.99.187.0/24 ',
# 117099188092 BHARTI AIRTEL LTD. IN
'117.99.188.0/24 ',
# 117099191072 BHARTI AIRTEL LTD. IN
'117.99.191.0/24 ',
# 117102002212 North CM Broadband NOC Call Center, Data Center, Co-location Services PK
'117.102.2.0/24 ',
# 117102016022 North LDI P2P Fiber Customer (lahore) PK
'117.102.16.0/24 ',
# 117102195145 INTERLINK Co.,LTD JP
'117.102.192.0/19 ',
# 117111003040 DACOM-PUBNETPLUS KR
'117.110.0.0/15 ',
# 117120007093 ReadySpace Cloud Services Singapore SG
'117.120.7.0/24 ',
# 117193039188 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.193.32.0/20 ',
# 117193083136 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.193.80.0/20 ',
# 117194222104 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.194.208.0/20 ',
# 117197040027 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.197.32.0/20 ',
# 117198044153 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.198.32.0/20 ',
# 117199179174 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.199.176.0/20 ',
# 117199195026 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.199.192.0/20 ',
# 117201177147 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.201.176.0/20 ',
# 117202119037 Broadband Multiplay Project, O/o DGM BB, NOC BSNL IN
'117.202.112.0/20 ',
# 117203022026 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.203.16.0/20 ',
# 117204111183 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.204.96.0/20 ',
# 117206198049 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.206.192.0/20 ',
# 117213057033 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.213.48.0/20 ',
# 117213116056 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.213.112.0/20 ',
# 117215209135 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.215.208.0/20 ',
# 117218243248 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.218.240.0/20 ',
# 117223032223 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.223.32.0/20 ',
# 117223140067 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.223.128.0/20 ',
# 117224177218 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.224.176.0/20 ',
# 117224205101 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.224.192.0/20 ',
# 117224209046 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.224.208.0/20 ',
# 117224246122 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.224.240.0/20 ',
# 117225003243 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.225.0.0/20 ',
# 117225017045 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.225.16.0/20 ',
# 117225110069 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.225.96.0/20 ',
# 117225129088 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.225.128.0/20 ',
# 117225198121 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.225.192.0/20 ',
# 117225212046 BSNL GSM North Zone, O/o Sr GM (CMTS), NC, Chandigarh IN
'117.225.208.0/20 ',
# 117232077209 NIB (National Internet Backbone) IN
'117.232.64.0/20 ',
# 117232103206 NIB (National Internet Backbone) IN
'117.232.96.0/20 ',
# 117239127160 Nehu Tura Campus IN
'117.239.112.0/20 ',
# 117240079020 NIMAWAT PUBLIC SCHOOL IN
'117.240.64.0/20 ',
# 117240220212 NIB (National Internet Backbone) IN
'117.240.208.0/20 ',
# 117241050210 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.241.48.0/20 ',
# 117241103100 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.241.96.0/20 ',
# 117241164140 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.241.160.0/20 ',
# 117242031053 Broadband Multiplay Project, O/o DGM BB, NOC BSNL IN
'117.242.16.0/20 ',
# 117249170248 BSNL GSM South Zone, O/o DE (VAS) Ivth floor, Haddows Road Telecom Bldg., Haddows Road, Chennai-600006 IN
'117.249.160.0/20 ',
# 117254079221 Broadband Multiplay Project, O/o DGM BB, NOC BSNL Bangalore IN
'117.254.64.0/20 ',
# 118067188023 Asia Pacific Network Information Centre AU
'116.0.0.0/6 ',
# 120029112025 Comclark Cable Internet PH
'120.29.112.0/22 ',
# 120050006042 Telnet Communication Limited BD
'120.50.6.0/24 ',
# 120050041222 M1 NET LTD SG
'120.50.32.0/20 ',
# 120052021132 CHINA UNICOM CLOUD DATA COMPANY LIMITED CN
'120.52.0.0/16 ',
# 120076114050 Aliyun Computing Co., LTD CN
'120.76.0.0/14 ',
# 120089075092 236 Okhla Industrial Estate IN
'120.89.75.0/24 ',
# 120133006006 DYXNET of Shenzhen Communication Co., Ltd. CN
'120.133.0.0/21 ',
# 120138008238 VK Marketing Services IN
'120.138.8.0/22 ',
# 120188004142 GGSN 3G ID
'120.188.4.0/24 ',
# 120188007010 GGSN 3G ID
'120.188.7.0/24 ',
# 120188037022 GGSN 3G ID
'120.188.37.0/24 ',
# 120188072092 GGSN 3G ID
'120.188.72.0/24 ',
# 120188076157 GGSN 3G ID
'120.188.76.0/24 ',
# 120188077052 GGSN 3G ID
'120.188.77.0/24 ',
# 120188079172 GGSN 3G ID
'120.188.79.0/24 ',
# 120188081164 GGSN 3G ID
'120.188.81.0/24 ',
# 120188082184 GGSN 3G ID
'120.188.82.0/24 ',
# 120188083005 GGSN 3G ID
'120.188.83.0/24 ',
# 120188085169 GGSN 3G ID
'120.188.85.0/24 ',
# 120188092190 GGSN 3G ID
'120.188.92.0/24 ',
# 120188094239 GGSN 3G ID
'120.188.94.0/24 ',
# 121052061034 Excelcomindo Pratama, PT. ID
'121.52.61.0/24 ',
# 121052209076 Beijing Topnew Info&Tech co., LTD. CN
'121.52.208.0/21 ',
# 121058237125 Comclark-MC PH
'121.58.236.0/22 ',
# 121066172140 LG DACOM Corporation KR
'121.64.0.0/14 ',
# 121126003062 HAIonNet KR
'121.126.0.0/16 ',
# 121248112020 US
'121.248.112.0/21 ',
# 122011138180 Starhub Internet Pte Ltd SG
'122.11.128.0/17 ',
# 122155137069 CAT Telecom public company Ltd TH
'122.155.128.0/20 ',
# 122160018006 ABTS DELHI, IN
'122.160.18.0/24 ',
# 122160032109 ABTS DELHI, IN
'122.160.32.0/24 ',
# 122160078120 ABTS DELHI, IN
'122.160.78.0/24 ',
# 122160201246 ABTS DELHI, IN
'122.160.201.0/24 ',
# 122161106096 ABTS DELHI, IN
'122.161.106.0/24 ',
# 122161200087 ABTS DELHI, IN
'122.161.200.0/24 ',
# 122161202144 ABTS DELHI, IN
'122.161.202.0/24 ',
# 122161203255 ABTS DELHI, IN
'122.161.203.0/24 ',
# 122161204059 ABTS DELHI, IN
'122.161.204.0/24 ',
# 122161205227 ABTS DELHI, IN
'122.161.205.0/24 ',
# 122161206136 ABTS DELHI, IN
'122.161.206.0/24 ',
# 122162004173 ABTS DELHI, IN
'122.162.4.0/24 ',
# 122162005016 ABTS DELHI, IN
'122.162.5.0/24 ',
# 122162009028 ABTS DELHI, IN
'122.162.9.0/24 ',
# 122162010053 ABTS DELHI, IN
'122.162.10.0/24 ',
# 122162012246 ABTS DELHI, IN
'122.162.12.0/24 ',
# 122162013128 ABTS DELHI, IN
'122.162.13.0/24 ',
# 122162014167 ABTS DELHI, IN
'122.162.14.0/24 ',
# 122162016150 ABTS DELHI, IN
'122.162.16.0/24 ',
# 122162017172 ABTS DELHI, IN
'122.162.17.0/24 ',
# 122162022013 ABTS DELHI, IN
'122.162.22.0/24 ',
# 122162023123 ABTS DELHI, IN
'122.162.23.0/24 ',
# 122162026003 ABTS DELHI, IN
'122.162.26.0/24 ',
# 122162028085 ABTS DELHI, IN
'122.162.28.0/24 ',
# 122162031171 ABTS DELHI, IN
'122.162.31.0/24 ',
# 122162034167 ABTS DELHI, IN
'122.162.34.0/24 ',
# 122162038072 ABTS DELHI, IN
'122.162.38.0/24 ',
# 122162039030 ABTS DELHI, IN
'122.162.39.0/24 ',
# 122162041118 ABTS DELHI, IN
'122.162.41.0/24 ',
# 122162045082 ABTS DELHI, IN
'122.162.45.0/24 ',
# 122162052118 ABTS DELHI, IN
'122.162.52.0/24 ',
# 122162053237 ABTS DELHI, IN
'122.162.53.0/24 ',
# 122162054082 ABTS DELHI, IN
'122.162.54.0/24 ',
# 122162057248 ABTS DELHI, IN
'122.162.57.0/24 ',
# 122162058137 ABTS DELHI, IN
'122.162.58.0/24 ',
# 122162060171 ABTS DELHI, IN
'122.162.60.0/24 ',
# 122162068102 ABTS DELHI, IN
'122.162.68.0/24 ',
# 122162071045 ABTS DELHI, IN
'122.162.71.0/24 ',
# 122162072230 ABTS DELHI, IN
'122.162.72.0/24 ',
# 122162077132 ABTS DELHI, IN
'122.162.77.0/24 ',
# 122162080084 ABTS DELHI, IN
'122.162.80.0/24 ',
# 122162081019 ABTS DELHI, IN
'122.162.81.0/24 ',
# 122162082250 ABTS DELHI, IN
'122.162.82.0/24 ',
# 122162083036 ABTS DELHI, IN
'122.162.83.0/24 ',
# 122162086190 ABTS DELHI, IN
'122.162.86.0/24 ',
# 122162091015 ABTS DELHI, IN
'122.162.91.0/24 ',
# 122162092110 ABTS DELHI, IN
'122.162.92.0/24 ',
# 122162094140 ABTS DELHI, IN
'122.162.94.0/24 ',
# 122162097059 ABTS DELHI, IN
'122.162.97.0/24 ',
# 122162098015 ABTS DELHI, IN
'122.162.98.0/24 ',
# 122162099111 ABTS DELHI, IN
'122.162.99.0/24 ',
# 122162100071 ABTS DELHI, IN
'122.162.100.0/24 ',
# 122162106159 ABTS DELHI, IN
'122.162.106.0/24 ',
# 122162124018 ABTS DELHI, IN
'122.162.124.0/24 ',
# 122162132123 ABTS DELHI, IN
'122.162.132.0/24 ',
# 122162133200 ABTS DELHI, IN
'122.162.133.0/24 ',
# 122162134050 ABTS DELHI, IN
'122.162.134.0/24 ',
# 122162135229 ABTS DELHI, IN
'122.162.135.0/24 ',
# 122162140162 ABTS DELHI, IN
'122.162.140.0/24 ',
# 122162142149 ABTS DELHI, IN
'122.162.142.0/24 ',
# 122162144154 ABTS DELHI, IN
'122.162.144.0/24 ',
# 122162152140 ABTS DELHI, IN
'122.162.152.0/24 ',
# 122162154053 ABTS DELHI, IN
'122.162.154.0/24 ',
# 122162156216 ABTS DELHI, IN
'122.162.156.0/24 ',
# 122162157063 ABTS DELHI, IN
'122.162.157.0/24 ',
# 122162163048 ABTS DELHI, IN
'122.162.163.0/24 ',
# 122162177036 ABTS DELHI, IN
'122.162.177.0/24 ',
# 122162185091 ABTS DELHI, IN
'122.162.185.0/24 ',
# 122162186049 ABTS DELHI, IN
'122.162.186.0/24 ',
# 122162190197 ABTS DELHI, IN
'122.162.190.0/24 ',
# 122162196076 ABTS DELHI, IN
'122.162.196.0/24 ',
# 122162200040 ABTS DELHI, IN
'122.162.200.0/24 ',
# 122162206225 ABTS DELHI, IN
'122.162.206.0/24 ',
# 122162218129 ABTS DELHI, IN
'122.162.218.0/24 ',
# 122162220088 ABTS DELHI, IN
'122.162.220.0/24 ',
# 122162224058 ABTS DELHI, IN
'122.162.224.0/24 ',
# 122162225019 ABTS DELHI, IN
'122.162.225.0/24 ',
# 122162226096 ABTS DELHI, IN
'122.162.226.0/24 ',
# 122162230085 ABTS DELHI, IN
'122.162.230.0/24 ',
# 122162232170 ABTS DELHI, IN
'122.162.232.0/24 ',
# 122162233100 ABTS DELHI, IN
'122.162.233.0/24 ',
# 122162235204 ABTS DELHI, IN
'122.162.235.0/24 ',
# 122162236114 ABTS DELHI, IN
'122.162.236.0/24 ',
# 122162237167 ABTS DELHI, IN
'122.162.237.0/24 ',
# 122162242203 ABTS DELHI, IN
'122.162.242.0/24 ',
# 122162244253 ABTS DELHI, IN
'122.162.244.0/24 ',
# 122162245112 ABTS DELHI, IN
'122.162.245.0/24 ',
# 122163023106 ABTS DELHI, IN
'122.163.23.0/24 ',
# 122163085096 ABTS DELHI, IN
'122.163.85.0/24 ',
# 122163086104 ABTS DELHI, IN
'122.163.86.0/24 ',
# 122163136106 ABTS DELHI, IN
'122.163.136.0/24 ',
# 122163140062 ABTS DELHI, IN
'122.163.140.0/24 ',
# 122163213173 ABTS DELHI, IN
'122.163.213.0/24 ',
# 122164100216 ABTS Tamilnadu, IN
'122.164.100.0/24 ',
# 122164240153 ABTS Tamilnadu, IN
'122.164.240.0/24 ',
# 122164243063 ABTS Tamilnadu, IN
'122.164.243.0/24 ',
# 122164244114 ABTS Tamilnadu, IN
'122.164.244.0/24 ',
# 122165119085 ABTS Tamilnadu, IN
'122.165.119.0/24 ',
# 122165128008 ABTS Tamilnadu, IN
'122.165.128.0/24 ',
# 122165151028 ABTS Tamilnadu, IN
'122.165.151.0/24 ',
# 122166096217 ABTS (Karnataka), IN
'122.166.96.0/24 ',
# 122166244066 ABTS (Karnataka), IN
'122.166.244.0/24 ',
# 122167235236 ABTS (Karnataka), IN
'122.167.235.0/24 ',
# 122168106005 ABTS MP, IN
'122.168.106.0/24 ',
# 122169026178 ABTS-MUMBAI IN
'122.169.26.0/24 ',
# 122169048168 ABTS-MUMBAI IN
'122.169.48.0/24 ',
# 122170105223 ABTS-MUMBAI IN
'122.170.105.0/24 ',
# 122170204066 BHARTI Airtel LTD. IN
'122.170.204.0/24 ',
# 122170206167 BHARTI Airtel LTD. IN
'122.170.206.0/24 ',
# 122171161162 ABTS (Karnataka), IN
'122.171.161.0/24 ',
# 122171177137 ABTS (Karnataka), IN
'122.171.177.0/24 ',
# 122174145011 ABTS Tamilnadu, IN
'122.174.145.0/24 ',
# 122174162120 ABTS Tamilnadu, IN
'122.174.162.0/24 ',
# 122174170150 ABTS Tamilnadu, IN
'122.174.170.0/24 ',
# 122174187239 ABTS Tamilnadu, IN
'122.174.187.0/24 ',
# 122174234051 ABTS Tamilnadu, IN
'122.174.234.0/24 ',
# 122174250192 ABTS Tamilnadu, IN
'122.174.250.0/24 ',
# 122175023237 ABTS (Hyderabad), IN
'122.175.23.0/24 ',
# 122175046014 ABTS (Hyderabad), IN
'122.175.46.0/24 ',
# 122175201139 ABTS MP, IN
'122.175.201.0/24 ',
# 122175216117 ABTS MP, IN
'122.175.216.0/24 ',
# 122175222052 ABTS MP, IN
'122.175.222.0/24 ',
# 122175224185 ABTS MP, IN
'122.175.224.0/24 ',
# 122175230115 ABTS MP, IN
'122.175.230.0/24 ',
# 122176005082 BHARTI TELENET LTD. NEW DELHI IN
'122.176.5.0/24 ',
# 122176006246 BHARTI TELENET LTD. NEW DELHI IN
'122.176.6.0/24 ',
# 122176010095 BHARTI TELENET LTD. NEW DELHI IN
'122.176.10.0/24 ',
# 122176037184 BHARTI TELENET LTD. NEW DELHI IN
'122.176.37.0/24 ',
# 122176050017 BHARTI TELENET LTD. NEW DELHI IN
'122.176.50.0/24 ',
# 122176071162 BHARTI TELENET LTD. NEW DELHI IN
'122.176.71.0/24 ',
# 122176104005 BHARTI TELENET LTD. NEW DELHI IN
'122.176.104.0/24 ',
# 122176140014 BHARTI TELENET LTD. NEW DELHI IN
'122.176.140.0/24 ',
# 122176148037 BHARTI TELENET LTD. NEW DELHI IN
'122.176.148.0/24 ',
# 122176149007 BHARTI TELENET LTD. NEW DELHI IN
'122.176.149.0/24 ',
# 122176150093 BHARTI TELENET LTD. NEW DELHI IN
'122.176.150.0/24 ',
# 122176155202 BHARTI TELENET LTD. NEW DELHI IN
'122.176.155.0/24 ',
# 122176156002 BHARTI TELENET LTD. NEW DELHI IN
'122.176.156.0/24 ',
# 122176158009 BHARTI TELENET LTD. NEW DELHI IN
'122.176.158.0/24 ',
# 122176160062 BHARTI TELENET LTD. NEW DELHI IN
'122.176.160.0/24 ',
# 122176197254 BHARTI TELENET LTD. NEW DELHI IN
'122.176.197.0/24 ',
# 122176204162 BHARTI TELENET LTD. NEW DELHI IN
'122.176.204.0/24 ',
# 122176208032 BHARTI TELENET LTD. NEW DELHI IN
'122.176.208.0/24 ',
# 122176215220 BHARTI TELENET LTD. NEW DELHI IN
'122.176.215.0/24 ',
# 122176220003 BHARTI TELENET LTD. NEW DELHI IN
'122.176.220.0/24 ',
# 122176224001 BHARTI TELENET LTD. NEW DELHI IN
'122.176.224.0/24 ',
# 122176225172 BHARTI TELENET LTD. NEW DELHI IN
'122.176.225.0/24 ',
# 122176226135 BHARTI TELENET LTD. NEW DELHI IN
'122.176.226.0/24 ',
# 122176227245 BHARTI TELENET LTD. NEW DELHI IN
'122.176.227.0/24 ',
# 122176232012 BHARTI TELENET LTD. NEW DELHI IN
'122.176.232.0/24 ',
# 122176233122 BHARTI TELENET LTD. NEW DELHI IN
'122.176.233.0/24 ',
# 122176234039 BHARTI TELENET LTD. NEW DELHI IN
'122.176.234.0/24 ',
# 122176235069 BHARTI TELENET LTD. NEW DELHI IN
'122.176.235.0/24 ',
# 122176238146 BHARTI TELENET LTD. NEW DELHI IN
'122.176.238.0/24 ',
# 122176239094 BHARTI TELENET LTD. NEW DELHI IN
'122.176.239.0/24 ',
# 122176241162 BHARTI TELENET LTD. NEW DELHI IN
'122.176.241.0/24 ',
# 122176242238 BHARTI TELENET LTD. NEW DELHI IN
'122.176.242.0/24 ',
# 122176243154 BHARTI TELENET LTD. NEW DELHI IN
'122.176.243.0/24 ',
# 122176244030 BHARTI TELENET LTD. NEW DELHI IN
'122.176.244.0/24 ',
# 122176247181 BHARTI TELENET LTD. NEW DELHI IN
'122.176.247.0/24 ',
# 122176255126 BHARTI TELENET LTD. NEW DELHI IN
'122.176.255.0/24 ',
# 122177019247 BHARTI TELENET LTD. NEW DELHI IN
'122.177.19.0/24 ',
# 122177034090 BHARTI TELENET LTD. NEW DELHI IN
'122.177.34.0/24 ',
# 122177071038 BHARTI TELENET LTD. NEW DELHI IN
'122.177.71.0/24 ',
# 122177084043 BHARTI TELENET LTD. NEW DELHI IN
'122.177.84.0/24 ',
# 122177154033 BHARTI TELENET LTD. NEW DELHI IN
'122.177.154.0/24 ',
# 122177165019 BHARTI TELENET LTD. NEW DELHI IN
'122.177.165.0/24 ',
# 122177176003 BHARTI TELENET LTD. NEW DELHI IN
'122.177.176.0/24 ',
# 122177240024 BHARTI TELENET LTD. NEW DELHI IN
'122.177.240.0/24 ',
# 122178056251 Bharti Telenet Ltd. Tamilnadu IN
'122.178.56.0/24 ',
# 122178148141 Bharti Telenet Ltd. Tamilnadu IN
'122.178.148.0/24 ',
# 122178158085 Bharti Telenet Ltd. Tamilnadu IN
'122.178.158.0/24 ',
# 122178167105 Bharti Telenet Ltd. Tamilnadu IN
'122.178.167.0/24 ',
# 122178182016 Bharti Telenet Ltd. Tamilnadu IN
'122.178.182.0/24 ',
# 122178184036 Bharti Telenet Ltd. Tamilnadu IN
'122.178.184.0/24 ',
# 122179012254 ABTS (Karnataka), IN
'122.179.12.0/24 ',
# 122179025184 ABTS (Karnataka), IN
'122.179.25.0/24 ',
# 122180017106 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.17.0/24 ',
# 122180020185 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.20.0/24 ',
# 122180033253 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.33.0/24 ',
# 122180035213 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.35.0/24 ',
# 122180038204 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.38.0/24 ',
# 122180160072 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.160.0/24 ',
# 122180161200 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.161.0/24 ',
# 122180162253 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.162.0/24 ',
# 122180166014 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.166.0/24 ',
# 122180167149 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.167.0/24 ',
# 122180171000 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.171.0/24 ',
# 122180172242 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.172.0/24 ',
# 122180178052 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.178.0/24 ',
# 122180179120 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.179.0/24 ',
# 122180184236 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.184.0/24 ',
# 122180185038 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.185.0/24 ',
# 122180186189 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.186.0/24 ',
# 122180190241 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.190.0/24 ',
# 122180191114 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.191.0/24 ',
# 122180192021 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.192.0/24 ',
# 122180196036 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.196.0/24 ',
# 122180197152 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.197.0/24 ',
# 122180199157 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.199.0/24 ',
# 122180200217 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.200.0/24 ',
# 122180201174 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.201.0/24 ',
# 122180203151 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.203.0/24 ',
# 122180205233 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.205.0/24 ',
# 122180206047 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.206.0/24 ',
# 122180207003 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.207.0/24 ',
# 122180208233 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.208.0/24 ',
# 122180210063 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.210.0/24 ',
# 122180211056 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.211.0/24 ',
# 122180212137 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.212.0/24 ',
# 122180214021 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.214.0/24 ',
# 122180218008 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.218.0/24 ',
# 122180219011 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.219.0/24 ',
# 122180220039 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.220.0/24 ',
# 122180223128 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.223.0/24 ',
# 122180224125 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.224.0/24 ',
# 122180225169 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.225.0/24 ',
# 122180226228 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.226.0/24 ',
# 122180228117 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.228.0/24 ',
# 122180229168 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.229.0/24 ',
# 122180232196 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.232.0/24 ',
# 122180235020 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.235.0/24 ',
# 122180236029 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.236.0/24 ',
# 122180238099 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.180.238.0/24 ',
# 122183017150 BHARTI Airtel Ltd. TELEMEDIA SERVICES IN
'122.183.17.0/24 ',
# 122224035068 MoveInternet Network Technology Co.,Ltd. CN
'122.224.35.0/24 ',
# 122226062090 Jinhua Siji Ruili Saloon CN
'122.226.62.88/30 ',
# 122226228234 Nanlong group Co., LTD CN
'122.226.228.232/30 ',
# 122228179178 Zhejiang Meisen Electric Co. Ltd. CN
'122.228.179.176/30 ',
# 122252230130 Malineni Engineering College - Ongole IN
'122.252.230.0/24 ',
# 123017055036 Vietnam Posts and Telecommunications(VNPT) VN
'123.17.48.0/20 ',
# 123017129125 Vietnam Posts and Telecommunications(VNPT) VN
'123.17.128.0/20 ',
# 123018047010 Vietnam Posts and Telecommunications(VNPT) VN
'123.18.32.0/19 ',
# 123019055143 Vietnam Posts and Telecommunications(VNPT) VN
'123.19.48.0/20 ',
# 123019183016 Vietnam Posts and Telecommunications(VNPT) VN
'123.19.176.0/20 ',
# 123019245029 Vietnam Posts and Telecommunications(VNPT) VN
'123.19.240.0/20 ',
# 123021131061 Vietnam Posts and Telecommunications(VNPT) VN
'123.21.128.0/20 ',
# 123022019063 Vietnam Posts and Telecommunications(VNPT) VN
'123.22.0.0/19 ',
# 123022139033 Vietnam Posts and Telecommunications(VNPT) VN
'123.22.128.0/19 ',
# 123025091085 VietNam Post and Telecom Corporation VN
'123.25.64.0/19 ',
# 123025190033 VietNam Post and Telecom Corporation VN
'123.25.160.0/19 ',
# 123026120111 Vietnam Posts and Telecommunications(VNPT) VN
'123.26.96.0/19 ',
# 123027178116 Vietnam Posts and Telecommunications(VNPT) VN
'123.27.160.0/19 ',
# 123027223160 Vietnam Posts and Telecommunications(VNPT) VN
'123.27.192.0/19 ',
# 123028047159 Vietnam Posts and Telecommunications(VNPT) VN
'123.28.32.0/19 ',
# 123031017079 VietNam Data Communication Company (VDC) VN
'123.31.0.0/19 ',
# 123059068137 CloudVsp.Inc CN
'123.59.64.0/19 ',
# 123059100243 CloudVsp.Inc CN
'123.59.96.0/19 ',
# 123059173000 CloudVsp.Inc CN
'123.59.160.0/19 ',
# 123100185004 Korea Cable TV Kwangju Broadcasting KR
'123.100.160.0/19 ',
# 123136176104 Tikona Digital Networks Pvt. LTD IN
'123.136.176.0/24 ',
# 123136201007 Tikona Digital Networks Pvt. LTD IN
'123.136.201.0/24 ',
# 123136205015 Tikona Digital Networks Pvt. LTD IN
'123.136.205.0/24 ',
# 123199040129 CJ-HELLOVISION KR
'123.199.0.0/17 ',
# 123200007094 Link3 Technologies Ltd. BD
'123.200.7.0/24 ',
# 123201015141 YOU Telecom India Pvt Ltd IN
'123.201.15.0/24 ',
# 123201016159 YOU Telecom India Pvt Ltd IN
'123.201.16.0/24 ',
# 123201030206 YOU Telecom India Pvt Ltd IN
'123.201.30.0/24 ',
# 123201057022 YOU Telecom India Pvt Ltd IN
'123.201.57.0/24 ',
# 123201081002 YOU Telecom India Pvt Ltd IN
'123.201.81.0/24 ',
# 123201090095 YOU Telecom India Pvt Ltd IN
'123.201.90.0/24 ',
# 123201091076 YOU Telecom India Pvt Ltd IN
'123.201.91.0/24 ',
# 123201095102 YOU Telecom India Pvt Ltd IN
'123.201.95.0/24 ',
# 123201105101 YOU Telecom India Pvt Ltd IN
'123.201.105.0/24 ',
# 123201163013 YOU Telecom India Pvt Ltd IN
'123.201.163.0/24 ',
# 123201165007 YOU Telecom India Pvt Ltd IN
'123.201.165.0/24 ',
# 123206093108 Tencent cloud computing (Beijing) Co., Ltd. CN
'123.206.0.0/15 ',
# 123252170234 Bill Pooling Test IN
'123.252.128.0/18 ',
# 123255131049 EDION Corporation JP
'123.255.128.0/18 ',
# 124029237012 Broadband Services PK
'124.29.237.0/24 ',
# 124041213120 Lumbini Net NP
'124.41.208.0/20 ',
# 124043074244 ADSL SECTION-DYNAMIC POOL LK
'124.43.74.0/24 ',
# 124043125198 SLTNET-DYNAMIC POOL LK
'124.43.125.0/24 ',
# 124109000003 BangkokVPS.com TH
'124.109.0.0/25 ',
# 124109036098 Nayatel (Pvt) Ltd PK
'124.109.36.0/24 ',
# 124109038103 Nayatel (Pvt) Ltd PK
'124.109.38.0/24 ',
# 124111118113 SK Broadband Co Ltd KR
'124.111.0.0/16 ',
# 124251090071 21ViaNet(China),Inc. CN
'124.251.0.0/16 ',
# 124255022040 FreeBit Co.,Ltd. JP
'124.255.0.0/17 ',
# 125001224236 FUJITSU LIMITED JP
'125.0.0.0/15 ',
# 125005020210 Rm 506 Executive Bldg Center Sen Gil Puyat cor Makati Ave Makati City PH
'125.5.0.0/16 ',
# 125007050082 Macquarie Telecom AU
'125.7.50.80/29 ',
# 125007132033 HAIonNet KR
'125.7.128.0/18 ',
# 125017016050 Meghbela Cable & Broadban IN
'125.17.16.0/24 ',
# 125018020078 Aakash Internet Services IN
'125.18.20.0/24 ',
# 125023220132 ABTS DELHI, IN
'125.23.220.0/24 ',
# 125031019025 CTM MO
'125.31.19.0/24 ',
# 125062193018 VOL BROADBAND IN
'125.62.192.0/22 ',
# 125062213030 VOL BROADBAND IN
'125.62.212.0/22 ',
# 125099024043 Hathway IP Over Cable Internet Access Service IN
'125.99.24.0/24 ',
# 125099167191 Hathway IP Over Cable Internet Access Service IN
'125.99.167.0/24 ',
# 125099191218 Hathway IP Over Cable Internet Access Service IN
'125.99.191.0/24 ',
# 125099194194 Hathway IP Over Cable Internet Access Service IN
'125.99.194.0/24 ',
# 125099252110 Hathway IP Over Cable Internet Access Service IN
'125.99.252.0/24 ',
# 125160208249 PT TELKOM INDONESIA ID
'125.160.208.0/24 ',
# 125160211229 PT TELKOM INDONESIA ID
'125.160.211.0/24 ',
# 125161170013 PT Telekomunikasi Indonesia ID
'125.161.170.0/24 ',
# 125163065063 PT TELKOM INDONESIA ID
'125.163.64.0/21 ',
# 125163124132 PT TELKOM INDONESIA ID
'125.163.124.0/22 ',
# 125163151000 PT TELKOM INDONESIA ID
'125.163.144.0/20 ',
# 125163189084 PT TELKOM INDONESIA ID
'125.163.184.0/21 ',
# 125165164101 PT TELKOM INDONESIA ID
'125.165.164.0/23 ',
# 125167069032 PT TELKOM INDONESIA ID
'125.167.68.0/22 ',
# 125167099046 PT TELKOM INDONESIA ID
'125.167.96.0/21 ',
# 125167156052 PT TELKOM INDONESIA ID
'125.167.156.0/23 ',
# 125167246075 PT TELKOM INDONESIA ID
'125.167.240.0/21 ',
# 125167254148 PT TELKOM INDONESIA ID
'125.167.254.0/23 ',
# 125209067074 Multinet Pakistan Pvt. Ltd. PK
'125.209.67.0/24 ',
# 125209071014 Multinet Pakistan Pvt. Ltd. PK
'125.209.71.0/24 ',
# 125209072234 Multinet Pakistan Pvt. Ltd. PK
'125.209.72.0/24 ',
# 125209084078 Multinet Pakistan Pvt. Ltd. PK
'125.209.84.0/24 ',
# 128001068233 Zenlayer Inc US
'128.1.0.0/16 ',
# 128008215107 University of Maryland US
'128.8.0.0/16 ',
# 128028251233 NTTPC Communications,Inc JP
'128.28.0.0/16 ',
# 128048010171 University of California - Office of the President US
'128.48.0.0/16 ',
# 128053019104 NTTPC Communications,Inc JP
'128.53.0.0/16 ',
# 128068125242 Dynamic IP Pool for Broadband Customers RU
'128.68.125.0/24 ',
# 128069077129 Dynamic IP Pool for Broadband Customers RU
'128.69.77.0/24 ',
# 128074123119 Dynamic IP Pool for Broadband Customers RU
'128.74.123.0/24 ',
# 128074168218 Dynamic IP Pool for Broadband Customers RU
'128.74.168.0/24 ',
# 128077113135 Get AS Customers NO
'128.76.0.0/15 ',
# 128086179063 JANET Aggregate GB
'128.86.0.0/16 ',
# 128106177188 SingNet Pte Ltd SG
'128.106.0.0/16 ',
# 128153146125 Clarkson University US
'128.153.0.0/16 ',
# 128228092078 City University of New York US
'128.228.0.0/16 ',
# 129000040228 PUBLIC SUBNET DOUALA CM
'129.0.0.0/18 ',
# 129056010103 NATCOM Development and Investment Limited NG
'129.56.0.0/16 ',
# 129088011041 MI2S (Moyens Informatiques et Multimedia, Information scientifique) FR
'129.88.0.0/16 ',
# 129093004065 University of Nebraska-Lincoln US
'129.93.0.0/16 ',
# 129125154211 Groningen NL
'129.125.0.0/16 ',
# 129157176218 Oracle Corporation US
'129.144.0.0/12 ',
# 129176197083 Mayo Foundation for Medical Education and Research US
'129.176.0.0/16 ',
# 129232222070 C0262509814 ZA
'129.232.128.0/17 ',
# 130105074058 SKYBROADBAND PH
'130.105.72.0/22 ',
# 130105107032 SKYBROADBAND PH
'130.105.104.0/22 ',
# 130105139235 SKYBROADBAND PH
'130.105.136.0/22 ',
# 130105168203 SKYBROADBAND PH
'130.105.168.0/22 ',
# 130105197123 SKYBROADBAND PH
'130.105.196.0/22 ',
# 130105213127 SKYBROADBAND PH
'130.105.212.0/22 ',
# 130105230168 SKYBROADBAND PH
'130.105.228.0/22 ',
# 130148004186 GEC Sensors Limited GB
'130.148.0.0/16 ',
# 130185150116 Rapidswitch Ltd GB
'130.185.144.0/21 ',
# 131000120163 ICENET TELECOMUNICACOES LTDA - ME US
'131.0.120.0/22 ',
# 131100149250 LINK WAP TELECOMUNICA��ES E INFORMATICA LTDA US
'131.100.148.0/22 ',
# 131108044074 FRANSUELEN DE LIMA DUARTE 15486485743 US
'131.108.44.72/29 ',
# 131108229003 CONECTAW TELECOM LTDA - ME US
'131.108.228.0/22 ',
# 131153027210 SECURED SERVERS LLC US
'131.153.0.0/16 ',
# 131161093242 Jdnet Telecomunica��es US
'131.161.92.0/22 ',
# 131211211012 Heidelberglaan 8 NL
'131.211.0.0/16 ',
# 131221045070 YAX ADMINISTRACAO E TECNOLOGIA LTDA - ME US
'131.221.44.0/22 ',
# 131221072238 SISALWEB INTERNET US
'131.221.72.0/22 ',
# 131252200208 Portland State University US
'131.252.0.0/16 ',
# 131255082189 Logic Pro Tecnologia US
'131.255.80.0/22 ',
# 131255153171 TEKYNIK SOLU�OES TECNOL�GICAS LTDA-EPP US
'131.255.152.0/22 ',
# 132147064112 Viewqwest-Fibernet SG
'132.147.64.0/24 ',
# 132147080136 Viewqwest-Fibernet SG
'132.147.80.0/24 ',
# 132147089129 Viewqwest-Fibernet SG
'132.147.89.0/24 ',
# 132147099084 Viewqwest Pte Ltd SG
'132.147.99.0/24 ',
# 132148019129 GoDaddy.com, LLC US
'132.148.0.0/16 ',
# 134000112170 Reg.Ru Hosting RU
'134.0.112.0/24 ',
# 134000113131 Reg.Ru Hosting RU
'134.0.113.0/24 ',
# 134000119157 Reg.Ru Hosting RU
'134.0.119.0/24 ',
# 134059001031 Universite de Nice FR
'134.59.0.0/16 ',
# 134090149231 Blix Solutions's Danish Network NO
'134.90.149.0/24 ',
# 134153073199 Memorial University of Newfoundland CA
'134.153.0.0/16 ',
# 134236016234 10 Fl. 72. CAT TELECOM TOWER Bangrak Bangkok Thailand TH
'134.236.0.0/17 ',
# 134255243014 GB
'134.255.243.0/24 ',
# 136144129163 TransIP BV NL
'136.144.128.0/17 ',
# 136169129163 JSC "Ufanet" RU
'136.169.128.0/23 ',
# 136169136088 JSC "Ufanet" RU
'136.169.136.0/23 ',
# 136169138083 JSC "Ufanet" RU
'136.169.138.0/23 ',
# 137059002172 Srinagar Net tech P ltd IN
'137.59.2.0/24 ',
# 137059023234 Network and Security Solutions Limited HK
'137.59.20.0/22 ',
# 137059044047 Trung tam Cong nghe thong tin Mobifone VN
'137.59.44.0/22 ',
# 137059061253 JOY INTERNET SERVICES IN
'137.59.61.0/24 ',
# 137059066110 SPRINTNET IN
'137.59.64.0/22 ',
# 137059100028 RM 1401 CAMBRIDGE HSE 26-28 HK
'137.59.100.0/22 ',
# 137059224059 Cyber Internet Services Pakistan PK
'137.59.224.0/24 ',
# 137059252130 TSS Australia AU
'137.59.252.0/22 ',
# 137074005060 Dedicated Servers PL
'137.74.0.0/16 ',
# 137097008253 Reliance Jio Infocomm Limited IN
'137.97.0.0/16 ',
# 137131083024 The Scripps Research Institute US
'137.131.0.0/16 ',
# 138000088003 DOBLECLICK SOFTWARE E INGENERIA CO
'138.0.88.0/24 ',
# 138000102002 Center Prestadora Servi�os S/C Ltda US
'138.0.100.0/22 ',
# 138000141079 F.B. BABETO ME US
'138.0.140.0/22 ',
# 138000152164 Gigared S.A. AR
'138.0.152.0/22 ',
# 138016101161 Brown University US
'138.16.0.0/16 ',
# 138036215026 INTELIX TECNOLOGIA LTDA-ME US
'138.36.212.0/22 ',
# 138059176067 AGRO INFER HN
'138.59.176.64/27 ',
# 138059217004 UBA CONECT TELECOM LTDA - ME US
'138.59.216.0/22 ',
# 138068000195 DigitalOcean, LLC US
'138.68.0.0/16 ',
# 138075050085 M1 LIMITED SG
'138.75.0.0/17 ',
# 138094058133 Junta Administrativa del Servicio El�ctrico Municipal de Cartago(JASEC) CR
'138.94.56.0/22 ',
# 138094084022 VANILCE PAES DE ARRUDA COTTA ME US
'138.94.84.0/22 ',
# 138097236002 COOPERATIVA DE ELECTRICIDAD DE R�O PRIMERO LTDA. AR
'138.97.236.0/22 ',
# 138099208136 ESECURITY & ENTERTAINMENT LTD BZ
'138.99.208.0/22 ',
# 138117121010 Ol� Telecomunica��es Ltda US
'138.117.120.0/22 ',
# 138117143226 Broadcom Group, S.A GT
'138.117.140.0/22 ',
# 138118196001 a-guedes propaganda US
'138.118.196.0/22 ',
# 138118233098 neospeed telecomunica��es eireli US
'138.118.232.0/22 ',
# 138128208195 IT7 Networks Inc IT7NET (NET-138-128-192-0-1) US
'138.128.192.0/19 ',
# 138128224019 IP Systems Limited VG
'138.128.228.0/23 ',
# 138186003174 COLEGIO CIDADE DE BAURU LTDA ME US
'138.186.3.172/30 ',
# 138186179116 Gandalf Comunicaciones C.A. VE
'138.186.176.0/22 ',
# 138197001203 DigitalOcean, LLC US
'138.197.0.0/16 ',
# 138201002122 Abdollah Arzanesh DE
'138.201.0.0/16 ',
# 138204146111 J A J INFORMATICA US
'138.204.144.0/22 ',
# 138219035187 Marcio Morguenroth EPP US
'138.219.32.0/22 ',
# 138219177236 URUCUINET TELECOM E INFORMATICA LTDA - ME US
'138.219.176.0/22 ',
# 138219188021 Impacto Informatica LTDA ME US
'138.219.188.0/22 ',
# 138219223166 Goldweb Barretos servi�os de Telecomunica��es Ltda US
'138.219.220.0/22 ',
# 139005006195 B2 COMPUTERS IN
'139.5.4.0/22 ',
# 139005228180 WORLDVIEW TELECOM IN
'139.5.228.0/22 ',
# 139005240117 PIYUSH NETWORKS IN
'139.5.240.0/24 ',
# 139005253029 EXCITEL IN
'139.5.253.0/24 ',
# 139059000121 DigitalOcean, LLC SG
'139.59.0.0/16 ',
# 139129094241 Aliyun Computing Co., LTD CN
'139.129.0.0/16 ',
# 139159214063 Huawei Public Cloud Service (Huawei Software Technologies Ltd.Co) CN
'139.159.128.0/17 ',
# 139162013017 Linode, LLC SG
'139.162.0.1/19 ',
# 139162032117 139.162.0.0/16 US
'139.162.0.0/16 ',
# 139167001224 Reliance Jio Infocomm Limited IN
'139.167.0.0/16 ',
# 139190026150 Telecom Services (DLI/WLL) Provider PK
'139.190.26.0/24 ',
# 139190044188 Telecom Services (DLI/WLL) Provider PK
'139.190.44.0/24 ',
# 139190212009 Telecom Services (DLI/WLL) Provider PK
'139.190.212.0/24 ',
# 139190240129 Telecom Services (DLI/WLL) Provider PK
'139.190.240.0/24 ',
# 139196036156 Aliyun Computing Co., LTD CN
'139.196.0.0/16 ',
# 139219194039 Microsoft (China) Co., Ltd. CN
'139.219.0.0/16 ',
# 139224237033 Aliyun Computing Co., LTD CN
'139.224.0.0/16 ',
# 142000196118 Servers.com, Inc. US
'142.0.192.0/20 ',
# 142177130072 Stentor National Integrated Communications Network ALIANT-TEL-142-177 (NET-142-177-0-0-1) US
'142.177.0.0/16 ',
# 143000064244 DOVA SRL AR
'143.0.64.0/22 ',
# 143000217254 Mega Net Turbo US
'143.0.216.0/22 ',
# 143159081096 INFONET Services Corporation US
'143.159.0.0/16 ',
# 143196199101 Direction Generale de l'Aviation Civile FR
'143.196.0.0/16 ',
# 143202038207 DATACENTER DESIREHOST US
'143.202.36.0/22 ',
# 143202155002 GRUPO PANAGLOBAL 15 S.A PA
'143.202.152.0/22 ',
# 143255104013 ARTEC TELECOMUNICACIONES LIMITADA CL
'143.255.104.0/22 ',
# 144036230221 Accenture Solutions Pvt. Ltd IN
'144.36.224.0/21 ',
# 144048109066 One Stop Media & Entertainment (ICC Communication) BD
'144.48.109.0/24 ',
# 144172071105 FranTech Solutions PONYNET-12 (NET-144-172-64-0-1) US
'144.172.64.0/18 ',
# 144194001001 International Air Transport Association CA
'144.194.0.0/16 ',
# 144217007143 OVH Hosting, Inc. OVH-VPS-144-217-4 (NET-144-217-4-0-1) US
'144.217.4.0/22 ',
# 144217014192 OVH Hosting, Inc. OVH-VPS-144-217-12 (NET-144-217-12-0-1) US
'144.217.12.0/22 ',
# 144217016065 OVH Hosting, Inc. HO-2 (NET-144-217-0-0-1) US
'144.217.0.0/16 ',
# 145058172175 Publieke Omroep Nederland NL
'145.58.0.0/16 ',
# 145128003085 KPN IAAS NL
'145.128.0.0/18 ',
# 145131169226 RoutIT NL
'145.131.169.0/24 ',
# 145133232213 KPN B.V. NL
'145.133.0.0/16 ',
# 145239032178 Failover Ips IT
'145.239.0.0/16 ',
# 146088202076 Xinwei (Cambodia) Telecom Co. Ltd, #Building No.B3 and No.C31, Street 169,Sangkat Veal Vong , Khan 7 Makara, Phnom Penh. KH
'146.88.200.1/21 ',
# 146120231110 ZServers DE
'146.120.0.0/16 ',
# 146158073132 Alfa Telecom master route FR
'146.158.0.0/17 ',
# 146185202036 Moscow Network RU
'146.185.202.0/24 ',
# 146185203005 Piter Network RU
'146.185.203.0/24 ',
# 146185204150 Belgrade Net RS
'146.185.204.0/24 ',
# 146185205014 Portugal Network PT
'146.185.205.0/24 ',
# 146185206025 Morocco Network MA
'146.185.206.0/24 ',
# 146185223012 RU
'146.185.223.0/24 ',
# 146185254150 net for Paylicense RU
'146.185.254.0/23 ',
# 146196035110 Jain Net Services IN
'146.196.35.0/24 ',
# 146196036089 Satendra Networks IN
'146.196.36.0/24 ',
# 146196038060 Satendra Networks IN
'146.196.38.0/24 ',
# 146196039012 Satendra Networks IN
'146.196.39.0/24 ',
# 146198047136 INFONET Services Corporation US
'146.198.0.0/16 ',
# 147030019121 JSC Kazakhtelecom, Kazakhstan Online Backbone KZ
'147.30.16.0/22 ',
# 147030020109 JSC Kazakhtelecom, Kazakhstan Online Backbone KZ
'147.30.20.0/22 ',
# 147030199254 JSC Kazakhtelecom, Kazakhstan Online Backbone KZ
'147.30.196.0/22 ',
# 147075123138 Azteca Comunicaciones Colombia SAS CO
'147.75.112.0/20 ',
# 147075210227 ORG-OML5-RIPE IL
'147.75.208.0/20 ',
# 147110059244 ESKOM Holdings SOC Ltd ZA
'147.110.0.0/16 ',
# 147135153092 Failover Ips FR
'147.135.128.0/17 ',
# 148103035247 TRICOM DO
'148.103.0.0/16 ',
# 148206189228 Universidad Autonoma Metropolitana MX
'148.206.0.0/16 ',
# 148244102098 Alestra, S. de R.L. de C.V. MX
'148.244.0.0/16 ',
# 148252114127 NTE Bredband FTTH BE102 NO
'148.252.96.0/19 ',
# 149036064022 PSINet, Inc. US
'149.36.0.0/16 ',
# 149056001210 Private Customer OVH-CUST-3101341 (NET-149-56-1-192-1) US
'149.56.1.192/27 ',
# 149056008228 OVH Hosting, Inc. CA
'149.56.0.0/16 ',
# 149154137205 Net By Net Holding LLC RU
'149.154.128.0/20 ',
# 149154152177 EDIS GmbH AT
'149.154.152.0/24 ',
# 149154154151 EDIS GmbH AT
'149.154.154.0/24 ',
# 149202024109 OVH Static IP DE
'149.202.0.0/16 ',
# 149255154004 AG Telecom LTD., Broadband network AZ
'149.255.144.0/20 ',
# 149255170162 BUZ-BUSINESS-POOL-29 GB
'149.255.170.0/24 ',
# 152101020004 CITIC Telecom International CPC Limited JP
'152.101.20.0/24 ',
# 152163100066 AOL Inc. US
'152.163.0.0/16 ',
# 152174122148 TELEFONICA MOVIL DE CHILE S.A. CL
'152.172.0.0/14 ',
# 152231029072 COLOMBIATEL TELECOMUNICACIONES CO
'152.231.29.0/24 ',
# 152231074252 ENTEL CHILE S.A. CL
'152.231.64.0/18 ',
# 155004021100 Dynamic private network SE
'155.4.0.0/16 ',
# 155093144134 CISPIP144-20 ZA
'155.93.144.0/20 ',
# 155097234246 University of Utah US
'155.97.0.0/16 ',
# 155133038215 FUFO STUDIO AGATA GRABOWSKA NET5 PL
'155.133.38.0/24 ',
# 155133040065 Biznes-Host.pl sp. z o.o. PL
'155.133.40.0/22 ',
# 155133064026 FUFO STUDIO AGATA GRABOWSKA NET6 PL
'155.133.64.0/24 ',
# 155133082064 FUFO STUDIO AGATA GRABOWSKA NET7 PL
'155.133.82.0/24 ',
# 155133086036 Daniel Urzedowski Speedmax PL
'155.133.86.0/24 ',
# 155254163206 Altus Communications Inc. ACGI-NETBLK-009 (NET-155-254-160-0-1) US
'155.254.160.0/19 ',
# 156067121102 AWB NET Agnieszka Bardzo PL
'156.67.121.0/24 ',
# 156194064243 TE Data EG
'156.194.0.0/16 ',
# 156196037186 TE Data EG
'156.196.0.0/15 ',
# 156198086156 TE Data EG
'156.198.0.0/15 ',
# 156201135026 TE Data EG
'156.201.0.0/16 ',
# 156202216109 TE Data EG
'156.202.0.0/15 ',
# 156204144219 TE Data EG
'156.204.0.0/15 ',
# 156208049024 TE Data EG
'156.208.0.0/15 ',
# 156210170124 TE Data EG
'156.210.0.0/15 ',
# 156212089076 TE Data EG
'156.212.0.0/15 ',
# 156214163104 TE Data EG
'156.214.0.0/15 ',
# 156216043048 TE Data EG
'156.216.0.0/15 ',
# 156218051174 TE DATA EG
'156.192.0.0/11 ',
# 157048004051 Reliance Jio infocomm ltd IN
'157.48.0.0/16 ',
# 157049000069 Reliance Jio infocomm ltd IN
'157.49.0.0/16 ',
# 157050008017 Reliance Jio infocomm ltd IN
'157.50.0.0/16 ',
# 157051032060 Reliance Jio infocomm ltd IN
'157.51.0.0/16 ',
# 157052154068 Aim2Game AIM2GAME (NET-157-52-154-0-1) US
'157.52.154.0/24 ',
# 157052182066 hlnode HLNODE (NET-157-52-182-0-1) US
'157.52.182.0/24 ',
# 157097121002 Express-Equinix-New-York US
'157.97.121.0/24 ',
# 157119081060 ANI DATA SERVICES IN
'157.119.80.0/22 ',
# 157119089173 Gigatel Networks Private Limited IN
'157.119.88.0/22 ',
# 157119186066 Summit Communications Limited-DR IP Block BD
'157.119.186.0/24 ',
# 157119200017 Fast 4 Technologies IN
'157.119.200.0/24 ',
# 157119218122 Gigantic Infotel Private Limited IN
'157.119.218.0/24 ',
# 158068066254 Bureau of Land Management US
'158.68.0.0/16 ',
# 158069006229 OVH Hosting, Inc. HO-2 (NET-158-69-0-0-1) US
'158.69.0.0/16 ',
# 158106223246 Pilot Fiber, Inc. US
'158.106.192.0/19 ',
# 158125240039 Loughborough University GB
'158.125.0.0/16 ',
# 158140129013 MyRepublic Ltd http://www.myrepublic.com.sg Vertex Building 33 Ubi Avenue Tower B, #04-13 SG
'158.140.128.0/19 ',
# 158140174043 PT. Eka Mas Republik ID
'158.140.160.0/19 ',
# 158174009116 Dynamic private network SE
'158.174.0.0/16 ',
# 158181016088 MEGALINE-NET-LTE KG
'158.181.16.0/20 ',
# 158255002026 hostkey network RU
'158.255.2.0/24 ',
# 158255005206 Breakleft Networks RU
'158.255.5.0/24 ',
# 158255006174 hostkey network RU
'158.255.6.0/24 ',
# 158255211009 EDIS GmbH AT
'158.255.211.0/24 ',
# 159008069148 SurfEasy Inc FR
'159.8.69.144/28 ',
# 159020100056 Andishe Sabz Khazar ADSL IP Block IR
'159.20.96.0/20 ',
# 159122133203 Hosting Services Inc. (dba Midphase) US
'159.122.133.192/26 ',
# 159122170170 US
'159.122.0.0/16 ',
# 159153129039 Electronic Arts, Inc. US
'159.153.0.0/16 ',
# 159192008100 10 Fl. 72. CAT TELECOM TOWER Bangrak Bangkok Thailand TH
'159.192.0.0/17 ',
# 159192222200 10 Fl. 72. CAT TELECOM TOWER Bangrak Bangkok Thailand TH
'159.192.128.0/17 ',
# 159203001072 DigitalOcean, LLC US
'159.203.0.0/16 ',
# 159224020038 Triolan, Kharkiv UA
'159.224.20.0/24 ',
# 159224090213 Triolan, Kharkiv UA
'159.224.90.0/24 ',
# 159224215108 Triolan, Odessa UA
'159.224.215.0/24 ',
# 159224217018 Triolan, Kyiv UA
'159.224.217.0/24 ',
# 159224255154 Triolan, Dnipro UA
'159.224.255.0/24 ',
# 159255022067 Teledyne Systems Limited RU
'159.255.16.0/21 ',
# 160000224191 UNIVERCELL SA BJ
'160.0.224.0/19 ',
# 160089143041 CASA_4G_MarocTelecom MA
'160.89.128.0/17 ',
# 160120000064 ORANGE COTE D'IVOIRE CI
'160.120.0.0/24 ',
# 160120001240 ORANGE COTE D'IVOIRE CI
'160.120.1.0/24 ',
# 160120015111 ORANGE COTE D'IVOIRE CI
'160.120.15.0/24 ',
# 160120033073 ORANGE COTE D'IVOIRE CI
'160.120.33.0/24 ',
# 160161221113 MarocTelecom4G MA
'160.160.0.0/13 ',
# 160202036176 Nitin Networks IN
'160.202.36.0/24 ',
# 160202037232 Nitin Networks IN
'160.202.37.0/24 ',
# 160202038251 Nitin Networks IN
'160.202.38.0/24 ',
# 160202042074 INDONESIA_COMNETS ID
'160.202.40.0/22 ',
# 160202144018 Antaranga Properties Ltd BD
'160.202.144.0/24 ',
# 160202160253 Gasan Digital 2-ro 98, Geumchon-gu, Seoul, Korea KR
'160.202.160.0/22 ',
# 160202196106 RAM SINGH GARG AND COMPANY IN
'160.202.196.0/22 ',
# 161047089110 Rackspace Hosting RACKS-8 (NET-161-47-0-0-1) US
'161.47.0.0/16 ',
# 161132100069 Red Cientifica Peruana PE
'161.132.0.0/16 ',
# 161148148141 SERVICO FEDERAL DE PROCESSAMENTO DE DADOS - SERPRO US
'161.148.0.0/16 ',
# 161202072139 Hosting Services Inc. (dba Midphase) US
'161.202.72.128/26 ',
# 162212168005 CachedNet LLC US
'162.212.168.0/21 ',
# 162217027154 DurableDNS Inc US
'162.217.24.0/21 ',
# 162217249121 SYN LTD US
'162.217.249.0/24 ',
# 162220220135 Anexia US
'162.220.220.0/22 ',
# 162223010006 NetActuate, Inc US
'162.223.8.0/21 ',
# 162244134006 Microglobe LLC ALNITECH-003 (NET-162-244-132-0-1) US
'162.244.132.0/22 ',
# 162244137152 CASCADELINK INC US
'162.244.136.0/22 ',
# 162245081231 ColoUp US
'162.245.80.0/21 ',
# 162246151040 Allo Communications LLC ALLO-SPRING-2014 (NET-162-246-148-0-1) US
'162.246.148.0/22 ',
# 162251006010 Virtual VM US
'162.251.0.0/21 ',
# 162251009142 Electronic Corporate Pages, Inc ECPI-WB (NET-162-251-8-0-1) US
'162.251.8.0/21 ',
# 162252126013 Broward County Commission FPLFI-BROWARDCOU-101782-2 (NET-162-252-126-0-1) US
'162.252.126.0/24 ',
# 162253233232 Spiral Solutions and Technologies Inc. US
'162.253.232.0/21 ',
# 162254205091 HEG US Inc. ILIKA-NET (NET-162-254-200-0-1) US
'162.254.200.0/21 ',
# 164039205186 Gamma Telecom Limited GB
'164.39.128.0/17 ',
# 164058022230 Frontier Schools ONENET-0000001038-0000003142 (NET-164-58-22-128-1) US
'164.58.22.128/25 ',
# 164125068025 Pusan National University KR
'164.125.0.0/16 ',
# 164132042120 OVH FR
'164.132.0.0/16 ',
# 164138089091 REDCOM Broadband Block 5 for flat-rate access RU
'164.138.88.0/21 ',
# 164151005012 Government of South Africa ZA
'164.151.0.0/16 ',
# 164160142060 JENY is an ISP in Benin republic. BJ
'164.160.142.0/24 ',
# 164215082170 TTK-Baikal/BRAS in Irkutsk RU
'164.215.80.0/20 ',
# 166048105199 YESUP YESUP-COM (NET-166-48-0-0-1) US
'166.48.0.0/16 ',
# 167088092025 Hotwire Communications HOTWI (NET-167-88-80-0-1) US
'167.88.80.0/20 ',
# 167205001101 Institut Teknologi Bandung ID
'167.205.0.0/17 ',
# 167249068066 starnet ltda US
'167.249.68.64/26 ',
# 167249108132 FULL TECH TELECOM US
'167.249.108.0/22 ',
# 167249150169 CONECT TELECOMUNICACOES COMUNICACOES E MULTIMIDIA US
'167.249.148.0/22 ',
# 167249211162 AS SISTEMAS LTDA US
'167.249.208.0/22 ',
# 167250075009 s�o miguel telecomunica��es e informatica ltda - m US
'167.250.72.0/22 ',
# 167250099013 PROVEDOR CARIRI CONECT US
'167.250.96.0/22 ',
# 167250243022 SINAL TELECOM LTDA US
'167.250.240.0/22 ',
# 168000217146 Intermicro Ltda US
'168.0.216.0/22 ',
# 168001006032 Hosting Services Inc. (dba Midphase) US
'168.1.6.0/26 ',
# 168001023122 Hosting Services Inc. (dba Midphase) US
'168.1.23.64/26 ',
# 168001027042 Privax Limited US
'168.1.27.40/29 ',
# 168001030132 Privax Limited US
'168.1.30.128/29 ',
# 168001036073 Privax Limited US
'168.1.36.72/29 ',
# 168001038089 Privax Limited US
'168.1.38.88/29 ',
# 168001046041 Privax Limited US
'168.1.46.40/29 ',
# 168001065182 VPNSecure Pty Ltd AU
'168.1.65.180/30 ',
# 168001096132 US
'168.1.0.0/16 ',
# 168128029075 Dimension Data ZA
'168.128.0.0/16 ',
# 168181107014 MULTI GLOBAL COM. E SERV. DE INFORM�TICA LTDA US
'168.181.104.0/22 ',
# 168194108012 Gandalf Comunicaciones C.A. VE
'168.194.108.0/22 ',
# 168196112250 SAN GABRIEL VIDEO CABLE COLOR S.A. AR
'168.196.112.0/22 ',
# 168197186020 WGS servi�o inf. e com. produtos ltda - me US
'168.197.184.0/22 ',
# 168232206197 R L GUIMARAES TELECOMUNICACAO - ME US
'168.232.204.0/22 ',
# 168235251025 Jinguo Chang NET-168-235-251-25 (NET-168-235-251-25-1) US
'168.235.251.0/24 ',
# 169000158036 AFRIHOST-DYNAMIC ZA
'169.0.0.0/15 ',
# 169045136213 NL
'169.45.0.0/11 ',
# 169050062069 Hosting Services Inc. (dba Midphase) DE
'169.50.62.64/26 ',
# 169054065197 AVAST SOFTWARE S.R.O. CZ
'169.54.65.196/30 ',
# 169054092132 US
'169.53.0.0/12 ',
# 169149041088 Reliance Jio infocomm ltd IN
'169.149.0.0/18 ',
# 169149128086 Reliance Jio infocomm ltd IN
'169.149.128.0/18 ',
# 169149212068 Reliance Jio infocomm ltd IN
'169.149.192.0/18 ',
# 169159065007 Smile Telecoms Nigeria- Lagos Core via London NG
'169.159.64.0/19 ',
# 169239000182 Simply Computers Tanzania Ltd TZ
'169.239.0.0/22 ',
# 169239046110 Perlcom CC T/A iSPOT ZA
'169.239.46.0/23 ',
# 169239048197 EXCELSIMO NETWORKS LIMITED NG
'169.239.48.0/22 ',
# 169239182127 VPS Hostafrica ZA
'169.239.182.0/24 ',
# 169239209001 Interworks Client Assignments ZA
'169.239.209.0/24 ',
# 169255077027 CLOUD CONNECT NETWORKS CC. ZA
'169.255.76.0/22 ',
# 170000048014 WebNet . US
'170.0.48.0/22 ',
# 170072053227 Vivint Wireless, Inc. US
'170.72.0.0/16 ',
# 170078109174 Cooperativa de Provisi�n de Servicios Publicos de Tortuguitas AR
'170.78.108.0/22 ',
# 170079016019 COOPERATIVA DE PROVISION DE SERVICIO ELECTRICO Y OTROS SERV DE PIGUE AR
'170.79.16.0/22 ',
# 170081042052 Ferenz Networks US
'170.81.40.0/22 ',
# 170083028002 ELETROINFO TELECOMUNICACOES LTDA - ME US
'170.83.28.0/22 ',
# 170084060004 Silva & Silva telecom US
'170.84.60.0/22 ',
# 170084093077 BOMFIM E SOUSA LTDA US
'170.84.92.0/22 ',
# 170084135057 Moviles Y CPE NI
'170.84.135.0/26 ',
# 170115248025 City of Philadelphia US
'170.115.0.0/16 ',
# 170239047242 JOSE OLIVEIRA DE LIMA DDSAT NET TELECOM E INF - ME US
'170.239.44.0/22 ',
# 170239084066 ZAM LTDA. CL
'170.239.84.0/22 ',
# 170239144002 JARBAS PASCHOAL BRAZIL JUNIOR INFORMATICA US
'170.239.144.0/22 ',
# 170239240050 PROVEDOR RJ NET RS LTDA - EPP US
'170.239.240.0/22 ',
# 170244174199 george rodrigues nobre - ME US
'170.244.172.0/22 ',
# 170245059018 ASOCIACION DE SERVICIO DE INTERNET S. DE RL. HN
'170.245.56.0/22 ',
# 170250140052 Hotwire Communications HOTWI (NET-170-250-0-0-1) US
'170.250.0.0/16 ',
# 170253168118 BCI Mississippi Broadband,LLC US
'170.253.128.0/17 ',
# 170254032102 GIGANET SERVICOS DE INTERNET LTDA ME US
'170.254.32.0/22 ',
# 171025167112 LINER RU
'171.25.164.0/22 ',
# 171048050221 Bharti Airtel Limited IN
'171.48.32.0/19 ',
# 171048067255 C-34 Mohali IN
'171.48.64.0/18 ',
# 171049144143 ABTS MP, descr: 1 Malviya Nagar, IN
'171.49.128.0/19 ',
# 171050193222 BHARTI-TELENET-LTD-MUMBAI IN
'171.50.192.0/18 ',
# 171060134199 BHARTI TELENET LTD.MADHYA PRADESH IN
'171.60.128.0/18 ',
# 171061006043 Bharti Airtel Limited IN
'171.61.0.0/18 ',
# 171061144255 BHARTI AIRTEL LTD.WEST BENGAL IN
'171.61.128.0/18 ',
# 171061225057 BHARTI AIRTEL LTD.WEST BENGAL IN
'171.61.128.0/17 ',
# 172081178013 Lunanode Hosting Inc. CA
'172.81.176.0/21 ',
# 172082148082 QuickPacket, LLC QP-IPV4-12 (NET-172-82-128-0-1) US
'172.82.128.0/18 ',
# 172087025119 EightJoy Network LLC US
'172.87.24.0/21 ',
# 172087255065 Net3 Inc. US
'172.87.240.0/20 ',
# 172093138006 Nexeon Technologies, Inc. NEXEON-IPV4-5 (NET-172-93-128-0-1) US
'172.93.128.0/17 ',
# 172094041061 Secure Internet LLC US
'172.94.0.0/17 ',
# 172098066164 Total Server Solutions L.L.C. TSSL (NET-172-98-64-0-1) US
'172.98.64.0/19 ',
# 172098188046 BraveWay LLC BWAY-19 (NET-172-98-160-0-1) US
'172.98.160.0/19 ',
# 172103066009 IP Systems Limited VG
'172.103.64.0/21 ',
# 172103088043 IP Systems Limited VG
'172.103.96.0/19 ',
# 172103136020 Cipherkey Exchange Corp. CA
'172.103.128.0/17 ',
# 172104041083 Linode US
'172.104.0.0/15 ',
# 172106148011 Psychz Networks US
'172.106.0.0/15 ',
# 172110128015 Univera Network US
'172.110.128.0/18 ',
# 172111129006 Secure Internet LLC INTERNET-SECURITY-15 (NET-172-111-128-0-1) US
'172.111.128.0/17 ',
# 173000031176 Reservation Telephone Coop. US
'173.0.16.0/20 ',
# 173044021234 Border Technology, LLC US
'173.44.0.0/19 ',
# 173082080033 MULTACOM CORPORATION US
'173.82.0.0/16 ',
# 173199249086 Hotwire Fision FISION-BLK-TRUMPTOWERS (NET-173-199-248-0-1) US
'173.199.248.0/21 ',
# 173224124210 HEG US Inc. US
'173.224.112.0/20 ',
# 173236168110 New Dream Network, LLC US
'173.236.128.0/17 ',
# 173246213095 Morris Broadband, LLC US
'173.246.192.0/19 ',
# 174138032088 DigitalOcean, LLC US
'174.138.0.0/17 ',
# 175028002154 No. 15 E2 Preah Norodom Blvd KH
'175.28.2.0/24 ',
# 175100051217 VIETTEL (CAMBODIA) PTE., LTD. KH
'175.100.48.0/20 ',
# 175100146147 YOU Telecom India Pvt Ltd IN
'175.100.146.0/24 ',
# 175100155249 YOU Telecom India Pvt Ltd IN
'175.100.155.0/24 ',
# 175100175023 PUBLIC ALLOCATION IN
'175.100.175.0/24 ',
# 175101026114 Excell Media Pvt Ltd IN
'175.101.26.0/24 ',
# 175110104090 Telecom Services (DLI/WLL) Provider PK
'175.110.104.0/24 ',
# 175110106010 Telecom Services (DLI/WLL) Provider PK
'175.110.106.0/24 ',
# 175111089039 PPTIK - UNIVERSITAS GADJAH MADA. ID
'175.111.89.0/24 ',
# 175111128004 Spiderlink Networks Pvt Ltd IN
'175.111.128.0/22 ',
# 175158047106 PT. Cyberindo Aditama ID
'175.158.32.0/19 ',
# 175158201029 Smart Broadband Incorporated PH
'175.158.192.0/18 ',
# 176011139169 Mobile end user terminals NO
'176.11.0.0/16 ',
# 176012125197 Sauron CZ s.r.o. CZ
'176.12.120.0/21 ',
# 176014088171 Dynamic IP Pool for Broadband Customers RU
'176.14.88.0/24 ',
# 176014090001 Dynamic IP Pool for Broadband Customers RU
'176.14.90.0/24 ',
# 176015189232 Dynamic IP Pool for Broadband Customers RU
'176.15.189.0/24 ',
# 176015229138 Dynamic IP Pool for Broadband Customers RU
'176.15.229.0/24 ',
# 176020171047 TDC BB-ADSL users DK
'176.20.0.0/14 ',
# 176032026095 Linkem IR Wimax Network IT
'176.32.26.0/24 ',
# 176032030067 Linkem IR Wimax Network IT
'176.32.30.0/24 ',
# 176033123118 Tellcom Kartal ADSL Pool TR
'176.33.120.0/21 ',
# 176033142110 Tellcom Avrupa Fiber Dynamic TR
'176.33.142.0/23 ',
# 176040151209 Tellcom Adana Fiber Dynamic TR
'176.40.151.0/24 ',
# 176040155203 Tellcom Ankara Fiber Dynamic TR
'176.40.152.0/21 ',
# 176042183222 Tellcom Ankara Fiber Dynamic TR
'176.42.176.0/21 ',
# 176043066047 Tellcom KARTAL Fttx Fiber TR
'176.43.64.0/21 ',
# 176043143213 Tellcom Fiber Dynamic TR
'176.43.143.0/24 ',
# 176043144025 Tellcom Fiber Dynamic TR
'176.43.144.0/24 ',
# 176053021216 Istanbul Datacenter Ltd. Sti. TR
'176.53.21.0/24 ',
# 176053081067 AS42926-NETWORK TR
'176.53.81.0/24 ',
# 176057001153 Al mouakhah lil khadamat al logesteih wa al itisalat JO
'176.57.0.0/19 ',
# 176058021178 FIRMA KOMPUTEROWA ULTRA.NET LESZEK PEKALA PL
'176.58.21.0/24 ',
# 176058089182 Host Virtual, Inc NL
'176.58.89.0/24 ',
# 176059000105 Tele2 Russia IP Network (SPB) RU
'176.59.0.0/19 ',
# 176059035098 Tele2 Russia IP Network (MSK) RU
'176.59.32.0/19 ',
# 176059105074 Tele2 Russia IP Network (NIN) RU
'176.59.96.0/19 ',
# 176062191028 OOO Istranet RU
'176.62.190.0/23 ',
# 176086162125 Telefonica de Espana SAU ES
'176.86.0.0/16 ',
# 176090013222 Ankara GPRS (2G-3G) TR
'176.90.0.0/17 ',
# 176098080080 76, Lenin str., Uman, Cherkasy Region, Ukraine UA
'176.98.80.0/24 ',
# 176102066158 To determine the registration information for a more EU # COUNTRY IS IN FACT WORLD WIDE
'176.0.0.0/8 ',
# 177010126053 Info House Inform�tica e Papeis Ltda US
'177.10.124.0/22 ',
# 177021222201 Hifive Provedor de Internet Ltda US
'177.21.208.0/20 ',
# 177023184051 Infobarra Solucoes em Informatica Ltda US
'177.23.184.0/21 ',
# 177024215178 TELEF�NICA BRASIL S.A US
'177.24.0.0/16 ',
# 177037071054 Gustavo Zanatta e Cia Ltda US
'177.37.64.0/20 ',
# 177047027186 EQUINIX BRASIL SP US
'177.47.0.0/19 ',
# 177055158018 N4 Telecomunicacoes LTDA - ME US
'177.55.152.0/21 ',
# 177070064100 Sulcom Inform�tica Ltda US
'177.70.64.0/20 ',
# 177072003009 MARCIA M DA S BARROS - ME US
'177.72.3.0/27 ',
# 177075087039 Sulcom Inform�tica Ltda US
'177.75.80.0/20 ',
# 177087095071 CELINO RIBEIRO SERVICOS DE TELECOMUNICACOES LTDA - US
'177.87.92.0/22 ',
# 177087228037 BMBB Ltda ME US
'177.87.224.0/21 ',
# 177089161223 CABO SERVICOS DE TELECOMUNICACOES LTDA US
'177.89.0.0/16 ',
# 177101195203 Sul Americana Tecnologia e Inform�tica Ltda. US
'177.101.192.0/19 ',
# 177124184052 R. Jose da Silva e Cia Ltda - Onda�gil US
'177.124.184.0/22 ',
# 177125061073 Networks Solu��es em Inform�tica US
'177.125.60.0/22 ',
# 177126240012 AGS Antenas LTDA ME US
'177.126.240.0/26 ',
# 177130000032 Frinet Provedor de Internet Ltda US
'177.130.0.0/23 ',
# 177130029036 HELP INTERNET US
'177.130.16.0/20 ',
# 177153008062 Locaweb Servi�os de Internet S/A US
'177.153.0.0/16 ',
# 177154139196 EQUINIX BRASIL SP US
'177.154.128.0/19 ',
# 177190209010 SUPRAMAX TELECOMUNICAC�ES E SERVI�OS EIRELI - ME US
'177.190.208.0/22 ',
# 177220130058 PREFEITURA MUNICIPAL DE JAGUAPITA US
'177.220.130.56/30 ',
# 177220156059 PREFEITURA MUNICIPAL DE JAGUAPITA US
'177.220.156.56/29 ',
# 177232214111 Mexico Red de Telecomunicaciones, S. de R.L. de C.V. MX
'177.232.0.0/15 ',
# 177235008013 CLARO S.A. US
'177.235.0.0/16 ',
# 178017171040 trabia network MD
'178.17.160.0/20 ',
# 178018151013 AOS-2013-08 CZ
'178.18.151.0/24 ',
# 178018201102 Avast Antivirus Servers CZ
'178.18.201.0/24 ',
# 178019210162 GSG Asset GmbH & Co Verwaltungs KG DE
'178.19.208.0/20 ',
# 178021231177 Elektrizitaets- und Wasserwerk der Stadt Buchs SG CH
'178.21.224.0/21 ',
# 178022148122 DECIMA-OFFICE FR
'178.22.144.0/21 ',
# 178022170065 Transtelecom Kazakhstan KZ
'178.22.170.0/24 ',
# 178034182194 Miranda-media Ltd RU
'178.34.176.0/20 ',
# 178045005022 Dynamic IP Pools for customers in the RU
'178.45.0.0/20 ',
# 178045145196 Dynamic IP Pools for customers in the RU
'178.45.144.0/21 ',
# 178057065082 Rostov-Don-Network RU
'178.57.65.0/24 ',
# 178057066166 Michigan Network US
'178.57.66.0/24 ',
# 178057068111 Trusov Ilya Igorevych RU
'178.57.68.0/24 ',
# 178074029237 Pronea Oslo Bynett NO
'178.74.0.0/19 ',
# 178076212244 ZAO "Electro-Com" Rostov RU
'178.76.212.0/24 ',
# 178077036180 Logosoft OnLine Broadband Internet Access Pool 2 BA
'178.77.32.0/19 ',
# 178078056126 L2TP-CUSTOMERS-COLTEL-4 RU
'178.78.48.0/20 ',
# 178080152046 Ettihad Etisalat SA
'178.80.128.0/19 ',
# 178089009205 JSC Kazakhtelecom, Astana Affiliate KZ
'178.89.8.0/21 ',
# 178090142067 JSC Kazakhtelecom, Almaty Affiliate KZ
'178.90.142.0/24 ',
# 178091113224 JSC Kazakhtelecom, Karaganda Affiliate KZ
'178.91.112.0/22 ',
# 178132078134 Evoluso.com SE
'178.132.72.0/21 ',
# 178135249235 3rd allocation intenum32 LB
'178.135.248.0/21 ',
# 178136229252 DHCP Kherson UA
'178.136.229.0/24 ',
# 178150186232 Triolan, Kharkiv UA
'178.150.186.0/24 ',
# 178150243202 Triolan, Kharkiv UA
'178.150.243.0/24 ',
# 178150248089 Triolan, Kyiv UA
'178.150.248.0/24 ',
# 178151021209 Triolan, Kharkiv UA
'178.151.21.0/24 ',
# 178151162167 Triolan, Kharkiv UA
'178.151.162.0/24 ',
# 178151224153 Triolan, Kharkiv UA
'178.151.224.0/24 ',
# 178151226205 Triolan, Kharkiv UA
'178.151.226.0/24 ',
# 178152226218 APC-BRAS-POOL-4 QA
'178.152.224.0/20 ',
# 178153226240 WBC-BRAS-POOL-6 QA
'178.153.224.0/20 ',
# 178155004067 MTS PJSC RU
'178.155.4.0/24 ',
# 178155005002 MTS PJSC RU
'178.155.5.0/24 ',
# 178155006042 MTS PJSC RU
'178.155.6.0/24 ',
# 178155020032 MTS PJSC RU
'178.155.16.0/20 ',
# 178158066012 NET.LG.UA#66 UA
'178.158.66.0/24 ',
# 178158068126 NET.LG.UA#68 UA
'178.158.68.0/24 ',
# 178158069021 NET.LG.UA#69 UA
'178.158.69.0/24 ',
# 178158100054 NET.LG.UA#100 UA
'178.158.100.0/24 ',
# 178158102111 NET.LG.UA#102 UA
'178.158.102.0/24 ',
# 178158103066 NET.LG.UA#103 UA
'178.158.103.0/24 ',
# 178158105068 NET.LG.UA#105 UA
'178.158.105.0/24 ',
# 178158112008 NET.LG.UA#112 UA
'178.158.112.0/24 ',
# 178158113239 NET.LG.UA#113 UA
'178.158.113.0/24 ',
# 178158114155 NET.LG.UA#114 UA
'178.158.114.0/24 ',
# 178158115128 NET.LG.UA#115 UA
'178.158.115.0/24 ',
# 178158116222 NET.LG.UA#116 UA
'178.158.116.0/24 ',
# 178158117053 NET.LG.UA#117 UA
'178.158.117.0/24 ',
# 178159037004 UA
'178.159.37.0/24 ',
# 178159039074 ZOMRO-NL NL
'178.159.39.0/24 ',
# 178159097046 TRUSOV RU
'178.159.97.0/24 ',
# 178159216106 LUGANET Ltd. UA
'178.159.216.0/24 ',
# 178170189023 Servachok Ltd RU
'178.170.189.0/24 ',
# 178172207069 FE "ALTERNATIVNAYA ZIFROVAYA SET" BY
'178.172.207.0/24 ',
# 178204037186 Kazan Broad-band access pools RU
'178.204.36.0/22 ',
# 178204048166 Kazan Broad-band access pools RU
'178.204.48.0/21 ',
# 178204150174 Kazan Broad-band access pools RU
'178.204.144.0/21 ',
# 178204159064 Kazan Broad-band access pools RU
'178.204.152.0/21 ',
# 178205136073 Kazan Broad-band access pools RU
'178.205.136.0/21 ',
# 178206095139 Kazan Broad-band access pools RU
'178.206.88.0/21 ',
# 178206230013 Kazan Broad-band access pools RU
'178.206.224.0/21 ',
# 178207028164 PJSC Tattelecom RU
'178.207.28.0/22 ',
# 178207037022 Tatarstan Broad-band access pools RU
'178.207.32.0/21 ',
# 178207040122 Tatarstan Broad-band access pools RU
'178.207.40.0/21 ',
# 178207160154 route object for TATTELECOM RU
'178.207.160.0/21 ',
# 178207173118 route object for TATTELECOM RU
'178.207.168.0/21 ',
# 178207202225 Kazan Broad-band access pools RU
'178.207.200.0/22 ',
# 178208102220 BITel Broadband Customers DE
'178.208.96.0/21 ',
# 178208106215 BITel Broadband Customers DE
'178.208.104.0/21 ',
# 178208168189 GB Dynamic IPs GB
'178.208.168.0/22 ',
# 178208174131 GB Dynamic IPs GB
'178.208.172.0/22 ',
# 178210052170 KVANT-TELECOM RU
'178.210.52.0/24 ',
# 178211039098 Istanbul Datacenter Ltd. Sti. TR
'178.211.39.0/24 ',
# 178211045212 Istanbul Datacenter Ltd. Sti. TR
'178.211.45.0/24 ',
# 178211184196 VPN (PPPoE) customers Sverdlovsk reg. "Interra" Ltd. RU
'178.211.184.0/21 ',
# 178212009065 OOO NetFox Providing the hosting servers RU
'178.212.8.0/21 ',
# 178213145024 Rusteko infrastructure RU
'178.213.144.0/21 ',
# 178213207176 Gerkon Ltd. net 2_8 RU
'178.213.207.0/24 ',
# 178214046230 route object RU
'178.214.32.0/19 ',
# 178214074027 GEMZO PS
'178.214.64.0/19 ',
# 178215150044 RU
'178.215.150.0/24 ',
# 178217107008 U-LAN-NET RU
'178.217.107.0/24 ',
# 178218029017 TTK AS50771 RU
'178.218.16.0/20 ',
# 178219043014 Professional Telesystems Ltd. net-1 RU
'178.219.40.0/22 ',
# 178219046072 Professional Telesystems Ltd. net-3 RU
'178.219.44.0/22 ',
# 178233134070 Turksat Uydu-Net Internet TR
'178.233.134.0/24 ',
# 178233158191 Turksat Uydu-Net Internet TR
'178.233.158.0/23 ',
# 178234178233 Broadband Connections Addresses Pool #0 RU
'178.234.176.0/20 ',
# 178234226251 Broadband Connections Addresses Pool #6 RU
'178.234.224.0/20 ',
# 178236036158 Toos-Ashena Co. Ltd IR
'178.236.36.0/24 ',
# 178237037150 Cyber Technology BVBA/SPRL BE
'178.237.37.0/24 ',
# 178237177218 OOO SET static pool /21 RU
'178.237.177.0/24 ',
# 178239156240 TRL-MAINRT IR
'178.239.156.0/22 ',
# 178239168057 Bandwidth Technologies Ltd GB
'178.239.160.0/20 ',
# 178239177210 --------------- IT
'178.239.176.0/20 ',
# 178246211177 TURKCELL INTERNET TR
'178.246.192.0/18 ',
# 178249146172 NAO Wexnet Customers SE
'178.249.144.0/21 ',
# 178250035165 Mikrovisata broadband customers LT
'178.250.32.0/21 ',
# 178250047233 H88 S.A. PL
'178.250.40.0/21 ',
# 178251142178 DSSV Clients Address Pool RU
'178.251.142.0/24 ',
# 178252149066 Mabna Route IR
'178.252.149.0/24 ',
# 178252184054 Mabna Route IR
'178.252.184.0/24 ',
# 178253236120 OT-Presevo RS
'178.253.236.0/24 ',
# 178255041251 CyberGhost S.R.L. PL
'178.255.41.0/24 ',
# 178255044059 CyberGhost S.R.L. PL
'178.255.44.0/24 ',
# 178255045108 VPNonline PL
'178.255.45.0/24 ',
# 179040095004 Tecnocomp Argentina Telecomunicaciones y Servicios SRL AR
'179.40.95.0/25 ',
# 179041021249 Telefonica de Argentina AR
'179.40.0.0/15 ',
# 179060147058 PE Solodky Oleg Geor RU
'179.60.147.0/24 ',
# 179061128001 Digital Energy Technologies Ltd DE
'179.61.128.0/24 ',
# 179061130003 Digital Energy Technologies Limited DE
'179.61.130.0/24 ',
# 179061134105 Digital Energy Technologies Limited DE
'179.61.134.0/24 ',
# 179061144011 Digital Energy Technologies Ltd DE
'179.61.144.0/20 ',
# 179061160008 Digital Energy Technologies Ltd DE
'179.61.160.0/19 ',
# 179061205035 Digital Energy Technologies Limited BR
'179.61.205.0/24 ',
# 179061236015 HOST1PLUS hosting services. Brazil. BR
'179.61.224.0/19 ',
# 179105016171 CLARO S.A. US
'179.105.0.0/16 ',
# 179124018243 ZUM TELECOM LTDA- ME US
'179.124.16.0/20 ',
# 179127252151 FIXTELL TELECOM US
'179.127.248.0/21 ',
# 179167170186 TELEF�NICA BRASIL S.A US
'179.164.0.0/14 ',
# 179242206178 Claro S/A US
'179.240.0.0/14 ',
# 180068097181 SK Broadband Co Ltd KR
'180.64.0.0/13 ',
# 180089131150 BeiJing Guoxin bilin Telecom Technology Co.,Ltd CN
'180.89.128.0/19 ',
# 180092239138 ISN-Narshindi-Zone BD
'180.92.239.0/24 ',
# 180094083114 AFTEL AF
'180.94.83.0/24 ',
# 180129226115 China Unicom Yunnan province network CN
'180.129.128.0/17 ',
# 180148033029 Broadband IN
'180.148.33.0/24 ',
# 180148214152 Stargate Communications Ltd. BD
'180.148.214.0/24 ',
# 180151007043 CITYCOM NETWORKS PVT LTD IN
'180.151.7.0/24 ',
# 180151015082 CITYCOM NETWORKS PVT LTD IN
'180.151.15.0/24 ',
# 180151084234 CITYCOM NETWORKS PVT LTD IN
'180.151.84.0/24 ',
# 180151195187 CITYCOM NETWORKS PVT LTD IN
'180.151.195.0/24 ',
# 180151230246 CITYCOM NETWORKS PVT LTD IN
'180.151.230.0/24 ',
# 180151232170 CITYCOM NETWORKS PVT LTD IN
'180.151.232.0/24 ',
# 180151240051 CITYCOM NETWORKS PVT LTD IN
'180.151.240.0/24 ',
# 180180216071 TOT Public Company Limited TH
'180.180.192.0/19 ',
# 180180243120 Internet Data Center Service TH
'180.180.243.0/24 ',
# 180190078036 GBB-MAKATI-IP-POOL PH
'180.190.64.0/18 ',
# 180226074254 LG POWERCOMM KR
'180.224.0.0/13 ',
# 180232106242 Eastern Telecom's DSL-Client PH
'180.232.106.0/24 ',
# 180234223202 Augere Wireless Broadband Bangladesh Limited BD
'180.234.223.0/24 ',
# 180241071020 PT TELKOM INDONESIA ID
'180.241.71.0/24 ',
# 180241103026 PT TELKOM INDONESIA ID
'180.241.102.0/23 ',
# 180241179161 PT TELKOM INDONESIA ID
'180.241.176.0/21 ',
# 180241190143 PT TELKOM INDONESIA ID
'180.241.184.0/21 ',
# 180243020207 PT TELKOM INDONESIA ID
'180.243.16.0/21 ',
# 180245025219 PT TELKOM INDONESIA ID
'180.245.24.0/22 ',
# 180245041126 PT TELKOM INDONESIA ID
'180.245.40.0/21 ',
# 180245120248 PT TELKOM INDONESIA ID
'180.245.120.0/23 ',
# 180245197247 PT TELKOM INDONESIA ID
'180.245.196.0/22 ',
# 180245227167 PT TELKOM INDONESIA ID
'180.245.224.0/22 ',
# 180246059035 PT TELKOM INDONESIA ID
'180.246.56.0/22 ',
# 180247213054 PT TELKOM INDONESIA ID
'180.247.212.0/22 ',
# 180249009199 PT TELKOM INDONESIA ID
'180.249.8.0/21 ',
# 180249016063 PT TELKOM INDONESIA ID
'180.249.16.0/22 ',
# 180249107146 PT TELKOM INDONESIA ID
'180.249.104.0/22 ',
# 180249211209 PT TELKOM INDONESIA ID
'180.249.208.0/22 ',
# 180249226096 PT TELKOM INDONESIA ID
'180.249.224.0/22 ',
# 180249228009 PT TELKOM INDONESIA ID
'180.249.228.0/23 ',
# 180250156186 PT TELKOM INDONESIA ID
'180.250.144.0/20 ',
# 180251010045 PT TELKOM INDONESIA ID
'180.251.0.0/20 ',
# 180252038149 PT TELKOM INDONESIA ID
'180.252.32.0/20 ',
# 180252122144 PT TELKOM INDONESIA ID
'180.252.112.0/20 ',
# 180252129228 PT TELKOM INDONESIA ID
'180.252.128.0/20 ',
# 180252237017 PT TELKOM INDONESIA ID
'180.252.224.0/20 ',
# 180253200186 PT TELKOM INDONESIA ID
'180.253.200.0/21 ',
# 181015156190 MUNICIPALIDAD DE SAN MIGUEL DE TUCUMAN AR
'181.15.156.184/29 ',
# 181041197222 Digital Energy Technologies Limited BR
'181.41.197.0/24 ',
# 181041201176 Digital Energy Technologies Limited BR
'181.41.201.0/24 ',
# 181041206060 DETC SpA Cloud Services US
'181.41.206.0/23 ',
# 181041221083 Digital Energy Technologies Limited BR
'181.41.221.0/24 ',
# 181189235010 Columbus Networks de Honduras S. de R.L. HN
'181.189.224.0/19 ',
# 181209072214 ARSAT - Empresa Argentina de Soluciones Satelitales S.A. AR
'181.209.0.0/17 ',
# 181214000166 HOST1PLUS hosting services. Brazil. BR
'181.214.0.0/16 ',
# 181215000122 HOST1PLUS hosting services. Brazil. BR
'181.215.0.0/16 ',
# 181220189135 CLARO S.A. US
'181.216.0.0/13 ',
# 182018231134 SKYBROADBAND PH
'182.18.231.0/24 ',
# 182023212006 437 Williamstown Road AU
'182.23.212.0/22 ',
# 182052137049 Static IP Addresses for Internet Services TH
'182.52.137.0/24 ',
# 182054231022 10 Dover Drive SG
'182.54.228.0/22 ',
# 182055092233 StarHub-Ltd-NGNBN-Services SG
'182.55.80.0/20 ',
# 182093024091 CTM MO
'182.93.24.0/24 ',
# 182156224204 TTSL-ISP DIVISION IN
'182.156.224.0/19 ',
# 182161006020 Dialog Telekom Plc LK
'182.161.0.0/19 ',
# 182171077065 Sony Network Communications Inc. JP
'182.168.0.0/14 ',
# 182237133162 Deep Broadband Fort IN
'182.237.133.0/24 ',
# 182255062030 Chongqing Jiangbei Road, No. 16 New section 32-8 CN
'182.255.60.0/22 ',
# 182255183248 VTOPIA KR
'182.255.128.0/17 ',
# 183076036080 ASAHI Net,Inc. JP
'183.76.0.0/15 ',
# 183078131064 HAIonNet KR
'183.78.128.0/19 ',
# 183080050000 FPT Telecom Company VN
'183.80.48.0/20 ',
# 183080121042 FPT Telecom Company VN
'183.80.112.0/20 ',
# 183080171010 FPT Telecom Company VN
'183.80.160.0/20 ',
# 183082004172 Core Infrastructure IN
'183.82.4.0/24 ',
# 183082016111 PPPoE IN
'183.82.16.0/24 ',
# 183082022097 PPPoE IN
'183.82.22.0/24 ',
# 183082106073 BRAS Pools - Secunderabad IN
'183.82.106.0/24 ',
# 183082112194 BRAS Pools - Secunderabad IN
'183.82.112.0/24 ',
# 183082117136 BRAS Pools - Secunderabad IN
'183.82.117.0/24 ',
# 183082121006 BRAS Pools - Secunderabad IN
'183.82.121.0/24 ',
# 183082131094 BRAS Pools - Gandhinagar IN
'183.82.131.0/24 ',
# 183082171184 BRAS Pools - Gandhinagar IN
'183.82.171.0/24 ',
# 183082181213 BRAS Pools - Gandhinagar IN
'183.82.181.0/24 ',
# 183082182096 BRAS Pools - Gandhinagar IN
'183.82.182.0/24 ',
# 183082216015 BRAS Pools - Hitech IN
'183.82.216.0/24 ',
# 183082217063 BRAS Pools - Hitech IN
'183.82.217.0/24 ',
# 183082218055 BRAS Pools - Hitech IN
'183.82.218.0/24 ',
# 183082221176 BRAS Pools - Hitech IN
'183.82.221.0/24 ',
# 183082228041 BRAS Pools - Hitech IN
'183.82.228.0/24 ',
# 183083048238 BRAS Pools - Srinagar IN
'183.83.48.0/24 ',
# 183083086027 BRAS Pools - Mehdipatnam IN
'183.83.86.0/24 ',
# 183083114148 BRAS Pools - Mehdipatnam IN
'183.83.114.0/24 ',
# 183087015074 Five network Broadband Solution Pvt Ltd IN
'183.87.15.0/24 ',
# 183087204054 JPR Digital Pvt. Ltd. IN
'183.87.204.0/24 ',
# 183087252226 Airnet Cable And Datacom Pvt Ltd IN
'183.87.252.0/24 ',
# 183090036062 STARHUB MOBILE SG
'183.90.32.0/21 ',
# 183147175187 CHINANET-ZJ Jinhua node network CN
'183.147.0.0/16 ',
# 183177124002 51/A RACHNA MIDAS GOKULPETH IN
'183.177.124.0/24 ',
# 183177126148 51/A RACHNA MIDAS GOKULPETH IN
'183.177.126.0/24 ',
# 183182099035 PO box T511 Phonexay road - Xaysettha district LA
'183.182.96.0/20 ',
# 184175135095 US Signal Company, L.L.C. US-SIGNAL6 (NET-184-175-128-0-1) US
'184.175.128.0/18 ',
# 184175212043 Nodes Direct US
'184.175.192.0/18 ',
# 185003132186 Reverse-Proxy SE
'185.3.132.0/24 ',
# 185003133008 Reverse-Proxy SE
'185.3.133.0/24 ',
# 185005051112 Assigned to VodafoneMalta MT
'185.5.51.0/24 ',
# 185005136074 Mail.Ru RU
'185.5.136.0/22 ',
# 185005175084 NOC Emergency Tel: 40 21 2074774 RO
'185.5.175.0/24 ',
# 185007030135 VMLAB LLC VPS Customers RU
'185.7.28.0/22 ',
# 185008060069 CloudHosting Data Center LV
'185.8.60.0/22 ',
# 185009019117 M247 LTD Vienna Infrastructure AT
'185.9.19.0/24 ',
# 185011129111 Data Space PL
'185.11.129.0/24 ',
# 185011139194 Customer PA Network DE
'185.11.136.0/22 ',
# 185013032039 Trusov Ilya Igorevych RU
'185.13.32.0/23 ',
# 185013120018 Dynamic IP TTK-Baikal/BRAS in Irkutsk RU
'185.13.120.0/22 ',
# 185013156022 Global Creation US
'185.13.156.0/22 ',
# 185013197115 Interactive TV LLC AM
'185.13.196.0/22 ',
# 185014195057 Moscow Network RU
'185.14.195.0/24 ',
# 185016041152 Cloud VPS cuctomers of VMFrame IP address range NL
'185.16.41.1/24 ',
# 185017120176 RU
'185.17.120.0/23 ',
# 185017202164 JSC "SvyazTeleKom", Magnitogorsk, Russia RU
'185.17.202.0/24 ',
# 185020064080 F0 IT
'185.20.64.0/22 ',
# 185020099190 Redstation Limited GB
'185.20.96.0/22 ',
# 185020185139 DELTAHOST-NET NL
'185.20.184.0/23 ',
# 185022063126 Management Networks LLC RU
'185.22.60.0/22 ',
# 185022067015 185.22.67.0/25 KZ
'185.22.67.0/24 ',
# 185022173146 veesp.com clients RU
'185.22.172.0/22 ',
# 185023017130 Network of UAB Dominant Plius LT
'185.23.16.0/23 ',
# 185023048143 GE-EGRISI-20131707 GE
'185.23.48.0/23 ',
# 185024068083 SurfEasy Inc CA
'185.24.68.0/22 ',
# 185025021163 GREEK INTERNET SERVICES GR
'185.25.21.0/24 ',
# 185026169061 OOO PKF "Delta Telekom" RU
'185.26.169.0/24 ',
# 185028061054 Route TR
'185.28.61.0/24 ',
# 185028062002 Route TR
'185.28.62.0/24 ',
# 185028102055 Cloud Services CZ1 CZ
'185.28.100.0/22 ',
# 185028249134 Mynet infrastructure PL
'185.28.248.0/23 ',
# 185029255027 Sole Proprietor Brishaty M. V. UA
'185.29.254.0/23 ',
# 185031172234 * THIS IS A TOR EXIT NODE * NL
'185.31.172.0/22 ',
# 185032066021 OOO "Center informational technologies" RU
'185.32.66.0/24 ',
# 185033145050 Cloud Services CZ1 CZ
'185.33.144.0/22 ',
# 185033236022 MAGLAN RU
'185.33.236.0/22 ',
# 185034020082 LTD "Erline" RU
'185.34.20.0/24 ',
# 185035064017 Aruba Cloud FR
'185.35.64.0/22 ',
# 185035101242 WildPark Co UA
'185.35.100.0/23 ',
# 185036173169 eratelecom ospf net1 RU
'185.36.173.0/24 ',
# 185038014171 YISP NL
'185.38.12.0/22 ',
# 185038046177 OnlineNode GB
'185.38.46.128/26 ',
# 185038150123 185.38.148.0/22 IPv4 route GB
'185.38.148.0/22 ',
# 185039042131 SEGUNDO_RANGO ES
'185.39.40.0/22 ',
# 185040087140 IP Route TR
'185.40.87.0/24 ',
# 185041168021 JSC "ER-Telecom Holding" Tyumen' branch RU
'185.41.168.0/22 ',
# 185042060014 Saint-Petersburg, Russia RU
'185.42.60.0/22 ',
# 185042080060 JSC "ER-Telecom Holding" Tyumen' branch RU
'185.42.80.0/22 ',
# 185042130076 "Telecomunikatsiina Companiya" Ltd UA
'185.42.128.0/22 ',
# 185043006138 JSC Server WebDC colocation RU
'185.43.6.0/23 ',
# 185044113091 nasser.par@gmail.com IR
'185.44.113.0/24 ',
# 185048033144 ECS-NET IT
'185.48.32.0/23 ',
# 185048034075 --------------- IT
'185.48.34.0/23 ',
# 185049168119 OLIVE SUBNET-1 ES
'185.49.168.0/24 ',
# 185050049020 Customers of sownet.pl PL
'185.50.48.0/22 ',
# 185050191250 EDSI-Tech Sarl CH
'185.50.188.0/22 ',
# 185051215229 route object JO
'185.51.215.0/24 ',
# 185051229104 AVAST Software s.r.o. US
'185.51.229.0/24 ',
# 185051247072 NL
'185.51.247.0/24 ',
# 185052069197 RialCom dynamic pppoe network for customers RU
'185.52.68.0/23 ',
# 185054057102 Derkom S.P Jawna Dariusz Klimczuk PL
'185.54.56.0/22 ',
# 185056090138 Buzinessware FZCO AE
'185.56.88.0/22 ',
# 185056137014 Phoenix NAP EU MT MT
'185.56.136.0/22 ',
# 185058205204 Marosnet enterprise network RU
'185.58.204.0/22 ',
# 185059220143 CDN77.com Frankfurt(Germany) POP DE
'185.59.220.0/24 ',
# 185059223183 Datacamp Limited US
'185.59.223.0/24 ',
# 185061124071 swhosting.com ES
'185.61.124.0/22 ',
# 185061207188 IBERSONTEL v4 ES
'185.61.204.0/22 ',
# 185062190023 BlazingFast LLC NL
'185.62.190.0/24 ',
# 185063189040 TheServer-RU RU
'185.63.188.0/22 ',
# 185065134076 Mullvad is a VPN service that helps keep your online activity, identity and location private. NL
'185.65.132.0/22 ',
# 185065205010 CityNet Telekom Ltd. TR
'185.65.205.0/24 ',
# 185066116146 Customers access and Interconnects GB
'185.66.116.0/23 ',
# 185066200010 https://www.skhosting.eu SK
'185.66.200.0/22 ',
# 185067177105 KujtesaNET Prishtine Endusers and Equipment AL
'185.67.177.0/24 ',
# 185068093055 RelinkRoute RU
'185.68.92.0/22 ',
# 185070105114 NCONNECT-NET RU
'185.70.105.0/24 ',
# 185070185112 www.hostkey.com NL
'185.70.185.0/24 ',
# 185075046008 QuickSoft LLC RU
'185.75.44.0/22 ',
# 185076009154 CDN77.com Stockholm (Sweden) POP SE
'185.76.9.0/24 ',
# 185076010083 CDN77.com Amsterdam (Netherlands) POP NL
'185.76.10.0/24 ',
# 185076071100 Omega Telecom IPv4 Aggregated UA
'185.76.68.0/22 ',
# 185076145020 RU-NIC VDS Hosting RU
'185.76.144.0/22 ',
# 185077248002 IL
'185.77.248.0/24 ',
# 185080220042 UK2 Infrastructure NL
'185.80.220.0/22 ',
# 185082203132 HostSailor NL Services NL
'185.82.203.0/24 ',
# 185082212095 CZ
'185.82.212.0/24 ',
# 185082223121 MUVHost - DGN Route - 185.82.223.0/24 TR
'185.82.223.0/24 ',
# 185085239208 IDEAL HOSTING SUNUCU INTERNET HIZM. TIC. LTD. STI TR
'185.85.239.0/24 ',
# 185086012213 CONTROL TELEKOMUNIKASYON HOSTING VE DATA CENTER LTD STI TR
'185.86.12.0/24 ',
# 185086121053 KOMTEL RU
'185.86.112.0/20 ',
# 185086132001 JSC "UL-Com-Media" RU
'185.86.132.0/24 ',
# 185086149184 Virtual Server hosting SE
'185.86.149.0/24 ',
# 185086150021 Virtual Server hosting SE
'185.86.150.0/24 ',
# 185086151110 Virtual server hosting LV
'185.86.151.0/24 ',
# 185087048017 Marosnet enterprise network RU
'185.87.48.0/22 ',
# 185087185045 PCextreme B.V. NL
'185.87.184.0/23 ',
# 185088127113 Sll "Computer & Communication System" RU
'185.88.124.0/22 ',
# 185089085029 NEWNET-ROUTE-185.89.85.0/24 LB
'185.89.85.0/24 ',
# 185089100011 EUNet DE
'185.89.100.0/24 ',
# 185089101012 Moscow Network RU
'185.89.101.0/24 ',
# 185089112129 Bitanet route IR
'185.89.112.0/24 ',
# 185089216236 Onavo Infrastructure US
'185.89.216.0/24 ',
# 185089251035 Giglinx Global Inc. CZ
'185.89.251.0/24 ',
# 185090061051 Oneprovider.com - Norway Infraestructure NO
'185.90.61.0/24 ',
# 185092025040 Express-Equinix-London GB
'185.92.25.0/24 ',
# 185093001020 CDN77.com US
'185.93.1.0/24 ',
# 185093110101 NetAngels.RU network in Yekaterinburg (Rostelecom DataCenter) RU
'185.93.110.0/23 ',
# 185093180104 M247-LTD-Frankfurt-2-Network DE
'185.93.180.0/24 ',
# 185093181090 M247-LTD-Madrid-Network ES
'185.93.181.0/24 ',
# 185093182133 Cyberghost-Madrid-Servers ES
'185.93.182.0/24 ',
# 185093183140 M247 LTD Milan Infrastructure IT
'185.93.183.0/24 ',
# 185094188183 M247 LTD Amsterdam Infrastructure NL
'185.94.188.0/24 ',
# 185094189136 Cyberghost-Servers-Paris FR
'185.94.189.0/24 ',
# 185094190156 Cyberghost-Budapest HU
'185.94.190.0/24 ',
# 185094193076 M247 LTD Milan Infrastructure IT
'185.94.193.0/24 ',
# 185095160175 Arkaden Konsult AB SE
'185.95.160.0/22 ',
# 185095202202 DE1-BUSINESSCOM US
'185.95.200.0/22 ',
# 185095252066 roIS252 LB
'185.95.252.0/24 ',
# 185097253119 ArtPlanet Virtusal Server RU
'185.97.252.0/23 ',
# 185098232052 Comunicacions Globals de Catalunya SL ES
'185.98.232.0/24 ',
# 185099171226 LLC "Magic IT Plus" RU
'185.99.168.0/22 ',
# 185100084082 FlokiNET RO
'185.100.84.0/23 ',
# 185100086100 FlokiNET FI
'185.100.86.0/24 ',
# 185100087082 FlokiNET ehf RO
'185.100.87.0/24 ',
# 185101032057 NO-SERVETHEWORLD NO
'185.101.32.0/22 ',
# 185101068033 Trusov Ilya Igorevych RU
'185.101.68.0/24 ',
# 185101069046 Trusov Ilya Igorevych RU
'185.101.69.0/24 ',
# 185101070040 Los Angeles Network US
'185.101.70.0/24 ',
# 185101071023 Route RU
'185.101.71.0/24 ',
# 185101218211 BudgetNode US
'185.101.218.0/24 ',
# 185101219101 HostSlick VPS Customers in Milan IT
'185.101.219.0/24 ',
# 185101238005 TarinNet ISP IQ
'185.101.238.0/24 ',
# 185102136106 Customer MGNHOST RU
'185.102.136.0/24 ',
# 185102190145 PA dla JMDI PL
'185.102.190.0/23 ',
# 185102219146 CDN77.com Frankfurt (Germany) POP DE
'185.102.219.0/24 ',
# 185103099060 ROB009 GB
'185.103.96.0/22 ',
# 185103217117 CRATIS HR
'185.103.216.0/22 ',
# 185104120002 /24 used for public and member sponsored Tor (torproject.org) relays GB
'185.104.120.0/22 ',
# 185104184136 M247 LTD Frankfurt Infrastructure DE
'185.104.184.0/24 ',
# 185104186167 M247 LTD Brussels Infrastructure BE
'185.104.186.0/24 ',
# 185104187060 M247 LTD Budapest Infrastructure HU
'185.104.187.0/24 ',
# 185104190076 NL
'185.104.190.0/24 ',
# 185104216053 ServeByte IE
'185.104.216.0/22 ',
# 185105225163 Network for VPS RU
'185.105.225.0/24 ',
# 185106103011 CY
'185.106.100.0/22 ',
# 185106104055 depo40 RU
'185.106.104.0/23 ',
# 185107024006 IP Range Serverius DC1 NL
'185.107.24.0/24 ',
# 185107044051 Amsterdam Residential Television and Internet NL
'185.107.44.0/22 ',
# 185107080179 Serverhosting NL
'185.107.80.0/22 ',
# 185108104007 S.S.NetShop-Internet-Services-Ltd GB
'185.108.104.0/24 ',
# 185108128003 IE
'185.108.128.0/22 ',
# 185108167017 Badr-Rayan Route IR
'185.108.164.0/22 ',
# 185108215247 AlushtaInternetServis LLC RU
'185.108.212.0/22 ',
# 185108217212 SurfEasy Inc DE
'185.108.216.0/22 ',
# 185109161181 HostDZire Web Services Pvt. Ltd. NL
'185.109.160.0/22 ',
# 185109245012 Asiatech Network IR
'185.109.244.0/23 ',
# 185110216001 IR
'185.110.216.0/24 ',
# 185112012002 Ukrnames LLC UA
'185.112.12.0/23 ',
# 185112014011 Ukrnames LLC UA
'185.112.14.0/23 ',
# 185112082049 Creanova FI
'185.112.82.0/24 ',
# 185112148142 IR
'185.112.148.0/22 ',
# 185112157001 MikroVPS Kft HU
'185.112.156.0/22 ',
# 185112249159 SharkServers GB
'185.112.249.0/24 ',
# 185113128234 GigaTux Ltd GB
'185.113.128.0/23 ',
# 185114141004 routed via LeaseWeb NL NL
'185.114.140.0/22 ',
# 185114225010 Jordy Visser trading as Yor-Game NL
'185.114.225.0/24 ',
# 185116045005 IP Range Serverius DC1 NL
'185.116.45.0/24 ',
# 185117020030 My Server Planet Limited GB
'185.117.20.0/24 ',
# 185117118091 Creanova FI
'185.117.118.0/24 ',
# 185117153099 Marosnet enterprise network RU
'185.117.152.0/22 ',
# 185117215009 Digineo GmbH DE
'185.117.214.0/23 ',
# 185118076026 GZ Systems Limited LV
'185.118.76.0/22 ',
# 185118165039 Chelyabinsk-Signal RU
'185.118.165.0/24 ',
# 185118167091 Chelyabinsk-Signal RU
'185.118.167.0/24 ',
# 185119059144 LLC SUDAK-NET RU
'185.119.56.0/22 ',
# 185119240142 Qom University Of Technology IR
'185.119.240.0/22 ',
# 185120034241 Hydra Communications Ltd GB
'185.120.34.0/24 ',
# 185120125037 099 Primo Communications IL
'185.120.124.0/22 ',
# 185120133003 Kancom-Net-185.120.132.0-185.120.133.255 UA
'185.120.132.0/22 ',
# 185120147163 M247 Europe SRL RO
'185.120.144.0/22 ',
# 185121026225 DR SOFT SRL GB
'185.121.26.0/24 ',
# 185121200035 Magic Net HR
'185.121.200.0/22 ',
# 185122107140 FIBER NET and DERKOM SP.J. PL
'185.122.104.0/22 ',
# 185122170014 routed via LeaseWeb NL NL
'185.122.168.0/22 ',
# 185123102017 IP Route TR
'185.123.102.0/24 ',
# 185123141194 M247 Europe SRL RO
'185.123.140.0/23 ',
# 185124154222 Right Side+ LCC RU
'185.124.152.0/22 ',
# 185125168247 TerraHost AS Customers NO
'185.125.168.0/22 ',
# 185125217159 Marosnet enterprise network RU
'185.125.216.0/22 ',
# 185126002056 PTE Network IR
'185.126.0.0/22 ',
# 185126013243 PTE-Network IR
'185.126.12.0/22 ',
# 185126176197 Bursabil Teknoloji A.S. TR
'185.126.176.0/24 ',
# 185127018134 GB
'185.127.18.0/24 ',
# 185127019220 GB
'185.127.19.0/24 ',
# 185127024086 JSC "Informtehtrans" RU
'185.127.24.0/24 ',
# 185127025068 JSC "Informtehtrans" RU
'185.127.25.0/24 ',
# 185127111227 ZEROSPACE NL
'185.127.111.0/24 ',
# 185127164045 RocketTelecom LLC RU
'185.127.164.0/24 ',
# 185127165017 RocketTelecom LLC RU
'185.127.165.0/24 ',
# 185127167218 RocketTelecom LLC RU
'185.127.167.0/24 ',
# 185128036100 CNC Telecom Ltd. IQ
'185.128.36.0/24 ',
# 185128038100 CNC Telecom Ltd. IQ
'185.128.38.0/24 ',
# 185128042028 Email: info@rackend.com CH
'185.128.42.0/24 ',
# 185128235245 UA
'185.128.235.0/24 ',
# 185129062062 Various servers including Tor servers DK
'185.129.60.0/22 ',
# 185129131116 RU
'185.129.130.0/23 ',
# 185129217164 PTE DSL Network IR
'185.129.216.0/22 ',
# 185130104198 Hosting purposes in Russian Federation RU
'185.130.104.0/24 ',
# 185130206134 Digital Energy Technologies Limited DE
'185.130.204.0/22 ',
# 185133042031 RU
'185.133.42.0/24 ',
# 185134030102 DK
'185.134.28.0/22 ',
# 185134120127 Rostelecom networks (ParkWeb) RU
'185.134.120.0/24 ',
# 185135009190 Digital Energy Technologies Limited DE
'185.135.8.0/22 ',
# 185135081180 ADMAN-NET RU
'185.135.80.0/22 ',
# 185135226055 Easy Com PL
'185.135.226.0/24 ',
# 185137017050 NL
'185.137.16.0/22 ',
# 185140114108 Oneprovider.com - Spanish infraestructure ES
'185.140.114.0/24 ',
# 185141165185 Digital Energy Technologies Limited DE
'185.141.164.0/22 ',
# 185141204038 North Hosts Limited GB
'185.141.204.0/24 ',
# 185142024062 GZ Systems DE
'185.142.24.0/22 ',
# 185142233035 SHABAKEH SHARIF ISDP Maintener IR
'185.142.233.0/24 ',
# 185143228205 Digital Energy Technologies Limited DE
'185.143.228.0/22 ',
# 185144029134 RU
'185.144.29.0/24 ',
# 185144078055 TR
'185.144.76.0/22 ',
# 185144080235 RO
'185.144.80.0/23 ',
# 185145036038 Digital Energy Technologies Limited DE
'185.145.36.0/22 ',
# 185145066163 Digital Energy Technologies Limited DE
'185.145.64.0/22 ',
# 185145131172 Abelohost B.V. NL
'185.145.128.0/22 ',
# 185145156046 DigitalFyre GB
'185.145.156.0/24 ',
# 185145192043 NL
'185.145.192.0/24 ',
# 185145193056 NL
'185.145.193.0/24 ',
# 185145194009 NL
'185.145.194.0/24 ',
# 185145195002 NL
'185.145.195.0/24 ',
# 185145253008 NL
'185.145.253.0/24 ',
# 185146212136 TRINET RU
'185.146.212.0/22 ',
# 185147034172 BudgetNode NL
'185.147.34.0/24 ',
# 185147083058 Miran Net for VDS,VPS RU
'185.147.83.0/24 ',
# 185148145093 Host.ag BG
'185.148.145.0/24 ',
# 185148220011 User_net_1_pppoe RU
'185.148.220.0/24 ',
# 185149255081 IL
'185.149.252.0/22 ',
# 185151057141 Digital Energy Technologies Limited DE
'185.151.56.0/22 ',
# 185151210038 ES
'185.151.208.0/22 ',
# 185153149007 BG
'185.153.149.0/24 ',
# 185153151016 LU
'185.153.151.0/24 ',
# 185153177002 TEFINCOM S.A. MX
'185.153.177.0/24 ',
# 185153179002 TEFINCOM S.A. CA
'185.153.179.0/24 ',
# 185154013007 ZOMRO-NL NL
'185.154.13.0/24 ',
# 185154015033 ZOMRO-NL NL
'185.154.15.0/24 ',
# 185154022018 RU
'185.154.20.0/22 ',
# 185154052089 Eurobyte VDS RU
'185.154.52.0/24 ',
# 185154053021 Eurobyte VDS RU
'185.154.53.0/24 ',
# 185156172131 M247 LTD Amsterdam Infrastructure NL
'185.156.172.0/24 ',
# 185156173012 M247 LTD Paris Infrastructure FR
'185.156.173.0/24 ',
# 185156174027 M247 LTD Prague Infrastructure CZ
'185.156.174.0/24 ',
# 185156178202 RU
'185.156.178.0/23 ',
# 185157033097 DE
'185.157.32.0/23 ',
# 185157162040 OVPN Amsterdam NL
'185.157.162.0/24 ',
# 185157232141 LoveServers Ltd GB
'185.157.232.0/24 ',
# 185158100172 Digital Energy Technologies Limited DE
'185.158.100.0/22 ',
# 185158104131 Digital Energy Technologies Limited DE
'185.158.104.0/22 ',
# 185158116131 Digital Energy Technologies Limited DE
'185.158.116.0/22 ',
# 185158120141 Digital Energy Technologies Limited DE
'185.158.120.0/22 ',
# 185158132131 Digital Energy Technologies Limited DE
'185.158.132.0/22 ',
# 185158148128 Digital Energy Technologies Limited DE
'185.158.148.0/22 ',
# 185159082074 Hosting purposes in Russian Federation RU
'185.159.82.0/24 ',
# 185159087117 IR
'185.159.84.0/22 ',
# 185160254102 GB
'185.160.252.0/22 ',
# 185161113091 IR
'185.161.113.0/24 ',
# 185162008194 Hosting Provider EuroHoster Ltd. NL
'185.162.8.0/24 ',
# 185162010160 Hosting Provider EuroHoster Ltd. BG
'185.162.10.0/24 ',
# 185162021058 Beetec-Telecom-AS UA
'185.162.20.0/22 ',
# 185163024085 UZ
'185.163.24.0/22 ',
# 185163045074 MivoCloud MD
'185.163.45.0/24 ',
# 185163124204 www.onetsolutions.net FR
'185.163.124.0/22 ',
# 185165029040 DE
'185.165.29.0/24 ',
# 185165089207 IT
'185.165.88.0/22 ',
# 185165168042 SC
'185.165.168.0/24 ',
# 185167219076 RU
'185.167.219.0/24 ',
# 185169229230 Hosting & Dedicated servers Services GI
'185.169.229.0/24 ',
# 185170041008 PA
'185.170.41.0/24 ',
# 185170042004 PA
'185.170.42.0/24 ',
# 185170143242 GR
'185.170.143.0/24 ',
# 185171069028 OGUZSATLINK SRL MD
'185.171.68.0/22 ',
# 185172086022 PL
'185.172.86.0/24 ',
# 185172240036 Cresh.NET PL
'185.172.240.0/22 ',
# 185173183100 LU
'185.173.180.0/22 ',
# 185174137249 RU
'185.174.137.0/24 ',
# 185174156008 Hostcolor LLC AT
'185.174.156.0/24 ',
# 185175116097 RU
'185.175.116.0/22 ',
# 185175158223 RU
'185.175.158.0/24 ',
# 185175159028 Client's network IN
'185.175.159.0/24 ',
# 185175208179 HostSlick VPS Customers in London GB
'185.175.208.0/24 ',
# 185177020130 North Hosts Limited GB
'185.177.20.0/24 ',
# 185177073242 Solucions Valencianes i Noves Tecnologies SL ES
'185.177.73.0/24 ',
# 185180015217 10Gbps.IO PRG II. IPv4 route CZ
'185.180.14.0/23 ',
# 185180230110 RU
'185.180.230.0/24 ',
# 185180231179 RU
'185.180.231.0/24 ',
# 185182048006 Hydra Communications Ltd GB
'185.182.48.0/23 ',
# 185182081008 NovoServe GmbH - Frankfurt DE
'185.182.80.0/22 ',
# 185182228013 Hosted on RapidSwitch GB
'185.182.228.0/24 ',
# 185182229007 Hosted on RapidSwitch GB
'185.182.229.0/24 ',
# 185182230030 Hosted on RapidSwitch GB
'185.182.230.0/24 ',
# 185182231010 Hosted on RapidSwitch GB
'185.182.231.0/24 ',
# 185183097119 HostSailor NL Services RO
'185.183.97.0/24 ',
# 185183107148 M247 LTD Vienna Infrastructure AT
'185.183.107.0/24 ',
# 185184241002 DreamServer SRL RO
'185.184.241.0/24 ',
# 185186076107 Oneprovider.com - Zurich Infraestructure CH
'185.186.76.0/24 ',
# 185186077013 Oneprovider.com - London Infraestructure GB
'185.186.77.0/24 ',
# 185186078060 Oneprovider.com - Stockholm Infraestructure SE
'185.186.78.0/24 ',
# 185188061013 GB
'185.188.61.0/24 ',
# 185188182028 RU
'185.188.182.0/24 ',
# 185188183124 RU
'185.188.183.0/24 ',
# 185189012058 RU
'185.189.12.0/24 ',
# 185189013234 RU
'185.189.13.0/24 ',
# 185189014026 RU
'185.189.14.0/24 ',
# 185189037049 EliteWork LLC US
'185.189.36.0/22 ',
# 185189044007 EliteWork LLC US
'185.189.44.0/22 ',
# 185189056140 DevCapsule ( Los Angeles ) US
'185.189.56.0/24 ',
# 185189113007 M247 LTD Paris Infrastructure FR
'185.189.113.0/24 ',
# 185191142015 RU
'185.191.142.0/24 ',
# 185193049069 EliteWork LLC US
'185.193.48.0/22 ',
# 185195017229 RO
'185.195.16.0/23 ',
# 185195027062 RU
'185.195.27.0/24 ',
# 185198222016 Centex Hosting US
'185.198.220.0/22 ',
# 185200188067 RU
'185.200.188.0/24 ',
# 185202103004 UA
'185.202.103.0/24 ',
# 185203241155 NL
'185.203.241.0/24 ',
# 185203242025 NL
'185.203.242.0/24 ',
# 185203243247 NL
'185.203.243.0/24 ',
# 185208170050 VMHaus Enterprise GB
'185.208.170.0/24 ',
# 185208209002 ContraWeb NL
'185.208.208.0/23 ',
# 186000094106 EPM Telecomunicaciones S.A. E.S.P. CO
'186.0.64.0/18 ',
# 186001180214 Dialnet de Colombia S.A. E.S.P. CO
'186.1.160.0/19 ',
# 186004192202 Clientes NETLIFE Quito - gepon EC
'186.4.192.128/25 ',
# 186027127129 Nuevatel PCS de Bolivia S.A. BO
'186.27.64.0/18 ',
# 186032024093 TIGO Costa Rica HOME CR
'186.32.24.0/22 ',
# 186033095212 Wind red L DO
'186.33.95.128/25 ',
# 186046041106 DINAPEN EC
'186.46.41.104/29 ',
# 186068085026 Satnet Gye CM EC
'186.68.84.0/22 ',
# 186148240001 Raul Alberto Bertani AR
'186.148.240.0/21 ',
# 186153171234 EL GRAN PORTAL S.R.L AR
'186.153.171.232/29 ',
# 186209030112 TPA TELECOMUNICACOES LTDA US
'186.209.24.0/21 ',
# 186209087084 DE ALMEIDA E MENSCH - PROVEDOR DE INTERNET LTDA US
'186.209.80.0/21 ',
# 186219027085 INTERNEXA RJ OPERADORA DE TELECOMUNICA��ES LTDA. US
'186.219.0.0/19 ',
# 186225026017 WINOVAR CALL CENTER LTDA US
'186.225.26.16/29 ',
# 186225065001 Next Telecomunica��es do Brasil LTDA US
'186.225.64.0/20 ',
# 186225101170 Autron Automacao Ind�stria e Com�rcio Ltda. US
'186.225.101.168/29 ',
# 186225235042 Unetvale Servicos e Equipamentos LTDA US
'186.225.224.0/20 ',
# 186226219050 8-Bit Informatica e Provedor LTDA US
'186.226.216.0/22 ',
# 186233041122 Inforwave Internet JF Ltda US
'186.233.40.0/21 ',
# 186249065018 WE RADIO COMUNICA��ES LTDA EPP US
'186.249.64.0/20 ',
# 186250220186 infinity brasil telecom ltda me US
'186.250.220.0/22 ',
# 187033229099 QUESALON-DISTRIB. PRODS FARMS. US
'187.33.229.96/29 ',
# 187063175026 BITCOM PROVEDOR DE SERVICOS DE INTERNET LTDA US
'187.63.160.0/19 ',
# 187084141050 WEST INTERNET BANDA LARGA US
'187.84.128.0/20 ',
# 187085179069 Vista Telecom Ltda US
'187.85.179.64/29 ',
# 187087077075 Roberto Russo Neto US
'187.87.77.72/29 ',
# 187094099038 Daniel Gon?alves US
'187.94.99.32/28 ',
# 187103079229 AKI CARNES COMERCIO LTDA - ME US
'187.103.79.228/30 ',
# 187108046242 Value Team Brasil Consult.em T.I. & Solu��es Ltda. US
'187.108.46.240/28 ',
# 187120181172 Microdata de Luc�lia Servi�os de Provedores Ltda US
'187.120.176.0/20 ',
# 187160004082 Television Internacional, S.A. de C.V. MX
'187.160.0.0/16 ',
# 187161003169 Television Internacional, S.A. de C.V. MX
'187.161.0.0/16 ',
# 187180002059 CLARO S.A. US
'187.180.0.0/14 ',
# 187190050017 TOTAL PLAY TELECOMUNICACIONES SA DE CV MX
'187.190.0.0/16 ',
# 187228093013 Uninet S.A. de C.V. MX
'187.228.0.0/16 ',
# 188042035150 LU
'188.42.32.0/21 ',
# 188042218245 Servers.com B.V. NL
'188.42.216.0/22 ',
# 188047180161 Orange Mobile PL
'188.47.160.0/19 ',
# 188065135026 NetONE net for Kvart RU
'188.65.128.0/21 ',
# 188068000022 GB
'188.68.0.0/24 ',
# 188068001024 RU
'188.68.1.0/24 ',
# 188068003012 CZ
'188.68.3.0/24 ',
# 188068029165 Rybinsk 188.68.24.0/21 RU
'188.68.24.0/21 ',
# 188068150039 CJSC "Ural-TransTeleCom" RU
'188.68.148.0/22 ',
# 188068179104 Client Uplinks RU
'188.68.176.0/22 ',
# 188072068088 ES
'188.72.68.0/24 ',
# 188072101018 GZ Systems Limited - Colocation in Unit-IS Ltd., Ukraine UA
'188.72.101.0/24 ',
# 188072102112 GZ Systems Limited - Colocation in BlackNight Internet Solutions, Ireland. IE
'188.72.102.0/24 ',
# 188072103036 GZ Systems Limited - Colocation in Ecatel LTD AE
'188.72.103.0/24 ',
# 188072110199 GZ Systems Limited - Colocation in Trabia Network MD
'188.72.110.0/24 ',
# 188072111007 GZ Systems Limited - Colocation in LinxTelecom EE
'188.72.111.0/24 ',
# 188072112025 GZ Systems Limited - Colocation in Exacom.sk SK
'188.72.112.0/24 ',
# 188072116013 GZ Systems Limited - Colocation in Duomenu Centras LT
'188.72.116.0/24 ',
# 188072117022 GZ Systems Limited - Colocation in Ecatel ltd NL
'188.72.117.0/24 ',
# 188072118052 GZ Systems Limited - Colocation in KemiNet Ltd. AL
'188.72.118.0/24 ',
# 188072119008 GZ Systems Limited - Colocation in Logosnet Services Limited CY
'188.72.119.0/24 ',
# 188072125033 GZ Systems Limited - Colocation in Deninet KFT. HU
'188.72.125.0/24 ',
# 188075255065 MKS75 Network RU
'188.75.224.0/19 ',
# 188078162108 Jazztel triple play services ES
'188.78.0.0/16 ',
# 188093133214 PPPoE access to Internet RU
'188.93.133.0/24 ',
# 188094114167 DE
'188.94.112.0/21 ',
# 188112116002 Slovanet 3PP 2nd pool SK
'188.112.96.0/19 ',
# 188113230136 Ucell Net 3 UZ
'188.113.228.0/22 ',
# 188114204075 RU
'188.114.200.0/21 ',
# 188116011181 www.hitme.net.pl PL
'188.116.11.0/24 ',
# 188120052073 OJSC Kostroma Municipal Telephone Network ( KGTS) RU
'188.120.48.0/21 ',
# 188120130029 Dynamic dialup customers, ADSL IL
'188.120.130.0/23 ',
# 188120188022 LAN clients SE
'188.120.160.0/19 ',
# 188123059177 Regional Digital Telecommunication Company RU
'188.123.56.0/22 ',
# 188123126139 Lertas network GR
'188.123.126.0/24 ',
# 188130157022 Uplink LLC KZ
'188.130.157.0/24 ',
# 188130230040 Business-Systems Ltd RU
'188.130.224.0/21 ',
# 188162180231 Infrastructure in Msk RU
'188.162.180.0/24 ',
# 188162192030 infrastructure in Ufa RU
'188.162.192.0/21 ',
# 188170080175 North-West Branch of OJSC MegaFon Network RU
'188.170.80.0/22 ',
# 188170193167 Caucasus Branch of OJSC MegaFon, Pool RU
'188.170.192.0/23 ',
# 188170196100 Caucasus Branch of OJSC MegaFon, Pool RU
'188.170.196.0/23 ',
# 188170198078 Caucasus Branch of OJSC MegaFon, Pool RU
'188.170.198.0/23 ',
# 188186013180 CJSC "Company "ER-Telecom" Tyumen' RU
'188.186.12.0/22 ',
# 188186030014 CJSC "Company "ER-Telecom" Tyumen' RU
'188.186.28.0/22 ',
# 188186076152 CJSC "Company "ER-Telecom" Tyumen' RU
'188.186.76.0/22 ',
# 188186083131 CJSC "Company "ER-Telecom" Tyumen' RU
'188.186.80.0/22 ',
# 188186089082 CJSC "Company "ER-Telecom" Tyumen' RU
'188.186.88.0/22 ',
# 188186098118 CJSC "Company "ER-Telecom" Tyumen' RU
'188.186.96.0/22 ',
# 188186102111 CJSC "Company "ER-Telecom" Tyumen' RU
'188.186.100.0/22 ',
# 188186107128 CJSC "Company "ER-Telecom" Tyumen' RU
'188.186.104.0/22 ',
# 188186223077 CJSC "ER-Telecom Holding" Orenburg branch RU
'188.186.220.0/22 ',
# 188187032131 CJSC "ER-Telecom Holding" Saint-Petersburg branch RU
'188.187.32.0/22 ',
# 188187190059 CJSC "Company "ER-Telecom" Yoshkar-Ola RU
'188.187.190.0/24 ',
# 188187208199 CJSC "ER-Telecom Holding" Rostov-na-Donu branch RU
'188.187.208.0/22 ',
# 188190238238 LocalNet UA
'188.190.224.0/19 ',
# 188225039110 TimeWeb Co. LTD RU
'188.225.39.0/24 ',
# 188228083012 Altibox Danmark Residential Customer Linknets DK
'188.228.0.0/17 ',
# 188232075029 CJSC "ER-Telecom Holding" Kursk branch RU
'188.232.72.0/22 ',
# 188232107193 CJSC "ER-Telecom Holding" Omsk branch RU
'188.232.104.0/22 ',
# 188232113151 CJSC "ER-Telecom Holding" Omsk branch RU
'188.232.112.0/22 ',
# 188232223188 CJSC "ER-Telecom Holding" Omsk branch RU
'188.232.220.0/22 ',
# 188233001149 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.0.0/22 ',
# 188233017186 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.16.0/22 ',
# 188233037149 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.36.0/22 ',
# 188233045117 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.44.0/22 ',
# 188233077223 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.76.0/22 ',
# 188233082014 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.80.0/22 ',
# 188233102015 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.100.0/22 ',
# 188233115237 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.112.0/22 ',
# 188233123072 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.120.0/22 ',
# 188233147203 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.144.0/22 ',
# 188233152251 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.152.0/22 ',
# 188233159192 CJSC "ER-Telecom Holding" Volgograd branch RU
'188.233.156.0/22 ',
# 188234176189 CJSC "ER-Telecom Holding" Kursk branch RU
'188.234.176.0/22 ',
# 188234180068 CJSC "ER-Telecom Holding" Kursk branch RU
'188.234.180.0/22 ',
# 188235042074 CJSC "ER-Telecom Holding" Voronezh branch RU
'188.235.40.0/22 ',
# 188235130034 CJSC "ER-Telecom Holding" Saratov branch RU
'188.235.130.0/24 ',
# 188235161158 CJSC "ER-Telecom Holding" Saratov branch RU
'188.235.160.0/22 ',
# 188239032094 NashNet route UA
'188.239.32.0/20 ',
# 188243232133 SkyNet Network RU
'188.243.192.0/18 ',
# 188247239217 Bunea Telecom SRL RO
'188.247.239.0/24 ',
# 189001169164 Maxihost Hospedagem de Sites Ltda US
'189.1.168.0/21 ',
# 189014195172 EBR Inform�tica Ltda US
'189.14.192.0/20 ',
# 189020032090 Telefonica Data S.A. US
'189.20.0.0/16 ',
# 189045201066 TPA TELECOMUNICACOES LTDA US
'189.45.192.0/20 ',
# 189052165134 CLARO S.A. US
'189.52.0.0/15 ',
# 189085184050 NEWSITE INTERNET LTDA US
'189.85.160.0/19 ',
# 189091200122 Superline Telecomunica��es Ltda US
'189.91.192.0/20 ',
# 189096201157 TELEF�NICA BRASIL S.A US
'189.96.0.0/15 ',
# 189126241202 MS LINK-TECNOLOGIA E COMUNICA��ES LTDA ME US
'189.126.240.0/20 ',
# 189127006022 Comercial Zaragoza Importa��o Exporta��o US
'189.127.6.20/30 ',
# 189127010139 Associacao Parque Tecnologico de Sao Jose dos Camp US
'189.127.10.128/26 ',
# 189135018194 Gesti�n de direccionamiento UniNet MX
'189.135.18.0/24 ',
# 189144205143 Gesti�n de direccionamiento UniNet MX
'189.144.205.0/24 ',
# 189148039049 Gesti�n de direccionamiento UniNet MX
'189.148.39.0/24 ',
# 189148163061 Gesti�n de direccionamiento UniNet MX
'189.148.163.0/24 ',
# 189166015162 Gesti�n de direccionamiento UniNet MX
'189.166.15.0/24 ',
# 189166032208 Gesti�n de direccionamiento UniNet MX
'189.166.32.0/24 ',
# 189166048240 Gesti�n de direccionamiento UniNet MX
'189.166.48.0/24 ',
# 189166059151 Gesti�n de direccionamiento UniNet MX
'189.166.59.0/24 ',
# 189181154053 Gesti�n de direccionamiento UniNet MX
'189.181.154.0/24 ',
# 189190048161 Gesti�n de direccionamiento UniNet MX
'189.190.48.0/24 ',
# 189208039201 Axtel - Recursos WiMAX MX
'189.208.32.0/21 ',
# 189209241119 Axtel, S.A.B. de C.V. MX
'189.209.0.0/16 ',
# 189218040012 Television Internacional, S.A. de C.V. MX
'189.218.32.0/20 ',
# 189218068175 Television Internacional, S.A. de C.V. MX
'189.218.64.0/20 ',
# 189218108019 Television Internacional, S.A. de C.V. MX
'189.218.108.0/24 ',
# 189218173021 Television Internacional, S.A. de C.V. MX
'189.218.172.0/23 ',
# 189218206062 Television Internacional, S.A. de C.V. MX
'189.218.204.0/22 ',
# 189218214244 Television Internacional, S.A. de C.V. MX
'189.218.214.0/23 ',
# 189218216065 Television Internacional, S.A. de C.V. MX
'189.218.216.0/23 ',
# 189218230030 Television Internacional, S.A. de C.V. MX
'189.218.230.0/23 ',
# 189218238235 Television Internacional, S.A. de C.V. MX
'189.218.238.0/23 ',
# 189218248137 Television Internacional, S.A. de C.V. MX
'189.218.248.0/24 ',
# 190005096058 Columbus Networks de Honduras S. de R.L. HN
'190.5.96.0/19 ',
# 190007252164 Imagenes Digitales AR
'190.7.252.160/29 ',
# 190009058103 Sociedad de Telecomunicaciones Geonet Ltda. CL
'190.9.56.0/21 ',
# 190010080004 Cable Vision CR
'190.10.80.0/25 ',
# 190012102205 CPS AR
'190.12.96.0/20 ',
# 190031081061 Apolo -Gold-Telecom-Per AR
'190.31.80.0/23 ',
# 190031085175 Apolo -Gold-Telecom-Per AR
'190.31.84.0/23 ',
# 190052130254 CO.PA.CO. PY
'190.52.128.0/19 ',
# 190093053176 SAN LUIS CTV S.A. AR
'190.93.48.0/20 ',
# 190105089092 Ver Tv S.A. AR
'190.105.64.0/18 ',
# 190105128194 CLIENTES STA ROSA COPAN SERCOM HN
'190.105.128.0/24 ',
# 190106132240 G2KHosting S.A. AR
'190.106.128.0/19 ',
# 190108035002 Cooperativa de Provisi�n de Obras y Servicios P�blicos de Caleufu Ltda. AR
'190.108.35.0/24 ',
# 190111031173 Navega Nicaragua NI
'190.111.31.128/25 ',
# 190112223117 Data Miners S.A. ( Racknation.cr ) CR
'190.112.220.0/22 ',
# 190123046051 Panamaserver.com PA
'190.123.32.0/20 ',
# 190124187253 Dez Solucoes em Telecomunicacoes LTDA US
'190.124.176.0/20 ',
# 190143231249 TELEFONICA MOVILES GUATEMALA S.A. GT
'190.143.192.0/18 ',
# 190186042122 LUIS ALEXANDER MIRANDA BO
'190.186.42.120/29 ',
# 190186055117 ADSL-IP-DINAMICA - PLAN-320 BO
'190.186.55.0/25 ',
# 190211080154 Patagonia Green S.A. AR
'190.211.80.0/21 ',
# 190217003162 GLOBAL CROSSING VENEZUELA INDUSTRIA DEMO VE
'190.217.3.160/29 ',
# 190217055002 CONEXION DIGITAL S.A.S. CO
'190.217.55.0/28 ',
# 190217099066 KUATRO COMUNICACIONES LTDA. CL
'190.217.99.64/30 ',
# 190230042084 Apolo -Gold-Telecom-Per AR
'190.230.42.0/23 ',
# 190232054239 PE-TDPERX6-LACNIC PE
'190.232.54.0/24 ',
# 190242119194 COLUMBUS NETWORKS COLOMBIA CO
'190.242.119.0/24 ',
# 191007039243 J Elvis Frota - ME (LaraNet) US
'191.7.32.0/21 ',
# 191007095192 MINAS NET LTDA ME US
'191.7.80.0/20 ',
# 191007113162 INFOSHOP Com�rcio e Servi�os Ltda US
'191.7.112.0/22 ',
# 191007142128 Ejmnet Tecnologia ltda US
'191.7.136.0/21 ',
# 191036192196 3WLINK INTERNET LTDA EPP US
'191.36.192.0/20 ',
# 191037130068 CyberTech Inform�tica Ltda. US
'191.37.128.0/21 ',
# 191096009031 Digital Energy Technologies Limited BR
'191.96.9.0/24 ',
# 191096011027 Digital Energy Technologies Chile SpA CL
'191.96.11.24/29 ',
# 191096013027 Digital Energy Technologies Chile SpA CL
'191.96.13.24/29 ',
# 191096015160 Digital Energy Technologies Limited BR
'191.96.15.0/24 ',
# 191096017029 Digital Energy Technologies Chile SpA CL
'191.96.17.24/29 ',
# 191096019022 Digital Energy Technologies Limited BR
'191.96.19.0/24 ',
# 191096020093 Digital Energy Technologies Limited BR
'191.96.20.0/24 ',
# 191096024222 Digital Energy Technologies Limited BR
'191.96.24.0/24 ',
# 191096026179 Digital Energy Technologies Limited BR
'191.96.26.0/24 ',
# 191096028031 Digital Energy Technologies Chile SpA CL
'191.96.28.24/29 ',
# 191096033028 Digital Energy Technologies Limited BR
'191.96.33.0/24 ',
# 191096051032 Digital Energy Technologies Limited US
'191.96.50.0/23 ',
# 191096099191 Digital Energy Technologies Limited BR
'191.96.99.0/24 ',
# 191096101004 Digital Energy Technologies Chile SpA CL
'191.96.0.0/16 ',
# 191102173006 Webline Services, S.A. HN
'191.102.128.0/18 ',
# 191126152197 TELEFONICA MOVIL DE CHILE S.A. CL
'191.124.0.0/14 ',
# 191222017098 Brasil Telecom S/A - Filial Distrito Federal US
'191.220.0.0/14 ',
# 191241036155 TECNET PROVEDOR DE ACESSO AS REDES DE COM. LTDA US
'191.241.32.0/21 ',
# 191242177114 Reale Tech Solu��es em Inform�tica Ltda US
'191.242.177.112/29 ',
# 191242233078 J E Provedor de Rede de Comunicacao Ltda US
'191.242.232.0/21 ',
# 191252092026 Locaweb Servi�os de Internet S/A US
'191.252.0.0/16 ',
# 192023022192 Schlumberger Limited US
'192.23.0.0/16 ',
# 192030089138 Tech Futures Interactive Inc. CA
'192.30.88.0/23 ',
# 192034080176 AxcelX Technologies LLC US
'192.34.80.0/21 ',
# 192040059147 Total Server Solutions L.L.C. TSS (NET-192-40-56-0-1) US
'192.40.56.0/22 ',
# 192055079166 Intel Corporation US
'192.55.80.0/23 ',
# 192071201006 Privat Kommunikation Sverige AB US
'192.71.201.0/24 ',
# 192071244014 EDIS GmbH SI
'192.71.244.0/24 ',
# 192075121046 Ontario Hydro CA
'192.75.121.0/24 ',
# 192075211207 North Park University US
'192.75.211.0/24 ',
# 192109031061 European Molecular Biology Laboratory DE
'192.109.31.0/24 ',
# 192110146047 PBT Communications, Inc. US
'192.110.144.0/22 ',
# 192117146110 EURONET IL
'192.117.128.0/19 ',
# 192118132159 TargetMatch Ltd. IL
'192.118.132.0/22 ',
# 192119014005 24 SHELLS 24SHELLS (NET-192-119-8-0-1) US
'192.119.8.0/21 ',
# 192119096002 Hostwinds LLC. US
'192.119.64.0/18 ',
# 192127094007 NCR Corporation US
'192.127.0.0/16 ',
# 192132091175 Telebit Corporation US
'192.132.91.0/24 ',
# 192154099058 GorillaServers, Inc. US
'192.154.96.0/20 ',
# 192154231117 Vivid Hosting VIVID-HOSTING-4 (NET-192-154-192-0-1) US
'192.154.192.0/18 ',
# 192156110170 GTECH Corporation US
'192.156.110.0/24 ',
# 192157053177 B2 Net Solutions Inc. CA
'192.157.48.0/20 ',
# 192160102164 Hextet Systems CA
'192.160.102.0/24 ',
# 192162026053 ES
'192.162.26.0/24 ',
# 192162034177 route object UA
'192.162.34.0/24 ',
# 192162140166 GlobalNet block 140 UA
'192.162.140.0/24 ',
# 192162242133 ORG-DL98-RIPE RU
'192.162.242.0/24 ',
# 192195080010 MEOW Global Networks, Inc. NET-MEOW-GLOBAL (NET-192-195-80-0-2) US
'192.195.80.0/28 ',
# 192200203005 Global Frag Networks US
'192.200.192.0/19 ',
# 192225102017 EARTHMETA MULTIMEDIA STUDIOS EMS-NET-US7 (NET-192-225-96-0-1) US
'192.225.96.0/20 ',
# 192225115175 MyServer.org, Inc. US
'192.225.112.0/20 ',
# 192228186004 TT DOTCOM SDN BHD MY
'192.228.128.0/17 ',
# 192230072025 Incapsula Inc US
'192.230.64.0/18 ',
# 192232162181 Elauwit Networks, LLC NTSC-ELAUW-PBPL-PKM1 (NET-192-232-162-0-1) US
'192.232.162.0/23 ',
# 192250242003 FEDERAL ONLINE GROUP LLC US
'192.250.240.0/20 ',
# 193000178016 MGNHost, 193.0.178.0 NL
'193.0.178.0/24 ',
# 193009028093 US
'193.9.28.0/24 ',
# 193017219016 KI-Services DE
'193.17.219.0/24 ',
# 193024030059 193.24.30.0/24 route UA
'193.24.30.0/24 ',
# 193024196152 Uzlovaya.net Ltd RU
'193.24.196.0/24 ',
# 193026217044 Memvds - vds/vps, colo and dedicated RU
'193.26.217.0/24 ',
# 193029187119 THCProjects RO
'193.29.187.0/24 ',
# 193035097112 JSC SvyazTeleKom, Magnitogorsk, Russia RU
'193.35.96.0/23 ',
# 193069193204 Ventelo DHCP Bodo NO
'193.69.0.0/16 ',
# 193070003144 OVH FR
'193.70.0.0/17 ',
# 193077043230 SiOL d.o.o. (Slovenia Online) SI
'193.77.0.0/18 ',
# 193077124085 Telekom Slovenije d.d. SI
'193.77.64.0/18 ',
# 193077152114 SiOL d.o.o. (Slovenia Online) SI
'193.77.128.0/18 ',
# 193084184025 RD Holding "Mira-1" LV
'193.84.184.0/24 ',
# 193092123005 INTERNET-SALES GR
'193.92.123.0/24 ',
# 193093048082 ISP Lis route UA
'193.93.48.0/22 ',
# 193093192013 GB
'193.93.192.0/24 ',
# 193093193135 net for depo40.ru RU
'193.93.193.0/24 ',
# 193093194094 net for depo40.ru RU
'193.93.194.0/24 ',
# 193093195041 net for depo40.ru RU
'193.93.195.0/24 ',
# 193106170033 Set Ltd. network/24 RU
'193.106.170.0/24 ',
# 193106185033 Bospor-Telecom LLC UA
'193.106.184.0/22 ',
# 193107202002 193-202 UA
'193.107.202.0/24 ',
# 193110022222 ISP AliceTelecom, Dnieprodzerzhinsk, UA UA
'193.110.20.0/22 ',
# 193111177051 IPBR RU
'193.111.176.0/22 ',
# 193124176230 Marosnet enterprise network RU
'193.124.176.0/20 ',
# 193138063146 T-2 d.o.o. Provider Aggregated Block SI
'193.138.32.0/19 ',
# 193150010088 GS Systems Ltd. announcement GB
'193.150.10.0/24 ',
# 193151013223 Alba subroute UA
'193.151.13.0/24 ',
# 193151240061 ISP STATUS UA
'193.151.240.0/24 ',
# 193169024142 Centrsvyaz CJSC Autonomous System RU
'193.169.24.0/23 ',
# 193169135133 CDC custumer network UA
'193.169.135.0/24 ',
# 193182144169 EDIS GmbH IL
'193.182.144.0/24 ',
# 193192037061 LDS-NET UA
'193.192.36.0/23 ',
# 193193068002 Gimnazjum nr 4 PL
'193.193.64.0/21 ',
# 193213080158 Telenor Norge xDSL customers NO
'193.212.0.0/14 ',
# 193238133091 route object RU
'193.238.132.0/22 ',
# 193242150235 B2B-Telecom Krasnodar Multiservices RU
'193.242.148.0/22 ',
# 193242208114 PartNet-Gostar Route IR
'193.242.208.0/24 ',
# 193254037098 clients ips ES
'193.254.37.0/24 ',
# 193254038038 clients ips ES
'193.254.38.0/24 ',
# 194000091184 SYNAPSE route object 90.0/23 UA
'194.0.90.0/23 ',
# 194008056244 X.netnetwork UA
'194.8.56.0/24 ',
# 194009050067 UkrUgmedia Ltd, Nikolaev, UA UA
'194.9.50.0/23 ',
# 194028051139 IWACOM SP. Z O.O. PL
'194.28.48.0/22 ',
# 194028208157 RU
'194.28.208.0/22 ',
# 194044138252 UARNet UA
'194.44.138.0/24 ',
# 194044171021 UARNet UA
'194.44.171.0/24 ',
# 194044220070 UARNet UA
'194.44.220.0/24 ',
# 194058120237 Reg.Ru Hosting RU
'194.58.120.0/24 ',
# 194063142115 NTX RU
'194.63.142.0/24 ',
# 194067178143 CLIENT-1276298786 RU
'194.67.128.0/18 ',
# 194067196054 Marosnet enterprise network RU
'194.67.196.0/22 ',
# 194067208162 Marosnet enterprise network RU
'194.67.208.0/20 ',
# 194071217050 Obenetwork AB SE
'194.71.216.0/23 ',
# 194074067062 FTIP003456668 Into Manchester Ltd GB
'194.72.0.0/14 ',
# 194078030171 SKYNETBE-CUSTOMERS BE
'194.78.0.0/16 ',
# 194080222002 University of Bedfordshire GB
'194.80.0.0/14 ',
# 194088105131 WORLDSTREAM-BLK-194-88-104-0 NL
'194.88.104.0/22 ',
# 194088142197 TIB436460 CoLo IE
'194.88.142.0/24 ',
# 194088143008 kgovps Services IE
'194.88.143.0/24 ',
# 194088224237 SHENTEL PL
'194.88.224.0/24 ',
# 194102108074 Altair Net ltd. RO
'194.102.108.0/24 ',
# 194103214083 International Programming Sweden AB SE
'194.103.214.0/24 ',
# 194126237086 KI-Services DE
'194.126.237.0/24 ',
# 194143251080 Contact Net Communication and Informatics LTD. HU
'194.143.224.0/19 ',
# 194177201075 University of Thessaly GR
'194.177.200.0/21 ',
# 194183173017 VOLZ unnumbered clients UA
'194.183.173.0/24 ',
# 194187096033 WEBZILLA NL
'194.187.96.0/22 ',
# 194187110038 KIEVLINE Route UA
'194.187.110.0/24 ',
# 194190026012 South Networks, Internet Service Provider RU
'194.190.26.0/24 ',
# 194190103193 Administration department of the Government of Saratov region RU
'194.190.103.0/24 ',
# 194190251072 Corporate domain for Dagestan State university RU
'194.190.251.0/24 ',
# 194208061078 TSCHANETT cable network AT
'194.208.61.0/24 ',
# 194208072176 Tschanett Rankweil cable network CPEs AT
'194.208.72.0/24 ',
# 194225035117 Amirkabir University IR
'194.225.32.0/22 ',
# 194242011011 NO-SERVETHEWORLD NO
'194.242.10.0/23 ',
# 194242121061 JSC SvyazTeleKom, Magnitogorsk, Russia RU
'194.242.120.0/22 ',
# 194247190248 Proxima Ltd. RU
'194.247.190.0/23 ',
# 194250241011 FR
'194.250.0.0/16 ',
# 195002195078 TK Region Telecom Ltd. RU
'195.2.195.0/24 ',
# 195008208130 Sohosted NL
'195.8.208.0/23 ',
# 195010210226 Vedekon Ltd. UA
'195.10.210.0/24 ',
# 195012049133 M247 LTD Manchester Infrastructure GB
'195.12.49.0/24 ',
# 195012173017 DFDS Lisco LT
'195.12.168.0/21 ',
# 195014031217 NG COMMUNICATIONS BE
'195.14.31.0/24 ',
# 195024142138 Apex NCC Reserve UA
'195.24.142.0/24 ',
# 195032106250 PPPoE Customers IP POOLs IT
'195.32.106.0/24 ',
# 195033246139 SOL-Customer-MIX TR
'195.33.246.0/24 ',
# 195034241085 Lipetsk DSL Dynamic-IP (PAT) RU
'195.34.240.0/21 ',
# 195035080058 BNP Paribas Bank Polska SA PL
'195.35.80.0/24 ',
# 195049206246 Swift Trace Ltd. RU
'195.49.200.0/21 ',
# 195054163060 SECOM-UA NL
'195.54.163.0/24 ',
# 195060077053 NOC Emergency Tel: 40 21 2074774 RO
'195.60.76.0/23 ',
# 195062070104 VARNOFF NET RU
'195.62.70.0/23 ',
# 195065152105 Swisscom AG CH
'195.65.0.0/16 ',
# 195068203179 Kyiv-150, Predslavenskaya str. 34b UA
'195.68.202.0/23 ',
# 195069185173 TNS block UA
'195.69.185.0/24 ',
# 195078126081 Penzenskaya Telephonnaya company open joint-stock company RU
'195.78.126.0/24 ',
# 195080140212 Comcor Service LLC UA
'195.80.140.0/24 ',
# 195088023226 Apanet ip address spase RU
'195.88.22.0/23 ',
# 195096075182 Astelit network RU
'195.96.64.0/19 ',
# 195098189178 2COM Co ltd. RU
'195.98.160.0/19 ',
# 195110059067 Hostinger International Ltd. GB
'195.110.58.0/23 ',
# 195114136030 Clients IP UA
'195.114.136.0/24 ',
# 195122250207 Sandy Info Ltd. RU
'195.122.250.0/24 ',
# 195123209104 LAYER6-NET-DC-1 195.123.208.0/21 LV
'195.123.208.0/21 ',
# 195140253065 VMIN CZ
'195.140.252.0/22 ',
# 195142092193 SOL-Customer-MIX TR
'195.142.92.0/24 ',
# 195142101194 Gelirler Bilgi Islem TR
'195.142.101.0/24 ',
# 195142206110 SOLNET TR
'195.142.206.0/24 ',
# 195155162080 SOLNET TR
'195.155.160.0/22 ',
# 195158008042 ISP UZ
'195.158.8.0/24 ',
# 195160216010 Monsterseweg 36 NL
'195.160.216.0/22 ',
# 195162004033 Olaf Haase IT-Services net DE
'195.162.4.0/23 ',
# 195181210203 Cloud Services CZ1 CZ
'195.181.208.0/20 ',
# 195191175244 Krastelecom Ltd RU
'195.191.174.0/23 ',
# 195200245209 PP Intertel.com UA
'195.200.244.0/23 ',
# 195208166170 LLC TK Telezon RU
'195.208.166.0/24 ',
# 195208220183 OOO Sirius-Project RU
'195.208.220.0/24 ',
# 195209058002 delegated for RECONN ISP, Ltd. ORG-RCNN1-RIPE RU
'195.209.32.0/19 ',
# 195211148115 Watson Home Network route UA
'195.211.148.0/24 ',
# 195219163068 Customers access -30 and BB internal use IT
'195.219.0.0/16 ',
# 195238116144 Zurbagan route UA
'195.238.116.0/24 ',
# 195238117200 Zurbagan route UA
'195.238.117.0/24 ',
# 195251252244 Athens University of Economics and Business GR
'195.251.248.0/21 ',
# 197048221248 TE Data EG
'197.48.0.0/13 ',
# 197135003122 Vodafone Egypt Mobile Users Pool EG
'197.134.0.0/15 ',
# 197136142005 SEKU Kitui KE
'197.136.142.0/24 ',
# 197148102148 IP addresses use by ADSL clients on BRAS for dynamic allocation on ppoe connections TG
'197.148.96.0/20 ',
# 197148122133 IP addresses use by ADSL clients on BRAS for dynamic allocation on ppoe connections TG
'197.148.120.0/22 ',
# 197178181039 SAFARICOM LTD KENYA KE
'197.176.0.0/14 ',
# 197189238180 C0262509814 ZA
'197.189.224.0/20 ',
# 197190011184 Used for Airtel Ghana Use GH
'197.190.0.0/16 ',
# 197195077164 Cairo 2G/3G expansion at NC GGSN EG
'197.195.0.0/17 ',
# 197206208099 POOL LIBRE DZ
'197.200.0.0/13 ',
# 197210025021 Reserved-for-WEB-APN-NAT NG
'197.210.24.0/22 ',
# 197210028034 Reserved-for-Enterprice-Internet-LAN-3GFW NG
'197.210.28.0/22 ',
# 197210044078 Reserved-for-3GFW-MSP NG
'197.210.44.0/22 ',
# 197210143242 Reserved-for-Enterprice-Internet-WAN NG
'197.210.143.0/24 ',
# 197210167014 Reserved-for-Enterprice-Internet-WAN NG
'197.210.167.0/24 ',
# 197210172137 ASSIGNED-FOR-OJOTA-FW-NAT NG
'197.210.172.0/23 ',
# 197210185246 Enterprise-Internet-Customers NG
'197.210.185.0/24 ',
# 197210216022 Enterprise Clients NG
'197.210.216.0/24 ',
# 197210226009 STATIC NAT FOR GPRS/WIMAX/CORPORATE NETWORK NG
'197.210.226.0/24 ',
# 197210227051 STATIC NAT FOR GPRS/WIMAX/CORPORATE NETWORK NG
'197.210.227.0/24 ',
# 197210247135 ENTERPRISE CLIENTS NG
'197.210.247.0/24 ',
# 197211045003 Globacom Limited NG
'197.211.32.0/19 ',
# 197220007206 Lusaka-WiMAX-Customers ZM
'197.220.4.0/22 ',
# 197220169150 Glo Mobile Ghana Telco GH
'197.220.169.0/24 ',
# 197228097125 Wifi-APN ZA
'197.228.64.0/18 ',
# 197229197034 GGSN-DTM ZA
'197.229.192.0/18 ',
# 197231141029 Residential customers of Wifly Gabon GA
'197.231.136.0/21 ',
# 197231178010 Frontier Optical Networks Ltd KE
'197.231.176.0/21 ',
# 197231200048 SOMTEL International SO
'197.231.200.0/22 ',
# 197234219051 4G, 3G and GPRS customers BJ
'197.234.219.0/24 ',
# 197237205078 Wananchi Online Limited KE
'197.237.0.0/16 ',
# 197239066107 MPLS host connectivity to MPBN and IT Network BF
'197.239.66.0/24 ',
# 197239080011 Dynamic assignment of IP to Orange-BF GPRS/EDGE/3G Internets users BF
'197.239.80.0/21 ',
# 197242107096 Dynamically Allocated to LAGOS LTE Customers NG
'197.242.106.0/23 ',
# 197246250033 Downstream virtual ISP which will use the assignment for its residential ADSL customers. EG
'197.246.0.0/16 ',
# 197249051253 TVCabo Mozambique MZ
'197.249.0.0/18 ',
# 197255062090 Cobranet Limited NG
'197.255.0.0/18 ',
# 197255201117 Dynamically assigned subnet GM
'197.255.200.0/21 ',
# 198002076035 Start Communications CA
'198.2.64.0/18 ',
# 198028069084 GlaxoSmithKline US
'198.28.64.0/18 ',
# 198029034119 Western Independent Networks, Inc. US
'198.29.32.0/20 ',
# 198044192011 EARTHMETA MULTIMEDIA STUDIOS US
'198.44.192.0/20 ',
# 198098183038 Fast Serv Networks, LLC FSNL-NET-1 (NET-198-98-180-0-1) US
'198.98.180.0/22 ',
# 198144176099 ColoCrossing CC-05 (NET-198-144-176-0-1) US
'198.144.176.0/20 ',
# 198148082083 Sharktech SHARKTECH-INC (NET-198-148-80-0-1) US
'198.148.80.0/20 ',
# 198154081061 B2 Net Solutions Inc. CA
'198.154.80.0/20 ',
# 198167223038 1337 Services LLC KN
'198.167.192.0/19 ',
# 198168027221 BAnQ CA
'198.168.27.0/24 ',
# 198254125228 Natural Wireless, LLC US
'198.254.112.0/20 ',
# 199019166042 Netspectrum Wireless Internet Solutions CA
'199.19.160.0/21 ',
# 199019183086 Home Communications Inc US
'199.19.176.0/21 ',
# 199027179046 CASCADELINK INC US
'199.27.176.0/22 ',
# 199048069203 Enzu Inc US
'199.48.68.0/22 ',
# 199048160069 Private Customer SC7011-199-48-160-64-27 (NET-199-48-160-64-1) US
'199.48.160.64/27 ',
# 199059169217 Tullahoma Utilities Authority US
'199.59.168.0/21 ',
# 199066088020 Yesup Ecommerce Solutions Inc. CA
'199.66.88.0/21 ',
# 199089055164 Private Customer CUSTOMER-VIRTUAL-MACHINES-3 (NET-199-89-55-128-1) US
'199.89.55.128/25 ',
# 199101185118 Md Fakhrul Islam ISLAM-199-101-185-116 (NET-199-101-185-116-1) US
'199.101.185.116/30 ',
# 199102071016 Route 256 ROUTE-256 (NET-199-102-68-0-1) US
'199.102.68.0/22 ',
# 199115096077 Sharktech SHARKTECH-INC (NET-199-115-96-0-1) US
'199.115.96.0/21 ',
# 199116104095 JAB Wireless, INC. US
'199.116.104.0/22 ',
# 199119112066 Allied Telecom Group, LLC US
'199.119.112.0/21 ',
# 199127120226 Promenade PROMENADE (NET-199-127-120-128-1) US
'199.127.120.128/25 ',
# 199167102198 Fibernet Direct US
'199.167.100.0/22 ',
# 199188105145 Qiyi Wan Luo Inc. 199-180-100-0 (NET-199-188-105-128-1) US
'199.188.105.128/26 ',
# 199193188084 NET TALK.COM INC. US
'199.193.188.0/22 ',
# 199195159011 Softsys Hosting US
'199.195.156.0/22 ',
# 199229235102 Enzu Inc US
'199.229.232.0/22 ',
# 199249223040 Quintex Alliance Consulting US
'199.249.223.0/24 ',
# 200000230018 Telefonica de Argentina AR
'200.0.224.0/19 ',
# 200003207039 Cable Onda PA
'200.3.200.0/21 ',
# 200007098014 HZ HOSTING LTD BG
'200.7.98.0/23 ',
# 200024159133 TRANSNEXA S.A. E.M.A. EC
'200.24.128.0/19 ',
# 200028176001 TELEF�NICA CHILE S.A. CL
'200.28.160.0/19 ',
# 200029191149 Telmex Servicios Empresariales S.A. CL
'200.29.176.0/20 ',
# 200029240015 NOVANET EC
'200.29.240.0/21 ',
# 200035152157 NWStack, LLC. US
'200.35.152.0/24 ',
# 200035155068 NWStack, LLC. US
'200.35.155.0/24 ',
# 200049042109 Divena Comercial Ltda. US
'200.49.42.104/29 ',
# 200052085099 Megacable Comunicaciones de Mexico, S.A. de C.V. MX
'200.52.80.0/20 ',
# 200054108054 Derco S.A. CL
'200.54.108.0/24 ',
# 200054166099 Master Martini Chile SPA CL
'200.54.166.96/29 ',
# 200059028031 Inversiones Apolo S.A. de C.V. HN
'200.59.24.0/21 ',
# 200060130162 JOSE RIXE TARAZON PE
'200.60.130.160/28 ',
# 200061221228 DATACENTER PACHECO AR
'200.61.208.0/20 ',
# 200066078179 Megacable Comunicaciones de Mexico, S.A. de C.V. MX
'200.66.72.0/21 ',
# 200068038030 Gobierno Regional de Antofagasta CL
'200.68.38.24/29 ',
# 200068117189 NSS S.A. AR
'200.68.112.0/20 ',
# 200072187075 ENTEL CHILE S.A. CL
'200.72.128.0/18 ',
# 200074006153 VTR BANDA ANCHA S.A. CL
'200.74.0.0/19 ',
# 200074236019 Dayco Telecom, C.A. VE
'200.74.224.0/20 ',
# 200076083161 Pegaso PCS, S.A. de C.V. MX
'200.76.80.0/20 ',
# 200077251062 Actinver SA de CV MX
'200.77.251.32/27 ',
# 200090179150 Telefonica Internet Empresas CL
'200.90.179.128/25 ',
# 200107012194 CORPORACION NACIONAL DE TELECOMUNICACIONES - CNT EP EC
'200.107.0.0/19 ',
# 200107253130 NEDETEL S.A. EC
'200.107.252.0/23 ',
# 200108035045 Telefonica Moviles PA
'200.108.35.40/29 ',
# 200119089148 Fundaci�n de Estudios Superiores CO
'200.119.89.144/29 ',
# 200119239252 Gtd Manquehue S.A. CL
'200.119.239.248/29 ',
# 200122252203 UNE EPM_(AUTONOMA)IE_ACP-3328061_DIGITAL_CONNECTIONS_TICS_SAS_(FX:126279675)_ECA_AAM37_C3 CO
'200.122.252.200/29 ',
# 200152094126 Matsunaka & Matsunaka Ltda - ME US
'200.152.80.0/20 ',
# 200152105208 MLS Projetos de Informatica US
'200.152.105.128/25 ',
# 200170076002 FRANCISCO SILVEIRA DE MOURA - ME US
'200.170.76.0/22 ',
# 200170176074 Companhia de Telecomunicacoes do Brasil Central US
'200.170.176.0/25 ',
# 200188192164 TIVIT TERCEIRIZA��O DE PROCESSOS, SERV. E TEC. S/A US
'200.188.192.0/20 ',
# 200196251089 Telefonica Data S.A. US
'200.196.224.0/19 ',
# 200204167042 TELEF�NICA BRASIL S.A US
'200.204.0.0/16 ',
# 200229193106 Telefonica Data S.A. US
'200.229.192.0/20 ',
# 201016238010 Banco BMG S.A. US
'201.16.238.0/24 ',
# 201033206246 Genius On Line Telecom. LTDA US
'201.33.192.0/20 ',
# 201076027029 CLARO S.A. US
'201.76.16.0/20 ',
# 201130011030 Grupo Empresarial Mexicano en Telecomunicaciones, S.A. de C.V. MX
'201.130.0.0/20 ',
# 201149087050 Megacable Comunicaciones de Mexico, S.A. de C.V. MX
'201.149.64.0/19 ',
# 201149100002 CRZNET TELECOM LTDA US
'201.149.100.0/22 ',
# 201162008111 Television Internacional, S.A. de C.V. MX
'201.162.8.0/21 ',
# 201162037183 Television Internacional, S.A. de C.V. MX
'201.162.32.0/20 ',
# 201162127178 Lafaiete Provedor de Internet e Telecomunic Ltda US
'201.162.64.0/18 ',
# 201166223194 Television Internacional, S.A. de C.V. MX
'201.166.192.0/19 ',
# 201172051135 Television Internacional, S.A. de C.V. MX
'201.172.48.0/20 ',
# 201172109190 Television Internacional, S.A. de C.V. MX
'201.172.96.0/20 ',
# 201172125041 Television Internacional, S.A. de C.V. MX
'201.172.125.0/24 ',
# 201173018043 Television Internacional, S.A. de C.V. MX
'201.173.0.0/16 ',
# 201175073186 Television Internacional, S.A. de C.V. MX
'201.175.64.0/19 ',
# 201175108243 Television Internacional, S.A. de C.V. MX
'201.175.96.0/19 ',
# 201216217109 NSS S.A. AR
'201.216.192.0/19 ',
# 201220025106 FINANCIERA PARAGUAYO JAPONESA S.A. PY
'201.220.25.0/24 ',
# 202005017076 HostUS US
'202.5.16.0/20 ',
# 202021180146 BIZ-DIST-STATIC MV
'202.21.180.0/24 ',
# 202038004015 IBSS Nepal Internet NP
'202.38.4.0/22 ',
# 202043109168 DTS Communication Technologies Corporation VN
'202.43.108.0/22 ',
# 202047112210 Airlink Communications Pvt. Ltd. IN
'202.47.112.0/21 ',
# 202050052010 Net Max Technologies NP
'202.50.52.0/24 ',
# 202051181034 FTTH customer1 BD
'202.51.181.0/24 ',
# 202051188170 FTTH customer1 BD
'202.51.188.0/24 ',
# 202058096038 Broadband KH
'202.58.96.0/24 ',
# 202058097198 Broadband KH
'202.58.97.0/24 ',
# 202059074190 Nexlinx ISP Pakistan PK
'202.59.74.0/24 ',
# 202060056195 GITN-NETWORK MY
'202.60.56.0/22 ',
# 202061038047 LINKdotNET Telecom Limited PK
'202.61.38.0/24 ',
# 202065196242 Diyixian.com Limited HK
'202.65.192.0/20 ',
# 202068178056 WebSatMedia TL
'202.68.178.0/24 ',
# 202068254099 IP4 Networks, Inc. KR
'202.68.224.0/19 ',
# 202069038068 Gerrys Information Technology (PVT) Ltd PK
'202.69.38.0/24 ',
# 202069046174 Gerrys Information Technology (PVT) Ltd PK
'202.69.46.0/24 ',
# 202069048090 4th Floor,Central Hotel Building, Mereweather Road Karachi PK
'202.69.48.0/24 ',
# 202069061131 4th Floor,Central Hotel Building, Mereweather Road Karachi PK
'202.69.61.0/24 ',
# 202072242022 Mongolian Railway Commercial Center - Railcom, MN
'202.72.242.0/24 ',
# 202073034201 Viewqwest Pte Ltd SG
'202.73.34.0/24 ',
# 202073038200 Viewqwest Pte Ltd SG
'202.73.38.0/24 ',
# 202073051102 Viewqwest Pte Ltd SG
'202.73.51.0/24 ',
# 202077021213 China Unicom Global HK
'202.77.21.0/24 ',
# 202078227033 ip range assigned for Cong ty TNHH Tin hoc Salan VN
'202.78.227.24/26 ',
# 202083172218 National Telecom Corporation PK
'202.83.172.0/24 ',
# 202084070003 X-Net Limited BD
'202.84.64.0/21 ',
# 202086153242 CTM MO
'202.86.153.0/24 ',
# 202087138026 Office des Postes et Telecommunications NC
'202.87.128.0/19 ',
# 202090040081 Bajaj Allianz Life Insurance Company Ltd IN
'202.90.40.0/24 ',
# 202091068226 SwiftMail Communications Limited IN
'202.91.68.0/24 ',
# 202091070136 SwiftMail Communications Limited IN
'202.91.70.0/24 ',
# 202091090236 SwiftMail Communications Limited IN
'202.91.90.0/24 ',
# 202091218096 ZTV CO.,LTD JP
'202.91.208.0/20 ',
# 202093230029 PT. Hipernet Indodata ID
'202.93.230.0/24 ',
# 202093231027 PT. Hipernet Indodata ID
'202.93.224.0/21 ',
# 202095200067 DATEC, Internet Service Provider PG
'202.95.192.0/20 ',
# 202099222142 sxsdifangshuiwuju CN
'202.99.192.0/19 ',
# 202101111132 CHINANET Fujian province network CN
'202.101.96.0/19 ',
# 202101149077 fujian telechnology supervise bureau CN
'202.101.149.64/26 ',
# 202121096033 ~{IO:#9z?4sQ'~} CN
'202.121.96.0/20 ',
# 202121178244 ~{IO:#E)Q'T:~} CN
'202.121.176.0/21 ',
# 202126118059 HAIonNet KR
'202.126.112.0/21 ',
# 202131103035 BlazeNet Ahmedabad Pool IN
'202.131.103.0/24 ',
# 202131115106 BLAZENET PVT. LTD IN
'202.131.96.0/19 ',
# 202131229250 Mobinet LLC MN
'202.131.229.0/24 ',
# 202131233202 Mobinet LLC MN
'202.131.233.0/24 ',
# 202133006114 Static Assigment (section# 6) for Corporate ID
'202.133.6.0/24 ',
# 202133060100 INTERNET SERVICE PROVIDER IN
'202.133.60.0/24 ',
# 202134011131 Robi Axiata Limited - Enterprise Services BD
'202.134.11.0/24 ',
# 202134095221 Diyixian.com Limited HK
'202.134.64.0/19 ',
# 202136245119 Planet Online Laos LA
'202.136.240.0/21 ',
# 202137004179 PT. LINKNET, ID
'202.137.0.0/20 ',
# 202139203130 CAT Telecom Public Company Limited TH
'202.139.192.0/19 ',
# 202141246166 Multinet Pakistan Pvt. Ltd. PK
'202.141.246.0/24 ',
# 202141255086 Multinet Pakistan Pvt. Ltd. PK
'202.141.255.0/24 ',
# 202142067030 Siti Cable Network Ltd IN
'202.142.67.0/24 ',
# 202142070136 Siti Cable Network Ltd IN
'202.142.70.0/24 ',
# 202142075004 Siti Cable Network Ltd IN
'202.142.75.0/24 ',
# 202142086251 Siti Cable Network Ltd IN
'202.142.86.0/24 ',
# 202142093165 Siti Cable Network Ltd IN
'202.142.93.0/24 ',
# 202142158243 Gerrys Information Technology (PVT) Ltd PK
'202.142.158.0/24 ',
# 202142172092 Multinet Pakistan Pvt. Ltd. PK
'202.142.172.0/24 ',
# 202143112245 Satcomm (Pvt.) Ltd. PK
'202.143.112.0/24 ',
# 202143113205 Satcomm (Pvt.) Ltd. PK
'202.143.113.0/24 ',
# 202148004026 D~NET Jakarta ID
'202.148.4.0/24 ',
# 202148021115 PT. Core Mediatech (DNET) ID
'202.148.21.0/24 ',
# 202150151066 PT. Comtronics Systems ID
'202.150.151.0/24 ',
# 202151160014 Netnam Corporation VN
'202.151.160.0/24 ',
# 202152040028 MERATUS JAYA IRON ID
'202.152.40.16/28 ',
# 202152045093 BANK PEMBANGUNAN DAERAH JAMBI ID
'202.152.45.88/29 ',
# 202152063005 BANK PEMBANGUNAN DAERAH SULAWESI TENGGARA ID
'202.152.63.0/29 ',
# 202152135006 PT Jembatan Citra Nusantara ID
'202.152.135.0/24 ',
# 202153041150 Excell Media Pvt Ltd IN
'202.153.41.0/24 ',
# 202153233043 Rekayasa Industri, PT ID
'202.153.232.0/21 ',
# 202158052212 Network Operations Center ID
'202.158.32.0/19 ',
# 202159036070 PT. IndoInternet ID
'202.159.32.0/21 ',
# 202164063227 MANSA IN
'202.164.63.0/24 ',
# 202165249074 Nayatel (Pvt) Ltd PK
'202.165.248.0/23 ',
# 202168150189 UNIT 6 ON 11/F TREASURE CTR HK
'202.168.148.0/22 ',
# 202168152152 Starry Network KR
'202.168.152.0/23 ',
# 202168157106 Airgenie Communications Private Limited IN
'202.168.156.0/22 ',
# 202168201123 EASPNET Inc. TW
'202.168.192.0/20 ',
# 202170201235 INDIRA MOBILE IN
'202.170.200.0/22 ',
# 202175124043 CTM MO
'202.175.124.0/24 ',
# 202179026132 MICOM-NETWORK-DSL MN
'202.179.24.0/21 ',
# 202181024172 Converged Communications Limited HK
'202.181.24.0/22 ',
# 202183032006 PLDT Global Point-of-Presence PH
'202.183.32.0/20 ',
# 202189237107 Tata Teleservices Maharashtra Ltd IN
'202.189.224.0/19 ',
# 203019032046 Chinatelecom Next Carrying Network backbone HK
'203.19.32.0/22 ',
# 203076109250 Link3 Technologies Ltd. BD
'203.76.109.0/24 ',
# 203083176028 Grameen CyberNet BD
'203.83.176.0/24 ',
# 203090144140 Daqing Zhongji Petroleum Communication CN
'203.90.128.0/17 ',
# 203092039037 CITYCOM NETWORKS PVT LTD IN
'203.92.39.0/24 ',
# 203101146134 Telstra Global Internet Services Network /24 Block TH
'203.101.146.0/24 ',
# 203101188147 Broadband Services PK
'203.101.188.0/24 ',
# 203101224137 UQConnect AU
'203.101.224.0/19 ',
# 203104192016 NHN Techorus Corp. JP
'203.104.192.0/19 ',
# 203109003119 HAIonNet KR
'203.109.0.0/19 ',
# 203110084202 delDSL Internet Pvt. Ltd. IN
'203.110.84.0/24 ',
# 203123047028 CITYCOM NETWORKS PVT LTD IN
'203.123.46.0/23 ',
# 203130002066 WiMax Clients PK
'203.130.0.0/22 ',
# 203134199096 Chandigarh IN
'203.134.199.0/24 ',
# 203135190002 Bandar Seri Iskandar MY
'203.135.190.0/23 ',
# 203145171136 ABTS DELHI, IN
'203.145.171.0/24 ',
# 203146217107 reassign to "Jaspal Company Limited" TH
'203.146.217.96/28 ',
# 203160052032 Cloudie Limited HK
'203.160.52.0/24 ',
# 203163234137 Hathway IP Over Cable Internet Access Service IN
'203.163.234.0/24 ',
# 203167017066 CUST-DIA-AKCTV KH
'203.167.17.0/24 ',
# 203174015138 PT. Orion Cyber Internet ID
'203.174.8.0/21 ',
# 203174086088 NewMedia Express Pte Ltd, Singapore Web Hosting Provider SG
'203.174.80.0/21 ',
# 203175072195 Nayatel (Pvt) Ltd PK
'203.175.72.0/24 ',
# 203187194011 YOU Broadband & Cable India Ltd. IN
'203.187.194.0/24 ',
# 203187233002 YOU Telecom India Pvt Ltd IN
'203.187.233.0/24 ',
# 203187238069 YOU Telecom India Pvt Ltd IN
'203.187.238.0/24 ',
# 203188255134 ISN Gulshan POP BD
'203.188.255.0/24 ',
# 203189088200 Departemen Energi dan Sumber Daya Mineral ID
'203.189.88.0/23 ',
# 203190033150 Help Line BD
'203.190.33.0/24 ',
# 203193132045 Software Technology Parks of India IN
'203.193.132.0/24 ',
# 203194100164 BROADBAND INTERNET SERVICE PROVIDER IN
'203.194.96.0/20 ',
# 203202215086 ITEC Hankyu Hanshin Co.,Ltd. JP
'203.202.192.0/19 ',
# 203202252102 Wintel POP broadband Customers BD
'203.202.252.0/22 ',
# 203212030103 Wancom (Pvt) Ltd. PK
'203.212.28.0/22 ',
# 203212227019 Hathway IP Over Cable Internet Access Service IN
'203.212.227.0/24 ',
# 203215168085 Pakistan Software Export Board PK
'203.215.168.0/24 ',
# 204000052012 NTT America, Inc. US
'204.0.0.0/14 ',
# 204012155201 Enmax Envision Inc. SHAWENV-BLOCK4 (NET-204-12-144-0-1) US
'204.12.144.0/20 ',
# 204013204125 Delaware County Intermediate Unit NET-DCIU-A (NET-204-13-204-0-1) US
'204.13.204.0/22 ',
# 204027197005 Intuitive Surgical, Inc. US
'204.27.197.0/24 ',
# 204042255036 NTT America, Inc. US
'204.42.0.0/16 ',
# 204052135126 SurfEasy Inc CA
'204.52.135.0/24 ',
# 204063214016 SCHUYLKILL INTERMEDIATE UNIT 29 US
'204.63.214.0/23 ',
# 204086017253 Hoffman Engineering US
'204.86.16.0/20 ',
# 204126132241 Howard Community College US
'204.126.132.0/23 ',
# 204194223130 AWeber Systems, Inc. US
'204.194.222.0/23 ',
# 205189037079 AlwaysON Internet US
'205.189.36.0/23 ',
# 205196181010 PHONOSCOPE PHONOSCOPE-NET-01 (NET-205-196-176-0-1) US
'205.196.176.0/20 ',
# 205201085141 Cebridge Connections US
'205.201.64.0/18 ',
# 205214240062 The Tri-County Telephone Association, Inc. US
'205.214.240.0/20 ',
# 205234159026 Server Central Network SCN-4 (NET-205-234-128-0-1) US
'205.234.128.0/17 ',
# 206051035177 AlwaysON Internet US
'206.51.35.0/24 ',
# 206051239081 NOC4Hosts Inc. US
'206.51.224.0/20 ',
# 206072213147 Foothills Rural Telephone Cooperative Corporation, Inc. US
'206.72.212.0/22 ',
# 206123095018 Colo4, LLC COLO4-BLK1 (NET-206-123-64-0-1) US
'206.123.64.0/18 ',
# 206123140006 Secure Internet LLC SECURE-INTERNET (NET-206-123-128-0-1) US
'206.123.128.0/19 ',
# 206125078244 Alternate Access Inc. US
'206.125.64.0/19 ',
# 206174249238 GigaMonster US
'206.174.224.0/19 ',
# 206183111025 Web Werks WEBWRKS-PHLA1 (NET-206-183-104-0-1) US
'206.183.104.0/21 ',
# 206214155142 OPTICALTEL HCTRL (NET-206-214-128-0-1) US
'206.214.128.0/19 ',
# 206217013156 Sweetwater Television Company US
'206.217.0.0/20 ',
# 206217090090 Transbeam, Inc. TRNS (NET-206-217-64-0-1) US
'206.217.64.0/18 ',
# 206248038102 JAB Wireless, INC. US
'206.248.32.0/19 ',
# 207059032226 PaeTec Communications, Inc. PAETECCOMM (NET-207-59-0-0-1) US
'207.59.0.0/16 ',
# 207154195112 DigitalOcean, LLC US
'207.154.192.0/18 ',
# 207178017173 Wolfenet US
'207.178.0.0/18 ',
# 207183166136 Silver Star Communications SILVERSTAR-2 (NET-207-183-160-0-1) US
'207.183.160.0/19 ',
# 207188073155 Energy Probe Research Foundation PATHWAYCOMM-GWENERGY (NET-207-188-73-128-1) US
'207.188.73.128/26 ',
# 207190074133 Vernon Telephone Cooperative, Inc. MWT-VERNON-VLAN800-001 (NET-207-190-74-128-1) US
'207.190.74.128/27 ',
# 207192236206 NPG Cable, INC NPG-STJOSEPH-MO-HSD-NETBLK-6 (NET-207-192-236-0-1) US
'207.192.236.0/24 ',
# 207228078231 TELUS Communications Inc. CA
'207.228.64.0/18 ',
# 208067001079 Wholesale Data Center, LLC WSDC-BLOCK1 (NET-208-67-0-0-1) US
'208.67.0.0/21 ',
# 208068059214 Royell Communications, Inc. 300-BROADBAND-NETWORK (NET-208-68-59-192-1) US
'208.68.59.192/26 ',
# 208074033114 Believe Wireless, LLC. US
'208.74.32.0/21 ',
# 208076172031 CIFNet, Inc. US
'208.76.168.0/21 ',
# 208077125108 Appalachian Wireless APPWIRELESS-RIM-DATA (NET-208-77-125-0-1) US
'208.77.125.0/22 ',
# 208080144242 Royell Communications, Inc. VDB-VIRDEN-BROADBAND-NETWORK (NET-208-80-144-192-1) US
'208.80.144.192/26 ',
# 208080149062 Royell Communications, Inc. ROYELLNET (NET-208-80-144-0-1) US
'208.80.144.0/21 ',
# 208086060005 CITY OF SCOTTSBURG US
'208.86.56.0/21 ',
# 208091064003 Paradise Networks LLC US
'208.91.64.0/21 ',
# 208096169058 4RWEB, Inc US
'208.96.160.0/20 ',
# 208111057142 DR Fortress, LLC US
'208.111.56.0/21 ',
# 208114092057 James Cable, LLC US
'208.114.64.0/19 ',
# 208157185149 Airstream Communications, LLC AS-BLK-4 (NET-208-157-160-0-1) US
'208.157.160.0/19 ',
# 209033120245 Cebridge Connections CEBRIDGE (NET-209-33-0-0-1) US
'209.33.0.0/17 ',
# 209034155094 Cascade Networks Incorporated US
'209.34.128.0/19 ',
# 209051162032 Hurricane Electric, Inc. HURRICANE-5 (NET-209-51-160-0-1) US
'209.51.160.0/19 ',
# 209066119150 Zayo Bandwidth US
'209.66.64.0/18 ',
# 209090225250 Wowrack.com US
'209.90.224.0/20 ',
# 209123234023 Net Access Corporation US
'209.123.0.0/16 ',
# 209131249086 Summit Broadband ORL-209-131-240-0-1 (NET-209-131-240-0-1) US
'209.131.240.0/20 ',
# 209133066214 Zayo Bandwidth US
'209.133.0.0/17 ',
# 209134005006 Worldsite Networks WORLDSITE97 (NET-209-134-0-0-1) US
'209.134.0.0/19 ',
# 209145087212 LUMOS Networks, Inc. US
'209.145.64.0/19 ',
# 209150146075 For ESS Use PK
'209.150.146.0/24 ',
# 209160113040 Paradise Networks LLC US
'209.160.112.0/20 ',
# 209182117216 Twin Valley Communications Inc US
'209.182.112.0/20 ',
# 209217208099 Northland Communications US
'209.217.192.0/19 ',
# 209222010211 Vultr Holdings, LLC NET-209-222-10-0-24 (NET-209-222-10-0-1) US
'209.222.10.0/24 ',
# 209222014098 Secure Internet LLC NET-209-222-14-64-26 (NET-209-222-14-64-1) US
'209.222.14.64/26 ',
# 209222083237 Barracuda Networks, Inc. US
'209.222.80.0/21 ',
# 209234248032 BandCon BANDCON (NET-209-234-240-0-1) US
'209.234.240.0/20 ',
# 209240181031 NetWest Online, Inc. US
'209.240.160.0/19 ',
# 210004114171 COMCLARK CABLE INTERNET PAMPANGA 104 PH
'210.4.114.0/21 ',
# 210016089093 SP INTERNET TECHNOLOGIES PRIVATE LTD IN
'210.16.88.0/22 ',
# 210016103075 scalebuzz solutions pvt ltd IN
'210.16.100.0/22 ',
# 210016120063 HostUS SG
'210.16.120.0/24 ',
# 210018169191 Hathway Cable and Datacom Pvt Ltd IN
'210.18.169.0/24 ',
# 210018171233 Hathway Cable and Datacom Pvt Ltd IN
'210.18.171.0/24 ',
# 210018184020 Hathway Cable and Datacom Pvt Ltd IN
'210.18.184.0/24 ',
# 210019007050 TT DOTCOM SDN BHD MY
'210.19.0.0/16 ',
# 210023025198 SuperInternet (Singapore) Pte Ltd, SBO(I) Licensee, SG
'210.23.0.0/19 ',
# 210035171069 ~{=-Nw=LS}:M?FQP US
'210.35.168.0/22 ',
# 210038001131 ~{9c6+J!=LS}:M?FQP US
'210.38.0.0/22 ',
# 210056003086 Commission for Science and Technology for PK
'210.56.3.0/24 ',
# 210056052113 Sun Network (Hong Kong) Limited HK
'210.56.52.96/27 ',
# 210068095245 Digital United Inc. TW
'210.68.0.0/16 ',
# 210097120055 Korea Telecom KR
'210.97.120.0/22 ',
# 210097252003 KINX KR
'210.97.240.0/20 ',
# 210177057130 STELLA FOOTWEAR CO LTD HK
'210.177.0.0/17 ',
# 210209089100 NWT CRS Dynamic Pool HK
'210.209.89.0/24 ',
# 210209098010 NWT CRS Dynamic Pool HK
'210.209.98.0/24 ',
# 210213226003 2041216_CARDINAL SANTOS MEDICAL CTR PH
'210.213.226.0/24 ',
# 210236127219 Japan Network Information Center JP
'210.236.0.0/14 ',
# 210245033144 Dai IP tinh cho khach hang xDSL VN
'210.245.32.0/21 ',
# 211033051118 SK Broadband Co Ltd KR
'211.33.0.0/17 ',
# 211034219223 Korea Telecom KR
'211.34.216.0/22 ',
# 211144217066 Shanghai Data Solution Co., Ltd. CN
'211.144.217.0/24 ',
# 212001109195 (ZHITOMIR+CHERNIGOV)-INFOCOM UA
'212.1.108.0/22 ',
# 212003193072 LMT-3G LV
'212.3.192.0/23 ',
# 212008244035 ZOMRO-NL NL
'212.8.244.0/24 ',
# 212013113014 GK "Intaleks" RU
'212.13.96.0/19 ',
# 212016070050 Farhang Azma Communications Company IR
'212.16.70.0/24 ',
# 212019017213 Solid network in khabarovsk RU
'212.19.0.0/19 ',
# 212022086114 LLC Perepelka RU
'212.22.86.0/24 ',
# 212022201090 for Freenet customers and infrastructure #4 UA
'212.22.201.0/24 ',
# 212024099122 LT-RACKRAY LT
'212.24.96.0/20 ',
# 212026236004 OJSC Rostelecom, Ryazan Branch RU
'212.26.236.0/24 ',
# 212030058002 Continuum LB
'212.30.32.0/19 ',
# 212034004233 Dialup Pool JO
'212.34.0.0/19 ',
# 212034039230 ZAO "CentrInform" RU
'212.34.32.0/20 ',
# 212036194045 IDM LB
'212.36.194.0/24 ',
# 212037113198 SE-STADSNAT SE
'212.37.96.0/19 ',
# 212042215244 GNC Alfa Retail AM
'212.42.192.0/19 ',
# 212043103055 Quipo Internet Provider by Micso s.r.l. IT
'212.43.96.0/20 ',
# 212049083006 IP MPLS BUSINESS VPN POOL KE
'212.49.83.0/24 ',
# 212049084102 BROADBAND ADSL NETWORK KE
'212.49.84.0/24 ',
# 212057038007 Cloud services SK
'212.57.38.0/24 ',
# 212071247199 Linode GB
'212.71.244.0/22 ',
# 212074202084 inteleca Rubtsovsk branch network RU
'212.74.192.0/19 ',
# 212086109070 NL
'212.86.109.0/24 ',
# 212086115069 ZOMRO-NL NL
'212.86.115.0/24 ',
# 212087162203 Element broadband pool in Snegnoe UA
'212.87.162.0/23 ',
# 212087177097 TOV Telecommunication Company Orion UA
'212.87.177.0/24 ',
# 212087178066 TOV Telecommunication Company Orion UA
'212.87.178.0/24 ',
# 212092105077 Amsterdam Residential Television and Internet NL
'212.92.104.0/21 ',
# 212092112031 Amsterdam Residential Television and Internet NL
'212.92.112.0/21 ',
# 212092121017 Amsterdam Residential Television and Internet NL
'212.92.120.0/22 ',
# 212092124011 Amsterdam Residential Television and Internet NL
'212.92.124.0/23 ',
# 212096076187 Mobile Services KZ
'212.96.76.0/24 ',
# 212096079191 Mobile Services KZ
'212.96.79.0/24 ',
# 212100204196 ARabian advanced systems SA
'212.100.192.0/19 ',
# 212109136187 INFRA-AW PL
'212.109.128.0/20 ',
# 212110088085 Blizoo DOOEL Skopje MK
'212.110.88.0/24 ',
# 212111041142 Linode GB
'212.111.40.0/22 ',
# 212116113183 Prometey Ltd netblock RU
'212.116.96.0/19 ',
# 212122089226 Penkiu kontinentu komunikaciju centras, LTD LT
'212.122.88.0/22 ',
# 212126104067 ALSARD FIBER Co. for Internet Fiber and Optical Cable Services /Ltd. IQ
'212.126.104.0/24 ',
# 212126113189 ALSARD FIBER Co. for Internet Fiber and Optical Cable Services /Ltd. IQ
'212.126.113.0/24 ',
# 212152051074 DigitOne Podolsk RU
'212.152.32.0/19 ',
# 212186060049 Telesystem Tirol AT
'212.186.0.0/17 ',
# 212188244123 Reserved for DSL Customer connectivity in Claranet UK GB
'212.188.128.0/17 ',
# 212192056002 Omsk State University RU
'212.192.32.0/19 ',
# 212199061007 HQserv_networks IL
'212.199.61.0/24 ',
# 212220030010 JSC "Uralsviazinform" RU
'212.220.24.0/21 ',
# 212237003039 Aruba S.p.A. - Cloud Services Farm2 IT
'212.237.0.0/18 ',
# 212238208212 XS4ALL Internet BV NL
'212.238.0.0/16 ',
# 212252082023 TR-SOL-CGN-Kartal TR
'212.252.82.0/24 ',
# 212252083077 TR-SOL-CGN-Kartal TR
'212.252.83.0/24 ',
# 212253115152 SOLNET-BB-Rezerve TR
'212.253.115.0/24 ',
# 213005224154 LTD Objedinennaja Setevaja Kompanija RU
'213.5.224.0/23 ',
# 213006147138 Palestine Telecommunications Company (PALTEL) PS
'213.6.144.0/21 ',
# 213014193041 SOL-DSL TR
'213.14.193.0/24 ',
# 213032015057 OVH Static IP FR
'213.32.0.0/17 ',
# 213052129170 Linode GB
'213.52.128.0/22 ',
# 213052200114 TeleCity Group Customer - King & Shaxson Asset Management Limited GB
'213.52.192.0/18 ',
# 213080097157 Static corporate network SE
'213.80.96.0/19 ',
# 213080136082 Small corporate clients of VTT in VGD, part III RU
'213.80.136.0/22 ',
# 213087154164 Mobile subscribers pool RU
'213.87.144.0/20 ',
# 213087163033 Mobile subscribers pool RU
'213.87.160.0/22 ',
# 213088010035 Net By Net Holding LLC RU
'213.88.0.0/17 ',
# 213108183052 UA
'213.108.183.0/24 ',
# 213109014173 Lipetskie Kabelnie Seti LLC RU
'213.109.0.0/20 ',
# 213111073103 Bilink LLC UA
'213.111.72.0/22 ',
# 213124171105 SSHN - Telemann Nijmegen NL
'213.124.160.0/20 ',
# 213126062114 Minibrew NL
'213.126.0.0/18 ',
# 213136085150 Contabo GmbH DE
'213.136.84.0/23 ',
# 213136087096 Contabo GmbH DE
'213.136.86.0/23 ',
# 213136105062 ISP Cote d'Ivoire CI
'213.136.104.0/22 ',
# 213137244016 AS25347 CENTRETRANSTELECOM retail network in Smolensk RU
'213.137.240.0/21 ',
# 213138064082 ZAO "Electro-Com Don" RU
'213.138.64.0/22 ',
# 213151184225 France Citevision FR
'213.151.160.0/19 ',
# 213152162015 Global Layer B.V. NL
'213.152.162.0/24 ',
# 213156122093 Public IPs PL
'213.156.122.0/24 ',
# 213157039090 Kazakhstan KZ
'213.157.39.0/24 ',
# 213163181011 PROVIDER Local Registry FR
'213.163.160.0/19 ',
# 213169045171 Spectrum NET Ltd. BG
'213.169.32.0/19 ',
# 213180193089 Yandex enterprise network RU
'213.180.193.0/24 ',
# 213180204089 Yandex enterprise network RU
'213.180.204.0/24 ',
# 213181052075 Belgacom Hosting 52 BE
'213.181.32.0/19 ',
# 213181205121 Webenlet Kft. HU
'213.181.205.0/24 ',
# 213183056022 EDIS infrastructure RU
'213.183.56.0/24 ',
# 213183057030 EDIS infrastructure RU
'213.183.57.0/24 ',
# 213183101075 TomTelecom, ISP in Tomsk and Tomsk region RU
'213.183.96.0/20 ',
# 213184183173 Dialup & ADSL / Awalnet SA
'213.184.160.0/19 ',
# 213184226068 FE "ALTERNATIVNAYA ZIFROVAYA SET" BY
'213.184.226.0/24 ',
# 213184248225 FE "ALTERNATIVNAYA ZIFROVAYA SET" BY
'213.184.248.0/24 ',
# 213186202176 VEGA TG UA
'213.186.192.0/19 ',
# 213192078056 TASK Academic Computer Centre PL
'213.192.64.0/20 ',
# 213196053028 RU
'213.196.52.0/22 ',
# 213211138045 EDPNET-StaticRange NL
'213.211.136.0/21 ',
# 213212059124 Viasat Satellite Services AB SE
'213.212.0.0/18 ',
# 213218198130 Gamma Telecom Holdings Ltd GB
'213.218.192.0/20 ',
# 213219038126 Linode-2 GB
'213.219.36.0/22 ',
# 213233057135 PROVIDER Local Registry IT
'213.233.0.0/18 ',
# 213233088104 MobiFon S.A. RO
'213.233.88.0/24 ',
# 213234123066 Thor7 Allcoation from Telenor Norge NO
'213.234.64.0/18 ',
# 213238166132 SPDNet Telekomunikasyon A.S. TR
'213.238.166.0/24 ',
# 213238192251 CUSTOMERS-OWNIT-SE SE
'213.238.192.0/18 ',
# 213240198115 Blizoo Media and Broadband EAD BG
'213.240.192.0/20 ',
# 213247064026 ROUTIT-KLIKSAFE NL
'213.247.64.0/24 ',
# 213248062004 Hosting and Colocation Services RU
'213.248.62.0/24 ',
# 213249226028 Address pool for Karoo ADSL users GB
'213.249.192.0/18 ',
# 216010002110 Colocation America Corporation US
'216.10.0.0/19 ',
# 216020131103 Community Antenna Service, Inc. US
'216.20.128.0/20 ',
# 216037080226 WorldSpice Technologies US
'216.37.64.0/19 ',
# 216047193135 WideOpenWest Finance LLC US
'216.47.192.0/19 ',
# 216049156252 SHREWSBURY ELECTRIC & COMMUNITY CABLE SHREWS-NET-CABLE4 (NET-216-49-144-0-2) US
'216.49.144.0/20 ',
# 216050230125 XO Communications ALGX-KVX-BLK3 (NET-216-50-0-0-1) US
'216.50.0.0/15 ',
# 216053157120 MPInet MPRD-MPINET (NET-216-53-128-0-1) US
'216.53.128.0/17 ',
# 216069156008 GoDaddy.com, LLC US
'216.69.128.0/18 ',
# 216082210179 Grande Communications AUSTIN HUB 1 Static GRANDECOM-MARKET02-01 (NET-216-82-210-0-1) US
'216.82.210.0/24 ',
# 216097072079 CoreSpace, Inc. US
'216.97.0.0/17 ',
# 216117177005 Advanced Internet Technologies, Inc. US
'216.117.128.0/18 ',
# 216131018096 Co-Mo Comm Inc US
'216.131.16.0/21 ',
# 216137007163 The Bahamas Telecommunications Company, Ltd. BS
'216.137.0.0/20 ',
# 216138021160 Adams NetWorks, Inc. ADNW-NET (NET-216-138-0-0-1) US
'216.138.0.0/18 ',
# 216138238125 CSTConsultantsInc. BLK-216-138-238-96-127 (NET-216-138-238-96-1) US
'216.138.238.96/27 ',
# 216146125076 Vision Net, Inc. VSNT (NET-216-146-96-0-1) US
'216.146.96.0/19 ',
# 216158229139 Interserver, Inc US
'216.158.224.0/20 ',
# 216171016123 One North(Formerly known as Campus Connection) ILURCCO (NET-216-171-16-0-1) US
'216.171.16.0/22 ',
# 216186159131 Wide Open West FL-PANA (NET-216-186-159-0-1) US
'216.186.159.0/24 ',
# 216196079248 Polar Communications US
'216.196.64.0/19 ',
# 216215124166 Logix NETBLK-LOGIXCOM3 (NET-216-215-64-0-1) US
'216.215.64.0/18 ',
# 216239090019 VIF Internet CA
'216.239.64.0/19 ',
# 216241142019 Cablenet Delegation CY
'216.241.128.0/19 ',
# 216246049012 ColoCrossing SCNET-216-246-49-0-24 (NET-216-246-49-0-1) US
'216.246.49.0/24 ',
# 216246108028 ColoCrossing SCNET-216-246-108-0-24 (NET-216-246-108-0-1) US
'216.246.108.0/24 ',
# 216249079140 Smithville Telephone Company DSL BLUT-216-249-72-0 (NET-216-249-72-0-1) US
'216.249.72.0/21 ',
# 216252126072 Yahoo! Inc. US
'216.252.96.0/19 ',
# 217010110117 FTTH Customers Tanum SE
'217.10.96.0/19 ',
# 217010204238 MobiFon S.A. RO
'217.10.204.0/24 ',
# 217012056166 NetLink, Vlkanova SK
'217.12.48.0/20 ',
# 217015205078 Internet services in Russia, Kaluga region, Obninsk RU
'217.15.192.0/20 ',
# 217019050082 LINKBYNET - Mutualized hosting FR
'217.19.50.0/24 ',
# 217019216244 Aggregate for ISP Interdnestrcom. MD
'217.19.216.0/24 ',
# 217020190033 W NET ISP UA
'217.20.190.0/24 ',
# 217028219220 Moscow, Russian Federation RU
'217.28.216.0/22 ',
# 217029053082 Moscow, Russian Federation RU
'217.29.52.0/22 ',
# 217061018077 Aruba S.p.A. - CLoud Services UK GB
'217.61.16.0/21 ',
# 217061120018 Aruba S.p.A. - Cloud Services Farm1 IT
'217.61.120.0/21 ',
# 217064042213 CYBERGHOST SRL FI
'217.64.32.0/20 ',
# 217064113196 M247 Ltd Milan Infrastructure IT
'217.64.113.0/24 ',
# 217064127164 M247 LTD Vienna Infrastructure AT
'217.64.127.0/24 ',
# 217066221164 FANAPTELECOM Whole customers network address space assignment IR
'217.66.192.0/19 ',
# 217070028155 Informational-measuring systems Ltd. RU
'217.70.16.0/20 ',
# 217070253127 dragonet.es ES
'217.70.253.0/24 ',
# 217071219066 Configo Systems Gesellschaft fuer Systemloesungen mbH DE
'217.71.216.0/21 ',
# 217075204006 Sberbanka Strosmajerova BA
'217.75.204.0/24 ',
# 217100076074 V.O.F. Hotel t Gemeentehuis Bedum NL
'217.100.0.0/17 ',
# 217103119158 Ziggo WifiSpots 5 between CMTS and cablemodems NL
'217.103.0.0/17 ',
# 217107106001 ROSTELECOM NETS RU
'217.107.106.0/24 ',
# 217107124045 ROSTELECOM NETS RU
'217.107.124.0/24 ',
# 217107126159 ROSTELECOM NETS RU
'217.107.126.0/24 ',
# 217107127189 ROSTELECOM NETS RU
'217.107.127.0/24 ',
# 217116153087 Yurga branch RU
'217.116.152.0/21 ',
# 217119082014 JSC "ER-Telecom Holding" Yekaterinburg branch RU
'217.119.80.0/22 ',
# 217126005224 Telefonica de Espana SAU (NCC#2001038578) ES
'217.126.0.0/16 ',
# 217149203242 PROVIDER LOCAL REGISTRY NL
'217.149.192.0/19 ',
# 217149240162 Artnet PL
'217.149.240.0/24 ',
# 217151098011 GB
'217.151.98.0/24 ',
# 217169221130 Milsped IP Network in Belgrade RS
'217.169.220.0/22 ',
# 217170254170 Petiak System PPPOE Clients IR
'217.170.254.0/23 ',
# 217172120018 Asiatech IPv4 Route IR
'217.172.112.0/20 ',
# 217172230132 Multimedia Polska S. A. PL
'217.172.224.0/19 ',
# 217196011126 "Unitron Systems & Development Ltd" GB
'217.196.0.0/20 ',
# 217196111178 LLC Sviaz-KTV Network pool-2 RU
'217.196.96.0/20 ',
# 219082152175 WASU-BB CN
'219.82.152.0/24 ',
# 219090087091 Skycable Central CATV Inc. PH
'219.90.84.0/22 ',
# 219090104242 Rajeshnet IN
'219.90.104.0/24 ',
# 219091140235 YOU Telecom India Pvt Ltd IN
'219.91.140.0/24 ',
# 219091159221 YOU Telecom India Pvt Ltd IN
'219.91.159.0/24 ',
# 219091183035 YOU Telecom India Pvt Ltd IN
'219.91.183.0/24 ',
# 219091210198 YOU Telecom India Pvt Ltd IN
'219.91.210.0/24 ',
# 219091251117 YOU Telecom India Pvt Ltd IN
'219.91.251.0/24 ',
# 219091254058 YOU Telecom India Pvt Ltd IN
'219.91.254.0/24 ',
# 219091255178 YOU Telecom India Pvt Ltd IN
'219.91.255.0/24 ',
# 220174236209 JieFang node Fttx+LAN user segmenet CN
'220.174.236.0/24 ',
# 220231206005 ShenZhenRunXunShuJuTongXinYouXianGongSi CN
'220.231.192.0/18 ',
# 220233015145 Exetel ADSL Users AU
'220.233.0.0/17 ',
# 220247166121 220.247.166.0/24 BTS BD
'220.247.166.0/24 ',
# 222165205204 PT Net2Cyber Indonesia ID
'222.165.205.0/24 ',
# 223024064071 GPRS/3G TH
'223.24.64.0/16 ',
# 223026250019 LX KR
'223.26.128.0/17 ',
# 223130029053 Fastway Transmission Private Limited IN
'223.130.29.0/24 ',
# 223179129170 BCL WEST,2nd Floor, Spectrum Tower, Mindspace, Malad, Mumbai IN
'223.179.128.0/19 ',
	);
}
?>
