<?php
/**
 * Lord of the Files: Uninstall
 *
 * This script removes all plugin options and unbinds scheduled events.
 *
 * phpcs:disable SlevomatCodingStandard.Namespaces
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (! defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

// Don't leave any settings behind.
delete_option('bm_contributor_notice');
delete_option('bm_remote_contributors');
delete_option('lotf_cleanup');

// Unhook the remote contributor cronjob if necessary.
$next = wp_next_scheduled('cron_get_remote_contributors');
if ($next) {
	wp_unschedule_event($next, 'cron_get_remote_contributors');
}
