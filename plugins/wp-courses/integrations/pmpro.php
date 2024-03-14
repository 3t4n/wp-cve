<?php

	/********** Paid Memberships Pro **********/

	add_filter('wpc_lesson_content', 'wpc_pmpro_filter_ajax_lesson_content', 10, 3);

	function wpc_pmpro_filter_ajax_lesson_content($content, $post_id, $course_id = null){

		if(function_exists('pmpro_hasMembershipLevel')) {

			$levels = wpc_pmpro_get_page_levels($post_id);
			$levels = implode(',', $levels);

			if(empty($levels)) { // not restricted by PMPro
				return $content;
			} else {
				return do_shortcode('[membership show_noaccess="true" level="' . $levels . '"]' . $content . '[/membership]');
			}
			
		} else {
			return $content;
		}

	}

	function wpc_pmpro_get_all_membership_levels(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'pmpro_membership_levels';
		$sql = "SELECT membership_id FROM {$table_name}";
	    $levels = $wpdb->get_col($sql);
		return $levels;  
	}

	function wpc_pmpro_get_page_levels($post_id){
		global $wpdb;
		$table_name = $wpdb->prefix . 'pmpro_memberships_pages';
		$sql = "SELECT membership_id FROM {$table_name} WHERE page_id = {$post_id}";
	    $levels = $wpdb->get_col($sql);
		return $levels;  
	}

	function wpc_pmpro_get_user_levels($user_id){
		global $wpdb;
		$table_name = $wpdb->prefix . 'pmpro_memberships_users';
		$sql = "SELECT membership_id FROM {$table_name} WHERE user_id = {$user_id}";
	    $levels = $wpdb->get_col($sql);
		return $levels;  
	}

?>