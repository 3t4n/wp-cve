<?php

namespace Wdr\App\Compatibility;

if (!defined('ABSPATH')) exit;

class MultiCurrencyByTivNet extends Base
{
    protected $key = 'compatible_multicurrency_tivnet';

    /**
     * Do compatibility script
     * */
    public function run(){
        $value = $this->config->getConfigData($this->key, 0);
        if($value){
            add_filter('advanced_woo_discount_rules_additional_fee_amount', array($this, 'convertPrice'));
            add_filter('advanced_woo_discount_rules_converted_currency_value', array($this, 'convertPrice'));
        }
    }

    /**
     * To convert price
     * */
    public function convertPrice($value) {
        if(is_numeric($value) && !empty($value)) {
            if(class_exists('\WOOMC\Currency\Detector') && class_exists('\WOOMC\Rate\Storage')){
                if(class_exists('\WOOMC\Price\Rounder') && class_exists('\WOOMC\Price\Calculator')){
                    if(class_exists('\WOOMC\Price\Controller')){
                        $currency_detector = new \WOOMC\Currency\Detector();
                        $currency_detector->setup_hooks();
        
                        $rate_storage = new \WOOMC\Rate\Storage();
                        $rate_storage->setup_hooks();
        
                        $price_rounder = new \WOOMC\Price\Rounder();
        
                        $price_calculator = new \WOOMC\Price\Calculator($rate_storage, $price_rounder);
                        $price_controller = new \WOOMC\Price\Controller($price_calculator, $currency_detector);
                        $value = $price_controller->convert($value);
                    }
                }
            }
        }
    
        return $value;
    }

    /**
     * load fields
     * */
    public function loadFields(&$available){
        $value = $this->config->getConfigData($this->key, 0);
        if ( is_plugin_active( 'woocommerce-multicurrency/woocommerce-multicurrency.php' ) || ($value == 1)) {
            $available = true;
            ?>
            <div class="awdr-compatible-field">
                <label>
                    <input type="checkbox" name="wdrc[<?php echo esc_attr($this->key); ?>]" id="<?php echo esc_attr($this->key); ?>" value="1" <?php if ($value == 1) { ?> checked <?php } ?>>
                    <?php esc_html_e('Add compatible for WooCommerce Multi-Currency.', 'woo-discount-rules'); ?>
                </label>
            </div>
            <?php
        }
    }
}
