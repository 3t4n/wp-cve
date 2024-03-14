<?php

class bogo_by_sp_public_class {

    /**
     * Constructor
     */
    Public function __construct() {
        $global_enable = (int) get_option('sp_bogo_disable_global', 0);
        $cart_notice = (int) get_option('sp_bogo_enable_cart_message', 0);

        if ($global_enable === 1) {
            add_action('woocommerce_add_to_cart', array(__CLASS__, 'bogo_by_sp_add_product_to_cart'), 100, 2);
            add_action( 'woocommerce_cart_item_restored', array(__CLASS__, 'bogo_by_sp_undoproduct'), 100, 2 );
            add_action('woocommerce_before_calculate_totals', array(__CLASS__, 'bogo_by_sp_add_custom_price'));
            add_filter('woocommerce_get_item_data', array(__CLASS__, 'bogo_by_sp_free_product_label'), 20, 2);
            add_filter('woocommerce_cart_item_quantity', array(__CLASS__, 'bogo_by_sp_cart_item_quantity'), 10, 3);
            add_filter( 'woocommerce_get_price_html',  array(__CLASS__, 'bogo_by_sp_cart_icon_to_price'), 10, 3 );
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
            add_action( 'woocommerce_before_single_product_summary', array(__CLASS__,'bogo_by_sp_cart_add_custom_text_after_product_title'), 5);
            add_action( 'woocommerce_remove_cart_item', array(__CLASS__,  'bogo_by_sp_cart_remove'), 20, 1 );
            add_action( 'woocommerce_update_cart_action_cart_updated',array(__CLASS__,  'bogo_by_sp_update_cart'), 10, 1 );
            add_filter( 'woocommerce_cart_item_name', array(__CLASS__,  'change_minicart_free_gifted_item_price'), 10, 3 );
            add_action( 'woocommerce_get_price_html', array(__CLASS__, 'bogo_related_products_free_icon') );
           
        }
		if ($global_enable === 1 && $cart_notice === 1) {
		add_action('woocommerce_before_cart', array(__CLASS__, 'bogo_by_sp_add_cart_notice'));
		
       }
    }


    //Hide the Free Item text on single product page of the free item.
    Public static function bogo_related_products_free_icon($price){
        
        $free_product_id = get_option('sp_bogo_product_free');
        $free_product_2_id = get_option('sp_bogo_product_2_free');
        $free_product_3_id = get_option('sp_bogo_product_3_free');

        global $post;
        $free_id =  $post->ID;

        if (is_admin() && !defined('DOING_AJAX'))
        return $price;

        if(is_product()){
    
            if( $free_id == $free_product_id || $free_id == $free_product_2_id || $free_id == $free_product_3_id ){     
                $icon =  '<style>.summary.entry-summary .icon_text{display: none !important;}</style>';
                $price = $icon;
                return $price;
            }
            else{
                return $price;
            }
        }
        else{
            return $price;
        }
            
        
    }
   
   

    
   //Set a Free label for the free item on cart
    Public static function change_minicart_free_gifted_item_price(  $item_name, $cart_item, $cart_item_key ) {
        $free_product_id   = (int) get_option('sp_bogo_product_free');
        $free_product_2_id   = (int) get_option('sp_bogo_product_2_free');
        $free_product_3_id   = (int) get_option('sp_bogo_product_3_free');
       
    
        if( ( isset($cart_item['free_product']) && ($cart_item['free_product'] == true) ) || ( isset($cart_item['free_product_2']) && ($cart_item['free_product_2'] == true) ) || ( isset($cart_item['free_product_3']) && ($cart_item['free_product_3'] == true) ) ) {
            $item_name = $item_name . ' <p><strong>Product: </strong><br/> Free </p> ';
        }

        
        return $item_name;
    }

    
    
  
    
