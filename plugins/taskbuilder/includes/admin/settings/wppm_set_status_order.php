<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user, $wpdb, $wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}
$load_orders = isset($_POST) && isset($_POST['load_orders']) ? $wppmfunction->sanitize_array($_POST['load_orders']) : array();
$statuses = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}wppm_project_statuses");
if (is_array($load_orders)) {
    foreach($load_orders as $key=>$val){
      $result = $wpdb->query("UPDATE ".$wpdb->prefix."wppm_project_statuses SET load_order=". $val." WHERE id=".$key );
    }
}
echo '{ "sucess_status":"1","messege":"'.__('Status order saved.','taskbuilder').'" }';