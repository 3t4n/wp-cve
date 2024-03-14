<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
 
delete_option( 'soc_pix_options' );