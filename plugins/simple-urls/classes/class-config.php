<?php
/**
 * Declare class Config
 *
 * @package Config
 */

namespace LassoLite\Classes;

/**
 * Config
 */
class Config {
	/**
	 * Print header html
	 */
	public static function get_header() {
		require_once SIMPLE_URLS_DIR . '/admin/views/header.php';
	}

	/**
	 * Print footer html
	 */
	public static function get_footer() {
		require_once SIMPLE_URLS_DIR . '/admin/views/footer.php';
	}
}
