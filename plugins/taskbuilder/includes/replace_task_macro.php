<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction,$wpdb;
$current_user_data = get_userdata($current_user->ID);
$task_id = intval(sanitize_text_field($task_id));
$wppm_task_data = $wppmfunction->get_task($task_id);
$wppm_task_comment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_task_comment where task_id='".$task_id."' ORDER BY id DESC LIMIT 1;");
$wppm_project_data = $wppmfunction->get_project($wppm_task_data['project']);
$auth_id           = sanitize_text_field($wppm_task_data['task_auth_code']);
$attachments = array();
if(!empty($wppm_task_comment)){
	$attachments = explode(',',$wppm_task_comment->attachment_ids);
}
preg_match_all("/{[^}]*}/" ,$str,$matches);
$matches = array_unique($matches[0]);
$flag =false;
foreach($matches as $match){
	switch($match){
		// Task ID
		case '{task_id}':
			$str = preg_replace('/{task_id}/', $task_id, $str);
			break;
		//Current User Name
		case '{user_name}':
			$str = preg_replace('/{user_name}/', sanitize_text_field($current_user_data->display_name), $str);
			break;
		// Task Name
		case '{task_name}':
			$str = preg_replace('/{task_name}/', sanitize_text_field($wppm_task_data['task_name']), $str);
			break;
		//Old Task Status
		case '{old_task_status}':
			$str = preg_replace('/{old_task_status}/', $this->get_old_task_status_name($task_id), $str);
			break;
		//New Task Status
		case '{new_task_status}':
			$str = preg_replace('/{new_task_status}/', $this->get_new_task_status_name(sanitize_text_field($wppm_task_data['status'])), $str);
			break;
		//New Task Status
		case '{task_status}':
			$str = preg_replace('/{task_status}/', $this->get_new_task_status_name(sanitize_text_field($wppm_task_data['status'])), $str);
			break;
		//Task Assigned Users
		case '{task_assigned_users}':
			$assigned_users = $this->get_task_assigned_users_names($task_id);
			$str = preg_replace('/{task_assigned_users}/', sanitize_text_field($assigned_users), $str);
			break;
		//Previously Assign Task Users
		case '{previously_assigned_task_users}':
			$previously_assigned_task_users = $this->get_task_previously_assigned_users_names($task_id);
			$str = preg_replace('/{previously_assigned_task_users}/', sanitize_text_field($previously_assigned_task_users), $str);
			break;
		// Task Start Date
		case '{task_start_date}':
			$str = preg_replace('/{task_start_date}/', sanitize_text_field($wppm_task_data['start_date']), $str);
			break;
		// Task End Date
		case '{task_end_date}':
			$str = preg_replace('/{task_end_date}/', sanitize_text_field($wppm_task_data['end_date']), $str);
			break;
		// Task Priority
		case '{task_priority}':
			$str = preg_replace('/{task_priority}/', $this->get_task_priority_name(sanitize_text_field($wppm_task_data['priority'])), $str);
			break;
		// Task Description
		case '{task_description}':
			$str = preg_replace('/{task_description}/', sanitize_text_field($wppm_task_data['description']), $str);
			break;
		// Date created
		case '{task_date_created}':
			$str = preg_replace('/{task_date_created}/', get_date_from_gmt(sanitize_text_field($wppm_task_data['date_created']) ), $str);
			break;
		// Project Name
		case '{project_name}':
			$str = preg_replace('/{project_name}/', sanitize_text_field($wppm_project_data['project_name']), $str);
		break;
		//Last comment user name
		case '{last_comment_user_name}':
			$str = preg_replace('/{last_comment_user_name}/', $this->get_last_comment_user_name($task_id), $str);
		break;
		//Project status
		case '{project_status}';
			$str = preg_replace('/{project_status}/', $this->get_new_project_status_name((sanitize_text_field($wppm_project_data['status'])), $str));
		break;
		// Project Category
		case '{project_category}';
			$str = preg_replace('/{project_category}/', $this->get_project_category_name((sanitize_text_field($wppm_project_data['cat_id'])), $str));
		break;
		// Project Name
		case '{project_name}':
			$str = preg_replace('/{project_name}/', (sanitize_text_field($wppm_project_data['project_name'])), $str);
		break;
		// Project Start Date
		case 'project_start_date':
			$str = preg_replace('/{project_start_date}/', (sanitize_text_field($wppm_project_data['start_date'])), $str);
		break;
		// Project End Date
		case '{project_end_date}':
			$str = preg_replace('/{project_end_date}/', (sanitize_text_field($wppm_project_data['end_date'])), $str);
		break;
		// Project Description
		case '{project_description}':
			$str = preg_replace('/{project_description}/', (sanitize_text_field($wppm_project_data['description'])), $str);
		break;
		// Project Assigned Users
		case '{project_assigned_users}':
			$assigned_users = $this->get_project_assigned_users_names($wppm_project_data['id']);
			$str = preg_replace('/{project_assigned_users}/', (sanitize_text_field($assigned_users)), $str);
		break;
		//Last comment body
		case '{comment_body}':
			$flag= true;
			$str = preg_replace('/{comment_body}/', $this->get_last_comment_body($task_id), $str);
		break;

	}
}
if($flag == true){
	if(!empty($attachments)){
		foreach($attachments as $attach_id){
			$upload_dir   = wp_upload_dir();
			$attachment = $wpdb->get_row("select * from {$wpdb->prefix}wppm_attachments where id='".$attach_id."'");
			if(!empty($attachment)){
				$updated_time = sanitize_text_field($attachment->date_created);
				$time  = strtotime(sanitize_text_field($updated_time));
				$month = date("m",$time);
				$year  = date("Y",$time);
				$findStr = ".txt";
				$attachment_name = preg_replace('/' . $findStr . '/', "", sanitize_file_name($attachment->name), 1);
				$file_url = $upload_dir['basedir'] . '/wppm/'.'/'.$year.'/'.$month.'/'. $attachment_name;
				$download_url = home_url('/').'?wppm_attachment='.sanitize_text_field($attachment->id).'&tid='.sanitize_text_field($task_id).'&tac='.sanitize_text_field($auth_id);
				$attach_url[] = '<a style="text-decoration:none;" href="'.$download_url.'" target="_blank">'.sanitize_file_name($attachment->file_name).'</a>';
			}else{
				$attach_url= array();
			}
		}
		$str = $str.implode("<br>",$attach_url);
	}
	
}
$str = apply_filters('wppm_replace_task_macro',$str,$task_id);