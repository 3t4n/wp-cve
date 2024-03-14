<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * CoinFLEX Exchange Rates Class
 *
 * @category   CryptoWoo
 * @package    Exchange
 * @subpackage ExchangeBase
 * Author: CryptoWoo AS
 * Author URI: https://cryptowoo.com
 */
class CW_Exchange_Coinflex extends CW_Exchange_Base {

	/**
	 *
	 * Get the exchange name in nice format.
	 *
	 * @return string
	 */
	public function get_exchange_nicename() : string {
		return 'CoinFLEX';
	}

	/**
	 * Get the exchange API URL with format
	 *
	 * @return string
	 */
	protected function get_exchange_url_format() : string {
		return 'https://v2api.coinflex.com/v3/tickers?marketCode=%s';
	}

	/**
	 * Get the exchange price index (last index)
	 *
	 * @return string
	 */
	protected function get_exchange_price_index() : string {
		return 'lastTradedPrice';
	}

	/**
	 * Get the formatting of currency pair for exchange API
	 *
	 * @return string
	 */
	protected function get_pair_format() : string {
		return '%2$s-%1$s';
	}

	/**
	 *
	 * Is the exchange rate search pair uppercase or lowercase in the api url?
	 *
	 * @return bool
	 */
	protected function search_pair_is_uppercase() : bool {
		return true;
	}

	/**
	 *
	 * Get the timestamp in the exchange data result
	 * Or generate timestamp if none exist
	 *
	 * @param stdClass $price_data Json decoded result from exchange api call.
	 *
	 * @return string
	 */
	protected function get_timestamp_from_price_data( stdClass $price_data ) : string {
		if ( isset( $price_data->lastUpdatedAt ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			return $price_data->lastUpdatedAt; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		} else {
			return parent::get_timestamp_from_price_data( $price_data );
		}
	}

	/**
	 *
	 * Format the price data from exchange result to default data format
	 *
	 * @param stdClass $price_data Json decoded result from exchange api call.
	 *
	 * @return stdClass
	 */
	protected function format_price_data_from_exchange( stdClass $price_data ) : stdClass {
		if ( isset( $price_data->data ) && is_array( $price_data->data ) && ! empty( $price_data->data ) ) {
			return current( $price_data->data );
		}

		return $price_data;
	}

	/**
	 * Get exchange rates, cross-calculate fiat calculate Altcoin/Fiat values via BTC/Fiat
	 *
	 * @return mixed
	 */
	public function get_coin_price() {
		$coin   = $this->get_currency_name();
		$method = $this->get_exchange_name();
		if ( 'BTC' !== $coin && 'BTC' === $this->get_base_currency_name() ) {
			$this->set_base_currency_override( 'USD' );
			$prices   = parent::get_coin_price();
			$btc_fiat = CW_ExchangeRates::processing()->get_exchange_rate( 'BTC', false, 'USD' );
			$btc_rate = $prices[ $method ][ $coin . 'USD' ]['price'] / $btc_fiat;

			$prices[ $method ][ $coin . 'BTC' ]          = $prices[ $method ][ $coin . 'USD' ];
			$prices[ $method ][ $coin . 'BTC' ]['price'] = $btc_rate;

			unset( $prices[ $method ][ $coin . 'USD' ] );
			$this->set_base_currency_override( 'BTC' );

			return $prices;
		}

		return parent::get_coin_price();
	}
}
