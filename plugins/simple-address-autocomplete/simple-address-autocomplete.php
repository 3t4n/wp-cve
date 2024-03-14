<?php

/**

 * @wordpress-plugin
 * Plugin Name:       Simple Address Autocomplete
 * Plugin URI:        https://saa.khadim.nz
 * Description:       A simple way to add Google address autocomplete functionality to any form field.
 * Version:           1.2.1
 * Author:            Raza Khadim
 * Author URI:        https://khadim.nz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-address-autocomplete
 * Domain Path:       /languages
 * 
 * 
 * @link              https://saa.khadim.nz
 * @since             1.0.0
 * @package           Simple_Address_Autocomplete
 * 
 */

if (!defined('WPINC')) {
	die;
}

/**
 * Current plugin version.
 */
define('SIMPLE_ADDRESS_AUTOCOMPLETE_VERSION', '1.2.1');

function simple_address_autocomplete_enqueue_google_maps_api_key()
{
	wp_enqueue_script('simple_address_autocomplete_google_maps_api', 'https://maps.googleapis.com/maps/api/js?key=' . get_option('simple_aa_options_google_maps_api_key') . '&libraries=places');
}

add_action('wp_enqueue_scripts', 'simple_address_autocomplete_enqueue_google_maps_api_key', 10);

function activate_simple_address_autocomplete()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-simple-address-autocomplete-activator.php';
	Simple_Address_Autocomplete_Activator::activate();
}


function deactivate_simple_address_autocomplete()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-simple-address-autocomplete-deactivator.php';
	Simple_Address_Autocomplete_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_simple_address_autocomplete');
register_deactivation_hook(__FILE__, 'deactivate_simple_address_autocomplete');


require plugin_dir_path(__FILE__) . 'includes/class-simple-address-autocomplete.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simple_address_autocomplete()
{

	$plugin = new Simple_Address_Autocomplete();
	$plugin->run();
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'simple_address_autocomplete_docs_link');

function simple_address_autocomplete_docs_link($url)
{
	$url[] = '<a target="_blank" href="https://wordpress.org/support/plugin/simple-address-autocomplete/"> Support </a>';
	$url[] = '<a href="options-general.php?page=simple_autocomplete"> Settings </a>';
	$url[] = '<a href="https://www.buymeacoffee.com/razakhadim"> Buy me a coffee </a>';

	return $url;
}

run_simple_address_autocomplete();
