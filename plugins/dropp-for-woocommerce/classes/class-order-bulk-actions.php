<?php
/**
 * Ajax
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Models\Dropp_Consignment;
use Exception;
use WC_Logger;

/**
 * Ajax
 */
class Order_Bulk_Actions {
	/**
	 * Setup
	 */
	public static function setup() {
		add_filter( 'bulk_actions-edit-shop_order', __CLASS__ . '::define_bulk_actions' );
		add_filter( 'bulk_actions-woocommerce_page_wc-orders', __CLASS__ . '::define_bulk_actions' );
		add_filter( 'admin_notices', __CLASS__ . '::bulk_admin_notices' );
		add_filter( 'handle_bulk_actions-edit-shop_order', __CLASS__ . '::handle_bulk_actions', 10, 3 );
		add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', __CLASS__ . '::handle_bulk_actions', 10, 3 );
	}


	/**
	 * Define bulk actions.
	 *
	 * @param array $actions Existing actions.
	 *
	 * @return array
	 */
	public static function define_bulk_actions( array $actions ): array {
		$actions['dropp_bulk_booking']  = __( 'Dropp - Book orders', 'woocommerce' );
		$actions['dropp_bulk_printing'] = __( 'Dropp - Print labels', 'woocommerce' );
		return $actions;
	}


	/**
	 * Handle bulk actions.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param string $action Action name.
	 * @param array $ids List of ids.
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function handle_bulk_actions( string $redirect_to, string $action, array $ids ): string {
		if ( 'dropp_bulk_booking' === $action ) {
			return self::handle_bulk_booking( $redirect_to, $ids );
		}
		if ( 'dropp_bulk_printing' === $action ) {
			return self::handle_bulk_printing( $redirect_to, $ids );
		}
		return $redirect_to;
	}

	/**
	 * Handle bulk booking.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param array $ids List of ids.
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function handle_bulk_booking( string $redirect_to, array $ids ): string {
		global $page_hook;
		$result = [
			'existing'  => [],
			'success'   => [],
			'not_dropp' => [],
			'failed'    => [],
		];

		foreach ( $ids as $order_id ) {
			$adapter = new Order_Adapter(
				wc_get_order( $order_id )
			);
			if ( ! $adapter->is_dropp() ) {
				$result['not_dropp'][] = $order_id;
			} elseif ( $adapter->count_consignments() ) {
				$result['existing'][] = $order_id;
			} elseif ( $adapter->book() ) {
				$result['success'][] = $order_id;
			} else {
				$result['failed'][] = $order_id;
			}
		}

		$args = [
			'bulk_action' => 'bulk_booking',
			'existing'    => join( ',', $result['existing'] ),
			'success'     => join( ',', $result['success'] ),
			'not_dropp'   => join( ',', $result['not_dropp'] ),
			'failed'      => join( ',', $result['failed'] ),
		];

		if ( 'woocommerce_page_wc-orders' !== $page_hook ) {
			$args['post_type']   = 'shop_order';
		}
		$redirect_to = add_query_arg(
			$args,
			$redirect_to
		);
		return esc_url_raw( $redirect_to );
	}

	/**
	 * Handle bulk printing.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param array $ids         List of ids.
	 *
	 * @return string
	 */
	public static function handle_bulk_printing( string $redirect_to, array $ids ): string {
		$redirect_to = add_query_arg(
			array(
				'post_type'   => 'shop_order',
				'bulk_action' => 'bulk_printing',
				'ids'   => join( ',', $ids ),
			),
			$redirect_to
		);
		return esc_url_raw( $redirect_to );
	}

	/**
	 * Show confirmation message that order status changed for number of orders.
	 */
	public static function bulk_admin_notices(): void {
		global $post_type, $pagenow, $page_hook;
		// Bail out if not on shop order list page.
		if ( (
				('edit.php' !== $pagenow || 'shop_order' !== $post_type) &&
				('admin.php' !== $pagenow || 'woocommerce_page_wc-orders' !== $page_hook)
			)
			|| ! isset( $_REQUEST['bulk_action'] ) ) { // WPCS: input var ok, CSRF ok.
			return;
		}
		$action = $_REQUEST['bulk_action']; // WPCS: input var ok, CSRF ok.
		if ( 'bulk_booking' !== $action && 'bulk_printing' !== $action ) {
			return;
		}

		$consignment_ids = [];
		// Get order id's.
		$order_ids       = self::get_id_array(
			( 'bulk_booking' == $action ? 'success' : 'ids' )
		);
		$dropp_order_ids = [];
		foreach ( $order_ids as $id ) {
			$collection = Dropp_Consignment::from_order( $id );
			$valid = false;
			foreach ( $collection as $consignment ) {
				if ( ! $consignment->dropp_order_id ) {
					continue;
				}
				$consignment_ids[] = $consignment->id;
				if ( in_array( $id, $order_ids ) ) {
					$dropp_order_ids[] = $id;
				}
			}
		}
		$dropp_order_ids = array_unique( $dropp_order_ids );

		if ( 'bulk_booking' === $action ) {
			$existing  = self::get_id_array( 'existing' );
			$success   = self::get_id_array( 'success' );
			$not_dropp = self::get_id_array( 'not_dropp' );
			$failed    = self::get_id_array( 'failed' );
			require dirname( __DIR__ ) . '/templates/admin-notices/bulk-booking.php';
		}
		if ( 'bulk_printing' === $action ) {
			require dirname( __DIR__ ) . '/templates/admin-notices/bulk-printing.php';
		}
	}

	/**
	 * Get ID array
	 *
	 * @param string $param Parameter.
	 *
	 * @return array         IDs.
	 */
	protected static function get_id_array( string $param ): array {
		$csv           = $_REQUEST[ $param ] ?? ''; // WPCS: input var ok, CSRF ok.
		$tentative_ids = explode( ',', $csv );
		$tentative_ids = array_map( 'trim', $tentative_ids );
		$ids           = [];
		foreach ( $tentative_ids as $id ) {
			if ( ! ctype_digit( $id ) || '0' === $id ) {
				continue;
			}
			$ids[] = (int) $id;
		}
		return $ids;
	}
}
