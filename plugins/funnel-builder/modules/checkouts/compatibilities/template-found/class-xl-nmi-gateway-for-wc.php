<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Name: XL NMI Gateway for WooCommerce by XLPlugins
 * Plugin URI: https://funnelkit.com/woocommerce-nmi-payment-gateway/
 */
#[AllowDynamicProperties] 

  class WFACP_XL_NMI_Gateway_For_WC {
	public function __construct() {
		add_filter( 'wfacp_css_js_removal_paths', [ $this, 'remove_nmi_front_js' ], 8 );
	}

	public function remove_nmi_front_js( $path ) {
		if ( class_exists( 'NMI_Gateway_Woocommerce_Loader' ) && WFACP_Common::is_theme_builder() ) {
			$path[] = 'https://secure.nmi.com/token/Collect.js';
		}

		return $path;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_XL_NMI_Gateway_For_WC(), 'wfacp-xl-nmi' );

