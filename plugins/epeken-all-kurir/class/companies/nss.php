<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_nss');
function epeken_invoke_nss ($shipping) {
	$en_nss_sds = get_option('epeken_enabled_nss_sds');
	$en_nss_ods = get_option('epeken_enabled_nss_ods');
	$en_nss_reg = get_option('epeken_enabled_nss_reg');
			
			if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
				$en_nss_sds = '';
				$en_nss_ods = '';
				$en_nss_reg = '';
			}
			
			if($en_nss_sds === "on" || $en_nss_ods === "on" || $en_nss_reg === "on") { 
					$content_nss_tariff = epeken_get_nss_tariff($shipping->shipping_city, $shipping->shipping_kecamatan,
									$shipping -> bulatkan_berat($shipping -> shipping_total_weight), $shipping->origin_city);

					if(empty($content_nss_tariff))
					   return;

					$content_nss_decoded = json_decode($content_nss_tariff, true);
					$content_nss_decoded = $content_nss_decoded['results'][0]['costs'];
				for($i=0;$i<sizeof($content_nss_decoded);$i++){
					$element = $content_nss_decoded[$i];
				 if($en_nss_sds === 'on' && $element['service'] === 'SDS') {	
					$package_name = 'NSS SDS';
					$cost_value = $element['cost'][0]['value'];
					if (!empty($cost_value))
					array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
				 } 
				 if($en_nss_ods === "on" && $element['service'] === 'ODS') {	
					$package_name = "NSS ODS";
					$cost_value = $element['cost'][0]['value'];
					if (!empty($cost_value))
					array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
				 }
				 if($en_nss_reg === "on" && $element['service'] === 'REG') {	
					$package_name = "NSS REG";
					$cost_value = $element['cost'][0]['value'];
					if (!empty($cost_value))
					array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
				 }
				}	
			}


}
?>
