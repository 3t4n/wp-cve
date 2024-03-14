<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.ticketself.com
 * @since      1.0.0
 *
 * @package    Wp_Reservas
 * @subpackage Wp_Reservas/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Reservas
 * @subpackage Wp_Reservas/includes
 * @author     mg <mikel@ticketself.com>
 */
class Wp_Reservas_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option('wpReservas-installed');
	}

}
