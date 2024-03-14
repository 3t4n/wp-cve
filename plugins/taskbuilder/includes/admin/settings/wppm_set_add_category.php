<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$cat_name = isset($_POST) && isset($_POST['cat_name']) ? sanitize_text_field($_POST['cat_name']) : '';
if (!$cat_name) {exit;}
$load_order = $wpdb->get_var("select max(load_order) from {$wpdb->prefix}wppm_project_categories");

$values=array(
	'name'=>sanitize_text_field($_POST['cat_name']),
	'load_order'=> ++$load_order
);
$wpdb->insert($wpdb->prefix.'wppm_project_categories',$values);
echo '{ "sucess_status":"1","messege":"'.__('Category added successfully.','taskbuilder').'" }';

