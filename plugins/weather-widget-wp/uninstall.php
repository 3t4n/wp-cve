<?php
/**
 * Delete the plugin options from the
 * wp_options table on uninstall.
 *
 * @since v1.0.0
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit; // User has permission to uninstall.


$options = get_option( 'weather_widget_wp_options', weather_widget_wp_options_default() );
if ( isset( $options['uninstall_data'] ) && $options['uninstall_data'] === "1" ) {
    delete_option( 'weather_widget_wp_options' );
    weather_widget_wp_delete_transients_with_prefix( 'weather_widget_wp_data_' );
}
