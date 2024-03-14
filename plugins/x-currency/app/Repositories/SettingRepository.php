<?php

namespace XCurrency\App\Repositories;

use XCurrency\App\Models\Currency;

class SettingRepository {
    public function get() {
        $db_settings    = $this->db_settings();
        $setting_inputs = $this->input_fields();

        $global_settings = [];

        foreach ( $setting_inputs as $key => $input ) {
            if ( isset( $db_settings[$key] ) ) {
                $global_settings[$key] = $db_settings[$key];
            } elseif ( isset( $input['default'] ) ) {
                $global_settings[$key] = $input['default'];
            } else {
                $global_settings[$key] = '';
            }
        }

        return $global_settings;
    }

    public function db_settings() {
        $data = get_option( x_currency_config()->get( 'app.settings_option_key' ) );
        if ( false !== $data ) {
            $data = unserialize( $data );
        } else {
            $data = [];
        }
        return $data;
    }

    public function save_settings( array $data ) {
        unset( $data['action'] );
        $old_settings_data = $this->db_settings();
        $old_base_currency = isset( $old_settings_data['base_currency'] ) ? $old_settings_data['base_currency'] : 0;

        if ( true == $data['currency_rate_auto_update'] ) {
            $transient_time = $this->calculate_transient_time( $data['rate_auto_update_time_type'], $data['rate_auto_update_time'] );
            set_transient( 'x-currency-currency-update', $transient_time, $transient_time );
        }

        if ( $old_base_currency != $data['base_currency'] ) {

            $currency = Currency::query()->where( 'id', $data['base_currency'] )->first();

            if ( $currency ) {
                update_option( 'woocommerce_currency', $currency->code );
                update_option( x_currency_config()->get( 'app.base_currency_option_key' ), $data['base_currency'] );

                $get_rates = get_option( x_currency_config()->get( 'app.currency_rates_option_key' ), [] );

                if ( ! is_array( $get_rates ) ) {
                    $get_rates = unserialize( $get_rates );
                }

                $currency_rate_repository = x_currency_singleton( CurrencyRateRepository::class );
                $rates                    = $currency_rate_repository->exchange_base_currency( $currency->code, $get_rates );

                $currency_rate_repository->exchange_all_currency( $rates['rates'], false );
            }
        }

        $this->update_settings( $data );
    }
    
    public function update_settings( array $settings ) {
        update_option( x_currency_config()->get( 'app.settings_option_key' ), serialize( $settings ) );
    }

    public function input_fields_with_value() {
        $data = $this->db_settings();

        if ( empty( $data['base_currency'] ) ) {
            $data['base_currency'] = x_currency_base_id();
        }

        $inputs = $this->input_fields();

        foreach ( $data as $key => $value ) {
            if ( isset( $inputs[$key] ) ) {
                if ( $value === 'true' ) {
                    $inputs[$key]['default'] = true;
                } elseif ( $value === 'false' ) {
                    $inputs[$key]['default'] = false;
                } else {
                    $inputs[$key]['default'] = $value;
                }
            }
        }

        return $inputs;
    }

    public function calculate_transient_time( $type, $time ) {
        switch ( $type ) {
            case 'hour':
                $formula = 3600;
                break;
            case 'minute':
                $formula = 60;
                break;
            default:
                $formula = 1;
        }

        return $time * $formula;
    }

