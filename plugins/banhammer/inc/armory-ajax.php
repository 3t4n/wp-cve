<?php // Armory Ajax

if (!defined('ABSPATH')) exit;

function banhammer_armory() {
	
	check_ajax_referer('banhammer', 'nonce');
	
	if (!current_user_can('manage_options')) return;
	
	$vars = banhammer_armory_vars();
	
	extract($vars); // $wpdb, $table, $items, $type, $bulk, $sort, $order, $search, $filter, $status, $fx, $jump, $count, $limit, $offset, $toggle
	
	$limit = ($limit > 10) ? 10 : $limit;
	
	if ($type === 'add') {
		
		list ($query, $count) = banhammer_armory_addvisit($wpdb, $table, $offset, $limit, $sort, $order, $status);
		
	} elseif ($type === 'items') {
		
		list ($query, $count) = banhammer_armory_update($wpdb, $table, $offset, $limit, $sort, $order, $toggle, $status, $fx);
		
	} elseif ($type === 'bulk') {
		
		list ($query, $count) = banhammer_armory_bulk($wpdb, $table, $offset, $limit, $sort, $order, $bulk, $items, $search, $filter, $status);
		
	} elseif ($type === 'delete') {
		
		list ($query, $count) = banhammer_armory_truncate($wpdb, $table);
		
	} elseif ($type === 'search') {
		
		list ($query, $count) = banhammer_armory_search($wpdb, $table, $offset, $limit, $sort, $order, $search, $filter, $status);
		
	} else {
		
		list ($query, $count) = banhammer_armory_select($wpdb, $table, $offset, $limit, $sort, $order, $status);
		
	}
	
	banhammer_armory_results($wpdb, $table, $query, $count, $type, $offset, $limit);
	
	die(); //
	
}

function banhammer_aux() {
	
	check_ajax_referer('banhammer', 'nonce');
	
	if (!current_user_can('manage_options')) return;
	
	$id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : null;
	
	$ip = isset($_POST['ip']) ? sanitize_text_field($_POST['ip']) : null;
	
	$host = null;
	
	if (!empty($ip)) {
		
		$host = gethostbyaddr($ip);
		
		$host = apply_filters('banhammer_host', $host);
		
	}
	
	if (!empty($host) && !empty($id)) {
		
		global $wpdb;
		
		$table = $wpdb->prefix .'banhammer';
		
		$update = $wpdb->update($table, array('host' => $host), array('id' => $id), array('%s'), array('%d'));
		
		$saved = $update ? esc_attr__(' (saved)', 'banhammer') : '';
		
		$url = 'https://gwhois.org/'. $ip .'+dns';
		
		$link = '<a target="_blank" rel="noopener noreferrer" href="'. esc_url($url) .'">'. esc_html($host) .'</a>'. $saved;
		
	} else {
		
		$link = esc_html__('[ undefined ]', 'banhammer');
		
	}
	
	echo $link;
	
	die(); //
	
}

//

function banhammer_armory_results($wpdb, $table, $query, $count, $type, $offset, $limit) {
	
	$results = null;
	
	if (!empty($query)) {
		
		$results = $wpdb->get_results($query, ARRAY_A);
		
		if (!empty($results)) {
			
			foreach ($results as $key => $row) {
				
				banhammer_armory_loop($wpdb, $table, $row);
				
			}
			
		}
		
	}
	
	banhammer_armory_count($query, $count, $type, $offset, $limit, $results);
	
}

function banhammer_armory_addvisit($wpdb, $table, $offset, $limit, $sort, $order, $status) {
	
	$add = banhammer_armory_examples();
	
	return banhammer_armory_select($wpdb, $table, $offset, $limit, $sort, $order, $status);
	
}

function banhammer_armory_update($wpdb, $table, $offset, $limit, $sort, $order, $toggle, $status, $fx) {
	
	global $BanhammerWP;
	
	$default = $BanhammerWP->armory();
	
	$armory = get_option('banhammer_armory', $default);
	
	if (isset($armory['rows'])) $armory['rows'] = $limit;
	
	if (isset($armory['view'])) $armory['view'] = $toggle;
	
	if (isset($armory['fx'])) $armory['fx'] = $fx;
	
	$update = update_option('banhammer_armory', $armory);
	
	return banhammer_armory_select($wpdb, $table, $offset, $limit, $sort, $order, $status);
	
}

