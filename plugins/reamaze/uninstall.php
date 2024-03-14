<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

// Remove Pages
wp_trash_post( get_option( 'reamaze_post_reamaze-kb' ) );
wp_trash_post( get_option( 'reamaze_post_reamaze-support' ) );

// Remove options
$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'reamaze_%;'");