    // function to add and remove product from cart    
    Public static function bogo_by_sp_add_product_to_cart($cart_item_key, $product_id) {
        global $woocommerce;

        if (is_admin() && !defined('DOING_AJAX'))
            return;

        if (did_action('woocommerce_before_calculate_totals') >= 2)
            return;


        
            $main_product_id = (int) get_option('sp_bogo_product_buy');
            $main_product_qty = (int) get_option('sp_bogo_product_buy_quantity');
            $free_product_id = (int) get_option('sp_bogo_product_free');
            $free_product_qty = (int) get_option('sp_bogo_product_free_quantity');

            $main_product_2_id = (int) get_option('sp_bogo_product_2_buy');
            $main_product_2_qty = (int) get_option('sp_bogo_product_2_buy_quantity');
            $free_product_2_id = (int) get_option('sp_bogo_product_2_free');
            $free_product_2_qty = (int) get_option('sp_bogo_product_2_free_quantity');

            $main_product_3_id = (int) get_option('sp_bogo_product_3_buy');
            $main_product_3_qty = (int) get_option('sp_bogo_product_3_buy_quantity');
            $free_product_3_id = (int) get_option('sp_bogo_product_3_free');
            $free_product_3_qty = (int) get_option('sp_bogo_product_3_free_quantity');


         

            if( ($main_product_id != 0) && ($main_product_qty != 0) && ($free_product_id != 0) && ($free_product_qty != 0)  ){
                // if added product is main product then only need to check free product case
                if($main_product_id == $product_id){ 
                    // $has_main = true;
                    $free_added = false;
                    $cart_main_qty = 0;
                        
                    
                    foreach (WC()->cart->get_cart() as $cart_item) {
                        // Get quantity of main product added to cart
                        if($cart_item['product_id'] == $main_product_id){
                            $cart_main_qty = $cart_item['quantity'];
                        }

                        // check free product already added in cart
                        if (isset($cart_item['free_product']) && ($cart_item['free_product'] == true)){
                            $free_added = true;
                        }
                    }
                   

                    if (!$free_added && ($cart_main_qty >= $main_product_qty)) {
                        $unique_cart_item_key = uniqid();
                        $cart_item_data['unique_key'] = $unique_cart_item_key;
                        $cart_item_data['free_product'] = true;
                        $free_prod_key = WC()->cart->add_to_cart($free_product_id, $free_product_qty, 0, array(), $cart_item_data);
                    } 
                    
                }
                
            }

            //Product 2
            if( ($main_product_2_id != 0) && ($main_product_2_qty != 0) && ($free_product_2_id != 0) && ($free_product_2_qty != 0)  ){
                // if added product is main product then only need to check free product case
                if($main_product_2_id == $product_id){ 
                    // $has_main = true;
                    $free_added_2 = false;
                    $cart_main_qty_2 = 0;
                        
                    
                    foreach (WC()->cart->get_cart() as $cart_item) {
                        // Get quantity of main product added to cart
                        if($cart_item['product_id'] == $main_product_2_id){
                            $cart_main_qty_2 = $cart_item['quantity'];
                        }

                        // check free product already added in cart
                        if (isset($cart_item['free_product_2']) && ($cart_item['free_product_2'] == true)){
                            $free_added_2 = true;
                        }
                    }
                   

                    if (!$free_added_2 && ($cart_main_qty_2 >= $main_product_2_qty)) {
                        $unique_cart_item_key_2 = uniqid();
                        $cart_item_data['unique_key_2'] = $unique_cart_item_key_2;
                        $cart_item_data['free_product_2'] = true;
                        $free_prod_key_2 = WC()->cart->add_to_cart($free_product_2_id, $free_product_2_qty, 0, array(), $cart_item_data);
                    } 
                    
                }
                
            }

            //Product 3
            if( ($main_product_3_id != 0) && ($main_product_3_qty != 0) && ($free_product_3_id != 0) && ($free_product_3_qty != 0)  ){
                // if added product is main product then only need to check free product case
                if($main_product_3_id == $product_id){ 
                    // $has_main = true;
                    $free_added_3 = false;
                    $cart_main_qty_3 = 0;
                        
                    
                    foreach (WC()->cart->get_cart() as $cart_item) {
                        // Get quantity of main product added to cart
                        if($cart_item['product_id'] == $main_product_3_id){
                            $cart_main_qty_3 = $cart_item['quantity'];
                        }

                        // check free product already added in cart
                        if (isset($cart_item['free_product_3']) && ($cart_item['free_product_3'] == true)){
                            $free_added_3 = true;
                        }
                    }
                   

                    if (!$free_added_3 && ($cart_main_qty_3 >= $main_product_3_qty)) {
                        $unique_cart_item_key_3 = uniqid();
                        $cart_item_data['unique_key_3'] = $unique_cart_item_key_3;
                        $cart_item_data['free_product_3'] = true;
                        $free_prod_key_3 = WC()->cart->add_to_cart($free_product_3_id, $free_product_3_qty, 0, array(), $cart_item_data);
                    } 
                    
                }
                
            }
            
    }

