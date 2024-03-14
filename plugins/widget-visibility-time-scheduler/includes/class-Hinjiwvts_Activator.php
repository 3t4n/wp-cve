<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wordpress.org/plugins/widget-visibility-time-scheduler
 * @since      1.0.0
 *
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/includes
 * @author     Kybernetik Services <wordpress@kybernetik.com.de>
 */
class Hinjiwvts_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// store the flag into the db to trigger the display of a message after activation
		set_transient( 'hinjiwvts', '1', 60 );
	}

}
