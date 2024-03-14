<?php

/**
 * Fired during plugin activation
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/includes
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Force_Reinstall_Activator
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
		if (boolval(get_transient(force_reinstall_name() . "-rate")) === false) {
			set_transient(force_reinstall_name() . "-rate", true, defined("MONTH_IN_SECONDS") ? MONTH_IN_SECONDS * 3 : YEAR_IN_SECONDS / 4);
		}
	}
}