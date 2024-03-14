<?php
/*
Plugin Name: International Namedays
Description: This plugin displays namedays for different countries.
Author: kgyt
Author URI: http://kgyt.hu/
Version: 2.3
Plugin URI: http://kgyt.hu/faq/namedays/

Keywords:
névnap (hu), imieniny (pl), namnsdag (se), svátek (jmeniny) (cz), meniny (sk),
navnedag (no), navnedag (dk), nameday (en)

--------------------------------------------------------------------------------
Usage
--------------------------------------------------------------------------------
Usage in templates

kgyt_nameday( [DISPLAY], [COUNTRY], [TIMESTAMP] );

display (boolean) - if true (default) the nameday is visible
country (string)  - select two letter countrycode (hu, pl, no, se, dk, cz, sk)
timestamp (int)   - set the date or timezone
--------------------------------------------------------------------------------
Usage in posts

Print today's Hungarian nameday:
<!-- kgyt_nameday -->

or

Print today's nameday from special country:
<!-- kgyt_nameday COUNTRY -->

or

Print Hungarian nameday of special time:
<!-- kgyt_nameday TIMESTAMP -->

or

Print customized nameday:
<!-- kgyt_nameday COUNTRY TIMESTAMP -->

country (string)  - select two letter countrycode (hu, pl, no, se, dk, cz, sk)
timestamp (int)   - set the date or timezone
--------------------------------------------------------------------------------
Examples

Print today's Hungarian nameday:
<?php kgyt_nameday(); ?>

Get yesterday Swedish nameday:
<?php
	$nameday = kgyt_nameday( false, 'se', time() - ( 3600 * 24 ) );
	echo $nameday;
?>
--------------------------------------------------------------------------------
Installation to WordPress (http://wordpress.org):

1. Upload plugin to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Edit your theme's php files and insert the code above
--------------------------------------------------------------------------------
License

This program is free software. Public Domain.
You can use this file with WordPress or any other compatible software.
--------------------------------------------------------------------------------
Changelog

2.3 Sunday 20. July, 2014.
     Compatibility update

2.2 Wednesday 18. August, 2010.
     Bugfix (Czech nameday for 18. August)

2.1 Thursday 17. September, 2009.
     Bugfix (Hungarian nameday for 17. September)

2.0: Wednesday 8. July, 2009.
     Second release (namedays in posts)

1.1: Saturday 4. April, 2009.
     New countries (cz, sk, dk, no)

1.0: Friday 3. April, 2009.
     First release (hu, pl, se)
--------------------------------------------------------------------------------
*/

