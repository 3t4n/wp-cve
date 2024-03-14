<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://clickship.com
 * @since      1.0.0
 *
 * @package    Clickship
 * @subpackage Clickship/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Clickship
 * @subpackage Clickship/includes
 * @author     ClickShip <info@clickship.com>
 */
class Clickship_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

}