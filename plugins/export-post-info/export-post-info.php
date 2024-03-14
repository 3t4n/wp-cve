<?php

/*
Plugin Name: Export Post Info
Plugin URI: https://apasionados.es/
Description: This plugin exports posts Date published, Post title, Word Count, Status, URL and Category to a CSV file
Version: 1.3.0
Author: Apasionados
Author URI: https://apasionados.es/
License: GPL v2 or higher
License URI: License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function apa_epi_f_load_plugin_textdomain() {
    load_plugin_textdomain( 'export-post-info', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'apa_epi_f_load_plugin_textdomain' );

add_filter("plugin_action_links_" . plugin_basename(__FILE__), "apa_epi_f_plugin_actions", 10, 4);
function apa_epi_f_plugin_actions($actions, $plugin_file, $plugin_data, $context) {
    array_unshift(
        $actions, '<a href=' . admin_url( 'options-general.php?page=export-post-info-settings' ) . '>' . __('Settings') . '</a>' . ' | ' . '<a href="https://wordpress.org/support/plugin/export-post-info/" target="_blank">' . __('Support','export-post-info') . '</a>'
    );
    return $actions;
}

function apa_epi_f_nav(){
    add_options_page( 'Export post info', 'Export post info', 'manage_options', 'export-post-info-settings', 'apa_epi_f_include_settings_page' );
	add_action( 'admin_init', 'apa_epi_f_register_settings' );
}
add_action( 'admin_menu', 'apa_epi_f_nav' );

function apa_epi_f_register_settings() {
	register_setting( 'export-post-info-settings-group', 'epi_random_string_filename' );
}


function apa_epi_f_include_settings_page(){

    include(plugin_dir_path(__FILE__) . 'export-post-info-settings.php');

}
/**
 * Do some check on plugin activation
 * @return void
 */
function apa_epi_f_activation() {
	if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		load_plugin_textdomain( 'export-post-info', false,  dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		$plugin_name = $plugin_data['Name'];
		wp_die( '<h1>' . __('Could not activate plugin: PHP version error', 'export-post-info' ) . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('You are using PHP version', 'export-post-info' ) . ' ' . PHP_VERSION . '</strong>. ' . __( 'This plugin has been tested with PHP versions 5.6 and greater.', 'export-post-info' ) . '</p><p>' . __('WordPress itself recommends using PHP version 7.3 or greater', 'export-post-info' ) . ': <a href="https://wordpress.org/about/requirements/" target="_blank">' . __('Official WordPress requirements', 'export-post-info' ) . '</a>' . '. ' . __('Please upgrade your PHP version or contact your Server administrator.', 'export-post-info' ) . '</p>', __('Could not activate plugin: PHP version error', 'export-post-info' ), array( 'back_link' => true ) );

	}
}
register_activation_hook( __FILE__, 'apa_epi_f_activation' );