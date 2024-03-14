<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Boomdevs_Swiss_Toolkit
 * @subpackage Boomdevs_Swiss_Toolkit/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Deactivator')) {
	class BDSTFW_Swiss_Toolkit_Deactivator
	{

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function deactivate()
		{
		}
	}
}
