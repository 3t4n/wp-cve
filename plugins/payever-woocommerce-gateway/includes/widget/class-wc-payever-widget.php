<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Widget' ) ) {
	return;
}

/**
 * Class WC_Payever_Widget
 */
class WC_Payever_Widget {

	const LIVE_WIDGET_JS = 'https://widgets.payever.org/finance-express/widget.min.js';
	const STAGE_WIDGET_JS = 'https://widgets.staging.devpayever.com/finance-express/widget.min.js';

	/**
	 * Widget settings.
	 *
	 * @var array $widget_settings
	 */
	private $widget_settings = array();

	/**
	 *
	 */
	public function __construct() {
		$this->widget_settings = WC_Payever_Helper::instance()->get_payever_widget_settings();
		$this->render_wrapper();
	}

	/**
	 * Registers the necessary action hooks to render the HTML depending on the settings.
	 *
	 * @return bool
	 */
	public function render_wrapper() {
		$this->render_button_wrapper_registrar();

		return true;
	}

	/**
	 * Registers the hooks where to render the express widget HTML code according to the settings.
	 *
	 * @return bool
	 * @throws NotFoundException When a setting was not found.
	 */
	private function render_button_wrapper_registrar() {
		if ( $this->widget_settings[ WC_Payever_Helper::PAYEVER_ACTIVE_FE_ON_SINGLE_PAGE ] ) {
			add_action(
				$this->single_product_add_to_cart_renderer_hook(),
				function () {
					$product = wc_get_product();

					if (
						is_a( $product, WC_Product::class )
						&& ! $this->product_supports_payment( $product )
					) {

						return;
					}

					$this->button_renderer_for_product();
				},
				1
			);
		}

		$enabled_on_cart = $this->widget_settings[ WC_Payever_Helper::PAYEVER_ACTIVE_FE_ON_CART ];
		add_action(
			$this->proceed_to_checkout_button_renderer_hook(),
			function () use ( $enabled_on_cart ) {
				if ( ! is_cart() || ! $enabled_on_cart || $this->is_cart_price_total_zero() ) {
					return;
				}

				$this->button_renderer_for_cart();
			},
			50
		);

		return true;
	}

	/**
	 * Renders the express widget HTML code
	 */
	public function button_renderer_for_product() {
		$this->render_widget_code_for_single_page();
	}

	/**
	 * Converts purchase unit to the express widget HTML code
	 *
	 * @param WC_Payever_Widget_Purchase_Unit $purchase_unit
	 * @param mixed $is_variable_product
	 */
	private function render_widget_code( WC_Payever_Widget_Purchase_Unit $purchase_unit, $is_variable_product = null ) {
		?>
		<div class="payever-widget-wrapper">
			<div class="payever-widget-finexp"<?php echo sanitize_text_field( $purchase_unit->to_html_params( $is_variable_product ) ); ?>></div>
			<script>
				var script = document.createElement('script');
				script.src = '<?php echo esc_url_raw( $this->get_widget_js_url() ); ?>';

				<?php if ( $is_variable_product ) : ?>
					<?php
						$product_name = 'All products';
						$purchase_cart = $purchase_unit->cart();
					if ( count( $purchase_cart ) ) {
						$purchase_item = array_shift( $purchase_cart );
						$product_name = $purchase_item['name'];
					}
					?>
					jQuery('body').on('found_variation', function( event, variation ) {
						var price = parseFloat(variation.display_price);
						var reference = 'prod_' + variation.variation_id;

						PayeverPaymentWidgetLoader.init(
							'.payever-widget-finexp',
							null,
							{
								amount: price,
								reference: reference,
								cart: [{
									name: '<?php esc_attr_e( $product_name ); ?>',
									description: variation.variation_description,
									identifier: '' + variation.variation_id,
									amount: price,
									price: price,
									quantity: 1,
									thumbnail: variation.image.url,
									unit: 'EACH'
								}]
							}
						);
					});
				<?php endif; ?>
				<?php if ( ! $is_variable_product ) : ?>
					script.onload = function () {
						PayeverPaymentWidgetLoader.init(
							".payever-widget-finexp"
						);
					};
				<?php endif; ?>


				document.head.appendChild(script);

			</script>
		</div>
		<?php
	}

	/**
	 * Renders the HTML for the payever express widget for cart page
	 */
	public function button_renderer_for_cart() {
		if ( ! is_cart() ) {
			return;
		}

		$cart_hash = WC()->cart->get_cart_hash();
		$reference = 'cart_' . $cart_hash;

		$widget_cart = array();
		$cart_items  = WC()->cart->get_cart();
		foreach ( $cart_items as $cart_item ) {
			$product      = $cart_item['data'];
			$product_id   = $cart_item['variation_id'] ?: $cart_item['product_id'];
			$quantity     = $cart_item['quantity'];
			$thumb        = wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' );
			$thumbnailUrl = $thumb ? array_shift( $thumb ) : wc_placeholder_img_src( 'thumbnail' );
			$price        = ( is_a( $product, WC_Product::class ) ) ? wc_get_price_including_tax( $product ) : 0;
			$price_amount = ( is_a( $product, WC_Product::class ) ) ? wc_get_price_including_tax( $product, array( 'qty' => $quantity ) ) : 0;

			$widget_cart[] = new WC_Payever_Widget_Cart(
				strval( $product->get_name() ),
				strval( $product->get_short_description() ),
				strval( $product_id ),
				floatval( $price ),
				floatval( $price_amount ),
				intval( $quantity ),
				strval( $thumbnailUrl )
			);
		}

		$shipping_option = $this->get_widget_shipping_option();

		$amount = (float) WC()->cart->get_total( 'raw' );
		if ( $shipping_option ) {
			$amount -= floatval( $shipping_option->price() );
		}

		$purchase_unit = new WC_Payever_Widget_Purchase_Unit(
			$amount,
			$reference,
			$widget_cart,
			$shipping_option
		);

		$this->render_widget_code( $purchase_unit );
	}

