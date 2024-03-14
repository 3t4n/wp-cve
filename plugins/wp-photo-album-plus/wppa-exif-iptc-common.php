<?php
/* wppa-exif-iptc-common.php
* Package: wp-photo-album-plus
*
* exif and iptc common functions
* Version: 8.4.05.001
*
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

global $wppa_supported_camara_brands;
$wppa_supported_camara_brands = array( 'CANON', 'NIKON', 'SAMSUNG' );

// Translate iptc tags into  photo dependant data inside a text
function wppa_filter_iptc( $desc, $photo, $hide_empty = false ) {
global $wpdb;
global $wppa_iptc_labels;
global $wppa_iptc_cache;

	// Anything to do here?
	if ( strpos($desc, '2#') === false ) return $desc;

	// Get te labels if not yet present
	if ( ! is_array( $wppa_iptc_labels ) ) {
		$wppa_iptc_labels = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_iptc WHERE photo = '0' ORDER BY tag", ARRAY_A );
	}

	// If in cache, use it
	$iptcdata = false;
	if ( is_array( $wppa_iptc_cache ) ) {
		if ( isset( $wppa_iptc_cache[$photo] ) ) {
			$iptcdata = $wppa_iptc_cache[$photo];
		}
	}

	// Get the photo data
	if ( $iptcdata === false ) {
		$iptcdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_iptc WHERE photo=%s ORDER BY tag", $photo ), ARRAY_A );

		// Save in cache, even when empty
		$wppa_iptc_cache[$photo] = $iptcdata;
	}

	// Init
	$temp = $desc;
	$prevtag = '';
	$combined = '';

	// Process all iptclines of this photo
	if ( ! empty( $iptcdata ) ) {
		foreach ( $iptcdata as $iptcline ) {
			$tag = $iptcline['tag'];

			// Fix trailing 0000 for '2#055'
			if ( $tag == '2#055' ) {
				$t = $iptcline['description'];
				if ( strlen( $t ) == 8 && substr( $t, 4 ) == '0000' ) {
					$iptcline['description'] = substr( $t, 0, 4 );
				}
			}

			if ( $prevtag == $tag ) {			// add a next item for this tag
				$combined .= ', '.htmlspecialchars( strip_tags( $iptcline['description'] ) );
			}
			else { 							// first item of this tag
				if ( $combined ) { 			// Process if required
					$temp = str_replace( $prevtag, $combined, $temp );
				}
				$combined = htmlspecialchars( strip_tags( $iptcline['description'] ) );
				$prevtag = $tag;
			}
		}

		// Process last
		$temp = str_replace( $tag, $combined, $temp );

		// If no content, remove label also if required
		if ( ! $combined && $hide_empty ) {
			$t = substr( $tag, 0, 2 ) . 'L' . substr( $tag, 2 );
			$tag = $t;
			$temp = str_replace( $tag, '', $temp );
		}
	}

	// Process all labels
	if ( $wppa_iptc_labels ) {
		foreach( $wppa_iptc_labels as $iptclabel ) {
			$tag = $iptclabel['tag'];

			// convert 2#XXX to 2#LXXX to indicate the label
			$t = substr( $tag, 0, 2 ) . 'L' . substr( $tag, 2 );
			$tag = $t;
			$temp = str_replace( $tag, __( $iptclabel['description'] ), $temp );
		}
	}

	// Remove untranslated
	$pos = strpos($temp, '2#');
	while ( $pos !== false ) {
		$tmp = substr( $temp, 0, $pos ) . substr( $temp, $pos+5 );
		$temp = $tmp;
		$pos = strpos($temp, '2#');
	}

	return $temp;
}

// Translate exif tags into  photo dependant data inside a text
function wppa_filter_exif( $desc, $photo, $hide_empty = false ) {
global $wpdb;
global $wppa_exif_labels;
global $wppa_exif_cache;

	if ( strpos($desc, 'E#') === false ) return $desc;	// No tags in desc: Nothing to do

	// Get the labels if not yet present
	if ( ! is_array( $wppa_exif_labels ) ) {
		$wppa_exif_labels = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_exif WHERE photo = '0' ORDER BY tag", ARRAY_A );
	}

	// If in cache, use it
	$exifdata = false;
	if ( is_array( $wppa_exif_cache ) ) {
		if ( isset( $wppa_exif_cache[$photo] ) ) {
			$exifdata = $wppa_exif_cache[$photo];
		}
	}

	// Get the photo data
	if ( $exifdata === false ) {
		$exifdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_exif WHERE photo=%s ORDER BY tag", $photo ), ARRAY_A );

		// Save in cache, even when empty
		$wppa_exif_cache[$photo] = $exifdata;
	}

	// Init
	$temp = $desc;
	$prevtag = '';
	$combined = '';

	// Process all exiflines of this photo
	if ( ! empty( $exifdata ) ) {
		foreach ( $exifdata as $exifline ) {

			$tag = $exifline['tag'];
			if ( $prevtag == $tag ) {			// add a next item for this tag
				$combined .= ', ' . wppa_format_exif( $tag, $exifline['description'] );
			}
			else { 							// first item of this tag
				if ( $combined ) { 			// Process if required
					$temp = str_replace( $prevtag, $combined, $temp );
				}
				$combined = wppa_format_exif( $tag, $exifline['description'] );
				$prevtag = $tag;
			}
		}

		// Process last
		$temp = str_replace( $tag, $combined, $temp );

		// If no content, remove label also if required
		if ( ! $combined && $hide_empty ) {
			$t = substr( $tag, 0, 2 ) . 'L' . substr( $tag, 2 );
			$tag = $t;
			$temp = str_replace( $tag, '', $temp );
		}
	}

	// Process all labels
	if ( $wppa_exif_labels ) {
		foreach( $wppa_exif_labels as $exiflabel ) {
			$tag = $exiflabel['tag'];

			// convert E#XXX to E#LXXX to indicate the label
			$t = substr( $tag, 0, 2 ) . 'L' . substr( $tag, 2 );
			$tag = $t;

			$temp = str_replace( $tag, __( $exiflabel['description'] ), $temp );
		}
	}

	// Remove untranslated
	$groups = array( 'E#', 'F#', 'G#' );
	foreach( $groups as $group ) {
		$pos = strpos( $temp, $group );
		while ( $pos !== false ) {
			$tmp = substr( $temp, 0, $pos ) . substr( $temp, $pos+6 );
			$temp = $tmp;
			$pos = strpos( $temp, $group );
		}
	}

	// Return result
	return $temp;
}

function wppa_format_exif( $tag, $data, $brand = '' ) {
global $wppa_exif_error_output;

	if ( true || $data !== '' ) {

		// If rational, simplify it.
		if ( wppa_is_valid_rational( $data, false ) ) {
			$data = wppa_simplify_rational( $data );
		}

		// If array, make it readable
		if ( is_serialized( $data ) ) {
			$data_arr = wppa_unserialize( $data ); 	// This may cause Out of memory error
			if ( is_array( $data_arr ) ) {
				$data = implode( ', ', $data_arr );
			}
			else {
				$data_arr = null;
			}
		}
		else {
			$data_arr = null;
		}

		// Default:
		$result = $data;

		switch ( $tag ) {

			case 'E#0001': 	// InteropIndex / CanonCameraSettings (Canon)
				if ( $brand == 'CANON' ) {	// CanonCameraSettings (Canon)
					$result = $data;
				}
				else { 	// InteropIndex
					switch( $data ) {
						case 'R03': $result = __( 'R03 - DCF option file (Adobe RGB)', 'wp-photo-album-plus' ); break;
						case 'R98': $result = __( 'R98 - DCF basic file (sRGB)', 'wp-photo-album-plus' ); break;
						case 'THM': $result = __( 'THM - DCF thumbnail file', 'wp-photo-album-plus' ); break;
						default: $result = __( 'Undefined', 'wp-photo-album-plus' );
					}
				}
				break;

			case 'E#0002': 	// CanonFocalLength / DeviceType
				if ( $brand == 'SAMSUNG' ) { 	// DeviceType
					switch( $data ) {
						case 0x1000: $result = 'Compact Digital Camera'; break;
						case 0x2000: $result = 'High-end NX Camera'; break;
						case 0x3000: $result = 'HXM Video Camera'; break;
						case 0x12000: $result = 'Cell Phone'; break;
						case 0x300000: $result = 'SMX Video Camera'; break;
						default: $result = '?';
					}
				}
				if ( $brand == 'CANON' ) { 		// CanonFocalLength
					if ( is_array( $data_arr ) && count( $data_arr ) == 4 ) {

						// 0
						$result = 'FocalType: ';
						switch( $data_arr[0] ) {
							case 0: $result .= 'Fixed'; break;
							case 1: $result .= 'Zoom'; break;
							default: $result .= '?';
						}
						$result .= ', ';

						// 1
						$result .= 'Length: ' . $data_arr[1] . ', ';

						// 2
						$result .= 'XSize: ' . $data_arr[2] . ', ';

						// 3
						$result .= 'YSize: ' . $data_arr[3];
					}

				}
				break;

			case 'E#0003': 	// CanonFlashInfo? / SamsungModelID
				if ( $brand == 'CANON' ) { 		// CanonFlashInfo?
					$result = $data;
				}
				if ( $brand == 'SAMSUNG' ) { 	// SamsungModelID
					switch( $data ) {
						case 0x100101c: $result = 'NX10'; break;
						case 0x1001226: $result = 'HMX-S15BP'; break;
						case 0x1001233: $result = 'HMX-Q10'; break;
						case 0x1001234: $result = 'HMX-H304'; break;
						case 0x100130c: $result = 'NX100'; break;
						case 0x1001327: $result = 'NX11'; break;
						case 0x170104b: $result = 'ES65, ES67 / VLUU ES65, ES67 / SL50'; break;
						case 0x170104e: $result = 'ES70, ES71 / VLUU ES70, ES71 / SL600'; break;
						case 0x1701052: $result = 'ES73 / VLUU ES73 / SL605'; break;
						case 0x1701055: $result = 'ES25, ES27 / VLUU ES25, ES27 / SL45'; break;
						case 0x1701300: $result = 'ES28 / VLUU ES28'; break;
						case 0x1701303: $result = 'ES74,ES75,ES78 / VLUU ES75,ES78'; break;
						case 0x2001046: $result = 'PL150 / VLUU PL150 / TL210 / PL151'; break;
						case 0x2001048: $result = 'PL100 / TL205 / VLUU PL100 / PL101'; break;
						case 0x2001311: $result = 'PL120,PL121 / VLUU PL120,PL121'; break;
						case 0x2001315: $result = 'PL170,PL171 / VLUUPL170,PL171'; break;
						case 0x200131e: $result = 'PL210, PL211 / VLUU PL210, PL211'; break;
						case 0x2701317: $result = 'PL20,PL21 / VLUU PL20,PL21'; break;
						case 0x2a0001b: $result = 'WP10 / VLUU WP10 / AQ100'; break;
						case 0x3000000: $result = 'Various Models (0x3000000)'; break;
						case 0x3a00018: $result = 'Various Models (0x3a00018)'; break;
						case 0x400101f: $result = 'ST1000 / ST1100 / VLUU ST1000 / CL65'; break;
						case 0x4001022: $result = 'ST550 / VLUU ST550 / TL225'; break;
						case 0x4001025: $result = 'Various Models (0x4001025)'; break;
						case 0x400103e: $result = 'VLUU ST5500, ST5500, CL80'; break;
						case 0x4001041: $result = 'VLUU ST5000, ST5000, TL240'; break;
						case 0x4001043: $result = 'ST70 / VLUU ST70 / ST71'; break;
						case 0x400130a: $result = 'Various Models (0x400130a)'; break;
						case 0x400130e: $result = 'ST90,ST91 / VLUU ST90,ST91'; break;
						case 0x4001313: $result = 'VLUU ST95, ST95'; break;
						case 0x4a00015: $result = 'VLUU ST60'; break;
						case 0x4a0135b: $result = 'ST30, ST65 / VLUU ST65 / ST67'; break;
						case 0x5000000: $result = 'Various Models (0x5000000)'; break;
						case 0x5001038: $result = 'Various Models (0x5001038)'; break;
						case 0x500103a: $result = 'WB650 / VLUU WB650 / WB660'; break;
						case 0x500103c: $result = 'WB600 / VLUU WB600 / WB610'; break;
						case 0x500133e: $result = 'WB150 / WB150F / WB152 / WB152F / WB151'; break;
						case 0x5a0000f: $result = 'WB5000 / HZ25W'; break;
						case 0x6001036: $result = 'EX1'; break;
						case 0x700131c: $result = 'VLUU SH100, SH100'; break;
						case 0x27127002: $result = 'SMX-C20N'; break;
						default: $result = '?';
					}
				}
				break;

			case 'E#0004': 	// CanonShotInfo / Quality (Nikon)
			case 'E#0005': 	// CanonPanorama / WhiteBalance (Nikon)
			case 'E#0006': 	// CanonImageType / Sharpness (Nikon)
			case 'E#0007': 	// CanonFirmwareVersion / FocusMode (Nikon)
			case 'E#0008': 	// FileNumber (Canon) / FlashSetting (Nikon)
			case 'E#0009': 	// OwnerName (Canon) / FlashType (Nikon)
			case 'E#000A': 	// UnknownD30 (Canon)
			case 'E#000B': 	// WhiteBalanceFineTune (Nikon)
			case 'E#000C': 	// SerialNumber (Canon) / WB_RBLevels (Nikon)
				break;

			case 'E#000D': 	// CanonCameraInfo / ProgramShift (Nikon)
				if ( $brand == 'CANON' ) {
					str_replace( 'CanonCanon', 'Canon', $result );
				}
				break;

			case 'E#000E': 	// CanonFileLength / ExposureDifference (Nikon)
				if ( $brand == 'CANON' ) {
					$result .= ' bytes.';
				}
				break;

			case 'E#000F': 	// CustomFunctions (Canon) / ISOSelection (Nikon)
				break;

			case 'E#0010': 	// CanonModelID (Canon) / DataDump (Nikon)
				if ( $brand == 'CANON' ) { 		// CanonModelID (Canon)
					$data = dechex( $data );
					switch( $data ) {
						case '1010000': $result	= 'PowerShot A30'; break;
						case '1040000': $result	= 'PowerShot S300 / Digital IXUS 300 / IXY Digital 300'; break;
						case '1060000': $result	= 'PowerShot A20'; break;
						case '1080000': $result	= 'PowerShot A10'; break;
						case '1090000': $result	= 'PowerShot S110 / Digital IXUS v / IXY Digital 200'; break;
						case '1100000': $result	= 'PowerShot G2'; break;
						case '1110000': $result	= 'PowerShot S40'; break;
						case '1120000': $result	= 'PowerShot S30'; break;
						case '1130000': $result	= 'PowerShot A40'; break;
						case '1140000': $result	= 'EOS D30'; break;
						case '1150000': $result	= 'PowerShot A100'; break;
						case '1160000': $result	= 'PowerShot S200 / Digital IXUS v2 / IXY Digital 200a'; break;
						case '1170000': $result	= 'PowerShot A200'; break;
						case '1180000': $result	= 'PowerShot S330 / Digital IXUS 330 / IXY Digital 300a'; break;
						case '1190000': $result	= 'PowerShot G3'; break;
						case '1210000': $result	= 'PowerShot S45'; break;
						case '1230000': $result	= 'PowerShot SD100 / Digital IXUS II / IXY Digital 30'; break;
						case '1240000': $result	= 'PowerShot S230 / Digital IXUS v3 / IXY Digital 320'; break;
						case '1250000': $result	= 'PowerShot A70'; break;
						case '1260000': $result	= 'PowerShot A60'; break;
						case '1270000': $result	= 'PowerShot S400 / Digital IXUS 400 / IXY Digital 400'; break;
						case '1290000': $result	= 'PowerShot G5'; break;
						case '1300000': $result	= 'PowerShot A300'; break;
						case '1310000': $result	= 'PowerShot S50'; break;
						case '1340000': $result	= 'PowerShot A80'; break;
						case '1350000': $result	= 'PowerShot SD10 / Digital IXUS i / IXY Digital L'; break;
						case '1360000': $result	= 'PowerShot S1 IS'; break;
						case '1370000': $result	= 'PowerShot Pro1'; break;
						case '1380000': $result	= 'PowerShot S70'; break;
						case '1390000': $result	= 'PowerShot S60'; break;
						case '1400000': $result	= 'PowerShot G6'; break;
						case '1410000': $result	= 'PowerShot S500 / Digital IXUS 500 / IXY Digital 500'; break;
						case '1420000': $result	= 'PowerShot A75'; break;
						case '1440000': $result	= 'PowerShot SD110 / Digital IXUS IIs / IXY Digital 30a'; break;
						case '1450000': $result	= 'PowerShot A400'; break;
						case '1470000': $result	= 'PowerShot A310'; break;
						case '1490000': $result	= 'PowerShot A85'; break;
						case '1520000': $result	= 'PowerShot S410 / Digital IXUS 430 / IXY Digital 450'; break;
						case '1530000': $result	= 'PowerShot A95'; break;
						case '1540000': $result	= 'PowerShot SD300 / Digital IXUS 40 / IXY Digital 50'; break;
						case '1550000': $result	= 'PowerShot SD200 / Digital IXUS 30 / IXY Digital 40'; break;
						case '1560000': $result	= 'PowerShot A520'; break;
						case '1570000': $result	= 'PowerShot A510'; break;
						case '1590000': $result	= 'PowerShot SD20 / Digital IXUS i5 / IXY Digital L2'; break;
						case '1640000': $result	= 'PowerShot S2 IS'; break;
						case '1650000': $result	= 'PowerShot SD430 / Digital IXUS Wireless / IXY Digital Wireless'; break;
						case '1660000': $result	= 'PowerShot SD500 / Digital IXUS 700 / IXY Digital 600'; break;
						case '1668000': $result	= 'EOS D60'; break;
						case '1700000': $result	= 'PowerShot SD30 / Digital IXUS i Zoom / IXY Digital L3'; break;
						case '1740000': $result	= 'PowerShot A430'; break;
						case '1750000': $result	= 'PowerShot A410'; break;
						case '1760000': $result	= 'PowerShot S80'; break;
						case '1780000': $result	= 'PowerShot A620'; break;
						case '1790000': $result	= 'PowerShot A610'; break;
						case '1800000': $result	= 'PowerShot SD630 / Digital IXUS 65 / IXY Digital 80'; break;
						case '1810000': $result	= 'PowerShot SD450 / Digital IXUS 55 / IXY Digital 60'; break;
						case '1820000': $result	= 'PowerShot TX1'; break;
						case '1870000': $result	= 'PowerShot SD400 / Digital IXUS 50 / IXY Digital 55'; break;
						case '1880000': $result	= 'PowerShot A420'; break;
						case '1890000': $result	= 'PowerShot SD900 / Digital IXUS 900 Ti / IXY Digital 1000'; break;
						case '1900000': $result	= 'PowerShot SD550 / Digital IXUS 750 / IXY Digital 700'; break;
						case '1920000': $result	= 'PowerShot A700'; break;
						case '1940000': $result	= 'PowerShot SD700 IS / Digital IXUS 800 IS / IXY Digital 800 IS'; break;
						case '1950000': $result	= 'PowerShot S3 IS'; break;
						case '1960000': $result	= 'PowerShot A540'; break;
						case '1970000': $result	= 'PowerShot SD600 / Digital IXUS 60 / IXY Digital 70'; break;
						case '1980000': $result	= 'PowerShot G7'; break;
						case '1990000': $result	= 'PowerShot A530'; break;
						case '2000000': $result	= 'PowerShot SD800 IS / Digital IXUS 850 IS / IXY Digital 900 IS'; break;
						case '2010000': $result	= 'PowerShot SD40 / Digital IXUS i7 / IXY Digital L4'; break;
						case '2020000': $result	= 'PowerShot A710 IS'; break;
						case '2030000': $result	= 'PowerShot A640'; break;
						case '2040000': $result	= 'PowerShot A630'; break;
						case '2090000': $result	= 'PowerShot S5 IS'; break;
						case '2100000': $result	= 'PowerShot A460'; break;
						case '2120000': $result	= 'PowerShot SD850 IS / Digital IXUS 950 IS / IXY Digital 810 IS'; break;
						case '2130000': $result	= 'PowerShot A570 IS'; break;
						case '2140000': $result	= 'PowerShot A560'; break;
						case '2150000': $result	= 'PowerShot SD750 / Digital IXUS 75 / IXY Digital 90'; break;
						case '2160000': $result	= 'PowerShot SD1000 / Digital IXUS 70 / IXY Digital 10'; break;
						case '2180000': $result	= 'PowerShot A550'; break;
						case '2190000': $result	= 'PowerShot A450'; break;
						case '2230000': $result	= 'PowerShot G9'; break;
						case '2240000': $result	= 'PowerShot A650 IS'; break;
						case '2260000': $result	= 'PowerShot A720 IS'; break;
						case '2290000': $result	= 'PowerShot SX100 IS'; break;
						case '2300000': $result	= 'PowerShot SD950 IS / Digital IXUS 960 IS / IXY Digital 2000 IS'; break;
						case '2310000': $result	= 'PowerShot SD870 IS / Digital IXUS 860 IS / IXY Digital 910 IS'; break;
						case '2320000': $result	= 'PowerShot SD890 IS / Digital IXUS 970 IS / IXY Digital 820 IS'; break;
						case '2360000': $result	= 'PowerShot SD790 IS / Digital IXUS 90 IS / IXY Digital 95 IS'; break;
						case '2370000': $result	= 'PowerShot SD770 IS / Digital IXUS 85 IS / IXY Digital 25 IS'; break;
						case '2380000': $result	= 'PowerShot A590 IS'; break;
						case '2390000': $result	= 'PowerShot A580'; break;
						case '2420000': $result	= 'PowerShot A470'; break;
						case '2430000': $result	= 'PowerShot SD1100 IS / Digital IXUS 80 IS / IXY Digital 20 IS'; break;
						case '2460000': $result	= 'PowerShot SX1 IS'; break;
						case '2470000': $result	= 'PowerShot SX10 IS'; break;
						case '2480000': $result	= 'PowerShot A1000 IS'; break;
						case '2490000': $result	= 'PowerShot G10'; break;
						case '2510000': $result	= 'PowerShot A2000 IS'; break;
						case '2520000': $result	= 'PowerShot SX110 IS'; break;
						case '2530000': $result	= 'PowerShot SD990 IS / Digital IXUS 980 IS / IXY Digital 3000 IS'; break;
						case '2540000': $result	= 'PowerShot SD880 IS / Digital IXUS 870 IS / IXY Digital 920 IS'; break;
						case '2550000': $result	= 'PowerShot E1'; break;
						case '2560000': $result	= 'PowerShot D10'; break;
						case '2570000': $result	= 'PowerShot SD960 IS / Digital IXUS 110 IS / IXY Digital 510 IS'; break;
						case '2580000': $result	= 'PowerShot A2100 IS'; break;
						case '2590000': $result	= 'PowerShot A480'; break;
						case '2600000': $result	= 'PowerShot SX200 IS'; break;
						case '2610000': $result	= 'PowerShot SD970 IS / Digital IXUS 990 IS / IXY Digital 830 IS'; break;
						case '2620000': $result	= 'PowerShot SD780 IS / Digital IXUS 100 IS / IXY Digital 210 IS'; break;
						case '2630000': $result	= 'PowerShot A1100 IS'; break;
						case '2640000': $result	= 'PowerShot SD1200 IS / Digital IXUS 95 IS / IXY Digital 110 IS'; break;
						case '2700000': $result	= 'PowerShot G11'; break;
						case '2710000': $result	= 'PowerShot SX120 IS'; break;
						case '2720000': $result	= 'PowerShot S90'; break;
						case '2750000': $result	= 'PowerShot SX20 IS'; break;
						case '2760000': $result	= 'PowerShot SD980 IS / Digital IXUS 200 IS / IXY Digital 930 IS'; break;
						case '2770000': $result	= 'PowerShot SD940 IS / Digital IXUS 120 IS / IXY Digital 220 IS'; break;
						case '2800000': $result	= 'PowerShot A495'; break;
						case '2810000': $result	= 'PowerShot A490'; break;
						case '2820000': $result	= 'PowerShot A3100/A3150 IS'; break;
						case '2830000': $result	= 'PowerShot A3000 IS'; break;
						case '2840000': $result	= 'PowerShot SD1400 IS / IXUS 130 / IXY 400F'; break;
						case '2850000': $result	= 'PowerShot SD1300 IS / IXUS 105 / IXY 200F'; break;
						case '2860000': $result	= 'PowerShot SD3500 IS / IXUS 210 / IXY 10S'; break;
						case '2870000': $result	= 'PowerShot SX210 IS'; break;
						case '2880000': $result	= 'PowerShot SD4000 IS / IXUS 300 HS / IXY 30S'; break;
						case '2890000': $result	= 'PowerShot SD4500 IS / IXUS 1000 HS / IXY 50S'; break;
						case '2920000': $result	= 'PowerShot G12'; break;
						case '2930000': $result	= 'PowerShot SX30 IS'; break;
						case '2940000': $result	= 'PowerShot SX130 IS'; break;
						case '2950000': $result	= 'PowerShot S95'; break;
						case '2980000': $result	= 'PowerShot A3300 IS'; break;
						case '2990000': $result	= 'PowerShot A3200 IS'; break;
						case '3000000': $result	= 'PowerShot ELPH 500 HS / IXUS 310 HS / IXY 31S'; break;
						case '3010000': $result	= 'PowerShot Pro90 IS'; break;
						case '3010001': $result	= 'PowerShot A800'; break;
						case '3020000': $result	= 'PowerShot ELPH 100 HS / IXUS 115 HS / IXY 210F'; break;
						case '3030000': $result	= 'PowerShot SX230 HS'; break;
						case '3040000': $result	= 'PowerShot ELPH 300 HS / IXUS 220 HS / IXY 410F'; break;
						case '3050000': $result	= 'PowerShot A2200'; break;
						case '3060000': $result	= 'PowerShot A1200'; break;
						case '3070000': $result	= 'PowerShot SX220 HS'; break;
						case '3080000': $result	= 'PowerShot G1 X'; break;
						case '3090000': $result	= 'PowerShot SX150 IS'; break;
						case '3100000': $result	= 'PowerShot ELPH 510 HS / IXUS 1100 HS / IXY 51S'; break;
						case '3110000': $result	= 'PowerShot S100 (new)'; break;
						case '3120000': $result	= 'PowerShot ELPH 310 HS / IXUS 230 HS / IXY 600F'; break;
						case '3130000': $result	= 'PowerShot SX40 HS'; break;
						case '3140000': $result	= 'IXY 32S'; break;
						case '3160000': $result	= 'PowerShot A1300'; break;
						case '3170000': $result	= 'PowerShot A810'; break;
						case '3180000': $result	= 'PowerShot ELPH 320 HS / IXUS 240 HS / IXY 420F'; break;
						case '3190000': $result	= 'PowerShot ELPH 110 HS / IXUS 125 HS / IXY 220F'; break;
						case '3200000': $result	= 'PowerShot D20'; break;
						case '3210000': $result	= 'PowerShot A4000 IS'; break;
						case '3220000': $result	= 'PowerShot SX260 HS'; break;
						case '3230000': $result	= 'PowerShot SX240 HS'; break;
						case '3240000': $result	= 'PowerShot ELPH 530 HS / IXUS 510 HS / IXY 1'; break;
						case '3250000': $result	= 'PowerShot ELPH 520 HS / IXUS 500 HS / IXY 3'; break;
						case '3260000': $result	= 'PowerShot A3400 IS'; break;
						case '3270000': $result	= 'PowerShot A2400 IS'; break;
						case '3280000': $result	= 'PowerShot A2300'; break;
						case '3330000': $result	= 'PowerShot G15'; break;
						case '3340000': $result	= 'PowerShot SX50 HS'; break;
						case '3350000': $result	= 'PowerShot SX160 IS'; break;
						case '3360000': $result	= 'PowerShot S110 (new)'; break;
						case '3370000': $result	= 'PowerShot SX500 IS'; break;
						case '3380000': $result	= 'PowerShot N'; break;
						case '3390000': $result	= 'IXUS 245 HS / IXY 430F'; break;
						case '3400000': $result	= 'PowerShot SX280 HS'; break;
						case '3410000': $result	= 'PowerShot SX270 HS'; break;
						case '3420000': $result	= 'PowerShot A3500 IS'; break;
						case '3430000': $result	= 'PowerShot A2600'; break;
						case '3440000': $result	= 'PowerShot SX275 HS'; break;
						case '3450000': $result	= 'PowerShot A1400'; break;
						case '3460000': $result	= 'PowerShot ELPH 130 IS / IXUS 140 / IXY 110F'; break;
						case '3470000': $result	= 'PowerShot ELPH 115/120 IS / IXUS 132/135 / IXY 90F/100F'; break;
						case '3490000': $result	= 'PowerShot ELPH 330 HS / IXUS 255 HS / IXY 610F'; break;
						case '3510000': $result	= 'PowerShot A2500'; break;
						case '3540000': $result	= 'PowerShot G16'; break;
						case '3550000': $result	= 'PowerShot S120'; break;
						case '3560000': $result	= 'PowerShot SX170 IS'; break;
						case '3580000': $result	= 'PowerShot SX510 HS'; break;
						case '3590000': $result	= 'PowerShot S200 (new)'; break;
						case '3600000': $result	= 'IXY 620F'; break;
						case '3610000': $result	= 'PowerShot N100'; break;
						case '3640000': $result	= 'PowerShot G1 X Mark II'; break;
						case '3650000': $result	= 'PowerShot D30'; break;
						case '3660000': $result	= 'PowerShot SX700 HS'; break;
						case '3670000': $result	= 'PowerShot SX600 HS'; break;
						case '3680000': $result	= 'PowerShot ELPH 140 IS / IXUS 150 / IXY 130'; break;
						case '3690000': $result	= 'PowerShot ELPH 135 / IXUS 145 / IXY 120'; break;
						case '3700000': $result	= 'PowerShot ELPH 340 HS / IXUS 265 HS / IXY 630'; break;
						case '3710000': $result	= 'PowerShot ELPH 150 IS / IXUS 155 / IXY 140'; break;
						case '3740000': $result	= 'EOS M3'; break;
						case '3750000': $result	= 'PowerShot SX60 HS'; break;
						case '3760000': $result	= 'PowerShot SX520 HS'; break;
						case '3770000': $result	= 'PowerShot SX400 IS'; break;
						case '3780000': $result	= 'PowerShot G7 X'; break;
						case '3790000': $result	= 'PowerShot N2'; break;
						case '3800000': $result	= 'PowerShot SX530 HS'; break;
						case '3820000': $result	= 'PowerShot SX710 HS'; break;
						case '3830000': $result	= 'PowerShot SX610 HS'; break;
						case '3840000': $result	= 'EOS M10'; break;
						case '3850000': $result	= 'PowerShot G3 X'; break;
						case '3860000': $result	= 'PowerShot ELPH 165 HS / IXUS 165 / IXY 160'; break;
						case '3870000': $result	= 'PowerShot ELPH 160 / IXUS 160'; break;
						case '3880000': $result	= 'PowerShot ELPH 350 HS / IXUS 275 HS / IXY 640'; break;
						case '3890000': $result	= 'PowerShot ELPH 170 IS / IXUS 170'; break;
						case '3910000': $result	= 'PowerShot SX410 IS'; break;
						case '3930000': $result	= 'PowerShot G9 X'; break;
						case '3940000': $result	= 'EOS M5'; break;
						case '3950000': $result	= 'PowerShot G5 X'; break;
						case '3970000': $result	= 'PowerShot G7 X Mark II'; break;
						case '3980000': $result	= 'EOS M100'; break;
						case '3990000': $result	= 'PowerShot ELPH 360 HS / IXUS 285 HS / IXY 650'; break;
						case '4010000': $result	= 'PowerShot SX540 HS'; break;
						case '4020000': $result	= 'PowerShot SX420 IS'; break;
						case '4030000': $result	= 'PowerShot ELPH 190 IS / IXUS 180 / IXY 190'; break;
						case '4040000': $result	= 'PowerShot G1'; break;
						case '4040001': $result	= 'IXY 180'; break;
						case '4050000': $result	= 'PowerShot SX720 HS'; break;
						case '4060000': $result	= 'PowerShot SX620 HS'; break;
						case '4070000': $result	= 'EOS M6'; break;
						case '4100000': $result	= 'PowerShot G9 X Mark II'; break;
						case '4150000': $result	= 'PowerShot ELPH 185 / IXUS 185 / IXY 200'; break;
						case '4160000': $result	= 'PowerShot SX430 IS'; break;
						case '4170000': $result	= 'PowerShot SX730 HS'; break;
						case '4180000': $result	= 'PowerShot G1 X Mark III'; break;
						case '6040000': $result	= 'PowerShot S100 / Digital IXUS / IXY Digital'; break;
						case '4007d673': $result = 'DC19/DC21/DC22'; break;
						case '4007d674': $result = 'XH A1'; break;
						case '4007d675': $result = 'HV10'; break;
						case '4007d676': $result = 'MD130/MD140/MD150/MD160/ZR850'; break;
						case '4007d777': $result = 'DC50'; break;
						case '4007d778': $result = 'HV20'; break;
						case '4007d779': $result = 'DC211'; break;
						case '4007d77a': $result = 'HG10'; break;
						case '4007d77b': $result = 'HR10'; break;
						case '4007d77d': $result = 'MD255/ZR950'; break;
						case '4007d81c': $result = 'HF11'; break;
						case '4007d878': $result = 'HV30'; break;
						case '4007d87c': $result = 'XH A1S'; break;
						case '4007d87e': $result = 'DC301/DC310/DC311/DC320/DC330'; break;
						case '4007d87f': $result = 'FS100'; break;
						case '4007d880': $result = 'HF10'; break;
						case '4007d882': $result = 'HG20/HG21'; break;
						case '4007d925': $result = 'HF21'; break;
						case '4007d926': $result = 'HF S11'; break;
						case '4007d978': $result = 'HV40'; break;
						case '4007d987': $result = 'DC410/DC411/DC420'; break;
						case '4007d988': $result = 'FS19/FS20/FS21/FS22/FS200'; break;
						case '4007d989': $result = 'HF20/HF200'; break;
						case '4007d98a': $result = 'HF S10/S100'; break;
						case '4007da8e': $result = 'HF R10/R16/R17/R18/R100/R106'; break;
						case '4007da8f': $result = 'HF M30/M31/M36/M300/M306'; break;
						case '4007da90': $result = 'HF S20/S21/S200'; break;
						case '4007da92': $result = 'FS31/FS36/FS37/FS300/FS305/FS306/FS307'; break;
						case '4007dca0': $result = 'EOS C300'; break;
						case '4007dda9': $result = 'HF G25'; break;
						case '4007dfb4': $result = 'XC10'; break;
						case '80000001': $result = 'EOS-1D'; break;
						case '80000167': $result = 'EOS-1DS'; break;
						case '80000168': $result = 'EOS 10D'; break;
						case '80000169': $result = 'EOS-1D Mark III'; break;
						case '80000170': $result = 'EOS Digital Rebel / 300D / Kiss Digital'; break;
						case '80000174': $result = 'EOS-1D Mark II'; break;
						case '80000175': $result = 'EOS 20D'; break;
						case '80000176': $result = 'EOS Digital Rebel XSi / 450D / Kiss X2'; break;
						case '80000188': $result = 'EOS-1Ds Mark II'; break;
						case '80000189': $result = 'EOS Digital Rebel XT / 350D / Kiss Digital N'; break;
						case '80000190': $result = 'EOS 40D'; break;
						case '80000213': $result = 'EOS 5D'; break;
						case '80000215': $result = 'EOS-1Ds Mark III'; break;
						case '80000218': $result = 'EOS 5D Mark II'; break;
						case '80000219': $result = 'WFT-E1'; break;
						case '80000232': $result = 'EOS-1D Mark II N'; break;
						case '80000234': $result = 'EOS 30D'; break;
						case '80000236': $result = 'EOS Digital Rebel XTi / 400D / Kiss Digital X'; break;
						case '80000241': $result = 'WFT-E2'; break;
						case '80000246': $result = 'WFT-E3'; break;
						case '80000250': $result = 'EOS 7D'; break;
						case '80000252': $result = 'EOS Rebel T1i / 500D / Kiss X3'; break;
						case '80000254': $result = 'EOS Rebel XS / 1000D / Kiss F'; break;
						case '80000261': $result = 'EOS 50D'; break;
						case '80000269': $result = 'EOS-1D X'; break;
						case '80000270': $result = 'EOS Rebel T2i / 550D / Kiss X4'; break;
						case '80000271': $result = 'WFT-E4'; break;
						case '80000273': $result = 'WFT-E5'; break;
						case '80000281': $result = 'EOS-1D Mark IV'; break;
						case '80000285': $result = 'EOS 5D Mark III'; break;
						case '80000286': $result = 'EOS Rebel T3i / 600D / Kiss X5'; break;
						case '80000287': $result = 'EOS 60D'; break;
						case '80000288': $result = 'EOS Rebel T3 / 1100D / Kiss X50'; break;
						case '80000289': $result = 'EOS 7D Mark II'; break;
						case '80000297': $result = 'WFT-E2 II'; break;
						case '80000298': $result = 'WFT-E4 II'; break;
						case '80000301': $result = 'EOS Rebel T4i / 650D / Kiss X6i'; break;
						case '80000302': $result = 'EOS 6D'; break;
						case '80000324': $result = 'EOS-1D C'; break;
						case '80000325': $result = 'EOS 70D'; break;
						case '80000326': $result = 'EOS Rebel T5i / 700D / Kiss X7i'; break;
						case '80000327': $result = 'EOS Rebel T5 / 1200D / Kiss X70'; break;
						case '80000328': $result = 'EOS-1D X MARK II'; break;
						case '80000331': $result = 'EOS M'; break;
						case '80000346': $result = 'EOS Rebel SL1 / 100D / Kiss X7'; break;
						case '80000347': $result = 'EOS Rebel T6s / 760D / 8000D'; break;
						case '80000349': $result = 'EOS 5D Mark IV'; break;
						case '80000350': $result = 'EOS 80D'; break;
						case '80000355': $result = 'EOS M2'; break;
						case '80000382': $result = 'EOS 5DS'; break;
						case '80000393': $result = 'EOS Rebel T6i / 750D / Kiss X8i'; break;
						case '80000401': $result = 'EOS 5DS R'; break;
						case '80000404': $result = 'EOS Rebel T6 / 1300D / Kiss X80'; break;
						case '80000405': $result = 'EOS Rebel T7i / 800D / Kiss X9i'; break;
						case '80000406': $result = 'EOS 6D Mark II'; break;
						case '80000408': $result = 'EOS 77D / 9000D'; break;
						case '80000417': $result = 'EOS Rebel SL2 / 200D / Kiss X9'; break;
						default: $result = '?';
					}
				}
				if ( $brand == 'NIKON' ) { 		// DataDump (Nikon)
					$result = $data;
				}
				break;

			case 'E#0011': 	// MovieInfo / OrientationInfo
				if ( $brand == 'SAMSUNG' ) { 		// OrientationInfo

					if ( ! wppa_is_valid_rational( $data ) ) {
						return $wppa_exif_error_output;
					}

					$temp = explode( '/', $data );
					$x = $temp[0];
					$y = $temp[1];

					$result = ( $x / $y ) . ' ' . __( 'degrees', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0012': 	// CanonAFInfo
				if ( $brand == 'CANON' ) {
					if ( is_array( $data_arr ) && count( $data_arr ) >= 12 ) {
						$result = '';
						$result .= 'NumAFPoints:' . $data_arr[0] . ', ';
						$result .= 'ValidAFPoints:' . $data_arr[1] . ', ';
						$result .= 'CanonImageWidth:' . $data_arr[2] . ', ';
						$result .= 'CanonImageHeight:' . $data_arr[3] . ', ';
						$result .= 'AFImageWidth:' . $data_arr[4] . ', ';
						$result .= 'AFImageHeight:' . $data_arr[5] . ', ';
						$result .= 'AFAreaWidths:' . $data_arr[6] . ', ';
						$result .= 'AFAreaHeights:' . $data_arr[7] . ', ';
						$result .= 'AFAreaXPositions:' . $data_arr[8] . ', ';
						$result .= 'AFAreaYPositions:' . $data_arr[9] . ', ';
						$result .= 'AFPointsInFocus:' . $data_arr[10] . ', ';
						$result .= 'PrimaryAFPoint:' . $data_arr[11] . ', ';
						if ( isset( $data_arr[12] ) ) {
							$result .= 'PrimaryAFPoint:' . $data_arr[12] . ', ';
						}
						$result = trim( $result, ', ' );
					}
				}
				break;

			case 'E#0013': 	// ThumbnailImageValidArea
				break;

			case 'E#0015': 	// SerialNumberFormat

				switch( $brand ) {
					case 'CANON':
						if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;
						$data = dechex( $data );
						switch( $data ) {
							case '90000000': $result = __( 'Format 1', 'wp-photo-album-plus' ); break;
							case 'a0000000': $result = __( 'Format 2', 'wp-photo-album-plus' ); break;
							default: $result = __( 'undefined', 'wp-photo-album-plus' );
						}
						break;
					default:
						$result = $data;
						break;
				}
				break;

			case 'E#001A': 	// SuperMacro
			case 'E#001C': 	// DateStampMode
				break;

			case 'E#001D': 	// MyColors
/*
2 	MyColorMode 	int16u
0 = Off
1 = Positive Film
2 = Light Skin Tone
3 = Dark Skin Tone
4 = Vivid Blue
5 = Vivid Green
6 = Vivid Red
7 = Color Accent
8 = Color Swap
9 = Custom
12 = Vivid
13 = Neutral
14 = Sepia
15 = B&W
*/
				break;

			case 'E#001E': 	// FirmwareRevision (Canon) / ColorSpace (Nikon)
				switch( $brand ) {
					case 'CANON':
						$result = $data;
						break;
					case 'NIKON':
						switch( $data ) {
							case 1: $result = 'sRGB'; break;
							case 2: $result = 'Adobe RGB'; break;
							default: $result = $data;
						}
						break;
					default:
						$result = $data;
						break;
				}
				break;

			case 'E#0020': 	// SmartAlbumColor ( samsung ) / ImageAuthentication (Nikon)
				switch( $brand ) {
					case 'SAMSUNG':
						switch( $data ) {
							case '0 0': $result = ''; break;
							case 0: $result = __( 'Red', 'wp-photo-album-plus' ); break;
							case 1: $result = __( 'Yellow', 'wp-photo-album-plus' ); break;
							case 2: $result = __( 'Green', 'wp-photo-album-plus' ); break;
							case 3: $result = __( 'Blue', 'wp-photo-album-plus' ); break;
							case 4: $result = __( 'Magenta', 'wp-photo-album-plus' ); break;
							case 5: $result = __( 'Black', 'wp-photo-album-plus' ); break;
							case 6: $result = __( 'White', 'wp-photo-album-plus' ); break;
							case 7: $result = __( 'Various', 'wp-photo-album-plus' ); break;
							default: $result = $data;
						}
						break;
					case 'NIKON':
						switch( $data ) {
							case 0: $result = __( 'Off', 'wp-photo-album-plus' ); break;
							case 1: $result = __( 'On', 'wp-photo-album-plus' ); break;
							default: $result = $data;
						}
						break;
					default:
						$result = $data;
						break;
				}
				break;

			case 'E#0023': 	// Categories
				if ( $brand == 'CANON' ) {
					if ( $data_arr[0] == 8 ) {
						if ( $data_arr[1] == 0 ) {
							$result = __( 'none', 'wp-photo-album-plus' );
						}
						else {
							$result = '';
							$b = $data_arr[1];
							if ( $b & 0x01 ) $result .= 'People, ';
							if ( $b & 0x02 ) $result .= 'Scenery, ';
							if ( $b & 0x04 ) $result .= 'Events, ';
							if ( $b & 0x08 ) $result .= 'User 1, ';
							if ( $b & 0x10 ) $result .= 'User 2, ';
							if ( $b & 0x20 ) $result .= 'User 3, ';
							if ( $b & 0x40 ) $result .= 'To Do, ';
							$result = trim( $result, ', ' );
						}
					}
				}
				break;

			case 'E#0024': 	// FaceDetect1
			case 'E#0025': 	// FaceDetect2
				break;

			case 'E#0026': 	// CanonAFInfo2
			case 'E#003C': 	// CanonAFInfo3
				if ( $brand == 'CANON' ) {
					if ( is_array( $data_arr ) && count( $data_arr ) >= 15 && $data_arr[1] >= 0 && $data_arr[1] <= 14 ) {
						$result = 'AFAreaMode:';
						switch( $data_arr[1] ) {
							case 0: $result .= 'Off, '; break;
							case 1: $result .= 'AF Point Expansion (surround), '; break;
							case 2: $result .= 'Single-point AF, '; break;
							case 4: $result .= 'Auto, '; break;
							case 5: $result .= 'Face Detect AF, '; break;
							case 6: $result .= 'Face + Tracking, '; break;
							case 7: $result .= 'Zone AF, '; break;
							case 8: $result .= 'AF Point Expansion (4 point), '; break;
							case 9: $result .= 'Spot AF, '; break;
							case 10: $result .= 'AF Point Expansion (8 point), '; break;
							case 11: $result .= 'Flexizone Multi, '; break;
							case 13: $result .= 'Flexizone Single, '; break;
							case 14: $result .= 'Large Zone AF, '; break;
							default: $result .= '?, ';
						}
						$result .= 'NumAFPoints:' . $data_arr[2] . ', ';
						$result .= 'ValidAFPoints:' . $data_arr[3] . ', ';
						$result .= 'CanonImageWidth:' . $data_arr[4] . ', ';
						$result .= 'CanonImageHeight:' . $data_arr[5] . ', ';
						$result .= 'AFImageWidth:' . $data_arr[6] . ', ';
						$result .= 'AFImageHeight:' . $data_arr[7] . ', ';
						$result .= 'AFAreaWidths:' . $data_arr[8] . ', ';
						$result .= 'AFAreaHeights:' . $data_arr[9] . ', ';
						$result .= 'AFAreaXPositions:' . $data_arr[10] . ', ';
						$result .= 'AFAreaYPositions:' . $data_arr[11] . ', ';
						$result .= 'AFPointsInFocus:' . $data_arr[12] . ', ';
						$result .= 'AFPointsSelected:' . $data_arr[13] . ', ';
						$result .= 'PrimaryAFPoint:' . $data_arr[14] . ', ';

						$result = trim( $result, ', ' );
					}
				}
				break;

			case 'E#0027': 	// ContrastInfo