function banhammer_armory_bulk($wpdb, $table, $offset, $limit, $sort, $order, $bulk, $items, $search, $filter, $status) {
	
	if (!empty($bulk) && !empty($items) && is_array($items)) {
		
		foreach ($items as $id) {
			
			$id = intval($id);
			
			if (is_int($id) && (
							$bulk === 'ban-ip'     || $bulk === 'ban-user' || 
							$bulk === 'warn-ip'    || $bulk === 'warn-user' || 
							$bulk === 'restore-ip' || $bulk === 'restore-user'
				)) {
				
				global $BanhammerWP;
				
				$default = $BanhammerWP->tower();
				
				$tower = get_option('banhammer_tower', $default);
				
				$query = $wpdb->prepare("SELECT * FROM ". $table ." WHERE id = %d", $id);
				
				$results = $wpdb->get_results($query, ARRAY_A);
				
				if (!empty($results)) {
					
					$i = count($tower);
					
					foreach ($results as $key => $row) {
						
						if ($bulk === 'ban-ip' || $bulk === 'warn-ip' || $bulk === 'restore-ip') {
							
							$type = 'ip';
							
						} elseif ($bulk === 'ban-user' || $bulk === 'warn-user' || $bulk === 'restore-user') {
							
							$type = 'user';
							
						}
						
						$target = isset($row[$type]) ? $row[$type] : '';
						
						$tower_key = banhammer_armory_tower_key($type, $target, $tower);
						
						$j = ($tower_key !== false) ? $tower_key : $i;
						
						if ($tower_key) {
							
							$tower[$j]['hits'] = (int) $tower[$j]['hits'] + 1;
							
						} else {
							
							$tower[$j]['hits']   = 1;
							$tower[$j]['target'] = $target;
							$tower[$j]['status'] = isset($row['status']) ? $row['status'] : '';
							$tower[$j]['date']   = banhammer_get_date();
							
						}
						
						$update = array('status' => $row['status']);
						
						if ($bulk === 'warn-ip') {
							
							$tower[$j]['status'] = 1;
							$update['status']    = 1;
							
						} elseif ($bulk === 'warn-user') {
							
							$tower[$j]['status'] = 2;
							$update['status']    = 2;
							
						} elseif ($bulk === 'ban-ip') {
							
							$tower[$j]['status'] = 3;
							$update['status']    = 3;
							
						} elseif ($bulk === 'ban-user') {
							
							$tower[$j]['status'] = 4;
							$update['status']    = 4;
							
						} elseif ($bulk === 'restore-ip') {
							
							if ($row['status'] == 1 || $row['status'] == 3) {
								
								$tower[$j]['status'] = 5;
								$update['status']    = 5;
								
							}
							
						} elseif ($bulk === 'restore-user') {
							
							if ($row['status'] == 2 || $row['status'] == 4) {
								
								$tower[$j]['status'] = 6;
								$update['status']    = 6;
								
							}
							
						}
						
						$updated = 0;
						
						if (!empty($update['status']) && !empty($tower[$j]['target'])) {
							
							$updated = $wpdb->update($table, $update, array($type => $tower[$j]['target']), array('%d'), array('%s'));
							
						}
						
						if ($updated) {
							
							update_option('banhammer_tower', $tower);
							
							banhammer_clear_cache();
							
						}
						
						$i++;
						
					}
					
				}
				
			} elseif ($bulk === 'delete') {
				
				$wpdb->delete($table, array('id' => $id), array('%d'));
				
			}
			
		}
		
	}
	
	if (!empty($search)) {
		
		list ($query, $count) = banhammer_armory_search($wpdb, $table, $offset, $limit, $sort, $order, $search, $filter, $status);
		
	} else {
		
		list ($query, $count) = banhammer_armory_select($wpdb, $table, $offset, $limit, $sort, $order, $status);
		
	} 
	
	return array($query, $count);
	
}

function banhammer_armory_truncate($wpdb, $table) {
	
	$query = null;
	
	$count = 0;
	
	$delete = $wpdb->query("TRUNCATE TABLE ". $table);
	
	return array($query, $count);
	
}

