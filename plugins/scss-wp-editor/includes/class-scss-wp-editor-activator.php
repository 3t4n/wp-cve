<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Scss_Wp_Editor
 * @subpackage Scss_Wp_Editor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Scss_Wp_Editor
 * @subpackage Scss_Wp_Editor/includes
 * @author     IT Path Solutions <info@itpathsolutions.com>
 */
class Scss_Wp_Editor_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        if ( ! get_option( 'swe_scss_box_value' ) || ! get_option( 'swe_box_value_status' ) ) {
            add_option( 'swe_scss_box_value', '', '', 'yes' );
            add_option( 'swe_box_value_status', '', '', 'yes' );
        }

	}

}
