<?php // Tower Ajax

if (!defined('ABSPATH')) exit;

function banhammer_tower() {
	
	check_ajax_referer('banhammer', 'nonce');
	
	if (!current_user_can('manage_options')) return;
	
	$vars = banhammer_tower_vars();
	
	extract($vars); // $wpdb, $table, $default, $tower, $sort, $type, $bulk, $items, $demo
	
	$tower = banhammer_tower_update($wpdb, $table, $default, $tower, $bulk, $items, $demo);
	
	$count = count($tower);
	
	$i = 0;
	
	foreach ($tower as $k => $v) {
		
		extract($v); // $date, $target, $hits, $status
		
		if (banhammer_tower_sort_status($sort, $status)) continue;
		
		if (banhammer_tower_sort_type($type, $status)) continue;
		
		?>
		
		<div class="banhammer-row<?php echo banhammer_tower_status_class($status); ?>">
			<div class="banhammer-col banhammer-col1">
				<div class="banhammer-box banhammer-checkbox"><?php echo banhammer_tower_checkbox($target, $status); ?></div>
				<div class="banhammer-box banhammer-actions"><?php echo banhammer_tower_actions(); ?></div>
			</div>
			<div class="banhammer-col banhammer-col2">
				<div class="banhammer-box banhammer-status"><?php echo banhammer_tower_status($status); ?></div>
				<div class="banhammer-box banhammer-hits"><?php echo banhammer_tower_hits($hits); ?></div>
			</div>
			<div class="banhammer-col banhammer-col3">
				<div class="banhammer-box banhammer-date"><?php echo banhammer_tower_date($date); ?></div>
				<div class="banhammer-box banhammer-target"><?php echo banhammer_tower_target($target, $status); ?></div>
			</div>
		</div>
		
		<?php
		
		$i++;
		
	}
	
	echo banhammer_tower_count($i, $count, $wpdb, $table, $sort, $type);
	
	die(); // 
	
}

function banhammer_tower_date($date) {
	
	return str_replace('@', '<span class="banhammer-at">@</span>', esc_html($date));
	
}

function banhammer_tower_hits($hits) {
	
	$hits = esc_attr__('Hits',  'banhammer') .': '.  $hits;
	
	return $hits;
	
}

function banhammer_tower_target($target, $status) {
	
	if ($status == 1 || $status == 3 || $status == 5) {
		
		$url = 'https://whatismyipaddress.com/ip/'. $target;
		
		$data = esc_attr__('IP Address', 'banhammer');
		
		$title = esc_attr__('Click for whois lookup', 'banhammer');
		
		$target = apply_filters('banhammer_armory_mask', $target);
		
		$status = $data .': <a target="_blank" rel="noopener noreferrer" href="'. esc_url($url) .'" title="'. esc_attr($title) .'" data-title="'. esc_attr($data) .'">'. esc_html($target) .'</a>';
		
	} elseif ($status == 2 || $status == 4 || $status == 6) {
		
		$user = get_user_by('login', $target);
		
		$url = get_edit_user_link($user->ID);
		
		$data = esc_attr__('WP User', 'banhammer');
		
		$title = esc_attr__('Click to edit user', 'banhammer');
		
		$status = $data .': <a target="_blank" rel="noopener noreferrer" href="'. esc_url($url) .'" title="'. esc_attr($title) .'" data-title="'. esc_attr($data) .'">'. esc_html($target) .'</a>';
		
	} else {
		
		$status = esc_attr__('Unknown: ',    'banhammer') . $target;
		
	}
	
	return $status;
	
}

function banhammer_tower_checkbox($target, $status) {
	
	$data = esc_attr__('Status: ', 'banhammer') . $status;
	
	$title = esc_attr__('Select for bulk action', 'banhammer');
	
	$checkbox = '<input type="checkbox" class="banhammer-tower-id" title="'. esc_attr($title) .'" data-title="'. esc_attr($data) .'" value="'. esc_attr($target) .'">';
	
	return $checkbox;
	
}

