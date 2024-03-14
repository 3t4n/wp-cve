<?php

if (!defined('ABSPATH')) {
    exit;
}
$scheme = parse_url(home_url())['scheme'];

define('IZCRMEF_PLUGIN_BASENAME', plugin_basename(IZCRMEF_PLUGIN_MAIN_FILE));
define('IZCRMEF_PLUGIN_BASEDIR', plugin_dir_path(IZCRMEF_PLUGIN_MAIN_FILE));
define('IZCRMEF_ROOT_URI', set_url_scheme(plugins_url('', IZCRMEF_PLUGIN_MAIN_FILE), $scheme));
define('IZCRMEF_PLUGIN_DIR_PATH', plugin_dir_path(IZCRMEF_PLUGIN_MAIN_FILE));
define('IZCRMEF_ASSET_URI', IZCRMEF_ROOT_URI . '/assets');
define('IZCRMEF_ASSET_JS_URI', IZCRMEF_ROOT_URI . '/assets/js');
// Autoload vendor files.
require_once IZCRMEF_PLUGIN_BASEDIR . 'vendor/autoload.php';
// Initialize the plugin.
FormInteg\IZCRMEF\Plugin::load();
