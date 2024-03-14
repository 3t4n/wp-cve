<?php
global $wpdb;
if (!defined('WPLANG') || WPLANG == '') {
	define('WTBP_WPLANG', 'en_GB');
} else {
	define('WTBP_WPLANG', WPLANG);
}
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

define('WTBP_PLUG_NAME', basename(dirname(__FILE__)));
define('WTBP_DIR', WP_PLUGIN_DIR . DS . WTBP_PLUG_NAME . DS);
define('WTBP_TPL_DIR', WTBP_DIR . 'tpl' . DS);
define('WTBP_CLASSES_DIR', WTBP_DIR . 'classes' . DS);
define('WTBP_TABLES_DIR', WTBP_CLASSES_DIR . 'tables' . DS);
define('WTBP_HELPERS_DIR', WTBP_CLASSES_DIR . 'helpers' . DS);
define('WTBP_LANG_DIR', WTBP_DIR . 'languages' . DS);
define('WTBP_IMG_DIR', WTBP_DIR . 'img' . DS);
define('WTBP_TEMPLATES_DIR', WTBP_DIR . 'templates' . DS);
define('WTBP_MODULES_DIR', WTBP_DIR . 'modules' . DS);
define('WTBP_FILES_DIR', WTBP_DIR . 'files' . DS);
define('WTBP_ADMIN_DIR', ABSPATH . 'wp-admin' . DS);

define('WTBP_PLUGINS_URL', plugins_url());
define('WTBP_SITE_URL', get_bloginfo('wpurl') . '/');
define('WTBP_JS_PATH', WTBP_PLUGINS_URL . '/' . WTBP_PLUG_NAME . '/js/');
define('WTBP_CSS_PATH', WTBP_PLUGINS_URL . '/' . WTBP_PLUG_NAME . '/css/');
define('WTBP_IMG_PATH', WTBP_PLUGINS_URL . '/' . WTBP_PLUG_NAME . '/img/');
define('WTBP_MODULES_PATH', WTBP_PLUGINS_URL . '/' . WTBP_PLUG_NAME . '/modules/');
define('WTBP_TEMPLATES_PATH', WTBP_PLUGINS_URL . '/' . WTBP_PLUG_NAME . '/templates/');
define('WTBP_JS_DIR', WTBP_DIR . 'js/');

define('WTBP_URL', WTBP_SITE_URL);

define('WTBP_LOADER_IMG', WTBP_IMG_PATH . 'loading.gif');
define('WTBP_TIME_FORMAT', 'H:i:s');
define('WTBP_DATE_DL', '/');
define('WTBP_DATE_FORMAT', 'm/d/Y');
define('WTBP_DATE_FORMAT_HIS', 'm/d/Y (' . WTBP_TIME_FORMAT . ')');
define('WTBP_DATE_FORMAT_JS', 'mm/dd/yy');
define('WTBP_DATE_FORMAT_CONVERT', '%m/%d/%Y');
define('WTBP_WPDB_PREF', $wpdb->prefix);
define('WTBP_DB_PREF', 'wtbp_');
define('WTBP_MAIN_FILE', 'woo-producttables.php');

define('WTBP_DEFAULT', 'default');
define('WTBP_CURRENT', 'current');

define('WTBP_EOL', "\n");

define('WTBP_PLUGIN_INSTALLED', true);
define('WTBP_VERSION', '1.9.5');
define('WTBP_USER', 'user');

define('WTBP_CLASS_PREFIX', 'wtbpc');
define('WTBP_FREE_VERSION', false);
define('WTBP_TEST_MODE', true);

define('WTBP_SUCCESS', 'Success');
define('WTBP_FAILED', 'Failed');
define('WTBP_ERRORS', 'wtbpErrors');

define('WTBP_ADMIN', 'admin');
define('WTBP_LOGGED', 'logged');
define('WTBP_GUEST', 'guest');

define('WTBP_ALL', 'all');

define('WTBP_METHODS', 'methods');
define('WTBP_USERLEVELS', 'userlevels');
/**
 * Framework instance code
 */
define('WTBP_CODE', 'wtbp');
define('WTBP_LANG_CODE', 'woo-product-tables');
/**
 * Plugin name
 */
define('WTBP_WP_PLUGIN_NAME', 'WBW Product Table');
/**
 * Custom defined for plugin
 */
define('WTBP_SHORTCODE', 'wtbp-table-press');
/**
 * Custom defined for plugin
 */
define('WTBP_PLUG_PRO_MODULE', 'wootablespro');
