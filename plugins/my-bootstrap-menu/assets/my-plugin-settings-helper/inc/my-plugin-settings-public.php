<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 9/06/2015
 * Time: 11:08
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {

    /**
     * Class My_Plugin_Settings_Public
     * The base class that handles the config settings for the public view of the plugin. Get/Set values stored in internal array.
     */
    class My_Plugin_Settings_Public extends My_Plugin_Settings_Base
    {
        /**
         * Holds an array of My_Plugin_Settings_Node
         * @var array
         */
        protected $config_settings = array();

        /**
         * Add settings errors on loading of settings
         * @param $code
         * @param $msg
         * @param string $type
         * @return mixed|void
         * @internal param $message
         */
        public function add_admin_notice($code, $msg, $type = My_Plugin_Notice_Type::Error )
        {
            $msg = $type . ': ';
            if (is_array($msg) || is_object($msg)) {
                $msg .= print_r($msg, true) . "<br>\n";
            } else {
                $msg .= $msg . "<br>\n";
            }
            echo "<h5 id='setting-error-{$code}' class='my_plugin_public-admin-notice error notice'>" . $msg . "</h5>";
        }

        /**
         * Uses the magic accessor to return the requested SettingsNode's value
         * @param $key
         * @return bool
         */
        public function __get($key)
        {
            //Get the Settings node value
            if (array_key_exists($key, $this->config_settings)) {
                return $this->config_settings[$key];
            }
            return null;
        }

        /**
         * Gets and sets the Settings Nodes Value only
         * @param $key
         * @param $value
         */
        public function __set($key, $value)
        {
            $this->config_settings[$key] = $value;
        }

        /**
         * Returns the key=>value array for settings
         * @param null $unique_id
         * @return array
         */
        public function get_settings_values($unique_id = null)
        {
            $this->load_options($unique_id);

            return $this->config_settings;
        }

    }
}