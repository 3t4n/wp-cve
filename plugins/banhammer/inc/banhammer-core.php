<?php // Plugin Core

if (!defined('ABSPATH')) exit;

function banhammer_init() {
	
	global $BanhammerWP;
	
	$options = get_option('banhammer_settings', $BanhammerWP->options());
	
	if (banhammer_abort($options)) return;
	
	extract(banhammer_get_vars()); // $status, $date, $user, $protocol, $method, $domain, $request, $ua, $refer, $proxy, $ip
	
	$check = banhammer_check($user, $ip);
	
	$banhammer = false;
	
	$status = 0;
	
	if (is_array($check)) {
		
		extract($check); // $tower, $status
		
		update_option('banhammer_tower', $tower);
		
		if ($status == 3 || $status == 4) $banhammer = true;
		
	}
	
	banhammer_insert($status, $date, $user, $protocol, $method, $domain, $request, $ua, $refer, $proxy, $ip);
	
	if ($banhammer) {
		
		banhammer_disable_cache();
		
		banhammer($options);
		
	}
	
	return false;
	
}

function banhammer_check($user, $ip) {
	
	global $BanhammerWP;
	
	$tower = get_option('banhammer_tower', $BanhammerWP->tower());
	
	foreach ($tower as $key => $value) {
		
		$hits   = isset($value['hits'])   ? $value['hits']   : 1;
		$target = isset($value['target']) ? $value['target'] : '';
		$status = isset($value['status']) ? $value['status'] : 0;
		
		if (empty($status) || empty($target)) continue;
		
		if ($status == 1 || $status == 3 || $status == 5) {
			
			if (stripos($ip, $target) === false) continue;
			
		}
		
		if ($status == 2 || $status == 4 || $status == 6) {
			
			if (stripos($user, $target) === false) continue;
			
		}
		
		$tower[$key]['hits'] = (int) $hits + 1;
		
		return array('tower' => $tower, 'status' => $status);
		
	}
	
	return false;
	
}

function banhammer_abort($options) {
	
	if (wp_doing_ajax()) return true;
	
	if (defined('BANHAMMER') && !BANHAMMER) return true;
	
	if (defined('DOING_CRON') && DOING_CRON) return true;
	
	if (!empty($_GET)) {
		
		foreach ($_GET as $k => $v) {
			
			if (strpos($k, 'banhammer-process') !== false) {
				
				$key = banhammer_get_secret_key(30, 'abort check');
				
				$get_key = explode('banhammer-process_', $k);
				
				$get_key = end($get_key);
				
				if ($get_key === $key) {
					
					return true;
					
				}
				
			}
			
		}
		
	}
	
	if (isset($options['enable_plugin']) && !$options['enable_plugin']) return true;
	
	if (isset($options['ignore_logged']) && $options['ignore_logged'] && is_user_logged_in()) return true;
	
	if (isset($options['protect_login']) && !$options['protect_login'] && banhammer_is_login()) return true;
	
	if (isset($options['protect_admin']) && !$options['protect_admin'] && is_admin()) return true;
	
	return false;
	
}

function banhammer($options) {
	
	$response = isset($options['banned_response']) ? $options['banned_response'] : '';
	$custom   = isset($options['custom_message'])  ? $options['custom_message']  : '';
	$redirect = isset($options['redirect_url'])    ? $options['redirect_url']    : '';
	$status   = isset($options['status_code'])     ? $options['status_code']     : '';
	
	$ban_status     = apply_filters('banhammer_ban_status',     $status);
	$ban_protocol   = apply_filters('banhammer_ban_protocol',   'HTTP/1.1');
	$ban_connection = apply_filters('banhammer_ban_connection', 'Connection: Close');
	
	header($ban_protocol .' '. $ban_status);
	header($ban_connection);
	
	if ($response === 'redirect' && !empty($redirect)) {
		
		wp_redirect(esc_url_raw($redirect));
		
		$message = null;
		
	} elseif ($response === 'custom' && !empty($custom)) {
		
		$message = $custom;
		
	} else {
		
		$message = banhammer_banned_default();
		
	}
	
	$message = apply_filters('banhammer_ban_message', $message);
	
	exit($message);
	
}

function banhammer_disable_cache() {
	
	if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
	
	if (
		isset($GLOBALS['wp_fastest_cache']) && 
		is_object($GLOBALS['wp_fastest_cache']) && 
		method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache') && 
		is_callable(array($GLOBALS['wp_fastest_cache'], 'deleteCache'))
	) {
		
		$GLOBALS['wp_fastest_cache']->deleteCache();
		
	}
	
	return DONOTCACHEPAGE;
	
}

function banhammer_clear_cache() {
	
	if (function_exists('w3tc_pgcache_flush')) w3tc_pgcache_flush();
	
	if (function_exists('wp_cache_clear_cache')) wp_cache_clear_cache();
	
	if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) $GLOBALS['wp_fastest_cache']->deleteCache();
		
}

function banhammer_banned_default() {
	
	$output = '<html><head><style>body{height:100vh;display:flex;align-items:center;justify-content:center;margin:0;padding:0;color:#bfa06b;background:#2a2a2a;}';
	
	$output .= '.bh{font-family:serif;text-align:center;}img{display:inline-block;width:300px;height:300px;border:0;outline:0;}</style></head>';
	
	$output .= '<body><div class="bh"><h1>'. esc_html__('You are banned.', 'banhammer') .'</h1>';
	
	$output .= '<img src="'. BANHAMMER_URL .'img/banhammer-crest.jpg" width="300" height="300" alt=""></div></body></html>';
	
	return $output;
	
}

function banhammer_is_login() {
	
	$is_login = (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') ? true : false;
	
	return $is_login;
	
}

function banhammer_insert($status, $date, $user, $protocol, $method, $domain, $request, $ua, $refer, $proxy, $ip) {
	
	global $wpdb;
	
	$table = $wpdb->prefix .'banhammer';
	
	$insert = $wpdb->insert($table, array(
		
		'status'   => $status,
		'date'     => $date,
		'user'     => $user, 
		'protocol' => $protocol,
		'method'   => $method,
		'domain'   => $domain,
		'request'  => $request,
		'ua'       => $ua,
		'refer'    => $refer,
		'proxy'    => $proxy,
		'ip'       => $ip
		
	), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
	
	return $insert;
	
}

function banhammer_get_vars() {
	
	$vars = array(
				// status
				'date'     => banhammer_get_date(),
				'user'     => banhammer_get_user(),
				'protocol' => banhammer_get_protocol(),
				'method'   => banhammer_get_method(),
				'domain'   => banhammer_get_domain(),
				'request'  => banhammer_get_request(),
				'ua'       => banhammer_get_ua(),
				'refer'    => banhammer_get_refer(),
				'proxy'    => banhammer_get_proxy(),
				'ip'       => banhammer_get_ip()
			);
	
	return apply_filters('banhammer_vars', $vars);
	
}
