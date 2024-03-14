<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user, $wpdb,$wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$load_orders = isset($_POST) && isset($_POST['load_orders']) ? ($_POST['load_orders']) : array();
if(!empty($load_orders)){
  $load_orders = $wppmfunction->sanitize_array($load_orders);
}
$categories = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}wppm_project_categories");

if (is_array($load_orders)) {
    foreach($load_orders as $key=>$val){
      $result = $wpdb->query("UPDATE ".$wpdb->prefix."wppm_project_categories SET load_order=". $val." WHERE id=".$key );
    }
}
echo '{ "sucess_status":"1","messege":"'.__('Category order saved.','taskbuilder').'" }';