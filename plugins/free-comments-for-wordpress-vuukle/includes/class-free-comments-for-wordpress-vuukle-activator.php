<?php

/**
 * Fired during plugin activation
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/includes
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/includes
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
 */
class Free_Comments_For_Wordpress_Vuukle_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public static function activate() {
		/**
		 * Check if plugin stores AppId alongside with main settings,
		 * If yes then move to another option row.
		 * This part is designed to decrease DB requests count and data amount
		 * to be retrieved.
		 */
		$settings = get_option( 'Vuukle' );
		$app_id   = get_option( 'Vuukle_App_Id' );
		if ( ! empty( $settings ) && is_array( $settings ) && ! empty( $settings['AppId'] ) && $app_id == null ) {
			// Move to another row with option name Vuukle_App_Id
			add_option( 'Vuukle_App_Id', $settings['AppId'] );
			// Remove from main options array
			unset( $settings['AppId'] );
			update_option( 'Vuukle', $settings );
		}
		// Store active flag , in order to open one time activation popup
		add_option( 'Activated_Vuukle_Plugin', '1' );
		if ( empty( get_option( 'Activated_Vuukle_Plugin_Date' ) ) ) {
			add_option( 'Activated_Vuukle_Plugin_Date', gmdate( 'Y-m-d H:i:s' ) );
		}
	}
}
