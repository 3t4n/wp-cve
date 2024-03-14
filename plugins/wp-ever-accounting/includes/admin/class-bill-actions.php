<?php
/**
 * Admin Bill Page
 *
 * Functions used for displaying bill related pages.
 *
 * @author      EverAccounting
 * @category    Admin
 * @package     EverAccounting\Admin
 * @version     1.1.10
 */

namespace EverAccounting\Admin;

use EverAccounting\Models\Bill;
use PHPUnit\Runner\Exception;

defined( 'ABSPATH' ) || exit();

/**
 * Class Bill_Actions
 *
 * @package EverAccounting\Admin
 */
class Bill_Actions {
	/**
	 * Bill_Actions constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_eaccounting_bill_action', array( $this, 'bill_action' ) );
	}

	/**
	 * Handle bill.
	 *
	 * @throws \Exception Exception.
	 */
	public function bill_action() {
		$nonce   = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
		$action  = filter_input( INPUT_GET, 'bill_action', FILTER_SANITIZE_STRING );
		$bill_id = filter_input( INPUT_GET, 'bill_id', FILTER_SANITIZE_NUMBER_INT );
		$bill    = $bill_id ? eaccounting_get_bill( $bill_id ) : false;

		if ( ! wp_verify_nonce( $nonce, 'ea_bill_action' ) || ! current_user_can( 'ea_manage_bill' ) || ! $bill->exists() ) {
			echo esc_html__( 'no cheating!', 'wp-ever-accounting' );
			exit();
		}
		$redirect_url = add_query_arg(
			array(
				'page'    => 'ea-expenses',
				'tab'     => 'bills',
				'action'  => 'view',
				'bill_id' => $bill_id,
			),
			admin_url( 'admin.php' )
		);
		switch ( $action ) {
			case 'status_received':
				try {
					$bill->set_status( 'received' );
					$bill->save();
					eaccounting_admin_notices()->add_success( __( 'Bill status updated to received.', 'wp-ever-accounting' ) );
				} catch ( \Exception $e ) {
					/* translators: %s reason */
					eaccounting_admin_notices()->add_error( sprintf( __( 'Bill status was not changes : %s ', 'wp-ever-accounting' ), $e->getMessage() ) );
				}
				break;
			case 'status_cancelled':
				$bill->set_cancelled();
				break;
			case 'status_paid':
				$bill->set_paid();
				break;
			case 'delete':
				$bill->delete();
				$redirect_url = remove_query_arg( array( 'action', 'bill_id' ), $redirect_url );
				break;
		}

		if ( ! did_action( 'eaccounting_bill_action_' . sanitize_title( $action ) ) ) {
			do_action( 'eaccounting_bill_action_' . sanitize_title( $action ), $bill, $redirect_url );
		}

		wp_safe_redirect( $redirect_url );
		exit();
	}

	/**
	 * View bill.
	 *
	 * @param int $bill_id Bill id.
	 *
	 * @since 1.1.0
	 */
	public static function view_bill( $bill_id = null ) {
		try {
			$bill = new Bill( $bill_id );
		} catch ( \Exception $e ) {
			wp_die( esc_html( $e->getMessage() ) );
		}

		if ( empty( $bill ) || ! $bill->exists() ) {
			wp_die( esc_html__( 'Sorry, Bill does not exist', 'wp-ever-accounting' ) );
		}

		eaccounting_get_admin_template(
			'bills/view-bill',
			array(
				'bill'   => $bill,
				'action' => 'view',
			)
		);
	}

	/**
	 * Edit bill.
	 *
	 * @param int $bill_id Bill id.
	 * @since 1.1.0
	 */
	public static function edit_bill( $bill_id = null ) {
		try {
			$bill = new Bill( $bill_id );
		} catch ( \Exception $e ) {
			wp_die( esc_html( $e->getMessage() ) );
		}
		eaccounting_get_admin_template(
			'bills/edit-bill',
			array(
				'bill'   => $bill,
				'action' => 'edit',
			)
		);
	}

	/**
	 * Get bill notes.
	 *
	 * @param Bill $bill Bill.
	 *
	 * @since 1.1.0
	 */
	public static function bill_notes( $bill ) {
		if ( ! $bill->exists() ) {
			return;
		}
		eaccounting_get_admin_template( 'bills/bill-notes', array( 'bill' => $bill ) );
	}

	/**
	 * Get bill payments.
	 *
	 * @param Bill $bill Bill.
	 *
	 * @since 1.1.0
	 */
	public static function bill_payments( $bill ) {
		if ( ! $bill->exists() ) {
			return;
		}
		eaccounting_get_admin_template( 'bills/bill-payments', array( 'bill' => $bill ) );
	}
}

new Bill_Actions();
