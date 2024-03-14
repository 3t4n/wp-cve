<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user,$wppmfunction,$wpdb;
$task_id = isset($_POST['task_id'])  ? sanitize_text_field($_POST['task_id']) : '';

if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_raised_by',$task_id))) {exit;}

$user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : 0 ;
$task_data = $wppmfunction->get_task($task_id);
$old_user_id  = $task_data['created_by'];

if ( $user_id != $old_user_id ){
	$wppmfunction->change_raised_by($task_id, $user_id);
}