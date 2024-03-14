<?php

add_action( 'wfacp_after_checkout_page_found', function () {
	if ( ! class_exists( 'Affiliate_WP' ) ) {
		return;
	}
	$obj = Affiliate_WP::instance();
	if ( ! is_null( $obj ) && property_exists( $obj, 'tracking' ) && $obj->tracking instanceof Affiliate_WP_Tracking ) {
		remove_action( 'wp_enqueue_scripts', array( $obj->tracking, 'load_scripts' ) );
		add_action( 'wp_head', array( $obj->tracking, 'load_scripts' ) );
	}

} );