    public function input_fields() {
        $symbols = x_currency_symbols();

        $currency_options = [];
        foreach ( get_woocommerce_currencies() as $currency_code => $currency_name ) {
            $symbol = isset( $symbols[$currency_code] ) ? $symbols[$currency_code] : '';
            array_push( $currency_options, ['value' => $currency_code, 'label' => $currency_name . ' (' . html_entity_decode( $symbol ) . ')'] );
        }

        return [
            'user_welcome_currency_type'   => [
                'type'    => 'XCurrencySelect',
                'label'   => esc_html__( 'User welcome currency', 'x-currency' ),
                'options' => [
                    ['value' => 'fixed', 'label' => esc_html__( 'Fixed', 'x-currency' )],
                    ['value' => 'auto', 'label' => esc_html__( 'Base on geo ip', 'x-currency' )]
                ],
                'default' => '',
                'help'    => '<a href="https://www.youtube.com/watch?v=8rWdlGhqIJY" target="_blank">Click here</a> to know how to use this feature'
            ],
            'user_welcome_currency'        => [
                'type'          => 'XCurrencySelect',
                'option_source' => 'custom',
                'source_type'   => 'currency_list',
                'options'       => [],
                'label'         => esc_html__( 'Welcome currency', 'x-currency' ),
                'placeholder'   => esc_html__( 'Select a currency', 'x-currency' ),
                'required'      => true,
                'message'       => esc_html__( 'Default currency field is required', 'x-currency' ),
                'conditional'   => ['user_welcome_currency_type' => 'fixed'],
                'default'       => ''
            ],
            'base_currency'                => [
                'type'          => 'XCurrencySelect',
                'option_source' => 'custom',
                'source_type'   => 'currency_list',
                'options'       => [],
                'label'         => esc_html__( 'Base currency', 'x-currency' ),
                'placeholder'   => esc_html__( 'Select a currency', 'x-currency' ),
                'required'      => true,
                'message'       => esc_html__( 'Base currency field is required', 'x-currency' ),
                'default'       => get_option( x_currency_config()->get( 'app.base_currency_option_key' ) ),
                'help'          => "You might need to refresh currency rate if you switch the base currency"
            ],
            'default_rate_provider'        => [
                'type'        => 'XCurrencySelect',
                'label'       => esc_html__( 'Currency rate aggregator', 'x-currency' ),
                'options'     => [
                    ['value' => 'fixer', 'label' => esc_html__( 'Fixer', 'x-currency' )],
                    ['value' => 'fixer-api-layer', 'label' => esc_html__( 'Fixer Api Layer', 'x-currency' )],
                    ['value' => 'currencyfreaks', 'label' => esc_html__( 'CurrencyFreaks', 'x-currency' )],
                    ['value' => 'openexchangerates', 'label' => esc_html__( 'OpenExchangeRates', 'x-currency' )]
                ],
                'isClearable' => true,
                'placeholder' => esc_html__( 'Select a aggregator', 'x-currency' ),
                'default'     => ''
            ],
            'fixer_api_token'              => [
                'label'       => esc_html__( 'Fixer api token', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter fixer api token', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'Fixer api token field is required', 'x-currency' ),
                'conditional' => ['default_rate_provider' => 'fixer'],
                'help'        => '<a href="https://fixer.io/" target="_blank">Click here</a> for Fixer token',
                'default'     => ''
            ],
            'fixer-api-layer_api_token'    => [
                'label'       => esc_html__( 'Fixer api layer api token', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter fixer api layer api token', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'Fixer api layer api token field is required', 'x-currency' ),
                'conditional' => ['default_rate_provider' => 'fixer-api-layer'],
                'help'        => '<a href="https://apilayer.com/marketplace/fixer-api" target="_blank">Click here</a> for Fixer api layer api token',
                'default'     => ''
            ],
            'currencyfreaks_api_token'     => [
                'label'       => esc_html__( 'CurrencyFreaks api token', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter CurrencyFreaks api token', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'CurrencyFreaks api token field is required', 'x-currency' ),
                'conditional' => ['default_rate_provider' => 'currencyfreaks'],
                'help'        => '<a href="https://currencyfreaks.com/" target="_blank">Click here</a> for CurrencyFreaks token',
                'default'     => ''
            ],
            'openexchangerates_api_token'  => [
                'label'       => esc_html__( 'OpenExchangeRates api token', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter OpenExchangeRates api token', 'x-currency' ),
                'type'        => 'XCurrencyText',
                'required'    => true,
                'message'     => esc_html__( 'OpenExchangeRates api token field is required', 'x-currency' ),
                'conditional' => ['default_rate_provider' => 'openexchangerates'],
                'help'        => '<a href="https://openexchangerates.org/" target="_blank">Click here</a> for OpenExchangeRates token',
                'default'     => ''
            ],
            'currency_rate_auto_update'    => [
                'label'   => esc_html__( 'Currency auto update', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => false
            ],
            'rate_auto_update_time'        => [
                'name'        => '',
                'label'       => esc_html__( 'Currency rate auto update time', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter currency rate auto update time', 'x-currency' ),
                'type'        => 'XCurrencyNumber',
                'required'    => true,
                'message'     => esc_html__( 'Currency auto update time field is required', 'x-currency' ),
                'conditional' => ['currency_rate_auto_update' => true],
                'default'     => 24
            ],
            'rate_auto_update_time_type'   => [
                'label'       => esc_html__( 'Currency rate auto update time type', 'x-currency' ),
                'placeholder' => esc_html__( 'Enter currency rate auto update time type', 'x-currency' ),
                'type'        => 'XCurrencySelect',
                'required'    => true,
                'message'     => esc_html__( 'Currency auto update time type field is required', 'x-currency' ),
                'conditional' => ['currency_rate_auto_update' => true],
                'options'     => [
                    ['value' => 'second', 'label' => esc_html__( 'Second', 'x-currency' )],
                    ['value' => 'minute', 'label' => esc_html__( 'Minute', 'x-currency' )],
                    ['value' => 'hour', 'label' => esc_html__( 'Hour', 'x-currency' )]
                ],
                'default'     => 'hour'
            ],
            'cart_total_extra_fee'         => [
                'label'   => esc_html__( 'Extra fee for the total amount of the cart', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'preview' => "<img src='" . esc_url( x_currency_url( 'media/preview/extra-free-preview.webp' ) ) . "'/>",
                'default' => false
            ],
            'cart_total_extra_fee_message' => [
                'label'       => esc_html__( 'Show message of the cart total extra fee', 'x-currency' ),
                'type'        => 'XCurrencySwitch',
                'default'     => false,
                'conditional' => ['cart_total_extra_fee' => true]
            ],
            'specific_shipping_amount'     => [
                'label'   => esc_html__( 'Specific shipping amount for each currency', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => false,
                'preview' => "<img src='" . esc_url( x_currency_url( 'media/preview/shipping-amount-preview.webp' ) ) . "'/>"
            ],
            'specific_coupon_amount'       => [
                'label'   => esc_html__( 'Specific coupon amount for each currency', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => false,
                'preview' => "<img src='" . esc_url( x_currency_url( 'media/preview/coupon-amount-preview.webp' ) ) . "'/>"
            ],
            'specific_product_price'       => [
                'label'   => esc_html__( 'Specific product price for each currency', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => false,
                'preview' => "<img src='" . esc_url( x_currency_url( 'media/preview/product-price-preview.webp' ) ) . "'/>"
            ],
            'approximate_currency_type'    => [
                'type'    => 'XCurrencySelect',
                'label'   => esc_html__( 'Approximate Currency Type', 'x-currency' ),
                'options' => [
                    ['value' => 'auto', 'label' => esc_html__( 'Auto', 'x-currency' )],
                    ['value' => 'fixed', 'label' => esc_html__( 'Fixed', 'x-currency' )]
                ],
                'default' => 'auto'
            ],
            'approximate_currency_code'    => [
                'type'        => 'XCurrencySelect',
                'label'       => esc_html__( 'Approximate Currency', 'x-currency' ),
                'options'     => $currency_options,
                'default'     => '',
                'conditional' => ['approximate_currency_type' => 'fixed']
            ],
            'approximate_product_price'    => [
                'label'   => esc_html__( 'Approximate product price', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => false,
                'preview' => "<img src='" . esc_url( x_currency_url( 'media/preview/approximate-product-price.webp' ) ) . "'/>"
            ],
            'approximate_cart_price'       => [
                'label'   => esc_html__( 'Approximate cart and checkout total amount', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => false
            ],
            'prices_without_cents'         => [
                'label'   => esc_html__( 'Prices without cents', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => false
            ],
            'no_get_data_in_link'          => [
                'label'   => esc_html__( 'No GET data in link', 'x-currency' ),
                'type'    => 'XCurrencySwitch',
                'default' => false
            ]
        ];
    }
}