<?php
/**
 * @package         FireBox
 * @version         2.1.8
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Settings
{
	/**
	 * Finds the option within the plugin settings
	 * 
	 * @param   string  $option
	 * 
	 * @return  mixed
	 */
	public static function findSettingsOption($option)
	{
		if (!$option && !is_string($option))
		{
			return false;
		}

		$settings = get_option('firebox_settings');

		if (!isset($settings[$option]))
		{
			return false;
		}

		return $settings[$option];
	}
}