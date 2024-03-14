<?php
    global $wpdb;

	// if uninstall.php is not called by WordPress, die
	if (!defined('WP_UNINSTALL_PLUGIN')) {
		die;
	}
	
	// Loop and delete options
	delete_option( 'show_in_dnd_file_uploader_in' );
    delete_option( 'show_in_dnd_file_upload_after' );
	
    $plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wcf_drag_n_drop_%'" );

    foreach( $plugin_options as $option ) {
        delete_option( $option->option_name );
    }