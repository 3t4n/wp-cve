<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('assign_task_users',$task_id))) {exit;}
$task_data = $wppmfunction->get_task($task_id);
$users = "";
if(!empty($_POST['user_names'])){
  $users = $wppmfunction->sanitize_array($_POST['user_names']);
  $users = array_unique($users);
  $users = implode(",",$users);
}
$prev_assign_users = $task_data['users'];
if(isset($prev_assign_users)){
  $prev_assign_users = explode(",",$prev_assign_users);
}
$prev_assign_user_meta = $wppmfunction->get_task_meta($task_id,'prev_assigned_task_users');
$wppmfunction->delete_task_meta($task_id,'prev_assigned_task_users');

if(!empty($prev_assign_users)){
  foreach( $prev_assign_users as $ass_user){
    $wppmfunction->add_task_meta($task_id,'prev_assigned_task_users',$ass_user);
  }
}
if(isset($users)){
  $values=array(
    'users'=> $users
  );
}else{
  $values=array(
    'users'=> ''
  );
}
if(($users != $task_data['users'])){
  $wpdb->update($wpdb->prefix.'wppm_task', $values, array('id'=>$task_id));
  do_action('wppm_set_task_users', $task_id);
}