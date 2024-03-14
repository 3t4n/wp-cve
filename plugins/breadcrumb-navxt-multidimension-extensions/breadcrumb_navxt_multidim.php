<?php
/*
Plugin Name: Breadcrumb NavXT Multidimension Extensions
Plugin URI: https://mtekk.us/extensions/breadcrumb-navxt-multidimension-extensions/
Description: Adds the bcn_display_list_multidim function for Vista like breadcrumb trails. For details on how to use this plugin visit <a href="https://mtekk.us/extensions/breadcrumb-navxt-multidimension-extensions/">Breadcrumb NavXT Multidimension Extensions</a>. 
Version: 2.7.1
Author: John Havlik
Author URI: http://mtekk.us/
Text Domain: breadcrumb-navxt-multidimension-extensions
*/
/*  Copyright 2011-2021  John Havlik  (email : john.havlik@mtekk.us)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once(dirname(__FILE__) . '/includes/block_direct_access.php');
use mtekk\adminKit\{adminKit, form, message, setting};
//Do a PHP version check, require 5.4 or newer
if(version_compare(phpversion(), '5.4.0', '<'))
{
	//Only purpose of this function is to echo out the PHP version error
	function bcn_multidim_ext_phpold()
	{
		printf('<div class="error"><p>' . __('Your PHP version is too old, please upgrade to a newer version. Your version is %1$s, Breadcrumb NavXT requires %2$s', 'breadcrumb-navxt-multidimension-extensions') . '</p></div>', phpversion(), '5.4.0');
	}
	//If we are in the admin, let's print a warning then return
	if(is_admin())
	{
		add_action('admin_notices', 'bcn_multidim_ext_phpold');
	}
	return;
}
//Have to bootstrap our init so that we don't rely on the order of activation
add_action('plugins_loaded', 'bcn_multidim_ext_init', 20);
function bcn_multidim_ext_init()
{
	//If Breadcrumb NavXT isn't active yet, warn the user
	if(!class_exists('breadcrumb_navxt'))
	{
		//Only purpose of this function is to echo out the PHP version error
		function bcn_multidim_ext_nobcn()
		{
			printf('<div class="error"><p>' . __('Breadcrumb NavXT is required for Breadcrumb NavXT Multidimension Extensions to work.', 'breadcrumb-navxt-multidimension-extensions') . '</p></div>');
		}
		//If we are in the admin, let's print a warning then return
		if(is_admin())
		{
			add_action('admin_notices', 'bcn_multidim_ext_nobcn');
		}
		return;
	}
	//If the installed Breadcrumb NavXT is 5.1.1 load current code
	else if(!defined('breadcrumb_navxt::version') || version_compare(breadcrumb_navxt::version, '5.1.0', '<'))
	{
		global $breadcrumb_navxt;
		//If the user's Breadcrumb NavXT version is more than 1 version back alert the user
		if(version_compare($breadcrumb_navxt->get_version(), '5.0.0', '<'))
		{
			//Only purpose of this function is to echo out the Breadcrumb NavXT version error
			function bcn_multidim_ext_old()
			{
				$version = __('unknown', 'breadcrumb-navxt');
				//While not usefull today, in the future this will be hit
				if(defined('breadcrumb_navxt::version'))
				{
					$version = breadcrumb_navxt::version;
				}
				//Most will see this one
				else if(class_exists('breadcrumb_navxt'))
				{
					global $breadcrumb_navxt;
					$version = $breadcrumb_navxt->get_version();
				}
				printf('<div class="error"><p>' . __('Your Breadcrumb NavXT version is too old, please upgrade to a newer version. Your version is %1$s, Breadcrumb NavXT Multidimension Extensions requires %2$s', 'breadcrumb-navxt-multidimension-extensions') . '</p></div>', $version, '5.1.0');
			}
			//If we are in the admin, let's print a warning then return
			if(is_admin())
			{
				add_action('admin_notices', 'bcn_multidim_ext_old');
			}
			return;
		}
		//If they are on 5.1.0, load the leagacy multidim class
		else if(!class_exists('bcn_breadcrumb_trail_multidim'))
		{
			require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim_legacy.php');
		}
	}
	//If the installed Breadcrumb NavXT is < 6.0 load BCN5 code
	else if(!defined('breadcrumb_navxt::version') || version_compare(breadcrumb_navxt::version, '5.9.60', '<'))
	{
		require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim_5.php');
		require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim_children_5.php');
	}
	//If the installed Breadcrumb NavXT is pre 6.4 but post 6.0
	else if(!defined('breadcrumb_navxt::version') || version_compare(breadcrumb_navxt::version, '6.3.60', '<'))
	{
		require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim_6.php');
		require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim_children_6.php');
	}
	//If the installed Breadcrumb NavXT is pre 7.0 but post 6.4
	else if(!defined('breadcrumb_navxt::version') || version_compare(breadcrumb_navxt::version, '6.9.60', '<'))
	{
		require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim_6_4.php');
		require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim_children_6_4.php');
	}
	//Otherwise we can now include our extended breadcrumb trail for 7.0.0+
	else if(!class_exists('bcn_breadcrumb_trail_multidim'))
	{
		require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim.php');
		require_once(dirname(__FILE__) . '/class.bcn_breadcrumb_trail_multidim_children.php');
	}
}
add_filter('bcn_settings_init', 'bcn_multidim_ext_settings_setup');
/**
 * Adds in default settings needed for Breadcrumb NavXT Multidimension Extensions
 * 
 * @param array $settings The settings array
 * @return array The filtered/updated settings array
 */
