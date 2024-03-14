<?php 

// Load JS and CSS files on Backend
add_action('admin_enqueue_scripts', 'WPLFCF7_load_admin_script_style');
function WPLFCF7_load_admin_script_style() {
    wp_enqueue_style( 'wplfcf7-back-css', WPLFCF7_PLUGIN_DIR . '/assets/css/back.css', false, '1.0.0' );
    wp_enqueue_script( 'wplfcf7-back-js', WPLFCF7_PLUGIN_DIR . '/assets/js/back.js', false, '1.0.0', true );
}

// Load JS and CSS files on Frontend
add_action( 'wp_enqueue_scripts', 'WPLFCF7_load_frontend_script_style' );
function WPLFCF7_load_frontend_script_style() {
    wp_enqueue_script( 'wplfcf7-front-js', WPLFCF7_PLUGIN_DIR . '/assets/js/front.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'wplfcf7-select2-js', WPLFCF7_PLUGIN_DIR . '/assets/js/select2.js', false, '1.0.0', true );
    wp_enqueue_style( 'wplfcf7-select2-css', WPLFCF7_PLUGIN_DIR . '/assets/css/select2.css', false, '1.0.0' );
    wp_enqueue_style( 'wplfcf7-front-css', WPLFCF7_PLUGIN_DIR . '/assets/css/front.css', false, '1.0.0' );
}
