<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Indeed Ultimate Affiliate Pro by WPIndeed Development */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Indeed_Ultimate_Affiliate_Pro {

	public function __construct() {

		add_action( 'admin_enqueue_scripts', [ $this, 'remove_script' ], 999 );

	}


	public function remove_script() {

		if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] !== 'wfacp' ) {
			return;
		}

		wp_dequeue_script( 'uap_admin_js' );

	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Indeed_Ultimate_Affiliate_Pro(), 'wfacp-indeed-ultimate' );


