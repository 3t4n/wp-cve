<?php

class pisol_corw_reorder_front{

    function __construct(){

        $position_of_text_link = apply_filters('pisol_repeat_order_button_position_on_view_order_page', 'woocommerce_order_details_before_order_table');

        add_action($position_of_text_link , array($this, 'repeatOrderOnViewOrderPage'),9999,1);

        add_filter( 'woocommerce_my_account_my_orders_actions', array($this, 'reorderButton'),9999,2);

        add_action('wp_ajax_pi_reorder', array($this, 'reorderRequest'));

        add_action('wp_ajax_nopriv_pi_reorder', array($this, 'reorderRequest'));

        add_action('wp_ajax_pi_reorder_replace', array($this, 'reorderReplace'));

        add_action('wp_ajax_nopriv_pi_reorder_replace', array($this, 'reorderReplace'));

        add_action('wp_ajax_pi_reorder_merge', array($this, 'reorderMerge'));

        add_action('wp_ajax_nopriv_pi_reorder_merge', array($this, 'reorderMerge'));
    }

    function repeatOrderOnViewOrderPage($order){
        $show_on_view_order_page = get_option('pi_corw_show_reorder_on_view_order_page',1);
        if(self::allowReorder($order) && !empty($show_on_view_order_page)){
            $order_id = version_compare( WC_VERSION, '3.0.0', '<' ) ? $order->id : $order->get_id();
            $url = admin_url("admin-ajax.php?action=pi_reorder&order_id={$order_id}");
            $label = esc_html(get_option('pi_corw_reorder_button_text',__( 'Repeat Order', 'cancel-order-request-woocommerce' )));

            printf('<a href="%s" class="woocommerce-button button pi_reorder">%s</a>', $url, $label);
        }
    }

    function reorderButton($actions, $order){
        
        if(self::allowReorder($order)){
            $order_id = version_compare( WC_VERSION, '3.0.0', '<' ) ? $order->id : $order->get_id();

            $actions['pi_reorder'] = array(
                    'url'  => admin_url("admin-ajax.php?action=pi_reorder&order_id={$order_id}"),
                    'name' => esc_html(get_option('pi_corw_reorder_button_text',__( 'Repeat Order', 'cancel-order-request-woocommerce' )))
            );
        }

        return $actions;
    }

    function allowReorder($order){
        $reorder_button_on_status = pisol_corw_get_option('pi_corw_reorder_button', array());

        if(empty($reorder_button_on_status) || !is_array($reorder_button_on_status)) return false;

        $order_status = $order->get_status();

        if(in_array($order_status, $reorder_button_on_status) || in_array('all', $reorder_button_on_status)) return true;

        return false;
    }

    function reorderRequest(){
        $order_id = filter_input(INPUT_GET, 'order_id');
        if(empty($order_id)){
            wp_send_json(self::message(__('Error','cancel-order-request-woocommerce'),__('No Order id provided', 'cancel-order-request-woocommerce'), 'error'));
        }

        $order = wc_get_order($order_id);

        if(empty($order)){
            wp_send_json(self::message(__('Error','cancel-order-request-woocommerce'),sprintf(__('Order #%s does not exist', 'cancel-order-request-woocommerce'),esc_html($order_id)), 'error'));
        } 

        if(self::cartEmpty()){
            wp_send_json(self::addReorderProducts($order));
        }else{
            self::showOptions($order_id);
        }
        die;
    }

    function reorderReplace(){
        $order_id = filter_input(INPUT_GET, 'order_id');
        if(empty($order_id)){
            wp_send_json(self::message(__('Error','cancel-order-request-woocommerce'),__('No Order id provided', 'cancel-order-request-woocommerce'), 'error'));
        }

        $order = wc_get_order($order_id);

        if(empty($order)){
            wp_send_json(self::message(__('Error','cancel-order-request-woocommerce'),sprintf(__('Order #%s does not exist', 'cancel-order-request-woocommerce'),esc_html($order_id)), 'error'));
        }

        self::emptyCart();
        wp_send_json(self::addReorderProducts($order));
        
    }

    function reorderMerge(){
        $order_id = filter_input(INPUT_GET, 'order_id');
        if(empty($order_id)){
            wp_send_json(self::message(__('Error','cancel-order-request-woocommerce'),__('No Order id provided', 'cancel-order-request-woocommerce'), 'error'));
        }

        $order = wc_get_order($order_id);

        if(empty($order)){
            wp_send_json(self::message(__('Error','cancel-order-request-woocommerce'),sprintf(__('Order #%s does not exist', 'cancel-order-request-woocommerce'),esc_html($order_id)), 'error'));
        }

        wp_send_json(self::addReorderProducts($order));
    }

    static function emptyCart(){
        if (function_exists('WC') && isset(WC()->cart)){
            return WC()->cart->empty_cart();
        }
    }

    static function cartEmpty(){
        $total  = WC()->cart->get_cart_contents_count();
        if (function_exists('WC') && isset(WC()->cart) && WC()->cart->get_cart_contents_count() != 0 ) {
            return false;
        }
        return true;
    }

    static function message($heading, $msg, $action = 'error'){
        return array(
            'heading'=> $heading,
            'action'=> $action,
            'message' => $msg,
            'icon' => $action
        );
    }


    static function addReorderProducts($order){
        return pisol_corw_addProductToCart::addFromOrder($order);
    }

    static function showOptions($order_id){
        $replace_cart_url = add_query_arg(array(
            'order_id'=> $order_id,
            'action'=> 'pi_reorder_replace'
        ), admin_url('admin-ajax.php'));

        $merge_cart_url = add_query_arg(array(
            'order_id'=> $order_id,
            'action'=> 'pi_reorder_merge'
        ), admin_url('admin-ajax.php'));

        wp_send_json(array(
            'action'=>'options',
            'html'=>'<div class="pi-options-button"><a href="'.$replace_cart_url.'" class="pi-replace-cart pi-button">'.esc_html(get_option('pi_corw_reorder_replace_cart_button_text','Replace cart')).'</a><a href="'.$merge_cart_url.'" class="pi-merge-cart pi-button">'.esc_html(get_option('pi_corw_reorder_merge_cart_button_text','Merge in cart')).'</a></div>'
        ));
    }
}

new pisol_corw_reorder_front();