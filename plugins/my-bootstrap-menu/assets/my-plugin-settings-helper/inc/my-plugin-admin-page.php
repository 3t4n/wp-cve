<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 28/06/2015
 * Time: 14:30
 */

namespace My_Bootstrap_Menu_Plugin_Namespace {


    class My_Plugin_Admin_Page
    {
        /**
         * This class's name
         * @var string
         */
        public static $NAME = __CLASS__;

        /**
         * Contains the Admin Settings for all Sections, Settings and Tabs etc.
         * @var
         */
        private $settings;
        /**
         * @var (string) (required) The slug name to refer to this menu by (should be unique for this menu). If you want to NOT duplicate the parent menu item, you need to set the name of the $menu_slug exactly the same as the parent slug.
         */
        protected $option_group_page_name;
        /**
         * @var  (string) (required) The text to display under the menu title
         */
        protected $summary_text;
        /**
         * @var (string) (required) The text to be displayed in the title tags of the page when the menu is selected
         */
        protected $page_title;
        /**
         * @var (string) (optional) The url of an image/icon for the main page.
         */
        protected $page_icon_url;
        /**
         * @var (string) (required) The text to be used for the menu
         */
        protected $menu_title;


        function __construct($page_args, My_Plugin_Settings_Admin $settings)
        {

            //Set all properties provided as page_args: option_group_page_name etc
            $this->fill_values($page_args);

            //Set main Admin settings and Sections
            $this->settings = $settings;

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
         * Builds the Main settings page - using the Settings API
         * *********************************************************
         */
        public function build_settings_page()
        {
            //must check that the user has the required capability
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            //Output the Settings page here:
            ?>
            <div class="wrap">

                <!-- Title bar for this plugin -->
                <div class="my_plugin_title_bar">

                    <!-- Header and page icon -->
                    <div class="header my_plugin_header">
                        <?php echo $this->get_page_icon(); ?>
                        <?php echo $this->get_title() ?>
                    </div>

                    <!-- Summary text -->
                    <p class="my_plugin_summary_text"><?php echo $this->summary_text; ?></p>

                </div>

                <!-- Errors section (if any set during load) -->
                <div class="my_plugin_errors">
                    <?php settings_errors($this->option_group_page_name); ?>
                </div>

                <!-- Glyphicon/Dashicon section (if any required during load) -->
                <?php echo $this->get_icon_selectors(); ?>

                <!-- Tab headers - if any are set -->
                <?php echo $this->get_tab_header(); ?>

                <!-- Main Input form section - outputs to WP options.php -->
                <form id='my_plugin_input_form'
                      class='my_plugin_input_form'
                      action='options.php'
                      method='post'>

                    <!-- Hidden settings section to pass other parameters to options.php -->
                    <?php settings_fields($this->option_group_page_name); ?>

                    <!-- Set the reset values flag - to prompt users before exiting after reset to defaults -->
                    <?php echo $this->set_reset_values_changed_flag(); ?>

                    <!-- Unique id - if set, pass as hidden parameter -->
                    <?php echo $this->get_unique_id_post_value(); ?>

                    <!-- Main Sections and Settings in a custom build script -->
                    <?php $this->build_settings_sections($this->option_group_page_name); ?>

                    <!-- Submit and Delete buttons -->
                    <?php submit_button('Save Changes', 'primary my_plugin_input_form_button', 'submit', false); ?>

                </form>

                <?php $this->build_reset_defaults_button();?>
            </div>

        <?php
        }

        /**
         * Add the Dashicon or Glyphicon selectors if required by the plugin
         * @return string
         */
        private function get_icon_selectors() {
            $html = '';
            $require_glyphicon = $this->settings->requires_input_type(My_Plugin_Settings_Input_Type::Glyphicon_Select);
            $require_dashicon = $this->settings->requires_input_type(My_Plugin_Settings_Input_Type::Dashicon_Select);
            if($require_glyphicon || $require_dashicon) {
                $html .= "<div id='my_plugin_icon_selectors'>";
                if($require_glyphicon) {
                    $html .= My_Plugin_Icons::get_glyphicons_list();
                }
                if($require_dashicon) {
                    $html .= My_Plugin_Icons::get_dashicon_list();
                }
                $html .= "</div>";
            }
            return $html;
        }

        /**
         * Sets the unique id value if there is one as an easily accessible return value from the post -
         *      this is required when the form action posts to options.php as it rebuilds the form with no parameters i.e. without: ?unique_id=[]
         * @return string
         */
        private function get_unique_id_post_value()
        {
            $html = '';
            if ($this->settings->has_unique_id()) {
                $html .= "<input type='hidden' name='unique_id' value='{$this->settings->get_unique_id()}' />";
            }
            return $html;
        }

        /**
         * Resets the values to the default values
         */
        private function build_reset_defaults_button()
        {
            $redirect_to_self = $_SERVER['REQUEST_URI'];

            echo "<form id='my_plugin_reset_form'
                    class='my_plugin_input_form'
                    action='{$redirect_to_self}'
                    method='post'>";

            settings_fields($this->option_group_page_name);

            $unique_id = ($this->settings->has_unique_id()) ? ' [' . $this->settings->get_unique_id() . ']' : '';

            echo "<input name='reset'
                     class='button button-secondary my_plugin_input_form_button'
                     type='submit'
                     value='Reset to default {$unique_id}' >
              </form>";
        }

        private function set_reset_values_changed_flag()
        {
            $html = '';
            if (!empty($_POST) && isset($_POST['reset'])) {
                $html .= "<input type='hidden' name='reset_values' value='true' />";
            }
            return $html;
        }

        /**
         * Get the Plugin Title string.. append the [unique id] if required.
         * @return string
         */
        private function get_title()
        {
            $html = $this->page_title;
            if ($this->settings->has_unique_id()) {
                $html .= ' [' . $this->settings->get_unique_id() . ']';
            }
            $html = "<h2 class='my_plugin_title'>" . $html . "</h2>";
            return $html;
        }


        /**
         * Builds the Tab header tab - if tabs are provided...
         * @return string
         */
        private function get_tab_header()
        {
            $html = '';
            if ($this->has_tabs()) {

                //WP Nav Tab wrapper
                $html .= "<h2 class='nav-tab-wrapper my_plugin_tab_header'>\n";
                $first_tab = $this->get_first_tab_key();

                //Add each tab
                foreach ($this->settings->tabs as $tab_key => $tab_name) {
                    //Set first tab active by default
                    $active_class = ($tab_key == $first_tab) ? "nav-tab-active" : "";
                    $html .= "<a href='#{$tab_key}'
                            class='my_plugin_tab nav-tab {$active_class}'
                            id='{$tab_key}'>{$tab_name}</a>\n";
                }

                $html .= "</h2>";
            }
            return $html;
        }


        /**
         * Builds the icon/page image url... sets the maximum size to something reasonable...
         * @return string
         */
        private function get_page_icon()
        {
            $html = '';
            if (isset($this->page_icon_url)) {
                $html .= "<div class='my_plugin_logo'>
                        <img src='{$this->page_icon_url}' alt='{$this->menu_title}' />
                      </div> ";
            }
            return $html;
        }

        /**
         * Gets the first element in the tab array key if set
         * @return mixed
         */
        private function get_first_tab_key()
        {
            static $first_tab;
            if ($this->has_tabs()) {
                if (!isset($first_tab)) {
                    $tab_keys = array_keys($this->settings->tabs);
                    $first_tab = reset($tab_keys);
                }
            }
            return $first_tab;
        }

        /**
         * Shows whether or not the current settings have tabs...
         * @return bool
         */
        private function has_tabs()
        {
            return isset($this->settings->tabs);
        }

        /**
         * Main function displaying each setting... for each tab(if present)
         * @param $page
         */
        private function build_settings_sections($page)
        {
            global $wp_settings_sections, $wp_settings_fields;

            //If there are no sections then return
            if (!isset($wp_settings_sections[$page]))
                return;

            //Output for each section
            foreach ((array)$wp_settings_sections[$page] as $section) {

                $section_node = $this->settings->get_section_node($section['id']);
                $tab_key = $section_node->get_tab_key();

                //Tab content start - Hidden keeps the form elements in the page so that the save function still works on all elements.
                if ($this->has_tabs()) {
                    //Set the first tab as visible by default, jQuery will manage this.
                    $active_tab_class = ($tab_key == $this->get_first_tab_key()) ? '' : 'my_plugin_hidden';
                    echo "<div id='{$tab_key}_content' class='my_plugin_tab_content {$active_tab_class}'>";
                }

                //Add the section title
                if ($section['title'])
                    echo "<h3 class='my_plugin_section_title'>{$section['title']}</h3>\n";

                //Run the callback func for each section
                if ($section['callback'])
                    call_user_func($section['callback'], $section);

                //Finish if no settings fields are available
                if (!isset($wp_settings_fields) ||
                    !isset($wp_settings_fields[$page]) ||
                    !isset($wp_settings_fields[$page][$section['id']])
                ) {

                    //Close the tabs
                    if ($this->has_tabs()) echo "</div>";
                    continue;
                }

                //Output all settings for each section
                echo '<table class="form-table my_plugin_settings_section">';
                do_settings_fields($page, $section['id']);
                echo '</table>';

                //Close the tabs
                if ($this->has_tabs()) echo "</div>";
            }

        }

    }
}