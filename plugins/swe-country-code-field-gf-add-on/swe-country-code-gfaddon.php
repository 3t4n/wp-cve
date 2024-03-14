<?php
/*
Plugin Name: SWE Country Code Field GF Add-On
Description: Add fields for country code with country flag in dropdown on phone number as a gf addons.
Version: 2.1.0
Author: SanjayWebExpert
Author URI: http://sanjaywebexpert.com
Text Domain: swecodegffieldaddon
*/

define( 'SWE_GF_COUNTRY_CODE_ADDON_VERSION', '2.1.0' );

add_action( 'gform_loaded', array( 'SWE_GF_Country_Code_AddOn_Bootstrap', 'load' ), 5 );

class SWE_GF_Country_Code_AddOn_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'class-swegfcountycodeaddon.php' );

        GFAddOn::register( 'SWEGFCountryCodeAddOn' );
    }

}