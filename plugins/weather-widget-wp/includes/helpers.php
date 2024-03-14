<?php
/**
 * Utility functions, filters and helpers.
 *
 **/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Get all transient keys from the db by a prefix.
 *
 * @since v1.0.0
 */
function weather_widget_wp_get_transient_keys_by_prefix( $prefix ) {

    global $wpdb;

    $prefix = $wpdb->esc_like( '_transient_' . $prefix );
	$sql    = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE '%s'";
	$keys   = $wpdb->get_results( $wpdb->prepare( $sql, $prefix . '%' ), ARRAY_A );

    if ( is_wp_error( $keys ) ) {
        return [];
    }

    return array_map( function( $key ) {
		// Remove '_transient_' from the option name.
		return ltrim( $key['option_name'], '_transient_' );
	}, $keys );
}


/**
 * Delete all transients from the db with a specific prefix.
 *
 * @since v1.0.0
 */
function weather_widget_wp_delete_transients_with_prefix( $prefix ) {
	foreach ( weather_widget_wp_get_transient_keys_by_prefix( $prefix ) as $key ) {
		delete_transient( $key );
	}
}


/**
 * Delete all plugin transients on plugin deactivation.
 *
 * @since v1.0.0
 */
function weather_widget_wp_data_on_deactivation() {
    if ( ! current_user_can( 'activate_plugins' ) ) return;

    weather_widget_wp_delete_transients_with_prefix( 'weather_widget_wp_data_' );
}
register_deactivation_hook( __FILE__, 'weather_widget_wp_data_on_deactivation' );
