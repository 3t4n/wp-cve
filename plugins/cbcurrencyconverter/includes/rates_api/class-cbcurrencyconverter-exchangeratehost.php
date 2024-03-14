<?php

/**
 * Currency rate api class for exchangerate.host
 *
 * Class CBCurrencyConverterExchangeratehost
 */
class CBCurrencyConverterExchangeratehost {
	/**
	 * alphavantage.co api method
	 *
	 * @param     $conversion_value
	 * @param     $price
	 * @param     $convert_from
	 * @param     $convert_to
	 * @param  int  $decimal_point
	 * @param  int  $auto_fill
	 *
	 * @return string
	 * @since 2.8.0
	 *
	 */
	public static function api_method( $conversion_value = 0, $price = 0, $convert_from = 'USD', $convert_to = 'CAD', $decimal_point = 2, $auto_fill = 0 ) {
		$setting = new CBCurrencyconverterSetting();

		$cache         = intval( $setting->get_option( 'rate_cache', 'cbcurrencyconverter_global', 1 ) ); //from version 2.7.1
		$cache_time_hr = intval( $setting->get_option( 'cache_time', 'cbcurrencyconverter_global', 2 ) ); //from version 2.7.1

		if ( $cache ) {
			// Get any existing copy of our transient data
			if ( false === ( $conversion_cache = get_transient( 'cbcurrencyconverter_exchangeratehost' ) ) ) {
				$conversion_cache                                      = [];
				$conversion_value                                      = CBCurrencyConverterExchangeratehost::api_get( $convert_from, $convert_to );
				$conversion_cache[ $convert_from . '-' . $convert_to ] = $conversion_value;
				$conversion_cache                                      = maybe_serialize( $conversion_cache );

				//https://codex.wordpress.org/Transients_API
				set_transient( 'cbcurrencyconverter_exchangeratehost', $conversion_cache, $cache_time_hr * HOUR_IN_SECONDS );

				$conversion_value = $conversion_value * $price;

				if ( ! $auto_fill ) {
					$conversion_value = apply_filters( 'cbcurrencyconverter_rate_final', $conversion_value, $price, $convert_from, $convert_to, $decimal_point );
				}

				//return number_format_i18n( $conversion_value, $decimal_point );
				return $conversion_value;
			}

			$conversion_cache = maybe_unserialize( $conversion_cache );
			if ( isset( $conversion_cache[ $convert_from . '-' . $convert_to ] ) ) {
				$conversion_value = $conversion_cache[ $convert_from . '-' . $convert_to ];
			} else {
				$conversion_value                                      = CBCurrencyConverterExchangeratehost::api_get( $convert_from, $convert_to );
				$conversion_cache[ $convert_from . '-' . $convert_to ] = $conversion_value;
				$conversion_cache                                      = maybe_serialize( $conversion_cache );

				//https://codex.wordpress.org/Transients_API
				set_transient( 'cbcurrencyconverter_exchangeratehost', $conversion_cache, $cache_time_hr * HOUR_IN_SECONDS );
			}
		} else {
			$conversion_value = CBCurrencyConverterExchangeratehost::api_get( $convert_from, $convert_to );
		}

		$conversion_value = $conversion_value * $price;

		if ( ! $auto_fill ) {
			$conversion_value = apply_filters( 'cbcurrencyconverter_rate_final', $conversion_value, $price, $convert_from, $convert_to, $decimal_point, 'alphavantage' );
		}

		//return number_format_i18n( $conversion_value, $decimal_point );
		return $conversion_value;
	}//end api_method

	/**
	 * api request to exchangerate.host
	 *
	 * @param $convert_from
	 * @param $convert_to
	 *
	 * @return int
	 * @since 2.8.0
	 *
	 */
	public static function api_get( $convert_from = 'USD', $convert_to = 'CAD' ) {
		$setting = new CBCurrencyconverterSetting();
		$api_key = $setting->get_option( 'apikey_exchangeratehost', 'cbcurrencyconverter_global', '' );

		if ( $api_key == '' ) {
			return 0;
		}


		//free doesn't allow https
		$url = 'http://api.exchangerate.host/convert?amount=1&from=' . urlencode( $convert_from ) . '&to=' . urlencode( $convert_to ) . '&base=USD&access_key=' . $api_key;
		$ch  = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$body = curl_exec( $ch );

		curl_close( $ch );

		$result = json_decode( $body, true );


		if ( is_array( $result ) && isset( $result['success'] ) && $result['success'] == 1 && isset( $result['result'] ) ) {
			return $result['result'];
		}

		return 0;
	}//end api_get
}//end class CBCurrencyConverterExchangeratehost