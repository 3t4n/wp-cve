<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi;


use WPDesk\GatewayWPPay\WooCommerceGateway\StandardPaymentGateway;

class BlueMediaClientFactory {
	/**
	 * @var \WC_Settings_API
	 */
	private $settings;

	public function set_settings( \WC_Settings_API $settings ): void {
		$this->settings = $settings;
	}

	public function get_settings(): \WC_Settings_API {
		return $this->settings;
	}

	public function has_credentials( string $currency = 'PLN' ): bool {
		$a           = unserialize( $this->settings->get_option( 'pos' ) ) ?? [];
		$credentials = $a[ $currency ] ?? [];

		return ! empty( $credentials );
	}

	public function get_client( string $currency = 'PLN' ): BlueMediaClient {
		$a           = unserialize( $this->settings->get_option( 'pos' ) ) ?? [];
		$credentials = $a[ $currency ] ?? [];
		if ( empty( $credentials ) ) {
			throw new BlueMediaNoCredentilalsException( __( 'No valid credentials for currency ' . $currency ) );
		}

		if ( $this->settings->get_option( StandardPaymentGateway::SETTINGS_FIELD_TEST ) === 'yes' ) {
			return new BlueMediaClientTest( $credentials['wppay'], $credentials['hash'] );
		}

		return new BlueMediaClient( $credentials['wppay'], $credentials['hash'] );
	}
}