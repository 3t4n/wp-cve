<?php
/**
 * Function include all files in folder
 *
 * @param $path   Directory address
 * @param $ext    array file extension what will include
 * @param $prefix string Class prefix
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'vi_include_folder' ) ) {
	function vi_include_folder( $path, $prefix = '', $ext = array( 'php' ) ) {

		/*Include all files in payment folder*/
		if ( ! is_array( $ext ) ) {
			$ext = explode( ',', $ext );
			$ext = array_map( 'trim', $ext );
		}
		$sfiles = scandir( $path );
		foreach ( $sfiles as $sfile ) {
			if ( $sfile != '.' && $sfile != '..' ) {
				if ( is_file( $path . "/" . $sfile ) ) {
					$ext_file  = pathinfo( $path . "/" . $sfile );
					$file_name = $ext_file['filename'];
					if ( $ext_file['extension'] ) {
						if ( in_array( $ext_file['extension'], $ext ) ) {
							$class = preg_replace( '/\W/i', '_', $prefix . ucfirst( $file_name ) );

							if ( ! class_exists( $class ) ) {
								require_once $path . $sfile;
								if ( class_exists( $class ) ) {
									new $class;
								}
							}
						}
					}
				}
			}
		}
	}
}
if ( ! function_exists( 'wlwl_is_url_exist' ) ) {
	function wlwl_is_url_exist( $url = '' ) {
		try {
			$r = wp_remote_get( $url );

			return true;

		} catch ( \Exception $e ) {
			return false;
		}
	}
}


if ( ! function_exists( 'wlwl_get_currency_symbol' ) ) {
	function wlwl_get_currency_symbol( $name = '' ) {
		if ( ! $name ) {
			$name = get_woocommerce_currencies();
		}
		$symbols = array(
			'AED' => '&#1583;.&#1573;',
			'AFN' => '&#1547;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&#402;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547; ',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#1583;.&#1576;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&#165;',
			'COP' => '&#36;',
			'CRC' => '&#8353;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#1583;.&#1580;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&#8364;',
			'FJD' => '&#36;',
			'FKP' => '&#163;',
			'GBP' => '&#163;',
			'GEL' => '&#4314;',
			'GGP' => '&#163;',
			'GHS' => '&#8373;',
			'GIP' => '&#163;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'Kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&#163;',
			'INR' => '&#8377;',
			'IQD' => '&#1593;.&#1883;',
			'IRR' => '&#65020;',
			'IRT' => '&#1578;&#1608;&#1605;&#1575;&#1606;',
			'ISK' => 'kr.',
			'JEP' => '&#163;',
			'JMD' => '&#36;',
			'JOD' => '&#1883;.&#1575;',
			'JPY' => '&#165;',
			'KES' => 'KSh',
			'KGS' => '&#1089;&#1086;&#1084;',
			'KHR' => '&#6107;',
			'KMF' => 'Fr',
			'KPW' => '&#8361;',
			'KRW' => '&#8361;',
			'KWD' => '&#1883;.&#1603;',
			'KYD' => '&#36;',
			'KZT' => 'KZT',
			'LAK' => '&#8365;',
			'LBP' => '&#1604;.&#1604;',
			'LKR' => '&#3515;&#3540;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#1604;.&#1883;',
			'MAD' => '&#1883;.&#1605;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#1076;&#1077;&#1085;',
			'MMK' => 'Ks',
			'MNT' => '&#8366;',
			'MOP' => 'P',
			'MRO' => 'UM',
			'MUR' => '&#8360;',
			'MVR' => '.&#1923;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#1585;.&#1593;.',
			'PAB' => 'B/.',
			'PEN' => 'S/.',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#1088;.',
			'PYG' => '&#8370;',
			'QAR' => '&#1585;.&#1602;',
			'RMB' => '&#165;',
			'RON' => 'lei',
			'RSD' => '&#1076;&#1080;&#1085;.',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#1585;.&#1587;',
			'SBD' => '&#36;',
			'SCR' => '&#8360;',
			'SDG' => '&#1580;.&#1587;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&#163;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&#163;',
			'STD' => 'Db',
			'SYP' => '&#1604;.&#1587;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#1029;&#1052;',
			'TMT' => 'm',
			'TND' => '&#1883;.&#1578;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#65020;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		);

		return isset( $symbols[ $name ] ) ? $symbols[ $name ] : '';
	}
}
if ( ! function_exists( 'wlwl_get_explode' ) ) {
	function wlwl_get_explode( $string, $sap = ',', $limit = 3 ) {
		$rand       = 0;
		$show_wheel = explode( $sap, $string, $limit );
		$show_wheel = array_map( 'absInt', $show_wheel );
		if ( sizeof( $show_wheel ) > 1 ) {
			$rand = $show_wheel[0] < $show_wheel[1] ? rand( $show_wheel[0], $show_wheel[1] ) : rand( $show_wheel[1], $show_wheel[0] );
		} else {
			$rand = $show_wheel[0];
		}

		return $rand;
	}
}

if ( ! function_exists( 'wlwl_sanitize_text_field' ) ) {
	function wlwl_sanitize_text_field( $string ) {
		return sanitize_text_field( stripslashes( $string ) );
	}
}
if ( ! function_exists( 'woocommerce_version_check' ) ) {
	function woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;

		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}

		return false;
	}
}