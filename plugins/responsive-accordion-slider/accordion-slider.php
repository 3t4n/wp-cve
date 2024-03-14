<?php
/*
Plugin Name: Responsive Accordion Slider
Plugin URI: https://demo.techknowprime.com/responsive-accordion-slider/
Description: Responsive Accordion Slider is fully resonsive, user-friendly, having horizonatal and vertical slider layouts. 
Author: TechKnow Prime	
Author URI: https://www.techknowprime.com
Version: 1.1.7
Text Domain: responsive-accordion-slider
*/

/** Configuration **/

if ( !defined( 'RESP_ACCORDION_SLIDER_CURRENT_VERSION' ) ) {
    define( 'RESP_ACCORDION_SLIDER_CURRENT_VERSION', '1.1.7' );
}

define( 'RESP_ACCORDION_SLIDER_DIR_PATH'       , plugin_dir_path(__FILE__) );
define( 'RESP_ACCORDION_SLIDER_URL_PATH'       , plugin_dir_url(__FILE__) );
define( 'RESP_ACCORDION_SLIDER_BASENAME'       , plugin_basename( __FILE__ ) );

define( 'RESP_ACCORDION_SLIDER_INCLUDES_PATH'  , RESP_ACCORDION_SLIDER_DIR_PATH  . 'includes' . DIRECTORY_SEPARATOR );
define( 'RESP_ACCORDION_SLIDER_ADMIN_PATH'     , RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'admin' . DIRECTORY_SEPARATOR );
define( 'RESP_ACCORDION_SLIDER_LIBRARIES_PATH' , RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'libraries' . DIRECTORY_SEPARATOR );

define( 'RESP_ACCORDION_SLIDER_ASSETS_PATH'    , RESP_ACCORDION_SLIDER_URL_PATH . 'assets/' );
define( 'RESP_ACCORDION_SLIDER_JS_PATH'        , RESP_ACCORDION_SLIDER_URL_PATH . 'assets/js/' );
define( 'RESP_ACCORDION_SLIDER_IMAGES_PATH'    , RESP_ACCORDION_SLIDER_URL_PATH . 'assets/images/' );

/**
* Activating plugin and adding some info
*/
function resp_accordion_slider_activate() {
    update_option("resp-accordion-slider-v", RESP_ACCORDION_SLIDER_CURRENT_VERSION );
    update_option("resp-accordion-slider-type","FREE");
    update_option("resp-accordion-slider-installDate",date('Y-m-d h:i:s') );

    require_once RESP_ACCORDION_SLIDER_INCLUDES_PATH. 'ras-default-image.php';
}
// Installation hooks
register_activation_hook(__FILE__, 'resp_accordion_slider_activate' );

/**
 * Deactivate the plugin
 */
function resp_accordion_slider_deactivate() {
    // Do nothing
} 
//uninstallation hooks
register_deactivation_hook(__FILE__, 'resp_accordion_slider_deactivate' );

/**
 * Pro version check.
 *
 * @return boolean
 */
function is_resp_accordion_slider_pro() {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if ( ! ( is_plugin_active( 'accordion-slider-pro/accordion-slider-pro.php' ) || is_plugin_active_for_network( 'accordion-slider-pro/accordion-slider-pro.php' ) ) ) {
        return true;
    }
}

add_image_size( 'resp_accordion_slider_img',1600,900,true);

/**
 * The core plugin class that is used to define admin-specific hooks,
 * internationalization, and public-facing site hooks.
 */

require RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'class-resp-accordion-slider.php';

/**
 * Start execution of the plugin.
*/
function resp_accordion_slider_run() {
	//instantiate the plugin class
    $ResponsiveAccordionSlider = new Resp_Accordion_Slider();
}

if ( is_resp_accordion_slider_pro() ) {
    resp_accordion_slider_run();
}