<?php

 
 /**

Plugin Name: Easy Document Embedder – Embed Word, excel, Powerpoint, Pdf file and more..

Description: You can easily embed word, pdf, excel, powerpoint/ppt and many more files. Via this plugin you can also add download option for the files.

Version: 1.0

WC requires at least: 3.0.0

WC tested up to: 5.6

Author: TechMix

Author URI: https://techmix.xyz/

License: GPLv2 or later

Text Domain: easy-document-embedder

*/

defined('ABSPATH') or die();

/**
 * include file
 */
require_once dirname(__FILE__) . '/inc/init.php';
require_once dirname(__FILE__) . '/inc/Base/class-activate.php';
require_once dirname(__FILE__) . '/inc/Base/class-deactivate.php';

use EDE\Inc\Init;
use EDE\Inc\Base\Activate;
use EDE\Inc\Base\Deactivate;

// activate function
if ( !function_exists( ' EDEActivate ' ) ) {
    function EDEActivate()
    {
        Activate::ede_activate();
    }
    register_activation_hook( __FILE__, 'EDEActivate' );
}

// deactivate function
if ( !function_exists( ' EDEDeactivate ' ) ) {
    function EDEDeactivate()
    {
        Deactivate::ede_deactivate();
    }
    register_deactivation_hook( __FILE__, 'EDEDeactivate' );
}

// initialize this plugin
if (class_exists(Init::class)) {
    Init::ede_register();
}