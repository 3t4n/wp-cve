<?php
/**
 * EverAccounting  AJAX Event Handlers.
 *
 * @since       1.0.2
 * @package     EverAccounting
 * @class       Ajax
 */

namespace EverAccounting;

use EverAccounting\Models\Bill;
use EverAccounting\Models\Invoice;
use EverAccounting\Models\Note;

defined( 'ABSPATH' ) || exit();

/**
 * Class Ajax
 *
 * @since 1.0.2
 */
class Ajax {

	/**
	 * Ajax constructor.
	 *
	 * @since 1.0.2
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		add_action( 'template_redirect', array( __CLASS__, 'do_ajax' ), 0 );
		self::add_ajax_events();
	}

	/**
	 * Set EA AJAX constant and headers.
	 *
	 * @since 1.0.2
	 */
	public static function define_ajax() {
		if ( filter_input( INPUT_GET, 'ea-ajax' ) ) {
			eaccounting_maybe_define_constant( 'DOING_AJAX', true );
			eaccounting_maybe_define_constant( 'EACCOUNTING_DOING_AJAX', true );
			$GLOBALS['wpdb']->hide_errors();
		}
	}


	/**
	 * Send headers for EverAccounting Ajax Requests.
	 *
	 * @since 1.0.2
	 */
	private static function ajax_headers() {
		if ( ! headers_sent() ) {
			send_origin_headers();
			send_nosniff_header();
			header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
			header( 'X-Robots-Tag: noindex' );
			status_header( 200 );
		}
	}

