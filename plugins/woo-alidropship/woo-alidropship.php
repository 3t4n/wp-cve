<?php
/**
 * Plugin Name: ALD - Dropshipping and Fulfillment for AliExpress and WooCommerce
 * Plugin URI: https://villatheme.com/extensions/aliexpress-dropshipping-and-fulfillment-for-woocommerce/
 * Description: Transfer data from AliExpress products to WooCommerce effortlessly and fulfill WooCommerce orders to AliExpress automatically.
 * Version: 2.0.1
 * Author: VillaTheme(villatheme.com)
 * Author URI: http://villatheme.com
 * Text Domain: woo-alidropship
 * Copyright 2019-2023 VillaTheme.com. All rights reserved.
 * Tested up to: 6.3
 * WC tested up to: 8.1
 * Requires PHP: 7.0
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VI_WOO_ALIDROPSHIP_VERSION', '2.0.1' );
define( 'VI_WOO_ALIDROPSHIP_DIR', plugin_dir_path( __FILE__ ) );
define( 'VI_WOO_ALIDROPSHIP_INCLUDES', VI_WOO_ALIDROPSHIP_DIR . "includes" . DIRECTORY_SEPARATOR );

if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-ali-orders-info-table.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "class-vi-wad-ali-orders-info-table.php";
}

if ( is_file( VI_WOO_ALIDROPSHIP_INCLUDES . "ali-product-table.php" ) ) {
	require_once VI_WOO_ALIDROPSHIP_INCLUDES . "ali-product-table.php";
}


/**
 * Class VI_WOO_ALIDROPSHIP
 */
class VI_WOO_ALIDROPSHIP {
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'before_woocommerce_init', [ $this, 'custom_order_tables_declare_compatibility' ] );
	}

	public function init() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( is_plugin_active( 'woocommerce-alidropship/woocommerce-alidropship.php' ) ) {
			return;
		}

		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			include_once VI_WOO_ALIDROPSHIP_INCLUDES . 'support.php';
		}

		$environment = new \VillaTheme_Require_Environment( [
				'plugin_name'     => 'ALD - Dropshipping and Fulfillment for AliExpress and WooCommerce',
				'php_version'     => '7.0',
				'wp_version'      => '5.0',
				'wc_version'      => '5.0',
				'require_plugins' => [ [ 'slug' => 'woocommerce', 'name' => 'WooCommerce' ] ]
			]
		);

		if ( $environment->has_error() ) {
			return;
		}

		global $wpdb;

		$tables = array(
			'ald_posts'    => 'ald_posts',
			'ald_postmeta' => 'ald_postmeta'
		);

		foreach ( $tables as $name => $table ) {
			$wpdb->$name    = $wpdb->prefix . $table;
			$wpdb->tables[] = $table;
		}

		require_once VI_WOO_ALIDROPSHIP_INCLUDES . "define.php";
	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		global $wp_version;
		if ( version_compare( $wp_version, "4", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 4.0 or higher." );
		}
		VI_WOO_ALIDROPSHIP_Ali_Orders_Info_Table::create_table();
		$check_active = get_option( 'wooaliexpressdropship_params' );
		if ( ! $check_active ) {
			if ( ! class_exists( 'VI_WOO_ALIDROPSHIP_DATA' ) ) {
				require_once VI_WOO_ALIDROPSHIP_INCLUDES . "data.php";
			}
			$settings = VI_WOO_ALIDROPSHIP_DATA::get_instance();
			$params   = $settings->get_params();

			foreach ( [ 'CNY', 'RUB' ] as $currency ) {
				if ( empty( $params["import_currency_rate_{$currency}"] ) ) {

					$rate = VI_WOO_ALIDROPSHIP_DATA::get_exchange_rate( 'google', $currency, $currency === 'CNY' ? 2 : 3 );

					$params["import_currency_rate_{$currency}"] = $rate;
				}
			}

			$params['secret_key'] = md5( time() );
			if ( is_plugin_active( 'woocommerce-extra-checkout-fields-for-brazil/woocommerce-extra-checkout-fields-for-brazil.php' ) ) {
				/*Set default custom fields if Brazilian Market on WooCommerce plugin is active*/
				$params['cpf_custom_meta_key']            = '_billing_cpf';
				$params['billing_number_meta_key']        = '_billing_number';
				$params['shipping_number_meta_key']       = '_shipping_number';
				$params['billing_neighborhood_meta_key']  = '_billing_neighborhood';
				$params['shipping_neighborhood_meta_key'] = '_shipping_neighborhood';
			}
			update_option( 'wooaliexpressdropship_params', $params );
			add_action( 'activated_plugin', array( $this, 'after_activated' ) );
		} elseif ( wp_next_scheduled( 'vi_wad_update_aff_urls' ) ) {
			wp_unschedule_hook( 'vi_wad_update_aff_urls' );
		}
	}

	public function after_activated( $plugin ) {
		if ( $plugin === plugin_basename( __FILE__ ) ) {
			$url = admin_url( '?vi_wad_setup_wizard=true' );
			$url = add_query_arg( '_wpnonce', wp_create_nonce( 'vi_wad_setup' ), $url );
			exit( wp_redirect( $url ) );
		}
	}

	public function custom_order_tables_declare_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
}

new VI_WOO_ALIDROPSHIP();