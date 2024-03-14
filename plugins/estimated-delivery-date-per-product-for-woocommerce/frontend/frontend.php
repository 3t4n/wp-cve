<?php 
/*display date single product page*/
function esdppfw_display_product_page(){
	global $product;
	
	// echo "<pre>";
	// print_r($product->get_id());
	// echo "</pre>";
	$ena_est_date_all_pro = get_option('est_date_ena_all_pro','');
	$all_product_est_date = get_option('est_delvry_date_all_pro','2');
	$est_date_delvry = get_post_meta($product->get_id(),'est_date_delivry_time',true);
	$delvry_text_pro_page = get_option('delvry_text_pro_page','this item will be delivery on');
	$delivry_datetext = get_post_meta($product->get_id(),'delivry_datetext',true);
	$delvry_text_outstock = get_post_meta($product->get_id(),'delvry_text_outstock',true);

	$delvry_date_format = 'd,F Y';
	//print_r($delivry_datetext);
	if ($ena_est_date_all_pro == 'yes') {
		$cha_est_date = $all_product_est_date;
		if ( ! $product->is_in_stock()) {
			$delvry_text_setting = $delvry_text_outstock;
		}else{
			$delvry_text_setting = $delvry_text_pro_page;
		}
	}else{
		$cha_est_date = $est_date_delvry;
		if ( ! $product->is_in_stock()) {
			$delvry_text_setting = $delvry_text_outstock;
		}else{
			$delvry_text_setting = $delivry_datetext;
		}
	}

	if (!empty($cha_est_date)) {
		$date = strtotime(+$cha_est_date. "day");
		$mdate = date($delvry_date_format, $date);
		?>
		<p class="deli_description"><?php echo esc_attr($delvry_text_setting).' '. esc_attr($mdate); ?></p>
		<?php
	}
	?>
	<style>
		.deli_description {
		 		background-color: <?php echo esc_attr(get_option('single_pro_delivry_text_bg','#f5f5f5')); ?>!important;
		    color: <?php echo esc_attr(get_option('single_pro_delivry_text_color','#ff0000')); ?>!important;
		}
	</style>
	<?php
}

/*Display Est date on cart and checkout page (specific products)*/
function esdppfw_display_estdate_cartpage($item_data, $cart_item){
	$est_date_delvry = get_post_meta($cart_item['product_id'],'est_date_delivry_time',true);
	$delvry_text_cart_checkout = get_option('delvry_text_cart_checkout','your order will be delivery on');
	$hide_product_backorder = get_option('hide_product_backorder','');
	$delivry_datetext = get_post_meta($cart_item['product_id'],'delivry_datetext',true);
	// echo "<pre>";
	// print_r($delivry_datetext);
	// echo "</pre>";
	$delvry_date_format = 'd,F Y';
			$all_product_est_date = get_option('est_delvry_date_all_pro','2');
			$ena_est_date_all_pro = get_option('est_date_ena_all_pro','');
			if ($hide_product_backorder == 'yes') {
				if ($cart_item['data']->is_on_backorder( $cart_item['quantity'] )) {
					unset($est_date_delvry);
					$est_date_delvry = "";
				}
			}else{
				$est_date_delvry = get_post_meta($cart_item['product_id'],'est_date_delivry_time',true);
			}

			if ($ena_est_date_all_pro == 'yes') {
				$cha_est_date = '';
				$delvry_text_setting = '';
			}else{
				
				$cha_est_date = $est_date_delvry;
				$delvry_text_setting = $delivry_datetext;
			}
	if (!empty($cha_est_date)) {
		$date = strtotime(+$cha_est_date. "day");
		$mdate = date($delvry_date_format, $date);
		// echo "<pre>";
		// print_r($mdate);
		// echo "</pre>";
	}


		if ( empty( $mdate ) ) {
			return $item_data;
		}
		$item_data[] = array(
			'key'     => __( 'Est Date' ),
			'value'   => wc_clean($delvry_text_setting.' '.$mdate ),
			'display' => '',
		);

		return $item_data;

}