	/**
	 * Check for EverAccounting Ajax request and fire action.
	 *
	 * @since 1.0.2
	 */
	public static function do_ajax() {
		global $wp_query;
		$ajax = filter_input( INPUT_GET, 'ea-ajax', FILTER_SANITIZE_STRING );
		if ( ! empty( $ajax ) ) {
			$wp_query->set( 'ea-ajax', sanitize_text_field( $ajax ) );
		}

		$action = $wp_query->get( 'ea-ajax' );
		if ( $action ) {
			self::ajax_headers();
			$action = sanitize_text_field( $action );
			do_action( 'eaccounting_ajax_' . $action );
			exit();
		}
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 *
	 * @since 1.0.2
	 */
	public static function add_ajax_events() {
		$ajax_events_nopriv = array();

		foreach ( $ajax_events_nopriv as $ajax_event ) {
			add_action( 'wp_ajax_eaccounting_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			add_action( 'wp_ajax_nopriv_eaccounting_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			// EverAccounting AJAX can be used for frontend ajax requests.
			add_action( 'eaccounting_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}

		$ajax_events = array(
			// currency.
			'get_currencies',
			'get_currency',
			'get_currency_codes',
			'edit_currency',

			// category.
			'get_expense_categories',
			'get_income_categories',
			'get_item_categories',
			'edit_category',

			// invoice.
			'add_invoice_payment',
			'add_invoice_note',
			'invoice_recalculate',
			'edit_invoice',

			// revenue.
			'edit_revenue',

			// customer.
			'get_customers',
			'edit_customer',

			// bill.
			'add_bill_payment',
			'add_bill_note',
			'bill_recalculate',
			'edit_bill',

			// payment.
			'edit_payment',

			// vendor.
			'get_vendors',
			'edit_vendor',

			// account.
			'get_account',
			'get_accounts',
			'get_account_currency',
			'edit_account',

			// transfer.
			'edit_transfer',

			// note.
			'delete_note',

			// item.
			'get_items',
			'edit_item',
		);

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wp_ajax_eaccounting_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}

	/**
	 * Get expense categories.
	 *
	 * @since 1.1.0
	 */
	public static function get_expense_categories() {
		check_admin_referer( 'ea_categories' );
		$search = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$page   = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );
		$results = eaccounting_get_categories(
			array(
				'search' => $search,
				'type'   => 'expense',
				'page'   => $page,
				'return' => 'raw',
				'status' => 'active',
			)
		);

		$total = eaccounting_get_categories(
			array(
				'search' => $search,
				'type'   => 'expense',
				'page'   => $page,
				'status' => 'active',
				'count_total' => true,
			)
		);

		$result = array(
			'results'    => $results,
			'page'       => $page,
			'pagination' => array(
				'more' => $total > $page * 20,
			),
		);
		wp_send_json_success( $result );
	}

	/**
	 * Get income categories.
	 *
	 * @since 1.1.0
	 */
	public static function get_income_categories() {
		check_admin_referer( 'ea_categories' );
		$search = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$page   = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );

		$results = eaccounting_get_categories(
			array(
				'search' => $search,
				'type'   => 'income',
				'page'   => $page,
				'return' => 'raw',
				'status' => 'active',
			)
		);

		$total = eaccounting_get_categories(
			array(
				'search' => $search,
				'type'   => 'income',
				'page'   => $page,
				'status' => 'active',
				'count_total' => true,
			)
		);

		$result = array(
			'results'    => $results,
			'page'       => $page,
			'pagination' => array(
				'more' => $total > $page * 20,
			),
		);
		wp_send_json_success( $result );
	}

	/**
	 * Get item categories.
	 *
	 * @since 1.1.0
	 */
	public static function get_item_categories() {
		check_admin_referer( 'ea_categories' );
		$search = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$page   = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );

		$results = eaccounting_get_categories(
			array(
				'search' => $search,
				'type'   => 'item',
				'page'   => $page,
				'return' => 'raw',
				'status' => 'active',
			)
		);

		$total = eaccounting_get_categories(
			array(
				'search' => $search,
				'type'   => 'item',
				'page'   => $page,
				'status' => 'active',
				'count_total' => true,
			)
		);

		$result = array(
			'results'    => $results,
			'page'       => $page,
			'pagination' => array(
				'more' => $total > $page * 20,
			),
		);
		wp_send_json_success( $result );
	}

	/**
	 * Handle ajax action of creating/updating account.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public static function edit_category() {
		check_admin_referer( 'ea_edit_category' );
		self::check_permission( 'ea_manage_category' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$created = eaccounting_insert_category( $posted );
		$referer = wp_get_referer();
		if ( is_wp_error( $created ) ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Category updated successfully!', 'wp-ever-accounting' );
		$update   = ! empty( $posted['id'] );
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Category created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), $referer );
		}
		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}

	/**
	 * Add payment to invoice.
	 *
	 * @throws \Exception When error.
	 * @since 1.1.0
	 * @return void
	 */
	public static function add_invoice_payment() {
		check_admin_referer( 'ea_add_invoice_payment' );
		self::check_permission( 'ea_manage_invoice' );
		$posted = eaccounting_clean( wp_unslash( $_REQUEST ) );

		try {
			$invoice = new Invoice( $posted['invoice_id'] );
			if ( ! $invoice->exists() ) {
				throw new \Exception( __( 'Invalid Invoice Item', 'wp-ever-accounting' ) );
			}
			$invoice->add_payment( $posted );
			$invoice->save();

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Invoice Payment saved', 'wp-ever-accounting' ),
					'total'   => $invoice->get_total(),
					'due'     => $invoice->get_total_due(),
					'paid'    => $invoice->get_total_paid(),
					'status'  => $invoice->get_status(),
				)
			);
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * Add invoice note.
	 *
	 * @since 1.1.0
	 */
	public static function add_invoice_note() {
		check_admin_referer( 'ea_add_invoice_note' );
		self::check_permission( 'ea_manage_invoice' );
		$invoice_id = filter_input( INPUT_POST, 'invoice_id', FILTER_SANITIZE_NUMBER_INT );
		$note       = filter_input( INPUT_POST, 'note', FILTER_SANITIZE_STRING );
		if ( empty( $note ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Note Content empty.', 'wp-ever-accounting' ),
				)
			);
		}
		try {
			$invoice = new Invoice( $invoice_id );
			$invoice->add_note( $note );
			$notes = eaccounting_get_admin_template_html( 'invoices/invoice-notes', array( 'invoice' => $invoice ) );
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Note Added.', 'wp-ever-accounting' ),
					'notes'   => $notes,
				)
			);
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * Recalculate invoice totals
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function invoice_recalculate() {
		check_admin_referer( 'ea_edit_invoice' );
		self::check_permission( 'ea_manage_invoice' );
		$posted = eaccounting_clean( wp_unslash( $_REQUEST ) );
		try {
			$posted  = wp_parse_args( $posted, array( 'id' => null ) );
			$invoice = new Invoice( $posted['id'] );
			$invoice->set_props( $posted );
			$totals = $invoice->calculate_totals();
			wp_send_json_success(
				array(
					'html'   => eaccounting_get_admin_template_html(
						'invoices/invoice-items',
						array(
							'invoice' => $invoice,
						)
					),
					'line'   => array_map( 'strval', $invoice->get_items() ),
					'totals' => $totals,
				)
			);
		} catch ( \Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Handle ajax action of creating/updating invoice.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function edit_invoice() {
		check_admin_referer( 'ea_edit_invoice' );
		self::check_permission( 'ea_manage_invoice' );
		$referer = wp_get_referer();
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );

		try {
			$posted  = wp_parse_args( $posted, array( 'id' => null ) );
			$invoice = new Invoice( $posted['id'] );
			$invoice->set_props( $posted );
			$invoice->save();

			$redirect = add_query_arg(
				array(
					'action'     => 'view',
					'invoice_id' => $invoice->get_id(),
				),
				$referer
			);
			$response = array(
				'items'    => eaccounting_get_admin_template_html(
					'invoices/invoice-items',
					array(
						'invoice' => $invoice,
					)
				),
				'line'     => array_map( 'strval', $invoice->get_items() ),
				'redirect' => $redirect,
			);
			if ( empty( $posted['id'] ) ) {
				$response['redirect'] = $redirect;
				/* translators: %s: invoice number */
				$invoice->add_note( sprintf( esc_html__( '%s added', 'wp-ever-accounting' ), $invoice->get_document_number() ) );
			}
			wp_send_json_success( $response );
		} catch ( \Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Handle ajax action of creating/updating revenue.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public static function edit_revenue() {
		check_admin_referer( 'ea_edit_revenue' );
		self::check_permission( 'ea_manage_revenue' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$referer = wp_get_referer();
		$created = eaccounting_insert_revenue( $posted );
		if ( is_wp_error( $created ) || ! $created->exists() ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Revenue updated successfully!', 'wp-ever-accounting' );
		$update   = ! empty( $posted['id'] );
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Revenue created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), $referer );
		}

		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}

	/**
	 * Get customers.
	 *
	 * @since 1.1.0
	 */
	public static function get_customers() {
		check_admin_referer( 'ea_get_customers' );
		$search  = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$page    = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => 1 ) ) );
		$results = eaccounting_get_customers(
			array(
				'search' => $search,
				'page'   => $page,
				'return' => 'raw',
				'status' => 'active',
			)
		);
		$total   = eaccounting_get_customers(
			array(
				'search'      => $search,
				'count_total' => true,
				'status'      => 'active',
			)
		);
		$result = array(
			'results'    => $results,
			'page'       => $page,
			'pagination' => array(
				'more' => $total > $page * 20,
			),
		);
		wp_send_json_success( $result );
	}


	/**
	 * Handle ajax action of creating/updating customer.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public static function edit_customer() {
		check_admin_referer( 'ea_edit_customer' );
		self::check_permission( 'ea_manage_customer' );
		$posted  = eaccounting_clean( $_REQUEST );
		$created = eaccounting_insert_customer( $posted );
		$referer = wp_get_referer();
		if ( is_wp_error( $created ) || ! $created->exists() ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Customer updated successfully!', 'wp-ever-accounting' );
		$update   = ! empty( $posted['id'] );
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Customer created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), eaccounting_clean( $referer ) );
		}

		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}

	/**
	 * Add payment to bill.
	 *
	 * @throws \Exception Error message.
	 * @since 1.1.0
	 * @return void
	 */
	public static function add_bill_payment() {
		check_admin_referer( 'ea_add_bill_payment' );
		self::check_permission( 'ea_manage_bill' );
		$posted = eaccounting_clean( wp_unslash( $_REQUEST ) );

		try {
			$bill = new Bill( $posted['bill_id'] );
			if ( ! $bill->exists() ) {
				throw new \Exception( __( 'Invalid Invoice Item', 'wp-ever-accounting' ) );
			}
			$bill->add_payment( $posted );
			$bill->save();

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Bill Payment saved', 'wp-ever-accounting' ),
					'total'   => $bill->get_total(),
					'due'     => $bill->get_total_due(),
					'paid'    => $bill->get_total_paid(),
					'status'  => $bill->get_status(),
				)
			);
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * Add bill note.
	 *
	 * @since 1.1.0
	 */
	public static function add_bill_note() {
		check_admin_referer( 'ea_add_bill_note' );
		self::check_permission( 'ea_manage_bill' );
		$bill_id = filter_input( INPUT_POST, 'bill_id', FILTER_SANITIZE_NUMBER_INT );
		$note    = filter_input( INPUT_POST, 'note', FILTER_SANITIZE_STRING );
		if ( empty( $note ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Note Content empty.', 'wp-ever-accounting' ),
				)
			);
		}
		try {
			$bill = new Bill( $bill_id );
			$bill->add_note( $note );
			$notes = eaccounting_get_admin_template_html( 'bills/bill-notes', array( 'bill' => $bill ) );
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Note Added.', 'wp-ever-accounting' ),
					'notes'   => $notes,
				)
			);
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * Recalculate bill totals
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function bill_recalculate() {
		check_admin_referer( 'ea_edit_bill' );
		self::check_permission( 'ea_manage_bill' );
		$posted = eaccounting_clean( wp_unslash( $_REQUEST ) );
		try {
			$posted = wp_parse_args( $posted, array( 'id' => null ) );
			$bill   = new Bill( $posted['id'] );
			$bill->set_props( $posted );
			$totals = $bill->calculate_totals();
			wp_send_json_success(
				array(
					'html'   => eaccounting_get_admin_template_html(
						'bills/bill-items',
						array(
							'bill' => $bill,
							'mode' => 'edit',
						)
					),
					'line'   => array_map( 'strval', $bill->get_items() ),
					'totals' => $totals,
				)
			);

		} catch ( \Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Handle ajax action of creating/updating bill.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function edit_bill() {
		check_admin_referer( 'ea_edit_bill' );
		self::check_permission( 'ea_manage_bill' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$referer = wp_get_referer();
		try {
			$posted = wp_parse_args( $posted, array( 'id' => null ) );
			$bill   = new Bill( $posted['id'] );
			$bill->set_props( $posted );
			$bill->save();
			$redirect = add_query_arg(
				array(
					'action'  => 'view',
					'bill_id' => $bill->get_id(),
				),
				eaccounting_clean( $referer )
			);

			$response = array(
				'items'    => eaccounting_get_admin_template_html(
					'bills/bill-items',
					array(
						'bill' => $bill,
					)
				),
				'line'     => array_map( 'strval', $bill->get_items() ),
				'redirect' => $redirect,
			);
			if ( empty( $posted['id'] ) ) {
				$response['redirect'] = $redirect;
				/* translators: %s: bill id */
				$bill->add_note( sprintf( __( '%s added', 'wp-ever-accounting' ), $bill->get_document_number() ) );
			}
			wp_send_json_success( $response );
		} catch ( \Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Get currencies.
	 *
	 * @since 1.1.0
	 */
	public static function get_currencies() {
		check_admin_referer( 'ea_get_currencies' );
		$search = isset( $_POST['search'] ) ? eaccounting_clean( wp_unslash( $_POST['search'] ) ) : '';
		$page   = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$items  = eaccounting_get_currencies(
			array(
				'search' => $search,
				'offset' => ( $page - 1 ) * 20,
				'return' => 'raw',
			)
		);
		$total  = eaccounting_get_currencies(
			array(
				'search'      => $search,
				'count_total' => true
			)
		);

		$result = array(
			'results'    => $items,
			'page'       => $page,
			'pagination' => array(
				'more' => $total > $page * 20,
			),
		);
		wp_send_json_success( $result );
	}

	/**
	 * Get currency data.
	 *
	 * @since 1.0.2
	 */
	public static function get_currency() {
		check_admin_referer( 'ea_get_currency' );
		self::check_permission( 'manage_eaccounting' );
		$posted = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$code   = ! empty( $posted['code'] ) ? $posted['code'] : false;
		if ( ! $code ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'No code received', 'wp-ever-accounting' ),
				)
			);
		}
		$currency = eaccounting_get_currency( $code );
		if ( empty( $currency ) || is_wp_error( $currency ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Could not find the currency', 'wp-ever-accounting' ),
				)
			);
		}

		wp_send_json_success( $currency->get_data() );
	}

	/**
	 * Get currency codes.
	 *
	 * @since 1.1.0
	 */
	public static function get_currency_codes() {
		check_admin_referer( 'ea_get_currency_codes' );
		self::check_permission( 'manage_eaccounting' );
		$currencies = eaccounting_get_global_currencies();
		wp_send_json_success( $currencies );
	}

	/**
	 * Handle ajax action of creating/updating currencies.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public static function edit_currency() {
		check_admin_referer( 'ea_edit_currency' );
		self::check_permission( 'ea_manage_currency' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$created = eaccounting_insert_currency( $posted );
		$referer = wp_get_referer();
		if ( is_wp_error( $created ) ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Currency updated successfully!', 'wp-ever-accounting' );
		$update   = ! empty( $posted['id'] );
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Currency created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), eaccounting_clean( $referer ) );
		}

		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}

	/**
	 * Handle ajax action of creating/updating payment.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public static function edit_payment() {
		check_admin_referer( 'ea_edit_payment' );
		self::check_permission( 'ea_manage_payment' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$referer = wp_get_referer();
		$created = eaccounting_insert_payment( $posted );
		if ( is_wp_error( $created ) || ! $created->exists() ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Payment updated successfully!', 'wp-ever-accounting' );
		$update   = empty( $posted['id'] ) ? false : true;
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Payment created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), eaccounting_clean( $referer ) );
		}

		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}

	/**
	 * Get vendors.
	 *
	 * @since 1.1.0
	 */
	public static function get_vendors() {
		check_admin_referer( 'ea_get_vendors' );
		$search = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$page   = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );

		$results = eaccounting_get_vendors(
			array(
				'search' => $search,
				'page'   => $page,
				'return' => 'raw',
				'status' => 'active',
			)
		);
		$total  = eaccounting_get_vendors(
			array(
				'search' => $search,
				'status' => 'active',
				'count_total' => true,
			)
		);
		$result = array(
			'results'    => $results,
			'page'       => $page,
			'pagination' => array(
				'more' => $total > $page * 20,
			),
		);
		wp_send_json_success( $result );
	}

	/**
	 * Handle ajax action of creating/updating vendor.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function edit_vendor() {
		check_admin_referer( 'ea_edit_vendor' );
		self::check_permission( 'ea_manage_vendor' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$referer = wp_get_referer();
		$created = eaccounting_insert_vendor( $posted );
		if ( is_wp_error( $created ) || ! $created->exists() ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Vendor updated successfully!', 'wp-ever-accounting' );
		$update   = ! empty( $posted['id'] );
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Vendor created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), eaccounting_clean( $referer ) );
		}

		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}

	/**
	 * Get single account.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public static function get_account() {
		check_admin_referer( 'ea_get_account' );
		self::check_permission( 'manage_eaccounting' );
		$id      = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		$account = eaccounting_get_account( $id );
		if ( $account ) {
			wp_send_json_success( $account->get_data() );
			exit();
		}

		wp_send_json_error( array() );

		exit();
	}

	/**
	 * Get accounts.
	 *
	 * @since 1.1.0
	 */
	public static function get_accounts() {
		check_admin_referer( 'ea_get_accounts' );
		$search = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$page   = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => 1 ) ) );

		$results = eaccounting_get_accounts(
			array(
				'search' => $search,
				'paged'   => $page,
				'return' => 'raw',
				'status' => 'active',
			)
		);

		$total = eaccounting_get_accounts(
			array(
				'search' => $search,
				'status' => 'active',
				'count_total' => true,
			)
		);

		$result = array(
			'results'    => $results,
			'page'       => $page,
			'pagination' => array(
				'more' => $total > $page * 20,
			),
		);
		wp_send_json_success( $result );
	}

	/**
	 * Get currency data.
	 *
	 * @since 1.0.2
	 */
	public static function get_account_currency() {
		check_admin_referer( 'ea_get_currency' );
		self::check_permission( 'manage_eaccounting' );
		$account_id = filter_input( INPUT_POST, 'account_id', FILTER_SANITIZE_NUMBER_INT );
		if ( empty( $account_id ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'No account id received', 'wp-ever-accounting' ),
				)
			);
		}
		$account = eaccounting_get_account( $account_id );
		if ( empty( $account ) || is_wp_error( $account ) || empty( $account->get_currency()->exists() ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Could not find the currency', 'wp-ever-accounting' ),
				)
			);
		}

		wp_send_json_success( $account->get_currency()->get_data() );
	}


	/**
	 * Handle ajax action of creating/updating account.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public static function edit_account() {
		check_admin_referer( 'ea_edit_account' );
		self::check_permission( 'ea_manage_account' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$created = eaccounting_insert_account( $posted );
		$referer = wp_get_referer();
		if ( is_wp_error( $created ) ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Account updated successfully!', 'wp-ever-accounting' );
		$update   = ! empty( $posted['id'] );
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Account created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), eaccounting_clean( $referer ) );
		}

		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}

	/**
	 * Handle ajax action of creating/updating transfer.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public static function edit_transfer() {
		check_admin_referer( 'ea_edit_transfer' );
		self::check_permission( 'ea_manage_transfer' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$created = eaccounting_insert_transfer( $posted );
		$referer = wp_get_referer();
		if ( is_wp_error( $created ) || ! $created->exists() ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Transfer updated successfully!', 'wp-ever-accounting' );
		$update   = ! empty( $posted['id'] );
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Transfer created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), eaccounting_clean( $referer ) );
		}

		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}


	/**
	 * Delete note from database.
	 *
	 * @since 1.1.0
	 */
	public static function delete_note() {
		check_admin_referer( 'ea_delete_note' );
		self::check_permission( 'ea_manage_invoice' );
		$id      = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		$note    = new Note( absint( $id ) );
		$note    = eaccounting_get_note( $note );
		$deleted = eaccounting_delete_note( absint( $id ) );
		if ( ! $deleted ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Note could not be deleted.', 'wp-ever-accounting' ),
				)
			);
		}
		if ( 'invoice' === $note->get_type() ) {
			$invoice_id = $note->get_parent_id();
			$invoice    = new Invoice( $invoice_id );
			$notes      = eaccounting_get_admin_template_html( 'invoices/invoice-notes', array( 'invoice' => $invoice ) );
		} elseif ( 'bill' === $note->get_type() ) {
			$bill_id = $note->get_parent_id();
			$bill    = new Bill( $bill_id );
			$notes   = eaccounting_get_admin_template_html( 'bills/bill-notes', array( 'bill' => $bill ) );
		}

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Note Deleted.', 'wp-ever-accounting' ),
				'notes'   => $notes,
			)
		);
	}

	/**
	 * Get all items
	 *
	 * @since 1.1.0
	 */
	public static function get_items() {
		check_admin_referer( 'ea_get_items' );
		self::check_permission( 'manage_eaccounting' );
		$search = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$page  = filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );

		$results = eaccounting_get_items(
			array(
				'search' => $search,
				'return' => 'raw',
				'status' => 'active',
			)
		);
		$total = eaccounting_get_items(
			array(
				'search' => $search,
				'status' => 'active',
				'count_total' => true,
			)
		);

		$result = array(
			'results'    => $results,
			'page'       => $page,
			'pagination' => array(
				'more' => $total > $page * 20,
			),
		);
		wp_send_json_success( $result );
	}

	/**
	 * Handle ajax action of creating/updating item.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public static function edit_item() {
		check_admin_referer( 'ea_edit_item' );
		self::check_permission( 'ea_manage_item' );
		$posted  = eaccounting_clean( wp_unslash( $_REQUEST ) );
		$created = eaccounting_insert_item( $posted );
		$referer = wp_get_referer();
		if ( is_wp_error( $created ) ) {
			wp_send_json_error(
				array(
					'message' => $created->get_error_message(),
				)
			);
		}

		$message  = __( 'Item updated successfully!', 'wp-ever-accounting' );
		$update   = ! empty( $posted['id'] );
		$redirect = '';
		if ( ! $update ) {
			$message  = __( 'Item created successfully!', 'wp-ever-accounting' );
			$redirect = remove_query_arg( array( 'action' ), eaccounting_clean( $referer ) );
		}
		wp_send_json_success(
			array(
				'message'  => $message,
				'redirect' => $redirect,
				'item'     => $created->get_data(),
			)
		);

		exit();
	}

	/**
	 * Check permission
	 *
	 * @param string $cap capability.
	 *
	 * @since 1.0.2
	 */
	public static function check_permission( $cap = 'manage_eaccounting' ) {
		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Error: You are not allowed to do this.', 'wp-ever-accounting' ) ) );
		}
	}
}

return new Ajax();
