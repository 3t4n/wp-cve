<?php
/**
 * Sync Orders.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Sync;

use Exception;
use Faire\Wc\Admin\Settings;
use Faire\Wc\Api\Order_Api;
use Faire\Wc\Faire\Order as Faire_Order;
use Faire\Wc\Utils;
use Faire\Wc\Woocommerce\Order as WC_Order;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sync Order class.
 */
class Sync_Order {

	/**
	 * Max number of orders per page.
	 */
	const ORDERS_PER_PAGE = 50;

	/**
	 * Instance of Sync_Order_Status class.
	 *
	 * @var Sync_Order_Status
	 */
	private Sync_Order_Status $sync_status;

	/**
	 * Instance of Faire\Wc\Api\Order class.
	 *
	 * @var Order_Api
	 */
	private Order_Api $order_api;

	/**
	 * Instance of Faire\Wc\Admin\Settings class.
	 *
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * Arguments to retrieve Faire orders.
	 *
	 * @var array{ updated_at_min: string|false, page: int }
	 */
	private array $faire_get_orders_args;

	/**
	 * Class constructor.
	 *
	 * @param Order_Api $order_api Instance of Faire\Wc\Api\Order class.
	 * @param Settings  $settings  Instance of Faire\Wc\Admin\Settings class.
	 */
	public function __construct( Order_Api $order_api, Settings $settings ) {
		$this->order_api   = $order_api;
		$this->settings    = $settings;
		$this->sync_status = new Sync_Order_Status( $settings );

		// Removes a Faire order ID from the list of already synced orders if the
		// related WooCommerce order is deleted.
		// We need to user the `before_delete_post` action because later on the
		// postmeta of the order is deleted and we lose the link with the related
		// Faire order.
		add_action( 'before_delete_post', array( $this, 'delete_from_synced_faire_orders' ) );

		new Sync_Order_Scheduler( array( $this, 'sync_orders' ), $this->settings );

		if (
			'yes' === get_option( 'woocommerce_manage_stock' ) &&
			$this->settings->get_inventory_sync_on_add_to_cart()
		) {
			add_action( 'woocommerce_add_to_cart', array( $this, 'hook_sync_orders_on_add_to_cart' ) );
		}
	}

	/**
	 * Removes a Faire order ID from the list of already synced orders.
	 *
	 * When a WooCommerce order created during a Faire orders sync is permanently
	 * deleted, we remove the related Faire order ID from the list of already
	 * synced orders. This prevents that the order gets permanently excluded from
	 * being synced.
	 *
	 * @param int $order_id The WooCommerce order ID.
	 *
	 * @return void
	 */
	public function delete_from_synced_faire_orders( int $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$faire_order_id = $order->get_meta( '_faire_order_id', true );
		if ( $faire_order_id ) {
			$this->sync_status->delete_order_already_synced( $faire_order_id );
		}
	}

	/**
	 * Handles add to cart action.
	 *
	 * @return void
	 */
	public function hook_sync_orders_on_add_to_cart() {
		if ( 'yes' !== get_option( 'woocommerce_manage_stock' ) ) {
			return;
		}
		if ( $this->sync_status->check_sync_running() ) {
			return;
		}
		if ( $this->sync_status->check_sync_finished_recently() ) {
			return;
		}
		$sync_scheduler = new Sync_Order_Scheduler(
			array( $this, 'sync_orders_once' ),
			$this->settings
		);
		$sync_scheduler->start_once_job();
	}

	/**
	 * Runs a single time orders sync.
	 *
	 * @return void
	 */
	public function sync_orders_once() {
		$this->sync_status->sync_running( true );
		$this->sync_status->save_orders_sync_results( $this->import_orders() );
		$this->sync_status->save_last_sync_finish_timestamp();
		$this->sync_status->sync_running( false );
	}

