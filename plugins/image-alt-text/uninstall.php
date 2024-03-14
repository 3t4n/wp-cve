<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

if(is_multisite()){
    
    global $wpdb;
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
    $original_blog_id = get_current_blog_id();

    foreach ( $blog_ids as $blog_id ) 
    {
        switch_to_blog( $blog_id );        
        delete_option( 'iat_review_reminder' );
        delete_option( 'iat_do_not_show_again' );        
    }
    switch_to_blog( $original_blog_id );

}else{

    delete_option( 'iat_review_reminder' );
    delete_option( 'iat_do_not_show_again' );
    
}