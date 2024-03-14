<?php
/**
 * API function for interakt add on.
 *
 * @package interakt-add-on-woocommerce
 */

/**
 * API class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Setting class.
 */
class Intrkt_API {
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
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'intrkt_oauth_rest_api' ) );
		add_action( 'woocommerce_order_status_changed', array( $this, 'intrkt_trigger_api_call' ), 10, 3 );
		add_action( 'intrkt_refresh_api', array( $this, 'intrkt_trigger_refresh_api_call' ), 10 );
		add_action( 'intrkt_refresh_call_retry', array( $this, 'intrkt_trigger_refresh_api_call' ), 10 );
	}

	public function intrkt_oauth_rest_api () {
		register_rest_route(
			'intrkt/v1',
			'oauth',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'intrkt_oauth_redirect_callback' ),
			),
		);
	}
	public function intrkt_oauth_redirect_callback( $request ){
		$code  = $request->get_param( 'code' );
		if ( ! empty( $code ) ) {
			$auth_token = sanitize_text_field( $code );
			$response   = $this->intrkt_oauth_process_access_token( $auth_token );
		} else {
			$error             = ! empty( $request->get_param( 'error' ) ) ? $request->get_param( 'error' ) : 'No Data';
			$error_description = ! empty( $request->get_param( 'error_description' ) ) ? $request->get_param( 'error_description' ) : 'Request not received';
			intrkt_load()->utils->intrkt_add_to_log( $error, $error_description );
		}
	}
	/**
	 * Trigger API call for order status change.
	 *
	 * @param int    $order_id Order Id.
	 * @param string $old_status Order older status.
	 * @param string $new_status Order new status.
	 *
	 * @return void.
	 */
	public function intrkt_trigger_api_call( $order_id, $old_status, $new_status ) {
		$order              = wc_get_order( $order_id );
		$payment_method     = $order->get_payment_method();
		$integration_status = intrkt_load()->utils->intrkt_get_account_integration_status();
		$oauth_status       = intrkt_load()->utils->intrkt_get_account_connection_status();
		if ( 'false' === $integration_status || 'false' === $oauth_status ) {
			return;
		}
		$status            = apply_filters( 'intrkt_current_order_status', 'wc-' . $new_status, $order_id );
		$interakt_statuses = intrkt_load()->utils->intrkt_get_intrkt_status_for_order_status( $status, $payment_method );
		if ( empty( $interakt_statuses ) || ! is_array( $interakt_statuses ) ) {
			return;
		}
		foreach ( $interakt_statuses as $interakt_status ) {
			foreach ( $interakt_status as $key => $status ) {
				if ( 'intrkt_status' === $key ) {
					$this->intrkt_event_order_api_call( $order, $status );
				}
			}
		}
	}
	/**
	 * API call for access token.
	 *
	 * @param string $auth_token Authorization token.
	 *
	 * @return boolean Access token call status.
	 */
	public function intrkt_oauth_process_access_token( $auth_token ) {
		if ( empty( $auth_token ) ) {
			return;
		}
		update_option( 'intrkt_refresh_call_retry_current_attempt', 0 );
		$endpoint      = INTRKT_API_HOST . '/v1/platform_integrations/oauth/token/';
		$body          = array(
			'client_id'     => INTRKT_CLIENT_ID,
			'client_secret' => INTRKT_CLIENT_SECRET,
			'grant_type'    => 'authorization_code',
			'redirect_uri'  => INTRKT_REDIRECT_URL,
			'code'          => $auth_token,
		);
		$options       = array(
			'body'        => $body,
			'headers'     => array(
				'Content-Type: application/json',
			),
			'data_format' => 'body',
		);
		$response      = wp_remote_post( $endpoint, $options );
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$body = $response_body;
			intrkt_load()->utils->intrkt_set_public_api_token( $body );
			$oauth_status = intrkt_load()->utils->intrkt_get_account_connection_status();
			if ( 'true' === $oauth_status ) {
				$this->intrkt_oauth_send_success_status();
				$this->intrkt_set_refresh_api_token_event( $body );
				intrkt_load()->utils->intrkt_set_intrkt_public_token_expiry();
			}
			intrkt_load()->utils->intrkt_add_to_log( 'Success: Token API call ' . $response_code, $response_body );
			return true;
		} else {
			intrkt_load()->utils->intrkt_add_to_log( 'Error: Token API call ' . $response_code, $response_body );
			return false;
		}
	}
	/**
	 * API call for success status.
	 *
	 * @return boolean Access token call status.
	 */
	public function intrkt_oauth_send_success_status() {
		$org_id = intrkt_load()->utils->intrkt_get_intrkt_org_id();
		if ( empty( $org_id ) ) {
			return;
		}
		$endpoint      = INTRKT_API_HOST . '/v1/platform_integrations/oauth/third_party_oauth_status/';
		$body          = array(
			'client_id'     => INTRKT_CLIENT_ID,
			'client_secret' => INTRKT_CLIENT_SECRET,
			'oauth_status'  => 'success',
			'org_id'        => $org_id,
		);
		$body          = wp_json_encode( $body );
		$options       = array(
			'body'        => $body,
			'headers'     => array(
				'Content-Type' => 'application/json',
			),
			'data_format' => 'body',
		);
		$response      = wp_remote_post( $endpoint, $options );
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			intrkt_load()->utils->intrkt_add_to_log( 'Success: Status Token API call ' . $response_code, $response_body );
			return true;
		} else {
			intrkt_load()->utils->intrkt_add_to_log( 'Error: Status Token API call ' . $response_code, $response_body );
			return false;
		}
	}
	/**
	 * Collect data need for API call.
	 *
	 * @param string $interakt_event Interakt event key.
	 * @param int    $order_id Order number.
	 */
	public function intrkt_collect_order_data( $interakt_event, $order_id = 0 ) {
		if ( empty( $interakt_event ) ) {
			return;
		}
		$data     = array();
		$order    = wc_get_order( $order_id );
		$platform = 'woocommerce';
		if ( empty( $order ) ) {
			return apply_filters( 'intrkt_event_api_body', '', $interakt_event );
		}
		$order_date        = $order->get_date_created();
		$order_date        = $order_date->date( 'Y-M-d H:i:s' );
		$order_date_utc    = get_gmt_from_date( $order_date, 'Y-m-d\TH:i:s.000\Z' );
		$payment_method    = $order->get_payment_method();
		$order_total       = floatval( $order->get_total() );
		$item_count        = 0;
		$order_currency    = $order->get_currency();
		$billing_city      = $order->get_billing_city();
		$products          = $order->get_items();
		$product_1_image   = '';
		$product_1_name    = '';
		$discount          = $order->get_total_discount();
		$phone_number      = $order->get_billing_phone();
		$order_confirm_url = $order->get_checkout_order_received_url();
		$tracking_urls     = '';
		$tracking_provider = '';
		$tracking_number   = '';
		foreach ( $order->get_items() as $item_key => $item ) {
			$product_id  = $item->get_product_id();
			$quantity    = $item->get_quantity();
			$item_count += $quantity;
			if ( empty( $product_1_image ) ) {
				$product_1_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'woocommerce_thumbnail' );
			}
			if ( empty( $product_1_name ) ) {
				$product_1_name = $item->get_name();
			}
		}
		$order_url = $order->get_checkout_order_received_url();
		if ( ! empty( $order->get_user_id() ) ) {
			$actions = wc_get_account_orders_actions( $order );
			foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$order_url = $action['url'];
			}
		}
		if ( class_exists( 'WC_Shipment_Tracking_Actions' ) ) {
			$shipment_tracking = WC_Shipment_Tracking_Actions::get_instance();
			$tracking_items    = $shipment_tracking->get_tracking_items( $order_id );
			if ( is_array( $tracking_items ) && ! empty( $tracking_items ) ) {
				foreach ( $tracking_items as $tracking_item ) {
					if ( ! empty( $tracking_urls ) && ! empty( $tracking_provider ) && ! empty( $tracking_number ) ) {
						break;
					}
					$formatted         = $shipment_tracking->get_formatted_tracking_item( $order_id, $tracking_item );
					$tracking_urls     = $formatted['formatted_tracking_link'];
					$tracking_provider = $formatted['formatted_tracking_provider'];
					$tracking_number   = $tracking_item['tracking_number'];
				}
			}
		}

		$discount_coupons = $order->get_coupon_codes();
		$discount_code    = '';
		if ( is_array( $discount_coupons ) && ! empty( $discount_coupons ) ) {
			foreach ( $discount_coupons as $discount_coupon ) {
				if ( ! empty( $discount_code ) ) {
					break;
				}
				$discount_code = $discount_coupon;
			}
		}
		$intrkt_track_event = '';
		$traits             = array();
		switch ( $interakt_event ) {
			case 'intrkt_order_placed_prepaid':
				$traits             = array(
					'order_number'   => $order_id,
					'order_date'     => $order_date_utc,
					'payment_mode'   => $payment_method,
					'total'          => $order_total,
					'discount'       => $discount,
					'discount_codes' => $discount_code,
					'order_url'      => $order_url,
					'item_count'     => $item_count,
					'currency'       => $order_currency,
					'city'           => $billing_city,
					'platform'       => $platform,
					'product1_image' => $product_1_image[0],
					'product1_name'  => $product_1_name,
				);
				$intrkt_track_event = 'order placed';
				break;
			case 'intrkt_order_placed_cod':
				$traits             = array(
					'order_number'          => $order_id,
					'order_date'            => $order_date_utc,
					'payment_mode'          => $payment_method,
					'total'                 => $order_total,
					'discount'              => $discount,
					'discount_codes'        => $discount_code,
					'order_url'             => $order_url,
					'item_count'            => $item_count,
					'currency'              => $order_currency,
					'city'                  => $billing_city,
					'platform'              => $platform,
					'COD Confirmation Link' => $order_confirm_url,
					'product1_image'        => $product_1_image[0],
					'product1_name'         => $product_1_name,
				);
				$intrkt_track_event = 'order cod';
				break;
			case 'intrkt_order_shipped':
				$traits             = array(
					'order_number'      => $order_id,
					'order_date'        => $order_date_utc,
					'payment_mode'      => $payment_method,
					'total'             => $order_total,
					'discount'          => $discount,
					'discount_codes'    => $discount_code,
					'order_url'         => $order_url,
					'item_count'        => $item_count,
					'currency'          => $order_currency,
					'city'              => $billing_city,
					'platform'          => $platform,
					'tracking_url'      => $tracking_urls,
					'tracking_number'   => $tracking_number,
					'tracking_provider' => $tracking_provider,
					'product1_image'    => $product_1_image[0],
					'product1_name'     => $product_1_name,
				);
				$intrkt_track_event = 'order shipped';
				break;
			case 'intrkt_order_delivered':
				$traits             = array(
					'order_number'      => $order_id,
					'order_date'        => $order_date_utc,
					'payment_mode'      => $payment_method,
					'total'             => $order_total,
					'discount'          => $discount,
					'discount_codes'    => $discount_code,
					'order_url'         => $order_url,
					'item_count'        => $item_count,
					'currency'          => $order_currency,
					'city'              => $billing_city,
					'platform'          => $platform,
					'tracking_url'      => $tracking_urls,
					'tracking_number'   => $tracking_number,
					'tracking_provider' => $tracking_provider,
					'product1_image'    => $product_1_image[0],
					'product1_name'     => $product_1_name,
				);
				$intrkt_track_event = 'order delivered';
				break;
			case 'intrkt_order_cancelled':
				$traits             = array(
					'order_number'   => $order_id,
					'order_date'     => $order_date_utc,
					'payment_mode'   => $payment_method,
					'total'          => $order_total,
					'discount'       => $discount,
					'discount_codes' => $discount_code,
					'order_url'      => $order_url,
					'item_count'     => $item_count,
					'currency'       => $order_currency,
					'city'           => $billing_city,
					'platform'       => $platform,
					'product1_image' => $product_1_image[0],
					'product1_name'  => $product_1_name,
				);
				$intrkt_track_event = 'order cancelled';
				break;
			default:
		}
		$country_code_selection = intrkt_load()->utils->intrkt_intrkt_get_country_code_selection();
		if ( INTRKT_WITHOUT_COUNTRY_CODE === $country_code_selection ) {
			$country_code = intrkt_load()->utils->intrkt_get_country_code();
			$data         = array(
				'phoneNumber' => $phone_number,
				'countryCode' => $country_code,
				'event'       => $intrkt_track_event,
				'traits'      => $traits,
			);
		} else {
			$data = array(
				'fullPhoneNumber' => $phone_number,
				'event'           => $intrkt_track_event,
				'traits'          => $traits,
			);
		}
		return apply_filters( 'intrkt_event_api_body', $data, $interakt_event );
	}
	/**
	 * Event track API call.
	 *
	 * @param object $order WooCommerce order object.
	 * @param string $interakt_status Interakt notification status.
	 *
	 * @return boolean event track api response.
	 */
	public function intrkt_event_order_api_call( $order, $interakt_status ) {
		if ( empty( $interakt_status ) || empty( $order ) ) {
			return;
		}
		$is_token_expired = intrkt_load()->utils->is_intrkt_public_token_expired();
		if ( true === $is_token_expired ) {
			$this->intrkt_trigger_refresh_api_call();
		}
		$user_call_response = $this->intrkt_user_order_api_call( $order, $interakt_status );
		if ( false === $user_call_response ) {
			return;
		}
		$endpoint     = intrkt_load()->utils->intrkt_get_public_api_endpoint();
		$body         = $this->intrkt_collect_order_data( $interakt_status, $order->get_id() );
		$bearer_token = intrkt_load()->utils->intrkt_get_public_api_token();
		if ( empty( $endpoint ) || empty( $bearer_token ) || empty( $body ) ) {
			return;
		}
		$body    = wp_json_encode( $body );
		$options = array(
			'body'        => $body,
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => "Bearer $bearer_token",
			),
			'data_format' => 'body',
		);
		$options = apply_filters( 'intrkt_event_api_call_data', $options, $endpoint, $order, $interakt_status );
		do_action( 'intrkt_event_api_call_before', $order, $interakt_status, $options );
		$response        = wp_remote_post( $endpoint, $options );
		$response_code   = wp_remote_retrieve_response_code( $response );
		$response_body   = wp_remote_retrieve_body( $response );
		$response_result = intrkt_load()->utils->intrkt_remote_retrieve_result( $response_body );
		do_action( 'intrkt_event_api_call_after', $order, $interakt_status, $options, $response );
		$response_result = apply_filters( 'intrkt_event_api_call_remote_result', $response_result, $endpoint, $order, $interakt_status );
		if ( true === $response_result ) {
			intrkt_load()->utils->intrkt_add_to_log( 'Success: Event track API call ' . $response_code, $response_body );
			return true;
		}
		intrkt_load()->utils->intrkt_add_to_log( 'Error: Event track API call ' . $response_code, $response_body );
		return false;
	}
	/**
	 * User track API call.
	 *
	 * @param object $order WooCommerce order object.
	 * @param string $interakt_status Interakt notification status.
	 *
	 * @return boolean user track api response.
	 */
	public function intrkt_user_order_api_call( $order, $interakt_status ) {
		if ( empty( $order ) ) {
			return;
		}
		$endpoint     = intrkt_load()->utils->intrkt_get_public_api_endpoint( 'user' );
		$bearer_token = intrkt_load()->utils->intrkt_get_public_api_token();
		if ( empty( $endpoint ) || empty( $bearer_token ) ) {
			return;
		}
		$name                   = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
		$phone_number           = $order->get_billing_phone();
		$traits                 = array(
			'name' => $name,
		);
		$body                   = array();
		$country_code_selection = intrkt_load()->utils->intrkt_intrkt_get_country_code_selection();
		if ( INTRKT_WITHOUT_COUNTRY_CODE === $country_code_selection ) {
			$country_code = intrkt_load()->utils->intrkt_get_country_code();
			$body         = array(
				'phoneNumber' => $phone_number,
				'countryCode' => $country_code,
				'traits'      => $traits,
			);
		} else {
			$body = array(
				'fullPhoneNumber' => $phone_number,
				'traits'          => $traits,
			);
		}
		$body    = wp_json_encode( $body );
		$options = array(
			'body'        => $body,
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => "Bearer $bearer_token",
			),
			'data_format' => 'body',
		);
		$options = apply_filters( 'intrkt_user_api_call_data', $options, $endpoint, $order, $interakt_status );
		do_action( 'intrkt_user_api_call_before', $order, $interakt_status, $options );
		$response        = wp_remote_post( $endpoint, $options );
		$response_code   = wp_remote_retrieve_response_code( $response );
		$response_body   = wp_remote_retrieve_body( $response );
		$response_result = intrkt_load()->utils->intrkt_remote_retrieve_result( $response_body );
		do_action( 'intrkt_user_api_call_after', $order, $interakt_status, $options, $response );
		$response_result = apply_filters( 'intrkt_user_api_call_remote_result', $response_result, $endpoint, $order, $interakt_status );
		if ( true === $response_result ) {
			intrkt_load()->utils->intrkt_add_to_log( 'Success: User track API call ' . $response_code, $response_body );
			return true;
		}
		intrkt_load()->utils->intrkt_add_to_log( 'Error: User track API call ' . $response_code, $response_body );
		return false;
	}
	/**
	 * Test function.
	 *
	 * Deprecated.
	 *
	 * @param object $order WC order object.
	 * @param string $interakt_status Interakt Status.
	 */
	public function intrkt_send_order_test_email( $order, $interakt_status ) {
		$endpoint     = intrkt_load()->utils->intrkt_get_public_api_endpoint();
		$bearer_token = intrkt_load()->utils->intrkt_get_public_api_token();
		$order_data   = $this->intrkt_collect_order_data( $interakt_status, $order->get_id() );
		if ( empty( $order_data ) ) {
			return;
		}
		$to      = get_option( 'admin_email' );
		$subject = "Event $interakt_status";
		$body    = '';
		foreach ( $order_data as $key => $data ) {
			$body .= ( ! is_array( $data ) ) ? '<br>' . $key . ' = ' . $data : '';
			if ( is_array( $data ) ) {
				foreach ( $data as $key => $value ) {
					$body .= '<br>' . $key . ' = ' . $value;
				}
			}
		}

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		wp_mail( $to, $subject, $body, $headers, array( '' ) );
	}
	/**
	 * Set single event cron for refresh token.
	 *
	 * @param Array $response WooCommerce order object.
	 *
	 * @return void.
	 */
	public function intrkt_set_refresh_api_token_event( $response ) {
		if ( empty( $response ) ) {
			return;
		}
		$data = json_decode( $response );
		if ( ! is_object( $data ) ) {
			return false;
		}
		$intrkt_expire_in = ! empty( $data->expires_in ) ? intval( $data->expires_in ) : 7614000;
		$intrkt_expire_in = apply_filters( 'intrkt_refresh_api_call_expiry_in', $intrkt_expire_in, $response );
		if ( ! wp_next_scheduled( 'intrkt_refresh_api' ) ) {
			wp_schedule_single_event( time() + $intrkt_expire_in, 'intrkt_refresh_api' );
		}
	}
	/**
	 * Set single event cron for refresh token retry.
	 *
	 * @param int $current_attempt Current Attempt count.
	 *
	 * @return void.
	 */
	public function intrkt_set_refresh_api_token_event_retry( $current_attempt = 0 ) {
		$current_attempt = ++$current_attempt;
		$max_attempt     = apply_filters( 'intrkt_refresh_call_retry_max_attempt', 5, $current_attempt );
		if ( $current_attempt > $max_attempt ) {
			return;
		}
		update_option( 'intrkt_refresh_call_retry_current_attempt', $current_attempt );
		$intrkt_refresh_call_retry_interval = apply_filters( 'intrkt_refresh_call_retry_interval', 300 );
		if ( ! wp_next_scheduled( 'intrkt_refresh_call_retry' ) ) {
			wp_schedule_single_event( time() + $intrkt_refresh_call_retry_interval, 'intrkt_refresh_call_retry' );
		}
	}
	/**
	 * Refresh API call.
	 *
	 * @return boolean Refresh api response.
	 */
	public function intrkt_trigger_refresh_api_call() {
		$refresh_token = intrkt_load()->utils->get_intrkt_refresh_token();
		if ( empty( $refresh_token ) ) {
			return;
		}
		$endpoint = INTRKT_API_HOST . '/v1/platform_integrations/oauth/token/';
		$body     = array(
			'client_id'     => INTRKT_CLIENT_ID,
			'client_secret' => INTRKT_CLIENT_SECRET,
			'grant_type'    => 'refresh_token',
			'refresh_token' => $refresh_token,
		);
		$options  = array(
			'body'        => $body,
			'headers'     => array(
				'Content-Type: application/json',
			),
			'data_format' => 'body',
		);
		$options  = apply_filters( 'intrkt_refresh_api_call_data', $options, $endpoint );
		do_action( 'intrkt_refresh_api_call_before', $options );
		$response = wp_remote_post( $endpoint, $options );
		do_action( 'intrkt_refresh_api_call_after', $response );
		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			intrkt_load()->utils->intrkt_set_public_api_token( $body );
			$this->intrkt_set_refresh_api_token_event( $body );
			intrkt_load()->utils->intrkt_set_intrkt_public_token_expiry();
			update_option( 'intrkt_refresh_call_retry_current_attempt', 0 );
			intrkt_load()->utils->intrkt_add_to_log( 'Success: Refresh Token API call', $body );
			return true;
		} else {
			$retry_attempt = get_option( 'intrkt_refresh_call_retry_current_attempt', 0 );
			$this->intrkt_set_refresh_api_token_event_retry( $retry_attempt );
			intrkt_load()->utils->intrkt_add_to_log( 'Error: Refresh Token API call', $body );
			intrkt_load()->utils->intrkt_add_to_log( 'Refresh call triggerd', $retry_attempt );
			return false;
		}
	}
}
Intrkt_API::get_instance();
