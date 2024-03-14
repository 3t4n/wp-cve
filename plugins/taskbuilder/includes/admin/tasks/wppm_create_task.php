
<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;

// Task name
$name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
if($name) $args['name'] = $name;

// Description
$allowedtags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$task_description = isset($_POST['wppm_task_description']) ? wp_kses(htmlspecialchars_decode($_POST['wppm_task_description'], ENT_QUOTES),$allowedtags) : '';
if($task_description) $args['wppm_task_description'] = $task_description;

//project
$wppm_task_project = isset($_POST['wppm_task_project']) ? intval(sanitize_text_field($_POST['wppm_task_project'])) : '';
if($wppm_task_project) $args['wppm_task_project'] = $wppm_task_project;

//Task start date
$text = isset($_POST['wppm_task_start_date']) ? sanitize_text_field($_POST['wppm_task_start_date']) : '';
if($text) $args['wppm_task_start_date'] = date("Y-m-d H:i:s" ,strtotime($text));

//Task end date
$text2 = isset($_POST['wppm_task_end_date']) ? sanitize_text_field($_POST['wppm_task_end_date']) : '';
if($text2) $args['wppm_task_end_date'] = date("Y-m-d H:i:s" ,strtotime($text2));

// Priority
$task_priority = isset($_POST['wppm_create_task_priority']) ? intval(sanitize_text_field($_POST['wppm_create_task_priority'])) : '';
if($task_priority) $args['wppm_create_task_priority'] = $task_priority;

//Assign user
$arrVal = isset($_POST['user_names']) ? array_unique($_POST['user_names']) : array();
if($arrVal) $args['user_names'] = $wppmfunction->sanitize_array($arrVal);

$args = apply_filters( 'wppm_before_create_task_args', $args);

$task_id = WPPM_Functions::create_task($args);
do_action('wppm_after_task_created', $task_id);
