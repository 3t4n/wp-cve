<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * WooCommerce Order Status Manager plugin
 */
if ( ! class_exists( 'VI_WOO_ORDERS_TRACKING_PLUGINS_WooCommerce_Order_Status_Manager' ) ) {
	class VI_WOO_ORDERS_TRACKING_PLUGINS_WooCommerce_Order_Status_Manager {
		public function __construct() {
			add_filter( 'woocommerce_orders_tracking_email_woo_statuses', array(
				$this,
				'woocommerce_orders_tracking_email_woo_statuses'
			) );
		}

		public function woocommerce_orders_tracking_email_woo_statuses( $email_woo_statuses ) {
			if ( is_plugin_active( 'woocommerce-order-status-manager/woocommerce-order-status-manager.php' ) ) {
				if ( function_exists( 'wc_order_status_manager_get_order_status_posts' ) ) {
					$statuses = self::get_emails();
					if ( count( $statuses ) ) {
						foreach ( $statuses as $status ) {
							$status_id = "wc_order_status_email_{$status->ID}";
							if ( ! isset( $email_woo_statuses[ $status_id ] ) ) {
								$email_woo_statuses[ $status_id ] = $status->post_title;
							}
						}
					}
				}
			}

			return $email_woo_statuses;
		}

		public static function get_emails() {
			return get_posts( array(
				'post_type'        => 'wc_order_email',
				'post_status'      => 'publish',
				'nopaging'         => true,
				'suppress_filters' => 1,
			) );
		}
	}
}
