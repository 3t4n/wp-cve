<?php
/**
 * Handle the invoice object.
 *
 * @package     EverAccounting\Models
 * @class       Invoice
 * @version     1.1.0
 */

namespace EverAccounting\Models;

use \EverAccounting\Abstracts\Document;

defined( 'ABSPATH' ) || exit;

/**
 * Class Invoice
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Models
 */
class Invoice extends Document {
	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'invoice';

	/**
	 * Transaction status.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $status_transition = array();

	/**
	 * Get the invoice if ID is passed, otherwise the account is new and empty.
	 *
	 * @since 1.1.0
	 *
	 * @param int|object|Invoice $invoice object to read.
	 */
	public function __construct( $invoice = 0 ) {
		$this->data = array_merge( $this->data, array( 'type' => 'invoice' ) );
		parent::__construct( $invoice );

		if ( $invoice instanceof self ) {
			$this->set_id( $invoice->get_id() );
		} elseif ( is_numeric( $invoice ) ) {
			$this->set_id( $invoice );
		} elseif ( ! empty( $invoice->id ) ) {
			$this->set_id( $invoice->id );
		} elseif ( is_array( $invoice ) ) {
			$this->set_props( $invoice );
		} else {
			$this->set_object_read( true );
		}

		if ( $this->get_id() > 0 ) {
			$this->repository->read( $this );
		}

		if ( 'invoice' !== $this->get_type() ) {
			$this->set_id( 0 );
			$this->set_defaults();
		}

		$this->required_props = array(
			'currency_code' => esc_html__( 'Currency', 'wp-ever-accounting' ),
			'category_id'   => esc_html__( 'Category', 'wp-ever-accounting' ),
			'contact_id'    => esc_html__( 'Customer', 'wp-ever-accounting' ),
			'issue_date'    => esc_html__( 'Issue date', 'wp-ever-accounting' ),
			'due_date'      => esc_html__( 'Due date', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Get supported statuses.
	 *
	 * @since 1.1.0
	 * @return array
	 */
	public function get_statuses() {
		return eaccounting_get_invoice_statuses();
	}

	/**
	|--------------------------------------------------------------------------
	| CRUD methods
	|--------------------------------------------------------------------------
	|
	 */

	/**
	 * Generate document number.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function maybe_set_invoice_number() {
		if ( empty( $this->get_invoice_number() ) ) {
			$number = $this->get_id();
			if ( empty( $number ) ) {
				$number = $this->repository->get_next_number( $this );
			}
			$this->set_document_number( $this->generate_number( $number ) );
		}
	}

	/**
	 * Generate number.
	 *
	 * @since 1.1.0
	 *
	 * @param int $number Number.
	 *
	 * @return string
	 */
	public function generate_number( $number ) {
		$prefix           = eaccounting()->settings->get( 'invoice_prefix', 'INV-' );
		$padd             = (int) eaccounting()->settings->get( 'invoice_digit', '5' );
		$formatted_number = zeroise( absint( $number ), $padd );
		$number           = apply_filters( 'eaccounting_generate_invoice_number', $prefix . $formatted_number );

		return $number;
	}
	/**
	 * Set the document key.
	 *
	 * @since 1.1.0
	 */
	public function maybe_set_key() {
		$key = $this->get_key();
		if ( empty( $key ) ) {
			$this->set_key( $this->generate_key() );
		}
	}

	/**
	 * Generate key.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function generate_key() {
		$key = 'ea-' . apply_filters( 'eaccounting_generate_invoice_key', 'invoice-' . str_replace( '-', '', wp_generate_uuid4() ) );
		return strtolower( sanitize_key( $key ) );
	}

	/**
	 * Conditionally change status
	 *
	 * @since 1.1.0
	 */
	public function maybe_set_payment_date() {
		if ( $this->is_status( 'paid' ) && empty( $this->get_payment_date() ) ) {
			$this->set_payment_date( time() );
		} else {
			$this->set_payment_date( '' );
		}

	}
	/**
	 * Save should create or update based on object existence.
	 *
	 * @since  1.1.0
	 *
	 * @return \Exception|bool
	 */
	public function save() {
		$this->maybe_set_invoice_number();
		$this->maybe_set_key();
		$this->calculate_totals();
		parent::save();
		$this->status_transition();
		return $this->exists();
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	|
	| Methods for getting data from the invoice object.
	|
	*/
	/**
	 * Return the document number.
	 *
	 * @since  1.1.0
	 *
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return string
	 */
	public function get_invoice_number( $context = 'edit' ) {
		return $this->get_prop( 'document_number', $context );
	}

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
	|
	| Functions for setting boll data. These should not update anything in the
	| database itself and should only change what is stored in the class
	| object.
	|
	*/
	/**
	 * set the customer id.
	 *
	 * @since  1.1.0
	 *
	 * @param int $customer_id .
	 */
	public function set_customer_id( $customer_id ) {
		parent::set_contact_id( $customer_id );
		if ( $this->get_contact_id() && ( ! $this->exists() || array_key_exists( 'contact_id', $this->changes ) ) ) {
			$contact = eaccounting_get_customer( $this->get_contact_id() );
			$address = $this->data['address'];
			foreach ( $address as $prop => $value ) {
				$getter = "get_{$prop}";
				$setter = "set_{$prop}";
				if ( is_callable( array( $contact, $getter ) ) && is_callable( array( $this, $setter ) ) && is_callable( array( $this, $getter ) ) ) {
					$this->$setter( $contact->$getter() );
				}
			}
		}
	}

	/**
	 * Set invoice status.
	 *
	 * @since 1.1.0
	 * @param string $new_status    Status to change the order to. No internal prefix is required.
	 * @return array
	 */
	public function set_status( $new_status ) {
		$result = parent::set_status( $new_status );
		if ( true === $this->object_read && ! empty( $result['from'] ) && $result['from'] !== $result['to'] ) {
			$this->status_transition = array(
				'from' => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $result['from'],
				'to'   => $result['to'],
			);
			$this->maybe_set_payment_date();
		}

		return $result;
	}

	/*
	|--------------------------------------------------------------------------
	| Invoice Item Handling
	|--------------------------------------------------------------------------
	|
	| Invoice items are used for products, taxes, shipping, and fees within
	| each order.
	*/

	/**
	 * Adds an item to the invoice.
	 *
	 * @param array $args Item data.
	 *
	 * @return bool
	 */
	public function add_item( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'item_id' => null,
				'line_id' => null,
			)
		);

		// check if we have item id or line_id.
		if ( empty( $args['item_id'] ) && empty( $args['line_id'] ) ) {
			return false;
		}

		// first check if we get line id if so then its from database.
		$line_item = new Document_Item();
		if ( $this->get_item( $args['line_id'] ) ) {
			$line_item = $this->items[ $args['line_id'] ];
		}

		if ( ! empty( $args['item_id'] ) ) {
			$product = new Item( $args['item_id'] );
			if ( $product->exists() ) {
				// convert the price from default to invoice currency.
				$default_currency = eaccounting_get_default_currency();
				$default          = array(
					'item_id'       => $product->get_id(),
					'item_name'     => $product->get_name(),
					'price'         => $product->get_purchase_price(),
					'currency_code' => $default_currency,
					'quantity'      => 1,
					'tax_rate'      => eaccounting_tax_enabled() ? $product->get_purchase_tax() : 0,
				);

				$args = wp_parse_args( $args, $default );
			}
		}

		$line_item->set_props( $args );

		if ( empty( $line_item->get_item_id() ) ) {
			return false;
		}

		if ( $line_item->get_currency_code() && ( $line_item->get_currency_code() !== $this->get_currency_code() ) ) {
			$converted = eaccounting_price_convert( $line_item->get_price(), $line_item->get_currency_code(), $this->get_currency_code() );
			$line_item->set_price( $converted );
		}

		foreach ( $this->get_items()  as $key => $item ) {
			if ( ! $line_item->get_id() && ( $item->get_item_id() === $line_item->get_item_id() ) ) {
				$item->increment_quantity( $line_item->get_quantity() );
				return $key;
			}
		}

		$key                 = $line_item->exists() ? $line_item->get_id() : 'new:' . count( $this->items );
		$this->items[ $key ] = $line_item;

		return $key;
	}

	/*
	|--------------------------------------------------------------------------
	| Notes
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get invoice notes.
	 *
	 * @since 1.1.0
	 *
	 * @param array $args Arguments to pass to get_comments.
	 *
	 * @return array|int|void
	 */
	public function get_notes( $args = array() ) {
		if ( ! $this->exists() ) {
			return array();
		}

		return eaccounting_get_notes(
			array_merge(
				$args,
				array(
					'parent_id' => $this->get_id(),
					'types'     => 'invoice',
				)
			)
		);
	}

	/**
	 * Add invoice note.
	 *
	 * @since 1.1.0
	 *
	 * @param string $note Note content.
	 *
	 * @return Note|false|int|\WP_Error
	 */
	public function add_note( $note ) {
		if ( ! $this->exists() ) {
			return false;
		}

		$creator_id = 0;
		// If this is an admin comment or it has been added by the user.
		if ( is_user_logged_in() ) {
			$creator_id = get_current_user_id();
		}

		return eaccounting_insert_note(
			array(
				'parent_id'  => $this->get_id(),
				'type'       => 'invoice',
				'note'       => $note,
				'creator_id' => $creator_id,
			)
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Payments
	|--------------------------------------------------------------------------
	*/

	/**
	 * Add a payment.
	 *
	 * @since 1.1.0
	 *
	 * @param array $args Arguments to pass to get_comments.
	 *
	 * @throws \Exception When payment not found.
	 * @return false
	 */
	public function add_payment( $args = array() ) {
		if ( ! $this->needs_payment() ) {
			return false;
		}
		$args = wp_parse_args(
			$args,
			array(
				'date'           => '',
				'amount'         => '',
				'account_id'     => '',
				'payment_method' => '',
				'description'    => '',
			)
		);

		if ( ! $this->exists() ) {
			return false;
		}

		if ( empty( $args['date'] ) ) {
			$args['date'] = current_time( 'mysql' );
		}

		if ( empty( $args['amount'] ) ) {
			throw new \Exception( __( 'Payment amount is required', 'wp-ever-accounting' ) );
		}

		if ( empty( $args['account_id'] ) ) {
			throw new \Exception( __( 'Payment account is required', 'wp-ever-accounting' ) );
		}

		if ( empty( $args['payment_method'] ) ) {
			throw new \Exception( __( 'Payment method is required', 'wp-ever-accounting' ) );
		}

		$amount           = eaccounting_price( $args['amount'], $this->get_currency_code(), true );
		$account          = eaccounting_get_account( $args['account_id'] );
		$currency         = eaccounting_get_currency( $account->get_currency_code() );
		$converted_amount = eaccounting_price_convert( $amount, $this->get_currency_code(), $currency->get_code(), $this->get_currency_rate(), $currency->get_rate() );
		$income           = new Revenue();
		$income->set_props(
			array(
				'payment_date'   => $args['date'],
				'document_id'    => $this->get_id(),
				'account_id'     => absint( $args['account_id'] ),
				'amount'         => $converted_amount,
				'category_id'    => $this->get_category_id(),
				'customer_id'    => $this->get_contact_id(),
				'payment_method' => eaccounting_clean( $args['payment_method'] ),
				'description'    => eaccounting_clean( $args['description'] ),
				/* translators: %s: invoice number */
				'reference'      => sprintf( __( 'Invoice Payment #%d', 'wp-ever-accounting' ), $this->get_id() ),
			)
		);

		$income->save();
		$methods = eaccounting_get_payment_methods();
		$method  = $methods[ $income->get_payment_method() ];
		/* translators: %s amount */
		$this->add_note( sprintf( __( 'Paid %1$s by %2$s', 'wp-ever-accounting' ), eaccounting_price( $args['amount'], $this->get_currency_code() ), $method ) );
		$this->save();
		return true;
	}
	/**
	 * Get payments.
	 *
	 * @since 1.1.0
	 *
	 * @return Payment[] Array of payments.
	 */
	public function get_payments() {
		if ( $this->exists() ) {
			return eaccounting_get_revenues(
				array(
					'document_id' => $this->get_id(),
					'number'      => '-1',
				)
			);
		}

		return array();
	}


	/*
	|--------------------------------------------------------------------------
	| Calculations
	|--------------------------------------------------------------------------
	*/
	/**
	 * Get total due.
	 *
	 * @since 1.1.0
	 *
	 * @return float|int
	 */
	public function get_total_due() {
		$due = eaccounting_price( ( $this->get_total() - $this->get_total_paid() ), $this->get_currency_code(), true );
		if ( eaccounting_price_to_default( $due, $this->get_currency_code(), $this->get_currency_rate() ) <= 0 ) {
			$due = 0;
		}

		return $due;
	}

	/**
	 * Get total paid
	 *
	 * @since 1.1.0
	 *
	 * @return float|int|string
	 */
	public function get_total_paid() {
		$total_paid = 0;
		foreach ( $this->get_payments() as $payment ) {
			$total_paid += (float) eaccounting_price_convert( $payment->get_amount(), $payment->get_currency_code(), $this->get_currency_code(), $payment->get_currency_rate(), $this->get_currency_rate() );
		}

		return $total_paid;
	}

	/**
	 * Calculate total.
	 *
	 * @since 1.1.0
	 * @throws \Exception When invoice not found.
	 */
	public function calculate_totals() {
		$subtotal       = 0;
		$total_tax      = 0;
		$total_discount = 0;
		$total_fees     = 0;
		$total_shipping = 0;
		$discount_rate  = $this->get_discount();

		// before calculating need to know subtotal so we can apply fixed discount.
		if ( $this->is_fixed_discount() ) {
			$subtotal_discount = 0;
			foreach ( $this->get_items() as $item ) {
				$subtotal_discount += ( $item->get_price() * $item->get_quantity() );
			}
			if ( $subtotal_discount > 0 ) {
				$discount_rate = ( ( $this->get_discount() * 100 ) / $subtotal_discount );
			}
		}

		foreach ( $this->get_items() as $item ) {
			$item_subtotal         = ( $item->get_price() * $item->get_quantity() );
			$item_discount         = $item_subtotal * ( $discount_rate / 100 );
			$item_subtotal_for_tax = $item_subtotal - $item_discount;
			$item_tax_rate         = ( $item->get_tax_rate() / 100 );
			$item_tax              = eaccounting_calculate_tax( $item_subtotal_for_tax, $item_tax_rate, $this->is_tax_inclusive() );
			$item_shipping         = $item->get_shipping();
			$item_shipping_tax     = $item->get_shipping_tax();
			$item_fees             = $item->get_fees();
			$item_fees_tax         = $item->get_fees_tax();
			if ( 'tax_subtotal_rounding' !== eaccounting()->settings->get( 'tax_subtotal_rounding', 'tax_subtotal_rounding' ) ) {
				$item_tax = eaccounting_format_decimal( $item_tax, 2 );
			}
			if ( $this->is_tax_inclusive() ) {
				$item_subtotal -= $item_tax;
			}
			$item_total = $item_subtotal - $item_discount + $item_tax;
			if ( $item_total < 0 ) {
				$item_total = 0;
			}

			$item->set_subtotal( $item_subtotal );
			$item->set_discount( $item_discount );
			$item->set_tax( $item_tax );
			$item->set_total( $item_total );

			$subtotal       += $item->get_subtotal();
			$total_tax      += $item->get_tax();
			$total_tax      += ( $item_fees_tax + $item_shipping_tax );
			$total_discount += $item->get_discount();
			$total_shipping += $item_shipping;
			$total_fees     += $item_fees;
		}

		$this->set_subtotal( $subtotal );
		$this->set_total_tax( $total_tax );
		$this->set_total_discount( $total_discount );
		$this->set_total_shipping( $total_shipping );
		$this->set_total_fees( $total_fees );
		$total = $this->get_subtotal() - $this->get_total_discount() + $this->get_total_tax() + $this->get_total_fees() + $this->get_total_shipping();
		$total = eaccounting_price( $total, $this->get_currency_code(), true );
		if ( $total < 0 ) {
			$total = 0;
		}

		$this->set_total( $total );
		if ( ( ! empty( $this->get_total_paid() ) && $this->get_total_due() > 0 ) ) {
			$this->set_status( 'partial' );
		} elseif ( empty( $this->get_total_due() ) ) {
			$this->set_status( 'paid' );
		} elseif ( $this->is_due() && $this->is_status( 'pending' ) ) {
			$this->set_status( 'overdue' );
		} elseif ( in_array( $this->get_status(), array( 'partial', 'paid' ), true ) ) {
			$this->set_status( 'received' );
		}

		return array(
			'subtotal'       => $this->get_subtotal(),
			'total_tax'      => $this->get_total_tax(),
			'total_shipping' => $this->get_total_shipping(),
			'total_fees'     => $this->get_total_fees(),
			'total_discount' => $this->get_total_discount(),
			'total'          => $this->get_total(),
		);
	}

	/**
	|--------------------------------------------------------------------------
	| Status handling.
	|--------------------------------------------------------------------------
	 */

	/**
	 * Set paid.
	 *
	 * @since 1.1.0
	 * @return bool|\Exception True on success, exception on failure.
	 * @throws \Exception When invoice not found.
	 */
	public function set_paid() {
		if ( ! $this->get_id() ) { // Order must exist.
			return false;
		}

		try {
			$default_account = eaccounting()->settings->get( 'default_account' );
			$payment_method  = eaccounting()->settings->get( 'default_payment_method', 'cash' );
			if ( empty( $default_account ) ) {
				throw new \Exception( __( 'Default account is not set, invoice status was not changed', 'wp-ever-accounting' ) );
			}
			$due = $this->get_total_due();
			if ( $due > 0 ) {
				$this->add_payment(
					array(
						'payment_date'   => time(),
						'account_id'     => absint( $default_account ),
						'amount'         => $due,
						'category_id'    => $this->get_category_id(),
						'customer_id'    => $this->get_contact_id(),
						'payment_method' => $payment_method,
					)
				);
			}
		} catch ( \Exception $e ) {
			$this->add_note( $e->getMessage() );
			return false;
		}

		return true;
	}

	/**
	 * Refund.
	 *
	 * @since 1.1.0
	 */
	public function set_refunded() {
		if ( ! $this->get_id() ) { // Order must exist.
			return false;
		}

		try {
			if ( $this->get_total_paid() > 0 ) {
				$this->add_note(
					sprintf(
					/* translators: %s amount */
						__( 'Removed %s payment', 'wp-ever-accounting' ),
						eaccounting_price( $this->get_total_paid(), $this->get_currency_code() )
					)
				);
			}
			$this->delete_payments();
			$this->set_status( 'refunded' );
			return $this->save();
		} catch ( \Exception $e ) {
			$this->add_note( $e->getMessage() );
			return false;
		}
	}

	/**
	 * Set cancelled.
	 *
	 * @since 1.1.0
	 * @return bool|\Exception
	 */
	public function set_cancelled() {
		if ( ! $this->get_id() ) { // Order must exist.
			return false;
		}

		try {
			if ( $this->get_total_paid() > 0 ) {
				$this->add_note(
					sprintf(
					/* translators: %s amount */
						__( 'Removed %s payment', 'wp-ever-accounting' ),
						eaccounting_price( $this->get_total_paid(), $this->get_currency_code() )
					)
				);
			}
			$this->delete_payments();
			$this->set_status( 'cancelled' );

			return $this->save();
		} catch ( \Exception $e ) {
			$this->add_note( $e->getMessage() );

			return false;
		}
	}

	/**
	 * Handle the status transition.
	 */
	protected function status_transition() {
		$status_transition = $this->status_transition;

		// Reset status transition variable.
		$this->status_transition = false;
		if ( $status_transition ) {
			try {
				do_action( 'eaccounting_invoice_status_' . $status_transition['to'], $this->get_id(), $this );

				if ( $status_transition['from'] !== $status_transition['to'] ) {
					/* translators: 1: old order status 2: new order status */
					$transition_note = sprintf( __( 'Status changed from %1$s to %2$s.', 'wp-ever-accounting' ), $status_transition['from'], $status_transition['to'] );

					// Note the transition occurred.
					$this->add_note( $transition_note );

					do_action( 'eaccounting_invoice_status_' . $status_transition['from'] . '_to_' . $status_transition['to'], $this->get_id(), $this );
					do_action( 'eaccounting_invoice_status_changed', $this->get_id(), $status_transition['from'], $status_transition['to'], $this );

					// Work out if this was for a payment, and trigger a payment_status hook instead.
					if (
						in_array( $status_transition['from'], array( 'cancelled', 'pending', 'viewed', 'approved', 'overdue', 'unpaid' ), true )
						&& in_array( $status_transition['to'], array( 'paid', 'partial' ), true )
					) {
						do_action( 'eaccounting_invoice_payment_status_changed', $this, $status_transition );
					}
				}
			} catch ( \Exception $e ) {
				$this->add_note( __( 'Error during status transition.', 'wp-ever-accounting' ) . ' ' . $e->getMessage() );
			}
		}
	}

	/*
	|--------------------------------------------------------------------------
	| URLs.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Admin edit URL.
	 *
	 * @param string $action Action.
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_admin_url( $action = 'view' ) {
		$url = admin_url( 'admin.php?page=ea-sales&tab=invoices&invoice_id=' . $this->get_id() );
		return add_query_arg( 'action', $action, $url );
	}

	/**
	 * Get invoice url.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function get_url() {
		$base = eaccounting_get_parmalink_base();
		$url  = site_url( $base );
		$url  = untrailingslashit( $url ) . '/invoice/' . $this->get_id() . '/' . $this->get_key();
		return $url;
	}
}
