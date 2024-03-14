<?php
/**
 * The deactivator class
 *
 * @package    Rock_Convert\Inc\Core
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Core;

/**
 * Fired during plugin deactivation
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */
class Deactivator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Get the timestamp for the next event.
		$timestamp = wp_next_scheduled( 'rock_convert_license_check_event' );

		$original_args = array();
	}

}
