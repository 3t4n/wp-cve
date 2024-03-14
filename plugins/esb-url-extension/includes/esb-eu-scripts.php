<?php

/**
 * Scripts File
 * Handles to admin functionality & other functions
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Load Admin styles & scripts
 */
function esb_eu_admin_scripts(){
    
     // Load our admin stylesheet.
     wp_enqueue_style( 'esb-eu-admin-style', ESB_EU_URL . 'css/admin-style.css' );
     
}

//add action to load scripts and styles for the back end
add_action( 'admin_enqueue_scripts', 'esb_eu_admin_scripts' );

?>