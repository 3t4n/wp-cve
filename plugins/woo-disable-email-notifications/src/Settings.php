<?php
/**
 * @package Woo Disable Email Notification
 */
namespace Woo_Disable_Email_Notification;

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Settings {

	public function __construct() {
		$this->plugin_options();
	}

	/**
	 * It create the settings of the plugin
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function plugin_options() {

		// Set a unique slug-like ID
		$prefix  = Bootstrap::PREFIX;
		$version = Bootstrap::VERSION;

		// Create options
		\CSF::createOptions( $prefix, array(
			'menu_title'      => 'Disable Notifications',
			'menu_slug'       => 'woo-disable-email-notifications',
			'framework_title' => 'Disable Email Notificatoins for WooCommerce <small>v' . $version . '</small>',
			'menu_type'       => 'submenu',
			'menu_parent'     => 'brightplugins',
			'nav'             => 'inline',
			'theme'           => 'dark',
			'footer_credit'   => '',
			'show_footer'     => false,
			// menu extras
			'show_bar_menu'   => false,
		) );

		// Create a section
		\CSF::createSection( $prefix, array(
			'title'  => 'General Settings',
			'fields' => array(

				// A text field
				array(
					'title'   => __( 'Low Stock', 'woocommerce-settings-tab-notifications-low-stock' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable low stock notifications' ),
					'id'      => 'wc_disable_low_stock_notifications',
					'default' => 0,
				),

				array(
					'title'   => __( 'No Stock', 'woocommerce-settings-tab-notifications-no-stock' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable no stock notifications' ),
					'id'      => 'wc_disable_no_stock_notifications',
					'default' => 0,
				),

				array(
					'title'   => __( 'Product on backorder', 'woocommerce-settings-tab-notifications-no-stock' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable product on backorder notifications' ),
					'id'      => 'wc_disable_product_on_backorder_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'New order - Pending to processing orders', 'woocommerce-settings-tab-notifications-order-pending-processing' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on pending to processing orders' ),
					'id'      => 'wc_disable_pending_processing_new_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'New order - Pending to completed orders', 'woocommerce-settings-tab-notifications-order-pending-completed' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on pending to completed orders' ),
					'id'      => 'wc_disable_pending_completed_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'New order - Pending to on-hold orders', 'woocommerce-settings-tab-notifications-order-pending-onhold' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on pending to on-hold orders' ),
					'id'      => 'wc_disable_pending_onhold_new_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'New order - Failed to processing orders', 'woocommerce-settings-tab-notifications-order-failed-processing' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on failed to processing orders' ),
					'id'      => 'wc_disable_failed_processing_new_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'New order - Failed to completed orders', 'woocommerce-settings-tab-notifications-failed-completed' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on failed to completed orders' ),
					'id'      => 'wc_disable_failed_completed_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'New order - Failed to on-hold orders', 'woocommerce-settings-tab-notifications-failed-onhold' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on failed to on-hold orders' ),
					'id'      => 'wc_disable_failed_onhold_new_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'Failed to on-hold orders', 'woocommerce-settings-tab-notifications-failed-onhold' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on failed to on-hold orders' ),
					'id'      => 'wc_disable_failed_onhold_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'Failed to processing orders', 'woocommerce-settings-tab-notifications-order-failed-processing' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on failed to processing orders' ),
					'id'      => 'wc_disable_failed_processing_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'Pending to processing orders', 'woocommerce-settings-tab-notifications-failed-onhold' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on pending to processing orders' ),
					'id'      => 'wc_disable_pending_processing_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'Pending to on-hold orders', 'woocommerce-settings-tab-notifications-failed-onhold' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on pending to on-hold orders' ),
					'id'      => 'wc_disable_pending_onhold_orders_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'Order status completed', 'woocommerce-settings-tab-notifications-failed-onhold' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable notifications on completed orders' ),
					'id'      => 'wc_disable_order_status_completed_notifications',
					'default' => 0,
				),
				array(
					'title'   => __( 'New customer note', 'woocommerce-settings-tab-notifications-failed-onhold' ),
					'type'    => 'checkbox',
					'label'   => __( 'Disable new customer note notifications' ),
					'id'      => 'wc_disable_order_new_customer_note_notifications',
					'default' => 0,
				),

			),
		) );

	}

}