/*
4 	IntelligentContrast 	int16u
0x0 = Off
0x8 = On
0xffff = n/a
*/
			case 'E#0028': 	// ImageUniqueID
			case 'E#002F': 	// FaceDetect3
				break;

			case 'E#0035': 	// TimeInfo
				if ( $brand == 'CANON' && is_array( $data_arr ) && count( $data_arr ) == 4 ) {
					$result = 'Timezone:' . $data_arr[1] . ', ';
					$result .= 'TimeZoneCity:';
					switch ( $data_arr[2] ) {
						case 0: $result .= 'n/a'; break;
						case 1: $result .= 'Chatham Islands'; break;
						case 2: $result .= 'Wellington'; break;
						case 3: $result .= 'Solomon Islands'; break;
						case 4: $result .= 'Sydney'; break;
						case 5: $result .= 'Adelaide'; break;
						case 6: $result .= 'Tokyo'; break;
						case 7: $result .= 'Hong Kong'; break;
						case 8: $result .= 'Bangkok'; break;
						case 9: $result .= 'Yangon'; break;
						case 10: $result .= 'Dhaka'; break;
						case 11: $result .= 'Kathmandu'; break;
						case 12: $result .= 'Delhi'; break;
						case 13: $result .= 'Karachi'; break;
						case 14: $result .= 'Kabul'; break;
						case 15: $result .= 'Dubai'; break;
						case 16: $result .= 'Tehran'; break;
						case 17: $result .= 'Moscow'; break;
						case 18: $result .= 'Cairo'; break;
						case 19: $result .= 'Paris'; break;
						case 20: $result .= 'London'; break;
						case 21: $result .= 'Azores'; break;
						case 22: $result .= 'Fernando de Noronha'; break;
						case 23: $result .= 'Sao Paulo'; break;
						case 24: $result .= 'Newfoundland'; break;
						case 25: $result .= 'Santiago'; break;
						case 26: $result .= 'Caracas'; break;
						case 27: $result .= 'New York'; break;
						case 28: $result .= 'Chicago'; break;
						case 29: $result .= 'Denver'; break;
						case 30: $result .= 'Los Angeles'; break;
						case 31: $result .= 'Anchorage'; break;
						case 32: $result .= 'Honolulu'; break;
						case 33: $result .= 'Samoa'; break;
						case 32766: $result .= '(not set)'; break;
						default: $result .= '?';

					}
					$result .= ', DaylightSavings:';
					if ( $data_arr[3] == 0 ) $result .= 'Off';
					elseif( $data_arr[3] == 60 ) $result .= 'On';
					else $result .= '?';
				}
				break;

		//	case 'E#003C': 	// AFInfo3 // See E#0026
		//		break;

			case 'E#0081': 	// RawDataOffset
			case 'E#0083': 	// OriginalDecisionDataOffset
			case 'E#0090': 	// CustomFunctions1D
			case 'E#0091': 	// PersonalFunctions
			case 'E#0092': 	// PersonalFunctionValues
			case 'E#0093': 	// CanonFileInfo
			case 'E#0094': 	// AFPointsInFocus1D
			case 'E#0095': 	// LensModel
			case 'E#0096': 	// SerialInfo
			case 'E#0097': 	// DustRemovalData
			case 'E#0098': 	// CropInfo
			case 'E#0099': 	// CustomFunctions2
				$result = $data;
				break;

			case 'E#009A': 	// AspectInfo
				$result = $data;
				if ( $brand == 'CANON' ) {
					if ( is_array( $data_arr ) && count( $data_arr ) == 5 ) {
						$aspects = array(
											0 => '3:2',
											1 => '1:1',
											2 => '4:3',
											7 => '16:9',
											8 => '4:5',
										);
						$labels = array(
											0 => 'AspectRatio',
											1 => 'CroppedImageWidth',
											2 => 'CroppedImageHeight',
											3 => 'CroppedImageLeft',
											4 => 'CroppedImageTop',
										);
						if ( in_array( $data_arr[0], array_keys( $aspects ) ) ) {
							$result = $labels[0] . ': ' . $aspects[$data_arr[0]] . ', ';
							for ( $i=1; $i<5; $i++ ) {
								$result .= $labels[$i] . ': ';
								if ( $data_arr[$i] >= 0 ) {
									$result .= $data_arr[$i] . ', ';
								}
								else {
									$result .= '?, ';
								}
							}
						}
						else {
							$result = $labels[0] . ': ?:?, ';
						}
						$result = trim( $result, ', ' );
					}
				}
				break;

			case 'E#00A0': 	// ProcessingInfo
			case 'E#00A1': 	// ToneCurveTable
			case 'E#00A2': 	// SharpnessTable
			case 'E#00A3': 	// SharpnessFreqTable
			case 'E#00A4': 	// WhiteBalanceTable
			case 'E#00A9': 	// ColorBalance
			case 'E#00AA': 	// MeasuredColor
			case 'E#00AE': 	// ColorTemperature
			case 'E#00B0': 	// CanonFlags
			case 'E#00B1': 	// ModifiedInfo
			case 'E#00B2': 	// ToneCurveMatching
			case 'E#00B3': 	// WhiteBalanceMatching
			case 'E#00B4': 	// ColorSpace
			case 'E#00B6': 	// PreviewImageInfo
			case 'E#00D0': 	// VRDOffset
			case 'E#00E0': 	// SensorInfo

				$result = $data;
				break;

			case 'E#00FE': // SubfileType (called NewSubfileType by the TIFF specification)

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0x0: $result = __( 'Full-resolution Image', 'wp-photo-album-plus' ); break;
					case 0x1: $result = __( 'Reduced-resolution image', 'wp-photo-album-plus' ); break;
					case 0x2: $result = __( 'Single page of multi-page image', 'wp-photo-album-plus' ); break;
					case 0x3: $result = __( 'Single page of multi-page reduced-resolution image', 'wp-photo-album-plus' ); break;
					case 0x4: $result = __( 'Transparency mask', 'wp-photo-album-plus' ); break;
					case 0x5: $result = __( 'Transparency mask of reduced-resolution image', 'wp-photo-album-plus' ); break;
					case 0x6: $result = __( 'Transparency mask of multi-page image', 'wp-photo-album-plus' ); break;
					case 0x7: $result = __( 'Transparency mask of reduced-resolution multi-page image', 'wp-photo-album-plus' ); break;
					case 0x10001: $result = __( 'Alternate reduced-resolution image', 'wp-photo-album-plus' ); break;
					case 0xffffffff: $result = __( 'invalid', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#00FF': 	// 	OldSubfileType (called SubfileType by the TIFF specification)

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch( $data ) {
					case 1: $result = __( 'Full-resolution image', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Reduced-resolution image', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Single page of multi-page image', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0100': 	// Image width (pixels), Short or long, 1 item
			case 'E#0101': 	// Image length (pixels), Short or long, 1 item

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				$result = $data . ' ' . __( 'px.', 'wp-photo-album-plus' );
				break;
				break;

			case 'E#0106': // PhotometricInterpretation

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'WhiteIsZero', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'BlackIsZero', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'RGB', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'RGB Palette', 'wp-photo-album-plus' ); break;
					case 4: $result = __( 'Transparency Mask', 'wp-photo-album-plus' ); break;
					case 5: $result = __( 'CMYK', 'wp-photo-album-plus' ); break;
					case 6: $result = __( 'YCbCr', 'wp-photo-album-plus' ); break;
					case 8: $result = __( 'CIELab', 'wp-photo-album-plus' ); break;
					case 9: $result = __( 'ICCLab', 'wp-photo-album-plus' ); break;
					case 10: $result = __( 'ITULab', 'wp-photo-album-plus' ); break;
					case 32803: $result = __( 'Color Filter Array', 'wp-photo-album-plus' ); break;
					case 32844: $result = __( 'Pixar LogL', 'wp-photo-album-plus' ); break;
					case 32845: $result = __( 'Pixar LogLuv', 'wp-photo-album-plus' ); break;
					case 34892: $result = __( 'Linear Raw', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0107': // Thresholding

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = __( 'No dithering or halftoning', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Ordered dither or halftone', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Randomized dither', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#010A': // FillOrder

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = __( 'Normal', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Reversed', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0112': 	// Orientation

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = __( 'Horizontal (normal)', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Mirror horizontal', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Rotate 180', 'wp-photo-album-plus' ); break;
					case 4: $result = __( 'Mirror vertical', 'wp-photo-album-plus' ); break;
					case 5: $result = __( 'Mirror horizontal and rotate 270 CW', 'wp-photo-album-plus' ); break;
					case 6: $result = __( 'Rotate 90 CW', 'wp-photo-album-plus' ); break;
					case 7: $result = __( 'Mirror horizontal and rotate 90 CW', 'wp-photo-album-plus' ); break;
					case 8: $result = __( 'Rotate 270 CW', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#011A': 	// XResolution
			case 'E#011B': 	// YResolution

				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = ( $x / $y );
				break;

			case 'E#011C': 	// PlanarConfiguration

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = __( 'Chunky', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Planar', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0122': 	// GrayResponseUnit

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = '0.1'; break;
					case 2: $result = '0.001'; break;
					case 3: $result = '0.0001'; break;
					case 4: $result = '1e-05'; break;
					case 5: $result = '1e-06'; break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0124': 	// T4Options

				$result = '';
				if ( $data & 0x0001 ) $result .= __( '2-Dimensional encoding', 'wp-photo-album-plus' ) . ' ';
				if ( $data & 0x0002 ) $result .= __( 'Uncompressed', 'wp-photo-album-plus' ) . ' ';
				if ( $data & 0x0004 ) $result .= __( 'Fill bits added', 'wp-photo-album-plus' );
				$result = trim( $result );
				if ( ! $result ) $result = __( 'Undefined', 'wp-photo-album-plus' );
				break;

			case 'E#0125': 	// T6Options
				if ( $data & 0x0001 ) $result = __( 'Uncompressed', 'wp-photo-album-plus' );
				else $result = __( 'Undefined', 'wp-photo-album-plus' );
				break;

			case 'E#0128': 	// Resolution unit

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 2:	$result = __( 'inches', 'wp-photo-album-plus' ); break;
					case 3:	$result = __( 'centimeters', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#013D': 	// Predictor

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 1: $result = __( 'None', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Horizontal differencing', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0147': 	// CleanFaxData

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Clean', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Regenerated', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Unclean', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#014C': 	// InkSet

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 1: $result = __( 'CMYK', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Not CMYK', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0152': 	// ExtraSamples

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Unspecified', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Associated Alpha', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Unassociated Alpha', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0153': 	// SampleFormat

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 1: $result = __( 'Unsigned', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Signed', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Float', 'wp-photo-album-plus' ); break;
					case 4: $result = __( 'Undefined', 'wp-photo-album-plus' ); break;
					case 5: $result = __( 'Complex int', 'wp-photo-album-plus' ); break;
					case 6: $result = __( 'Complex float', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#015A': 	// Indexed

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Not indexed', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Indexed', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#015F': 	// OPIProxy

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Higher resolution image does not exist', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Higher resolution image exists', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0191': 	// ProfileType

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Unspecified', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Group 3 FAX', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0192': 	// FaxProfile

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Unknown', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Minimal B&W lossless, S', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Extended B&W lossless, F', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Lossless JBIG B&W, J', 'wp-photo-album-plus' ); break;
					case 4: $result = __( 'Lossy color and grayscale, C', 'wp-photo-album-plus' ); break;
					case 5: $result = __( 'Lossless color and grayscale, L', 'wp-photo-album-plus' ); break;
					case 6: $result = __( 'Mixed raster content, M', 'wp-photo-album-plus' ); break;
					case 7: $result = __( 'Profile T', 'wp-photo-album-plus' ); break;
					case 255: $result = __( 'Multi Profiles', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0193': 	// CodingMethods

				$result = '';
				if ( $data & 0x01 ) {
					$result .= __( 'Unspecified compression', 'wp-photo-album-plus' ) . ', ';
				}
				if ( $data & 0x02 ) {
					$result .= __( 'Modified Huffman', 'wp-photo-album-plus' ) . ', ';
				}
				if ( $data & 0x04 ) {
					$result .= __( 'Modified Read', 'wp-photo-album-plus' ) . ', ';
				}
				if ( $data & 0x08 ) {
					$result .= __( 'Modified MR', 'wp-photo-album-plus' ) . ', ';
				}
				if ( $data & 0x10 ) {
					$result .= __( 'JBIG', 'wp-photo-album-plus' ) . ', ';
				}
				if ( $data & 0x20 ) {
					$result .= __( 'Baseline JPEG', 'wp-photo-album-plus' ) . ', ';
				}
				if ( $data & 0x40 ) {
					$result .= __( 'JBIG color', 'wp-photo-album-plus' ) . ', ';
				}
				$result = trim( $result, ', ' );
				if ( ! $result ) $result = __( 'Undefined', 'wp-photo-album-plus' );
				break;

			case 'E#A210': 	// FocalPlaneResolutionUnit

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 2:	$result = __( 'inches', 'wp-photo-album-plus' ); break;
					case 3:	$result = __( 'centimeters', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#0212': 	// YCbCrSubSampling

				/*
				'1 1' = YCbCr4:4:4 (1 1)
				'1 2' = YCbCr4:4:0 (1 2)
				'1 4' = YCbCr4:4:1 (1 4)
				'2 1' = YCbCr4:2:2 (2 1)
				'2 2' = YCbCr4:2:0 (2 2)
				'2 4' = YCbCr4:2:1 (2 4)
				'4 1' = YCbCr4:1:1 (4 1)
				'4 2' = YCbCr4:1:0 (4 2)
				*/
				$result = $data;
				break;

			case 'E#0213': 	// YCbCrPositioning

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 1:
						$result = __( 'centered', 'wp-photo-album-plus' );
						break;
					case 2:
						$result = __( 'co-sited', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#4001': 	// ColorData
			case 'E#4002': 	// CRWParam?
			case 'E#4003': 	// ColorInfo
			case 'E#4005': 	// Flavor?

				$result = $data;
				break;

			case 'E#4008': 	// PictureStyleUserDef
				switch( $brand ) {
					case 'CANON':
						switch( $data ) {
							case 0x0: $result = __( 'None', 'wp-photo-album-plus' ); break;
							case 0x1: $result = __( 'Standard', 'wp-photo-album-plus' ); break;
							case 0x2: $result = __( 'Portrait', 'wp-photo-album-plus' ); break;
							case 0x3: $result = __( 'High Saturation', 'wp-photo-album-plus' ); break;
							case 0x4: $result = __( 'Adobe RGB', 'wp-photo-album-plus' ); break;
							case 0x5: $result = __( 'Low Saturation', 'wp-photo-album-plus' ); break;
							case 0x6: $result = __( 'CM Set 1', 'wp-photo-album-plus' ); break;
							case 0x7: $result = __( 'CM Set 2', 'wp-photo-album-plus' ); break;
							case 0x21: $result = __( 'User Def. 1', 'wp-photo-album-plus' ); break;
							case 0x22: $result = __( 'User Def. 2', 'wp-photo-album-plus' ); break;
							case 0x23: $result = __( 'User Def. 3', 'wp-photo-album-plus' ); break;
							case 0x41: $result = __( 'PC 1', 'wp-photo-album-plus' ); break;
							case 0x42: $result = __( 'PC 2', 'wp-photo-album-plus' ); break;
							case 0x43: $result = __( 'PC 3', 'wp-photo-album-plus' ); break;
							case 0x81: $result = __( 'Standard', 'wp-photo-album-plus' ); break;
							case 0x82: $result = __( 'Portrait', 'wp-photo-album-plus' ); break;
							case 0x83: $result = __( 'Landscape', 'wp-photo-album-plus' ); break;
							case 0x84: $result = __( 'Neutral', 'wp-photo-album-plus' ); break;
							case 0x85: $result = __( 'Faithful', 'wp-photo-album-plus' ); break;
							case 0x86: $result = __( 'Monochrome', 'wp-photo-album-plus' ); break;
							case 0x87: $result = __( 'Auto', 'wp-photo-album-plus' ); break;
							case 0x88: $result = __( 'Fine Detail', 'wp-photo-album-plus' ); break;
							case 0xff: $result = __( 'n/a', 'wp-photo-album-plus' ); break;
							case 0xffff: $result = __( 'n/a', 'wp-photo-album-plus' ); break;
							default: $result = $data;
						}
						break;

					default:
						$result = $data;
				}
				break;

			case 'E#4009': 	// PictureStylePC
			case 'E#4010': 	// CustomPictureStyleFileName
			case 'E#4013': 	// AFMicroAdj
			case 'E#4015': 	// VignettingCorr
			case 'E#4016': 	// VignettingCorr2
			case 'E#4018': 	// LightingOpt
			case 'E#4019': 	// LensInfo
				$result = $data;
				break;


			case 'E#4020': 	// AmbienceInfo
				if ( $brand == 'CANON' ) {
					switch( $data ) {
						case 0: $result = __( 'Standard', 'wp-photo-album-plus' ); break;
						case 1: $result = __( 'Vivid', 'wp-photo-album-plus' ); break;
						case 2: $result = __( 'Warm', 'wp-photo-album-plus' ); break;
						case 3: $result = __( 'Soft', 'wp-photo-album-plus' ); break;
						case 4: $result = __( 'Cool', 'wp-photo-album-plus' ); break;
						case 5: $result = __( 'Intense', 'wp-photo-album-plus' ); break;
						case 6: $result = __( 'Brighter', 'wp-photo-album-plus' ); break;
						case 7: $result = __( 'Darker', 'wp-photo-album-plus' ); break;
						case 8: $result = __( 'Monochrome', 'wp-photo-album-plus' ); break;
						default: $result = $data;
					}
				}
				else {
					$result = $data;
				}
				break;

			case 'E#4021': 	// MultiExp
			case 'E#4024': 	// FilterInfo
			case 'E#4025': 	// HDRInfo
			case 'E#4028': 	// AFConfig

				$result = $data;
				break;

			case 'E#7000': 	// SonyRawFileType

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Sony Uncompressed 14-bit RAW', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Sony Uncompressed 12-bit RAW', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Sony Compressed RAW', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Sony Lossless Compressed RAW', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#829A': 	// Exposure time

				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				// 1 s.
				if ( $x / $y == 1 ) {
					$result = '1 s.';
				}

				// Normal: 1/nn s.
				elseif ( $x == 1 ) {
					$result = $data . ' s.';
				}

				// 'nn/1'
				elseif ( $y == 1 ) {
					$result = $x . ' s.';
				}

				// Simplify nnn/mmm > 1
				elseif ( ( $x / $y ) > 1 ) {
					$result = sprintf( '%2.1f', $x / $y );
					if ( substr( $result, -2 ) == '.0' ) { 	// Remove trailing '.0'
						$result = substr( $result, 0, strlen( $result ) -2 ) . ' s.';
					}
					else {
						$result .= ' s.';
					}
					return $result;
				}

				// Simplify nnn/mmm < 1
				if ( ! $x ) {
					return $wppa_exif_error_output;
				}
				$v = $y / $x;
				$z = round( $v ) / $v;
				if ( 0.99 < $z && $z < 1.01 ) {
					if ( round( $v ) == '1' ) {
						$result = '1 s.';
					}
					else {
						$result = '1/' . round( $v ) . ' s.';
					}
				}
				else {
					$z = $x / $y;
					$i = 2;
					$n = 0;
					while ( $n < 2 && $i < strlen( $z ) ) {
						if ( substr( $z, $i, 1 ) != '0' ) {
							$n++;
						}
						$i++;
					}
					$result = substr( $z, 0, $i ) . ' s.';
				}
				break;

			case 'E#829D':	// F-Stop

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				// Bogus data?
				if ( $x / $y > 100 || ( round( 10 * $x / $y ) / 10 ) == 0 ) {
					$result = '';
					return $result;
				}

				// Valid meaningful data
				$result = 'f/' . ( round( 10 * $x / $y ) / 10 );
				break;

			case 'E#84E3': 	// RasterPadding

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Byte', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Word', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Long Word', 'wp-photo-album-plus' ); break;
					case 9: $result = __( 'Sector', 'wp-photo-album-plus' ); break;
					case 10: $result = __( 'Long Sector', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#84E7': 	// ImageColorIndicator

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Unspecified Image Color', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Specified Image Color', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#84E8': 	// BackgroundColorIndicator

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Unspecified Background Color', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Specified Background Color', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#84EE': 	// HCUsage

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'CT', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Line Art', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Trap', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#877F': 	// TIFF_FXExtensions

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				$result = '';
				if ( $data & 0x01 ) $data .= __( 'Resolution/Image Width', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x02 ) $data .= __( 'N Layer Profile M', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x04 ) $data .= __( 'Shared Data', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x08 ) $data .= __( 'B&W JBIG2', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x10 ) $data .= __( 'JBIG2 Profile M', 'wp-photo-album-plus' ) . ',  ';
				$result = trim( $result, ', ' );
				if ( ! $result ) $result = __( 'Undefined', 'wp-photo-album-plus' );
				break;

			case 'E#8780': 	// MultiProfiles

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				$result = '';
				if ( $data & 0x001 ) $data .= __( 'Profile S', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x002 ) $data .= __( 'Profile F', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x004 ) $data .= __( 'Profile J', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x008 ) $data .= __( 'Profile C', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x010 ) $data .= __( 'Profile L', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x020 ) $data .= __( 'Profile M', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x040 ) $data .= __( 'Profile T', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x080 ) $data .= __( 'Resolution/Image Width', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x100 ) $data .= __( 'N Layer Profile M', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x200 ) $data .= __( 'Shared Data', 'wp-photo-album-plus' ) . ',  ';
				if ( $data & 0x400 ) $data .= __( 'JBIG2 Profile M', 'wp-photo-album-plus' ) . ',  ';
				$result = trim( $result, ', ' );
				if ( ! $result ) $result = __( 'Undefined', 'wp-photo-album-plus' );
				break;

			case 'E#8822': 	// Exposure program

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case '0': $result = __('Not Defined', 'wp-photo-album-plus' ); break;
					case '1': $result = __('Manual', 'wp-photo-album-plus' ); break;
					case '2': $result = __('Program AE', 'wp-photo-album-plus' ); break;
					case '3': $result = __('Aperture-priority AE', 'wp-photo-album-plus' ); break;
					case '4': $result = __('Shutter speed priority AE', 'wp-photo-album-plus' ); break;
					case '5': $result = __('Creative (Slow speed)', 'wp-photo-album-plus' ); break;
					case '6': $result = __('Action (High speed)', 'wp-photo-album-plus' ); break;
					case '7': $result = __('Portrait', 'wp-photo-album-plus' ); break;
					case '8': $result = __('Landscape', 'wp-photo-album-plus' ); break;
					case '9': $result = __('Bulb', 'wp-photo-album-plus' ); break;
					default:  $result = __('Unknown', 'wp-photo-album-plus' );
				}
				break;

			case 'E#8830': 	// SensitivityType

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case '0': $result = __('Unknown', 'wp-photo-album-plus' ); break;
					case '1': $result = __('Standard Output Sensitivity', 'wp-photo-album-plus' ); break;
					case '2': $result = __('Recommended Exposure Index', 'wp-photo-album-plus' ); break;
					case '3': $result = __('ISO Speed', 'wp-photo-album-plus' ); break;
					case '4': $result = __('Standard Output Sensitivity and Recommended Exposure Index', 'wp-photo-album-plus' ); break;
					case '5': $result = __('Standard Output Sensitivity and ISO Speed', 'wp-photo-album-plus' ); break;
					case '6': $result = __('Recommended Exposure Index and ISO Speed', 'wp-photo-album-plus' ); break;
					case '7': $result = __('Standard Output Sensitivity, Recommended Exposure Index and ISO Speed', 'wp-photo-album-plus' ); break;
					default:  $result = __('Unknown', 'wp-photo-album-plus' );
				}
				break;

			case 'E#9101': 	// ComponentsConfiguration

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case '0': $result = '-'; break;
					case '1': $result = 'Y'; break;
					case '2': $result = 'Cb'; break;
					case '3': $result = 'Cr'; break;
					case '4': $result = 'R'; break;
					case '5': $result = 'G'; break;
					case '6': $result = 'B'; break;
					default:  $result = '?';
				}
				break;

			case 'E#9102': 	// CompressedBitsPerPixel

				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;

				$t = explode( '/', $data );
				if ( $t[1] == '1' ) {
					$result = $t[0];
				}
				else {
					$result = $data;
				}
				break;

			case 'E#9201': 	// Shutter speed value

				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = round( 10 * $x / $y ) / 10;
				break;

			case 'E#9202': 	// Aperture value

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = round( 10 * $x / $y ) / 10;
				break;

			case 'E#9204': 	// ExposureBiasValue

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				$t = explode( '/', $data );
				$x = $t[0];
				$y = $t[1];

				$result = sprintf( '%5.2f EV', $x/$y );
				break;

			case 'E#9205': 	// Max aperture value

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = round( 10 * $x / $y ) / 10;
				break;

			case 'E#9206': 	// Subject distance

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				if ( $x == 0 || $y == 0 || $x/$y > 1000 ) {
					$result = '';
				}
				else {
					if ( $temp[1] != 0 ) {
						$result = round( 100*$temp[0]/$temp[1] ) / 100;
					}
					if ( $result == -1 ) {
						$result = 'inf';
					}
					else {
						$result .= ' m.';
					}
				}
				break;

			case 'E#9207':	// Metering mode
				switch ( $data ) {
					case '1': $result = __('Average', 'wp-photo-album-plus' ); break;
					case '2': $result = __('Center-weighted average', 'wp-photo-album-plus' ); break;
					case '3': $result = __('Spot', 'wp-photo-album-plus' ); break;
					case '4': $result = __('Multi-spot', 'wp-photo-album-plus' ); break;
					case '5': $result = __('Multi-segment', 'wp-photo-album-plus' ); break;
					case '6': $result = __('Partial', 'wp-photo-album-plus' ); break;
					case '255': $result = __('Other', 'wp-photo-album-plus' ); break;
					default: $result = __('reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#9208': 	// LghtSource
				switch ( $data ) {
					case '0': $result = __('unknown', 'wp-photo-album-plus' ); break;
					case '1': $result = __('Daylight', 'wp-photo-album-plus' ); break;
					case '2': $result = __('Fluorescent', 'wp-photo-album-plus' ); break;
					case '3': $result = __('Tungsten (incandescent light)', 'wp-photo-album-plus' ); break;
					case '4': $result = __('Flash', 'wp-photo-album-plus' ); break;
					case '9': $result = __('Fine weather', 'wp-photo-album-plus' ); break;
					case '10': $result = __('Cloudy weather', 'wp-photo-album-plus' ); break;
					case '11': $result = __('Shade', 'wp-photo-album-plus' ); break;
					case '12': $result = __('Daylight fluorescent (D 5700 – 7100K)', 'wp-photo-album-plus' ); break;
					case '13': $result = __('Day white fluorescent (N 4600 – 5400K)', 'wp-photo-album-plus' ); break;
					case '14': $result = __('Cool white fluorescent (W 3900 – 4500K)', 'wp-photo-album-plus' ); break;
					case '15': $result = __('White fluorescent (WW 3200 – 3700K)', 'wp-photo-album-plus' ); break;
					case '17': $result = __('Standard light A', 'wp-photo-album-plus' ); break;
					case '18': $result = __('Standard light B', 'wp-photo-album-plus' ); break;
					case '19': $result = __('Standard light C', 'wp-photo-album-plus' ); break;
					case '20': $result = __('D55', 'wp-photo-album-plus' ); break;
					case '21': $result = __('D65', 'wp-photo-album-plus' ); break;
					case '22': $result = __('D75', 'wp-photo-album-plus' ); break;
					case '23': $result = __('D50', 'wp-photo-album-plus' ); break;
					case '24': $result = __('ISO studio tungsten', 'wp-photo-album-plus' ); break;
					case '255': $result = __('other light source', 'wp-photo-album-plus' ); break;
					default: $result = __('reserved', 'wp-photo-album-plus' ); break;
				}
				break;

			case 'E#9209':	// Flash
				switch ( $data ) {
					case '0': $result = __('No Flash', 'wp-photo-album-plus' ); break;
					case '1': $result = __('Fired', 'wp-photo-album-plus' ); break;
					case '5': $result = __('Fired, Return not detected', 'wp-photo-album-plus' ); break;
					case '7': $result = __('Fired, Return detected', 'wp-photo-album-plus' ); break;
					case '8': $result = __('On, Did not fire', 'wp-photo-album-plus' ); break;
					case '9': $result = __('On, Fired', 'wp-photo-album-plus' ); break;
					case '13': $result = __('On, Return not detected', 'wp-photo-album-plus' ); break;
					case '15': $result = __('On, Return detected', 'wp-photo-album-plus' ); break;
					case '16': $result = __('Off, Did not fire', 'wp-photo-album-plus' ); break;
					case '20': $result = __('Off, Did not fire, Return not detected', 'wp-photo-album-plus' ); break;
					case '24': $result = __('Auto, Did not fire', 'wp-photo-album-plus' ); break;
					case '25': $result = __('Auto, Fired', 'wp-photo-album-plus' ); break;
					case '29': $result = __('Auto, Fired, Return not detected', 'wp-photo-album-plus' ); break;
					case '31': $result = __('Auto, Fired, Return detected', 'wp-photo-album-plus' ); break;
					case '32': $result = __('No flash function', 'wp-photo-album-plus' ); break;
					case '48': $result = __('Off, No flash function', 'wp-photo-album-plus' ); break;
					case '65': $result = __('Fired, Red-eye reduction', 'wp-photo-album-plus' ); break;
					case '69': $result = __('Fired, Red-eye reduction, Return not detected', 'wp-photo-album-plus' ); break;
					case '71': $result = __('Fired, Red-eye reduction, Return detected', 'wp-photo-album-plus' ); break;
					case '73': $result = __('On, Red-eye reduction', 'wp-photo-album-plus' ); break;
					case '77': $result = __('Red-eye reduction, Return not detected', 'wp-photo-album-plus' ); break;
					case '79': $result = __('On, Red-eye reduction, Return detected', 'wp-photo-album-plus' ); break;
					case '80': $result = __('Off, Red-eye reduction', 'wp-photo-album-plus' ); break;
					case '88': $result = __('Auto, Did not fire, Red-eye reduction', 'wp-photo-album-plus' ); break;
					case '89': $result = __('Auto, Fired, Red-eye reduction', 'wp-photo-album-plus' ); break;
					case '93': $result = __('Auto, Fired, Red-eye reduction, Return not detected', 'wp-photo-album-plus' ); break;
					case '95': $result = __('Auto, Fired, Red-eye reduction, Return detected', 'wp-photo-album-plus' ); break;
					default:   $result = __('Unknown', 'wp-photo-album-plus' );
				}
				break;

			case 'E#9210': 	// FocalPlaneResolutionUnit

				switch( $data ) {
					case 1: $result = __( 'None', 'wp-photo-album-plus' ); break;
					case 2: $result = 'inches'; break;
					case 3: $result = 'cm'; break;
					case 4: $result = 'mm'; break;
					case 5: $result = '&mu;m'; break;
					default: $result = __( 'Unknown', 'wp-photo-album-plus' );
				}
				break;

			case 'E#920A': // 	Focal length

				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$z = round( $x / $y );
				if ( $z < 10 ) {
					$result = round( $x * 10 / $y ) / 10 . ' mm.';
				}
				else {
					$result = round( $x / $y ) . ' mm.';
				}
				break;

			case 'E#9212': 	// SecurityClassification

				switch( $data ) {
					case 'C': $result = __( 'Confidential', 'wp-photo-album-plus' ); break;
					case 'R': $result = __( 'Restricted', 'wp-photo-album-plus' ); break;
					case 'S': $result = __( 'Secret', 'wp-photo-album-plus' ); break;
					case 'T': $result = __( 'Top Secret', 'wp-photo-album-plus' ); break;
					case 'U': $result = __( 'Unclassified', 'wp-photo-album-plus' ); break;
					default:  $result = __( 'Unknown', 'wp-photo-album-plus' );
				}
				break;

			case 'E#9217': 	// SensingMethod

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = __( 'Monochrome area', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'One-chip color area', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Two-chip color area', 'wp-photo-album-plus' ); break;
					case 4: $result = __( 'Three-chip color area', 'wp-photo-album-plus' ); break;
					case 5: $result = __( 'Color sequential area', 'wp-photo-album-plus' ); break;
					case 6: $result = __( 'Monochrome linear', 'wp-photo-album-plus' ); break;
					case 7: $result = __( 'Trilinear', 'wp-photo-album-plus' ); break;
					case 8: $result = __( 'Color sequential linear', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Unknown', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A001': 	// ColorSpace

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 1: $result = __( 'sRGB', 'wp-photo-album-plus' ); break;
					case 0x2: $result = __( 'Adobe RGB', 'wp-photo-album-plus' ); break;
					case 0xfffd: $result = __( 'Wide Gamut RGB', 'wp-photo-album-plus' ); break;
					case 0xfffe: $result = __( 'ICC Profile', 'wp-photo-album-plus' ); break;
					case 0xFFFF: $result = __( 'Uncalibrated', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A002': 	// PixelXDimension
			case 'E#A003': 	// PixelYDimension

				switch( $brand ) {
					case 'SAMSUNG': 	// LensType

						switch ( $data ) {
							case 0: $result = 'Built-in or Manual Lens'; break;
							case 1: $result = 'Samsung NX 30mm F2 Pancake'; break;
							case 2: $result = 'Samsung NX 18-55mm F3.5-5.6 OIS'; break;
							case 3: $result = 'Samsung NX 50-200mm F4-5.6 ED OIS'; break;
							case 4: $result = 'Samsung NX 20-50mm F3.5-5.6 ED'; break;
							case 5: $result = 'Samsung NX 20mm F2.8 Pancake'; break;
							case 6: $result = 'Samsung NX 18-200mm F3.5-6.3 ED OIS'; break;
							case 7: $result = 'Samsung NX 60mm F2.8 Macro ED OIS SSA'; break;
							case 8: $result = 'Samsung NX 16mm F2.4 Pancake'; break;
							case 9: $result = 'Samsung NX 85mm F1.4 ED SSA'; break;
							case 10: $result = 'Samsung NX 45mm F1.8'; break;
							case 11: $result = 'Samsung NX 45mm F1.8 2D/3D'; break;
							case 12: $result = 'Samsung NX 12-24mm F4-5.6 ED'; break;
							case 13: $result = 'Samsung NX 16-50mm F2-2.8 S ED OIS'; break;
							case 14: $result = 'Samsung NX 10mm F3.5 Fisheye'; break;
							case 15: $result = 'Samsung NX 16-50mm F3.5-5.6 Power Zoom ED OIS'; break;
							case 20: $result = 'Samsung NX 50-150mm F2.8 S ED OIS'; break;
							case 21: $result = 'Samsung NX 300mm F2.8 ED OIS'; break;
							default: $result = $data;
						}
						break;

					default:
						if ( ! wppa_is_valid_integer( $data ) ) {
							return $wppa_exif_error_output;
						}

						$result = $data . ' px.';
				}
				break;

			case 'E#A011': 	// ColorSpace (Samsung)

				switch( $brand ) {
					case 'SAMSUNG': 	// ColorSpace

						switch ( $data ) {
							case 0: $result = 'sRGB'; break;
							case 1: $result = 'Adobe RGB'; break;
							default: $result = $data;
						}
						break;

					default:

						$result = $data;
				}
				break;

			case 'E#A012': 	// SmartRange (Samsung)

				switch( $brand ) {
					case 'SAMSUNG': 	// ColorSpace

						switch ( $data ) {
							case 0: $result = __( 'Off', 'wp-photo-album-plus' ); break;
							case 1: $result = __( 'On', 'wp-photo-album-plus' ); break;
							default: $result = $data;
						}
						break;

					default:

						$result = $data;
				}
				break;

			case 'E#A20E':	// FocalPlaneXResolution
			case 'E#A20F':	// FocalPlaneYResolution

				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = round( $x / $y );
				break;

			case 'E#A210': 	// FocalPlaneResolutionUnit

				switch( $data ) {
					case 1: $result = __( 'None', 'wp-photo-album-plus' ); break;
					case 2: $result = 'inches'; break;
					case 3: $result = 'cm'; break;
					case 4: $result = 'mm'; break;
					case 5: $result = '&mu;m'; break;
					default: $result = __( 'Unknown', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A217': 	// SensingMethod

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 1:
						$result = __( 'Not defined', 'wp-photo-album-plus' );
						break;
					case 2:
						$result = __( 'One-chip color area sensor', 'wp-photo-album-plus' );
						break;
					case 3:
						$result = __( 'Two-chip color area sensor', 'wp-photo-album-plus' );
						break;
					case 4:
						$result = __( 'Three-chip color area sensor', 'wp-photo-album-plus' );
						break;
					case 5:
						$result = __( 'Color sequential area sensor', 'wp-photo-album-plus' );
						break;
					case 7:
						$result = __( 'Trilinear sensor', 'wp-photo-album-plus' );
						break;
					case 8:
						$result = __( 'Color sequential linear sensor', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A300': 	// FileSource

				switch( $data ) {
					case 1: $result = __( 'Film Scanner', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Reflection Print Scanner', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Digital Camera', 'wp-photo-album-plus' ); break;
					case "\x03\x00\x00\x00": $result = __( 'Sigma Digital Camera', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A401': 	// CustomRendered

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'Normal', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Custom', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'HDR', 'wp-photo-album-plus' ); break;
					case 6: $result = __( 'Panorama', 'wp-photo-album-plus' ); break;
					case 8: $result = __( 'Portrait', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A402': 	// ExposureMode

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0:
						$result = __( 'Auto exposure', 'wp-photo-album-plus' );
						break;
					case 1:
						$result = __( 'Manual exposure', 'wp-photo-album-plus' );
						break;
					case 2:
						$result = __( 'Auto bracket', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A403': 	// WhiteBalance

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 0: $result = __( 'Auto white balance', 'wp-photo-album-plus' );
						break;
					case 1: $result = __( 'Manual white balance', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A404': 	// DigitalZoomRatio

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				if ( $x == 0 ) {
					$result = __( 'Not used', 'wp-photo-album-plus' );
					return $result;
				}

				$result = $data;
				break;

			case 'E#A405': 	// FocalLengthIn35mmFilm

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				$result = $data . ' mm.';
				break;

			case 'E#A406': 	// SceneCaptureType

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch( $data ) {
					case 0: $result = __( 'Standard', 'wp-photo-album-plus' );
						break;
					case 1: $result = __( 'Landscape', 'wp-photo-album-plus' );
						break;
					case 2: $result = __( 'Portrait', 'wp-photo-album-plus' );
						break;
					case 3: $result = __( 'Night scene', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
						break;
				}
				break;

			case 'E#A407': 	// GainControl

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch( $data ) {
					case 0: $result = __( 'None', 'wp-photo-album-plus' );
						break;
					case 1: $result = __( 'Low gain up', 'wp-photo-album-plus' );
						break;
					case 2: $result = __( 'High gain up', 'wp-photo-album-plus' );
						break;
					case 3: $result = __( 'Low gain down', 'wp-photo-album-plus' );
						break;
					case 4: $result = __( 'High gain down', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A408': 	// Contrast

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 0:
						$result = __( 'Normal', 'wp-photo-album-plus' );
						break;
					case 1:
						$result = __( 'Soft', 'wp-photo-album-plus' );
						break;
					case 2:
						$result = __( 'Hard', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A409': 	// Saturation

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 0:
						$result = __( 'Normal', 'wp-photo-album-plus' );
						break;
					case 1:
						$result = __( 'Low saturation', 'wp-photo-album-plus' );
						break;
					case 2:
						$result = __( 'High saturation', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A40A': 	// Sharpness

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 0:
						$result = __( 'Normal', 'wp-photo-album-plus' );
						break;
					case 1:
						$result = __( 'Soft', 'wp-photo-album-plus' );
						break;
					case 2:
						$result = __( 'Hard', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#A40C': 	// SubjectDistanceRange

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0: $result = __( 'unknown', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Macro', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Close view', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Distant view', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#BC01': 	// PixelFormat

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch ( $data ) {
					case 0x5: $result = __( 'Black & White', 'wp-photo-album-plus' ); break;
					case 0x8: $result = __( '8-bit Gray', 'wp-photo-album-plus' ); break;
					case 0x9: $result = __( '16-bit BGR555', 'wp-photo-album-plus' ); break;
					case 0xa: $result = __( '16-bit BGR565', 'wp-photo-album-plus' ); break;
					case 0xb: $result = __( '16-bit Gray', 'wp-photo-album-plus' ); break;
					case 0xc: $result = __( '24-bit BGR', 'wp-photo-album-plus' ); break;
					case 0xd: $result = __( '24-bit RGB', 'wp-photo-album-plus' ); break;
					case 0xe: $result = __( '32-bit BGR', 'wp-photo-album-plus' ); break;
					case 0xf: $result = __( '32-bit BGRA', 'wp-photo-album-plus' ); break;
					case 0x10: $result = __( '32-bit PBGRA', 'wp-photo-album-plus' ); break;
					case 0x11: $result = __( '32-bit Gray Float', 'wp-photo-album-plus' ); break;
					case 0x12: $result = __( '48-bit RGB Fixed Point', 'wp-photo-album-plus' ); break;
					case 0x13: $result = __( '32-bit BGR101010', 'wp-photo-album-plus' ); break;
					case 0x15: $result = __( '48-bit RGB', 'wp-photo-album-plus' ); break;
					case 0x16: $result = __( '64-bit RGBA', 'wp-photo-album-plus' ); break;
					case 0x17: $result = __( '64-bit PRGBA', 'wp-photo-album-plus' ); break;
					case 0x18: $result = __( '96-bit RGB Fixed Point', 'wp-photo-album-plus' ); break;
					case 0x19: $result = __( '128-bit RGBA Float', 'wp-photo-album-plus' ); break;
					case 0x1a: $result = __( '128-bit PRGBA Float', 'wp-photo-album-plus' ); break;
					case 0x1b: $result = __( '128-bit RGB Float', 'wp-photo-album-plus' ); break;
					case 0x1c: $result = __( '32-bit CMYK', 'wp-photo-album-plus' ); break;
					case 0x1d: $result = __( '64-bit RGBA Fixed Point', 'wp-photo-album-plus' ); break;
					case 0x1e: $result = __( '128-bit RGBA Fixed Point', 'wp-photo-album-plus' ); break;
					case 0x1f: $result = __( '64-bit CMYK', 'wp-photo-album-plus' ); break;
					case 0x20: $result = __( '24-bit 3 Channels', 'wp-photo-album-plus' ); break;
					case 0x21: $result = __( '32-bit 4 Channels', 'wp-photo-album-plus' ); break;
					case 0x22: $result = __( '40-bit 5 Channels', 'wp-photo-album-plus' ); break;
					case 0x23: $result = __( '48-bit 6 Channels', 'wp-photo-album-plus' ); break;
					case 0x24: $result = __( '56-bit 7 Channels', 'wp-photo-album-plus' ); break;
					case 0x25: $result = __( '64-bit 8 Channels', 'wp-photo-album-plus' ); break;
					case 0x26: $result = __( '48-bit 3 Channels', 'wp-photo-album-plus' ); break;
					case 0x27: $result = __( '64-bit 4 Channels', 'wp-photo-album-plus' ); break;
					case 0x28: $result = __( '80-bit 5 Channels', 'wp-photo-album-plus' ); break;
					case 0x29: $result = __( '96-bit 6 Channels', 'wp-photo-album-plus' ); break;
					case 0x2a: $result = __( '112-bit 7 Channels', 'wp-photo-album-plus' ); break;
					case 0x2b: $result = __( '128-bit 8 Channels', 'wp-photo-album-plus' ); break;
					case 0x2c: $result = __( '40-bit CMYK Alpha', 'wp-photo-album-plus' ); break;
					case 0x2d: $result = __( '80-bit CMYK Alpha', 'wp-photo-album-plus' ); break;
					case 0x2e: $result = __( '32-bit 3 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x2f: $result = __( '40-bit 4 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x30: $result = __( '48-bit 5 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x31: $result = __( '56-bit 6 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x32: $result = __( '64-bit 7 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x33: $result = __( '72-bit 8 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x34: $result = __( '64-bit 3 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x35: $result = __( '80-bit 4 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x36: $result = __( '96-bit 5 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x37: $result = __( '112-bit 6 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x38: $result = __( '128-bit 7 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x39: $result = __( '144-bit 8 Channels Alpha', 'wp-photo-album-plus' ); break;
					case 0x3a: $result = __( '64-bit RGBA Half', 'wp-photo-album-plus' ); break;
					case 0x3b: $result = __( '48-bit RGB Half', 'wp-photo-album-plus' ); break;
					case 0x3d: $result = __( '32-bit RGBE', 'wp-photo-album-plus' ); break;
					case 0x3e: $result = __( '16-bit Gray Half', 'wp-photo-album-plus' ); break;
					case 0x3f: $result = __( '32-bit Gray Fixed Point', 'wp-photo-album-plus' ); break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'E#BC02': 	// Transformation

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'Horizontal (normal)', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Mirror vertical', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Mirror horizontal', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Rotate 180', 'wp-photo-album-plus' ); break;
					case 4: $result = __( 'Rotate 90 CW', 'wp-photo-album-plus' ); break;
					case 5: $result = __( 'Mirror horizontal and rotate 90 CW', 'wp-photo-album-plus' ); break;
					case 6: $result = __( 'Mirror horizontal and rotate 270 CW', 'wp-photo-album-plus' ); break;
					case 7: $result = __( 'Rotate 270 CW', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#BC03': 	// Uncompressed

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'No', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Yes', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#BC04': 	// ImageType

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = __( 'Preview', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Page', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Preview', 'wp-photo-album-plus' ) . ' ' . __( 'Page', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

/*
0xbc80 	ImageWidth
0xbc81 	ImageHeight
0xbc82 	WidthResolution
0xbc83 	HeightResolution
0xbcc0 	ImageOffset
0xbcc1 	ImageByteCount
0xbcc2 	AlphaOffset
0xbcc3 	AlphaByteCount
*/


			case 'E#BCC4': 	// ImageDataDiscard

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'Full Resolution', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Flexbits Discarded', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'HighPass Frequency Data Discarded', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Highpass and LowPass Frequency Data Discarded', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#BCC5': 	// AlphaDataDiscard

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'Full Resolution', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Flexbits Discarded', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'HighPass Frequency Data Discarded', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Highpass and LowPass Frequency Data Discarded', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;
/*
0xc427 	OceScanjobDesc
0xc428 	OceApplicationSelector
0xc429 	OceIDNumber
0xc42a 	OceImageLogic
0xc44f 	Annotations
0xc4a5 	PrintIM
0xc573 	OriginalFileName
*/
			case 'E#C580': 	// USPTOOriginalContentType

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'Text or Drawing', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Grayscale', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Color', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

/*
0xc5e0 	CR2CFAPattern
1 => '0 1 1 2' = [Red,Green][Green,Blue]
4 => '1 0 2 1' = [Green,Red][Blue,Green]
3 => '1 2 0 1' = [Green,Blue][Red,Green]
2 => '2 1 1 0' = [Blue,Green][Green,Red]


0xc612 	DNGVersion 	int8u[4]! 	IFD0 	(tags 0xc612-0xc7b5 are defined by the DNG specification unless otherwise noted. See https://helpx.adobe.com/photoshop/digital-negative.html for the specification)
0xc613 	DNGBackwardVersion 	int8u[4]! 	IFD0
0xc614 	UniqueCameraModel 	string 	IFD0
0xc615 	LocalizedCameraModel 	string 	IFD0
0xc616 	CFAPlaneColor 	no 	SubIFD
*/

			case 'E#C617': 	// CFALayout

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = __( 'Rectangular', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Even columns offset down 1/2 row', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Even columns offset up 1/2 row', 'wp-photo-album-plus' ); break;
					case 4: $result = __( 'Even rows offset right 1/2 column', 'wp-photo-album-plus' ); break;
					case 5: $result = __( 'Even rows offset left 1/2 column', 'wp-photo-album-plus' ); break;
					case 6: $result = __( 'Even rows offset up by 1/2 row, even columns offset left by 1/2 column', 'wp-photo-album-plus' ); break;
					case 7: $result = __( 'Even rows offset up by 1/2 row, even columns offset right by 1/2 column', 'wp-photo-album-plus' ); break;
					case 8: $result = __( 'Even rows offset down by 1/2 row, even columns offset left by 1/2 column', 'wp-photo-album-plus' ); break;
					case 9: $result = __( 'Even rows offset down by 1/2 row, even columns offset right by 1/2 column', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#C6FD': 	// ProfileEmbedPolicy

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'Allow Copying', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Embed if Used', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'Never Embed', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'No Restrictions', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#C71A': 	// PreviewColorSpace

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'Unknown', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Gray Gamma 2.2', 'wp-photo-album-plus' ); break;
					case 2: $result = __( 'sRGB', 'wp-photo-album-plus' ); break;
					case 3: $result = __( 'Adobe RGB', 'wp-photo-album-plus' ); break;
					case 4: $result = __( 'ProPhoto RGB', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#C7A3': 	// ProfileHueSatMapEncoding
			case 'E#C7A4': 	// ProfileLookTableEncoding

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'Linear', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'sRGB', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'E#C7A6': 	// DefaultBlackRender

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 0: $result = __( 'Auto', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'None', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;


			// FILE
			case 'F#0001': 	// FileName
				$result = $data;
				break;

			case 'F#0002': 	// FileDateTime
				$result = wppa_local_date( '', $data );
				break;

			case 'F#0003': 	// FileSize
				$result = $data . ' Bytes';
				break;

			case 'F#0004': 	// FileType

				if ( ! wppa_is_valid_integer( $data ) ) return $wppa_exif_error_output;

				switch( $data ) {
					case 1: $result = 'gif'; break;
					case 2: $result = 'jpg'; break;
					case 3: $result = 'png'; break;
					default: $result = __( 'reserved', 'wp-photo-album-plus' );
				}
				break;

			case 'F#0005': 	// MimeType
			case 'F#0006': 	// SectionsFound
				$result = $data;
				break;

			// GPS
			case 'G#0000': 	// GPSVersionID 	int8u[4]
				$result = $data;
				break;

			case 'G#0001': 	// GPSLatitudeRef 	string[2]
				switch( $data ) {
					case 'N': $result = __( 'North', 'wp-photo-album-plus' ); break;
					case 'S': $result = __( 'South', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#0002': 	// GPSLatitude 	rational64u[3]
				for( $i = 0; $i < 3; $i++ ) {
					if ( ! wppa_is_valid_rational( $data_arr[$i] ) ) return $wppa_exif_error_output;
					$data_arr[$i] = wppa_simplify_rational( $data_arr[$i], true );
				}
				$result = $data_arr[0] . '&deg;' . $data_arr[1] . "'" . $data_arr[2] . '"';
				break;

			case 'G#0003': 	// GPSLongitudeRef 	string[2]
				switch( $data ) {
					case 'E': $result = __( 'East', 'wp-photo-album-plus' ); break;
					case 'W': $result = __( 'West', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#0004': 	// GPSLongitude 	rational64u[3]
				for( $i = 0; $i < 3; $i++ ) {
					if ( ! wppa_is_valid_rational( $data_arr[$i] ) ) return $wppa_exif_error_output;
					$data_arr[$i] = wppa_simplify_rational( $data_arr[$i], true );
				}
				$result = $data_arr[0] . '&deg;' . $data_arr[1] . "'" . $data_arr[2] . '"';
				break;

			case 'G#0005': 	// GPSAltitudeRef 	int8u
				switch( $data ) {
					case 0: $result = __( 'Above Sea Level', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Below Sea Level', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#0006': 	// GPSAltitude 	rational64u
				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;
				$result = wppa_simplify_rational( $data, true, 2 );
				break;

			case 'G#0007': 	// GPSTimeStamp 	rational64u[3] 	UTC time of GPS fix.
				for( $i = 0; $i < 3; $i++ ) {
					if ( ! wppa_is_valid_rational( $data_arr[$i] ) ) return $wppa_exif_error_output;
					$data_arr[$i] = wppa_simplify_rational( $data_arr[$i], true );
				}
				$result = $data_arr[0] . ':' . $data_arr[1] . ':' . $data_arr[2];
				break;

			case 'G#0008': 	// GPSSatellites 	string
				$result = $data;
				break;

			case 'G#0009': 	// GPSStatus 	string[2]
				switch( $data ) {
					case 'A': $result = __( 'Measurement Active', 'wp-photo-album-plus' ); break;
					case 'V': $result = __( 'Measurement Void', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#000A': 	// GPSMeasureMode 	string[2]
				switch( $data ) {
					case '2': $result = __( '2-Dimensional Measurement', 'wp-photo-album-plus' ); break;
					case '3': $result = __( '3-Dimensional Measurement', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#000B': 	// GPSDOP 	rational64u
				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;
				$result = wppa_simplify_rational( $data, true );
				break;

			case 'G#000C': 	// GPSSpeedRef 	string[2]
				switch( $data ) {
					case 'K': $result = __( 'km/h', 'wp-photo-album-plus' ); break;
					case 'M': $result = __( 'mph', 'wp-photo-album-plus' ); break;
					case 'N': $result = __( 'knots', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#000D': 	// GPSSpeed 	rational64u
				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;
				$result = wppa_simplify_rational( $data, true );
				break;

			case 'G#000E': 	// GPSTrackRef 	string[2]
				switch( $data ) {
					case 'M': $result = __( 'Magnetic North', 'wp-photo-album-plus' ); break;
					case 'T': $result = __( 'True North', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#000F': 	// GPSTrack 	rational64u
				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;
				$result = wppa_simplify_rational( $data, true );
				break;

			case 'G#0010': 	// GPSImgDirectionRef 	string[2]
				switch( $data ) {
					case 'M': $result = __( 'Magnetic North', 'wp-photo-album-plus' ); break;
					case 'T': $result = __( 'True North', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#0011': 	// GPSImgDirection 	rational64u
				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;
				$result = wppa_simplify_rational( $data, true );
				break;

			case 'G#0012': 	// GPSMapDatum 	string
				$result = $data;
				break;

			case 'G#0013': 	// GPSDestLatitudeRef 	string[2]
				switch( $data ) {
					case 'N': $result = __( 'North', 'wp-photo-album-plus' ); break;
					case 'S': $result = __( 'South', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#0014': 	// GPSDestLatitude 	rational64u[3]
				for( $i = 0; $i < 3; $i++ ) {
					if ( ! wppa_is_valid_rational( $data_arr[$i] ) ) return $wppa_exif_error_output;
					$data_arr[$i] = wppa_simplify_rational( $data_arr[$i], true );
				}
				$result = $data_arr[0] . '&deg;' . $data_arr[1] . "'" . $data_arr[2] . '"';
				break;

			case 'G#0015': 	// GPSDestLongitudeRef 	string[2]
				switch( $data ) {
					case 'E': $result = __( 'East', 'wp-photo-album-plus' ); break;
					case 'W': $result = __( 'West', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#0016': 	// GPSDestLongitude 	rational64u[3]
				for( $i = 0; $i < 3; $i++ ) {
					if ( ! wppa_is_valid_rational( $data_arr[$i] ) ) return $wppa_exif_error_output;
					$data_arr[$i] = wppa_simplify_rational( $data_arr[$i], true );
				}
				$result = $data_arr[0] . '&deg;' . $data_arr[1] . "'" . $data_arr[2] . '"';
				break;

			case 'G#0017': 	// GPSDestBearingRef 	string[2]
				switch( $data ) {
					case 'M': $result = __( 'Magnetic North', 'wp-photo-album-plus' ); break;
					case 'T': $result = __( 'True North', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#0018': 	// GPSDestBearing 	rational64u
				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;
				$result = wppa_simplify_rational( $data, true );
				break;

			case 'G#0019': 	// GPSDestDistanceRef 	string[2]
				switch( $data ) {
					case 'K': $result = __( 'Kilometers', 'wp-photo-album-plus' ); break;
					case 'M': $result = __( 'Miles', 'wp-photo-album-plus' ); break;
					case 'N': $result = __( 'Nautical Miles', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#001A': 	// GPSDestDistance 	rational64u
				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;
				$result = wppa_simplify_rational( $data, true );
				break;

			case 'G#001B': 	// GPSProcessingMethod 	undef
			case 'G#001C': 	// GPSAreaInformation 	undef
			case 'G#001D': 	// GPSDateStamp 	string[11]
				$result = $data;
				break;

			case 'G#001E': 	// GPSDifferential 	int16u
				switch( $data ) {
					case 0: $result = __( 'No Correction', 'wp-photo-album-plus' ); break;
					case 1: $result = __( 'Differential Corrected', 'wp-photo-album-plus' ); break;
					default: $result = __( 'Undefined', 'wp-photo-album-plus' );
				}
				break;

			case 'G#001F': 	// GPSHPositioningError 	rational64u
				if ( ! wppa_is_valid_rational( $data ) ) return $wppa_exif_error_output;
				$result = wppa_simplify_rational( $data, true );
				break;


			// Unformatted
			default:
				$result = sanitize_text_field( $data );
				break;
		}
	}

	// Empty data
	else {
		$result = '';
	}

	return esc_html( $result );
}

function wppa_is_valid_rational( $data, $complain = true ) {
global $wppa_exif_error_output;

	// Must contain a '/'
	if ( strpos( $data, '/' ) == false ) {
		if ( $complain ) $wppa_exif_error_output = __( 'Missing /', 'wp-photo-album-plus' );
		return false;
	}

	// make array
	$t = explode( '/', $data );

	// Numeric?
	if ( ! is_numeric( $t[0] ) || ! is_numeric ( $t[1] ) ) {
		if ( $complain ) $wppa_exif_error_output = __( 'Not rational', 'wp-photo-album-plus' );
		return false;
	}

	// Divide by zero?
	if ( $t[1] == 0 ) {
		if ( $complain ) $wppa_exif_error_output = __( 'Divide by zero', 'wp-photo-album-plus' );
		return false;
	}

	// May be zero
	if ( $t[0] / $t[1] == 0 ) {
		return true;
	}

	// Unlikely value?
	if ( $t[0] / $t[1] > 100000 || abs( $t[0] / $t[1] ) < 0.00001 ) {
		if ( $complain ) $wppa_exif_error_output = __( 'Unlikely value', 'wp-photo-album-plus' );
		return false;
	}

	// Ok.
	return true;
}

function wppa_simplify_rational( $data, $divide = false, $dec = 0 ) {

	// make array
	$t = explode( '/', $data );
	$x = $t[0];
	$y = $t[1];

	// Divide result?
	if ( $divide ) {
		if ( $y ) {
			$result = $x / $y;
			if ( $dec ) {
				$result = sprintf( '%4.' . $dec . 'f', $result );
			}
		}
		else {
			$result = $data;
		}
		return $result;
	}

	// Is already simplified to the max?
	if ( $x == 1 ) {
		$result = $data;
		return $result;
	}

	// Result is zero?
	if ( $x == 0 ) {
		$result = $data;
		return $result;
	}

	// See if it can be simplified to '1/nn'
	if ( round( $y / $x ) == $y / $x ) {
		$result = '1/' . ( $y / $x );
		return $result;
	}

	// Continue simplifying
	$prime = array(2,3,5,7,11,13,17,19,23,29,31);
	foreach( $prime as $p ) {
		while ( wppa_is_divisible( $x, $p ) && wppa_is_divisible( $y, $p ) ) {
			$x = $x / $p;
			$y = $y / $p;
		}
	}
	$result = $x . '/' . $y;

	return $result;
}

function wppa_is_valid_integer( $data ) {
global $wppa_exif_error_output;

	// Must be integer
	if ( ! wppa_is_int( $data ) ) {
		$wppa_exif_error_output = __( 'Invalid format', 'wp-photo-album-plus' );
		return false;
	}

	// Ok.
	return true;
}

function wppa_iptc_clean_garbage() {
global $wpdb;

	// Remove labels that are no longer used
	$labels = $wpdb->get_results( "SELECT DISTINCT tag FROM $wpdb->wppa_iptc WHERE photo = '0'", ARRAY_A );
	if ( ! empty( $labels ) ) {
		foreach( $labels as $label ) {
			$used = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_iptc WHERE tag = %s AND photo <> '0'", $label['tag'] ) );
			if ( $used == 0 ) {
				$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->wppa_iptc WHERE tag = %s AND photo = '0'", $label['tag'] ) );
				wppa_log( 'dbg', 'Iptc tag label ' . $label['tag'] . ' removed.' );
			}
		}
	}
}

function wppa_exif_clean_garbage() {
global $wpdb;

	// Remove labels that are no longer used
	$labels = $wpdb->get_results( "SELECT DISTINCT tag FROM $wpdb->wppa_exif WHERE photo = '0'", ARRAY_A );
	if ( ! empty( $labels ) ) {
		foreach( $labels as $label ) {
			$used = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_exif WHERE tag = %s AND photo <> '0'", $label['tag'] ) );
			if ( $used == 0 ) {
				$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->wppa_exif WHERE tag = %s AND photo = '0'", $label['tag'] ) );
				wppa_log( 'dbg', 'Exif tag label ' . $label['tag'] . ' removed.' );
			}
		}
	}
}

// (Re-)calculate and store formatted exif entries for photo $photo
function wppa_fix_exif_format( $photo ) {
global $wpdb;

	if ( ! wppa_is_int( $photo ) ) {
		wppa_log( 'Err', 'wppa_fix_exif_format() called with arg: ' . $photo );
		return false;
	}

	$exifs = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_exif WHERE photo = $photo", ARRAY_A );

	if ( ! empty( $exifs ) ) {

		$brand = wppa_get_camera_brand( $photo );

		foreach( $exifs as $exif ) {

			$f_description 	= strip_tags( wppa_format_exif( $exif['tag'], $exif['description'], $brand ) );
			$tagbrand 		= trim( wppa_exif_tagname( $exif['tag'], $brand, 'brandonly' ), ': ' ) ? $brand : '';

			// If f_description or thabrand changed: update
			if ( $f_description != $exif['f_description'] || $tagbrand != $exif['brand'] ) {
				$id = $exif['id'];
				$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_exif SET f_description = %s, brand = %s WHERE id = %s", $f_description, $tagbrand, $id ) );
				$photodata = wppa_cache_photo( $photo );

				// If the format changed and the exif tag is used in the description, the photo must be re-indexed
				if ( strpos( $photodata['description'], $exif['tag'] ) !== false ) {
					wppa_update_photo( $photo, ['indexdtm' => ''] );
					wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
				}
			}
		}
	}
}

// Process the iptc data
function wppa_import_iptc( $id, $info, $nodelete = false ) {
global $wpdb;
static $labels;

	$doit = false;
	// Do we need this?
	if ( wppa_switch( 'save_iptc' ) ) $doit = true;
	if ( substr( wppa_opt( 'newphoto_name_method' ), 0, 2 ) == '2#' ) $doit = true;
	if ( ! $doit ) return;

	// Is iptc data present?
	if ( !isset( $info['APP13'] ) ) return false;	// No iptc data avail

	// Parse
	$iptc = iptcparse( $info['APP13'] );
	if ( ! is_array( $iptc ) ) return false;		// No data avail

	// There is iptc data for this image.
	// First delete any existing ipts data for this image
	if ( ! $nodelete ) {
		wppa_del_row( WPPA_IPTC, 'photo', $id );
	}

	// Find defined labels
	if ( ! is_array( $labels ) ) {
		$result = $wpdb->get_results( "SELECT tag FROM $wpdb->wppa_iptc WHERE photo = '0' ORDER BY tag", ARRAY_N );

		if ( ! is_array( $result ) ) $result = array();
		$labels = array();
		foreach ( $result as $res ) {
			$labels[] = $res['0'];
		}
	}

	foreach ( array_keys( $iptc ) as $s ) {

		// Check for valid item
		if ( $s == '2#000' ) continue; 	// Skip this one
		if ( $s == '1#000' ) continue; 	// Skip this one

		if ( is_array( $iptc[$s] ) ) {
			$c = count ( $iptc[$s] );
			for ( $i=0; $i <$c; $i++ ) {

				// Check labels first
				if ( ! in_array( $s, $labels ) ) {

					// Add to labels
					$labels[] = $s;

					// Add to db
					$photo 	= '0';
					$tag 	= $s;
					$desc 	= $s.':';
						if ( $s == '2#005' ) $desc = 'Graphic name:';
						if ( $s == '2#010' ) $desc = 'Urgency:';
						if ( $s == '2#015' ) $desc = 'Category:';
						if ( $s == '2#020' ) $desc = 'Supp categories:';
						if ( $s == '2#040' ) $desc = 'Spec instr:';
						if ( $s == '2#055' ) $desc = 'Creation date:';
						if ( $s == '2#080' ) $desc = 'Photographer:';
						if ( $s == '2#085' ) $desc = 'Credit byline title:';
						if ( $s == '2#090' ) $desc = 'City:';
						if ( $s == '2#095' ) $desc = 'State:';
						if ( $s == '2#101' ) $desc = 'Country:';
						if ( $s == '2#103' ) $desc = 'Otr:';
						if ( $s == '2#105' ) $desc = 'Headline:';
						if ( $s == '2#110' ) $desc = 'Source:';
						if ( $s == '2#115' ) $desc = 'Photo source:';
						if ( $s == '2#120' ) $desc = 'Caption:';
					$status = 'display';
						if ( $s == '1#090' ) $status = 'hide';
						if ( $desc == $s.':' ) $status= 'hide';
					//	if ( $s == '2#000' ) $status = 'hide';
					$bret = wppa_create_iptc_entry( array( 'photo' => $photo, 'tag' => $tag, 'description' => $desc, 'status' => $status ) );
					if ( ! $bret ) wppa_log( 'War', 'Could not add IPTC tag '.$tag.' for photo '.$photo );
				}

				// Now add poto specific data item
				$photo 	= $id;
				$tag 	= $s;
				$desc 	= $iptc[$s][$i];
				if ( ! seems_utf8( $desc ) ) {
					$desc 	= utf8_encode( $desc );
				}
				$status = 'default';
				$bret = wppa_create_iptc_entry( array( 'photo' => $photo, 'tag' => $tag, 'description' => $desc, 'status' => $status ) );
				if ( ! $bret ) wppa_log( 'War', 'Could not add IPTC tag '.$tag.' for photo '.$photo );
			}
		}
	}
}

function wppa_get_exif_datetime( $file ) {

	// Make sure we do not process a -o1.jpg file
	$file = str_replace( '-o1.jpg', '.jpg', $file );
	return wppa_get_exif_item( $file, 'DateTimeOriginal' );
}

function wppa_get_exif_orientation( $file ) {

	return wppa_get_exif_item( $file, 'Orientation' );
}

function wppa_get_exif_item( $file, $item ) {

	// File exists?
	if ( ! is_file( $file ) ) {
		return false;
	}

	// Exif functions present?
	if ( ! function_exists( 'exif_imagetype' ) ) {
		return false;
	}

	// Check filetype
	$image_type = @ exif_imagetype( $file );
	if ( $image_type != IMAGETYPE_JPEG ) {
		return false;
	}

	// Get exif data
	$exif = wppa_exif_read_data( $file, 'EXIF' );

	// Data present
	if ( isset( $exif[$item] ) ) {
		return $exif[$item];
	}

	// Nothing found
	return false;
}


function wppa_import_exif( $id, $file, $nodelete = false ) {
global $wpdb;
static $labels;
static $names;
global $wppa;

	// Do we need this?
	if ( ! wppa_switch( 'save_exif' ) ) return;

	// Not on pdf
	if ( wppa_is_pdf( $id ) ) return;

	// Make sure we do not process a -o1.jpg file
	$file = str_replace( '-o1.jpg', '.jpg', $file );

	// Check filetype
	if ( ! function_exists( 'exif_imagetype' ) ) return false;

	$image_type = @ exif_imagetype( $file );
	if ( $image_type != IMAGETYPE_JPEG ) return false;	// Not supported image type

	// Get exif data
	$exif = wppa_exif_read_data( $file, 'ANY_TAG' );
	if ( empty( $exif ) ) return false;			// No data present

	// There is exif data for this image.
	// First delete any existing exif data for this image
	if ( ! $nodelete ) {
		wppa_del_row( WPPA_EXIF, 'photo', $id );
	}

	// Find defined labels
	if ( ! is_array( $labels ) ) {
		$result = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_exif WHERE photo = '0' ORDER BY tag", ARRAY_A );

		if ( ! is_array( $result ) ) $result = array();
		$labels = array();
		$names  = array();
		foreach ( $result as $res ) {
			$labels[] = $res['tag'];
			$names[]  = $res['description'];
		}
	}

	// Process items
	foreach ( array_keys( $exif ) as $s ) {

		// Check labels first
		$tag = '';
		if ( in_array( $s, $names ) ) {
			$i = 0;
			while ( $i < count( $labels ) ) {
				if ( $names[$i] == $s ) {
					$tag = $labels[$i];
				}
				$i++;
			}
		}

		if ( $tag == '' ) $tag = wppa_exif_tag( $s );
		if ( $tag == 'E#EA1C' ) $tag = ''; // EA1C is explixitly undefined and will fail to register
		if ( $tag == '' ) continue;

		if ( ! in_array( $tag, $labels ) ) {

			// Add to labels
			$labels[] = $tag;
			$names[]  = $s.':';

			// Add to db
			$photo 	= '0';
			$desc 	= $s.':';
			$status = 'display';
			if ( substr( $s, 0, 12 ) == 'UndefinedTag' ) {
				$status = 'option';
				$desc = wppa_exif_tagname( $tag );
				if ( substr( $desc, 0, 12 ) != 'UndefinedTag' ) {
					$status = 'display';
				}
			}

			$bret = wppa_create_exif_entry( array( 'photo' => $photo, 'tag' => $tag, 'description' => $desc, 'status' => $status ) );
			if ( ! $bret ) wppa_log( 'War', 'Could not add EXIF tag label '.$tag.' for photo '.$photo );
		}

		// Now add poto specific data item
		// If its an array...
		if ( is_array( $exif[$s] ) ) {

			$desc = serialize( $exif[$s] );
		}

		// Its not an array
		else {

			$desc 	= $exif[$s];
		}

		$photo 	= $id;
		$status = 'default';
		$bret 	= wppa_create_exif_entry( array( 'photo' => $photo, 'tag' => $tag, 'description' => $desc, 'status' => $status ) );
	}

	wppa_fix_exif_format( $id );
}

// Convert exif tagname as found by exif_read_data() to E#XXXX, Inverse of exif_tagname();
function wppa_exif_tag( $tagname ) {
static $wppa_inv_exiftags;
static $wppa_inv_gpstags;
static $wppa_inv_filetags;

	// Setup inverted matrix standard tags
	if ( ! is_array( $wppa_inv_exiftags ) ) {
		if ( function_exists( 'exif_tagname' ) ) {
			$key = 0;
			while ( $key < 65536 ) {
				$tag = @ exif_tagname( $key );
				if ( $tag != '' ) {
					$wppa_inv_exiftags[$tag] = $key;
				}
				$key++;
				if ( ! $key ) break;	// 16 bit server wrap around ( do they still exist??? )
			}
		}
		else {
			$wppa_inv_exiftags = array();
		}
	}

	// Setup inverted matrix filetags
	if ( ! is_array( $wppa_inv_filetags ) ) {
		$wppa_inv_filetags = array(
									'FileName' 		=> 0x0001,
									'FileDateTime'	=> 0x0002,
									'FileSize' 		=> 0x0003,
									'FileType' 		=> 0x0004,
									'MimeType' 		=> 0x0005,
									'SectionsFound' => 0x0006,
									);
	}

	// Setup inverted matrix GPS tags
	if ( ! is_array( $wppa_inv_gpstags ) ) {
		$wppa_inv_gpstags = array(
									'GPSVersionID' 			=> 0x0000,	// int8u[4]
									'GPSLatitudeRef' 		=> 0x0001, 	// string[2] 'N' = North 'S' = South
									'GPSLatitude' 			=> 0x0002, 	// rational64u[3]
									'GPSLongitudeRef' 		=> 0x0003, 	// string[2] 'E' = East 'W' = West
									'GPSLongitude' 			=> 0x0004, 	// rational64u[3]
									'GPSAltitudeRef' 		=> 0x0005, 	// int8u 0 = Above Sea Level 1 = Below Sea Level
									'GPSAltitude' 			=> 0x0006,	// rational64u
									'GPSTimeStamp' 			=> 0x0007, 	// rational64u[3]
									'GPSSatellites' 		=> 0x0008, 	// string
									'GPSStatus' 			=> 0x0009, 	// string[2] 	'A' = Measurement Active 'V' = Measurement Void
									'GPSMeasureMode' 		=> 0x000a, 	// string[2] 	2 = 2-Dimensional Measurement 3 = 3-Dimensional Measurement
									'GPSDOP' 				=> 0x000b, 	// rational64u
									'GPSSpeedRef' 			=> 0x000c, 	// string[2] 	'K' = km/h 'M' = mph 'N' = knots
									'GPSSpeed' 				=> 0x000d, 	// rational64u
									'GPSTrackRef' 			=> 0x000e, 	// string[2] 	'M' = Magnetic North 'T' = True North
									'GPSTrack' 				=> 0x000f, 	// rational64u
									'GPSImgDirectionRef' 	=> 0x0010, 	// string[2] 	'M' = Magnetic North 'T' = True North
									'GPSImgDirection' 		=> 0x0011, 	// rational64u
									'GPSMapDatum' 			=> 0x0012, 	// string
									'GPSDestLatitudeRef' 	=> 0x0013, 	// string[2] 	'N' = North 'S' = South
									'GPSDestLatitude' 		=> 0x0014, 	// rational64u[3]
									'GPSDestLongitudeRef' 	=> 0x0015, 	// string[2] 	'E' = East 'W' = West
									'GPSDestLongitude' 		=> 0x0016, 	// rational64u[3]
									'GPSDestBearingRef' 	=> 0x0017, 	// string[2] 	'M' = Magnetic North 'T' = True North
									'GPSDestBearing' 		=> 0x0018, 	// rational64u
									'GPSDestDistanceRef' 	=> 0x0019, 	// string[2] 	'K' = Kilometers 'M' = Miles 'N' = Nautical Miles
									'GPSDestDistance' 		=> 0x001a, 	// rational64u
									'GPSProcessingMethod' 	=> 0x001b, 	// undef 	(values of "GPS", "CELLID", "WLAN" or "MANUAL" by the EXIF spec.)
									'GPSAreaInformation' 	=> 0x001c, 	// undef
									'GPSDateStamp' 			=> 0x001d, 	// string[11] 	(Format is YYYY:mm:dd)
									'GPSDifferential' 		=> 0x001e, 	// int16u 	0 = No Correction 1 = Differential Corrected
									'GPSHPositioningError' 	=> 0x001f, 	// rational64u
									);
	}

	// Search
	if ( isset( $wppa_inv_exiftags[$tagname] ) ) {
		return sprintf( 'E#%04X', $wppa_inv_exiftags[$tagname] );
	}
	elseif ( isset( $wppa_inv_filetags[$tagname] ) ) {
		return sprintf( 'F#%04X', $wppa_inv_filetags[$tagname] );
	}
	elseif ( isset( $wppa_inv_gpstags[$tagname] ) ) {
		return sprintf( 'G#%04X', $wppa_inv_gpstags[$tagname] );
	}
	elseif ( strlen( $tagname ) == 19 ) {
		if ( substr( $tagname, 0, 12 ) == 'UndefinedTag' ) return 'E#'.substr( $tagname, -4 );
	}
	else return '';
}

// Wrapper around exif_tagname(), convert E#XXXX, F# XXXX or G#XXXX to TagName
function wppa_exif_tagname( $tag, $brand = '', $brandonly = false ) {
global $wpdb;
static $canontags;
static $nikontags;
static $samsungtags;
static $editabletags;
static $commontags;
static $gpstags;
static $filetags;

if ( strlen($tag) != 6 ) {
	wppa_log('Err', 'wppa_exif_tagname() called with tag = '.$tag, true);
}
	// Fill $canontags if not done yet
	if ( empty( $canontags ) ) {
		$canontags = array(
							'E#0001' => 'CanonCameraSettings',
							'E#0002' => 'CanonFocalLength',
							'E#0003' => 'CanonFlashInfo?',
							'E#0004' => 'CanonShotInfo',
							'E#0005' => 'CanonPanorama',
							'E#0006' => 'CanonImageType',
							'E#0007' => 'CanonFirmwareVersion',
							'E#0008' => 'FileNumber',
							'E#0009' => 'OwnerName',
							'E#000A' => 'UnknownD30',
							'E#000C' => 'SerialNumber',
							'E#000D' => 'CanonCameraInfo',
							'E#000E' => 'CanonFileLength',
							'E#000F' => 'CustomFunctions',
							'E#0010' => 'CanonModelID',
							'E#0011' => 'MovieInfo',
							'E#0012' => 'CanonAFInfo',
							'E#0013' => 'ThumbnailImageValidArea',
							'E#0015' => 'SerialNumberFormat',
							'E#001A' => 'SuperMacro',
							'E#001C' => 'DateStampMode',
							'E#001D' => 'MyColors',
							'E#001E' => 'FirmwareRevision',
							'E#0023' => 'Categories',
							'E#0024' => 'FaceDetect1',
							'E#0025' => 'FaceDetect2',
							'E#0026' => 'CanonAFInfo2',
							'E#0027' => 'ContrastInfo',
							'E#0028' => 'ImageUniqueID',
							'E#002F' => 'FaceDetect3',
							'E#0035' => 'TimeInfo',
							'E#003C' => 'AFInfo3',
							'E#0081' => 'RawDataOffset',
							'E#0083' => 'OriginalDecisionDataOffset',
							'E#0090' => 'CustomFunctions1D',
							'E#0091' => 'PersonalFunctions',
							'E#0092' => 'PersonalFunctionValues',
							'E#0093' => 'CanonFileInfo',
							'E#0094' => 'AFPointsInFocus1D',
							'E#0095' => 'LensModel',
							'E#0096' => 'SerialInfo',
							'E#0097' => 'DustRemovalData',
							'E#0098' => 'CropInfo',
							'E#0099' => 'CustomFunctions2',
							'E#009A' => 'AspectInfo',
							'E#00A0' => 'ProcessingInfo',
							'E#00A1' => 'ToneCurveTable',
							'E#00A2' => 'SharpnessTable',
							'E#00A3' => 'SharpnessFreqTable',
							'E#00A4' => 'WhiteBalanceTable',
							'E#00A9' => 'ColorBalance',
							'E#00AA' => 'MeasuredColor',
							'E#00AE' => 'ColorTemperature',
							'E#00B0' => 'CanonFlags',
							'E#00B1' => 'ModifiedInfo',
							'E#00B2' => 'ToneCurveMatching',
							'E#00B3' => 'WhiteBalanceMatching',
							'E#00B4' => 'ColorSpace',
							'E#00B6' => 'PreviewImageInfo',
							'E#00D0' => 'VRDOffset',
							'E#00E0' => 'SensorInfo',
							'E#4001' => 'ColorData',
							'E#4002' => 'CRWParam?',
							'E#4003' => 'ColorInfo',
							'E#4005' => 'Flavor?',
							'E#4008' => 'PictureStyleUserDef',
							'E#4009' => 'PictureStylePC',
							'E#4010' => 'CustomPictureStyleFileName',
							'E#4013' => 'AFMicroAdj',
							'E#4015' => 'VignettingCorr',
							'E#4016' => 'VignettingCorr2',
							'E#4018' => 'LightingOpt',
							'E#4019' => 'LensInfo',
							'E#4020' => 'AmbienceInfo',
							'E#4021' => 'MultiExp',
							'E#4024' => 'FilterInfo',
							'E#4025' => 'HDRInfo',
							'E#4028' => 'AFConfig',
		);
	}

	// Fill $nikontags if not done yet
	if ( empty( $nikontags ) ) {
		$nikontags = array(
							'E#0001' => 'MakerNoteVersion',
							'E#0002' => 'ISO',
							'E#0003' => 'ColorMode',
							'E#0004' => 'Quality',
							'E#0005' => 'WhiteBalance',
							'E#0006' => 'Sharpness',
							'E#0007' => 'FocusMode',
							'E#0008' => 'FlashSetting',
							'E#0009' => 'FlashType',
							'E#000B' => 'WhiteBalanceFineTune',
							'E#000C' => 'WB_RBLevels',
							'E#000D' => 'ProgramShift',
							'E#000E' => 'ExposureDifference',
							'E#000F' => 'ISOSelection',
							'E#0010' => 'DataDump',
							'E#0011' => 'PreviewIFD',
							'E#0012' => 'FlashExposureComp',
							'E#0013' => 'ISOSetting',
							'E#0014' => 'ColorBalanceA',
							'E#0016' => 'ImageBoundary',
							'E#0017' => 'ExternalFlashExposureComp',
							'E#0018' => 'FlashExposureBracketValue',
							'E#0019' => 'ExposureBracketValue',
							'E#001A' => 'ImageProcessing',
							'E#001B' => 'CropHiSpeed',
							'E#001C' => 'ExposureTuning',
							'E#001D' => 'SerialNumber',
							'E#001E' => 'ColorSpace',
							'E#001F' => 'VRInfo',
							'E#0020' => 'ImageAuthentication',
							'E#0021' => 'FaceDetect',
							'E#0022' => 'ActiveD-Lighting',
							'E#0023' => 'PictureControlData',
							'E#0024' => 'WorldTime',
							'E#0025' => 'ISOInfo',
							'E#002A' => 'VignetteControl',
							'E#002B' => 'DistortInfo',
							'E#002C' => 'UnknownInfo',
							'E#0032' => 'UnknownInfo2',
							'E#0035' => 'HDRInfo',
							'E#0039' => 'LocationInfo',
							'E#003D' => 'BlackLevel',
							'E#004F' => 'ColorTemperatureAuto',
							'E#0080' => 'ImageAdjustment',
							'E#0081' => 'ToneComp',
							'E#0082' => 'AuxiliaryLens',
							'E#0083' => 'LensType',
							'E#0084' => 'Lens',
							'E#0085' => 'ManualFocusDistance',
							'E#0086' => 'DigitalZoom',
							'E#0087' => 'FlashMode',
							'E#0088' => 'AFInfo',
							'E#0089' => 'ShootingMode',
							'E#008B' => 'LensFStops',
							'E#008C' => 'ContrastCurve',
							'E#008D' => 'ColorHue',
							'E#008F' => 'SceneMode',
							'E#0090' => 'LightSource',
							'E#0091' => 'ShotInfo',
							'E#0092' => 'HueAdjustment',
							'E#0093' => 'NEFCompression',
							'E#0094' => 'Saturation',
							'E#0095' => 'NoiseReduction',
							'E#0096' => 'NEFLinearizationTable',
							'E#0097' => 'ColorBalance',
							'E#0099' => 'RawImageCenter',
							'E#009A' => 'SensorPixelSize',
							'E#009C' => 'SceneAssist',
							'E#009E' => 'RetouchHistory',
							'E#00A0' => 'SerialNumber',
							'E#00A2' => 'ImageDataSize',
							'E#00A5' => 'ImageCount',
							'E#00A6' => 'DeletedImageCount',
							'E#00A7' => 'ShutterCount',
							'E#00A8' => 'FlashInfo',
							'E#00A9' => 'ImageOptimization',
							'E#00AA' => 'Saturation',
							'E#00AB' => 'VariProgram',
							'E#00AC' => 'ImageStabilization',
							'E#00AD' => 'AFResponse',
							'E#00B0' => 'MultiExposure',
							'E#00B1' => 'HighISONoiseReduction',
							'E#00B3' => 'ToningEffect',
							'E#00B6' => 'PowerUpTime',
							'E#00B7' => 'AFInfo2',
							'E#00B8' => 'FileInfo',
							'E#00B9' => 'AFTune',
							'E#00BB' => 'RetouchInfo',
							'E#00BD' => 'PictureControlData',
							'E#00C3' => 'BarometerInfo',
							'E#0E00' => 'PrintIM',
							'E#0E01' => 'NikonCaptureData',
							'E#0E09' => 'NikonCaptureVersion',
							'E#0E0E' => 'NikonCaptureOffsets',
							'E#0E10' => 'NikonScanIFD',
							'E#0E13' => 'NikonCaptureEditVersions',
							'E#0E1D' => 'NikonICCProfile',
							'E#0E1E' => 'NikonCaptureOutput',
							'E#0E22' => 'NEFBitDepth',

		);
	}

	// Fill $samsungtags
	if ( empty( $samsungtags ) ) {
		$samsungtags = array(
							'E#0001' => 'MakerNoteVersion',
							'E#0002' => 'DeviceType',
							'E#0003' => 'SamsungModelID',
							'E#0011' => 'OrientationInfo',
							'E#0020' => 'SmartAlbumColor',
							'E#0021' => 'PictureWizard',
							'E#0030' => 'LocalLocationName',
							'E#0031' => 'LocationName',
							'E#0035' => 'PreviewIFD',
							'E#0040' => 'RawDataByteOrder',
							'E#0041' => 'WhiteBalanceSetup',
							'E#0043' => 'CameraTemperature',
							'E#0050' => 'RawDataCFAPattern',
							'E#0100' => 'FaceDetect',
							'E#0120' => 'FaceRecognition',
							'E#0123' => 'FaceName',
							'E#A001' => 'FirmwareName',
							'E#A003' => 'LensType',
							'E#A004' => 'LensFirmware',
							'E#A005' => 'InternalLensSerialNumber',
							'E#A010' => 'SensorAreas',
							'E#A011' => 'ColorSpace',
							'E#A012' => 'SmartRange',
							'E#A013' => 'ExposureCompensation',
							'E#A014' => 'ISO',
							'E#A018' => 'ExposureTime',
							'E#A019' => 'FNumber',
							'E#A01A' => 'FocalLengthIn35mmFormat',
							'E#A020' => 'EncryptionKey',
							'E#A021' => 'WB_RGGBLevelsUncorrected',
							'E#A022' => 'WB_RGGBLevelsAuto',
							'E#A023' => 'WB_RGGBLevelsIlluminator1',
							'E#A024' => 'WB_RGGBLevelsIlluminator2',
							'E#A025' => 'HighlightLinearityLimit',
							'E#A028' => 'WB_RGGBLevelsBlack',
							'E#A030' => 'ColorMatrix',
							'E#A031' => 'ColorMatrixSRGB',
							'E#A032' => 'ColorMatrixAdobeRGB',
							'E#A033' => 'CbCrMatrixDefault',
							'E#A034' => 'CbCrMatrix',
							'E#A035' => 'CbCrGainDefault',
							'E#A036' => 'CbCrGain',
							'E#A040' => 'ToneCurveSRGBDefault',
							'E#A041' => 'ToneCurveAdobeRGBDefault',
							'E#A042' => 'ToneCurveSRGB',
							'E#A043' => 'ToneCurveAdobeRGB',
							'E#A048' => 'RawData?',
							'E#A050' => 'Distortion?',
							'E#A051' => 'ChromaticAberration?',
							'E#A052' => 'Vignetting?',
							'E#A053' => 'VignettingCorrection?',
							'E#A054' => 'VignettingSetting?',

		);
	}

	// Fill $editabletags
	if ( empty( $editabletags ) ) {
		$temp = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_exif WHERE photo = '0'", ARRAY_A );
		$editabletags = array();
		if ( is_array( $temp ) ) foreach ( $temp as $item ) {
			$editabletags[ hexdec( substr( $item['tag'], 2, 4 ) ) ] = trim( $item['description'], ': ' );
		}
	}

	if ( empty( $commontags ) ) {
		$commontags = array(
							'E#0001' => 'InteropIndex',
							'E#0002' => 'InteropVersion',
							'E#000B' => 'ProcessingSoftware',
							'E#00FE' => 'SubfileType',
							'E#00FF' => 'OldSubfileType',
							'E#0100' => 'ImageWidth',
							'E#0101' => 'ImageHeight',
							'E#0102' => 'BitsPerSample',
							'E#0103' => 'Compression',
							'E#0106' => 'PhotometricInterpretation',
							'E#0107' => 'Thresholding',
							'E#0108' => 'CellWidth',
							'E#0109' => 'CellLength',
							'E#010A' => 'FillOrder',
							'E#010D' => 'DocumentName',
							'E#010E' => 'ImageDescription',
							'E#010F' => 'Make',
							'E#0110' => 'Model',
							'E#0111' => 'StripOffsets',
							'E#0112' => 'Orientation',
							'E#0115' => 'SamplesPerPixel',
							'E#0116' => 'RowsPerStrip',
							'E#0117' => 'StripByteCounts',
							'E#0118' => 'MinSampleValue',
							'E#0119' => 'MaxSampleValue',
							'E#011A' => 'XResolution',
							'E#011B' => 'YResolution',
							'E#011C' => 'PlanarConfiguration',
							'E#011D' => 'PageName',
							'E#011E' => 'XPosition',
							'E#011F' => 'YPosition',
							'E#0120' => 'FreeOffsets',
							'E#0121' => 'FreeByteCounts',
							'E#0122' => 'GrayResponseUnit',
							'E#0123' => 'GrayResponseCurve',
							'E#0124' => 'T4Options',
							'E#0125' => 'T6Options',
							'E#0128' => 'ResolutionUnit',
							'E#0129' => 'PageNumber',
							'E#012C' => 'ColorResponseUnit',
							'E#012D' => 'TransferFunction',
							'E#0131' => 'Software',
							'E#0132' => 'ModifyDate',
							'E#013B' => 'Artist',
							'E#013C' => 'HostComputer',
							'E#013D' => 'Predictor',
							'E#013E' => 'WhitePoint',
							'E#013F' => 'PrimaryChromaticities',
							'E#0140' => 'ColorMap',
							'E#0141' => 'HalftoneHints',
							'E#0142' => 'TileWidth',
							'E#0143' => 'TileLength',
							'E#0144' => 'TileOffsets',
							'E#0145' => 'TileByteCounts',
							'E#0146' => 'BadFaxLines',
							'E#0147' => 'CleanFaxData',
							'E#0148' => 'ConsecutiveBadFaxLines',
							'E#014A' => 'SubIFD',
							'E#014C' => 'InkSet',
							'E#014D' => 'InkNames',
							'E#014E' => 'NumberofInks',
							'E#0150' => 'DotRange',
							'E#0151' => 'TargetPrinter',
							'E#0152' => 'ExtraSamples',
							'E#0153' => 'SampleFormat',
							'E#0154' => 'SMinSampleValue',
							'E#0155' => 'SMaxSampleValue',
							'E#0156' => 'TransferRange',
							'E#0157' => 'ClipPath',
							'E#0158' => 'XClipPathUnits',
							'E#0159' => 'YClipPathUnits',
							'E#015A' => 'Indexed',
							'E#015B' => 'JPEGTables',
							'E#015F' => 'OPIProxy',
							'E#0190' => 'GlobalParametersIFD',
							'E#0191' => 'ProfileType',
							'E#0192' => 'FaxProfile',
							'E#0193' => 'CodingMethods',
							'E#0194' => 'VersionYear',
							'E#0195' => 'ModeNumber',
							'E#01B1' => 'Decode',
							'E#01B2' => 'DefaultImageColor',
							'E#01B3' => 'T82Options',
							'E#01B5' => 'JPEGTables',
							'E#0200' => 'JPEGProc',
							'E#0201' => 'ThumbnailOffset',
							'E#0202' => 'ThumbnailLength',
							'E#0203' => 'JPEGRestartInterval',
							'E#0205' => 'JPEGLosslessPredictors',
							'E#0206' => 'JPEGPointTransforms',
							'E#0207' => 'JPEGQTables',
							'E#0208' => 'JPEGDCTables',
							'E#0209' => 'JPEGACTables',
							'E#0211' => 'YCbCrCoefficients',
							'E#0212' => 'YCbCrSubSampling',
							'E#0213' => 'YCbCrPositioning',
							'E#0214' => 'ReferenceBlackWhite',
							'E#022F' => 'StripRowCounts',
							'E#02BC' => 'ApplicationNotes',
							'E#03E7' => 'USPTOMiscellaneous',
							'E#1000' => 'RelatedImageFileFormat',
							'E#1001' => 'RelatedImageWidth',
							'E#1002' => 'RelatedImageHeight',
							'E#4746' => 'Rating',
							'E#4747' => 'XP_DIP_XML',
							'E#4748' => 'StitchInfo',
							'E#4749' => 'RatingPercent',
							'E#7000' => 'SonyRawFileType',
							'E#7032' => 'VignettingCorrParams',
							'E#7035' => 'ChromaticAberrationCorrParams',
							'E#7037' => 'DistortionCorrParams',
							'E#800D' => 'ImageID',
							'E#80A3' => 'WangTag1',
							'E#80A4' => 'WangAnnotation',
							'E#80A5' => 'WangTag3',
							'E#80A6' => 'WangTag4',
							'E#80B9' => 'ImageReferencePoints',
							'E#80BA' => 'RegionXformTackPoint',
							'E#80BB' => 'WarpQuadrilateral',
							'E#80BC' => 'AffineTransformMat',
							'E#80E3' => 'Matteing',
							'E#80E4' => 'DataType',
							'E#80E5' => 'ImageDepth',
							'E#80E6' => 'TileDepth',
							'E#8214' => 'ImageFullWidth',
							'E#8215' => 'ImageFullHeight',
							'E#8216' => 'TextureFormat',
							'E#8217' => 'WrapModes',
							'E#8218' => 'FovCot',
							'E#8219' => 'MatrixWorldToScreen',
							'E#821A' => 'MatrixWorldToCamera',
							'E#827D' => 'Model2',
							'E#828D' => 'CFARepeatPatternDim',
							'E#828E' => 'CFAPattern2',
							'E#828F' => 'BatteryLevel',
							'E#8290' => 'KodakIFD',
							'E#8298' => 'Copyright',
							'E#829A' => 'ExposureTime',
							'E#829D' => 'FNumber',
							'E#82A5' => 'MDFileTag',
							'E#82A6' => 'MDScalePixel',
							'E#82A7' => 'MDColorTable',
							'E#82A8' => 'MDLabName',
							'E#82A9' => 'MDSampleInfo',
							'E#82AA' => 'MDPrepDate',
							'E#82AB' => 'MDPrepTime',
							'E#82AC' => 'MDFileUnits',
							'E#830E' => 'PixelScale',
							'E#8335' => 'AdventScale',
							'E#8336' => 'AdventRevision',
							'E#835C' => 'UIC1Tag',
							'E#835D' => 'UIC2Tag',
							'E#835E' => 'UIC3Tag',
							'E#835F' => 'UIC4Tag',
							'E#83BB' => 'IPTC-NAA',
							'E#847E' => 'IntergraphPacketData',
							'E#847F' => 'IntergraphFlagRegisters',
							'E#8480' => 'IntergraphMatrix',
							'E#8481' => 'INGRReserved',
							'E#8482' => 'ModelTiePoint',
							'E#84E0' => 'Site',
							'E#84E1' => 'ColorSequence',
							'E#84E2' => 'IT8Header',
							'E#84E3' => 'RasterPadding',
							'E#84E4' => 'BitsPerRunLength',
							'E#84E5' => 'BitsPerExtendedRunLength',
							'E#84E6' => 'ColorTable',
							'E#84E7' => 'ImageColorIndicator',
							'E#84E8' => 'BackgroundColorIndicator',
							'E#84E9' => 'ImageColorValue',
							'E#84EA' => 'BackgroundColorValue',
							'E#84EB' => 'PixelIntensityRange',
							'E#84EC' => 'TransparencyIndicator',
							'E#84ED' => 'ColorCharacterization',
							'E#84EE' => 'HCUsage',
							'E#84EF' => 'TrapIndicator',
							'E#84F0' => 'CMYKEquivalent',
							'E#8546' => 'SEMInfo',
							'E#8568' => 'AFCP_IPTC',
							'E#85B8' => 'PixelMagicJBIGOptions',
							'E#85D7' => 'JPLCartoIFD',
							'E#85D8' => 'ModelTransform',
							'E#8602' => 'WB_GRGBLevels',
							'E#8606' => 'LeafData',
							'E#8649' => 'PhotoshopSettings',
							'E#8769' => 'ExifOffset',
							'E#8773' => 'ICC_Profile',
							'E#877F' => 'TIFF_FXExtensions',
							'E#8780' => 'MultiProfiles',
							'E#8781' => 'SharedData',
							'E#8782' => 'T88Options',
							'E#87AC' => 'ImageLayer',
							'E#87AF' => 'GeoTiffDirectory',
							'E#87B0' => 'GeoTiffDoubleParams',
							'E#87B1' => 'GeoTiffAsciiParams',
							'E#87BE' => 'JBIGOptions',
							'E#8822' => 'ExposureProgram',
							'E#8824' => 'SpectralSensitivity',
							'E#8825' => 'GPSInfo',
							'E#8827' => 'ISO',
							'E#8828' => 'Opto-ElectricConvFactor',
							'E#8829' => 'Interlace',
							'E#882A' => 'TimeZoneOffset',
							'E#882B' => 'SelfTimerMode',
							'E#8830' => 'SensitivityType',
							'E#8831' => 'StandardOutputSensitivity',
							'E#8832' => 'RecommendedExposureIndex',
							'E#8833' => 'ISOSpeed',
							'E#8834' => 'ISOSpeedLatitudeyyy',
							'E#8835' => 'ISOSpeedLatitudezzz',
							'E#885C' => 'FaxRecvParams',
							'E#885D' => 'FaxSubAddress',
							'E#885E' => 'FaxRecvTime',
							'E#8871' => 'FedexEDR',
							'E#888A' => 'LeafSubIFD',
							'E#9000' => 'ExifVersion',
							'E#9003' => 'DateTimeOriginal',
							'E#9004' => 'CreateDate',
							'E#9009' => 'GooglePlusUploadCode',
							'E#9010' => 'OffsetTime',
							'E#9011' => 'OffsetTimeOriginal',
							'E#9012' => 'OffsetTimeDigitized',
							'E#9101' => 'ComponentsConfiguration',
							'E#9102' => 'CompressedBitsPerPixel',
							'E#9201' => 'ShutterSpeedValue',
							'E#9202' => 'ApertureValue',
							'E#9203' => 'BrightnessValue',
							'E#9204' => 'ExposureCompensation',
							'E#9205' => 'MaxApertureValue',
							'E#9206' => 'SubjectDistance',
							'E#9207' => 'MeteringMode',
							'E#9208' => 'LightSource',
							'E#9209' => 'Flash',
							'E#920A' => 'FocalLength',
							'E#920B' => 'FlashEnergy',
							'E#920C' => 'SpatialFrequencyResponse',
							'E#920D' => 'Noise',
							'E#920E' => 'FocalPlaneXResolution',
							'E#920F' => 'FocalPlaneYResolution',
							'E#9210' => 'FocalPlaneResolutionUnit',
							'E#9211' => 'ImageNumber',
							'E#9212' => 'SecurityClassification',
							'E#9213' => 'ImageHistory',
							'E#9214' => 'SubjectArea',
							'E#9215' => 'ExposureIndex',
							'E#9216' => 'TIFF-EPStandardID',
							'E#9217' => 'SensingMethod',
							'E#923A' => 'CIP3DataFile',
							'E#923B' => 'CIP3Sheet',
							'E#923C' => 'CIP3Side',
							'E#923F' => 'StoNits',
							'E#927C' => 'MakerNote',
							'E#9286' => 'UserComment',
							'E#9290' => 'SubSecTime',
							'E#9291' => 'SubSecTimeOriginal',
							'E#9292' => 'SubSecTimeDigitized',
							'E#932F' => 'MSDocumentText',
							'E#9330' => 'MSPropertySetStorage',
							'E#9331' => 'MSDocumentTextPosition',
							'E#935C' => 'ImageSourceData',
							'E#9400' => 'AmbientTemperature',
							'E#9401' => 'Humidity',
							'E#9402' => 'Pressure',
							'E#9403' => 'WaterDepth',
							'E#9404' => 'Acceleration',
							'E#9405' => 'CameraElevationAngle',
							'E#9C9B' => 'XPTitle',
							'E#9C9C' => 'XPComment',
							'E#9C9D' => 'XPAuthor',
							'E#9C9E' => 'XPKeywords',
							'E#9C9F' => 'XPSubject',
							'E#A000' => 'FlashpixVersion',
							'E#A001' => 'ColorSpace',
							'E#A002' => 'ExifImageWidth',
							'E#A003' => 'ExifImageHeight',
							'E#A004' => 'RelatedSoundFile',
							'E#A005' => 'InteropOffset',
							'E#A010' => 'SamsungRawPointersOffset',
							'E#A011' => 'SamsungRawPointersLength',
							'E#A101' => 'SamsungRawByteOrder',
							'E#A102' => 'SamsungRawUnknown?',
							'E#A20B' => 'FlashEnergy',
							'E#A20C' => 'SpatialFrequencyResponse',
							'E#A20D' => 'Noise',
							'E#A20E' => 'FocalPlaneXResolution',
							'E#A20F' => 'FocalPlaneYResolution',
							'E#A210' => 'FocalPlaneResolutionUnit',
							'E#A211' => 'ImageNumber',
							'E#A212' => 'SecurityClassification',
							'E#A213' => 'ImageHistory',
							'E#A214' => 'SubjectLocation',
							'E#A215' => 'ExposureIndex',
							'E#A216' => 'TIFF-EPStandardID',
							'E#A217' => 'SensingMethod',
							'E#A300' => 'FileSource',
							'E#A301' => 'SceneType',
							'E#A302' => 'CFAPattern',
							'E#A401' => 'CustomRendered',
							'E#A402' => 'ExposureMode',
							'E#A403' => 'WhiteBalance',
							'E#A404' => 'DigitalZoomRatio',
							'E#A405' => 'FocalLengthIn35mmFormat',
							'E#A406' => 'SceneCaptureType',
							'E#A407' => 'GainControl',
							'E#A408' => 'Contrast',
							'E#A409' => 'Saturation',
							'E#A40A' => 'Sharpness',
							'E#A40B' => 'DeviceSettingDescription',
							'E#A40C' => 'SubjectDistanceRange',
							'E#A420' => 'ImageUniqueID',
							'E#A430' => 'OwnerName',
							'E#A431' => 'SerialNumber',
							'E#A432' => 'LensInfo',
							'E#A433' => 'LensMake',
							'E#A434' => 'LensModel',
							'E#A435' => 'LensSerialNumber',
							'E#A480' => 'GDALMetadata',
							'E#A481' => 'GDALNoData',
							'E#A500' => 'Gamma',
							'E#AFC0' => 'ExpandSoftware',
							'E#AFC1' => 'ExpandLens',
							'E#AFC2' => 'ExpandFilm',
							'E#AFC3' => 'ExpandFilterLens',
							'E#AFC4' => 'ExpandScanner',
							'E#AFC5' => 'ExpandFlashLamp',
							'E#BC01' => 'PixelFormat',
							'E#BC03' => 'Uncompressed',
							'E#BC04' => 'ImageType',
							'E#BC80' => 'ImageWidth',
							'E#BC81' => 'ImageHeight',
							'E#BC82' => 'WidthResolution',
							'E#BC83' => 'HeightResolution',
							'E#BCC0' => 'ImageOffset',
							'E#BCC1' => 'ImageByteCount',
							'E#BCC2' => 'AlphaOffset',
							'E#BCC3' => 'AlphaByteCount',
							'E#BCC4' => 'ImageDataDiscard',
							'E#BCC5' => 'AlphaDataDiscard',
							'E#C427' => 'OceScanjobDesc',
							'E#C428' => 'OceApplicationSelector',
							'E#C429' => 'OceIDNumber',
							'E#C42A' => 'OceImageLogic',
							'E#C44F' => 'Annotations',
							'E#C4A5' => 'PrintIM',
							'E#C573' => 'OriginalFileName',
							'E#C580' => 'USPTOOriginalContentType',
							'E#C5E0' => 'CR2CFAPattern',
							'E#C612' => 'DNGVersion',
							'E#C613' => 'DNGBackwardVersion',
							'E#C614' => 'UniqueCameraModel',
							'E#C615' => 'LocalizedCameraModel',
							'E#C616' => 'CFAPlaneColor',
							'E#C617' => 'CFALayout',
							'E#C618' => 'LinearizationTable',
							'E#C619' => 'BlackLevelRepeatDim',
							'E#C61A' => 'BlackLevel',
							'E#C61B' => 'BlackLevelDeltaH',
							'E#C61C' => 'BlackLevelDeltaV',
							'E#C61D' => 'WhiteLevel',
							'E#C61E' => 'DefaultScale',
							'E#C61F' => 'DefaultCropOrigin',
							'E#C620' => 'DefaultCropSize',
							'E#C621' => 'ColorMatrix1',
							'E#C622' => 'ColorMatrix2',
							'E#C623' => 'CameraCalibration1',
							'E#C624' => 'CameraCalibration2',
							'E#C625' => 'ReductionMatrix1',
							'E#C626' => 'ReductionMatrix2',
							'E#C627' => 'AnalogBalance',
							'E#C628' => 'AsShotNeutral',
							'E#C629' => 'AsShotWhiteXY',
							'E#C62A' => 'BaselineExposure',
							'E#C62B' => 'BaselineNoise',
							'E#C62C' => 'BaselineSharpness',
							'E#C62D' => 'BayerGreenSplit',
							'E#C62E' => 'LinearResponseLimit',
							'E#C62F' => 'CameraSerialNumber',
							'E#C630' => 'DNGLensInfo',
							'E#C631' => 'ChromaBlurRadius',
							'E#C632' => 'AntiAliasStrength',
							'E#C633' => 'ShadowScale',
							'E#C640' => 'RawImageSegmentation',
							'E#C65A' => 'CalibrationIlluminant1',
							'E#C65B' => 'CalibrationIlluminant2',
							'E#C65C' => 'BestQualityScale',
							'E#C65D' => 'RawDataUniqueID',
							'E#C660' => 'AliasLayerMetadata',
							'E#C68B' => 'OriginalRawFileName',
							'E#C68C' => 'OriginalRawFileData',
							'E#C68D' => 'ActiveArea',
							'E#C68E' => 'MaskedAreas',
							'E#C68F' => 'AsShotICCProfile',
							'E#C690' => 'AsShotPreProfileMatrix',
							'E#C691' => 'CurrentICCProfile',
							'E#C692' => 'CurrentPreProfileMatrix',
							'E#C6Bf' => 'ColorimetricReference',
							'E#C6C5' => 'SRawType',
							'E#C6D2' => 'PanasonicTitle',
							'E#C6D3' => 'PanasonicTitle2',
							'E#C6F3' => 'CameraCalibrationSig',
							'E#C6F4' => 'ProfileCalibrationSig',
							'E#C6F5' => 'ProfileIFD',
							'E#C6F6' => 'AsShotProfileName',
							'E#C6F7' => 'NoiseReductionApplied',
							'E#C6F8' => 'ProfileName',
							'E#C6F9' => 'ProfileHueSatMapDims',
							'E#C6FA' => 'ProfileHueSatMapData1',
							'E#C6FB' => 'ProfileHueSatMapData2',
							'E#C6FC' => 'ProfileToneCurve',
							'E#C6FD' => 'ProfileEmbedPolicy',
							'E#C6FE' => 'ProfileCopyright',
							'E#C714' => 'ForwardMatrix1',
							'E#C715' => 'ForwardMatrix2',
							'E#C716' => 'PreviewApplicationName',
							'E#C717' => 'PreviewApplicationVersion',
							'E#C718' => 'PreviewSettingsName',
							'E#C719' => 'PreviewSettingsDigest',
							'E#C71A' => 'PreviewColorSpace',
							'E#C71B' => 'PreviewDateTime',
							'E#C71C' => 'RawImageDigest',
							'E#C71D' => 'OriginalRawFileDigest',
							'E#C71E' => 'SubTileBlockSize',
							'E#C71F' => 'RowInterleaveFactor',
							'E#C725' => 'ProfileLookTableDims',
							'E#C726' => 'ProfileLookTableData',
							'E#C740' => 'OpcodeList1',
							'E#C741' => 'OpcodeList2',
							'E#C74E' => 'OpcodeList3',
							'E#C761' => 'NoiseProfile',
							'E#C763' => 'TimeCodes',
							'E#C764' => 'FrameRate',
							'E#C772' => 'TStop',
							'E#C789' => 'ReelName',
							'E#C791' => 'OriginalDefaultFinalSize',
							'E#C792' => 'OriginalBestQualitySize',
							'E#C793' => 'OriginalDefaultCropSize',
							'E#C7A1' => 'CameraLabel',
							'E#C7A3' => 'ProfileHueSatMapEncoding',
							'E#C7A4' => 'ProfileLookTableEncoding',
							'E#C7A5' => 'BaselineExposureOffset',
							'E#C7A6' => 'DefaultBlackRender',
							'E#C7A7' => 'NewRawImageDigest',
							'E#C7A8' => 'RawToPreviewGain',
							'E#C7B5' => 'DefaultUserCrop',
							'E#EA1C' => 'Padding',
							'E#EA1D' => 'OffsetSchema',
							'E#FDE8' => 'OwnerName',
							'E#FDE9' => 'SerialNumber',
							'E#FDEA' => 'Lens',
							'E#FE00' => 'KDC_IFD',
							'E#FE4C' => 'RawFile',
							'E#FE4D' => 'Converter',
							'E#FE4E' => 'WhiteBalance',
							'E#FE51' => 'Exposure',
							'E#FE52' => 'Shadows',
							'E#FE53' => 'Brightness',
							'E#FE54' => 'Contrast',
							'E#FE55' => 'Saturation',
							'E#FE56' => 'Sharpness',
							'E#FE57' => 'Smoothness',
							'E#FE58' => 'MoireFilter',

		);
	}

	if ( empty( $gpstags ) ) {
		$gpstags = array( 	'G#0000' => 'GPSVersionID',
							'G#0001' => 'GPSLatitudeRef',
							'G#0002' => 'GPSLatitude',
							'G#0003' => 'GPSLongitudeRef',
							'G#0004' => 'GPSLongitude',
							'G#0005' => 'GPSAltitudeRef',
							'G#0006' => 'GPSAltitude',
							'G#0007' => 'GPSTimeStamp',
							'G#0008' => 'GPSSatellites',
							'G#0009' => 'GPSStatus',
							'G#000A' => 'GPSMeasureMode',
							'G#000B' => 'GPSDOP',
							'G#000C' => 'GPSSpeedRef',
							'G#000D' => 'GPSSpeed',
							'G#000E' => 'GPSTrackRef',
							'G#000F' => 'GPSTrack',
							'G#0010' => 'GPSImgDirectionRef',
							'G#0011' => 'GPSImgDirection',
							'G#0012' => 'GPSMapDatum',
							'G#0013' => 'GPSDestLatitudeRef',
							'G#0014' => 'GPSDestLatitude',
							'G#0015' => 'GPSDestLongitudeRef',
							'G#0016' => 'GPSDestLongitude',
							'G#0017' => 'GPSDestBearingRef',
							'G#0018' => 'GPSDestBearing',
							'G#0019' => 'GPSDestDistanceRef',
							'G#001A' => 'GPSDestDistance',
							'G#001B' => 'GPSProcessingMethod',
							'G#001C' => 'GPSAreaInformation',
							'G#001D' => 'GPSDateStamp',
							'G#001e' => 'GPSDifferential',
							'G#001F' => 'GPSHPositioningError',

		);
	}

	if ( empty( $filetags ) ) {
		$filetags = array( 	'F#0001' => 'FileName',
							'F#0002' => 'FileDateTime',
							'F#0003' => 'FileSize',
							'F#0004' => 'FileType',
							'F#0005' => 'MimeType',
							'F#0006' => 'SectionsFound',
		);
	}

	// If brand given, try to find brand dependant tagname
	$result = '';
	switch( $brand ) {

		case 'CANON':
			if ( isset( $canontags[$tag] ) ) {
				$result = $canontags[$tag];
			}
			break;

		case 'NIKON':
			if ( isset( $nikontags[$tag] ) ) {
				$result = $nikontags[$tag];
			}
			break;

		case 'SAMSUNG':
			if ( isset( $samsungtags[$tag] ) ) {
				$result = $samsungtags[$tag];
			}
			break;

		default:
			break;
	}

	// If brand only requested, return result, even when blank
	if ( $brandonly ) {
		return $result;
	}

	// Not found? Try editable tags
	if ( ! $result ) {
		if ( isset( $editabletags[$tag] ) ) {
			$result = $editabletags[$tag];
		}
	}

	// Not found? Try common tags
	if ( ! $result ) {
		if ( isset( $commontags[$tag] ) ) {
			$result = $commontags[$tag];
		}
	}

	// Not found? Try gpstags
	if ( ! $result ) {
		if ( isset( $gpstags[$tag] ) ) {
			$result = $gpstags[$tag];
		}
	}

	// Not found? Try filetags
	if ( ! $result ) {
		if ( isset( $filetags[$tag] ) ) {
			$result = $filetags[$tag];
		}
	}

	// Not found? Find generic tag name
	if ( ! $result ) {
		$hextag = hexdec( substr( $tag, 2, 4 ) );
		if ( function_exists( 'exif_tagname' ) ) {
			$result = @ exif_tagname( $hextag );
		}
		else {
			$result = '';
		}
		if ( ! $result ) {
			$result = sprintf( 'UndefinedTag:0x%04X', $hextag );
		}
	}

	return $result;
}

function wppa_iptc_tagname( $tag ) {
global $wpdb;
static $labels;

	// Get all labels
	if ( ! $labels ) {
		$labels = $wpdb->get_results( "SELECT tag, description
									   FROM $wpdb->wppa_iptc
									   WHERE photo = '0'", ARRAY_A );
	}

	// Find it
	foreach ( $labels as $label ) {
		if ( $label['tag'] == $tag ) {
			return rtrim( $label['description'], ':' );
		}
	}

	return '';
}

// Get gps data from photofile
function wppa_get_coordinates( $picture_path, $photo_id ) {

	// Not on pdf
	if ( wppa_is_pdf( $photo_id ) ) return false;

	// Make sure we look at the original, not the -o1 file
	$picture_path = str_replace( '-o1.jpg', '.jpg', $picture_path );

	// get exif data
	$exif = wppa_exif_read_data( $picture_path, 0 );
	if ( empty( $exif ) ) {
		return false;
	}

	// any coordinates available?
	if ( !isset ( $exif['GPSLatitude'][0] ) ) return false;	// No GPS data
	if ( !isset ( $exif['GPSLongitude'][0] ) ) return false;	// No GPS data

	// north, east, south, west?
	if ( $exif['GPSLatitudeRef'] == "S" ) {
		$gps['latitude_string'] = -1;
		$gps['latitude_dicrection'] = "S";
	}
	else {
		$gps['latitude_string'] = 1;
		$gps['latitude_dicrection'] = "N";
	}
	if ( $exif['GPSLongitudeRef'] == "W" ) {
		$gps['longitude_string'] = -1;
		$gps['longitude_dicrection'] = "W";
	}
	else {
		$gps['longitude_string'] = 1;
		$gps['longitude_dicrection'] = "E";
	}
	// location
	$gps['latitude_hour'] = $exif["GPSLatitude"][0];
	$gps['latitude_minute'] = $exif["GPSLatitude"][1];
	$gps['latitude_second'] = $exif["GPSLatitude"][2];
	$gps['longitude_hour'] = $exif["GPSLongitude"][0];
	$gps['longitude_minute'] = $exif["GPSLongitude"][1];
	$gps['longitude_second'] = $exif["GPSLongitude"][2];

	// calculating
	foreach( $gps as $key => $value ) {
		$pos = strpos( $value, '/' );
		if ( $pos !== false ) {
			$temp = explode( '/',$value );
			if ( $temp[1] ) $gps[$key] = $temp[0] / $temp[1];
			else $gps[$key] = 0;
		}
	}

	$geo['latitude_format'] = $gps['latitude_dicrection']." ".$gps['latitude_hour']."&deg;".$gps['latitude_minute']."&#x27;".round ( $gps['latitude_second'], 4 ).'&#x22;';
	$geo['longitude_format'] = $gps['longitude_dicrection']." ".$gps['longitude_hour']."&deg;".$gps['longitude_minute']."&#x27;".round ( $gps['longitude_second'], 4 ).'&#x22;';

	$geo['latitude'] = $gps['latitude_string'] * ( $gps['latitude_hour'] + ( $gps['latitude_minute'] / 60 ) + ( $gps['latitude_second'] / 3600 ) );
	$geo['longitude'] = $gps['longitude_string'] * ( $gps['longitude_hour'] + ( $gps['longitude_minute'] / 60 ) + ( $gps['longitude_second'] / 3600 ) );
	
	// Process result
	$result = implode( '/', $geo );
	wppa_update_photo( $photo_id, ['location' => $result] );
	return $geo;
}

function wppa_get_camera_brand( $id ) {
global $wpdb;

	// Try stored exif data
	$E010F = $wpdb->get_var( $wpdb->prepare( "SELECT description FROM $wpdb->wppa_exif WHERE photo = %s AND tag = 'E#010F' ", $id ) );
	if ( $E010F ) {
		$E010F = strtolower( $E010F );
		if ( strpos( $E010F, 'canon' ) !== false ) {
			return 'CANON';
		}
		if ( strpos( $E010F, 'nikon' ) !== false ) {
			return 'NIKON';
		}
		if ( strpos( $E010F, 'samsung' ) !== false ) {
			return 'SAMSUNG';
		}
	}

	// Try source path
	$src = wppa_get_source_path( $id );
	if ( file_exists( $src ) && strtolower( wppa_get_ext( $src ) ) == 'jpg' ) {
		try{
			$exifs = wppa_exif_read_data( $src, 'EXIF' );
		}
		catch( Exception $e ) {
			$exifs = false;
		}
		if ( $exifs ) {
			if ( isset( $exifs['Make'] ) ) {
				$E010F = strtolower( $exifs['Make'] );
				if ( strpos( $E010F, 'canon' ) !== false ) {
					return 'CANON';
				}
				if ( strpos( $E010F, 'nikon' ) !== false ) {
					return 'NIKON';
				}
				if ( strpos( $E010F, 'samsung' ) !== false ) {
					return 'SAMSUNG';
				}
			}
		}
	}

	// Not found
	return '';

}