function banhammer_armory_select($wpdb, $table, $offset, $limit, $sort, $order, $status) {
	
	$sort = array_key_exists($sort, banhammer_armory_cols()) ? $sort : 'id';
	
	$order = ($order === 'asc' || $order === 'desc') ? strtoupper($order) : 'DESC';
	
	$statuses = array('ban', 'warn', 'restore');
	
	$status = in_array($status, $statuses) ? $status : null;
	
	$where = '';
	
	if     ($status === 'warn')    $where = ' WHERE status IN (1, 2)';
	elseif ($status === 'ban')     $where = ' WHERE status IN (3, 4)';
	elseif ($status === 'restore') $where = ' WHERE status IN (5, 6)';
	
	$query = $wpdb->prepare("SELECT * FROM ". $table . $where ." ORDER BY ". $sort ." ". $order ." LIMIT %d, %d", $offset, $limit);
	
	$count = $wpdb->get_var("SELECT COUNT(*) FROM ". $table . $where);
	
	return array($query, $count);
	
}

function banhammer_armory_search($wpdb, $table, $offset, $limit, $sort, $order, $search, $filter, $status) {
	
	$sort = array_key_exists($sort, banhammer_armory_cols()) ? $sort : 'id';
	
	$order = ($order === 'asc' || $order === 'desc') ? strtoupper($order) : 'DESC';
	
	$statuses = array('ban', 'warn', 'restore');
	
	$status = in_array($status, $statuses) ? $status : null;
	
	$where_and = '';
	
	if     ($status === 'warn')    $where_and = '(status = 1 OR status = 2) AND ';
	elseif ($status === 'ban')     $where_and = '(status = 3 OR status = 4) AND ';
	elseif ($status === 'restore') $where_and = '(status = 5 OR status = 6) AND ';
	
	$cols = array('id', 'date', 'user', 'country', 'region', 'city', 'zip', 'protocol', 'method', 'response', 'ip', 'proxy', 'host', 'request', 'ua', 'refer');
	
	$cols = (!empty($filter) && $filter !== 'all') ? array($filter) : $cols;
	
	$search = "%". $wpdb->esc_like($search) ."%";
	
	$where = " WHERE ". $where_and ."(";
	
	foreach ($cols as $col) $where .= $col . $wpdb->prepare(" LIKE %s OR ", $search);
	
	$where = rtrim($where, ' OR ') .")";
	
	$orderby = $wpdb->prepare(" ORDER BY ". $sort ." ". $order ." LIMIT %d, %d", $offset, $limit);
	
	$query = "SELECT * FROM ". $table . $where . $orderby;
	
	$count = $wpdb->get_var("SELECT COUNT(*) FROM ". $table . $where);
	
	return array($query, $count);
	
}

function banhammer_armory_loop($wpdb, $table, $row) {
	
	$row = banhammer_process($wpdb, $table, $row);
	
	extract($row);
	
	// $id, $date, $status, $process, $user, $type, $theme, $code, $country, $region, $city, $zip, 
	// $protocol, $method, $response, $connect, $domain, $ip, $proxy, $host, $request, $postvars, $files, 
	// $ua, $refer, $cookies, $headers, $message, $notes, $custom, $data, $aux
	
	?>
	
	<div class="banhammer-row<?php banhammer_armory_status($status); ?>">
		<div class="banhammer-col banhammer-col1">
			<div class="banhammer-meta">
				<div class="banhammer-box banhammer-checkbox"><?php banhammer_armory_checkbox($id, $status); ?></div> 
				<div class="banhammer-box banhammer-date"><?php echo esc_html($date); ?></div>
			</div>
			<div class="banhammer-data">
				<div class="banhammer-box banhammer-actions"><?php banhammer_armory_actions($id, $ip, $user, $status); ?></div>
			</div>
		</div>
		<div class="banhammer-col banhammer-col2">
			<div class="banhammer-meta">
				<div class="banhammer-box banhammer-user"><?php banhammer_armory_user($user); ?></div> 
				<div class="banhammer-box banhammer-ip"><?php banhammer_armory_ip($ip); ?></div>
			</div>
			<div class="banhammer-data">
				<div class="banhammer-box banhammer-location"><?php banhammer_armory_location($country, $region, $city, $zip, $code); ?></div>
				<div class="banhammer-box banhammer-proxy"><?php banhammer_armory_proxy($proxy, $ip); ?></div>
				<div class="banhammer-box banhammer-host"><?php banhammer_armory_host($host, $ip, $id); ?></div>
			</div>
		</div>
		<div class="banhammer-col banhammer-col3">
			<div class="banhammer-meta">
				<div class="banhammer-box banhammer-request"><?php banhammer_armory_request($domain, $protocol, $method, $request, $response); ?></div>
			</div>
			<div class="banhammer-data">
				<div class="banhammer-box banhammer-refer"><?php banhammer_armory_refer($refer); ?></div>
				<div class="banhammer-box banhammer-ua"><?php banhammer_armory_ua($ua); ?></div>
			</div>
		</div>
	</div>
	
	<?php
	
}

