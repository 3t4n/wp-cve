<?php

include_once(IPBL_LIB_DIR.'/IPv6/IPv6.php');

function ipbl_getip(){
	
	$ip_pref = get_option('ipbl_ip_pref');
	
	$ip = $_SERVER["REMOTE_ADDR"];
	
	if(!empty($ip_pref)){
		
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $ip_pref == 'HTTP_X_FORWARDED_FOR'){
			if(strpos($_SERVER["HTTP_X_FORWARDED_FOR"], ',')){
				$temp_ip = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);
				$ip = trim($temp_ip[0]);
			}else{
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}
		}
		
		if(isset($_SERVER["HTTP_CLIENT_IP"]) && $ip_pref == 'HTTP_CLIENT_IP'){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		
		if(isset($_SERVER["HTTP_X_ORIGINAL_FORWARDED_FOR"]) && $ip_pref == 'HTTP_X_ORIGINAL_FORWARDED_FOR'){
			$ip = $_SERVER["HTTP_X_ORIGINAL_FORWARDED_FOR"];
		}
		
		if(isset($_SERVER["HTTP_CF_CONNECTING_IP"]) && $ip_pref == 'HTTP_CF_CONNECTING_IP'){
			$ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		
		// For custom proxy
		if(isset($_SERVER['PROXY_REMOTE_ADDR']) && $ip_pref == 'PROXY_REMOTE_ADDR' && ipbl_is_supported_feature()){
			$ip = $_SERVER['PROXY_REMOTE_ADDR'];
		}
		
	}else{
		
		// Normal Remote IP
		if(isset($_SERVER["REMOTE_ADDR"])){
			return $_SERVER["REMOTE_ADDR"];
		
		// Forwarded IP Proxy
		}elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			if(strpos($_SERVER["HTTP_X_FORWARDED_FOR"], ',')){
				$temp_ip = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);
				$ip = trim($temp_ip[0]);
			}else{
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}
			return $ip;
		
		// HTTP Client IP
		}elseif(isset($_SERVER["HTTP_CLIENT_IP"])){
			return $_SERVER["HTTP_CLIENT_IP"];
		}
	}
	
	// Hacking fix for X-Forwarded-For
	if(!ipbl_valid_ip($ip)){
		return '';
	}
	
	return $ip;
}

function ipbl_supported_ip($ip){
	
	if(ipbl_valid_ipv6($ip)){
		$ret = apply_filters('ipbl_has_ipv6_support', false);
		
		if($ret == false){
			return '';
		}else{
			return $ip;
		}
	}
	
	return $ip;
}

function ipbl_is_supported_ip($ip){
	
	global $error;
	
	if(!ipbl_supported_ip($ip)){
		$error['no_ipv6_support'] = sprintf( __('IPv6 is not supported in Free version. %1$sUpgrade to Pro%2$s', 'ip-based-login'), '<a href="'.IPBL_PRICING_URL.'" target="_blank">', '</a>');
		return '';
	}
	
	return $ip;
}

function ipbl_is_supported_feature($feature = '', $set_error = ''){
	
	global $error;
	
	$is_supported = apply_filters('ipbl_pro_features_support', false);
	
	if($is_supported == false && !empty($set_error)){
		$error[] = sprintf( __('%1$s feature is not supported in Free version. %2$sUpgrade to Pro%3$s', 'ip-based-login'), $feature, '<a href="'.IPBL_PRICING_URL.'" target="_blank">', '</a>');
	}
	
	return $is_supported;
}

// Execute a select query and return an array
function ipbl_selectquery($query, $array = 0){
	global $wpdb;
	
	$result = $wpdb->get_results($query, 'ARRAY_A');
	
	if(empty($array)){
		return current($result);
	}else{
		return $result;
	}
}

// Check if IP Based Login is pro
function ipbl_is_premium(){
	//return false;
	return defined('IPBL_PREMIUM');
}

function ipbl_sanitize_variables($variables = array()){
	
	if(is_array($variables)){
		foreach($variables as $k => $v){
			$variables[$k] = trim($v);
			$variables[$k] = esc_sql($v);
		}
	}else{
		$variables = esc_sql(trim($variables));
	}
	
	return $variables;
}

function ipbl_valid_ipv4($ip){
	return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}

function ipbl_valid_ipv6($ip){
	return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
}

function ipbl_valid_ip($ip){
	return filter_var($ip, FILTER_VALIDATE_IP);
}

function ipbl_is_checked($post){

	if(!empty($_POST[$post])){
		return true;
	}	
	return false;
}

function ipbl_report_error($error = array()){

	if(empty($error)){
		return true;
	}
	
	$error_string = '<b>'.__('Please fix the below errors', 'ip-based-login').' :</b> <br />';
	
	foreach($error as $ek => $ev){
		$error_string .= '* '.$ev.'<br />';
	}
	
	echo '<div id="message" class="error"><p>'
			. $error_string
			. '</p></div>';
}

function ipbl_objectToArray($d){
  if(is_object($d)){
    $d = get_object_vars($d);
  }
  
  if(is_array($d)){
    return array_map(__FUNCTION__, $d); // recursive
  }elseif(is_object($d)){
    return ipbl_objectToArray($d);
  }else{
    return $d;
  }
}

function ipbl_ipv6_support(){
	
	if(!ipbl_is_premium()){
		echo '<a href="'.IPBL_PRICING_URL.'" target="_blank" style="text-decoration:none;">
			<span style="color:red;">'.__('IPv4 supported. Upgrade to Pro for IPv6 support','ip-based-login').'</span>
		</a>';
	}else{
		echo '<span style="color:green;">'.__('IPv4 and IPv6 supported !','ip-based-login').'</span>';
	}
	
}