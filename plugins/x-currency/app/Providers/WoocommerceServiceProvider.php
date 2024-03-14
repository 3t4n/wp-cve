<?php

namespace XCurrency\App\Providers;

use WC_Product;
use WC_Product_Simple;
use WC_Product_Variation;
use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\App\Woocommerce\ApproximateProductPrice;
use XCurrency\App\Woocommerce\BaseCurrencyExchange;
use XCurrency\App\Woocommerce\Coupon;
use XCurrency\App\Woocommerce\Order;
use XCurrency\App\Woocommerce\Product;
use XCurrency\App\Woocommerce\Report;
use XCurrency\App\Woocommerce\Shipping;
use XCurrency\WpMVC\Contracts\Provider;

class WoocommerceServiceProvider implements Provider {
     /**
     * @var mixed
     */
    private $settings;

    /**
     * @var mixed
     */
    private $global_settings;

    /**
     * @var mixed
     */
    public $variation_price;

    /**
     * @var mixed
     */
    public $simple_product_price = false;

    /**
     * @var mixed
     */
    private array $updated_prices = [];

    private $simple_product_specific_price = [];

    private $products_prices = [];

    private $cart_coupon_free_shipping_check = false;

    private $is_cart_coupon_allow_free_shipping = false;

    private $order_currency;

    public CurrencyRepository $currency_repository;

    public function __construct( CurrencyRepository $currency_repository ) {
        $this->currency_repository = $currency_repository;
    }

    public function boot() {
        add_action( 'init', [$this, 'init'] );
    }

    public function init() {
        $this->settings        = x_currency_selected();
        $this->global_settings = x_currency_global_settings();

        ( x_currency_singleton( Order::class ) )->boot();

        if ( is_admin() ) {

            $supports = [Coupon::class, Shipping::class, Product::class];

            foreach ( $supports as $support ) {
                ( x_currency_singleton( $support ) )->boot();
            }

            if ( isset( $_GET['page'] ) && $_GET['page'] == 'wc-reports' ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
                ( x_currency_singleton( Report::class ) )->boot();
            }

        } elseif ( ! empty( $this->settings ) ) {
            add_action( 'wp', [$this, 'action_reference_wp'] );

            if ( 'disabled' !== $this->settings->rounding ) {
                add_filter( 'x_currency_exchange', [$this, 'round_price'] );
            }
    
            add_filter( 'woocommerce_price_format', [$this, 'price_format'], 99999999 );
            add_action( 'woocommerce_coupon_loaded', [$this, 'coupon_amount'], 9999 );
            add_filter( 'woocommerce_available_payment_gateways', [$this, 'payment_gateways'] );
            add_filter( 'woocommerce_currency', [$this, 'currency_code'] );
            add_filter( 'wc_get_price_decimals', [$this, 'price_decimal'] );

            if ( x_currency_is_base_currency() ) {
                new BaseCurrencyExchange;
            } else {
                add_filter( 'woocommerce_shipping_packages', [$this, 'shippings_cost'] );
                add_filter( 'woocommerce_product_get_regular_price', [$this, 'product_regular_price'], 10, 2 );
                add_filter( 'woocommerce_product_get_price', [$this, 'simple_product_price'], 10, 2 );
                add_filter( 'woocommerce_product_get_sale_price', [$this, 'product_sale_price'], 10, 2 );

                add_filter( 'woocommerce_product_variation_get_price', [$this, 'variation_get_price'], 9999, 2 );
                add_filter( 'woocommerce_product_variation_get_regular_price', [$this, 'variation_get_regular_price'], 9999, 2 );
                add_filter( 'woocommerce_variation_prices', [$this, 'variation_prices'], 1, 2 );
                add_filter( 'woocommerce_get_price_html', [$this, 'woocommerce_get_price_html'], 9, 2 );
            }

            add_filter( 'wc_get_price_thousand_separator', [$this, 'price_thousand_separator'] );
            add_filter( 'wc_get_price_decimal_separator', [$this, 'price_decimal_separator'] );

            // cart total extra fee
            if ( isset( $this->global_settings['cart_total_extra_fee'] ) && $this->global_settings['cart_total_extra_fee'] == true ) {
                add_filter( 'woocommerce_cart_get_total', [$this, 'cart_extra_fee'] );
                if ( isset( $this->global_settings['cart_total_extra_fee_message'] ) && $this->global_settings['cart_total_extra_fee_message'] == true ) {
                    add_filter( 'woocommerce_cart_total', [$this, 'cart_total_extra_fee'] );
                }
            }

            if ( ! empty( $this->global_settings['approximate_product_price'] ) || ! empty( $this->global_settings['approximate_cart_price'] ) ) {
                ( x_currency_singleton( ApproximateProductPrice::class ) )->boot();
            }
        }
        add_filter( 'woocommerce_currency_symbols', [$this, 'currency_symbol'] );
    }