	/**
	 * Prepares payment unit for single page
	 *
	 * @return WC_Payever_Widget_Purchase_Unit
	 */
	private function render_widget_code_for_single_page() {
		$product = wc_get_product();
		$amount  = ( is_a( $product, WC_Product::class ) ) ? wc_get_price_including_tax( $product ) : 0;

		$product_id = version_compare( WOOCOMMERCE_VERSION, '3.5', '<' ) ? $product['id'] : $product->get_id();
		$reference  = 'prod_' . $product_id;

		$thumb        = wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' );
		$thumbnailUrl = $thumb ? array_shift( $thumb ) : wc_placeholder_img_src( 'thumbnail' );

		$widget_cart = array();
		$widget_cart[] = new WC_Payever_Widget_Cart(
			strval( $product->get_name() ),
			strval( $product->get_short_description() ),
			strval( $product_id ),
			floatval( $amount ),
			floatval( $amount ),
			1,
			strval( $thumbnailUrl )
		);

		$purchase_unit = new WC_Payever_Widget_Purchase_Unit(
			$amount,
			$reference,
			$widget_cart,
			$this->get_widget_shipping_option()
		);

		$is_variable_product = $product->is_type( 'variable' );

		$this->render_widget_code( $purchase_unit, $is_variable_product );
	}

	/**
	 * Checks if payever express widget can be rendered for the given product.
	 *
	 * @param WC_Product $product The product.
	 *
	 * @return bool
	 */
	private function product_supports_payment( WC_Product $product ) {
		$in_stock = $product->is_in_stock();

		if ( $product->is_type( 'variable' ) ) {
			$variations = $product->get_available_variations( 'objects' );
			$in_stock   = $this->has_in_stock_variation( $variations );
		}

		return apply_filters(
			'woocommerce_payever_payments_product_supports_payment_request_button',
			! $product->is_type( array( 'external', 'grouped' ) ) && $in_stock,
			$product
		);
	}

	/**
	 * Checks if variations contain any in stock variation.
	 *
	 * @param WC_Product_Variation[] $variations The list of variations.
	 *
	 * @return bool True if any in stock variation, false otherwise.
	 */
	private function has_in_stock_variation( $variations ) {
		foreach ( $variations as $variation ) {
			if ( $variation->is_in_stock() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the action name that payever express widget will use for rendering on the single product page after add to cart button.
	 *
	 * @return string
	 */
	private function single_product_add_to_cart_renderer_hook() {
		return (string) apply_filters( 'woocommerce_payever_payments_single_product_add_to_cart_renderer_hook', 'woocommerce_after_add_to_cart_button' );
	}

	/**
	 * Returns action name that payever express widget will use for rendering on the shopping cart page.
	 *
	 * @return string
	 */
	private function proceed_to_checkout_button_renderer_hook() {
		return (string) apply_filters(
			'woocommerce_payever_payments_proceed_to_checkout_button_renderer_hook',
			'woocommerce_proceed_to_checkout'
		);
	}

	/**
	 * Checks if cart total is zero
	 *
	 * @return bool
	 */
	private function is_cart_price_total_zero() {
		return WC()->cart && WC()->cart->get_total( 'numeric' ) <= 0.001;
	}

	/**
	 * Returns widget js file url
	 *
	 * @return string
	 */
	private function get_widget_js_url() {
		return ( $this->widget_settings[ WC_Payever_Helper::PAYEVER_ENVIRONMENT ] )
			? self::STAGE_WIDGET_JS
			: self::LIVE_WIDGET_JS;
	}

	/**
	 * Returns shipping method by code
	 *
	 * @param $chosenShippingMethod
	 *
	 * @return mixed|null
	 */
	private function get_shipping_method_by_code( $chosenShippingMethod ) {
		$shippingMethods = WC_Payever_Helper::instance()->get_available_shipping_methods();
		if ( $chosenShippingMethod ) {
			foreach ( $shippingMethods as $shippingMethod ) {
				$shippingMethodCode = $shippingMethod->id . ':' . $shippingMethod->get_instance_id();
				if ( $chosenShippingMethod === $shippingMethodCode ) {
					return $shippingMethod;
				}
			}
		}

		return null;
	}

	/**
	 * Builds WC_Payever_Widget_Shipping_Option object
	 *
	 * @return WC_Payever_Widget_Shipping_Option|null
	 */
	private function get_widget_shipping_option() {
		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		$chosen_shipping_method = $chosen_shipping_methods
			? array_shift( $chosen_shipping_methods )
			: $this->widget_settings[ WC_Payever_Helper::PAYEVER_FE_DEFAULT_SHIPPING_METHOD ];

		$shipping_method = $this->get_shipping_method_by_code( $chosen_shipping_method );
		if ( ! $shipping_method ) {
			return null;
		}

		return new WC_Payever_Widget_Shipping_Option(
			strval( $shipping_method->get_method_title() ),
			floatval( $shipping_method->cost ),
			strval( $chosen_shipping_method )
		);
	}
}
