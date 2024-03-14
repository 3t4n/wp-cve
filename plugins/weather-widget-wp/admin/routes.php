<?php
/**
 * RESTful routes for the plugin settings
 *
 * @since v1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Create REST routes for our plugin settings page
 *
 * @since v1.0.0
 */
function weather_widget_wp_rest_routes() {
    register_rest_route( 'weather-widget-wp/api', '/settings', [
        'methods'   => WP_REST_Server::READABLE,
        'callback'  => 'weather_widget_wp_get_settings',
        'permission_callback' => 'weather_widget_wp_settings_permission'
    ] );
    register_rest_route( 'weather-widget-wp/api', '/settings', [
        'methods'   => 'POST',
        'callback'  => 'weather_widget_wp_save_settings',
        'permission_callback'   => 'weather_widget_wp_settings_permission'
    ] );
}
add_action( 'rest_api_init', 'weather_widget_wp_rest_routes' );


/**
 * Settings Permission to use the REST API
 *
 * @since v1.0.0
 */
function weather_widget_wp_settings_permission() {
    return current_user_can( 'activate_plugins' );
}


/**
 * Get the plugin settings from the DB
 *
 * @since v1.0.0
 */
function weather_widget_wp_get_settings() {
    $options = get_option( 'weather_widget_wp_options', weather_widget_wp_options_default() );

    $response = [
        'api_key'        => $options['api_key'],
        'caching'        => $options['caching'],
        'uninstall_data' => $options['uninstall_data'],
    ];

    return rest_ensure_response( $response );
}


/**
 * Save the plugin settings to the DB
 *
 * @since v1.0.0
 */
function weather_widget_wp_save_settings( $request ) {
    $settings = [
        'api_key'           => esc_html( $request['api_key'] ),
        'caching'           => esc_html( $request['caching'] ),
        'uninstall_data'    => wp_validate_boolean( $request['uninstall_data'] )
    ];

    update_option( 'weather_widget_wp_options', $settings );

    return rest_ensure_response( 'Plugin settings saved!' );
}
