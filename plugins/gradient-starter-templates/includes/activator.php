<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.gradientthemes.com/
 * @since      1.0.0
 *
 * @package    Gradient Themes
 *  
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Gradient Themes
 *  
 * @author     Gradient Themes <info@gradientthemes.com>
 */
class gradient_starter_templates_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        update_option( '__gradient_starter_templates_do_redirect', true );
	}
}