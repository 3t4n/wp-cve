<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;

$checklist_id = isset($_POST) && isset($_POST['checklist_id']) ? intval(sanitize_text_field($_POST['checklist_id'])) : '';
if (!$checklist_id) {exit;}

$wpdb->delete($wpdb->prefix.'wppm_checklist_items', array( 'checklist_id' => $checklist_id));
$wpdb->delete($wpdb->prefix.'wppm_checklist', array( 'id' => $checklist_id));

