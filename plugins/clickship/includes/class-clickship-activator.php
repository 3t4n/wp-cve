<?php
/**
 * Fired during plugin activation
 *
 * @link       https://clickship.com
 * @since      1.0.0
 *
 * @package    Clickship
 * @subpackage Clickship/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Clickship
 * @subpackage Clickship/includes
 * @author     ClickShip <info@clickship.com>
 */
class Clickship_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();
	}

}