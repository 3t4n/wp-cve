<?php

/**
 * Helper file for defining the plugin constants.
 * 
 * @since       1.0.0
 * @package     Aarambha_Demo_Sites
 * @subpackage  Aarambha_Demo_Sites/Inc/Helpers
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

$plugin_bootstrap = AARAMBHA_BOOTSTRAP;

$plugin_root      = wp_normalize_path(plugin_dir_path($plugin_bootstrap));
$plugin_uri       = plugin_dir_url($plugin_bootstrap);

define('AARAMBHA_DS_VERSION', '1.1.7');

/**
 * Core constants to be overridden by individual theme.
 */

defined('AARAMBHA_DS_PLUGIN_NAME') || define('AARAMBHA_DS_PLUGIN_NAME', 'Aarambha Demo Sites');

if (!defined('AARAMBHA_DS_AUTHOR')) {
    define('AARAMBHA_DS_AUTHOR', 'Aarambha Themes');
    define('AARAMBHA_DS_AUTHOR_SLUG', 'aarambhathemes');
}

if (!defined('AARAMBHA_DS_AUTHOR_URI')) {
    define('AARAMBHA_DS_AUTHOR_URI', 'https://wordpress.org/themes/author/aarambhathemes/');
}

if (!defined('AARAMBHA_DS_API_URL')) {
    define('AARAMBHA_DS_API_URL', 'http://demo.aarambhathemes.com/wp-json/demos/v1/');
}




// Plugin internal structure.
define('AARAMBHA_DS_ROOT', $plugin_root);
define('AARAMBHA_DS_LANGUAGES', AARAMBHA_DS_ROOT . 'languages/');
define('AARAMBHA_DS_INC', AARAMBHA_DS_ROOT . 'inc/');
define('AARAMBHA_DS_MAIN', AARAMBHA_DS_INC . 'main/');
define('AARAMBHA_DS_CLASSES', AARAMBHA_DS_MAIN . 'classes/');
define('AARAMBHA_DS_IMPORTER', AARAMBHA_DS_CLASSES . 'importer/');
define('AARAMBHA_DS_UI', AARAMBHA_DS_MAIN . 'ui/');
define('AARAMBHA_DS_CONFIG', AARAMBHA_DS_INC . 'config/');
define('AARAMBHA_DS_HELPERS', AARAMBHA_DS_INC . 'helpers/');
define('AARAMBHA_DS_VIEWS', AARAMBHA_DS_INC . 'views/');

// Plugin assets urls.
define('AARAMBHA_DS_ASSETS', $plugin_uri . 'assets/');
define('AARAMBHA_DS_IMAGES', AARAMBHA_DS_ASSETS . 'images/');
define('AARAMBHA_DS_CSS', AARAMBHA_DS_ASSETS . 'css/');
define('AARAMBHA_DS_JS', AARAMBHA_DS_ASSETS . 'js/');
