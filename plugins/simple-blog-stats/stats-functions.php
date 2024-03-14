<?php // Stats Functions



// number of logged-in users

function simple_blog_stats_count_logged() {
	
	if (wp_doing_ajax() || wp_doing_cron()) return;
	
	if (is_user_logged_in()) {
		
		$logged_in_users = get_transient('online_status');
		
		$logged_in_users = $logged_in_users ? $logged_in_users : array();
		
		$user = wp_get_current_user();
		
		$user_id = isset($user->ID) ? $user->ID : null;
		
		$no_need_to_update = isset($logged_in_users[$user_id]) && $logged_in_users[$user_id] > (time() - (1 * 60));
		
		if (!$no_need_to_update) {
			
			$logged_in_users[$user_id] = time();
			
			set_transient('online_status', $logged_in_users, (2 * 60));
			
		}
		
	}
	
}
add_action('wp',         'simple_blog_stats_count_logged');
add_action('admin_init', 'simple_blog_stats_count_logged');



function simple_blog_stats_clear_transient() {
	
	$user_id = get_current_user_id();
	
	$users_transient_id = get_transient('online_status');
	
	if (is_array($users_transient_id)) {
		
		foreach($users_transient_id as $id => $value) {
			
			if ($id == $user_id) {
				
				unset($users_transient_id[$user_id]);
				
				set_transient('online_status', $users_transient_id, (2 * 60));
				
				break;
				
			}
		}
		
	} else {
		
		delete_transient('online_status');
		
	}
	
}
add_action('clear_auth_cookie', 'simple_blog_stats_clear_transient');



function sbs_logged_users_shortcode() {
	
	global $sbs_options;
	
	$before = isset($sbs_options['logged_users_before']) ? $sbs_options['logged_users_before'] : '';
	
	$after = isset($sbs_options['logged_users_after']) ? $sbs_options['logged_users_after'] : '';
	
	$logged_in_users = get_transient('online_status');
	
	$count_zero = __('Currently no users logged in.', 'simple-blog-stats');
	
	$count_zero = apply_filters('sbs_no_logged_users', $count_zero);
	
	$count = !empty($logged_in_users) ? count($logged_in_users) : $count_zero;
	
	return $before . $count . $after;
	
}
add_shortcode('sbs_logged_users', 'sbs_logged_users_shortcode');



function sbs_logged_users() {
	
	echo sbs_logged_users_shortcode();

}



function simple_blog_stats_logged_users_widget() {
	
	if (current_user_can('manage_options')) {
		
		wp_add_dashboard_widget('simple_blog_stats_logged_users_widget', __('Online Users', 'simple-blog-stats'), 'sbs_logged_users');
		
	}
	
}
add_action('wp_dashboard_setup', 'simple_blog_stats_logged_users_widget');