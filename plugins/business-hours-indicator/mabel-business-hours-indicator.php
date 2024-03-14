<?php
/*
 * Plugin Name: Business Hours Indicator
 * Plugin URI: https://www.studiowombat.com/plugin/business-hours-indicator//?utm_source=bhifree&utm_medium=plugin&utm_campaign=plugins
 * Description: Add an indication of being currently open or closed. Show business hour tables. Conditional logic: show/hide content only when open/closed.
 * Version: 2.4
 * Author: StudioWombat
 * Author URI: https://www.studiowombat.com/?utm_source=bhifree&utm_medium=plugin&utm_campaign=plugins
 * Text Domain: business-hours-indicator
*/

if(!defined('ABSPATH')){die;}

/**
 * Auto loader for Plugin classes
 *
 * @param string $class_name Name of the class that shall be loaded
 */
function mabel_bhi_lite_auto_loader ($class_name) {

	// Not loading a class from our plugin.
	if ( !is_int(strpos( $class_name, 'MABEL_BHI_LITE')) )
		return;

	// Remove root namespace as we don't have that as a folder.
	$class_name = str_replace('MABEL_BHI_LITE\\','',$class_name);
	$class_name = str_replace('\\','/',strtolower($class_name)) .'.php';
	// Get only the file name.
	$pos =  strrpos($class_name, '/');
	$file_name = is_int($pos) ? substr($class_name, $pos + 1) : $class_name;
	// Get only the path.
	$path = str_replace($file_name,'',$class_name);
	// Append 'class-' to the file name and replace _ with -
	$new_file_name = 'class-'.str_replace('_','-',$file_name);
	// Construct file path.
	$file_path = plugin_dir_path(__FILE__)  . str_replace('\\', DIRECTORY_SEPARATOR, $path . strtolower($new_file_name));

	if (file_exists($file_path))
		require_once($file_path);
}

spl_autoload_register('mabel_bhi_lite_auto_loader');

// API
if(!function_exists('MBHILITE'))
{
	function MBHILITE() {
		return \MABEL_BHI_LITE\API\API::instance();
	}
}

function run_mabel_bhi_lite() {
	// todo, this can probably go? Not sure why I left it here ^^
	if (!defined('MABEL_BHI_LITE_SETTINGS'))
		define('MABEL_BHI_LITE_SETTINGS', 'mb-bhi-settings');
	$plugin = new \MABEL_BHI_LITE\Business_Hours_Indicator(
		plugin_dir_path( __FILE__ ),
		plugin_dir_url( __FILE__ ),
		plugin_basename( __FILE__ ),
		'Business Hours Indicator',
		'2.4'
	);

	$plugin->run();
}

run_mabel_bhi_lite();