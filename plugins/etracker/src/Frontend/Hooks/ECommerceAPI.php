<?php
/**
 * Adds etracker eCommerce API functions
 *
 * @link       https://etracker.com
 * @since      1.8.0
 *
 * @package    Etracker
 */

namespace Etracker\Frontend\Hooks;

/**
 * ECommerce functions.
 *
 * This class defines all code necessary to generate etracker ecommerce events.
 *
 * @since      1.8.0
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class ECommerceAPI {
	/**
	 * Constructor.
	 *
	 * Sets default values for minimal required settings.
	 *
	 * @since    1.8.0
	 */
	public function __construct() {
		// Initialize properties with default values.
	}

	/**
	 * Gets the sku or fallback to product id.
	 *
	 * @param WC_Product $product The current product.
	 */
	private function get_sku_or_id( $product ) {
		return ( $product && $product->get_sku() ) ? $product->get_sku() : $product->get_id();
	}

	/**
	 * Gets the categories of a given products.
	 *
	 * @param WC_Product $product The current product.
	 *
	 * @return array
	 */
	private function get_categories( $product ) {
		$terms      = get_the_terms( $product->get_id(), 'product_cat' );
		$categories = array();

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return $categories;
		}

		foreach ( $terms as $category ) {
			$categories[] = $category->name;
		}

		// Restrict to 5 categories.
		return array_slice( $categories, 0, 5 );
	}

	/**
	 * Gets an etracker product object for a given product.
	 *
	 * @param WC_Product $product The current product.
	 *
	 * @return array
	 */
	private function get_product_object( $product ) {
		return array(
			'id'       => $this->get_sku_or_id( $product ),
			'name'     => $product->get_title(),
			'price'    => $product->get_price(),
			'currency' => get_woocommerce_currency(),
			'category' => $this->get_categories( $product ),
		);
	}

	/**
	 * Function for `woocommerce_after_single_product` action-hook.
	 *
	 * @return void
	 */
	public function handle_view_product() {
		try {
			global $product;
			if ( empty( $product ) ) {
				return;
			}

			$product_json = json_encode( $this->get_product_object( $product ) );
			$script       = "
				_etrackerOnReady.push(function() { etCommerce.sendEvent('viewProduct', " . $product_json . ' ); });
			';
			wc_enqueue_js( $script );
		} catch ( \Exception $e ) {
			; // phpcs:ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
		}
	}

	/**
	 * Function for `woocommerce_after_add_to_cart_button` action-hook.
	 *
	 * @return void
	 */
	public function handle_insert_to_basket() {
		try {
			global $product;
			if ( empty( $product ) ) {
				return;
			}

			$product_json = json_encode( $this->get_product_object( $product ) );
			$script       = "
				$('body').on( 'mousedown', '.single_add_to_cart_button', function() {
					var quantity = $( 'input.qty' ).val() ? $( 'input.qty' ).val() : 1;
					etCommerce.sendEvent('insertToBasket', " . $product_json . ', quantity);
				});
			';
			wc_enqueue_js( $script );
		} catch ( \Exception $e ) {
			; // phpcs:ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
		}
	}

	/**
	 * Processes the [etracker_send_wc_order] shortcode.
	 *
	 * @param array $args The arguments passed to the shortcode, e.g., 'order_id_parameter_name'.
	 *
	 * The [etracker_send_wc_order] shortcode can be used to send etracker order events from custom order confirmation
	 * pages.
	 *
	 * @return void
	 */
	public function handle_order_shortcode( $args ) {
		$param_name = 'order_id';
		if ( isset( $args['order_id_parameter_name'] ) ) {
			$param_name = $args['order_id_parameter_name'];
		}

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$order_id = $_GET[ $param_name ] ?? '';

		if ( is_numeric( $order_id ) ) {
			$this->handle_order( $order_id );
		}
	}

	/**
	 * Function for `woocommerce_thankyou` action-hook.
	 *
	 * @param string $order_id The current order id.
	 *
	 * @return void
	 */
	public function handle_order( $order_id ) {
		try {
			$order       = wc_get_order( $order_id );
			$order_items = $order->get_items();

			$products = array();
			foreach ( $order_items as $item ) {
				$product            = $item->get_product();
				$quantity           = $item->get_quantity();
				$product_obj        = $this->get_product_object( $product );
				$quantified_product = array(
					'product'  => $product_obj,
					'quantity' => $quantity,
				);
				$products[]         = $quantified_product;
			}

			$basket = array(
				'id'       => $order->get_order_number(),
				'products' => $products,
			);

			$order = array(
				'orderNumber' => $order->get_order_number(),
				'status'      => 'sale',
				'orderPrice'  => $order->get_total(),
				'currency'    => get_woocommerce_currency(),
				'basket'      => $basket,
			);

			$order_json = json_encode( $order );
			$script     = "
				_etrackerOnReady.push(function(){ etCommerce.sendEvent('order', " . $order_json . ' ); });
			';
			wc_enqueue_js( $script );
		} catch ( \Exception $e ) {
			; // phpcs:ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
		}
	}

	/**
	 * Function for `woocommerce_loop_add_to_cart_link` filter-hook.
	 *
	 * @param string     $link    The link code.
	 * @param WC_Product $product The current product.
	 * @param array      $args    Other arguments.
	 *
	 * @return string $link
	 */
	public function filter_wc_loop_add_to_cart_link( $link, $product, $args ) {
		try {
			if ( $product->supports( 'ajax_add_to_cart' ) ) {
				$search_string = 'data-product_id=';

				// Insert custom product data as data tags.
				$replace_string = sprintf(
					'data-product_pid="%s" data-product_name="%s" data-product_price="%s" data-product_currency="%s" data-product_category="%s" %s',
					esc_attr( $this->get_sku_or_id( $product ) ),
					esc_attr( $product->get_name() ), // Product name.
					esc_attr( wc_get_price_to_display( $product ) ), // Displayed price.
					esc_attr( get_woocommerce_currency() ), // Currency.
					esc_attr( json_encode( $this->get_categories( $product ) ) ),
					$search_string
				);

				$link = str_replace( $search_string, $replace_string, $link );
			}
		} catch ( \Exception $e ) {
			; // phpcs:ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
		}
		return $link;
	}

	/**
	 * Function for `woocommerce_cart_item_remove_link` filter-hook.
	 *
	 * @param string $link          The link code.
	 * @param string $cart_item_key ID of the item in the cart.
	 *
	 * @return string $link
	 */
	public function filter_wc_cart_item_remove_link( $link, $cart_item_key ) {
		try {
			$cart_item  = WC()->cart->get_cart()[ $cart_item_key ];
			$product_id = $cart_item['product_id'];
			$quantity   = $cart_item['quantity'];
			$product    = wc_get_product( $product_id );

			$search_string = 'data-product_id=';

			// Insert custom product data as data tags.
			$replace_string = sprintf(
				'data-product_quantity="%s" data-product_pid="%s" data-product_name="%s" data-product_price="%s" data-product_currency="%s" data-product_category="%s" %s',
				esc_attr( $quantity ),
				esc_attr( $this->get_sku_or_id( $product ) ),
				esc_attr( $product->get_name() ), // Product name.
				esc_attr( wc_get_price_to_display( $product ) ), // Displayed price.
				esc_attr( get_woocommerce_currency() ), // Currency.
				esc_attr( json_encode( $this->get_categories( $product ) ) ),
				$search_string
			);

			$link = str_replace( $search_string, $replace_string, $link );
		} catch ( \Exception $e ) {
			; // phpcs:ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
		}
		return $link;
	}

	/**
	 * Function for `wp_footer` action-hook.
	 */
	public function js_event_loop_insert_to_basket() {
		try {
			$script = "
				function etrackerGetProductFromData(element) {
					return {
						id: element.data('product_pid'),
						name: element.data('product_name'),
						price: element.data('product_price'),
						currency: element.data('product_currency'),
						category: element.data('product_category')
					};
				};

				$('body').on( 'click', '.add_to_cart_button.ajax_add_to_cart', function() {
					var product = etrackerGetProductFromData($(this));
					etCommerce.sendEvent('insertToBasket', product, 1);
				});
			";
			wc_enqueue_js( $script );
		} catch ( \Exception $e ) {
			; // phpcs:ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
		}
	}

	/**
	 * Function for `woocommerce_after_cart` and `woocommerce_after_mini_cart` action-hook.
	 */
	public function js_event_remove_from_basket() {
		try {
			$script = "
				$('body').on( 'click', '.product-remove .remove', function() {
					var product = etrackerGetProductFromData($(this));
					etCommerce.sendEvent('removeFromBasket', product, $(this).data('product_quantity'));
				});
			";
			wc_enqueue_js( $script );
		} catch ( \Exception $e ) {
			; // phpcs:ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
		}
	}

	/**
	 * Function for `woocommerce_after_cart` and `woocommerce_after_mini_cart` action-hook.
	 */
	public function js_event_update_quantity() {
		try {
			$script = "
				$('body').on( 'click', '[name=\"update_cart\"]', function() {
					$('.product-remove .remove').each(function(index, item) {
						var old_qty = $(item).data('product_quantity');
						var new_qty = $('input.qty')[index].value;
						if(old_qty != new_qty) {
							var product = etrackerGetProductFromData($(item));
							var diff = new_qty - old_qty;
							if(diff > 0) {
								etCommerce.sendEvent('insertToBasket', product, diff);
							} else {
								etCommerce.sendEvent('removeFromBasket', product, -diff);
							}
						}
					});
				});
			";
			wc_enqueue_js( $script );
		} catch ( \Exception $e ) {
			; // phpcs:ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
		}
	}
}
