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
if ( ! function_exists( 'woocommerce_version_check' ) ) {
	function woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;

		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}

		return false;
	}
}
if ( ! function_exists( 'wtyp_in_array_r' ) ) {
	function wtyp_in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && wtyp_in_array_r($needle, $item, $strict))) {
				return true;
			}
		}

		return false;
	}
}
if ( ! function_exists( 'wtyp_sanitize_block' ) ) {
	function wtyp_array_depth($array) {
		$max_indentation = 1;

		$array_str = print_r($array, true);
		$lines = explode("\n", $array_str);

		foreach ($lines as $line) {
			$indentation = (strlen($line) - strlen(ltrim($line))) / 4;

			if ($indentation > $max_indentation) {
				$max_indentation = $indentation;
			}
		}

		return ceil(($max_indentation - 1) / 2) + 1;
	}
}
if ( ! function_exists( 'wtyp_sanitize_block' ) ) {
	function wtyp_sanitize_block( $var ) {
		return stripslashes( $var );
	}
}
if ( ! function_exists( 'wtyp_json_decode' ) ) {
	function wtyp_json_decode( $var ) {
		return json_decode( stripslashes($var) );
	}
}
if ( ! function_exists( 'wtypc_base64_encode' ) ) {
	function wtypc_base64_encode($value){
		if ( is_array( $value ) ) {
			return array_map( 'wtypc_base64_encode', $value );
		} else {
			return base64_encode($value);
		}
	}
}