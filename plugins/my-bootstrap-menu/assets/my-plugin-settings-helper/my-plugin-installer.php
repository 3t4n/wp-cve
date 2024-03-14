<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 28/06/2015
 * Time: 11:53
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {

    defined('ABSPATH') OR exit;


    /**
     * Activate, Deactivate and Uninstall are listed here to either be
     *  overwritten, extended and to remove the settings on uninstall
     * This needs to be run from outside of the normal plugin hook (e.g. init or plugins_loaded)
     *  on create will register the activation hooks
     * http://wordpress.stackexchange.com/questions/25910/uninstall-activate-deactivate-a-plugin-typical-features-how-to
     * Class My_Plugin_Installer
     */
    abstract class My_Plugin_Installer
    {

        //Variables need to be public to allow ReflectionClass access
        public static $plugin_basefile;
        public static $option_group_page_name;

        public function __construct($args)
        {
            //Fills required values only
            $this->fill_values($args);

            //Register the acivate/deactivate/uninstall hoooks
            $this->register_activation_hooks();

        }

        /**
         * Add each provided value, as long as it exists as a property - i.e ignore others!
         * @param array $arr_values
         */
        private function fill_values(array $arr_values)
        {
            $reflection_class = new \ReflectionClass(get_called_class());
            foreach ($arr_values as $label => $value) {
                if (property_exists(get_called_class(), $label))
                    if (isset($arr_values[$label]))
                        $reflection_class->setStaticPropertyValue($label, $arr_values[$label]);
            }
        }

        /**
         * Register the activation/deactivation/uninstall hooks - might need to be abstract static methods written for the class
         */
        public function register_activation_hooks()
        {
            // Activation Function
            register_activation_hook($this::$plugin_basefile, array(get_called_class(), 'on_activation'));

            // Deactivation Function
            register_deactivation_hook($this::$plugin_basefile, array(get_called_class(), 'on_deactivation'));

            // Uninstall Function
            register_uninstall_hook($this::$plugin_basefile, array(get_called_class(), 'on_uninstall'));
        }

        public static function on_activation()
        {
            if (!current_user_can('activate_plugins'))
                return;
            $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
            check_admin_referer("activate-plugin_{$plugin}");

            // do something on activate
            if (method_exists(get_called_class(), 'activate'))
                static::activate();
        }


        public static function on_deactivation()
        {
            if (!current_user_can('activate_plugins'))
                return;
            $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
            check_admin_referer("deactivate-plugin_{$plugin}");

            // do something on deactivate
            if (method_exists(get_called_class(), 'deactivate'))
                static::deactivate();
        }

        public static function on_uninstall()
        {
            if (!current_user_can('activate_plugins'))
                return;

            //check_admin_referer('bulk-plugins');

            // Important: do not check WP_UNINSTALL_PLUGIN as this isn't called for the hook uninstall method.

            //Remove all options from the db
            $uninstall_db_name = static::get_uninstall_db_name();
            $setting_db_names_to_uninstall = get_option($uninstall_db_name);
            static::unregister_all_settings(static::$option_group_page_name, array_keys($setting_db_names_to_uninstall));
            delete_option($uninstall_db_name);

            //Run additional uninstall function
            if (method_exists(get_called_class(), 'uninstall'))
                static::uninstall();

        }

        public static function register_uninstall_db_name($settings_db_name)
        {
            $uninstall_db_name = static::get_uninstall_db_name();
            $current_db_names = get_option($uninstall_db_name);
            $current_db_names[$settings_db_name] = $settings_db_name;
            update_option($uninstall_db_name, $current_db_names, false);
        }

        private static function get_uninstall_db_name()
        {
            return static::$option_group_page_name . '_uninstall_list';
        }

        /**
         * Unregisters each setting and deletes saved options if required.
         */
        public static function unregister_all_settings($option_group_page_name,
                                                       $option_settings_db_names,
                                                       $delete_saved_settings = true)
        {
            if (is_array($option_settings_db_names)) {
                foreach ($option_settings_db_names as $unique_db_name) {

                    //Unregister each setting for each unique id
                    unregister_setting($option_group_page_name, // settings page group
                        $unique_db_name);       // settings db name - can change with unique id
                    // cannot access the abstract function - as it is particular to each instance of this class
                    //Delete the saved options for each unique id
                    if ($delete_saved_settings) {
                        delete_option($unique_db_name);
                    }
                }
            } else {
                //Unregister settings
                unregister_setting($option_group_page_name,     // settings page group
                    $option_settings_db_names); // settings db name - can change with unique id
                // cannot access the abstract function - as it is particular to each instance of this class
                //Delete the saved options
                if ($delete_saved_settings) {
                    delete_option($option_settings_db_names);
                }
            }
        }
    }
}