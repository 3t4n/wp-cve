<?php

namespace WordressLaravel\Wp;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use Illuminate\Support\Facades\Log;

class ApiActions {

    public $siteUrl = '';
    public $count = 10;
    public $offset = 0;
    public $check = 0;
    public $qtyData = [];

    public $woocommerce = false;

    public function __construct() {
        $this->siteUrl = get_site_url();
        $this->count = !empty($_GET['count']) ? (int)$_GET['count'] : $this->count;
        $this->offset = !empty($_GET['offset']) ? (int)$_GET['offset'] : $this->offset;

        $wc_access = get_option('wc_api_access');
        $this->check = (!empty($wc_access) && ($wc_access == 'yes')) ? true : false;

        $this->qtyData = ['per_page' => $this->count, 'offset' => $this->offset];

        $wc_api_data = get_option('wc_api_data');
        $wc_api_data = (!empty($wc_api_data) ) ? $wc_api_data : [];

        $consumer_key = !empty( $wc_api_data['consumer_key']) ?  $wc_api_data['consumer_key'] : 'ck_4c0162ca9026fcdd366f5a0f0bc8fc30cb51695f';
        $consumer_secret = !empty( $wc_api_data['consumer_secret']) ? $wc_api_data['consumer_secret'] : 'cs_aca670f4b1efc8adda0985d4b109456d20cf7d6d';

        $this->woocommerce = new Client(
            $this->siteUrl,
            $consumer_key,
            $consumer_secret,
            [
                'wp_api' => true,
                'version' => 'wc/v3'
            ]
        );

    }

