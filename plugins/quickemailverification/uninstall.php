<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

delete_option( 'qev_email_validator' );
delete_option( 'QEV_PLUGIN_VER' );

wp_cache_flush();