    public function round_price( $price ) {
        $price = (float) $price;

        switch ( $this->settings->rounding ) {
            case 'up':
                return ceil( $price );
            case 'down':
                return floor( $price );
            default:
                return round( $price );
        }
    }

    public function woocommerce_get_price_html( $price, WC_Product $product ) {
        return x_currency_get_price_html( $price, $product, $this->updated_prices ); 
    }

    public function cart_extra_fee( $price ) {
        if ( empty( $this->settings->extra_fee ) ) {
            return $price;
        }

        if ( $this->settings->extra_fee_type === 'percent' ) {
            $extra_fee = ( $this->settings->extra_fee / 100 ) * $price;
        } else {
            $extra_fee = $this->settings->extra_fee;
        }

        return $price + $extra_fee;
    }

    /**
     * Fires once the WordPress environment has been set up.
     */
    public function action_reference_wp() {
        $order_id = false;
        if ( is_wc_endpoint_url( 'order-pay' ) ) {
            $order_id = get_query_var( 'order-pay' );
        } elseif ( is_wc_endpoint_url( 'order-received' ) ) {
            $order_id = get_query_var( 'order-received' );
        } elseif ( is_wc_endpoint_url( 'view-order' ) ) {
            $order_id = get_query_var( 'view-order' );
        }

        if ( false !== $order_id ) {
            $order                = wc_get_order( $order_id );
            $this->order_currency = $this->currency_repository->get_by_first( 'code', $order->get_currency(), true );
        }
    }

    public function price_decimal() {
        if ( isset( $this->global_settings['prices_without_cents'] ) && $this->global_settings['prices_without_cents'] == true ) {
            return 0;
        }

        if ( ! empty( $this->order_currency ) ) {
            return $this->order_currency->max_decimal;
        }

        return x_currency_selected()->max_decimal;
    }

    /**
     * @param $price
     * @return mixed
     */
    public function product_regular_price( $price, WC_Product $product ) {
        if ( isset( $this->products_prices[$product->get_id()]['regular_price'] ) ) {
            return $this->products_prices[$product->get_id()]['regular_price'];
        }

        $price = $this->get_product_regular_and_sale_price( $price, 'regular', $product );

        $this->products_prices[$product->get_id()]['regular_price'] = $price;

        return $price;
    }

    /**
     * @param $price
     * @return mixed
     */
    public function product_sale_price( $price, WC_Product $product ) {

        if ( isset( $this->products_prices[$product->get_id()]['sale_price'] ) ) {
            return $this->products_prices[$product->get_id()]['sale_price'];
        }

        if ( ! empty( $price ) && ! empty( $this->global_settings['specific_product_price'] ) && ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) ) {
            $price = $this->get_product_regular_and_sale_price( $price, 'sale', $product );
        } else {
            $price = x_currency_exchange( $price );
        }

        $this->products_prices[$product->get_id()]['sale_price'] = $price;

