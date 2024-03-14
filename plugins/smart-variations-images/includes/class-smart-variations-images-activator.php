<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.rosendo.pt
 * @since      1.0.0
 *
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/includes
 * @author     David Rosendo <david@rosendo.pt>
 */
class Smart_Variations_Images_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		// Check fro PRO SVI v3 and Deactivate it.
		if (is_plugin_active('smart-variations-images-pro/svipro.php')) {
			deactivate_plugins('smart-variations-images-pro/svipro.php');
		}
	}
}