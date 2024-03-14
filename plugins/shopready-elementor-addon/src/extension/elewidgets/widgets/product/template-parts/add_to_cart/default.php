<?php 

/**
 * default Add to cart | Simple Product
 */

    defined( 'ABSPATH' ) || exit;

    $id = get_the_id();

    if(shop_ready_is_elementor_mode()){

        if($settings['wready_product_id'] !=''){
            $id = $settings['wready_product_id'];
        }

    }

    global $product;
    $product = is_null($product)? wc_get_product($id): $product;
    if(!is_object($product)){
        return;
    }
    if( !method_exists($product,'get_type') ){
        return;
    }
   
    include(__DIR__.'/types/'.$product->get_type().'.php');
  