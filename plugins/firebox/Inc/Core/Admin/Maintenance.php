<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Admin;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Maintenance
{
	/**
	 * Hook data
	 * 
	 * @var  array
	 */
	private static $hook_data;

	public function __construct($hook_data)
	{
		self::$hook_data = $hook_data;
	}
	
	/**
	 * Runs maintenance.
	 * 
	 * @return  void
	 */
	public function init()
	{
		register_activation_hook(FBOX_PLUGIN_BASE_FILE, [__CLASS__, 'activation']);
		register_uninstall_hook(FBOX_PLUGIN_BASE_FILE, [__CLASS__, 'uninstall']);
	}

	/**
	 * Runs on plugin activation
	 * 
	 * @return  void
	 */
	public static function activation()
	{
		$pluginActivation = new PluginActivation(self::$hook_data);
		$pluginActivation->start();
	}
	
	/**
	 * Runs on plugin deactivation
	 * 
	 * @return  void
	 */
	public static function uninstall()
	{
		$pluginUninstall = new PluginUninstall(self::$hook_data);
		$pluginUninstall->start();
	}
}