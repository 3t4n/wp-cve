<?php

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
 
$option_name = 'fca_eoi_allow_customform';
 
delete_option( $option_name );
 
// For site options in Multisite
delete_site_option( $option_name );  
