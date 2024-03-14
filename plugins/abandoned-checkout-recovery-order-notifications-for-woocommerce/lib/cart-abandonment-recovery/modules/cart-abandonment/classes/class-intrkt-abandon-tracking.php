<?php
/**
 * Cart Abandonment
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cart abandonment tracking class.
 */
class INTRKT_ABANDON_Tracking {



	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *  Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {

		$this->intrkt_define_cart_abandonment_constants();
		$integration_status = intrkt_load()->utils->intrkt_get_account_integration_status();
		$oauth_status       = intrkt_load()->utils->intrkt_get_account_connection_status();
		if ( 'false' === $integration_status || 'false' === $oauth_status ) {
			return;
		}
		// Add script to track the cart abandonment.
		add_action( 'woocommerce_after_checkout_form', array( $this, 'intrkt_cart_abandonment_tracking_script' ) );

		// Store user details from the current checkout page.
		add_action( 'wp_ajax_intrkt_save_cart_abandonment_data', array( $this, 'intrkt_save_cart_abandonment_data' ) );
		add_action( 'wp_ajax_nopriv_intrkt_save_cart_abandonment_data', array( $this, 'intrkt_save_cart_abandonment_data' ) );

		// Delete the stored cart abandonment data once order gets created.
		add_action( 'woocommerce_new_order', array( $this, 'intrkt_delete_cart_abandonment_data' ) );
		add_action( 'woocommerce_thankyou', array( $this, 'intrkt_delete_cart_abandonment_data' ) );
		// add_action( 'woocommerce_order_status_changed', array( $this, 'intrkt_delete_update_order_status' ), 999, 3 ); Deprecated

		// Adding filter to restore the data if recreating abandonment order.
		add_filter( 'wp', array( $this, 'intrkt_restore_cart_abandonment_data' ), 10 );
		/**
		 * Imp.
		 */
		add_action( 'intrkt_abandon_update_order_status_action', array( $this, 'intrkt_check_abandon_event' ) );

	}

		/**
		 *  Initialize all the constants
		 */
	public function intrkt_define_cart_abandonment_constants() {
		define( 'INTRKT_ABANDON_TRACKING_DIR', INTRKT_ABANDON_DIR . 'modules/cart-abandonment/' );
		define( 'INTRKT_ABANDON_TRACKING_URL', INTRKT_ABANDON_URL . 'modules/cart-abandonment/' );
		define( 'INTRKT_CART_ABANDONED_ORDER', 'abandoned' );
		define( 'INTRKT_CART_COMPLETED_ORDER', 'completed' );
		define( 'INTRKT_CART_LOST_ORDER', 'lost' );
		define( 'INTRKT_CART_NORMAL_ORDER', 'normal' );
		define( 'INTRKT_CART_FAILED_ORDER', 'failed' );
		define( 'INTRKT_ACTION_ABANDONED_CARTS', 'abandoned_carts' );
		define( 'INTRKT_ACTION_RECOVERED_CARTS', 'recovered_carts' );
		define( 'INTRKT_ACTION_LOST_CARTS', 'lost_carts' );
		define( 'INTRKT_ACTION_SETTINGS', 'settings' );
		define( 'INTRKT_ACTION_REPORTS', 'reports' );
		define( 'INTRKT_SUB_ACTION_REPORTS_VIEW', 'view' );
		define( 'INTRKT_SUB_ACTION_REPORTS_RESCHEDULE', 'reschedule' );
		define( 'INTRKT_DEFAULT_CUT_OFF_TIME', 15 );
		define( 'INTRKT_DEFAULT_COUPON_AMOUNT', 10 );
		define( 'INTRKT_CA_DATETIME_FORMAT', 'Y-m-d H:i:s' );
		define( 'INTRKT_CA_COUPON_DESCRIPTION', __( 'This coupon is for abandoned cart email templates.', 'cart-abandonment-recovery' ) );
		define( 'INTRKT_CA_COUPON_GENERATED_BY', 'cart-abandonment-recovery' );
	}