    //undo cart
   
   Public static function bogo_by_sp_undoproduct($cart_item_key, $product_id) {
    
    global $woocommerce;

        if (is_admin() && !defined('DOING_AJAX'))
            return;

        if (did_action('woocommerce_before_calculate_totals') >= 2)
            return;
        
        $main_product_id = (int) get_option('sp_bogo_product_buy');
        $main_product_qty = (int) get_option('sp_bogo_product_buy_quantity');
        $free_product_id = (int) get_option('sp_bogo_product_free');
        $free_product_qty = (int) get_option('sp_bogo_product_free_quantity');

        $main_product_2_id = (int) get_option('sp_bogo_product_2_buy');
        $main_product_2_qty = (int) get_option('sp_bogo_product_2_buy_quantity');
        $free_product_2_id = (int) get_option('sp_bogo_product_2_free');
        $free_product_2_qty = (int) get_option('sp_bogo_product_2_free_quantity');

        $main_product_3_id = (int) get_option('sp_bogo_product_3_buy');
        $main_product_3_qty = (int) get_option('sp_bogo_product_3_buy_quantity');
        $free_product_3_id = (int) get_option('sp_bogo_product_3_free');
        $free_product_3_qty = (int) get_option('sp_bogo_product_3_free_quantity');

           foreach ( WC()->cart->get_cart() as $cart_item ) {    
            if($cart_item['product_id'] == $main_product_id){
                    $cart_main_qty = $cart_item['quantity'];
                }
            if($cart_item['product_id'] == $main_product_2_id){
                    $cart_main_qty_2 = $cart_item['quantity'];
                }
            if($cart_item['product_id'] == $main_product_3_id){
                    $cart_main_qty_3 = $cart_item['quantity'];
                }
                 // check free product already added in cart
            if (isset($cart_item['free_product']) && ($cart_item['free_product'] == true)){
                    $free_added = true;
                } 
            if (isset($cart_item['free_product_2']) && ($cart_item['free_product_2'] == true)){
                    $free_added_2 = true;
                }
            if (isset($cart_item['free_product_3']) && ($cart_item['free_product_3'] == true)){
                    $free_added_3 = true;
                }           
           } 
       
           if (!$free_added && ($cart_main_qty >= $main_product_qty)) {
                $unique_cart_item_key = uniqid();
                $cart_item_data['unique_key'] = $unique_cart_item_key;
                $cart_item_data['free_product'] = true;
                $free_prod_key = WC()->cart->add_to_cart($free_product_id, $free_product_qty, 0, array(), $cart_item_data);
            }

            if (!$free_added_2 && ($cart_main_qty_2 >= $main_product_2_qty)) {
                $unique_cart_item_key_2 = uniqid();
                $cart_item_data['unique_key_2'] = $unique_cart_item_key_2;
                $cart_item_data['free_product_2'] = true;
                $free_prod_key_2 = WC()->cart->add_to_cart($free_product_2_id, $free_product_2_qty, 0, array(), $cart_item_data);
            }

            if (!$free_added_3 && ($cart_main_qty_3 >= $main_product_3_qty)) {
                $unique_cart_item_key_3 = uniqid();
                $cart_item_data['unique_key_3'] = $unique_cart_item_key_3;
                $cart_item_data['free_product_3'] = true;
                $free_prod_key_3 = WC()->cart->add_to_cart($free_product_3_id, $free_product_3_qty, 0, array(), $cart_item_data);
            }

    }

    //update cart

