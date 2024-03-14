<?php
/**
 * Handle the Revenue object.
 *
 * @package     EverAccounting\Models
 * @class       Revenue
 * @version     1.0.2
 */

namespace EverAccounting\Models;

use EverAccounting\Traits\Attachment;
use EverAccounting\Abstracts\Transaction;

defined( 'ABSPATH' ) || exit;

/**
 * Class Revenue
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Models
 */
class Revenue extends Transaction {
	use Attachment;
	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'revenue';

	/**
	 * Get the Revenue if ID is passed, otherwise the Revenue is new and empty.
	 *
	 * @since 1.1.0
	 *
	 * @param int|object|Revenue $data object to read.
	 */
	public function __construct( $data = 0 ) {
		$this->data = array_merge( $this->data, array( 'type' => 'income' ) );
		parent::__construct( $data );

		if ( $data instanceof self ) {
			$this->set_id( $data->get_id() );
		} elseif ( is_numeric( $data ) ) {
			$this->set_id( $data );
		} elseif ( ! empty( $data->id ) ) {
			$this->set_id( $data->id );
		} elseif ( is_array( $data ) ) {
			$this->set_props( $data );
		} else {
			$this->set_object_read( true );
		}

		if ( $this->get_id() > 0 ) {
			$this->repository->read( $this );
		}

		$this->required_props = array(
			'type'           => __( 'Type', 'wp-ever-accounting' ),
			'payment_date'   => __( 'Revenue Date', 'wp-ever-accounting' ),
			'account_id'     => __( 'Account ID', 'wp-ever-accounting' ),
			'category_id'    => __( 'Category ID', 'wp-ever-accounting' ),
			'payment_method' => __( 'Payment Method', 'wp-ever-accounting' ),
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Return the customer id.
	 *
	 * @since  1.1.0
	 *
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return string
	 */
	public function get_customer_id( $context = 'edit' ) {
		return $this->get_prop( 'contact_id', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/
	/**
	 * set the customer id.
	 *
	 * @since  1.1.0
	 *
	 * @param int $customer_id .
	 */
	public function set_customer_id( $customer_id ) {
		$this->set_prop( 'contact_id', absint( $customer_id ) );
	}
}
