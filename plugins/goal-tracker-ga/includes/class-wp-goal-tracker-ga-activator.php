<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.wpgoaltracker.com/
 * @since      1.0.0
 *
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/includes
 * @author     yuvalo <support@wpgoaltracker.com>
 */
class Wp_Goal_Tracker_Ga_Activator
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
		update_option('gtga_activation_timestamp', time());
	}
}