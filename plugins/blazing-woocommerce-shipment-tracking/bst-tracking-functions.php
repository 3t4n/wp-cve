<?php

/**
 * @package     blazing-shipment-tracking
 * @category    Functions
 * @since       1.0
 *
 * Functions used by plugins
 */

if ( ! class_exists( 'BS_Shipment_Tracking_Dependencies' ) )
	require_once 'class-bst-tracking-dependencies.php';

/**
 * WC Detection
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		return BS_Shipment_Tracking_Dependencies::woocommerce_active_check();
	}
}