function kgyt_nameday(
	$display = true, // If you want use without echo, use false
	$country = 'hu', // Select your country (if installed...)
	$timestamp = 0   // Set up date of the nameday (timestamp)
	) {
	// Initialize time variables
	if( $timestamp === 0 ) {
		$timestamp = time();
	};
	$namedaytime = $timestamp - 0;
	$calendar = array(
		'month' => date( 'm', $namedaytime ) - 0,
		'day'   => date( 'd', $namedaytime ) - 0,
		'leap'  => date( 'L', $namedaytime )
	);
	// Set country and initialize
	if( ( $country === 'pl' ) || ( $country === 'sk' ) || ( $country === 'cz' ) || ( $country === 'se' ) || ( $country === 'no' ) ) {
		// No special leap year
		//
		// Polak, Węgier, dwa bratanki, i do szabli, i do szklanki
		// „Lengyel, magyar – két jó barát, együtt harcol, s issza borát.”
	} elseif( ( $country === 'hu' ) || ( $country === 'dk' ) ) {
		// Special leap year (leap day is: 24 Feb)
		if( !$calendar[ 'leap' ] && ( $calendar[ 'month' ] === 2 ) && ( $calendar[ 'day' ] > 23 ) ) {
			$calendar[ 'day' ]++;
		};
	} else {
		// If language not installed, set Hungarian
		$country = 'hu';
		// Leap year setting
		if( !$calendar[ 'leap' ] && ( $calendar[ 'month' ] === 2 ) && ( $calendar[ 'day' ] > 23 ) ) {
			$calendar[ 'day' ]++;
		};
	};
	// Convert date variables to string
	if( $calendar[ 'month' ] < 10 ) {
		$calendar[ 'month' ] = '0' . $calendar[ 'month' ];
	} else {
		$calendar[ 'month' ] = '' . $calendar[ 'month' ];
	};
	if( $calendar[ 'day' ] < 10 ) {
		$calendar[ 'day' ] = '0' . $calendar[ 'day' ];
	} else {
		$calendar[ 'day' ] = '' . $calendar[ 'day' ];
	};
	// Set the nameday day :)
	$thisnameday = $calendar[ 'month' ] . $calendar[ 'day' ];
	// Initialize days
	$days = array(
		'0101' =>   0, '0102' =>   1, '0103' =>   2, '0104' =>   3, '0105' =>   4,
		'0106' =>   5, '0107' =>   6, '0108' =>   7, '0109' =>   8, '0110' =>   9,
		'0111' =>  10, '0112' =>  11, '0113' =>  12, '0114' =>  13, '0115' =>  14,
		'0116' =>  15, '0117' =>  16, '0118' =>  17, '0119' =>  18, '0120' =>  19,
		'0121' =>  20, '0122' =>  21, '0123' =>  22, '0124' =>  23, '0125' =>  24,
		'0126' =>  25, '0127' =>  26, '0128' =>  27, '0129' =>  28, '0130' =>  29,
		'0131' =>  30, '0201' =>  31, '0202' =>  32, '0203' =>  33, '0204' =>  34,
		'0205' =>  35, '0206' =>  36, '0207' =>  37, '0208' =>  38, '0209' =>  39,
		'0210' =>  40, '0211' =>  41, '0212' =>  42, '0213' =>  43, '0214' =>  44,
		'0215' =>  45, '0216' =>  46, '0217' =>  47, '0218' =>  48, '0219' =>  49,
		'0220' =>  50, '0221' =>  51, '0222' =>  52, '0223' =>  53, '0224' =>  54,
		'0225' =>  55, '0226' =>  56, '0227' =>  57, '0228' =>  58, '0229' =>  59,
		'0301' =>  60, '0302' =>  61, '0303' =>  62, '0304' =>  63, '0305' =>  64,
		'0306' =>  65, '0307' =>  66, '0308' =>  67, '0309' =>  68, '0310' =>  69,
		'0311' =>  70, '0312' =>  71, '0313' =>  72, '0314' =>  73, '0315' =>  74,
		'0316' =>  75, '0317' =>  76, '0318' =>  77, '0319' =>  78, '0320' =>  79,
		'0321' =>  80, '0322' =>  81, '0323' =>  82, '0324' =>  83, '0325' =>  84,
		'0326' =>  85, '0327' =>  86, '0328' =>  87, '0329' =>  88, '0330' =>  89,
		'0331' =>  90, '0401' =>  91, '0402' =>  92, '0403' =>  93, '0404' =>  94,
		'0405' =>  95, '0406' =>  96, '0407' =>  97, '0408' =>  98, '0409' =>  99,
		'0410' => 100, '0411' => 101, '0412' => 102, '0413' => 103, '0414' => 104,
		'0415' => 105, '0416' => 106, '0417' => 107, '0418' => 108, '0419' => 109,
		'0420' => 110, '0421' => 111, '0422' => 112, '0423' => 113, '0424' => 114,
		'0425' => 115, '0426' => 116, '0427' => 117, '0428' => 118, '0429' => 119,
		'0430' => 120, '0501' => 121, '0502' => 122, '0503' => 123, '0504' => 124,
		'0505' => 125, '0506' => 126, '0507' => 127, '0508' => 128, '0509' => 129,
		'0510' => 130, '0511' => 131, '0512' => 132, '0513' => 133, '0514' => 134,
		'0515' => 135, '0516' => 136, '0517' => 137, '0518' => 138, '0519' => 139,
		'0520' => 140, '0521' => 141, '0522' => 142, '0523' => 143, '0524' => 144,
		'0525' => 145, '0526' => 146, '0527' => 147, '0528' => 148, '0529' => 149,
		'0530' => 150, '0531' => 151, '0601' => 152, '0602' => 153, '0603' => 154,
		'0604' => 155, '0605' => 156, '0606' => 157, '0607' => 158, '0608' => 159,
		'0609' => 160, '0610' => 161, '0611' => 162, '0612' => 163, '0613' => 164,
		'0614' => 165, '0615' => 166, '0616' => 167, '0617' => 168, '0618' => 169,
		'0619' => 170, '0620' => 171, '0621' => 172, '0622' => 173, '0623' => 174,
		'0624' => 175, '0625' => 176, '0626' => 177, '0627' => 178, '0628' => 179,
		'0629' => 180, '0630' => 181, '0701' => 182, '0702' => 183, '0703' => 184,
		'0704' => 185, '0705' => 186, '0706' => 187, '0707' => 188, '0708' => 189,
		'0709' => 190, '0710' => 191, '0711' => 192, '0712' => 193, '0713' => 194,
		'0714' => 195, '0715' => 196, '0716' => 197, '0717' => 198, '0718' => 199,
		'0719' => 200, '0720' => 201, '0721' => 202, '0722' => 203, '0723' => 204,
		'0724' => 205, '0725' => 206, '0726' => 207, '0727' => 208, '0728' => 209,
		'0729' => 210, '0730' => 211, '0731' => 212, '0801' => 213, '0802' => 214,
		'0803' => 215, '0804' => 216, '0805' => 217, '0806' => 218, '0807' => 219,
		'0808' => 220, '0809' => 221, '0810' => 222, '0811' => 223, '0812' => 224,
		'0813' => 225, '0814' => 226, '0815' => 227, '0816' => 228, '0817' => 229,
		'0818' => 230, '0819' => 231, '0820' => 232, '0821' => 233, '0822' => 234,
		'0823' => 235, '0824' => 236, '0825' => 237, '0826' => 238, '0827' => 239,
		'0828' => 240, '0829' => 241, '0830' => 242, '0831' => 243, '0901' => 244,
		'0902' => 245, '0903' => 246, '0904' => 247, '0905' => 248, '0906' => 249,
		'0907' => 250, '0908' => 251, '0909' => 252, '0910' => 253, '0911' => 254,
		'0912' => 255, '0913' => 256, '0914' => 257, '0915' => 258, '0916' => 259,
		'0917' => 260, '0918' => 261, '0919' => 262, '0920' => 263, '0921' => 264,
		'0922' => 265, '0923' => 266, '0924' => 267, '0925' => 268, '0926' => 269,
		'0927' => 270, '0928' => 271, '0929' => 272, '0930' => 273, '1001' => 274,
		'1002' => 275, '1003' => 276, '1004' => 277, '1005' => 278, '1006' => 279,
		'1007' => 280, '1008' => 281, '1009' => 282, '1010' => 283, '1011' => 284,
		'1012' => 285, '1013' => 286, '1014' => 287, '1015' => 288, '1016' => 289,
		'1017' => 290, '1018' => 291, '1019' => 292, '1020' => 293, '1021' => 294,
		'1022' => 295, '1023' => 296, '1024' => 297, '1025' => 298, '1026' => 299,
		'1027' => 300, '1028' => 301, '1029' => 302, '1030' => 303, '1031' => 304,
		'1101' => 305, '1102' => 306, '1103' => 307, '1104' => 308, '1105' => 309,
		'1106' => 310, '1107' => 311, '1108' => 312, '1109' => 313, '1110' => 314,
		'1111' => 315, '1112' => 316, '1113' => 317, '1114' => 318, '1115' => 319,
		'1116' => 320, '1117' => 321, '1118' => 322, '1119' => 323, '1120' => 324,
		'1121' => 325, '1122' => 326, '1123' => 327, '1124' => 328, '1125' => 329,
		'1126' => 330, '1127' => 331, '1128' => 332, '1129' => 333, '1130' => 334,
		'1201' => 335, '1202' => 336, '1203' => 337, '1204' => 338, '1205' => 339,
		'1206' => 340, '1207' => 341, '1208' => 342, '1209' => 343, '1210' => 344,
		'1211' => 345, '1212' => 346, '1213' => 347, '1214' => 348, '1215' => 349,
		'1216' => 350, '1217' => 351, '1218' => 352, '1219' => 353, '1220' => 354,
		'1221' => 355, '1222' => 356, '1223' => 357, '1224' => 358, '1225' => 359,
		'1226' => 360, '1227' => 361, '1228' => 362, '1229' => 363, '1230' => 364,
		'1231' => 365
	);
	// Identify day
	$dayid = $days[ $thisnameday ];
	// Array for namedays
	$namedays = array(
		'hu' => array(
			'Fruzsina', 'Ábel', 'Benjámin, Genovéva', 'Leona, Titusz', 'Simon',
			'Boldizsár', 'Attila, Ramóna', 'Gyöngyvér', 'Marcell', 'Melánia', 'Ágota',
			'Ernő', 'Veronika', 'Bódog', 'Loránd, Lóránt', 'Gusztáv',
			'Antal, Antónia', 'Piroska', 'Sára, Márió', 'Fábián, Sebestyén', 'Ágnes',
			'Artúr, Vince', 'Rajmund, Zelma', 'Timót', 'Pál', 'Paula, Vanda',
			'Angelika', 'Károly, Karola', 'Adél', 'Gerda, Martina', 'Marcella',
			'Ignác', 'Karolina, Aida', 'Balázs', 'Ráhel, Csenge', 'Ágota, Ingrid',
			'Dóra, Dorottya', 'Rómeó, Tódór', 'Aranka', 'Abigél, Alex', 'Elvira',
			'Bertold, Marietta', 'Lídia, Lívia', 'Ella, Linda', 'Bálint, Valentin',
			'Georgina, Kolos', 'Julianna, Lilla', 'Donát', 'Bernadett', 'Zsuzsanna',
			'Aladár, Álmos', 'Eleonóra', 'Gerzson', 'Alfréd', 'Szökőnap', 'Mátyás',
			'Géza', 'Edina', 'Ákos, Bátor', 'Elemér', 'Albin', 'Lujza', 'Kornélia',
			'Kázmér', 'Adorján, Adrián', 'Leonóra, Inez', 'Tamás', 'Zoltán',
			'Franciska, Fanni', 'Ildikó', 'Szilárd', 'Gergely', 'Krisztián, Ajtony',
			'Matild', 'Kristóf', 'Henrietta', 'Gertrúd, Patrik', 'Sándor, Ede',
			'József, Bánk', 'Klaudia,', 'Benedek', 'Beáta, Izolda, Lea', 'Emőke',
			'Gábor, Karina', 'Irén, Írisz', 'Emánuel', 'Hajnalka', 'Gedeon, Johanna',
			'Auguszta', 'Zalán', 'Árpád', 'Hugó', 'Áron', 'Buda, Richárd', 'Izidor',
			'Vince', 'Vilmos, Bíborka', 'Herman', 'Dénes', 'Erhard', 'Zsolt',
			'Leó, Szaniszló', 'Gyula', 'Ida', 'Tibor', 'Anasztázia, Tas', 'Csongor',
			'Rudolf', 'Andrea, Ilma', 'Emma', 'Tivadar', 'Konrád', 'Csilla, Noémi',
			'Béla', 'György', 'Márk', 'Ervin', 'Zita', 'Valéria', 'Péter',
			'Katalin, Kitti', 'Fülöp, Jakab', 'Zsigmond', 'Tímea, Irma',
			'Mónika, Flórián', 'Györgyi', 'Frida, Ivett', 'Gizella', 'Mihály',
			'Gergely', 'Ármin, Pálma', 'Ferenc', 'Pongrác', 'Szervác, Imola',
			'Bonifác', 'Zsófia, Szonja', 'Botond, Mózes', 'Paszkál',
			'Erik, Alexandra', 'Ivó, Milán', 'Bernát, Felícia', 'Konstantin',
			'Júlia, Rita', 'Dezső', 'Eszter, Eliza', 'Orbán', 'Evelin, Fülöp',
			'Hella', 'Emil, Csanád', 'Magdolna', 'Janka, Zsanett',
			'Angéla, Petronella', 'Tünde', 'Anita, Kármen', 'Klotild, Cecília',
			'Bulcsú', 'Fatime, Fatima', 'Norbert, Cintia', 'Róbert', 'Medárd',
			'Félix', 'Margit, Gréta', 'Barnabás', 'Villő', 'Antal, Anett', 'Vazul',
			'Jolán, Vid', 'Jusztin', 'Laura, Alida', 'Arnold, Levente', 'Gyárfás',
			'Rafael', 'Alajos, Leila', 'Paulina', 'Zoltán', 'Iván', 'Vilmos',
			'János, Pál', 'László', 'Levente, Irén', 'Péter, Pál', 'Pál',
			'Tihamér, Annamária', 'Ottó', 'Kornél, Soma', 'Ulrik', 'Emese, Sarolta',
			'Csaba', 'Apollónia', 'Ellák', 'Lukrécia', 'Amália', 'Nóra, Lili',
			'Dalma, Izabella', 'Jenő', 'Örs, Stella', 'Henrik, Roland', 'Valter',
			'Endre, Elek', 'Frigyes', 'Emília', 'Illés', 'Dániel, Daniella',
			'Magdolna', 'Lenke', 'Kinga, Kincső', 'Kristóf, Jakab', 'Anna, Anikó',
			'Olga, Liliána', 'Szabolcs', 'Márta, Flóra', 'Judit, Xénia', 'Oszkár',
			'Boglárka', 'Lehel', 'Hermina', 'Domonkos, Dominika', 'Krisztina',
			'Berta, Bettina', 'Ibolya', 'László', 'Emőd', 'Lőrinc',
			'Zsuzsanna, Tiborc', 'Klára', 'Ipoly', 'Marcell', 'Mária', 'Ábrahám',
			'Jácint', 'Ilona', 'Huba', 'István', 'Sámuel, Hajna', 'Menyhért, Mirjam',
			'Bence', 'Bertalan', 'Lajos, Patricia', 'Izsó', 'Gáspár', 'Ágoston',
			'Beatrix, Erna', 'Rózsa', 'Erika, Bella', 'Egon, Egyed', 'Dorina, Rebeka',
			'Hilda', 'Rozália', 'Viktor, Lőrinc', 'Zakariás', 'Regina',
			'Mária, Adrienn', 'Ádám', 'Nikolett, Hunor', 'Teodóra', 'Mária', 'Kornél',
			'Szeréna, Roxána', 'Enikő, Melitta', 'Edit', 'Zsófia', 'Diána',
			'Vilhelmina', 'Friderika', 'Máté, Mirella', 'Móric', 'Tekla, Líviusz',
			'Gellért, Mercédesz', 'Eufrozina, Kende', 'Jusztina', 'Adalbert',
			'Vencel', 'Mihály', 'Jeromos', 'Malvin', 'Petra', 'Helga', 'Ferenc',
			'Aurél', 'Brúnó, Renáta', 'Amália', 'Koppány', 'Dénes', 'Gedeon',
			'Brigitta', 'Miksa', 'Kálmán, Ede', 'Helén', 'Teréz', 'Gál', 'Hedvig',
			'Lukács', 'Nándor', 'Vendel', 'Orsolya', 'Előd', 'Gyöngyi', 'Salamon',
			'Blanka, Bianka', 'Dömötör', 'Szabina', 'Simon, Szimonetta', 'Nárcisz',
			'Alfonz', 'Farkas', 'Marianna', 'Achilles', 'Győző', 'Károly', 'Imre',
			'Lénárd', 'Rezső', 'Zsombor', 'Tivadar', 'Réka', 'Márton',
			'Jónás, Renátó', 'Szilvia', 'Aliz', 'Albert, Lipót', 'Ödön',
			'Gergő, Hortenzia', 'Jenő', 'Erzsébet', 'Jolán', 'Olivér', 'Cecília',
			'Kelemen, Klementina', 'Emma', 'Katalin', 'Virág', 'Virgil', 'Stefánia',
			'Taksony', 'András, Andor', 'Elza', 'Melinda, Vivien', 'Ferenc, Olívia',
			'Barbara, Borbála', 'Vilma', 'Miklós', 'Ambrus', 'Mária', 'Natália',
			'Judit', 'Árpád, Árpádina', 'Gabriella', 'Luca, Otília', 'Szilárda',
			'Valér', 'Etelka, Aletta', 'Lázár, Olimpia', 'Auguszta', 'Viola',
			'Teofil', 'Tamás', 'Zénó', 'Viktória', 'Ádám, Éva', 'Eugénia', 'István',
			'János', 'Kamilla', 'Tamás, Tamara', 'Dávid', 'Szilveszter'
		),
		'dk' => array(
			'Nytårsdag', 'Abel', 'Enoch', 'Metusalem', 'Simeon', 'Hellige 3 Konger',
			'Knud', 'Erhardt', 'Julianus', 'Paul', 'Hygimus', 'Reinhold', 'Hilarius',
			'Felix', 'Maurus', 'Marcellus', 'Antonius', 'Prisca', 'Pontiaus',
			'Fabian, Sebastian', 'Agnes', 'Vincentius', 'Emerentius', 'Timotheus',
			'Pauli Omvendelsesdag', 'Polycarpus', 'Chrysostomus',
			'Carolus, Magnus, Karl', 'Valerius', 'Adelgunde', 'Vigilius', 'Brigida',
			'Kyndelmisse', 'Blasius', 'Veronica', 'Agathe', 'Dorothea', 'Richard',
			'Corintha', 'Apollonia', 'Scholastica', 'Euphrosyne', 'Eulalia',
			'Benignus', 'Valentinus', 'Faustinus', 'Juliane', 'Findanus', 'Concordia',
			'Ammon', 'Eucharias', 'Samuel', 'Peters Stol', 'Papias', 'Skuddag',
			'Mattias', 'Victorinus', 'Inger', 'Leander', 'Øllegaard', 'Albinus',
			'Simplicius', 'Kunigunde', 'Adrianus', 'Theophillus', 'Gotfred',
			'Perpetua', 'Beata', 'Fyrre riddere', 'Edel', 'Thala', 'Gregorius',
			'Macedonius', 'Eutychius', 'Zacharias', 'Gudmund', 'Gertrud', 'Alexander',
			'Joseph', 'Gordius', 'Benedictus', 'Paulus', 'Fidelis', 'Judica, Ulrica',
			'Maria bebudelsesdag', 'Gabriel', 'Kastor', 'Eustachius', 'Jonas',
			'Quirinus', 'Balbina', 'Hugo', 'Theodosius', 'Nicæas', 'Ambrosius',
			'Irene', 'Sixtus', 'Egesippus', 'Janus', 'Otto, Procopius', 'Ezechiel',
			'Leo', 'Julius', 'Justinus', 'Tiburtius', 'Olympia', 'Mariane',
			'Anicetus', 'Eleutherius', 'Daniel', 'Sulpicius', 'Florentius', 'Cajus',
			'Georgius', 'Albertus', 'Markus', 'Cletus', 'Ananias', 'Vitalis',
			'Peter Martyr', 'Serverus, Valborg', 'Jacob, Philip, Valborg',
			'Athanasius', 'Kormisse', 'Florian', 'Gothard', 'Johannes ante portam',
			'Flavia', 'Stanislaus', 'Caspar', 'Gordianus', 'Mamertus', 'Pancratius',
			'Ingenuus', 'Kristian', 'Sophie', 'Sara', 'Bruno', 'Erik', 'Potentiana',
			'Angelica', 'Helene', 'Castus', 'Desiderus', 'Esther', 'Urbanus', 'Beda',
			'Lucian', 'Vilhelm', 'Maciminus', 'Vigand', 'Petronella', 'Nikodemus',
			'Marcellinus', 'Erasmus', 'Optatus', 'Bonifacius', 'Nobertus', 'Jeremias',
			'Medardus', 'Primus', 'Onuphrius', 'Barnabas', 'Balisius', 'Cyrillus',
			'Rufinus', 'Vitus', 'Tycho', 'Botolphus', 'Leontius', 'Gervasius',
			'Sylverius', 'Albanus', '10.000 martyrer', 'Paulinus', 'Sankt Hans dag',
			'Prosper', 'Pelagius', 'Syvsoverdag', 'Eleonora', 'Petrus, Paulus',
			'Lucina', 'Theobaldus', 'Maria besøgelsesdag', 'Cornelius', 'Ulricus',
			'Anshelmus', 'Dion', 'Villebaldus', 'Kjeld', 'Sostrata', 'Knud Konge',
			'Josva', 'Henrik', 'Margrethe', 'Bonaventura', 'Apostlenes deling',
			'Tychos', 'Alecius', 'Arnolphus', 'Justa', 'Elias', 'Evenus',
			'Maria Magdalene', 'Apollinaris', 'Christina', 'Jacobus', 'Anna',
			'Martha', 'Aurelius', 'Oluf', 'Abdon', 'Helena, Germanus',
			'Peter fængsel', 'Hannibal', 'Nikodemus', 'Dominicus', 'Osvaldus',
			'Kristi forklarelse', 'Donatus', 'Tuth', 'Rosmanus', 'Laurentius',
			'Herman', 'Clara', 'Hippolytus', 'Eusebius', 'Maria himmelfart', 'Rochus',
			'Anastatius', 'Agapetus', 'Selbadus', 'Bernhard, Bernhard', 'Salomon',
			'Symphorian', 'Zakæus', 'Bartholomæus', 'Ludvig', 'Ienæus', 'Gebhardus',
			'Augustinus', 'Johannes halshuggelsesdag', 'Albert, Benjamin', 'Bertha',
			'Ægidius', 'Elisa', 'Seraphia', 'Theodosias', 'Regina', 'Magnus',
			'Robert', 'Maria fødselsdag', 'Gorgonius', 'Buchardt', 'Hillebert',
			'Guido', 'Cyprianus', 'Korsets ophøjelsesdag', 'Eskild', 'Euphemia',
			'Lambertu', 'Titus', 'Constantia', 'Tobias', 'Matthæus', 'Mauritius',
			'Linus', 'Tecla', 'Cleophas', 'Adolph', 'Cosmus', 'Venceslaus',
			'St. Michael', 'Hieronymus', 'Remigius', 'Ditlev', 'Mette', 'Franciscus',
			'Placidus', 'Broderus', 'Amalie', 'Ingeborg', 'Dionysius', 'Gereon',
			'Probus', 'Maximillian', 'Angelus', 'Calixus', 'Hedevig', 'Gallus',
			'Floretinus', 'Lucas', 'Balthasar', 'Felicianus', 'Ursula', 'Cordula',
			'Søren', 'Proclus', 'Crispinus', 'Amandus', 'Sem', 'Judas, Simon',
			'Narcissus', 'Elsa, Absalon', 'Louise', 'Allehelgensdag',
			'Alle sjæles dag', 'Hubertus', 'Otto', 'Malachias', 'Leonhardus',
			'Engelbrecht', 'Cladius', 'Theodor', 'Luther', 'Morten bisp', 'Torkild',
			'Arcadius', 'Frederik', 'Leopold', 'Othenius', 'Anianus', 'Hesychius',
			'Elisabeth', 'Volkmarus', 'Maria ofring', 'Cecilia', 'Clemens',
			'Chrysogonus', 'Catharina', 'Conradus', 'Facindus', 'Sophie, Magdalene',
			'Saturnius', 'Andreas', 'Arnold', 'Bibiana', 'Svend', 'Barbara', 'Sabina',
			'Nikolaus', 'Agathon', 'Maria undfangelse', 'Rudolph', 'Judith',
			'Damasus', 'Epimachus', 'Lucia', 'Crispus', 'Nikatius', 'Lazarus',
			'Albina', 'Lovise', 'Nemesius', 'Abraham', 'Thomas', 'Japetus',
			'Torlacus', 'Adam, Alexandrine', 'Juledag', 'Stefan',
			'Johannes evangeliets dag', 'Børnedag', 'Noa', 'David', 'Sylvester'
		),
		'cz' => array(
			'Nový rok', 'Karina', 'Radmila', 'Diana', 'Dalimil', 'Tři králové',
			'Vilma', 'Čestmír', 'Vladan', 'Břetislav', 'Bohdana', 'Pravoslav',
			'Edita, Záviš', 'Radovan', 'Alice', 'Ctirad', 'Drahoslav', 'Vladislav',
			'Doubravka', 'Ilona', 'Běla', 'Slavomír', 'Zdeněk', 'Milena', 'Miloš',
			'Zora', 'Ingrid', 'Otýlie', 'Zdislava', 'Robin', 'Marika', 'Hynek',
			'Nela', 'Blažej', 'Jarmila', 'Dobromila', 'Vanda', 'Veronika', 'Milada',
			'Apolena', 'Mojmír', 'Božena', 'Slavěna', 'Věnceslav', 'Valentýn',
			'Jiřina', 'Ljuba', 'Miloslava', 'Gizela', 'Patrik', 'Oldřich', 'Lenka',
			'Petr', 'Svatopluk', 'Matěj', 'Liliana', 'Dorota', 'Alexandr', 'Lumír',
			'Horymír', 'Bedřich', 'Anežka', 'Kamil', 'Stela', 'Kazimír', 'Miroslav',
			'Tomáš, Tomislav', 'Gabriela', 'Františka', 'Viktorie', 'Anděla', 'Řehoř',
			'Růžena', 'Rút, Matylda', 'Ida', 'Elena, Herbert', 'Vlastimil', 'Eduard',
			'Josef', 'Světlana', 'Radek', 'Leona', 'Ivona', 'Gabriel', 'Marián',
			'Emanuel', 'Dita', 'Soňa', 'Taťána', 'Arnošt', 'Kvido', 'Hugo', 'Erika',
			'Richard', 'Ivana', 'Miroslava', 'Vendula', 'Heřman, Hermína', 'Ema',
			'Dušan', 'Darja', 'Izabela', 'Julius', 'Aleš', 'Vincenc', 'Anastázie',
			'Irena, Bernadeta', 'Rudolf', 'Valérie', 'Rostislav', 'Marcela',
			'Alexandra', 'Evženie', 'Vojtěch', 'Jiří', 'Marek', 'Oto', 'Jaroslav',
			'Vlastislav', 'Robert', 'Blahoslav', 'Svátek práce', 'Zikmund', 'Alexej',
			'Květoslav', 'Klaudie', 'Radoslav', 'Stanislav', 'Den vítězství',
			'Ctibor', 'Blažena', 'Svatava', 'Pankrác', 'Servác', 'Bonifác', 'Žofie',
			'Přemysl', 'Aneta', 'Nataša', 'Ivo', 'Zbyšek', 'Monika', 'Emil',
			'Vladimír', 'Jana', 'Viola', 'Filip', 'Valdemar', 'Vilém',
			'Maxmilián, Maxim', 'Ferdinand', 'Kamila', 'Laura', 'Jarmil', 'Tamara',
			'Dalibor', 'Dobroslav', 'Norbert', 'Iveta, Slavoj', 'Medard',
			'Stanislava', 'Gita', 'Bruno', 'Antonie', 'Antonín', 'Roland', 'Vít',
			'Zbyněk', 'Adolf', 'Milan', 'Leoš', 'Květa', 'Alois', 'Pavla', 'Zdeňka',
			'Jan', 'Ivan', 'Adriana', 'Ladislav', 'Lubomír', 'Petr, Pavel', 'Šárka',
			'Jaroslava', 'Patricie', 'Radomír', 'Prokop', 'Cyril, Metoděj', 'Jan Hus',
			'Bohuslava', 'Nora', 'Drahoslava', 'Libuše, Amálie', 'Olga', 'Bořek',
			'Markéta', 'Karolína', 'Jindřich', 'Luboš', 'Martina', 'Drahomíra',
			'Čeněk', 'Ilja', 'Vítězslav', 'Magdaléna', 'Libor', 'Kristýna', 'Jakub',
			'Anna', 'Věroslav', 'Viktor', 'Marta', 'Bořivoj', 'Ignác', 'Oskar',
			'Gustav', 'Miluše', 'Dominik', 'Kristián', 'Oldřiška', 'Lada', 'Soběslav',
			'Roman', 'Vavřinec', 'Zuzana', 'Klára', 'Alena', 'Alan', 'Hana', 'Jáchym',
			'Petra', 'Helena', 'Ludvík', 'Bernard', 'Johana', 'Bohuslav', 'Sandra',
			'Bartoloměj', 'Radim', 'Luděk', 'Otakar', 'Augustýn', 'Evelína',
			'Vladěna', 'Pavlína', 'Linda, Samuel', 'Adéla', 'Bronislav, Bronislava',
			'Jindřiška', 'Boris', 'Boleslav', 'Regina, Regína', 'Mariana', 'Daniela',
			'Irma', 'Denisa', 'Marie', 'Lubor', 'Radka', 'Jolana', 'Ludmila',
			'Naděžda', 'Kryštof', 'Zita', 'Oleg', 'Matouš', 'Darina', 'Berta',
			'Jaromír', 'Zlata', 'Andrea', 'Jonáš', 'Václav', 'Michal', 'Jeroným',
			'Igor', 'Olivie, Oliver', 'Bohumil', 'František', 'Eliška', 'Hanuš',
			'Justýna', 'Věra', 'Štefan, Sára', 'Marina', 'Andrej', 'Marcel', 'Renáta',
			'Agáta', 'Tereza', 'Havel', 'Hedvika', 'Lukáš', 'Michaela', 'Vendelín',
			'Brigita', 'Sabina', 'Teodor', 'Nina', 'Beáta', 'Erik', 'Šarlota, Zoe',
			'Státní svátek', 'Silvie', 'Tadeáš', 'Štěpánka', 'Felix', 'Dušičky',
			'Hubert', 'Karel', 'Miriam', 'Liběna', 'Saskie', 'Bohumír', 'Bohdan',
			'Evžen', 'Martin', 'Benedikt', 'Tibor', 'Sáva', 'Leopold', 'Otmar',
			'Mahulena', 'Romana', 'Alžběta', 'Nikola', 'Albert', 'Cecílie', 'Klement',
			'Emílie', 'Kateřina', 'Artur', 'Xenie', 'René', 'Zina', 'Ondřej', 'Iva',
			'Blanka', 'Svatoslav', 'Barbora', 'Jitka', 'Mikuláš', 'Ambrož, Benjamín',
			'Květoslava', 'Vratislav', 'Julie', 'Dana', 'Simona', 'Lucie', 'Lýdie',
			'Radana', 'Albína', 'Daniel', 'Miloslav', 'Ester', 'Dagmar', 'Natálie',
			'Šimon', 'Vlasta', 'Adam, Eva', 'Vánoce', 'Štěpán', 'Žaneta', 'Bohumila',
			'Judita', 'David', 'Silvestr'
		),
		'sk' => array(
			'Courtney', 'Alexandra, Karina', 'Daniela', 'Drahoslav', 'Andrea',
			'Antónia', 'Bohuslava', 'Severín', 'Alexej', 'Dáša', 'Malvína', 'Ernest',
			'Rastislav', 'Radovan', 'Dobroslav', 'Kristína', 'Nataša', 'Bohdana',
			'Mário, Drahomíra', 'Dalibor', 'Vincent', 'Zora', 'Miloš', 'Timotej',
			'Gejza', 'Tamara', 'Bohuš', 'Alfonz', 'Gašpar', 'Ema', 'Emil', 'Tatiana',
			'Erik, Erika', 'Blažej', 'Veronika', 'Agáta', 'Dorota', 'Vanda', 'Zoja',
			'Zdenko', 'Gabriela', 'Dezider', 'Perla', 'Arpád', 'Valentín',
			'Pravoslav', 'Ida, Liana', 'Miloslava', 'Jaromír', 'Vlasta', 'Lívia',
			'Eleonóra', 'Etela', 'Roman, Romana', 'Matej', 'Frederik, Frederika',
			'Viktor', 'Alexander', 'Zlatica', 'Radomír', 'Albín', 'Anežka',
			'Bohumil, Bohumila', 'Kazimír', 'Fridrich', 'Radoslav, Radoslava',
			'Tomáš', 'Alan, Alana', 'Františka', 'Branislav, Bruno',
			'Angela, Angelika', 'Gregor', 'Vlastimil', 'Matilda', 'Svetlana',
			'Boleslav', 'Ľubica', 'Eduard', 'Jozef', 'Víťazoslav', 'Blahoslav',
			'Beňadik', 'Adrián', 'Gabriel', 'Marián', 'Emanuel', 'Alena', 'Soňa',
			'Miroslav', 'Vieroslava', 'Benjamín', 'Hugo', 'Zita', 'Richard', 'Izidor',
			'Miroslava', 'Irena', 'Zoltán', 'Albert', 'Milena', 'Igor', 'Július',
			'Estera', 'Aleš', 'Justína', 'Fedor', 'Dana, Danica', 'Rudolf', 'Valér',
			'Jela', 'Marcel', 'Ervín', 'Slavomír', 'Vojtech', 'Juraj', 'Marek',
			'Jaroslava', 'Jaroslav', 'Jarmila', 'Lea', 'Anastázia', 'Amarila, Pamela',
			'Žigmund', 'Galina', 'Florián', 'Lesana', 'Hermína', 'Monika', 'Ingrida',
			'Roland', 'Viktória', 'Blažena', 'Pankrác', 'Servác', 'Bonifác', 'Žofia',
			'Svetozár', 'Gizela', 'Viola', 'Gertrúda', 'Bernard', 'Zina',
			'Júlia, Juliána', 'Želmíra', 'Ela', 'Urban', 'Dušan', 'Iveta', 'Viliam',
			'Vilma', 'Ferdinand', 'Petronela', 'Žaneta', 'Oxana, Xénia', 'Karolína',
			'Lenka', 'Laura', 'Norbert', 'Róbert', 'Medard', 'Stanislava',
			'Margaréta', 'Dobroslava', 'Zlatko', 'Anton', 'Vasil', 'Vít',
			'Bianka, Blanka', 'Adolf', 'Vratislav', 'Alfréd', 'Valéria', 'Alojz',
			'Paulína', 'Sidónia', 'Ján', 'Tadeáš, Olívia', 'Adriána',
			'Ladislav, Ladislava', 'Beáta', 'Pavol, Peter, Petra', 'Melánia', 'Diana',
			'Berta', 'Miloslav', 'Prokop', 'Cyril, Metod', 'Patrik, Patrícia',
			'Oliver', 'Ivan', 'Lujza', 'Amália', 'Milota', 'Nina', 'Margita', 'Kamil',
			'Henrich', 'Drahomír', 'Bohuslav', 'Kamila', 'Dušana', 'Eliáš, Iľja',
			'Daniel', 'Magdaléna', 'Oľga', 'Vladimír', 'Jakub', 'Anna, Hana',
			'Božena', 'Krištof', 'Marta', 'Libuša', 'Ignác', 'Božidara', 'Gustáv',
			'Jerguš', 'Dominik, Dominika', 'Hortenzia', 'Jozefína', 'Štefánia',
			'Oskár', 'Ľubomíra', 'Vavrinec', 'Zuzana', 'Darina', 'Ľubomír', 'Mojmír',
			'Marcela', 'Leonard', 'Milica', 'Elena, Helena', 'Lýdia', 'Anabela',
			'Jana', 'Tichomír', 'Filip', 'Bartolomej', 'Ľudovít', 'Samuel', 'Silvia',
			'Augustín', 'Nikola', 'Ružena', 'Nora', 'Drahoslava', 'Linda', 'Belo',
			'Rozália', 'Regína', 'Alica', 'Marianna', 'Miriama', 'Martina', 'Oleg',
			'Bystrík', 'Mária', 'Ctibor', 'Ľudomil', 'Jolana', 'Ľudmila', 'Olympia',
			'Eugénia', 'Konštantín', 'Ľuboslav, Ľuboslava', 'Matúš', 'Móric',
			'Zdenka', 'Ľubor', 'Vladislav', 'Edita', 'Cyprián', 'Václav',
			'Michal, Michaela', 'Jarolím', 'Arnold', 'Levoslav', 'Stela', 'František',
			'Viera', 'Natália', 'Eliška', 'Brigita', 'Dionýz', 'Slavomíra',
			'Valentína', 'Maximilián', 'Koloman', 'Boris', 'Terézia', 'Vladimíra',
			'Hedviga', 'Lukáš', 'Kristián', 'Vendelín', 'Uršuľa', 'Sergej', 'Alojzia',
			'Kvetoslava', 'Aurel', 'Demeter', 'Sabína', 'Dobromila', 'Klára',
			'Šimon, Simona', 'Aurélia', 'Denis, Dennis, Denisa', 'Cézar, Cezária',
			'Hubert', 'Karol', 'Imrich', 'Renáta', 'René', 'Bohumír', 'Teodor',
			'Tibor', 'Maroš, Martin', 'Svätopluk', 'Stanislav', 'Irma', 'Leopold',
			'Agnesa', 'Klaudia', 'Eugen', 'Alžbeta', 'Félix', 'Elvíra', 'Cecília',
			'Klement', 'Emília', 'Katarína', 'Kornel', 'Milan', 'Henrieta', 'Vratko',
			'Andrej, Ondrej', 'Edmund', 'Bibiána', 'Oldrich', 'Barbora', 'Oto',
			'Mikuláš', 'Ambróz', 'Marína', 'Izabela', 'Radúz', 'Hilda', 'Otília',
			'Lucia', 'Branislava, Bronislava', 'Ivica', 'Albína', 'Kornélia', 'Sláva',
			'Judita', 'Dagmara', 'Bohdan', 'Adela', 'Nadežda', 'Adam, Eva', '-',
			'Štefan', 'Filoména', 'Ivana, Ivona', 'Milada', 'Dávid', 'Silvester'
		),
		'pl' => array(
			'Mieszko, Mieczysław', 'Izydor, Grzegorz', 'Danuta, Zdzisław',
			'Elzbieta, Aniela', 'Hanna, Edward, Szymon', 'Kacper, Melchior, Baltazar',
			'Lucjan, Julian', 'Seweryn, Juliusz', 'Adrian, Marcelina',
			'Danuta, Wilhelm', 'Honorata, Matylda', 'Ada, Benedykt, Arkadiusz',
			'Bogumiła, Weronika', 'Feliks, Nina', 'Paweł, Arnold, Izydor',
			'Marcela, Włodzimierz', 'Antoni, Jan', 'Malgorzata, Piotr',
			'Henryk, Marta', 'Fabian, Sebastian', 'Agnieszka, Jarosław',
			'Anastazy, Wincenty', 'Ildefons, Rajmund', 'Rafal, Felicja, Franciszska',
			'Paweł, Miłosz', 'Tymoteusz, Michal', 'Aniela, Jerzy', 'Walery, Karol',
			'Franciszk, Jozef', 'Maciej, Martyna', 'Jan, Marcela, Ludwika',
			'Brygida, Ignacy', 'Maria, Mirosław', 'Blazej, Oskar',
			'Andrzej, Jozef, Tytus', 'Agata, Adelajda', 'Dorota, Bogdan, Paweł',
			'Ryszard, Teodor', 'Hieronim, Sebastian', 'Apolina, Eryka, Cyryl',
			'Elwira, Jack', 'Grzegorz, Lucjan', 'Radosław, Damian',
			'Grzegorz, Katarzyna', 'Cyryl, Metody', 'Jowita, Faustyn',
			'Danuta, Julian', 'Aleksy, Łukasz', 'Szymon, Konstancja',
			'Arnold, Jozef, Konrad', 'Leon, Ludomir', 'Robert, Eleonora',
			'Marta, Malgorzata', 'Romana, Damian', 'Maciej, Marek', 'Cezary, Donat',
			'Mirosław, Aleksandr', 'Gabriel, Anastazja', 'Roman, Ludomir',
			'Dobronieg, Roman', 'Albin, Antoni', 'Helena, Halszka',
			'Maryna, Kunegunda', 'Arkadiusz, Eugeniusz, Kazimierz',
			'Adryjan, Fryderyk', 'Roza, Wiktor', 'Tomasz, Felicyta',
			'Beata, Wincenty', 'Franciszka, Dominika', 'Cyprian, Aleksandr',
			'Benedykt, Konstantyn', 'Alojzy, Bernard', 'Bozena, Krystyna',
			'Leon, Martyna', 'Longin, Klemens', 'Izabela, Oktawia',
			'Patryk, Zbigniew', 'Cyryl, Edward', 'Jozef, Bogdan', 'Klaudia, Eufemia',
			'Ludomir, Benedykt', 'Katarzyna, Bogusław', 'Pelagia, Feliks',
			'Mark, Gabriel', 'Mariola, Wienczysław', 'Emanuel, Larysa, Teodor',
			'Lidia, Ernest', 'Aniela, Jan', 'Viktoryn, Helmut', 'Aniela, Leonard',
			'Beniamin, Balbina', 'Grazyna, Irena', 'Wladysław, Franciszka',
			'Ryszard, Irena', 'Benedykt, Izydor', 'Katarzyna, Wincenty',
			'Izolda, Ireneusz', 'Rufin, Donat', 'Dionizy, Julia', 'Maria, Dymitr',
			'Michal, Makary', 'Filip, Leon', 'Damian, Juliusz', 'Przemysław, Ida',
			'Berenika, Walerian', 'Ludwina, Wacława', 'Cecylian, Bernadeta',
			'Robert, Rudolf', 'Alicja, Bogusław', 'Adolf, Tymon',
			'Czesław, Agnieszka', 'Bartosz, Feliks', 'Kazimierz, Łukasz',
			'Jerzy, Wojciech, Idzi', 'Aleksy, Horacy', 'Mark, Jarosław',
			'Marzena, Maria, Klaudiusz', 'Ludwik, Piotr', 'Paweł, Waleria',
			'Rita, Donata', 'Marian, Katarzyna', 'Jozef, Jeremiasz',
			'Zygmunt, Atanazy', 'Maria, Mariola', 'Monika, Florian',
			'Irena, Waldemar', 'Filip, Judyta', 'Benedykt, Gizela',
			'Ilza, Stanisław, Wiktor', 'Bozydar, Grzegorz', 'Izydor, Antoniny',
			'Iga, Ignacy', 'Joanna, Achilles', 'Glora, Gerwazy',
			'Bonifacy, Dobiesław', 'Zofia, Nadziea', 'Andrzej, Jedrzej',
			'Brunon, Paschalis', 'Eryk, Feliks', 'Piotr, Iwa', 'Aleksandr, Bazyly',
			'Jan, Wiktor', 'Helena, Wiesław', 'Emilia, Iwona', 'Joanna, Zuzanna',
			'Borysław, Grzegorz', 'Filip, Paulina', 'Augustyn, Julian',
			'Jaromir, Justyna', 'Magdalena, Bogumiła', 'Karol, Ferdynand',
			'Aniela, Petronela', 'Jakub, Justyn', 'Erazm, Marianna', 'Leszek, Tamara',
			'Franciszka, Karol', 'Bonifacy, Walter', 'Norbert, Laurenty',
			'Robert, Wiesław', 'Maksym, Medard', 'Anna, Felicjan',
			'Bogumił, Malgorzata', 'Barnaba, Radomił', 'Janina, Jan',
			'Lucjan, Antoni', 'Bazyly, Eliza', 'Wit, Jolanta', 'Alina, Benon',
			'Albert, Ignacy', 'Mark, Elzbieta', 'Gerwazy, Protazy', 'Dina, Bogna',
			'Alicja, Alojzy', 'Paulina, Tomasz', 'Wanda, Zenon', 'Jan, Danuta',
			'Lucja, Wilhelm', 'Jan, Paula', 'Maryla, Wladysław', 'Leon, Ireneusz',
			'Piotr, Paweł', 'Emilia, Lucyca', 'Halina, Marian', 'Jagoda, Urban',
			'Jack, Anatol', 'Malwina, Odon', 'Marii, Antoni', 'Dominika, Gotard',
			'Benedykt, Cyryl', 'Adryiana, Eugeniusz', 'Lukrecja, Weronika',
			'Olaf, Witalis', 'Olga, Kalina', 'Jan, Brunon', 'Ernest, Malgorzata',
			'Bonawentura, Stelia', 'Dawid, Henryk', 'Eustachy, Maria',
			'Aneta, Bogdan', 'Emil, Erwin', 'Wincenty, Wodzisław',
			'Czesław, Fryderyk', 'Daniel, Dalida', 'Maria, Magdalena',
			'Bogna, Apolinary', 'Kinga, Krystyna', 'Walentyna, Krzysztof',
			'Anna, Mirosława', 'Celestyn, Lilia', 'Aida, Innocenty', 'Olaf, Marta',
			'Julita, Piotr', 'Ignacy, Lubomir', 'Alfons, Nadia', 'Karina, Gustaw',
			'Lidia, August', 'Dominik, Jan', 'Maria, Oswald', 'Sława, Jakub',
			'Klaudia, Kajetan', 'Cyprian, Dominik', 'Roman, Ryszard', 'Bogdan, Borys',
			'Klara, Lidia', 'Lech, Euzebia', 'Diana, Hipolit', 'Alfred, Euzebiusz',
			'Maria, Napoleon', 'Stefan, Roch', 'Anita, Eliza', 'Ilona, Klara',
			'Jan, Bolesław', 'Bernard, Samuel', 'Franciszka, Joanna', 'Maria, Cezary',
			'Roza, Apolinary', 'Emilia, Jerzy', 'Luiza, Ludwik', 'Maria, Zefiryna',
			'Monika, Cezary', 'Patrycja, Wyszomir', 'Beata, Jan', 'Rebeka, Szczęsna',
			'Izabela, Ramona', 'Bronisława, Idzi', 'Julian, Stefan',
			'Izabela, Szymon', 'Jda, Lilianna', 'Dorota, Wawrzync',
			'Beata, Eugeniusz', 'Regina, Melchior', 'Maria, Adriana',
			'Piotr, Sergiusz', 'Łukasz, Mikołaj', 'Jack, Dagna', 'Radzimir, Gwidon',
			'Eugenia, Aureliusz', 'Roksana, Bernard', 'Albin, Nikodem',
			'Edyta, Kornel', 'Franciszka, Hildegarda', 'Irma, Jozef',
			'January, Konstancja', 'Filipina, Eustachy', 'Jonasz, Mateusz',
			'Tomasz, Maurycy', 'Bogusław, Tekla', 'Gerard, Teodor',
			'Aurelia, Ladysław', 'Justyna, Cyprian', 'Damian, Amadeusz',
			'Luba, Wacław', 'Michal, Michalina', 'Wera, Honoriusz',
			'Danuta, Remigiusz', 'Teofil, Dinozja', 'Teresa, Heliodor',
			'Rozalia, Edwin', 'Igor, Flawia', 'Artur, Brunon', 'Maria, Mark',
			'Pelagia, Brygida', 'Arnold, Dionizy', 'Paulina, Franciszka',
			'Emil, Aldona', 'Eustachy, Maksymilian', 'Gerard, Edward',
			'Alan, Kalikst', 'Teresa, Jadwiga', 'Gawl, Florentyna',
			'Malgorzata, Wiktor', 'Juliusz, Łukasz', 'Pelagia, Piotr', 'Irena, Jan',
			'Urszula, Hilaria', 'Filip, Kordula', 'Marlena, Seweryn', 'Rafal, Marcin',
			'Daria, Wilhelmina', 'Lucjan, Ewaryst', 'Jwona, Sabina',
			'Szymon, Tadeusz', 'Euzebia, Wioletta', 'Zenobia, Przemysław',
			'Urban, Saturnin', 'Julian, Łukasz', 'Bohdana, Tobiasz', 'Sylwia, Hubert',
			'Karol, Olgierd', 'Elzbieta, Sławomir', 'Feliks, Leonard',
			'Antoni, Zytomir', 'Sewer, Hadriana', 'Ursyn, Todor', 'Lena, Ludomir',
			'Marcin, Bartlomiej', 'Renata, Witold', 'Mikołaj, Stanisław',
			'Roger, Serafina', 'Albert, Leopold', 'Gertruda, Edmund',
			'Grzegorz, Salomea', 'Roman, Klaudyna', 'Elzbieta, Seweryn',
			'Anatol, Sedzimir', 'Janusz, Konrad', 'Mark, Cecylia', 'Adela, Klemens',
			'Flora, Emma', 'Katarzyna, Erazm', 'Delfina, Sylwestr',
			'Walery, Wilgiusz', 'Lesław, Zdzisław', 'Blazej, Saturnin',
			'Maura, Andrzej', 'Natalia, Eligiusz', 'Balbina, Bibiana',
			'Franciszka, Ksawery', 'Barbara, Krystian', 'Saba, Kryspin',
			'Mikołaj, Jarema', 'Marcin, Ambroza', 'Maria, Swiatozar',
			'Wiesław, Leokadia', 'Julia, Daniela', 'Damazy, Waldemar',
			'Dagmara, Aleksandra', 'Lucja, Otylia', 'Alfred, Izydor', 'Nina, Celina',
			'Albina, Zdzisław', 'Olimpia, Łazarz', 'Gracjan, Bogusław',
			'Gabriela, Dariusz', 'Bogumiła, Dominika', 'Tomasz, Tomisław',
			'Zenon, Honorata', 'Wiktoria, Sławomira', 'Adam, Ewa',
			'Anastazja, Eulalia', 'Jana, Zaneta', 'Teofila, Godzisław',
			'Jana, Maksym', 'Dawid, Tomasz', 'Irmina, Eugeniusz', 'Melania, Sylwester'
		),
		'no' => array(
			'Nyttårsdag', 'Dagfinn, Dagfrid', 'Alfred, Alf', 'Roar, Roger',
			'Hanna, Hanne', 'Aslaug, Åslaug', 'Eldbjørg, Knut', 'Turid, Torfinn',
			'Gunnar, Gunn', 'Sigmund, Sigrun', 'Børge, Børre', 'Reinhard, Reinert',
			'Gisle, Gislaug', 'Herbjørn, Herbjørg', 'Laurits, Laura',
			'Hjalmar, Hilmar', 'Anton, Tønnes, Tony', 'Hildur, Hild',
			'Marius, Margunn', 'Fabian, Sebastian, Bastian', 'Agnes, Agnete',
			'Ivan, Vanja', 'Emil, Emilie, Emma', 'Joar, Jarle, Jarl', 'Paul, Pål',
			'Øystein, Esten', 'Gaute, Gurli, Gry', 'Karl, Karoline',
			'Herdis, Hermod, Hermann', 'Gunnhild, Gunda', 'Idun, Ivar',
			'Birte, Bjarte', 'Jomar, Jostein', 'Ansgar, Asgeir', 'Veronika, Vera',
			'Agate, Ågot', 'Dortea, Dorte', 'Rikard, Rigmor, Riborg', 'Åshild, Åsne',
			'Lone, Leikny', 'Ingfrid, Ingrid', 'Ingve, Yngve',
			'Randulf, Randi, Ronja', 'Svanhild, Svanaug', 'Hjørdis, Jardar',
			'Sigfred, Sigbjørn', 'Julian, Juliane, Jill',
			'Aleksandra, Sandra, Sondre', 'Frøydis, Frode', 'Ella, Elna',
			'Halldis, Halldor', 'Samuel, Selma, Celine', 'Tina, Tim',
			'Torstein, Torunn', 'Mattias,Mattis, Mats', 'Viktor, Viktoria',
			'Inger, Ingjerd', 'Laila, Lill', 'Marina, Maren', 'Ingen namnedag',
			'Audny, Audun', 'Erna, Ernst', 'Gunnbjørg, Gunnveig', 'Ada, Adrian',
			'Patrick, Patricia', 'Annfrid, Andor', 'Arild, Are',
			'Beate, Betty, Bettina', 'Sverre, Sindre', 'Edel, Edle', 'Edvin, Tale',
			'Gregor, Gro', 'Greta, Grete', 'Mathilde, Mette',
			'Christel, Christer, Chris', 'Gudmund, Gudny', 'Gjertrud, Trude',
			'Aleksander, Sander, Edvard', 'Josef, Josefine', 'Joakim, Kim',
			'Bendik, Bengt, Bent', 'Paula, Pauline', 'Gerda, Gerd', 'Ulrikke, Rikke',
			'Maria, Marie, Mari', 'Gabriel, Glenn', 'Rudolf, Rudi', 'Åsta, Åste',
			'Jonas, Jonatan', 'Holger, Olga', 'Vebjørn, Vegard', 'Aron, Arve, Arvid',
			'Sigvard, Sivert', 'Gunnvald, Gunvor', 'Nanna, Nancy, Nina',
			'Irene, Eirin, Eiril', 'Åsmund, Asmund', 'Oddveig, Oddvin', 'Asle, Atle',
			'Rannveig, Rønnaug', 'Ingvald, Ingveig', 'Ylva, Ulf', 'Julius, Julie',
			'Asta, Astrid', 'Ellinor, Nora', 'Oda, Odin, Odd', 'Magnus, Mons',
			'Elise, Else, Elsa', 'Eilen, Eilert', 'Arnfinn, Arnstein',
			'Kjellaug, Kjellrun', 'Jeanette, Jannike', 'Oddgeir, Oddny',
			'Georg, Jørgen, Jørn', 'Albert, Olaug', 'Markus, Mark', 'Terese, Tea',
			'Charles, Charlotte, Lotte', 'Vivi, Vivian', 'Toralf, Torolf',
			'Gina, Gitte', 'Filip, Valborg', 'Åsa, Åse', 'Gjermund, Gøril',
			'Monika, Mona', 'Gudbrand, Gullborg', 'Guri, Gyri', 'Maia, Mai, Maiken',
			'Åge, Åke', 'Kasper, Jesper', 'Asbjørg, Asbjørn, Espen', 'Magda, Malvin',
			'Normann, Norvald', 'Linda, Line, Linn', 'Kristian, Kristen, Karsten',
			'Hallvard, Halvor', 'Sara, Siren', 'Harald, Ragnhild',
			'Eirik, Erik, Erika', 'Torjus, Torje, Truls', 'Bjørnar, Bjørnhild',
			'Helene, Ellen, Eli', 'Henning, Henny', 'Oddleif, Oddlaug', 'Ester, Iris',
			'Ragna, Ragnar', 'Annbjørg, Annlaug', 'Katinka, Cato',
			'Vilhelm, William, Willy', 'Magnar, Magnhild', 'Gard, Geir',
			'Pernille, Preben', 'June, Juni', 'Runa, Runar, Rune', 'Rasmus, Rakel',
			'Heidi, Heid', 'Torbjørg, Torbjørn, Torben', 'Gustav, Gyda',
			'Robert, Robin', 'Renate, René', 'Kolbein, Kolbjørn', 'Ingolf, Ingunn',
			'Borgar, Bjørge, Bjørg', 'Sigfrid, Sigrid, Siri', 'Tone, Tonje, Tanja',
			'Erlend, Erland', 'Vigdis, Viggo', 'Torhild, Toril, Tiril',
			'Botolv, Bodil', 'Bjarne, Bjørn', 'Erling, Elling', 'Salve, Sølve, Sølvi',
			'Agnar, Annar', 'Håkon, Maud', 'Elfrid, eldrid', 'Johannes, Jon, Hans',
			'Jørund, Jorunn', 'Jenny, Jonny', 'Aina, Ina, Ine', 'Lea, Leo, Leon',
			'Peter, Petter, Per', 'Solbjørg, Solgunn', 'Ask, Embla',
			'Kjartan, Kjellfrid', 'Andrea, Andrine, André', 'Ulrik, Ulla',
			'Mirjam, Mina', 'Torgrim, Torgunn', 'Håvard, Hulda',
			'Sunniva, Synnøve, Synne', 'Gøran, Jøran, Ørjan', 'Anita, Anja',
			'Kjetil, Kjell', 'Elias, Eldar', 'Mildrid, Melissa, Mia',
			'Solfrid, Solrun', 'Oddmund, Oddrun', 'Susanne, Sanna', 'Guttorm, Gorm',
			'Arnulf, Ørnulf', 'Gerhard, Gjert', 'Margareta, Margit, Marit',
			'Johanne, Janne, Jane', 'Malene, Malin, Mali', 'Brita, Brit, Britt',
			'Kristine, Kristin, Kristi', 'Jakob, Jack, Jim', 'Anna, Anne, Ane',
			'Marita, Rita', 'Reidar, Reidun', 'Olav, Ola, Ole',
			'Aurora, Audhild, Aud', 'Elin, Eline', 'Peder, Petra', 'Karen, Karin',
			'Oline, Oliver, Olve', 'Arnhild, Arna, Arne', 'Osvald, Oskar',
			'Gunnlaug, Gunnleiv', 'Didrik, Doris', 'Evy, Yvonne', 'Ronald, Ronny',
			'Lorents, Lars, Lasse', 'Torvald, Tarald', 'Klara, Camilla',
			'Anny, Anine, Ann', 'Hallgeir, Hallgjerd', 'Margot, Mary, Marielle',
			'Brynjulf, Brynhild', 'Verner, Wenche', 'Tormod, Torodd',
			'Sigvald, Sigve', 'Bernhard, Bernt', 'Ragnvald, Ragni', 'Harriet, Harry',
			'Signe, Signy', 'Belinda, Bertil', 'Ludvig, Lovise, Lousie',
			'Øyvind, Eivind, Even', 'Roald, Rolf', 'Artur, August', 'Johan, Jone, Jo',
			'Benjamin, Ben', 'Berta, Berte', 'Solveig, Solvor', 'Lisa, Lise, Liss',
			'Alise, Alvhild, Vilde', 'Ida, Idar', 'Brede, Brian, Njål',
			'Sollaug, Siril, Siv', 'Regine, Rose', 'Amalie, Alma, Allan',
			'Trygve, Tyra, Trym', 'Tord, Tor', 'Dagny, Dag', 'Jofrid, Jorid',
			'Stian, Stig', 'Ingebjørg, Ingeborg', 'Aslak, Eskil', 'Lillian, Lilly',
			'Hildebjørg, Hildegunn', 'Henriette, Henry', 'Konstanse, Connie',
			'Tobias, Tage', 'Trine, Trond', 'Kyrre, Kåre', 'Snorre, Snefrid',
			'Jan, Jens', 'Ingvar, Yngvar', 'Einar, Endre', 'Dagmar, Dagrun',
			'Lena, Lene', 'Mikael, Mikal, Mikkel', 'Helga, Helge, Hege',
			'Rebekka, Remi', 'Live, Liv', 'Evald, Evelyn', 'Frans, Frank',
			'Brynjar, Boye, Bo', 'Målfrid, Møyfrid', 'Birgitte, Birgit, Berit',
			'Benedikte, Bente', 'Leidulf, Leif', 'Fridtjof, Frida, Frits',
			'Kevin, Kennet, Kent', 'Valter, Vibeke', 'Torgeir, Terje, Tarjei',
			'Kaia, Kai', 'Hedvig, Hedda', 'Flemming, Finn', 'Marta, Marte',
			'Kjersti, Kjerstin', 'Tora, Tore', 'Henrik, Heine, Henrikke',
			'Bergljot, Birger', 'Karianne, Karine, Kine', 'Severin, Søren',
			'Eilif, Eivor', 'Margrete, Merete, Märtha', 'Amandus, Amanda',
			'Sturla, Sture', 'Simon, Simen', 'Noralf, Norunn', 'Aksel, Ånund, Ove',
			'Edit, Edna', 'Veslemøy, Vetle', 'Tove, Tuva', 'Raymond, Roy',
			'Otto, Ottar', 'Egil, Egon', 'Leonard, Lennart', 'Ingebrigt, Ingelin',
			'Ingvild, Yngvild', 'Tordis, Teodor', 'Gudbjørg, Gudveig',
			'Martin, Morten, Martine', 'Torkjell, Torkil', 'Kirsten, Kirsti',
			'Fredrik, Fred, Freddy', 'Oddfrid, Oddvar', 'Edmund, Edgar',
			'Hugo, Hogne, Hauk', 'Magne, Magny', 'Elisabeth, Lisbet',
			'Halvdan, Helle', 'Mariann, Marianne', 'Cecilie, Silje, Sissel',
			'Klement, Klaus', 'Gudrun, Guro', 'Katarina, Katrine, Kari',
			'Konrad, Kurt', 'Torlaug, Torleif', 'Ruben, Rut', 'Sofie, Sonja',
			'Andreas, Anders', 'Arnold, Arnljot, Arnt', 'Borghild, Borgny, Bård',
			'Sveinung, Svein', 'Barbara, Barbro', 'Stine, Ståle', 'Nils, Nikolai',
			'Hallfrid, Hallstein', 'Marlene, Marion, Morgan', 'Anniken, Annette',
			'Judit, Jytte', 'Daniel, Dan', 'Pia, Peggy', 'Lucia, Lydia',
			'Steinar, Stein', 'Hilda, Hilde', 'Oddbjørg, Oddbjørn', 'Inga, Inge',
			'Kristoffer, Kate', 'Iselin, Isak', 'Abraham, Amund', 'Tomas, Tom, Tommy',
			'Ingemar, Ingar', 'Sigurd, Sjur', 'Adam, Eva', 'Første juledag',
			'Stefan, Steffen', 'Narve, Natalie', 'Unni, Une, Unn', 'Vidar, Vemund',
			'David, Diana, Dina', 'Sylfest, Sylvia, Sylvi'
		),
		'se' => array(
			'Nyårsdagen', 'Svea, Sverker', 'Alfred, Alfrida', 'Rut, Ritva',
			'Hanna, Hannele', 'Kasper, Melker, Baltsar', 'August, Augusta',
			'Erland, Erhard', 'Gunnar, Gunder', 'Sigurd, Sigbritt, Sigmund',
			'Jan, Jannike, Hugo, Hagar', 'Frideborg, Fridolf', 'Knut',
			'Felix, Felicia', 'Laura, Lorentz, Liv', 'Hjalmar, Helmer, Hervor',
			'Anton, Tony', 'Hilda, Hildur', 'Henrik, Henry', 'Fabian, Sebastian',
			'Agnes, Agneta', 'Vincent, Viktor, Veine', 'Frej, Freja, Emilia, Emilie',
			'Erika, Eira', 'Paul, Pål', 'Bodil, Boel', 'Göte, Göta', 'Karl, Karla',
			'Diana, Valter, Vilma', 'Gunilla, Gunhild', 'Ivar, Joar',
			'Max, Maximilian, Magda', 'Marja, Mia', 'Disa, Hjördis', 'Ansgar, Anselm',
			'Agata, Agda, Lisa, Elise', 'Dorotea, Doris, Dora', 'Rikard, Dick',
			'Berta, Bert, Berthold', 'Fanny, Franciska, Betty', 'Iris, Egon, Egil',
			'Yngve, Inge, Ingolf', 'Evelina, Evy', 'Agne, Ove, Agnar',
			'Valentin, Tina', 'Sigfrid, Sigbritt', 'Julia, Julius, Jill',
			'Alexandra, Sandra', 'Frida, Fritiof, Fritz', 'Gabriella, Ella',
			'Vivianne, Rasmus, Ruben', 'Hilding, Hulda', 'Pia, Marina, Marlene',
			'Torsten, Torun', 'Mattias, Mats', 'Sigvard, Sivert', 'Torgny, Torkel',
			'Lage, Laila', 'Maria, Maja', 'Skottdagen', 'Albin, Elvira, Inez',
			'Ernst, Erna', 'Gunborg, Gunvor', 'Adrian, Adriana, Ada',
			'Tora, Tove, Tor', 'Ebba, Ebbe', 'Camilla, Isidor, Doris', 'Siv, Saga',
			'Torbjörn, Torleif', 'Edla, Ada, Ethel', 'Edvin, Egon, Elon',
			'Viktoria, Viktor', 'Greger, Iris', 'Matilda, Maud',
			'Kristoffer, Christel', 'Herbert, Gilbert', 'Gertrud',
			'Edvard, Edmund, Eddie', 'Josef, Josefina', 'Joakim, Kim', 'Bengt, Benny',
			'Kennet, Kent, Viking, Vilgot', 'Gerda, Gerd, Gert', 'Gabriel, Rafael',
			'Mary, Marion', 'Emanuel, Manne', 'Rudolf, Ralf, Raymond',
			'Malkolm, Morgan', 'Jonas, Jens', 'Holger, Holmfrid, Reidar',
			'Ester, Estrid', 'Harald, Hervor, Halvar',
			'Gudmund, Ingemund, Gunnel, Gun', 'Ferdinand, Nanna, Florence',
			'Marianne, Marlene', 'Irene, Irja', 'Vilhelm, Helmi, Willy',
			'Irma, Irmelin, Mimmi', 'Nadja, Tanja, Vanja, Ronja', 'Otto, Ottilia',
			'Ingvar, Ingvor', 'Ulf, Ylva', 'Liv, Julius, Gillis', 'Artur, Douglas',
			'Tiburtius, Tim', 'Olivia, Oliver', 'Patrik, Patricia', 'Elias, Elis',
			'Valdemar, Volmar', 'Olaus, Ola', 'Amalia, Amelie, Emelie',
			'Anneli, Annika', 'Allan, Glenn, Alida', 'Georg, Göran', 'Vega, Viveka',
			'Markus, Mark', 'Teresia, Terese', 'Engelbrekt, Enok', 'Ture, Tyra',
			'Tyko, Kennet, Kent', 'Mariana, Marianne', 'Valborg, Maj',
			'Filip, Filippa', 'John, Jane, Jack', 'Monika, Mona',
			'Gotthard, Erhard, Vivianne, Vivan', 'Marit, Rita',
			'Carina, Carita, Lilian, Lilly', 'Åke', 'Reidar, Reidun, Jonatan, Gideon',
			'Esbjörn, Styrbjörn, Elvira, Elvy', 'Märta, Märit', 'Charlotta, Lotta',
			'Linnea, Linn, Nina', 'Halvard, Halvar, Lillemor, Lill', 'Sofia, Sonja',
			'Ronald, Ronny, Hilma, Hilmer', 'Rebecka, Ruben, Nore, Nora',
			'Erik, Jerker', 'Maj, Majken, Majvor', 'Karolina, Carola, Lina',
			'Konstantin, Conny', 'Hemming, Henning', 'Desideria, Desirée, Renee',
			'Ivan, Vanja, Yvonne', 'Urban, Ursula', 'Vilhelmina, Vilma, Helmy',
			'Beda, Blenda', 'Ingeborg, Borghild', 'Yvonne, Jeanette, Jean',
			'Vera, Veronika, Fritiof, Frej', 'Petronella, Pernilla, Isabella, Isa',
			'Gun, Gunnel, Rune, Runa', 'Rutger, Roger', 'Ingemar, Gudmar',
			'Solbritt, Solveig', 'Bo, Boris', 'Gustav, Gösta', 'Robert, Robin',
			'Eivor, Majvor, Elaine', 'Börje, Birger, Petra, Petronella',
			'Svante, Boris, Kerstin, Karsten', 'Bertil, Berthold, Berit',
			'Eskil, Esbj', 'Aina, Aino, Eila', 'Håkan, Hakon', 'Margit, Margot, Mait',
			'Axel, Axelina', 'Torborg, Torvald', 'Björn, Bjarne',
			'Germund, Görel, Jerry', 'Linda, Linn', 'Alf, Alvar, Alva',
			'Paulina, Paula', 'Adolf, Alice, Adela', 'Johan, Jan', 'David, Salomon',
			'Rakel, Lea, Gunni, Jim', 'Selma, Fingal, Herta', 'Leo, Leopold',
			'Peter, Petra', 'Elof, Leif', 'Aron, Mirjam', 'Rosa, Rosita',
			'Aurora, Adina', 'Ulrika, Ulla', 'Laila, Ritva, Melker, Agaton',
			'Esaias, Jessika, Ronald, Ronny', 'Klas, Kaj', 'Kjell, Tjelvar',
			'Jörgen, Örjan', 'André, Andrea, Anund, Gunda', 'Eleonora, Ellinor',
			'Herman, Hermine', 'Joel, Judit', 'Folke, Odd', 'Ragnhild, Ragnvald',
			'Reinhold, Reine', 'Bruno, Alexis, Alice', 'Fredrik, Fritz, Fred',
			'Sara, Sally', 'Margareta, Greta', 'Johanna, Jane',
			'Magdalena, Madeleine', 'Emma, Emmy', 'Kristina, Kerstin, Stina',
			'Jakob, James', 'Jesper, Jessika', 'Marta, Moa', 'Botvid, Seved',
			'Olof, Olle', 'Algot, Margot', 'Helena, Elin, Elna', 'Per, Pernilla',
			'Karin, Kajsa', 'Tage, Tanja', 'Arne, Arnold', 'Ulrik, Alrik',
			'Alfons, Inez', 'Dennis, Denise, Donald', 'Silvia, Sylvia',
			'Roland, Roine', 'Lars, Lorentz', 'Susanna, Sanna', 'Klara, Clary',
			'Kaj, Hillevi, Gullvi', 'Uno, William, Bill', 'Stella, Estelle, Stefan',
			'Brynolf, Sigyn', 'Verner, Valter, Veronika', 'Ellen, Lena, Helena',
			'Magnus, Måns', 'Bernhard, Bernt', 'Jon, Jonna',
			'Henrietta, Henrika, Henny', 'Signe, Signhild', 'Bartolomeus, Bert',
			'Lovisa, Louise', 'Östen', 'Rolf, Raoul, Rudolf', 'Gurli, Leila, Gull',
			'Hans, Hampus', 'Albert, Albertina', 'Arvid, Vidar', 'Samuel, Sam',
			'Justus, Justina', 'Alfhild, Alva, Alfons', 'Gisela, Glenn',
			'Adela, Heidi, Harry, Harriet', 'Lilian, Lilly, Sakarias, Esaias',
			'Regina, Roy', 'Alma, Hulda, Ally', 'Anita, Annette, Anja',
			'Tord, Turid, Tove', 'Dagny, Helny, Daniela', 'Åsa, Åslög, Tyra',
			'Sture, Styrbj', 'Ida, Ellida', 'Sigrid, Siri', 'Dag, Daga',
			'Hildegard, Magnhild', 'Orvar, Alvar', 'Fredrika, Carita',
			'Elise, Lisa, Agda, Agata', 'Matteus, Ellen, Elly',
			'Maurits, Moritz, Morgan', 'Tekla, Tea', 'Gerhard, Gert', 'Tryggve',
			'Enar, Einar', 'Dagmar, Rigmor', 'Lennart, Leonard', 'Mikael, Mikaela',
			'Helge, Helny', 'Ragnar, Ragna', 'Ludvig, Love, Louis', 'Evald, Osvald',
			'Frans, Frank', 'Bror, Bruno', 'Jenny, Jennifer', 'Birgitta, Britta',
			'Nils, Nelly', 'Ingrid, Inger', 'Harry, Harriet, Helmer, Hadar',
			'Erling, Jarl', 'Valfrid, Manfred, Ernfrid', 'Berit, Birgit, Britt',
			'Stellan, Manfred, Helfrid', 'Hedvig, Hillevi, Hedda', 'Finn, Fingal',
			'Antonia, Toini, Annette', 'Lukas, Matteus', 'Tore, Torleif',
			'Sibylla, Camilla', 'Ursula, Yrsa, Birger', 'Marika, Marita',
			'Severin, Sören', 'Evert, Eilert', 'Inga, Ingalill, Ingvald',
			'Amanda, Rasmus, My', 'Sabina, Ina', 'Simon, Simone', 'Viola, Vivi',
			'Elsa, Isabella, Elsie', 'Edit, Edgar', 'Andre, Andrea', 'Tobias, Toini',
			'Hubert, Hugo, Diana', 'Sverker, Uno, Unn', 'Eugen, Eugenia',
			'Gustav Adolf', 'Ingegerd, Ingela', 'Vendela, Vanda',
			'Teodor, Teodora, Ted', 'Martin, Martina', 'Mårten', 'Konrad, Kurt',
			'Kristian, Krister', 'Emil, Emilia, Mildred', 'Leopold, Katja, Nadja',
			'Vibeke, Viveka, Edmund, Gudmund', 'Naemi, Naima, Nancy',
			'Lillemor, Moa, Pierre, Percy', 'Elisabet, Lisbeth',
			'Pontus, Marina, Pia', 'Helga, Olga', 'Cecilia, Sissela, Cornelia',
			'Klemens, Clarence', 'Gudrun, Rune, Runar', 'Katarina, Katja, Carina',
			'Linus, Love', 'Astrid, Asta', 'Malte, Malkolm', 'Sune, Synn',
			'Andreas, Anders', 'Oskar, Ossian', 'Beata, Beatrice', 'Lydia, Carola',
			'Barbara, Barbro', 'Sven, Svante', 'Nikolaus, Niklas', 'Angela, Angelika',
			'Virginia, Vera', 'Anna, Annie', 'Malin, Malena', 'Daniel, Daniela, Dan',
			'Alexander, Alexis, Alex', 'Lucia', 'Sten, Sixten, Stig',
			'Gottfrid, Gotthard', 'Assar, Astor', 'Stig, Inge, Ingemund',
			'Abraham, Efraim', 'Isak, Rebecka', 'Israel, Moses', 'Tomas, Tom',
			'Natanael, Jonatan, Natalia', 'Adam', 'Eva', 'Juldagen',
			'Stefan, Staffan', 'Johannes, Johan, Hannes', 'Benjamin',
			'Natalia, Natalie', 'Abel, Set, Gunl', 'Sylvester'
		)
	);
	// Get namedays
	$return = $namedays[ $country ][ $dayid ];
	// If display
	if( $display ) { echo $return; };
	return $return;
}
function kgyt_nameday_inposts( $content ) {
	// If there is the tag for nameday in the post
	if ( strpos( $content, "<!-- kgyt_nameday " ) !== false ) {
		$content = preg_replace( '/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content );
		if( preg_match( "/<!-- kgyt_nameday (\w\w) (\d+) -->/", $content, $matches1 ) ) {
			$temp_nameday1 = kgyt_nameday( false, $matches1[1], $matches1[2] - 0 );
			$content = preg_replace( "/<!-- kgyt_nameday \w\w \d+ -->/", $temp_nameday1, $content );
		};
		if( preg_match( "/<!-- kgyt_nameday (\d+) -->/", $content, $matches2 ) ) {
			$temp_nameday2 = kgyt_nameday( false, 'hu', $matches2[1] - 0 );
			$content = preg_replace( "/<!-- kgyt_nameday \d+ -->/", $temp_nameday2, $content );
		};
		if( preg_match( "/<!-- kgyt_nameday (\w\w) -->/", $content, $matches3 ) ) {
			$temp_nameday3 = kgyt_nameday( false, $matches3[1], 0 );
			$content = preg_replace( "/<!-- kgyt_nameday \w\w -->/", $temp_nameday3, $content );
		};
		$temp_nameday4 = kgyt_nameday( false, 'hu', 0 );
		$content = preg_replace( "/<!-- kgyt_nameday -->/", $temp_nameday4, $content );
	}
	return $content;
}
add_filter( 'the_content', 'kgyt_nameday_inposts' );
?>
