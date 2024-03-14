<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Plugin Name: TeraWallet by WCBeginner
 * Plugin URI: https://wordpress.org/plugins/woo-wallet/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Woo_Wallet {

	public function __construct() {
		add_filter( 'wfacp_css_js_deque', [ $this, 'css_enqueue' ], 10, 3 );
	}

	public function css_enqueue( $bool, $path, $url ) {
		if ( false !== strpos( $url, '/smoothness/jquery-ui.css' ) ) {
			return false;
		}

		return $bool;
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Woo_Wallet(), 'woo-wallet' );
