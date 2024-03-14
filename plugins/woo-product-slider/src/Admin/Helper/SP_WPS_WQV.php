<?php
/**
 * Product Slider for WooCommerce - SP_WPS_WQV class file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/Admin/views.
 */

namespace ShapedPlugin\WooProductSlider\Admin\Helper;

/**
 * Product Slider for WooCommerce - SP_WPS_WQV class
 *
 * @since 2.0
 */
class SP_WPS_WQV {

	/**
	 * Single instance of the class.
	 *
	 * @var $_instance
	 */
	private static $_instance;

	/**
	 * GetInstance function of the class.
	 *
	 * @return SP_WPS_WQV
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new SP_WPS_WQV();
		}
		return self::$_instance;
	}

	/**
	 * SP_WPS_WQV constructor.
	 */
	public function __construct() {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	}

	/**
	 * WQV Plugin install url.
	 *
	 * @param string $url WQV Plugin install url.
	 */
	public function install_plugin( $url ) {
		if ( false !== strstr( $url, '.zip' ) ) {
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

		$upgrader = new \Plugin_Upgrader();

		if ( ! $upgrader->install( $download_link ) ) {
			return 0;
		}

		$plugin_to_activate = $upgrader->plugin_info();
		$this->activate_wqv_plugin( $plugin_to_activate );

		return 1;
	}

	/**
	 * WQV Plugin install activate function.
	 *
	 * @param string $plugin_to_activate WQV Plugin install activate.
	 */
	public function activate_wqv_plugin( $plugin_to_activate ) {
		$activate = activate_plugin( $plugin_to_activate );
		wp_cache_flush();

		$this->show_activation_notice();
	}

	/**
	 * WQV Plugin install activate notice function.
	 */
	public function show_activation_notice() {
		echo '<div class="updated notice is-dismissible"><p>Plugin <strong>activated.</strong></p></div>';
	}

}
