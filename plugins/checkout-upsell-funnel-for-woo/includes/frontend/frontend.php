<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend {
	protected static $cache, $settings, $today, $current_day;
	protected static $is_user_login, $current_user;
	protected static $wc_cart, $wc_checkout;

	public function __construct() {
		self::add_ajax_events();
		add_filter( 'woocommerce_before_calculate_totals', array( $this, 'viwcuf_woocommerce_before_calculate_totals' ), PHP_INT_MIN, 1 );
		add_filter( 'wp_kses_allowed_html', array( $this, 'viwcuf_wp_kses_allowed_html' ), PHP_INT_MAX, 2 );
	}

	public static function init() {
		self::$wc_cart     = self::$wc_cart ?? WC()->cart;
		self::$today       = self::$today ?? getdate();
		self::$current_day = self::$current_day ?? date( 'w' );
	}

	public static function add_ajax_events() {
		$ajax_events = array(
			'viwcuf_get_variation'      => true,
			'viwcuf_add_to_cart'        => true,
			'viwcuf_remove_form_cart'   => true,
			'viwcuf_us_add_all_to_cart' => true,
			'viwcuf_us_set_session'     => true,
		);
		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_woocommerce_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
			// WC AJAX can be used for frontend ajax requests.
			add_action( 'wc_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}

	public static function viwcuf_get_variation() {
		check_ajax_referer('viwcuf_nonce','viwcuf_nonce');
		$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : 0;
		$wcuf_pd_type = isset( $_POST['wcuf_pd_type'] ) ? sanitize_text_field( $_POST['wcuf_pd_type'] ) : '';
		if ( ! $product_id || ! $wcuf_pd_type ) {
			wp_die();
		}
		$variable_product = wc_get_product( $product_id );
		if ( ! $variable_product ) {
			wp_die();
		}
		$data_store   = WC_Data_Store::load( 'product' );
		$variation_id = $data_store->find_matching_product_variation( $variable_product, wc_clean(wp_unslash( $_POST ) ));
		$variation    = $variation_id ? $variable_product->get_available_variation( $variation_id ) : false;
		if ( $variation && $variation_id && ( $variation_object = wc_get_product( $variation_id ) ) ) {
			if ( ! $variation_object->is_in_stock() || ( $variation_object->managing_stock() && $variation_object->get_stock_quantity() <= get_option( 'woocommerce_notify_no_stock_amount', 0 ) && 'no' === $variation_object->get_backorders() ) ) {
				$variation['is_in_stock'] = false;
			}
			if ( $wcuf_pd_type === 'order_bump' ) {
				$discount_type   = isset( $_POST['discount_type'] ) ? sanitize_text_field( $_POST['discount_type'] ) : '';
				$discount_amount = isset( $_POST['discount_amount'] ) ? sanitize_text_field( $_POST['discount_amount'] ) : 0;
				$current_price   = $discount_type === '5' ? $discount_amount : (float) $variation_object->get_price();
				$regular_price   = in_array( $discount_type, [ '1', '2', '5' ] ) ? (float) $variation_object->get_regular_price() : $current_price;
				$new_price       = self::set_new_price_pd( $current_price, $regular_price, $discount_type, $discount_amount );
				if ( $current_price === $new_price && $discount_type !== '5' ) {
					$variation['viwcuf_price_html'] = $variation['price_html'] ?? sprintf( '<span class="price">%s</span>', $variation_object->get_price_html() );
				} else {
					$variation['viwcuf_price_html'] = $discount_type === '5' ? sprintf( '<span class="price">%s</span>', wc_price( $new_price ) ) : sprintf( '<span class="price"><del>%s</del><ins>%s</ins></span>', wc_price( $current_price ), wc_price( $new_price ) );
				}
			} else {
				$rule_info = WC()->session->get( 'viwcuf_us_rule_info', array() );
				if ( $rule_info && is_array( $rule_info ) && count( $rule_info ) ) {
					$discount_type   = $rule_info['discount_type'] ?? '';
					$discount_amount = $rule_info['discount_amount'] ?? 0;
					$current_price   = (float) $variation_object->get_price();
					$regular_price   = in_array( $discount_type, [ '1', '2' ] ) ? (float) $variation_object->get_regular_price() : $current_price;
					$new_price       = self::set_new_price_pd( $current_price, $regular_price, $discount_type, $discount_amount );
					if ( $current_price === $new_price ) {
						$variation['viwcuf_price_html'] = $variation['price_html'] ?? sprintf( '<span class="price">%s</span>', $variation_object->get_price_html() );
					} else {
						$variation['viwcuf_price_html'] = sprintf( '<span class="price"><del>%s</del><ins>%s</ins></span>', wc_price( $current_price ), wc_price( $new_price ) );
					}
				}
			}
		}
		wp_send_json( apply_filters( 'viwcuf_get_variation', $variation ) );
	}

	public static function viwcuf_add_to_cart() {
		check_ajax_referer('viwcuf_nonce','viwcuf_nonce');
		if ( empty( $_REQUEST['viwcuf_us_product_id'] ) && empty( $_REQUEST['viwcuf_ob_product_id'] ) ) {
			wp_die();
		}
		$notices = WC()->session->get( 'wc_notices', array() );
		if ( ! empty( $notices['error'] ) ) {
			wp_send_json( array( 'error' => true, 'message' => wc_print_notices( true ) ) );
		}
		if ( ! empty( $notices['success'] ) ) {
			unset( $notices['success'] );
			WC()->session->set( 'wc_notices', $notices );
		}
		WC_AJAX::get_refreshed_fragments();
		die();
	}

	public static function viwcuf_remove_form_cart() {
		check_ajax_referer('viwcuf_nonce','viwcuf_nonce');
		$cart_item_key = isset( $_POST['cart_item_key'] ) ? wc_clean( wp_unslash( $_POST['cart_item_key'] ) ) : '';
		if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
			WC_AJAX::get_refreshed_fragments();
		} else {
			$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : 0;
			$product_type = isset( $_POST['product_type'] ) ? sanitize_text_field( wp_unslash( $_POST['product_type'] ) ) : '';
			if ( $product_id && $product_type ) {
				foreach ( WC()->cart->get_cart() as $key => $item ) {
					if ( isset( $item[$product_type]) && ( $product_id == $item['product_id'] || $product_id == $item['variation_id'] ) ) {
						$cart_item_key = $key;
					}
				}
				if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
					WC_AJAX::get_refreshed_fragments();
				}
			}
			$notices = WC()->session->get( 'wc_notices', array() );
			if ( ! empty( $notices['error'] ) ) {
				wp_send_json( array( 'error' => true, 'message' => wc_print_notices( true ) ) );
			}
			wp_send_json_error();
		}
		die();
	}

	public static function viwcuf_us_add_all_to_cart() {
		check_ajax_referer('viwcuf_nonce','viwcuf_nonce');
		$data   = isset( $_POST['viwcuf_us_alltc'] ) ? wc_clean( $_POST['viwcuf_us_alltc'] ) : array();
		$result = array(
			'status'  => 'error',
			'message' => '',
		);
		if ( empty( $data ) ) {
			$result['message'] = esc_html__( 'Not found data', 'checkout-upsell-funnel-for-woo' );
			wp_send_json( $result );
			wp_die();
		}
		$request = wc_clean($_REQUEST);
		$post    = wc_clean($_POST);
		foreach ( $data as $i => $pd_data ) {
			$arg            = array_column( $pd_data, 'value', 'name' );
			$_REQUEST       = array_merge( $request, $arg );
			$_POST          = array_merge( $post, $arg );
			$product_id     = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $arg['product_id'] ?? 0 ) );
			$adding_to_cart = wc_get_product( $product_id );
			if ( ! $adding_to_cart ) {
				continue;
			}
			$product_type   = $adding_to_cart->get_type();
			$quantity       = empty( $arg['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $arg['quantity'] ) );
			$product_status = get_post_status( $product_id );
			$variation_id   = absint( $arg['variation_id'] ?? 0 );
			$variations     = array();
			foreach ( $arg as $k => $v ) {
				$check = strpos( $k, 'attribute_' );
				if ( $check === 0 ) {
					$variations[ $k ] = $v;
				}
			}
			if ( 'variable' === $product_type || 'variation' === $product_type ) {
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
				if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) && 'publish' === $product_status ) {
					do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				}
			} else {
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
				if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) && 'publish' === $product_status ) {
					do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				}
			}
		}
		$notices = WC()->session->get( 'wc_notices', array() );
		if ( ! empty( $notices['error'] ) ) {
			$result['message'] = wc_print_notices( true );
			wp_send_json( $result );
		}
		if ( ! empty( $notices['success'] ) ) {
			unset( $notices['success'] );
			WC()->session->set( 'wc_notices', $notices );
		}
		WC_AJAX::get_refreshed_fragments();
		die();
	}

	public static function viwcuf_us_set_session() {
		check_ajax_referer('viwcuf_nonce','viwcuf_nonce');
		if ( ! isset( $_POST['time_pause'] ) && ! isset( $_POST['time_end'] ) ) {
			wp_die();
		}
		if ( ! empty( $_POST['time_pause'] ) ) {
			WC()->session->set( 'viwcuf_us_time_pause', 1 );
			wp_send_json( array( 'status' => 'success' ) );
		}
		$error = isset( $_POST['error_message'] ) ? wp_kses_post( wp_unslash( $_POST['error_message'] ) ) : '';
		if ( $error ) {
			wc_add_notice( $error, 'error' );
		}
		if ( ! empty( $_POST['time_end'] ) ) {
			WC()->session->set( 'viwcuf_us_time_end', current_time( 'timestamp' ) );
			WC()->session->set( 'viwcuf_us_time_pause', '' );
			wp_send_json( array( 'status' => 'success' ) );
		}
		wp_die();
	}

	public function viwcuf_woocommerce_before_calculate_totals( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return $cart;
		}
		if ( $cart->is_empty() ) {
			return $cart;
		}
		$count_items = 0;
		$cart_items  = $cart->get_cart();
		foreach ( $cart_items as $key => $cart_item ) {
			if ( isset( $cart_item['viwcuf_ob_product'] ) || isset( $cart_item['viwcuf_us_product'] ) ) {
				continue;
			}
			$count_items ++;
		}
		if ( ! $count_items ) {
			$cart->empty_cart();
		}

		return $cart;
	}

	public function viwcuf_wp_kses_allowed_html( $allowed, $context ) {
		if ( $context === 'post' ) {
			$allowed['a']['data-*'] = true;

			$allowed['select']['name']   = true;
			$allowed['select']['class']  = true;
			$allowed['select']['id']     = true;
			$allowed['select']['data-*'] = true;

			$allowed['option']['data-*'] = true;
			$allowed['option']['value']  = true;

			$allowed['div']['data-*'] = true;
		}

		return $allowed;
	}

	public static function get_rules( $prefix, $shipping = false ) {
		if ( ! $prefix ) {
			return false;
		}
		self::init();
		if ( self::$wc_cart->is_empty() ) {
			return false;
		}
		self::$settings      = new VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		self::$wc_checkout   = WC_Checkout::instance();
		self::$is_user_login = is_user_logged_in();
		global $current_user;
		self::$current_user = $current_user;
		$ids                = $prefix === 'us_' ? array('default'): self::$settings->get_params( $prefix . 'ids' );
		if ( empty( $ids ) || ! is_array( $ids ) ) {
			return false;
		}
		$type_apply = $prefix === 'us_' ? 1 : self::$settings->get_params( $prefix . 'apply_rule' );
		foreach ( $ids as $i => $id ) {
			if ( $type_apply && ! empty( $result ) ) {
				break;
			}
			if ( ! self::$settings->get_current_setting( $prefix . 'active', $i, '' ) ) {
				continue;
			}
			if ( ! self::check_date( $prefix, $id ) ) {
				continue;
			}
			if ( ! self::check_customer( $prefix, $id ) ) {
				continue;
			}
			if ( ! self::check_cart( $prefix, $id, $shipping ) ) {
				continue;
			}
			$result[] = $id;
		}

		return empty( $result ) ? false : ( $prefix === 'us_' ? $result[0] : $result );
	}

	public static function check_date( $prefix, $id ) {
		if ( ! $prefix || ! $id ) {
			return false;
		}
		$days_show = self::$settings->get_current_setting( $prefix . 'days_show', $id, '' );
		if ( $days_show && is_array( $days_show ) && count( $days_show ) && ! in_array( self::$current_day, $days_show ) ) {
			return false;
		}

		return true;
	}

	public static function check_customer( $prefix, $id ) {
		if ( ! $prefix || ! $id ) {
			return false;
		}
		$types    = self::$settings->get_current_setting( $prefix . 'user_rule_type', $id, '' );
		$continue = true;
		if ( $types && is_array( $types ) && count( $types ) ) {
			foreach ( $types as $type ) {
				$prefix_t = $prefix . $type;
				switch ( $type ) {
					case 'user_logged':
						if ( self::$settings->get_current_setting( $prefix_t, $id, '' ) && ! self::$is_user_login ) {
							$continue = false;
						}
						break;
					case 'user_role_include':
						$user_role_include = self::$settings->get_current_setting( $prefix_t, $id, array() );
						if ( $user_role_include && is_array( $user_role_include ) && count( $user_role_include ) && ! count( array_intersect( self::$current_user->roles, $user_role_include ) ) ) {
							$continue = false;
						}
						break;
					case 'user_role_exclude':
						$user_role_exclude = self::$settings->get_current_setting( $prefix_t, $id, array() );
						if ( $user_role_exclude && is_array( $user_role_exclude ) && count( $user_role_exclude ) && count( array_intersect( self::$current_user->roles, $user_role_exclude ) ) ) {
							$continue = false;
						}
						break;
					case 'user_include':
						$user_include = self::$settings->get_current_setting( $prefix_t, $id, array() );
						if ( $user_include && is_array( $user_include ) && count( $user_include ) && ! in_array( self::$current_user->ID, $user_include ) ) {
							$continue = false;
						}
						break;
					case 'user_exclude':
						$user_exclude = self::$settings->get_current_setting( $prefix_t, $id, array() );
						if ( $user_exclude && is_array( $user_exclude ) && count( $user_exclude ) && in_array( self::$current_user->ID, $user_exclude ) ) {
							$continue = false;
						}
						break;
				}
				if ( ! $continue ) {
					break;
				}
			}
		}

		return $continue;
	}

	public static function check_cart( $prefix, $id, $shipping = false ) {
		if ( ! $prefix || ! $id ) {
			return false;
		}
		$types    = self::$settings->get_current_setting( $prefix . 'cart_rule_type', $id, '' );
		$continue = true;
		if ( $types && is_array( $types ) && count( $types ) ) {
			$wc_cart_data     = self::$wc_cart->get_cart();
			foreach ( $types as $type ) {
				$prefix_t = $prefix . $type;
				switch ( $type ) {
					case 'cart_item_include':
						$cart_item_include = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $cart_item_include && is_array( $cart_item_include ) && count( $cart_item_include ) ) {
							$check_condition = false;
							foreach ( $wc_cart_data as $cart_item ) {
								if ( isset( $cart_item['viwcuf_ob_product'] ) || isset( $cart_item['viwcuf_us_product'] ) ) {
									continue;
								}
								$variation_id = $cart_item['variation_id'] ?? 0;
								if ( $variation_id && in_array( $variation_id, $cart_item_include ) ) {
									$check_condition = true;
									break;
								}
								$product_id = $cart_item['product_id'];
								if ( in_array( $product_id, $cart_item_include ) ) {
									$check_condition = true;
									break;
								}
							}
							$continue = $check_condition;
						}
						break;
					case 'cart_item_exclude':
						$cart_item_exclude = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $cart_item_exclude && is_array( $cart_item_exclude ) && count( $cart_item_exclude ) ) {
							$check_condition = true;
							foreach ( $wc_cart_data as $cart_item ) {
								if ( isset( $cart_item['viwcuf_ob_product'] ) || isset( $cart_item['viwcuf_us_product'] ) ) {
									continue;
								}
								$variation_id = $cart_item['variation_id'] ?? 0;
								if ( $variation_id && in_array( $variation_id, $cart_item_exclude ) ) {
									$check_condition = false;
									break;
								}
								$product_id = $cart_item['product_id'];
								if ( in_array( $product_id, $cart_item_exclude ) ) {
									$check_condition = false;
									break;
								}
							}
							$continue = $check_condition;
						}
						break;
					case 'cart_cats_include':
						$cart_cats_include = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $cart_cats_include && is_array( $cart_cats_include ) && count( $cart_cats_include ) ) {
							$check_condition = false;
							foreach ( $wc_cart_data as $cart_item ) {
								if ( isset( $cart_item['viwcuf_ob_product'] ) || isset( $cart_item['viwcuf_us_product'] ) ) {
									continue;
								}
								$product_id = $cart_item['product_id'];
								$cate_ids   = wc_get_product_cat_ids( $product_id );
								if ( ! empty( $cate_ids ) && count( array_intersect( $cate_ids, $cart_cats_include ) ) ) {
									$check_condition = true;
									break;
								}
							}
							$continue = $check_condition;
						}
						break;
					case 'cart_cats_exclude':
						$cart_cats_exclude = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $cart_cats_exclude && is_array( $cart_cats_exclude ) && count( $cart_cats_exclude ) ) {
							$check_condition = true;
							foreach ( $wc_cart_data as $cart_item ) {
								if ( isset( $cart_item['viwcuf_ob_product'] ) || isset( $cart_item['viwcuf_us_product'] ) ) {
									continue;
								}
								$product_id = $cart_item['product_id'];
								$cate_ids   = wc_get_product_cat_ids( $product_id );
								if ( ! empty( $cate_ids ) && count( array_intersect( $cate_ids, $cart_cats_exclude ) ) ) {
									$check_condition = false;
									break;
								}
							}
							$continue = $check_condition;
						}
						break;
					case 'cart_coupon_include':
						$cart_coupon_include = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $cart_coupon_include && is_array( $cart_coupon_include ) && count( $cart_coupon_include ) ) {
							$coupons = self::$wc_cart->get_applied_coupons();
							if ( empty( $coupons ) ) {
								$continue = false;
								break;
							}
							$coupons = array_map( 'strtolower', $coupons );
							if ( ! count( array_intersect( $coupons, $cart_coupon_include ) ) ) {
								$continue = false;
							}
						}
						break;
					case 'cart_coupon_exclude':
						$cart_coupon_exclude = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $cart_coupon_exclude && is_array( $cart_coupon_exclude ) && count( $cart_coupon_exclude ) ) {
							$coupons = self::$wc_cart->get_applied_coupons();
							if ( empty( $coupons ) ) {
								break;
							}
							$coupons = array_map( 'strtolower', $coupons );
							if ( count( array_intersect( $coupons, $cart_coupon_exclude ) ) ) {
								$continue = false;
							}
						}
						break;
					case 'billing_countries_include':
						$billing_country_include = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $billing_country_include && is_array( $billing_country_include ) && count( $billing_country_include ) ) {
							$billing_country = self::$wc_checkout->get_value( 'billing_country' );
							if ( ! in_array( $billing_country, $billing_country_include ) ) {
								$continue = false;
							}
						}
						break;
					case 'billing_country_exclude':
						$billing_country_exclude = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $billing_country_exclude && is_array( $billing_country_exclude ) && count( $billing_country_exclude ) ) {
							$billing_country = self::$wc_checkout->get_value( 'billing_country' );
							if ( in_array( $billing_country, $billing_country_exclude ) ) {
								$continue = false;
							}
						}
						break;
					case 'shipping_country_include':
						$shipping_country_include = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $shipping_country_include && is_array( $shipping_country_include ) && count( $shipping_country_include ) ) {
							$shipping_country = $shipping ? self::$wc_checkout->get_value( 'shipping_country' ) : self::$wc_checkout->get_value( 'billing_country' );
							if ( ! in_array( $shipping_country, $shipping_country_include ) ) {
								$continue = false;
							}
						}
						break;
					case 'shipping_country_exclude':
						$shipping_country_exclude = self::$settings->get_current_setting( $prefix_t, $id, '' );
						if ( $shipping_country_exclude && is_array( $shipping_country_exclude ) && count( $shipping_country_exclude ) ) {
							$shipping_country = $shipping ? self::$wc_checkout->get_value( 'shipping_country' ) : self::$wc_checkout->get_value( 'billing_country' );
							if ( in_array( $shipping_country, $shipping_country_exclude ) ) {
								$continue = false;
							}
						}
						break;
				}
				if ( ! $continue ) {
					break;
				}
			}
		}

		return $continue;
	}

	public static function change_price_3rd( $price, $filter = null ) {
		if ( ! $price ) {
			return $price;
		}
		$filter = $filter ?? apply_filters( 'viwcuf_get_change_currency', 'wmc_change_3rd_plugin_price' );

		return $filter ? apply_filters( $filter, $price ) : $price;
	}


	public static function get_cart_item( $product_id, $type = '', $rule_id = '' ) {
		self::init();
		if ( ! $type || ! $product_id || self::$wc_cart->is_empty() ) {
			return 0;
		}
		$cart_item = array();
		foreach ( self::$wc_cart->get_cart() as $k => $item ) {
			if ( empty( $item[ $type ] ) ) {
				continue;
			}
			if ( $rule_id && ! empty( $item[ $type ]['rule_id'] ) && $rule_id != $item[ $type ]['rule_id'] ) {
				continue;
			}
			if ( ! $rule_id && ! empty( $item[ $type ]['product_id'] ) && $product_id != $item[ $type ]['product_id'] ) {
				continue;
			}
			$item_product_id   = $item['product_id'] ?? 0;
			$item_variation_id = $item['variation_id'] ?? 0;
			if ( $product_id == $item_product_id || $item_variation_id == $product_id ) {
				$cart_item['cart_item_key'] = $k;
				$cart_item['product_id']    = $item_variation_id ?: $product_id;
				if ( $item_variation_id ) {
					$cart_item['variation'] = $item['variation'];
				}
				break;
			}
		}

		return $cart_item;
	}

	public static function get_pd_qty_in_cart( $product_id, $type = '', $rule_id = '' ) {
		self::init();
		if ( self::$wc_cart->is_empty() ) {
			return 0;
		}
		$in_cart = 0;
		foreach ( self::$wc_cart->get_cart() as $k => $cart_item ) {
			if ( $type ) {
				if ( empty( $cart_item[ $type ] ) ) {
					continue;
				}
				if ( $rule_id && ! empty( $cart_item[ $type ]['rule_id'] ) && $rule_id != $cart_item[ $type ]['rule_id'] ) {
					continue;
				}
				if ( ! $rule_id && ! empty( $cart_item[ $type ]['product_id'] ) && $product_id != $cart_item[ $type ]['product_id'] ) {
					continue;
				}
			}
			$item_variation_id = $cart_item['variation_id'] ?? 0;
			$item_product_id   = $cart_item['product_id'] ?? 0;
			if ( $product_id == $item_product_id || $item_variation_id == $product_id ) {
				$in_cart += $cart_item['quantity'] ?? 0;
			}
		}

		return $in_cart;
	}

	public static function product_price_html( $product, $discount_type, $discount_amount ) {
		if ( $product->is_type( 'variable' ) ) {
			if ( class_exists( 'VIREDIS_Frontend_Product' ) && ! empty( VIREDIS_Frontend_Product::$cache['is_product_list'] ) ) {
				$product->get_price_html();
				$variation_prices = VIREDIS_Frontend_Product_Pricing_Store::$cache['variation_prices'][ $product->get_id() ]['new'];
				if ( ! empty( $variation_prices ) ) {
					$min = current( $variation_prices );
					$max = end( $variation_prices );
				}
			}
			$min                      = (float) ( $min ?? $product->get_variation_price( 'min' ) );
			$max                      = (float) ( $max ?? $product->get_variation_price( 'max' ) );
			$min_regular = (float) $product->get_variation_regular_price( 'min' );
			$max_regular = (float) $product->get_variation_regular_price( 'max' );
			switch ( $discount_type ) {
				case '1':
					//Percentage(%) regular price
					$min_new = $min_regular && $discount_amount ? $min_regular * ( 100 - $discount_amount ) / 100 : $min_regular;
					$max_new = $max_regular && $discount_amount ? $max_regular * ( 100 - $discount_amount ) / 100 : $max_regular;
					break;
				case '2':
					//Fixed($) regular price
					$discount_amount1 = self::change_price_3rd( $discount_amount );
					$min_new          = $min_regular > $discount_amount1 ? $min_regular - $discount_amount1 : 0;
					$max_new          = $max_regular > $discount_amount1 ? $max_regular - $discount_amount1 : 0;
					break;
				case '3':
					//Percentage(%) current price
					$min_new = $min && $discount_amount ? $min * ( 100 - $discount_amount ) / 100 : $min;
					$max_new = $max && $discount_amount ? $max * ( 100 - $discount_amount ) / 100 : $max;
					break;
				case '4':
					//Fixed($) current price
					$discount_amount1 = self::change_price_3rd( $discount_amount );
					$min_new          = $min > $discount_amount1 ? $min - $discount_amount1 : 0;
					$max_new          = $max > $discount_amount1 ? $max - $discount_amount1 : 0;
					break;
				default:
					$min_new = $min;
					$max_new = $max;
			}
			if ( $min_new == $max_new ) {
				if ( $min_new < $min && $min === $max && $min_regular === $max_regular ) {
					$price_html = wc_format_sale_price( wc_price( $max ), wc_price( $min_new ) );
				} else {
					$price_html = wc_price( $min_new );
				}
			} else {
				$price_html = wc_format_price_range( $min_new, $max_new );
			}
		} else {
			$product_current_price = $product->get_price();
			if ( class_exists( 'VIREDIS_Frontend_Product' ) && ! empty( VIREDIS_Frontend_Product::$cache['is_product_list'] ) ) {
				$product->get_price_html();
				$product_current_price = VIREDIS_Frontend_Product_Pricing_Store::$cache['prices'][ $product->get_id() ][ $product_current_price ] ?? $product_current_price;
			}
			$product_current_price = (float) $product_current_price;
			$product_regular_price = in_array( $discount_type, [ '1', '2' ] ) ? (float) $product->get_regular_price() : $product_current_price;
			$product_new_price     = self::set_new_price_pd( $product_current_price, $product_regular_price, $discount_type, $discount_amount );
			if ( $product_current_price === $product_new_price ) {
				$price_html = wp_kses_post( $product->get_price_html() );
			} elseif($product_new_price > $product_regular_price && $product_new_price > $product_current_price) {
			    $price_html =wc_get_price_to_display( $product, array( 'price' => $product_new_price ) );
			} else {
				$price_html = wc_format_sale_price(
					wc_get_price_to_display( $product, array( 'price' => $product_current_price > $product_new_price ? $product_current_price : $product_regular_price) ),
					wc_get_price_to_display( $product, array( 'price' => $product_new_price ) )
				);
			}
		}
		if ( $price_html ) {
			?>
            <span class="price"><?php echo wp_kses_post( $price_html ); ?></span>
			<?php
		}
	}

	public static function set_new_price_pd( $product_current_price, $product_regular_price, $discount_type, $discount_amount ) {
		switch ( $discount_type ) {
			case '1':
				//Percentage(%) regular price
				$product_new_price = $product_regular_price && $discount_amount ? $product_regular_price * ( 100 - $discount_amount ) / 100 : $product_regular_price;
				break;
			case '2':
				//Fixed($) regular price
				$discount_amount   = self::change_price_3rd( $discount_amount );
				$product_new_price = $product_regular_price > $discount_amount ? $product_regular_price - $discount_amount : 0;
				break;
			case '3':
				//Percentage(%) sale price
				$product_new_price = $product_current_price && $discount_amount ? $product_current_price * ( 100 - $discount_amount ) / 100 : $product_current_price;
				break;
			case '4':
				//Fixed($) sale price
				$discount_amount   = self::change_price_3rd( $discount_amount );
				$product_new_price = $product_current_price > $discount_amount ? $product_current_price - $discount_amount : 0;
				break;
			default:
				$product_new_price = $product_current_price;
		}

		return $product_new_price;
	}
}