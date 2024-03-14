<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    wugrat
 * @subpackage wugrat/includes
 * @author     wupo
 */
class Wugrat_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $wpdb;

        $result = $wpdb->get_var("SHOW COLUMNS FROM $wpdb->term_taxonomy LIKE 'children';");

        if (empty($result)) {
            $wpdb->query("ALTER TABLE $wpdb->term_taxonomy ADD COLUMN children VARCHAR(10000) NULL");
        }
	}
}
