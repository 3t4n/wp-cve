<?php defined('ABSPATH') or die;

/*
Plugin Name: Paymattic - Payments made simple
Description: Create and Accept Payments in minutes with Stripe, PayPal & other top gateways with built-in form builder
Version: 4.5.1
Author: WPManageNinja LLC
Author URI: https://paymattic.com
Plugin URI: https://paymattic.com/
License: GPLv2 or later
Domain Path: /language
Text Domain: wp-payment-form
*/

if (!defined('WPPAYFORM_VERSION')) {
    define('WPPAYFORM_VERSION_LITE', true);
    define('WPPAYFORM_VERSION', '4.5.1');
    define('WPPAYFORM_DB_VERSION', 120);
    // Stripe API version should be in 'YYYY-MM-DD' format.
    define('WPPAYFORM_STRIPE_API_VERSION', '2019-05-16');
    define('WPPAYFORM_MAIN_FILE', __FILE__);
    define('WPPAYFORM_URL', plugin_dir_url(__FILE__));
    define('WPPAYFORM_DIR', plugin_dir_path(__FILE__));
    if (!defined('WPPAYFORM_UPLOAD_DIR')) {
        define('WPPAYFORM_UPLOAD_DIR', '/wppayform');
    }

    require __DIR__ . '/vendor/autoload.php';

    call_user_func(function ($bootstrap) {
        $bootstrap(__FILE__);
    }, require(__DIR__ . '/boot/app.php'));
}