    Public static function bogo_by_sp_update_cart( $cart_updated ){
        $main_product_id = (int) get_option('sp_bogo_product_buy');
        $main_product_qty = (int) get_option('sp_bogo_product_buy_quantity');
        $free_product_id = (int) get_option('sp_bogo_product_free');
        $free_product_qty = (int) get_option('sp_bogo_product_free_quantity');

        $main_product_2_id = (int) get_option('sp_bogo_product_2_buy');
        $main_product_2_qty = (int) get_option('sp_bogo_product_2_buy_quantity');
        $free_product_2_id = (int) get_option('sp_bogo_product_2_free');
        $free_product_2_qty = (int) get_option('sp_bogo_product_2_free_quantity');

        $main_product_3_id = (int) get_option('sp_bogo_product_3_buy');
        $main_product_3_qty = (int) get_option('sp_bogo_product_3_buy_quantity');
        $free_product_3_id = (int) get_option('sp_bogo_product_3_free');
        $free_product_3_qty = (int) get_option('sp_bogo_product_3_free_quantity');

         $qty_matched = false;
         $free_added = false;
         $qty_matched_2 = false;
         $free_added_2 = false;
         $qty_matched_3 = false;
         $free_added_3 = false;
         if($main_product_id && $cart_updated == true ){
            $cart_main_qty = 0;
            foreach (WC()->cart->get_cart() as $key => $cart_item) {
                // check main product exist in cart
                // then get main product quantity
                if($cart_item['product_id'] == $main_product_id){
                    $cart_main_qty = $cart_item['quantity'];
                }
               
               // find free product key
               if ($cart_item['free_product'] == true) {
                    $free_added = true;
                    $free_key = $key;
                }
                
            }
            
            // check main product quantity is reached the minimum limit
            if($cart_main_qty >= $main_product_qty){
                $qty_matched = true;
            }
            
            
            // remove free product if quantity decreased
            if(!$qty_matched && $free_added){
                WC()->cart->remove_cart_item($free_key);
                
            }
            
            
            // add free product if quantity increased
            if($qty_matched && !$free_added){
                $unique_cart_item_key = uniqid();
                $cart_item_data['unique_key'] = $unique_cart_item_key;
                $cart_item_data['free_product'] = true;
                WC()->cart->add_to_cart($free_product_id, $free_product_qty, 0, array(), $cart_item_data);
            }
           
         }

        if($main_product_2_id && $cart_updated == true ){
            $cart_main_qty_2 = 0;
            foreach (WC()->cart->get_cart() as $key_2 => $cart_item) {
                // check main product exist in cart
                // then get main product quantity
                if($cart_item['product_id'] == $main_product_2_id){
                    $cart_main_qty_2 = $cart_item['quantity'];
                }
               
               // find free product key
               if ($cart_item['free_product_2'] == true) {
                    $free_added_2 = true;
                    $free_key_2 = $key_2;
                }
                
            }
            
            // check main product quantity is reached the minimum limit
            if($cart_main_qty_2 >= $main_product_2_qty){
                $qty_matched_2 = true;
            }
            
            
            // remove free product if quantity decreased
            if(!$qty_matched_2 && $free_added_2){
                WC()->cart->remove_cart_item($free_key_2);
                
            }
            
            
            // add free product if quantity increased
            if($qty_matched_2 && !$free_added_2){
                $unique_cart_item_key_2 = uniqid();
                $cart_item_data['unique_key_2'] = $unique_cart_item_key_2;
                $cart_item_data['free_product_2'] = true;
                WC()->cart->add_to_cart($free_product_2_id, $free_product_2_qty, 0, array(), $cart_item_data);
            }
           
        }

         if($main_product_3_id && $cart_updated == true ){
            $cart_main_qty_3 = 0;
            foreach (WC()->cart->get_cart() as $key_3 => $cart_item) {
                // check main product exist in cart
                // then get main product quantity
                if($cart_item['product_id'] == $main_product_3_id){
                    $cart_main_qty_3 = $cart_item['quantity'];
                }
               
               // find free product key
               if ($cart_item['free_product_3'] == true) {
                    $free_added_3 = true;
                    $free_key_3 = $key_3;
                }
                
            }
            
            // check main product quantity is reached the minimum limit
            if($cart_main_qty_3 >= $main_product_3_qty){
                $qty_matched_3 = true;
            }
            
            
            // remove free product if quantity decreased
            if(!$qty_matched_3 && $free_added_3){
                WC()->cart->remove_cart_item($free_key_3);
                
            }
            
            
            // add free product if quantity increased
            if($qty_matched_3 && !$free_added_3){
                $unique_cart_item_key_3 = uniqid();
                $cart_item_data['unique_key_3'] = $unique_cart_item_key_3;
                $cart_item_data['free_product_3'] = true;
                WC()->cart->add_to_cart($free_product_3_id, $free_product_3_qty, 0, array(), $cart_item_data);
            }
           
        }

    }
	