function banhammer_armory_status($status) {
	
	$status = !empty($status) ? ' banhammer-status-'. $status : '';
	
	echo esc_attr($status);
	
}

function banhammer_armory_checkbox($id, $status) {
	
	$status = banhammer_armory_status_id($status);
	
	$data = esc_attr__('ID: ', 'banhammer') . $id;
	
	$title = empty($status) ? esc_attr__('Select for bulk action', 'banhammer') : $status;
	
	$checkbox = '<input type="checkbox" class="banhammer-id" value="'. esc_attr($id) .'" title="'. esc_attr($title) .'" data-title="'. esc_attr($data) .'">';
	
	echo $checkbox;
	
}

function banhammer_armory_status_id($status) {
	
	$ip   = esc_attr__('IP Address', 'banhammer');
	$user = esc_attr__('WP User',    'banhammer');
	
	$warned   = esc_attr__('WARNED',   'banhammer');
	$banned   = esc_attr__('BANNED',   'banhammer');
	$restored = esc_attr__('RESTORED', 'banhammer');
	
	if     ($status == 1) $status = $warned   .': '. $ip;
	elseif ($status == 2) $status = $warned   .': '. $user;
	elseif ($status == 3) $status = $banned   .': '. $ip;
	elseif ($status == 4) $status = $banned   .': '. $user;
	elseif ($status == 5) $status = $restored .': '. $ip;
	elseif ($status == 6) $status = $restored .': '. $user;
	else                  $status = '';
	
	return $status;
	
}

function banhammer_armory_user($login) {
	
	$link = esc_html__('Visitor', 'banhammer');
	
	if (!empty($login)) {
		
		$user = get_user_by('login', $login);
		
		if ($user) {
			
			$url = get_edit_user_link($user->ID);
			
			$data = esc_attr__('WP User', 'banhammer');
			
			$title = esc_attr__('Click to edit user', 'banhammer');
			
			$link = '<a target="_blank" rel="noopener noreferrer" href="'. esc_url($url) .'" title="'. esc_attr($title) .'" data-title="'. esc_attr($data) .'">'. esc_html($login) .'</a>';
			
		}
		
	}
	
	echo $link;
	
}

function banhammer_armory_ip($ip) {
	
	$link = esc_html__('[ undefined ]', 'banhammer');
	
	if (!empty($ip)) {
		
		$url = 'https://whatismyipaddress.com/ip/'. $ip;
		
		$data = esc_attr__('IP Address', 'banhammer');
		
		$title = esc_attr__('Click for whois lookup', 'banhammer');
		
		$ip = apply_filters('banhammer_armory_mask', $ip);
		
		$link = '<span class="banhammer-at">@</span> ';
		
		$link .= '<a target="_blank" rel="noopener noreferrer" href="'. esc_url($url) .'" title="'. esc_attr($title) .'" data-title="'. esc_attr($data) .'">'. esc_html($ip) .'</a>';
		
	}
	
	echo $link;
	
}

