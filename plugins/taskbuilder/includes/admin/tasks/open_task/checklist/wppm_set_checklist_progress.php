<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;

$checklist_id = isset($_POST) && isset($_POST['checklist_id']) ? intval(sanitize_text_field($_POST['checklist_id'])) : '';
if (!$checklist_id) {exit;}
$item_id = isset($_POST) && isset($_POST['item_id']) ? intval(sanitize_text_field($_POST['item_id'])) : '';
if (!$item_id) {exit;}
$checked_item = isset($_POST) && isset($_POST['checked_item']) ? intval(sanitize_text_field($_POST['checked_item'])) : '';
$values= array(
    'checked'=>$checked_item
);
$wpdb->update($wpdb->prefix.'wppm_checklist_items',$values,array('id'=>intval($item_id))); 
echo '{ "sucess_status":"1","messege":"Success" }';