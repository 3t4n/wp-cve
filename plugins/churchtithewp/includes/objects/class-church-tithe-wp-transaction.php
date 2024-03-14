<?php
/**
 * Transaction Object
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Transaction
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Church_Tithe_WP_Transaction Class
 *
 * @since 1.0.0
 */
class Church_Tithe_WP_Transaction {

	/**
	 * The transaction Number (used for invoice number)
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $id = 0;

	/**
	 * The user ID associated with the transaction
	 *
	 * @since  1.0.0
	 * @var int
	 */
	public $user_id;

	/**
	 * The transaction's creation date
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $date;

	/**
	 * The start date of the period this transaction represents.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $period_start_date;

	/**
	 * The end date of the period this transaction represents.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $period_end_date;

	/**
	 * The transaction's gateway
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $gateway;

	/**
	 * The transaction's method
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $method;

	/**
	 * The page url where the transaction took place
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $page_url;

	/**
	 * The amount that was charged to the customer
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $charged_amount;

	/**
	 * The currency that was used to transaction the customer
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $charged_currency;

	/**
	 * The home currency, which the amount charged is converted to prior to charging fees and depositing
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $home_currency;

	/**
	 * The transaction's gateway fee amount in the home currency
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $gateway_fee_hc;

	/**
	 * The actual earnings in the home currency minus transaction fees.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $earnings_hc;

	/**
	 * The transaction's note with the tithe
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $note_with_tithe;

	/**
	 * The charge id from the gateway (stripe)
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $charge_id;

	/**
	 * The refund id from the gateway (stripe)
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $refund_id;

	/**
	 * The statement descriptor (The way the transaction appears on the credit card statement)
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $statement_descriptor;

	/**
	 * The arrangement IDs associated with the transaction
	 *
	 * @since  1.0.0
	 * @var int
	 */
	private $arrangement_id;

	/**
	 * The PaymentIntent IDs associated with the transaction
	 *
	 * @since  1.0.0
	 * @var string
	 */
	private $payment_intent;

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
	 * @param int    $id The value with which to get the transaction object.
	 * @param string $column_slug The column from which to check for the value.
	 */
	public function __construct( $id = false, $column_slug = 'id' ) {

		$this->db = church_tithe_wp()->transactions_db;

		if ( false === $id || ( is_numeric( $id ) && absint( $id ) !== (int) $id ) ) {
			return false;
		}

		$transaction = $this->db->get_transaction( $id, $column_slug );

		if ( empty( $transaction ) || ! is_object( $transaction ) ) {
			return false;
		}

		$this->setup_transaction( $transaction );

	}

