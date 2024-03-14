<?php 
if (!defined ('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_jnt');
function epeken_invoke_jnt($shipping) {
$en_jetez = get_option('epeken_enabled_jetez');
			 if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
				$is_wcpv = false;
				$is_wcpv = epeken_is_wcpv_active();
				
				 $en_jetez_v = get_user_meta($shipping->vendor_id, 'vendor_jnt_ez', true);
				 if ($is_wcpv)
			    	   $en_jetez_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_jnt_ez'];

 				 if($en_jetez_v !== 'on' || $en_jetez !== 'on')
				   $en_jetez = '';
			 }
                         if($en_jetez === "on") {
                                $content_jet = epeken_get_jet_ongkir($shipping -> shipping_city, $shipping -> shipping_kecamatan, $shipping->bulatkan_berat($shipping->shipping_total_weight), $shipping -> origin_city);
                                $content_jet_decoded = json_decode($content_jet);
                                if(!empty($content_jet_decoded)) {
                                        $content_jet_decoded = $content_jet_decoded -> {'tarifjnt'};
					if(!empty($content_jet_decoded)) {
					$is_eta = get_option('epeken_setting_eta');
                                       foreach($content_jet_decoded as $element) {
					       $package_name = $element -> {'class'}; 
					       $cost_value = $element -> {'cost'};
					       $etd = $element -> {'etd'};	
					       $markup = $shipping -> additional_mark_up('jnt',$shipping -> shipping_total_weight);
					       if ($cost_value > 0)
					         $cost_value = $cost_value + $markup;
					       $label = 'J&T '.$package_name;
					       if($is_eta === 'on' && !empty($etd))
						       $label .= '('.$etd.' hari)';
					       if ($cost_value !== "0") 
						       array_push($shipping -> array_of_tarif, 
						       array('id' => 'jet.co.id_'.$package_name,
						             'label' => $label, 
							     'cost' => $cost_value));
                                        }    }
                                }    
     
                         }
  add_action('woocommerce_cart_calculate_fees', 'epeken_calculate_discount_jnt');
}
function epeken_calculate_discount_jnt() {
   global $woocommerce;
   $shipping = WC_Shipping::instance();
   $methods = $shipping -> get_shipping_methods();
   $epeken = $methods['epeken_courier'];

   if($epeken -> is_subsidi_applied)
     return;

   $chosen = WC()->session->get('chosen_shipping_methods');
   $value_diskon_jnt = get_option('epeken_diskon_tarif_jnt'); #percentage discount JNT
   $id_kurir = $chosen[0];
   $price = 0; 
   $array_of_tarif = $epeken -> array_of_tarif;
   foreach($array_of_tarif as $t) {
         if($t['id'] === $id_kurir)
           {$price = $t['cost']; break;} 
   }    
   $discount = 0; 
   if($value_diskon_jnt > 0 && in_array($id_kurir, array('jet.co.id_EZ'))) {
      $discount = -1 * ($value_diskon_jnt/100) * $price;
      if (abs($discount) >= $price){
         $discount = -1 * $price;
      }
   }    
   if($discount < 0) 
    $woocommerce -> cart -> add_fee(__('Shipping Discount', 'epeken-all-kurir'), $discount , false, ''); 
}
?>
