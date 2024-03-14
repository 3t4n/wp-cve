<?php

/**
 * 
 * 
 * Product Api get the data of products
 * 
 */

namespace Includes\CHLSRestApis;
defined('ABSPATH') || exit;

class CHLSRestApis 
{
    function __construct() {
        add_action('rest_api_init', function() {
            register_rest_route('api', 'products', array(
                'methods'             =>'GET',
                'callback'            => [$this ,'chls_get_product_api'],
                'permission_callback' => '__return_true'
            ));
            
            add_filter('rest_pre_serve_request', function($value) {
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Allow-Methods: GET');
                header('Access-Control-Allow-Headers: Authorization, Content-Type');
                return $value;
            });
        });
    }

    function chls_get_product_api() 
    { 
        $response = array();
        try {
            $options = get_option('channelize_live_shopping');
            if (empty($options['private_key'])) {
                 throw new \Exception('The plugin is not configured properly. Please configure the plugin.');
            }
            $this->chls_verify_private_key();

            $limit = isset($_GET['limit']) ? $_GET['limit'] : 25;
            $limit = sanitize_text_field($limit);

            $offset = isset($_GET['skip']) ? $_GET['skip'] : 0;
            $offset = sanitize_text_field($offset);

            $product_ids = isset($_GET['id']) ? $_GET['id'] : null;
            $product_ids = sanitize_text_field($product_ids);

            $product_title = isset($_GET['title']) ? $_GET['title'] : null;
            $product_title = sanitize_text_field($product_title);

            $response = $this->chls_get_products($limit, $offset, $product_title, $product_ids);

        } catch (\Exception $e) {
            $response['statusCode'] = 400;
            $response['message']    = $e->getMessage();
            $response['error']      = true;
            return $response;
        }
        return $response;
    }

    public function chls_verify_private_key()
    {
        try {
            $headers = apache_request_headers();
            if (!isset($headers['Authorization'])) {
                if (!isset($headers['authorization'])) {
                    throw new \Exception;
                } else {
                    $header_value = $headers['authorization'];
                }
            } else {
                $header_value = $headers['Authorization'];
            }
            $header_value = explode(" ", $header_value);
            if ($header_value[0] != 'Basic') {
                throw new \Exception;
            }
            $options = get_option('channelize_live_shopping');
            $private_key = base64_encode($options['private_key']);
            if ($private_key != $header_value[1]) {
               throw new \Exception;
            }
        } catch (\Exception $e) {
            throw new \Exception('Authentication failed');
            throw $e;
        }
    }

    public function chls_get_products($limit, $offset, $product_title, $product_ids)
    {
        global $wpdb;
        $response = array(
            'statusCode' => 200,
            'success'    => 'OK',
            'count'      => 0,
            'products'   => []
        );
        $filters = array(
            'post_status'   => 'publish',
            'post_type'     => 'product',
            'orderby'       => 'publish_date',
            'order'         => 'DESC',
            'limit'         => $limit,
            'offset'        => $offset,
        );
        try {
            if ($product_ids != NULL) {
                $product_ids = explode(",", $product_ids);
                $filters['include'] = $product_ids;
                $response = $this->chls_get_products_by_filters($filters);
                $counter = 0;
                foreach ($product_ids as  $product_id) {
                    $product_status = get_post_status($product_id);
                    if ($product_status != 'publish') {
                        continue;
                    }
                    $counter++;
                }
                $response['count'] = $counter;
                return $response;
            }

            if ($product_title == NULL) {
                $response = $this->chls_get_products_by_filters($filters);
                return $response;
            } else {
                $querystr = "SELECT ID FROM " . $wpdb->prefix . "posts where post_title LIKE '%$product_title%' and post_status = 'publish' and post_type = 'product'
                    ORDER BY post_date DESC
                    LIMIT $limit offset $offset ";
                $pageposts = $wpdb->get_results($querystr);
                $product_ids = array();
                foreach ($pageposts as $value) {
                    array_push($product_ids, $value->ID);
                }
                if(empty($product_ids)) {
                    return $response;
                }
                $filters = array(
                    'orderby' => 'publish_date',
                    'order'   => 'DESC',
                    'limit'   => $limit,
                    'include' => $product_ids,
                );
                $response = $this->chls_get_products_by_filters($filters);
                $querystring = "SELECT ID FROM " . $wpdb->prefix . "posts where post_title LIKE '%$product_title%' and post_status = 'publish' and post_type = 'product' ";
                $pagepost = $wpdb->get_results($querystring);
                $response['count'] = count($pagepost);
                return $response;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function chls_get_products_by_filters($filters)
    {
        $response = array(
            'statusCode' => 200,
            'success'    => 'OK',
            'count'      => 0,
            'products'   => []
        );
        $product_count = new \WP_Query($filters);
        $products      = wc_get_products($filters);
        try {
            if (empty($products and $product_count)) {
                return $response;
            }
            $response['count'] = $product_count->found_posts;
            foreach ($products as $key => $product) {
                $response['products'][$key] = $this->chls_get_a_product($product);
            }
            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function chls_get_a_product($product)
    {
        $product_data['id']      = $product->get_id();
        $product_data['title']   = $product->get_name();
        $product_data['price']   = $product->get_price();
        $product_data['sku']     = $product->get_sku();
        $product_data['product_url'] = get_permalink($product->get_id());
        $product_post_thumbnail = get_post_thumbnail_id($product->get_id());
        if(isset(wp_get_attachment_image_src($product_post_thumbnail, 'single-post-thumbnail')[0])) {
            $product_image = wp_get_attachment_image_src($product_post_thumbnail, 'single-post-thumbnail')[0];
        } 
        if (isset($product_image) == null) {
            $product_image = wc_placeholder_img_src();
        }
        $product_data['image']       = $product_image;
        $product_data['description'] = $product->get_short_description();
        $product_data['currency']    = get_woocommerce_currency();
        return $product_data;
    }
}