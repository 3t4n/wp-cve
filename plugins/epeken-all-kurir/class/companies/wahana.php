<?php
if (!defined('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_wahana');
function epeken_invoke_wahana($shipping) {
$en_wahana = get_option('epeken_enabled_wahana');
			if(epeken_is_multi_vendor_mode()  && epeken_is_vendor_id($shipping -> vendor_id)) {
				$is_wcpv = false;
				$is_wcpv = epeken_is_wcpv_active();
				
				$en_wahana_v = get_user_meta($shipping->vendor_id, 'vendor_wahana', true);
				if ($is_wcpv)
			    		$en_wahana_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_wahana'];

				if($en_wahana_v !== 'on' || $en_wahana !== 'on')
					$en_wahana = '';
			}
			if ($en_wahana === "on") {
			 $content_wahana = epeken_get_wahana_ongkir($shipping->shipping_city,$shipping-> shipping_kecamatan,$shipping->bulatkan_berat($shipping->shipping_total_weight), $shipping->origin_city);	

			 $content_wahana_decoded = json_decode($content_wahana);
			 if (!empty($content_wahana_decoded)) {
			 $content_wahana_decoded = $content_wahana_decoded -> {'tarifwahana'};
				if(!empty($content_wahana_decoded)) {
				foreach($content_wahana_decoded as $element) {
				 $package_name = $element -> {'class'};
				 $markup = $shipping -> additional_mark_up('wahana',$shipping -> shipping_total_weight);
				 $cost_value = $element -> {'cost'};
				 if ($markup > 0)
 				  $cost_value = $cost_value + $markup;
				 if ($cost_value !== "0")
				 array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
				}}
			 }
			}
			
			if (get_option('epeken_is_asuransi_wahana') === 'on')
		          add_action('woocommerce_cart_calculate_fees','epeken_asuransi_wahana');
			
 			add_action('woocommerce_cart_calculate_fees','epeken_calculate_discount_wahana');
}
function epeken_asuransi_wahana() {
	global $woocommerce;
	$sub_total = WC() -> cart -> subtotal;
	if ($sub_total < 200000)
          return;
  	$chosen = WC()->session->get('chosen_shipping_methods');
    	$id_kurir = $chosen[0]; //WAHANA
	if ($id_kurir === 'WAHANA') {
	  $biaya_asuransi = (0.5/100) * $sub_total;
          $woocommerce->cart->add_fee('Asuransi Wahana',$biaya_asuransi,false, '');
	}
}
function epeken_calculate_discount_wahana() {
   global $woocommerce;
   $shipping = WC_Shipping::instance();
   $methods = $shipping -> get_shipping_methods();
   $epeken = $methods['epeken_courier'];

   if($epeken -> is_subsidi_applied)
    return;

   $chosen = WC()->session->get('chosen_shipping_methods');
   $value_diskon_wahana = get_option('epeken_diskon_tarif_wahana'); #percentage discount POS
   $id_kurir = $chosen[0];
   $price = 0; 
   $array_of_tarif = $epeken -> array_of_tarif;
   foreach($array_of_tarif as $t) {
         if($t['id'] === $id_kurir)
           {$price = $t['cost']; break;} 
   }    
   $discount = 0; 
   if($value_diskon_wahana > 0 && in_array($id_kurir, array('WAHANA'))) {
      $discount = -1 * ($value_diskon_wahana/100) * $price;
      if (abs($discount) >= $price){
         $discount = -1 * $price;
      }
   }    
   if($discount < 0) 
    $woocommerce -> cart -> add_fee(__('Shipping Discount', 'epeken-all-kurir'), $discount , false, ''); 
}
?>
