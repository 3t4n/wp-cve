<?php
/*
Plugin Name: Unite Gallery Lite
Plugin URI: http://wp.unitegallery.net
Description: Unite Gallery Lite - All in one image and video gallery
Author: Valiano
Version: 1.7.62
Author URI: http://wp.unitegallery.net
*/

//ini_set("display_errors", "on");
//ini_set("error_reporting", E_ALL);

if(!defined("UNITEGALLERY_INC"))
	define("UNITEGALLERY_INC", true);

define("UNITEGALLERY_VERSION", "1.7.62");


if ( ! function_exists( 'ugl_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ugl_fs() {
        global $ugl_fs;

        if ( ! isset( $ugl_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';
			
            $ugl_fs = fs_dynamic_init( array(
                'id'                  => '12303',
                'slug'                => 'unite-gallery-lite',
                'premium_slug'        => 'unitegallery',
                'type'                => 'plugin',
                'public_key'          => 'pk_5e92c0d17c394ac5373e6d1d4ec97',
                'is_premium'          => false,
                'premium_suffix'      => 'Unite Gallery Pro',
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'menu'                => array(
                    'slug'           => 'unitegallery',
                    'contact'        => false,
                    'support'        => false,
                )
            ) );
        }

        return $ugl_fs;
    }

    // Init Freemius.
    ugl_fs();
    // Signal that SDK was initiated.
    do_action( 'ugl_fs_loaded' );
}


$mainFilepath = __FILE__;
$currentFolder = dirname($mainFilepath);

try{
	require_once $currentFolder.'/includes.php';
	
	require_once $currentFolder."/inc_php/framework/provider/provider_main_file.php";

}catch(Exception $e){
	
	$message = $e->getMessage();
	echo $message;
}

