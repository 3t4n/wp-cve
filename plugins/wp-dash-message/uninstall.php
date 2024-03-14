<?php

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

// Deletes site-level dash message stored in DB 
delete_option( 'wp_dash_message' );

// Deletes network-level dash message stored in DB
delete_site_option( 'wp_dash_message_network' );

// Deletes site-level checkbox settings stored in DB
delete_option( 'wp_remove_site_widgets');

// Deletes network-level checkbox settings stored in DB
delete_site_option( 'wp_remove_network_widgets' );
delete_site_option( 'wp_remove_site_widgets_N' );
delete_site_option( 'wp_remove_global_widgets' );
?>