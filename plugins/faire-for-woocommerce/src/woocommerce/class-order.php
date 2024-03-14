<?php
/**
 * WooCommerce Orders.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Woocommerce;

use Exception;
use Faire\Wc\Country;
use Faire\Wc\Faire\Order as Faire_Order;
use Faire\Wc\Faire\Order_Status as Faire_Order_Status;
use Faire\Wc\Utils;
use Faire\Wc\Woocommerce\Product as Faire_WC_Product;
use WC_Data_Exception;
use WC_Coupon;
use WC_Order;
use WC_Order_Item_Product;
use WC_Product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WooCommerce Orders.
 */
class Order extends WC_Order {

	/**
	 * Creates a WooCommerce order using data from a Faire order.
	 *
	 * @param Faire_Order $faire_order The Faire order.
	 *
	 * @return array{ "status": string, "info": string } Result of the order creation.
	 *
	 * @throws WC_Data_Exception
	 */
	public static function create( Faire_Order $faire_order ): array {
		$faire_order_id = $faire_order->get_id();

		// Prepare the products to add to the order.
		$wc_products = Faire_WC_Product::get_wc_products_in_faire_order( $faire_order->get_items() );

		// No products to add.
		if ( 0 === count( $wc_products ) ) {
			return Utils::create_import_error_entry(
				sprintf(
					// Translators: %s Faire  order ID.
					__( 'No products found to add to the order. Faire order ID: %s.' ),
					$faire_order_id
				)
			);
		}

		// Create the WooCommerce order.
		$wc_order = wc_create_order();

		if ( ! $wc_order instanceof WC_Order ) {
			return Utils::create_import_error_entry(
				sprintf(
					// Translators: %s Faire  order ID.
					__( 'Failed to create WC_Order. Faire order ID: %s.' ),
					$faire_order_id
				)
			);
		}

		// Set the WooCommerce order currency if necessary.
		$order_currency = $faire_order->get_order_currency();
		if ( $order_currency ) {
			$wc_order->set_currency( $order_currency );
		}
		// Add Faire order ID as metadata.
		$wc_order->add_meta_data( '_faire_order_id', $faire_order->get_id() );
		// Set order billing and shipping address.
		$wc_order = self::set_addresses( $wc_order, $faire_order->get_address() );
		// Add products to the order.
		$items_created = self::add_products( $wc_order, $wc_products );

		if ( ! $items_created ) {
			$error = sprintf(
				'Could not add products to faire order %s, WC order %d',
				$faire_order_id,
				$wc_order->get_id()
			);
			return Utils::create_import_error_entry( $error );
		}

		self::maybe_apply_cart_discount( $wc_order, $faire_order );

		$wc_order->calculate_totals();

		$items_added = self::add_items_meta( $items_created );
		if ( ! $items_added ) {
			$error = sprintf(
				'Could not add item meta data for faire order %s, WC order %d',
				$faire_order_id,
				$wc_order->get_id()
			);
			return Utils::create_import_error_entry( $error );
		}

		$message = sprintf(
			// translators: %s ID of the order.
			__( 'Order imported from FAIRE. ID: %s', 'faire-for-woocommerce' ),
			$faire_order_id
		);
		$order_status = Faire_Order_Status::get_faire_to_wc_order_status(
			$faire_order->get_state()
		);
		$wc_order->set_status( $order_status, $message, true );

		// Ensure we maintain correct stock values.
		if (
			in_array( $order_status, array( 'faire-new', 'faire-backordered' ), true )
		) {
			$wc_order_id = $wc_order->get_id();
			wc_reduce_stock_levels( $wc_order_id );
			$wc_order->set_order_stock_reduced( true );
		}

		$wc_order->save();

		return Utils::create_import_success_entry( $message );
	}

