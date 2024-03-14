<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://boomdevs.com
 * @since      1.0.0
 *
 * @package    Wp_Bnav
 * @subpackage Wp_Bnav/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Bnav
 * @subpackage Wp_Bnav/includes
 * @author     BOOM DEVS <contact@boomdevs.com>
 */
class Wp_Bnav_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        unregister_nav_menu('bnav_bottom_nav');
	}

}
