<?php

namespace Wdr\App\Compatibility;

use Wdr\App\Helpers\Woocommerce;

if (!defined('ABSPATH')) exit;

class FacebookForWoocommerceByFacebook extends Base
{
    protected $key = 'compatible_fb_facebook';

    /**
     * Do compatibility script
     * */
    public function run(){
        $value = $this->config->getConfigData($this->key, 0);
        if($value){
            add_filter('wc_facebook_product_price', function ($price, $facebook_price, $product){
                if ( !$facebook_price ){
                    $product_price = Woocommerce::getProductPrice($product);
                    $discounted_price = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', $product_price, $product, 1, 0, 'discounted_price', true, false);
                    if($discounted_price !== false){
                        $price = (int)round($discounted_price*100);
                    }
                }

                return $price;
            }, 10, 3);
        }
    }

    /**
     * load fields
     * */
    public function loadFields(&$available){
        $value = $this->config->getConfigData($this->key, 0);
        if ( is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) || ($value == 1)) {
            $available = true;
            ?>
            <div class="awdr-compatible-field">
                <label>
                    <input type="checkbox" name="wdrc[<?php echo esc_attr($this->key); ?>]" id="<?php echo esc_attr($this->key); ?>" value="1" <?php if ($value == 1) { ?> checked <?php } ?>>
                    <?php esc_html_e('Add compatible for Facebook for WooCommerce', 'woo-discount-rules'); ?>
                </label>
                <br>
                <span class="wdr_desc_text awdr-clear-both"><?php esc_html_e('Support only for rule type Product Adjustment and Bulk Discount (which has minimum quantity as 1).', 'woo-discount-rules'); ?></span>
            </div>
            <?php
        }
    }
}