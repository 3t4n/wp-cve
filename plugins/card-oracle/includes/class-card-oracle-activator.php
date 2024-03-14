<?php
/**
 * Fired during plugin activation
 *
 * @link       https://cdgraham.com
 * @since      0.5.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.5.0
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 * @author     Christopher Graham <support@chillichalli.com>
 */
class Card_Oracle_Activator {

	/**
	 * Sets Card Oracle Version during activation.
	 *
	 * Sets Card Oracle Version during activation.
	 *
	 * @since    0.15.0
	 */
	public static function activate() {

		// Installed version number.
		update_option( 'card_oracle_version', CARD_ORACLE_VERSION );

		if ( ! wp_next_scheduled( 'card-oracle_cron_refresh_cache' ) ) {
			wp_schedule_event( time(), 'daily', 'card-oracle_cron_refresh_cache' );
		}

		flush_rewrite_rules();

	}

}
