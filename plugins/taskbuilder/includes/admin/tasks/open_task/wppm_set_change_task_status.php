<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user,$wppmfunction,$wpdb;

$task_id    = isset($_POST['task_id'])  ? sanitize_text_field($_POST['task_id']) : '';
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_status',$task_id))) {exit;}

$status_id   = isset($_POST['wppm_status']) ? intval(sanitize_text_field($_POST['wppm_status'])) : 0 ;
if( !$status_id ){
  die();
}
$task_data = $wppmfunction->get_task($task_id);
$old_status_id   	= $task_data['status'];
if($status_id && $status_id!=$old_status_id){
	$wppmfunction->change_status( $task_id, $status_id);
}
do_action('wppm_after_set_change_task_status',$task_id,$status_id,$old_status_id);
