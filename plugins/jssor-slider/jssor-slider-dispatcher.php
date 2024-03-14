<?php
/**
 * jssor slider controller.
 *
 * load config file
 * load module
 * process request
 *
 * @version 1.0
 * @author Jssor
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit();
}

require_once plugin_dir_path(__FILE__) . 'config/config.php';

class Jssor_Slider_Dispatcher {
    #region load modules

    public static function load($rel_path) {
        include WP_JSSOR_SLIDER_PATH . $rel_path;
    }

    public static function load_once($rel_path) {
        include_once WP_JSSOR_SLIDER_PATH . $rel_path;
    }

    public static function load_module_common() {
        self::load_once('includes/framework/jssor-slider-condition.php');
        self::load_once('includes/framework/class-jssor-slider-globals.php');
        self::load_once('includes/framework/class-jssor-slider-utils.php');
        self::load_once('includes/bll/class-jssor-slider-slider.php');
    }

    private static function load_module_custom() {
        self::load_module_common();
        self::load_once('interface/custom/jssor-slider-custom.php');
    }

    private static function load_module_api() {
        self::load_module_common();
        self::load_once('interface/api/class-jssor-slider-admin-controller.php');
    }

    private static function load_module_admin_ajax() {
        self::load_module_common();
        self::load_once('interface/api/class-jssor-slider-admin-ajax.php');
    }

    private static function load_module_admin_page() {
        //translation not ready yet
        //load_plugin_textdomain('jssor-slider', false, basename(dirname(__FILE__)) . '/languages/');
        self::load_module_common();
        self::load_once('interface/admin/class-jssor-slider-admin-page.php');
    }

    public static function load_module_output() {
        self::load_module_common();
        self::load_once('includes/bll/class-jssor-slider-output.php');
    }

    public static function load_module_activation() {
        self::load_module_common();
        self::load_once('includes/bll/class-jssor-slider-activator.php');
    }

    public static function load_module_deactivation() {
        self::load_module_common();
        self::load_once('includes/bll/class-jssor-slider-deactivator.php');
    }

    #endregion

    #region dispatch interface like hook, request or call

    public static function get_slider_display_html_code($id_or_alias, $dynamic = false) {
        self::load_module_output();
        return WP_Jssor_Slider_Output::get_slider_display_html_code($id_or_alias, $dynamic);
    }

    public static function template_redirect() {
        //returns error in api response instead
        ini_set('display_errors','Off');

        if(isset($_GET['jssor_extension'])) {
            self::load_module_custom();

            wp_jssor_template_redirect();
        }
        else {
            self::load_module_api();

            $controller = new Jssor_Slider_Admin_Controller();
            $controller->process_request();
        }
    }

    public static function admin_menu() {
        global $wp_jssor_slider_admin_page;
        $page_title = 'Jssor Slider - Admin Dashboard';
        $wp_jssor_slider_admin_page = add_menu_page(
                $page_title,
                'Jssor Slider',
                'manage_options',
                'jssor-slider-admin',
                'Jssor_Slider::display_admin_dashboard',
                WP_JSSOR_SLIDER_URL . 'interface/admin/images/jssor-icon-16.png'
        );

        if(isset($_GET['page']) && $_GET['page'] == 'jssor-slider-admin') {
            self::load_module_admin_page();
            WP_Jssor_Slider_Admin_Page::initialize();
        }
    }

    public static function display_admin_dashboard() {
        self::load_module_admin_page();
        WP_Jssor_Slider_Admin_Page::load_page();
    }

    public static function admin_ajax_action() {
        self::load_module_admin_ajax();
        $wp_jssor_slider_admin_ajax = new WP_Jssor_Slider_Admin_Ajax();
        $wp_jssor_slider_admin_ajax->process_admin_ajax();
    }

    public static function upgrade_completed($upgrader_object, $options, $plugin_basename = null) {
        if(isset($options)
            && !empty($options)
            && isset($options['action'])
            && $options['action'] == 'update'
            && isset($options['type'])
            && $options['type'] == 'plugin' ) {

            if(is_null($plugin_basename)) {
                $plugin_basename = 'jssor-slider/jssor-slider.php';
            }

            $hit = false;

            if(isset($options['plugin'])) {
                $hit = ($options['plugin'] == $plugin_basename);
            }
            else if(isset( $options['plugins'] )) {
                foreach( $options['plugins'] as $plugin ) {
                    if( $plugin == $plugin_basename ) {
                        $hit = true;
                    }
                }
            }

            if($hit) {
                self::load_module_activation();
                WP_Jssor_Slider_Activator::activate();
            }
        }
    }

    public static function activate_jssor_slider() {
        self::load_module_activation();
        WP_Jssor_Slider_Activator::activate();
    }

    #endregion
}
