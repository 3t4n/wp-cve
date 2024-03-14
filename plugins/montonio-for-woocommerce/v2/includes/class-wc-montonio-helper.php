<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Montonio_Helper {

    /**
     * Get Montonio API keys
     * 
     * @return array
     */
    public static function get_api_keys( $sandbox_mode = 'no' ) {
        $api_settings = get_option( 'woocommerce_wc_montonio_api_settings' );

		$access_key = ! empty( $api_settings['access_key'] ) ? $api_settings['access_key'] : '';
		$secret_key = ! empty( $api_settings['secret_key'] ) ? $api_settings['secret_key'] : '';

        if ( $sandbox_mode === 'yes' ) {
			$access_key = ! empty( $api_settings['sandbox_access_key'] ) ? $api_settings['sandbox_access_key'] : '';
			$secret_key = ! empty( $api_settings['sandbox_secret_key'] ) ? $api_settings['sandbox_secret_key'] : '';
		}

        return apply_filters( 'wc_montonio_api_keys', [ 'access_key' => $access_key, 'secret_key' => $secret_key ], $sandbox_mode );
    }

    /**
     * Method that returns the appropriate locale identifier used in Montonio's systems
     *
     * @param string $locale The locale to search for
     * @return string identifier for locale if found, en_US by default
     */
    public static function get_locale( $locale ) {
        if ( in_array( $locale, [ 'lt', 'lt_LT', 'lt_lt', 'lt-LT', 'lt-lt' ] ) ) {
            return 'lt';
        } else if ( in_array( $locale, [ 'lv', 'lv_LV', 'lv_lv', 'lv-LV', 'lv-lv' ] ) ) {
            return 'lv';
        } else if ( in_array( $locale, [ 'ru', 'ru_RU', 'ru_ru', 'ru-RU', 'ru-ru' ] ) ) {
            return 'ru';
        } else if ( in_array( $locale, [ 'et', 'ee', 'EE', 'ee_EE', 'ee-EE', 'et_EE', 'et-EE', 'et_ee', 'et-ee' ] ) ) {
            return 'et';
        } else if ( in_array( $locale, [ 'fi', 'fi_FI', 'fi_fi', 'fi-FI', 'fi-fi' ] ) ) {
            return 'fi';
        } else if ( in_array( $locale, [ 'pl', 'pl_PL', 'pl_pl', 'pl-PL', 'pl-pl' ] ) ) {
            return 'pl';
        } else if ( in_array( $locale, [ 'de', 'de_DE', 'de_de', 'de-DE', 'de-de' ] ) ) {
            return 'de';
        } else {
            return 'en';
        }
    }

    /**
     * Method that returns the appropriate currency identifier used in Montonio's systems
     *
     * @param string $locale The currency to search for
     * @return string identifier for currency if found, EUR by default
     */
    public static function get_currency() {
        global $woocommerce_wpml;

        $currency = get_woocommerce_currency();

        // WPML Multi-Currency support
        if ( ! is_null( $woocommerce_wpml ) ) {
            if ( function_exists( 'wcml_is_multi_currency_on' ) && wcml_is_multi_currency_on() ) {
                $currency = $woocommerce_wpml->multi_currency->get_client_currency();
            }
        }

        if ( $currency === 'PLN' ) {
            return 'PLN';
        } else {
            return 'EUR';
        }
    }

    public static function is_client_currency_supported( $supported_currencies = [ 'EUR', 'PLN' ] ) {
        global $woocommerce_wpml;

        $currency = get_woocommerce_currency();

        // WPML Multi-Currency support
        if ( ! is_null($woocommerce_wpml ) ) {
            if ( function_exists( 'wcml_is_multi_currency_on' ) && wcml_is_multi_currency_on() ) {
                $currency = $woocommerce_wpml->multi_currency->get_client_currency();
            }
        }

        return in_array( $currency, $supported_currencies );
    }

    public static function get_payment_description( $custom_description, $order_id, $descripton ) {
        $order = wc_get_order( $order_id );
        $order_number = apply_filters( 'wc_montonio_merchant_reference_display', $order->get_order_number() );

        if ( $custom_description == 'yes' && ! empty( $descripton ) ) {
            $find    = '{order_number}';
            $replace = $order_number;

            $description = str_replace( $find, $replace, $descripton );

            return apply_filters( 'wc_montonio_payment_description', sanitize_text_field( $description ), $order_id );
        }

        return $order_number;
    }
}