function banhammer_tower_actions() {
	
	$ban_text  = esc_html__('Ban',       'banhammer');
	$ban_title = esc_html__('Ban item',  'banhammer');
	$ban_data  = esc_html__('Banhammer', 'banhammer');
	$ban_class = 'class="banhammer-action banhammer-action-ban"';
	
	$warn_text  = esc_html__('Warn',        'banhammer');
	$warn_title = esc_html__('Warn item',   'banhammer');
	$warn_data  = esc_html__('Gjallarhorn', 'banhammer');
	$warn_class = 'class="banhammer-action banhammer-action-warn"';
	
	$restore_text  = esc_html__('Restore',      'banhammer');
	$restore_title = esc_html__('Restore item', 'banhammer');
	$restore_data  = esc_html__('Skjold',       'banhammer');
	$restore_class = 'class="banhammer-action banhammer-action-restore"';
	
	$delete_text  = esc_html__('Delete',      'banhammer');
	$delete_title = esc_html__('Delete item', 'banhammer');
	$delete_data  = esc_html__('Øks',         'banhammer');
	$delete_class = 'class="banhammer-action banhammer-action-delete"';
	
	$output  = '<a '. $ban_class     .' href="#banhammer" data-action="ban"     title="'. $ban_title     .'" data-title="'. $ban_data     .'">'. $ban_text     .'</a> ';
	$output .= '<a '. $warn_class    .' href="#banhammer" data-action="warn"    title="'. $warn_title    .'" data-title="'. $warn_data    .'">'. $warn_text    .'</a> ';
	$output .= '<a '. $restore_class .' href="#banhammer" data-action="restore" title="'. $restore_title .'" data-title="'. $restore_data .'">'. $restore_text .'</a> ';
	$output .= '<a '. $delete_class  .' href="#banhammer" data-action="delete"  title="'. $delete_title  .'" data-title="'. $delete_data  .'">'. $delete_text  .'</a> ';
	
	return $output;
	
}

function banhammer_tower_vars() {
	
	global $BanhammerWP, $wpdb;
	
	$table = $wpdb->prefix .'banhammer';
	
	$default = $BanhammerWP->tower();
	
	$tower = get_option('banhammer_tower', $default);
	
	$sort   = (isset($_POST['sort'])) ? sanitize_text_field($_POST['sort']) : null;
	
	$type   = (isset($_POST['type'])) ? sanitize_text_field($_POST['type']) : null;
	
	$bulk   = (isset($_POST['bulk'])) ? sanitize_text_field($_POST['bulk']) : null;
	
	$items  = (isset($_POST['items'])) ? $_POST['items'] : null;
	
	$demo   = (isset($_POST['demo']) && !empty($_POST['demo'])) ? 1 : 0;
	
	$vars = array(
		
		'wpdb'    => $wpdb,
		'table'   => $table,
		'default' => $default,
		'tower'   => $tower,
		'sort'    => $sort,
		'type'    => $type,
		'bulk'    => $bulk,
		'items'   => $items,
		'demo'    => $demo
	);
	
	return apply_filters('banhammer_tower_vars', $vars);
	
}

function banhammer_tower_update($wpdb, $table, $default, $tower, $bulk, $items, $demo) {
	
	if (!empty($tower) && !empty($bulk) && !empty($items)) {
		
		foreach ($tower as $key => $value) {
			
			$status = isset($value['status']) ? $value['status'] : false;
			
			$target = isset($value['target']) ? $value['target'] : false;
			
			$tower[$key]['status'] = $status;
			
			$update = array();
			
			$type = false;
			
			if (is_array($items) && in_array($target, $items)) {
				
				if ($status == 1 || $status == 3 || $status == 5) {
					
					$type = 'ip';
					
					if ($bulk === 'warn') {
						
						$tower[$key]['status'] = 1;
						$update['status']      = 1;
						
					} elseif ($bulk === 'ban') { 
						
						$tower[$key]['status'] = 3;
						$update['status']      = 3;
						
					} elseif ($bulk === 'restore') {
						
						$tower[$key]['status'] = 5;
						$update['status']      = 5;
						
					} elseif ($bulk === 'delete') {
						
						unset($tower[$key]);
						$update['status'] = 0;
						
					}
					
				} elseif ($status == 2 || $status == 4 || $status == 6) {
					
					$type = 'user';
					
					if ($bulk === 'warn') {
						
						$tower[$key]['status'] = 2;
						$update['status']      = 2;
						
					} elseif ($bulk === 'ban') { 
						
						$tower[$key]['status'] = 4;
						$update['status']      = 4;
						
					} elseif ($bulk === 'restore') {
						
						$tower[$key]['status'] = 6;
						$update['status']      = 6;
						
					} elseif ($bulk === 'delete') {
						
						unset($tower[$key]);
						$update['status'] = 0;
						
					}
					
				}
				
				$wpdb->update($table, $update, array($type => $target), array('%d'), array('%s'));
				
			}
			
		}
		
		$tower = array_values($tower);
		
		update_option('banhammer_tower', $tower);
		
		banhammer_clear_cache();
		
	} else {
		
		if ($demo && empty($tower)) {
			
			update_option('banhammer_tower', $default);
			
			banhammer_clear_cache();
			
		}
		
	}
	
	$tower = get_option('banhammer_tower', $default);
	
	return $tower;
	
}

