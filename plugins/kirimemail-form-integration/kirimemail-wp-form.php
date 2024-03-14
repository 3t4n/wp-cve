<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin Name: KIRIM.EMAIL Form Integration
 * Plugin URI: https://kirim.email/worpdress-plugins
 * Description: A plugin for inserting KIRIM.EMAIL Form into your Wordpress Page or Post
 * Author: Kirim.Email
 * Author URI: https://kirim.email
 * Version: 1.4.0
 * Copyright: (c) 2021
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 4.4
 * Text Domain: kirimemail_wpform
 * Domain Path: i18n/language
 */
define('KE_PATH', __DIR__);
require_once __DIR__ . '/vendor/autoload.php';

$loader = new josegonzalez\Dotenv\Loader([
    __DIR__ . '/.env',
    __DIR__ . '/.env.default',
]);
$loader->parse()->putenv(true);

// Define KE_PLUGIN_FILE.
if (!defined('KIRIMEMAIL_WPFORM_PLUGIN_FILE')) {
    define('KIRIMEMAIL_WPFORM_PLUGIN_FILE', __FILE__);
}
if (!defined('KIRIMEMAIL_API_URL')) {
    define('KIRIMEMAIL_API_URL', getenv('KIRIMEMAIL_API_URL'));
}
if (!defined('KIRIMEMAIL_APP_URL')) {
    define('KIRIMEMAIL_APP_URL', getenv('KIRIMEMAIL_APP_URL'));
}

defined('WPINC') || die;
// Include the main Kirimemail class.
if (!class_exists('Kirimemail_Wordpress_Form')) {
    require_once __DIR__ . '/includes/class-kirimemail-wordpress-form.php';
}

/**
 * Main instance of Kiriemail Wordpress Form
 *
 * Returns the main instance of Kirimemail to prevent the need to use globals.
 *
 * @return Kirimemail_Wordpress_Form
 */
function keWP()
{
    return Kirimemail_Wordpress_Form::instance();
}

// Global for backwards compatibility.
$GLOBALS['kirimemail_wordpress_form'] = keWP();
