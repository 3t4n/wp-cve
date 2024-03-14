<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Functions used by plugins
 */
if ( ! class_exists( 'WFACP_WC_Dependencies' ) ) {
	require_once __DIR__ . '/class-wfacp-wc-dependencies.php';
}

/**
 * WC Detection
 */
if ( ! function_exists( 'wfacp_is_woocommerce_active' ) ) {
	function wfacp_is_woocommerce_active() {
		return WFACP_WC_Dependencies::woocommerce_active_check();
	}

}
