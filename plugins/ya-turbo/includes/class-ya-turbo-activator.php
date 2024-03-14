<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.1
 *
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/includes
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/includes
 * @author     hardkod.ru <hello@hardkod.ru>
 */
class Ya_Turbo_Activator {

	/**
	 * Plugin activation.
	 *
	 * Custom options, database schema definition.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;

		if ( get_option( 'ya-turbo' ) === false ) {

			@require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$charset_collate = $wpdb->get_charset_collate();

			/**
			 * Feed database schema
			 */
			$feed_table =  $wpdb->prefix . YATURBO_DB_FEEDS;

			$sql = /** @lang sql */
				"CREATE TABLE IF NOT EXISTS {$feed_table} (
				  `id` INT NOT NULL AUTO_INCREMENT,
				  `type` INT UNSIGNED NOT NULL,
				  `status` INT UNSIGNED NOT NULL,				  
				  `slug` VARCHAR(50) NOT NULL,
				  `title` VARCHAR(255) NULL,
				  `description` LONGTEXT NULL,
				  `language` VARCHAR(2) NOT NULL,
				  `limit` INT UNSIGNED NOT NULL DEFAULT 1000,
				  `settings` LONGTEXT NULL,
				  PRIMARY KEY (`id`),
                  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC)
                ) $charset_collate;
			";

			dbDelta($sql);

			/* Default options */

			$options = get_option( 'ya-turbo', array() );

			/* $options['enable_setting_pages']   = false; */

			update_option( 'ya-turbo', $options );

			$feed_table = $options = null;
		}
	}

}
