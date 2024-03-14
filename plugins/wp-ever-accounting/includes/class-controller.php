<?php
/**
 * Controller various actions of the plugin.
 *
 * @version     1.1.0
 * @subpackage  Classes
 * @package     EverAccounting
 */

use EverAccounting\Models\Account;
use EverAccounting\Models\Category;
use EverAccounting\Models\Payment;
use EverAccounting\Models\Revenue;

defined( 'ABSPATH' ) || exit();

/**
 * Class Controller
 *
 * @since 1.1.0
 */
class Controller {

	/**
	 * Controller constructor.
	 */
	public function __construct() {
		// accounts.
		add_action( 'eaccounting_pre_save_account', array( $this, 'validate_account_data' ), 10, 2 );
		add_action( 'eaccounting_delete_account', array( $this, 'delete_account_reference' ) );

		// customers.
		add_action( 'eaccounting_delete_revenue', array( $this, 'update_customer_total_paid' ), 10, 2 );
		add_action( 'eaccounting_insert_revenue', array( $this, 'update_customer_total_paid' ), 10, 2 );
		add_action( 'eaccounting_update_revenue', array( $this, 'update_customer_total_paid' ), 10, 2 );
		add_action( 'eaccounting_insert_invoice', array( $this, 'update_customer_total_paid' ), 10, 2 );
		add_action( 'eaccounting_update_invoice', array( $this, 'update_customer_total_paid' ), 10, 2 );
		add_action( 'eaccounting_delete_invoice', array( $this, 'update_customer_total_paid' ), 10, 2 );
		add_action( 'eaccounting_delete_customer', array( $this, 'delete_customer_reference' ) );

		// vendors.
		add_action( 'eaccounting_delete_payment', array( $this, 'update_vendor_total_paid' ), 10, 2 );
		add_action( 'eaccounting_insert_payment', array( $this, 'update_vendor_total_paid' ), 10, 2 );
		add_action( 'eaccounting_update_payment', array( $this, 'update_vendor_total_paid' ), 10, 2 );
		add_action( 'eaccounting_insert_bill', array( $this, 'update_vendor_total_paid' ), 10, 2 );
		add_action( 'eaccounting_update_bill', array( $this, 'update_vendor_total_paid' ), 10, 2 );
		add_action( 'eaccounting_delete_bill', array( $this, 'update_vendor_total_paid' ), 10, 2 );
		add_action( 'eaccounting_delete_vendor', array( $this, 'delete_vendor_reference' ) );

		// payments.
		add_action( 'eaccounting_validate_payment_data', array( $this, 'validate_payment_data' ), 10, 2 );

		// revenues.
		add_action( 'eaccounting_validate_revenue_data', array( $this, 'validate_revenue_data' ), 10, 2 );

		// category.
		add_action( 'eaccounting_pre_save_category', array( $this, 'validate_category_data' ), 10, 2 );
		add_action( 'eaccounting_delete_category', array( $this, 'delete_category_reference' ) );

		// currency.
		add_action( 'update_option_eaccounting_settings', array( $this, 'update_default_currency' ), 10, 2 );
		add_action( 'eaccounting_delete_currency', array( $this, 'delete_currency_reference' ), 10, 2 );

		// bill.
		add_action( 'eaccounting_delete_payment', array( $this, 'update_bill_data' ), 10, 2 );
		add_action( 'eaccounting_update_payment', array( $this, 'update_bill_data' ), 10, 2 );
		add_action( 'eaccounting_daily_scheduled_events', array( $this, 'update_bill_status' ) );

		// invoice.
		add_action( 'eaccounting_delete_revenue', array( $this, 'update_invoice_data' ), 10, 2 );
		add_action( 'eaccounting_update_revenue', array( $this, 'update_invoice_data' ), 10, 2 );
		add_action( 'eaccounting_daily_scheduled_events', array( $this, 'update_invoice_status' ) );

		// thumbnail.
		add_action( 'delete_attachment', array( $this, 'delete_attachment_reference' ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Account
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting account
	*/

	/**
	 * Validate account data.
	 *
	 * @param array $data Account data.
	 * @param int   $id Account ID.
	 *
	 * @since 1.1.0
	 * @throws \Exception  Exception.
	 */
	public static function validate_account_data( $data, $id ) {
		global $wpdb;
		if ( $id != (int) $wpdb->get_var( $wpdb->prepare( "SELECT id from {$wpdb->prefix}ea_accounts WHERE number='%s'", eaccounting_clean( $data['number'] ) ) ) ) { // @codingStandardsIgnoreLine
			throw new \Exception( __( 'Duplicate account.', 'wp-ever-accounting' ) );
		}

	}

	/**
	 * When an account is deleted check if
	 * default account need to be updated or not.
	 *
	 * @param int $account_id Account ID.
	 *
	 * @since 1.1.0
	 */
	public static function delete_account_reference( $account_id ) {
		global $wpdb;
		$wpdb->update( "{$wpdb->prefix}ea_documents", array( 'account_id' => null ), array( 'account_id' => $account_id ) );
		$wpdb->update( "{$wpdb->prefix}ea_transactions", array( 'account_id' => null ), array( 'account_id' => $account_id ) );

		// delete default account.
		$default_account = eaccounting()->settings->get( 'default_account' );
		if ( intval( $default_account ) === intval( $account_id ) ) {
			eaccounting()->settings->set( array( array( 'default_account' => '' ) ), true );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Customer
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting customer
	*/

	/**
	 * Update customer total paid
	 *
	 * @param int                                   $transaction_id transaction id.
	 * @param \EverAccounting\Abstracts\Transaction $transaction transaction.
	 *
	 * @since 1.1.0
	 */
	public function update_customer_total_paid( $transaction_id, $transaction ) {
		$customer = eaccounting_get_customer( $transaction->get_customer_id() );
		if ( $customer ) {
			eaccounting_insert_customer(
				array(
					'id'         => $customer->get_id(),
					'total_paid' => $customer->get_calculated_total_paid(),
					'total_due'  => $customer->get_calculated_total_due(),
				)
			);
		}
	}

	/**
	 * When an customer is deleted check if
	 * customer is associated with any document and transactions.
	 *
	 * @param int $customer_id Customer ID.
	 *
	 * @since 1.1.0
	 */
	public function delete_customer_reference( $customer_id ) {
		global $wpdb;
		$wpdb->update( "{$wpdb->prefix}ea_documents", array( 'contact_id' => null ), array( 'contact_id' => $customer_id ) );
		$wpdb->update( "{$wpdb->prefix}ea_transactions", array( 'contact_id' => null ), array( 'contact_id' => $customer_id ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Vendor
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting vendor
	*/

	/**
	 * Update vendor total paid
	 *
	 * @param int                                   $transaction_id transaction id.
	 * @param \EverAccounting\Abstracts\Transaction $transaction transaction.
	 *
	 * @since 1.1.0
	 */
	public function update_vendor_total_paid( $transaction_id, $transaction ) {
		$vendor = eaccounting_get_vendor( $transaction->get_vendor_id() );
		if ( $vendor ) {
			eaccounting_insert_vendor(
				array(
					'id'         => $vendor->get_id(),
					'total_paid' => $vendor->get_calculated_total_paid(),
					'total_due'  => $vendor->get_calculated_total_due(),
				)
			);
		}
	}

	/**
	 * When a vendor is deleted check if
	 * customer is associated with any document and transactions.
	 *
	 * @param int $vendor_id Vendor ID.
	 *
	 * @since 1.1.0
	 */
	public function delete_vendor_reference( $vendor_id ) {
		global $wpdb;
		$wpdb->update( "{$wpdb->prefix}ea_documents", array( 'contact_id' => null ), array( 'contact_id' => $vendor_id ) );
		$wpdb->update( "{$wpdb->prefix}ea_transactions", array( 'contact_id' => null ), array( 'contact_id' => $vendor_id ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Payment
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting payment
	*/

	/**
	 * Validate payment data.
	 *
	 * @param array $data Payment data.
	 * @param null  $id Payment ID.
	 * @since 1.1.0
	 * @throws \Exception Exception.
	 */
	public static function validate_payment_data( $data, $id = null ) {
		if ( empty( $data['payment_date'] ) ) {
			throw new \Exception( 'empty_prop', __( 'Payment date is required.', 'wp-ever-accounting' ) );
		}

		if ( empty( $data['payment_method'] ) ) {
			throw new \Exception( 'empty_prop', __( 'Payment method is required.', 'wp-ever-accounting' ) );
		}

		$category = eaccounting_get_category( $data['category_id'] );
		if ( empty( $category ) || ! in_array( $category->get_type(), array( 'expense', 'other' ), true ) ) {
			throw new \Exception( __( 'A valid payment category is required.', 'wp-ever-accounting' ) );
		}

		$vendor = eaccounting_get_vendor( $data['contact_id'] );
		if ( ! empty( $data['contact_id'] ) && empty( $vendor ) ) {
			throw new \Exception( __( 'Vendor is not valid.', 'wp-ever-accounting' ) );
		}

		$account = eaccounting_get_account( $data['account_id'] );
		if ( ! empty( $data['account_id'] ) && empty( $account ) ) {
			throw new \Exception( __( 'Account is not valid.', 'wp-ever-accounting' ) );
		}

		if ( empty( eaccounting_sanitize_number( $data['amount'] ) ) ) {
			throw new \Exception( 'empty_prop', __( 'Payment amount is required.', 'wp-ever-accounting' ) );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Revenue
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting revenue
	*/

	/**
	 * Validate expense data.
	 *
	 * @param array $data Expense data.
	 * @param null  $id Expense ID.
	 *
	 * @since 1.1.0
	 * @throws \Exception Exception.
	 */
	public static function validate_revenue_data( $data, $id = null ) {
		if ( empty( $data['payment_date'] ) ) {
			throw new \Exception( 'empty_prop', __( 'Revenue date is required.', 'wp-ever-accounting' ) );
		}

		if ( empty( $data['payment_method'] ) ) {
			throw new \Exception( 'empty_prop', __( 'Payment method is required.', 'wp-ever-accounting' ) );
		}

		$category = eaccounting_get_category( $data['category_id'] );
		if ( empty( $category ) || ! in_array( $category->get_type(), array( 'income', 'other' ), true ) ) {
			throw new \Exception( 'empty_prop', __( 'A valid income category is required.', 'wp-ever-accounting' ) );
		}

		$account = eaccounting_get_account( $data['account_id'] );
		if ( empty( $account ) ) {
			throw new \Exception( 'empty_prop', __( 'Account is required.', 'wp-ever-accounting' ) );
		}

		$customer = eaccounting_get_customer( $data['contact_id'] );
		if ( ! empty( $data['contact_id'] ) && empty( $customer ) ) {
			throw new \Exception( 'empty_prop', __( 'Customer is not valid.', 'wp-ever-accounting' ) );
		}

		if ( empty( eaccounting_sanitize_number( $data['amount'] ) ) ) {
			throw new \Exception( 'empty_prop', __( 'Revenue amount is required.', 'wp-ever-accounting' ) );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Transactions
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting account
	*/


	/*
	|--------------------------------------------------------------------------
	| Category
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting category
	*/

	/**
	 * Validate category data.
	 *
	 * @param array $data Category data.
	 * @param int   $id Category id.
	 *
	 * @since 1.1.0
	 * @throws \Exception When category is not valid.
	 */
	public static function validate_category_data( $data, $id ) {
		global $wpdb;
		$sql                  = $wpdb->prepare( "SELECT id from {$wpdb->prefix}ea_categories WHERE type=%s AND name=%s", eaccounting_clean( $data['type'] ), eaccounting_clean( $data['name'] ) );
		$existing_category_id = (int) $wpdb->get_var( $sql );

		if ( ! empty( $existing_category_id ) && ( absint( $id ) !== absint( $existing_category_id ) ) ) {
			throw new \Exception( __( 'Duplicate category.', 'wp-ever-accounting' ) );
		}
	}

	/**
	 * Delete category id from transactions.
	 *
	 * @param int $id Category id.
	 *
	 * @since 1.1.0
	 */
	public static function delete_category_reference( $id ) {
		global $wpdb;
		$wpdb->update( $wpdb->prefix . 'ea_transactions', array( 'category_id' => null ), array( 'category_id' => absint( $id ) ) );
		$wpdb->update( $wpdb->prefix . 'ea_documents', array( 'category_id' => null ), array( 'category_id' => absint( $id ) ) );
		$wpdb->update( $wpdb->prefix . 'ea_items', array( 'category_id' => null ), array( 'category_id' => absint( $id ) ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Currency
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting currency
	*/

	/**
	 * Update default currency.
	 *
	 * @param array $value New value.
	 * @param array $old_value Old value.
	 *
	 * @since 1.1.0
	 */
	public static function update_default_currency( $value, $old_value ) {
		if ( ! array_key_exists( 'default_currency', $value ) || $value['default_currency'] === $old_value['default_currency'] ) {
			return;
		}

		if ( empty( eaccounting_get_currency( $old_value['default_currency'] ) ) ) {
			return;
		}

		do_action( 'eaccounting_pre_change_default_currency', $value['default_currency'], $old_value['default_currency'] );
		$new_currency          = eaccounting_get_currency( $old_value['default_currency'] );
		$new_currency_old_rate = $new_currency->get_rate();
		$conversion_rate       = (float) ( 1 / $new_currency_old_rate );
		$currencies            = eaccounting_collect( get_option( 'eaccounting_currencies', array() ) );
		$currencies            = $currencies->each(
			function ( $currency ) use ( $conversion_rate ) {
				$currency['rate'] = eaccounting_format_decimal( $currency['rate'] * $conversion_rate, 4 );

				return $currency;
			}
		)->all();
		update_option( 'eaccounting_currencies', $currencies );
	}

	/**
	 * Delete currency id from settings.
	 *
	 * @param int   $id Currency id.
	 * @param array $data Currency data.
	 *
	 * @since 1.1.0
	 */
	public static function delete_currency_reference( $id, $data ) {
		$default_currency = eaccounting()->settings->get( 'default_currency' );
		if ( $default_currency === $data['code'] ) {
			eaccounting()->settings->set( array( array( 'default_currency' => '' ) ), true );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Bill
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting Bill
	*/

	/**
	 * Update Bill data.
	 *
	 * @param int     $payment_id  Payment id.
	 * @param Payment $payment Payment object.
	 *
	 * @since 1.1.0
	 */
	public static function update_bill_data( $payment_id, $payment ) {
		try {
			if ( empty( $payment->get_document_id() ) ) {
				return;
			}
			$bill = eaccounting_get_bill( $payment->get_document_id() );
			if ( $bill ) {
				$bill->save();
			}
		} catch ( \Exception  $e ) {
			// Do nothing.
		}
	}

	/**
	 * Update bill status.
	 *
	 * @since 1.1.0
	 */
	public static function update_bill_status() {
		global $wpdb;
		$current_time = date_i18n( 'Y-m-d H:i:s' );
		$bill_ids     = $wpdb->get_col( $wpdb->prepare( "select id from {$wpdb->prefix}ea_documents where due_date != '' AND %s > due_date AND `type` ='bill' AND status not in ('paid', 'cancelled', 'draft', 'overdue')", $current_time ) );
		foreach ( $bill_ids as $id ) {
			$bill = eaccounting_get_bill( $id );
			if ( $bill ) {
				$bill->set_status( 'overdue' );
				$bill->save();
			}
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Invoice
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting invoice
	*/
	/**
	 * Update invoice data.
	 *
	 * @param int     $payment_id Payment id.
	 * @param Revenue $revenue Revenue object.
	 *
	 * @since 1.1.0
	 */
	public static function update_invoice_data( $payment_id, $revenue ) {
		try {
			if ( empty( $revenue->get_document_id() ) ) {
				return;
			}
			$invoice = eaccounting_get_invoice( $revenue->get_document_id() );
			if ( $invoice ) {
				$invoice->save();
			}
		} catch ( \Exception  $e ) {
			// Do nothing.
		}
	}

	/**
	 * Update invoice status.
	 *
	 * @since 1.1.0
	 */
	public static function update_invoice_status() {
		global $wpdb;
		$current_time = date_i18n( 'Y-m-d H:i:s' );
		$invoice_ids  = $wpdb->get_col( $wpdb->prepare( "select id from {$wpdb->prefix}ea_documents where due_date != '' AND %s > due_date AND `type` ='invoice' AND status not in ('paid', 'cancelled', 'draft', 'overdue')", $current_time ) );
		foreach ( $invoice_ids as $id ) {
			$invoice = eaccounting_get_invoice( $id );
			if ( $invoice ) {
				$invoice->set_status( 'overdue' );
				$invoice->save();
			}
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Attachment
	|--------------------------------------------------------------------------
	|
	| Handle side effect of inserting, update, deleting attachment
	*/

	/**
	 * When an attachments is deleted check if
	 * customer is associated with any account or contacts or document  or items or transactions.
	 *
	 * @param int $attachment_id Attachment ID.
	 *
	 * @since 1.1.0
	 */
	public function delete_attachment_reference( $attachment_id ) {
		global $wpdb;
		$wpdb->update( $wpdb->prefix . 'ea_accounts', array( 'thumbnail_id' => null ), array( 'thumbnail_id' => absint( $attachment_id ) ) );
		$wpdb->update( $wpdb->prefix . 'ea_contacts', array( 'thumbnail_id' => null ), array( 'thumbnail_id' => absint( $attachment_id ) ) );
		$wpdb->update( $wpdb->prefix . 'ea_documents', array( 'attachment_id' => null ), array( 'attachment_id' => absint( $attachment_id ) ) );
		$wpdb->update( $wpdb->prefix . 'ea_items', array( 'thumbnail_id' => null ), array( 'thumbnail_id' => absint( $attachment_id ) ) );
		$wpdb->update( $wpdb->prefix . 'ea_transactions', array( 'attachment_id' => null ), array( 'attachment_id' => absint( $attachment_id ) ) );
	}
}

new Controller();
