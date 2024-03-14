<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.gradientthemes.com/
 * @since      1.0.0
 *
 * @package    Gradient Themes
 *  
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Gradient Themes
 *  
 * @author     Gradient Themes <info@gradientthemes.com>
 */
class gradient_starter_templates_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        update_option( '__gradient_starter_templates_do_redirect', false );
    }
}