<?php 
/**
* Plugin Name: Dropdown Search Option for Contact Form 7
* Description: This plugin Make search option in drop-down menu field in Contact Form 7.
* Version: 1.0
* Copyright: 2023
* Text Domain: dropdown-search-option-for-contact-form-7
*/


if (!defined('ABSPATH')) {
    die('-1');
}


// define for base name
define('DSOFCF7_BASE_NAME', plugin_basename(__FILE__));


// define for plugin file
define('DSOFCF7_plugin_file', __FILE__);


// define for plugin dir path
define('DSOFCF7_PLUGIN_DIR',plugins_url('', __FILE__));



function DSOCF7_load_script_style(){
    wp_enqueue_script( 'jquery-select', DSOFCF7_PLUGIN_DIR . '/asset/js/select2.min.js', array('jquery'), '2.0');
    wp_enqueue_script( 'jquery-selects', DSOFCF7_PLUGIN_DIR. '/asset/js/custom.js', array('jquery'), '1.0');
    
    wp_localize_script( 'jquery-selects', 'selects_ajax', array( 'ajax_urla' => DSOFCF7_PLUGIN_DIR) );

    wp_enqueue_style( 'jquery-selects-style', DSOFCF7_PLUGIN_DIR. '/asset/css/select2.min.css', '', '3.0' );

}
add_action( 'wp_enqueue_scripts', 'DSOCF7_load_script_style' );

?>