function bcn_multidim_ext_settings_setup($settings)
{
	//BCN 7.0 compat
	if(class_exists('\mtekk\adminKit\setting\setting_bool'))
	{
		if(!isset($settings['bhome_display_children']))
		{
			$settings['bhome_display_children'] = new setting\setting_bool(
					'home_display_children',
					false,
					__('Home Breadcrumb', 'breadcrumb-navxt-multidimension-extensions'));
		}
	}
	//Legacy compat
	else
	{
		if(!isset($settings['bhome_display_children']))
		{
			//Add our 'default' use_menu option
			$settings['bhome_display_children'] = false;
		}
	}
	return $settings; 
}
add_action('bcn_widget_display_types', 'bcn_multidim_ext_widget_types', 10);
/**
 * Adds the two multidimension types to the types option in the Breadcrumb NavXT widget
 * 
 * @param array $instance The settings array instance for this Widget
 */
function bcn_multidim_ext_widget_types($instance)
{
	?>
	<option value="multidim" <?php selected('multidim', $instance['type']);?>><?php _e('Multidimensional (siblings in 2nd dimension)', 'breadcrumb-navxt-multidimension-extensions'); ?></option>
	<option value="multidim_child" <?php selected('multidim_child', $instance['type']);?>><?php _e('Multidimensional (children in 2nd dimension)', 'breadcrumb-navxt-multidimension-extensions'); ?></option>
	<?php
}
add_action('bcn_widget_display_trail', 'bcn_multidim_ext_widget_display', 10);
/**
 * Checks and displays the proper breadcrumb trail type, if applicable
 * 
 * @param array $instance The settings array instance for this Widget
 */
