<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_pos');
function epeken_invoke_pos($shipping) {
		$en_pos_reg = get_option('epeken_enabled_pos_reguler');
		$en_pos_sd = get_option('epeken_enabled_pos_sameday');
		$en_pos_nd = get_option('epeken_enabled_pos_nextday');
		$en_pos_bi = get_option('epeken_enabled_pos_biasa');
		$en_pos_kk = get_option('epeken_enabled_pos_kilat_khusus');
		$en_pos_end = get_option('epeken_enabled_pos_express_nextday');
		$en_pos_vg = get_option('epeken_enabled_pos_val_good');
		$en_pos_kprt = get_option('epeken_enabled_pos_kprt');
		$en_pos_kpru = get_option('epeken_enabled_pos_kpru');

		if(epeken_is_multi_vendor_mode()  && epeken_is_vendor_id($shipping -> vendor_id))
		{
			$is_wcpv = false;
			$is_wcpv = epeken_is_wcpv_active();

			$en_pos_reg_v = get_user_meta($shipping->vendor_id, 'vendor_pos_reguler', true);
			if ($is_wcpv)
			    $en_pos_reg_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_reguler'];

			if($en_pos_reg_v !== 'on' || $en_pos_reg !== 'on')
				$en_pos_reg = '';

			$en_pos_sd_v = get_user_meta($shipping->vendor_id, 'vendor_pos_sameday', true);
			if ($is_wcpv)
			    $en_pos_sd_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_sameday'];

			if($en_pos_sd_v !== 'on' || $en_pos_sd !== 'on')
				$en_pos_sd = '';

			$en_pos_nd_v = get_user_meta($shipping->vendor_id, 'vendor_pos_nextday', true);
			if ($is_wcpv)
                            $en_pos_nd_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_nextday'];

			if($en_pos_nd_v !== 'on' || $en_pos_nd !== 'on')
				$en_pos_nd = '';

			$en_pos_bi_v = get_user_meta($shipping->vendor_id, 'vendor_pos_biasa', true);
		  	if ($is_wcpv)
			     $en_pos_bi_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_biasa'];

			if($en_pos_bi_v !== 'on' || $en_pos_bi !== 'on')
				$en_pos_bi = '';

			$en_pos_kk_v = get_user_meta($shipping->vendor_id, 'vendor_pos_kilat_khusus', true);
			if ($is_wcpv)
			     $en_pos_kk_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_kilat_khusus'];

			if($en_pos_kk_v !== 'on' || $en_pos_kk !== 'on')
				$en_pos_kk = '';

			$en_pos_end_v = get_user_meta($shipping->vendor_id, 'vendor_pos_express_next_day', true);
			if ($is_wcpv)
			     $en_pos_end_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_express_next_day'];

			if($en_pos_end_v !== 'on' || $en_pos_end !== 'on')
				$en_pos_end = '';

			$en_pos_vg_v = get_user_meta($shipping->vendor_id, 'vendor_pos_valuable_goods', true);
			if ($is_wcpv)
			     $en_pos_vg_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_valuable_goods'];

			if($en_pos_vg_v !== 'on' || $en_pos_vg !== 'on')
				$en_pos_vg = '';

			$en_pos_kprt_v = get_user_meta($shipping->vendor_id, 'vendor_pos_kprt', true);
			if ($is_wcpv)
			     $en_pos_kprt_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_kprt'];

			if($en_pos_kprt_v !== 'on' || $en_pos_kprt !== 'on')
				$en_pos_kprt = '';
			
			$en_pos_kpru_v = get_user_meta($shipping->vendor_id, 'vendor_pos_kpru', true);
		 	if ($is_wcpv)
			     $en_pos_kpru_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_pos_kpru'];

			if($en_pos_kpru_v !== 'on' || $en_pos_kpru !== 'on')
				$en_pos_kpru = '';

		}	

			if ($en_pos_reg === 'on' || $en_pos_sd ==='on' || $en_pos_nd === 'on' || $en_pos_bi === "on" || $en_pos_kk === "on" || $en_pos_end === "on" || $en_pos_vg === "on" || $en_pos_kprt === "on" || $en_pos_kpru === "on") {
			 $weight = $shipping -> shipping_total_weight*1000;
                         $length = $shipping -> shipping_total_length;
                         $width = $shipping -> shipping_total_width;
                         $height = $shipping -> shipping_total_height;
                         $price = $shipping -> get_cart_total() - $shipping -> get_discount();

			 if($shipping -> current_currency !== "IDR") {
				$price = $price * ($shipping -> current_currency_rate);
			 }

			 $cache_input_key = $shipping->shipping_city.'-'.$shipping->shipping_kecamatan.'-'.$shipping->origin_city.'-'.$weight.'-'.$price.'-'.$length.'-'.$width.'-'.$height.'_pos';
			 $cache_input_key = preg_replace( '/[^\da-z]/i', '_', $cache_input_key );
			 $content_pos = '';
			 if(!empty(WC() -> session -> get($cache_input_key))) {
				$content_pos = WC() -> session -> get($cache_input_key);
			 }else{
			 	$content_pos = epeken_get_tarif_pt_pos_v3(
			  	$shipping -> shipping_city,
				$shipping -> shipping_kecamatan, 
				$weight, $price, $length, $width, $height, 
				$shipping -> origin_city );
				WC() -> session -> set($cache_input_key, $content_pos);
			 }
			 
			 if(!empty($content_pos)) {
			  $content_pos_json_decode = json_decode($content_pos);
			  $content_pos_json_decode = $content_pos_json_decode -> {'tarifpos'};
			  $is_eta = get_option('epeken_setting_eta');
			  if(!empty($content_pos_json_decode)) {
			   foreach($content_pos_json_decode as $element){
				   $package_name = $element -> {'class'};
				   $label = "PT POS - ". $package_name;
				$cost_value = $element -> {'cost'};
				   $etd = $element -> {'etd'};
				   if($is_eta === 'on' && !empty($etd)) {
					   $etd = str_replace(' HARI','',$etd);
					   $label .= ' ('.$etd.' hari)';
				   }
				$markup = $shipping->additional_mark_up('pos',$shipping -> shipping_total_weight);
				if ($cost_value > 0) 
                                   $cost_value = $cost_value + $markup;
				if((trim($package_name) === "POS REGULER" && $en_pos_reg === "on") || 
				   (trim($package_name) === "POS SAMEDAY" && $en_pos_sd === "on") || 
				   (trim($package_name) === "POS NEXTDAY" && $en_pos_nd === "on") || 
				   (trim($package_name) === "PAKET KILAT KHUSUS" && $en_pos_kk === "on") ||
			 	   (trim($package_name) === "EXPRESS NEXT DAY BARANG" && $en_pos_end === "on") ||
				   (trim($package_name) === "PAKETPOS VALUABLE GOODS" && $en_pos_vg === "on") ||
				   (trim($package_name) === "PAKETPOS BIASA" && $en_pos_bi === "on") || 
				   (trim($package_name) === "KARGOPOS RITEL TRAIN" && $en_pos_kprt === "on") || 
				   (trim($package_name) === "KARGOPOS RITEL UDARA DN" && $en_pos_kpru === "on")
				  )
				  array_push($shipping -> array_of_tarif, array(
					  'id' => $package_name,
					  'label' => $label, 
					  'cost' => $cost_value));
			   }
			  } 
			 }
			}
		add_action('woocommerce_cart_calculate_fees', 'epeken_calculate_discount_pos');
}
function epeken_calculate_discount_pos() {
   global $woocommerce;
   $shipping = WC_Shipping::instance();
   $methods = $shipping -> get_shipping_methods();
   $epeken = $methods['epeken_courier'];

   if($epeken -> is_subsidi_applied)
     return;

   $chosen = WC()->session->get('chosen_shipping_methods');
   $value_diskon_pos = get_option('epeken_diskon_tarif_pos'); #percentage discount POS
   $id_kurir = $chosen[0];
   $price = 0; 
   $array_of_tarif = $epeken -> array_of_tarif;
   foreach($array_of_tarif as $t) {
         if($t['id'] === $id_kurir)
           {$price = $t['cost']; break;} 
   }    
   $discount = 0; 
   if($value_diskon_pos > 0 && in_array($id_kurir, array('POS REGULER','POS SAMEDAY','POS NEXTDAY'))) {
      $discount = -1 * ($value_diskon_pos/100) * $price;
      if (abs($discount) >= $price){
         $discount = -1 * $price;
      }
   }    
   if($discount < 0) 
    $woocommerce -> cart -> add_fee(__('Shipping Discount', 'epeken-all-kurir'), $discount , false, ''); 
}

?>
