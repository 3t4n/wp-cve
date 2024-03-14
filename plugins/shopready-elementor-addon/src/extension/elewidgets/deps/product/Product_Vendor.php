<?php

namespace Shop_Ready\extension\elewidgets\deps\product;

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
use Shop_Ready\helpers\classes\WooCommerce_Product as WooCommerce_Product;
use Illuminate\Config\Repository as Shop_Ready_Repository;

/** 
 * @since 1.0 
 * WooCommerce Product Product_Vendor 
 * Products Details
 * @author quomodosoft.com 
 */

class Product_Vendor
{

    public $field_one = '_shop_ready_product_vendor_name';

    public $config = [];

    public function register()
    {
        return false;

        $this->set_config();
        add_filter('woocommerce_product_options_advanced', [$this, '_product_data'], 21);
        add_filter('woocommerce_process_product_meta', [$this, 'product_meta_save'], 21);
    }

    public function set_config()
    {
        $this->config = $this->shop_ready_product_meta_config()->get('vendor');
    }

    public function product_meta_save($post_id)
    {

        $product_text_field = sanitize_text_field(isset($_POST[$this->field_one]) ? sanitize_text_field($_POST[$this->field_one]) : '');
        if (!empty($product_text_field)) {
            update_post_meta($post_id, $this->field_one, sanitize_text_field($product_text_field));
        }
    }

    function shop_ready_product_meta_config()
    {

        static $shop_ready_product_component = null;
        if (is_null($shop_ready_product_component)) {
            $shop_ready_product_component = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/elewidgets/config/product.php');
        }

        return $shop_ready_product_component;
    }

    public function _product_data()
    {

        WooCommerce_Product::text_field($this->config[$this->field_one]);
    }
}