	/**
	 * Updates WooCommerce inventory to a given Faire order data.
	 *
	 * @param Faire_Order $faire_order A Faire order.
	 *
	 * @return array{ status: string, info: string } The result of the order sync.
	 */
	public static function update_inventory( Faire_Order $faire_order ): array {
		$order_products = Faire_WC_Product::get_wc_products_in_faire_order( $faire_order->get_items() );
		if ( ! $order_products ) {
			return Utils::create_import_error_entry(
				sprintf(
					// translators: %s ID of the order.
					__( 'Could not find products from the order. ID %s', 'faire-for-woocommerce' ),
					$faire_order->get_id()
				)
			);
		}

		$failed_products = Faire_WC_Product::update_inventory(
			$order_products,
			Faire_Order_Status::get_faire_to_wc_order_status( $faire_order->get_state() )
		);

		$success = 0 === count( $failed_products );
		$message = $success
			? sprintf(
				// translators: %1$s ID of the order, %2$s products ID.
				__( 'Inventory updated. ID: %s', 'faire-for-woocommerce' ),
				$faire_order->get_id(),
			)
			: sprintf(
				// translators: %1$s ID of the order, %2$s products ID.
				__( 'Some products stock could not be updated. Order ID: %1$s. Products ID: %2$s', 'faire-for-woocommerce' ),
				$faire_order->get_id(),
				implode( ', ', $failed_products )
			);

		return Utils::create_import_result_entry( $success, $message );
	}

	/**
	 * Get details about the products in an order.
	 *
	 * @param WC_Order $order The shop order.
	 *
	 * @return array<array{ wc_item_id: int, faire_item_id: string, product_id: int, parent_id: int, is_variation: bool, name: string, thumbnail: string, link: string, quantity: int, stock: int }>
	 */
	public static function get_order_products_details( WC_Order $order ): array {
		$order_items = $order->get_items();
		$products    = array_map(
			function ( WC_Order_Item_Product $item ) {
				$wc_item_id    = $item->get_id();
				$faire_item_id = $item->get_meta( 'faire_order_item' )->id;
				$product       = $item->get_product();
				if ( ! $product instanceof WC_Product ) {
					return null;
				}
				$product_id   = $product->get_id();
				$is_variation = 'variation' === $product->get_type();
				$parent_id    = $is_variation ? $product->get_parent_id() : 0;
				$quantity     = $item->get_quantity();
				$thumbnail    = $product->get_image( 'woocommerce_gallery_thumbnail' );
				$link         = sprintf(
					'https://fairedev.local/wp-admin/post.php?post=%s&action=edit',
					$parent_id ? $parent_id : $product_id
				);

				return array(
					'wc_item_id'    => $wc_item_id,
					'faire_item_id' => $faire_item_id,
					'product_id'    => $product_id,
					'parent_id'     => $parent_id,
					'is_variation'  => $is_variation,
					'name'          => $product->get_name(),
					'thumbnail'     => $thumbnail,
					'link'          => $link,
					'quantity'      => $quantity,
					'stock'         => $product->get_stock_quantity(),
				);
			},
			$order_items
		);

		$products = array_filter( $products, fn ( $value ) => ! is_null( $value ) );
		return array_values( $products );
	}

	/**
	 * Sets the billing and shipping address for an order.
	 *
	 * @param WC_Order $wc_order The order.
	 * @param object   $address  The address for the order.
	 *
	 * @return WC_Order The updated order.
	 */
	private static function set_addresses(
		WC_Order $wc_order,
		object $address
	): WC_Order {
		// Gather data to create the order.
		$address = array(
			'first_name' => $address->name,
			'last_name'  => '',
			'company'    => $address->company_name ?? '',
			'phone'      => $address->phone_number ?? '',
			'address_1'  => $address->address1,
			'address_2'  => $address->address2 ?? '',
			'city'       => $address->city,
			'state'      => $address->state_code ?? '',
			'postcode'   => $address->postal_code,
			'country'    => Country::convert_country_iso3_to_iso2( $address->country_code ),
		);

		/** @noinspection PhpRedundantOptionalArgumentInspection */
		$wc_order->set_address( $address, 'billing' );
		$wc_order->set_address( $address, 'shipping' );

		return $wc_order;
	}

