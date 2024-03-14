<?php 
/* This code is included from set_shipping_cost method of WC_Tikijne_Shipping class */
if (! defined ('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_jmx');
function epeken_invoke_jmx($shipping) {
$en_jmx_cos = get_option('epeken_enabled_jmx_cos'); $en_jmx_sms = get_option('epeken_enabled_jmx_sms');
			 $en_jmx_lts = get_option('epeken_enabled_jmx_lts'); $en_jmx_sos = get_option('epeken_enabled_jmx_sos');
			 if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
				$is_wcpv = false;
				$is_wcpv = epeken_is_wcpv_active();
				$en_jmx_cos_v = get_user_meta($shipping->vendor_id, 'vendor_jmx_cos', true);
				if ($is_wcpv)
			    	  $en_jmx_cos_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_jmx_cos'];

				if ($en_jmx_cos_v !== 'on' || $en_jmx_cos !== 'on')
					$en_jmx_cos = '';
			
				$en_jmx_sms_v = get_user_meta($shipping->vendor_id, 'vendor_jmx_sms', true);
				if ($is_wcpv)
			    	  $en_jmx_sms_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_jmx_sms'];

				if ($en_jmx_sms_v !== 'on' || $en_jmx_sms !== 'on')
					$en_jmx_sms = '';
				
				$en_jmx_lts_v = get_user_meta($shipping->vendor_id, 'vendor_jmx_lts', true);
				if ($is_wcpv)
			    	  $en_jmx_lts_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_jmx_lts'];

				if ($en_jmx_lts_v !== 'on' || $en_jmx_lts !== 'on')
				  $en_jmx_lts = '';
				
				$en_jmx_sos_v = get_user_meta($shipping->vendor_id, 'vendor_jmx_sos', true);
				if ($is_wcpv)
			    	  $en_jmx_sos_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_jmx_sos'];

				if ($en_jmx_sos_v !== 'on' || $en_jmx_sos !== 'on')
				  $en_jmx_sos = '';
			}
			if($en_jmx_cos === "on" || $en_jmx_sms === "on" || $en_jmx_lts === "on" || $en_jmx_sos === "on") { 
				$content_jmx_tariff = epeken_get_jmx_tariff($shipping->shipping_city, $shipping->shipping_kecamatan,
									$shipping -> bulatkan_berat($shipping -> shipping_total_weight), $shipping->origin_city);
				$arr = json_decode($content_jmx_tariff, true);
				$arr = $arr['results'];
				if(!empty($arr)) {
				foreach($arr as $ar) {
					$service = $ar['service'];
					$service = strtolower($service);
					$cost = $ar['biayaKirim'];
					$eta = $ar['estimasiHari'];
					if($en_jmx_cos === "on" && $service === "cos" && $cost > 0) {
						 array_push($shipping -> array_of_tarif, array('id' => "jmx_".$service,'label' => "JMX COS (ETA ".$eta." hari)", 'cost' => $cost));
					}
					if($en_jmx_sms === "on" && $service === "sms" && $cost > 0) {
						 array_push($shipping -> array_of_tarif, array('id' => "jmx_".$service,'label' => "JMX SMS (ETA ".$eta." hari)", 'cost' => $cost));
					}
					if($en_jmx_lts === "on" && $service === "lts" && $cost > 0) {
						 array_push($shipping -> array_of_tarif, array('id' => "jmx_".$service,'label' => "JMX LTS (ETA ".$eta." hari)", 'cost' => $cost));
					}
					if($en_jmx_sms === "on" && $service === "sos" && $cost > 0) {
						 array_push($shipping -> array_of_tarif, array('id' => "jmx_".$service,'label' => "JMX SOS (ETA ".$eta." hari)", 'cost' => $cost));
					}
				}}
				
			}
}
?>
