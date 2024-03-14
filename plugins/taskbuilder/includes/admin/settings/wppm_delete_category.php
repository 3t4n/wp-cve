<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$cat_id = isset($_POST) && isset($_POST['cat_id']) ? intval(sanitize_text_field($_POST['cat_id'])) : 0;
if (!$cat_id) {exit;}

$wpdb->delete($wpdb->prefix.'wppm_project_categories', array( 'id' => $cat_id));


