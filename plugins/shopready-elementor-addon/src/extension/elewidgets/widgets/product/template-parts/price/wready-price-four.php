<?php

    /**
     * Product price, sales price
     * @since 1.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

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
    if(!method_exists($product,'get_price')){
       return;  
    }

  
   if( !is_numeric( $product->get_price() ) ){
        return;  
   }  



?>
<div class="wready-product-price">
    <?php echo wp_kses_post($settings['discount_prefix'] . wc_price($product->get_price()) .$settings['discount_suffix']); ?>
</div>
