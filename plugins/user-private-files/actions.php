<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

// Add to folder if selected
add_action('upf_file_inserted', 'upf_pro_file_inserted', 10, 1);
if (!function_exists('upf_pro_file_inserted')) {
	function upf_pro_file_inserted($attach_id){
		if(isset($_POST['fldr_id'])){
			$fldr_id = sanitize_text_field($_POST['fldr_id']);
			
			$curr_user_id = get_current_user_id();
			update_post_meta($attach_id, 'upf_allowed', array($curr_user_id));
			update_post_meta($attach_id, 'upf_acs_full', array($curr_user_id));
			
			if($fldr_id != 'all-files'){
				
				update_post_meta($attach_id, 'upf_foldr_id', $fldr_id);
				
				$curr_allowed_users = get_post_meta($fldr_id, 'upf_allowed', true);
				if (!in_array($curr_user_id, $curr_allowed_users)){
					array_push($curr_allowed_users, $curr_user_id);
				}
				update_post_meta($attach_id, 'upf_allowed', $curr_allowed_users);
				
				$curr_full_acs_users = get_post_meta($fldr_id, 'upf_acs_full', true);
				if (!in_array($curr_user_id, $curr_full_acs_users)){
					array_push($curr_full_acs_users, $curr_user_id);
				}
				update_post_meta($attach_id, 'upf_acs_full', $curr_full_acs_users);
				
			}
		}
	}
}