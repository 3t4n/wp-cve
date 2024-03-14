<?php

/**
 * Hester Core Admin class. Hester related pages in WP Dashboard.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Core Admin Class.
 *
 * @since 1.0.0
 * @package Hester Core
 */
final class Hester_Core_Customizer {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Main Hester Core Admin Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Core_Customizer
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Core_Customizer ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		// Init Hester Core admin.
		add_action( 'init', array( $this, 'includes' ) );

		// add_filter('hester_custom_customizer_controls', array($this, 'hester_core_customzier_control'), 10, 1);

		// Hester Core Admin loaded.
		do_action( 'Hester_Core_Customizer_loaded' );
	}

	/**
	 * Include files.
	 *
	 * @since 1.0.0
	 */
	public function includes() {

		// Repeater control.
		// require_once HESTER_CORE_PLUGIN_DIR . 'includes/customizer/controls/repeater/class-hester-customizer-control-repeater.php';
		require_once HESTER_CORE_PLUGIN_DIR . 'themes/hester/customizer/settings/index.php';
		require_once HESTER_CORE_PLUGIN_DIR . 'themes/hester/customizer/default.php';
	}


	// public function hester_core_customzier_control($controls)
	// {
	// $controls['repeater'] = 'Hester_Customizer_Control_Repeater';
	// return $controls;
	// }

}

/**
 * The function which returns the one Hester_Core_Customizer instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $Hester_Core_Customizer = Hester_Core_Customizer(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function Hester_Core_Customizer() {
	return Hester_Core_Customizer::instance();
}

Hester_Core_Customizer();
