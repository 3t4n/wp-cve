<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user,$wppmfunction,$wpdb;

$project_id    = isset($_POST['project_id'])  ? sanitize_text_field($_POST['project_id']) : '';
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('change_project_status',$project_id))) {exit;}

$status_id   = isset($_POST['wppm_project_status']) ? intval(sanitize_text_field($_POST['wppm_project_status'])) : 0 ;
if( !$status_id ){
  die();
}
$project_data = $wppmfunction->get_project($project_id);
$old_status_id   	= $project_data['status'];

if($status_id && $status_id!=$old_status_id){
	$wppmfunction->change_project_status( $project_id, $status_id);
}
do_action('wppm_after_set_change_project_status',$project_id, $status_id, $old_status_id);