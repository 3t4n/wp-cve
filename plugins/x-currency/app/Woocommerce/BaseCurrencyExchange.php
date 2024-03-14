<?php

namespace XCurrency\App\Woocommerce;

defined( 'ABSPATH' ) || exit;

use WC_Product;

class BaseCurrencyExchange {
    private array $products_prices = [];

    private array $updated_prices = [];

    public function __construct() {
        add_filter( 'woocommerce_shipping_packages', [$this, 'shippings_cost'] );
        add_filter( 'woocommerce_product_get_regular_price', [$this, 'product_regular_price'], 10, 2 );
        add_filter( 'woocommerce_product_get_price', [$this, 'simple_product_price'], 10, 2 );
        add_filter( 'woocommerce_product_get_sale_price', [$this, 'product_sale_price'], 10, 2 );
        add_filter( 'woocommerce_product_variation_get_price', [$this, 'variation_get_price'], 9999, 2 );
        add_filter( 'woocommerce_product_variation_get_regular_price', [$this, 'variation_get_regular_price'], 9999, 2 );
        add_filter( 'woocommerce_variation_prices', [$this, 'variation_prices'], 1, 2 );
        add_filter( 'woocommerce_get_price_html', [$this, 'woocommerce_get_price_html'], 9, 2 );
    }

    public function shippings_cost( $packages ) {
        foreach ( $packages as $package ) {
            foreach ( $package['rates'] as $rate ) {
                /**
                 * @var \WC_Shipping_Rate $rate
                 */
                $cost = $rate->get_cost();

                if ( 0 < $cost ) {                        
                    $taxes = $rate->get_taxes();

                    if ( ! empty( $taxes ) ) {
                        $rate->set_taxes( array_map( 'x_currency_exchange', $taxes ) );
                    }

                    $rate->set_cost( x_currency_exchange( $cost ) );
                }
            }
        }

        return $packages;
    }

    /**
     * @param $price
     * @return mixed
     */
    public function product_regular_price( $price, WC_Product $product ) {
        if ( isset( $this->products_prices[$product->get_id()]['regular_price'] ) ) {
            return $this->products_prices[$product->get_id()]['regular_price'];
        }

        $updated_price = x_currency_exchange( $price );

        $this->products_prices[$product->get_id()]['regular_price'] = $updated_price;

        return $updated_price;
    }

    /**
     * @param $price
     * @return mixed
     */
    public function simple_product_price( $price, WC_Product $product ) {
        if ( isset( $this->products_prices[$product->get_id()]['price'] ) ) {
            return $this->products_prices[$product->get_id()]['price'];
        }

        $updated_price = x_currency_exchange( $price );

        $this->products_prices[$product->get_id()]['price'] = $updated_price;

        return $updated_price;
    }

    /**
     * @param $price
     * @return mixed
     */
    public function product_sale_price( $price, WC_Product $product ) {
        if ( isset( $this->products_prices[$product->get_id()]['sale_price'] ) ) {
            return $this->products_prices[$product->get_id()]['sale_price'];
        }

        $updated_price = x_currency_exchange( $price );

        $this->products_prices[$product->get_id()]['sale_price'] = $updated_price;

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

        $updated_price =  isset( $this->updated_prices['price'][$product->get_ID()] ) ? $this->updated_prices['price'][$product->get_ID()] : x_currency_exchange( $price );

        $this->products_prices[$product->get_id()]['price'] = $updated_price;

        return $updated_price;
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

        $updated_price = isset( $this->updated_prices['regular_price'][$product->get_ID()] ) ? $this->updated_prices['regular_price'][$product->get_ID()] : x_currency_exchange( $price );

        $this->products_prices[$product->get_id()]['regular_price'] = $updated_price;

        return $updated_price;
    }

        /**
     * @param $prices
     * @return mixed
     */
    public function variation_prices( $product_prices, WC_Product $product ) {
        $prices = $product_prices;

        foreach ( $prices as &$values ) {
            foreach ( $values as &$price ) {
                $price = x_currency_exchange( $price );
            }
        }

        $this->updated_prices = $prices;
    
        return $prices;
    }

    public function woocommerce_get_price_html( $price, WC_Product $product ) {
        return x_currency_get_price_html( $price, $product, $this->updated_prices );
    }
}