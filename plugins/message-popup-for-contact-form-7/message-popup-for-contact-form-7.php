<?php
/*
  Plugin Name: Message Popup For Contact Form 7
  Description: This plugin give success fail popup message For Contact Form 7.
  Version: 1.0
  Copyright: 2023
  Text Domain: message-popup-for-contact-form-7
*/


if (!defined('ABSPATH')) {
    die('-1');
}


// define for base name
define('MPFCF7_BASE_NAME', plugin_basename(__FILE__));


// define for plugin file
define('mpfcf7_plugin_file', __FILE__);


// define for plugin dir path
define('MPFCF7_PLUGIN_DIR',plugins_url('', __FILE__));

// Include function files
include_once('inc/mpfcf7_backend.php');

function MPFCF7_admin_enqueue_scripts() {
    wp_enqueue_script( 'jquery-color-js', MPFCF7_PLUGIN_DIR. '/asset/js/coloris.min.js', array('jquery'), '1.1');
    wp_enqueue_script( 'jquery-colors-js', MPFCF7_PLUGIN_DIR. '/asset/js/mpdcf7_backebd.js', array('jquery'), '1.1');
    wp_enqueue_style( 'jquery-popup-color', MPFCF7_PLUGIN_DIR. '/asset/css/coloris.min.css', '', '3.0' );
}
add_action('admin_enqueue_scripts', 'MPFCF7_admin_enqueue_scripts');


function MPFCF7_load_script_style(){
    wp_enqueue_script( 'jquery-popup', MPFCF7_PLUGIN_DIR . '/asset/js/sweetalert.min.js', array('jquery'), '2.0');
    // wp_enqueue_script( 'jquery-popups', MPFCF7_PLUGIN_DIR. '/asset/js/mpdcf7_index.js', array('jquery'), '1.0');
    wp_enqueue_script( 'jquery-popupss', MPFCF7_PLUGIN_DIR. '/asset/js/mpdcf7_fronted.js', array('jquery'), '1.0');
    wp_enqueue_style( 'jquery-popup-style', MPFCF7_PLUGIN_DIR. '/asset/css/sweetalert.css', '', '3.0' );

    $args = array(
        'post_type' => 'wpcf7_contact_form', 
        'posts_per_page' => -1
    ); 
    $cf7Forms = get_posts( $args );

    foreach ($cf7Forms as $form) {
        $form_id = $form->ID;
    }

    $passarray =  array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'popup_text' => get_post_meta( $form_id, 'mpfcf7_popup_success_text', true ),
    );
    wp_localize_script( 'jquery-popupss', 'popup_message', $passarray);

}
add_action( 'wp_enqueue_scripts', 'MPFCF7_load_script_style' );