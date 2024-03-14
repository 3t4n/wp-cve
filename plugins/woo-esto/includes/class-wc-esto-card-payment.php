<?php
class WC_Esto_Card_Payment extends WC_Esto_Payment {

	function __construct() {

		$this->id            = 'esto_card';
		$this->method_title  = __( 'Card payment (ESTO Pay)', 'woo-esto' );
		$this->method_description  = __( 'ESTO Pay card payments are Visa and Mastercard credit/debit card payments. Contact ESTO Partner Support for additional information and activation.', 'woo-esto' );
		$this->schedule_type = 'ESTO_PAY';

		parent::__construct();

		$this->admin_page_title = __( 'Card payment (ESTO Pay)', 'woo-esto' );

		if ( $this->enabled === 'yes' ) {
			$method = $this->get_card_method();
			$this->payment_method_key = isset( $method->key ) ? $method->key : false;
		}
	}

	function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields = [
			'enabled' => [
				'title'   => __( 'Enable/Disable', 'woo-esto' ),
				'type'    => 'checkbox',
				'label'   => __( 'ESTO Pay card payments are Visa and Mastercard credit/debit card payments. Contact ESTO Partner Support for additional information and activation.', 'woo-esto' ),
				'default' => 'no',
			],
			'title' => [
				'title'       => __( 'Title', 'woo-esto' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-esto' ),
				'default'     => __( 'Pay by card (Visa/Mastercard)', 'woo-esto' ),
			],
			'description' => [
				'title'       => __( 'Description', 'woo-esto' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-esto' ),
				'default'     => __( 'Payment is made using a secure payment solution.', 'woo-esto' ),
			],
			'show_logo' => [
				'title'   => __( 'Show logo', 'woo-esto' ),
				'type'    => 'checkbox',
				'label'   => __( 'Show Visa/Mastercard logo in checkout', 'woo-esto' ),
				'default' => 'yes',
			],
		] + [
            'order_prefix' => $this->form_fields['order_prefix'],
        ];
	}

	public function is_available() {
		if ( $this->enabled !== 'yes' ) {
			return false;
		}

		$method = $this->get_card_method();
		return ! empty( $method );
	}

	public function get_active_endpoint_country() {
		$payment_settings = get_option( 'woocommerce_esto_settings', null );
		if ( $payment_settings && ! empty( $payment_settings['endpoint'] ) ) {
			$api_url = $payment_settings['endpoint'];
		}
		else {
			$api_url = WOO_ESTO_API_URL_EE;
		}

		switch ( $api_url ) {
			case WOO_ESTO_API_URL_EE:
				return 'ee';
			case WOO_ESTO_API_URL_LV:
				return 'lv';
			case WOO_ESTO_API_URL_LT:
				return 'lt';
			default:
				return 'ee';
		}
	}

	public function get_card_methods_from_api() {
		$endpoint_country = $this->get_active_endpoint_country();
		$url  = esto_get_api_url_from_options() . "v2/purchase/payment-methods?country_code=" . strtoupper( $endpoint_country );
		if ( $this->connection_mode == 'test' ) {
			$url .= "&test_mode=1";
		}

		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		curl_setopt( $curl, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json, application/x-www-form-urlencoded"
		) );

		curl_setopt( $curl, CURLOPT_USERPWD, $this->shop_id . ":" . $this->secret_key );
		curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );

		//for debug only!
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

		$resp = curl_exec( $curl );
		curl_close( $curl );

		$data = json_decode( $resp );

		$card_methods = [];

		if ( is_array( $data ) ) {
			foreach ( $data as $row ) {
				if ( isset( $row->type ) && $row->type == 'CARD' ) {
					$card_methods[] = $row;
				}
			}
		}

		$this->card_methods_data = $card_methods;

		return $this->card_methods_data;
	}

	// just 1 for now
	public function get_card_method() {
		$transient_name = 'woo_esto_card_methods';
		$methods = get_transient( $transient_name );

		if ( ! $methods ) {
			$methods = $this->get_card_methods_from_api();
			set_transient( $transient_name, $methods, HOUR_IN_SECONDS );
		}

		return ! empty( $methods ) ? reset( $methods ) : false;
	}
}
