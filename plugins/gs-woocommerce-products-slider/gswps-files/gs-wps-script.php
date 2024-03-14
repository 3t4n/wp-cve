<?php

function load_gswps_front_script() {
    if (!is_admin()) {
        wp_register_script( 'gswps-owlcarousel-js', GSWPS_FILES_URI . '/assets/js/owl.carousel.min.js', array( 'jquery' ), GSWPS_VERSION, true );
        wp_register_script( 'gswps-custom-js', GSWPS_FILES_URI . '/assets/js/gswps.custom.js', array( 'jquery' ), GSWPS_VERSION, true );
        wp_register_script( 'gswps-modernizr-custom-js', GSWPS_FILES_URI . '/assets/js/modernizr.custom.js', array( 'jquery' ), GSWPS_VERSION, true );

        wp_enqueue_script('gswps-owlcarousel-js');
        wp_enqueue_script('gswps-custom-js');
        wp_enqueue_script('gswps-modernizr-custom-js');
    }
}
add_action( 'wp_enqueue_scripts', 'load_gswps_front_script' );

function load_gswps_front_style() {
    $media = 'all';
    if (!is_admin()) {
        wp_register_style( 'gswps-owlcarousel-style', GSWPS_FILES_URI . '/assets/css/owl.carousel.css', '', GSWPS_VERSION, $media );
        wp_register_style( 'gswps-owltheme-style', GSWPS_FILES_URI . '/assets/css/owl.theme.default.css', '', GSWPS_VERSION, $media );
        wp_register_style( 'gswps-custom-style', GSWPS_FILES_URI . '/assets/css/gswps.custom.css', '', GSWPS_VERSION, $media );
        wp_register_style( 'gswps-component-style', GSWPS_FILES_URI . '/assets/css/gswps.component.css', '', GSWPS_VERSION, $media );
        wp_register_style( 'gswps-bitter-gfont', 'http://fonts.googleapis.com/css?family=Bitter', '', GSWPS_VERSION, $media );
        
        wp_enqueue_style( 'gswps-owlcarousel-style' );
        wp_enqueue_style( 'gswps-owltheme-style' );
        wp_enqueue_style( 'gswps-custom-style' );
        wp_enqueue_style( 'gswps-component-style' );
        wp_enqueue_style( 'gswps-bitter-gfont' );

        
    }
}
add_action( 'init', 'load_gswps_front_style' );

//------------ Include Admin css files-----------------

function gswps_enque_admin_style() {
    $media = 'all';
    //cdn-images.mailchimp.com/embedcode/slim-081711.css

    wp_register_style( 'gswps-mailchimp-style', '//cdn-images.mailchimp.com/embedcode/slim-081711.css', '', GSWPS_VERSION, $media );
    wp_enqueue_style( 'gswps-mailchimp-style' );
    wp_enqueue_style('select2', GSWPS_FILES_URI . '/assets/css/select2.min.css', '', GSWPS_VERSION, $media );

    wp_register_style( 'gswps-modal-style', GSWPS_FILES_URI . '/assets/css/gswps.modal.css', '', GSWPS_VERSION, $media );
    wp_enqueue_style( 'gswps-modal-style' );

    wp_register_style( 'gswps-free-plugins-style', GSWPS_FILES_URI . '/gswps-admin/css/gs_free_plugins.css', '', GSWPS_VERSION, $media );
    wp_enqueue_style( 'gswps-free-plugins-style' );
    wp_enqueue_script('select2', GSWPS_FILES_URI . '/assets/js/select2.min.js', array( 'jquery' ), GSWPS_VERSION, true  );
    wp_enqueue_script('admin_custom', GSWPS_FILES_URI . '/gswps-admin/js/admin_custom.js', array( 'jquery' ), GSWPS_VERSION, true  );
}
add_action( 'admin_enqueue_scripts', 'gswps_enque_admin_style' );