/*display date cart page (for all products)*/
function esdppfw_display_est_date_on_cart_page(){
	global $woocommerce;

	$all_product_est_date = get_option('est_delvry_date_all_pro','2');
	$ena_est_date_all_pro = get_option('est_date_ena_all_pro','');
	$delvry_text_cart_checkout = get_option('delvry_text_cart_checkout','your order will be delivery on');
		//print_r($all_product_est_date);
	$delvry_date_format = 'd,F Y';
	if ($ena_est_date_all_pro == 'yes') {
		$cha_est_date = $all_product_est_date;
		$delvry_text_setting = $delvry_text_cart_checkout;
	}else{
		$cha_est_date = '';
		$delvry_text_setting = '';
	}

	if (!empty($cha_est_date)) {
		$date = strtotime(+$cha_est_date. "day");
		$mdate = date($delvry_date_format, $date);
	
	
		?>
		<p class="cart_est_desc cart"><?php echo esc_attr($delvry_text_setting).' '.esc_attr($mdate); ?></p>
		<?php
	}
	?>
	<style>
		.cart_est_desc {
		 		background-color: <?php echo esc_attr(get_option('delivry_text_bg','#000000')); ?>!important;
		    color: <?php echo esc_attr(get_option('delivry_text_color','#ffffff')); ?>!important;
		}
	</style>
	<?php
}

/*display date checkout page*/
function esdppfw_display_est_on_checkout_page(){
	global $woocommerce;

	$all_product_est_date = get_option('est_delvry_date_all_pro','2');
	$ena_est_date_all_pro = get_option('est_date_ena_all_pro','');
	$delvry_text_cart_checkout = get_option('delvry_text_cart_checkout','your order will be delivery on');
		//print_r($all_product_est_date);
	$delvry_date_format = 'd,F Y';
	if ($ena_est_date_all_pro == 'yes') {
		$cha_est_date = $all_product_est_date;
		$delvry_text_setting = $delvry_text_cart_checkout;
	}else{
		$cha_est_date = '';
		$delvry_text_setting = '';
	}

	if (!empty($cha_est_date)) {
		$date = strtotime(+$cha_est_date. "day");
		$mdate = date($delvry_date_format, $date);
	
	
		?>
		<p class="cart_est_desc check"><?php echo esc_attr($delvry_text_setting).' '.esc_attr($mdate); ?></p>
		<?php
	}
	?>
	<style>
		.cart_est_desc {
		 		background-color: <?php echo esc_attr(get_option('delivry_text_bg','#000000')); ?>!important;
		    color: <?php echo esc_attr(get_option('delivry_text_color','#ffffff')); ?>!important;
		}
	</style>
	<?php
}

  function esdppfw_add_values_to_order_item_meta($item_id, $values)
  {
  	global $woocommerce,$wpdb;
  	$all_product_est_date = get_option('est_delvry_date_all_pro','2');
	$ena_est_date_all_pro = get_option('est_date_ena_all_pro','');
	$delvry_text_order_page = get_option('delvry_text_order_page','your order will be delivery on');
	//print_r($delvry_text_order_page);
  	$est_date_delvry = get_post_meta($values['product_id'],'est_date_delivry_time',true);
  	$delvry_text_orderpage = get_post_meta($values['product_id'],'delvry_text_orderpage',true);
  	$delvry_date_format = 'd,F Y';
  	 $hide_product_backorder = get_option('hide_product_backorder','');
  	if ($hide_product_backorder == 'yes') {
		if ($values['data']->is_on_backorder( $values['quantity'] )) {
			unset($est_date_delvry);
			$est_date_delvry = "";
		}
	}else{
			$est_date_delvry = get_post_meta($values['product_id'],'est_date_delivry_time',true);
	}

  if ($ena_est_date_all_pro == 'yes') {
		$cha_est_date = '';
		$delvry_text_setting = '';
	}else{
		$cha_est_date = $est_date_delvry;
		$delvry_text_setting = $delvry_text_orderpage;

		if (!empty($cha_est_date)) {  
	        $date = strtotime(+$cha_est_date. "day");
			$mdate = $delvry_text_setting.' '.date($delvry_date_format, $date);
	        // echo "<pre>";
	        // print_r($mdate);
	        // echo "</pre>";
	        if(!empty($mdate))
	        {
	            wc_add_order_item_meta($item_id,'order_est_date',$mdate);  
	        }
	          	//exit();
	        if(array_key_exists('order_est_date', $values))
		    {
		        $item->add_meta_data('order_est_date',$values['order_est_date']);
		        //$item->update_meta_data( 'Custom label', $values['order_est_date'] );
		    }
			}
	}
	//print_r($delvry_text_setting);
	//exit();

	    // if (!empty($cha_est_date)) {  
	    //     $date = strtotime(+$cha_est_date. "day");
			// $mdate = $delvry_text_setting.' '.date($delvry_date_format, $date);
	    //     // echo "<pre>";
	    //     // print_r($mdate);
	    //     // echo "</pre>";
	    //     if(!empty($mdate))
	    //     {
	    //         wc_add_order_item_meta($item_id,'order_est_date',$mdate);  
	    //     }
	    //       	//exit();
	    //     if(array_key_exists('order_est_date', $values))
		  //   {
		  //       $item->add_meta_data('order_est_date',$values['order_est_date']);
		  //       //$item->update_meta_data( 'Custom label', $values['order_est_date'] );
		  //   }
			// }
  }


