<?php

namespace WC_BPost_Shipping\Options;

use WC_BPost_Shipping_Address;
use WC_BPost_Shipping_Cart;
use WC_BPost_Shipping_Delivery_Methods;
use WC_Coupon;

/**
 * Class WC_BPost_Shipping_Options_Base allows to get options defined into admin panel
 */
class WC_BPost_Shipping_Options_Base {

	const OPTION_DOMAIN = 'woocommerce_bpost_shipping_settings';

	/** @var  array */
	private $options;

	/**
	 * @return string
	 */
	public function get_account_id() {
		$this->migrate_option( 'account_id', 'api_account_id' ); // To delete after 2016-02-01

		return $this->get_option( 'api_account_id' );
	}

	/**
	 * Init $this->options
	 */
	private function init_options() {
		if ( $this->options === null ) {
			$this->options = get_option( self::OPTION_DOMAIN );
		}
	}

	/**
	 * Set $this->options
	 *
	 * @param $options
	 */
	public function set_options( $options ) {
		$this->options = $options;
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	protected function get_option( $key ) {
		$this->init_options();

		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : null;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	private function has_option( $key ) {
		return $this->get_option( $key ) !== null;
	}

	/**
	 * @param string $old_key
	 * @param string $new_key
	 */
	private function migrate_option( $old_key, $new_key ) {
		if ( $this->has_option( $old_key ) ) {
			$this->options[ $new_key ] = $this->options[ $old_key ];
			unset( $this->options[ $old_key ] );

			update_option( self::OPTION_DOMAIN, $this->options );
			$this->options = null;
		}
	}

	/**
	 * @return bool
	 */
	public function is_enabled() {
		return $this->get_option( 'enabled' ) === 'yes';
	}

	/**
	 * @param array $bpost_data
	 * @param string $country
	 *
	 * @return string
	 */
	public function get_hash( array $bpost_data, $country ) {
		$values = array();

		$mandatory_values = array(
			'accountId'       => $bpost_data['account_id'],
			'action'          => 'START',
			'customerCountry' => $country,
			'orderReference'  => $bpost_data['order_reference'],
		);

		$optional_values = array(
			'costCenter'              => 'cost_center',
			'deliveryMethodOverrides' => 'delivery_method_overrides',
			'extraSecure'             => 'extra_secure',
			'orderWeight'             => 'sub_weight',
		);

		foreach ( $mandatory_values as $key => $value ) {
			$item            = $key . '=' . $value;
			$values[ $item ] = $item;
		}

		foreach ( $optional_values as $key => $value ) {
			if ( isset( $bpost_data[ $value ] ) ) {
				if ( is_array( $bpost_data[ $value ] ) ) {
					foreach ( $bpost_data[ $value ] as $sub_value ) {
						$item            = $key . '=' . $sub_value;
						$values[ $item ] = $item;
					}
				} else {
					$item            = $key . '=' . $bpost_data[ $value ];
					$values[ $item ] = $item;
				}
			}
		}

		ksort( $values );

		$values[] = $this->get_passphrase();

		$concatenated_values = implode( '&', $values );

		return hash( 'sha256', $concatenated_values );
	}

	/**
	 * @return string
	 */
	public function get_passphrase() {
		$this->migrate_option( 'passphrase', 'api_passphrase' ); // To delete after 2016-02-01

		return $this->get_option( 'api_passphrase' );
	}

	/**
	 * TODO Bpost::API_URL is repeated at three linked position. Is it really needed?
	 * @return string
	 */
	public function get_api_url() {
		if ( $api_url = $this->get_option( 'api_url' ) ) {
			// remove spaces to avoid stupid curl errors
			return str_replace( ' ', '', $api_url );
		}

		return \Bpost\BpostApiClient\Bpost::API_URL;
	}

	/**
	 * TODO why this is here?
	 *
	 * @param WC_BPost_Shipping_Address $shipping_address
	 * @param WC_BPost_Shipping_Cart $cart
	 * @param WC_BPost_Shipping_Delivery_Methods $delivery_methods
	 *
	 * @return string[]
	 */
	public function get_delivery_method_overrides(
		WC_BPost_Shipping_Address $shipping_address,
		WC_BPost_Shipping_Cart $cart,
		WC_BPost_Shipping_Delivery_Methods $delivery_methods
	) {

		if ( $this->is_free_shipping(
			$shipping_address->get_shipping_country(),
			$cart->get_discounted_subtotal(),
			$cart->get_used_coupons()
		)
		) {
			return $delivery_methods->get_delivery_method_overrides(
				$this->is_national_shipping( $shipping_address->get_shipping_country() )
			);
		}

		return array();
	}

	/**
	 * TODO why this is here?
	 *
	 * @param string $country_iso_2
	 * @param float $amount
	 * @param array $free_shipping_coupons
	 *
	 * @return bool
	 */
	public function is_free_shipping( $country_iso_2, $amount, array $free_shipping_coupons ) {
		return $this->is_free_country_for_amount( $country_iso_2, $amount ) || $this->has_free_shipping_coupon( $free_shipping_coupons );
	}

	/**
	 * TODO why this is here?
	 *
	 * @param array $coupon_codes
	 *
	 * @return bool
	 */
	public function has_free_shipping_coupon( array $coupon_codes ) {
		foreach ( $coupon_codes as $coupon_code ) {
			$coupon = new WC_Coupon( $coupon_code );
			if ( $coupon->get_free_shipping() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $country_iso_2
	 *
	 * @return bool
	 */
	public function is_national_shipping( $country_iso_2 ) {
		return $country_iso_2 === 'BE';
	}

	/**
	 * @return bool
	 */
	public function is_logs_debug_mode() {
		return $this->get_option( 'logs_debug_mode' ) === 'yes';
	}

	public function get_free_shipping_items() {
		return json_decode( $this->get_option( 'free_shipping_items' ), true ) ?: array();
	}

	public function is_free_country_for_amount( $country_iso_2, $amount ) {
		$free_shipping_items = $this->get_free_shipping_items();

		if ( ! array_key_exists( $country_iso_2, $free_shipping_items ) ) {
			return false;
		}

		return $amount >= $free_shipping_items[ $country_iso_2 ];
	}

	public function get_label_cache_time() {
		return $this->get_option( 'label_cache_time' ) ?: '';
	}

	/**
	 * @return string|null api key if defined, null if not
	 */
	public function get_gmaps_api_key() {
		return $this->get_option( 'google_api_key' );
	}

	public function get_label_api_key() {
		return $this->get_option( 'label_api_key' );
	}
}
