<?php

class YektanetMainClass
{
    public function __construct()
    {
        if (!defined('yektanet_ua_url')) define('yektanet_ua_url', 'https://ua.yektanet.com/__fake.gif?');
        if (!defined('yektanet_product_update_api_url')) define('yektanet_product_update_api_url', 'http://87.247.185.150/products');
        $this->create_products_views_table();
    }

    private function create_products_views_table(){
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $table_name = $wpdb->prefix . 'yektanet_products_views';
        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
			`ID` int(11) NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            `last_updated_time` int(11) NOT NULL,
			KEY `product_id` (`product_id`) USING BTREE,
			PRIMARY KEY (`ID`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        dbDelta($sql);
    }

    protected function getProductSku($product_id)
    {
        $product = wc_get_product($product_id);
        $sku = $product->get_sku();
        if (!$sku) {
            $sku = $product_id;
        }
        return $sku;
    }

    protected function getProductCategory($product_id): string
    {
        $categories = get_the_terms($product_id, 'product_cat')[0];
        $parentcats = get_ancestors($categories->term_id, 'product_cat');
        $category_data = [];
        $category_data[] = $categories->name;
        foreach ($parentcats as $cat) {
            $category_data[] = get_term_by('id', $cat, 'product_cat')->name;
        }

        return join(",", $category_data);
    }

    protected function getProductDiscount($product_id)
    {
        $product = wc_get_product($product_id);
        $regular = $product->get_regular_price();
        $sale = $product->get_sale_price();
        $discount = 0;
        if ($regular && $sale) {
            $discount = $regular - $sale;
        }

        return $discount;
    }

    protected function setArgs(): array
    {
        return array(
            'headers' => array(
                'Content-type' => 'text/plain;charset=UTF-8',
                'user-agent' => $_SERVER['HTTP_USER_AGENT'],
                'origin' => get_site_url(),
                'referer' => wp_get_referer(),
            ),
            'timeout' => 1,
        );
    }
}