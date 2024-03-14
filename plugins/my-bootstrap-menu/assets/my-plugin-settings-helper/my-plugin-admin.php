<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 2/06/2015
 * Time: 13:17
 *
 * http://codex.wordpress.org/Settings_API
 */
namespace My_Bootstrap_Menu_Plugin_Namespace
{
    if (!defined(__NAMESPACE__ . '\MY_PLUGIN_SETTINGS_PATH'))
        define(__NAMESPACE__ . '\MY_PLUGIN_SETTINGS_PATH', dirname(__FILE__));
    if (!defined(__NAMESPACE__ . '\MY_PLUGIN_SETTINGS_INC_PATH'))
        define(__NAMESPACE__ . '\MY_PLUGIN_SETTINGS_INC_PATH', MY_PLUGIN_SETTINGS_PATH . '/inc');

    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-settings-base.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-settings-node.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-section-node.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-settings-admin.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-admin-notice.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-admin-page.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-input-forms.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-icons.php');
    require_once(MY_PLUGIN_SETTINGS_INC_PATH . '/my-plugin-consts.php');
    require_once(MY_PLUGIN_SETTINGS_PATH . '/my-plugin-installer.php'); //include installer for uninstall register list

    /**
     * Main Admin class which takes the Admin settings and builds the Admin page
     * Class My_Plugin_Admin
     */
    abstract class My_Plugin_Admin
    {

        /**
         * This class's name
         * @var string
         */
        public static $NAME = __CLASS__;

        /**
         * Gets the page id as saved by add_menu or add_sub_menu... to be used in admin_notices etc.
         * @var
         */
        private $page_id;

        /**
         * Accessor for private page_id
         * @return mixed
         */
        public function get_page_id()
        {
            return $this->page_id;
        }

        /**
         * Stores the basename for the actual (parent) plugin
         * @var
         */
        private $plugin_basename;

        /**
         * Private object to build the Admin Settings Page
         * @var
         */
        private $admin_page;

        //Sub Menu only
        // *****************************
        /**
         * @var string (required) The slug name for the parent menu (or the file name of a standard WordPress admin page). Use NULL or set to 'options.php' if you want to create a page that doesn't appear in any menu (see example below).
         */
        protected $parent_slug;

        //Both Main and SubMenus
        // *****************************
        /**
         * @var (string) (required) The text to be displayed in the title tags of the page when the menu is selected
         */
        protected $page_title;

        /**
         * @var (string) (required) The text to be used for the menu
         */
        protected $menu_title;
        /**
         * @var (string) (required) The capability required for this menu to be displayed to the user.
         */
        protected $user_capability;
        /**
         * @var (string) (required) The slug name to refer to this menu by (should be unique for this menu). If you want to NOT duplicate the parent menu item, you need to set the name of the $menu_slug exactly the same as the parent slug.
         */
        protected $option_group_page_name;

        // Main Menu only
        // *****************************
        /**
         * @var (string) (optional) The icon for this menu.
         */
        protected $main_menu_icon_url;
        /**
         * @var (integer) (optional) The position in the menu order this menu should appear. By default, if this parameter is omitted, the menu will appear at the bottom of the menu structure. The higher the number, the lower its position in the menu. WARNING: if two menu items use the same position attribute, one of the items may be overwritten so that only one item displays! Risk of conflict can be reduced by using decimal instead of integer values, e.g. 63.3 instead of 63 (Note: Use quotes in code, IE '63.3').
         *  Default: bottom of menu structure
         */
        protected $main_menu_position;

        // Other Parameters
        // *****************************
        /**
         * Contains the Admin Settings for all Sections, Settings and Tabs etc.
         * @var
         */
        private $settings;

        /**
         * The url for this file
         * @var
         * @return string return the url
         */
        private function get_plugin_url()
        {
            static $_plugin_url;
            if (!isset($_plugin_url))
                $_plugin_url = plugins_url('', __FILE__);

            return $_plugin_url;
        }

        /***
         * Create the page for the settings
         * My_Plugin_Admin constructor.
         * @param $page_args
         * @param My_Plugin_Settings_Admin $admin_settings
         */
        function __construct($page_args, My_Plugin_Settings_Admin $admin_settings)
        {
            //Set all properties provided as page_args: option_group_page_name etc
            $this->fill_values($page_args);

            //Required User capability to view/edit this page
            $this->user_capability = isset($page_args['user_capability']) ? $page_args['user_capability'] : 'manage_options';

            //Set main Admin settings and Sections
            $this->settings = $admin_settings;

            //Create the admin page object
            $this->admin_page = new My_Plugin_Admin_Page($page_args, $admin_settings);

            //Initialise other variables etc.
            $this->init();
        }

