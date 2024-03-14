<?php // Plugin Functions

if (!defined('ABSPATH')) exit;

function banhammer_get_date() {
	
	$format = 'Y-m-d \@\&\n\b\s\p\;H:i:s';
	
	if (function_exists('current_datetime')) {
		
		$date = current_datetime()->format($format);
		
	} else {
		
		$date = date($format, current_time('timestamp'));
		
	}
	
	return apply_filters('banhammer_date', $date);
	
}

function banhammer_get_user() {
	
	$user = wp_get_current_user();
	
	$username = !empty($user->ID) ? $user->user_login : '';
	
	return apply_filters('banhammer_user', $username);
	
}

function banhammer_get_protocol() {
	
	$protocol  = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
	
	return apply_filters('banhammer_protocol', $protocol);
	
}

function banhammer_get_method() {
	
	$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
	
	return apply_filters('banhammer_method', $method);
		
}

function banhammer_get_domain() {
	
	$protocol = is_ssl() ? 'https://' : 'http://';
	
	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	
	$domain = $protocol . $host;
	
	return apply_filters('banhammer_domain', $domain, $protocol, $host);
	
}

function banhammer_get_request() {
	
	$request = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	
	return apply_filters('banhammer_request', $request);
	
}

function banhammer_get_ua() {
	
	$ua = (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : BANHAMMER_BLANK;
	
	return apply_filters('banhammer_ua', $ua);
	
}

function banhammer_get_refer() {
	
	$refer = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : BANHAMMER_BLANK;
	
	return apply_filters('banhammer_refer', $refer);
		
}

function banhammer_get_proxy() {
	
	$proxy = banhammer_evaluate_ip(true);
	
	return apply_filters('banhammer_proxy', $proxy);
	
}

function banhammer_get_ip() {
	
	$ip = banhammer_evaluate_ip(false);
	
	if (preg_match('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/', $ip, $ip_match)) {
		
		$ip = $ip_match[1];
		
	}
	
	return apply_filters('banhammer_ip', $ip);
	
}

function banhammer_evaluate_ip($proxy = true) {
	
	if ($proxy) {
		
		$ip_keys = array('HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_X_REAL_IP', 'HTTP_X_COMING_FROM', 'HTTP_PROXY_CONNECTION', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_COMING_FROM', 'HTTP_VIA', 'REMOTE_ADDR');
		
	} else {
		
		$ip_keys = array('REMOTE_ADDR', 'HTTP_VIA', 'HTTP_COMING_FROM', 'HTTP_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_PROXY_CONNECTION', 'HTTP_X_COMING_FROM', 'HTTP_X_REAL_IP', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_X_FORWARDED', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_CF_CONNECTING_IP');
		
	}
	
	foreach ($ip_keys as $key) {
		
		if (array_key_exists($key, $_SERVER) === true) {
			
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				
				$ip = trim($ip);
				
				$ip = banhammer_normalize_ip($ip);
				
				if (banhammer_validate_ip($ip)) {
					
					return $ip;
					
				}
				
			}
			
		}
		
	}
	
	return esc_html__('Invalid IP Address', 'banhammer');
	
}

function banhammer_normalize_ip($ip) {
	
	if (strpos($ip, ':') !== false && substr_count($ip, '.') == 3 && strpos($ip, '[') === false) {
		
		// IPv4 with port (e.g., 123.123.123:80)
		$ip = explode(':', $ip);
		$ip = $ip[0];
		
	} else {
		
		// IPv6 with port (e.g., [::1]:80)
		$ip = explode(']', $ip);
		$ip = ltrim($ip[0], '[');
		
	}
	
	return $ip;
	
}
	
function banhammer_validate_ip($ip) {
	
	$options  = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
	
	$options  = apply_filters('banhammer_ip_filter', $options);
	
	$filtered = filter_var($ip, FILTER_VALIDATE_IP, $options);
	
	if (!$filtered || empty($filtered)) {
		
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			
			return $ip; // IPv4
			
		} elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) { 
			
			return $ip; // IPv6
			
		}
		
		if ($ip) error_log('Invalid IP Address: '. $ip);
		
		return false;
		
	}
	
	return $filtered;
	
}

