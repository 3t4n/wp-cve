<?php

namespace CODNetwork\Services;

use CODNetwork\Controller\CODN_Settings_Controller;

class CODN_Admin_Menus
{
    private static $instance;

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Add menu item.
     */
    public static function make_status_menu()
    {
        add_submenu_page(
            "cod.network",
            "Cod.network status",
            "Activity Logs",
            "manage_options",
            "codNetwork-status",
            array(self::get_instance(), 'status_page'));
        add_submenu_page(
            'cod.network',
            'codn-setting',
            'Settings',
            'manage_options',
            'codn-settings',
            array(self::get_instance(), 'setting_page'));
    }

    /**
     * init the status page.
     */
    public function status_page()
    {
        $this->codn_load_resource();
        include_once(sprintf('%sadmin/view/pageStatus.php', CODN__PLUGIN_DIR));
    }

    /**
     * Setting page
     * @return void
     */
    public function setting_page()
    {
        $this->codn_load_resource();
        $setting = new CODN_Settings_Controller();
        $setting->codn_update_status_logger();
        $status = $setting->codn_select_logs_status_is_active();
        include_once(sprintf('%sadmin/view/pageSettings.php', CODN__PLUGIN_DIR));
    }

    /*
     * Load resource
     */
    public function codn_load_resource()
    {
        wp_register_style('bootstrap.min.css', sprintf('%sassets/css/bootstrap.min.css', codn_plugin_dir_path()));
        wp_enqueue_style('bootstrap.min.css');

        wp_register_script('bootstrap.min.js', sprintf('%sassets/js/bootstrap.min.js', codn_plugin_dir_path()));
        wp_enqueue_script('bootstrap.min.js');

    }
}

