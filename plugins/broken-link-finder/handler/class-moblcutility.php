<?php
/**
 * This is utility file.
 *
 * @package broken-link-finder/utility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'MOBLCUtility' ) ) {
	/**
	 * This is utility class.
	 */
	class MOBLCUtility {
		/**
		 * Function for returning scan object.
		 *
		 * @return object
		 */
		public static function moblc_return_object() {
				$scan_object = new Moblc_Plugin();

			return $scan_object;
		}
		/**
		 * Function to store debug file.
		 *
		 * @param mixed $text text.
		 * @return void
		 */
		public static function moblc_debug_file( $text ) {
			if ( get_site_option( 'moblc_debug_log' ) === '1' ) {
				$debug_log_path = wp_upload_dir();
				$debug_log_path = $debug_log_path['basedir'] . DIRECTORY_SEPARATOR;
				$filename       = 'blc_debug_log.txt';
				$data           = '[UTC: ' . gmdate( 'Y/m/d h:i:s A', time() ) . ']:' . $text . "\n";
				$handle         = fopen( $debug_log_path . DIRECTORY_SEPARATOR . $filename, 'a+' );//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen -- fopen can be used here.
				fwrite( $handle, $data );//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite -- fwrite can be used here.
				fclose( $handle );//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose -- fclose can be used here.
			}
		}
		/**
		 * Function to get links.
		 *
		 * @param mixed $moblc_attr attribute.
		 * @param mixed $links links.
		 * @param array $link_array links array.
		 * @return void
		 */
		public static function moblc_get_links( $moblc_attr, $links, &$link_array ) {
			$lsize = count( $links );

			for ( $index = 0; $index < $lsize; $index ++ ) {
				$link = $links[ $index ];
				if ( strpos( $link, $moblc_attr ) !== false ) {
					$link = preg_replace( '/.*\s*' . $moblc_attr . "=[\"|']/sm", '', $link );
					$link = preg_replace( "/[\"|'].*/s", '', $link );
					$link = trim( $link );
					if ( strpos( $link, '/embed/' ) !== false ) {
						list( , $video_id ) = explode( '/embed/', $link );
						$link               = 'https://youtube.com/watch?v=' . $video_id;
						list( $video_id, )  = explode( '?', $video_id );
						$link               = 'https://youtube.com/watch?v=' . $video_id;
					}
					if ( ! empty( $link ) && '#' !== $link ) {
						array_push( $link_array, $link );
					}
				}
			}
		}
	}
}

