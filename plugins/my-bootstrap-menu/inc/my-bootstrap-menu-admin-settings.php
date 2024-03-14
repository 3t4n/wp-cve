<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 29/05/2015
 * Time: 10:38
 */

namespace My_Bootstrap_Menu_Plugin_Namespace {

    /**
     * Wraps the settings and config for the plugin
     * Class My_Bootstrap_Menu_Admin
     */
    class My_Bootstrap_Menu_Admin_Settings extends My_Plugin_Settings_Admin
    {
        /**
         * Tab and Section names
         */
        const tab_main = 'Main';
        const tab_options = 'Options';
        const tab_format = 'Format';
        const tab_advanced = 'Advanced Options';
        const tab_about = 'About';

        //Main
        const section_menu = 'my_bootstrap_menu_settings';
        const section_navbar_format = 'my_bootstrap_menu_navbar_format';
        const section_navbar_options = 'my_bootstrap_menu_navbar_options';

        //Options
        const section_logo = 'my_bootstrap_menu_logo_settings';
        const section_title = 'my_bootstrap_menu_title_settings';
        const section_search = 'my_bootstrap_menu_search_settings';
        const section_login = 'my_bootstrap_menu_login_settings';
        const section_register = 'my_bootstrap_menu_register_settings';

        //Menu Specific Options
        const section_menu_alignment = 'my_bootstrap_menu_advanced_alignment';
        const section_menu_tab_pills = 'my_bootstrap_menu_advanced_tab_pills';
        const section_menu_buttons = 'my_bootstrap_menu_advanced_buttons';

        //Advanced Options
        const section_submenu_format = 'my_bootstrap_menu_submenu_format';
        const section_advanced_options = 'my_bootstrap_menu_advanced_options';
        const section_load_styles = 'my_bootstrap_menu_load_styles';

        //About
        const section_about_page = 'my_bootstrap_menu_about_page';

        private function get_about_page()
        {
            //html markup for the about page... include anything here to describe the plugin.
            return "This plugin gives the ability to select a menu by Name or by Theme Location and apply bootstrap formatting to it.
                Some of the features available are:
                <ul class='my_bootstrap_menu_list'>
                  <li>Add a logo or title</li>
                  <li>Use Bootstrap formats:
                      <ul class='my_bootstrap_submenu_list'>
                        <li>Navbar</li>
                        <li>Pills</li>
                        <li>Tabs</li>
                        <li>Buttons and Button Groups</li>
                      </ul>
                  </li>
                  <li>Align any and all elements Left or Right</li>
                  <li>Default or Inverse formats</li>
                  <li>Include additional buttons for:
                    <ul class='my_bootstrap_submenu_list'>
                        <li>Login </li>
                        <li>Search</li>
                        <li>Register</li>
                    </ul>
                  <li>Advanced Options</li>
                </ul>
                More information here:  <a target='_blank' href='http://www.codetoolbox.net/wordpress/wordpress-plugins/my-bootstrap-menu/'>My Bootstrap Menu</a><br>
                <h4>Built by:</h4> <a target='_blank' href='http://www.michaelcarder.com'>Michael Carder Ltd</a> - contact us for more information or development work<br>
                The plugin was developed using the 'My-Plugin-Settings' to manage the settings... see more details here: <a target='_blank' href='http://www.codetoolbox.net/wordpress/wordpress-plugins/my-plugin-settings/'/>Code Toolbox</a> ";
        }