	/**
	 * Update the Order status.
	 *
	 * @Deprecated.
	 *
	 * @param integer $order_id order id.
	 * @param string  $old_order_status old order status.
	 * @param string  $new_order_status new order status.
	 */
	public function intrkt_delete_update_order_status( $order_id, $old_order_status, $new_order_status ) {
		if ( $order_id ) {
			$order = wc_get_order( $order_id );

			$order_email_phone = ! empty( $order->get_billing_email() ) ? $order->get_billing_email() : $order->get_billing_phone();
			$captured_data     = $this->intrkt_get_tracked_data_without_status( $order_email_phone );

			if ( $captured_data && is_object( $captured_data ) ) {
				$capture_status = $captured_data->order_status;
				global $wpdb;
				$cart_abandonment_table = $wpdb->prefix . INTRKT_ABANDON_CART_ABANDONMENT_TABLE;
				if ( ( INTRKT_CART_NORMAL_ORDER === $capture_status ) ) {
					$wpdb->delete( $cart_abandonment_table, array( 'session_id' => sanitize_key( $captured_data->session_id ) ) ); // phpcs:ignore
				}

				if ( ( INTRKT_CART_ABANDONED_ORDER === $capture_status || INTRKT_CART_LOST_ORDER === $capture_status ) ) {
					if ( WC()->session ) {
						WC()->session->__unset( 'intrkt_session_id' );
					}
				}
			}
		}

	}

	/**
	 * Restore cart abandonment data on checkout page.
	 *
	 * @param  array $fields checkout fields values.
	 * @return array field values
	 */
	public function intrkt_restore_cart_abandonment_data( $fields = array() ) {
		global $woocommerce;
		$result = array();
		// Restore only of user is not logged in.
		$intrkt_ac_token = filter_input( INPUT_GET, 'intrkt_ac_token', FILTER_SANITIZE_STRING );
		if ( $this->intrkt_is_valid_token( $intrkt_ac_token ) ) {
			// Check if `INTRKT_restore_token` exists to restore cart data.
			$token_data = $this->intrkt_decode_token( $intrkt_ac_token );
			if ( is_array( $token_data ) && isset( $token_data['intrkt_session_id'] ) ) {
				$result = INTRKT_ABANDON_Helper::get_instance()->intrkt_get_checkout_details( $token_data['intrkt_session_id'] );
				if ( isset( $result ) && INTRKT_CART_ABANDONED_ORDER === $result->order_status || INTRKT_CART_LOST_ORDER === $result->order_status ) {
					WC()->session->set( 'intrkt_session_id', $token_data['intrkt_session_id'] );
				}
			}

			if ( $result ) {
				$cart_content = unserialize( $result->cart_contents );

				if ( $cart_content ) {
					$woocommerce->cart->empty_cart();
					wc_clear_notices();
					foreach ( $cart_content as $cart_item ) {

						$cart_item_data = array();
						$variation_data = array();
						$id             = $cart_item['product_id'];
						$qty            = $cart_item['quantity'];

						// Skip bundled products when added main product.
						if ( isset( $cart_item['bundled_by'] ) ) {
							continue;
						}

						if ( isset( $cart_item['variation'] ) ) {
							foreach ( $cart_item['variation']  as $key => $value ) {
								$variation_data[ $key ] = $value;
							}
						}

						$cart_item_data = $cart_item;

						$woocommerce->cart->add_to_cart( $id, $qty, $cart_item['variation_id'], $variation_data, $cart_item_data );
					}

					if ( isset( $token_data['intrkt_coupon_code'] ) && ! $woocommerce->cart->applied_coupons ) {
						$coupon_codes = unserialize( $token_data['intrkt_coupon_code'] );
						if ( is_array( $coupon_codes ) ) {
							foreach ( $coupon_codes as $code ) {
								$woocommerce->cart->add_discount( $code );
							}
						}
					}
				}
				$other_fields = unserialize( $result->other_fields );

				$country = '';
				$city    = '';
				if ( isset( $other_fields['INTRKT_location'] ) ) {
					$parts = explode( ',', $other_fields['INTRKT_location'] );
					if ( count( $parts ) > 1 ) {
						$country = $parts[0];
						$city    = trim( $parts[1] );
					} else {
						$country = $parts[0];
						$city    = '';
					}
				}

				foreach ( $other_fields as $key => $value ) {
					$key           = str_replace( 'INTRKT_', '', $key );
					$_POST[ $key ] = sanitize_text_field( $value );
				}
				$_POST['billing_first_name'] = sanitize_text_field( $other_fields['intrkt_first_name'] );
				$_POST['billing_last_name']  = sanitize_text_field( $other_fields['intrkt_last_name'] );
				$_POST['billing_phone']      = sanitize_text_field( $other_fields['intrkt_phone_number'] );
				$_POST['billing_email']      = sanitize_email( $result->email );
				$_POST['billing_city']       = sanitize_text_field( $city );
				$_POST['billing_country']    = sanitize_text_field( $country );

			}
		}
		return $fields;
	}

