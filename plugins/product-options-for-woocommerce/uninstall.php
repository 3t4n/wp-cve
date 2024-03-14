<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

global $wpdb;

$wpdb->query("DROP TABLE IF EXISTS {$wpdb->base_prefix}pofw_product_option");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->base_prefix}pofw_product_option_value");
