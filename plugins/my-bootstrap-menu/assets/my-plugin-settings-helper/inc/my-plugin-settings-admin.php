<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 1/06/2015
 * Time: 15:02
 */
namespace My_Bootstrap_Menu_Plugin_Namespace{

    /**
     * Class My_Plugin_Settings_Admin
     * The base class that handles settings_nodes info, settings_nodes is stored in arrays and subarrays as formatted 'My_Plugin_Settings_Node's
     * See class My_Plugin_Settings_Node.
     */
    abstract class My_Plugin_Settings_Admin extends My_Plugin_Settings_Base
    {
        /**
         * Holds an array of My_Plugin_Settings_Node
         * @var array
         */
        private $settings_nodes;

        /**
         * Holds an array of section nodes
         * @var
         */
        protected $section_nodes;

        /**
         * Holds an array of tab names
         * @var
         */
        public $tabs;

        /**
         * Admin Notice object to manage admin notices on load or errors for settings page
         * @var
         */
        public $admin_notice;

        /**
         * Used to track whether the settings have a unique id field
         * @var
         */
        private $_has_unique_id;
        private $_unique_id_node_key;

        /**
         * Build the Settings Node array (array of My_Plugin_Settings_Node)
         * @return mixed
         */
        abstract function build_settings_nodes();

        /**
         * Build the Section Node array (array of My_Plugin_Section_Node)
         * @return mixed
         */
        abstract function build_section_nodes();

        /**
         * Validates the form input
         * @param $input array input from the form
         * @return mixed
         */
        abstract public function settings_input_validation($input);

        /**
         *
         * Base class for creating a settings class for each project - settings and sections can be fed in using the abstract functions below.
         *  These will be registered and a settings page can then be built from these. Errors checks can also be added on load/validation.
         *
         * @param $settings_args
         * @internal param string $option_group_page_name Unique name for the group / page - can be an existing group or page - will be prepended to the settings input name
         * @internal param string $option_settings_db_name Unique name for saving/loading from the WP db
         * @internal param $page_title
         * @internal param $menu_title
         * @internal param $summary_text
         * @internal param string $parent_page_location
         * @internal param string $user_capability
         * @internal param null $main_menu_icon_url
         * @internal param null $main_menu_position
         * @internal param null $current_plugin_version number Version Id is saved with the options used to check whether existing options work with a later version
         * @internal param null $min_required_version number Minimum Version Id used to check if the saved settings work with this version
         */
        public function __construct($settings_args)
        {
            //Set the option group page name for the possible errors.
            $this->option_group_page_name = $settings_args['option_group_page_name'];

            //Initialise values and settings/section nodes
            $this->build_sections_and_settings();

            //If resetting to default values... stops loading from the db
            if (!empty($_POST) && isset($_POST['reset'])) {
                $this->use_default_values = true;
            }

            //Load the default settings and set values
            parent::__construct($settings_args);
        }

        /**
         * Initialise the class and load the Settings, Sections and register them
         *  add notices as well
         */
        public function build_sections_and_settings()
        {
            //build the settings nodes array - abstract function to be build with class instance
            $this->build_settings_nodes();
            if (!isset($this->settings_nodes))
                $this->add_admin_notice('No Settings Nodes have been set in the function: build_settings_nodes', My_Plugin_Notice_Type::Error);

            //build the section nodes array - abstract function to be built with class instance
            $this->build_section_nodes();
            if (!isset($this->section_nodes))
                $this->add_admin_notice('No Section Nodes have been set in the function: build_settings_nodes', My_Plugin_Notice_Type::Error);

            //Set the unique id if there is one
            $this->update_unique_id();

            //Register Fields, Sections and Settings
            add_action('admin_init', array($this, 'register_sections_and_settings'));
        }

        /**
         * Determines if the settings have a unique id field, to save/load from the settings db.
         * @return bool
         */
        public function has_unique_id()
        {
            if (!isset($this->_has_unique_id)) {

                //Set to false by default
                $this->_has_unique_id = false;

                //Else search all nodes to see if one is a unique id field, then set as true
                foreach ($this->settings_nodes as $key => $settings_node) {
                    if ($settings_node->input_type == My_Plugin_Settings_Input_Type::Unique_Settings_Id) {
                        $this->_has_unique_id = true;
                        $this->_unique_id_node_key = $key;
                    }
                }
            }
            return $this->_has_unique_id;
        }

        /**
         * Determines if the settings have a type of input field, to save/load from the settings db.
         * @return bool
         */
        public function requires_input_type($input_type)
        {
            //Set to false by default
            $requires_input_type = false;

            //Else search all nodes to see if one is of type (e.g. unique id field), then set as true
            foreach ($this->settings_nodes as $key => $settings_node) {
                if ($settings_node->input_type == $input_type) {
                    $requires_input_type = true;
                }
            }

            return $requires_input_type;
        }