function banhammer_armory_request($domain, $protocol, $method, $request, $response) {
	
	$response = empty($response) ? banhammer_get_response($domain . $request, $method) : $response;
	
	$response_data = esc_attr__('Server Response', 'banhammer');
	
	$response_title = esc_attr__('Status Code:', 'banhammer') .' '. $response;
	
	$response = '<span title="'. esc_attr($response_title) .'" data-title="'. esc_attr($response_data) .'" data-response="'. esc_attr($response) .'">'. esc_html($response) .'</span> ';
	
	$method_data = esc_attr__('Request Method', 'banhammer');
	
	$method_title = $method .' '. esc_attr__('via', 'banhammer') .' '. $protocol;
	
	$method = '<span title="'. esc_attr($method_title) .'" data-title="'. esc_attr($method_data) .'">'. esc_html($method) .'</span> ';
	
	$request = '<a target="_blank" rel="noopener noreferrer" href="'. esc_url($domain . $request) .'" data-request="'. esc_attr($request) .'">'. esc_html($request) .'</a>';
	
	echo '<span class="banhammer-label">'. $response . $method .'</span> '. $request;
	
}

function banhammer_armory_actions($id, $ip, $user, $status) {
	
	$output = '';
	
	$ban_text   = esc_html__('Ban', 'banhammer');
	
	$ban_title  = esc_attr__('Ban target', 'banhammer');
	
	$ban_data   = esc_attr__('Banhammer', 'banhammer');
	
	$warn_text  = esc_html__('Warn', 'banhammer');
	
	$warn_title = esc_attr__('Warn target', 'banhammer');
	
	$warn_data  = esc_attr__('Gjallarhorn', 'banhammer');
	
	if ($status == 3 || $status == 4) {
		
		$output .= '<div class="banhammer-action-message">';
		
		if ($status == 3) {
			
			$output .= '<div>'. esc_html__('Banned IP Address:', 'banhammer') .' '. esc_html($ip) .'</div>';
			
		} else {
			
			$output .= '<div>'. esc_html__('Banned WP User:', 'banhammer') .' '. esc_html($user) .'</div>';
			
		}
		
		$output .= '<div><a href="'. admin_url('admin.php?page=banhammer-tower') .'">'. esc_html__('Manage in Tower', 'banhammer') .'</a></div>';
		
		$output .= '</div>';
		
	}
	
	$output .= '<a class="banhammer-action banhammer-action-ban" href="#banhammer" title="'. esc_attr($ban_title) .'" data-title="'. esc_attr($ban_data) .'" data-id="'. esc_attr($id) .'" data-action="ban">'. esc_html($ban_text) .'</a>';
	
	$output .= '<a class="banhammer-action banhammer-action-warn" href="#banhammer" title="'. esc_attr($warn_title) .'" data-title="'. esc_attr($warn_data) .'" data-id="'. esc_attr($id) .'" data-action="warn">'. esc_html($warn_text) .'</a>';
	
	$output .= '<select class="banhammer-select-target">';
	
	$output .= '<option value="ip" selected="selected">'. esc_html__('Target: IP Address', 'banhammer') .'</option>';
	
	$output .= (!empty($user)) ? '<option value="user">'. esc_html__('Target: WP User', 'banhammer') .'</option>' : '';
	
	$output .= '</select>';
	
	echo $output;
	
}

function banhammer_armory_location($country, $region, $city, $zip, $code) {
	
	$flag = banhammer_armory_flag($code);
	
	$country = str_replace(' ', '&nbsp;', $country);
	$region  = str_replace(' ', '&nbsp;', $region);
	$city    = str_replace(' ', '&nbsp;', $city);
	$zip     = str_replace(' ', '&nbsp;', $zip);
	
	if (empty($code)) {
		
		$data = esc_html__('Anonymous visitor (refresh page to retry geo lookup)', 'banhammer');
		
	} else {
		
		$data = '('. $code .') '. $country .', '. $region .', '. $city .', '. $zip;
		
	}
	
	$data = trim($data, ', ');
	
	echo $flag .'<span class="banhammer-whois">'. esc_html($data) .'</span>';
	
}

function banhammer_armory_flag($code) {
	
	$file = file_exists(BANHAMMER_DIR .'img/flags/'. strtolower($code) .'.svg') ? strtolower($code) : '_none';
	
	$url = BANHAMMER_URL .'img/flags/'. $file .'.svg';
	
	$flag = '<img class="banhammer-flag" src="'. esc_url($url) .'" width="50" alt="'. esc_attr($code) .'">';
	
	return $flag;
	
}