	/**
	 * Adds products to an order.
	 *
	 * @param WC_Order $wc_order The Order.
	 * @param array    $products Products to add to the order.
	 *
	 * @return array List of generated order items.
	 */
	private static function add_products(
		WC_Order $wc_order,
		array $products
	): array {
		$result = array();

		foreach ( $products as $product ) {
			$product            = apply_filters( 'faire_add_to_order_product', $product );
			$product_unit_price = self::get_order_item_price( $product['item'] ) / 100.0;
			$wc_item_id         = $wc_order->add_product(
				$product['product_obj'],
				$product['quantity'],
				array(
					'subtotal' => $product_unit_price,
					'total'    => $product_unit_price * $product['quantity'],
				)
			);
			self::maybe_apply_item_discount( $wc_order, $product );

			$result[] = array(
				'item_id' => $wc_item_id,
				'item'    => $product['item'],
			);
		}

		return $result;
	}

	/**
	 * Returns the unit price of a given Faire order item.
	 *
	 * @param object|null $item The order item.
	 *
	 * @return int The item price.
	 */
	private static function get_order_item_price( ?object $item ): int {
		// Field price_cents is null if the brand’s shop currency
		// is not set to USD.
		$item_price = $item->price_cents ?? $item->price->amount_minor;

		return $item_price;
	}

	/**
	 * Apply item discount to WC_Order.
	 *
	 * @param  WC_Order $wc_order WC_Order.
	 * @param  array    $product Array[ product_obj: WC_Product, quantity: int, item: Faire_Order_Item].
	 *
	 * @return void
	 */
	private static function maybe_apply_item_discount( $wc_order, $product ) {

		$discount_types = array(
			'FLAT_AMOUNT' => 'fixed_product',
			'PERCENTAGE'  => 'percent',
		);

		if ( empty( $product['item']->discounts ) || ! is_array( $product['item']->discounts ) ) {
			return;
		}

		foreach ( $product['item']->discounts as $discount ) {

			if ( ! isset( $discount_types[ $discount->discount_type ] ) ) {
				continue;
			}

			if ( 'FLAT_AMOUNT' === $discount->discount_type ) {
				$amount = $discount->discount_amount->amount_minor / 100.0;
			}

			if ( 'PERCENTAGE' === $discount->discount_type ) {
				$amount = $discount->discount_percentage;
			}

			$product_id = $product['product_obj']->get_id();
			$discount_type = $discount_types[ $discount->discount_type ];

			add_filter(
				'woocommerce_get_shop_coupon_data',
				function() use ( $discount, $amount, $discount_type, $product_id ) {
					return array(
						'code'          => $discount->code . '_' . $discount->id,
						'amount'        => $amount,
						'discount_type' => $discount_type,
						'product_ids'   => array( $product_id ),
					);
				}
			);

			$coupon = new \WC_Coupon( $discount->code . '_' . $discount->id );

			if ( $coupon instanceof \WC_Coupon ) {
				$applied = $wc_order->apply_coupon( $coupon );
			}

			$test = $applied;
		}
	}

	/**
	 * Adds Faire order items data as metadata to order items.
	 *
	 * Faire order item details is saved as metadata to each order item generated
	 * when products were added to the order.
	 *
	 * @param array $items Items created when added products to the order.
	 *
	 * @return bool True if items metadata could be added.
	 */
	private static function add_items_meta( array $items ): bool {
		$result = true;

		foreach ( $items as $item ) {
			try {
				wc_add_order_item_meta(
					$item['item_id'],
					'faire_order_item',
					$item['item'],
					true
				);
			} catch ( Exception $e ) {
				$result = false;
			}
		}

		return $result;
	}

	/**
	 * Apply cart discount to WC_Order.
	 *
	 * @param  \WC_Order   $wc_order WC_Order.
	 * @param  Faire_Order $faire_order Faire order.
	 *
	 * @return void
	 */
	private static function maybe_apply_cart_discount( $wc_order, $faire_order ) {
		$discount_types = array(
			'FLAT_AMOUNT' => 'fixed_cart',
			'PERCENTAGE'  => 'percent',
		);

		foreach ( $faire_order->get_discounts() as $discount ) {

			if ( ! isset( $discount_types[ $discount->discount_type ] ) ) {
				continue;
			}

			if ( 'FLAT_AMOUNT' === $discount->discount_type ) {
				$amount = $discount->discount_amount->amount_minor / 100.0;
			}

			if ( 'PERCENTAGE' === $discount->discount_type ) {
				$amount = $discount->discount_percentage;
			}

			$coupon_data = array(
				'code'          => $discount->code . '_' . $discount->id,
				'amount'        => $amount,
				'discount_type' => $discount_types[ $discount->discount_type ],
			);

			$coupon = new WC_Coupon();
			$coupon->read_manual_coupon( $coupon_data['code'], $coupon_data );

			if ( $coupon instanceof WC_Coupon ) {
				$wc_order->apply_coupon( $coupon );
			}
		}
	}

