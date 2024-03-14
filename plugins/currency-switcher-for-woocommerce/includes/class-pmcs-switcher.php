<?php

class PMCS_Switcher {
	protected $current_currency       = null;
	protected $currencies             = array();
	protected $woocommerce_currency   = null;
	protected $woocommerce_currencies = array();
	protected $price_args             = array();
	protected $rate                   = null;
	protected $store_type             = 'cookie';
	protected $change_currency        = true;
	protected $doing_wc_ajax          = false;
	protected $use_for_pages        = array(
		'cart'         => 'yes',
		'checkout'     => 'yes',
		'account_page' => 'no',
	);
	protected $hooks_added            = false;
	protected $geoip_rulers           = null;
	protected $currency_by_ip         = false;
	protected $add_switcher_to_menu   = false;
	protected $auto_convert           = false;

	/**
	 * Construct method.
	 *
	 * @see WC_Product_Variable::get_price_html
	 * @see wc_price
	 */
	public function __construct() {

		if ( isset( $_GET['wc-ajax'] ) && ! empty( $_GET['wc-ajax'] ) ) {
			$this->doing_wc_ajax = true;
		}

		$this->woocommerce_currencies = get_woocommerce_currencies();
		$this->woocommerce_currency   = get_woocommerce_currency();
		$this->currency_by_ip         = get_option( 'pmcs_currency_by_ip', 'yes' ) == 'yes' ? true : false;
		$this->store_type             = get_option( 'pmcs_store_data_type' );
		$this->add_swicther_to_menu   = get_option( 'pmcs_add_to_menu', 'yes' ) == 'yes' ? true : false;
		$this->auto_convert           = get_option( 'pmcs_currency_auto_convert', 'yes' ) == 'yes' ? true : false;
		$default = array(
			'currency_code'      => '',
			'sign_position'      => get_option( 'woocommerce_currency_pos' ),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'decimal_separator'  => wc_get_price_decimal_separator(),
			'num_decimals'       => wc_get_price_decimals(),
			'rate'               => '',
			'display_text'       => '',
		);

		// Load currencies settings.
		$currencies = get_option( 'pmcs_currencies' );
		if ( ! is_array( $currencies ) ) {
			$currencies = array();
		}

		// var_dump( $this->woocommerce_currencies );

		foreach ( $currencies as $currency ) {
			$currency = wp_parse_args( $currency, $default );
			if ( $currency['currency_code'] ) { // skip empty row.
				if ( ! $currency['display_text'] ) {
					$currency['display_text'] = isset( $this->woocommerce_currencies[  $currency['currency_code'] ] ) ? $this->woocommerce_currencies[  $currency['currency_code'] ] : '';
				}
				$this->currencies[ $currency['currency_code'] ] = $currency;
			}
		}

		ob_start();
		if ( 'session' == $this->store_type ) {
			session_start();
		}

		$this->geoip_rulers = get_option( 'pmcs_geoip' );

		$this->get_current_currency( true );

		$settings = array();

		if ( isset( $this->currencies[ $this->current_currency ] ) ) {
			$settings = $this->currencies[ $this->current_currency ];
			$currency_pos = $settings['sign_position'];
		} else {
			$currency_pos = get_option( 'woocommerce_currency_pos' );
		}

		$currency_format       = '%1$s%2$s';
		switch ( $currency_pos ) {
			case 'left':
				$currency_format = '%1$s%2$s';
				break;
			case 'right':
				$currency_format = '%2$s%1$s';
				break;
			case 'left_space':
				$currency_format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space':
				$currency_format = '%2$s&nbsp;%1$s';
				break;
		}

		$settings = wp_parse_args( $settings, $default );

		$this->price_args = array(
			'decimal_separator'  => $settings['decimal_separator'],
			'thousand_separator' => $settings['thousand_separator'],
			'decimals'           => intval( $settings['num_decimals'] ),
			'price_format'       => $currency_format,
		);

		$this->rate = (float) $settings['rate'];

		if ( $this->current_currency == $this->woocommerce_currency ) {
			$this->rate = 1; // Convert to itself.
		}

		$this->use_for_pages['cart'] = get_option( 'pmcs_cart_default_currency', 'yes' );
		$this->use_for_pages['checkout'] = get_option( 'pmcs_checkout_default_currency', 'yes' );

		if ( ! $this->doing_wc_ajax ) {
			$this->set_change( ! is_admin() );
		}

		add_action( 'wp', array( $this, 'init' ) );
		if ( ! is_admin() ) {
			add_action( 'wp_footer', array( $this, 'footer_scripts' ), 95 );
		}

	}