    // remove free product when main product remove
    Public static function bogo_by_sp_cart_remove ( $cart_item_key) {
         $main_product_id = (int) get_option('sp_bogo_product_buy');
         $main_product_2_id = (int) get_option('sp_bogo_product_2_buy');
         $main_product_3_id = (int) get_option('sp_bogo_product_3_buy');
         $remove_main = false;
         $free_added = false;
         $remove_main_2 = false;
         $free_added_2 = false;
         $remove_main_3 = false;
         $free_added_3 = false;
         if($main_product_id){
            foreach (WC()->cart->get_cart() as $key => $cart_item) {
               if($key == $cart_item_key){
                    if($cart_item['product_id'] == $main_product_id){
                        $remove_main = true;
                    }
               }
               // find free product key
               if ($cart_item['free_product'] == true) {
                    $free_added = true;
                    $free_key = $key;
                }
            }

            if($remove_main && $free_added){
                WC()->cart->remove_cart_item($free_key);
            }
         }
         //Product 2
         if($main_product_2_id){
            foreach (WC()->cart->get_cart() as $key_2 => $cart_item) {
               if($key_2 == $cart_item_key){
                    if($cart_item['product_id'] == $main_product_2_id){
                        $remove_main_2 = true;
                    }
               }
               // find free product key
               if ($cart_item['free_product_2'] == true) {
                    $free_added_2 = true;
                    $free_key_2 = $key_2;
                }
            }

            if($remove_main_2 && $free_added_2){
                WC()->cart->remove_cart_item($free_key_2);
            }
         }
         //Product 3
        if($main_product_3_id){
            foreach (WC()->cart->get_cart() as $key_3 => $cart_item) {
               if($key_3 == $cart_item_key){
                    if($cart_item['product_id'] == $main_product_3_id){
                        $remove_main_3 = true;
                    }
               }
               // find free product key
               if ($cart_item['free_product_3'] == true) {
                    $free_added_3 = true;
                    $free_key_3 = $key_3;
                }
            }

            if($remove_main_3 && $free_added_3){
                WC()->cart->remove_cart_item($free_key_3);
            }
         }
        }

    // function Add cart message
   	Public static function bogo_by_sp_add_cart_notice() {
   		 // $free_prod_key = WC()->session->get('free_prod_key');
         $has_free = false;
         $has_free_2 = false;
         $has_free_3 = false;
         // check cart has free product added
         if (!WC()->cart->is_empty()) {
                foreach (WC()->cart->get_cart() as $cart_item) {
                    if (isset($cart_item['free_product']) && ($cart_item['free_product'] == true)) {
                        $has_free = true;
                        break; // stop the loop if product is found
                    }
                   if (isset($cart_item['free_product_2']) && ($cart_item['free_product_2'] == true)) {
                        $has_free_2 = true;
                        break; // stop the loop if product is found
                    }
                    if (isset($cart_item['free_product_3']) && ($cart_item['free_product_3'] == true)) {
                        $has_free_3 = true;
                        break; // stop the loop if product is found
                    }
                }
         }
        
        

			
      $link = '#';
	  $cart_text = (string) get_option('sp_bogo_cart_message_content'); 
   	  if ( $has_free || $has_free_2 || $has_free_3) {
		  if ($cart_text == ''){
   	  wc_print_notice( sprintf( '<span class="subscription-reminder">' . __('A free product has been added to your cart !!', 'woocommerce') . '</span>','<a href='.$link.' class="button alt" style="float:right">'. __('X', 'woocommerce') .'</a>'), 'notice' );
   			    }
	   
				else {
	   wc_print_notice( sprintf( '<span class="subscription-reminder">' . $cart_text . '</span>','<a href='.$link.' class="button alt" style="float:right">'. __('X', 'woocommerce') .'</a>'), 'notice' );
   	}
  }
}


