<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Theme_Twenty_Twenty_2 {
	public function __construct() {


		add_filter( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );

	}


	public function add_internal_css() {


		echo "<style>";
		echo 'body.woocommerce-page .select2-container .select2-dropdown {padding: 0;}';
		echo '.woocommerce-page .select2-container .select2-search__field,.woocommerce-page .select2-container .select2-selection {height: auto;padding: 8px;margin: 0 !important;}';

		echo "</style>";

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Twenty_Twenty_2(), 'wfacp-twentytwenty' );
