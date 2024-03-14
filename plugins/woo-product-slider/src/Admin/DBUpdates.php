<?php
/**
 * Fired during plugin updates.
 *
 * This class defines all code necessary to run during the plugin's updates.
 *
 * @since      2.2.0
 * @package    Woo_Product_Slider
 * @subpackage Woo_Product_Slider/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product slider updates class.
 */
class DBUpdates {

	/**
	 * DB updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = array(
		'2.2.0' => 'updates/update-2.2.0.php',
		'2.4.0' => 'updates/update-2.4.0.php',
		'2.6.8' => 'updates/update-2.6.8.php',
		'2.7.0' => 'updates/update-2.7.0.php',
	);

	/**
	 * Binding all events
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'do_updates' ) );
	}

	/**
	 * Check if need any update
	 *
	 * @since 2.2.0
	 *
	 * @return boolean
	 */
	public function is_needs_update() {
		$installed_version = get_option( 'woo_product_slider_version' );

		if ( false === $installed_version ) {
			update_option( 'woo_product_slider_version', SP_WPS_VERSION );
			update_option( 'woo_product_slider_db_version', SP_WPS_VERSION );
		}

		if ( version_compare( $installed_version, SP_WPS_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}



	/**
	 * Do updates.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function do_updates() {
		$this->perform_updates();
	}

	/**
	 * Perform all updates
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function perform_updates() {
		if ( ! $this->is_needs_update() ) {
			return;
		}

		$installed_version = get_option( 'woo_product_slider_version' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'woo_product_slider_version', $version );
			}
		}

		update_option( 'woo_product_slider_version', SP_WPS_VERSION );

	}

}