/*display date thank you page*/
function esdppfw_display_est_on_thankyou_page() {
	$all_product_est_date = get_option('est_delvry_date_all_pro','2');
	$ena_est_date_all_pro = get_option('est_date_ena_all_pro','');
	$delvry_text_order_page = get_option('delvry_text_order_page','your order will be delivery on');
	//print_r($all_product_est_date);
	$delvry_date_format = 'd,F Y';
	if ($ena_est_date_all_pro == 'yes') {
		$cha_est_date = $all_product_est_date;
		$delvry_text_setting = $delvry_text_order_page;
	}else{
		$cha_est_date = '';
		$delvry_text_setting = '';
	}

	if (!empty($cha_est_date)) {
		$date = strtotime(+$cha_est_date. "day");
		$mdate = date($delvry_date_format, $date);
	
	
		?>
		<p class="cart_est_desc"><?php echo esc_attr($delvry_text_setting).' '.esc_attr($mdate); ?></p>
		<?php
	}
	?>
	<style>
		.cart_est_desc {
		 		background-color: <?php echo esc_attr(get_option('delivry_text_bg','#000000')); ?>!important;
		    color: <?php echo esc_attr(get_option('delivry_text_color','#ffffff')); ?>!important;
		}
	</style>
	<?php
}


/* display text product order */
function esdppfw_display_est_on_order_details($order){
	$all_product_est_date = get_option('est_delvry_date_all_pro','2');
	$ena_est_date_all_pro = get_option('est_date_ena_all_pro','');
	$delvry_text_order_page = get_option('delvry_text_order_page','your order will be delivery on');
		//print_r($all_product_est_date);
	$delvry_date_format = 'd,F Y';
	if ($ena_est_date_all_pro == 'yes') {
		$cha_est_date = $all_product_est_date;
		$delvry_text_setting = $delvry_text_order_page;
	}else{
		$cha_est_date = '';
		$delvry_text_setting = '';
	}

	if (!empty($cha_est_date)) {
		$date = strtotime(+$cha_est_date. "day");
		$mdate = date($delvry_date_format, $date);
	
	
		?>
		<p class="cart_est_desc"><?php echo esc_attr($delvry_text_setting).' '.esc_attr($mdate); ?></p>
		<?php
	}
	?>
	<style>
		.cart_est_desc {
		 		background-color: <?php echo esc_attr(get_option('delivry_text_bg','#000000')); ?>!important;
		    color: <?php echo esc_attr(get_option('delivry_text_color','#ffffff')); ?>!important;
        text-align: center;
		}
	</style>
	<?php
}


/*display my account view order page*/
add_action('woocommerce_view_order','esdppfw_display_vieworder_page');
function esdppfw_display_vieworder_page($has_orders){
	$all_product_est_date = get_option('est_delvry_date_all_pro','2');
	$ena_est_date_all_pro = get_option('est_date_ena_all_pro','');
	$delvry_text_order_page = get_option('delvry_text_order_page','your order will be delivery on');
		//print_r($all_product_est_date);
	$delvry_date_format = 'd,F Y';
	if ($ena_est_date_all_pro == 'yes') {
		$cha_est_date = $all_product_est_date;
		$delvry_text_setting = $delvry_text_order_page;
	}else{
		$cha_est_date = '';
		$delvry_text_setting = '';
	}

	if (!empty($cha_est_date)) {
		$date = strtotime(+$cha_est_date. "day");
		$mdate = date($delvry_date_format, $date);
	
	
		?>
		<p class="cart_est_desc"><?php echo esc_attr($delvry_text_setting).' '.esc_attr($mdate); ?></p>
		<?php
	}
	?>
	<style>
		.cart_est_desc {
		 		background-color: <?php echo esc_attr(get_option('delivry_text_bg','#000000')); ?>!important;
		    color: <?php echo esc_attr(get_option('delivry_text_color','#ffffff')); ?>!important;
		}
	</style>
	<?php
}


