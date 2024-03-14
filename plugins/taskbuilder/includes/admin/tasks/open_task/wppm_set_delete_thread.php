<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction ,$wpdb;
$task_id  = isset($_POST['task_id']) ? sanitize_text_field($_POST['task_id']) : 0 ;
$thread_id  = isset($_POST['comment_id']) ? sanitize_text_field($_POST['comment_id']) : 0 ;
$task_comment = $wppmfunction->get_task_comment($thread_id);
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_comment_permission('delete_task_thread',$task_id,$thread_id))) {exit;}
$sql="SELECT attachment_ids FROM {$wpdb->prefix}wppm_task_comment WHERE id =".$thread_id;
$thread_attachment_ids = $wpdb->get_results( $sql );
if(!empty($thread_attachment_ids) ){
	foreach ($thread_attachment_ids as $thread_attachment_id){
		$attachment_ids_temp=array();
		if($thread_attachment_id->attachment_ids){
			$attachment_ids_temp =  explode(',', $thread_attachment_id->attachment_ids);
		}
		$attachment_ids=$attachment_ids_temp;
		if(!empty($attachment_ids_temp)){
			foreach ($attachment_ids_temp as $attachment_id){
				$sql="SELECT * FROM {$wpdb->prefix}wppm_attachments WHERE id =".$attachment_id;
				$result=$wpdb->get_row($sql);
				if(!empty($result)){
					$sql_query="SELECT file_path FROM {$wpdb->prefix}wppm_attachments WHERE file_path ='".$result->file_path."'";
					$attach_result=$wpdb->get_results($sql_query);
					$result_count = count($attach_result);
					if(file_exists($result->file_path) && $result_count < 2)
					{
						unlink($result->file_path);
					}
					$wpdb->delete($wpdb->prefix.'wppm_attachments', array( 'id' => $attachment_id));
				}
			}
		}
	}
}
$wpdb->delete($wpdb->prefix.'wppm_task_comment',array('id'=>$thread_id));

?>