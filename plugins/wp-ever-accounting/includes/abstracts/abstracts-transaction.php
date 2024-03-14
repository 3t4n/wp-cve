<?php
/**
 * Handle the transaction object.
 *
 * @package     EverAccounting\Models
 * @class       Payment
 * @version     1.0.2
 */

namespace EverAccounting\Abstracts;

use EverAccounting\Repositories;
use EverAccounting\Models\Account;
use EverAccounting\Models\Currency;
use EverAccounting\Traits\CurrencyTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Class Transaction
 *
 * @package EverAccounting\Abstracts
 */
abstract class Transaction extends Resource_Model {
	use CurrencyTrait;

	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'transaction';

	/**
	 * This is the name of the repository class.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $cache_group = 'ea_transactions';

	/**
	 * Repository name.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	protected $repository_name = 'transactions';

	/**
	 * Item Data array.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'type'           => 'income',
		'type_id'        => null,
		'payment_date'   => null,
		'amount'         => 0.00,
		'currency_code'  => '', // protected.
		'currency_rate'  => 0.00, // protected.
		'account_id'     => null,
		'document_id'    => null,
		'contact_id'     => null,
		'category_id'    => null,
		'description'    => '',
		'payment_method' => '',
		'reference'      => '',
		'attachment_id'  => null,
		'parent_id'      => 0,
		'reconciled'     => 0,
		'creator_id'     => null,
		'date_created'   => null,
	);

	/**
	 * Get the contact if ID is passed, otherwise the contact is new and empty.
	 *
	 * @param int|object $data object to read.
	 *
	 * @since 1.1.0
	 */
	public function __construct( $data = 0 ) {
		parent::__construct( $data );
		$this->repository = Repositories::load( $this->repository_name );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	|
	| Functions for getting item data. Getter methods wont change anything unless
	| just returning from the props.
	|
	*/


	/**
	 * Transaction type.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_type( $context = 'edit' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Paid at time.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function get_payment_date( $context = 'edit' ) {
		$payment_date = $this->get_prop( 'payment_date', $context );

		return $payment_date ? eaccounting_date( $payment_date, 'Y-m-d' ) : $payment_date;
	}

	/**
	 * Transaction Amount.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_amount( $context = 'edit' ) {
		return $this->get_prop( 'amount', $context );
	}

	/**
	 * Get formatted amount.
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public function get_formatted_amount() {
		return eaccounting_format_price( $this->get_amount(), $this->get_currency_code() );
	}

	/**
	 * Currency code.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_currency_code( $context = 'edit' ) {
		return $this->get_prop( 'currency_code', $context );
	}

	/**
	 * Currency rate.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_currency_rate( $context = 'edit' ) {
		return $this->get_prop( 'currency_rate', $context );
	}

	/**
	 * Transaction from account id.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_account_id( $context = 'edit' ) {
		return $this->get_prop( 'account_id', $context );
	}

	/**
	 * Get document id.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_document_id( $context = 'edit' ) {
		return $this->get_prop( 'document_id', $context );
	}

	/**
	 * Contact id.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_contact_id( $context = 'edit' ) {
		return $this->get_prop( 'contact_id', $context );
	}

	/**
	 * Contact id.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_customer_id( $context = 'edit' ) {
		return $this->get_contact_id( $context );
	}

	/**
	 * Category ID.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_category_id( $context = 'edit' ) {
		return $this->get_prop( 'category_id', $context );
	}

	/**
	 * Description.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_description( $context = 'edit' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Transaction payment methods.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_payment_method( $context = 'edit' ) {
		return $this->get_prop( 'payment_method', $context );
	}

	/**
	 * Transaction reference.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_reference( $context = 'edit' ) {
		return $this->get_prop( 'reference', $context );
	}

	/**
	 * Get attachment url.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_attachment_id( $context = 'edit' ) {
		return $this->get_prop( 'attachment_id', $context );
	}

	/**
	 * Get associated parent payment id.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 * @since 1.0.2
	 */
	public function get_parent_id( $context = 'edit' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Get if reconciled
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 * @since 1.0.2
	 */
	public function get_reconciled( $context = 'edit' ) {
		return (bool) $this->get_prop( 'reconciled', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	|
	| Functions for setting item data. These should not update anything in the
	| database itself and should only change what is stored in the class
	| object.
	*/

	/**
	 * Set contact's email.
	 *
	 * @param string $value Email.
	 *
	 * @since 1.0.2
	 */
	public function set_type( $value ) {
		if ( array_key_exists( $value, eaccounting_get_transaction_types() ) ) {
			$this->set_prop( 'type', $value );
		}
	}

	/**
	 * Set transaction date.
	 *
	 * @param string $value Transaction date.
	 *
	 * @since 1.0.2
	 */
	public function set_payment_date( $value ) {
		$this->set_date_prop( 'payment_date', $value );
	}

	/**
	 * Set transaction amount.
	 *
	 * @param string $value Amount.
	 *
	 * @since 1.0.2
	 */
	public function set_amount( $value ) {
		$this->set_prop( 'amount', eaccounting_format_decimal( $value, 4 ) );
	}

	/**
	 * Set currency code.
	 *
	 * @param string $value Currency code.
	 *
	 * @since 1.0.2
	 */
	public function set_currency_code( $value ) {
		$this->set_prop( 'currency_code', eaccounting_clean( $value ) );
	}

	/**
	 * Set currency rate.
	 *
	 * @param string $value Currency rate.
	 *
	 * @since 1.0.2
	 */
	public function set_currency_rate( $value ) {
		$this->set_prop( 'currency_rate', eaccounting_format_decimal( $value, 8 ) );
	}

	/**
	 * Set account id.
	 *
	 * @param int $value Account id.
	 *
	 * @since 1.0.2
	 */
	public function set_account_id( $value ) {
		$this->set_prop( 'account_id', absint( $value ) );
	}

	/**
	 * Set invoice id.
	 *
	 * @param int $value Invoice id.
	 *
	 * @since 1.0.2
	 */
	public function set_document_id( $value ) {
		$this->set_prop( 'document_id', absint( $value ) );
	}

	/**
	 * Set contact id.
	 *
	 * @param int $value Contact id.
	 *
	 * @since 1.0.2
	 */
	public function set_contact_id( $value ) {
		$this->set_prop( 'contact_id', absint( $value ) );
	}

	/**
	 * Set category id.
	 *
	 * @param int $value Category id.
	 *
	 * @since 1.0.2
	 */
	public function set_category_id( $value ) {
		$this->set_prop( 'category_id', absint( $value ) );
	}

	/**
	 * Set description.
	 *
	 * @param string $value Description.
	 *
	 * @since 1.0.2
	 */
	public function set_description( $value ) {
		$this->set_prop( 'description', eaccounting_clean( $value ) );
	}

	/**
	 * Set payment method.
	 *
	 * @param string $value  Payment method.
	 *
	 * @since 1.0.2
	 */
	public function set_payment_method( $value ) {
		if ( array_key_exists( $value, eaccounting_get_payment_methods() ) ) {
			$this->set_prop( 'payment_method', $value );
		}
	}

	/**
	 * Set reference.
	 *
	 * @param string $value Reference.
	 *
	 * @since 1.0.2
	 */
	public function set_reference( $value ) {
		$this->set_prop( 'reference', eaccounting_clean( $value ) );
	}

	/**
	 * Set attachment.
	 *
	 * @param string $value  Attachment ID.
	 *
	 * @since 1.0.2
	 */
	public function set_attachment_id( $value ) {
		$this->set_prop( 'attachment_id', intval( $value ) );
	}

	/**
	 * Set parent id.
	 *
	 * @param string $value  Parent id.
	 *
	 * @since 1.0.2
	 */
	public function set_parent_id( $value ) {
		$this->set_prop( 'parent_id', absint( $value ) );
	}

	/**
	 * Set if reconciled.
	 *
	 * @param string $value  yes or no.
	 *
	 * @since 1.0.2
	 */
	public function set_reconciled( $value ) {
		$this->set_prop( 'reconciled', absint( $value ) );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD methods
	|--------------------------------------------------------------------------
	|
	| Methods which create, read, update and delete discounts from the database.
	|
	*/
	/**
	 * Save should create or update based on object existence.
	 *
	 * @return bool
	 * @throws \Exception When invalid data is found.
	 *
	 * @since  1.1.0
	 */
	public function save() {
		// If account id is changing need to update currency.
		if ( array_key_exists( 'account_id', $this->get_changes() ) || ! $this->exists() ) {
			$account = new Account( $this->get_account_id() );
			$this->set_currency_code( $account->get_currency_code() );
		}

		if ( array_key_exists( 'currency_code', $this->get_changes() ) || ! $this->exists() ) {
			$currency = new Currency( $this->get_currency_code() );
			$this->set_currency_rate( $currency->get_rate() );
		}

		// saving same.
		return parent::save();
	}
}
