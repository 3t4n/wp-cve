<?php
/**
 * uninstall.php
 *
 *
 */

include_once( 'admin/class-account-information.php' );
include_once( 'admin/class-form-setup.php' );
include_once( 'admin/class-form-custom.php' );

// If uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

// Delete options from options table

delete_option( Account_Information::$key );
delete_option( Form_Setup::$key );
delete_option( Form_Custom::$key );

// remove any additional options and custom tables

