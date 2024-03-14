<?php
/*
Plugin Name: IS-theme-companion
Plugin URI:
Description: Enhances themes with extra functionality for Infigo Software Themes.
Author: vibhorp
Author URI: www.infigosoftware.in
Version: 1.54
Text Domain: WL_COMPANION
Domain Path: /lang/
*/

defined('ABSPATH') or die();

if (!defined('WL_COMPANION_DOMAIN')) {
    define('WL_COMPANION_DOMAIN', 'WL_COMPANION');
}

if (!defined('WL_COMPANION_PLUGIN_URL')) {
    define('WL_COMPANION_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('WL_COMPANION_PLUGIN_DIR_PATH')) {
    define('WL_COMPANION_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
}

if (!defined('WL_COMPANION_PLUGIN_BASENAME')) {
    define('WL_COMPANION_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

if (!defined('WL_COMPANION_PLUGIN_FILE')) {
    define('WL_COMPANION_PLUGIN_FILE', __FILE__);
}

final class WL_COMPANION_MAIN {
    private static $instance = null;

    private function __construct() {
        $this->initialize_hooks();
        $this->setup_init();
    }

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initialize_hooks() {
        require_once('admin/admin.php');
        require_once('public/public.php');
    }

    private function setup_init() {
        require(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/set-home.php');
        register_activation_hook(__FILE__, array('wl_set_home_page', 'wl_companion_install_function'));
    }
}
WL_COMPANION_MAIN::get_instance();
