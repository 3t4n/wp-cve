<?php
/**
 * Plugin Name: Orders Tracking for WooCommerce
 * Plugin URI: https://villatheme.com/extensions/woocommerce-orders-tracking
 * Description: Easily import/manage your tracking numbers, add tracking numbers to PayPal and send email notifications to customers.
 * Version: 1.2.7
 * Author: VillaTheme
 * Author URI: https://villatheme.com
 * Text Domain: woo-orders-tracking
 * Domain Path: /languages
 * Copyright 2019-2023 VillaTheme.com. All rights reserved.
 * Tested up to: 6.3
 * WC tested up to: 8.1
 * Requires PHP: 7.0
 **/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_WOO_ORDERS_TRACKING_VERSION', '1.2.7' );
define( 'VI_WOO_ORDERS_TRACKING_PATH_FILE', __FILE__ );
define( 'VI_WOO_ORDERS_TRACKING_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'woo-orders-tracking' . DIRECTORY_SEPARATOR );
define( 'VI_WOO_ORDERS_TRACKING_INCLUDES', VI_WOO_ORDERS_TRACKING_DIR . 'includes' . DIRECTORY_SEPARATOR );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
/*Required for register_activation_hook*/
if ( is_file( VI_WOO_ORDERS_TRACKING_INCLUDES . 'functions.php' ) ) {
	require_once VI_WOO_ORDERS_TRACKING_INCLUDES . 'functions.php';
}
if ( is_file( VI_WOO_ORDERS_TRACKING_INCLUDES . 'data.php' ) ) {
	require_once VI_WOO_ORDERS_TRACKING_INCLUDES . 'data.php';
}
if ( is_file( VI_WOO_ORDERS_TRACKING_INCLUDES . 'class-vi-woo-orders-tracking-trackingmore-table.php' ) ) {
	require_once VI_WOO_ORDERS_TRACKING_INCLUDES . 'class-vi-woo-orders-tracking-trackingmore-table.php';
}

if ( ! class_exists( 'WOO_ORDERS_TRACKING' ) ) {
	class WOO_ORDERS_TRACKING {
		protected $settings;

		public function __construct() {
			//compatible with 'High-Performance order storage (COT)'
			add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );
			if ( is_plugin_active( 'woocommerce-orders-tracking/woocommerce-orders-tracking.php' ) ) {
				return;
			}
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'activated_plugin', array( $this, 'install' ), 10, 2 );
		}
		public function init() {
			$include_dir = plugin_dir_path( __FILE__ ) . 'includes/';
			if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
				include_once $include_dir . 'support.php';
			}

			$environment = new VillaTheme_Require_Environment( [
					'plugin_name'     => 'Orders Tracking for WooCommerce',
					'php_version'     => '7.0',
					'wp_version'      => '5.0',
					'wc_version'      => '6.0',
					'require_plugins' => [ [ 'slug' => 'woocommerce', 'name' => 'WooCommerce' ] ]
				]
			);

			if ( $environment->has_error() ) {
				return;
			}

			$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-orders-tracking" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
			require_once $init_file;
		}
		public function before_woocommerce_init() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}

		public static function install($plugin, $network_wide) {
			if ( $plugin !== plugin_basename( __FILE__ ) ) {
				return;
			}
			/*Create custom table to store tracking data*/
			global $wpdb;
			if ( function_exists( 'is_multisite' ) && is_multisite() && $network_wide ) {
				$current_blog = $wpdb->blogid;
				$blogs        = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );

				//Multi site activate action
				foreach ( $blogs as $blog ) {
					switch_to_blog( $blog );
					/*Create custom table to store tracking data*/
					VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::create_table();
				}
				switch_to_blog( $current_blog );
			} else {
				//Single site activate action
				/*Create custom table to store tracking data*/
				VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::create_table();
			}
			/*create tracking page*/
			if ( ! get_option( 'woo_orders_tracking_settings' ) ) {
				$current_user = wp_get_current_user();
				// create post object
				$page = array(
					'post_title'  => esc_html__( 'Orders Tracking', 'woo-orders-tracking' ),
					'post_status' => 'publish',
					'post_author' => $current_user->ID,
					'post_type'   => 'page',
					'post_name'   => 'orders-tracking',
				);
				// insert the post into the database
				$page_id = wp_insert_post( $page, true );
				if ( ! is_wp_error( $page_id ) ) {
					$settings                      = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
					$args                          = $settings->get_params();
					$args['service_tracking_page'] = $page_id;
					update_option( 'woo_orders_tracking_settings', $args );
				}
			}
		}
	}
}
new WOO_ORDERS_TRACKING();