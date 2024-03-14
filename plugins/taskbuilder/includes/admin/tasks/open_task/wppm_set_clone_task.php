<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb,$current_user,$wppmfunction;

$prev_task_id  = isset($_POST['task_id']) ? sanitize_text_field($_POST['task_id']) : $ptask_id ;
$task     = $wppmfunction->get_task($prev_task_id);
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_permission('clone_task',$task['id']))) {
 exit;
}
$checklists = $wppmfunction->get_checklist($prev_task_id);
$task_project = isset($project_id) ? $project_id : $task['project'];
$task_comments = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wppm_task_comment WHERE task_id=$prev_task_id ");
$task_name    = isset($_POST['task_name']) ? stripslashes(sanitize_text_field($_POST['task_name'])) : $task['task_name'] ;
$now   = date("Y-m-d H:i:s");
$values = array(
    'created_by'=>$task['created_by'],
    'name'=>$task_name,
    'wppm_task_description'=>$task['description'],
    'wppm_task_project'=>$task_project,
    'wppm_task_start_date'=>$task['start_date'],
    'wppm_task_end_date'=>$task['end_date'],
    'status'=>$task['status'],
    'wppm_create_task_priority'=>$task['priority'],
    'user_names'=>explode(",",$task['users']),
    'date_created'=>$task['date_created'],
    'task_auth_code'=> $wppmfunction->getRandomString(10),
    'active'=>1
);
$task_id = $wppmfunction->create_task($values);
do_action('wppm_after_task_created', $task_id);
if(!empty($checklists)){
    foreach($checklists as $checklist){
        $chk_values = array(
            'task_id' => $task_id,
            'checklist_name' => $checklist['checklist_name'],
            'created_by'=> $checklist['created_by']
        );
        $checklist_id = $wppmfunction->create_checklist($chk_values);
        $checklist_items = $wppmfunction->get_checklist_items($checklist['id']);
        if(!empty($checklist_items)){
            $chk_items_values = array();
            $checklist_item_id = array();
            foreach($checklist_items as $checklist_item){
                $chk_items_values = array(
                    'checklist_id' => $checklist_id,
                    'item_name' => $checklist_item['item_name'],
                    'checked'=>$checklist_item['checked'],
                    'members'=>$checklist_item['members'],
                    'due_date'=> $checklist_item['due_date']
                );
                if(!empty($chk_items_values)){
                    $checklist_item_id[] = $wppmfunction->create_checklist_item($chk_items_values);
                }
            }
            
        }
    }
}
if(!empty($task_comments)){
    $args = array();
    foreach($task_comments as $task_comment){
        $task_comment_data = json_decode(json_encode($task_comment), true);
        $attachment_ids = $task_comment_data['attachment_ids'];
        $attachment_ids_array = explode(",",$attachment_ids);
        if(!empty($attachment_ids_array)){
            $attachment_id = array();
            $values = array();
			foreach($attachment_ids_array as $attach_id){
                if(!empty($attach_id)){
                    $attachment_data = $wppmfunction->get_attachment($attach_id);
                    $values=array(
                        'name'=>$attachment_data['name'],
                        'file_name'=> $attachment_data['file_name'],
                        'file_path'=>$attachment_data['file_path'],
                        'is_image'=>$attachment_data['is_image'],
                        'is_active'=>1,
                        'is_uploaded'=>0,
                        'date_created'=>$attachment_data['date_created']
                    );
                    if(!empty($values)){
                        $wpdb->insert($wpdb->prefix.'wppm_attachments',$values);
                        $attachment_id[] = $wpdb->insert_id;
                    }
                }
			}
		}
        $args = array(
            'task_id'=>$task_id,
            'body'=>$task_comment_data['body'],
            'attachment_ids' =>implode(',',$attachment_id) ,
            'create_time'=>$task_comment_data['create_time'],
            'created_by'=>$task_comment_data['created_by']
        ); 
        if(!empty($args)){
            $comment_id[] = $wppmfunction->wppm_submit_task_comment($args);
        }
    }
}
do_action("wppm_after_task_clone",$prev_task_id,$task_id);