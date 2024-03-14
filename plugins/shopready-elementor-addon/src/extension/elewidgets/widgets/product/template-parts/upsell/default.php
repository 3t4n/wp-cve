<?php 
defined( 'ABSPATH' ) || exit;
/**
 * Upsell
 * @since 1.0
 */

    $id = get_the_id();
  
    if(shop_ready_is_elementor_mode()){

        $temp_id = WC()->session->get('sr_single_product_id');
        if( $settings['show_product_content'] == 'yes' && is_numeric( $settings['wready_product_id'] ) ){
            $temp_id = $settings['wready_product_id'];
        } 

        if( is_numeric($temp_id) ){
            setup_postdata($temp_id);
        }else{
            setup_postdata(shop_ready_get_single_product_key());
        }
        
        if($settings['wready_product_id'] !=''){
            $id = $settings['wready_product_id'];
            global $product;
            $product = is_null($product)? wc_get_product($id): $product;
        }
        
    }

 
    $limit   = 4;
    $columns = 4;
    $orderby = 'rand';
    $order   = 'desc';
    
    if ( ! empty( $settings['columns'] ) ) {
        $columns = $settings['columns'];
    }

    if ( ! empty( $settings['orderby'] ) ) {
        $orderby = $settings['orderby'];
    }

    if ( ! empty( $settings['order'] ) ) {
        $order = $settings['order'];
    }

    if ( ! empty( $settings['limit'] ) ) {
        $limit = $settings['limit'];
    }

    woocommerce_upsell_display( $limit, $columns, $orderby, $order );