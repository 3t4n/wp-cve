<?php

/**
 * Class Xpro_Beaver_Dashboard
 *
 * Main Xpro_Beaver_Dashboard class
 * @since 1.2.0
 */
class Xpro_Beaver_Dashboard {


	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Xpro_Beaver_Dashboard The single instance of the class.
	 */
	private static $instance = null;
	public $utils;

	/**
	 *  Xpro_Beaver_Dashboard class constructor
	 *
	 * Register Xpro_Beaver_Dashboard action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		require_once XPRO_ADDONS_FOR_BB_DIR . 'dashboard/classes/utils.php';
		require_once XPRO_ADDONS_FOR_BB_DIR . 'dashboard/classes/ajax.php';

	}

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Xpro_Beaver_Dashboard An instance of the class.
	 * @since 1.2.0
	 * @access public
	 *
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

// Instantiate Xpro_Beaver_Dashboard Class
Xpro_Beaver_Dashboard::instance();
