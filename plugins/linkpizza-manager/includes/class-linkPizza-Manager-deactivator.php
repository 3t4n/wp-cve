<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 *
 * @package    linkPizza_Manager
 * @subpackage linkPizza_Manager/includes
 */
class linkPizza_Manager_Deactivator {

	/**
	 * Deactivation methods
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Unregister cronjobs.
		LinkPizza_Manager_Jobs::unschedule();
	}
}
