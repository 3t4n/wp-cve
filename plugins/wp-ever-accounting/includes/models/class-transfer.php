<?php
/**
 * Handle the transfer object.
 *
 * @package     EverAccounting\Models
 * @class       Transfer
 * @version     1.0.2
 */

namespace EverAccounting\Models;

use EverAccounting\Abstracts\Resource_Model;
use EverAccounting\Repositories;

defined( 'ABSPATH' ) || exit;

/**
 * Class Transfer
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Models
 */
class Transfer extends Resource_Model {
	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'transfer';

	/**
	 * Cache group.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $cache_group = 'ea_transfers';

	/**
	 * Item Data array.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'date'            => null,
		'from_account_id' => null,
		'amount'          => null,
		'to_account_id'   => null,
		'income_id'       => null,
		'expense_id'      => null,
		'payment_method'  => null,
		'reference'       => null,
		'description'     => null,
		'creator_id'      => null,
		'date_created'    => null,
	);

	/**
	 * Category id.
	 *
	 * @since 1.1.0
	 * @var int
	 */
	protected $category_id;

	/**
	 * Get the account if ID is passed, otherwise the account is new and empty.
	 *
	 * @since 1.1.0
	 *
	 * @param int|object|Account $data object to read.
	 */
	public function __construct( $data = 0 ) {
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

		$this->repository = Repositories::load( 'transfers' );

		if ( $this->get_id() > 0 ) {
			$this->repository->read( $this );
		}

		$this->required_props = array(
			'date'            => __( 'Transfer Date', 'wp-ever-accounting' ),
			'from_account_id' => __( 'From account ID', 'wp-ever-accounting' ),
			'to_account_id'   => __( 'To account ID', 'wp-ever-accounting' ),
			'amount'          => __( 'Transfer amount', 'wp-ever-accounting' ),
			'payment_method'  => __( 'Payment method', 'wp-ever-accounting' ),
		);
	}
	/**
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	 */

	/**
	 * Income ID.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_income_id( $context = 'edit' ) {
		return $this->get_prop( 'income_id', $context );
	}

	/**
	 * Expense ID.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_expense_id( $context = 'edit' ) {
		return $this->get_prop( 'expense_id', $context );
	}

	/**
	 * Transaction payment methods.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_from_account_id( $context = 'edit' ) {
		return $this->get_prop( 'from_account_id', $context );
	}


	/**
	 * Transaction payment methods.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_to_account_id( $context = 'edit' ) {
		return $this->get_prop( 'to_account_id', $context );
	}

	/**
	 * Transaction payment methods.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_amount( $context = 'edit' ) {
		return $this->get_prop( 'amount', $context );
	}

	/**
	 * Transaction payment methods.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \EverAccounting\DateTime
	 */
	public function get_date( $context = 'edit' ) {
		return $this->get_prop( 'date', $context );
	}

	/**
	 * Transaction payment methods.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_payment_method( $context = 'edit' ) {
		return $this->get_prop( 'payment_method', $context );
	}

	/**
	 * Description.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_reference( $context = 'edit' ) {
		return $this->get_prop( 'reference', $context );
	}

	/**
	 * Description.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_description( $context = 'edit' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * get currency_code.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_currency_code( $context = 'edit' ) {
		return $this->get_prop( 'currency_code', $context );
	}

	/**
	 * Get transfer category.
	 *
	 * @since 1.1.0
	 *
	 * @return integer
	 */
	public function get_category_id() {
		return absint( $this->category_id );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set income id.
	 *
	 * @since 1.0.2
	 *
	 * @param int $value income_id.
	 */
	public function set_income_id( $value ) {
		$this->set_prop( 'income_id', absint( $value ) );
	}

	/**
	 * Set expense id.
	 *
	 * @since 1.0.2
	 *
	 * @param int $value expense_id.
	 */
	public function set_expense_id( $value ) {
		$this->set_prop( 'expense_id', absint( $value ) );
	}

	/**
	 * Set from account id.
	 *
	 * @since 1.0.2
	 *
	 * @param int $account_id account_id.
	 */
	public function set_from_account_id( $account_id ) {
		$this->set_prop( 'from_account_id', absint( $account_id ) );
	}

	/**
	 * Set to account id.
	 *
	 * @since 1.0.2
	 *
	 * @param int $account_id account_id.
	 */
	public function set_to_account_id( $account_id ) {
		$this->set_prop( 'to_account_id', absint( $account_id ) );
	}

	/**
	 * Set date.
	 *
	 * @since 1.0.2
	 *
	 * @param string $date date.
	 */
	public function set_date( $date ) {
		$this->set_date_prop( 'date', eaccounting_clean( $date ) );
	}

	/**
	 * Set amount.
	 *
	 * @since 1.0.2
	 *
	 * @param string $amount amount.
	 */
	public function set_amount( $amount ) {
		$this->set_prop( 'amount', (float) eaccounting_sanitize_number( $amount, true ) );
	}

	/**
	 * Set payment method.
	 *
	 * @since 1.0.2
	 *
	 * @param string $payment_method payment_method.
	 */
	public function set_payment_method( $payment_method ) {
		$this->set_prop( 'payment_method', eaccounting_clean( $payment_method ) );
	}

	/**
	 * Set reference.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value reference.
	 */
	public function set_reference( $value ) {
		$this->set_prop( 'reference', eaccounting_clean( $value ) );
	}

	/**
	 * Set description.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value description.
	 */
	public function set_description( $value ) {
		$this->set_prop( 'description', eaccounting_clean( $value ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Extra
	|--------------------------------------------------------------------------
	*/
	/**
	 * Get formatted transaction amount.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_formatted_amount() {
		return eaccounting_format_price( $this->get_amount(), $this->get_currency_code() );
	}


	/**
	 * Save should create or update based on object existence.
	 *
	 * @since  1.1.0
	 *
	 * @throws \Exception When invalid data is found.
	 * @return \Exception|bool
	 */
	public function save() {
		if ( ! $this->get_from_account_id() || ! $this->get_to_account_id() ) {
			throw new \Exception( __( 'Transfer from and to account can not be same.', 'wp-ever-accounting' ) );
		}

		$this->maybe_set_transfer_category();

		return parent::save();
	}


	/**
	 * Set transfer category.
	 *
	 * @since 1.1.0
	 *
	 * @throws \Exception When invalid data is found.
	 */
	protected function maybe_set_transfer_category() {
		global $wpdb;
		$cache_key   = md5( 'other' . __( 'Transfer', 'wp-ever-accounting' ) );
		$category_id = wp_cache_get( $cache_key, 'ea_categories' );
		if ( false === $category_id ) {
			$category_id = $wpdb->get_var( $wpdb->prepare( "SELECT id from {$wpdb->prefix}ea_categories WHERE type=%s AND name=%s", 'other', __( 'Transfer', 'wp-ever-accounting' ) ) );
			wp_cache_add( $cache_key, $category_id, 'eaccounting_categories' );
		}
		if ( empty( $category_id ) ) {
			throw new \Exception(
				sprintf(
				/* translators: %s: category name %s: category type */
					__( 'Transfer category is missing please create a category named "%1$s" and type"%2$s".', 'wp-ever-accounting' ),
					__( 'Transfer', 'wp-ever-accounting' ),
					'other'
				)
			);
		}

		$this->category_id = $category_id;
	}
}
