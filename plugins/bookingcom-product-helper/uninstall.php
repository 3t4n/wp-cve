<?php
// If uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit( );
// Remove all matching options from the database
if ( function_exists( 'wp_load_alloptions' ) ){    
    // Delete options from options table
    delete_option( 'bookingcom_product_helper_list' );

    foreach ( wp_load_alloptions() as $option => $value ) {	
        if ( strpos( $option, 'booking_product_helper_shortname-' ) !== false ) {		
            delete_option( $option );		
        }	
    }
}
?>