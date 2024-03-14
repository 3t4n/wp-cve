<?php

function directorypress_package_description($message, $placement = 'auto', $return = false) {
	$out = '<a class="directorypress-hint-icon" href="javascript:void(0);" data-content="' . esc_attr($message) . '" data-html="true" rel="popover" data-placement="' . $placement . '" data-trigger="hover"></a>';
	if ($return) {
		return wp_kses_post($out);
	} else {
		echo wp_kses_post($out);
	}
}

function directorypress_package_price($package) {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$price = apply_filters('directorypress_submitlisting_package_price', null, $package);
	if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pricing_plan_style'] == 'pplan-style-4'){
		$spliter = '';
		$for = __('For', 'DIRECTORYPRESS');
	}else{
		$spliter = '&#47;';
		$for = '';
	}
	if (!is_null($price)) {
		if (!$package->package_no_expiry) {
			if ($package->package_duration_unit == 'day' && $package->package_duration == 1)
				$price .= '<span class="directorypress-price-period">'.$spliter . __('Per day', 'DIRECTORYPRESS') . '</span>';
			elseif ($package->package_duration_unit == 'day' && $package->package_duration > 1)
				$price .= '<span class="directorypress-price-period">'.$spliter . $for.' '.  $package->package_duration . ' ' . _n('day', 'days', $package->package_duration, 'DIRECTORYPRESS') . '</span>';
			elseif ($package->package_duration_unit == 'week' && $package->package_duration == 1)
				$price .= '<span class="directorypress-price-period">'.$spliter . __('Per week', 'DIRECTORYPRESS');
			elseif ($package->package_duration_unit == 'week' && $package->package_duration > 1)
				$price .= '<span class="directorypress-price-period">'.$spliter . $for.' '. $package->package_duration . ' ' . _n('week', 'weeks', $package->package_duration, 'DIRECTORYPRESS') . '</span>';
			elseif ($package->package_duration_unit == 'month' && $package->package_duration == 1)
				$price .= '<span class="directorypress-price-period">'.$spliter . __('Per month', 'DIRECTORYPRESS');
			elseif ($package->package_duration_unit == 'month' && $package->package_duration > 1)
				$price .= '<span class="directorypress-price-period">'.$spliter . $for.' '. $package->package_duration . ' ' . _n('month', 'months', $package->package_duration, 'DIRECTORYPRESS') . '</span>';
			elseif ($package->package_duration_unit == 'year' && $package->package_duration == 1)
				$price .= '<span class="directorypress-price-period">'.$spliter . __('Per Year', 'DIRECTORYPRESS') . '</span>';
			elseif ($package->package_duration_unit == 'year' && $package->package_duration > 1)
				$price .= '<span class="directorypress-price-period">'.$spliter . $for.' '. $package->package_duration . ' ' . _n('year', 'years', $package->package_duration, 'DIRECTORYPRESS') . '</span>';
		}
		return '<span class="directorypress-price">' . $price . '</span>';
	}
}

function directorypress_package_purchase_limit($packages){

    $user_id = get_current_user_id();
    $current_user= wp_get_current_user();
    $customer_email = $current_user->email;
	if(!is_user_logged_in())
		return $packages;
		
	$new_packages = array();
    foreach($packages as $package):
		$package_count = get_user_meta($user_id, '_renew_count_package_'.$package->id, true);
		if(($package->number_of_package_renew_allowed !='' && $package_count >= $package->number_of_package_renew_allowed) && (!get_user_meta($user_id, '_listings_unlimited_'.$package->id, true) && get_user_meta($user_id, '_listings_number_'.$package->id, true) == 0)){
			$new_packages[] = $package->id;
		}
    endforeach; 

    return $new_packages;
}