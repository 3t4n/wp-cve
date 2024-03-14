<?php

namespace DominoKitApp\Frontend\Controller;

defined('ABSPATH') || exit;

class DominoKitFilter
{
    /**
     * @var null
     */
    private static $instance = null;

    public $WooCart_product_txt;

    public function __construct()
    {
        $unavailable_products = get_option('woo_unavailable_products') === 'true';
        if ($unavailable_products) {
            add_filter('posts_clauses', array($this, 'wookit_order_by_stock_status'), 2000);
        }

        $this->WooCart_product_txt = get_option('dominokit_cart_button_product_txt');
        if ($this->WooCart_product_txt !== false) {
            add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'wookit_custom_add_to_cart_text_callback'));
            add_filter('woocommerce_product_add_to_cart_text', array($this, 'wookit_custom_add_to_cart_text_callback'));
        }
    }

    public function wookit_custom_add_to_cart_text_callback()
    {
        if ($this->WooCart_product_txt === false) return false;
        return $this->WooCart_product_txt;
    }

    public function wookit_order_by_stock_status($posts_clauses)
    {
        global $wpdb;

        if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())) {
            $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
            $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
            $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
        }

        return $posts_clauses;
    }

    /**
     * @return DominoKitFilter|null
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
