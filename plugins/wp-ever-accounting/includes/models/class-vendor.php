<?php
/**
 * Handle the vendor object.
 *
 * @package     EverAccounting\Models
 * @class       Vendor
 * @version     1.0.2
 */

namespace EverAccounting\Models;

use EverAccounting\Abstracts\Contact;
use EverAccounting\Traits\Attachment;

defined( 'ABSPATH' ) || exit;

/**
 * Class Vendor
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Models
 */
class Vendor extends Contact {
	use Attachment;
	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'vendor';

	/**
	 * Get the vendor if ID is passed, otherwise the vendor is new and empty.
	 *
	 * @since 1.1.0
	 *
	 * @param int|object|Customer $data object to read.
	 *
	 * @throws \Exception If invalid data is passed.
	 */
	public function __construct( $data = 0 ) {
		$this->data = array_merge( $this->data, array( 'type' => 'vendor' ) );
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
			'name'          => __( 'Name', 'wp-ever-accounting' ),
			'currency_code' => __( 'Currency Code', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Get due amount.
	 *
	 * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return float
	 */
	public function get_total_due( $context = 'view' ) {
		return $this->get_meta( 'total_due', $context );
	}

	/**
	 * Get paid amount.
	 *
	 * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return float
	 */
	public function get_total_paid( $context = 'view' ) {
		return $this->get_meta( 'total_paid', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set due.
	 *
	 * @param string $value due amount.
	 */
	public function set_total_due( $value ) {
		$this->update_meta_data( 'total_due', eaccounting_price( $value, null, true ) );
	}

	/**
	 * Set paid.
	 *
	 * @param string $value paid amount.
	 */
	public function set_total_paid( $value ) {
		$this->update_meta_data( 'total_paid', eaccounting_price( $value, null, true ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Non CRUD Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get total paid by a vendor.
	 *
	 * @since 1.1.0
	 * @return float|int|string
	 */
	public function get_calculated_total_paid() {
		global $wpdb;
		$total = wp_cache_get( 'vendor_total_total_paid_' . $this->get_id(), 'ea_vendors' );
		if ( false === $total ) {
			$total        = 0;
			$transactions = $wpdb->get_results( $wpdb->prepare( "SELECT amount, currency_code, currency_rate FROM {$wpdb->prefix}ea_transactions WHERE type='expense' AND contact_id=%d", $this->get_id() ) );
			foreach ( $transactions as $transaction ) {
				$total += eaccounting_price_to_default( $transaction->amount, $transaction->currency_code, $transaction->currency_rate );
			}
			wp_cache_set( 'vendor_total_total_paid_' . $this->get_id(), $total, 'ea_vendors' );
		}

		return $total;
	}

	/**
	 * Get total due by a vendor.
	 *
	 * @since 1.1.0
	 * @return float|int|string
	 */
	public function get_calculated_total_due() {
		global $wpdb;
		$total = wp_cache_get( 'vendor_total_total_due_' . $this->get_id(), 'ea_vendors' );
		if ( false === $total ) {
			$bills = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT id, total amount, currency_code, currency_rate  FROM   {$wpdb->prefix}ea_documents
					   WHERE  status NOT IN ( 'draft', 'cancelled', 'paid' )
					   AND type = 'bill' AND contact_id=%d",
					$this->get_id()
				)
			);

			$total = 0;
			foreach ( $bills as $bill ) {
				$total += eaccounting_price_to_default( $bill->amount, $bill->currency_code, $bill->currency_rate );
			}

			if ( ! empty( $total ) ) {
				$bill_ids = implode( ',', wp_parse_id_list( wp_list_pluck( $bills, 'id' ) ) );
				$revenues = $wpdb->get_results(
					$wpdb->prepare( "SELECT Sum(amount) amount, currency_code, currency_rate FROM   {$wpdb->prefix}ea_transactions WHERE  type = %s AND document_id IN ($bill_ids) GROUP  BY currency_code,currency_rate", 'expense' )
				);

				foreach ( $revenues as $revenue ) {
					$total -= eaccounting_price_to_default( $revenue->amount, $revenue->currency_code, $revenue->currency_rate );
				}
			}
			wp_cache_set( 'vendor_total_total_due_' . $this->get_id(), $total, 'ea_vendors' );
		}

		return $total;
	}
}
