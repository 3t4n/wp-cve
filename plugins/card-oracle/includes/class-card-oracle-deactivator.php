<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://cdgraham.com
 * @since      0.5.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.5.0
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 * @author     Christopher Graham <support@chillichalli.com>
 */
class Card_Oracle_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.5.0
	 */
	public static function deactivate() {

		flush_rewrite_rules();

	}

}