add_action('init','esdppfw_all_action_setting');
function esdppfw_all_action_setting(){
	
	$display_est_date_enab_disab = get_option('est_delvry_date','yes');
	if ($display_est_date_enab_disab == 'yes') {

		/* Position For Single Product */
		$est_text_position = get_option('delvry_text_position_sinpro');
		$est_date_display_single_pro = get_option('est_date_display_single_pro','yes');
		if ($est_date_display_single_pro == 'yes') {
			if ($est_text_position == 'single_pro_sum') {
				add_action('woocommerce_single_product_summary','esdppfw_display_product_page');
			}else if ($est_text_position == 'before_atc_btn') {
				add_action('woocommerce_before_add_to_cart_button','esdppfw_display_product_page');
			}else if ($est_text_position == 'after_atc_quantity') {
				add_action('woocommerce_after_add_to_cart_quantity','esdppfw_display_product_page');
			}else if ($est_text_position == 'after_atc_btn') {
				add_action('woocommerce_after_add_to_cart_button','esdppfw_display_product_page');
			}else if ($est_text_position == 'pro_meta_start') {
				add_action('woocommerce_product_meta_start','esdppfw_display_product_page');
			}else if ($est_text_position == 'pro_meta_end') {
				add_action('woocommerce_product_meta_end','esdppfw_display_product_page');
			}
		}

		$est_display_on_cartpage = get_option('est_display_on_cartpage','yes');
		if ($est_display_on_cartpage == 'yes') {
			$delvry_text_position_cart = get_option('delvry_text_position_cart');
			if($delvry_text_position_cart == 'before_cart_table'){
				add_action('woocommerce_before_cart_table','esdppfw_display_est_date_on_cart_page');
			}else if ($delvry_text_position_cart == 'after_cart_table') {
				add_action('woocommerce_after_cart_table','esdppfw_display_est_date_on_cart_page');
			}

			add_action('woocommerce_get_item_data','esdppfw_display_estdate_cartpage', 10, 2);
		}

		$est_display_on_checkoutpage = get_option('est_display_on_checkoutpage','yes');

		if($est_display_on_checkoutpage == 'yes') {
			$delvry_text_position_checkout = get_option('delvry_text_position_checkout');
			if($delvry_text_position_checkout == 'before_order_review'){
				add_action('woocommerce_checkout_before_order_review','esdppfw_display_est_on_checkout_page');
			}else if ($delvry_text_position_checkout == 'review_order_before_payment') {
				add_action('woocommerce_review_order_before_payment','esdppfw_display_est_on_checkout_page');
			}
		}

		$display_order_page = get_option('est_display_on_orderpage','yes');
		//echo "noncs";
		if ($display_order_page == 'yes') {
			$delvry_text_position_order = get_option('delvry_text_position_order');
			if($delvry_text_position_order == 'before_order_detail') {
				add_action( 'woocommerce_thankyou', 'esdppfw_display_est_on_thankyou_page', 9);
			}else if($delvry_text_position_order == 'inside_order_detail'){
				add_action('woocommerce_order_details_after_order_table_items', 'esdppfw_display_est_on_thankyou_page');
			}else if ($delvry_text_position_order == 'after_customer_detail') {
				add_action('woocommerce_order_details_after_customer_details', 'esdppfw_display_est_on_thankyou_page');
			} 

			add_action('woocommerce_add_order_item_meta','esdppfw_add_values_to_order_item_meta',1,2);
			add_action( 'woocommerce_admin_order_data_after_billing_address', 'esdppfw_display_est_on_order_details', 10, 1 );

			add_action('woocommerce_email_before_order_table', 'esdppfw_display_est_on_order_details');
		}
	}

}
