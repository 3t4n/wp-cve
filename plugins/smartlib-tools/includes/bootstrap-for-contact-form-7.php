<?php

/**
 * Check if bootstrap-for-contact-form-7 is installed
 */



if(!defined('CF7BS_VERSION')){

    if (!function_exists('cf7bs_maybe_init')) {
        // load Social if not already loaded
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/bootstrap-for-contact-form-7/bootstrap-for-contact-form-7.php' );



    }



}


