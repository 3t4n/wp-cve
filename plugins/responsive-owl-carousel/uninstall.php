<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

function owlc_delete_plugin() {
	global $wpdb;

	delete_option( 'owl-carousel-responsive' );

	$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",
		$wpdb->prefix . 'owl_carousel_tbl' ) );
}

owlc_delete_plugin();