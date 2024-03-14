<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

function ari_cf7button_clear_site_settings() {
    delete_option( 'ari_cf7button' );
    delete_option( 'ari_cf7button_settings' );
}

if ( ! is_multisite() ) {
    ari_cf7button_clear_site_settings();
} else {
    global $wpdb;

    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
    $original_blog_id = get_current_blog_id();

    foreach ( $blog_ids as $blog_id )   {
        switch_to_blog( $blog_id );

        ari_cf7button_clear_site_settings();
    }

    switch_to_blog( $original_blog_id );
}
