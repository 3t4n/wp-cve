<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Boomdevs_Swiss_Toolkit
 * @subpackage Boomdevs_Swiss_Toolkit/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_i18n')) {
	class BDSTFW_Swiss_Toolkit_i18n
	{

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain()
		{
			load_plugin_textdomain(
				'swiss-toolkit-for-wp',
				false,
				dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
			);
		}
	}
}
