<?php
/*
Plugin Name: Flamix: Bitrix24 and Contact Form 7 integration
Plugin URI: https://flamix.solutions/bitrix24/integrations/site/cf7.php
Description: Bitrix24 and WordPress Contact Form 7 integration
Author: Roman Shkabko (Flamix)
Version: 3.0.0
Author URI: https://flamix.info
License: GPLv2
*/

defined('ABSPATH') || exit;

use Flamix\Plugin\General\Checker;
use Flamix\Plugin\Init as FlamixPlugin;
use FlamixLocal\CF7\Settings\Setting;
use FlamixLocal\CF7\Handlers;

if (version_compare(PHP_VERSION, '7.4.0') < 0) {
    add_action('admin_notices', function () { echo '<div class="error notice"><p><b>Bitrix24 and Contact Form 7 integration</b>: Upgrade your PHP version. Minimum version - 7.4+. Your PHP version ' . PHP_VERSION . '! If you don\'t know how to upgrade PHP version, just ask in your hosting provider! If you can\'t upgrade - delete this plugin!</p></div>';});
    return false;
}

include_once __DIR__ . '/includes/vendor/autoload.php';

// Register Flamix base helpers
FlamixPlugin::init(__DIR__, 'FLAMIX_BITRIX24_CF7')->setLogsPath(WP_CONTENT_DIR . '/uploads/flamix');

// Register Menu and Fields
Setting::init();

// Register handlers
if (Checker::isPluginActive('contact-form-7/wp-contact-form-7.php')) {
    // Video compatibility
    add_action('wpcf7_init', fn() => wpcf7_add_form_tag('b24_trace', fn() => ''));
    add_action('wp', [Handlers::class, 'trace']); // Save UTMs and Trace
    add_action('wpcf7_mail_sent', [Handlers::class, 'forms'], 10, 4); // Forms handle
}