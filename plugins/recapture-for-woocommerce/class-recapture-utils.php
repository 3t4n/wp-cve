<?php

class RecaptureUtils
{
    static $cart_id_cookie_key = 'racart';

    private static function get_recapture_host() {
        $custom = get_option('recapture_custom_recapture_host');

        if (empty($custom) || !$custom) {
            return 'https://app.recapture.io';
        }

        return $custom;
    }

    public static function get_loader_url() {
        $custom = get_option('recapture_custom_loader_url');

        if (empty($custom) || !$custom) {
            return 'https://cdn.recapture.io/sdk/v1/ra-queue.min.js';
        }

        return $custom;
    }

    static function send_request($endpoint, $data) {
        $url = self::get_recapture_host().$endpoint;

        if (isset($_COOKIE['ra_customer_id'])) {
            $customer = sanitize_text_field(wp_unslash($_COOKIE['ra_customer_id']));

            if (is_object($data)) {
                $data->customer = $customer;
            } else {
                $data['customer'] = $customer;
            }
        }

        wp_remote_post($url, [
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => false,
            'cookies' => [],
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'api-key' => self::get_api_key()
            ],
            'body' => json_encode($data)
        ]);
    }

    public static function send_cart($cart) {
        self::send_request('/beacon/js/cart', $cart);
    }

    public static function send_reviews(
        $external_id,
        $author,
        $email,
        $products
    ) {
        $products_to_review = array_filter($products, function ($product) {
            return $product->skip == false;
        });

        $review_data = array_map(function ($product) use ($author, $email) {
            return [
                'title' => $product->title,
                'detail' => $product->detail,
                'sku' => $product->sku,
                'product_id' => $product->product_id,
                'name' => $product->name,
                'author' => $author,
                'email' => $email,
                'product_url' => $product->product_url,
                'ratings' => [[
                    'label' => 'Rating',
                    'value' => $product->rating
                ]],
            ];
        }, $products_to_review);

        self::send_request('/beacon/order/review', [
            'external_id' => $external_id,
            'reviews' => $review_data
        ]);
    }

    public static function convert_cart($data) {
        self::send_request('/beacon/js/convert', $data);
    }

    public static function update_order($data) {
        self::send_request('/beacon/order/update', $data);
    }

    public static function set_cart_id($cart_id) {
        if (!headers_sent()) {
            $expires = time() + (YEAR_IN_SECONDS * 10);
            setcookie(self::$cart_id_cookie_key, $cart_id, $expires, '/');
            $_COOKIE[self::$cart_id_cookie_key] = $cart_id;
        }
    }

    public static function set_new_cart_id() {
        $new_id = self::generate_uuid();
        self::set_cart_id($new_id);
        return $new_id;
    }

    public static function set_cart_id_if_missing() {
        if (!isset($_COOKIE[self::$cart_id_cookie_key])) {
            self::set_new_cart_id();
        }
    }

    public static function get_cart_id() {
        // should have already been set in set_cart_id_if_missing
        if (isset($_COOKIE[self::$cart_id_cookie_key])) {
            return sanitize_text_field(wp_unslash($_COOKIE[self::$cart_id_cookie_key]));
        }

        return null;
    }

    private static function generate_uuid() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    static function url_encode($data) {
        return strtr(base64_encode($data), '+/=', '._-');
    }

    static function url_decode($data) {
        return base64_decode(strtr($data, '._-', '+/='));
    }

    public static function decode_array($base64, $assoc = false) {
        try {
            $decoded_base64 = self::url_decode($base64);
            return json_decode($decoded_base64, $assoc);
        } catch (Exception $e) {
            return null;
        }
    }

    public static function encode_array($data) {
        try {
            $json = json_encode($data);
            return self::url_encode($json);
        } catch (Exception $e) {
            return null;
        }
    }

    public static function get_api_key() {
        return get_option('recapture_api_key');
    }

    public static function delete_api_key() {
        delete_option('recapture_api_key');
    }

    public static function set_api_key($key) {
        update_option('recapture_api_key', $key);
    }

    public static function is_connected() {
        $key = self::get_api_key();
        return !empty($key) && $key != null;
    }

    public static function get_authenticator_token() {
        return get_option('recapture_authenticator_token');
    }

    public static function set_new_authenticator_token() {
        $token = self::generate_uuid();
        update_option('recapture_authenticator_token', $token);
        return $token;
    }

    public static function get_connect_url($platform, $return, $cancel) {
        $user = wp_get_current_user();

        $discount = self::get_discount_details();

        $query = join('&', [
            'platform='.urlencode($platform),
            'base='.urlencode(get_site_url()),
            'return='.urlencode($return),
            'return_cancel='.urlencode($cancel),
            'email='.urlencode($user->user_email),
            'display_name='.urlencode($user->display_name),
            'first_name='.urlencode($user->first_name),
            'last_name='.urlencode($user->last_name),
            'site_name='.urlencode(get_bloginfo('name')),
            'discount_code='.($discount ? urlencode($discount->code) : ''),
            'token='.self::set_new_authenticator_token()
        ]);

        return self::get_recapture_host().'/wordpress/connect?'.$query;
    }

    public static function get_cart_id_for_post($post_id) {
        return get_post_meta($post_id, '_recapture_cart_id', true);
    }

    public static function set_cart_id_for_post($post_id) {
        update_post_meta($post_id, '_recapture_cart_id', RecaptureUtils::get_cart_id());
    }

    public static function post_has_cart_id($post_id) {
        $cart_id = self::get_cart_id_for_post($post_id);
        return !empty($cart_id);
    }

    public static function create_or_update_unique_customer($data) {
        self::send_request('/beacon/js/create-unique-customer', $data);
    }

    public static function get_discount_details() {
        $discount = get_option('recapture_discount_code');

        if ($discount == false) {
            return null;
        }

        return (object) $discount;
    }

    public static function clear_discount_details() {
        delete_option('recapture_discount_code');
    }

    public static function get_server_variable($field) {
        if (!empty($_SERVER[$field])) {
            return sanitize_text_field(
                wp_unslash($_SERVER[$field])
            );
        }
        return null;
    }

    public static function get_real_ip() {
        //check ip from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return self::get_server_variable('HTTP_CLIENT_IP');
        }

        //to check ip is pass from proxy
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return self::get_server_variable('HTTP_X_FORWARDED_FOR');
        }

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            return self::get_server_variable('REMOTE_ADDR');
        }

        return null;
    }

    public static function get_order_for_reviews($hash) {
        $url = self::get_recapture_host().'/beacon/reviews/'.$hash;

        $response = wp_remote_post($url, [
            'method' => 'GET',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'cookies' => [],
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'api-key' => self::get_api_key()
            ]
        ]);

        if (is_wp_error($response)) {
            return null;
        }

        return json_decode($response['body']);
    }

    public static function get_cart_recovery_meta($cart) {
        $url = self::get_recapture_host().'/beacon/cart-recovery-meta/'.$cart;

        $response = wp_remote_post($url, [
            'method' => 'GET',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'cookies' => [],
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'api-key' => self::get_api_key()
            ]
        ]);

        if (is_wp_error($response)) {
            return null;
        }

        return json_decode($response['body']);
    }

    public static function replace_tag($input, $tag, $replacement) {
        // return preg_replace('\\{\\{(\\s*)?'.$tag.'(\\s*)\\}\\}', $replacement, $input);
        $pattern = '/\\{\\{(\\s*)?'.$tag.'(\\s*)\\}\\}/';
        return preg_replace($pattern, $replacement, $input);
    }
}