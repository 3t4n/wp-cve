<?php
/**
 * Orders backend common functionality.
 *
 * @package Faire/Admin
 */

namespace Faire\Wc\Admin\Order;

use Exception;
use Faire\Wc\Api\Order_Api;
use Faire\Wc\Woocommerce\Order as Shop_Order;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Orders backend functionality class.
 */
class Order {

	/**
	 * Faire order ID custom column.
	 *
	 * @var string
	 */
	const FAIRE_ORDER_ID_COLUMN = 'faire_order_id';

	/**
	 * Faire order ID meta key.
	 *
	 * @var string
	 */
	const FAIRE_ORDER_ID_FIELD = '_faire_order_id';

	/**
	 * Instance of Faire\Wc\Api\Order class.
	 *
	 * @var Order_Api
	 */
	private Order_Api $order_api;

	/**
	 * Class constructor.
	 *
	 * @param Order_Api $order_api Order_Api class instance.
	 */
	public function __construct( Order_Api $order_api ) {
		$this->order_api = $order_api;

		// Adds custom single order page meta-boxes.
		add_action( 'admin_init', array( $this, 'add_order_metaboxes' ) );

		// Adds Faire ID custom column to orders listing.
		add_filter(
			'manage_edit-shop_order_columns',
			array( $this, 'add_faire_order_id_column' ),
			20
		);
		add_filter(
			'manage_woocommerce_page_wc-orders_columns',
			array( $this, 'add_faire_order_id_column' ),
			20
		);

		// Fills custom column Faire order ID.
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'fill_faire_order_id_column_post' ), 20, 2 );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'fill_faire_order_id_column_order' ), 20, 2 );

		// Makes Faire ID custom column sortable.
		add_filter(
			'manage_edit-shop_order_sortable_columns',
			array( $this, 'sort_faire_order_id_column' )
		);
		add_action( 'pre_get_posts', array( $this, 'faire_order_id_column_sort' ) );
		// Makes orders searchable by Faire ID.
		add_filter(
			'woocommerce_shop_order_search_fields',
			array( $this, 'faire_order_id_searchable_field' )
		);
		add_filter(
			'woocommerce_order_table_search_query_meta_keys',
			array( $this, 'faire_order_id_searchable_field' )
		);

		// Handles the Ajax call to accept a Faire order.
		add_action( 'wp_ajax_accept_faire_order', array( $this, 'ajax_accept_faire_order' ) );
		// Handles the AJAX call to update the status of a shop order.
		add_action( 'wp_ajax_update_order_status', array( $this, 'ajax_update_order_status' ) );
		// Handles the AJAX call to set an order shipment.
		add_action( 'wp_ajax_set_order_shipment', array( $this, 'ajax_set_order_shipment' ) );
		// Handles the AJAX call to backorder products.
		add_action( 'wp_ajax_backorder_products', array( $this, 'ajax_backorder_products' ) );
	}

	/**
	 * Adds custom single order page meta-boxes.
	 */
	public function add_order_metaboxes() {
		add_meta_box(
			'faire_order_management',
			__( 'Faire order management', 'faire-for-woocommerce' ),
			array( $this, 'add_order_management_metabox_controls' ),
			'shop_order',
			'side',
			'core'
		);
		add_meta_box(
			'faire_backorder',
			__( 'Faire backorder', 'faire-for-woocommerce' ),
			array( $this, 'add_backorder_metabox_controls' ),
			'shop_order',
			'normal',
			'core'
		);
	}

	/**
	 * Adds Faire ID custom column to orders listing.
	 *
	 * @param array $columns Columns in orders listing table.
	 *
	 * @return array Updated columns.
	 */
	public function add_faire_order_id_column( array $columns ): array {
		$updated_columns = array();

		foreach ( $columns as $key => $column ) {
			$updated_columns[ $key ] = $column;
			if ( 'order_number' === $key ) {
				$updated_columns[ self::FAIRE_ORDER_ID_COLUMN ] =
					__( 'Faire Order ID', 'faire-for-woocommerce' );
			}
		}
		return $updated_columns;
	}

	/**
	 * Fills custom column Faire order ID.
	 *
	 * @param string $column      Name of a column in the orders listing.
	 * @param int    $wc_order_id WooCommerce order ID.
	 */
	public function fill_faire_order_id_column_post( string $column, int $wc_order_id ) {
		if ( self::FAIRE_ORDER_ID_COLUMN !== $column ) {
			return;
		}

		$faire_order_id = $this->get_faire_order_id( $wc_order_id );
		if ( ! $faire_order_id ) {
			return;
		}
		echo esc_html( $faire_order_id );
	}

	/**
	 * Fills custom column Faire order ID.
	 *
	 * @param string    $column    Name of a column in the orders listing.
	 * @param \WC_Order $wc_order WooCommerce order ID.
	 */
	public function fill_faire_order_id_column_order( string $column, \WC_Order $wc_order ) {
		if ( self::FAIRE_ORDER_ID_COLUMN !== $column ) {
			return;
		}

		$faire_order_id = $this->get_faire_order_id( $wc_order->get_id() );
		if ( ! $faire_order_id ) {
			return;
		}
		echo esc_html( $faire_order_id );
	}

	/**
	 * Makes Faire ID custom column sortable.
	 *
	 * @param array $columns Sortable columns in orders listing table.
	 *
	 * @return array Updated columns.
	 */
	public function sort_faire_order_id_column( array $columns ): array {
		return wp_parse_args(
			array( self::FAIRE_ORDER_ID_COLUMN => self::FAIRE_ORDER_ID_FIELD ),
			$columns
		);
	}

	/**
	 * Sorts orders by custom column Faire order ID.
	 *
	 * @param WP_Query $query Query to get the orders.
	 */
	public function faire_order_id_column_sort( WP_Query $query ) {
		global $pagenow;

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if (
			'edit.php' !== $pagenow ||
			! isset( $_GET['post_type'] ) ||
			'shop_order' !== $_GET['post_type']
		) {
			return;
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( self::FAIRE_ORDER_ID_FIELD === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', self::FAIRE_ORDER_ID_FIELD );
			$query->set( 'orderby', self::FAIRE_ORDER_ID_FIELD );
		}
	}

	/**
	 * Makes orders searchable by Faire ID.
	 *
	 * @param array $meta_keys Meta keys to search orders by.
	 *
	 * @return array Updated meta keys.
	 */
	public function faire_order_id_searchable_field( array $meta_keys ): array {
		$meta_keys[] = self::FAIRE_ORDER_ID_FIELD;
		return $meta_keys;
	}

	/**
	 * Adds Faire order management custom meta-box.
	 */
	public function add_order_management_metabox_controls() {
		$wc_order_id    = $this->get_current_order_id();
		$faire_order_id = $this->get_faire_order_id( $wc_order_id );
		if ( ! $faire_order_id ) {
			echo '<p>' . esc_html__( 'Not a Faire order.', 'faire-for-woocommerce' );
			return;
		}

		$wc_order = wc_get_order( $wc_order_id );
		if ( ! $wc_order instanceof \WC_Order ) {
			return;
		}
		$wc_order_status  = $wc_order->get_status();
		$wc_order_created = $wc_order->get_date_created();
		$wc_order_updated = $wc_order->get_date_modified();

		include dirname( dirname( __FILE__ ) ) . '/templates/order-management-metabox.php';
	}

	/**
	 * Adds Faire order products backorder metabox.
	 */
	public function add_backorder_metabox_controls() {
		$wc_order_id    = $this->get_current_order_id();
		$faire_order_id = $this->get_faire_order_id( $wc_order_id );
		if ( ! $faire_order_id ) {
			return;
		}
		$products = Shop_Order::get_order_products_details( wc_get_order( $wc_order_id ) );
		include dirname( dirname( __FILE__ ) ) . '/templates/backorder-management-metabox.php';
	}

	/**
	 * Retrieves the current order ID.
	 *
	 * @return int Current order ID.
	 */
	private function get_current_order_id(): int {
		global $post;

		return $post->ID;
	}

	/**
	 * Retrieves the Faire Order ID of a given WooCommerce order.
	 *
	 * @param int $wc_order_id The WooCommerce order ID.
	 *
	 * @return string|false The Faire Order ID.
	 */
	private function get_faire_order_id( int $wc_order_id ): string {
		$order = wc_get_order( $wc_order_id );

		if ( ! $order instanceof \WC_Order ) {
			return false;
		}

		return (string) $order->get_meta( self::FAIRE_ORDER_ID_FIELD, true );
	}

	/**
	 * Sends an error response if the validation of a nonce value fails.
	 *
	 * @param array  $data The data received in the request.
	 * @param string $nonce_key The key used to generate the nonce value.
	 *
	 * @return bool True is the nonce is valid, false otherwise.
	 */
	private function validate_nonce( array $data, string $nonce_key ): bool {
		$nonce = isset( $data['nonce'] ) ?
			sanitize_text_field( wp_unslash( $data['nonce'] ) ) :
			'';
		return ! empty( $nonce ) && wp_verify_nonce( $nonce, $nonce_key );
	}

	/**
	 * Sends a JSON error signaling an unauthorized request.
	 */
	private function unauthorized_request_error() {
		wp_send_json_error(
			__( 'Request failed. Unauthorized request.', 'faire-for-woocommerce' ),
			401
		);
	}

	/**
	 * Handles the AJAX call to accept a Faire order.
	 */
	public function ajax_accept_faire_order() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! $this->validate_nonce( $_POST, 'faire_admin_order' ) ) {
			$this->unauthorized_request_error();
		}

		if ( ! isset( $_POST['order_id'] ) ) {
			return;
		}
		$order_id = sanitize_text_field( wp_unslash( $_POST['order_id'] ) );

		try {
			$this->order_api->accept_order( $order_id );
			wp_send_json_success();
		} catch ( Exception $e ) {
			wp_send_json_error(
				sprintf(
					'%s %s',
					__( 'Faire order could not be accepted', 'faire-for-woocommerce' ),
					$e->getMessage(),
				),
				401
			);
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Handles the AJAX call to set a shop order status.
	 */
	public function ajax_update_order_status() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! $this->validate_nonce( $_POST, 'faire_admin_order' ) ) {
			$this->unauthorized_request_error();
		}

		if ( ! isset( $_POST['order_id'] ) || ! isset( $_POST['status'] ) ) {
			return;
		}

		$order_id = (int) sanitize_text_field( wp_unslash( $_POST['order_id'] ) );
		$status   = sanitize_text_field( wp_unslash( $_POST['status'] ) );

		if ( ! $order_id || ! $status ) {
			wp_send_json_error();
		}

		$order = wc_get_order( $order_id );
		if ( ! $order instanceof \WC_Order ) {
			wp_send_json_error();
		}
		$order->update_status( $status ) ? wp_send_json_success() : wp_send_json_error();
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Handles the AJAX call to set the shipment carrier for a Faire order.
	 */
	public function ajax_set_order_shipment() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! $this->validate_nonce( $_POST, 'faire_admin_order' ) ) {
			$this->unauthorized_request_error();
		}

		$fields = array(
			'order_id',
			'maker_cost_cents',
			'carrier',
			'tracking_code',
		);
		$data   = $this->sanitize_text_fields( $fields, $_POST );

		// Prepare shipping cost value to be in cents.
		$price_decimals = floatval( wc_format_decimal( $data['maker_cost_cents'] ) );
		$price_cents    = intval( $price_decimals * 100 );
		$currency       = get_woocommerce_currency();

		unset( $data['maker_cost_cents'] );
		$data['maker_cost'] = array(
			'amount_minor' => $price_cents,
			'currency'     => $currency,
		);

		try {
			$this->order_api->set_order_shipment( $data );
			wp_send_json_success();
		} catch ( Exception $e ) {
			wp_send_json_error(
				sprintf(
					'%s %s',
					__( 'Faire order shipment could not be set', 'faire-for-woocommerce' ),
					$e->getMessage(),
				),
				401
			);
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Handles the AJAX call to backorder products of a Faire order.
	 */
	public function ajax_backorder_products() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! $this->validate_nonce( $_POST, 'faire_admin_order' ) ) {
			$this->unauthorized_request_error();
		}
		if ( empty( $_POST['wc_order_id'] ) ) {
			wp_send_json_error( __( 'Missing order ID.', 'faire-for-woocommerce' ), 400 );
		}
		if ( empty( $_POST['items_data'] ) ) {
			wp_send_json_error( __( 'Missing items data.', 'faire-for-woocommerce' ), 400 );
		}
		if ( empty( $_POST['faire_order_id'] ) ) {
			wp_send_json_error( __( 'Missing Faire order ID.', 'faire-for-woocommerce' ), 400 );
		}
		if ( empty( $_POST['availabilities'] ) ) {
			wp_send_json_error( __( 'Missing availabilities data.', 'faire-for-woocommerce' ), 400 );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$items_data = $this->sanitize_array( $_POST['items_data'] );

		$data = array(
			'order_id'       => sanitize_text_field(
				wp_unslash( $_POST['faire_order_id'] )
			),
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			'availabilities' => $this->sanitize_array( $_POST['availabilities'] ),
		);

		try {
			$this->order_api->backorder_products( $data );
			Shop_Order::update_backordered_items( $items_data );
			wp_send_json_success();
		} catch ( Exception $e ) {
			wp_send_json_error(
				sprintf(
					'%s %s',
					__( 'Order products could not be backordered.', 'faire-for-woocommerce' ),
					$e->getMessage(),
				),
				401
			);
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Sanitizes a set of text fields from an input array.
	 *
	 * @param array $fields List of names of text fields.
	 * @param array $input  Array of input fields.
	 *
	 * @return array List of sanitized fields.
	 */
	private function sanitize_text_fields( array $fields, array $input ): array {
		$data = array();
		foreach ( $fields as $field ) {
			$data[ $field ] = isset( $input[ $field ] ) ?
				sanitize_text_field( wp_unslash( $input[ $field ] ) ) :
				'';
		}
		return $data;
	}

	/**
	 * Sanitizes an array.
	 *
	 * @param array $array The array to sanitize.
	 *
	 * @return array Sanitized array.
	 */
	private function sanitize_array( array &$array ): array {
		foreach ( $array as &$value ) {
			if ( ! is_array( $value ) ) {
				$value = sanitize_text_field( wp_unslash( $value ) );
			} else {
				$this->sanitize_array( $value );
			}
		}

		return $array;
	}

}
