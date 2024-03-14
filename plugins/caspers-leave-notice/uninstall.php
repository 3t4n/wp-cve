<?php

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
 
delete_option( 'cpln_content_settings' );
delete_option( 'cpln_exclusions' );
delete_option( 'cpln_other_settings' );
?>