<?php
/* This code is included from set_shipping_cost method of WC_Tikijne_Shipping class */
if(! defined ('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_ninja');
function epeken_invoke_ninja($shipping) {
  $en_ninja_next_day = get_option('epeken_enabled_ninja_next_day'); $en_ninja_standard = get_option('epeken_enabled_ninja_standard');
			if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)){
				$is_wcpv = false;
				$is_wcpv = epeken_is_wcpv_active();

				$en_ninja_next_day_v = get_user_meta($shipping -> vendor_id, 'vendor_ninja_next_day', true);
				if ($is_wcpv)
			    	  $en_ninja_next_day_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_ninja_next_day'];

				if ($en_ninja_next_day_v !== 'on' || $en_ninja_next_day !== 'on')
					$en_ninja_next_day = '';

				$en_ninja_standard_v = get_user_meta($shipping -> vendor_id, 'vendor_ninja_standard', true);
				if ($is_wcpv)
			    	  $en_ninja_standard_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_ninja_standard'];

				if ($en_ninja_standard_v !== 'on' || $en_ninja_standard !== 'on')
				       $en_ninja_standard = '';	
			}
			if($en_ninja_next_day === 'on' || $en_ninja_standard === 'on') {
				$weight = $shipping -> bulatkan_berat($shipping -> shipping_total_weight);
				$cache_input_key = $shipping->shipping_city.'-'.$shipping->shipping_kecamatan.'-'.$shipping->origin_city.'-'.$weight.'_ninja';
                         	$cache_input_key = preg_replace( '/[^\da-z]/i', '_', $cache_input_key );
                         	$content_ninja = '';
                         	if(!empty(WC() -> session -> get($cache_input_key))) {
                                	$content_ninja = WC() -> session -> get($cache_input_key);
                         	}else{
                                 	$content_ninja = epeken_get_ninja_express_tariff($shipping -> shipping_city, $shipping -> shipping_kecamatan, 
                                                $shipping -> bulatkan_berat($shipping -> shipping_total_weight), $shipping -> origin_city);
					WC() -> session -> set($cache_input_key, $content_ninja);
                         	}	
				$content_ninja_decode = json_decode($content_ninja, true);
				if(!empty($content_ninja_decode)) {
					foreach($content_ninja_decode['tarifninja'] as $rate){
						$class = $rate['class']; $cost = $rate['cost'];
						$markup = $shipping -> additional_mark_up('ninja', $shipping -> shipping_total_weight);
						if ($cost > 0)
					          $cost = $cost + $markup;
						if($class === 'NEXT_DAY') {$class = 'NEXT DAY';}
						if(($en_ninja_next_day === 'on' && $class === 'NEXT DAY') || ($en_ninja_standard === 'on' && $class === 'STANDARD')) {
						 array_push($shipping -> array_of_tarif, array('id' => 'ninja_'.strtolower($class),
								'label' => 'NINJA '.$class,'cost' => $cost));
						}
					}
				}
			}
   add_action('woocommerce_cart_calculate_fees', 'epeken_calculate_discount_ninja');
}
function epeken_calculate_discount_ninja() {
   global $woocommerce;
   $shipping = WC_Shipping::instance();
   $methods = $shipping -> get_shipping_methods();
   $epeken = $methods['epeken_courier'];

   if($epeken -> is_subsidi_applied)
     return;

   $chosen = WC()->session->get('chosen_shipping_methods');
   $value_diskon_ninja= get_option('epeken_diskon_tarif_ninja'); #percentage discount Ninja
   $id_kurir = $chosen[0];
   $price = 0; 
   $array_of_tarif = $epeken -> array_of_tarif;
   foreach($array_of_tarif as $t) {
         if($t['id'] === $id_kurir)
           {$price = $t['cost']; break;} 
   }    
   $discount = 0; 
   $logger = new WC_Logger(); $logger -> add('epeken-all-kurir', $id_kurir);
   if($value_diskon_ninja > 0 && in_array($id_kurir, array('ninja_standard','ninja_next day'))) {
      $discount = -1 * ($value_diskon_ninja/100) * $price;
      if (abs($discount) >= $price){
         $discount = -1 * $price;
      }
   }    
   if($discount < 0) 
    $woocommerce -> cart -> add_fee(__('Shipping Discount', 'epeken-all-kurir'), $discount , false, ''); 
}
?>