function bcn_multidim_ext_widget_display($instance)
{
	if($instance['type'] == 'multidim')
	{
		//Display the multidimensional list output breadcrumb
		echo $instance['pretext'] . '<ol class="breadcrumb_trail breadcrumbs">';
		bcn_display_list_multidim(false, $instance['linked'], $instance['reverse'], $instance['force']);
		echo '</ol>';
	}
	else if($instance['type'] == 'multidim_child')
	{
		//Display the multidimensional list output breadcrumb
		echo $instance['pretext'] . '<ol class="breadcrumb_trail breadcrumbs">';
		bcn_display_list_multidim_children(false, $instance['linked'], $instance['reverse'], $instance['force']);
		echo '</ol>';
	}
}
add_action('plugins_loaded', 'bcn_multidim_ext_admin_init', 16);
function bcn_multidim_ext_admin_init()
{
	//If this is the admin, should load the admin settings update code
	if(is_admin() && (class_exists('mtekk_adminKit') || class_exists('mtekk\adminKit\adminKit')))
	{
		//Check to see if someone else has setup the extensions settings tab
		if(has_action('bcn_after_settings_tabs', 'bcn_extensions_tab') === false)
		{
			//All versions prior to 6.3.0 used a different extensions tab format
			if(!defined('breadcrumb_navxt::version') || version_compare(breadcrumb_navxt::version, '6.2.60', '<'))
			{
				require_once(dirname(__FILE__) . '/includes/bcn_extensions_tab_62.php');
			}
			else
			{
				require_once(dirname(__FILE__) . '/includes/bcn_extensions_tab.php');
			}
			add_action('bcn_after_settings_tabs', 'bcn_extensions_tab');
		}
		//Breadcrumb NavXT 7.0 compat
		if(class_exists('mtekk\adminKit\adminKit'))
		{
			require_once(dirname(__FILE__) . '/class.bcn_multidim_admin.php');
		}
		else
		{
			require_once(dirname(__FILE__) . '/class.bcn_multidim_admin_6.php');
		}
		$bcn_multidim_admin = new bcn_multidim_admin(plugin_basename(__FILE__));
	}
}
$bcn_mutidim_settings_global = array();
//We want to run the settings extracter late
add_filter('bcn_settings_init', 'bcn_grab_settings', 99);
//This functions to grab the default settings from within breadcrumb navxt. It is sort of ugly, but would be less so if this extension was in a class
function bcn_grab_settings($settings)
{
	global $bcn_mutidim_settings_global;
	$bcn_mutidim_settings_global = $settings;
	return $settings;
}
/**
 * Outputs the breadcrumb trail in a list with the sibling pages/terms of the breadcrumb in its second dimension
 * 
 * @param bool $return Whether to return or echo the trail.
 * @param bool $linked Whether to allow hyperlinks in the trail or not.
 * @param bool $reverse Whether to reverse the output or not.
 * @param bool $force Whether or not to force the fill function to run. (optional)
*/
function bcn_display_list_multidim($return = false, $linked = true, $reverse = false, $force = false)
{
	//Make new instance of the ext_breadcrumb_trail object
	$breadcrumb_trail = new bcn_breadcrumb_trail_multidim();
	//Initial setup of options
	if(class_exists('mtekk\adminKit\adminKit'))
	{
		$settings = array();
		//7.0
		if(version_compare(breadcrumb_navxt::version, '7.0.1', '<'))
		{
			$settings = $GLOBALS['bcn_mutidim_settings_global'];
		}
		//7.0.1
		else
		{
			breadcrumb_navxt::setup_setting_defaults($settings);
		}
		$breadcrumb_trail->opt = adminKit::settings_to_opts($settings);
	}
	else
	{
		breadcrumb_navxt::setup_options($breadcrumb_trail->opt);
	}
	//Merge in options from the database
	$breadcrumb_trail->opt = wp_parse_args(get_option('bcn_options'), $breadcrumb_trail->opt);
	//If we're being forced to fill the trail, clear it before calling fill
	if($force)
	{
		$breadcrumb_trail->breadcrumbs = array();
	}
	//Fill the breadcrumb trail
	$breadcrumb_trail->fill();
	//Display the trail
	return $breadcrumb_trail->display_list($return, $linked, $reverse);
}
/**
 * Outputs the breadcrumb trail in a list with the child pages/terms of the breadcrumb in its second dimension
 * 
 * @param bool $return Whether to return or echo the trail.
 * @param bool $linked Whether to allow hyperlinks in the trail or not.
 * @param bool $reverse Whether to reverse the output or not.
 * @param bool $force Whether or not to force the fill function to run. (optional)
*/
function bcn_display_list_multidim_children($return = false, $linked = true, $reverse = false, $force = false)
{
	if(!class_exists('bcn_breadcrumb_trail_multidim_children'))
	{
		_doing_it_wrong(__FUNCTION__, __('Breadcrumb NavXT 5.1.1 or newer is required for the latest features', 'breadcrumb-navxt-multidimension-extensions'), '1.9.0');
		return;
	}
	//Make new instance of the ext_breadcrumb_trail object
	$breadcrumb_trail = new bcn_breadcrumb_trail_multidim_children();
	//Initial setup of options
	if(class_exists('mtekk\adminKit\adminKit'))
	{
		$settings = array();
		//7.0
		if(version_compare(breadcrumb_navxt::version, '7.0.1', '<'))
		{
			$settings = $GLOBALS['bcn_mutidim_settings_global'];
		}
		//7.0.1
		else
		{
			breadcrumb_navxt::setup_setting_defaults($settings);
		}
		$breadcrumb_trail->opt = adminKit::settings_to_opts($settings);
	}
	else
	{
		breadcrumb_navxt::setup_options($breadcrumb_trail->opt);
	}
	//Merge in options from the database
	$breadcrumb_trail->opt = wp_parse_args(get_option('bcn_options'), $breadcrumb_trail->opt);
	//If we're being forced to fill the trail, clear it before calling fill
	if($force)
	{
		$breadcrumb_trail->breadcrumbs = array();
	}
	//Fill the breadcrumb trail
	$breadcrumb_trail->fill();
	//Display the trail
	return $breadcrumb_trail->display_list($return, $linked, $reverse);
}