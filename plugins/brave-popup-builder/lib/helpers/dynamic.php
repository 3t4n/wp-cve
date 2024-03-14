<?php

function bravepop_dynamic_data(){
   $data = array();

   $data['date'] = bravepop_dynamic_date_data();
   $data['post'] = bravepop_dynamic_posts_data();
   $data['general'] = bravepop_dynamic_general_data();
   if ( BRAVEPOP_WOO_ACTIVE ) {
      $data['product'] = bravepop_dynamic_woo_data();
   }


   return $data;
}

function bravepop_dynamic_date_data(){
   $dates = new stdClass();
   //$dates->today = date_i18n();

   $dt_gmt =  date('l jS F Y');
   $dt = get_date_from_gmt($dt_gmt, 'd-m-Y');// convert from GMT to local date/time based on WordPress time zone setting.
   $dates->today =  date_i18n('jS F Y');// get format from WordPress settings.
   $tomorrow_unix_timestamp = strtotime(date('m/d/Y', strtotime("+1 days")));
   $dates->tomorrow =  date_i18n('jS F Y', $tomorrow_unix_timestamp);
   $dates->month =  date_i18n('F');
   $dates->year =  date_i18n('Y');

   return $dates;
}

function bravepop_dynamic_posts_data(){
   $wpData = new stdClass();
   $latestPosts = get_posts(array('post_type' => 'post', 'numberposts' => 5));
   $wpData->latest = bravepop_dynamic_wp_loop($latestPosts);
   return $wpData;
}

function bravepop_dynamic_general_data( ){
   $general = new stdClass();
   $general->post = new stdClass();
   $general->product = new stdClass();
   $general->user = new stdClass();

   if ( BRAVEPOP_WOO_ACTIVE ) {
      if(is_product()){
         $product = wc_get_product();
         if($product){ 
            $currentCats = $product->get_category_ids();
            if($currentCats){
               $currencySymbol = function_exists('get_woocommerce_currency_symbol') ?  get_woocommerce_currency_symbol() : '$';
               $general->product->category = get_term( $currentCats[0] )->name ;
               $general->product->category_link = get_term_link($currentCats[0]);
               $general->product->title = $product->get_name() ;
               $general->product->price = $currencySymbol.$product->get_price();
               $general->product->original_price = $currencySymbol.$product->get_regular_price();
            }
         }
      }
      if(is_product_category()){
         $category = get_queried_object();
         if(isset($category->term_id)){
            $general->product->category = $category->name ;
            $general->product->category_link = get_term_link($category->term_id);
         }
      }
   }

   if(is_category()){
      $category = get_queried_object();
      if(isset($category->term_id)){
         $general->post->category = $category->name ;
         $general->post->category_link = get_term_link($category->term_id);
      }
   }

   if(is_singular( 'post' )){
      $post = get_queried_object();
      $category = get_the_category($post->ID);
      if(isset($category[0])){
         $general->post->category = $category[0]->name ;
         $general->post->category_link = get_category_link($category[0]->term_id);
      }
   }

   if(is_user_logged_in()){
      $current_user = wp_get_current_user();
      if(!empty($current_user->user_firstname)){   $general->user->firstname = $current_user->user_firstname ;  }
      if(!empty($current_user->user_lastname)){   $general->user->lastname = $current_user->user_lastname ;  }
   }

   return $general;
}

function bravepop_dynamic_woo_data(){
   $woocommerce = new stdClass();
   if(BRAVEPOP_WOO_ACTIVE){
      $latestProducts = wc_get_products( array( 'limit' => 5 ) );
      $woocommerce->latest = bravepop_dynamic_woo_loop( $latestProducts );
   
      if(function_exists('bravepop_dynamic_woo_mostSold')){
         $woocommerce->popular = bravepop_dynamic_woo_mostSold();
      }
      if(is_product() && function_exists('bravepop_dynamic_woo_upsell')){
         $woocommerce->upsell = bravepop_dynamic_woo_upsell();
      }
      if(is_product() && function_exists('bravepop_dynamic_woo_crossell')){
         $woocommerce->crossell = bravepop_dynamic_woo_crossell();
      }
   }

   return $woocommerce;
}


