<?php
/**
 * Tithes DB class
 *
 * This class is for interacting with the transactions' database table
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/DB Tithes
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Church_Tithe_WP_Transactions_DB Class
 *
 * @since 1.0.0
 */
class Church_Tithe_WP_Transactions_DB extends Church_Tithe_WP_DB {

	/**
	 * The metadata type.
	 *
	 * @since  1.0.0
	 * @var string
	 */
	public $meta_type = 'transaction';

	/**
	 * The name of the date column.
	 *
	 * @since  2.8
	 * @var string
	 */
	public $date_key = 'date_created';

	/**
	 * The name of the cache group.
	 *
	 * @since  1.0.0
	 * @var string
	 */
	public $cache_group = 'transactions';

	/**
	 * Get things started
	 *
	 * @since   1.0.0
	 */
	public function __construct() {

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'church_tithe_wp_transactions';
		$this->primary_key = 'id';
		$this->version     = '1.0';

	}

	/**
	 * Get columns and formats.
	 *
	 * @since   1.0.0
	 */
	public function get_columns() {
		return array(
			'id'                   => '%d',
			'user_id'              => '%d',
			'date_created'         => '%s',
			'date_paid'            => '%s',
			'period_start_date'    => '%s',
			'period_end_date'      => '%s',
			'type'                 => '%s',
			'method'               => '%s',
			'page_url'             => '%s',
			'charged_amount'       => '%d',
			'charged_currency'     => '%s',
			'home_currency'        => '%s',
			'gateway_fee_hc'       => '%d',
			'earnings_hc'          => '%d',
			'charge_id'            => '%s',
			'refund_id'            => '%s',
			'note_with_tithe'        => '%s',
			'statement_descriptor' => '%s',
			'arrangement_id'       => '%s',
			'payment_intent_id'    => '%s',
			'is_live_mode'         => '%d',
			'initial_emails_sent'  => '%d',
		);
	}

	/**
	 * Get default column values
	 *
	 * @since   1.0.0
	 */
	public function get_column_defaults() {
		return array(
			'id'                   => 0,
			'user_id'              => 0,
			'date_created'         => gmdate( 'Y-m-d H:i:s' ),
			'date_paid'            => gmdate( 'Y-m-d H:i:s' ),
			'period_start_date'    => '',
			'period_end_date'      => '',
			'type'                 => '',
			'method'               => '',
			'page_url'             => '',
			'charged_amount'       => 0,
			'charged_currency'     => '',
			'home_currency'        => '',
			'gateway_fee_hc'       => 0,
			'earnings_hc'          => 0,
			'charge_id'            => '',
			'refund_id'            => '',
			'statement_descriptor' => '',
			'note_with_tithe'        => '',
			'arrangement_id'       => '',
			'payment_intent_id'    => '',
			'is_live_mode'         => 0,
			'initial_emails_sent'  => 0,
		);
	}

	/**
	 * Add a transaction
	 *
	 * @since   1.0.0
	 * @param   array $data The data being added to this transaction.
	 * @return  bool
	 */
	public function add( $data = array() ) {

		$defaults = $this->get_column_defaults();

		$args = wp_parse_args( $data, $defaults );

		if ( isset( $args['id'] ) ) {
			$transaction = $this->get_transaction( $args['id'] );
		} else {
			$transaction = false;
		}

		if ( $transaction ) {

			// Update an existing transaction.
			$this->update( $transaction->id, $args );

			return $transaction->id;

		} else {

			// Insert/Create a new transaction.
			return $this->insert( $args, 'transaction' );

		}

	}

	/**
	 * Insert a new transaction
	 *
	 * @since   1.0.0
	 * @param   array $data The data about this new transaction.
	 * @return  int
	 */
	public function insert( $data ) {

		// Then insert this new transaction.
		$result = parent::insert( $data );

		if ( $result ) {
			$this->set_last_changed();
		}

		return $result;
	}

	/**
	 * Update a transaction
	 *
	 * @since   1.0.0
	 * @param   int    $row_id The id of the transaction being updated.
	 * @param   array  $data The data for the update about this transaction.
	 * @param   string $where The mysql where clause for the update.
	 * @return  bool
	 */
	public function update( $row_id, $data = array(), $where = '' ) {
		$result = parent::update( $row_id, $data, $where );

		if ( $result ) {
			$this->set_last_changed();
		}

		return $result;
	}

