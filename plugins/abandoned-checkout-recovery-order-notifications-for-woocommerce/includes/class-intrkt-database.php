<?php
/**
 * Interakt DB
 *
 * @package interakt-add-on-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class for database.
 */
class Intrkt_Database {
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
		$this->intrkt_create_order_status_table();
		$this->intrkt_check_if_all_table_created();
	}
	/**
	 * Insert default data.
	 */
	public function insert_default_data() {
		$this->intrkt_insert_default_data_order_status_table();
	}

	/**
	 *  Check if tables created.
	 */
	public function intrkt_check_if_all_table_created() {

		global $wpdb;

		$required_tables = array(
			INTRKT_ORDER_STATUS_TABLE,
		);

		delete_option( 'intrkt_all_db_tables_created' );

		foreach ( $required_tables as $table ) {
			$is_table_exist = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}{$table}'" ); //phpcs:ignore
			if ( empty( $is_table_exist ) ) {
				update_option( 'intrkt_all_db_tables_created', 'no' );
				break;
			}
		}

	}
	/**
	 *  Create tables for order status.
	 */
	public function intrkt_create_order_status_table() {
		global $wpdb;

		$wpdb->hide_errors();

		$intrkt_db       = $wpdb->prefix . INTRKT_ORDER_STATUS_TABLE;
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $intrkt_db (
			intrkt_status VARCHAR(100) NOT NULL,
			order_status VARCHAR(100) NOT NULL,
			payment_mode VARCHAR(100),
			table_order VARCHAR(100),
			is_enabled  boolean DEFAULT 1,
			PRIMARY KEY  (`intrkt_status`)
		) $charset_collate;\n";

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

	}
	/**
	 * Insert default data for order status.
	 */
	public function intrkt_insert_default_data_order_status_table() {
		global $wpdb;
		$intrkt_order_status = $wpdb->prefix . INTRKT_ORDER_STATUS_TABLE;
		$default_values      = $this->intrkt_order_status_default();
		if ( ! empty( $default_values ) && is_array( $default_values ) ) {
			foreach ( $default_values as $default_value ) {
				$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$intrkt_order_status,
					$default_value
				);
			}
		}

	}
	/**
	 * Default value for order status table.
	 */
	public function intrkt_order_status_default() {
		$default_values = array(
			array(
				'intrkt_status' => 'intrkt_abandon_checkout',
				'order_status'  => 'wc-failed,wc-pending',
				'payment_mode'  => 'any',
				'table_order'   => '1',
			),
			array(
				'intrkt_status' => 'intrkt_order_placed_prepaid',
				'order_status'  => 'wc-processing',
				'payment_mode'  => 'not-cod',
				'table_order'   => '2',
			),
			array(
				'intrkt_status' => 'intrkt_order_placed_cod',
				'order_status'  => 'wc-processing',
				'payment_mode'  => 'cod',
				'table_order'   => '3',
			),

			array(
				'intrkt_status' => 'intrkt_order_shipped',
				'order_status'  => 'wc-completed',
				'payment_mode'  => 'any',
				'table_order'   => '4',
			),
			array(
				'intrkt_status' => 'intrkt_order_delivered',
				'order_status'  => '',
				'payment_mode'  => 'any',
				'table_order'   => '5',
			),
			array(
				'intrkt_status' => 'intrkt_order_cancelled',
				'order_status'  => 'wc-cancelled',
				'payment_mode'  => 'any',
				'table_order'   => '6',
			),
		);
		$default_values = apply_filters( 'intrkt_order_status_default_value', $default_values );
		return $default_values;
	}
}

Intrkt_Database::get_instance();
