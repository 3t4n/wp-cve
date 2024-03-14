<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.sanil.com.np
 * @since      1.0.0
 *
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/includes
 * @author     Sanil Shakya <sanilshakya@gmail.com>
 */
class Sticky_Social_Icons_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	 
	public static function deactivate() {

		$all_option_names = get_option( 'sanil_ssi_db_all_options_names' );
		if( !empty($all_option_names) ){
			$all_option_names = unserialize($all_option_names);

			foreach($all_option_names as $option_name ){
				delete_option( $option_name );
			}
		}

		delete_option( 'sanil_ssi_db_all_options_names' );
	}

}