	/**
	 * Delete a transaction
	 *
	 * @param   int $id The id of the transaction being deleted.
	 * @since   1.0.0
	 */
	public function delete( $id = false ) {

		if ( empty( $id ) ) {
			return false;
		}

		$transaction = $this->get_transaction( $id );

		if ( $transaction->id > 0 ) {

			global $wpdb;

			$result = $wpdb->delete( $this->table_name, array( 'id' => $transaction->id ), array( '%d' ) );

			if ( $result ) {
				$this->set_last_changed();
			}

			return $result;

		} else {
			return false;
		}

	}

	/**
	 * Checks if a transaction exists
	 *
	 * @param   string $value The value being checked for existence.
	 * @param   string $field The field containing the value being checked for existence.
	 * @since   1.0.0
	 */
	public function exists( $value = '', $field = 'email' ) {

		$columns = $this->get_columns();
		if ( ! array_key_exists( $field, $columns ) ) {
			return false;
		}

		return (bool) $this->get_column_by( 'id', $field, $value );

	}

	/**
	 * Retrieves a single transaction from the database
	 *
	 * @since  1.0.0
	 * @param  mixed  $id          The transaction ID or value of the column in question.
	 * @param  string $column_slug The slug of the column we are checking the value of.
	 * @return mixed          Upon success, an object of the transaction. Upon failure, NULL
	 */
	public function get_transaction( $id = 0, $column_slug = 'id' ) {

		if ( empty( $id ) ) {
			return false;
		}

		if ( ! $id ) {
			return false;
		}

		$args                           = array( 'number' => 1 );
		$args['column_values_included'] = array(
			$column_slug => $id,
		);

		$query = new Church_Tithe_WP_General_Query( '', $this );

		$results = $query->query( $args );

		if ( empty( $results ) ) {
			return false;
		}

		return array_shift( $results );
	}

	/**
	 * Retrieve transactions from the database
	 *
	 * @param   array $args The data being used to fetch transactions.
	 * @since   1.0.0
	 */
	public function get_transactions( $args = array() ) {
		$args['count'] = false;

		$query = new Church_Tithe_WP_General_Query( '', $this );

		return $query->query( $args );
	}


	/**
	 * Count the total number of transactions in the database
	 *
	 * @param   array $args The data being used to fetch transactions.
	 * @since   1.0.0
	 */
	public function count( $args = array() ) {

		$args['count']  = true;
		$args['offset'] = 0;

		$query   = new Church_Tithe_WP_General_Query( '', $this );
		$results = $query->query( $args );

		return $results;
	}

	/**
	 * Sets the last_changed cache key for transactions.
	 *
	 * @since  1.0.0
	 */
	public function set_last_changed() {
		wp_cache_set( 'last_changed', microtime(), $this->cache_group );
	}

	/**
	 * Retrieves the value of the last_changed cache key for transactions.
	 *
	 * @since  1.0.0
	 */
	public function get_last_changed() {
		if ( function_exists( 'wp_cache_get_last_changed' ) ) {
			return wp_cache_get_last_changed( $this->cache_group );
		}

		$last_changed = wp_cache_get( 'last_changed', $this->cache_group );
		if ( ! $last_changed ) {
			$last_changed = microtime();
			wp_cache_set( 'last_changed', $last_changed, $this->cache_group );
		}

		return $last_changed;
	}

	/**
	 * Create the table
	 *
	 * @since   1.0.0
	 */
	public function create_table() {

		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql = 'CREATE TABLE ' . $this->table_name . ' (
		id int(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id int(20) unsigned NOT NULL,
		date_created datetime NOT NULL,
		date_paid datetime NOT NULL,
		period_start_date datetime NOT NULL,
		period_end_date datetime NOT NULL,
		type mediumtext NOT NULL,
		method mediumtext NOT NULL,
		page_url mediumtext NOT NULL,
		charged_amount int NOT NULL,
		charged_currency mediumtext NOT NULL,
		home_currency mediumtext NOT NULL,
		gateway_fee_hc int NOT NULL,
		earnings_hc int NOT NULL,
		charge_id mediumtext NOT NULL,
		refund_id mediumtext NOT NULL,
		statement_descriptor mediumtext NOT NULL,
		note_with_tithe mediumtext NOT NULL,
		arrangement_id int(20) unsigned NOT NULL,
		payment_intent_id mediumtext NOT NULL,
		is_live_mode int(1) NOT NULL,
		initial_emails_sent int(1) NOT NULL,
		PRIMARY KEY  id (id),
		KEY id (id)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;';

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

}
