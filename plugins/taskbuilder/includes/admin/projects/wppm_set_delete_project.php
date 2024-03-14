<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction ,$wpdb;
$project_id  = isset($_POST['project_id']) ? sanitize_text_field($_POST['project_id']) : 0 ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('delete_project',$project_id))) {exit;}
$tasks = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_task where project = $project_id");
/*************delete project********/
    $wpdb->delete($wpdb->prefix.'wppm_project',array('id'=>$project_id));

/*************delete project's tasks and comments********/
foreach($tasks as $task){
    $sql="SELECT attachment_ids FROM {$wpdb->prefix}wppm_task_comment WHERE task_id =".$task->id;
    $thread_attachment_ids= $wpdb->get_results( $sql );

    /***************************Code for deleting attachment files****************************************************/
    if(!empty($thread_attachment_ids)){
        foreach ($thread_attachment_ids as $thread_attachment_id){
            $attachment_ids_temp=array();
            if($thread_attachment_id->attachment_ids){
                $attachment_ids_temp=  explode(',', $thread_attachment_id->attachment_ids);
            }
            //$attachment_ids=$attachment_ids_temp;
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
    /***************************Code for deleting checklists****************************************************/
    $sql="SELECT id FROM {$wpdb->prefix}wppm_checklist WHERE task_id =".$task->id;
    $checklists = $wpdb->get_results( $sql );
    if(!empty($checklists)){
        foreach($checklists as $checklist){
            $chk_array = (array) $checklist;
            $sql="SELECT id FROM {$wpdb->prefix}wppm_checklist_items WHERE checklist_id =".$chk_array['id'];
            $checklist_items = $wpdb->get_results( $sql );
            if(!empty($checklist_items)){
                foreach($checklist_items as $ch_item){
                    $chk_items_array = (array) $ch_item;
                    $wpdb->delete($wpdb->prefix.'wppm_checklist_items',array('id'=>$chk_items_array['id']));
                }
            }
            $wpdb->delete($wpdb->prefix.'wppm_checklist',array('id'=>$chk_array['id']));
        }
    }
    $wpdb->delete($wpdb->prefix.'wppm_task',array('project'=>$project_id));
    $wpdb->delete($wpdb->prefix.'wppm_task_comment',array('task_id'=>$task->id));
}
