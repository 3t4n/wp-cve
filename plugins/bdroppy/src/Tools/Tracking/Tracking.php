<?php
namespace BDroppy\Tools\Tracking;

if ( ! defined( 'ABSPATH' ) ) exit;

use BDroppy\Init\Core;

class  Tracking
{

    private $baseURL = '';
    private $user_id = null;
    private $events_queue = array();
    private $single_item_tracked = false;
    private $ignore_for_events = [];
    private $product_brand_taxonomy = 'none';
    private $identify_call_data = false;
    private $ignore_for_roles = [];
    private $has_events_in_cookie = false;
    private $woo = false;

    private $core;
    private $remote;
    private $loader;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->loader = $core->getLoader();
        $this->remote = $core->getRemote();
        $this->loader->addAction( 'plugins_loaded', $this, 'init' );
        $this->baseURL = "https://prod.bdroppy.com";

        $this->loader->addAction( 'wp_head', $this, 'render_snippet' );
        $this->loader->addAction( 'wp_head', $this, 'woocommerce_tracking' );
        $this->loader->addAction( 'wp_footer', $this, 'woocommerce_footer_tracking' );

    }

    public function init()
    {
        global $woocommerce;

        $this->woo = function_exists('WC') ? WC() : $woocommerce;
        $this->ensure_uid();
        $this->accept_tracking = true;

        $response = $this->remote->getMe();

        $this->user_id = $response['body']->_id;

    }



    public function ensure_hooks(){

        // general tracking snipper hook
        $this->loader->addAction( 'wp_head', $this, 'render_snippet' );
        $this->loader->addAction( 'wp_head', $this, 'woocommerce_tracking' );
        $this->loader->addAction( 'wp_footer', $this, 'woocommerce_footer_tracking' );



        // background events tracking
//        add_action('woocommerce_add_to_cart', array($this, 'add_to_cart'), 10, 6);
//        add_action('woocommerce_before_cart_item_quantity_zero', array($this, 'remove_from_cart'), 10);
//        add_action('woocommerce_remove_cart_item', array($this, 'remove_from_cart'), 10);
//        add_filter('woocommerce_applied_coupon', array($this, 'applied_coupon'), 10);

        // hook on new order placed
//        add_action('woocommerce_checkout_order_processed', array($this, 'new_order_event'), 10);
//
//        // hook on WooCommerce subscriptions renewal
//        add_action('woocommerce_subscriptions_renewal_order_created', array($this, 'new_subscription_order_event'), 10, 4);
//
//        // hook on WooCommerce order update
//        add_action('woocommerce_order_status_changed', array($this, 'order_status_changed'), 10, 3);
//
//        // cookie clearing actions
//        add_action('wp_ajax_metrilo_chunk_sync', array($this, 'sync_orders_chunk'));
//
//        add_action('admin_menu', array($this, 'setup_admin_pages'));

    }


    public function woocommerce_footer_tracking(){
        if(count($this->events_queue) > 0) $this->render_footer_events();
    }


    public function ensure_uid(){
        $this->cbuid = $this->session_get('ensure_cbuid');

        if(!$this->cbuid){
            $this->cbuid = md5(uniqid(rand(), true)) . rand();
            $this->session_set('ensure_cbuid', $this->cbuid);
        }
    }

    public function session_get($k){
        if(!is_object($this->woo->session)){
            return isset($_COOKIE[$k]) ? $_COOKIE[$k] : false;
        }
        return $this->woo->session->get($k);
    }

    public function session_set($k, $v){
        if(!is_object($this->woo->session)){
            @setcookie($k, $v, time() + 43200, COOKIEPATH, COOKIE_DOMAIN);
            $_COOKIE[$k] = $v;
            return true;
        }
        return $this->woo->session->set($k, $v);
    }

    public function process_cookie_events(){
        $items = $this->get_items_in_cookie();
        if(count($items) > 0){
            $this->has_events_in_cookie = true;
            foreach($items as $event){
                // put event in queue for sending to the JS library
                $this->put_event_in_queue($event['method'], $event['event'], $event['params']);
            }
        }

        // check if identify data resides in the session
        $identify_data = $this->get_identify_data_in_cookie();
        if(!empty($identify_data)) $this->identify_call_data = $identify_data;
    }

    public function check_for_bdroppy_clear(){
//        if(!empty($_REQUEST) && !empty($_REQUEST['bdroppy_clear'])){
//            $this->clear_items_in_cookie();
//            wp_send_json_success();
//        }
    }


    public function resolve_product($product_id)
    {
        if(function_exists('wc_get_product')){
            return wc_get_product($product_id);
        }else{
            return get_product($product_id);
        }
    }

    public function check_if_event_should_be_ignored($event)
    {
        if(empty($this->ignore_for_events))
        {
            return false;
        }
        if(in_array($event, $this->ignore_for_events))
        {
            return true;
        }
        return false;
    }

    public function put_event_in_queue($method, $event = '', $params = [])
    {
        if($this->check_if_event_should_be_ignored($method)){
            return true;
        }
        if($this->check_if_event_should_be_ignored($event)){
            return true;
        }
        array_push($this->events_queue, $this->prepare_event_for_queue($method, $event, $params));
    }

    public function prepare_event_for_queue($method, $event, $params){
        return array('method' => $method, 'event' => $event, 'params' => $params);
    }

    public function prepare_variation_data($variation_id, $variation = false){
        // prepare variation data array
        $variation_data = array('id' => $variation_id, 'name' => '', 'price' => '', 'sku' => '');

        // prepare variation name if $variation is provided as argument
        if($variation){
            $variation_attribute_count = 0;
            foreach($variation as $attr => $value){
                $variation_data['name'] = $variation_data['name'] . ($variation_attribute_count == 0 ? '' : ', ') . $value;
                $variation_attribute_count++;
            }
        }

        // get variation price from object
        if(function_exists('wc_get_product')){
            $variation_obj = wc_get_product($variation_id);
        }else{
            $variation_obj = new WC_Product_Variation($variation_id);
        }
        $variation_data['price'] = $this->object_property($variation_obj, 'variation', 'price');
        if($variation_obj) {
            if(empty($variation_data['name'])) {
                $variation_data['name'] = $variation_obj->get_title();
            }
            $variation_data['sku'] = $variation_obj->get_sku();
        }
        // return
        return $variation_data;
    }

    public function get_user_property($object, $property){
        switch($property) {
            case 'id':
                return method_exists($object, 'get_id') ? $object->get_id() : $object->id;
        }
    }
    public function get_variation_property($object, $property){
        switch($property) {
            case 'price':
                return method_exists($object, 'get_price') ? $object->get_price() : $object->price;
        }
    }
    public function get_order_property($object, $property){
        switch($property) {
            case 'id':
                return method_exists($object, 'get_id') ? $object->get_id() : $object->id;
            case 'payment_method_title':
                return method_exists($object, 'get_payment_method_title') ? $object->get_payment_method_title() : $object->payment_method_title;
            case 'billing_company':
                return method_exists($object, 'get_billing_company') ? $object->get_billing_company() : $object->billing_company;
            case 'billing_address_1':
                return method_exists($object, 'get_billing_address_1') ? $object->get_billing_address_1() : $object->billing_address_1;
            case 'billing_address_2':
                return method_exists($object, 'get_billing_address_2') ? $object->get_billing_address_2() : $object->billing_address_2;
            case 'billing_country':
                return method_exists($object, 'get_billing_country') ? $object->get_billing_country() : $object->billing_country;
            case 'billing_postcode':
                return method_exists($object, 'get_billing_postcode') ? $object->get_billing_postcode() : $object->billing_postcode;
            case 'billing_state':
                return method_exists($object, 'get_billing_state') ? $object->get_billing_state() : $object->billing_state;
            case 'billing_city':
                return method_exists($object, 'get_billing_city') ? $object->get_billing_city() : $object->billing_city;
            case 'billing_phone':
                return method_exists($object, 'get_billing_phone') ? $object->get_billing_phone() : $object->billing_phone;
        }
    }


    public function object_property($object, $type, $property){
        if($type == 'user'){
            return $this->get_user_property($object, $property);
        }
        if($type == 'order'){
            return $this->get_order_property($object, $property);
        }
        if($type == 'variation'){
            return $this->get_variation_property($object, $property);
        }
    }

    public function getImgUrl($productId) {
        $image_id = get_post_thumbnail_id($productId);
        return wp_get_attachment_image_src($image_id, 'full')[0];
    }

    public function prepare_product_hash($product, $variation_id = false, $variation = false)
    {
        $product_id = method_exists($product, 'get_id') ? $product->get_id() : $product->id;
        $product_hash = array(
            'id'    => $product_id,
            'name'  => $product->get_title(),
            'price' => $product->get_price(),
            'url'   => get_permalink($product_id),
            'sku'   => $product->get_sku()
        );

        if($variation_id){
            $variation_data = $this->prepare_variation_data($variation_id, $variation);
            $product_hash['option_id'] = $variation_data['id'];
            $product_hash['option_name'] = $variation_data['name'];
            $product_hash['option_price'] = $variation_data['price'];
            $product_hash['option_sku'] = $variation_data['sku'];
        }
        // fetch image URL
        $image_url = $this->getImgUrl($product_id);
        if($image_url) $product_hash['image_url'] = $image_url;

        // fetch the categories
        $categories_list = array();
        $categories = wp_get_post_terms($product_id, 'product_cat');
        if(!empty($categories)){
            foreach($categories as $cat){
                array_push($categories_list, array('id' => $cat->term_id, 'name' => $cat->name));
            }
        }

        // fetch brand taxonomy if available
        if($this->product_brand_taxonomy != 'none'){
            $brand_name = $product->get_attribute($this->product_brand_taxonomy);
            if(!empty($brand_name)){
                array_push($categories_list, array('id' => 'brand_'.$brand_name, 'name' => 'Brand: '.$brand_name));
            }
        }

        // include list of categories if any
        if(!empty($categories_list)) $product_hash['categories'] = $categories_list;

        // return
        return $product_hash;
    }

    public function prepare_category_hash($category){
        $category_hash = array(
            'id'    =>	$category->term_id,
            'name'  => 	$category->name
        );
        return $category_hash;
    }


    public function woocommerce_tracking()
    {
        // check if woocommerce is installed
        if(class_exists('WooCommerce')){
            /** check certain tracking scenarios **/
            // if visitor is viewing    product
            if(!$this->single_item_tracked && is_product()){
                $product = $this->resolve_product(get_queried_object_id());
                $this->put_event_in_queue('track', 'view_product', $this->prepare_product_hash($product));
                $this->single_item_tracked = true;
            }

            // if visitor is viewing product category
            if(!$this->single_item_tracked && is_product_category()){
                $this->put_event_in_queue('track', 'view_category', $this->prepare_category_hash(get_queried_object()));
                $this->single_item_tracked = true;
            }

            // if visitor is viewing shopping cart page
            if(!$this->single_item_tracked && is_cart()){
                $this->put_event_in_queue('track', 'view_cart', array());
                $this->single_item_tracked = true;
            }
            // if visitor is anywhere in the checkout process
            if(!$this->single_item_tracked && is_order_received_page()){

                $this->put_event_in_queue('track', 'pageview', 'Thank You');
                $this->single_item_tracked = true;

            }elseif(!$this->single_item_tracked && function_exists('is_checkout_pay_page') && is_checkout_pay_page()){
                $this->put_event_in_queue('track', 'checkout_payment', array());
                $this->single_item_tracked = true;
            }elseif(!$this->single_item_tracked && is_checkout()){
                $this->put_event_in_queue('track', 'checkout_start', array());
                $this->single_item_tracked = true;
            }
        }

        // ** GENERIC WordPress tracking - doesn't require WooCommerce in order to work **//

        // if visitor is viewing homepage or any text page
        if(!$this->single_item_tracked && is_front_page()){
            $this->put_event_in_queue('track', 'pageview', 'Homepage');
            $this->single_item_tracked = true;
        }elseif(!$this->single_item_tracked && is_page()){
            $this->put_event_in_queue('track', 'pageview', get_the_title());
            $this->single_item_tracked = true;
        }

        // if visitor is viewing post
        if(!$this->single_item_tracked && is_single()){
            $post_id = get_the_id();
            $this->put_event_in_queue('track', 'view_article', array('id' => $post_id, 'name' => get_the_title(), 'url' => get_permalink($post_id)));
            $this->single_item_tracked = true;
        }

        // if nothing else is tracked - send pageview event
        if(!$this->single_item_tracked){
            $this->put_event_in_queue('pageview');
        }

        // check if there are events in the queue to be sent to bdroppy
        if($this->identify_call_data !== false) $this->render_identify();
        if(count($this->events_queue) > 0) $this->render_events();
    }

    public function render_snippet(){

        // check if we should track data for this user (if user is available)
        if( !is_admin() && is_user_logged_in()){
            $user = wp_get_current_user();
            if($user->roles && $this->ignore_for_roles){
                foreach($user->roles as $r){
                    if(in_array($r, $this->ignore_for_roles)){
                        $this->accept_tracking = false;
                    }
                }
            }
        }

        // render the JS tracking code
        include_once(BDROPPY_PATH.'/includes/tools/tracking/js.php');
    }

    public function render_footer_events(){
        include_once(BDROPPY_PATH.'/includes/tools/tracking/render_footer_tracking_events.php');
    }

    public function render_events(){
        include_once(BDROPPY_PATH.'/includes/tools/tracking/render_tracking_events.php');
    }

    public function render_identify(){

        include_once(BDROPPY_PATH.'/includes/tools/tracking/render_identify.php');
    }
}