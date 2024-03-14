<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_rpx');
function epeken_invoke_rpx($shipping) {
	$en_rpx_sdp = get_option('epeken_enabled_rpx_sdp');
	$en_rpx_mdp = get_option('epeken_enabled_rpx_mdp');
	$en_rpx_ndp = get_option('epeken_enabled_rpx_ndp');
	$en_rpx_rgp = get_option('epeken_enabled_rpx_rgp');

	if(epeken_is_multi_vendor_mode()  && epeken_is_vendor_id($shipping -> vendor_id))
	{
		$is_wcpv = false;
		$is_wcpv = epeken_is_wcpv_active();

		$en_rpx_sdp_v = get_user_meta($vendor_id, 'vendor_rpx_sdp', true);
		if($is_wcpv)
		    $en_rpx_sdp_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_rpx_sdp'];
	
		if($en_rpx_sdp_v !== 'on' || $en_rpx_sdp !== 'on') $en_rpx_sdp = '';
		$en_rpx_mdp_v = get_user_meta($vendor_id, 'vendor_rpx_mdp', true);
		if($is_wcpv)
		    $en_rpx_mdp_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_rpx_mdp'];
	        if($en_rpx_mdp_v !== 'on' || $en_rpx_mdp !== 'on') $en_rpx_mdp = '';
		
		$en_rpx_ndp_v = get_user_meta($vendor_id, 'vendor_rpx_ndp', true);
		if($is_wcpv)
		    $en_rpx_ndp_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_rpx_ndp'];
	
		if($en_rpx_ndp_v !== 'on' || $en_rpx_ndp !== 'on') $en_rpx_ndp = '';
		
		$en_rpx_rgp_v = get_user_meta($vendor_id, 'vendor_rpx_rgp', true);
		if($is_wcpv)
		    $en_rpx_rgp_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_rpx_rgp'];
	
		if($en_rpx_rgp_v !== 'on' || $en_rpx_rgp !== 'on') $en_rpx_rgp = '';
	}	

		if ($en_rpx_sdp === "on" || $en_rpx_mdp === "on" || $en_rpx_ndp === "on" || $en_rpx_rgp === "on") {
			$weight = $shipping -> shipping_total_weight*1000;
						$length = $shipping -> shipping_total_length;
						$width = $shipping -> shipping_total_width;
						$height = $shipping -> shipping_total_height;
						$price = $shipping -> get_cart_total() - $shipping -> get_discount();

			if($shipping -> current_currency !== "IDR") {
				$price = $price * ($shipping -> current_currency_rate);
			}

			 $cache_input_key = $shipping->shipping_city.'-'.$shipping->shipping_kecamatan.'-'.$shipping->origin_city.'-'.$weight.'-'.$price.'-'.$length.'-'.$width.'-'.$height.'_rpx';
			 $cache_input_key = preg_replace( '/[^\da-z]/i', '_', $cache_input_key );
			 $content_pos = '';
			 if(!empty(WC() -> session -> get($cache_input_key))) {
				$content_pos = WC() -> session -> get($cache_input_key);
			 }else{
			 	$content_pos = epeken_get_tarif_rpx(
			  	$shipping -> shipping_city,
				$shipping -> shipping_kecamatan, 
				$weight, $price, $length, $width, $height, 
				$shipping -> origin_city );
				WC() -> session -> set($cache_input_key, $content_pos);
			 }
			 
			 if(!empty($content_pos)) {
			  $content_pos_json_decode = json_decode($content_pos);
			  $content_pos_json_decode = $content_pos_json_decode -> {'tarifrpx'};
			  $is_eta = get_option('epeken_setting_eta');
			  if(!empty($content_pos_json_decode)) {
			   foreach($content_pos_json_decode as $element){
				   $package_name = $element -> {'class'};
				   $label = 'RPX '.$package_name;
				$cost_value = $element -> {'cost'};
				   $etd = $element -> {'etd'};
				   if($is_eta === 'on' && !empty($etd)) {
					   $etd = str_replace(' HARI','',$etd);
					   $label .= ' ('.$etd.' hari)';
				   }
				$markup = $shipping->additional_mark_up('pos',$shipping -> shipping_total_weight);
				if ($cost_value > 0)
                                  $cost_value = $cost_value + $markup;
				if((trim($package_name) === "SDP" && $en_rpx_sdp === "on") ||
					 (trim($package_name) === "MDP" && $en_rpx_mdp === "on") ||
					 (trim($package_name) === "NDP" && $en_rpx_ndp === "on") ||
					 (trim($package_name) === "RGP" && $en_rpx_rgp === "on") 
				  )
				  array_push($shipping -> array_of_tarif, array(
					  'id' => 'rpx_'.$package_name,
					  'label' => $label, 
					  'cost' => $cost_value));
			   }
			  } 
			 }
			}


}
?>
