<?php 
if (! defined ('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_lion');
function epeken_invoke_lion($shipping) {
	$en_lion_onepack = get_option('epeken_enabled_lion_onepack');
	$en_lion_regpack = get_option('epeken_enabled_lion_regpack');
	if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
		$is_wcpv = false;
		$is_wcpv = epeken_is_wcpv_active();

		$en_lion_regpack_v = get_user_meta($shipping->vendor_id, 'vendor_lion_regpack', true);
		if ($is_wcpv)
			$en_lion_regpack_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_lion_regpack'];

		if($en_lion_regpack_v !== 'on' || $en_lion_regpack !== 'on')
			$en_lion_regpack = '';

		$en_lion_onepack_v = get_user_meta($shipping->vendor_id, 'vendor_lion_onepack', true);
		if ($is_wcpv)
			$en_lion_onepack_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_lion_onepack'];

		if($en_lion_onepack_v !== 'on' || $en_lion_onepack !== 'on')
			$en_lion_onepack = '';
	}
	if($en_lion_regpack === "on" || $en_lion_onepack === "on") {
		$content_lion_tarif = epeken_get_tarif_lion($shipping->shipping_city, $shipping->shipping_kecamatan,
				$shipping -> bulatkan_berat($shipping -> shipping_total_weight), $shipping->origin_city);

		$content_lion_decoded = json_decode($content_lion_tarif);
		if (!empty($content_lion_decoded)) {
		$content_lion_decoded = $content_lion_decoded -> {'tariflion'};
		if(!empty($content_lion_decoded)) {
		foreach($content_lion_decoded as $element) {
		 				 				 
		 $package_name = $element -> {'class'};
		 if($package_name === 'onepack' && $en_lion_onepack !== 'on')
			 continue;
		 if($package_name === 'regpack' && $en_lion_regpack !== 'on')
			 continue;
		 
		 
		 $cost_value = $element -> {'cost'};
		 if ($cost_value > 0) {
		  $markup = $shipping -> additional_mark_up('lion',$shipping -> shipping_total_weight);
		  if($cost_value > 0)
		    $cost_value = $cost_value + $markup;
		  array_push($shipping -> array_of_tarif, array('id' => 'lion_'.$package_name,'label' => 'lion parcel '.$package_name, 'cost' => $cost_value));
		 }
		}}
	 }
	}

   add_action('woocommerce_cart_calculate_fees', 'epeken_calculate_discount_lion');
}
function epeken_calculate_discount_lion() {
   global $woocommerce;

   $shipping = WC_Shipping::instance();
   $methods = $shipping -> get_shipping_methods();
   $epeken = $methods['epeken_courier'];

   if($epeken -> is_subsidi_applied)
	return;

   $chosen = WC()->session->get('chosen_shipping_methods');
   $value_diskon_lion = get_option('epeken_diskon_tarif_lion'); #percentage discount Lion
   $id_kurir = trim($chosen[0]);
   $price = 0; 
   $array_of_tarif = $epeken -> array_of_tarif;
   foreach($array_of_tarif as $t) {
         if($t['id'] === $id_kurir)
           {$price = $t['cost']; break;} 
   }    
   $discount = 0; 
   if($value_diskon_lion > 0 && in_array($id_kurir, array('lion_ONEPACK', 'lion_REGPACK'))) {
      $discount = -1 * ($value_diskon_lion/100) * $price;
      if (abs($discount) >= $price){
         $discount = -1 * $price;
      }
   }    
   if($discount < 0) 
    $woocommerce -> cart -> add_fee(__('Shipping Discount', 'epeken-all-kurir'), $discount , false, ''); 
}

?>
