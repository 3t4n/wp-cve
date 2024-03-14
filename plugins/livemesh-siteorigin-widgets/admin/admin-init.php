<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class LSOW_Admin {


    protected $plugin_slug = 'livemesh_so_widgets';

    public function __construct() {

        $this->includes();
        $this->init_hooks();

    }

    public function includes() {

        // load class admin ajax function
        require_once(LSOW_PLUGIN_DIR . '/admin/admin-ajax.php');

        /**
         * Classes responsible for displaying admin notices.
         */
        if (lsow_fs()->is_not_paying()) {

            require_once LSOW_PLUGIN_DIR . 'admin/notices/admin-notice.php';

            require_once LSOW_PLUGIN_DIR . 'admin/notices/admin-notice-rate.php';
        }

    }

    public function init_hooks() {

        // Build admin menu/pages
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        add_action('current_screen', array($this, 'remove_admin_notices'));


        /**
         * Notice: Rate plugin
         */
        if (lsow_fs()->is_not_paying()) {
            $rate = new LSOW_Notice_Rate('rate', LSOW_PLUGIN_DIR . 'admin/notices/templates/rate.php');

            add_action('load-plugins.php', array($rate, 'defer_first_time'));
            add_action('admin_notices', array($rate, 'display_notice'));
            add_action('admin_post_lsow_dismiss_notice', array($rate, 'dismiss_notice'));
        }

    }

    public function remove_admin_notices($screen) {

        // If this screen is Livemesh SiteOrigin Widgets plugin options page, remove annoying admin notices
        if (strpos($screen->id, $this->plugin_slug) !== false) {
            add_action('admin_notices', array(&$this, 'remove_notices_start'));
            add_action('admin_notices', array(&$this, 'remove_notices_end'), 999);
        }
    }

    public function remove_notices_start() {

        // Turn on output buffering
        ob_start();

    }

    public function remove_notices_end() {

        // Get current buffer contents and delete current output buffer
        $content = ob_get_contents();
        ob_clean();

    }

    public function add_plugin_admin_menu() {

        add_menu_page(
            'Livemesh SiteOrigin Widgets',
            __('Livemesh Widgets', 'livemesh-so-widgets'),
            'manage_options',
            $this->plugin_slug,
            array($this, 'display_settings_page'),
            LSOW_PLUGIN_URL . 'admin/assets/images/logo-shape16.png'
        );

        // add plugin settings submenu page
        add_submenu_page(
            $this->plugin_slug,
            'Widgets Settings',
            __('Settings', 'livemesh-so-widgets'),
            'manage_options',
            $this->plugin_slug,
            array($this, 'display_settings_page')
        );

        // add import/export submenu page
        add_submenu_page(
            $this->plugin_slug,
            'Widgets Documentation',
            __('Documentation', 'livemesh-so-widgets'),
            'manage_options',
            $this->plugin_slug . '_documentation',
            array($this, 'display_plugin_documentation')
        );

    }

    public function display_settings_page() {

        require_once('views/admin-header.php');
        require_once('views/admin-banner2.php');
        require_once('views/settings.php');
        require_once('views/admin-footer.php');

    }

    public function display_plugin_documentation() {


        require_once('views/admin-header.php');
        require_once('views/admin-banner1.php');
        require_once('views/documentation.php');
        require_once('views/admin-footer.php');

    }

    public function enqueue_admin_scripts() {

        // Use minified libraries if LSOW_SCRIPT_DEBUG is turned off
        $suffix = (defined('LSOW_SCRIPT_DEBUG') && LSOW_SCRIPT_DEBUG) ? '' : '.min';

        // get current admin screen
        $screen = get_current_screen();

        // If screen is a part of Livemesh SiteOrigin Widgets plugin options page
        if (strpos($screen->id, $this->plugin_slug) !== false) {

            wp_enqueue_script('jquery-ui-datepicker');

            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style('wp-color-picker');

            wp_register_style('lsow-admin-styles', LSOW_PLUGIN_URL . 'admin/assets/css/lsow-admin.css', array(), LSOW_VERSION);
            wp_enqueue_style('lsow-admin-styles');

            wp_register_script('lsow-admin-scripts', LSOW_PLUGIN_URL . 'admin/assets/js/lsow-admin' . $suffix . '.js', array(), LSOW_VERSION, true);
            wp_enqueue_script('lsow-admin-scripts');

            wp_register_style('lsow-admin-page-styles', LSOW_PLUGIN_URL . 'admin/assets/css/lsow-admin-page.css', array(), LSOW_VERSION);
            wp_enqueue_style('lsow-admin-page-styles');
        }

        if (strpos($screen->id, $this->plugin_slug . '_documentation') !== false) {

            // Load scripts and styles for documentation
            wp_register_script('lsow-doc-scripts', LSOW_PLUGIN_URL . 'admin/assets/js/documentation' . $suffix . '.js', array(), LSOW_VERSION, true);
            wp_enqueue_script('lsow-doc-scripts');

            wp_register_style('lsow-doc-styles', LSOW_PLUGIN_URL . 'admin/assets/css/documentation.css', array(), LSOW_VERSION);
            wp_enqueue_style('lsow-doc-styles');

            // Thickbox
            add_thickbox();

        }

    }

}

new LSOW_Admin;