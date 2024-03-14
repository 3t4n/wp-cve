<?php
/**
 * Cart Abandonment DB
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cart Abandonment DB class.
 */
class INTRKT_ABANDON_Database {



	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *  Create tables
	 */
	public function intrkt_create_tables() {
		$this->intrkt_create_cart_abandonment_table();
		$this->intrkt_check_if_all_table_created();
	}

	/**
	 *  Check if tables created.
	 */
	public function intrkt_check_if_all_table_created() {

		global $wpdb;

		$required_tables = array(
			INTRKT_ABANDON_CART_ABANDONMENT_TABLE,
		);

		delete_option( 'INTRKT_ca_all_db_tables_created' );

		foreach ( $required_tables as $table ) {
			$is_table_exist = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}{$table}'" ); //phpcs:ignore
			if ( empty( $is_table_exist ) ) {
				update_option( 'INTRKT_ca_all_db_tables_created', 'no' );
				break;
			}
		}

	}

	/**
	 *  Create tables for analytics.
	 */
	public function intrkt_create_cart_abandonment_table() {

		global $wpdb;

		$wpdb->hide_errors();

		$cart_abandonment_db = $wpdb->prefix . INTRKT_ABANDON_CART_ABANDONMENT_TABLE;
		$charset_collate     = $wpdb->get_charset_collate();

		// Cart abandonment tracking db sql command.
		if ( 'true' === INTRKT_IS_DB_UPDATE_REQUIRED ) {
			$wpdb->get_results( "DROP TABLE IF EXISTS {$cart_abandonment_db}" );
		}
		$sql = "CREATE TABLE IF NOT EXISTS $cart_abandonment_db (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			checkout_id int(11) NOT NULL,
			email VARCHAR(100),
			cart_contents LONGTEXT,
			cart_total DECIMAL(10,2),
			session_id VARCHAR(60) NOT NULL,
			other_fields LONGTEXT,
			order_status ENUM( 'normal','abandoned','completed','lost') NOT NULL DEFAULT 'normal',
			unsubscribed  boolean DEFAULT 0,
			coupon_code LONGTEXT,
   			time DATETIME DEFAULT NULL,
			is_notified boolean DEFAULT 0,
			PRIMARY KEY  (`id`, `session_id`),
			UNIQUE KEY `session_id_UNIQUE` (`session_id`)
		) $charset_collate;\n";

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

	}

}

INTRKT_ABANDON_Database::get_instance();
