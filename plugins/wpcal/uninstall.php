<?php
/**
 * WPCal.io
 * Copyright (c) 2021 Revmakx LLC
 * revmakx.com
 */

defined('WP_UNINSTALL_PLUGIN') || exit;

global $wpdb;

if (defined('WPCAL_REMOVE_ALL_DATA') && WPCAL_REMOVE_ALL_DATA === true) {

	//delete options
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'wpcal\_%'");

	//delete usermeta
	$wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'wpcal\_%'");

	//drop tables
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_admins`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_availability_dates`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_availability_periods`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_background_tasks`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_bookings`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_calendars`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_calendar_accounts`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_calendar_events`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_notices`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_services`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_service_admins`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_service_availability`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_service_availability_slots_cache`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_tp_accounts`");
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}wpcal_tp_resources`");
}
