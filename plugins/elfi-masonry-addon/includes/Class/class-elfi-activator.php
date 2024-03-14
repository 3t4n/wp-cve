<?php

/**
 * Fired during plugin activation
 *
 * @link       https://sharabindu.com
 * @since      1.4.0
 *
 * @package    Elfi Masonry Addon
 * @subpackage Elfi Masonry Addon/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.4.0
 * @package    Elfi Masonry Addon
 * @subpackage Elfi Masonry Addon/includes
 * @author     BakshiWp <sharabindu.bakshi@gmail.com>
 */
class elfi_light_activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.4.0
	 */
	public static function elfi_light_activate() {
		
		flush_rewrite_rules();
	}

}