	/**
	 * Updates purchased quantity for backordered order items.
	 *
	 * @param array $items_data Details for the backordered items.
	 *
	 * @throws Exception
	 */
	public static function update_backordered_items( array $items_data ) {
		foreach ( $items_data as $item_data ) {
			$current_qty = (int) wc_get_order_item_meta( $item_data['item_id'], '_qty' );
			$new_qty     = $current_qty - (int) $item_data['backordered'];
			wc_update_order_item_meta(
				$item_data['item_id'],
				'_qty',
				$new_qty
			);
		}
	}

	/**
	 * Updates the state of a given WooCommerce order.
	 *
	 * @param int    $wc_order_id A WooCommerce order ID.
	 * @param string $status      Status for the order.
	 *
	 * @return bool|null True if the order is successfully updated, false is status can't be updated, null if status is already up to date.
	 */
	public static function apply_status(
		int $wc_order_id,
		string $status
	) {
		$mapped_status = Faire_Order_Status::get_faire_to_wc_order_status( $status );

		$wc_order = wc_get_order( $wc_order_id );
		if ( ! $wc_order instanceof WC_Order ) {
			return false;
		}

		if ( $mapped_status === $wc_order->get_status() ) {
			return null;
		}

		return $wc_order->update_status( $mapped_status );
	}

	/**
	 * Backorders a WooCommerce order related to a given Faire order.
	 *
	 * To backorder a WC order, we restore the stock for the products in that
	 * order, delete the WC order items and add the items form the imported
	 * backordered Faire order, updating WC order totals and products stocks.
	 *
	 * @param Faire_Order $faire_order The Faire order.
	 * @param int         $wc_order_id The WooCommerce order ID.
	 *
	 * @return bool True if the order was successfully backordered.
	 */
	public static function apply_backorder(
		Faire_Order $faire_order,
		int $wc_order_id
	): bool {
		$wc_order = wc_get_order( $wc_order_id );
		if ( ! $wc_order instanceof WC_Order ) {
			return false;
		}

		// Prepare the products to add to the order.
		$wc_products = Faire_WC_Product::get_wc_products_in_faire_order(
			$faire_order->get_items()
		);
		if ( 0 === count( $wc_products ) ) {
			return false;
		}
		wc_maybe_increase_stock_levels( $wc_order_id );
		$wc_order->remove_order_items();
		self::add_products( $wc_order, $wc_products );
		wc_reduce_stock_levels( $wc_order_id );
		$wc_order->set_order_stock_reduced( true );
		$wc_order->calculate_totals();
		$order_status = Faire_Order_Status::get_faire_to_wc_order_status(
			$faire_order->get_state()
		);
		$wc_order->update_status( $order_status, '', true );

		return true;
	}

	/**
	 * Retrieves the ID of a WooCommerce order with a given Faire order ID.
	 *
	 * Faire order ID is saved as a metadata field of WooCommerce related orders.
	 *
	 * @param string $order_id The Faire order ID.
	 *
	 * @return int Returns the WooCommerce order ID if it exists, 0 otherwise.
	 */
	public static function get_order_by_faire_id( string $order_id ): int {
		$args = array(
			'limit'        => 1,
			'meta_key'     => '_faire_order_id',
			'meta_value'   => $order_id,
			'meta_compare' => '=',
			'return'       => 'ids',
		);

		$orders = wc_get_orders( $args );

		return isset( $orders[0] ) ? $orders[0] : 0;
	}

}