function banhammer_get_geo($ip) {
	
	$id = '12lQj0zvx17df4St9u1x';
	
	$lookup = 'https://zen-wp.com/api/?id='. $id .'&ip='. $ip .'&to=bh';
	
	$lookup = apply_filters('banhammer_geo_url', $lookup, $ip, $id);
	
	$error_message = '';
	
	$error = false;
	
	if (function_exists('curl_version')) {
		
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $lookup);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, apply_filters('banhammer_curl_connecttimeout', 2)); // default = 300 seconds
		curl_setopt($curl, CURLOPT_TIMEOUT,        apply_filters('banhammer_curl_timeout', 3));        // default = 0 (no timeout)
		
		// curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 3000);
		// curl_setopt($curl, CURLOPT_TIMEOUT_MS, 10000);
		
		// curl_easy_setopt($curl, CURLOPT_LOW_SPEED_TIME, 60L);
		// curl_easy_setopt($curl, CURLOPT_LOW_SPEED_LIMIT, 30L);
		
		curl_setopt($curl, CURLOPT_NOSIGNAL, 1);
		
		$json = curl_exec($curl);
		
		if (curl_errno($curl)) {
			
			$error = true;
			
			$error_message = __('WP Plugin Banhammer: cURL failed with error:', 'banhammer') .' '. curl_error($curl);
			
		}
		
		curl_close($curl);
		
	} elseif (file_get_contents(__FILE__) && ini_get('allow_url_fopen')) {
		
		$timeout = apply_filters('banhammer_file_get_contents_timeout', 3);
		
		$ctx = stream_context_create(array('http' => array('timeout' => $timeout))); // default = 60 seconds
		
		try {
			
			$json = file_get_contents($lookup, 0, $ctx);
			
			if ($json === false) $error = true;
			
		} catch (Exception $e) {
			
			$error = true;
			
		}
		
		if ($error) $error_message = __('WP Plugin Banhammer: file_get_contents() failed.', 'banhammer');
		
	} else {
		
		$error = true;
		
		$error_message = __('WP Plugin Banhammer: file_get_contents() disabled. cURL disabled. Geo Lookup not possible.', 'banhammer');
		
	}
	
	if ($error) {
		
		$geo = array();
		
		if ($error_message) error_log($error_message);
		
	} else {
		
		$geo = json_decode($json, true);
		
	}
	
	$code    = isset($geo['country_code']) ? $geo['country_code'] : '';
	$country = isset($geo['country_name']) ? $geo['country_name'] : '';
	$region  = isset($geo['region_name'])  ? $geo['region_name']  : '';
	$city    = isset($geo['city_name'])    ? $geo['city_name']    : '';
	$zip     = isset($geo['zip_code'])     ? $geo['zip_code']     : '';
	
	$results = array('code' => $code, 'country' => $country, 'region' => $region, 'city' => $city, 'zip' => $zip);
	
	return apply_filters('banhammer_geo_data', $results, $geo);
	
}

function banhammer_get_response($url, $method) {
	
	$key = banhammer_get_secret_key(30, 'response code');
	
	$url = preg_replace('/banhammer-process(_.*)?/i', '', $url);
	
	$url = add_query_arg('banhammer-process_'. $key, '', $url);
	
	$timeout = apply_filters('banhammer_get_response_timeout', 0.7);
	
	$response = wp_safe_remote_get(esc_url_raw($url), array('method' => $method, 'timeout' => $timeout)); // default = 5 seconds
	
	$response = wp_remote_retrieve_response_code($response);
	
	return apply_filters('banhammer_response', $response);
	
}

function banhammer_get_secret_key($bytes, $context) {
	
	$key = get_option('banhammer_secret_key');
	
	if (!$key) {
		
		$key = banhammer_secret_key($bytes);
		
		$update = update_option('banhammer_secret_key', $key);
		
		$update = $update ? 'added' : 'failed';
		
		$context = $update .' via '. $context;
		
		$log = apply_filters('banhammer_get_secret_key_log', false);
		
		if ($log) error_log('Banhammer: Secret key '. print_r($context, true));
		
	}
	
	return apply_filters('banhammer_get_secret_key', $key);
	
}