	/**
	 * Load cart abandonment tracking script.
	 *
	 * @return void
	 */
	public function intrkt_cart_abandonment_tracking_script() {

		$intrkt_ca_ignore_users = get_option( 'intrkt_ca_ignore_users' );
		$current_user           = wp_get_current_user();
		$roles                  = $current_user->roles;
		$role                   = array_shift( $roles );
		if ( ! empty( $intrkt_ca_ignore_users ) ) {
			foreach ( $intrkt_ca_ignore_users as $user ) {
				$user = strtolower( $user );
				$role = preg_replace( '/_/', ' ', $role );
				if ( $role === $user ) {
					return;
				}
			}
		}

		global $post;
		wp_enqueue_script(
			'intrkt-abandon-tracking',
			INTRKT_ABANDON_TRACKING_URL . 'assets/js/intrkt-cart-abandonment-tracking.js',
			array( 'jquery' ),
			INTRKT_ABANDON_VER,
			true
		);

		$vars = array(
			'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
			'_nonce'                    => wp_create_nonce( 'intrkt_save_cart_abandonment_data' ),
			'_post_id'                  => get_the_ID(),
			'_show_gdpr_message'        => ( intrkt_abandon()->utils->intrkt_is_gdpr_enabled() && ! isset( $_COOKIE['intrkt_abandon_skip_track_data'] ) ),
			'_gdpr_message'             => get_option( 'intrkt_gdpr_message' ),
			'_gdpr_nothanks_msg'        => __( 'No Thanks', 'cart-abandonment-recovery' ),
			'_gdpr_after_no_thanks_msg' => __( 'You won\'t receive further emails from us, thank you!', 'cart-abandonment-recovery' ),
			'enable_ca_tracking'        => true,
		);

		wp_localize_script( 'intrkt-abandon-tracking', 'intrkt_ca_vars', $vars );

	}

