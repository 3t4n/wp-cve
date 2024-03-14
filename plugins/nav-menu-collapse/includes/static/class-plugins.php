<?php
/*!
 * Plugin plugins functions.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage Plugins
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement plugins functions.
 *
 * @since 2.0.0
 */
final class Nav_Menu_Collapse_Plugins
{
	/**
	 * Check to see if a plugin is active and matches a version comparison.
	 * 
	 * @since 2.0.0
	 * 
	 * @access public static
	 * @param  string $base_name Base name to the plugin to get the data for.
	 * @param  string $version   Version to check for the plugin.
	 * @param  string $compare   Comparison operator for the 'version_compare' function.
	 * @return string            Plugin data if it's found.
	 */
	public static function check_version($base_name, $version, $compare)
	{
		$data = (self::is_active($base_name))
		? self::get_data($base_name)
		: '';
		
		return
		(
			!empty($data)
			&&
			version_compare($data['Version'], $version, $compare)
		);
	}
	
	/**
	 * Get the data for a plugin.
	 * 
	 * @since 2.0.0
	 * 
	 * @access public static
	 * @param  string $base_name Base name to the plugin to get the data for.
	 * @return string            Plugin data if it's found.
	 */
	public static function get_data($base_name)
	{
		self::_load();

		return (empty($base_name))
		? ''
		: get_plugin_data(WP_PLUGIN_DIR . '/' . $base_name);
	}
	
	/**
	 * Check to see if a plugin is active.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string  $base_name Base name for the plugin to check.
	 * @return boolean            True if the plugin is active.
	 */
	public static function is_active($base_name)
	{
		self::_load();

		return
		(
			file_exists(WP_PLUGIN_DIR . '/' . $base_name)
			&&
			is_plugin_active($base_name)
		);
	}
	
	/**
	 * Load plugin functionality if necessary.
	 *
	 * @since 2.0.0
	 *
	 * @access private static
	 * @return void
	 */
	private static function _load()
	{
		if (!function_exists('is_plugin_active'))
		{
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
	}
}
