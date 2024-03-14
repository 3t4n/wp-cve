<?php

class RecaptureBasePlatform {
    function __construct() {
    }

    function get_name() {
        return 'unknown';
    }

    function remove_actions() {
    }

    function add_actions() {
    }

    function regenerate_cart_from_url($cart, $contents) {
    }

    function is_product_page() {
        return false;
    }

    public function enqueue_scripts() {
    }

    public static function get_base_path($url = true) {
        $path = dirname(__FILE__);
        return $url
            ? plugin_dir_url($path)
            : plugin_dir_path($path);
    }

    public static function save_reviews($external_id, $author, $email, $products) {
        // save the reviews
    }

    public static function get_product_url($external_id) {
        // return the product url based on an internal id
        return null;
    }

    public static function get_customer_email_from_order($order_id) {
        return '';
    }

    public static function get_customer_name_from_order($order_id) {
        return '';
    }

    public static function get_customer_first_name_from_order($order_id) {
        return '';
    }

    public static function supports_reviews() {
        return true;
    }

    public static function reviews_have_title() {
        return true;
    }

    public static function create_unique_discount_code($spec) {
        $expires = date('c', strtotime('+1 month'));

        return (object) [
            'code' => 'EXAMPLE_DISCOUNT',
            'expires' => $expires
        ];
    }

    public static function delete_unique_discount_code($code) {
        return false;
    }

    public static function find_products($filter) {
        return [];
    }
}