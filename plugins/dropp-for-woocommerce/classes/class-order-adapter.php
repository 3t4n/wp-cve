<?php
/**
 * Booking
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Models\Dropp_Consignment;
use Dropp\Models\Dropp_Customer;
use Dropp\Models\Dropp_Location;
use Dropp\Models\Dropp_Product_Line;
use Exception;
use WC_Log_Levels;
use WC_Logger;
use WC_Order;
use WC_Order_Item_Shipping;

/**
 * API Booking
 */
class Order_Adapter {

	protected $order;

	/**
	 * Construct
	 *
	 * @param WC_Order $order Order.
	 */
	public function __construct( WC_Order $order ) {
		$this->order = $order;
	}

	/**
	 * Is dropp
	 *
	 * @return boolean True if the dropp shipping method is present on the order.
	 */
	public function is_dropp(): bool {
		$dropp_methods  = array_keys(
			Dropp::get_shipping_methods( true )
		);
		$shipping_items = $this->get_shipping_items();
		foreach ( $shipping_items as $shipping_item ) {
			if ( in_array( $shipping_item->get_method_id(), $dropp_methods, true ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Count consignments
	 *
	 * @param boolean $only_booked (optional) Default is false. Only counts booked items when true.
	 *
	 * @return integer              Count.
	 */
	public function count_consignments( bool $only_booked = false ): int {
		global $wpdb;
		$shipping_items    = $this->get_shipping_items();
		$shipping_item_ids = [];
		foreach ( $shipping_items as $shipping_item ) {
			$shipping_item_ids[] = $shipping_item->get_id();
		}
		if ( empty( $shipping_item_ids ) ) {
			return 0;
		}
		$shipping_item_ids = implode( ',', $shipping_item_ids);
		$sql = "SELECT count(*) FROM {$wpdb->prefix}dropp_consignments WHERE shipping_item_id in ({$shipping_item_ids})";
		if ( $only_booked ) {
			$sql .= " AND status NOT IN ( 'ready', 'error', 'overweight' )";
		}
		$result = $wpdb->get_var( $sql );
		return (int) $result;
	}

	/**
	 * Consignments
	 *
	 * @return Collection Collection of consignments.
	 */
	public function consignments(): Collection {
		$line_items = $this->get_shipping_items();
		$container  = [];
		foreach ( $line_items as $order_item_id => $order_item ) {
			$container = array_merge(
				$container,
				Dropp_Consignment::from_shipping_item( $order_item )
			);
		}
		return new Collection( $container );
	}

	/**
	 * Make consignment
	 *
	 * @param WC_Order_Item_Shipping $shipping_item Shipping item.
	 * @param array $product_lines Product lines.
	 *
	 * @return Dropp_Consignment         Consignment.
	 */
	public function make_consignment( WC_Order_Item_Shipping $shipping_item, array $product_lines = [] ): ?Dropp_Consignment {
		$dropp_methods = array_keys(
			Dropp::get_shipping_methods( true )
		);
		if ( ! in_array( $shipping_item->get_method_id(), $dropp_methods, true ) ) {
			return null;
		}
		$billing_address  = $this->order->get_address();
		$shipping_address = $this->order->get_address( 'shipping' );
		$line_items       = $this->get_shipping_items();

		// Fix missing args in address.
		if ( empty( $shipping_address['email'] ) ) {
			$shipping_address['email'] = $billing_address['email'];
		}
		if ( empty( $shipping_address['phone'] ) ) {
			$shipping_address['phone'] = $billing_address['phone'];
		}

		$instance_id     = $shipping_item->get_instance_id();
		$shipping_method = new Shipping_Method\Dropp( $instance_id );
		$location        = Dropp_Location::from_shipping_item( $shipping_item );

		if ( ! $location->id ) {
			return null;
		}

		if ( empty( $product_lines ) ) {
			$product_lines = Dropp_Product_Line::array_from_order( $this->order, true );
		}
		$consignment        = new Dropp_Consignment();
		$consignment->debug = $shipping_method->debug_mode;
		$comment            = '';
		if ( $shipping_method->copy_order_notes ) {
			$comment = $this->order->get_customer_note();
		}
		$consignment->fill(
			[
				'shipping_item_id' => $shipping_item->get_id(),
				'location_id'      => $location->id,
				'customer'         => Dropp_Customer::from_shipping_address( $shipping_address ),
				'products'         => $product_lines,
				'day_delivery'     => $shipping_item->get_method_id() === 'dropp_daytime',
				'comment'          => $comment,
				'test'             => $shipping_method->test_mode,
				'mynto_id'         => $shipping_item->get_meta('mynto_id'),
			]
		);
		return $consignment;
	}

	/**
	 * Book order
	 *
	 * @return boolean True if any order was booked.
	 * @throws Exception
	 */
	public function book(): bool {
		$shipping_items = $this->get_shipping_items();

		$any_booked = false;
		foreach ( $shipping_items as $shipping_item ) {
			$product_lines = Dropp_Product_Line::array_from_order( $this->order, true );
			$consignment   = $this->make_consignment( $shipping_item, $product_lines );
			if ( ! $consignment ) {
				continue;
			}
			if ( ! $consignment->check_weight() ) {
				$consignment->status         = 'overweight';
				$consignment->status_message = sprintf(
					__( 'Cannot book the order because it\'s over the weight limit of %d Kg', 'dropp-for-woocommerce' ),
					$consignment->get_shipping_method()->weight_limit ?? 10
				);
				$consignment->save();
				continue;
			}

			$consignment->save();

			try {
				$consignment->remote_post();
				$consignment->save();
				$consignment->maybe_update_order_status();
				$any_booked = true;
			} catch ( \Exception $e ) {
				$consignment->status         = 'error';
				$consignment->status_message = $e->getMessage();
				$consignment->save();
			}
		}
		return $any_booked;
	}

	/**
	 * Book order
	 *
	 * @return boolean True if any order was booked.
	 */
	public function add_new(): bool {
		$shipping_items = $this->get_shipping_items();

		$any_added = false;
		foreach ( $shipping_items as $shipping_item ) {
			$consignment = $this->make_consignment( $shipping_item );
			if ( ! $consignment ) {
				continue;
			}
			try {
				$response  = $consignment->remote_add();
				$success   = ( $response['status'] ?? null ) === 0;
				$any_added = ( $success || $any_added );
			} catch ( Exception $e ) {
				// Log the error.
				$log = new WC_Logger();
				$log->add(
					'dropp-for-woocommerce',
					'[ERROR] ' . $e->getMessage(),
					WC_Log_Levels::ERROR
				);
			}
		}
		return $any_added;
	}

	/**
	 * Get shipping items
	 *
	 * @return array Shipping items.
	 */
	public function get_shipping_items(): array {
		return $this->order->get_items( 'shipping' );
	}
}
