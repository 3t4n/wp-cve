<?php

class YektanetOrderHandler extends YektanetMainClass
{
    public function __construct()
    {
        parent::__construct();

        add_action('woocommerce_order_status_changed', function ($order_id, $this_status_transition_from, $this_status_transition_to, $instance) {
            $order = wc_get_order($order_id);
            $this->orderStatusChange($order, $this_status_transition_from, $this_status_transition_to);
        }, 10, 4);
    }


    private function orderStatusChange($order, $previous_status, $new_status)
    {
        $items_data = array();
        $items_data['previous_status'] = $previous_status;
        $items_data['status'] = $new_status;
        $utm_data = sanitize_text_field($_COOKIE['analytics_campaign']) ?: sanitize_text_field($_COOKIE['_ynsrc']);
        $utm_data = json_decode(stripslashes($utm_data), true);

        $counter = 0;
        foreach ($order->get_items() as $item_id => $item) {
            $product = wc_get_product($item->get_product_id());
            $categories = get_the_terms($product->get_id(), 'product_cat');
            $categories_data = array();
            foreach ($categories as $category) {
                $category_data = array();
                $category_data['name'] = $category->name;
                $category_data['id'] = $category->term_id;
                $parentcats = get_ancestors($category->term_id, 'product_cat');
                foreach ($parentcats as $cat) {
                    $category_data['name'] = get_term_by('id', $cat, 'product_cat')->name;
                    $category_data['id'] = $cat;
                }
                $categories_data[] = $category_data;
            }
            $item_data = array(
                'price' => $product->get_price(),
                'quantity' => $item->get_quantity(),
                'product_id' => $product->get_id(),
                'sku' => $this->getProductSku($product->get_id()),
                'total' => $item->get_total(),
                'url' => get_permalink($product->get_id()),
                'title' => $product->get_title(),
                'discount' => $this->getProductDiscount($item->get_product_id())
            );
            $image = wp_get_attachment_image_url($product->get_image_id(), 'full');
            if ($image) {
                $item_data['image'] = $image;
            }

            $item_data['categories'] = $categories_data;
            $items_data['items'][$counter] = $item_data;
            $counter++;
        }

        $data = array(
            'acm' => 'purchase',
            'aa' => 'product',
            'aef' => get_option('yektanet_app_id', true),
            'acb' => $order->get_id(),
            'ad' => get_site_url(),
            'ac' => $order->get_checkout_order_received_url(),
            'ace' => $order->get_total(),
            'ba' => array_key_exists('_yngt',$_COOKIE ) ? sanitize_text_field($_COOKIE['_yngt']) : '',
            'ai' => array_key_exists('analytics_session_token',$_COOKIE ) ? sanitize_text_field($_COOKIE['analytics_session_token']) : '',
            'aaa' => $utm_data['source'],
            'aab' => $utm_data['medium'],
            'ip' => $order->get_customer_ip_address(),
            'abg' => $order->get_customer_user_agent(),
            'acs' => json_encode($items_data),
            'acn' => $order->get_id(),
            'acf' => $order->get_currency(),
            'ach' => $order->get_total_discount(),
        );
        wp_remote_post(yektanet_ua_url . http_build_query($data), $this->setArgs());
    }
}