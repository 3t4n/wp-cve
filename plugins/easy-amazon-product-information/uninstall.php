<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
   exit();
} 

global $wpdb;
$table_name = $wpdb->prefix . 'easy_amazon_product_information_data';
$wpdb->query("DROP TABLE IF EXISTS  $table_name; ");

$table_name = $wpdb->prefix . 'options';
$prefix = "eapi_";
$wpdb->query("Delete from $table_name where option_name like '$prefix".'%'."'" );

?>