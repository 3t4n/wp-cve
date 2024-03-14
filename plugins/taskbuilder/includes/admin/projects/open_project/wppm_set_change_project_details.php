<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$project_id  = isset($_POST['project_id']) ? sanitize_text_field($_POST['project_id']) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('change_project_details',$project_id))) {exit;}
$project_data = $wppmfunction->get_project($project_id);
$project_label = isset($_POST['wppm_edit_project_label']) ? sanitize_text_field($_POST['wppm_edit_project_label']) : "" ;
$project_start_date = isset($_POST['wppm_edit_project_start_date']) ? sanitize_text_field($_POST['wppm_edit_project_start_date']) : "" ;
$project_end_date = isset($_POST['wppm_edit_project_end_date']) ? sanitize_text_field($_POST['wppm_edit_project_end_date']) : "" ;
$project_category = isset($_POST['wppm_edit_project_category']) ? intval(sanitize_text_field($_POST['wppm_edit_project_category'])) : "" ;
$allowed_tags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$project_description = isset($_POST['wppm_edit_project_description']) ? wp_kses(htmlspecialchars_decode($_POST['wppm_edit_project_description'], ENT_QUOTES),$allowed_tags) : "" ;

if($project_label && $project_label != $project_data['project_name'] ){
    $wppmfunction->change_project_label( $project_id, $project_label);
}

if($project_start_date && $project_start_date != $project_data['start_date'] ){
    $wppmfunction->change_project_start_date( $project_id, $project_start_date);
}

if($project_end_date && $project_end_date != $project_data['end_date'] ){
    $wppmfunction->change_project_end_date( $project_id, $project_end_date);
}

if( $project_category && $project_category != $project_data['cat_id']){
	$wppmfunction->change_category( $project_id, $project_category);
}

if( $project_description && $project_description != $project_data['description']){
	$wppmfunction->change_project_description( $project_id, $project_description);
}