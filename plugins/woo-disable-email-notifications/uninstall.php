<?php
/**
 * Woo Disable Email Notification Uninstall
 *
 * Uninstalling Woo Disable Email Notification deletes options saved in database.
 *
 * @version 1.0.0
 * @package Woo Disable Email Notification\Uninstaller
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'woo-disable-email-notifications' );

// Clear any cached data that has been removed.
wp_cache_flush();