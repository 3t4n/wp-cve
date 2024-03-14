<?php
/**
 * @package Woo Disable Email Notification
 */
namespace Woo_Disable_Email_Notification;

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bootstrap {

	const PREFIX  = 'woo-disable-email-notifications';
	const VERSION = '1.0.2';

	public function __construct() {

		add_action( 'woocommerce_email', [$this, 'disable_emails'] );
		$this->loadClasses();
	}

	public function loadClasses() {
		new Settings();
		new SettingsLink();
	}

	/**
	 * @param $email_class
	 */
	function disable_emails( $email_class ) {

		// Get options
		$prefix  = self::PREFIX;
		$options = get_option( $prefix ); // unique id of the framework

		$keys = array_keys( $options );
		for ( $i = 0; $i < count( $options ); $i++ ) {
			$options[$keys[$i]] = (bool) $options[$keys[$i]];
		}

		/**
		 * Hooks for sending emails during store events
		 *
		 */
		if ( true === $options['wc_disable_low_stock_notifications'] ) {
			remove_action( 'woocommerce_low_stock_notification', array( $email_class, 'low_stock' ) );
		}

		if ( true === $options['wc_disable_no_stock_notifications'] ) {
			remove_action( 'woocommerce_no_stock_notification', array( $email_class, 'no_stock' ) );
		}

		if ( true === $options['wc_disable_product_on_backorder_notifications'] ) {
			remove_action( 'woocommerce_product_on_backorder_notification', array( $email_class, 'backorder' ) );
		}

		// New order emails
		if ( true === $options['wc_disable_pending_processing_new_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		}
		if ( true === $options['wc_disable_pending_completed_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_pending_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		}
		if ( true === $options['wc_disable_pending_onhold_new_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		}
		if ( true === $options['wc_disable_failed_processing_new_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_failed_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		}
		if ( true === $options['wc_disable_failed_processing_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_failed_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
		}
		if ( true === $options['wc_disable_failed_completed_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_failed_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		}
		if ( true === $options['wc_disable_failed_onhold_new_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		}
		if ( true === $options['wc_disable_failed_onhold_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_On_Hold_Order'], 'trigger' ) );
		}

		// Processing order emails
		if ( true === $options['wc_disable_pending_processing_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
		}
		if ( true === $options['wc_disable_pending_onhold_orders_notifications'] ) {
			remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_On_Hold_Order'], 'trigger' ) );
		}

		// Completed order emails
		if ( true === $options['wc_disable_order_status_completed_notifications'] ) {
			remove_action( 'woocommerce_order_status_completed_notification', array( $email_class->emails['WC_Email_Customer_Completed_Order'], 'trigger' ) );
		}

		// Note emails
		if ( true === $options['wc_disable_order_new_customer_note_notifications'] ) {
			remove_action( 'woocommerce_new_customer_note_notification', array( $email_class->emails['WC_Email_Customer_Note'], 'trigger' ) );
		}
	}

}