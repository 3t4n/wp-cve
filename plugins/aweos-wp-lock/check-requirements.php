<?php

defined('ABSPATH') || exit;

function wplock_requirement_are_ok() {
    if (version_compare('7.1.8', phpversion(), '>')) {
        return false;
    }

    if (version_compare('4.8', get_bloginfo('version'), '>')) {
        return false;
    }

    return true;
}

function wplock_show_admin_error() {
    $wp = 'WP Lock: Please update your WordPress version to 4.8 or higher to use this plugin.';
    $php = 'WP Lock: Please update your PHP version to 7.1.33 or higher to use this plugin.';

    if (!wplock_requirement_are_ok()) {
        printf("<div class='notice notice-error'><p>$wp</p></div>");
        printf("<div class='notice notice-error'><p>$php</p></div>");
    }
}

add_action('admin_notices', 'wplock_show_admin_error');
