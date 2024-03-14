<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$status_id = isset($_POST) && isset($_POST['status_id']) ? intval(sanitize_text_field($_POST['status_id'])) : 0;
if (!$status_id) {exit;}

$wpdb->delete($wpdb->prefix.'wppm_task_statuses', array( 'id' => $status_id));
