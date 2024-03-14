<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

global $wpdb;

$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wacs_%'" );

foreach( $plugin_options as $option ) {
	delete_option( $option->option_name );
}