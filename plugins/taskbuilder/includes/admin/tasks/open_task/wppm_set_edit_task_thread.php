<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$task_id  = isset($_POST['task_id']) ? sanitize_text_field($_POST['task_id']) : '' ;
$comment_id  = isset($_POST['comment_id']) ? sanitize_text_field($_POST['comment_id']) : '' ;
$task_comment = $wppmfunction->get_task_comment($comment_id);
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('change_task_details',$task_id) || $current_user->ID == $task_comment['created_by'])) {exit;}
$task_comment_data = $wppmfunction->get_task_comment($comment_id);
$old_comment_body = $task_comment_data['body'];
$allowed_tags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$new_task_comment = isset($_POST['wppm_edit_task_thread']) ? wp_kses(htmlspecialchars_decode($_POST['wppm_edit_task_thread'], ENT_QUOTES),$allowed_tags) : "" ;

if($new_task_comment && $old_comment_body != $new_task_comment ){
    $wppmfunction->change_task_comment($comment_id, $new_task_comment);
}