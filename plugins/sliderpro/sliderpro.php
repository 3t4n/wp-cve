<?php

/*
	Plugin Name: SliderPro
	Plugin URI:  https://bqworks.net/slider-pro/
	Description: Elegant and professional sliders.
	Version:     4.8.8
	Author:      bqworks
	Author URI:  https://bqworks.net
*/

// if the file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die();
}

define( 'SLIDERPRO_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once( SLIDERPRO_DIR_PATH . 'public/class-sliderpro.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-slider-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-slide-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-slide-renderer-factory.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-dynamic-slide-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-posts-slide-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-gallery-slide-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-flickr-slide-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-layer-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-layer-renderer-factory.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-paragraph-layer-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-heading-layer-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-image-layer-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-div-layer-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-video-layer-renderer.php' );
require_once( SLIDERPRO_DIR_PATH . 'public/class-lightbox-slider.php' );

require_once( SLIDERPRO_DIR_PATH . 'includes/class-sliderpro-activation.php' );
require_once( SLIDERPRO_DIR_PATH . 'includes/class-sliderpro-widget.php' );
require_once( SLIDERPRO_DIR_PATH . 'includes/class-sliderpro-settings.php' );
require_once( SLIDERPRO_DIR_PATH . 'includes/class-sliderpro-validation.php' );
require_once( SLIDERPRO_DIR_PATH . 'includes/class-flickr.php' );
require_once( SLIDERPRO_DIR_PATH . 'includes/class-hideable-gallery.php' );

register_activation_hook( __FILE__, array( 'BQW_SliderPro_Activation', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BQW_SliderPro_Activation', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'BQW_SliderPro', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'BQW_SliderPro_Activation', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'BQW_Hideable_Gallery', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'BQW_SP_Lightbox_Slider', 'get_instance' ) );

// register the widget
add_action( 'widgets_init', 'bqw_sp_register_widget' );

// Gutenberg block
require_once( SLIDERPRO_DIR_PATH . 'gutenberg/class-sliderpro-block.php' );
add_action( 'plugins_loaded', array( 'BQW_SliderPro_Block', 'get_instance' ) );

if ( is_admin() ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
	require_once( SLIDERPRO_DIR_PATH . 'admin/class-sliderpro-admin.php' );
	require_once( SLIDERPRO_DIR_PATH . 'admin/class-sliderpro-add-ons.php' );
	require_once( SLIDERPRO_DIR_PATH . 'admin/class-sliderpro-updates.php' );
	add_action( 'plugins_loaded', array( 'BQW_SliderPro_Admin', 'get_instance' ) );
	add_action( 'plugins_loaded', array( 'BQW_SliderPro_Add_Ons', 'get_instance' ) );
	add_action( 'admin_init', array( 'BQW_SliderPro_Updates', 'get_instance' ) );
}