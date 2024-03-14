<?php
// Snapshot Backup uninstall script
// deletes all database options when plugin is removed
// @since 1.6
// 
// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ait-pro.com for this  code 
// @since 2.1

if ( !function_exists('add_action') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

if ( !current_user_can('manage_options') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}
// if uninstall is not called from WordPress then exit
if (!defined('WP_UNINSTALL_PLUGIN')) exit();

// delete all options
    delete_option ('snapshot_ftp_host');
    delete_option ('snapshot_ftp_port');
    delete_option ('snapshot_ftp_user');
    delete_option ('snapshot_ftp_pass');
    delete_option ('snapshot_ftp_subdir');
	delete_option ('snapshot_ftp_prefix');
	delete_option ('snapshot_add_dir1');
	delete_option ('snapshot_latest');
	delete_option ('snapshot_auto_interval');
	delete_option ('snapshot_auto_email');
	delete_option ('snapshot_repo_amount');
	
	// @since 2.1
	// UNSCHEDULING OUR EVENTS
	// once the option is switched, we'll unschedule what's been setup previously
	// find out when that was
	$timestamp = wp_next_scheduled ('snapshot_automation');
	// unschedule previous event if any
	wp_unschedule_event ($timestamp, 'snapshot_automation');

// Thanks for using Snapshot Backup
// If you'd like to try again someday check out http://wpguru.tv where it lives and grows
?>