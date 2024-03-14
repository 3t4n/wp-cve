<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.wpgoaltracker.com/
 * @since      1.0.0
 *
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/includes
 * @author     yuvalo <support@wpgoaltracker.com>
 */
class Wp_Goal_Tracker_Ga_i18n
{


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain()
	{

		load_plugin_textdomain(
			'wp-goal-tracker-ga',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}
}
