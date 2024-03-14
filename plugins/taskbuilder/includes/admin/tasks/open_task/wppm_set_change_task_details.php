<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$task_id  = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '' ;
$proj_id =  isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_task_details',$task_id))) {exit;}
$task_data = $wppmfunction->get_task($task_id);
$task_label = isset($_POST['wppm_edit_task_label']) ? sanitize_text_field($_POST['wppm_edit_task_label']) : "" ;
$project_label = isset($_POST['wppm_edit_project_label']) ? sanitize_text_field($_POST['wppm_edit_project_label']) : "" ;
$task_start_date = isset($_POST['wppm_edit_task_start_date']) ? sanitize_text_field($_POST['wppm_edit_task_start_date']) : "" ;
$task_end_date = isset($_POST['wppm_edit_task_end_date']) ? sanitize_text_field($_POST['wppm_edit_task_end_date']) : "" ;
$task_priority = isset($_POST['wppm_edit_task_priority']) ? intval(sanitize_text_field($_POST['wppm_edit_task_priority'])) : "" ;
$allowedtags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$task_description = isset($_POST['wppm_edit_task_description']) ? wp_kses(htmlspecialchars_decode($_POST['wppm_edit_task_description'], ENT_QUOTES),$allowedtags) : "" ;
$task_project = isset($_POST['wppm_task_project']) ? intval(sanitize_text_field($_POST['wppm_task_project'])) : $proj_id ;
if($task_label && $task_label != $task_data['task_name'] ){
    $wppmfunction->change_task_label( $task_id, $task_label);
}

if($project_label && $project_label != $task_data['project'] ){
    $wppmfunction->change_task_project_label( $task_id, $project_label);
}

if($task_start_date && $task_start_date != $task_data['start_date'] ){
    $wppmfunction->change_start_date( $task_id, $task_start_date);
}

if($task_end_date && $task_end_date != $task_data['end_date'] ){
    $wppmfunction->change_end_date( $task_id, $task_end_date);
}

if( $task_priority && $task_priority != $task_data['priority']){
	$wppmfunction->change_priority( $task_id, $task_priority);
}

if( $task_description && $task_description != $task_data['description']){
	$wppmfunction->change_description( $task_id, $task_description);
}

if( $task_project  && $task_project != $proj_id){
	$wppmfunction->change_project( $task_id, $task_project);
}

do_action('wppm_after_change_task_details',$task_id,$task_data['project']);