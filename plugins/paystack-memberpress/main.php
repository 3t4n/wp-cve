<?php

/**
 * Plugin Name: MemberPress Paystack
 * Plugin URI: https://wordpress.org/plugins/paystack-memberpress/
 * Description: Paystack integration for MemberPress.
 * Version: 1.3.3
 * Author: Paystack
 * Author URI: https://paystack.com/
 * Developer: Wisdom Ebong
 * Developer URI: https://wisdomebong.com/
 * Text Domain: paystack-memberpress
 * License: GPLv2 or later
 * Copyright: 2020, Paystack.
 */

if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (is_plugin_active('memberpress/memberpress.php')) {
    define('MP_PAYSTACK_PLUGIN_SLUG', 'paystack-memberpress/main.php');
    define('MP_PAYSTACK_PLUGIN_NAME', 'paystack-memberpress');
    define('MP_PAYSTACK_EDITION', MP_PAYSTACK_PLUGIN_NAME);
    define('MP_PAYSTACK_PATH', WP_PLUGIN_DIR . '/' . MP_PAYSTACK_PLUGIN_NAME);

    $mp_paystack_url_protocol = (is_ssl()) ? 'https' : 'http'; // Make all of our URLS protocol agnostic
    define('MP_PAYSTACK_URL', preg_replace('/^https?:/', "{$mp_paystack_url_protocol}:", plugins_url('/' . MP_PAYSTACK_PLUGIN_NAME)));
    define('MP_PAYSTACK_JS_URL', MP_PAYSTACK_URL . '/js');
    define('MP_PAYSTACK_IMAGES_URL', MP_PAYSTACK_URL . '/images');

    // Load Memberpress Base Gateway
    require_once(MP_PAYSTACK_PATH . '/../memberpress/app/lib/MeprBaseGateway.php');
    require_once(MP_PAYSTACK_PATH . '/../memberpress/app/lib/MeprBaseRealGateway.php');

    // Load Memberpress Paystack API
    require_once(MP_PAYSTACK_PATH . '/MeprPaystackAPI.php');

    // Load Memberpress Paystack Addon
    require_once(MP_PAYSTACK_PATH . '/MpPaystack.php');

    new MpPaystack;
}
