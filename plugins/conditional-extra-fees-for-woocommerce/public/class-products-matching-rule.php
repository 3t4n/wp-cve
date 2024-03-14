<?php

class pi_cefw_products_matching_rule{
    function __construct($fees_id, $cart){
        $this->fees_id = $fees_id;
        $this->cart = $cart;
    }

    function getProductQty($max_qty = '', $max_product_qty = '', $excluded_products= ''){
        $products = $this->cart->get_cart();
        $qt = 0;
        $excluded_products_list = self::excludedProductList($excluded_products);
        foreach($products as $product){
            $product_id = $product['product_id'];
            $variation_id = $product['variation_id'];

            if(in_array($product_id, $excluded_products_list) || in_array($variation_id, $excluded_products_list)) continue;
            
            if(filter_var($max_product_qty, FILTER_VALIDATE_INT) && $product['quantity'] > $max_product_qty){
                $qt += $max_product_qty;
            }else{
                $qt += $product['quantity'];
            }
            
        }

        if(filter_var($max_qty, FILTER_VALIDATE_INT) && $qt > $max_qty){
            return $max_qty;
        }

        return $qt;
    }

    static function excludedProductList($excluded_products){
        if(empty($excluded_products)) return array();

        $list = explode(',', $excluded_products);
        $list = array_map('trim', $list);
        return $list;
    }

    function getMatchedProductsQuantity($max_qty = '', $max_product_qty = '', $excluded_products= ''){
        $products = $this->cart->get_cart();
        $qt = 0;
        $excluded_products_list = self::excludedProductList($excluded_products);
        foreach($products as $product){
            $product_id = $product['product_id'];
            $variation_id = $product['variation_id'];

            if(in_array($product_id, $excluded_products_list) || in_array($variation_id, $excluded_products_list)) continue;
            
            if($this->isMatch($product)){
                if(filter_var($max_product_qty, FILTER_VALIDATE_INT) && $product['quantity'] > $max_product_qty){
                    $qt += $max_product_qty;
                }else{
                    $qt += $product['quantity'];
                }
            }
        }

        if(filter_var($max_qty, FILTER_VALIDATE_INT) && $qt > $max_qty){
            return $max_qty;
        }

        return $qt;
    }

    function getMatchedProductsCount($max_count = '', $excluded_products = ''){
        $matched_products = [];
        $products = $this->cart->get_cart();

        $excluded_products_list = self::excludedProductList($excluded_products);
        foreach($products as $product){
            $product_id = $product['product_id'];
            $variation_id = $product['variation_id'];

            if(in_array($product_id, $excluded_products_list) || in_array($variation_id, $excluded_products_list)) continue;
            
            if($this->isMatch($product)){
                if(!empty($variation_id)){
                    $matched_products[] = $variation_id;
                }else{
                    $matched_products[] = $product_id;
                }
            }
        }

        $matched_products = array_unique($matched_products);

        $count = $matched_products ? count($matched_products) : 0;

        return !empty($max_count) && $count > $max_count ? $max_count : $count;

        return 0;
    }

    function getMatchedProductsCost(){
        $products = $this->cart->get_cart();
        $total = 0;
        foreach($products as $product){
            if($this->isMatch($product)){
                $total += pisol_cefw_cart_clone::get_product_subtotal($product['data'], $product['quantity']);
            }
        }
        return $total;
    }

    function isMatch($product){
        $rules = $this->getProductBasedRules();
        foreach($rules as $rule){
            if($rule['pi_condition'] == 'product'){

                if($this->productMatched($product, $rule['pi_value'])){
                    return true;
                }

            }elseif($rule['pi_condition'] == 'variable_product'){

                if($this->variableProductMatched($product, $rule['pi_value'])){
                    return true;
                }

            }elseif($rule['pi_condition'] == 'category_product'){

                if($this->categoryMatched($product, $rule['pi_value'])){
                    return true;
                }

            }elseif($rule['pi_condition'] == 'category_quantity'){

                if($this->categoryQuantityMatched($product, $rule['pi_value'])){
                    return true;
                }

            }elseif($rule['pi_condition'] == 'shipping_class'){
                if($this->shippingClassMatched($product, $rule['pi_value'])){
                    return true;
                }
            }
        }
        return false;
    }

    function productMatched($product, $value){
        if( in_array($product['product_id'],$value) ){
            return true;
        }
        return false;
    }

    function variableProductMatched($product, $value){
        if( in_array($product['variation_id'],$value) ){
            return true;
        }
        return false;
    }

    function categoryMatched($product, $value){
        $categories = $this->getCategoriesOfProduct($product);
        $intersect = array_intersect($value, $categories);
        if(count($intersect) > 0){
            return true;
        }
        return false;
    }

    function categoryQuantityMatched($product, $value){
        $categories = $this->getCategoriesOfProduct($product);
        $cat_value[] = isset($value['category']) ? $value['category'] : '';
        $intersect = array_intersect($cat_value, $categories);
        if(count($intersect) > 0){
            return true;
        }
        return false;
    }

    function shippingClassMatched($product, $values){
        $product_obj = $product['data'];
        $class = $product_obj->get_shipping_class_id();
        if(in_array($class, $values)) return true;
        return false;
    }

    function getCategoriesOfProduct($product){
       
        $user_products_categories = array();
        
        $product_obj = wc_get_product($product['product_id']);

        $product_categories = $product_obj->get_category_ids();
        foreach($product_categories as $product_category){
            $user_products_categories[] = $product_category;
        }
        
        return $user_products_categories;
    }

    function getRules(){
        $rules = get_post_meta($this->fees_id, 'pi_metabox', true);
        return $rules;
    }

    function getProductBasedRules(){
        $rules = $this->getRules();
        $product_based_rules = array('product', 'category_product', 'variable_product', 'category_quantity', 'shipping_class');
        foreach($rules as $key => $rule){
            if(!in_array($rule['pi_condition'], $product_based_rules)){
                unset($rules[$key]);
            }
        }
        return $rules;
    }
}