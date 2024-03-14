<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
if (!($current_user->ID)) {exit;}

if (!isset($source)) {
	$term       = isset($_REQUEST) && isset($_REQUEST['term']) ? sanitize_text_field($_REQUEST['term']) : '';
	$field_slug = isset($_REQUEST) && isset($_REQUEST['field']) ? sanitize_text_field($_REQUEST['field']) : '';
    $task_id =    isset($_REQUEST) && isset($_REQUEST['task_id']) ? intval(sanitize_text_field($_REQUEST['task_id'])) : '0';
    $project_id = isset($_REQUEST) && isset($_REQUEST['proj_id']) ? intval(sanitize_text_field($_REQUEST['proj_id'])) : '0';
}
$output = array();
switch ($field_slug) {
    case 'users_name':
        if($field_slug == 'users_name'){
            $users = get_users(array('search'=>'*'.$term.'*','number' => 5));
            $users = apply_filters("wppm_filter_autocomplete_users_name",$users,$field_slug,$term,$task_id,$project_id);
            if(!empty($users)){
                foreach ($users as $user) {
                    $output[] = array(
                        'label' => $user->display_name,
                        'value' => $user->display_name,
                        'user_id'  => $user->ID,
                    );
                }
            }
        }
        break;
    case 'task_user_name':
        if($field_slug == 'task_user_name'){
            $wppm_fillter = $wpdb->get_var("SELECT Project.users
                  FROM {$wpdb->prefix}wppm_project AS Project 
                  Left join {$wpdb->prefix}wppm_task task ON Project.id = task.project
                  where task.id = $task_id");
            $users = get_users(array('search'=>'*'.$term.'*','number' => 5));
            $users = apply_filters("wppm_filter_autocomplete_task_users_name",$users,$field_slug,$term,$task_id,$project_id);
            $users_array = explode(",",(string)$wppm_fillter);
            if(!empty($users)){
                foreach ($users as $user) {
                    if(in_array($user->ID,$users_array)){
                        $output[] = array(
                            'label' => $user->display_name,
                            'value' => $user->display_name,
                            'user_id'  => $user->ID,
                        );
                    }
                }
            }
        }
        break;
        case 'task_users_by_project_name':
            if($field_slug == 'task_users_by_project_name'){
                $wppm_fillter = $wpdb->get_var( "SELECT users FROM {$wpdb->prefix}wppm_project where id = $project_id" );
                $users = get_users(array('search'=>'*'.$term.'*','number' => 5));
                $users = apply_filters("wppm_filter_autocomplete_task_users_by_project_name",$users,$field_slug,$term,$task_id,$project_id);
                $project_user_role = $wpdb->get_var("SELECT role_id FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id AND user_id = $current_user->ID");
                $users_array = explode(",",(string)$wppm_fillter);
                if((!empty($users_array) && ($project_user_role==1 || $current_user->has_cap('manage_options'))) ){
                        foreach ($users as $user) {
                            if(in_array($user->ID,$users_array)){
                                $output[] = array(
                                    'label' => $user->display_name,
                                    'value' => $user->display_name,
                                    'user_id'  => $user->ID,
                                );
                            }
                        }
                    
                }else{
                    $output[] = array(
                        'label' => $current_user->display_name,
                        'value' => $current_user->display_name,
                        'user_id'  => $current_user->ID,
                    );
                }
            }
        break;
        case 'project_creator_name':
            if($field_slug == 'project_creator_name'){
                $users = get_users(array('search'=>'*'.$term.'*','number' => 5,'role'=>'administrator'));
                $users = apply_filters("wppm_filter_autocomplete_project_creator_name",$users,$field_slug,$term,$task_id,$project_id);
                if(!empty($users)){
                    foreach ($users as $user) {
                        $output[] = array(
                            'label' => $user->display_name,
                            'value' => $user->display_name,
                            'user_id'  => $user->ID,
                        );
                    }
                }
            }
            break;
}
if (!$output) {
    $output[] = array(
        'label'    => __('No matching data','taskbuilder'),
        'value'    => '',
        'flag_val' => '',
        'user_id'     => '',
    );
}
  
$output = apply_filters("wppm_filter_autocomplete_search_result",$output,$users,$field_slug,$term,$task_id,$project_id);
if ($output) {
    $output = array_unique($output,SORT_REGULAR);
}

if (!isset($source)) {
    echo json_encode($output);
}