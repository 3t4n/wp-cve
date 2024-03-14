<?php

/**
 * Fired during plugin activation
 *
 * @link       https://payamito.com/
 * @since      1.0.0
 * @package    Payamito
 * @subpackage Payamito/includes
 */

/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Payamito
 * @subpackage Payamito/includes
 * @author     payamito <payamito@gmail.com>
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( "Payamito_Activator" ) ) {
	class Payamito_Activator
	{

		/**
		 * Short Description. (use period)
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate()
		{
		}
	}
}

