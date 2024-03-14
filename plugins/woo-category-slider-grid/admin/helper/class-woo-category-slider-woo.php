<?php
/**
 * The file check WooCommerce plugin active.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/helper
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WooCommerce plugin check.
 */
class SP_WCS_WOO {

	/**
	 * Instance
	 *
	 * @var null
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * GetInstance.
	 *
	 * @return SP_WPS_WQV
	 */
	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new SP_WPS_WQV();
		}

		return self::$instance;
	}

	/**
	 * Woo constructor.
	 */
	public function __construct() {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	}

	/**
	 * Install plugin.
	 *
	 * @param string $url plugin url.
	 * @return statement
	 */
	public function install_plugin( $url ) {
		if ( strstr( $url, '.zip' ) != false ) {
			$download_link = $url;
		} else {
			$slug          = explode( '/', $url );
			$slug          = $slug[ count( $slug ) - 2 ];
			$api           = plugins_api(
				'plugin_information',
				array(
					'slug'   => $slug,
					'fields' => array( 'sections' => 'false' ),
				)
			);
			$download_link = $api->download_link;
		}

		$upgrader = new Plugin_Upgrader();

		if ( ! $upgrader->install( $download_link ) ) {
			return 0;
		}

		$plugin_to_activate = $upgrader->plugin_info();
		$this->activate_woo_plugin( $plugin_to_activate );

		return 1;
	}

	/**
	 * Activate WooCommerce.
	 *
	 * @param [type] $plugin_to_activate plugin.
	 * @return void
	 */
	public function activate_woo_plugin( $plugin_to_activate ) {
		$activate = activate_plugin( $plugin_to_activate );
		wp_cache_flush();

		$this->show_activation_notice();
	}

	/**
	 * Activation notice.
	 *
	 * @return void
	 */
	public function show_activation_notice() {
		echo '<div class="updated notice is-dismissible"><p>';
		echo __( 'Plugin <strong>activated.</strong>', 'woo-category-slider-grid' );
		echo '</p></div>';
	}

}
