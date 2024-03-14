<?php

namespace XCurrency\App\Providers;

use Exception;
use XCurrency\App\Repositories\CurrencyRateRepository;
use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\App\Repositories\SettingRepository;
use XCurrency\WpMVC\Contracts\Provider;

class SettingServiceProvider implements Provider {
    /**
     * @var array
     */
    public $global_settings = [];

    /**
     * @var mixed
     */
    public $selected_currency_settings;

    public SettingRepository $setting_repository;

    public CurrencyRepository $currency_repository;

    public function __construct( SettingRepository $setting_repository, CurrencyRepository $currency_repository ) {
        $this->setting_repository  = $setting_repository;
        $this->currency_repository = $currency_repository;
    }

    public function boot() {
        add_action( 'init', [ $this, 'action_init' ], 6 );
    }

    /**
     * Fires after WordPress has finished loading but before any headers are sent.
     *
     */
    public function action_init() : void {
        if ( ! session_id() ) {
            @session_start();
        }

        $this->set_up_base_currency();

        global $x_currency;

        $ip = $this->get_user_ip_address();

        $x_currency['global_settings'] = $this->setting_repository->get();

        if ( empty( $_SESSION['user_ip_address'] )  || $_SESSION['user_ip_address'] != $ip ) {
            $country_code                    = \WC_Geolocation::geolocate_ip( $ip )['country'];
            $_SESSION['user_ip_address']     = $ip;
            $_SESSION['user_country_code']   = $country_code;
            $x_currency['user_country_code'] = $country_code;
        } else {
            $x_currency['user_country_code'] = isset( $_SESSION['user_country_code'] ) ? $_SESSION['user_country_code'] : '';
        }

        $x_currency['selected_currency'] = apply_filters( 'x_currency_selected_currency', $this->select_currency( $x_currency['user_country_code'] ) );

        session_write_close();
    }

    /**
     * @return null
     */
    public function select_currency( $user_country ) {
        global $x_currency;

        if ( ! empty( $_GET['currency'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended --Here we are getting data from public URL, there is no possible way to verify nonce.
            //phpcs:ignore WordPress.Security.NonceVerification.Recommended --Here we are getting data from public URL, there is no possible way to verify nonce.
            $currency = $this->currency_repository->get_by_first( 'code', sanitize_text_field( wp_unslash( $_GET['currency'] ) ) );

            if ( $currency ) {
                $this->set_currency( $currency );
                return $currency;
            }
        }

        if ( ! empty( $_SESSION['x_currency_code'] ) ) {

            $currency = $this->currency_repository->get_by_first( 'code', $_SESSION['x_currency_code'] );

            if ( $currency ) {
                $this->set_currency( $currency );
                return $currency;
            }
        }

        if ( isset( $x_currency['global_settings']['user_welcome_currency_type'] ) ) {

            if ( $x_currency['global_settings']['user_welcome_currency_type'] === 'auto' ) {

                if ( ! empty( $user_country ) ) {

                    $currency = $this->currency_repository->get_by_first( 'welcome_country', $user_country );
                    if ( $currency ) {
                        $this->set_currency( $currency );
                        return $currency;
                    }
                }

            } else {
                if ( ! empty( $x_currency['global_settings']['user_welcome_currency'] ) ) {
                    $currency = $this->currency_repository->get_by_first( 'id', $x_currency['global_settings']['user_welcome_currency'] );
                    if ( $currency ) {
                        $this->set_currency( $currency );
                        return $currency;
                    }
                }
            }
        }

        $currency = $this->currency_repository->get_base_currency();

        $this->set_currency( $currency );
        return $currency;    
    }

    /**
     * @param $currency
     */
    public function set_currency( $currency ) {
        $_SESSION['x_currency_code'] = $currency->code;
        return true;
    }

    /**
     * @return mixed
     */
    public function get_user_ip_address() {
        foreach ( ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key ) {
            if ( ! array_key_exists( $key, $_SERVER ) ) {
                continue;
            }
            foreach ( explode( ',', sanitize_text_field( wp_unslash( $_SERVER[$key] ) ) ) as $ip ) {
                $ip = trim( $ip );
                if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
                    return $ip;
                }
            }
        }
        return '';
    }

    /**
     * Setup base currency if base currency not exits
     *
     * @return void
     */
    public function set_up_base_currency() {
        $base_currency_option_key = x_currency_config()->get( 'app.base_currency_option_key' );

        if ( ! empty( get_option( $base_currency_option_key ) ) ) {
            return;
        }

        $currency_code = get_woocommerce_currency();

        $currency = [];

        $symbol_code = get_woocommerce_currency_symbol( $currency_code );
        $symbol      = mb_convert_encoding( $symbol_code, 'UTF-8', 'HTML-ENTITIES' );

        $currency = [
            'active'                   => true,
            'name'                     => get_woocommerce_currencies()[$currency_code],
            'code'                     => $currency_code,
            'symbol'                   => $symbol,
            'flag'                     => 0,
            'rate'                     => 1,
            'rate_type'                => 'fixed',
            'extra_fee'                => 0,
            'extra_fee_type'           => 'fixed',
            'thousand_separator'       => wc_get_price_thousand_separator(),
            'max_decimal'              => wc_get_price_decimals(),
            'rounding'                 => 'disabled',
            'decimal_separator'        => wc_get_price_decimal_separator(),
            'symbol_position'          => get_option( 'woocommerce_currency_pos' ),
            'disable_payment_gateways' => [],
            'geo_countries_status'     => false,
            'disable_countries'        => [],
            'welcome_country'          => ''
        ];

        try {
            $currency_id = $this->currency_repository->create( $currency );
            update_option( $base_currency_option_key, sanitize_text_field( $currency_id ) );
            $currency_rate_repository = x_currency_singleton( CurrencyRateRepository::class );
            $currency_rate_repository->exchange_base_currency( $currency_code, x_currency_get_json_file_content( x_currency_dir( 'sample-data/rates.json' ) ) );
        } catch ( Exception $exception ) {
        }
    }
}