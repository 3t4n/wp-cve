<?php
/**
 * Abstrac class Exchange Rates Sever
 */
abstract class PMCS_Exchange_Server_Abstract {
	public $api_url = '';
	public $base = '';
	public $label = '';
	public $website = '';
	protected $using_currencies = null;

	public function __construct() {
		$this->base = $this->get_shop_base();
	}

	/**
	 * Get currency base
	 *
	 * @return string Currency code.
	 */
	public function get_shop_base() {
		return get_woocommerce_currency();
	}


	/**
	 * Get using_curencies
	 *
	 * @return array Currencies.
	 */
	public function get_using_currencies() {
		if ( ! is_null( $this->using_currencies ) ) {
			return $this->using_currencies;
		}

		$option_key = 'pmcs_currencies';
		$default_code = $this->get_shop_base();
		$defaults = array(
			'currency_code'      => '',
			'sign_position'      => get_option( 'woocommerce_currency_pos' ),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'decimal_separator'  => wc_get_price_decimal_separator(),
			'num_decimals'       => wc_get_price_decimals(),
			'rate'               => '',
			'display_text'       => '',
			'default'            => '',
		);

		// Load currencies settings.
		$currencies = get_option( $option_key );
		if ( ! is_array( $currencies ) ) {
			$currencies = array();
		}

		foreach ( $currencies as $k => $currency ) {
			$currencies[ $k ] = wp_parse_args( $currency, $defaults );
			if ( $currency['currency_code'] == $default_code ) {
				$currencies[ $k ]['default'] = 1;
			}
		}
		$this->using_currencies = $currencies;
		return $currencies;
	}

	/**
	 * Set using currencies
	 *
	 * @param string $currencies
	 * @return void
	 */
	public function set_using_currencies( $currencies ) {

	}


	/**
	 * Build query url to remote get exchange rates data.
	 *
	 * @return string API URL.
	 */
	public function build_query_url() {
		return $this->api_url;
	}

	/**
	 * Remote get exchange rates .
	 *
	 * @return array
	 */
	public function api_request() {
		$retusult = array();
		$res = wp_remote_get( $this->build_query_url() );
		if ( 200 == wp_remote_retrieve_response_code( $res ) ) {
			$body = wp_remote_retrieve_body( $res );
			$retusult = json_decode( $body, true );
		}
		return $retusult;
	}

	/**
	 * Hander action before update rate
	 * Call when settings change.
	 *
	 * @return void
	 */
	public function before_update() {

	}

	/**
	 * Update exchange rates
	 *
	 * @param string|bool $from
	 * @param string|bool $to
	 * @return array
	 */
	public function update( $from = false, $to = false ) {
		$rest = $this->api_request();
		$this->save( $rest );
		return $rest;
	}

	/**
	 * Admin settings fields
	 *
	 * @return array
	 */
	public function settings() {
		return array();
	}

	/**
	 * Save exchange rates
	 *
	 * @param array $data
	 * @return void
	 */
	protected function save( $data ) {
		$key = get_class( $this ) . ( isset( $data['base'] ) ? $data['base'] : $this->base );
		$data['_last_update'] = date_i18n( 'Y-m-d H:i:s' );
		delete_transient( $key );
		set_transient( $key, $data, 12 * HOUR_IN_SECONDS ); // One day.
		$this->sync_currency_rates( $data );
	}

	public function sync_currency_rates( $data ) {
		$option_key       = 'pmcs_currencies';
		$use_currencies   = get_option( $option_key, array() );
		$currency_code    = get_woocommerce_currency();
		if ( ! is_array( $use_currencies ) ) {
			$value = array();
		}

		$this->base = $currency_code;

		if ( $data['base'] != $currency_code ) {
			$data = $this->get_rates( $data['base'], $currency_code, $data );
		}

		foreach ( $use_currencies as $index => $args ) {
			if ( isset( $args['currency_code'] ) && $args['currency_code'] ) {
				if ( $args['currency_code'] != $currency_code ) { // If not is default currency.
					$rate = '';
					if ( isset( $data['rates'][ $args['currency_code'] ] ) ) {
						$rate = $data['rates'][ $args['currency_code'] ];
					}
					$use_currencies[ $index ]['rate'] = $rate;
				} else {
					$use_currencies[ $index ]['rate'] = 1; // Rate itself.
				}
			}
		}

		update_option( $option_key, $use_currencies );
	}

	/**
	 * Get exchange rates
	 *
	 * @return array
	 */
	public function get_rates( $from = false, $to = false, $data = false ) {
		$force = false;
		if ( $from || $to ) {
			$force = true;
		}

		if ( ! $data ) {
			if ( ! $force ) {
				$key = get_class( $this ) . $this->base;
				$data = get_transient( $key );
				if ( false === $data ) {
					$data = $this->update( $from, $to );
				}
			} else {
				$data = $this->update( $from, $to );
			}
		}

		if ( $data['base'] != $this->base ) {
			$base_rate = $data['rates'][ $this->base ];
			$one_base = false;
			if ( 0 != $base_rate ) {
				$one_base = round( 1 / $base_rate, 10 );
			}

			$new_rates = array();
			foreach ( $data['rates'] as $key => $value ) {
				$new_rates[ $key ] = $value * $one_base;
			}
			$data['base'] = $this->base;
			$data['rates'] = $new_rates;
		}

		return wp_parse_args(
			$data,
			array(
				'base' => '',
				'last_update' => date_i18n( 'Y-m-d H:i:s' ),
				'rates' => array(),
			)
		);
	}

}
