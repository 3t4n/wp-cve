<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Revo {

	public function __construct() {
		add_filter( 'wfacp_css_js_removal_paths', [ $this, 'disallow_sw_theme_js_css' ] );
		add_filter( 'wfacp_css_js_deque', [ $this, 'allow_currency_exchange_js' ], 10, 3 );
	}


	public function disallow_sw_theme_js_css( $paths ) {
		$paths[] = '/sw_theme/';

		return $paths;
	}

	public function allow_currency_exchange_js( $status, $path, $url ) {

		if ( false !== strpos( $url, '/revo/lib/plugins/currency-converter/' ) ) {
			$status = false;
		}

		return $status;

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Revo(), 'wfacp-revo' );
