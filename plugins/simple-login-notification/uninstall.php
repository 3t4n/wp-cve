<?php // Uninstall Simple Login Notification

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

delete_option('simple_login_notification_options');