    // function to set the price of free product to zero
    Public static function bogo_by_sp_add_custom_price($cart_object) {
        // if (WC()->session->__isset("free_added")) {
            foreach ($cart_object->cart_contents as $cart_item) {
                if (isset($cart_item['free_product']) && ($cart_item['free_product'] == true)) {
                    // Woocommerce 3+ compatibility
                    if (version_compare(WC_VERSION, '3.0', '<'))
                        $cart_item['data']->price = 0;
                    else
                        $cart_item['data']->set_price(0);
                }
               if (isset($cart_item['free_product_2']) && ($cart_item['free_product_2'] == true)) {
                    // Woocommerce 3+ compatibility
                    if (version_compare(WC_VERSION, '3.0', '<'))
                        $cart_item['data']->price = 0;
                    else
                        $cart_item['data']->set_price(0);
                }
                if (isset($cart_item['free_product_3']) && ($cart_item['free_product_3'] == true)) {
                    // Woocommerce 3+ compatibility
                    if (version_compare(WC_VERSION, '3.0', '<'))
                        $cart_item['data']->price = 0;
                    else
                        $cart_item['data']->set_price(0);
                }
            }
        // }
    }
   
     

    Public static function bogo_by_sp_free_product_label($item_data, $cart_item_data) {
        // if (WC()->session->__isset("free_added")) {
            if (isset($cart_item['free_product']) && ($cart_item['free_product'] == true)) {
                $item_data[] = array(
                    'key' => __('Product'),
                    'value' => __('Free')
                );
            }
            if (isset($cart_item['free_product_2']) && ($cart_item['free_product_2'] == true)) {
                $item_data[] = array(
                    'key' => __('Product'),
                    'value' => __('Free')
                );
            }
            if (isset($cart_item['free_product_3']) && ($cart_item['free_product_3'] == true)) {
                $item_data[] = array(
                    'key' => __('Product'),
                    'value' => __('Free')
                );
            }
        // }
        return $item_data;
    }

  
    // function to disable qty updater of free product
    Public static function bogo_by_sp_cart_item_quantity($product_quantity, $cart_item_key, $cart_item) {
        // if (WC()->session->__isset("free_added")) {
            if (isset($cart_item['free_product']) && ($cart_item['free_product'] == true)) {
                $product_quantity = sprintf('%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity']);
            }
            if (isset($cart_item['free_product_2']) && ($cart_item['free_product_2'] == true)) {
                $product_quantity = sprintf('%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity']);
            }
            if (isset($cart_item['free_product_3']) && ($cart_item['free_product_3'] == true)) {
                $product_quantity = sprintf('%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity']);
            }
        // }
        return $product_quantity;
    }

    
  

     //function to add provision to add text for the promotional product
     Public static function bogo_by_sp_cart_icon_to_price( $price, $product ) {
        
       if (is_admin() && !defined('DOING_AJAX'))
        return $price;

        $free_product_ids = get_option('sp_bogo_product_free_icon');
        $free_product_id = get_option('sp_bogo_product_free');
        $free_product_2_id = get_option('sp_bogo_product_2_free');
        $free_product_3_id = get_option('sp_bogo_product_3_free');
        $product=$product->get_id();
        
    
        

            if(($product== $free_product_id && $free_product_ids == '') || ($product== $free_product_2_id && $free_product_ids == '') || ($product== $free_product_3_id && $free_product_ids == '')){
                
                echo '<span class="price">'. $price .' </span>';
            }
            
           
        

            else if(($product== $free_product_id && !empty($free_product_ids)) || ($product== $free_product_2_id && !empty($free_product_ids)) || ($product== $free_product_3_id && !empty($free_product_ids))) {
                $icon =  '<span class ="icon_text" style="position: absolute; top: 0; left: 0; padding: 10px; 
                background-color: #764b3e; color: #fff;">'. $free_product_ids.' </span>';
                $price = $icon . $price;
                 echo '<span class="price">'. $price .' </span>';
                 
            }

            else{
                return $price;
            }
           
       

        
    }

    
    
    
       //function to add provision to add text for the promotional product - single
    
       Public static function bogo_by_sp_cart_add_custom_text_after_product_title($price){
        if (is_admin() && !defined('DOING_AJAX'))
            return;
            
        $main_product = get_option('sp_bogo_product_buy');
        $main_product_2 = get_option('sp_bogo_product_2_buy');
        $main_product_3 = get_option('sp_bogo_product_3_buy');
        $free_product_text = get_option('sp_bogo_product_free_text_single');
        $free_product_id = get_option('sp_bogo_product_free');
        $free_product_2_id = get_option('sp_bogo_product_2_free');
        $free_product_3_id = get_option('sp_bogo_product_3_free');
        $main_product_title = get_the_title(  $main_product );
        $main_product_2_title = get_the_title(  $main_product_2 );
        $main_product_3_title = get_the_title(  $main_product_3 );
        $main_product_url = get_permalink($main_product);
        $main_product_2_url = get_permalink($main_product_2);
        $main_product_3_url = get_permalink($main_product_3);
        $min_qty_main_product = get_option('sp_bogo_product_buy_quantity');
        $min_qty_main_product_2 = get_option('sp_bogo_product_2_buy_quantity');
        $min_qty_main_product_3 = get_option('sp_bogo_product_3_buy_quantity');
        $qty_free_product = get_option('sp_bogo_product_free_quantity');
        $qty_free_product_2 = get_option('sp_bogo_product_2_free_quantity');
        $qty_free_product_3 = get_option('sp_bogo_product_3_free_quantity');
        global $post;
        $free_id =  $post->ID;

        if(is_product()){
    
            if($free_id  == $free_product_id && $free_product_text == ''){     
                echo ( '<span class="product_title entry-title promotional_product_title">'. __('This item comes for free','woocommerce').' (Quantity - '.$qty_free_product .' ) with <a href="'.$main_product_url.'">'.$main_product_title.' </a> (minimum quantity of '. $min_qty_main_product .' ) </span>') ;
               
            }
            else if($free_id  == $free_product_2_id && $free_product_text == ''){     
                echo ( '<span class="product_title entry-title promotional_product_title">'. __('This item comes for free','woocommerce').' (Quantity - '.$qty_free_product_2 .' ) with <a href="'.$main_product_2_url.'">'.$main_product_2_title.' </a> (minimum quantity of '. $min_qty_main_product_2 .' ) </span>') ;
               
            }
            else if($free_id  == $free_product_3_id && $free_product_text == ''){     
                echo ( '<span class="product_title entry-title promotional_product_title">'. __('This item comes for free','woocommerce').' (Quantity - '.$qty_free_product_3 .' ) with <a href="'.$main_product_3_url.'">'.$main_product_3_title.' </a> (minimum quantity of '. $min_qty_main_product_3 .' ) </span>') ;
               
            }
        
        
            else if($free_id  == $free_product_id && !empty($free_product_text)){     
            echo ( '<span class="product_title entry-title promotional_product_title">'. $free_product_text.' (Quantity - '.$qty_free_product .' ) with <a href="'.$main_product_url.'">'.$main_product_title.' </a> (Minimum Quantity - '. $min_qty_main_product .' ) </span>') ;
            
            }

            else if($free_id  == $free_product_2_id && !empty($free_product_text)){     
                echo ( '<span class="product_title entry-title promotional_product_title">'. $free_product_text.' (Quantity - '.$qty_free_product_2 .' ) with <a href="'.$main_product_2_url.'">'.$main_product_2_title.' </a> (Minimum Quantity - '. $min_qty_main_product_2 .' ) </span>') ;
                
            }
            else if($free_id  == $free_product_3_id && !empty($free_product_text)){     
                echo ( '<span class="product_title entry-title promotional_product_title">'. $free_product_text.' (Quantity - '.$qty_free_product_3 .' ) with <a href="'.$main_product_3_url.'">'.$main_product_3_title.' </a> (Minimum Quantity - '. $min_qty_main_product_3 .' ) </span>') ;
                
            }

            
        }
       
    }


}

$bogobysppublicclass = new bogo_by_sp_public_class;