        /**
         *  Builds all the required settings nodes and default values - adds them in the build_sections_and_settings function
         */
        function build_settings_nodes()
        {
            //Get a delimited list of menus and themes to choose from
            $menus_and_theme_location_options = My_Bootstrap_Menu_Funcs::list_menus_and_theme_locations();

            //Return error if no menus or locations
            if (!$menus_and_theme_location_options) {
                $this->add_admin_notice('load_menus', 'No Menus or Theme Locations exist, unable to continue!');
                $menus_and_theme_location_options = array();
            }

            //build the array of settings nodes
            $settings_nodes = array
            (
                //Menu/Theme Selection
                //section_menu with Unique_Settings_Id
                My_Plugin_Settings_Node::withValues('menu_theme', 'Select Menu or Theme Location', null, $this::section_menu, My_Plugin_Settings_Input_Type::Unique_Settings_Id, 'Menu Name has priority over Theme Location', $menus_and_theme_location_options),
                My_Plugin_Settings_Node::withValues('bootstrap_this_menu', 'Bootstrap this menu: ', false, $this::section_menu, My_Plugin_Settings_Input_Type::Checkbox, 'Set this menu or theme location to be a bootstrap menu'),

                //section_navbar_format
                My_Plugin_Settings_Node::withValues('menu_type', 'Select Menu Type', null, $this::section_navbar_format, My_Plugin_Settings_Input_Type::Select_Option, 'Select the menu type', My_Bootstrap_Menu_Nav_Menu_Consts::Menu_Type()),
                My_Plugin_Settings_Node::withValues('navbar_format', 'Select Navbar Format', null, $this::section_navbar_format, My_Plugin_Settings_Input_Type::Select_Option, 'Select the navbar format', My_Bootstrap_Menu_Nav_Menu_Consts::Navbar_Format()),
                My_Plugin_Settings_Node::withValues('navbar_fixed_type', 'Select Navbar Fixed Type', null, $this::section_navbar_format, My_Plugin_Settings_Input_Type::Select_Option, 'Select the navbar fixed type', My_Bootstrap_Menu_Nav_Menu_Consts::Fixed_Type()),

                //section_navbar_options
                My_Plugin_Settings_Node::withValues('display_icon_bar_button', 'Display 3-Icon-Bar Button: ', true, $this::section_navbar_options, My_Plugin_Settings_Input_Type::Checkbox, 'Displays the 3 bar icon, only when the menu is in mobile mode'),
                My_Plugin_Settings_Node::withValues('display_title', 'Display Site Title: ', true, $this::section_navbar_options, My_Plugin_Settings_Input_Type::Checkbox, 'Displays the site title next to the menu'),
                My_Plugin_Settings_Node::withValues('display_logo', 'Display Logo: ', true, $this::section_navbar_options, My_Plugin_Settings_Input_Type::Checkbox, 'Displays the logo'),
                My_Plugin_Settings_Node::withValues('display_login', 'Display Login: ', true, $this::section_navbar_options, My_Plugin_Settings_Input_Type::Checkbox, 'Shows login button'),
                My_Plugin_Settings_Node::withValues('display_register', 'Display Register: ', true, $this::section_navbar_options, My_Plugin_Settings_Input_Type::Checkbox, 'Shows register button'),
                My_Plugin_Settings_Node::withValues('display_search', 'Display Search: ', true, $this::section_navbar_options, My_Plugin_Settings_Input_Type::Checkbox, 'Shows the search box in the menu bar'),

                //section_logo
                My_Plugin_Settings_Node::withValues('logo_url', 'Select title logo', null, $this::section_logo, My_Plugin_Settings_Input_Type::Image_Select, 'Select the image from the media library'),
                My_Plugin_Settings_Node::withValues('logo_small_url', 'Select mobile version logo (opt)', null, $this::section_logo, My_Plugin_Settings_Input_Type::Image_Select, 'Select the logo image for the mobile or smaller screens - optional'),
                My_Plugin_Settings_Node::withValues('logo_height', 'Enter logo height', 30, $this::section_logo, My_Plugin_Settings_Input_Type::Text, 'Enter the height in px (or auto)'),
                My_Plugin_Settings_Node::withValues('logo_width', 'Enter logo width', 'auto', $this::section_logo, My_Plugin_Settings_Input_Type::Text, 'Enter the width in px (or auto)'),
                My_Plugin_Settings_Node::withValues('logo_title', 'Enter logo title', '', $this::section_logo, My_Plugin_Settings_Input_Type::Text, 'Enter logo title if required'),
                My_Plugin_Settings_Node::withValues('logo_alt_text', 'Enter logo alt text', '', $this::section_logo, My_Plugin_Settings_Input_Type::Text, 'Enter logo alt text if required'),

                //section_title
                My_Plugin_Settings_Node::withValues('title_text_transform', 'Select title text format', null, $this::section_title, My_Plugin_Settings_Input_Type::Select_Option, 'Select the text transform UPPER, lower, Capitalize or Default = none', My_Bootstrap_Menu_Nav_Menu_Consts::Text_Transform()),

                //section_search
                My_Plugin_Settings_Node::withValues('search_label', 'Enter a label for the search box', null, $this::section_search, My_Plugin_Settings_Input_Type::Text, 'Leave blank for no label'),
                My_Plugin_Settings_Node::withValues('search_glyphicon', 'Select search glyphicon', 'glyphicon glyphicon-search', $this::section_search, My_Plugin_Settings_Input_Type::Glyphicon_Select, 'Select the glyhicon'),
                My_Plugin_Settings_Node::withValues('search_default_value', 'Default value for the search box', 'Search...', $this::section_search, My_Plugin_Settings_Input_Type::Text, 'Leave blank for no label, cleared on user selecting search box'),
                My_Plugin_Settings_Node::withValues('search_box_width', 'Enter a width for the search box', 25, $this::section_search, My_Plugin_Settings_Input_Type::Number, 'Select width in pixels for search box'),
                My_Plugin_Settings_Node::withValues('search_button_type', 'Select a search button type', '', $this::section_search, My_Plugin_Settings_Input_Type::Select_Option, 'Select the button type for the search button', My_Bootstrap_Menu_Nav_Menu_Consts::Button_Type()),

                //section_login
                My_Plugin_Settings_Node::withValues('login_label', 'Enter a label for login', 'Login', $this::section_login, My_Plugin_Settings_Input_Type::Text, 'Leave blank for no label'),
                My_Plugin_Settings_Node::withValues('logout_label', 'Enter a label for logout', 'Logout', $this::section_login, My_Plugin_Settings_Input_Type::Text, 'Leave blank for no label'),
                My_Plugin_Settings_Node::withValues('login_glyphicon', 'Select login glyphicon', 'glyphicon glyphicon-log-in', $this::section_login, My_Plugin_Settings_Input_Type::Glyphicon_Select, 'Select the glyhicon'),
                My_Plugin_Settings_Node::withValues('logout_glyphicon', 'Select logout glyphicon', 'glyphicon glyphicon-log-out', $this::section_login, My_Plugin_Settings_Input_Type::Glyphicon_Select, 'Select the glyhicon'),

                //section_register
                My_Plugin_Settings_Node::withValues('register_label', 'Enter a label for the register', 'Sign Up', $this::section_register, My_Plugin_Settings_Input_Type::Text, 'Leave blank for no label'),
                My_Plugin_Settings_Node::withValues('register_glyphicon', 'Select register glyphicon', 'glyphicon glyphicon-user', $this::section_register, My_Plugin_Settings_Input_Type::Glyphicon_Select, 'Select the glyhicon'),

                //section_menu_alignment
                My_Plugin_Settings_Node::withValues('menu_alignment', 'Align menu', null, $this::section_menu_alignment, My_Plugin_Settings_Input_Type::Select_Option, null, My_Bootstrap_Menu_Nav_Menu_Consts::Menu_Alignment()),
                My_Plugin_Settings_Node::withValues('title_alignment', 'Align title', null, $this::section_menu_alignment, My_Plugin_Settings_Input_Type::Select_Option, null, My_Bootstrap_Menu_Nav_Menu_Consts::Alignment()),
                My_Plugin_Settings_Node::withValues('logo_alignment', 'Align logo', null, $this::section_menu_alignment, My_Plugin_Settings_Input_Type::Select_Option, null, My_Bootstrap_Menu_Nav_Menu_Consts::Alignment()),
                My_Plugin_Settings_Node::withValues('login_alignment', 'Align login', 'navbar-right', $this::section_menu_alignment, My_Plugin_Settings_Input_Type::Select_Option, null, My_Bootstrap_Menu_Nav_Menu_Consts::Alignment()),
                My_Plugin_Settings_Node::withValues('register_alignment', 'Align register', 'navbar-right', $this::section_menu_alignment, My_Plugin_Settings_Input_Type::Select_Option, null, My_Bootstrap_Menu_Nav_Menu_Consts::Alignment()),
                My_Plugin_Settings_Node::withValues('search_alignment', 'Align search box', 'navbar-right', $this::section_menu_alignment, My_Plugin_Settings_Input_Type::Select_Option, null, My_Bootstrap_Menu_Nav_Menu_Consts::Alignment()),

                //section_menu_buttons
                My_Plugin_Settings_Node::withValues('button_type', 'Select Button Type', null, $this::section_menu_buttons, My_Plugin_Settings_Input_Type::Select_Option, 'Select the type of Button to use', My_Bootstrap_Menu_Nav_Menu_Consts::Button_Type()),
                My_Plugin_Settings_Node::withValues('button_group_size', 'Select Button Size', null, $this::section_menu_buttons, My_Plugin_Settings_Input_Type::Select_Option, 'Select the size of the Button menus', My_Bootstrap_Menu_Nav_Menu_Consts::Button_Group_Size()),

                //section_submenu_format
                My_Plugin_Settings_Node::withValues('display_caret', "Display Icon e.g. <span class='dashicons dashicons-arrow-down'></span>", true, $this::section_submenu_format, My_Plugin_Settings_Input_Type::Checkbox, 'Display icon if menu item has a submenu'),
                My_Plugin_Settings_Node::withValues('submenu_caret_icon', "Select Menu Dropdown Glyphicon", null, $this::section_submenu_format, My_Plugin_Settings_Input_Type::Glyphicon_Select, 'Icon to display if main menu item is enabled above'),
                My_Plugin_Settings_Node::withValues('submenu_headings_are_links', "Submenu headings are links", false, $this::section_submenu_format, My_Plugin_Settings_Input_Type::Checkbox, 'Clicking a submenu link will open the page (if checked), or just display the submenu (if not checked). NB: dropdown icon must be displayed if selected.'),
                My_Plugin_Settings_Node::withValues('submenu_dropdown_direction', "Select the Submenu dropdown direction", null, $this::section_submenu_format, My_Plugin_Settings_Input_Type::Select_Option, 'Select the dropdown direction for the submenu', My_Bootstrap_Menu_Nav_Menu_Consts::Submenu_Dropdown_Direction()),
                My_Plugin_Settings_Node::withValues('submenu_dropdown_alignment', "Select the Submenu alignment", null, $this::section_submenu_format, My_Plugin_Settings_Input_Type::Select_Option, 'Select the dropdown menu alignment for the submenu', My_Bootstrap_Menu_Nav_Menu_Consts::Submenu_Dropdown_Alignment()),

                //section_advanced_options
                My_Plugin_Settings_Node::withValues('class_container', "Select the container type", 'container-fluid', $this::section_advanced_options, My_Plugin_Settings_Input_Type::Select_Option, 'Select the container type: container (fixed-width) or container-fluid (full-width) or none', My_Bootstrap_Menu_Nav_Menu_Consts::Class_Container()),
                My_Plugin_Settings_Node::withValues('wrap_in_container', 'Wrap the whole menu in a container class', null, $this::section_advanced_options, My_Plugin_Settings_Input_Type::Checkbox, 'If selected - restricts the width of the menu to the content (NB: cannot be fixed type)'),
                My_Plugin_Settings_Node::withValues('include_wp_menu_classes', 'Include WP menu classes', true, $this::section_advanced_options, My_Plugin_Settings_Input_Type::Checkbox, 'If selected this will include the default WP menu classes (e.g. menu-item, menu-item-type-taxonomy etc)'),
                My_Plugin_Settings_Node::withValues('include_div_for_fixed_top', 'Include div for fixed top', true, $this::section_advanced_options, My_Plugin_Settings_Input_Type::Checkbox, 'Include a fixed height div to move the content below a fixed top menu'),
                My_Plugin_Settings_Node::withValues('override_fallback_menu', 'Override the fallback menu if none set', true, $this::section_advanced_options, My_Plugin_Settings_Input_Type::Checkbox, 'Override the Bootstrapped menu fallback to be blank for users, or prompt admin to add a menu'),

                //section_load_styles
                My_Plugin_Settings_Node::withValues('load_bootstrap_styles', "Load the plugin's version of bootstrap", false, $this::section_load_styles, My_Plugin_Settings_Input_Type::Checkbox, 'Select whether to load the included version of bootstrap in your theme (ver 3.3.4)'),
                My_Plugin_Settings_Node::withValues('load_bootstrap_custom_styles', "Load the plugin's custom bootstrap styles", true, $this::section_load_styles, My_Plugin_Settings_Input_Type::Checkbox, 'Select whether to load the extended versions of bootstrap css (e.g. title formats)'),
                My_Plugin_Settings_Node::withValues('load_bootstrap_submenu_styles', "Load the plugin's version of bootstrap submenus", true, $this::section_load_styles, My_Plugin_Settings_Input_Type::Checkbox, 'Select whether to load the bootstrap submenu styles (from Bootstrap v2)'),
                My_Plugin_Settings_Node::withValues('load_bootstrap_custom_scripts', "Load the plugin's custom jQuery scripts", true, $this::section_load_styles, My_Plugin_Settings_Input_Type::Checkbox, 'Select whether to load the custom bootstrap scripts for buttons/pills on collapse'),

                //about_page
                My_Plugin_Settings_Node::withValues('about', 'My Bootstrap Menu Plugin: ', null, $this::section_about_page, My_Plugin_Settings_Input_Type::About_Page, $this->get_about_page()),

            );

            //Add the settings nodes to the class
            $this->add_settings_nodes($settings_nodes);
        }

