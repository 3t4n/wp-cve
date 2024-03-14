<?php

function bravepop_EDD_filter_match($rules){
   $EDDMatch = true; $userID = get_current_user_id();
   $cart_content_IDS = array();

   if(function_exists('edd_has_user_purchased') && function_exists('edd_get_cart_contents') ){

      $cart_contents = edd_get_cart_contents();
      if ( ! empty( $cart_contents ) ) {
         foreach ( $cart_contents as $item ) {
            if(isset($item['id'])){
               $cart_content_IDS[] = $item['id'];
            }
         }
      }

      foreach ($rules as $key => $EDDRule) {
         if($EDDMatch  && !empty($EDDRule->id) ){
            //Past Product Match
            if(function_exists('edd_has_user_purchased') && $EDDRule->action === 'purchased'){
               if(edd_has_user_purchased( $userID, absint($EDDRule->id)) === false ){   $EDDMatch = false;  }
            }
            if(function_exists('edd_has_user_purchased') && $EDDRule->action === 'not_purchased'){
               if(edd_has_user_purchased( $userID, absint($EDDRule->id)) === true ){   $EDDMatch = false;  }
            }
            //Cart Match
            if($EDDRule->action === 'in_cart' && in_array(absint($EDDRule->id), $cart_content_IDS) === false){
               $EDDMatch = false;
            }
         }
      }
   }

   return $EDDMatch;
}

function bravepop_rest_EDD_objects($addedItemIDs){
   $EDDObjects = new stdClass();
   $allDownloads = [];

   $downloads = get_posts(array( 'post_type' => 'download', 'numberposts' => -1 ));
   
   foreach ( $downloads as $dlItem ) {
      //$variable_pricing = get_post_meta( $dlItem->ID, '_variable_pricing', true );
      //$prices = get_post_meta( $dlItem->ID, 'edd_variable_prices', true );
       $object = new stdClass();
       $object->ID = $dlItem->ID ;
       $object->title = $dlItem->post_title ;
       $object->link = esc_url(get_permalink( $dlItem->ID )) ;
       $object->slug = $dlItem->post_name ;
       if(!in_array($dlItem->ID, $addedItemIDs)){
        $allDownloads[] = $object;
        $addedItemIDs[] = $dlItem->ID;
       }
   }

   $EDDObjects->downloads = $allDownloads;
   
   return $EDDObjects;
}