<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user, $wpdb;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$priority_id = isset($_POST) && isset($_POST['priority_id']) ? intval(sanitize_text_field($_POST['priority_id'])) : '';
if (!$priority_id) {exit;}

$priority_name = isset($_POST) && isset($_POST['priority_name']) ? sanitize_text_field($_POST['priority_name']) : '';
if (!$priority_name) {exit;}

$priority_color = isset($_POST) && isset($_POST['priority_color']) ? sanitize_text_field($_POST['priority_color']) : '';
if (!$priority_color) {exit;}

$priority_bg_color = isset($_POST) && isset($_POST['priority_bg_color']) ? sanitize_text_field($_POST['priority_bg_color']) : '';
if (!$priority_bg_color) {exit;}

if ($priority_color==$priority_bg_color) {
  echo '{ "sucess_status":"0","messege":"'.__('Priority color and background color should not be same.','taskbuilder').'" }';
  die();
}
$values= array(
  'name'=>$priority_name,
  'color'=>$priority_color,
  'bg_color'=>$priority_bg_color
);
$wpdb->update($wpdb->prefix.'wppm_task_priorities',$values,array('id'=>intval($priority_id))); 
echo '{ "sucess_status":"1","messege":"Success" }';
?>