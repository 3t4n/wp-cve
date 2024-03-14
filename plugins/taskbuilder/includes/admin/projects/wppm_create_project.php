<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb, $wppmfunction;
// project name
$name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
if($name) $args['name'] = $name;

// Description
$allowedtags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$project_description = isset($_POST['wppm_proj_description']) ? wp_kses(htmlspecialchars_decode($_POST['wppm_proj_description'], ENT_QUOTES),$allowedtags) : '';
if($project_description) $args['wppm_proj_description'] = $project_description;

//Project start date
$text = isset($_POST['wppm_start_date']) ? sanitize_text_field($_POST['wppm_start_date']) : '';
if($text) $args['wppm_start_date'] = date("Y-m-d H:i:s" ,strtotime($text));

//Project end date
$text2 = isset($_POST['wppm_end_date']) ? sanitize_text_field($_POST['wppm_end_date']) : '';
if($text2) $args['wppm_end_date'] = date("Y-m-d H:i:s" ,strtotime($text2));

// Category
$project_category = isset($_POST['wppm_create_project_category']) ? intval(sanitize_text_field($_POST['wppm_create_project_category'])) : '';
if($project_category) $args['wppm_create_project_category'] = $project_category;

//Assign user
$arrVal = isset($_POST['user_names']) ? array_unique($_POST['user_names']) : array();
if($arrVal) $args['user_names'] = $wppmfunction->sanitize_array($arrVal);

//public project
$public_proj = isset($_POST['wppm_public_project']) ?  1 : 0;

$args = apply_filters( 'wppm_before_create_project_args', $args);

$project_id = WPPM_Functions::create_project($args);
$wppmfunction->add_project_meta($project_id,'public_project',$public_proj);

do_action('wppm_after_project_created',$project_id);
