<?php

namespace Wdr\App\Compatibility;

if (!defined('ABSPATH')) exit;

class PriceBasedOnCountryByOscarGare extends Base
{
    protected $key = 'compatible_wcpbc_oscargare';

    /**
     * Do compatibility scripts
     * */
    public function run(){
        $value = $this->config->getConfigData($this->key, 0);
        if($value){
            add_filter('advanced_woo_discount_rules_converted_currency_value', function($price){
                if(is_numeric($price) && !empty($price)) {
                    // Pass the price to the currency conversion function provided by the Currency Switcher. This
                    // will ensure that the discount is converted correctly
                    if(function_exists('wcpbc_the_zone')) {
                        if(is_object(wcpbc_the_zone()) && method_exists(wcpbc_the_zone(), 'get_exchange_rate_price')) {
                            // Return a price calculate by exchange rate
                            $price = wcpbc_the_zone()->get_exchange_rate_price($price, true, 'generic', null);
                        }
                    }
                }

                return $price;
            }, 10);

            // add_filter('advanced_woo_discount_rules_calculate_cart_subtotal_manually', '__return_true');
        }
    }

    /**
     * load fields
     * */
    public function loadFields(&$available){
        $value = $this->config->getConfigData($this->key, 0);
        if ( is_plugin_active( 'woocommerce-product-price-based-on-countries/woocommerce-product-price-based-on-countries.php' ) || ($value == 1)) {
            $available = true;
            ?>
            <div class="awdr-compatible-field">
                <label>
                    <input type="checkbox" name="wdrc[<?php echo esc_attr($this->key); ?>]" id="<?php echo esc_attr($this->key); ?>" value="1" <?php if ($value == 1) { ?> checked <?php } ?>>
                    <?php esc_html_e('Add compatible for WooCommerce Price Based on Country', 'woo-discount-rules'); ?>
                </label>
            </div>
            <?php
        }
    }
}