	/**
	 * Given the transaction data, let's set the variables
	 *
	 * @since  1.0.0
	 * @param  object $transaction The Transaction Object.
	 * @return bool                If the setup was successful or not
	 */
	private function setup_transaction( $transaction ) {

		if ( ! is_object( $transaction ) ) {
			return false;
		}

		foreach ( $transaction as $key => $value ) {

			switch ( $key ) {

				default:
					$this->$key = $value;
					break;

			}
		}

		// Transaction ID is the only thing that is necessary, make sure it exists.
		if ( ! empty( $this->id ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Magic __get function to dispatch a call to retrieve a private property
	 *
	 * @since 1.0.0
	 * @param mixed $key The value to retreive about this transaction.
	 */
	public function __get( $key ) {

		if ( method_exists( $this, 'get_' . $key ) ) {

			return call_user_func( array( $this, 'get_' . $key ) );

		} else {

			// translators: The key that could not be retrieved about an transaction.
			return new WP_Error( 'church-tithe-wp-transaction-invalid-property', sprintf( __( 'Can\'t get property %s', 'church-tithe-wp' ), $key ) );

		}

	}

	/**
	 * Creates a transaction
	 *
	 * @since  1.0.0
	 * @param  array $data Array of attributes for a transaction.
	 * @return mixed False if not a valid creation, Transaction ID if user is found or valid creation
	 */
	public function create( $data = array() ) {

		if ( 0 !== $this->id || empty( $data ) ) {
			return false;
		}

		$response = array(
			'success'     => false,
			'code'        => 'unknown',
			'transaction' => null,
		);

		if ( 'initial' === $data['type'] || 'single' === $data['type'] ) {

			// Create a corresponding arrangement for each non-recurring transaction in this transaction.
			$arrangement = new Church_Tithe_WP_Arrangement();

			$arrangement_data = array(
				'initial_gateway_id'      => sanitize_text_field( $data['payment_intent_id'] ),
				'user_id'                 => intval( $data['user_id'] ),
				'initial_amount'          => intval( $data['initial_amount'] ),
				'renewal_amount'          => intval( $data['renewal_amount'] ),
				'currency'                => sanitize_text_field( $data['charged_currency'] ),
				'interval_count'          => intval( $data['interval_count'] ),
				'interval_string'         => sanitize_text_field( $data['interval_string'] ),
				'recurring_status'        => sanitize_text_field( $data['recurring_status'] ),
				'gateway_subscription_id' => sanitize_text_field( $data['gateway_subscription_id'] ),
				'is_live_mode'            => sanitize_text_field( $data['is_live_mode'] ),
			);

			// Add the arrangement id to the data passed to the transaction creation.
			$data['arrangement_id'] = $arrangement->create( $arrangement_data );

			$newly_added_id = $this->db->add( $data );

		} elseif ( 'refund' === $data['type'] ) {

			// If this is a refund, make sure this transaction has not already been refunded.
			$already_refunded = $this->db->get_transactions(
				array(
					'column_values_included' => array(
						'refund_id' => $data['refund_id'],
						'type'      => 'refund',
					),
				)
			);

			if ( ! $already_refunded ) {

				$newly_added_id = $this->db->add( $data );

			} else {

				$newly_updated_id = $this->db->update( $data );

				$response = array(
					'success'     => false,
					'code'        => 'already_refunded',
					'transaction' => new Church_Tithe_WP_Transaction( $already_refunded[0]->id ),
				);

			}
		} else {
			$newly_added_id = $this->db->add( $data );
		}

		// The DB class 'add' implies an update if the transaction being asked to be created already exists.
		if ( isset( $newly_added_id ) && $newly_added_id ) {

			// We've successfully added/updated the transaction, reset the class vars with the new data.
			$transaction = $this->db->get_transaction( $newly_added_id );

			// Setup the transaction data with the values from DB.
			$this->setup_transaction( $transaction );

			$created = $this->id;

			$response = array(
				'success'     => true,
				'code'        => 'transaction_created',
				'transaction' => $transaction,
			);
		} else {
			$response = array(
				'success'     => false,
				'code'        => 'transaction_not_created',
				'transaction' => $this,
			);
		}

		// Put any Arrangement ID that was created by this transaction and attach this transaction id as the initial transaction id.
		if ( 'initial' === $data['type'] && isset( $data['arrangement_id'] ) ) {
			$arrangement = new Church_Tithe_WP_Arrangement( $data['arrangement_id'] );
			$arrangement->update(
				array(
					'initial_transaction_id' => $this->id,
				)
			);
		}

		return $response;

	}

	/**
	 * Update a transaction record
	 *
	 * @since  1.0.0
	 * @param  array $data Array of data attributes for a transaction (checked via whitelist).
	 * @return bool         If the update was successful or not
	 */
	public function update( $data = array() ) {

		if ( empty( $data ) ) {
			return false;
		}

		$data = $this->sanitize_columns( $data );

		$updated = false;

		if ( $this->db->update( $this->id, $data ) ) {

			$transaction = $this->db->get_transaction( $this->id );
			$this->setup_transaction( $transaction );

			$updated = true;

			$response = array(
				'success'     => true,
				'code'        => 'transaction_updated',
				'transaction' => $transaction,
			);

		} else {

			$response = array(
				'success'        => false,
				'code'           => 'transaction_not_updated',
				'transaction_id' => $this,
			);

		}

		return $response;
	}

	/**
	 * Get the arrangement ids of the transaction in an array.
	 *
	 * @since 1.0.0
	 * @return array An array of arrangement IDs for the transaction, or an empty array if none exist.
	 */
	public function get_arrangement_id() {

		return $this->arrangement_id;

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
					if ( ! is_numeric( $data[ $key ] ) || absint( $data[ $key ] !== (int) $data[ $key ] ) ) {
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
