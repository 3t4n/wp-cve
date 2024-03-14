<?php

if ( ! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$table_name = $wpdb->get_blog_prefix() . 'vnr_visitors';
$sql        = "DROP TABLE IF EXISTS $table_name;";
$wpdb->query($sql);
