<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

class Price_Manager {

    public static function init() {

        $self = new self();

        add_filter('woocommerce_product_get_price', array($self, 'get_price'), 9999, 2);
        add_filter('woocommerce_product_get_regular_price', array($self, 'get_regular_price'), 9999, 2);
        add_filter('woocommerce_product_get_sale_price', array($self, 'get_sale_price'), 9999, 2);
        add_filter('woocommerce_product_variation_get_price', array($self, 'variation_get_price'), 9999, 2);
        add_filter('woocommerce_product_variation_get_regular_price', array($self, 'variation_get_regular_price'), 9999, 2);
//        add_filter('woocommerce_available_variation', array($self, 'available_variation'), 9999, 3);
        add_filter('wc_price', array($self, 'wc_price'), 9999, 3);
        add_filter('woocommerce_variation_prices', array($self, 'variation_prices'), 9999, 4);
        add_filter('woocommerce_price_format', array($self, 'price_format'), 9999, 2);
    }

    /**
     * 
     * @param type $price
     * @param type $product
     * @return type
     */
    public function get_price($price, $product = null) {
        if (!$price) {
            return $price;
        }
        $precision = get_option('woocommerce_price_num_decimals');
        //set to 1 if no rate
        $rate = Currency_Manager::get_currency_rate() ?: 1;
        $price = round($price * $rate, $precision);
        
        return $price;
    }
    
    public function get_regular_price($price, $product = null) {
        return $this->get_price($price);
    }
    
    public function get_sale_price($price, $product = null) {
        return $this->get_price($price);
    }
    
    public function variation_get_price($price, $product = null) {
        return $this->get_price($price);
    }
    
    public function variation_get_regular_price($price, $product = null) {
        return $this->get_price($price);
    }

        /**
     * Fix for dynamic variable prices
     * @param array $result
     * @param WC_Product_Variable $product
     * @param type $variation
     * @return type
     */
    public function available_variation($result, $product, $variation) {
        $v_price = $this->get_price($variation->get_price());
        $v_regular_price = $this->get_regular_price($variation->get_regular_price());
        
        if ( '' === $variation->get_price() ) {
			return apply_filters( 'woocommerce_empty_price_html', '', $variation );
		}
        
        if ($variation->is_on_sale()) {
            $price = wc_format_sale_price(wc_get_price_to_display(
                        $variation, array('price' => $v_regular_price)), wc_get_price_to_display(
                        $variation, array('price' => $v_price)))
                . $variation->get_price_suffix();
        } else {
            $price = wc_price(wc_get_price_to_display($variation, array('price' => $v_price))) . $variation->get_price_suffix();
        }
        
        $result['display_price'] = wc_get_price_to_display($variation, array('price' => $v_price));
        $result['display_regular_price'] = wc_get_price_to_display($variation, array('price' => $v_regular_price));
        $result['price_html'] = !empty($result['price_html']) ? '<span class="price">' . $price . '</span>' : '';

        return $result;
    }

    /**
     * WC HOOK
     * https://docs.woocommerce.com/wc-apidocs/source-function-get_woocommerce_price_format.html#407
     * @param type $return
     * @param type $price
     * @param type $args
     * @return string
     */
    public function wc_price($return, $price, $args) {
        extract(apply_filters('wc_price_args', wp_parse_args($args, array(
            'ex_tax_label' => false,
            //custom changes
            'currency' => isset($args['currency']) ? $args['currency'] : Currency_Manager::get_currency_code(),
            'decimal_separator' => wc_get_price_decimal_separator(),
            'thousand_separator' => wc_get_price_thousand_separator(),
            'decimals' => wc_get_price_decimals(),
            'price_format' => get_woocommerce_price_format()
        ))));
        
        $negative = $price < 0;
        //custom changes
        //price already formated, now clear the old format
        $price = str_replace($thousand_separator, '', $price);
        $price = str_replace($decimal_separator, '.', $price);

        $price = apply_filters('raw_woocommerce_price', floatval($negative ? $price * -1 : $price ));
        $price = apply_filters('formatted_woocommerce_price', number_format($price, $decimals, $decimal_separator, $thousand_separator), $price, $decimals, $decimal_separator, $thousand_separator);

        if (apply_filters('woocommerce_price_trim_zeros', false) && $decimals > 0) {
            $price = wc_trim_zeros($price);
        }

        $formatted_price = ( $negative ? '-' : '' ) . sprintf($price_format, '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol($currency) . '</span>', $price);
        $return = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

        if ($ex_tax_label && wc_tax_enabled()) {
            $return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
        }

        return $return;
    }
    
    /**
     * WC HOOK
     * https://docs.woocommerce.com/wc-apidocs/source-function-get_woocommerce_price_format.html#368
     * @param type $format
     * @param type $currency_pos
     * @return string
     */
    public function price_format($format, $currency_pos) {
        $currency_pos = Currency_Manager::get_currency_pos() ?: $currency_pos;
        switch ($currency_pos) {
            case 'left':
                $format = '%1$s%2$s';
                break;
            case 'right':
                $format = '%2$s%1$s';
                break;
            case 'left_space':
                $format = '%1$s&nbsp;%2$s';
                break;
            case 'right_space':
                $format = '%2$s&nbsp;%1$s';
                break;
        }

        return $format;
    }

    /**
     * WC HOOK
     * https://docs.woocommerce.com/wc-apidocs/source-class-WC_Product_Variable.html#328
     * @param array $prices_array
     * @param WC_Product_Variable $product
     * @param bool $display
     * @return array
     */
    public function variation_prices($prices_array, $product, $display) {
        foreach ($prices_array as &$prices) {
            foreach ($prices as &$price) {
                $price = $this->get_price($price);
            }
        }
        
        return $prices_array;
    }
}