        /**
         * Sets the unique id field, to be used when saving/loading from the settings db id.
         */
        public function update_unique_id()
        {
            //If it does not have a unique id - then exit
            if (!$this->has_unique_id())
                return;

            //Get the unique id from either url param, post value, or from specified inputs/defaults
            if (isset($_GET['unique_id'])) {

                //If url parameter is requested... then use that...
                $this->set_unique_id($_GET['unique_id']);

            } elseif (isset($_POST['unique_id'])) {

                //Used during the form post (i.e. saving settings)
                $this->set_unique_id($_POST['unique_id']);

            } else {

                //Sets the unique settings node value (determined in the has_unique_id function)
                if (isset($this->settings_nodes[$this->_unique_id_node_key]->value)) {
                    //Use value if given
                    $this->set_unique_id($this->settings_nodes[$this->_unique_id_node_key]->value);

                } elseif (isset($this->settings_nodes[$this->_unique_id_node_key]->default_value)) {
                    //Use default value
                    $this->set_unique_id($this->settings_nodes[$this->_unique_id_node_key]->default_value);

                } elseif (isset($this->settings_nodes[$this->_unique_id_node_key]->select_options)) {
                    //Get the first option in the select list
                    $this->set_unique_id(reset($this->settings_nodes[$this->_unique_id_node_key]->select_options));

                } else {
                    //Error message if no default value set
                    $this->add_admin_notice("There are no unique settings options provided, default value must be specified at load.", My_Plugin_Notice_Type::Error);
                }
            }
            //Set the value field on change... this will either be the URL requested value or default.. typically.
            $this->settings_nodes[$this->_unique_id_node_key]->value = $this->get_unique_id();
        }

        /**
         * Registers each of the Settings, Sections and Fields
         */
        public function register_sections_and_settings()
        {
            //Register Fields, Sections and Settings
            $this->add_settings_sections();
            $this->add_settings_fields();
            $this->register_settings();
        }


        /**
         * Returns the list of select options for the unique id
         */
        public function get_unique_id_select_options()
        {
            if ($this->has_unique_id())
                return $this->settings_nodes[$this->_unique_id_node_key]->select_options;
        }

        /**
         * Registers each setting to the main group, adds validation function on save
         */
        public function register_settings()
        {
            static $previous_option_settings_db_name;

            //Update the current unique id, then register settings
            register_setting($this->option_group_page_name,                     // settings page group
                $this->get_option_settings_db_name(),               // settings db name - can change with unique id
                array($this, 'settings_input_validate_and_save'));  //calls the abstract function - particular to each instance of this class

            //Save the name of the settings for the uninstaller
            if ($previous_option_settings_db_name != $this->get_option_settings_db_name()) {
                My_Plugin_Installer::register_uninstall_db_name($this->get_option_settings_db_name());
                $previous_option_settings_db_name = $this->get_option_settings_db_name();
            }
        }


        /**
         * Adds each setting section
         */
        public function add_settings_sections()
        {
            foreach ($this->section_nodes as $id => $section_node) {
                add_settings_section($section_node->id,
                    $section_node->title,
                    array($this, 'settings_section_callback'),
                    $this->option_group_page_name);  // settings page
            }
        }

        /**
         * Adds each field to the group and settings
         */
        public function add_settings_fields()
        {
            foreach ($this->settings_nodes as $key => $settings_node) {
                add_settings_field($settings_node->id,                      // unique input id = [groupname]_[id]
                                    $settings_node->title,                  // setting title
                                    array($this, 'settings_field_callback'),// display callback
                                    $this->option_group_page_name,          // settings page
                                    $settings_node->section,                // settings section
                                    $settings_node->get_additional_args()); // additional args,
            }
        }

        /**
         * Builds the settings section, e.g. summary information etc.
         * @param $args
         */
        public function settings_section_callback($args)
        {
            //Get current section node and pass to build arg
            $section_node = $this->get_section_node($args['id']);

            //Build section html using the section node
            $html_section = My_Plugin_Input_Forms::build_section($section_node);

            echo $html_section;
        }

        /**
         * Builds the Settings Field edit/view section - uses My_Plugin_Admin
         */
        public function settings_field_callback($args)
        {
            //need to prepend the 'name' with $this->get_option_settings_db_name()[field_id]

            //Get the current settings node to build the input settings html
            $settings_node = $this->get_settings_node($args['id']);

            //Build the input form by type here
            $html_input_form = My_Plugin_Input_Forms::build_input_form($this->get_option_settings_db_name(), $settings_node);

            echo $html_input_form;
        }

        /**
         * Default function to validate the input, then load into this class
         * @param $input
         * @return mixed
         */
        public function settings_input_validate_and_save($input)
        {
            //Perform basic settings validation first
            $validated_input = $this->basic_settings_validation($input);

            //Calls the instance specific function below to validate the input data
            $validated_input = $this->settings_input_validation($validated_input);

            //Once saved reload into this instance
            if ($validated_input) {
                $this->set_settings_values($validated_input);

                //Append the current version if set
                if (isset($this->current_plugin_version))
                    $validated_input['plugin_version'] = $this->current_plugin_version;

            }

            //Returns false if validation failed and processes any additional functions filtered by this action
            return apply_filters('settings_input_validate_and_save', $validated_input, $input);

            //return $validated_input;
        }

