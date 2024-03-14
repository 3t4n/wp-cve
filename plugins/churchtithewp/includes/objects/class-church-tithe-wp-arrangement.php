<?php
/**
 * Arrangement Object
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Arrangement
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Church_Tithe_WP_Arrangement Class
 *
 * @since 1.0.0
 */
class Church_Tithe_WP_Arrangement {

	/**
	 * The arrangement ID
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $id = 0;

	/**
	 * The user ID associated with the arrangement
	 *
	 * @since  1.0.0
	 * @var int
	 */
	public $user_id;

	/**
	 * The arrangement's creation date
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $created_date;

	/**
	 * The initial transaction which created this arrangement
	 *
	 * @since 1.0.0
	 * @var id
	 */
	public $initial_transaction_id;

	/**
	 * The number of intervals this arrangement lasts before expiring/renewing
	 *
	 * @since  1.0.0
	 * @var int
	 */
	public $interval_count;

	/**
	 * The interval string used for this arrangement lasts before expiring/renewing (like day, month, year)
	 *
	 * @since  1.0.0
	 * @var string
	 */
	public $interval_string;

	/**
	 * The currency associated with this arrangement
	 *
	 * @since  1.0.0
	 * @var string
	 */
	public $currency;

	/**
	 * The initial amount this arrangement was for
	 *
	 * @since  1.0.0
	 * @var int
	 */
	public $initial_amount;

	/**
	 * The renewal amount this arrangement was for
	 *
	 * @since  1.0.0
	 * @var int
	 */
	public $renewal_amount;


	/**
	 * This is the status of the recurring subscription at the gateway (off, on, cancelled)
	 *
	 * @since  1.0.0
	 * @var string
	 */
	public $recurring_status;

	/**
	 * The Database Abstraction
	 *
	 * @since  1.0.0
	 * @var object
	 */
	protected $db;

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 * @param int    $id The value with which to get the arrangement object.
	 * @param string $column_slug The column from which to check for the value.
	 */
	public function __construct( $id = false, $column_slug = 'id' ) {

		$this->db = church_tithe_wp()->arrangements_db;

		if ( false === $id || ( is_numeric( $id ) && absint( $id ) !== (int) $id ) ) {
			return false;
		}

		$arrangement = $this->db->get_arrangement( $id, $column_slug );

		if ( empty( $arrangement ) || ! is_object( $arrangement ) ) {
			return false;
		}

		$this->setup_arrangement( $arrangement );

	}

	/**
	 * Given the arrangement data, let's set the variables
	 *
	 * @since  1.0.0
	 * @param  object $arrangement The Arrangement Object.
	 * @return bool                If the setup was successful or not
	 */
	private function setup_arrangement( $arrangement ) {

		if ( ! is_object( $arrangement ) ) {
			return false;
		}

		foreach ( $arrangement as $key => $value ) {

			switch ( $key ) {

				default:
					$this->$key = $value;
					break;

			}
		}

		// Arrangement ID is the only thing that is necessary, make sure it exists.
		if ( ! empty( $this->id ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Magic __get function to dispatch a call to retrieve a private property
	 *
	 * @since 1.0.0
	 * @param mixed $key The value to retreive about this arrangement.
	 */
	public function __get( $key ) {

		if ( method_exists( $this, 'get_' . $key ) ) {

			return call_user_func( array( $this, 'get_' . $key ) );

		} else {

			// translators: The key that could not be retrieved about an arrangement.
			return new WP_Error( 'church-tithe-wp-arrangement-invalid-property', sprintf( __( 'Can\'t get property %s', 'church-tithe-wp' ), $key ) );

		}

	}

	/**
	 * Magic SET function
	 *
	 * @since    1.0.0
	 * @param    string $key The property name.
	 * @param    mixed  $value The value the property is being set to.
	 */
	public function __set( $key, $value ) {

		if ( ! empty( $key ) ) {

			if ( method_exists( $this, 'set_' . $key ) ) {

				return call_user_func( array( $this, 'set_' . $key ), $value );

			} else {
				$this->$key = $value;

			}
		}

	}

	/**
	 * Get the arrangement ids of the transaction in an array.
	 *
	 * @since 1.0.0
	 * @return array An array of arrangement IDs for the transaction, or an empty array if none exist.
	 */
	public function get_recurring_status() {

		return $this->recurring_status;

	}

	/**
	 * Creates a arrangement
	 *
	 * @since  1.0.0
	 * @param  array $data Array of attributes for a arrangement.
	 * @return mixed False if not a valid creation, Arrangement ID if user is found or valid creation
	 */
	public function create( $data = array() ) {

		if ( 0 !== $this->id || empty( $data ) ) {
			return false;
		}

		$defaults = array(
			'id'                     => 0,
			'user_id'                => 0,

			// Initial information.
			'initial_transaction_id' => 0,

			// Time period information.
			'interval_count'         => '', // 1.
			'interval_string'        => '', // day, month, year, etc.

			// Cost information.
			'currency'               => '',
			'initial_amount'         => '',
			'renewal_amount'         => '',

			// Recurring Information.
			'recurring_status'       => '',
		);

		$args = wp_parse_args( $data, $defaults );
		$args = $this->sanitize_columns( $args );

		$created = false;

		$newly_added_id = $this->db->add( $args );

		// The DB class 'add' implies an update if the arrangement being asked to be created already exists.
		if ( $newly_added_id ) {

			// We've successfully added/updated the arrangement, reset the class vars with the new data.
			$arrangement = $this->db->get_arrangement( $newly_added_id );

			// Setup the arrangement data with the values from DB.
			$this->setup_arrangement( $arrangement );

			$created = $this->id;
		}

		return $created;

	}

	/**
	 * Update an arrangement record
	 *
	 * @since  1.0.0
	 * @param  array $data Array of data attributes for a arrangement (checked via whitelist).
	 * @return bool If the update was successful or not
	 */
	public function update( $data = array() ) {

		if ( empty( $data ) ) {
			return false;
		}

		$data = $this->sanitize_columns( $data );

		$updated = false;

		if ( $this->db->update( $this->id, $data ) ) {

			$arrangement = $this->db->get_arrangement( $this->id );
			$this->setup_arrangement( $arrangement );

			$updated = true;
		}

		return $updated;
	}

	/**
	 * Sanitize the data for update/create
	 *
	 * @since  1.0.0
	 * @param  array $data The data to sanitize.
	 * @return array       The sanitized data, based off column defaults
	 */
	private function sanitize_columns( $data ) {

		$columns        = $this->db->get_columns();
		$default_values = $this->db->get_column_defaults();

		foreach ( $columns as $key => $type ) {

			// Only sanitize data that we were provided.
			if ( ! array_key_exists( $key, $data ) ) {
				continue;
			}

			switch ( $type ) {

				case '%s':
					$data[ $key ] = sanitize_text_field( $data[ $key ] );

					break;

				case '%d':
					if ( ! is_numeric( $data[ $key ] ) || absint( $data[ $key ] ) !== (int) $data[ $key ] ) {
						$data[ $key ] = $default_values[ $key ];
					} else {
						$data[ $key ] = absint( $data[ $key ] );
					}
					break;

				case '%f':
					// Convert what was given to a float.
					$value = floatval( $data[ $key ] );

					if ( ! is_float( $value ) ) {
						$data[ $key ] = $default_values[ $key ];
					} else {
						$data[ $key ] = $value;
					}
					break;

				default:
					$data[ $key ] = sanitize_text_field( $data[ $key ] );
					break;

			}
		}

		return $data;
	}

}
