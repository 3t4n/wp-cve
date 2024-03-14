<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$priority_id = isset($_POST) && isset($_POST['priority_id']) ? intval(sanitize_text_field($_POST['priority_id'])) : 0;
if (!$priority_id) {exit;}

$wpdb->delete($wpdb->prefix.'wppm_task_priorities', array( 'id' => $priority_id));
