<?php
/*!
 * Plugin Name: Nav Menu Collapse
 * Plugin URI:  https://wordpress.org/plugins/nav-menu-collapse/
 * Description: Adds functionality to collapse and expand nav menus within the WordPress admin.
 * Version:     2.1.3
 * Author:      Robert Noakes
 * Author URI:  https://robertnoakes.com/
 * Text Domain: nav-menu-collapse
 * Domain Path: /languages/
 * Copyright:   (c) 2018-2023 Robert Noakes (mr@robertnoakes.com)
 * License:     GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */
 
/**
 * Main plugin file.
 * 
 * @since 2.1.2 Removed PHP_INT_MAX fallback.
 * @since 2.1.0 Added fallback for PHP_INT_MAX.
 * @since 2.0.0
 * 
 * @package Nav Menu Collapse
 */
 
if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Setup autoloading for plugin classes.
 *
 * @since 2.0.1 Improved conditions.
 * @since 2.0.0
 */
spl_autoload_register(function ($class)
{
	$base_class = 'Nav_Menu_Collapse';

	if (strpos($class, $base_class) === 0)
	{
		$includes_path = dirname(__FILE__) . '/includes/';
		$core_path = $includes_path . 'core/class-';
		$static_path = $includes_path . 'static/class-';
		$standalone_path = $includes_path . 'standalone/class-';
		$fields_path = $includes_path . 'fields/class-';
		$plugins_path = $includes_path . 'plugins/class-';

		$file_name = ($class === $base_class)
		? 'base'
		: strtolower(str_replace(array($base_class . '_', '_'), array('', '-'), $class));

		$file_name .= '.php';

		if (file_exists($core_path . $file_name))
		{
			require_once($core_path . $file_name);
		}
		else if (file_exists($static_path . $file_name))
		{
			require_once($static_path . $file_name);
		}
		else if (file_exists($standalone_path . $file_name))
		{
			require_once($standalone_path . $file_name);
		}
		else if (file_exists($fields_path . $file_name))
		{
			require_once($fields_path . $file_name);
		}
		else if (file_exists($plugins_path . $file_name))
		{
			require_once($plugins_path . $file_name);
		}
	}
	else if ($class === 'WP_Screen')
	{
		require_once(ABSPATH . 'wp-admin/includes/class-wp-screen.php');
	}
});

/**
 * Returns the main instance of Nav_Menu_Collapse.
 *
 * @since 2.0.0
 *
 * @param  string          $file Optional main plugin file name.
 * @return Nav_Menu_Collapse       Main Nav_Menu_Collapse instance.
 */
function Nav_Menu_Collapse($file = '')
{
	return Nav_Menu_Collapse::_get_instance($file);
}

Nav_Menu_Collapse(__FILE__);
