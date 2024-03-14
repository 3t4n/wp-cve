<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php
$currentDir = dirname(__FILE__);

define('_ABH_NAMESPACE_', 'ABH'); //THIS LINE WILL BE CHANGED WITH THE USER SETTINGS
define('_ABH_PLUGIN_NAME_', 'starbox'); //THIS LINE WILL BE CHANGED WITH THE USER SETTINGS
define('_ABH_THEME_NAME_', 'admin'); //THIS LINE WILL BE CHANGED WITH THE USER SETTINGS

/* Directories */
define('_ABH_ROOT_DIR_', realpath(dirname($currentDir)) . '/');
define('_ABH_CLASSES_DIR_', _ABH_ROOT_DIR_ . '/classes/');
define('_ABH_CONTROLLER_DIR_', _ABH_ROOT_DIR_ . '/controllers/');
define('_ABH_MODEL_DIR_', _ABH_ROOT_DIR_ . '/models/');
define('_ABH_TRANSLATIONS_DIR_', _ABH_ROOT_DIR_ . '/translations/');
define('_ABH_CORE_DIR_', _ABH_ROOT_DIR_ . '/core/');
define('_ABH_ALL_THEMES_DIR_', _ABH_ROOT_DIR_ . '/themes/');
define('_ABH_THEME_DIR_', _ABH_ROOT_DIR_ . '/themes/' . _ABH_THEME_NAME_ . '/');

/* URLS */
define('_ABH_URL_', rtrim(plugins_url('', $currentDir), '/') . '/');
define('_ABH_ALL_THEMES_URL_', _ABH_URL_ . 'themes/');
define('_ABH_THEME_URL_', _ABH_URL_ . 'themes/' . _ABH_THEME_NAME_ . '/');

if (!defined('UPLOADS')) {
    $basedir = WP_CONTENT_DIR . '/uploads/gravatar';
    $baseurl = rtrim(content_url(), '/') . '/uploads/gravatar';
} else {
    $basedir = rtrim(ABSPATH, '/') . '/' . trim(UPLOADS, '/') . '/gravatar';
    $baseurl = home_url() . '/' . trim(UPLOADS, '/') . '/gravatar';
}

if (!is_dir($basedir)) {
    @wp_mkdir_p($basedir);
}

if (!is_dir($basedir) || !function_exists('wp_is_writable') || !wp_is_writable($basedir)) {
    $basedir = _ABH_ROOT_DIR_ . 'cache';
    $baseurl = _ABH_URL_ . 'cache';
}

defined('_ABH_GRAVATAR_DIR_') || define('_ABH_GRAVATAR_DIR_', $basedir . '/');
defined('_ABH_GRAVATAR_URL_') || define('_ABH_GRAVATAR_URL_', $baseurl . '/');