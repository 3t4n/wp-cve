<?php
/**
 * Woocommerce Cart Abandonment Recovery
 * Unscheduling the events.
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

wp_clear_scheduled_hook( 'intrkt_abandon_update_order_status_action' );




