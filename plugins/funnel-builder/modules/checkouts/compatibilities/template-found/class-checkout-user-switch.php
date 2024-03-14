<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Checkout_User_Switching {
	public function __construct() {
		$instance = user_switching::get_instance();
		remove_action( 'wp_footer', array( $instance, 'action_wp_footer' ) );
		add_action( 'wfacp_footer_after_print_scripts', array( $instance, 'action_wp_footer' ) );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Checkout_User_Switching(), 'user_switching' );
