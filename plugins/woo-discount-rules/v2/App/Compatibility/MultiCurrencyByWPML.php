<?php

namespace Wdr\App\Compatibility;

if (!defined('ABSPATH')) exit;

class MultiCurrencyByWPML extends Base
{
    protected $key = 'compatible_multicurrency_by_wpml';

    /**
     * Do compatibility script
     * */
    public function run(){
        $value = $this->config->getConfigData($this->key, 0);
        if($value){
            // Convert price based on the currency
            add_filter('advanced_woo_discount_rules_converted_currency_value', function($price) {
                if(is_numeric($price) && !empty($price)) {
                    // Pass the price to the currency conversion filter provided by the WPML.
                    $price = apply_filters('wcml_raw_price_amount', $price);
                }
            
                return $price;
            }, 10);

            // Add a Wdr Ajax action to WPML Multi-currency Ajax actions list
            add_filter('wcml_multi_currency_ajax_actions', function($ajax_actions) {
                $ajax_actions[] = 'wdr_ajax'; // Add a AJAX action to the array 
                $ajax_actions[] = 'awdr_get_product_discount';
                $ajax_actions[] = 'awdr_change_discount_product_in_cart';
                
                return $ajax_actions;
            }, 10, 1);
        }
    }

    /**
     * load fields
     * */
    public function loadFields(&$available){
        $value = $this->config->getConfigData($this->key, 0);
        if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) || ($value == 1)) {
            $available = true;
            ?>
            <div class="awdr-compatible-field">
                <label>
                    <input type="checkbox" name="wdrc[<?php echo esc_attr($this->key); ?>]" id="<?php echo esc_attr($this->key); ?>" value="1" <?php if ($value == 1) { ?> checked <?php } ?>>
                    <?php esc_html_e('Add additional compatible for WPML (Multi-Currency)', 'woo-discount-rules'); ?>
                </label>
            </div>
            <?php
        }
    }
}
