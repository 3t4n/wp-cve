<?php
defined('ABSPATH') || die('Cheatin\' uh?');

$currentDir = dirname(__FILE__);

define('RKMW_NAME', 'Rank My WP');
define('RKMW_NAMESPACE', 'RKMW');
define('RKMW_PLUGIN_NAME', 'rank-my-wp'); //THIS LINE WILL BE CHANGED WITH THE USER SETTINGS

defined('RKMW_SSL') || define('RKMW_SSL', (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") || (defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN) || (function_exists('is_ssl') && is_ssl())) ? 'https:' : 'http:')); //CHECK SSL
defined('RKMW_CHECK_SSL') || define('RKMW_CHECK_SSL', RKMW_SSL);
defined('RKMW_DASH_URL') || define('RKMW_DASH_URL', 'https://cloud.rankmywp.com/');
defined('RKMW_API_URL') || define('RKMW_API_URL', RKMW_SSL . '//api.rankmywp.com/');
define('RKMW_SITE_HOST', parse_url(home_url(), PHP_URL_HOST));

define('RKMW_SUPPORT_EMAIL', 'support@rankmywp.com');
defined('RKMW_HOWTO_URL') || define('RKMW_HOWTO_URL', 'https://howto.rankmywp.com/');
defined('RKMW_SUPPORT_URL') || define('RKMW_SUPPORT_URL', 'https://howto.rankmywp.com/contact/');

/* Directories */
define('RKMW_ROOT_DIR', realpath(dirname($currentDir)) . '/');
define('RKMW_CLASSES_DIR', RKMW_ROOT_DIR . 'classes/');
define('RKMW_CONTROLLER_DIR', RKMW_ROOT_DIR . 'controllers/');
define('RKMW_MODEL_DIR', RKMW_ROOT_DIR . 'models/');
define('RKMW_SERVICE_DIR', RKMW_MODEL_DIR . 'services/');
define('RKMW_TRANSLATIONS_DIR', RKMW_ROOT_DIR . 'translations/');
define('RKMW_CORE_DIR', RKMW_ROOT_DIR . 'core/');
define('RKMW_THEME_DIR', RKMW_ROOT_DIR . 'view/');
define('RKMW_ASSETS_DIR', RKMW_THEME_DIR . 'assets/');

/* URLS */
define('RKMW_URL', rtrim(plugins_url('', $currentDir), '/') . '/');
define('RKMW_THEME_URL', RKMW_URL . 'view/');
define('RKMW_ASSETS_URL', RKMW_THEME_URL . 'assets/');
define('RKMW_ASSETS_RELATIVE_URL', ltrim(parse_url(RKMW_ASSETS_URL, PHP_URL_PATH), '/'));


$upload_dir = array();
$upload_dir['baseurl'] = WP_CONTENT_URL . '/uploads';
$upload_dir['basedir'] = WP_CONTENT_DIR . '/uploads';

if (!defined('UPLOADS')) {
    $basedir = WP_CONTENT_DIR . '/uploads/' . RKMW_NAME;
    $baseurl = rtrim(content_url(), '/') . '/uploads/' . RKMW_NAME;
} else {
    $basedir = rtrim(ABSPATH, '/') . '/' . trim(UPLOADS, '/') . '/' . RKMW_NAME;
    $baseurl = home_url() . '/' . trim(UPLOADS, '/') . '/' . RKMW_NAME;
}

if (!is_dir($basedir)) {
    @wp_mkdir_p($basedir);
}

if (!is_dir($basedir) || !function_exists('wp_is_writable') || !wp_is_writable($basedir)) {
    $basedir = RKMW_ROOT_DIR . 'cache';
    $baseurl = RKMW_URL . 'cache';
}

defined('RKMW_CACHE_DIR') || define('RKMW_CACHE_DIR', $basedir . '/');
defined('RKMW_CACHE_URL') || define('RKMW_CACHE_URL', $baseurl . '/');

