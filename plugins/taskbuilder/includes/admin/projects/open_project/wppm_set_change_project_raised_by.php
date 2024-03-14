<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user,$wppmfunction,$wpdb;
$project_id = isset($_POST['project_id'])  ? intval(sanitize_text_field($_POST['project_id'])) : '';

if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('change_project_raised_by',$project_id))) {exit;}

$user_id = isset($_POST['wppm_user_id']) ? intval(sanitize_text_field($_POST['wppm_user_id'])) : 0 ;
$project_data = $wppmfunction->get_project($project_id);
$old_user_id  = $project_data['created_by'];

if ( $user_id != $old_user_id ){
	$wppmfunction->change_project_raised_by($project_id, $user_id);
}