function banhammer_armory_proxy($proxy, $ip) {
	
	$link = '';
	
	if (!empty($proxy)) {
		
		$url = 'https://whatismyipaddress.com/ip/'. $proxy;
		
		$data = esc_attr__('Proxy Address', 'banhammer');
		
		$title = esc_attr__('Click for whois lookup', 'banhammer');
		
		$text = '<span class="banhammer-label">'. esc_html__('Proxy:', 'banhammer') .'</span> ';
		
		if ($proxy !== $ip) {
			
			$proxy = apply_filters('banhammer_armory_mask', $proxy);
			
			$link = $text .'<a target="_blank" rel="noopener noreferrer" href="'. esc_url($url) .'">'. esc_html($proxy) .'</a> ';
			
		}
		
	}
	
	echo $link;
	
}

function banhammer_armory_host($host, $ip, $id) {  
	
	$data = esc_attr__('Host Name', 'banhammer');
	
	if (empty($host)) {
		
		$title = esc_attr__('Click link for host name', 'banhammer');
		
		$link = '<span class="banhammer-hostlookup-id-'. esc_attr($id) .'">';
		
		$link .= '<a class="banhammer-hostlookup-link" href="#banhammer" data-id="'. esc_attr($id) .'" data-ip="'. esc_attr($ip) .'">'. esc_attr__('Get Host Name', 'banhammer') .'</a>';
		
		$link .= '</span>';
		
	} else {
		
		$url = 'https://gwhois.org/'. $ip .'+dns';
		
		$title = esc_attr__('Click link for whois', 'banhammer');
		
		$link = '<a target="_blank" rel="noopener noreferrer" href="'. esc_url($url) .'">'. esc_html($host) .'</a>';
		
	}
	
	$text = '<span class="banhammer-label" title="'. esc_attr($title) .'" data-title="'. esc_attr($data) .'">'. esc_attr__('Host', 'banhammer') .':</span> ';
	
	echo $text . $link;
	
}

function banhammer_armory_refer($refer) {
	
	$text = '<span class="banhammer-label">'. esc_html__('Referrer:', 'banhammer') .'</span> ';
	
	if ($refer === BANHAMMER_BLANK) {
		
		$link = '<span>'. esc_html($refer) .'</span>';
		
	} else {
		
		$link = '<a target="_blank" rel="noopener noreferrer" href="'. esc_url($refer) .'">'. esc_html($refer) .'</a>';
		
	}
	
	echo $text . $link;
	
}

function banhammer_armory_ua($ua) {
	
	$output = '<span class="banhammer-label">'. esc_html__('User Agent:', 'banhammer') .'</span> '. esc_html($ua);
	
	echo $output;
	
}

function banhammer_armory_vars() {
	
	global $wpdb;
	
	$table  = $wpdb->prefix .'banhammer';
	
	$items  = (isset($_POST['items'])) ? $_POST['items'] : null;
	
	$type   = (isset($_POST['type'])) ? sanitize_text_field($_POST['type']) : null;
	
	$bulk   = (isset($_POST['bulk'])) ? sanitize_text_field($_POST['bulk']) : null;
	
	$sort   = (isset($_POST['sort'])) ? sanitize_text_field($_POST['sort']) : null;
	
	$order  = (isset($_POST['order'])) ? sanitize_text_field($_POST['order']) : null;
	
	$search = (isset($_POST['search'])) ? sanitize_text_field($_POST['search']) : null;
	
	$filter = (isset($_POST['filter'])) ? sanitize_text_field($_POST['filter']) : null;
	
	$status = (isset($_POST['status'])) ? sanitize_text_field($_POST['status']) : null;
	
	$fx     = (isset($_POST['fx']) && banhammer_is_positive_integer($_POST['fx'])) ? $_POST['fx'] : 0;
	
	$jump   = (isset($_POST['jump']) && banhammer_is_positive_integer($_POST['jump'])) ? $_POST['jump'] : 1;
	
	$count  = (isset($_POST['count']) && banhammer_is_positive_integer($_POST['count'])) ? $_POST['count'] : 0;
	
	$limit  = (isset($_POST['limit']) && banhammer_is_positive_integer($_POST['limit'])) ? $_POST['limit'] : 3;
	
	$offset = (isset($_POST['offset']) && banhammer_is_positive_integer($_POST['offset'])) ? $_POST['offset'] : 0;
	
	$toggle = (isset($_POST['toggle']) && banhammer_is_positive_integer($_POST['toggle'])) ? $_POST['toggle'] : 2;
	
	$vars = array(
		
		'wpdb'   => $wpdb,
		'table'  => $table,
		'items'  => $items,
		'type'   => $type,
		'bulk'   => $bulk,
		'sort'   => $sort,
		'order'  => $order,
		'search' => $search,
		'filter' => $filter,
		'status' => $status,
		'fx'     => $fx,
		'jump'   => $jump,
		'count'  => $count,
		'limit'  => $limit,
		'offset' => $offset,
		'toggle' => $toggle
	);
	
	return apply_filters('banhammer_armory_vars', $vars);
	
}