	public function get_rate( $currency_code ) {
		$rate = 1;
		if ( isset( $this->currencies[ $currency_code ] ) ) {
			$settings = $this->currencies[ $currency_code ];
			$rate = $settings['rate'];
		} else {
			$rate = pmcs()->exchange_rates->covert( $this->woocommerce_currency, $currency_code );
		}
		return $rate;
	}

	public function is_doing_wc_ajax( $status ) {
		return $this->doing_wc_ajax;
	}

	public function doing_wc_ajax( $status ) {
		$this->doing_wc_ajax = $status;
	}

	/**
	 * Get all settings currcenies.
	 *
	 * @return array
	 */
	public function get_currencies() {
		return $this->currencies;
	}

	/**
	 * Get woocommerce currency.
	 *
	 * @return string
	 */
	public function get_woocommerce_currency() {
		return $this->woocommerce_currency;
	}

	/**
	 * Get woocommerce currency.
	 *
	 * @return string
	 */
	public function get_woocommerce_currencies() {
		return $this->woocommerce_currencies;
	}

	/**
	 * Force refresh ajax cart.
	 *
	 * @return void
	 */
	public function footer_scripts() {
		?>
		<script type='text/javascript'>
			jQuery( document ).ready( function( $ ){
				$( document.body ).trigger( 'wc_fragments_refreshed' );
			} );
		</script>
		<?php
	}

