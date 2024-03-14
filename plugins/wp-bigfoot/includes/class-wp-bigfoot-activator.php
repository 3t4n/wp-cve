<?php

/**
 * Fired during plugin activation
 *
 * @link       zemartino.com
 * @since      1.0.0
 *
 * @package    Wp_Bigfoot
 * @subpackage Wp_Bigfoot/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Bigfoot
 * @subpackage Wp_Bigfoot/includes
 * @author     Adam Martinez <am@zemartino.com>
 */
class Wp_Bigfoot_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

					$tmp = get_option('wpbf-options');
					if((!is_array($tmp))) {
						$arr = array("wpbf-style" => "Default");
						add_option('wpbf-options', $arr);
					}
	}

}

