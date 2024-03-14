<?php // Uninstall Plugin

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

delete_option('banhammer_settings');
delete_option('banhammer_armory');
delete_option('banhammer_tower');
delete_option('banhammer_secret_key');

$timestamp = wp_next_scheduled('banhammer_cron_reset');
wp_unschedule_event($timestamp, 'banhammer_cron_reset');
wp_clear_scheduled_hook('banhammer_cron_reset');

global $wpdb;
$table = $wpdb->prefix .'banhammer';
$wpdb->query('DROP TABLE IF EXISTS '. $table);