<?php
/**
 * GoogleAnalytics View Core.
 *
 * @package GoogleAnalytics
 */

/**
 * View Core class.
 */
class Ga_View_Core {

	/**
	 * Name of the view folder.
	 */
	const PATH = 'view';

	/**
	 * Loads given view file and it's data.
	 * Displays view or returns HTML code.
	 *
	 * @param string $view Filename string.
	 * @param array  $data_array Data array.
	 * @param bool   $html   Whether to display or return HTML code.
	 *
	 * @return string
	 */
	public static function load( $view, $data_array = array(), $html = false ) {
		if ( ! empty( $view ) ) {
			foreach ( $data_array as $k => $v ) {
				$$k = $v;
			}

			ob_start();
			include GA_PLUGIN_DIR . '/' . self::PATH . '/' . $view . '.php';
			if ( $html ) {
				return ob_get_clean();
			} else {
				// Note: this gets escaped elsewhere.
				echo ob_get_clean(); // phpcs:ignore
			}
		}
	}
}
