<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
$wppm_task_list_view = isset($_POST) && isset(($_POST['wppm_task_list_view'])) ? sanitize_text_field($_POST['wppm_task_list_view']) : '';
update_option('wppm_default_task_list_view',$wppm_task_list_view);
$wppm_project_time = isset($_POST) && isset(($_POST['wppm_project_time'])) ? sanitize_text_field($_POST['wppm_project_time']) : '1';
update_option('wppm_project_time',$wppm_project_time);
$wppm_default_project_date = isset($_POST) && isset(($_POST['wppm_default_project_date'])) ? sanitize_text_field($_POST['wppm_default_project_date']) : '1';
update_option('wppm_default_project_date',$wppm_default_project_date);
$wppm_default_task_date = isset($_POST) && isset(($_POST['wppm_default_task_date'])) ? sanitize_text_field($_POST['wppm_default_task_date']) : '1';
update_option('wppm_default_task_date',$wppm_default_task_date);
$wppm_edit_tasks_permission = isset($_POST) && isset(($_POST['wppm_edit_tasks_permission'])) ? sanitize_text_field($_POST['wppm_edit_tasks_permission']) : '0';
update_option('wppm_default_edit_tasks_permission',$wppm_edit_tasks_permission);
$wppm_task_time = isset($_POST) && isset(($_POST['wppm_task_time'])) ? sanitize_text_field($_POST['wppm_task_time']) : '1';
update_option('wppm_task_time',$wppm_task_time);
do_action('wppm_set_general_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','taskbuilder').'" }';