        return $price;
    }

    /**
     * @param $price
     * @return mixed
     */
    public function simple_product_price( $price, WC_Product $product ) {

        if ( isset( $this->products_prices[$product->get_id()]['price'] ) ) {
            return $this->products_prices[$product->get_id()]['price'];
        }

        $this->get_simple_product_specific_price( $product );

        $lower_currency_code = strtolower( $this->settings->code );

        if ( ! empty( $this->simple_product_specific_price[$product->get_ID()][$lower_currency_code]['sale'] ) ) {
            $price = $this->simple_product_specific_price[$product->get_ID()][$lower_currency_code]['sale'];
        } elseif ( ! empty( $this->simple_product_specific_price[$product->get_ID()][$lower_currency_code]['regular'] ) ) {
            $price = $this->simple_product_specific_price[$product->get_ID()][$lower_currency_code]['regular'];
        } else {
            $price = x_currency_exchange( $price );
        }

        $this->products_prices[$product->get_id()]['price'] = $price;

        return $price;
    }

    public function get_product_regular_and_sale_price( $price, $price_type, WC_Product $product ) {
        $this->get_simple_product_specific_price( $product );
        $lower_currency_code = strtolower( $this->settings->code );
        if ( ! empty( $this->simple_product_specific_price[$product->get_ID()][$lower_currency_code][$price_type] ) ) {
            return $this->simple_product_specific_price[$product->get_ID()][$lower_currency_code][$price_type];
        }

        return x_currency_exchange( $price );
    }

    /**
     * @return mixed
     */
    public function price_format() {
        return x_currency_price_format( $this->settings->symbol_position );
    }

    /**
     * @return mixed
     */
    public function currency_symbol( $symbols ) {
        foreach ( $this->currency_repository->get_all() as $currency ) {
            $symbols[$currency->code] = $currency->symbol;
        }
        return $symbols;
    }

    /**
     * @param $coupon
     * @return mixed
     */
    public function coupon_amount( $coupon ) {
        if ( isset( $this->global_settings['specific_coupon_amount'] ) && $this->global_settings['specific_coupon_amount'] == 'true' ) {
            $coupon_for_each_currency = $coupon->get_meta( 'x_currency_coupon_amounts' );
            if ( $coupon_for_each_currency ) {
                $coupon_for_each_currency = unserialize( $coupon_for_each_currency );
                if ( ! empty( $coupon_for_each_currency[$this->settings->code] ) ) {
                    $coupon->set_amount( $coupon_for_each_currency[$this->settings->code] );
                    return $coupon;
                }
            }
        }

        $type = $coupon->get_discount_type();
        if ( $type === 'fixed_product' || $type === 'fixed_cart' ) {
            $coupon->set_amount( x_currency_exchange( $coupon->get_amount() ) );
        }
        return $coupon;
    }

    public function get_simple_product_specific_price( $product ) {
        if ( empty( $this->simple_product_specific_price[$product->get_ID()] ) && ! empty( $this->global_settings['specific_product_price'] ) ) {
            $this->simple_product_specific_price[$product->get_ID()] = json_decode( get_post_meta( $product->get_ID(), 'x_currency_simple', true ), true );
        }
    }

    /**
     * @param $gateways
     * @return mixed
     */
    public function payment_gateways( $gateways ) {
        if ( ! empty( $this->order_currency ) ) {
            $this->settings->disable_payment_gateways = $this->order_currency->disable_payment_gateways;
        }

        if ( empty( $_SERVER['HTTP_REFERER'] ) || isset( $_SERVER['HTTP_REFERER'] ) && strpos( sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ), 'wp-admin' ) === false ) {
            if ( ! empty( $this->settings->disable_payment_gateways ) && is_array( $this->settings->disable_payment_gateways ) ) {
                foreach ( $this->settings->disable_payment_gateways as $gateway ) {
                    if ( isset( $gateways[$gateway] ) ) {
                        unset( $gateways[$gateway] );
                    }
                }
            }
        }
        return $gateways;
    }

    /**
     * @param $prices
     * @return mixed
     */
    public function variation_prices( $product_prices, WC_Product $product ) {

        $prices = $product_prices;
        
        if ( empty( $this->global_settings['specific_product_price'] ) ) {
            foreach ( $prices as $price_type => &$values ) {
                foreach ( $values as $variation_id => &$price ) {
                    $price = x_currency_exchange( $price );
                }
            }
            $this->updated_prices = $prices;
            return $prices;
        }

        $currency_code = strtolower( $this->settings->code );

        $variation_fixed_prices = json_decode( get_post_meta( $product->get_id(), 'x_currency_variation', true ), true );

        foreach ( $prices as $price_type => &$values ) {

            foreach ( $values as $variation_id => &$price ) {
                if ( $price_type === 'price' ) {
                    /**
                     * If sale specific price exists the set it as a product main price
                     */
                    if ( ! empty( $variation_fixed_prices[$variation_id][$currency_code]['sale'] ) ) {
                        $price = $variation_fixed_prices[$variation_id][$currency_code]['sale'];

                        /**
                         * If regular specific price exists the set it as a product main price
                         */
                    } elseif ( ! empty( $variation_fixed_prices[$variation_id][$currency_code]['regular'] ) ) {
                        $price = $variation_fixed_prices[$variation_id][$currency_code]['regular'];

                        /**
                         * If regular and sale specifics price not exists then exchange original price
                         */
                    } else {
                        $price = x_currency_exchange( $price );
                    }
                } elseif ( 'sale_price' === $price_type ) {
                    /**
                     * If sale specific price exists the set it as a product sale price
                     */
                    if ( ! empty( $variation_fixed_prices[$variation_id][$currency_code]['sale'] ) ) {
                        $price = $variation_fixed_prices[$variation_id][$currency_code]['sale'];

                        /**
                         * If regular specific price exists the set it as a product sale price
                         */
                    } elseif ( ! empty( $variation_fixed_prices[$variation_id][$currency_code]['regular'] ) ) {
                        $price = $variation_fixed_prices[$variation_id][$currency_code]['regular'];

                        /**
                         * If sale and regular specifics price not exists then exchange original sale price
                         */
                    } else {
                        $price = x_currency_exchange( $price );
                    }
                } else {
                    $price_type = str_replace( '_price', '', $price_type );
                    if ( ! empty( $variation_fixed_prices[$variation_id][$currency_code][$price_type] ) ) {
                        $price = $variation_fixed_prices[$variation_id][$currency_code][$price_type];
                    } else {
                        $price = x_currency_exchange( $price );
                    }
                }
            }
        }

        $this->updated_prices = $prices;

        return $prices;
    }

    /**
     * @param $price
     * @param $product
     * @return mixed
     */
    public function variation_get_regular_price( $price, WC_Product $product ) {
        if ( isset( $this->products_prices[$product->get_id()]['regular_price'] ) ) {
            return $this->products_prices[$product->get_id()]['regular_price'];
        }

        if ( empty( $this->updated_prices ) && ! empty( $this->global_settings['specific_product_price'] ) ) {
            $currency_code          = strtolower( $this->settings->code );
            $variation_fixed_prices = json_decode( get_post_meta( $product->get_parent_id(), 'x_currency_variation', true ), true );
            if ( ! empty( $variation_fixed_prices[$product->get_ID()][$currency_code]['regular'] ) ) {
                $updated_price = $variation_fixed_prices[$product->get_ID()][$currency_code]['regular'];
            }
        }

        if ( ! isset( $updated_price ) ) {
            $updated_price = isset( $this->updated_prices['regular_price'][$product->get_ID()] ) ? $this->updated_prices['regular_price'][$product->get_ID()] : x_currency_exchange( $price );
        }

        $this->products_prices[$product->get_id()]['regular_price'] = $updated_price;

        return $updated_price;
    }

    /**
     * @param $price
     * @param $product
     * @return mixed
     */
    public function variation_get_price( $price, WC_Product $product ) {
        if ( isset( $this->products_prices[$product->get_id()]['price'] ) ) {
            return $this->products_prices[$product->get_id()]['price'];
        }

        if ( empty( $this->updated_prices ) && ! empty( $this->global_settings['specific_product_price'] ) ) {
            $currency_code          = strtolower( $this->settings->code );
            $variation_fixed_prices = json_decode( get_post_meta( $product->get_parent_id(), 'x_currency_variation', true ), true );
            if ( ! empty( $variation_fixed_prices[$product->get_ID()][$currency_code]['sale'] ) ) {
                $updated_price =  $variation_fixed_prices[$product->get_ID()][$currency_code]['sale'];
            } elseif ( ! empty( $variation_fixed_prices[$product->get_ID()][$currency_code]['regular'] ) ) {
                $updated_price =  $variation_fixed_prices[$product->get_ID()][$currency_code]['regular'];
            }
        }

        if ( ! isset( $updated_price ) ) {
            $updated_price =  isset( $this->updated_prices['price'][$product->get_ID()] ) ? $this->updated_prices['price'][$product->get_ID()] : x_currency_exchange( $price );
        }

        $this->products_prices[$product->get_id()]['price'] = $updated_price;

        return $updated_price;
    }

    /**
     * @return mixed
     */
    public function currency_code() {
        return $this->settings->code;
    }

    /**
     * @param $packages
     * @return mixed
     */
    public function shippings_cost( $packages ) {
        $excluded_shipping_methods = apply_filters(
            'x_currency_excluded_shipping_methods_from_exchange', [
                'printful_shipping',
                'printful_shipping_PRINTFUL_SLOW',
                'printful_shipping_STANDARD',
                'printful_shipping_PRINTFUL_MEDIUM'
            ]
        );

        foreach ( $packages as $key => $package ) {
            foreach ( $package['rates'] as $rate ) {

                /**
                 * @var \WC_Shipping_Rate $rate
                 */
                $method_id = $rate->get_method_id();

                if ( in_array( $method_id, $excluded_shipping_methods ) ) {
                    continue;
                }

                /**
                 * if specific amount shipping enable
                 */
                if ( isset( $this->global_settings['specific_shipping_amount'] ) && $this->global_settings['specific_shipping_amount'] == 'true' ) {
                    $settings_key             = 'woocommerce_' . $method_id . '_' . $rate->get_instance_id() . '_settings';
                    $shipping_method_settings = get_option( $settings_key );

                    if ( is_array( $shipping_method_settings ) ) {
                        /**
                         * if shipping method has specific amount (selected currency)
                         */
                        if ( ! empty( $shipping_method_settings['x_currency_' . strtolower( $this->settings->code )] ) ) {
                            $specific_cost = $shipping_method_settings['x_currency_' . strtolower( $this->settings->code )];
                            /**
                             * if shipping method is free shipping
                             */
                            if ( 'free_shipping' === $method_id ) {
                                if ( in_array( $shipping_method_settings['requires'], ['both', 'min_amount'] ) || ( $shipping_method_settings['requires'] === 'either' && ! $this->cart_coupon_allow_free_shipping() ) ) {
                                    if ( $package['contents_cost'] < $specific_cost ) {
                                        unset( $packages[$key]['rates'][$rate->get_id()] );
                                    }
                                }
                            } else {
                                $rate->set_cost( $specific_cost );
                            }
                            continue;
                        } else {
                            if ( 'free_shipping' === $method_id ) {
                                if ( in_array( $shipping_method_settings['requires'], ['both', 'min_amount'] ) || ( $shipping_method_settings['requires'] === 'either' && ! $this->cart_coupon_allow_free_shipping() ) ) {
                                    $minimum_cart_requires = x_currency_exchange( $shipping_method_settings['min_amount'] );
                                    if ( $package['contents_cost'] < $minimum_cart_requires ) {
                                        unset( $packages[$key]['rates'][$rate->get_id()] );
                                    }
                                }
                                continue;
                            }
                        }
                    }
                }

                $taxes = $rate->get_taxes();

                if ( ! empty( $taxes ) ) {
                    $rate->set_taxes( array_map( 'x_currency_exchange', $taxes ) );
                }

                $rate->set_cost( x_currency_exchange( $rate->get_cost() ) );
            }
        }

        return $packages;
    }

    private function cart_coupon_allow_free_shipping(): bool {
        if ( ! $this->cart_coupon_free_shipping_check ) {
            $applied_coupons = WC()->cart->get_applied_coupons();
            foreach ( $applied_coupons as $coupon_code ) {
                $coupon = new \WC_Coupon( $coupon_code );
                if ( $coupon->get_free_shipping() ) {
                    $this->is_cart_coupon_allow_free_shipping = true;
                }
            }
        }
        return $this->is_cart_coupon_allow_free_shipping;
    }

    /**
     * @return mixed
     */
    public function price_thousand_separator() {
        return $this->settings->thousand_separator;
    }

    /**
     * @return mixed
     */
    public function price_decimal_separator() {
        return $this->settings->decimal_separator;
    }

    /**
     * @param $view
     * @return mixed
     */
    public function cart_total_extra_fee( $view ) {
        if ( ! empty( $this->settings->extra_fee ) && $this->settings->extra_fee != 0 ) {
            if ( $this->settings->extra_fee_type === 'percent' ) {
                $extra_fee_text = $this->settings->extra_fee . ' %';
            } else {
                $extra_fee_text = sprintf( get_woocommerce_price_format(), $this->settings->symbol, $this->settings->extra_fee );
            }
            $view .= '<span class="x-currency-extra-fee" style="display:block;">' . sprintf( esc_html__( 'Extra fee %s for %s currency', 'x-currency' ), $extra_fee_text, get_woocommerce_currency() ) . '</span>';
        }
        return $view;
    }
}