<?php

class pluginSupport extends WC_Peach_Payments{

	function __construct() {
		
	}
	
	function sequentialNumbers($order, $reversed){
		if(in_array('woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php', apply_filters('active_plugins', get_option('active_plugins')))){
			if($reversed == 1){
				return $this->convertSequentialNumber($order);
			}else{
				return $order->get_id();
			}

		}else if(in_array('wt-woocommerce-sequential-order-numbers/wt-advanced-order-number.php', apply_filters('active_plugins', get_option('active_plugins')))){
			if($reversed == 1){
				return $this->convertSequentialNumber($order);
			}else{
				return $order->get_order_number();
			}

		}else{
			if(isset($order) && is_object($order)){
				return $order->get_id();
			}else{
				return $order;
			}
		}
	}
	
	function convertSequentialNumber($orderNum){
		$query_args = array( 
            'numberposts' => 1,  
            'meta_key' => '_order_number',  
            'meta_value' => $orderNum,  
            'post_type' => 'shop_order',  
            'post_status' => 'any',  
            'fields' => 'ids',  
 ); 
 
        $posts = get_posts( $query_args ); 
        list( $order_id ) = ! empty( $posts ) ? $posts : null; 
 
        // order was found 
        if ( $order_id !== null ) { 
            return $order_id; 
        }else{
			return $orderNum;
		} 
	}
	
}