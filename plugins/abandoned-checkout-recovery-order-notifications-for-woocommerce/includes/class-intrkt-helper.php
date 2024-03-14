<?php
/**
 * Helper function for interakt add on.
 *
 * @package interakt-add-on-woocommerce
 */

/**
 * Helper class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Setting class.
 */
class Intrkt_Helper {
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
	 * Get order status table data
	 *
	 * @return array $intrkt_order_status_data Interakt status for WC order status.
	 */
	public function intrkt_get_order_status_data() {
		global $wpdb;
		$intrkt_order_status_table = $wpdb->prefix . INTRKT_ORDER_STATUS_TABLE;
		$intrkt_order_status_data  = $wpdb->get_results( // phpcs:ignore.WordPress.DB
			$wpdb->prepare( "SELECT * FROM `$intrkt_order_status_table` ORDER BY %1s", 'table_order' ), // phpcs:ignore WordPress.DB
			ARRAY_A
		);
		$intrkt_order_status_data  = apply_filters( 'intrkt_order_status_data', $intrkt_order_status_data );
		return $intrkt_order_status_data;
	}
	/**
	 * Interakt status label name.
	 *
	 * @param string $intrkt_order_status_slug intrkt status slug.
	 *
	 * @return string status label.
	 */
	public function intrkt_get_intrkt_order_status_label( $intrkt_order_status_slug ) {
		$order_statuses = $this->intrkt_get_order_statuses();
		if ( empty( $order_statuses ) || ! is_array( $order_statuses ) ) {
			return;
		}
		return apply_filters( 'intrkt_order_status_label', $order_statuses[ $intrkt_order_status_slug ], $intrkt_order_status_slug );
	}
	/**
	 * Interakt status label name.
	 *
	 * @param string $wc_order_status_slug intrkt status slug.
	 *
	 * @return string status label.
	 */
	public function intrkt_get_wc_order_status_label( $wc_order_status_slug ) {
		$order_statuses = $this->intrkt_get_order_statuses();
		if ( empty( $order_statuses ) || ! is_array( $order_statuses ) ) {
			return;
		}
		return apply_filters( 'intrkt_wc_order_status_label', $order_statuses[ $intrkt_order_status_slug ], $intrkt_order_status_slug );
	}
	/**
	 * Interakt all status label name.
	 *
	 * @return array status array.
	 */
	public function intrkt_get_order_statuses() {
		$intrkt_order_statuses = array(
			'intrkt_abandon_checkout'     => __( 'Abandoned Checkout', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
			'intrkt_order_placed_prepaid' => __( 'Prepaid Order Confirmation', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
			'intrkt_order_placed_cod'     => __( 'CoD Order Confirmation', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
			'intrkt_order_shipped'        => __( 'Shipment Confirmation', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
			'intrkt_order_delivered'      => __( 'Delivery Confirmation', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
			'intrkt_order_cancelled'      => __( 'Cancellation Confirmation', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
		);
		return apply_filters( 'intrkt_order_statuses', $intrkt_order_statuses );
	}
	/**
	 * Interakt all status label name.
	 *
	 * @return array empty status slug.
	 */
	public function intrkt_get_order_statuses_empty_allowed() {
		$intrkt_order_statuses = array(
			'intrkt_order_delivered',
		);
		return apply_filters( 'intrkt_order_empty_allowed', $intrkt_order_statuses );
	}
	/**
	 * Interakt get country code setting.
	 *
	 * @return string
	 */
	public function intrkt_intrkt_get_country_code_selection() {
		$country_code_selection = ! empty( get_option( 'intrkt_country_code_selection' ) ) ? get_option( 'intrkt_country_code_selection' ) : INTRKT_WITHOUT_COUNTRY_CODE;
		return apply_filters( 'intrkt_country_code_selection_value', $country_code_selection );
	}
	/**
	 * Interakt get country code.
	 *
	 * @return string
	 */
	public function intrkt_get_country_code() {
		$country_code = ! empty( get_option( 'intrkt_country_code' ) ) ? get_option( 'intrkt_country_code' ) : '';
		return apply_filters( 'intrkt_country_code_value', $country_code );
	}
	/**
	 * Get Oauth options list.
	 */
	public function intrkt_get_oauth_option_list() {
		$intrkt_oauth_options = array(
			'intrkt_public_api_token_expires_in',
			'intrkt_public_api_token_created_at',
			'intrkt_public_api_token_access_token',
			'intrkt_public_api_token_refresh_token',
			'intrkt_public_api_token_org_id',
		);
		$intrkt_oauth_options = apply_filters( 'intrkt_oauth_option_list', $intrkt_oauth_options );
		return $intrkt_oauth_options;
	}
	/**
	 * Interakt get account status.
	 *
	 * @return boolean
	 */
	public function intrkt_get_account_connection_status() {
		$intrkt_oauth_options = $this->intrkt_get_oauth_option_list();
		if ( ! is_array( $intrkt_oauth_options ) ) {
			return 'false';
		}
		$connection_status = 'true';
		foreach ( $intrkt_oauth_options as $intrkt_oauth_option ) {
			if ( empty( get_option( $intrkt_oauth_option, '' ) ) ) {
				$connection_status = 'false';
				break;
			}
		}
		return $connection_status;
	}
	/**
	 * Interakt get account status.
	 *
	 * @return boolean
	 */
	public function intrkt_get_account_integration_status() {
		$oauth_status = $this->intrkt_get_account_connection_status();
		if ( 'false' === $oauth_status ) {
			return 'false';
		}
		$integration_status = get_option( 'intrkt_integration_status', 'false' );
		return apply_filters( 'intrkt_integration_status', $integration_status );
	}
	/**
	 * Interakt get OAuth custom route.
	 *
	 * @return boolean
	 */
	public function intrkt_get_oauth_custom_route() {
		$setting_page = get_admin_url( '', 'admin.php?page=' . INTRKT_SLUG );
		$oauth_route  = INTRKT_OAUTH_URL . '?client_id=' . INTRKT_CLIENT_ID . '&redirect_url=' . INTRKT_REDIRECT_URL;
		return apply_filters( 'intrkt_oauth_custom_route', $oauth_route );
	}
	/**
	 * Get intrkt status for woocommerce order status.
	 *
	 * @param string $order_status Order woocommerce status.
	 * @param string $payment_method Payment method.
	 *
	 * @return string Interakt Status.
	 */
	public function intrkt_get_intrkt_status_for_order_status( $order_status, $payment_method ) {
		if ( empty( $order_status ) || empty( $payment_method ) ) {
			return;
		}
		global $wpdb;
		$order_status_table_name = $wpdb->prefix . INTRKT_ORDER_STATUS_TABLE;
		if ( 'cod' === $payment_method ) {
			$intrkt_select_query = $wpdb->prepare(
				"SELECT `intrkt_status` FROM `$order_status_table_name`
				WHERE `order_status` = %s
				AND `payment_mode` IN ( %s, 'any' )
				AND `is_enabled` = 1
				ORDER BY `table_order`",
				$order_status,
				'cod'
			); // phpcs:ignore
		} elseif ( 'cod' !== $payment_method ) {
			$intrkt_select_query = $wpdb->prepare(
				"SELECT `intrkt_status` FROM `$order_status_table_name`
				WHERE `order_status` = %s
				AND `payment_mode` IN ( %s, %s, 'any' )
				AND `is_enabled` = 1
				ORDER BY `table_order`",
				$order_status,
				$payment_method,
				'not-cod'
			);
		}
		$interakt_status = $wpdb->get_results( $intrkt_select_query, ARRAY_A ); // phpcs:ignore
		return apply_filters( 'intrkt_interakt_status_for_order_status', $interakt_status, $order_status, $payment_method );
	}
	/**
	 * Interakt get public api endpoint.
	 *
	 * @param String $is_for Event or User.
	 *
	 * @return string.
	 */
	public function intrkt_get_public_api_endpoint( $is_for = 'event' ) {
		$endpoint = INTRKT_API_HOST . '/v1/public/track/events/';
		if ( 'user' === $is_for ) {
			$endpoint = INTRKT_API_HOST . '/v1/public/track/users/';
		}
		return apply_filters( 'intrkt_public_api_endpoint', $endpoint );
	}
	/**
	 * Interakt get public api token.
	 *
	 * @return string
	 */
	public function intrkt_get_public_api_token() {
		$token = get_option( 'intrkt_public_api_token_access_token', '' );
		return apply_filters( 'intrkt_public_api_token', $token );
	}
	/**
	 * Interakt set public api token.
	 *
	 * @param string $response API call response array.
	 *
	 * @return boolean
	 */
	public function intrkt_set_public_api_token( $response ) {
		if ( empty( $response ) ) {
			return;
		}
		$data = json_decode( $response );
		if ( ! is_object( $data ) ) {
			return false;
		}
		$data = apply_filters( 'intrkt_set_public_api_token', $data );
		if ( ! empty( $data->expires_in ) ) {
			update_option( 'intrkt_public_api_token_expires_in', 10 );
		}
		if ( ! empty( $data->created_at ) ) {
			update_option( 'intrkt_public_api_token_created_at', $data->created_at );
		}
		if ( ! empty( $data->access_token ) ) {
			update_option( 'intrkt_public_api_token_access_token', $data->access_token );
		}
		if ( ! empty( $data->refresh_token ) ) {
			update_option( 'intrkt_public_api_token_refresh_token', $data->refresh_token );
		}
		if ( ! empty( $data->org_id ) ) {
			update_option( 'intrkt_public_api_token_org_id', $data->org_id );
		}
		return true;
	}
	/**
	 * Interakt get org id.
	 *
	 * @return string Organization ID
	 */
	public function intrkt_get_intrkt_org_id() {
		$org_id = get_option( 'intrkt_public_api_token_org_id', '' );
		return apply_filters( 'intrkt_public_org_id', $org_id );
	}
	/**
	 * Interakt Set expiry date.
	 *
	 * @param int $expiry_in_second Expiry_in_second.
	 *
	 * @return void
	 */
	public function intrkt_set_intrkt_public_token_expiry( $expiry_in_second = '' ) {
		if ( empty( $expiry_in_second ) ) {
			$expiry_in_second = get_option( 'intrkt_public_api_token_expires_in', '' );
		}
		$expiry_in_second = intval( $expiry_in_second );
		if ( empty( $expiry_in_second ) ) {
			return;
		}
		$date = new DateTime();
		$date->add( new DateInterval( 'PT' . $expiry_in_second . 'S' ) );
		$expiry_datetime = apply_filters( 'intrkt_set_intrkt_public_token_expiry', $date->getTimestamp() );
		update_option( 'intrkt_public_api_expiry_datetime', $expiry_datetime );
	}
	/**
	 * Interakt get expiry date.
	 *
	 * @return string
	 */
	public function intrkt_get_intrkt_public_token_expiry() {
		$expiry_datetime = get_option( 'intrkt_public_api_expiry_datetime', '' );
		return apply_filters( 'get_intrkt_public_token_expiry', $expiry_datetime );
	}
	/**
	 * Interakt token check if expired.
	 *
	 * @return boolean
	 */
	public function is_intrkt_public_token_expired() {
		$expiry_datetime = $this->intrkt_get_intrkt_public_token_expiry();
		if ( empty( $expiry_datetime ) ) {
			return false;
		}
		$date = new DateTime();
		if ( $expiry_datetime > $date->getTimestamp() ) {
			return false;
		}
		return true;
	}
	/**
	 * Interakt get refresh token.
	 *
	 * @return string
	 */
	public function get_intrkt_refresh_token() {
		$refresh_token = get_option( 'intrkt_public_api_token_refresh_token', '' );
		return apply_filters( 'intrkt_public_refresh_token', $refresh_token );
	}
	/**
	 * Interakt retrieve result from public API response.
	 *
	 * @param string $response_body API call response body.
	 *
	 * @return boolean result.
	 */
	public function intrkt_remote_retrieve_result( $response_body ) {
		if ( empty( $response_body ) ) {
			return false;
		}
		$data = json_decode( $response_body );
		if ( ! is_object( $data ) ) {
			return false;
		}
		$data   = apply_filters( 'intrkt_remote_result', $data );
		$result = $data->result;
		return $result;
	}
	/**
	 * Disconnect the oAuth connection.
	 *
	 * @return boolean
	 */
	public function intrkt_disconnect_oauth() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		$intrkt_oauth_options = $this->intrkt_get_oauth_option_list();
		if ( ! is_array( $intrkt_oauth_options ) ) {
			$intrkt_oauth_options = array();
		}
		$intrkt_oauth_options[] = 'intrkt_integration_status';
		$intrkt_oauth_options   = apply_filters( 'intrkt_disconnect_option_list', $intrkt_oauth_options );
		foreach ( $intrkt_oauth_options as $intrkt_oauth_option ) {
			delete_option( $intrkt_oauth_option );
		}
		return true;
	}
	/**
	 * Check if its interakt setting page.
	 */
	public function is_intrkt_setting_page() {
		if( false === is_admin() ) {
			return false;
		}
		$intrkt_current_screen = get_current_screen();
		if ( 'woocommerce_page_' . INTRKT_SLUG === $intrkt_current_screen->id ) {
			return true;
		}
		return false;
	}
	/**
	 * Add to log file.
	 *
	 * @param string $tittle Tittle of the message.
	 * @param mixed  $message Message to be add.
	 *
	 * @return void.
	 */
	public function intrkt_add_to_log( $tittle, $message ) {
		$is_log_enabled = get_option( 'intrkt_log_data', INTRKT_DEBUG_MODE );
		// if ( 'false' === $is_log_enabled ) {
		// 	return;
		// }
		$log_file_path = INTRKT_DIR . '/includes/api_calls.log';
		$log_file      = fopen( $log_file_path, "a" );
		if ( is_writable( $log_file_path ) ) {
			fwrite( $log_file, "\n" . $tittle . "\n" . print_r( $message, 1 ) );
			fwrite( $log_file, "\n" . '---------------------------------------------------------' );
			fclose( $log_file );
		}
	}
}
