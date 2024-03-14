<?php
/**
 * Tithes DB class
 *
 * This class is for interacting with the arrangements' database table
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
 * Church_Tithe_WP_Arrangements_DB Class
 *
 * @since 1.0.0
 */
class Church_Tithe_WP_Arrangements_DB extends Church_Tithe_WP_DB {

	/**
	 * The metadata type.
	 *
	 * @since  1.0.0
	 * @var string
	 */
	public $meta_type = 'arrangement';

	/**
	 * The name of the date column.
	 *
	 * @since  1.0
	 * @var string
	 */
	public $date_key = 'date_created';

	/**
	 * The name of the cache group.
	 *
	 * @since  1.0.0
	 * @var string
	 */
	public $cache_group = 'church-tithe-wp-arrangements';

	/**
	 * Get things started
	 *
	 * @since   1.0.0
	 */
	public function __construct() {

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'church_tithe_wp_arrangements';
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
			'id'                      => '%d',
			'user_id'                 => '%d',

			'date_created'            => '%s',
			'initial_transaction_id'  => '%d',

			'interval_count'          => '%d',
			'interval_string'         => '%s',

			'currency'                => '%s',
			'initial_amount'          => '%d',
			'renewal_amount'          => '%d',

			'recurring_status'        => '%s',
			'status_reason'           => '%s',
			'gateway_subscription_id' => '%s',
			'current_period_end'      => '%s',
			'is_live_mode'            => '%d',
		);
	}

	/**
	 * Get default column values
	 *
	 * @since   1.0.0
	 */
	public function get_column_defaults() {
		return array(
			'user_id'                 => 0,

			'date_created'            => gmdate( 'Y-m-d H:i:s' ),
			'initial_transaction_id'  => 0,

			'interval_count'          => 0,
			'interval_string'         => '',

			'currency'                => '',
			'initial_amount'          => 0,
			'renewal_amount'          => 0,

			'recurring_status'        => 'off',
			'status_reason'           => '',
			'gateway_subscription_id' => '',
			'current_period_end'      => '',
			'is_live_mode'            => 0,
		);
	}

	/**
	 * Add a arrangement
	 *
	 * @param array $data The data about this arrangement.
	 * @since 1.0.0
	 */
	public function add( $data = array() ) {

		$defaults = $this->get_column_defaults();

		$args = wp_parse_args( $data, $defaults );

		if ( isset( $args['id'] ) ) {
			$arrangement = $this->get_arrangement( $args['id'] );
		} else {
			$arrangement = false;
		}

		if ( $arrangement ) {

			// Update an existing arrangement.
			$this->update( $arrangement->id, $args );

			return $arrangement->id;

		} else {

			return $this->insert( $args, 'arrangement' );

		}

	}

	/**
	 * Insert a new arrangement
	 *
	 * @since   1.0.0
	 * @param   array $data The data about this arrangement.
	 * @return  int
	 */
	public function insert( $data ) {

		// Then insert this new arrangement.
		$result = parent::insert( $data );

		if ( $result ) {
			$this->set_last_changed();
		}

		return $result;
	}

	/**
	 * Update a arrangement
	 *
	 * @since   1.0.0
	 * @param   int    $row_id The id of the row being updated.
	 * @param   array  $data The data about this arrangement.
	 * @param   string $where A where clause.
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
	 * Delete a arrangement
	 *
	 * @since   1.0.0
	 * @param   int $id The id of the arrangement being updated.
	 * @return  bool The result of the udpate
	 */
	public function delete( $id = false ) {

		if ( empty( $id ) ) {
			return false;
		}

		$arrangement = $this->get_arrangement( $id );

		if ( $arrangement->id > 0 ) {

			global $wpdb;

			$result = $wpdb->delete( $this->table_name, array( 'id' => $arrangement->id ), array( '%d' ) );

			if ( $result ) {
				$this->set_last_changed();
			}

			return $result;

		} else {
			return false;
		}

	}

	/**
	 * Checks if a arrangement exists
	 *
	 * @since    1.0.0
	 * @param    string $value The value we are checking for existence.
	 * @param    string $field The field in question.
	 * @return   bool Whether it exists or not.
	 */
	public function exists( $value = '', $field = 'email' ) {

		$columns = $this->get_columns();
		if ( ! array_key_exists( $field, $columns ) ) {
			return false;
		}

		return (bool) $this->get_column_by( 'id', $field, $value );

	}

	/**
	 * Retrieves a single arrangement from the database
	 *
	 * @since  1.0.0
	 * @param  mixed  $id  The arrangement ID., or any value we are looking for.
	 * @param  string $column_slug Any column in the arrangements table.
	 * @return mixed  Upon success, an object of the transaction. Upon failure, NULL
	 */
	public function get_arrangement( $id = 0, $column_slug = 'id' ) {

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
	 * Retrieve arrangements from the database
	 *
	 * @since   1.0.0
	 * @param   array $args The arguments being used to filter a request for arrangements.
	 * @return  mixed $id  The arrangement ID., or any value we are looking for.
	 */
	public function get_arrangements( $args = array() ) {
		$args['count'] = false;

		$query = new Church_Tithe_WP_General_Query( '', $this );

		return $query->query( $args );
	}


	/**
	 * Count the total number of arrangements in the database
	 *
	 * @since   1.0.0
	 * @param   array $args The arguments being used to filter a request for arrangements.
	 * @return  mixed The number of results.
	 */
	public function count( $args = array() ) {

		$args['count']  = true;
		$args['offset'] = 0;

		$query   = new Church_Tithe_WP_General_Query( '', $this );
		$results = $query->query( $args );

		return $results;
	}

	/**
	 * Sets the last_changed cache key for arrangements.
	 *
	 * @since  1.0
	 * @return void
	 */
	public function set_last_changed() {
		wp_cache_set( 'last_changed', microtime(), $this->cache_group );
	}

	/**
	 * Retrieves the value of the last_changed cache key for arrangements.
	 *
	 * @since  1.0
	 * @return string When it was was changed.
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
		initial_transaction_id int(20) unsigned NOT NULL,
		interval_count int(20) unsigned NOT NULL,
		interval_string mediumtext NOT NULL,
		currency mediumtext NOT NULL,
		initial_amount int NOT NULL,
		renewal_amount int NOT NULL,
		recurring_status mediumtext NOT NULL,
		status_reason mediumtext NOT NULL,
		gateway_subscription_id mediumtext NOT NULL,
		current_period_end datetime NOT NULL,
		is_live_mode int(1) unsigned NOT NULL,
		PRIMARY KEY  (id)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;';

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

}
