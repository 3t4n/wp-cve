<?php

class pisol_corw_addProductToCart{

    static function addFromOrder($order){
        $products = self::getProductsFromOrder($order);

        if(empty($products)) return pisol_corw_reorder_front::message(__('Error','cancel-order-request-woocommerce'),__('No product found in the order', 'cancel-order-request-woocommerce'), 'error');

        return self::addProductToCart($products);

    }

    static function getProductsFromOrder($order){
        $order = self::getOrder($order);
        $products = array();

        if(empty($order)) return $products;

        $items = $order->get_items();

        foreach($items as $item){
            $product_id = $item->get_product_id();
            $variation_id = $item->get_variation_id();
            if(!empty($variation_id) && $variation_id != 0){
                $meta = self::getVariationMeta($variation_id, $item);
            }else{
                $meta = array();
            }

            $qty = $item->get_quantity();
            $products[] = array(
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'quantity' => $qty,
                'meta_data' => $meta,
                'extra' => apply_filters('woocommerce_order_again_cart_item_data', array(), $item, $order)
            );
        }

        return $products;

    }

    static function getVariationMeta($variation_id, $item){
        $variation_meta = wc_get_product_variation_attributes($variation_id);
        $final_meta = array();
        foreach($variation_meta as $meta_key => $val){
            $search_key = str_replace('attribute_','',$meta_key);
            $val = $item->get_meta($search_key);
            if($val){
                $final_meta[$meta_key] = $val;
            }
        }
        return $final_meta;
    }

    static function addProductToCart($products){

        if(empty($products)) return pisol_corw_reorder_front::message(__('Error','cancel-order-request-woocommerce'),__('No product found in the order', 'cancel-order-request-woocommerce'), 'error');

        $error = array();

        if(function_exists('WC') && isset(WC()->cart)){
            foreach($products as $product){
                $result = WC()->cart->add_to_cart(
                    $product['product_id'],
                    $product['quantity'],
                    $product['variation_id'],
                    $product['meta_data'],
                    $product['extra']
                );

                if(!$result){
                    $not_added[] = $product;
                }
            }
        }

        if(empty($not_added)){
            $navigation_link =self::linkToCartCheckout();
            return pisol_corw_reorder_front::message(__('Success','cancel-order-request-woocommerce'),__('All products from the order added to the cart', 'cancel-order-request-woocommerce').$navigation_link, 'success');
        }else{
            $product_list = self::listOfProductThatCantBeAdded($not_added);
            return pisol_corw_reorder_front::message(__('Warning','cancel-order-request-woocommerce'),sprintf(__('Below products have some changes in them so they cant be added, consider adding them manually %s', 'cancel-order-request-woocommerce'), $product_list), 'error');
        }
    }

    static function linkToCartCheckout(){
        $cart = wc_get_cart_url();
        $checkout = wc_get_checkout_url();
        return '<div class="pi-navigation-link">'.(!empty($cart) ? sprintf('<a href="%s">%s</a>', esc_url($cart), esc_html(get_option('pi_corw_reorder_go_to_cart_button_text','Cart'))) : "").(!empty($checkout) ? sprintf('<a href="%s">%s</a>', esc_url($checkout), esc_html(get_option('pi_corw_reorder_go_to_checkout_button_text','Checkout'))) : "").'</div>';
    }

    static function listOfProductThatCantBeAdded($products){
        $list = '';
        foreach($products as $product){
            if(!empty($product['variation_id'])){
                $obj = wc_get_product($product['variation_id']);
            }else{
                $obj = wc_get_product($product['product_id']);
            }
            if(!is_object($obj) || empty($obj)) continue;
            
            $list .= sprintf('<li><a href="%s" target="_blank">%s</a></li>',$obj->get_permalink(), $obj->get_title());
        }

        return !empty($list) ? sprintf('<ul class="pi-failed-product-list">%s</ul>',$list) : "";
    }

    static function getOrder($order){
        if(is_object($order)) return $order;

        $order = wc_get_order($order);

        if(is_object($order)) return $order;

        return false;
    }

}