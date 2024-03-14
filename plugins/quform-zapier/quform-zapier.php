<?php

/**
 * Plugin Name: Quform Zapier
 * Plugin URI: https://www.quform.com/addons/zapier
 * Description: Easily integrate Zapier with Quform forms.
 * Version: 1.1.1
 * Author: ThemeCatcher
 * Author URI: https://www.themecatcher.net
 * Text Domain: quform-zapier
 */

// Prevent direct script access
if ( ! defined('ABSPATH')) {
    exit;
}

define('QUFORM_ZAPIER_VERSION', '1.1.1');
define('QUFORM_ZAPIER_PATH', dirname(__FILE__));
define('QUFORM_ZAPIER_NAME', basename(QUFORM_ZAPIER_PATH));
define('QUFORM_ZAPIER_BASENAME', QUFORM_ZAPIER_NAME . '/' . basename(__FILE__));
define('QUFORM_ZAPIER_LIBRARY_PATH', QUFORM_ZAPIER_PATH . '/library');
define('QUFORM_ZAPIER_TEMPLATE_PATH', QUFORM_ZAPIER_PATH . '/library/templates');

require_once QUFORM_ZAPIER_LIBRARY_PATH . '/Quform/Zapier/ClassLoader.php';
Quform_Zapier_ClassLoader::register();

add_action('quform_container_setup', array('Quform_Zapier', 'containerSetup'));
add_action('quform_bootstrap', array('Quform_Zapier', 'bootstrap'));
register_activation_hook(QUFORM_ZAPIER_BASENAME, array('Quform_Zapier', 'onActivation'));
