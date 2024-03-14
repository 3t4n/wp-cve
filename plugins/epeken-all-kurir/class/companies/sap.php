<?php 
if (! defined('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_sap');
function epeken_invoke_sap($shipping) {
	$en_sap_sds = get_option('epeken_enabled_sap_sds');
		$en_sap_ods = get_option('epeken_enabled_sap_ods');
		$en_sap_reg = get_option('epeken_enabled_sap_reg');

		if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
			$is_wcpv = false;
			$is_wcpv = epeken_is_wcpv_active();

			$en_sap_sds_v = get_user_meta($shipping -> vendor_id,  'vendor_sap_sds', true);
			if ($is_wcpv)
			    $en_sap_sds_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_sap_sds'];

			if ($en_sap_sds_v !== 'on' || $en_sap_sds !== 'on')
				$en_sap_sds = '';

			$en_sap_ods_v = get_user_meta($shipping -> vendor_id,  'vendor_sap_ods', true);
			if ($is_wcpv)
			    $en_sap_ods_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_sap_ods'];

			if ($en_sap_ods_v !== 'on' || $en_sap_ods !== 'on')
				$en_sap_ods = '';

			$en_sap_reg_v = get_user_meta($shipping -> vendor_id,  'vendor_sap_reg', true);
			if ($is_wcpv)
			    $en_sap_reg_v = get_term_meta($shipping->vendor_id,'epeken_vendor_data')[0]['vendor_sap_reg'];

			if($en_sap_reg_v !== 'on' || $en_sap_reg !== 'on')
				$en_sap_reg = '';
		}
			
		if($en_sap_sds === "on" || $en_sap_ods === "on" || $en_sap_reg === "on") {
			$content_sap = epeken_get_sap_express_tariff($shipping->shipping_city, $shipping->shipping_kecamatan,
			$shipping->bulatkan_berat($shipping->shipping_total_weight), $shipping-> origin_city);
			$content_sap_decoded = json_decode($content_sap, true);
			if(!empty($content_sap_decoded)){
			foreach($content_sap_decoded as $element){
			 if($en_sap_sds === "on") {	
				$package_name = "SAP SDS";
				$cost_value = $element["SDS"];
				if (!empty($cost_value))
				array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
			 } 
			 if($en_sap_ods === "on") {	
				$package_name = "SAP ODS";
				$cost_value = $element["ODS"];
				if (!empty($cost_value))
				array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
			 }
			 if($en_sap_reg === "on") {	
				$package_name = "SAP REG";
				$cost_value = $element["REG"];
				if (!empty($cost_value))
				array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
			 }
			}}
		}
}
?>
