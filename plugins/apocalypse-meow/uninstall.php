<?php
/**
 * Apocalypse Meow - Uninstall
 *
 * Parting is such sweet sorrow.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

// phpcs:disable SlevomatCodingStandard.Namespaces

/**
 * Do not execute this file directly.
 */
if (! defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

// Get rid of the options we've been saving.
$options = array(
	'meow_community_give',
	'meow_db_version',
	'meow_options',
	'meow_remote_sync',
);
foreach ($options as $v) {
	delete_option($v);
}

// Try to remove the database.
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}meow2_log`");

// Remove CRON jobs.
$actions = array(
	'meow_cron_community_give',
	'meow_cron_community_receive',
	'meow_community_weighting',
	'meow_cron_prune',
);
foreach ($actions as $v) {
	if (false !== ($timestamp = wp_next_scheduled($v))) {
		wp_unschedule_event($timestamp, $v);
	}
}
