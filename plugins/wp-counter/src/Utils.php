<?php
/**
 * Helper Class
 *
 * @package Haruncpi\WpCounter
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

namespace Haruncpi\WpCounter;

/**
 * Utils Class
 *
 * @since 1.2
 */
class Utils {

	/**
	 * Load view
	 *
	 * @since 1.2
	 *
	 * @param string $path view path.
	 * @param array  $data view data.
	 *
	 * @return void
	 */
	public static function load_view( $path = null, $data = array() ) {
		$final_path = WPCOUNTER_DIR . 'views';
		if ( $path ) {
			$final_path .= '/' . $path;
		}

		if ( file_exists( $final_path ) ) {
			if ( count( $data ) > 0 ) {
				extract( $data );
			}
			
			require_once $final_path;
		}

	}
}
