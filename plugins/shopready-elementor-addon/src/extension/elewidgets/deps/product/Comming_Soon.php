<?php

namespace Shop_Ready\extension\elewidgets\deps\product;

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
use Shop_Ready\helpers\classes\WooCommerce_Product as WooCommerce_Product;
use Illuminate\Config\Repository as Shop_Ready_Repository;

/** 
 * @since 1.0 
 * WooCommerce Product Comming_Soon 
 * Products Details
 * @author quomodosoft.com
 */

class Comming_Soon
{

    public $field_one = '_woo_ready_product_comming_soon';
    public $field_date = '_woo_ready_product_comming_soon_expire_date';
    public $field_time = '_woo_ready_product_comming_soon_expire_time';

    public $config = [];

    public function register()
    {

        $this->set_config();
        add_filter('woocommerce_product_options_advanced', [$this, '_product_data'], 20);
        add_filter('woocommerce_process_product_meta', [$this, 'product_meta_save'], 20);
    }

    public function set_config()
    {
        $this->config = $this->shop_ready_product_meta_config()->get('comming_soon');
    }

    public function product_meta_save($post_id)
    {

        $product_text_field = sanitize_text_field(isset($_POST[$this->field_one]) ? 'yes' : 'no');
        if (!empty($product_text_field)) {
            update_post_meta($post_id, $this->field_one, sanitize_text_field($product_text_field));
        }
        // date
        $product_text_field_2 = sanitize_text_field(isset($_POST[$this->field_date]) ? sanitize_text_field($_POST[$this->field_date]) : '');
        update_post_meta($post_id, $this->field_date, sanitize_text_field($product_text_field_2));
        // time
        $product_text_field_3 = sanitize_text_field(isset($_POST[$this->field_time]) ? sanitize_text_field($_POST[$this->field_time]) : '');
        update_post_meta($post_id, $this->field_time, sanitize_text_field($product_text_field_3));
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

        echo wp_kses_post('<div class="product_comming_soon">');

        woocommerce_wp_checkbox(
            $this->config[$this->field_one]
        );

        echo wp_kses_post('</div>');

        WooCommerce_Product::date_field($this->config[$this->field_date]);

        WooCommerce_Product::time_field($this->config[$this->field_time]);
    }
}