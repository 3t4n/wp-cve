<?php
defined( 'ABSPATH' ) || exit;

#[AllowDynamicProperties]
class WFACP_Public {
	public static $is_checkout = null;
	private static $ins = null;
	public $page_id = 0;
	public $added_products = [];
	public $products_in_cart = [];
	public $applied_coupon_in_cart = '';
	public $product_settings = [];
	public $variable_product = false;
	public $is_hide_qty = false;
	public $is_checkout_override = false;
	public $billing_details = [];
	public $paypal_billing_address = false;
	public $paypal_shipping_address = false;
	public $shipping_details = [];
	public $is_paypal_express_active_session = false;
	public $is_amazon_express_active_session = false;
	protected $products = [];
	protected $settings = [];
	protected $image_src = [];
	protected $already_discount_apply = [];
	protected $products_count = 0;
	protected $add_to_cart_via_url = false;
	private $have_product = false;

	protected function __construct() {

		add_action( 'wfacp_changed_default_woocommerce_page', [ $this, 'wfacp_changed_default_woocommerce_page' ] );
		/**
		 * We only process checkout page data if header is valid
		 * @since 1.6.0
		 */
		if ( $this->check_valid_header_of_page() ) {

			$hook = 'wfacp_checkout_page_found';
			if ( WFACP_Common::is_theme_builder() ) {
				$hook = 'wfacp_after_checkout_page_found';
			}
			add_action( $hook, [ $this, 'add_to_cart_action' ], 0 );
		}
		// get All setting when AJax is running
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'get_page_data' ], 1 );

		add_action( 'wfacp_before_add_to_cart', [ $this, 'best_value_via_url' ] );
		add_action( 'wfacp_before_add_to_cart', [ $this, 'add_to_cart_via_url' ] );
		add_action( 'wfacp_before_add_to_cart', [ $this, 'default_value_via_url' ] );
		add_action( 'wfacp_before_add_to_cart', [ $this, 'wfacp_before_add_to_cart' ] );
		add_action( 'wfacp_after_add_to_cart', [ $this, 'wfacp_after_add_to_cart' ] );

		add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'save_meta_cart_data' ], 10, 4 );
		add_filter( 'woocommerce_order_item_get_formatted_meta_data', [ $this, 'hide_out_meta_data' ], 10, 4 );
		add_filter( 'woocommerce_coupon_message', [ $this, 'hide_coupon_msg' ], 959 );
		add_filter( 'woocommerce_get_checkout_url', [ $this, 'woocommerce_get_checkout_url' ], 99999 );
		add_action( 'woocommerce_checkout_process', [ $this, 'set_session_when_place_order_btn_pressed' ], - 1 );

		add_action( 'woocommerce_checkout_update_user_meta', [ $this, 'woocommerce_checkout_process' ] );
		add_action( 'woocommerce_applied_coupon', [ $this, 'set_session_when_coupon_applied' ] );
		add_action( 'woocommerce_removed_coupon', [ $this, 'reset_session_when_coupon_removed' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'global_script' ] );
		add_filter( 'wfacp_form_section', [ $this, 'remove_shipping_method' ], 10, 3 );
		add_filter( 'wfacp_hide_section', [ $this, 'skip_empty_section' ], 10, 2 );

		/**
		 * @since 1.6.0
		 */
		if ( apply_filters( 'wfacp_remove_persistent_cart_after_merging', true ) ) {
			/**
			 * We store the cart items into session when user is not logged in
			 * after logged in we restore the stored cart for preventing the persistent cart issue in woocommerce             *
			 **/
			add_action( 'woocommerce_cart_loaded_from_session', [ $this, 'save_wfacp_session' ], 99 );
			add_filter( 'woocommerce_cart_contents_changed', [ $this, 'set_save_session' ], 99 );
		}

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_canonical_link' ], 99 );
		add_action( 'woocommerce_thankyou', [ $this, 'reset_our_localstorage' ] );

		add_action( 'woocommerce_cart_is_empty', [ $this, 'woocommerce_cart_is_empty' ] );


		add_filter( 'wfacp_default_product', [ $this, 'merge_default_product' ], 10, 3 );
		add_action( 'wfacp_page_is_cached', [ $this, 'wfacp_page_is_cached' ] );

		/**
		 * Change woocommerce ajax endpoint only for our checkout pages only
		 * not for every page
		 *
		 */
		add_action( 'wfacp_after_checkout_page_found', function () {
			add_filter( 'woocommerce_ajax_get_endpoint', [ $this, 'woocommerce_ajax_get_endpoint' ], 0, 2 );
		} );


		add_filter( 'woocommerce_add_to_cart_sold_individually_found_in_cart', [
			$this,
			'restrict_sold_individual'
		], 10, 2 );

		add_filter( 'woocommerce_checkout_no_payment_needed_redirect', [ $this, 'reset_session_when_order_processed' ] );
		add_filter( 'woocommerce_payment_successful_result', [ $this, 'reset_session_when_order_processed' ] );
		add_action( 'woocommerce_thankyou', [ $this, 'reset_session_when_order_processed' ] );

		add_action( 'pre_get_posts', [ $this, 'load_page_to_home_page' ], 9999 );
		add_action( 'woocommerce_before_calculate_totals', [ $this, 'calculate_totals' ], 1 );
		// tracking script for native checkout page
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ], 100 );
		add_filter( 'wfacp_tracking_options_data', [ $this, 'update_tracking_data' ], 10, 1 );
		add_filter( 'woocommerce_add_cart_item', [ $this, 'calculate_item_discount' ] );

		/* Shimmer Styling*/
		add_action( 'wfacp_internal_css', [ $this, 'shimmer_css' ] );
		add_filter( 'woocommerce_get_item_data', array( $this, 'unset_aero_line_item_data_in_cart' ), 20, 2 );
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'attach_awaiting_order_id' ] );
	}

	/**
	 * Check valid header of the page (Text/Html)
	 * We only process text/html header
	 * If client enqueue script like this /wfacp_age/?script=frontend
	 * then we not process this call for our checkout page
	 * This issue occur with Oxygen Builder
	 * @return bool
	 * @since 1.6.0
	 *
	 */
	public function check_valid_header_of_page() {

		if ( wp_doing_ajax() ) {
			return true;
		}

		if ( isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( $_SERVER['HTTP_ACCEPT'], 'text/html' ) ) {
			return true;
		}

		return false;

	}


	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function add_to_cart_action( $page_id ) {
		//Do Not Process Dedicated checkout page id order is awaiting against page
		if ( ! is_null( WC()->cart ) && ! is_null( WC()->session ) ) {
			$wc_await_order        = WC()->session->get( 'order_awaiting_payment', null );
			$dedicated_await_order = WC()->session->get( 'wfacp_await_order_' . WFACP_Common::get_id(), null );
			if ( ! is_null( $wc_await_order ) && ! is_null( $dedicated_await_order ) && $wc_await_order == $dedicated_await_order ) {
				return;
			}
		}

		/** Checking for embed forms if embedded on the same page */
		$override_checkout_page_id = WFACP_Common::get_checkout_page_id();
		if ( intval( $page_id ) === intval( $override_checkout_page_id ) ) {
			$design = WFACP_Common::get_page_design( $page_id );
			if ( $design['selected_type'] === 'embed_forms' ) {
				return;
			}
		}
		$this->maybe_pass_no_cache_header( $page_id );
		$this->get_page_data( $page_id );
		$this->add_to_cart( $page_id );
	}

	public function wfacp_changed_default_woocommerce_page() {
		if ( ! is_null( WC()->session ) ) {
			WC()->session->set( 'removed_cart_contents', [] );
			$this->is_checkout_override = true;
			//Override WooCommerce Block Checkout
			WFACP_Common::override_block_cart_checkout();
		}

	}


	public function wfacp_before_add_to_cart() {
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'split_product_individual_cart_items' ), 10, 1 );
	}

	public function wfacp_after_add_to_cart() {
		remove_filter( 'woocommerce_add_cart_item_data', array( $this, 'split_product_individual_cart_items' ), 10 );
	}

	public function get_page_data( $page_id ) {

		$this->products         = WFACP_Common::get_page_product( $page_id );
		$this->products_count   = ! empty( $this->products ) ? count( $this->products ) : 0;
		$this->product_settings = WFACP_Common::get_page_product_settings( $page_id );
		$this->settings         = WFACP_Common::get_page_settings( $page_id );
	}

	public function get_settings() {
		return $this->settings;
	}

	public function get_product_list( $wfacp_id = 0 ) {
		if ( $wfacp_id > 0 ) {
			return WC()->session->get( 'wfacp_product_data_' . $wfacp_id, $this->products );

		}

		return $this->products;
	}

	public function get_product_settings() {
		return $this->product_settings;
	}

	/**
	 * add to cart product after checkout page is found
	 * checkout page id
	 *
	 * @param $page_id
	 */
	public function add_to_cart( $page_id ) {
		do_action( 'wfacp_add_to_cart_init', $this );


		if ( isset( $_GET['cancel_order'] ) ) {
			return;
		}

		if ( WFACP_Common::is_customizer() && false == WC()->cart->is_empty() && 0 == $this->get_product_count() ) {
			return;
		}


		$wfacp_woocommerce_applied_coupon = WC()->session->get( 'wfacp_woocommerce_applied_coupon_' . WFACP_Common::get_Id(), [] );

		if ( $page_id > 0 && isset( $wfacp_woocommerce_applied_coupon[ $page_id ] ) ) {
			return;
		} else {
			WC()->session->set( 'wfacp_woocommerce_applied_coupon_' . WFACP_Common::get_Id(), [] );
		}

		if ( ! is_super_admin() ) {

			$wfacp_checkout_processed = WC()->session->get( 'wfacp_checkout_processed_' . WFACP_Common::get_Id() );
			if ( isset( $wfacp_checkout_processed ) ) {

				$session_return         = false;
				$add_checkout_parameter = $this->aero_add_to_checkout_parameter();

				if ( isset( $_GET[ $add_checkout_parameter ] ) && '' != $_GET[ $add_checkout_parameter ] ) {
					$session_aero_add_to_checkout_parameter = WC()->session->get( 'aero_add_to_checkout_parameter_' . WFACP_Common::get_Id(), false );
					if ( true !== $session_aero_add_to_checkout_parameter && $session_aero_add_to_checkout_parameter == $_GET[ $add_checkout_parameter ] ) {
						$session_return = true;
					}
				} else {
					$session_return = true;
				}
				if ( $session_return ) {
					$this->merge_session_product_with_actual_product();

					return;
				}
			}
		}

		// for third party system
		if ( apply_filters( 'wfacp_skip_add_to_cart', false, $this ) ) {
			return;
		}


		if ( $this->is_checkout_override ) {
			if ( WC()->cart->is_empty() ) {
				if ( isset( $_GET['ct_builder'] ) ) {
					return;
				}
				// case of default checkout and no cart is empty then i  redirect to cart native way
				wp_redirect( get_the_permalink( wc_get_page_id( 'cart' ) ) );
				exit;
			}
			WC()->session->set( 'wfacp_id', WFACP_Common::get_id() );
			WC()->session->set( 'wfacp_is_override_checkout', WFACP_Common::get_id() );

			return;
		} else {
			if ( ! wp_doing_ajax() ) {
				WC()->session->set( 'wfacp_is_override_checkout', 0 );
			}
		}
		if ( isset( $_REQUEST['wc-ajax'] ) ) {
			return;
		}
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( ! function_exists( 'WC' ) || is_null( WC()->cart ) ) {
			return;
		}

		if ( ! is_array( $this->products ) || $this->get_product_count() == 0 ) {
			// case of no product found in our checkout page now i redirect to cart page
			if ( isset( $_GET['ct_builder'] ) ) {
				return;
			}
			$error_messages   = wc_get_notices( 'error' );
			$wfacp_no_product = array_filter( $error_messages, function ( $a ) {
				return isset( $a['data'] ) && isset( $a['data']['id'] ) && $a['data']['id'] == 'wfacp_no_product';
			} );

			if ( empty( $wfacp_no_product ) ) {
				wc_add_notice( __( 'No product in this checkout page', 'woofunnels-aero-checkout' ), 'error', [
					'id' => 'wfacp_no_product'
				] );
			}

			return;
		}

		WC()->cart->empty_cart();

		$this->push_product_to_cart();
	}

	/**
	 * @since 1.5.2
	 */
	public function merge_session_product_with_actual_product() {
		$session_products = WC()->session->get( 'wfacp_product_data_' . WFACP_Common::get_id(), [] );
		if ( ! empty( $session_products ) && ! empty( $this->products ) ) {

			$merge_session_product = [];
			foreach ( $session_products as $pkey => $session_product ) {
				if ( ! isset( $this->products[ $pkey ] ) ) {
					continue;
				}
				if ( isset( $session_product['is_added_cart'] ) ) {
					$merge_session_product[ $pkey ] = $session_product;
				} else {
					$merge_session_product[ $pkey ]                 = $this->products[ $pkey ];
					$merge_session_product[ $pkey ]['org_quantity'] = $this->products[ $pkey ]['quantity'];
				}
			}

			if ( ! empty( $merge_session_product ) ) {
				WC()->session->set( 'wfacp_id', WFACP_Common::get_id() );
				WC()->session->set( 'wfacp_product_data_' . WFACP_Common::get_id(), $merge_session_product );
			}
		}
	}


	public function default_value_via_url() {
		if ( wp_doing_ajax() ) {
			return;
		}
		$default_value_parameter = $this->aero_default_value_parameter();
		if ( isset( $_GET[ $default_value_parameter ] ) && '' != $_GET[ $default_value_parameter ] ) {
			$best_value = filter_input( INPUT_GET, $default_value_parameter, FILTER_UNSAFE_RAW );
			WC()->session->set( 'wfacp_product_default_value_parameter_' . WFACP_Common::get_id(), $best_value );
		} else {
			WC()->session->set( 'wfacp_product_default_value_parameter_' . WFACP_Common::get_id(), '' );
		}
	}

	public function best_value_via_url() {
		if ( wp_doing_ajax() ) {
			return;
		}
		$best_value_parameter = $this->aero_best_value_parameter();
		if ( isset( $_GET[ $best_value_parameter ] ) && '' != $_GET[ $best_value_parameter ] ) {
			$best_value = filter_input( INPUT_GET, $best_value_parameter, FILTER_UNSAFE_RAW );
			WC()->session->set( 'wfacp_product_best_value_by_parameter_' . WFACP_Common::get_id(), $best_value );
		} else {
			WC()->session->set( 'wfacp_product_best_value_by_parameter_' . WFACP_Common::get_id(), '' );
		}
	}


	private function find_existing_match_product( $pid ) {
		foreach ( $this->products as $index => $data ) {
			if ( $pid == $data['id'] ) {
				return array(
					'key'  => $index,
					'data' => $data,
				);
			}
		}

		return null;
	}

	public function split_product_individual_cart_items( $cart_item_data ) {
		$cart_item_data['unique_key'] = uniqid();

		return $cart_item_data;
	}

	/**
	 * @param $ins WC_Cart
	 */
	public function calculate_totals( $ins ) {

		if ( WFACP_Common::get_id() == 0 || apply_filters( 'wfacp_disabled_discounting', false, $this ) ) {
			return $ins;
		}
		$cart_content = $ins->get_cart_contents();

		if ( count( $cart_content ) == 0 ) {
			return $ins;
		}
		$currency = get_woocommerce_currency();
		foreach ( $cart_content as $key => $item ) {
			if ( ! isset( $item['_wfacp_item_discount'] ) ) {
				continue;
			}

			if ( ! isset( $item['_wfacp_item_discount'][ $currency ] ) ) {
				$item = $this->calculate_item_discount( $item, $currency );
			}

			$discounts = $item['_wfacp_item_discount'][ $currency ];

			$item['data']->set_regular_price( $discounts['regular_price'] );
			$item['data']->set_price( $discounts['sale_price'] );
			$item['data']->set_sale_price( $discounts['sale_price'] );
			do_action( 'wfacp_after_discount_added_to_item' );
			$item                       = apply_filters( 'wfacp_after_discount_added_to_item', $item, $key, $ins );
			$ins->cart_contents[ $key ] = $item;
		}

	}

	public function calculate_item_discount( $cart_item_data, $currency = '' ) {


		if ( apply_filters( 'wfacp_disabled_item_discounting', false, $this ) ) {
			return $cart_item_data;
		}
		if ( empty( $currency ) ) {
			$currency = get_woocommerce_currency();
		}

		return $this->modify_calculate_price_per_session( $cart_item_data, $currency );

	}

	/**
	 * Apply discount on basis of input for product raw prices
	 *
	 * @param $item WC_cart;
	 *
	 * @return mixed
	 */

	public function modify_calculate_price_per_session( $item, $currency ) {
		if ( ! isset( $item['_wfacp_product'] ) ) {
			return $item;
		}
		if ( isset( $item['_wfacp_options']['add_to_cart_via_url'] ) && isset( $item['_wfacp_options']['not_existing_product'] ) ) {
			return $item;
		}
		$discount_amount = $item['_wfacp_options']['discount_amount'];
		if ( floatval( $discount_amount ) == 0 && true == apply_filters( 'wfacp_allow_zero_discounting', true, $item ) ) {
			return $item;
		}

		/**
		 * @var $product WC_product;
		 */
		$product                                                     = $item['data'];
		$raw_data                                                    = $product->get_data();
		$raw_data                                                    = apply_filters( 'wfacp_product_raw_data', $raw_data, $product );
		$regular_price                                               = apply_filters( 'wfacp_discount_regular_price_data', $raw_data['regular_price'] );
		$price                                                       = apply_filters( 'wfacp_discount_price_data', $raw_data['price'] );
		$discount_amount                                             = apply_filters( 'wfacp_discount_amount_data', $item['_wfacp_options']['discount_amount'], $item['_wfacp_options']['discount_type'] );
		$discount_data                                               = [
			'wfacp_product_rp'      => $regular_price,
			'wfacp_product_p'       => $price,
			'wfacp_discount_amount' => $discount_amount,
			'wfacp_discount_type'   => $item['_wfacp_options']['discount_type'],
		];
		$new_price                                                   = WFACP_Common::calculate_discount( $discount_data );
		$this->already_discount_apply[ $item['_wfacp_product_key'] ] = true;
		if ( is_null( $new_price ) ) {
			return $item;
		}
		if ( ! isset( $item['_wfacp_item_discount'] ) ) {
			$item['_wfacp_item_discount'] = [];
		}
		if ( ! isset( $item['_wfacp_item_discount'][ $currency ] ) ) {
			$item['_wfacp_item_discount'][ $currency ] = [];
		}
		$item['_wfacp_item_discount'][ $currency ]['regular_price'] = $regular_price;
		$item['_wfacp_item_discount'][ $currency ]['sale_price']    = $new_price;

		return $item;
	}

	/**
	 *
	 * @param $cart WC_Cart;
	 */
	public function save_wfacp_session( $cart ) {
		if ( ! is_user_logged_in() ) {
			$cart_content = $cart->get_cart_contents();
			if ( ! empty( $cart_content ) && WFACP_Common::get_id() > 0 ) {
				WC()->session->set( 'wfacp_sustain_cart_content_' . WFACP_Common::get_id(), $cart_content );
			}
		}

	}

	public function global_script() {
		if ( WFACP_Common::is_customizer() ) {
			add_filter( 'woocommerce_checkout_show_terms', function () {
				return false;
			} );
		}
	}


	public function get_image_src( $image_id, $size = 'full' ) {

		if ( isset( $this->image_src[ $image_id ][ $size ] ) && ! empty( $this->image_src[ $image_id ][ $size ] ) ) {
			return $this->image_src[ $image_id ][ $size ];
		} else {
			if ( $image_id == '' ) {
				return;
			}
			$img_src_arr = wp_get_attachment_image_src( $image_id, $size );
			$img_src     = $img_src_arr[0];
			if ( ! isset( $this->image_src[ $image_id ][ $size ] ) ) {
				$this->image_src[ $image_id ][ $size ] = $img_src;
			}

			return $img_src;
		}
	}


	/**
	 * @param $item WC_Order_Item
	 * @param $cart_item_key String
	 * @param $values Object
	 * @param $order WC_Order
	 */
	public function save_meta_cart_data( $item, $cart_item_key, $values, $order ) {
		if ( $order instanceof WC_Order && ! empty( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( false !== strpos( $key, 'wfacp_' ) ) {
					$item->add_meta_data( $key, $value );
				}
			}
		}
	}

	/**
	 * @param $formatted_meta Array
	 * @param $instance WC_Order_Item
	 */

	public function hide_out_meta_data( $formatted_meta, $instance ) {
		if ( $instance instanceof WC_Order_Item && ! empty( $formatted_meta ) ) {
			foreach ( $formatted_meta as $key => $value ) {
				if ( false !== strpos( $value->key, 'wfacp_' ) && apply_filters( 'wfacp_hide_out_meta_data', true, $key, $value ) ) {
					unset( $formatted_meta[ $key ] );
				}
			}
		}

		return $formatted_meta;
	}

	public function hide_coupon_msg( $msg ) {

		if ( isset( $this->settings['disable_coupon'] ) && 'true' === $this->settings['disable_coupon'] ) {
			$msg = '';
		}

		return $msg;

	}

	public function is_checkout_override() {
		if ( is_null( WC()->session ) ) {
			return $this->is_checkout_override;
		}

		$wfacp_is_override_checkout = WC()->session->get( 'wfacp_is_override_checkout', 0 );

		if ( $wfacp_is_override_checkout > 0 ) {
			$this->is_checkout_override = true;
		}

		if ( isset( $_REQUEST['wfacp_is_checkout_override'] ) && 'yes' == $_REQUEST['wfacp_is_checkout_override'] ) {
			$this->is_checkout_override = true;
		}

		if ( isset( $_REQUEST['wfacp_is_checkout_override'] ) && 'no' == $_REQUEST['wfacp_is_checkout_override'] ) {
			$this->is_checkout_override = false;
		}


		return $this->is_checkout_override;
	}

	public function woocommerce_ajax_get_endpoint( $url, $request ) {
		if ( WFACP_Common::get_id() > 0 ) {
			$query = [
				'wfacp_id'                   => WFACP_Common::get_id(),
				'wfacp_is_checkout_override' => ( $this->is_checkout_override ) ? 'yes' : 'no',
			];
			if ( isset( $_REQUEST['currency'] ) ) {
				$query['currency'] = filter_input( INPUT_GET, 'currency', FILTER_UNSAFE_RAW );;
			}
			if ( isset( $_REQUEST['lang'] ) ) {
				$query['lang'] = filter_input( INPUT_GET, 'lang', FILTER_UNSAFE_RAW );
			}
			$query            = apply_filters( 'wfacp_ajax_endpoint_parameters', $query, $this );
			$query['wc-ajax'] = $request;
			$url              = add_query_arg( $query, $url );
		}

		return $url;
	}

	public function unset_wcct_campaign( $status, $instance ) {

		if ( $this->get_product_count() > 0 ) {

			foreach ( $this->products as $index => $data ) {
				$product_id = absint( $data['id'] );
				if ( $data['parent_product_id'] && $data['parent_product_id'] > 0 ) {
					$product_id = absint( $data['parent_product_id'] );
				}
				unset( $instance->single_campaign[ $product_id ] );
				$status = false;
			}
		}

		return $status;

	}

	public function maybe_pass_no_cache_header() {
		WC()->cart->removed_cart_contents = [];
		$this->set_nocache_constants();
		nocache_headers();

	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public function set_nocache_constants() {

		$this->maybe_define_constant( 'DONOTCACHEPAGE', true );
		$this->maybe_define_constant( 'DONOTCACHEOBJECT', true );
		$this->maybe_define_constant( 'DONOTCACHEDB', true );

		return null;
	}

	function maybe_define_constant( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function woocommerce_get_checkout_url( $url ) {

		if ( WFACP_Core()->pay instanceof WFACP_Order_pay && WFACP_Core()->pay->is_order_pay() ) {
			return $url;
		}
		$id = WFACP_Common::get_id();
		if ( $id > 0 ) {
			$posts  = get_post( $id );
			$loader = WFACP_Core()->template_loader;
			if ( ! is_null( $posts ) && $posts->post_status == 'publish' && $loader->is_valid_state_for_data_setup() ) {
				$override_checkout_page_id = WFACP_Common::get_checkout_page_id();
				if ( $override_checkout_page_id !== $id ) {
					return get_the_permalink( $id );
				}
			}
		}

		return $url;
	}

	public function remove_shipping_method( $section, $section_index, $step ) {


		if ( ! is_array( $section ) || count( $section ) == 0 || ! isset( $section['fields'] ) || count( $section['fields'] ) == 0 ) {
			return $section;
		}
		$shipping_calculator_index = false;

		foreach ( $section['fields'] as $index => $field ) {
			if ( isset( $field['id'] ) && 'shipping_calculator' == $field['id'] ) {
				$shipping_calculator_index = $index;
				break;
			}
		}

		if ( false !== $shipping_calculator_index ) {

			WC()->session->set( 'wfacp_shipping_method_parent_fields_count_' . WFACP_Common::get_id(), [
				'count' => count( $section['fields'] ),
				'index' => $section_index,
				'step'  => $step,
			] );
		}

		return $section;
	}

	public function skip_empty_section( $status, $section ) {
		if ( ! is_array( $section ) || count( $section ) == 0 || ! isset( $section['fields'] ) || count( $section['fields'] ) == 0 ) {
			return true;
		}

		return $status;
	}

	public function set_session_when_place_order_btn_pressed() {
		WC()->session->set( 'wfacp_checkout_processed_' . WFACP_Common::get_Id(), true );
		if ( ! empty( $_POST ) && isset( $_POST['_wfacp_post_id'] ) ) {
			WC()->session->set( 'wfacp_posted_data', $_POST );
		}
	}

	public function reset_session_when_order_processed( $data ) {
		if ( is_null( WC()->session ) ) {
			return $data;
		}
		// if Reload set by woocommerce in any case then won`t unset any our sessions for Preventing Multiple Order issue.
		if ( true === WC()->session->reload_checkout ) {
			return $data;
		}
		$checkout_id = WFACP_Common::get_Id();
		WC()->session->__unset( 'wfacp_checkout_processed_' . $checkout_id );
		WC()->session->__unset( 'aero_add_to_checkout_parameter_' . $checkout_id );
		WC()->session->__unset( 'wfacp_cart_hash' );
		WC()->session->__unset( 'wfacp_product_objects_' . $checkout_id );
		WC()->session->__unset( 'wfacp_product_data_' . $checkout_id );
		WC()->session->__unset( 'wfacp_is_override_checkout' );
		WC()->session->__unset( 'wfacp_product_best_value_by_parameter_' . $checkout_id );
		WC()->session->__unset( 'wfacp_sustain_cart_content_' . $checkout_id );
		WC()->session->__unset( 'removed_cart_contents' );
		WC()->session->__unset( 'wfacp_woocommerce_applied_coupon_' . $checkout_id );

		return $data;
	}

	public function set_session_when_coupon_applied() {

		$c = WC()->session->get( 'wfacp_woocommerce_applied_coupon_' . WFACP_Common::get_Id(), [] );
		if ( isset( $_REQUEST['wfacp_id'] ) ) {
			$id       = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
			$id       = absint( $id );
			$c[ $id ] = true;
		}
		WC()->session->set( 'wfacp_woocommerce_applied_coupon_' . WFACP_Common::get_Id(), $c );
	}

	public function reset_session_when_coupon_removed() {
		WC()->session->__unset( 'wfacp_woocommerce_applied_coupon_' . WFACP_Common::get_Id() );
	}

	/**
	 * validate cart hash of multiple checkout page when open in same browser
	 * Make sure latest open checkout page is open
	 */
	public function woocommerce_checkout_process() {

		if ( isset( $_POST['wfacp_has_active_multi_checkout'] ) && $_POST['wfacp_has_active_multi_checkout'] != 'no' ) {
			return;
		}
		if ( isset( $_POST['wfacp_cart_hash'] ) && '' !== $_POST['wfacp_cart_hash'] ) {
			$wfacp_cart_hash = filter_input( INPUT_POST, 'wfacp_cart_hash', FILTER_UNSAFE_RAW );
			$form_cart_hash  = trim( $wfacp_cart_hash );
			$cart_hash       = trim( WC()->session->get( 'wfacp_cart_hash', '' ) );
			if ( '' !== $cart_hash && ( $form_cart_hash !== $cart_hash ) ) {
				/**
				 * We found two separate cart hash now send reload trigger to checkout.js
				 */
				wp_send_json( [
					'reload' => true,
				] );
			}
		}
	}


	public function set_save_session( $cart_content ) {
		if ( is_user_logged_in() ) {

			$cart_conm = WC()->session->get( 'wfacp_sustain_cart_content_' . WFACP_Common::get_Id(), [] );
			if ( ! empty( $cart_conm ) ) {
				WC()->session->__unset( 'wfacp_sustain_cart_content_' . WFACP_Common::get_Id() );

				return $cart_conm;

			}
		}

		return $cart_content;
	}

	/**
	 * Remove all canonical in our page
	 * Because of checkout page not for seo
	 * IN firefox <link rel='next' href="URL">
	 * Load our current page in network
	 * and this cause to wrong behaviour of page
	 *
	 */
	public function remove_canonical_link() {
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'rel_canonical' );
	}

	public function reset_our_localstorage() {
		?>
        <script>

            if (typeof Storage !== 'undefined') {
                window.localStorage.removeItem('wfacp_checkout_page_id');
            }
        </script>
		<?php
	}


	public function woocommerce_cart_is_empty() {
		WC()->session->__unset( 'wfacp_sustain_cart_content_' . WFACP_Common::get_Id() );
		WC()->session->__unset( 'wfacp_woocommerce_applied_coupon_' . WFACP_Common::get_Id() );

	}

	public function add_to_cart_via_url() {

		$add_checkout_parameter = $this->aero_add_to_checkout_parameter();

		if ( isset( $_GET[ $add_checkout_parameter ] ) && '' != $_GET[ $add_checkout_parameter ] ) {
			$this->add_to_cart_via_url = true;
			$t_add_checkout_parameter  = filter_input( INPUT_GET, $add_checkout_parameter, FILTER_UNSAFE_RAW );
			WC()->session->set( 'aero_add_to_checkout_parameter_' . WFACP_Common::get_Id(), $t_add_checkout_parameter );
			$products           = explode( ',', $t_add_checkout_parameter );
			$products_qty       = [];
			$quantity_parameter = $this->aero_add_to_checkout_product_quantity_parameter();
			if ( isset( $_GET[ $quantity_parameter ] ) ) {
				$t_quantity_parameter = filter_input( INPUT_GET, $quantity_parameter, FILTER_UNSAFE_RAW );
				$products_qty         = explode( ',', $t_quantity_parameter );
			}
			if ( is_array( $products ) && count( $products ) > 0 ) {
				$new_products = [];
				foreach ( $products as $pid_index => $pid ) {
					$unique_id     = uniqid( 'wfacp_' );
					$existing_data = $this->find_existing_match_product( $pid );
					if ( ! is_null( $existing_data ) ) {
						$existing_data['data']['whats_included'] = '';
						if ( isset( $products_qty[ $pid_index ] ) && $products_qty[ $pid_index ] > 0 ) {
							$existing_data['data']['org_quantity']                 = 1;
							$existing_data['data']['add_to_cart_via_url_quantity'] = $products_qty[ $pid_index ];
						}
						$existing_data['data']['add_to_cart_via_url'] = true;
						$existing_data['data']['is_default']          = true;
						$new_products[ $existing_data['key'] ]        = $existing_data['data'];

						continue;
					}
					$product = wc_get_product( $pid );
					if ( $product instanceof WC_Product ) {
						$product_type = $product->get_type();
						$image_id     = $product->get_image_id();
						$default      = WFACP_Common::get_default_product_config();
						$image        = '';

						if ( ! empty( $image_id ) ) {
							$image = wp_get_attachment_image_src( $image_id );
						}

						$default['image']             = ( is_array( $image ) ) ? $image[0] : '';
						$default['type']              = $product_type;
						$default['id']                = $product->get_id();
						$default['parent_product_id'] = $product->get_parent_id();
						$default['title']             = $product->get_title();
						$default['quantity']          = 1;
						$default['org_quantity']      = 1;
						$default['is_default']        = true;

						$default['not_existing_product'] = true;
						if ( isset( $products_qty[ $pid_index ] ) && $products_qty[ $pid_index ] > 0 ) {
							$default['add_to_cart_via_url_quantity'] = $products_qty[ $pid_index ];
						}


						if ( 'variable' === $product_type ) {
							$default['variable'] = 'yes';
							$default['price']    = $product->get_price_html();
							$is_found_variation  = WFACP_Common::get_default_variation( $product );

							if ( count( $is_found_variation ) > 0 ) {
								$default['default_variation']      = $is_found_variation['variation_id'];
								$default['default_variation_attr'] = $is_found_variation['attributes'];
							}
						} else {
							$row_data                 = $product->get_data();
							$sale_price               = $row_data['sale_price'];
							$default['price']         = wc_price( $row_data['price'] );
							$default['regular_price'] = wc_price( $row_data['regular_price'] );
							if ( '' != $sale_price ) {
								$default['sale_price'] = wc_price( $sale_price );
							}
						}
						$default                        = WFACP_Common::remove_product_keys( $default );
						$default['add_to_cart_via_url'] = true;
						$default['whats_included']      = '';
						$new_products[ $unique_id ]     = $default;

					}
				}

				if ( count( $new_products ) > 0 ) {
					$this->products       = $new_products;
					$this->products_count += count( $new_products );
				}


			}
		}
	}

	private function push_product_to_cart() {

		do_action( 'wfacp_before_add_to_cart', $this->products );

		if ( function_exists( 'WCCT_Core' ) && class_exists( 'WCCT_discount' ) ) {
			add_filter( 'wcct_force_do_not_run_campaign', [ $this, 'unset_wcct_campaign' ], 10, 2 );
		}


		$virtual_product = 0;
		foreach ( $this->products as $index => $data ) {
			$product_id   = absint( $data['id'] );
			$quantity     = absint( $data['quantity'] );
			$variation_id = 0;
			if ( $data['parent_product_id'] && $data['parent_product_id'] > 0 ) {
				$product_id   = absint( $data['parent_product_id'] );
				$variation_id = absint( $data['id'] );
			}

			$product_obj = WFACP_Common::wc_get_product( ( $variation_id > 0 ? $variation_id : $product_id ), $index );
			if ( ! $product_obj instanceof WC_Product ) {
				continue;
			}
			if ( $product_obj->is_virtual() ) {
				$virtual_product ++;
			}
			$data['is_default'] = true;
			if ( ! isset( $data['add_to_cart_via_url'] ) || ! isset( $data['add_to_cart_via_url_quantity'] ) ) {
				$data['org_quantity'] = $quantity;
				$data['quantity']     = 1;
			}
			// assign url quantity to quantity key in product data for sustainability
			if ( isset( $data['add_to_cart_via_url_quantity'] ) ) {
				$data['quantity'] = $data['add_to_cart_via_url_quantity'];
			}
			$data['item_key']         = $index;
			$this->products[ $index ] = $data;

			$product_obj->add_meta_data( 'wfacp_data', $data );
			$this->added_products[ $index ] = $product_obj;
			if ( in_array( $product_obj->get_type(), WFACP_Common::get_variable_product_type() ) ) {
				$this->variable_product = true;
			}
		}

		$is_product_added_to_cart = false;

		$all_notices = wc_get_notices();
		$success     = [];

		$run_status = apply_filters( 'wfacp_run_add_to_cart_at_load', true, $this );
		if ( true == $run_status ) {
			foreach ( $this->added_products as $index => $product_obj ) {
				$data = $product_obj->get_meta( 'wfacp_data' );

				if ( ! is_array( $data ) ) {
					continue;
				}

				if ( ! isset( $data['is_default'] ) ) {
					continue;
				}

				$product_id = absint( $data['id'] );
				$quantity   = absint( $data['org_quantity'] );
				if ( isset( $data['not_existing_product'] ) ) {
					$quantity = absint( $data['quantity'] );
				}
				if ( isset( $data['add_to_cart_via_url_quantity'] ) ) {
					$quantity = $data['add_to_cart_via_url_quantity'];
				}
				$variation_id = 0;
				if ( $data['parent_product_id'] && $data['parent_product_id'] > 0 ) {
					$product_id   = absint( $data['parent_product_id'] );
					$variation_id = absint( $data['id'] );
				}
				try {
					$attributes  = [];
					$custom_data = [];
					if ( isset( $data['variable'] ) ) {
						$variation_id                             = absint( $data['default_variation'] );
						$attributes                               = $data['default_variation_attr'];
						$custom_data['wfacp_variable_attributes'] = $attributes;
						$default_variation                        = WFACP_Common::get_default_variation( $product_obj );
						if ( count( $default_variation ) > 0 ) {
							$variation_id = absint( $default_variation['variation_id'] );
							$attributes   = $default_variation['attributes'];
						}
					} else if ( in_array( $product_obj->get_type(), WFACP_Common::get_variation_product_type() ) ) {
						$attributes = $product_obj->get_attributes();
						if ( empty( $attributes ) ) {
							continue;
						}
						if ( WFACP_Common::is_invalid_variation_attribute( $attributes ) ) {
							$attributes = WFACP_Common::map_variation_attributes( $attributes, wc_get_product( $product_id )->get_variation_attributes() );
						} else {
							$new_attributes = [];
							foreach ( $attributes as $ts => $attribute ) {
								$ts                    = 'attribute_' . $ts;
								$new_attributes[ $ts ] = $attribute;
							}
							$attributes = $new_attributes;
						}
					}
					$custom_data['_wfacp_product']     = true;
					$custom_data['_wfacp_product_key'] = $index;
					$custom_data['_wfacp_options']     = $data;

					$cart_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $attributes, $custom_data );
					if ( is_string( $cart_key ) ) {
						$success[]                        = $cart_key;
						$this->products_in_cart[ $index ] = 1;
						$data['is_added_cart']            = $cart_key;
						$this->added_products[ $index ]->update_meta_data( 'wfacp_data', $data );;
						$this->products[ $index ]['is_added_cart'] = $cart_key;
						$this->have_product                        = true;
						$is_product_added_to_cart                  = true;
					} else {
						unset( $this->added_products[ $index ], $this->products[ $index ] );
					}
				} catch ( Exception $e ) {

				}
			}
		}

		if ( false == $is_product_added_to_cart ) {
			$all_notices = array_merge( wc_get_notices(), $all_notices );
			WC()->session->set( 'wc_notices', $all_notices );
		} else {
			WC()->session->set( 'wc_notices', $all_notices );
		}

		do_action( 'wfacp_after_add_to_cart' );
		if ( count( $success ) > 0 || false == $run_status ) {
			WC()->cart->removed_cart_contents = [];
			WC()->session->set( 'wfacp_id', WFACP_Common::get_id() );
			WC()->session->set( 'wfacp_cart_hash', md5( maybe_serialize( WC()->cart->get_cart_contents() ) ) );
			WC()->session->set( 'wfacp_product_objects_' . WFACP_Common::get_id(), $this->added_products );
			WC()->session->set( 'wfacp_product_data_' . WFACP_Common::get_id(), $this->products );
		}

	}

	public function aero_add_to_checkout_parameter() {
		return apply_filters( 'wfacp_aero_add_to_checkout_parameter', 'aero-add-to-checkout' );
	}

	public function aero_add_to_checkout_product_quantity_parameter() {
		return apply_filters( 'wfacp_add_to_checkout_product_quantity_parameter', 'aero-qty' );
	}


	public function aero_default_value_parameter() {
		return apply_filters( 'wfacp_aero_default_value_parameter', 'aero-default' );
	}

	public function aero_best_value_parameter() {
		return apply_filters( 'wfacp_aero_best_value_parameter', 'aero-best-value' );
	}

	public function merge_default_product( $default_products, $products, $settings ) {
		$default = $this->aero_default_value_parameter();
		if ( isset( $_GET[ $default ] ) && '' !== $_GET[ $default ] ) {

			$data = WC()->session->get( 'wfacp_product_default_value_parameter_' . WFACP_Common::get_id(), '' );

			if ( '' !== $data ) {
				$t_default    = filter_input( INPUT_GET, $default, FILTER_UNSAFE_RAW );
				$default_data = explode( ',', $t_default );

				if ( ! empty( $default_data ) ) {
					$default_products = [];
				}

				if ( true == $this->add_to_cart_via_url ) {
					$products = $this->products;

				}
				$counter = 1;
				foreach ( $products as $key => $product ) {
					if ( in_array( $counter, $default_data ) ) {
						$default_products[] = $key;
					}
					$counter ++;
				}
				$default_products = array_unique( $default_products );

			}
		}

		return $default_products;
	}

	/**
	 * @param $data product data arrray;
	 */
	public function product_available_form_purchase( $data, $unique_key ) {

		$product_available = true;
		$product_id        = absint( $data['id'] );
		$quantity          = absint( $data['quantity'] );
		$variation_id      = 0;
		if ( $data['parent_product_id'] && $data['parent_product_id'] > 0 ) {
			$product_id   = absint( $data['parent_product_id'] );
			$variation_id = absint( $data['id'] );
		}
		$product_obj = WFACP_Common::wc_get_product( ( $variation_id > 0 ? $variation_id : $product_id ), $unique_key );
		if ( ! $product_obj instanceof WC_Product ) {
			return false;
		}
		$stock_status = WFACP_Common::check_manage_stock( $product_obj, $quantity );

		if ( ! $product_obj->is_purchasable() || false == $stock_status ) {
			$product_available = false;
		}

		return $product_available;
	}

	public function get_product_count() {
		return $this->products_count;
	}

	public function wfacp_page_is_cached( $page_id ) {
		$page_id = absint( $page_id );
		WFACP_Common::set_id( $page_id );
		$this->get_page_data( $page_id );
		$this->push_product_to_cart();
	}

	public function restrict_sold_individual( $status, $product_id ) {
		$cart_content = WC()->cart->get_cart_contents();
		if ( ! empty( $cart_content ) ) {
			foreach ( $cart_content as $item_key => $item_data ) {
				if ( $item_data['product_id'] == $product_id && isset( $item_data['_wfacp_options'] ) ) {
					$status = true;
					break;
				}
			}
		}

		return $status;
	}

	/**
	 * @param $query WP_Query
	 */
	public function load_page_to_home_page( $query ) {
		if ( $query->is_main_query() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

			$post_type = $query->get( 'post_type' );

			$page_id = $query->get( 'page_id' );

			if ( empty( $post_type ) && ! empty( $page_id ) ) {
				$t_post = get_post( $page_id );
				if ( $t_post->post_type == WFACP_Common::get_post_type_slug() ) {
					$query->set( 'post_type', get_post_type( $page_id ) );
				}
			}
		}
	}

	/**
	 * script render for on native checkout
	 * @return void
	 */
	public function enqueue_script() {
		if ( empty( $this->is_native_checkout() ) ) {
			return;
		}
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wfacp_track_checkout_js', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/native-tracks.js', [ 'jquery' ], WFACP_VERSION_DEV, false );
		wp_localize_script( 'wfacp_track_checkout_js', 'wfacp_analytics_data', $this->get_analytics_data() );

	}

	/**
	 * localize tracking data for native checkout
	 * @return array|void
	 */
	public function get_analytics_data() {
		if ( empty( $this->is_native_checkout() ) ) {
			return;
		}
		$checkout = $this->is_native_checkout();

		// prepare data for native checkout
		do_action( 'wfacp_after_native_checkout_page_found' );

		$final    = [];
		$services = WFACP_Analytics::get_available_service();

		foreach ( $services as $service => $analytic ) {
			/**
			 * @var $analytic WFACP_Analytics;
			 */
			$final[ $service ] = $analytic->get_prepare_data();
		}
		$do_tracking = true;
		if ( is_checkout_pay_page() ) {
			$do_tracking = false;
		}
		$final['shouldRender'] = apply_filters( 'wfacp_do_tracking', $do_tracking );

		$final['conversion_api'] = 'false';
		$admin_general           = BWF_Admin_General_Settings::get_instance();
		$is_conversion_api       = $admin_general->get_option( 'is_fb_purchase_conversion_api' );
		if ( is_array( $is_conversion_api ) && count( $is_conversion_api ) > 0 && 'yes' === $is_conversion_api[0] && ! empty( $admin_general->get_option( 'conversion_api_access_token' ) ) ) {
			$final['conversion_api'] = 'true';
		}
		$final['wfacp_frontend'] = [
			'id'            => $checkout->ID,
			'title'         => get_the_title( $checkout->ID ),
			'edit_mode'     => WFACP_Common::is_theme_builder() ? 'yes' : 'no',
			'is_customizer' => false,
			'admin_ajax'    => admin_url( 'admin-ajax.php' ),
			'wc_endpoints'  => WFACP_AJAX_Controller::get_public_endpoints(),
			'wfacp_nonce'   => wp_create_nonce( 'wfacp_secure_key' ),
		];

		$final['fb_advanced']     = WFACP_Common::pixel_advanced_matching_data();
		$final['tiktok_advanced'] = WFACP_Common::tiktok_advanced_matching_data();

		return $final;
	}

	/**
	 * check is site native checkout
	 * @return array|false|int|mixed|WP_Post
	 */
	public function is_native_checkout() {
		global $post;
		if ( is_null( $post ) ) {
			return false;
		}
		if ( $post->post_status !== 'publish' || $post->post_type === WFACP_Common::get_post_type_slug() || 0 !== did_action( 'wfacp_after_checkout_page_found' ) ) {
			return false;
		}
		$meta_exist = get_post_meta( $post->ID, '_is_aero_checkout_page', true );
		if ( '' != $meta_exist ) {
			return false;
		}
		if ( ! is_checkout() || is_wc_endpoint_url( 'order-received' ) ) {
			return false;
		}

		return $post;
	}

	/* hide add to cart tracking for global checkout
	* @param $data
	* @return array|mixed
	*/
	public function update_tracking_data( $data ) {

		if ( ! isset( $data['settings']['add_to_cart'] ) || 'true' !== $data['settings']['add_to_cart'] ) {
			return $data;
		}

		if ( isset( $_GET['add-to-cart'] ) && $_GET['add-to-cart'] > 0 ) {
			return $data;
		}

		if ( $this->is_checkout_override ) {
			$data['settings']['add_to_cart'] = 'false';
		}

		return $data;
	}

	public function shimmer_css() {

		if ( apply_filters( 'wfacp_shimmer_active', true ) ) {
			echo "<style>";
			include plugin_dir_path( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp-shimmer.min.css';
			echo "</style>";
		} else {

			$path = plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/img/spinner.gif';

			echo "<style>";
			echo 'body .wfacp_main_form #wfacp_checkout_form .blockUI.blockOverlay {  background: url(' . $path . ') no-repeat 50% rgb(255, 255, 255) !important;display:block !important;}';
			echo "</style>";
		}
	}

	/**
	 * Unset Aero line item data on cart page
	 *
	 * @param array $cart_item_data
	 * @param array $cart_item
	 */
	public function unset_aero_line_item_data_in_cart( $cart_item_data, $cart_item ) {

		if ( empty( $cart_item_data ) ) {
			return $cart_item_data;
		}

		foreach ( $cart_item_data as $index => $line_item ) {
			if ( isset( $line_item['key'] ) && false !== strpos( $line_item['key'], 'wfacp' ) ) {
				unset( $cart_item_data[ $index ] );
			}
		}


		return $cart_item_data;
	}

	public function attach_awaiting_order_id( $order_id ) {
		if ( is_null( WC()->cart ) || is_null( WC()->session ) ) {
			return;
		}
		WC()->session->set( 'wfacp_await_order_' . WFACP_Common::get_id(), $order_id );

	}

}

if ( class_exists( 'WFACP_Core' ) && ! WFACP_Common::is_disabled() ) {
	WFACP_Core::register( 'public', 'WFACP_Public' );
}


