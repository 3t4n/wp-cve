<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$task_id  = isset($_POST['task_id']) ? sanitize_text_field($_POST['task_id']) : '' ;
$comment  = isset($_POST['comment_body']) ? ($_POST['comment_body']) : '' ;
$allowed_tags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(), 'rel'=> array(), 'target'=> array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$comment_body = wp_kses(htmlspecialchars_decode($comment, ENT_QUOTES),$allowed_tags);
if($comment_body=="") exit;
$attachments = !empty($_POST['wppm_comment_attachment']) ? $wppmfunction->sanitize_array($_POST['wppm_comment_attachment']) : [];
$attachment_ids = implode(",",$attachments);
$vals = array('is_active' => 1);
foreach($attachments as $attach){
  $wpdb->update($wpdb->prefix.'wppm_attachments', $vals, array('id'=>$attach));
}
$args=array(
  'task_id'=> $task_id,
  'body'=>$comment_body,
  'attachment_ids'=>$attachment_ids,
  'create_time'=>current_time('mysql', 1),
  'created_by'=>$current_user->ID
);
$args = apply_filters( 'wppm_submit_comment_args', $args );
$comment_id = $wppmfunction->wppm_submit_task_comment($args);

do_action('wppm_after_submit_task_comment', $task_id,$comment_id);

