<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction,$wpdb;
$current_user_data = get_userdata($current_user->ID);
$project_id = intval(sanitize_text_field($project_id));
$wppm_project_data = $wppmfunction->get_project($project_id);
preg_match_all("/{[^}]*}/" ,$str,$matches);
$matches = array_unique($matches[0]);
foreach($matches as $match){
	switch($match){
		//Current User Name
		case '{user_name}':
			$str = preg_replace('/{user_name}/', $current_user_data->display_name, $str);
			break;
		// Project ID
		case '{project_id}':
			$str = preg_replace('/{project_id}/', $project_id, $str);
			break;
		// Old project Status
		case '{old_project_status}':
			$str = preg_replace('/{old_project_status}/', $this->get_old_project_status_name($project_id), $str);
			break;
		// Project Status
		case '{new_project_status}':
			$str = preg_replace('/{new_project_status}/', $this->get_new_project_status_name(sanitize_text_field($wppm_project_data['status'])), $str);
			break;
		// Project Status
		case '{project_status}':
			$str = preg_replace('/{project_status}/', $this->get_new_project_status_name(sanitize_text_field($wppm_project_data['status'])), $str);
			break;
		// Project Category
		case '{project_category}':
			$str = preg_replace('/{project_category}/', $this->get_project_category_name(sanitize_text_field($wppm_project_data['cat_id'])), $str);
			break;
		// Project Name
		case '{project_name}':
			$str = preg_replace('/{project_name}/', sanitize_text_field($wppm_project_data['project_name']), $str);
			break;
		// Project Start Date
		case '{project_start_date}':
			$str = preg_replace('/{project_start_date}/', sanitize_text_field($wppm_project_data['start_date']), $str);
			break;
		// Project End Date
		case '{project_end_date}':
			$str = preg_replace('/{project_end_date}/', sanitize_text_field($wppm_project_data['end_date']), $str);
			break;
		// Assigned Users
		case '{project_assigned_users}':
			$assigned_users = $this->get_project_assigned_users_names($project_id);
			$str = preg_replace('/{project_assigned_users}/', sanitize_text_field($assigned_users), $str);
			break;
		// Previously Assigned users
		case '{previously_assigned_project_users}':
			$previously_assigned_users = $this->get_project_previously_assigned_users_names($project_id);
			$str = preg_replace('/{previously_assigned_project_users}/', sanitize_text_field($previously_assigned_users), $str);
			break;
		// Date created
		case '{date_created}':
			$str = preg_replace('/{date_created}/', get_date_from_gmt(sanitize_text_field($wppm_project_data['date_created'] )), $str);
			break;
		// Project Description
		case '{project_description}':
		$str = preg_replace('/{project_description}/', sanitize_text_field($wppm_project_data['description']), $str);

		
	}
}
$str = apply_filters('wppm_replace_macro',$str,$project_id);