        /**
         * Builds the Settings Section Nodes and adds them to the class - used in the build_sections_and_settings function
         *
         * */
        function build_section_nodes()
        {
            $sections = array
            (
                //tab_main
                new My_Plugin_Section_Node($this::section_menu, 'Select Menu', null, $this::tab_main),
                new My_Plugin_Section_Node($this::section_navbar_format, 'Menu Format', null, $this::tab_main),
                new My_Plugin_Section_Node($this::section_navbar_options, 'Menu Options', null, $this::tab_main),
                //tab_options
                new My_Plugin_Section_Node($this::section_logo, 'Logo Settings', null, $this::tab_options),
                new My_Plugin_Section_Node($this::section_title, 'Title Settings', null, $this::tab_options),
                new My_Plugin_Section_Node($this::section_search, 'Search Box Settings', null, $this::tab_options),
                new My_Plugin_Section_Node($this::section_login, 'Login Settings', null, $this::tab_options),
                new My_Plugin_Section_Node($this::section_register, 'Register Settings', null, $this::tab_options),
                //tab_format
                new My_Plugin_Section_Node($this::section_menu_alignment, 'Align Menu Elements', null, $this::tab_format),
                new My_Plugin_Section_Node($this::section_menu_buttons, 'Advanced Button Settings', null, $this::tab_format),
                new My_Plugin_Section_Node($this::section_submenu_format, 'Submenu Format', null, $this::tab_format),
                //tab_advanced
                new My_Plugin_Section_Node($this::section_advanced_options, 'Advanced Options', null, $this::tab_advanced),
                new My_Plugin_Section_Node($this::section_load_styles, 'Load My Bootstrap Menu Plugin Styles', null, $this::tab_advanced),
                //tab_about
                new My_Plugin_Section_Node($this::section_about_page, 'About', null, $this::tab_about),
            );

            $this->add_section_nodes($sections);
        }

        /**
         * On Submit: Settings inputs are validated here...
         * @param array $input
         * @return mixed|void
         */
        public function settings_input_validation($input)
        {
            //Make plugin specific validation if required
            $validated_input = $input;

            // Return the array processing any additional functions filtered by this action
            return $validated_input;
        }

    }
}