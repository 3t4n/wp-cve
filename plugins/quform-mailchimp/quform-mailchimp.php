<?php

/**
 * Plugin Name: Quform Mailchimp
 * Plugin URI: https://www.quform.com
 * Description: Easily add contacts to Mailchimp from Quform forms.
 * Version: 1.3.1
 * Author: ThemeCatcher
 * Author URI: https://www.themecatcher.net
 * Text Domain: quform-mailchimp
 */

// Prevent direct script access
if ( ! defined('ABSPATH')) {
    exit;
}

define('QUFORM_MAILCHIMP_VERSION', '1.3.1');
define('QUFORM_MAILCHIMP_PATH', dirname(__FILE__));
define('QUFORM_MAILCHIMP_NAME', basename(QUFORM_MAILCHIMP_PATH));
define('QUFORM_MAILCHIMP_BASENAME', QUFORM_MAILCHIMP_NAME . '/' . basename(__FILE__));
define('QUFORM_MAILCHIMP_LIBRARY_PATH', QUFORM_MAILCHIMP_PATH . '/library');
define('QUFORM_MAILCHIMP_TEMPLATE_PATH', QUFORM_MAILCHIMP_PATH . '/library/templates');

require_once QUFORM_MAILCHIMP_LIBRARY_PATH . '/Quform/Mailchimp/ClassLoader.php';
Quform_Mailchimp_ClassLoader::register();

add_action('quform_container_setup', array('Quform_Mailchimp', 'containerSetup'));
add_action('quform_bootstrap', array('Quform_Mailchimp', 'bootstrap'));
register_activation_hook(QUFORM_MAILCHIMP_BASENAME, array('Quform_Mailchimp', 'onActivation'));
