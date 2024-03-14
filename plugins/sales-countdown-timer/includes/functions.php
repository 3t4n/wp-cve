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
if(!function_exists('woo_ctr_time')){
	function woo_ctr_time($time){
		if(!$time){
			return 0;
		}
		$temp=explode(":",$time);
		if(count($temp)==2){
			return (absint($temp[0])*3600+absint($temp[1])*60);
		}else{
			return 0;
		}
	}
}
if(!function_exists('woo_ctr_time_revert')){
	function woo_ctr_time_revert($time){
		$hour=floor($time/3600);
		$min=floor(($time-3600*$hour)/60);
		return implode(':',array(zeroise($hour,2),zeroise($min,2)));
	}
}
