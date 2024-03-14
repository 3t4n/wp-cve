<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;
$task_id = isset($_POST) && isset($_POST['task_id']) ? intval(sanitize_text_field($_POST['task_id'])) : '';
if (!$task_id) {exit;}

$checklist_name = isset($_POST) && isset($_POST['checklist_name']) ? sanitize_text_field($_POST['checklist_name']) : '';
if (!$checklist_name) {exit;}

$values = array(
    'task_id'=>$task_id,
    'checklist_name'=>$checklist_name,
    'created_by'=>$current_user->ID
);
$wpdb->insert($wpdb->prefix .'wppm_checklist', $values);