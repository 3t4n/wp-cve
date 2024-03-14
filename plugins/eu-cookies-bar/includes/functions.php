<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Function include all files in folder
 *
 * @param $path   Directory address
 * @param $ext    array file extension what will include
 * @param $prefix string Class prefix
 */
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
if ( ! function_exists( 'eu_cookies_bar_hex2rgba' ) ) {

	function eu_cookies_bar_hex2rgba( $color, $opacity = false ) {
		$default = 'rgb(0,0,0)';
		if ( empty( $color ) ) {
			return $default;
		}
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}
		$rgb = array_map( 'hexdec', $hex );
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ",", $rgb ) . ')';
		}

		return $output;
	}
}