<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction, $wpdb;
$attachment     = isset($_POST['attachment']) ? intval(sanitize_text_field($_POST['attachment'])) : '0' ;
$comment_id = isset($_POST['comment_id']) ? intval(sanitize_text_field($_POST['comment_id'])) : '0' ;
$task_id = isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '0' ;
$task_comment = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_task_comment where id = $comment_id AND task_id=$task_id");
if(!(((!empty($task_comment)) && ($task_comment->created_by == $current_user->ID)) || ($current_user->has_cap('manage_options')) || ($wppmfunction->has_comment_permission('edit_task_comment',$task_id,$comment_id)))){
  exit;
}
$sql="SELECT * FROM {$wpdb->prefix}wppm_attachments WHERE id =".$attachment;
$result=$wpdb->get_row($sql);
$sql_query="SELECT file_path FROM {$wpdb->prefix}wppm_attachments WHERE file_path ='".$result->file_path."'";
$attach_result=$wpdb->get_results($sql_query);
$result_count = count($attach_result);
if(file_exists($result->file_path) && $result_count < 2)
{
	unlink($result->file_path);
}
$success = $wpdb->delete( 
	$wpdb->prefix . 'wppm_attachments', 
	array( 'id' => $attachment ) 
);
if( !$success ) return false;
$task_attachment = $wpdb->get_var("SELECT attachment_ids FROM {$wpdb->prefix}wppm_task_comment WHERE id=$comment_id ");
$task_attachment = explode(",",$task_attachment);
if(!empty($task_attachment)){
	foreach($task_attachment as $key=>$val){
		if($val == $attachment){
			unset($task_attachment[$key]);
		}
	}
}
$tattachment = implode(',',$task_attachment);
$values=array(
	'attachment_ids'=>$tattachment
);
$wpdb->update($wpdb->prefix.'wppm_task_comment', $values, array('id'=>$comment_id));
$attachment = apply_filters('wppm_after_delete_comment_attachment',$attachment,$comment_id,$task_id);