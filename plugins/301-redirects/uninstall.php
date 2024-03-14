<?php
//if uninstall not called from WordPress exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit();
}

global $wpdb;
$redirect_table = $wpdb->prefix . 'ts_redirects';
$wpdb->query('DROP TABLE IF EXISTS ' . $redirect_table);

delete_option('301_redirects_404_log');
