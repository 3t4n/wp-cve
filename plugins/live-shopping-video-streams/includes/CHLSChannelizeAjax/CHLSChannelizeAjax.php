<?php


/**
 * Ajax handle function for channelize live shoping plugin
 * 
 */

namespace Includes\CHLSChannelizeAjax;

defined('ABSPATH') || exit;

class CHLSChannelizeAjax
{

    public $ajaxPrefix = 'channelize_live_shopping_';

    public $ajaxActions = array(
        'update_cart_item',
        'get_cart_details',
        'add_to_cart',
        'get_product',
        'get_all_products',
        'update_settings',
    );

    public function register()
    {

        foreach ($this->ajaxActions as $action) {
            add_action('ch_lsc_ajax_' . $this->ajaxPrefix . $action, array($this, $action));
        }

        add_action('template_redirect',  array($this, 'do_channelize_live_shopping_ajax'), 0);
    }

    function do_channelize_live_shopping_ajax()
    {

        if (!empty($_GET['ch-lsc-ajax'])) {
            $action = sanitize_text_field(wp_unslash(sanitize_text_field($_GET['ch-lsc-ajax'])));
        }
        if (isset($action) && in_array($action, $this->ajaxActions)) {
            define("DOING_AJAX", true);
            $action = sanitize_text_field($action);

            do_action('ch_lsc_ajax_' . $this->ajaxPrefix . $action);
            wp_die();
        }
    }

