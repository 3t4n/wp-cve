<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 9/06/2015
 * Time: 11:08
 */

namespace My_Bootstrap_Menu_Plugin_Namespace {
    /**
     * The base class for handling settings (options) to save and load from WP db..
     *  also defines abstract magic methods to access the settings e.g. settings->my_setting so it is the same for Admin and Public versions.
     *
     * Class My_Plugin_Settings_Base
     */
    abstract class My_Plugin_Settings_Base
    {

        /**
         * Holds the option group name to keep all these settings together
         *  can be used with an existing group (e.g. reading)
         * @var
         */
        protected $option_group_page_name;

        /**
         * The name used to save/load the options from the db.
         *  picked up by the Settings API to manage settings automatically
         * @var
         */
        protected $option_settings_db_name;

        /**
         * Provides a unique id for saving and loading settings: [$option_settings_db_name]_[$unique_id]
         * @var
         */
        private $unique_id;

        /**
         * Current and Minimum required plugin versions to work with the saved settings
         * @var
         */
        protected $min_plugin_version;

        /**
         * Current and Minimum required plugin versions to work with the saved settings
         * @var
         */
        public $current_plugin_version;

        /**
         * Stores the parent plugin basefile name, for registration/activation hooks
         * @var
         */
        protected $plugin_basefile;

        /**
         * Flag to set when resetting values to default values, stops loading of values on build.
         * @var
         */
        protected $use_default_values;

        /**
         * Abstract function to be set on the class instance for accessing each setting in the data array
         * @param $key
         * @param $value
         * @return mixed
         */
        abstract public function __set($key, $value);

        /**
         * Abstract function for getting the settings value in the class instance data array
         * @param $key
         * @return mixed
         */
        abstract public function __get($key);

        /**
         * Abstract function for adding an admin notice - as Admin_Notice in admin screen - html error on public
         * @param $code
         * @param $msg
         * @param string $type
         * @return mixed
         * @internal param $message
         */
        abstract public function add_admin_notice($code, $msg, $type = My_Plugin_Notice_Type::Error );

        /**
         * Get the values from each Settings node as key=>value
         */
        abstract public function get_settings_values();

        /**
         * Parent constructor to manage the settings/options
         * @param $settings_args
         */
        function __construct($settings_args)
        {
            //Main options group id: $option_group_page_name
            //                  == $page[for add_settings_section & add_settings_field]
            //                  == $option_group[register_setting]
            //                  == $menu_slug [for add page]
            $this->option_group_page_name = $settings_args['option_group_page_name'];

            //Used to save/load from the db. Also validated against Settings API Automatically.
            $this->option_settings_db_name = $settings_args['option_settings_db_name'];

            //Min and Current Version
            if (array_key_exists('plugin_basefile', $settings_args))
                $this->plugin_basefile = $settings_args['plugin_basefile'];

            if (array_key_exists('min_required_version', $settings_args))
                $this->min_plugin_version = $settings_args['min_required_version'];

            //Set the unique id if provided
            if (array_key_exists('unique_id', $settings_args))
                $this->set_unique_id($settings_args['unique_id']);

            // Load the current settings from the db... if any
            if (!$this->use_default_values)
                $this->load_options(null, is_admin());
        }

        /**
         * Sets the values for each Settings node from a key=>value array
         * Ignores any unknown settings... only those that exist already
         */
        public function set_settings_values($key_value_array)
        {
            foreach ($key_value_array as $key => $value) {
                $this->$key = $value;
            }
        }

        /**
         * Returns the unique id for these options
         * @return mixed
         */
        public function get_unique_id()
        {
            return $this->unique_id;
        }

        public function set_unique_id($unique_id = null)
        {
            if(isset($unique_id)) {
                $this->unique_id = $this::format_unique_id($unique_id);
            }
        }

        private static function format_unique_id($unique_id)
        {
            return sanitize_title($unique_id);
            //return strtolower(preg_replace("/[^a-zA-Z0-9]+/", '_', html_entity_decode((string)$unique_id)));
        }

        /**
         * Gets the current plugin's version from the basefilename - if provided
         * @return null|string
         */
        public function get_plugin_version()
        {
            if (!isset($this->current_plugin_version)) {
                if (isset($this->plugin_basefile) and function_exists('get_plugin_data')) {
                    $plugin_data = get_plugin_data($this->plugin_basefile);
                    $this->current_plugin_version = '' . $plugin_data['Version'];
                } else {
                    return null;
                }
            }
            return $this->current_plugin_version;
        }

        /**
         * Returns the unique option settings db name
         * @param null $override_unique_id
         * @return string
         */
        public function get_option_settings_db_name($override_unique_id = null)
        {
            if (isset($override_unique_id)) {
                $override_unique_id = $this::format_unique_id($override_unique_id);
                return $this::format_option_settings_db_name($this->option_settings_db_name, $override_unique_id);
            }

            if (isset($this->unique_id))
                return $this::format_option_settings_db_name($this->option_settings_db_name, $this->get_unique_id());

            return $this::format_option_settings_db_name($this->option_settings_db_name);
        }

        /**
         * Creates the option settings db name to be used to save/load the unique settings from the db.
         * @param $db_name
         * @param null $unique_id
         * @return string
         */
        private static function format_option_settings_db_name($db_name, $unique_id = null)
        {
            if (isset($unique_id))
                return $db_name . "_" . $unique_id;

            return $db_name;
        }



        /**
         * Loads options from the WP database if exist
         * @return bool
         */
        function load_options($unique_id = null, $display_error = false)
        {
            //Set the unique id
            $this->set_unique_id($unique_id);

            // Get the options from WP database
            $options = get_option($this->get_option_settings_db_name(), false);

            //If no options available from WP db then return false
            if (!$options) {
                if ($display_error) {
                    $this->add_admin_notice('load_settings',
                        'No saved options available',
                        My_Plugin_Notice_Type::Update);
                }
                return false;
            }

            //Check if min version is ok for these settings
            if (array_key_exists('plugin_version', $options) && isset($this->min_plugin_version)) {
                $current_plugin_version = $this->get_plugin_version();
                if (version_compare($current_plugin_version, $this->min_plugin_version, '<')) {
                    if ($display_error) {
                        $this->add_admin_notice('load_settings',
                            "Saved version of settings out of date [current version: {$current_plugin_version} min required: {$this->min_plugin_version}]",
                            My_Plugin_Notice_Type::Error);
                    }
                    return false;
                }
            }


            // Load each option into an existing or new Key=>Value pair
            foreach ($options as $key => $value) {
                $this->$key = $value;
            }

            return true;
        }

        /**
         * Save options to db
         */
        function save_options()
        {
            //Get the option db name
            $option_id = $this->get_option_settings_db_name();

            //The NEW value for this option. This value can be an integer, string, array, or object.
            $new_values = $this->get_settings_values();

            //Whether to load the option when WordPress starts up. For existing options `$autoload` can only be updated using `update_option()`
            // if `$value` is also changed. Accepts 'yes' or true to enable, 'no' or false to disable. For non-existent options, the default value is 'yes'.
            $autoload = 'yes';

            $saved = update_option($option_id, $new_values, $autoload);

            if ($saved) {
                $this->add_admin_notice('Settings saved', My_Plugin_Notice_Type::Update);
            } else {
                $this->add_admin_notice('Settings have not been saved', My_Plugin_Notice_Type::Error);
            }
        }
    }
}