        /**
         * Performs basic settings validation -
         *      ignores 'about' pages
         *      sets checkbox values to false (instead of missing)
         *      formats urls
         *      strip_tags / stripslashes from other values
         * @param $input
         * @return array
         */
        private function basic_settings_validation($input)
        {
            //Get the default / current values
            $validated_input = $this->get_settings_values();

            // Loop through each of the incoming options and put into the validated options
            foreach ($validated_input as $key => $value) {

                switch ($this->get_settings_node($key)->input_type) {
                    case My_Plugin_Settings_Input_Type::Checkbox:
                        //NB: forms do not return a value for unchecked options, however false is a value...!!
                        $validated_input[$key] = array_key_exists($key, $input) ? $input[$key] : false;
                        break;

                    case My_Plugin_Settings_Input_Type::URL:
                        $validated_input[$key] = esc_url($input[$key]);
                        break;

                    case My_Plugin_Settings_Input_Type::About_Page:
                        //remove this as it will not be updated
                        unset($validated_input[$key]);
                        break;

                    default:
                        // Strip all HTML and PHP tags and properly handle quoted strings
                        $validated_input[$key] = strip_tags(stripslashes($input[$key]));
                        break;
                }
            }

            return $validated_input;
        }

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
            if(!isset($this->admin_notice)) {
                $this->admin_notice = new My_Plugin_Admin_Notice($this->option_group_page_name);
            }
            //Add settings error
            $this->admin_notice->add_settings_error($code, $msg, $type);
        }

        /**
         * Add Settings errors on validating inputs
         * @param $message
         * @param string $type
         */
        public function add_settings_validation_error($message, $type = My_Plugin_Notice_Type::Error)
        {
            add_settings_error($this->option_group_page_name, $this->error_slug_on_validate(), $message, $type);
        }


        /**
         * Returns the Error slug used to show errors on validation
         * @return string
         */
        protected function error_slug_on_validate()
        {
            return $this->option_group_page_name . '_on-validate-error';
        }

        /**
         * Uses the magic accessor to return the requested SettingsNode's value
         * @param $key
         * @return bool
         */
        public function __get($key)
        {
            //Get the Settings node value
            if (array_key_exists($key, $this->settings_nodes)) {
                return $this->settings_nodes[$key]->value;
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
            if (array_key_exists($key, $this->settings_nodes)) {
                $this->settings_nodes[$key]->value = $value;
            }
        }

        /**
         * Add one settings node
         * @param My_Plugin_Settings_Node $settings_node
         */
        public function add_settings_node(My_Plugin_Settings_Node $settings_node)
        {

            $key = $settings_node->id;
            //Set the default value if missing
            if (!isset($settings_node->default_value) && isset($settings_node->select_options)) {
                $settings_node->default_value = reset($settings_node->select_options);
            }
            $this->settings_nodes[$key] = $settings_node;
        }

        /**
         * Add multiple settings nodes in a array
         * @param array $settings_nodes
         */
        public function add_settings_nodes(array $settings_nodes)
        {
            foreach ($settings_nodes as $settings_node) {
                $this->add_settings_node($settings_node);
            }
        }

        /**
         * Returns the settings node with the given key [id]
         * @param $key
         * @return mixed
         */
        public function get_settings_node($key)
        {
            return $this->settings_nodes[$key];
        }


        /**
         * Clears the values from each settings node... to be used when changing the unique id and then loading values/defaults
         */
        public function clear_settings_values()
        {
            foreach ($this->settings_nodes as $key => $settings_node) {
                unset($this->settings_nodes[$key]->value);
            }
        }

        /**
         * Add one section node
         * @param My_Plugin_Section_Node $section_node
         * @internal param My_Plugin_Settings_Node $settings_node
         */
        public function add_section_node(My_Plugin_Section_Node $section_node)
        {
            $key = $section_node->id;
            $this->section_nodes[$key] = $section_node;

            //Add tab to tabs if the value is set... must be all set or not set at all.
            if (isset($section_node->tab))
                $this->tabs[$section_node->get_tab_key()] = $section_node->tab; //use key to enforce uniqueness
        }


        /**
         * Add multiple section nodes in a array
         * @param array $section_nodes
         * @internal param array $settings_nodes
         */
        public function add_section_nodes(array $section_nodes)
        {
            foreach ($section_nodes as $section_node) {
                $this->add_section_node($section_node);
            }
        }

        /**
         * Returns the section node with the given id
         * @param $key
         * @return mixed
         */
        public function get_section_node($key)
        {
            return $this->section_nodes[$key];
        }

        /**
         * For each settings node stored, return the internal value from the settings node array
         * @return array
         */
        public function get_settings_values()
        {
            $settings_values = array();
            foreach ($this->settings_nodes as $settings_node) {
                $key = $settings_node->id;
                $settings_values[$key] = $settings_node->value;
            }
            return $settings_values;
        }

    }
}