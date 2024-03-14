<?php
/*
 Plugin Name: Image Slider Slideshow
 Plugin URI: #
 
 Description: Responsive Image Slider plugin is an easy way to create responsive image slider.
 
 Author: wptexture
 Author URI: #
 
 Version: 1.8
 Text Domain: img-slider
*/
/** Configuration **/

if ( !defined( 'IMG_SLIDER_CURRENT_VERSION' ) ) {
    define( 'IMG_SLIDER_CURRENT_VERSION', '1.8' );
}

if(!defined( 'SLIDER_SLIDESHOW_PLUGIN_UPGRADE' ) ) {
    define('SLIDER_SLIDESHOW_PLUGIN_UPGRADE','https://testerwp.com/product/image-slider-slideshow-pro/'); // Plugin Check link
}

define( 'IMG_SLIDER_NAME'             , 'img_slider' );
define( 'IMG_SLIDER_DIR'              , plugin_dir_path(__FILE__) );
define( 'IMG_SLIDER_URL'              , plugin_dir_url(__FILE__) );

define( 'IMG_SLIDER_INCLUDES'         , IMG_SLIDER_DIR       . 'includes'    . DIRECTORY_SEPARATOR );
define( 'IMG_SLIDER_ADMIN'            , IMG_SLIDER_INCLUDES   . 'admin'       . DIRECTORY_SEPARATOR );
define( 'IMG_SLIDER_LIBRARIES'        , IMG_SLIDER_INCLUDES   . 'libraries'   . DIRECTORY_SEPARATOR );

define( 'IMG_SLIDER_ASSETS'           , IMG_SLIDER_URL . 'assets/' );
define( 'IMG_SLIDER_JS'               , IMG_SLIDER_URL . 'assets/js/' );
define( 'IMG_SLIDER_IMAGES'           , IMG_SLIDER_URL . 'assets/images/' );
define( 'IMG_SLIDER_RESOURCES'        , IMG_SLIDER_URL . 'assets/resources/' );

//define( 'img_slider'                  , 'img_slider' );


/**
* Activating plugin and adding some info
*/
function img_slider_activate() {
    update_option( "img-slider-v", IMG_SLIDER_CURRENT_VERSION );
    update_option("img-slider-type","FREE");
    update_option("img-slider-installDate",date('Y-m-d h:i:s') );

    require_once IMG_SLIDER_DIR.'default_image.php';
}

/**
 * Deactivate the plugin
 */
function img_slider_deactivate() {
    // Do nothing
} 

// Installation and uninstallation hooks
register_activation_hook(__FILE__, 'img_slider_activate' );
register_deactivation_hook(__FILE__, 'img_slider_deactivate' );



add_image_size( 'rpg_image_slider',1920,800,true);
add_image_size( 'rpg_image_thumbnail',900,500,true);


/**
 * The core plugin class that is used to define admin-specific hooks,
 * internationalization, and public-facing site hooks.
 */

require IMG_SLIDER_INCLUDES . 'class-img-slider.php';


/**
 * Start execution of the plugin.
*/
function img_slider_run() {
	//instantiate the plugin class
    $imgSlider = new IMG_Slider();
}
img_slider_run();