    /* Get Cart Item Details Ajax Handle Function */
    function get_cart_details()
    {
        ob_start();
        $response = array();
        $response['status'] = 200;
        $response['items'] = array();
        try {
            global $woocommerce;
            $i = 0;
            if ($woocommerce->cart->cart_contents_count > 0) {
                foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {

                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                        $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                        $thumbnail = $_product->get_image();

                        $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);


                        $thumbnail = isset(wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail')[0]) ? wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail')[0] : null;
                        if ($thumbnail == null) {
                            $thumbnail = wc_placeholder_img_src();
                        }

                        $product_price = $cart_item['data']->price;

                        $response['items'][$i] = $cart_item['data']->get_data();

                        $response['items'][$i]['product_id'] = $product_id;
                        $response['items'][$i]['name'] = $product_name;
                        $response['items'][$i]['thumbnail'] = $thumbnail;
                        $response['items'][$i]['permalink'] = $product_permalink;
                        $response['items'][$i]['price'] = $product_price;

                        $response['items'][$i]['quantity'] = $cart_item['quantity'];
                        $response['items'][$i]['cart_item_key'] = $cart_item_key;

                        $response['items'][$i]['variation_id'] = $cart_item['variation_id'];

                        $response['items'][$i]['variation'] = $cart_item['variation'];

                        $response['items'][$i]['regular_price'] = $_product->get_regular_price();
                        $response['items'][$i]['sale_price'] =  $_product->get_sale_price();
                    }
                    $i++;
                }
                $response['currency'] = get_woocommerce_currency();
                $response['subtotal'] = WC()->cart->get_subtotal();
                $response['total'] = WC()->cart->total;
                $response['total_discount'] = WC()->cart->get_subtotal() - WC()->cart->total;
            }
        } catch (Exception $e) {
            $response['error'] = 400;
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response);
        wp_die();
    }

    /* Update Item in Cart Ajax Handle Function */
    function update_cart_item()
    {

        $response = array(
            'status' => 400,
            'message' => ''
        );
        try {
            global $woocommerce;

            $product_id = sanitize_text_field($_POST['product_id']);
            $quantity = sanitize_text_field($_POST['quantity']);

            $cart_contents = $woocommerce->cart->get_cart();
            if (isset($quantity) && isset($product_id)) {

                if (get_post_status($product_id) == 'publish') {
                    if (count($cart_contents) > 0) {
                        $product_cart_item_key = 0;
                        foreach ($cart_contents as $cart_item_key => $cart_item) {
                            if ($cart_item['product_id'] == $product_id) {
                                $product_cart_item_key = $cart_item_key;
                            }
                        }
                    }
                    if ($product_cart_item_key != 0) {
                        try {
                            $woocommerce->cart->set_quantity($product_cart_item_key, $quantity);
                            $response['status'] = 200;
                            $response['success'] = true;
                            $response['message'] = 'Product ws updated successfully';
                        } catch (Exception $e) {
                            $response['error'] = true;
                            $response['message'] = $e->getMessage();
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Sorry! this product is not added or removed from the cart";
                    }
                } else {
                    $response['status'] = 404;
                    $response['error'] = true;
                    $response['message'] = "Sorry! product does not exist";
                }
            } else {
                $response['status'] = 400;
                $response['error'] = true;
                $response['message'] = "Invalid parameters!!";
            }
        } catch (Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response);
        wp_die();
    }

    /**
     * Add to Cart AJAX handle
     */

    public function add_to_cart()
    {
        global $woocommerce;

        $response = array(
            'status' => 400,
            'message' => ''
        );
        $product_details = sanitize_text_field($_POST['data']);
        $product_details = isset($product_details) ? $product_details : array();
        $product_details = json_decode(stripslashes($product_details));

        if (is_array($product_details)) {
            foreach ($product_details as $data) {
                if (isset($data->product_id)) {
                    $product_id        = $data->product_id;
                    $quantity          = isset($data->quantity) ? $data->quantity : 1;
                    $product           = wc_get_product($product_id);
                    $product_status    = get_post_status($product_id);
                    $variation_id      = 0;
                    $variation         = array();
                    if ($product_status  === 'publish') {

                        if ($product && 'variation' === $product->get_type()) {
                            $variation_id = $product_id;
                            $product_id   = $product->get_parent_id();
                            $variation    = $product->get_variation_attributes();
                        }
                        try {

                            $woocommerce->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
                            $response['status'] = 200;
                            $response['success'] = true;
                            $response['message'] = 'Success! Item added successfully';
                        } catch (Exception $e) {
                            $response['error'] = true;
                            $response['message'] = $e->getMessage();
                        }
                    } else {
                        $response[$product_id] = "Sorry! this product id is not available for purchase.";
                    }
                } else {
                    $response['message'] = "Sorry! Product Id is not valid.";
                    $response['error'] = true;
                }
            }
        } else {
            $response['message'] = "Sorry! data is not valid.";
            $response['error'] = true;
        }



        echo json_encode($response);
        wp_die();
    }


    /**
     * Get a single product handle
     */

    public function get_product()
    {
        $response = array(
            'status' => 400,
            'message' => '',
            'error'  => false
        );
        $product_ids =   sanitize_text_field($_GET['ids']);
        $product_ids =   isset($product_ids) ? $product_ids : null;
        if ($product_ids != null) {
            $product_ids = explode(",", $product_ids);
        }

        $product_details = array();
        if ($product_ids != NULL) {
            foreach ($product_ids as  $product_id) {
                if ($product_id == null) {
                    continue;
                }
                $product           = wc_get_product($product_id);
                $product_status    = get_post_status($product_id);
                if ($product_status  === 'publish') {
                    try {
                        $product = wc_get_product($product_id);
                        $product_details['products'][] = $this->get_a_product($product);
                        $product_details['currency'] = get_woocommerce_currency();
                    } catch (Exception $e) {
                        $response['error'] = true;
                        $response['message'] = $e->getMessage();
                    }
                } else {
                    $product_details[$product_id] = 'Sorry! invalid product id ' . $product_id;
                }
            }
        } else {
            $product_details['message'] = "Sorry! Product Id is not valid.";
            $product_details['error'] = true;
        }
        echo json_encode($product_details);
        wp_die();
    }



    /**
     * Get all products handle
     */

    public function get_all_products()
    {
        $response = array(
            'status' => 400,
            'message' => ''
        );
        $limit = sanitize_text_field($_POST['limit']);
        $limit = isset($limit) ? $limit : 10;

        $args = array(
            'orderby' => 'publish_date',
            'order' => 'DESC',
            'limit' => $limit,
        );
        $products = wc_get_products($args);
        $i = 0;
        $productsData['products'] = array();
        try {
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $productsData['products'][$key] = $this->get_a_product($product);
                }
                $productsData['currency'] = get_woocommerce_currency();
            }
        } catch (Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
            wp_die();
        }
        echo json_encode($productsData);
        wp_die();
    }



    public function get_a_product($product)
    {
        $product_data = array();
        $product_data =   $product->get_data();
        $product_variations = $product->get_attributes();

        $variations_children = $product->get_children();

        $catArr = array();
        $categories = $product->get_category_ids();

        $_product = wc_get_product($product->get_id());

        $product_data['regular_price'] = $_product->get_regular_price();
        $product_data['price'] = $_product->get_price();
        $product_data['sale_price'] = $_product->get_sale_price();
        $product_data['purchasable']  = $_product->is_purchasable();
        $product_data['type'] = $_product->get_type();

        $product_image = isset(wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail')[0]) ? wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail')[0] : null;
        if (isset($product_image) == null) {
            $product_image = wc_placeholder_img_src();
        }
        $product_data['image'] = $product_image;

        $gallery_image_ids = $_product->get_gallery_image_ids();
        $images = array();

        foreach ($gallery_image_ids as $attachment_id) {
            $images[] = $Original_image_url = wp_get_attachment_url($attachment_id);
        }
        $product_data['gallery_images'] = $images;
        foreach ($categories as $i => $id) {
            $catArr[$i]['id'] = $id;
            if ($term = get_term_by('id', $id, 'product_cat')) {
                $catArr[$i]['name'] = $term->name;
                $catArr[$i]['slug'] = $term->slug;
            }
        }
        $variation_details_array = array();

        foreach ($variations_children as $variation_id) {
            $variation_details = wc_get_product($variation_id);
            $variation_details_array_single = array();

            $variation_details_array_single = $variation_details->get_data();

            $attributes_product = $variation_details->get_attributes();


            if ($variation_details->is_type('variation')) {

                foreach ($variation_details->get_variation_attributes() as $attribute_name => $attribute) {
                    $name = wc_attribute_label(str_replace('attribute_', '', $attribute_name), $_product);
                    $slug = str_replace('attribute_', '', wc_attribute_taxonomy_slug($attribute_name));
                    $variation_details_array_single['attributes'][$name] = $attribute;
                    if (substr($slug, 0, 3) == 'pa_') {
                        $variation_details_array_single['attributes'][$name] = isset(get_term_by('slug', $attribute, $slug)->name) ? get_term_by('slug', $attribute, $slug)->name : null;
                    }

                    unset($variation_details_array_single['attributes'][$slug]);
                }
            }
            $variation_details_array_single['price'] = $variation_details->get_price();

            $variation_details_array_single['regular_price'] = $variation_details->get_regular_price();

            $variation_details_array_single['sale_price'] = $variation_details->get_sale_price();

            $variation_details_array_single['purchasable'] = $variation_details->is_purchasable();

            $image = isset(wp_get_attachment_image_src(get_post_thumbnail_id($variation_id), 'single-post-thumbnail')[0]) ? wp_get_attachment_image_src(get_post_thumbnail_id($variation_id), 'single-post-thumbnail')[0] : null;

            if (isset($image) == null || $image == '') {
                $image = $product_image;
            }
            $variation_details_array_single['image'] = $image;

            $variation_details_array[] = $variation_details_array_single;
        }
        $product_data['variations'] = $variation_details_array;
        $product_data['categories'] = $catArr;

        $attributes_product = $_product->get_attributes();
        $attribute_product_data = array();
        foreach ($attributes_product as $attribute) {
            $attributes_data = $attribute->get_data();
            $attributes_data['name']     = wc_attribute_label($attribute['name'], $_product);
            if (isset($attribute['is_taxonomy']) && $attribute['is_taxonomy']) {
                $attributes_data['options'] = wc_get_product_terms($_product->get_id(), $attribute['name'], array('fields' => 'names'));
            }

            $attribute_product_data[] = $attributes_data;
        }


        $product_data['attributes'] = $attribute_product_data;
        return $product_data;
    }

    public function update_settings()
    {   
        $options['enableMiniPlayer'] = sanitize_text_field($_POST['enableMiniPlayer']);
        update_option('channelize_live_shopping_settings', $options);
    }
}