function banhammer_secret_key($bytes) {
	
	$chars = '1234567890abcdefghijklmnopqrstuvwxyz';
	
	$key = base64_encode(substr(md5($chars), 0, $bytes));
	
	return apply_filters('banhammer_secret_key', $key);
	
}

function banhammer_process($wpdb, $table, $row) {
	
	$id      = (isset($row['id'])      && !empty($row['id']))      ? intval($row['id'])      : '';
	$process = (isset($row['process']) && !empty($row['process'])) ? intval($row['process']) : '';
	$request = (isset($row['request']) && !empty($row['request'])) ? $row['request']         : '';
	$domain  = (isset($row['domain'])  && !empty($row['domain']))  ? $row['domain']          : '';
	$method  = (isset($row['method'])  && !empty($row['method']))  ? $row['method']          : '';
	$ip      = (isset($row['ip'])      && !empty($row['ip']))      ? $row['ip']              : '';
	
	if (empty($id) || !empty($process)) return $row;
	
	$process = 1;
	
	$response = banhammer_get_response($domain . $request, $method);
	
	$geo = banhammer_get_geo($ip);
	
	extract($geo); // $code, $country, $region, $city, $zip
	
	if (empty($code) || empty($response)) $process = 0;
	
	$data = array(
				'process'  => $process,
				'response' => $response,
				'code'     => $code,
				'country'  => $country,
				'region'   => $region,
				'city'     => $city,
				'zip'      => $zip,
			);
	
	$where = array('id' => $id);
	
	$format_data = array('%d', '%d', '%s', '%s', '%s', '%s', '%s');
	
	$format_where = array('%d');
	
	$update = $wpdb->update($table, $data, $where, $format_data, $format_where);
	
	if ($update) {
		
		if (isset($row['process']))  $row['process']  = $process;
		if (isset($row['response'])) $row['response'] = $response;
		if (isset($row['code']))     $row['code']     = $code;
		if (isset($row['country']))  $row['country']  = $country;
		if (isset($row['region']))   $row['region']   = $region;
		if (isset($row['city']))     $row['city']     = $city;
		if (isset($row['zip']))      $row['zip']      = $zip;
		
	}
	
	return $row;
	
}

function banhammer_is_positive_integer($str) {
	
	return (is_numeric($str) && $str > 0 && $str == round($str));
	
}

function banhammer_get_random_alphanumeric($limit = 32) {
	
	return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	
}

function banhammer_add_target() {
	
	$key = isset($_GET['banhammer-key']) ? $_GET['banhammer-key'] : null;
	$ip  = isset($_GET['banhammer-ip'])  ? $_GET['banhammer-ip']  : null;
	
	if (!$key || !$ip) return;
	
	global $BanhammerWP, $wpdb;
	
	$table = $wpdb->prefix .'banhammer';
	
	$options = get_option('banhammer_settings', $BanhammerWP->options());
	$tower   = get_option('banhammer_tower',    $BanhammerWP->tower());
	
	$target_key = isset($options['target_key']) ? $options['target_key'] : null;
	
	if (($target_key === $key) && current_user_can('manage_options')) {
		
		$i = is_array($tower) ? count($tower) : null;
		
		$tower_key = banhammer_armory_tower_key('ip', $ip, $tower);
		
		if (!is_int($tower_key)) {
			
			$tower[$i]['hits']   = 1;
			$tower[$i]['target'] = $ip;
			$tower[$i]['status'] = 3; // ban ip
			$tower[$i]['date']   = banhammer_get_date();
			
			$updated = update_option('banhammer_tower', $tower);
			
			banhammer_clear_cache();
			
		} else {
			
			$updated = false;
		}
		
		$result = $updated ? 'true' : 'false';
		
		$location = admin_url('admin.php?page=banhammer-tower&banhammer-add-target='. $result);
		
		wp_redirect(esc_url_raw($location));
		
		exit;
		
	}
	
	return;
	
}
