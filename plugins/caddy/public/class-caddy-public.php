<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Caddy
 * @subpackage Caddy/public
 * @author     Tribe Interactive <success@madebytribe.co>
 */
class Caddy_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( is_checkout() ) {
			$caddy                         = new Caddy();
			$cc_premium_license_activation = $caddy->cc_check_premium_license_activation();
			if ( $cc_premium_license_activation ) {
				$cc_enable_on_checkout_page = get_option( 'cc_enable_on_checkout_page' );
				if ( 'enabled' !== $cc_enable_on_checkout_page ) {
					return;
				}
			} else {
				return;
			}
		}
		wp_enqueue_style( 'cc-slick', CADDY_DIR_URL . '/public/css/caddy-slick.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'caddy-public', CADDY_DIR_URL . '/public/css/caddy-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'cc-icons', CADDY_DIR_URL . '/public/css/caddy-icons.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( isset( $_GET['elementor-preview'] ) ) {
			// Return if current screen is elementor editor
			return;
		}
		if ( is_checkout() ) {
			$caddy                         = new Caddy();
			$cc_premium_license_activation = $caddy->cc_check_premium_license_activation();
			if ( $cc_premium_license_activation ) {
				$cc_enable_on_checkout_page = get_option( 'cc_enable_on_checkout_page' );
				if ( 'enabled' !== $cc_enable_on_checkout_page ) {
					return;
				}
			} else {
				return;
			}
		}

		wp_enqueue_script( 'cc-tabby-js', CADDY_DIR_URL . '/public/js/tabby.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'cc-tabby-polyfills-js', CADDY_DIR_URL . '/public/js/tabby.polyfills.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'cc-slick-js', CADDY_DIR_URL . '/public/js/slick.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'caddy-public', CADDY_DIR_URL . '/public/js/caddy-public.js', array( 'jquery' ), $this->version, true );

		// make the ajaxurl var available to the above script
		$params = array(
			'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'wc_ajax_url'        => WC_AJAX::get_endpoint( '%%endpoint%%' ),
			'wc_currency_symbol' => get_woocommerce_currency_symbol(),
			'nonce'              => wp_create_nonce( 'caddy' ),
			'wc_archive_page'    => ( is_shop() || is_product_category() || is_product_tag() ) ? true : false,
			'is_mobile'          => wp_is_mobile(),
		);
		wp_localize_script( 'caddy-public', 'cc_ajax_script', $params );
	}

	/**
	 * Load the cc widget
	 */
	public function cc_load_widget() {
		if ( isset( $_GET['elementor-preview']) || isset($_GET['et_fb']) ) {
			// Return if current screen is elementor editor
			return;
		}

		if ( is_checkout() ) {
			$caddy                         = new Caddy();
			$cc_premium_license_activation = $caddy->cc_check_premium_license_activation();
			if ( $cc_premium_license_activation ) {
				$cc_enable_on_checkout_page = get_option( 'cc_enable_on_checkout_page' );
				if ( 'enabled' !== $cc_enable_on_checkout_page ) {
					return;
				}
			} else {
				return;
			}
		}

		require_once( plugin_dir_path( __FILE__ ) . 'partials/caddy-public-display.php' );
	}

	/**
	 * Ajaxify cart count.
	 *
	 * @param $fragments
	 *
	 * @return mixed
	 */
	public function cc_compass_cart_count_fragments( $fragments ) {
		ob_start();
		$cart_count   = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$cc_cart_zero = ( $cart_count == 0 ) ? ' cc-cart-zero' : '';
		?>
		<span class="cc-compass-count<?php echo $cc_cart_zero; ?>">
			<?php echo sprintf( _n( '%d', '%d', $cart_count ), $cart_count ); ?>
		</span>
		<?php
		$fragments['.cc-compass-count'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Ajaxify short-code cart count.
	 *
	 * @param $fragments
	 *
	 * @return mixed
	 */
	public function cc_shortcode_cart_count_fragments( $fragments ) {
		ob_start();
		$cart_count   = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$cc_cart_zero = ( $cart_count == 0 ) ? ' cc_cart_zero' : '';
		?>
		<span class="cc_cart_count<?php echo $cc_cart_zero; ?>"><?php echo sprintf( _n( '%d', '%d', $cart_count ), $cart_count ); ?></span>
		<?php
		$fragments['.cc_cart_count'] = ob_get_clean();

		return $fragments;
	}

	public function cc_cart_html_fragments( $fragments ) {

		ob_start();
		$this->cc_cart_screen();
		$cc_cart_screen_container = ob_get_clean();

		ob_start();
		$this->cc_sfl_screen();
		$cc_sfl_screen_container = ob_get_clean();

		$fragments['div.cc-cart-container'] = $cc_cart_screen_container;
		$fragments['div.cc-sfl-container']  = $cc_sfl_screen_container;

		return $fragments;
	}

	/**
	 * Cart screen template.
	 */
	public function cc_cart_screen() {
		include( plugin_dir_path( __FILE__ ) . 'partials/cc-cart-screen.php' );
	}

	/**
	 * Save for later template.
	 */
	public function cc_sfl_screen() {
		$caddy_license_status  = get_option( 'caddy_premium_edd_license_status' );
		$cc_enable_sfl_options = get_option( 'cc_enable_sfl_options' );

		// Return if the premium license is valid and sfl option is not enabled
		if ( isset( $caddy_license_status ) && 'valid' === $caddy_license_status
		     && 'enabled' !== $cc_enable_sfl_options ) {
			return;
		}

		include( plugin_dir_path( __FILE__ ) . 'partials/cc-sfl-screen.php' );
	}

	/**
	 * Caddy add item to the cart.
	 */
	public function caddy_add_to_cart() {

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['add-to-cart'] ) );
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );

		if ( $passed_validation && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );
			$open_cc_compass_flag = true;
			if ( 'valid' === $caddy_license_status ) {
				if ( wp_is_mobile() ) {
					$cp_mobile_notices = get_option( 'cp_mobile_notices' );
					if ( 'mob_no_notice' === $cp_mobile_notices ) {
						$open_cc_compass_flag = false;
					}
				} else {
					$cp_desktop_notices = get_option( 'cp_desktop_notices' );
					if ( 'desk_notices_only' === $cp_desktop_notices ) {
						$open_cc_compass_flag = false;
					}
				}
			}

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			$this->get_refreshed_fragments();

			$data = array(
				'cc_compass_open' => $open_cc_compass_flag,
			);
			wp_send_json( $data );

		} else {

			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			);

			wp_send_json( $data );
		}

		wp_die();
	}

	public function get_refreshed_fragments() {
		WC_AJAX::get_refreshed_fragments();
	}

	/**
	 * Remove product from the cart
	 */
	public function caddy_remove_item_from_cart() {
		//Check nonce
		if ( is_user_logged_in() ) {
			$condition = ( wp_verify_nonce( $_POST['nonce'], 'caddy' ) && isset( $_POST['cart_item_key'] ) );
		} else {
			$condition = ( isset( $_POST['cart_item_key'] ) );
		}

		if ( $condition ) {
			$cart_item_key = wc_clean( isset( $_POST['cart_item_key'] ) ? wp_unslash( $_POST['cart_item_key'] ) : '' );
			if ( ! empty( $cart_item_key ) ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
			$this->get_refreshed_fragments();
		}
		wp_die();
	}

	/**
	 * Cart item quantity update
	 */
	public function caddy_cart_item_quantity_update() {

		$key        = sanitize_text_field( $_POST['key'] );
		$product_id = sanitize_text_field( $_POST['product_id'] );
		$number     = intval( sanitize_text_field( $_POST['number'] ) );

		if ( is_user_logged_in() ) {
			$condition = ( $key && $number > 0 && wp_verify_nonce( $_POST['security'], 'caddy' ) );
		} else {
			$condition = ( $key && $number > 0 );
		}

		if ( $condition ) {

			$_product          = wc_get_product( $product_id );
			$product_data      = $_product->get_data();
			$product_name      = $product_data['name'];
			$product_stock_qty = $_product->get_stock_quantity();

			$qty_error_flag = true;
			if ( ! empty( $product_stock_qty ) ) {
				if ( $number <= $product_stock_qty || $_product->backorders_allowed() ) {
					$qty_error_flag = false;
					WC()->cart->set_quantity( $key, $number );
				}
			} else {
				$qty_error_flag = false;
				WC()->cart->set_quantity( $key, $number );
			}

			$this->get_refreshed_fragments();
			$data = array();
			if ( $qty_error_flag ) {
				$data['qty_error_msg'] = sprintf(
					esc_html__( 'Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.', 'caddy' ),
					$product_name,
					$product_stock_qty );
			}
			wp_send_json( $data );

		}
		wp_die();
	}

	/**
	 * Add cart item to wishlist
	 */
	public function caddy_save_for_later_item() {

		//Check nonce
		if ( wp_verify_nonce( $_POST['security'], 'caddy' ) &&
		     isset( $_POST['product_id'] ) ) {

			$product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
			
			// Get the 'cart_item_key' parameter from the POST request
			$raw_cart_item_key = filter_input(INPUT_POST, 'cart_item_key', FILTER_DEFAULT);
			
			// Sanitize the 'cart_item_key' parameter
			$post_item_key = sanitize_text_field($raw_cart_item_key);

			$current_user_id = get_current_user_id();

			$cc_sfl_items = get_user_meta( $current_user_id, 'cc_save_for_later_items', true );
			if ( ! is_array( $cc_sfl_items ) ) {
				$cc_sfl_items = array();
			}
			$cc_sfl_items[]   = $product_id;
			$unique_sfl_items = array_unique( $cc_sfl_items );
			update_user_meta( $current_user_id, 'cc_save_for_later_items', $unique_sfl_items );

			// Remove item from the cart
			$cart_items    = WC()->cart->get_cart();
			$cc_cart_items = array_reverse( $cart_items );

			$final_cart_items = array();
			foreach ( $cc_cart_items as $cc_cart_item_key => $cc_cart_item ) {
				$final_cart_items[] = $cc_cart_item;
			}
			foreach ( $final_cart_items as $cart_item_key => $cart_item ) {
				if ( $cart_item['key'] == $post_item_key ) {
					WC()->cart->remove_cart_item( $post_item_key );
				}
			}

			WC()->cart->calculate_totals();
			WC()->cart->maybe_set_cart_cookies();

			$this->get_refreshed_fragments();
		}
		wp_die();
	}

	/**
	 * Window screen template.
	 */
	public function cc_window_screen() {
		include( plugin_dir_path( __FILE__ ) . 'partials/cc-window-screen.php' );
	}

	/**
	 * Add item to cart from wishlist
	 */
	public function caddy_move_to_cart_item() {

		//Check nonce
		if ( wp_verify_nonce( $_POST['security'], 'caddy' ) &&
		     isset( $_POST['product_id'] ) ) {

			$product_id        = filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT );
			$product_data      = wc_get_product( $product_id );
			$product_type      = $product_data->get_type();
			$variation_id      = ( 'variation' == $product_type ) ? $product_id : 0;
			$quantity          = 1;
			$current_user_id   = get_current_user_id();
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
			$product_status    = get_post_status( $product_id );

			if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && 'publish' === $product_status ) {

				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
					wc_add_to_cart_message( array( $product_id => $quantity ), true );
				}

				// Get save for later items
				$cc_sfl_items_array = get_user_meta( $current_user_id, 'cc_save_for_later_items', true );
				if ( ! is_array( $cc_sfl_items_array ) ) {
					$cc_sfl_items_array = array();
				}
				// Search and remove from items array
				$key_pos = array_search( $product_id, $cc_sfl_items_array );
				unset( $cc_sfl_items_array[ $key_pos ] );
				$unique_sfl_items = array_unique( $cc_sfl_items_array );
				update_user_meta( $current_user_id, 'cc_save_for_later_items', $unique_sfl_items );

				$this->get_refreshed_fragments();

			} else {

				$_product          = wc_get_product( $product_id );
				$product_name      = $product_data->get_name();
				$product_stock_qty = $_product->get_stock_quantity();

				$data = array(
					'error'         => true,
					'product_url'   => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
					'error_message' => sprintf(
						__( 'Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.', 'caddy' ),
						$product_name,
						$product_stock_qty ),
				);

				wp_send_json( $data );
			}

			wp_die();
		}
	}

	/**
	 * Remove item from save for later
	 */
	public function caddy_remove_item_from_sfl() {

		//Check nonce
		if ( wp_verify_nonce( $_POST['nonce'], 'caddy' ) &&
		     isset( $_POST['product_id'] ) ) {

			$product_id         = filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT );
			$current_user_id    = get_current_user_id();
			$cc_sfl_items_array = get_user_meta( $current_user_id, 'cc_save_for_later_items', true );
			if ( ! is_array( $cc_sfl_items_array ) ) {
				$cc_sfl_items_array = array();
			}

			if ( ( $key = array_search( $product_id, $cc_sfl_items_array ) ) !== false ) {
				unset( $cc_sfl_items_array[ $key ] );
			}
			$unique_sfl_items = array_unique( $cc_sfl_items_array );
			update_user_meta( $current_user_id, 'cc_save_for_later_items', $unique_sfl_items );

			$this->get_refreshed_fragments();

		}
		wp_die();
	}

	/**
	 * Apply coupon code to the cart
	 */
	public function caddy_apply_coupon_to_cart() {

		if ( is_user_logged_in() ) {
			// Get the 'nonce' parameter from the POST request
			$raw_post_nonce = filter_input(INPUT_POST, 'nonce', FILTER_DEFAULT);
			
			// Sanitize the 'nonce' parameter
			$post_nonce = sanitize_text_field($raw_post_nonce);
			$condition  = ( wp_verify_nonce( $post_nonce, 'caddy' ) && isset( $_POST['coupon_code'] ) );
		} else {
			$condition = ( isset( $_POST['coupon_code'] ) );
		}

		if ( $condition ) {

			global $woocommerce;
			// Get the 'coupon_code' parameter from the POST request
			$raw_coupon_code = filter_input(INPUT_POST, 'coupon_code', FILTER_DEFAULT);
			
			// Sanitize the 'coupon_code' parameter
			$coupon_code = sanitize_text_field($raw_coupon_code);
			$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ) );

			$coupon_discount_amount = 0;
			$applied_coupons        = WC()->cart->get_applied_coupons();
			foreach ( $applied_coupons as $code ) {
				$coupon                 = new WC_Coupon( $code );
				$coupon_discount_amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
			}
			$cc_cart_subtotal    = WC()->cart->get_displayed_subtotal();
			$caddy_cart_subtotal = (float) ( $cc_cart_subtotal - $coupon_discount_amount );

			$this->get_refreshed_fragments();

			$data = array(
				'final_cart_subtotal' => wc_price( $caddy_cart_subtotal, array( 'currency' => get_woocommerce_currency() ) ),
			);
			wp_send_json( $data );

		} else {
			wc_add_notice( WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ), 'error' );
		}

		wc_print_notices();
		wp_die();
	}

	/**
	 * Remove coupon code to the cart
	 */
	public function caddy_remove_coupon_code() {

		if ( is_user_logged_in() ) {
			// Get the 'nonce' parameter from the POST request
			$raw_post_nonce = filter_input(INPUT_POST, 'nonce', FILTER_DEFAULT);
			
			// Sanitize the 'nonce' parameter
			$post_nonce = sanitize_text_field($raw_post_nonce);

			$condition  = ( wp_verify_nonce( $post_nonce, 'caddy' ) && isset( $_POST['coupon_code_to_remove'] ) );
		} else {
			$condition = ( isset( $_POST['coupon_code_to_remove'] ) );
		}

		if ( $condition ) {

			global $woocommerce;
			// Get the 'coupon_code_to_remove' parameter from the POST request
			$raw_coupon_code_to_remove = filter_input(INPUT_POST, 'coupon_code_to_remove', FILTER_DEFAULT);
			
			// Sanitize the 'coupon_code_to_remove' parameter
			$coupon_code_to_remove = sanitize_text_field($raw_coupon_code_to_remove);

			WC()->cart->remove_coupon( $coupon_code_to_remove );

			/* Calculate free shipping remaining amount and bar amount */
			$final_cart_subtotal     = WC()->cart->get_displayed_subtotal();
			$cc_free_shipping_amount = get_option( 'cc_free_shipping_amount' );

			$free_shipping_remaining_amount = floatval( $cc_free_shipping_amount ) - floatval( $final_cart_subtotal );
			$free_shipping_remaining_amount = ! empty( $free_shipping_remaining_amount ) ? $free_shipping_remaining_amount : 0;

			// Bar width based off % left
			$cc_bar_amount = 100;
			if ( ! empty( $cc_free_shipping_amount ) && $final_cart_subtotal <= $cc_free_shipping_amount ) {
				$cc_bar_amount = $final_cart_subtotal * 100 / $cc_free_shipping_amount;
			}

			$cc_shipping_country = get_option( 'cc_shipping_country' );

			$cc_bar_active = ( $final_cart_subtotal >= $cc_free_shipping_amount ) ? ' cc-bar-active' : '';

			if ( $final_cart_subtotal >= $cc_free_shipping_amount ) {
				ob_start();
				do_action( 'caddy_fs_congrats_text', $cc_shipping_country );
				$cc_fs_title = ob_get_clean();
			} else {
				ob_start();
				do_action( 'caddy_fs_spend_text', $free_shipping_remaining_amount, $cc_shipping_country );
				$cc_fs_title = ob_get_clean();
			}

			$cc_fs_meter = '<span class="cc-fs-meter-used' . esc_attr( $cc_bar_active ) . '" style="width:' . esc_attr( $cc_bar_amount ) . '%"></span>';

			$this->get_refreshed_fragments();
			$data = array(
				'free_shipping_title' => $cc_fs_title,
				'free_shipping_meter' => $cc_fs_meter,
				'final_cart_subtotal' => wc_price( $final_cart_subtotal, array( 'currency' => get_woocommerce_currency() ) ),
			);
			wp_send_json( $data );

		}
		wp_die();
	}

	/**
	 * Hide shipping rates when free shipping amount matched.
	 * Updated to support WooCommerce 2.6 Shipping Zones.
	 *
	 * @param array $rates Array of rates found for the package.
	 *
	 * @return array
	 */
	public function cc_shipping_when_free_is_available( $rates ) {
		$shipping_array       = array();
		$coupon_free_shipping = false;

		$applied_coupons = WC()->cart->get_applied_coupons();
		if ( ! empty( $applied_coupons ) ) {
			foreach ( $applied_coupons as $coupon_code ) {
				$coupon = new WC_Coupon( $coupon_code );
				if ( $coupon->get_free_shipping() ) {
					$coupon_free_shipping = true;
				}
			}
		}

		$cart_total              = floatval( preg_replace( '#[^\d.]#', '', WC()->cart->get_cart_contents_total() ) );
		$subcart_total           = (float) number_format( $cart_total, 2 );
		$cc_free_shipping_amount = (float) get_option( 'cc_free_shipping_amount' );

		if ( ! empty( $cc_free_shipping_amount ) ) {
			if ( $cc_free_shipping_amount <= $subcart_total ) {
				foreach ( $rates as $rate_id => $rate ) {
					if ( 'free_shipping' === $rate->method_id ) {
						$shipping_array[ $rate_id ] = $rate;
						break;
					}
				}
			} else {
				foreach ( $rates as $rate_id => $rate ) {
					if ( 'free_shipping' !== $rate->method_id ) {
						$shipping_array[ $rate_id ] = $rate;
					}
				}
			}
		}

		if ( ! empty( $shipping_array ) && ! $coupon_free_shipping ) {
			$return_array = $shipping_array;
		} else {
			$return_array = $rates;
		}

		return $return_array;
	}

	/**
	 * Saved items short-code.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function cc_saved_items_shortcode( $atts ) {

		$default = array(
			'text' => '',
			'icon' => '',
		);

		$attributes         = shortcode_atts( $default, $atts );
		$attributes['text'] = ! empty( $attributes['text'] ) ? $attributes['text'] : $default['text'];

		$saved_items_link = sprintf(
			'<a href="%1$s" class="cc_saved_items_list" aria-label="%2$s">%3$s %4$s</a>',
			'javascript:void(0);',
			esc_html__( 'Saved Items', 'caddy' ),
			( 'yes' === $attributes['icon'] ) ? '<i class="ccicon-heart-empty"></i>' : '',
			esc_html( $attributes['text'] )
		);

		return $saved_items_link;
	}

	/**
	 * Cart items short-code.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function cc_cart_items_shortcode( $atts ) {

		$default = array(
			'text' => '',
			'icon' => '',
		);

		$cart_items_link    = '';
		$attributes         = shortcode_atts( $default, $atts );
		$attributes['text'] = ! empty( $attributes['text'] ) ? $attributes['text'] : $default['text'];

		$cart_count      = '';
		$cc_cart_class   = '';
		$cart_icon_class = apply_filters( 'caddy_cart_bubble_icon', 'cp_icon_cart' );

		if ( ! is_admin() ) {
			$cart_count    = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
			$cc_cart_class = ( $cart_count == 0 ) ? 'cc_cart_count cc_cart_zero' : 'cc_cart_count';
		}

		$cart_items_link = sprintf(
			'<a href="%1$s" class="cc_cart_items_list" aria-label="%2$s">%3$s %4$s <span class="%5$s">%6$s</span></a>',
			'javascript:void(0);',
			esc_html__( 'Cart Items', 'caddy' ),
			( 'yes' === $attributes['icon'] ) ? $cart_icon_class : '',
			esc_html( $attributes['text'] ),
			$cc_cart_class,
			esc_html( $cart_count )
		);

		return $cart_items_link;
	}

	/**
	 * Display caddy cart bubble icon
	 *
	 * @param $cart_icon_class
	 *
	 * @return string
	 */
	public function cc_display_cart_bubble_icon( $cart_icon_class ) {
		$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );
		if ( 'valid' !== $caddy_license_status ) {
			$cart_icon_class = '<i class="ccicon-cart"></i>';
		}

		return $cart_icon_class;
	}

	/**
	 * Add product to save for later button.
	 */
	public function cc_add_product_to_sfl() {

		$caddy_license_status  = get_option( 'caddy_premium_edd_license_status' );
		$cc_enable_sfl_options = get_option( 'cc_enable_sfl_options' );

		if ( 'valid' !== $caddy_license_status ) {
			return;
		}
		$cc_sfl_btn_on_product = get_option( 'cc_sfl_btn_on_product' );
		$current_user_id       = get_current_user_id();
		$cc_sfl_items_array    = get_user_meta( $current_user_id, 'cc_save_for_later_items', true ); // phpcs:ignore
		$cc_sfl_items_array    = ! empty( $cc_sfl_items_array ) ? $cc_sfl_items_array : array();

		if ( is_user_logged_in() && 'enabled' === $cc_sfl_btn_on_product && 'enabled' === $cc_enable_sfl_options ) {
			global $product;
			$product_id   = $product->get_id();
			$product_type = $product->get_type();

			if ( in_array( $product_id, $cc_sfl_items_array ) ) {
				echo sprintf(
					'<a href="%1$s" class="button cc-sfl-btn remove_from_sfl_button" data-product_id="' . $product_id . '" data-product_type="' . $product_type . '"><i class="ccicon-heart-filled"></i> <span>%2$s</span></a>',
					'javascript:void(0);',
					esc_html__( 'Saved', 'caddy' )
				);
			} else {
				echo sprintf(
					'<a href="%1$s" class="button cc-sfl-btn cc_add_product_to_sfl" data-product_id="' . $product_id . '" data-product_type="' . $product_type . '"><i class="ccicon-heart-empty"></i> <span>%2$s</span></a>',
					'javascript:void(0);',
					esc_html__( 'Save for later', 'caddy' )
				);
			}
		}
	}

	/**
	 * Add product to save for later directly via button.
	 */
	public function caddy_add_product_to_sfl_action() {

		//Check nonce
		if ( wp_verify_nonce( $_POST['nonce'], 'caddy' ) &&
		     isset( $_POST['product_id'] ) ) {

			$product_id      = filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT );
			$current_user_id = get_current_user_id();

			$cc_sfl_items = get_user_meta( $current_user_id, 'cc_save_for_later_items', true );
			if ( ! is_array( $cc_sfl_items ) ) {
				$cc_sfl_items = array();
			}

			if ( ! in_array( $product_id, $cc_sfl_items ) ) {
				$cc_sfl_items[]   = $product_id;
				$unique_sfl_items = array_unique( $cc_sfl_items );
				update_user_meta( $current_user_id, 'cc_save_for_later_items', $unique_sfl_items );
			}

			$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );
			$open_cc_compass_flag = true;
			if ( 'valid' === $caddy_license_status ) {
				if ( wp_is_mobile() ) {
					$cp_mobile_notices = get_option( 'cp_mobile_notices' );
					if ( 'mob_no_notice' === $cp_mobile_notices ) {
						$open_cc_compass_flag = false;
					}
				} else {
					$cp_desktop_notices = get_option( 'cp_desktop_notices' );
					if ( 'desk_notices_only' === $cp_desktop_notices ) {
						$open_cc_compass_flag = false;
					}
				}
			}

			$this->get_refreshed_fragments();
			$data = array(
				'cc_compass_open' => $open_cc_compass_flag,
			);
			wp_send_json( $data );

		}
		wp_die();
	}

	/**
	 * Hide 'Added to Cart' message.
	 *
	 * @param $message
	 * @param $products
	 *
	 * @return string
	 */
	public function cc_empty_wc_add_to_cart_message( $message, $products ) {
		return '';
	}

	/**
	 * Caddy load Custom CSS added to custom css box into footer.
	 */
	public function cc_load_custom_css() {

		$cc_custom_css = get_option( 'cc_custom_css' );
		if ( ! empty( $cc_custom_css ) ) {
			echo '<style>' . stripslashes( $cc_custom_css ) . '</style>';
		}
	}

	/**
	 * Display compass icon
	 */
	public function cc_display_compass_icon() {
		$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );
		$cart_count           = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$cc_cart_zero         = ( $cart_count == 0 ) ? ' cc-cart-zero' : '';

		// Check if premium plugin license status is active or not
		if ( 'valid' !== $caddy_license_status && ! class_exists( 'Caddy_Premium' ) ) {
			?>
			<!-- The floating icon -->
			<div class="cc-compass">
				<span class="licon"></span>
				<div class="cc-loader" style="display: none;"></div>
				<span class="cc-compass-count<?php echo esc_attr( $cc_cart_zero ); ?> cc-hidden"><?php echo sprintf( _n( '%d', '%d', $cart_count ), $cart_count ); ?></span>
			</div>
			<?php
		}
	}

	/**
	 * Display up-sells slider in product added screen
	 *
	 * @param $product_id
	 */
	public function cc_display_product_upsells_slider( $product_id ) {

		$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );

		// Check if premium plugin is active or not
		if ( ! class_exists( 'Caddy_Premium' ) ||
		     ( isset( $caddy_license_status ) && ! empty( $caddy_license_status ) ) ) {

			// Return if the license key is valid
			if ( 'valid' === $caddy_license_status || empty( $product_id ) ) {
				return;
			}

			include( plugin_dir_path( __FILE__ ) . 'partials/cc-product-recommendations-screen.php' );
		}
	}

	/**
	 * Display free shipping congrats text
	 *
	 * @param $cc_shipping_country
	 */
	public function caddy_display_free_shipping_congrats_text( $cc_shipping_country ) {
		
		// SVG code
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M22.87,7.1A.24.24,0,0,0,23,6.86a.23.23,0,0,0-.15-.21L16,3.92a1.13,1.13,0,0,0-.9,0L13,4.94a.24.24,0,0,0-.14.23.24.24,0,0,0,.15.22l6.94,3.07a.52.52,0,0,0,.44,0Z" fill="currentColor"></path><path d="M16.61,19.85a.27.27,0,0,0,.12.22.26.26,0,0,0,.24,0l6.36-3.18a1.12,1.12,0,0,0,.62-1V8.06a.26.26,0,0,0-.13-.22.25.25,0,0,0-.24,0L16.74,11.5a.26.26,0,0,0-.13.22Z" fill="currentColor"></path><path d="M7.52,8.31a.24.24,0,0,0-.23,0,.23.23,0,0,0-.11.2c0,.56,0,2.22,0,7.41a1.11,1.11,0,0,0,.68,1l7.42,3.16a.21.21,0,0,0,.23,0,.24.24,0,0,0,.12-.21V11.78a.26.26,0,0,0-.16-.23Z" fill="currentColor"></path><path d="M15.87,10.65a.54.54,0,0,0,.43,0l2.3-1.23a.26.26,0,0,0,.13-.23.24.24,0,0,0-.15-.22L11.5,5.82a.48.48,0,0,0-.42,0L8.31,7.12a.24.24,0,0,0-.14.23.23.23,0,0,0,.15.22Z" fill="currentColor"></path><path d="M5,13.76,1.07,11.94a.72.72,0,0,0-1,.37.78.78,0,0,0,.39,1l3.9,1.8a.87.87,0,0,0,.31.07.73.73,0,0,0,.67-.43A.75.75,0,0,0,5,13.76Z" fill="currentColor"></path><path d="M5,10.31,2.68,9.23a.74.74,0,0,0-1,.36.75.75,0,0,0,.36,1L4.4,11.65a.7.7,0,0,0,.31.07A.74.74,0,0,0,5,10.31Z" fill="currentColor"></path><path d="M5,6.86,3.91,6.35a.73.73,0,0,0-1,.36.74.74,0,0,0,.36,1L4.4,8.2a.7.7,0,0,0,.31.07A.74.74,0,0,0,5,6.86Z" fill="currentColor"></path></g></svg>';
		
		echo sprintf(
			'<span class="cc-fs-icon">%1$s</span>%2$s<strong> %3$s <span class="cc-fs-country">%4$s</span> %5$s</strong>!',
			$svg,
			esc_html( __( 'Congrats, you\'ve activated', 'caddy' ) ),
			esc_html( __( 'free', 'caddy' ) ),
			esc_html( $cc_shipping_country ),
			esc_html( __( 'shipping', 'caddy' ) )
		);
	}

	/**
	 * Display free shipping spend text
	 *
	 * @param $free_shipping_remaining_amount
	 * @param $cc_shipping_country
	 */
	public function caddy_display_free_shipping_spend_text( $free_shipping_remaining_amount, $cc_shipping_country ) {
		$cc_shipping_country = get_option( 'cc_shipping_country' );
		
		// SVG code
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M22.87,7.1A.24.24,0,0,0,23,6.86a.23.23,0,0,0-.15-.21L16,3.92a1.13,1.13,0,0,0-.9,0L13,4.94a.24.24,0,0,0-.14.23.24.24,0,0,0,.15.22l6.94,3.07a.52.52,0,0,0,.44,0Z" fill="currentColor"></path><path d="M16.61,19.85a.27.27,0,0,0,.12.22.26.26,0,0,0,.24,0l6.36-3.18a1.12,1.12,0,0,0,.62-1V8.06a.26.26,0,0,0-.13-.22.25.25,0,0,0-.24,0L16.74,11.5a.26.26,0,0,0-.13.22Z" fill="currentColor"></path><path d="M7.52,8.31a.24.24,0,0,0-.23,0,.23.23,0,0,0-.11.2c0,.56,0,2.22,0,7.41a1.11,1.11,0,0,0,.68,1l7.42,3.16a.21.21,0,0,0,.23,0,.24.24,0,0,0,.12-.21V11.78a.26.26,0,0,0-.16-.23Z" fill="currentColor"></path><path d="M15.87,10.65a.54.54,0,0,0,.43,0l2.3-1.23a.26.26,0,0,0,.13-.23.24.24,0,0,0-.15-.22L11.5,5.82a.48.48,0,0,0-.42,0L8.31,7.12a.24.24,0,0,0-.14.23.23.23,0,0,0,.15.22Z" fill="currentColor"></path><path d="M5,13.76,1.07,11.94a.72.72,0,0,0-1,.37.78.78,0,0,0,.39,1l3.9,1.8a.87.87,0,0,0,.31.07.73.73,0,0,0,.67-.43A.75.75,0,0,0,5,13.76Z" fill="currentColor"></path><path d="M5,10.31,2.68,9.23a.74.74,0,0,0-1,.36.75.75,0,0,0,.36,1L4.4,11.65a.7.7,0,0,0,.31.07A.74.74,0,0,0,5,10.31Z" fill="currentColor"></path><path d="M5,6.86,3.91,6.35a.73.73,0,0,0-1,.36.74.74,0,0,0,.36,1L4.4,8.2a.7.7,0,0,0,.31.07A.74.74,0,0,0,5,6.86Z" fill="currentColor"></path></g></svg>';
	
		echo sprintf(
			'<span class="cc-fs-icon">%1$s</span>%2$s<strong> <span class="cc-fs-amount">%3$s</span> %4$s</strong> %5$s <strong>%6$s <span class="cc-fs-country">%7$s</span> %8$s</strong>',
			$svg,
			esc_html( __( 'Spend', 'caddy' ) ),
			wc_price( $free_shipping_remaining_amount, array( 'currency' => get_woocommerce_currency() ) ),
			esc_html( __( 'more', 'caddy' ) ),
			esc_html( __( 'to get', 'caddy' ) ),
			esc_html( __( 'free', 'caddy' ) ),
			esc_html( $cc_shipping_country ),
			esc_html( __( 'shipping', 'caddy' ) )
		);
	}

	/**
	 * Free shipping bar html
	 */
	public function cc_free_shipping_bar_html() {

		$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );

		// Check if premium plugin is active or not
		if ( ! class_exists( 'Caddy_Premium' ) ||
		     ( isset( $caddy_license_status ) && 'valid' !== $caddy_license_status ) ) {
			
			$calculate_with_tax = 'enabled' === get_option('cc_free_shipping_tax', 'disabled');
			$final_cart_subtotal = $calculate_with_tax ? WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() : WC()->cart->get_displayed_subtotal();

			$cc_free_shipping_amount = get_option( 'cc_free_shipping_amount' );

			$free_shipping_remaining_amount = floatval( $cc_free_shipping_amount ) - floatval( $final_cart_subtotal );
			$free_shipping_remaining_amount = ! empty( $free_shipping_remaining_amount ) ? $free_shipping_remaining_amount : 0;

			// Bar width based off % left
			$cc_bar_amount = 100;
			if ( ! empty( $cc_free_shipping_amount ) && $final_cart_subtotal <= $cc_free_shipping_amount ) {
				$cc_bar_amount = $final_cart_subtotal * 100 / $cc_free_shipping_amount;
			}

			$cc_shipping_country = get_option( 'cc_shipping_country' );
			if ( 'GB' === $cc_shipping_country ) {
				$cc_shipping_country = 'UK';
			}

			$cc_bar_active = ( $final_cart_subtotal >= $cc_free_shipping_amount ) ? ' cc-bar-active' : '';
			?>
			<span class="cc-fs-title">
				<?php
				if ( $final_cart_subtotal >= $cc_free_shipping_amount ) {
					do_action( 'caddy_fs_congrats_text', $cc_shipping_country );
				} else {
					do_action( 'caddy_fs_spend_text', $free_shipping_remaining_amount, $cc_shipping_country );
				}
				?>
			</span>
			<div class="cc-fs-meter">
				<span class="cc-fs-meter-used<?php echo esc_attr( $cc_bar_active ); ?>" style="width: <?php echo esc_attr( $cc_bar_amount ); ?>%"></span>
			</div>
			<?php
		}
	}

	/**
	 * Cart items array list for the cc-cart screen
	 *
	 * @param array $cart_items_array
	 */
	public function cart_items_list( $cart_items_array = array() ) {
		if ( ! empty( $cart_items_array ) ) {
			foreach ( $cart_items_array as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = $_product->get_id();
				
				// Check if the WooCommerce Product Bundles plugin functions exist
				if ( function_exists( 'wc_pb_is_bundle_container_cart_item' ) && wc_pb_is_bundle_container_cart_item( $cart_item ) ) {
					echo '<div class="cc-cart-product-list bundle">';
				} elseif ( function_exists( 'wc_pb_is_bundled_cart_item' ) && wc_pb_is_bundled_cart_item( $cart_item ) ) {
					echo '<div class="cc-cart-product-list bundled_child">';
				} else {
					echo '<div class="cc-cart-product-list">';
				}
				?>
	
				<?php
				$percentage = 0;
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0
					 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key )
				) {
					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$product_image = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 200, 200 ) ), $cart_item, $cart_item_key );
	
					$product_regular_price = get_post_meta( $product_id, '_regular_price', true );
					$product_sale_price    = get_post_meta( $product_id, '_sale_price', true );
					if ( ! empty( $product_sale_price ) ) {
						$percentage = ( ( $product_regular_price - $product_sale_price ) * 100 ) / $product_regular_price;
					}
					$product_stock_qty = $_product->get_stock_quantity();
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
	
					$plus_disable = '';
					if ( $product_stock_qty > 0 ) {
						if ( ( $product_stock_qty <= $cart_item['quantity'] && ! $_product->backorders_allowed() ) ) {
							$plus_disable = ' cc-qty-disabled';
						}
					}
					?>
					<div class="cc-cart-product">
						<a href="<?php echo esc_url( $product_permalink ); ?>" class="cc-product-link cc-product-thumb"
						   data-title="<?php echo esc_attr( $product_name ); ?>">
							<?php echo $product_image; ?>
						</a>
						<div class="cc_item_content">
							<div class="cc-item-content-top">
								<div class="cc_item_title">
									<?php
	
									if ( ! $product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="cc-product-link">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
									}
									// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
									?>
									<div class="cc_item_quantity_wrap">
										<?php if ( $_product->is_sold_individually() ) {
											echo sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
										} else { ?>
											<div class="cc_item_quantity_update cc_item_quantity_minus" data-type="minus">−</div>
											<input type="text" readonly class="cc_item_quantity" data-product_id="<?php echo esc_attr( $product_id ); ?>"
												   data-key="<?php echo esc_attr( $cart_item_key ); ?>" value="<?php echo $cart_item['quantity']; ?>">
											<div class="cc_item_quantity_update cc_item_quantity_plus<?php echo esc_attr( $plus_disable ); ?>" data-type="plus">+</div>
										<?php } ?>
									</div>
								</div>
								<div class="cc_item_total_price">
									<div class="price">
										<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
										?>
									</div>
									<?php if ( $_product->is_on_sale() ) { ?>
										<div class="cc_saved_amount"><?php echo '(Save ' . round( $percentage ) . '%)'; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="cc-item-content-bottom">
								<div class="cc-item-content-bottom-left">
	
									<?php
									if ( is_user_logged_in() ) {
										$caddy_sfl_button              = true;
										$caddy                         = new Caddy();
										$cc_premium_license_activation = $caddy->cc_check_premium_license_activation();
										if ( $cc_premium_license_activation ) {
											$cc_enable_sfl_options = get_option( 'cc_enable_sfl_options' );
											if ( 'disabled' === $cc_enable_sfl_options ) {
												$caddy_sfl_button = false;
											}
										}
										if ( $caddy_sfl_button ) {
											?>
											<div class="cc_sfl_btn">
												<?php
												echo sprintf(
													'<a href="%s" class="button cc-button-sm save_for_later_btn" aria-label="%s" data-product_id="%s" data-cart_item_key="%s">Save for later</a>',
													'javascript:void(0);',
													esc_html__( 'Save for later', 'caddy' ),
													esc_attr( $product_id ),
													esc_attr( $cart_item_key ),
	
												);
												?>
												<div class="cc-loader" style="display: none;"></div>
											</div>
											<?php
										}
									}
									?>
								</div>
								<?php
								echo sprintf(
									'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_name="%s"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke="currentColor" d="M1 6H23"></path><path stroke="currentColor" d="M4 6H20V22H4V6Z"></path><path stroke="currentColor" d="M9 10V18"></path><path stroke="currentColor" d="M15 10V18"></path><path stroke="currentColor" d="M8 6V6C8 3.79086 9.79086 2 12 2V2C14.2091 2 16 3.79086 16 6V6"></path></svg></a>',
									'javascript:void(0);',
									esc_html__( 'Remove this item', 'caddy' ),
									esc_attr( $product_id ),
									esc_attr( $cart_item_key ),
									esc_attr( $product_name )
								);
								?>
							</div>
						</div>
					</div>
				<?php }
				
				// Example of calling do_action with two arguments
				do_action('caddy_cart_after_product', $cart_item, $cart_item_key);
				
				?>
				
				</div>
			<?php }
		}
	}

	public function caddy_add_cart_widget_to_menu($items, $args) {
		if ($args->menu->slug === get_option('cc_menu_cart_widget')) {
			$cart_widget = new caddy_cart_widget();
	
			// Simulate the arguments required for the widget method
			$widget_args = array(
				'before_widget' => '<li class="menu-item">',
				'after_widget'  => '</li>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>'
			);
			$instance = array(); // Adjust or populate as needed
	
			// Use output buffering to capture the widget output
			ob_start();
			$cart_widget->widget($widget_args, $instance);
			$widget_output = ob_get_clean();
	
			// Append the widget output to the menu items
			$items .= $widget_output;
		}
	
		return $items;
	}
	
	public function caddy_add_saves_widget_to_menu($items, $args) {
		// Check if user is logged in
		if (is_user_logged_in() && $args->menu->slug === get_option('cc_menu_saves_widget')) {
			$save_for_later_widget = new caddy_saved_items_widget();
	
			// Simulate the arguments required for the widget method
			$widget_args = array(
				'before_widget' => '<li class="menu-item">',
				'after_widget'  => '</li>',
				'before_title'  => '', // Title wrappers removed
				'after_title'   => ''
			);
	
			// Provide default or expected values for the instance, excluding si_widget_title
			$instance = array(
				'si_text'    => __('Saves', 'caddy'),  // Default text
				'cc_si_icon' => 'off'                  // Set icon display behavior
			);
	
			// Use output buffering to capture the widget output
			ob_start();
			$save_for_later_widget->widget($widget_args, $instance);
			$widget_output = ob_get_clean();
	
			// Append the widget output to the menu items
			$items .= $widget_output;
		}
	
		return $items;
	}

}
