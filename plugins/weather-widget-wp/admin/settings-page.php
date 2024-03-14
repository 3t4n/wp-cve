<?php
/**
 * Plugin Settings Page
 *
 * @since v1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Register Plugin Admin Menu
 *
 * @since v1.0.0
 */
function weather_widget_wp_top_admin_menu() {

    add_menu_page(
        'Weather Widget WP | Settings',
        'Weather Widget',
        'manage_options',
        'weather-widget-wp',
        'weather_widget_wp_settings_page',
        'dashicons-cloud',
        null
    );
}
add_action( 'admin_menu', 'weather_widget_wp_top_admin_menu' );


/**
 * Enqueue Admin Script & Styles
 *
 * @since v1.0.0
 */
function weather_widget_wp_admin_scrips_styles() {
    // Weather Icons CSS
    wp_enqueue_style( 'weather-icons', WEATHER_WIDGET_WP_URL . 'assets/fonts/weather-widget-wp-icons/weather-widget-wp-icons.css', [], WEATHER_WIDGET_WP_VERSION );

    // Settings Page CSS
    wp_enqueue_style( 'weather-widget-wp-settings-page', WEATHER_WIDGET_WP_URL . 'build/admin/index.css', [], WEATHER_WIDGET_WP_VERSION );
    // Settings Page JS
    wp_enqueue_script( 'weather-widget-wp-settings-page', WEATHER_WIDGET_WP_URL . 'build/admin/index.js', [ 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n' ], WEATHER_WIDGET_WP_VERSION, true );
    wp_localize_script( 'weather-widget-wp-settings-page', 'weatherWidgetWpObject', [
        'apiUrl' => get_rest_url(),
        'nonce' => wp_create_nonce( 'wp_rest' )
    ] );
}
add_action( 'admin_enqueue_scripts', 'weather_widget_wp_admin_scrips_styles' );


/**
 * Set script translation WP init
 *
 * @since v1.0.0
 */
function weather_widget_wp_script_translations() {
    wp_set_script_translations( 'weather-widget-wp-settings-page', 'weather-widget-wp' );
}
add_action( 'init', 'weather_widget_wp_script_translations' );


/**
 * Init settings page
 *
 * @since v1.0.0
 */
function weather_widget_wp_settings_page() {

    printf( '<div id="weather-widget-wp-settings-page"><h2>%s</h2></div>', esc_html__( 'Loading...', 'weather-widget-wp' ) );

}


/**
 * Plugin settings - defaults
 *
 * @since v1.0.0
 */
function weather_widget_wp_options_default() {

    return array(
        'api_key'           => 'Enter the open weather api key here',
        'caching'           => '4',
        'uninstall_data'    => true,
    );
}


/**
 * Admin imports
 *
 * @since v1.0.0
 */
require_once WEATHER_WIDGET_WP_PATH . 'admin/routes.php';