	/**
	 * Handles Ajax requests to sync Faire orders.
	 *
	 * @return void
	 */
	public function ajax_orders_manual_sync() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_orders_manual_sync' )
		) {
			wp_send_json_error(
				__( '<i class="error"></i> Manual sync failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		$result = $this->sync_orders();
		if ( $this->sync_status::ORDERS_SYNC_RUNNING_STATUS === $result ) {
			wp_send_json_error(
				__( 'An orders sync is already in progress.', 'faire-for-woocommerce' ),
				400
			);
		}
		if ( $this->sync_status::ORDERS_SYNC_FINISHED_RECENTLY_STATUS === $result ) {
			wp_send_json_error(
				sprintf(
					__( '<i class="warning"></i> An orders sync finished recently. Please, try later @ %s', 'faire-for-woocommerce' ),
					gmdate( 'Y-m-d\TH:i:s.v\Z', $this->sync_status->get_last_sync_finish_timestamp() + 300 )
				),
				400
			);
		}

		$data['message'] = false !== strpos( $this->sync_status->get_orders_sync_results(), 'Could not find orders to import' )
			? '<i class="ok"></i> ' . __( 'No new orders to sync.', 'faire-for-woocommerce' )
			: '';

		$data['details'] = $this->sync_status->get_orders_sync_results();

		wp_send_json_success( $data );
	}

	/**
	 * Handles Ajax requests to cancel Faire orders sync.
	 *
	 * @return void
	 */
	public function ajax_cancel_orders_manual_sync() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_cancel_orders_manual_sync' )
		) {
			wp_send_json_error(
				__( 'Canceling orders sync failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		if ( ! $this->sync_status->check_sync_running() ) {
			wp_send_json_error(
				__( 'Orders sync is no longer in progress. Please refresh the page.', 'faire-for-woocommerce' ),
				400
			);
		}

		$this->sync_status->sync_running( false );
		wp_send_json_success(
			__( 'Orders sync process canceled.', 'faire-for-woocommerce' )
		);

	}

	/**
	 * Runs orders sync and saves results.
	 *
	 * @return int
	 */
	public function sync_orders(): int {
		if ( $this->sync_status->check_sync_running() ) {
			return $this->sync_status::ORDERS_SYNC_RUNNING_STATUS;
		}
		if ( $this->sync_status->check_sync_finished_recently() ) {
			return $this->sync_status::ORDERS_SYNC_FINISHED_RECENTLY_STATUS;
		}
		try {
			$this->sync_status->sync_running( true );
			$results = $this->import_orders();
			$this->sync_status->update_orders_sync_occurrence();
			$this->sync_status->save_last_sync_finish_timestamp();
			$this->sync_status->save_orders_sync_results( $results );
			$this->sync_status->sync_running( false );
		} catch ( Exception $e ) {
			// TODO: Log error.
			$this->sync_status->sync_running( false );
		}

		return $this->sync_status::ORDERS_SYNC_FINISHED_STATUS;
	}

	/**
	 * Imports orders from Faire into the shop.
	 *
	 * @return array Results of the orders import.
	 */
	public function import_orders(): array {
		$synced_orders        = array();
		$is_first_orders_sync = $this->sync_status->is_first_orders_sync();

		// We go back 30 days from the last orders sync to ensure no orders
		// get stuck un-synced forever.
		$offset         = $is_first_orders_sync ? '-30 days' : '';
		$updated_at_min = gmdate(
			'Y-m-d\TH:i:s.v\Z',
			strtotime( $this->sync_status->get_orders_last_sync_date() . $offset )
		);

		$this->faire_get_orders_args = array(
			'updated_at_min' => $updated_at_min,
			'page'           => 1,
		);

		$results = array();

		do {
			$orders = $this->get_orders_page( $this->faire_get_orders_args );
			$error  = $this->check_orders_page_errors( $orders );
			if ( $error ) {
				$results[] = $error;
				return $results;
			}

			$orders_in_page = count( $orders );
			// No orders in the first page, something might be wrong.
			$error = $this->check_first_orders_page_empty( $orders_in_page );
			if ( $error ) {
				$results[] = $error;
				return $results;
			}

			$skip_order_create = $this->settings->get_order_sync_skip_orders_create();

			// Process the retrieved orders.
			foreach ( $orders as $order ) {
				$faire_order = new Faire_Order( $order );

				// On first orders sync, we skip Faire orders with following statuses.
				if (
					$is_first_orders_sync
					&& in_array( $faire_order->get_state(), array( 'PRE_TRANSIT', 'IN_TRANSIT', 'DELIVERED', 'CANCELED' ), true )
				) {
					$results[] = Utils::create_import_result_entry(
						true,
						sprintf(
							// translators: %1$s Faire order ID, %2$d Faire order status.
							__( 'Faire order skipped on first sync: %1$s. Status %2$s.', 'faire-for-woocommerce' ),
							$faire_order->get_id(),
							$faire_order->get_state()
						)
					);
					continue;
				}

				$result      = array();
				$wc_order_id = WC_Order::get_order_by_faire_id( $faire_order->get_id() );

				// Faire orders with the following statuses may create or update a related
				// WooCommerce order.
				if ( in_array( $faire_order->get_state(), array( 'NEW', 'PROCESSING', 'BACKORDERED', 'PRE_TRANSIT', 'IN_TRANSIT', 'DELIVERED', 'CANCELED' ), true ) ) {
					$result = $wc_order_id
						? $this->sync_order_status( $faire_order, $wc_order_id )
						: $this->sync_faire_to_wc_order( $faire_order, $skip_order_create );
				}

				if ( $result && 'success' === $result['status'] ) {
					$synced_orders[] = $faire_order->get_id();
				}
				$results[] = $result;
			}
			$this->faire_get_orders_args['page']++;
		} while ( self::ORDERS_PER_PAGE === $orders_in_page );

		$total_orders_synced = count( $synced_orders );
		if ( $total_orders_synced ) {
			$this->sync_status->save_orders_already_synced( $synced_orders );
		}
		$this->sync_status->save_orders_last_sync_date();

		$results[] = Utils::create_import_result_entry(
			true,
			sprintf(
				// translators: %1$d number of orders imported, %2$d date of import.
				__( 'Successfully synced: %1$d. Date %2$s.', 'faire-for-woocommerce' ),
				$total_orders_synced,
				$this->sync_status->get_orders_last_sync_date()
			)
		);

		return $results;
	}

	/**
	 * Checks if retrieving a page of orders failed with errors.
	 *
	 * @param array $orders Orders retrieved or errors result.
	 *
	 * @return array Resulting errors.
	 */
	private function check_orders_page_errors( array $orders ): array {
		$errors = array();
		if ( isset( $orders['error'] ) ) {
			$errors[] = Utils::create_import_error_entry(
				sprintf(
					// translators: %1d orders page, %2$s: date of import.
					__( 'Orders import failed at page %1$d. Date: %2$s', 'faire-for-wordpress' ),
					$this->faire_get_orders_args['page'],
					$this->faire_get_orders_args['updated_at_min']
				)
			);
			$errors[] = Utils::create_import_error_entry(
				$orders['error']['code'] . ': ' . $orders['error']['message']
			);
		}

		return $errors;
	}

	/**
	 * Checks if the first page of retrieved orders is empty.
	 *
	 * @param int $orders_in_page Orders retrieved.
	 *
	 * @return array Resulting error.
	 */
	private function check_first_orders_page_empty( int $orders_in_page ): array {
		$errors = array();
		if ( 0 === $orders_in_page && 1 === $this->faire_get_orders_args['page'] ) {
			$errors = Utils::create_import_error_entry(
				sprintf(
					// translators: %s date of import.
					__( 'Could not find orders to import. Date: %s', 'faire-for-wordpress' ),
					$this->faire_get_orders_args['updated_at_min']
				)
			);
		}

		return $errors;
	}

	/**
	 * Creates or updates a WooCommerce order from a given Faire order data.
	 *
	 * @param Faire_Order $faire_order       A Faire order.
	 * @param bool        $skip_order_create If true, WC order should not be created.
	 *
	 * @return array The result of the order sync.
	 */
	private function sync_faire_to_wc_order(
		Faire_Order $faire_order,
		bool $skip_order_create
	): array {
		$wc_order_id = WC_Order::get_order_by_faire_id( $faire_order->get_id() );
		if ( $wc_order_id ) {
			return $this->sync_order_status( $faire_order, $wc_order_id );
		}

		return $skip_order_create
			? WC_Order::update_inventory( $faire_order )
			: WC_Order::create( $faire_order );
	}

	/**
	 * Syncs the status of a Faire order into an existing WooCommerce order.
	 *
	 * @param Faire_Order $faire_order A Faire order.
	 * @param int         $wc_order_id  The ID of a WooCommerce order.
	 *
	 * @return array The result of the order status sync.
	 */
	private function sync_order_status(
		Faire_Order $faire_order,
		int $wc_order_id
	): array {
		$faire_order_state    = $faire_order->get_state();
		$status_update_result = ( 'backordered' === strtolower( $faire_order_state ) )
			? WC_Order::apply_backorder( $faire_order, $wc_order_id )
			: WC_Order::apply_status( $wc_order_id, $faire_order_state );

		if ( true === $status_update_result ) {
			// translators: %1$s ID of the order.
			$raw_message = __( 'Order status updated. Faire Order ID: %1$s, WC Order ID: %2$s.', 'faire-for-woocommerce' );
			$result      = true;
		}

		if ( false === $status_update_result ) {
			// translators: %1$s ID of the order.
			$raw_message = __( 'Order status could not be updated. Faire Order ID: %1$s, WC Order ID: %2$s.', 'faire-for-woocommerce' );
			$result      = false;
		}

		if ( null === $status_update_result ) {
			// translators: %1$s ID of the Faire order.
			$raw_message = __( 'Order status already up to date, skipping. Faire Order ID: %1$s, WC Order ID: %2$s.', 'faire-for-woocommerce' );
			$result      = true;
		}

		return Utils::create_import_result_entry(
			$result,
			sprintf( $raw_message, $faire_order->get_id(), $wc_order_id )
		);
	}

	/**
	 * Retrieves a page of Faire orders that were updated after a given date.
	 *
	 * @param array $args Arguments to retrieve orders from the Faire API.
	 *
	 * @return object[] Page of orders.
	 */
	private function get_orders_page( array $args ): array {
		$default_args = array(
			'page'  => 1,
			'limit' => self::ORDERS_PER_PAGE,
		);

		$args = wp_parse_args( $args, $default_args );

		try {
			$orders = $this->order_api->get_orders( $args )->orders;
		} catch ( Exception $e ) {
			$orders = array(
				'error' => array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);
		}

		return $orders;
	}

}