function banhammer_tower_sort_status($sort, $status) {
	
	$continue = false;
	
	if ($sort === 'warned') {
		
		if ($status != 1 && $status != 2) $continue = true;
		
	} elseif ($sort === 'banned') {
		
		if ($status != 3 && $status != 4) $continue = true;
		
	} elseif ($sort === 'restored') {
		
		if ($status != 5 && $status != 6) $continue = true;
		
	}
	
	return $continue;
	
}

function banhammer_tower_sort_type($type, $status) {
	
	$continue = false;
	
	if ($type === 'ip') {
		
		if ($status != 1 && $status != 3 && $status != 5) $continue = true;
		
	} elseif ($type === 'user') {
		
		if ($status != 2 && $status != 4 && $status != 6) $continue = true;
		
	}
	
	return $continue;
	
}

function banhammer_tower_count($i, $count, $wpdb, $table, $sort, $type) {
	
	$output = '';
	
	if (empty($i)) {
		
		banhammer_tower_status_reset($wpdb, $table);
		
		$output .= banhammer_tower_noresults($sort, $type);
		
	}
	
	$output .= '<div class="banhammer-count-data" style="display:none;" ';
	
	$output .= 'data-count="'. esc_attr($i) .'" data-total="'. esc_attr($count) .'" ';
	
	$output .= 'data-text1="'. esc_attr__('Viewing', 'banhammer') .'" data-text2="'. esc_attr__('of', 'banhammer') .'">';
	
	$output .= '</div>';
	
	return $output;
	
}

function banhammer_tower_noresults($sort, $type) {
	
	$output = '<div class="banhammer-row banhammer-status-0">';
	
	$output .= '<div class="banhammer-box banhammer-noresults">';
	
	$output .= esc_html__('No results.', 'banhammer');
	
	if (empty($sort) && empty($type)) {
		
		$output .= ' '. esc_html__('Visit the', 'banhammer');
		
		$output .= ' <a href="'. admin_url('admin.php?page=banhammer-armory') .'">'. esc_html__('Armory', 'banhammer') .'</a> ';
		
		$output .= esc_html__('to monitor traffic and ban some enemies. Or', 'banhammer');
		
		$output .= ' <a class="banhammer-example-link" href="#banhammer">'. esc_html__('click here', 'banhammer') .'</a> ';
		
		$output .= esc_html__('to load an example.', 'banhammer');
		
	}
	
	$output .= '</div>';
	
	$output .= '</div>';
	
	return $output;
	
}

function banhammer_tower_status($status) {
	
	if ($status == 1 || $status == 2) $status = esc_html__('Warned', 'banhammer');
	
	if ($status == 3 || $status == 4) $status = esc_html__('Banned', 'banhammer');
	
	if ($status == 5 || $status == 6) $status = esc_html__('Restored', 'banhammer');
	
	return $status;
	
}

function banhammer_tower_status_class($status) {
	
	$status = !empty($status) ? ' banhammer-status-'. $status : '';
	
	return esc_attr($status);
	
}

function banhammer_tower_status_reset($wpdb, $table) {
	
	$status = 0;
	
	$prepare = $wpdb->prepare("UPDATE ". $table ." SET status = %d", $status);
	
	return $wpdb->query($prepare);
	
}
