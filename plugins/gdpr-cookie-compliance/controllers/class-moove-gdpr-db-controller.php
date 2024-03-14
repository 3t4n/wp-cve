<?php
/**
 * Moove_GDPR_Database_Controller File Doc Comment
 *
 * @category Moove_GDPR_Database_Controller
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Moove_GDPR_Database_Controller Class Doc Comment
 *
 * @category Class
 * @package  Moove_GDPR_Database_Controller
 * @author   Moove Agency
 */
class Moove_GDPR_DB_Controller {

	/**
	 * Global variable used as primary key
	 *
	 * @var primary_key Primary key.
	 */
	public static $primary_key = 'id';

	/**
	 * Construct
	 */
	public function __construct() {
		/**
		 * Creating database structure on the first time
		 */
		if ( ! get_option( 'gdpr_cc_db_created' ) ) :
			global $wpdb;
			$gdpr_db_init = $wpdb->query(
				"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gdpr_cc_options(
          id INTEGER NOT NULL auto_increment,
          option_key VARCHAR(255) NOT NULL DEFAULT 1,
          option_value LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          site_id INTEGER DEFAULT NULL,
          extras LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          PRIMARY KEY (id)
        );"
			); // phpcs:ignore
			if ( $gdpr_db_init && ! is_wp_error( $gdpr_db_init ) ) :
				add_action(
					'init',
					function() {
						$gdpr_default_content = new Moove_GDPR_Content();
						$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
						$gdpr_options         = get_option( $option_name );
						if ( $gdpr_options && is_array( $gdpr_options ) ) :
							foreach ( $gdpr_options as $go_key => $go_value ) :
								gdpr_update_field( $go_key, $go_value );
							endforeach;
						endif;
					}
				);
				update_option( 'gdpr_cc_db_created', true );
			endif;
		endif;
	}

	/**
	 * GDPR Table name
	 */
	private static function gdpr_table() {
		global $wpdb;
		$tablename = 'gdpr_cc_options';
		return $wpdb->prefix . $tablename;
	}

	/**
	 * Get a Single value from database
	 *
	 * @param string $key Key name.
	 * @param string $site_id Site ID.
	 */
	public static function get( $key = false, $site_id = '1' ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( "SELECT option_value, option_key FROM {$wpdb->prefix}gdpr_cc_options WHERE `option_key` = %s", $key ) ); // db call ok; no-cache ok.
	}

	/**
	 * Get all values from table
	 *
	 * @param string $site_id Site ID.
	 */
	public static function get_options( $site_id = '1' ) {
		global $wpdb;
		$option_cache = wp_cache_get( 'gdpr_cc_options_' . $site_id );
		if ( false === $option_cache || is_admin() ) :
			$row = $wpdb->get_results( "SELECT option_key, option_value FROM {$wpdb->prefix}gdpr_cc_options", OBJECT_K ); // db call ok; no-cache ok.
			if ( is_array( $row ) ) :
				wp_cache_set( 'gdpr_cc_options_' . $site_id, $row );
				$result = $row;
			else :
				$result = array();
			endif;
		else :
			$result = $option_cache;
		endif;
		return $result;
	}

	/**
	 * Update value in table
	 *
	 * @param mixed $data Data.
	 */
	public static function update( $data ) {
		global $wpdb;
		self::remove_duplicate_entries();
		if ( self::get( $data['option_key'] ) ) :
			// Update.
			$where = array( 'option_key' => $data['option_key'] );
			return $wpdb->update( self::gdpr_table(), $data, $where ); // db call ok; no-cache ok.
		else :
			// Insert.
			return $wpdb->insert( self::gdpr_table(), $data ); // db call ok; no-cache ok.
		endif;
	}

	/**
	 * Removing duplicate entries from table if found
	 */
	private static function remove_duplicate_entries() {
		global $wpdb;

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}gdpr_cc_options'" ) != $wpdb->prefix . 'gdpr_cc_options' ) :
			$wpdb->query(
				"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gdpr_cc_options(
	        id INTEGER NOT NULL auto_increment,
	        option_key VARCHAR(255) NOT NULL DEFAULT 1,
	        option_value LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	        site_id INTEGER DEFAULT NULL,
	        extras LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	        PRIMARY KEY (id)
	      );"
			); // phpcs:ignore
		endif;

		return $wpdb->query( "DELETE c1 FROM {$wpdb->prefix}gdpr_cc_options c1 INNER JOIN {$wpdb->prefix}gdpr_cc_options c2 WHERE c1.id > c2.id AND c1.option_key = c2.option_key" ); // db call ok; no-cache ok.

	}

	/**
	 * Remove values from database
	 */
	public static function delete_option() {
		global $wpdb;
		$table_name = self::gdpr_table();
		delete_option( 'gdpr_cc_db_created' );
		return $wpdb->query(  "DROP TABLE IF EXISTS {$wpdb->prefix}gdpr_cc_options" ); // db call ok; no-cache ok.
	}
}
