<?php

/**
 * Register Global Scripts
 */
function youzify_global_scripts() {

    // Get Data.
    $jquery = array( 'jquery' );

    // Font Awesome.
    wp_register_style( 'youzify-icons', YOUZIFY_ADMIN_ASSETS . 'css/all.min.css', array(), YOUZIFY_VERSION );

}

add_action( 'wp_loaded', 'youzify_global_scripts' );

/**
 * Icon Picker.
 */
function youzify_iconpicker_scripts() {

    // Icon Picker.
    wp_enqueue_style( 'youzify-iconpicker', YOUZIFY_ADMIN_ASSETS . 'css/klabs-icon-picker.min.css', array(), YOUZIFY_VERSION );

    // IconPicker Script
    wp_enqueue_script( 'youzify-iconpicker', YOUZIFY_ADMIN_ASSETS .'js/klabs-icon-picker.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );

}