<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_sicepat');
function epeken_invoke_sicepat($shipping) {
	$en_sicepat_reg = sanitize_text_field(get_option('epeken_enabled_sicepat_reg'));
	$en_sicepat_best = sanitize_text_field(get_option('epeken_enabled_sicepat_best'));
	$en_sicepat_siunt = sanitize_text_field(get_option('epeken_enabled_sicepat_siunt'));
	$en_sicepat_gokil = sanitize_text_field(get_option('epeken_enabled_sicepat_gokil'));
	$en_sicepat_sds = sanitize_text_field(get_option('epeken_enabled_sicepat_sds'));

	if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
		$is_wcpv = false;
		$is_wcpv = epeken_is_wcpv_active();

		$en_sicepat_reg_v = get_user_meta($shipping -> vendor_id, 'vendor_sicepat_reg', true);
		if ($is_wcpv)
	    		$en_sicepat_reg_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_sicepat_reg'];

		if($en_sicepat_reg_v !== 'on' || $en_sicepat_reg !== 'on') {
			$en_sicepat_reg = '';
		}
		$en_sicepat_best_v = get_user_meta($shipping -> vendor_id, 'vendor_sicepat_best', true);
		if ($is_wcpv)
	    		$en_sicepat_best_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_sicepat_best'];

		if($en_sicepat_best_v !== 'on' || $en_sicepat_best !== 'on') {
			$en_sicepat_best = '';
		}
		$en_sicepat_gokil_v = get_user_meta($shipping -> vendor_id, 'vendor_sicepat_gokil', true);
		if ($is_wcpv)
	    		$en_sicepat_gokil_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_sicepat_gokil'];

		if($en_sicepat_gokil_v !== 'on' || $en_sicepat_gokil !== 'on') {
			$en_sicepat_gokil = '';
		}
		$en_sicepat_siunt_v = get_user_meta($shipping -> vendor_id, 'vendor_sicepat_siunt', true);
		if ($is_wcpv)
	    		$en_sicepat_siunt_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_sicepat_siunt'];

		if($en_sicepat_siunt_v !== 'on' || $en_sicepat_siunt !== 'on') {
			$en_sicepat_siunt = '';
		}
		$en_sicepat_sds_v = get_user_meta($shipping -> vendor_id, 'vendor_sicepat_sds', true);
		if ($is_wcpv)
	    		$en_sicepat_sds_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_sicepat_sds'];

		if($en_sicepat_sds_v !== 'on' || $en_sicepat_sds !== 'on') {
			$en_sicepat_sds = '';
		}
	}
	if($en_sicepat_reg === "on" || $en_sicepat_best === "on"  ||
	   $en_sicepat_siunt === 'on' || $en_sicepat_gokil === 'on' || 
	   $en_sicepat_sds === 'on') {
		$content_sicepat = epeken_get_sicepat_ongkir($shipping -> shipping_city, 
			$shipping -> shipping_kecamatan, 
			$shipping->bulatkan_berat($shipping->shipping_total_weight), 
			$shipping -> origin_city
		);
		$content_sicepat_decoded = json_decode($content_sicepat);
		$content_sicepat_decoded = $content_sicepat_decoded -> {'tarifsicepat'};
		if(!empty($content_sicepat_decoded)) {
		foreach($content_sicepat_decoded as $element) {
		    $package_name = $element -> {'class'}; 
		    if($package_name === "REGULAR" && $en_sicepat_reg !== "on") continue; 
		    if($package_name === "BEST" && $en_sicepat_best !== "on") continue; 
		    if($package_name === "SIUNT" && $en_sicepat_siunt !== "on") continue; 
		    if($package_name === "GOKIL" && $en_sicepat_gokil !== "on") continue; 
		    if($package_name === "SDS" && $en_sicepat_sds !== "on") continue;

		    $markup = $shipping -> additional_mark_up('sicepat',$shipping -> shipping_total_weight);
		    $cost_value = $element -> {'cost'}; 
	            if($cost_value > 0)
		       $cost_value = $cost_value + $markup;
		    $etd = $element -> {'etd'};
		    $label = 'SICEPAT '.$package_name;
		    $is_eta = get_option('epeken_setting_eta');
		    if($is_eta === 'on')
			    $label .= ' ('.$etd.')';
		    if ($cost_value !== "0" && !empty($cost_value)) 
		    array_push($shipping -> array_of_tarif, 
   			array('id' => 'sicepat_'.$package_name,
						          'label' => $label, 
							  'cost' => $cost_value));
		   }
		  }
		 }
   add_action('woocommerce_cart_calculate_fees', 'epeken_calculate_discount_sicepat');
}
function epeken_calculate_discount_sicepat() {
   global $woocommerce;
   
   $shipping = WC_Shipping::instance();
   $methods = $shipping -> get_shipping_methods();
   $epeken = $methods['epeken_courier'];

   if ($epeken -> is_subsidi_applied) {
	return; 
   }

   $chosen = WC()->session->get('chosen_shipping_methods');
   $value_diskon_sicepat = get_option('epeken_diskon_tarif_sicepat'); #percentage discount Sicepat
   $id_kurir = $chosen[0];
   $price = 0; 

   $array_of_tarif = $epeken -> array_of_tarif;
   foreach($array_of_tarif as $t) {
         if($t['id'] === $id_kurir)
           {$price = $t['cost']; break;} 
   }    
   $discount = 0; 
   if($value_diskon_sicepat > 0 && in_array($id_kurir, array('sicepat_REGULAR', 'sicepat_BEST', 'sicepat_SIUNT', 'sicepat_GOKIL', 'sicepat_SDS'))) {
      $discount = -1 * ($value_diskon_sicepat/100) * $price;
      if (abs($discount) >= $price){
         $discount = -1 * $price;
      }
   }    
   if($discount < 0) 
    $woocommerce -> cart -> add_fee(__('Shipping Discount', 'epeken-all-kurir'), $discount , false, ''); 
}
?>
