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
    
    if(!method_exists($product,'get_regular_price')){
       return;  
    }

   if( !is_numeric( $product->get_regular_price() ) || !is_numeric( $product->get_sale_price() ) ){
        return;  
   }  

    if( $settings['price_in_percent'] == 'yes' ){
        if( ( is_numeric($product->get_sale_price()) && !empty($product->get_sale_price()) ) && ( is_numeric($product->get_regular_price()) && !empty($product->get_regular_price()) ) ){
            $save =  number_format((float)($product->get_regular_price()-$product->get_sale_price()) / $product->get_regular_price() * 100,1,'.','') . '%';
        }
    }else{
        $save = $product->get_regular_price() - $product->get_sale_price();
    }
   
    if(!isset($save)){
       return;
    }

?>
<div class="wready-product-price">
    
     <?php if($settings['price_in_percent'] == 'yes'): ?>
        <?php echo wp_kses_post($settings['discount_prefix'] . $save .$settings['discount_suffix']); ?>
    <?php else: ?>
        <?php echo wp_kses_post($settings['discount_prefix'] . wc_price($save) .$settings['discount_suffix']); ?>
    <?php endif; ?>
   
</div>
