<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_atlas');
function epeken_invoke_atlas($shipping) {
   $en_atlas = sanitize_text_field(get_option('epeken_enabled_atlas_express'));
              if($en_atlas === 'on') {
                 $content = epeken_get_atlas_ongkir($shipping -> shipping_city, 
			$shipping -> bulatkan_berat($shipping -> shipping_total_weight), 
			$shipping -> origin_city);
		 $atlas = json_decode($content,true);
		 if(empty($atlas))
		    return;
		 if(!is_array($atlas['response_data']))
		    return;
                 if(array_key_exists('error', $atlas['response_data']))
                    return;
                 $scheme = $atlas['response_data']['tariff_data'];
                    if(sizeof($scheme) < 1)
                     return;
                 foreach($scheme as $tarif){
                    $service = strtolower($tarif['service']); $package = strtolower($tarif['package']);
                    $tarif_total = $tarif['tariff_total'];
                    array_push($shipping -> array_of_tarif, array('id' => 'atlas_'.$package.'_'.$service, 
                        'label' => 'Atlas '.$package.' '.$service, 'cost' => $tarif_total)); 
                 }
             }
}
?>
