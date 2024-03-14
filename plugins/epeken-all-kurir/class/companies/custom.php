<?php 
if (! defined('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_custom');
function epeken_invoke_custom ($shipping) {
			$en_custom_tarif = get_option('epeken_enabled_custom_tarif');
			if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
				$is_wcpv = false;
				$is_wcpv = epeken_is_wcpv_active();
			
				$en_custom_tarif_v = get_user_meta($shipping->vendor_id, 'vendor_custom', true);
				if ($wcpv)
				    $en_custom_tarif_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_custom'];

				if ($en_custom_tarif_v !== 'on' || $en_custom_tarif !== 'on')
					$en_custom_tarif = '';
                         }
			
			if($en_custom_tarif === 'on') {
				$content_custom_tarif = epeken_get_custom_tarif($shipping -> shipping_city, $shipping -> bulatkan_berat($shipping -> shipping_total_weight), $shipping->origin_city, $shipping->shipping_kecamatan) ;
			$content_custom_tarif_decoded = json_decode($content_custom_tarif);
			 if(!empty($content_custom_tarif_decoded)) {
				$content_custom_tarif_decoded = $content_custom_tarif_decoded -> {'tarifcustom'};
				if(!empty($content_custom_tarif_decoded)) {
				foreach($content_custom_tarif_decoded as $elem) {
					$package_name = $elem->{'class'};
					$cost_value = $elem->{'cost'};
					if ($cost_value !== "0") 
                         	        array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
				}}
			 }		
			}


}
?>