function bravepop_dynamic_woo_loop( $products ){
   $allProducts= [];
   if(BRAVEPOP_WOO_ACTIVE){
      $currencySymbol = function_exists('get_woocommerce_currency_symbol') ?  get_woocommerce_currency_symbol() : '$';
      foreach ( $products as $index=>$product ) {
         if($product){
            $object = new stdClass();
            $object->title = $product->get_name();
            $object->description = $product->get_short_description();
            //$object->description = $product->get_description();
            $object->ID = $product->get_id();
            $object->SKU = $product->get_sku();
            $object->type = $product->get_type();
            $object->link = get_permalink( $product->get_id() );
            $object->cart_link = esc_url( $product->add_to_cart_url());
            $object->price = $currencySymbol.$product->get_price();
            $object->regular_price = $currencySymbol.$product->get_regular_price();
            $object->sale_price = $currencySymbol.$product->get_sale_price();
            //$object->categories = bravepop_get_product_terms($product->get_category_ids());
            $imgDataArray = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'large', false );
            $object->image =  is_array($imgDataArray) && $imgDataArray[0] ? $imgDataArray[0] : '';
            $object->index = $index+1;
            if($product->is_purchasable() && $product->is_in_stock()){
               $allProducts[] = $object;
            }

         }
      }
   }
   return $allProducts;
}

function bravepop_dynamic_wp_loop( $posts ){
   $allPosts = array();
   foreach ( $posts as $index=>$post ) {
      $object = new stdClass();
      $object->title = $post->post_title ;
      $object->link = esc_url(get_permalink( $post->ID )) ;
      $object->date = get_the_time( get_option('date_format'), $post->ID )  ;
      $object->excerpt = get_the_excerpt($post->ID);
      $imgDataArray = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large', false );
      $object->image =  is_array($imgDataArray) && $imgDataArray[0] ? $imgDataArray[0] : '';
      $object->index = $index+1;
      $allPosts[] = $object;
   }

   return $allPosts;
}

function bravepopup_dynamicLink_data($actionData, $dynamicData, $elementID){
   $elementData = $actionData->dynamicURL;
   $dnmcType = isset($elementData->type) ? $elementData->type : '';
   $dnmcPostType = isset($elementData->post) ? $elementData->post : '';
   $dnmcDataType = isset($elementData->data) ? $elementData->data : '';
   $dnmcIndex = isset($elementData->index) ? $elementData->index : '';
   $dynamicLink = new stdClass();
   if(!empty($dynamicData[$dnmcPostType]->$dnmcType)){
      foreach ($dynamicData[$dnmcPostType]->$dnmcType as $item) {
         if(($item->index === $dnmcIndex)){
            if($dnmcDataType === 'cart'){
               $classes = implode( ' ',  array( 'product_type_' . $item->type, 'add_to_cart_button','ajax_add_to_cart' ) );

               $dynamicLink->classes = $classes;
               $dynamicLink->link = $item->cart_link;
               $dynamicLink->attr = ' data-product_id="'.$item->ID.'" data-product_sku="'.$item->SKU.'" data-quantity="1" onclick="brave_add_to_cart(\''.$elementData->id.'\')" ';

            }
            if($dnmcDataType === 'link' && !empty($item->$dnmcDataType)){
               $dynamicLink->link = $item->$dnmcDataType;
            }
         }
      }
   }

   if($dnmcType === 'general' && !empty($dynamicData['general']->$dnmcPostType->$dnmcDataType)){
      $dynamicLink->link =$dynamicData['general']->$dnmcPostType->$dnmcDataType;
   }

   if($dnmcType === 'general' && BRAVEPOP_WOO_ACTIVE && ($dnmcDataType === 'custom_cart_link' || $dnmcDataType === 'current_cart_link')){
      $productID = ''; global $wp_query;
      if(!empty($actionData->dynamicProductID)){   $productID = $actionData->dynamicProductID;  }
      if(isset($wp_query->post->post_type) && $wp_query->post->ID && $wp_query->post->post_type === 'product'){
         $productID = $wp_query->post->ID;
      }

      $item = wc_get_product( $productID );

      if($item){
         $classes = implode( ' ',  array( 'product_type_' . $item->get_type(), 'add_to_cart_button','ajax_add_to_cart' ) );
         $dynamicLink->classes = $classes;
         $dynamicLink->link = esc_url( $item->add_to_cart_url());
         $dynamicLink->attr = ' data-product_id="'.$productID.'" data-product_sku="'.$item->get_sku().'" data-quantity="1" onclick="brave_add_to_cart(\''.$elementID.'\')" ';
      }
   }

   return $dynamicLink;
}