<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * WooCommerce Order Status & Actions Manager plugin
 */
if ( ! class_exists( 'VI_WOO_ORDERS_TRACKING_PLUGINS_WooCommerce_Status_Actions' ) ) {
	class VI_WOO_ORDERS_TRACKING_PLUGINS_WooCommerce_Status_Actions {
		public function __construct() {
			add_filter( 'woocommerce_orders_tracking_email_woo_statuses', array(
				$this,
				'woocommerce_orders_tracking_email_woo_statuses'
			) );
		}

		public function woocommerce_orders_tracking_email_woo_statuses( $email_woo_statuses ) {
			if ( is_plugin_active( 'woocommerce-status-actions/woocommerce-status-actions.php' ) ) {
				if ( function_exists( 'wc_sa_get_statuses' ) ) {
					$statuses = wc_sa_get_statuses();
					if ( count( $statuses ) ) {
						foreach ( $statuses as $status ) {
							$status_id = "wc_sa_order{$status->label}";
							if ( ! isset( $email_woo_statuses[ $status_id ] ) ) {
								$email_woo_statuses[ $status_id ] = $status->title;
							}
						}
					}
				}
			}

			return $email_woo_statuses;
		}
	}
}
