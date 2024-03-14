<?php
if (! defined('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_dakota');
function epeken_invoke_dakota ($shipping) {
$en_dakota_tarif = get_option('epeken_enabled_dakota_tarif');
                        if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)){
				$is_wcpv = false;
			 	$is_wcpv = epeken_is_wcpv_active();

				$en_dakota_tarif_v = get_user_meta($shipping -> vendor_id, 'vendor_dakota', true);
				if ($is_wcpv)
			    		$en_dakota_tarif_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_dakota'];

				if ($en_dakota_tarif_v !== 'on' || $en_dakota_tarif !== 'on')
					$en_dakota_tarif = '';
                        }
                        if($en_dakota_tarif === 'on') {
                                $content_dakota_tarif = epeken_get_dakota_tarif($shipping->shipping_city, $shipping -> shipping_kecamatan, $shipping -> bulatkan_berat($shipping -> shipping_total_weight), $shipping->origin_city );
                                $content_dakota_decoded = json_decode($content_dakota_tarif);
                                if(!empty($content_dakota_decoded)){
                                        $content_dakota_decoded = $content_dakota_decoded -> {'tarifcustom'};
                                 for($i=0; $i <= sizeof($content_dakota_decoded); $i++) {
                                        $package_name = $content_dakota_decoded[$i]->{'class'};
                                        $cost_value = $content_dakota_decoded[$i]->{'cost'};
                                        if ($cost_value !== "0")
                                        array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
                                 }
                                }
                        }
}
?>
