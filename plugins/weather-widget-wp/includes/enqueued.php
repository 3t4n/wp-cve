<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Plugin Scripts & Styles
 *
 * @since 1.0.0
 *
 **/
function weather_widget_wp_scrips_styles() {
    // Weather Icons CSS
    wp_enqueue_style( 'weather-icons', WEATHER_WIDGET_WP_URL . 'assets/fonts/weather-widget-wp-icons/weather-widget-wp-icons.css', [], WEATHER_WIDGET_WP_VERSION );
    // Plugin CSS
    wp_enqueue_style( 'weather-widget-wp', WEATHER_WIDGET_WP_URL . 'assets/css/main.css', [], WEATHER_WIDGET_WP_VERSION );
}
add_action( 'wp_enqueue_scripts', 'weather_widget_wp_scrips_styles' );


/**
 * Register Custom Blocks
 *
 * @since 1.0.0
 *
 **/
function weather_widget_wp_custom_block_register() {
    register_block_type( WEATHER_WIDGET_WP_PATH . 'build/blocks/weather-widget-wp-block/' );
}
add_action( 'init', 'weather_widget_wp_custom_block_register' );
