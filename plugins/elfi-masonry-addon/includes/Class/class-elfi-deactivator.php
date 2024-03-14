<?php


/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.4.0
 * @package    Elfi Masonry Addon
 * @author     BakshiWp <sharabindu.bakshi@gmail.com>
 */
class Elfi_Light_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.4.0
	 */
	public static function elfi_light_deactivate() {
		
		flush_rewrite_rules();
	}

}