        /**
         * Basic init function to register all required hooks to:
         *  - add the options page
         *  - scripts
         *  - settings link in the plugin's meta
         */
        function init()
        {
            //Add page
            add_action('admin_menu', array($this, 'add_options_page'));

            //Add scripts/styles etc
            add_action('admin_enqueue_scripts', array($this, 'my_plugin_enqueue_admin_scripts'));

            //Add the 'Settings' link to the plugin meta
            add_filter('plugin_row_meta', array($this, 'set_plugin_settings_link'), 10, 2);
        }

        /**
         * Add each provided value, as long as it exists as a property - i.e ignore others!
         * @param array $arr_values
         */
        private function fill_values(array $arr_values)
        {
            foreach ($arr_values as $label => $value) {
                if (property_exists($this::$NAME, $label))
                    if (isset($arr_values[$label]))
                        $this->$label = $arr_values[$label];
            }
        }


        /**
         * Enqueue scripts and styles
         */
        function my_plugin_enqueue_admin_scripts($hook)
        {

            //Exit if not on this admin page... i.e. do not load scripts for ALL admin pages!!
            if ($hook != $this->page_id) {
                return;
            }

            //Basic scripts required for all admin pages
            wp_enqueue_script('jquery');
            wp_enqueue_script('my-plugin-settings', $this->get_plugin_url() . '/js/my-plugin-settings.js', array('jquery'), false, true);

            //Load scripts for Image selection
            if ($this->settings->requires_input_type(My_Plugin_Settings_Input_Type::Image_Select)) {
                wp_enqueue_media();
                wp_enqueue_script('my-plugin-settings-image-select', plugins_url('/js/my-plugin-settings-image-select.js', __FILE__), array('jquery'), false, true);
            }

            //Load scripts for the Color picker
            if ($this->settings->requires_input_type(My_Plugin_Settings_Input_Type::Colour_Select)) {
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_script('wp-color-picker');
                wp_enqueue_script('my-plugin-settings-colour-picker', $this->get_plugin_url() . '/js/my-plugin-settings-colour-picker.js', array('wp-color-picker'), false, true);
            }

            //Load the scripts for Glyphicons OR Dashicons if required
            if ($this->settings->requires_input_type(My_Plugin_Settings_Input_Type::Glyphicon_Select) ||
                $this->settings->requires_input_type(My_Plugin_Settings_Input_Type::Dashicon_Select)
            ) {
                wp_enqueue_script('my-plugin-settings-icon-select', plugins_url('/js/my-plugin-settings-icon.js', __FILE__), array('jquery'), false, true);
            }

            //Load the Bootstrap Glyhicons if required
            if ($this->settings->requires_input_type(My_Plugin_Settings_Input_Type::Glyphicon_Select)) {
                wp_enqueue_style('bootstrap-glyphicons-only', $this->get_plugin_url() . '/bootstrap.glyphicons/css/bootstrap.min.css');
            }

            //Add the local css here
            wp_enqueue_style('my-plugin-styles', plugins_url('/css/my-plugin-styles.css', __FILE__));

            //Load any additional plugin scripts/templates
            if (method_exists($this, 'additional_theme_scripts'))
                $this->additional_theme_scripts();
        }

        /**
         * Add an options page either in the Main Menu or as a Sub Menu determined by the parent-slug
         * https://codex.wordpress.org/Function_Reference/add_options_page
         */
        public function add_options_page()
        {
            if ($this->parent_slug == My_Plugin_Settings_Page_Location::Main_Menu) {
                // Add Main Menu
                $this->page_id = add_menu_page($this->page_title, $this->menu_title, $this->user_capability, $this->option_group_page_name, array($this->admin_page, 'build_settings_page'), $this->main_menu_icon_url, $this->main_menu_position);
            } else {
                //Add Sub Menu
                $this->page_id = add_submenu_page($this->parent_slug, $this->page_title, $this->menu_title, $this->user_capability, $this->option_group_page_name, array($this->admin_page, 'build_settings_page'));
            }

        }

        /**
         * Adds a link to the settings page in the meta data of the plugins page.
         * @param $links
         * @param $file
         * @return array
         */
        public function set_plugin_settings_link($links, $file)
        {
            // create link
            if ($file == $this->plugin_basename) {
                return array_merge(
                    $links,
                    array(sprintf('<a href="%s?page=%s">%s</a>', $this->parent_slug, $this->option_group_page_name, __('Settings')))
                );
            }
            return $links;
        }

    }
}