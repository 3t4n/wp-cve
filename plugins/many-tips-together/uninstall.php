<?php
/**
 * Uninstall Procedure for Admin Tweaks
 */

// Make sure that we are uninstalling
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

// Leave no trail
$adtw_option = "b5f_admin_tweaks";

delete_option( $adtw_option );
delete_option( "$adtw_option-support" );