	/**
	 * Validate the token before use.
	 *
	 * @param  string $token token form the url.
	 *
	 * @return bool
	 */
	public function intrkt_is_valid_token( $token ) {
		$is_valid   = false;
		$token_data = $this->intrkt_decode_token( $token );
		if ( is_array( $token_data ) && array_key_exists( 'intrkt_session_id', $token_data ) ) {
			$result = INTRKT_ABANDON_Helper::get_instance()->intrkt_get_checkout_details( $token_data['intrkt_session_id'] );
			if ( isset( $result ) ) {
				$is_valid = true;
			}
		}
		return $is_valid;
	}
	/**
	 * Check abandon cart is present and trigger API calls.
	 *
	 * @since 1.0.0
	 */
	public function intrkt_check_abandon_event() {
		$integration_status = intrkt_load()->utils->intrkt_get_account_integration_status();
		$oauth_status       = intrkt_load()->utils->intrkt_get_account_connection_status();
		if ( 'false' === $integration_status || 'false' === $oauth_status ) {
			return;
		}
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . INTRKT_ABANDON_CART_ABANDONMENT_TABLE;
		$minutes                = intrkt_abandon()->utils->intrkt_get_cart_abandonment_tracking_cut_off_time();
		/**
		 * Delete abandoned cart orders if empty.
		 */
		// $this->intrkt_delete_empty_abandoned_order();

		$wp_current_datetime = current_time( INTRKT_CA_DATETIME_FORMAT );
		$abandoned_ids       = $wpdb->get_results( // phpcs:ignore
			$wpdb->prepare(
				"SELECT `session_id` FROM {$cart_abandonment_table} WHERE `order_status` = %s AND ADDDATE( `time`, INTERVAL %d MINUTE) <= %s",
				INTRKT_CART_NORMAL_ORDER,
				$minutes,
				$wp_current_datetime
			),
			ARRAY_A
		);
		if ( ! is_array( $abandoned_ids ) ) {
			return;
		}
		foreach ( $abandoned_ids as $session_id ) {
			if ( isset( $session_id['session_id'] ) ) {
				$current_session_id = $session_id['session_id'];
				$details            = INTRKT_ABANDON_Helper::get_instance()->intrkt_get_checkout_details( $current_session_id );
				$user_details       = (object) unserialize( $details->other_fields );
				$checkout_details   = INTRKT_ABANDON_Helper::get_instance()->intrkt_get_checkout_details( $details->session_id );
				$cart_abandonment   = INTRKT_ABANDON_Helper::get_instance();
				$token_data         = array(
					'intrkt_session_id'  => $details->session_id,
					'intrkt_coupon_code' => $checkout_details->coupon_code,
				);
				$checkout_url       = $cart_abandonment->intrkt_get_checkout_url( $details->checkout_id, $token_data );
				$cart_content       = unserialize( $checkout_details->cart_contents );
				$other_fields       = unserialize( $checkout_details->other_fields );
				$quantity           = 0;
				$discount           = 0;
				$product_1_name     = '';
				if ( ! empty( $cart_content ) && is_array( $cart_content ) ) {
					foreach ( $cart_content as $key => $cart_item ) {
						$quantity += $cart_item['quantity'];
						$discount  = number_format_i18n( $discount + ( $cart_item['line_subtotal'] - $cart_item['line_total'] ), 2 );
						if ( array_key_first( $cart_content ) === $key ) {
							$product_1_image = wp_get_attachment_image_src( get_post_thumbnail_id( $cart_item['product_id'] ) );
							if ( $product_1_image ) {
								$product_1_image = $product_1_image[0];
							}
						}
						if ( empty( $product_1_name ) ) {
							$product        = wc_get_product( $cart_item['product_id'] );
							$product_1_name = $product->get_title();
						}
					}
				}
				$checkout_datetime      = $checkout_details->time;
				$order_date_utc         = get_gmt_from_date( $checkout_datetime, 'Y-m-d\TH:i:s.000\Z' );
				$phone_number           = $other_fields['intrkt_phone_number'];
				$total                  = $checkout_details->cart_total;
				$intrkt_shipping_city   = $other_fields['intrkt_shipping_city'];
				$cart_total             = floatval( $checkout_details->cart_total );
				$get_wc_currency        = get_option( 'woocommerce_currency', true );
				$traits                 = array(
					'order_date'             => $order_date_utc,
					'total'                  => $cart_total,
					'discount'               => $discount,
					'abandoned_checkout_url' => $checkout_url,
					'item_count'             => $quantity,
					'currency'               => $get_wc_currency,
					'city'                   => $intrkt_shipping_city,
					'platform'               => 'woocommerce',
					'product1_image'         => $product_1_image,
					'product1_name'          => $product_1_name,
				);
				$name                   = $other_fields['intrkt_first_name'] . ' ' . $other_fields['intrkt_last_name'];
				$user_traits            = array(
					'name' => $name,
				);
				$country_code_selection = intrkt_load()->utils->intrkt_intrkt_get_country_code_selection();
				$intrkt_track_event     = 'Abandoned checkout';
				$data                   = array();
				$user_data              = array();
				if ( INTRKT_WITHOUT_COUNTRY_CODE === $country_code_selection ) {
					$country_code = intrkt_load()->utils->intrkt_get_country_code();
					$data         = array(
						'phoneNumber' => $phone_number,
						'countryCode' => $country_code,
						'event'       => $intrkt_track_event,
						'traits'      => $traits,
					);
					$user_data    = array(
						'phoneNumber' => $phone_number,
						'countryCode' => $country_code,
						'traits'      => $user_traits,
					);
				} else {
					$data      = array(
						'fullPhoneNumber' => $phone_number,
						'event'           => $intrkt_track_event,
						'traits'          => $traits,
					);
					$user_data = array(
						'fullPhoneNumber' => $phone_number,
						'traits'          => $user_traits,
					);
				}
				update_option( 'order_data_' . $current_session_id, $traits );
				$user_call_response = $this->intrkt_abandon_cart_user_api_call( $user_data );
				if ( true === $user_call_response ) {
					$event_call_response = $this->intrkt_send_abandon_event_api_call( $data, $intrkt_track_event );
					if ( true === $event_call_response ) {
						$wpdb->update( // phpcs:ignore
							$cart_abandonment_table,
							array(
								'order_status' => INTRKT_CART_ABANDONED_ORDER,
								'is_notified'  => 1,
							),
							array( 'session_id' => $current_session_id )
						);
					}
				}
			}
		}
	}
	/**
	 * Send abandon order API call.
	 *
	 * @param object $data Abandon cart data.
	 * @param string $interakt_status Interakt notification status.
	 *
	 * @return boolean API call result.
	 */
	public function intrkt_send_abandon_event_api_call( $data, $interakt_status ) {
		if ( empty( $interakt_status ) || empty( $data ) ) {
			return;
		}
		$endpoint     = intrkt_load()->utils->intrkt_get_public_api_endpoint();
		$bearer_token = intrkt_load()->utils->intrkt_get_public_api_token();
		if ( empty( $endpoint ) || empty( $bearer_token ) ) {
			return;
		}
		$body    = wp_json_encode( $data );
		$options = array(
			'body'        => $body,
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => "Bearer $bearer_token",
			),
			'data_format' => 'body',
		);
		$options = apply_filters( 'intrkt_abandon_event_api_call_data', $options, $endpoint, $data, $interakt_status );
		do_action( 'intrkt_abandon_event_api_call_before', $data, $interakt_status, $options );
		$response        = wp_remote_post( $endpoint, $options );
		$response_code   = wp_remote_retrieve_response_code( $response );
		$response_body   = wp_remote_retrieve_body( $response );
		$response_result = intrkt_load()->utils->intrkt_remote_retrieve_result( $response_body );
		do_action( 'intrkt_abandon_event_api_call_after', $order, $interakt_status, $options, $response );
		$response_result = apply_filters( 'intrkt_abandon_event_api_call_remote_result', $response_result, $endpoint, $order, $interakt_status );
		if ( true === $response_result ) {
			intrkt_load()->utils->intrkt_add_to_log( 'Success: Abandon Event track API call ' . $response_code, $response_body );
			return true;
		}
		intrkt_load()->utils->intrkt_add_to_log( 'Error: AbandonEvent track API call ' . $response_code, $response_body );
		return false;
	}
	/**
	 * Abandon event user API call.
	 *
	 * @param Array $data WooCommerce cart data.
	 */
	public function intrkt_abandon_cart_user_api_call( $data ) {
		if ( empty( $data ) ) {
			return;
		}
		$endpoint     = intrkt_load()->utils->intrkt_get_public_api_endpoint( 'user' );
		$bearer_token = intrkt_load()->utils->intrkt_get_public_api_token();
		if ( empty( $endpoint ) || empty( $bearer_token ) ) {
			return;
		}
		$body    = wp_json_encode( $data );
		$options = array(
			'body'        => $body,
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => "Bearer $bearer_token",
			),
			'data_format' => 'body',
		);
		$options = apply_filters( 'intrkt_abandon_user_api_call_data', $options, $endpoint, $data );
		do_action( 'intrkt_abandon_user_api_call_before', $data, $options );
		$response        = wp_remote_post( $endpoint, $options );
		$response_code   = wp_remote_retrieve_response_code( $response );
		$response_body   = wp_remote_retrieve_body( $response );
		$response_result = intrkt_load()->utils->intrkt_remote_retrieve_result( $response_body );
		do_action( 'intrkt_abandon_user_api_call_after', $order, $interakt_status, $options, $response );
		$response_result = apply_filters( 'intrkt_abandon_user_api_call_remote_result', $response_result, $endpoint, $order, $interakt_status );
		if ( true === $response_result ) {
			intrkt_load()->utils->intrkt_add_to_log( 'Success: User track API call ' . $response_code, $response_body );
			return true;
		}
		intrkt_load()->utils->intrkt_add_to_log( 'Error: User track API call ' . $response_code, $response_body );
		return false;
	}
	/**
	 * Send order email to admin on cart abandoned
	 *
	 * Deprecated.
	 *
	 * @param array $order_data Order data.
	 */
	public function intrkt_send_order_email( $order_data ) {
		if ( empty( $order_data ) ) {
			return;
		}
		$to      = get_option( 'admin_email' );
		$subject = 'Abandoned Checkout';
		$body    = '';
		foreach ( $order_data as $key => $data ) {
			$body .= '<br>' . ( ! is_array( $data ) ) ? $data : '';
			if ( is_array( $data ) ) {
				foreach ( $data as $key => $value ) {
					$body .= '<br>' . $value;
				}
			}
		}
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		wp_mail( $to, $subject, $body, $headers, array( '' ) );
	}

	/**
	 * Sanitize post array.
	 *
	 * @return array
	 */
	public function intrkt_sanitize_post_data() {

		$input_post_values = array(
			'intrkt_billing_company'             => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_email'                       => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_EMAIL,
			),
			'intrkt_billing_address_1'           => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_billing_address_2'           => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_billing_state'               => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_billing_postcode'            => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_first_name'         => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_shipping_last_name' => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_company'            => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_country'            => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_address_1'          => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_address_2'          => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_city'               => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_state'              => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_shipping_postcode'           => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_order_comments'              => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_name'                        => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_surname'                     => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_phone'                       => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_country'                     => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_city'                        => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'intrkt_post_id'                     => array(
				'default'  => 0,
				'sanitize' => FILTER_SANITIZE_NUMBER_INT,
			),
		);

		$sanitized_post = array();
		foreach ( $input_post_values as $key => $input_post_value ) {

			if ( isset( $_POST[ $key ] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
				$sanitized_post[ $key ] = filter_input( INPUT_POST, $key, $input_post_value['sanitize'] );
			} else {
				$sanitized_post[ $key ] = $input_post_value['default'];
			}
		}
		return $sanitized_post;

	}

	/**
	 * Save cart abandonment tracking and schedule new event.
	 *
	 * @since 1.0.0
	 */
	public function intrkt_save_cart_abandonment_data() {
		check_ajax_referer( 'intrkt_save_cart_abandonment_data', 'security' );
		$post_data = $this->intrkt_sanitize_post_data();
		if ( isset( $post_data['intrkt_phone'] ) ) {
			$user_email = sanitize_email( $post_data['intrkt_email'] );
			global $wpdb;
			$cart_abandonment_table = $wpdb->prefix . INTRKT_ABANDON_CART_ABANDONMENT_TABLE;

			// Verify if email is already exists.
			$session_id               = WC()->session->get( 'intrkt_session_id' );
			$session_checkout_details = null;
			if ( isset( $session_id ) ) {
				$session_checkout_details = INTRKT_ABANDON_Helper::get_instance()->intrkt_get_checkout_details( $session_id );
			} else {
				$session_checkout_details = $this->intrkt_get_checkout_details_by_email( $user_email );
				if ( $session_checkout_details ) {
					$session_id = $session_checkout_details->session_id;
					WC()->session->set( 'intrkt_session_id', $session_id );
				} else {
					$session_id = md5( uniqid( wp_rand(), true ) );
				}
			}

			$checkout_details = $this->intrkt_prepare_abandonment_data( $post_data );

			if ( isset( $session_checkout_details ) && INTRKT_CART_COMPLETED_ORDER === $session_checkout_details->order_status ) {
				WC()->session->__unset( 'intrkt_session_id' );
				$session_id = md5( uniqid( wp_rand(), true ) );
			}

			if ( isset( $checkout_details['cart_total'] ) && $checkout_details['cart_total'] > 0 ) {

				if ( ( ! is_null( $session_id ) ) && ! is_null( $session_checkout_details ) ) {

					// Updating row in the Database where users Session id = same as prevously saved in Session.
					$wpdb->update( // phpcs:ignore
						$cart_abandonment_table,
						$checkout_details,
						array( 'session_id' => $session_id )
					);

				} else {

					$checkout_details['session_id'] = sanitize_text_field( $session_id );
					// Inserting row into Database.
					$wpdb->insert( // phpcs:ignore
						$cart_abandonment_table,
						$checkout_details
					);

					// Storing session_id in WooCommerce session.
					WC()->session->set( 'intrkt_session_id', $session_id );

				}
			} else {
				$wpdb->delete( $cart_abandonment_table, array( 'session_id' => sanitize_key( $session_id ) ) ); // phpcs:ignore
			}

			wp_send_json_success();
		}
	}

	/**
	 * Prepare cart data to save for abandonment.
	 *
	 * @param array $post_data post data.
	 * @return array
	 */
	public function intrkt_prepare_abandonment_data( $post_data = array() ) {

		if ( ! function_exists( 'WC' ) ) {
			return;
		}
		if ( empty( $post_data ) ) {
			return;
		}
		// Retrieving cart total value and currency.
		$cart_total = WC()->cart->total;

		$payment_gateway = WC()->session->chosen_payment_method;

		// Retrieving cart products and their quantities.
		$products     = WC()->cart->get_cart();
		$cart_coupons = WC()->cart->get_applied_coupons();
		$current_time = current_time( INTRKT_CA_DATETIME_FORMAT );
		$other_fields = array(
			'intrkt_billing_company'             => $post_data['intrkt_billing_company'],
			'intrkt_billing_address_1'           => $post_data['intrkt_billing_address_1'],
			'intrkt_billing_address_2'           => $post_data['intrkt_billing_address_2'],
			'intrkt_billing_state'               => $post_data['intrkt_billing_state'],
			'intrkt_billing_postcode'            => $post_data['intrkt_billing_postcode'],
			'intrkt_shipping_first_name'         => $post_data['intrkt_shipping_first_name'],
			'intrkt_shipping_shipping_last_name' => $post_data['intrkt_shipping_shipping_last_name'],
			'intrkt_shipping_company'            => $post_data['intrkt_shipping_company'],
			'intrkt_shipping_country'            => $post_data['intrkt_shipping_country'],
			'intrkt_shipping_address_1'          => $post_data['intrkt_shipping_address_1'],
			'intrkt_shipping_address_2'          => $post_data['intrkt_shipping_address_2'],
			'intrkt_shipping_city'               => $post_data['intrkt_shipping_city'],
			'intrkt_shipping_state'              => $post_data['intrkt_shipping_state'],
			'intrkt_shipping_postcode'           => $post_data['intrkt_shipping_postcode'],
			'intrkt_order_comments'              => $post_data['intrkt_order_comments'],
			'intrkt_first_name'                  => $post_data['intrkt_name'],
			'intrkt_last_name'                   => $post_data['intrkt_surname'],
			'intrkt_phone_number'                => $post_data['intrkt_phone'],
			'intrkt_location'                    => $post_data['intrkt_country'] . ', ' . $post_data['intrkt_city'],
			'intrkt_email'                       => $post_data['intrkt_email'],
		);

		$checkout_details = array(
			'email'         => ! empty( $post_data['intrkt_email'] ) ? $post_data['intrkt_email'] : $post_data['intrkt_phone'],
			'cart_contents' => serialize( $products ),
			'cart_total'    => sanitize_text_field( $cart_total ),
			'time'          => sanitize_text_field( $current_time ),
			'other_fields'  => serialize( $other_fields ),
			'checkout_id'   => $post_data['intrkt_post_id'],
			'coupon_code'   => serialize( $cart_coupons ),
		);
		return $checkout_details;
	}

	/**
	 * Deletes cart abandonment tracking and scheduled event.
	 *
	 * @param int $order_id Order ID.
	 * @since 1.0.0
	 */
	public function intrkt_delete_cart_abandonment_data( $order_id ) {
		$order        = wc_get_order( $order_id );
		$order_status = 'wc-' . $order->get_status();

		global $wpdb;
		$cart_abandonment_table    = $wpdb->prefix . INTRKT_ABANDON_CART_ABANDONMENT_TABLE;
		$intrkt_order_status_table = $wpdb->prefix . INTRKT_ORDER_STATUS_TABLE;

		$get_intrkt_abandon_checkout_values = $wpdb->get_var( "SELECT order_status FROM $intrkt_order_status_table WHERE intrkt_status = 'intrkt_abandon_checkout'" );
		$abandon_checkout_values_array      = explode( ',', $get_intrkt_abandon_checkout_values );

		if ( ! in_array( $order_status, $abandon_checkout_values_array ) ) {
			if ( isset( WC()->session ) ) {
				$session_id = WC()->session->get( 'intrkt_session_id' );

				if ( isset( $session_id ) ) {
					$checkout_details = INTRKT_ABANDON_Helper::get_instance()->intrkt_get_checkout_details( $session_id );
					$wpdb->delete( $cart_abandonment_table, array( 'session_id' => sanitize_key( $session_id ) ) );
				}
				if ( WC()->session ) {
					WC()->session->__unset( 'intrkt_session_id' );
				}
			}
		}
	}

	/**
	 * Get the checkout details for the user.
	 *
	 * @param string $email user email.
	 * @since 1.0.0
	 */
	public function intrkt_get_checkout_details_by_email( $email ) {
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . INTRKT_ABANDON_CART_ABANDONMENT_TABLE;
		$result                 = $wpdb->get_row(
			$wpdb->prepare('SELECT * FROM `' . $cart_abandonment_table . '` WHERE email = %s AND `order_status` IN ( %s, %s )', $email, INTRKT_CART_ABANDONED_ORDER, INTRKT_CART_NORMAL_ORDER ) // phpcs:ignore
		);
		return $result;
	}

	/**
	 * Get the checkout details for the user.
	 *
	 * @param string $value value.
	 * @since 1.0.0
	 */
	public function intrkt_get_tracked_data_without_status( $value ) {
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . INTRKT_ABANDON_CART_ABANDONMENT_TABLE;
		$result                 = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM `' . $cart_abandonment_table . '` WHERE email = %s LIMIT 1', $value ) // phpcs:ignore
		);
		return $result;
	}

	/**
	 *  Decode and get the original contents.
	 *
	 * @param string $token token.
	 */
	public function intrkt_decode_token( $token ) {
		$token = sanitize_text_field( $token );
		parse_str( base64_decode( urldecode( $token ) ), $token );
		return $token;
	}

	/**
	 * Delete orders from cart abandonment table whose cart total is zero and order status is abandoned.
	 */
	public function intrkt_delete_empty_abandoned_order() {
		global $wpdb;

		$cart_abandonment_table = $wpdb->prefix . INTRKT_ABANDON_CART_ABANDONMENT_TABLE;

		$where = array(
			'cart_total' => 0,
		);

		$wpdb->delete( $cart_abandonment_table, $where );
	}
}

INTRKT_ABANDON_Tracking::get_instance();
