<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class SearchDropdownHelper
{
	/**
	 * The total items to display on the dropdown
	 * 
	 * @var  integer
	 */
	const SELECTION_ITEMS = 8;
	
	/**
	 * Parses the given items via the class identified by the $type
	 * 
	 * @param   string  $path
	 * @param   array   $items
	 * 
	 * @return  array
	 */
	public static function parseData($path, $items)
	{
		if (!method_exists($path, 'parseData'))
		{
			return SearchDropdownBaseHelper::parseData($items);
		}
		
		return $path::parseData($items);
	}

	/**
	 * Returns the selected items in a format that the Search Dropdown field can display.
	 * 
	 * @param   string  $path
	 * @param   array   $needle
	 * @param   array   $haystack
	 * 
	 * @return  array
	 */
	public static function getSelectedItems($path, $needle, $haystack = [])
	{
		if (empty($needle) || empty($path))
		{
			return [];
		}

		if (!$helper_class = self::getHelperClass($path))
		{
			return [];
		}

		if (!method_exists($helper_class, 'getSelectedItems'))
		{
			return [];
		}
		
		// return the selected items from the helper class
		if ($haystack)
		{
			return $helper_class->getSelectedItems($needle, $haystack);
		}

		return $helper_class->getSelectedItems($needle);
	}

	/**
	 * Returns the helper class from the path's plugin.
	 * i.e. if the path is in framework, we search the framework's helper middleware
	 * to find the helper class. if it's in a plugin, we search the plugin's helper middleware.
	 * 
	 * @param   string  $path
	 * 
	 * @return  mixed
	 */
	public static function getHelperClass($path)
	{
		if (empty($path))
		{
			return [];
		}

		// get helper class data
		if (!$helper_class_data = self::getHelperClassData($path))
		{
			return [];
		}

		// ensure plugin(framework or plugin) function exists
		if (!function_exists($helper_class_data['plugin']))
		{
			return [];
		}

		$plugin = $helper_class_data['plugin']();
		
		// ensure plugin contains helper property
		if (!property_exists($plugin, 'helper'))
		{
			return [];
		}
		
		// get helper class name to call
		$helper_class_name = strtolower($helper_class_data['class_name']);

		// ensure the plugin's helper class contains the helper class name we need
		if (!property_exists($plugin->helper, $helper_class_name))
		{
			return [];
		}
		
		// return the selected items from the helper class
		return $plugin->helper->$helper_class_name;
	}

	/**
	 * Returns the class data
	 * 
	 * @param   string   $type
	 * 
	 * @return  array
	 */
	public static function getHelperClassData($path)
	{
		if (!is_string($path))
		{
			return false;
		}
		
		if (empty($path))
		{
			return false;
		}

		// helper class in the dependent plugin
		// retrieve the name of the plugin from the given namespace
		$path_data = explode('\\', $path);
		$path_data = array_filter($path_data);
		$path_data = array_values($path_data);
		
		// get plugin (fpframework or plugin name);
		$plugin = ltrim($path_data[0], '\\');
		$plugin = strtolower($plugin);

		// get class name
		$class_name = strtolower(end($path_data));
		$class_name = str_replace('helper', '', $class_name);

		return [
			'plugin' => $plugin,
			'class_name' => $class_name
		];
	}
}