function banhammer_armory_cols() {
	
	return array(
		'id'       => __('ID',         'banhammer'),
		'date'     => __('Date',       'banhammer'),
		'status'   => __('Status',     'banhammer'),
		'user'     => __('User',       'banhammer'),
		'country'  => __('Country',    'banhammer'),
		'region'   => __('Region',     'banhammer'),
		'city'     => __('City',       'banhammer'),
		'zip'      => __('Zip',        'banhammer'),
		'protocol' => __('Protocol',   'banhammer'),
		'method'   => __('Method',     'banhammer'),
		'response' => __('Response',   'banhammer'),
		'ip'       => __('IP Address', 'banhammer'),
		'proxy'    => __('Proxy/IP',   'banhammer'),
		'host'     => __('Host',       'banhammer'),
		'request'  => __('Request',    'banhammer'),
		'ua'       => __('User Agent', 'banhammer'),
		'refer'    => __('Referrer',   'banhammer')
	);
	
}

function banhammer_armory_tower_key($key, $string, $array) {
	
	foreach ($array as $k => $v) {
		
		if (isset($v['target']) && $v['target'] === $string) return $k;
		
	}
	
	return false;
	
}

function banhammer_armory_examples() {
	
	$args1 = array(
		'method'      => 'GET',
		'httpversion' => '1.0',
		'user-agent'  => 'Banhammer: Example Bot; '. home_url()
	);
	
	$args2 = array(
		'method'      => 'POST',
		'httpversion' => '1.1',
		'user-agent'  => 'Banhammer: Example Bot; '. home_url()
	);
	
	$args3 = array(
		'method'      => 'PUT',
		'httpversion' => '2.0',
		'user-agent'  => 'Banhammer: Example Bot; '. home_url()
	);
	
	$args = array(
		add_query_arg('s', 'banhammer-test-1', get_home_url()) => $args1,
		add_query_arg('s', 'banhammer-test-2', get_home_url()) => $args2,
		add_query_arg('s', 'banhammer-test-3', get_home_url()) => $args3,
	);
	
	$args = apply_filters('banhammer_armory_examples', $args);
	
	foreach ($args as $k => $v) wp_safe_remote_request($k, $v);
	
	return false;
	
}

function banhammer_armory_count($query, $count, $type, $offset, $limit, $results) {
	
	if (empty($query) || empty($results)) { // after truncate || zero results
		
		$message = '<div class="banhammer-noresults">'. esc_html__('No results', 'banhammer');
		
		if ($type !== 'search' && $type !== 'bulk' && $type !== 'status') {
			
			$message .= '<span class="banhammer-addvisit">. <a class="banhammer-addvisit-link" href="#banhammer">';
			$message .= esc_html__('Click here', 'banhammer') .'</a> '. esc_html__('to load some examples.', 'banhammer') .'</span>';
			
		} else {
			
			$message .= '<span class="banhammer-addvisit"> <span class="banhammer-sep">|</span> ';
			$message .= '<a class="banhammer-reload-link" href="#reset">'. esc_html__('Reset', 'banhammer') .'</a></span>';
			
		}
		
		$message .= '</div>';
		
	} else {
		
		$count = empty($count) ? 0 : $count;
		
		$total = ($count < ($offset + $limit)) ? $count : ($offset + $limit);
		
		$message = ($offset + 1) .'&ndash;'. $total .' '. esc_html__('of', 'banhammer') .' '. $count;
		
	}
	
	$output = '<div class="banhammer-count-data" data-count="'. $count .'" style="display:none;">'. $message .'</div>';
	
	echo $output;
	
}
