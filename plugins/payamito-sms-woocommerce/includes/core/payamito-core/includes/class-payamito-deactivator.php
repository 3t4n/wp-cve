<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://payamito.com/
 * @since      1.0.0
 * @package    Payamito
 * @subpackage Payamito/includes
 */

/**
 * Fired during plugin deactivation.
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Payamito
 * @subpackage Payamito/includes
 * @author     payamito <payamito@gmail.com>
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( "Payamito_Deactivator" ) ) {
	class Payamito_Deactivator
	{

		/**
		 * Short Description. (use period)
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function deactivate()
		{
			wp_clear_scheduled_hook( 'payamito_remove_log' );
		}

	}
}
