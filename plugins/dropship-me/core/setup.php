<?php

/**
 * Setup the plugin
 */
function dm_install() {
 
	update_site_option( 'dm-version', DM_VERSION  );

	if ( DM_PLUGIN == 'woocommerce' ) {

		require( DM_PATH . 'core/sql.php' );

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		foreach( dm_sql_list() as $key ) {
			dbDelta( $key );
		}
	}
}

/**
 * Uninstall plugin
 */
function dm_uninstall() {}

/**
 * Check installed plugin
 */
function dm_installed() {

	if ( ! current_user_can( 'install_plugins' ) ) {
        return;
    }

    $version = get_option( 'dm-version' );

	if ( $version < DM_VERSION ) {
		dm_install();
    }
}
add_action( 'admin_menu', 'dm_installed' );

/**
 * When activate plugin
 */
function dm_activate() {

    global $wpdb;
    
	dm_installed();

	do_action( 'dm_activate' );
	
	$wpdb->query( "DELETE m FROM {$wpdb->prefix}adsw_ali_meta m LEFT JOIN {$wpdb->posts} p ON p.ID = m.post_id WHERE p.ID IS NULL" );
}

/**
 * When deactivate plugin
 */
function dm_deactivate(){

	do_action( 'dm_deactivate' );
}