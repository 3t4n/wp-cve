<?php

namespace XCurrency\App\Woocommerce;

use XCurrency\App\Repositories\CurrencyRateRepository;
use XCurrency\App\Repositories\CurrencyRepository;

defined( 'ABSPATH' ) || exit;

class ApproximateProductPrice {
    public $currency;

    public $approximate_currency_code;

    public CurrencyRepository $currency_repository;

    public function __construct( CurrencyRepository $currency_repository ) {
        $this->currency_repository = $currency_repository;
    }

    /**
     * @return mixed
     */
    public function boot() {
        global $x_currency;

        /**
         * Checking is using approximate price feature 
         */
        if ( empty( $x_currency['global_settings']['approximate_product_price'] ) && empty( $x_currency['global_settings']['approximate_cart_price'] ) ) {
            return;
        }

        if ( 'auto' === $x_currency['global_settings']['approximate_currency_type'] ) {

            $country_and_currency_code = x_currency_get_json_file_content( x_currency_dir( 'sample-data/country-and-currency-code.json' ) );

            $country_code = x_currency_user_country_code();

            if ( ! empty( $country_code ) && ! empty( $country_and_currency_code[$country_code] ) ) {
                $approximate_currency_code = $country_and_currency_code[$country_code];
                $this->apply_approximate_price( $approximate_currency_code );
            }

        } elseif ( ! empty( $x_currency['global_settings']['approximate_currency_code'] ) ) {

            $this->apply_approximate_price( $x_currency['global_settings']['approximate_currency_code'] );
        }
    }

    public function apply_approximate_price( $currency_code ) {
        global $x_currency;

        if ( $currency_code === x_currency_selected()->code ) {
            return;
        }

        $this->approximate_currency_code = $currency_code;

        $currency = $this->currency_repository->get_by_first( 'code', $currency_code );

        if ( ! empty( $currency ) ) {
            $this->currency         = $currency;
            $this->currency->format = x_currency_price_format( $currency->symbol_position );
        } else {
            $rates = get_option( x_currency_config()->get( 'app.currency_rates_option_key' ) );
            if ( false === $rates ) {
                $base_currency_code = x_currency_base_code();
                /**
                 * @var CurrencyRateRepository $currency_rate_repository
                 */
                $currency_rate_repository = x_currency_singleton( CurrencyRateRepository::class );
                $rates                    = serialize( $currency_rate_repository->exchange_base_currency( $base_currency_code, x_currency_get_json_file_content( x_currency_dir( 'sample-data/rates.json' ) ) ) );
            }

            $rates = unserialize( $rates );

            if ( empty( $rates['rates'][$currency_code] ) ) {
                return;
            }

            $symbols                      = x_currency_symbols();
            $currency                     = new \stdClass;
            $currency->rate               = $rates['rates'][$currency_code];
            $currency->code               = $currency_code;
            $currency->symbol             = isset( $symbols[$currency_code] ) ? $symbols[$currency_code] : $currency_code;
            $currency->format             = get_woocommerce_price_format();
            $currency->decimal_separator  = wc_get_price_decimal_separator();
            $currency->thousand_separator = wc_get_price_thousand_separator();
            $currency->max_decimal        = wc_get_price_decimals();
            $this->currency               = $currency;
        }

        if ( ! empty( $x_currency['global_settings']['approximate_product_price'] ) ) {
            add_filter(
                'woocommerce_get_price_html', function ( $price, $product ) {
                    if ( $product->is_type( 'simple' ) && is_numeric( $product->get_price() ) ) {
                        return $price . '<span class="x-currency-product-approximate-price" style="display:block;">' . esc_html__( 'Approximately', 'x-currency' ) . ' ' . $this->approximate_price_html( $product->get_price() ) . '</span>';
                    }
                    return $price;
                }, 10, 2
            );
        }

        if ( ! empty( $x_currency['global_settings']['approximate_cart_price'] ) ) {
            add_action( 'woocommerce_cart_totals_after_order_total', [$this, 'approximate_cart_total_amount'] );
            add_action( 'woocommerce_review_order_after_order_total', [$this, 'approximate_cart_total_amount'] );
        }
    }

    public function approximate_cart_total_amount() {
        $wc_cart = WC()->cart;
        x_currency_render( '<tr class="x-currency-cart-approximate-total"><th>' . esc_html__( 'Approximately Total', 'x-currency' ) . '</th><td>' . $this->approximate_price_html( $wc_cart->get_cart_contents_total() + $wc_cart->get_shipping_total() ) . '</td></tr>' );
    }

    /**
     * @param $price
     */
    public function approximate_price_html( $price ) {
        $exchanged_currency = x_currency_selected()->rate;
        $product_base_price = ( 1 / $exchanged_currency ) * $price;
        $price              = $product_base_price * $this->currency->rate;
        $price              = number_format( (float) $price, intval( $this->currency->max_decimal ), $this->currency->decimal_separator, $this->currency->thousand_separator );
        $formatted_price    = sprintf( $this->currency->format, '<span class="woocommerce-Price-currencySymbol">' . $this->currency->symbol . '</span>', $price );
        return '<span class="woocommerce-Price-amount amount"><bdi>' . $formatted_price . '</bdi></span> (' . $this->approximate_currency_code . ')';
    }
}