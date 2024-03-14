<?php

class YektanetCartHandler extends YektanetMainClass
{
    public function __construct()
    {
        parent::__construct();

        add_action('woocommerce_add_to_cart', function ($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
            $this->sendProductDataToUa($product_id, $quantity);
        }, 10, 6);
    }

    private function sendProductDataToUa($product_id, $quantity = 0)
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
            'acm' => 'add',
            'aa' => 'product',
            'aca' => $product->get_title(),
            'acb' => $this->getProductSku($product_id),
            'acc' => $this->getProductCategory($product_id),
            'acd' => $quantity,
            'ace' => $product->get_price(),
            'ach' => $this->getProductDiscount($product_id),
            'aco' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
            'acq' => $product->is_in_stock(),
            'ac' => get_permalink($product->get_id()),
            'ae' => json_encode($params_data),
            'ad' => get_site_url(),
            'ba' => sanitize_text_field($_COOKIE['_yngt']),
            'as' => $product->get_title(),
            'aef' => get_option('yektanet_app_id', true),
            'aaa' => $utm_data['source'],
            'aab' => $utm_data['medium'],
            'aac' => array_key_exists('content', $utm_data) ? $utm_data['content'] : '',
            'aad' => array_key_exists('campaign', $utm_data) ? $utm_data['campaign'] : '',
            'aae' => array_key_exists('term', $utm_data) ? $utm_data['term'] : '',
            'abi' => array_key_exists('yn', $utm_data) ? $utm_data['yn'] : '',
            'uys' => array_key_exists('yn_source', $utm_data) ? $utm_data['yn_source'] : '',
            'uyd' => array_key_exists('yn_data', $utm_data) ? $utm_data['yn_data'] : '',
            'ai' => sanitize_text_field($_COOKIE['analytics_session_token']),
            'af' => wp_get_referer(),
            'ag' => explode('/', wp_get_referer())[2],
        );
        wp_remote_post(yektanet_ua_url . http_build_query($data), $this->setArgs());
    }
}