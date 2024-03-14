<?php

class YektanetProductHandler extends YektanetMainClass
{
    public function __construct()
    {
        parent::__construct();
        add_action('woocommerce_update_product', function ($product_id) {
            $this->productUpdated($product_id);
        }, 10, 1);
        add_action('woocommerce_before_single_product', function () {
            global $product;
            $product_id = $product->get_id();
            
            if( !current_user_can('administrator') ) {
                $this->updateProductMetaValue($product_id);
                $this->addToViewed($product_id);
            }
            $this->productViewed($product_id);
        }, 10, 2);
    }

    private function updateProductMetaValue($product_id)
    {
        $current_value = get_post_meta($product_id , 'yektanet_view_count', true);
        if ($current_value) {
            update_post_meta($product_id, 'yektanet_view_count', $current_value + 1);
        } else {
            update_post_meta($product_id, 'yektanet_view_count', 1);
        }
    }

    private function addToViewed($product_id)
    {
        global $wpdb;
        $wpdb->insert($wpdb->prefix . 'yektanet_products_views', array(
            'product_id' => $product_id,
            'last_updated_time' => time()
        ));
    }

    private function productUpdated($product_id)
    {
        $product = wc_get_product($product_id);
        $categories = get_the_terms($product->get_id(), 'product_cat')[0];
        $parentcats = get_ancestors($categories->term_id, 'product_cat');
        $category_data = [];
        $category_data[] = $categories->name;
        foreach ($parentcats as $cat) {
            $category_data[] = get_term_by('id', $cat, 'product_cat')->name;
        }
        $data = array(
            'appId' => get_option('yektanet_app_id', true),
            'productSku' => $this->getProductSku($product_id),
            'host' => get_site_url(),
            'url' => get_permalink($product_id),
            'productTitle' => $product->get_title(),
            'productImage' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
            'productCategory' => array_reverse($category_data),
            'productDiscount' => $this->getProductDiscount($product_id),
            'productPrice' => $product->get_regular_price(),
            'productCurrency' => get_woocommerce_currency(),
            'productIsAvailable' => $product->is_in_stock(),
        );
        $args = array(
            'headers' => array(
                'Content-type' => 'text/plain;charset=UTF-8'
            ),
            'timeout' => 3,
            'method' => 'PUT',
            'body' => json_encode($data)
        );
        wp_remote_request(yektanet_product_update_api_url, $args);
    }

    private function productViewed($product_id)
    {
        $product = wc_get_product($product_id);
        $utm_data = sanitize_text_field($_COOKIE['analytics_campaign']) ?: sanitize_text_field($_COOKIE['_ynsrc']);
        $utm_data = json_decode(stripslashes($utm_data), true);

        $params = $_SERVER['QUERY_STRING'];
        $params = explode('&', $params);
        $params_data = [];
        foreach ($params as $par) {
            $par_data = explode('=', $par);
            if (count($par_data) > 1) {
                $params_data[$par_data[0]] = $par_data[1];
            }
        }
        $data = array(
            'acm' => 'detail',
            'aa' => 'product',
            'aca' => $product->get_title(),
            'acb' => $this->getProductSku($product_id),
            'acc' => $this->getProductCategory($product_id),
            'acd' => 0,
            'ace' => $product->get_price(),
            'ach' => $this->getProductDiscount($product_id),
            'aco' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
            'acq' => $product->is_in_stock(),
            'ac' => get_permalink($product->get_id()),
            'ae' => json_encode($params_data),
            'ad' => get_site_url(),
            'ba' => array_key_exists('_yngt',$_COOKIE ) ? sanitize_text_field($_COOKIE['_yngt']) : '',
            'as' => $product->get_title(),
            'aef' => get_option('yektanet_app_id', true),
            'aaa' => $utm_data['source'],
            'aab' => $utm_data['medium'],
            'aac' => $utm_data['content'],
            'aad' => $utm_data['campaign'],
            'aae' => $utm_data['term'],
            'abi' => $utm_data['yn'],
            'uys' => $utm_data['yn_source'],
            'uyd' => $utm_data['yn_data'],
            'ai' => array_key_exists('analytics_session_token',$_COOKIE ) ? sanitize_text_field($_COOKIE['analytics_session_token']) : '',
            'af' => wp_get_referer(),
            'ag' => explode('/', wp_get_referer())[2],
        );
        wp_remote_post(yektanet_ua_url . http_build_query($data), $this->setArgs());
    }
}