	/**
	 * Inital
	 *
	 * @return void
	 */
	public function init() {
		$this->get_current_currency();
		$this->change_currency_hooks();

		// Add currency swicther to menu.
		add_filter( 'wp_nav_menu_items', array( $this, 'add_swicther_to_menu' ), 35, 2 );

		// Change currency by raw price.
		add_filter( 'woocommerce_cart_fragment_name', array( $this, 'woocommerce_cart_fragment_name' ) );
		add_filter( 'woocommerce_ajax_get_endpoint', array( $this, 'woocommerce_ajax_get_endpoint' ), 95, 2 );

		/**
		 * Before order created
		 */
		add_action( 'woocommerce_before_checkout_process', array( $this, 'woocommerce_before_checkout_process' ) );

		/**
		 * Before send to payment gateways
		 */
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'woocommerce_checkout_order_processed' ), 15, 3 );

		/**
		 * Do action for pay action.
		 */
		add_action( 'woocommerce_before_pay_action', array( $this, 'woocommerce_before_pay_action' ), 15 );

	}

	/**
	 * Add more currency code to ajax endpoint
	 *
	 * @param string $url
	 * @return string
	 */
	public function woocommerce_ajax_get_endpoint( $url ) {
		$r = '?';
		if ( strpos( $url, '?' ) !== false ) {
			$r = '&';
		}
		$url .= $r . 'currency=' . ( $this->will_change() ? $this->current_currency : $this->woocommerce_currency );
		return $url;
	}

	/**
	 * Set change state
	 *
	 * @param bool $status
	 * @return void
	 */
	public function set_change( $status ) {
		$this->change_currency = $status;
		if ( ! $status ) {
			$this->remove_currency_hooks();
		} else {
			$this->change_currency_hooks();
		}
	}

	/**
	 * Check if will convert to other currency.
	 *
	 * @return string
	 */
	public function will_change() {

		// if ( $this->current_currency == $this->woocommerce_currency ) {
		// return false;
		// }
		if ( $this->doing_wc_ajax ) {
			return $this->change_currency;
		}

		if ( is_checkout() ) {
			if ( 'yes' == $this->use_for_pages['checkout'] ) {
				return false;
			}
		} elseif ( is_cart() ) {
			if ( 'yes' == $this->use_for_pages['cart'] ) {
				return false;
			}
		} elseif ( is_account_page() ) {
			return false;
		}

		return $this->change_currency;
	}

	/**
	 * Set current currency
	 *
	 * @param string $current_currency Currency code.
	 * @return void
	 */
	public function set_currency( $current_currency ) {
		$this->current_currency = $current_currency;
	}

	/**
	 * Set rate for current currency.
	 *
	 * @param float $rate
	 * @return void
	 */
	public function set_rate( $rate ) {
		$this->rate = (float) $rate;
	}

	/**
	 * Get currency by geoip.
	 *
	 * @return string Current currency.
	 */
	public function get_currency_by_geoip() {
		if ( ! $this->currency_by_ip ) {
			return $this->woocommerce_currency;
		}

		if ( is_array( $this->geoip_rulers ) ) {
			$location = WC_Geolocation::geolocate_ip();
			if ( $location['country'] ) {
				foreach ( $this->geoip_rulers as $currency_code => $countries ) {
					if ( is_array( $countries ) && ! empty( $countries ) ) {
						if ( in_array( $location['country'], $countries ) ) { // phpcs:ignore
							return $currency_code;
						}
					}
				}
			}
		}

		return $this->woocommerce_currency;
	}

	/**
	 * Get current currency.
	 *
	 * @return string Currency code.
	 */
	public function get_current_currency( $force = false ) {
		if ( empty( $this->currencies ) ) {
			return $this->woocommerce_currency;
		}

		if ( ! $force ) {
			if ( ! is_null( $this->current_currency ) ) {
				return $this->current_currency;
			}
		}

		$currency_by_country = $this->get_currency_by_geoip();

		$save = true;
		if ( isset( $_GET['currency'] ) ) {
			$this->current_currency = sanitize_text_field( wp_unslash( $_GET['currency'] ) );
			$save = true;
		} else {
			switch ( $this->store_type ) {
				case 'session':
					if ( isset( $_SESSION['currency'] ) ) {
						$this->current_currency = $_SESSION['currency'];
						$save = false;
					} else {
						$this->current_currency = $currency_by_country;
					}
					break;
				default:
					if ( isset( $_COOKIE['currency'] ) ) {
						$save = false;
						$this->current_currency = sanitize_text_field( wp_unslash( $_COOKIE['currency'] ) );
					} else {
						$this->current_currency = $currency_by_country;
					}
			}
		}

		if ( empty( $this->current_currency ) || ! isset( $this->woocommerce_currencies[ $this->current_currency ] ) ) {
			$this->current_currency = $currency_by_country;
		}

		if ( ! isset( $this->currencies[ $this->current_currency ] ) ) {
			$this->current_currency = $this->woocommerce_currency;
		}

		if ( $this->doing_wc_ajax ) {
			$save = false;
		}

		if ( $save ) {
			switch ( $this->store_type ) {
				case 'session':
					$_SESSION['currency'] = $this->current_currency;
					break;
				default:
					$expire = time() + 30 * DAY_IN_SECONDS;
					pmcs()->setcookie( 'currency', $this->current_currency, $expire );
			}
		}

		return $this->current_currency;

	}

	/**
	 * Convert product price.
	 *
	 * @param float      $value
	 * @param WC_Product $product
	 * @return float
	 */
	public function product_get_price( $value, $product ) {
		if ( $product instanceof WC_Product ) {
			if ( $product->is_on_sale() ) {
				return $this->product_get_sale_price( $value, $product );
			} else {
				return $this->product_get_sale_price( $value, $product );
			}
		}

		return $this->product_get_regular_price( $value, $product );
	}

	/**
	 * Convert product sale price.
	 *
	 * @param float      $value
	 * @param WC_Product $product
	 * @return float
	 */
	public function product_get_sale_price( $value, $product ) {
		if ( $this->will_change() ) {
			$meta_key = '_sale_price_' . $this->current_currency;
			$meta_value = $product->get_meta( $meta_key );
			if ( strlen( $meta_value ) > 0 ) {
				return (float) $meta_value;
			} elseif ( ! $this->auto_convert ) {
				return ''; // This product will not purchase able and remove from cart.
			}
		}
		return $this->convert_rate( $value );
	}

	/**
	 * Convert product regular price.
	 *
	 * @param float      $value
	 * @param WC_Product $product
	 * @return float
	 */
	public function product_get_regular_price( $value, $product ) {
		if ( $this->will_change() ) {
			$meta_key = '_regular_price_' . $this->current_currency;
			$meta_value = $product->get_meta( $meta_key );
			if ( strlen( $meta_value ) > 0 ) {
				return (float) $meta_value;
			} elseif ( ! $this->auto_convert ) {
				return ''; // This product will not purchase able and remove from cart.
			}
		}
		return $this->convert_rate( $value );
	}

	/**
	 * Convert coupon amout
	 *
	 * @param float     $value
	 * @param WP_Coupon $coupon
	 * @return float
	 */
	public function coupon_get_amount( $value, $coupon ) {
		if ( ! $this->will_change() ) {
			return $value;
		}

		$type = $coupon->get_discount_type();
		$meta_key = '_amount_' . $this->current_currency;
		$meta_value = $coupon->get_meta( $meta_key );
		if ( strlen( $meta_value ) > 0 ) {
			$meta_value = (float) $meta_value;
			return $meta_value;
		} elseif ( ! $this->auto_convert ) {
			return 0;
		}

		if ( 'percent' == $type ) {
			return $value;
		}
		return $this->convert_rate( $value );
	}

	/**
	 * Hash deep.
	 *
	 * @param string|array $hash
	 * @return boolean
	 */
	protected function hash_deep( $hash ) {
		if ( is_string( $hash ) ) {
			return $hash . $this->current_currency;
		} elseif ( is_array( $hash ) ) {
			foreach ( $hash as $k => $v ) {
				$hash[ $k ] = $this->hash_deep( $v );
			}
		}
		return $hash;
	}

	/**
	 * Filter price hash for product variable.
	 *
	 * @param string $hash
	 * @return string
	 */
	public function woocommerce_get_variation_prices_hash( $hash ) {
		if ( $this->will_change() ) {
			$hash = $this->hash_deep( $hash );
		}

		return $hash;
	}

	public function add_order_item_hooks() {
		add_filter( 'woocommerce_order_item_get_subtotal', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_item_get_subtotal_tax', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_item_get_total', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_item_get_total_tax', array( $this, 'convert_rate' ), 95 );
	}

	public function remove_order_item_hooks() {
		remove_filter( 'woocommerce_order_item_get_subtotal', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_item_get_subtotal_tax', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_item_get_total', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_item_get_total_tax', array( $this, 'convert_rate' ), 95 );
	}

	/**
	 * Addd hooks to change prices deppend on each currency.
	 *
	 * @return void
	 */
	public function change_currency_hooks() {
		if ( ! $this->hooks_added ) {
			$this->hooks_added = true;
		} else {
			return; // The actions added, just skip the code bellow.
		}

		// Convert prices for simple product.
		add_filter( 'woocommerce_product_get_price', array( $this, 'product_get_price' ), 95, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'product_get_sale_price' ), 95, 2 );
		add_filter( 'woocommerce_product_get_regular_price', array( $this, 'product_get_regular_price' ), 95, 2 );

		// Convert prices for variable product.
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'product_get_price' ), 95, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'product_get_sale_price' ), 95, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'product_get_regular_price' ), 95, 2 );

		add_filter( 'woocommerce_variation_prices_price', array( $this, 'product_get_price' ), 95, 2 );
		add_filter( 'woocommerce_variation_prices_regular_price', array( $this, 'product_get_sale_price' ), 95, 2 );
		add_filter( 'woocommerce_variation_prices_sale_price', array( $this, 'product_get_regular_price' ), 95, 2 );

		add_filter( 'woocommerce_get_variation_prices_hash', array( $this, 'woocommerce_get_variation_prices_hash' ), 95 );

		// Convert prices for shipping.
		add_filter( 'woocommerce_shipping_rate_cost', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_shipping_rate_cost', array( $this, 'convert_rate' ), 95 );

		// Convert prices for order item.
		$this->add_order_item_hooks();

		// Convert prices for order.
		add_filter( 'woocommerce_order_get_cart_tax', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_shipping_total', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_shipping_tax', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_total', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_total_tax', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_total_discount', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_subtotal', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_tax_totals', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_discount', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_discount', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_order_get_discount_tax', array( $this, 'convert_rate' ), 95 );

		// Coupon.
		add_filter( 'woocommerce_coupon_get_amount', array( $this, 'coupon_get_amount' ), 95, 2 );

		// Fee.
		add_filter( 'woocommerce_fee_get_amount', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_fee_get_total', array( $this, 'convert_rate' ), 95 );
		add_filter( 'woocommerce_fee_get_total_tax', array( $this, 'convert_rate' ), 95 );

		// Change global currency.
		add_filter( 'woocommerce_currency', array( $this, 'filter_woocommerce_currency' ), 95 );

		// Filter price args deepended on each currency.
		add_filter( 'wc_price_args', array( $this, 'filter_price_args' ), 95 );

	}

	/**
	 * Remove convert price hooks.
	 *
	 * @return void
	 */
	public function remove_currency_hooks() {
		$this->hooks_added = false;
		// Convert prices for product.
		remove_filter( 'woocommerce_product_get_price', array( $this, 'product_get_price' ), 95, 2 );
		remove_filter( 'woocommerce_product_get_sale_price', array( $this, 'product_get_sale_price' ), 95, 2 );
		remove_filter( 'woocommerce_product_get_regular_price', array( $this, 'product_get_regular_price' ), 95, 2 );

		// Convert prices for variable product.
		remove_filter( 'woocommerce_product_variation_get_price', array( $this, 'product_get_price' ), 95, 2 );
		remove_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'product_get_sale_price' ), 95, 2 );
		remove_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'product_get_regular_price' ), 95, 2 );

		remove_filter( 'woocommerce_variation_prices_price', array( $this, 'product_get_price' ), 95, 2 );
		remove_filter( 'woocommerce_variation_prices_regular_price', array( $this, 'product_get_sale_price' ), 95, 2 );
		remove_filter( 'woocommerce_variation_prices_sale_price', array( $this, 'product_get_regular_price' ), 95, 2 );

		remove_filter( 'woocommerce_get_variation_prices_hash', array( $this, 'woocommerce_get_variation_prices_hash' ), 95 );

		// Convert prices for shipping.
		remove_filter( 'woocommerce_shipping_rate_cost', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_shipping_rate_cost', array( $this, 'convert_rate' ), 95 );

		// Convert prices for order item.
		$this->remove_order_item_hooks();

		// Convert prices for order.
		remove_filter( 'woocommerce_order_get_cart_tax', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_shipping_total', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_shipping_tax', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_total', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_total_tax', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_total_discount', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_subtotal', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_tax_totals', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_discount', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_discount', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_order_get_discount_tax', array( $this, 'convert_rate' ), 95 );

		// Coupon.
		remove_filter( 'woocommerce_coupon_get_amount', array( $this, 'coupon_get_amount' ), 95 );

		// Fee.
		remove_filter( 'woocommerce_fee_get_amount', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_fee_get_total', array( $this, 'convert_rate' ), 95 );
		remove_filter( 'woocommerce_fee_get_total_tax', array( $this, 'convert_rate' ), 95 );

		// Change global currency.
		remove_filter( 'woocommerce_currency', array( $this, 'filter_woocommerce_currency' ), 95 );

		// Filter price args deepended on each currency.
		remove_filter( 'wc_price_args', array( $this, 'filter_price_args' ), 95 );
	}

	public function woocommerce_before_checkout_process() {
		// Do something.
	}

	/**
	 * Add order meta when order added.
	 *
	 * @param init     $order_id
	 * @param array    $posted_data
	 * @param WC_Order $order
	 * @return void
	 */
	public function woocommerce_checkout_order_processed( $order_id, $posted_data, $order ) {

		$order_data     = $order->get_data();
		$line_items     = $order->get_items();
		$shipping_items = $order->get_items( 'shipping' );
		$fee_items      = $order->get_items( 'fee' );
		$coupon_items   = $order->get_items( 'coupon' );
		$tax_items      = $order->get_items( 'tax' );

		$order->update_meta_data( '_currency_checkout', $this->current_currency );
		$order->update_meta_data( '_currency_rate', $this->rate );
		$order->update_meta_data( '_base_currency', $this->woocommerce_currency );

		// Remove currency hook because we don't need use any more.
		$this->remove_currency_hooks();

	}

	/**
	 * Add order item meta
	 *
	 * @param array $items
	 * @param array $keys
	 * @return void
	 */
	public function add_order_item_meta( $items = array(), $keys = array() ) {
		$affix_meta = '_converted';
		// Add convert tax meta.
		foreach ( $items as $id => $item ) {
			$item_id = $item->get_id();
			$item->set_object_read( false );
			$item_data = $item->get_data();

			foreach ( $keys as $meta_key => $key ) {
				$n = 0;
				if ( isset( $item_data[ $key ] ) ) {
					$n = $item_data[ $key ];
				}
				$save_key = $meta_key . $affix_meta;
				wc_add_order_item_meta( $item_id, $save_key, $this->rate * $n );

				$method_set = 'set_' . $key;
				if ( method_exists( $item, $method_set ) && is_callable( array( $item, $method_set ) ) ) {
					$item->$method_set( $n );
				}
			}
		}
	}

	public function woocommerce_before_pay_action( $order ) {
		// Do something.
	}

	/**
	 * Change cart fragment name for each currency.
	 *
	 * @param string $name
	 * @return string
	 */
	public function woocommerce_cart_fragment_name( $name ) {
		if ( ! $this->will_change() ) {
			return $name;
		}
		$name .= '_' . $this->current_currency;
		return $name;
	}

	/**
	 * Change currency
	 *
	 * @param string $currency_code
	 * @return string
	 */
	public function filter_woocommerce_currency( $currency_code ) {
		if ( ! $this->will_change() ) {
			return $currency_code;
		}
		return $this->current_currency;
	}

	/**
	 * Change price args
	 *
	 * @param array $args
	 * @return array
	 */
	public function filter_price_args( $args ) {
		if ( ! $this->will_change() ) {
			return $args;
		}
		$args['currency'] = $this->current_currency;
		foreach ( $this->price_args as $k => $v ) {
			$args[ $k ] = $v;
		}
		return $args;
	}

	/**
	 * Convert price rate
	 *
	 * @param float $value
	 * @return float
	 */
	public function convert_rate( $value ) {
		if ( ! $this->will_change() ) {
			return $value;
		}
		if ( empty( $value ) ) {
			return $value;
		}
		if ( ! is_numeric( $value ) ) {
			return $value;
		}
		return $value * $this->rate;
	}


	public function get_currencies_li( $skip_current = true, $show_flag = false, $name_type = 'name' ) {
		global $wp;
		if ( get_option( 'permalink_structure' ) != '' ) {
			$link = trailingslashit( home_url( $wp->request ) );
		} else {
			$link = $_SERVER['REQUEST_URI']; // phpcs:ignore
		}

		$ul_list = array();
		foreach ( $this->get_currencies() as $code => $currency ) {
			$skip = false;
			if ( $skip_current ) {
				if ( $code == $this->current_currency ) {
					$skip = true;
				}
			}
			if ( ! $skip ) {
				$item_link = add_query_arg( array( 'currency' => $currency['currency_code'] ), $link );

				if ( 'code' == $name_type ) {
					$text = $currency['currency_code'];
				} else {
					$text = $currency['display_text'] ? $currency['display_text'] : $this->woocommerce_currencies[ $code ];
				}
				if ( ! $text ) {
					$text = $this->woocommerce_currencies[ $code ];
				}

				if ( $show_flag ) {
					$text = pmcs()->get_flag( $currency['currency_code'] ) . $text;
				}
				$li_classes = array( 'currency-li', 'currency-' . strtolower( $code ) );
				if ( pmcs()->switcher->get_current_currency() == $code ) {
					$li_classes[] = 'current-currency';
				}
				$ul_list[ $code  ] = sprintf( '<li class="%3$s"><a href="%1$s"><span class="pmcs-item">%2$s</span></a></li>', $item_link, $text, esc_attr( join( ' ', $li_classes ) ) );
			}
		}
		return $ul_list;
	}

	/**
	 * Add switcher to menu
	 *
	 * @param array $items
	 * @param array $args
	 * @return string
	 */
	public function add_swicther_to_menu( $items, $args ) {

		if ( empty( $this->currencies ) ) {
			return $items;
		}

		if ( ! $this->add_swicther_to_menu ) {
			return $items;
		}

		$show_in_locations = get_option( 'pmcs_show_in_menu_location' );

		if ( ! is_object( $args ) || empty( $args->theme_location ) || empty( $show_in_locations ) ) {
			return $items;
		}

		if ( is_string( $show_in_locations ) ) {
			if ( $args->theme_location != $args->theme_location ) {
				return $items;
			}
		} elseif ( ! is_array( $show_in_locations ) || ! in_array( $args->theme_location, $show_in_locations ) ) { // phpcs:ignore
			return $items;
		}

		$change            = $this->will_change();
		$active_currencies = $this->get_currencies();
		$show_flag         = get_option( 'pmcs_show_flag', 'yes' ) == 'yes';
		$name_type         = get_option( 'pmcs_show_name', 'name' );

		$ul_list = array();
		if ( $change ) {
			if ( 'code' != $name_type ) {
				$top_name = isset( $this->currencies[ $this->current_currency ] ) ? $this->currencies[ $this->current_currency ]['display_text'] : $this->woocommerce_currencies[ $this->current_currency ];
			} else {
				$top_name = $this->current_currency;
			}

			if ( ! $top_name ) {
				$top_name = $this->woocommerce_currencies[ $this->current_currency ];
			}

			if ( $show_flag ) {
				$top_name = pmcs()->get_flag( $this->current_currency ) . $top_name;
			}
		} else {
			if ( 'code' != $name_type ) {
				$top_name = $this->woocommerce_currencies[ $this->woocommerce_currency ];
			} else {
				$top_name = $this->woocommerce_currency;
			}

			if ( ! $top_name ) {
				$top_name = $this->woocommerce_currencies[ $this->woocommerce_currency ];
			}

			if ( $show_flag ) {
				$top_name = pmcs()->get_flag( $this->woocommerce_currency ) . $top_name;
			}
		}

		$ul_list_html = '';
		$parent_classes = array( 'pmcs-menu-item menu-item' );
		if ( $change ) {
			$parent_classes['parent'] = 'menu-item-has-children';
			$ul_list = $this->get_currencies_li( true, $show_flag, $name_type );
			if ( ! empty( $ul_list ) ) {
				$ul_list_html = '<ul class="sub-menu">' . join( '', $ul_list ) . '</ul>';
			}
		} else {
			$parent_classes['parent'] = 'menu-item-no-children';
		}

		$items .= '<li class="' . esc_attr( join( ' ', $parent_classes ) ) . '"><a href="#"><span class="pmcs-item">' . $top_name . '</span></a>' . $ul_list_html . '</li>';

		return $items;
	}

}