    /**
     * Function for initializing all API requests
     *
     */
    public function api_functions( ) {

        /* Get Products - http://spacinsider.loc/wp-json/laravel_wordpress/getProducts */
        register_rest_route(
            'laravel_wordpress',
            '/getProducts/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_products_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Get Products By Id's - http://spacinsider.loc/wp-json/laravel_wordpress/getProductsByIds */
        register_rest_route(
            'laravel_wordpress',
            '/getProductsByIds/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_products_by_ids_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Get Product Variants - http://spacinsider.loc/wp-json/laravel_wordpress/getProductVariants */
        register_rest_route(
            'laravel_wordpress',
            '/getProductVariants/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_product_variants_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Get Product - http://spacinsider.loc/wp-json/laravel_wordpress/getProduct */
        register_rest_route(
            'laravel_wordpress',
            '/getProduct/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_product_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Get Orders - http://spacinsider.loc/wp-json/laravel_wordpress/getOrders */
        register_rest_route(
            'laravel_wordpress',
            '/getOrders/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_orders_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Get Pages - http://spacinsider.loc/wp-json/laravel_wordpress/getPages */
        register_rest_route(
            'laravel_wordpress',
            '/getPages/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_pages_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Get Page - http://spacinsider.loc/wp-json/laravel_wordpress/getPage */
        register_rest_route(
            'laravel_wordpress',
            '/getPage/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_page_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Get Store Settings - http://spacinsider.loc/wp-json/laravel_wordpress/getStore*/
        register_rest_route(
            'laravel_wordpress',
            '/getStore/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_store_settings_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Get Checking Woocoomerce - http://spacinsider.loc/wp-json/laravel_wordpress/checkWoocommerce */
        register_rest_route(
            'laravel_wordpress',
            '/checkWoocommerce/',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'check_woocommerce_func' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Getting Woocommerce APi Response - http://spacinsider.loc/wp-json/laravel_wordpress/getWcData */
        register_rest_route(
            'laravel_wordpress',
            '/getWcData/',
            array(
                'methods'  => 'POST',
                'callback' => array( $this, 'get_wc_data_function' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Getting Woocommerce APi Response - http://spacinsider.loc/wp-json/laravel_wordpress/includeScriptFile */
        register_rest_route(
            'laravel_wordpress',
            '/include-script/',
            array(
                'methods'  => 'POST',
                'callback' => array( $this, 'include_api_script' ),
                'permission_callback' => '__return_true',
            )
        );

        /* Manage Orders - http://spacinsider.loc/wp-json/laravel_wordpress/manageOrders
        register_rest_route(
            'laravel_wordpress',
            '/manageOrders/',
            array(
                'methods'  => 'POST',
                'callback' => array( $this, 'manage_orders_function' ),
                'permission_callback' => '__return_true',
            )
        );
        */

    }

    /**
     * Function API for getting all woocommerce products
     *
     * @throws \Exception
     */
    public function get_products_function() {

        $this->security_system();

        $result = ['products' => []];

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && !empty($this->check)) {
            /*
            $response = wp_remote_get( $this->siteUrl . "/wp-json/wc/v3/products?per_page=" . $this->count );
            $response = json_decode( $response['body'], true );
            */

            try {
                $response = $this->woocommerce->get('products', $this->qtyData);
                $result['products'] = $response;
            } catch (HttpClientException $e) {

            }
        }

        wp_send_json( $result, 200 );
    }


    /**
     * Function API for getting all woocommerce products
     *
     * @throws \Exception
     */
    public function get_products_by_ids_function() {

        $this->security_system();

        $result = ['products' => []];

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && !empty($this->check)) {
            try {
                $productIds = !empty($_GET['ids']) ? explode(',', $_GET['idd']) : [];
                if (!empty($productIds)) {
                    foreach ($productIds as $product) {
                        $response = $this->woocommerce->get("products/{$product}");
                        $result['products'][] = $response;
                    }
                }
            } catch (HttpClientException $e) {

            }
        }

        wp_send_json( $result, 200 );
    }


    /**
     * Function API for checking if woocommerce is activated
     *
     * @throws \Exception
     */
    public function check_woocommerce_func() {

        $this->security_system();

        $res = (is_plugin_active('woocommerce/woocommerce.php')) ? true : false;

        wp_send_json( $res, 200 );
    }


    /**
     * Function API for getting all woocommerce product
     *
     * @throws \Exception
     */
    public function get_product_function() {

        $this->security_system();

        $result = ['product' => []];

        $product_id = !empty($_GET['id']) ? (int)$_GET['id'] : 0;

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && !empty($this->check) ) {
            /*
            $response = wp_remote_get( $this->siteUrl . "/wp-json/wc/v3/products/$product_id" );
            $response     = json_decode( $response['body'], true );
            */

            try {
                $response = $this->woocommerce->get("products/$product_id");
                $result['product'] = $response;
            } catch (HttpClientException $e) {

            }
        }

        wp_send_json( $result, 200 );
    }

    /**
     * Function API for getting all woocommerce products
     *
     * @throws \Exception
     */
    public function get_product_variants_function() {

        $this->security_system();

        $result = ['variants' => []];
        $product_id = !empty($_GET['id']) ? (int)$_GET['id'] : 0;

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && !empty($this->check) ) {
            /*
            $response = wp_remote_get( $this->siteUrl . "/wp-json/wc/v3/products/$product_id/variations");
            $response     = json_decode( $response['body'], true );
            */

            try {
                $response = $this->woocommerce->get("products/$product_id/variations");
                $result['variants'] = $response;
                $result['product_id'] = $product_id;
            } catch (HttpClientException $e) {

            }
        }

        wp_send_json( $result, 200 );
    }


    /**
     * Function API for getting all woocommerce orders
     *
     * @throws \Exception
     */
    public function get_orders_function() {

        $this->security_system();

        $result = ['orders' => []];

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && !empty($this->check) ) {
            /*
            $response = wp_remote_get( $this->siteUrl . "/wp-json/wc/v3/orders?per_page=" . $this->count );
            $response     = json_decode( $response['body'], true );
            */

            try {
                $response = $this->woocommerce->get('orders', $this->qtyData);
                $result['orders'] = $response;
            } catch (HttpClientException $e) {
            }

        }

        wp_send_json( $result, 200 );
    }

    /**
     * Function API for getting pages
     *
     * @throws \Exception
     */
    public function get_pages_function() {

        $this->security_system();

        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'number' => $this->count,
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );

        $result['pages'] = get_pages($args);

        wp_send_json( $result, 200 );

    }


    /**
     * Function API for getting page
     *
     * @throws \Exception
     */
    public function get_page_function() {

        $this->security_system();
        $page_id = !empty($_GET['id']) ? (int)$_GET['id'] : 0;
        $result['page'] = get_page($page_id);

        wp_send_json( $result, 200 );
    }


    /**
     * Function API for getting Woocommerce API response for api keys
     *
     * @throws \Exception
     */
    public function get_wc_data_function() {

        $rest_json = file_get_contents("php://input");
        $post      = json_decode( $rest_json, true );

        if ( !empty($post['consumer_key']) && !empty($post['consumer_secret']) ) {
            $wc_access = get_option('wc_api_data');

            if (isset($wc_access)) {
                update_option('wc_api_data', $post);
            }
            else {
                add_option('wc_api_data', $post);
            }
        }

        wp_send_json( ['status' => 200], 200 );
    }

    /**
     * Function API for including application script
     *
     * @throws \Exception
     */
    public function include_api_script() {

        $this->security_system();
        $rest_json = file_get_contents("php://input");
        $post      = json_decode( $rest_json, true );

        $file_src = $post['file_src'];

        $api_file_link = get_option('api_file_link');
        $api_file_link = $api_file_link ?? '';

        if (!empty($api_file_link)) {
            update_option('api_file_link', $file_src);
        }
        else {
            add_option('api_file_link', $file_src);
        }

        wp_send_json(['status' => 200], 200);
    }

    /**
     * Function API for getting store settings
     *
     * @throws \Exception
     */
    public function get_store_settings_function() {

        $this->security_system();

        $domain    = str_replace(['http://', 'https://'], ['', ''], $this->siteUrl);
        $siteName  = get_bloginfo('name');
        $siteEmail = get_bloginfo('admin_email');
        $timezone  = get_option('timezone_string');

        $check = ( is_plugin_active( 'woocommerce/woocommerce.php' ) && !empty($this->check) ) ? true : false;

        $country   = !empty($check) ? get_option('woocommerce_default_country') : "";
        $zip_code  = !empty($check) ? get_option('woocommerce_store_postcode') : "";
        $currency  = !empty($check) ? get_option('woocommerce_currency') : "";
        $address1  = !empty($check) ? get_option('woocommerce_store_address') : "";
        $address2  = !empty($check) ? get_option('woocommerce_store_address_2') : "";
        $city      = !empty($check) ? get_option('woocommerce_store_city') : "";
        $weight    = !empty($check) ? get_option('woocommerce_weight_unit') : "";
        $taxes     = !empty($check) ? !empty(get_option('woocommerce_calc_taxes')) ? true : null : null;

        $shop = [
            "shop" => [
                "id" => 1,
                "name" => $siteName,
                "email" => $siteEmail,
                "domain" => $domain,
                "country" => $country,
                "address1" => $address1,
                "address2" => $address2,
                "zip" => $zip_code,
                "city" => $city,
                "phone" => "",
                "primary_locale" => "en",
                "created_at" => '',
                "updated_at" => '',
                "country_code" => "",
                "country_name" => $country,
                "currency" => $currency,
                "customer_email" => $siteEmail,
                "timezone" => $timezone,
                "iana_timezone" => $timezone,
                "shop_owner" => $siteName,
                "money_format" => "{{amount}}",
                "money_with_currency_format" => "{{amount}} " . $currency,
                "weight_unit" => $weight,
                "taxes_included" => $taxes,
                "myshopify_domain" => $domain,
                "money_in_emails_format" => "{{amount}}",
                "money_with_currency_in_emails_format" => "{{amount}} " . $currency
            ],
            "status_code" => 200,
            "api_limit"   => 0,
        ];


        wp_send_json( $shop, 200 );
    }


    /**
     * Function for security system
     *
     *
     */
    private function security_system() {

        $headers = array();
        foreach ( $_SERVER as $name => $value ) {
            if ( substr($name, 0, 5 ) == 'HTTP_' ) {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        $config = include plugin_dir_path(__DIR__) . 'config/config.php';

        /* To client */
        $api_token = !empty($config['token']) ? $config['token'] : '';
        $api_platform =  !empty($config['platform']) ? $config['platform'] : '';

        if ( $headers['Token'] != $api_token ) {
            $this->sendError( true, 'Invalid token' );
        }

        if ( $headers['Platform'] != $api_platform ) {
            $this->sendError( true, 'Invalid platform header' );
        }

        return true;
    }


    /**
     * Function for sending REST API error
     *
     * @param $error
     * @param $message
     */
    private function sendError( $error, $message ) {
        wp_send_json( array( 'error'=> $error, 'message' => $message ), 400 );
    }

}
