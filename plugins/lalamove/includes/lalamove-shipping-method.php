<?php

function lalamove_shipping_method() {
	if ( ! class_exists( 'WC_Lalamove_Shipping_Method' ) ) {
		class WC_Lalamove_Shipping_Method extends WC_Shipping_Method {

			public function __construct( $instance_id = 0 ) {
				$this->instance_id = absint( $instance_id );

				$this->id                 = 'LALAMOVE_CARRIER_SERVICE';
				$this->method_title       = 'Lalamove';
				$this->title              = 'Lalamove';
				$this->method_description = __( 'Fast & Reliable Delivery to your Doorstep.' );
				$settings                 = array( 'title' => 'Lalamove' );
				$this->instance_settings  = $settings;

				$this->supports = array(
					'shipping-zones',
					'instance-settings',
					'instance-settings-modal',
				);

				$this->init();
			}

			function init() {
				$this->init_settings();
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
			}

			/**
			 * @param array $package
			 */
			public function calculate_shipping( $package = array() ) {
				if ( empty( $package['destination']['country'] ) ) {
					return;
				}
				$destination = $package['destination'];
				$country_id  = Lalamove_App::$wc_llm_dc[ strtolower( $destination['country'] ) ][1] ?? '';
				if ( ! $country_id ) {
					lalamove_error_log( 'Not supported country or state' );
					return;
				}
				$request_body = array(
					'currency'   => get_woocommerce_currency() ?? '',
					'country_id' => $country_id,
					'country'    => $destination['country'] ?? '',
					'state'      => $destination['state'] ?? '',
					'city'       => $destination['city'] ?? '',
					'address'    => $destination['address'] ?? '',
					'address1'   => $destination['address_1'] ?? '',
					'address2'   => $destination['address_2'] ?? '',
				);
				lalamove_error_log( array( 'request_body' => $request_body ) );
				$url          = lalamove_get_service_url();
				$api_response = wp_remote_post(
					$url . '/webhooks/quotation',
					array(
						'timeout'  => 10,
						'blocking' => true,
						'headers'  => array(
							'Content-Type'  => 'application/json; charset=utf-8',
							'authorization' => 'Bearer ' . lalamove_gen_jwt(),
						),
						'body'     => wp_json_encode( $request_body ),
					)
				);
				lalamove_error_log( array( 'response' => $api_response ) );
				if ( is_wp_error( $api_response ) ) {
					lalamove_error_log( $api_response->get_error_message() );
					return;
				}
				if ( 200 !== $api_response['response']['code'] ?? 0 ) {
					lalamove_error_log( $api_response['response']['message'] );
					return;
				}
				$response = json_decode( wp_remote_retrieve_body( $api_response ) );
				if ( ! empty( $res_currency = $response->currency ) && ! empty( $woo_currency = get_woocommerce_currency() ) ) {
					if ( strtolower( $woo_currency ) !== strtolower( $res_currency ) ) {
						lalamove_error_log( 'quotation currency not match with shop currency' );
						return;
					}
				}
				if ( ! empty( $response->total_price ) ) {
					$rate = array(
						'id'       => $this->id,
						'label'    => $this->title,
						'cost'     => $response->total_price / 100,
						'calc_tax' => 'per_order',
					);
					$this->add_rate( $rate );
				}
			}
		}
	}
}
