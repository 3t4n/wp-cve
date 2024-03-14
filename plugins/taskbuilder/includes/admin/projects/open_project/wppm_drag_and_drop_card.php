<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb,$current_user, $wppmfunction;

$wppm_card = isset($_POST) && isset($_POST['el']) ? sanitize_text_field($_POST['el']) : '';
if (!$wppm_card) {exit;}
$target_task_status = isset($_POST) && isset($_POST['target']) ? sanitize_text_field($_POST['target']) : '';
if (!$wppm_card) {exit;}
$wppm_card_id= substr($wppm_card, 20);
$wppm_target_task_status = substr($target_task_status,25);
$values= array(
	'status'=> $wppm_target_task_status
);
$wpdb->update($wpdb->prefix.'wppm_task',$values,array('id'=>intval($wppm_card_id))); 
echo '{ "sucess_status":"1","messege":"Success" }';