<?php

namespace XCurrency\App\Repositories;

use Exception;
use XCurrency\App\Models\Currency;
use XCurrency\App\RateProvider\ProviderBase;

class CurrencyRateRepository {
    public SettingRepository $setting_repository;

    public CurrencyRepository $currency_repository;

    public function __construct( SettingRepository $setting_repository, CurrencyRepository $currency_repository ) {
        $this->setting_repository  = $setting_repository;
        $this->currency_repository = $currency_repository;
    }

    public function exchange( $currency_id = 'all', $currency_code = null ) {
        $settings = $this->setting_repository->db_settings();

        if ( empty( $settings['default_rate_provider'] ) ) {
            throw new Exception( esc_html__( 'Please go to the global settings page and place your Currency Rate Aggregator API token', 'x-currency' ) );
        }

        $rate_provider = $settings['default_rate_provider'];
        $api_token     = $settings[$rate_provider . '_api_token'];
        $provider_list = x_currency_config()->get( 'exchange-providers' );
        $class         = $provider_list[$rate_provider]['class'];

        /**
         * @var ProviderBase $provider
         */
        $provider     = new $class;
        $api_response = $provider->get_rates( $api_token );

        $api_response = $this->exchange_base_currency( x_currency_base_code(), $api_response );

        if ( $currency_id === 'all' ) { // update all currency
            $this->exchange_all_currency( $api_response['rates'] );
        } elseif ( isset( $api_response['rates'][$currency_code] ) ) { // update single currency
            $this->update_currency_rate( $currency_id, $api_response['rates'][$currency_code] );
        }

        $this->currency_repository->get_currencies( true );
    }

    /**
     * @param $base_currency
     * @param $api_response
     * @return array
     */
    public function exchange_base_currency( $base_currency, $api_response, $update = true ) {
        $api_response = (array) $api_response;

        if ( empty( $api_response['base'] ) ) {
            return [];
        }

        $base = $api_response['base'];
        if ( $base === $base_currency ) {
            update_option( x_currency_config()->get( 'app.currency_rates_option_key' ), serialize( $api_response ) );
            return $api_response;
        }

        $rates = (array) $api_response['rates'];
        if ( empty( $rates[$base_currency] ) ) {
            return [];
        }

        $new_base_currency_old_rate = $rates[$base_currency];
        unset( $rates[$base_currency] );
        $old_base_currency_rate = 1 / $new_base_currency_old_rate;

        $final_rates = [$base_currency => 1, $base => $old_base_currency_rate];

        foreach ( $rates as $key => $value ) {
            $final_rates[$key] = $value / $new_base_currency_old_rate;
        }
        $api_response['base']  = $base_currency;
        $api_response['rates'] = $final_rates;

        if ( $update ) {
            update_option( x_currency_config()->get( 'app.currency_rates_option_key' ), serialize( $api_response ) );
        }

        return $api_response;
    }

    public function exchange_all_currency( $rates, bool $only_auto = true ) {
        $currencies = $this->currency_repository->get_all();

        if ( $only_auto ) {
            foreach ( $currencies as $currency ) {
                if ( isset( $rates[$currency->code] ) && $currency->rate_type === 'auto' ) {
                    $this->update_currency_rate( $currency->id, $rates[$currency->code] );
                }
            }
        } else {
            foreach ( $currencies as $currency ) {
                if ( isset( $rates[$currency->code] ) ) {
                    $this->update_currency_rate( $currency->id, $rates[$currency->code] );
                }
            }
        }
    }

    protected function update_currency_rate( $id, $rate ) {
        Currency::query()->where( 'id', (int) $id )->update(
            [
                'rate' => number_format( $rate, 12, '.', '' )
            ]
        );
    }
}