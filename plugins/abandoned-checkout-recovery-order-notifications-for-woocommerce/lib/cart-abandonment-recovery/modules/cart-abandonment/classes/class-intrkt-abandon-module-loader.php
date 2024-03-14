<?php
/**
 * Cart Abandonment DB
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cart Abandonment DB class.
 */
class INTRKT_ABANDON_Module_Loader {



	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *  Constructor
	 */
	public function __construct() {

		$this->intrkt_abandon_load_module_files();
	}




	/**
	 *  Load required files for module.
	 */
	private function intrkt_abandon_load_module_files() {

		$module_files = array(
			'class-intrkt-abandon-tracking.php',
			'class-intrkt-abandon-cron.php',
			'class-intrkt-abandon-helper.php',
		);

		foreach ( $module_files as $index => $file ) {

			$filename = INTRKT_ABANDON_DIR . '/modules/cart-abandonment/classes/' . $file;

			if ( file_exists( $filename ) ) {
				include_once $filename;
			}
		}

	}

}

INTRKT_ABANDON_Module_Loader::get_instance();
