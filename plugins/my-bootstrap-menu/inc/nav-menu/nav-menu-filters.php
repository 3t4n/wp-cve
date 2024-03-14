<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 30/05/2015
 * Time: 14:45
 */

namespace My_Bootstrap_Menu_Plugin_Namespace {


    class My_Bootstrap_Menu_Nav_Menu_Filters
    {
        /**
         * The settings object which will provide all the user's settings
         * @var
         */
        private $settings;

        /**
         * Constructs the class to build the Bootstrap Menus and adds the required filters to catch the menu build process.
         * @param My_Plugin_Settings_Public $settings
         */
        function __construct(My_Plugin_Settings_Public $settings)
        {
            //Set the main settings
            $this->settings = $settings;

            //Add filters to catch pre-nav menu build (i.e. replace with walker) and post to prefix/suffix the required menu code.
            add_filter('pre_wp_nav_menu', array($this, 'my_bootstrap_menu_pre_wp_nav_menu'), 999, 2);
            add_filter('wp_nav_menu', array($this, 'my_bootstrap_menu_wp_nav_menu'),999, 2);
        }

        /**
         *
         * Short-circuits the menu generation so anything other then null returned will skip the menu generation.
         *  Replaces the Nav Menu walker class with a custom bootstrap class.
         *  Run at filter: pre_wp_nav_menu
         *
         * @param  string|null $nav Nav menu output to short-circuit with.
         * @param  object $args An object containing wp_nav_menu() arguments
         *
         * @return string|null
         */
        public function my_bootstrap_menu_pre_wp_nav_menu($nav, $args)
        {
            //Return unchanged if there are no current settings for this menu or theme location
            if (!$this->has_current_settings($args))
                return $nav;

            //Get the new nav menu walker
            $nav_menu_walker = new My_Bootstrap_Menu_Nav_Menu_Walker($this->settings);

            //Amend any additional args -> classes etc.
            $nav_menu_walker->amend_arg_values($args);

            //Set the nav menu walker to be the custom Bootstrap walker
            $args->walker = $nav_menu_walker;

            return $nav;
        }

        /**
         * Wraps the final nav menu items in the specified html and classes.
         *  e.g. prepends the title logo, and 3-bar button code.
         *  Run at filter: wp_nav_menu
         *
         * @param  string $nav The HTML content for the navigation menu.
         * @param  object $args An object containing wp_nav_menu() arguments
         * @return string       The HTML content for the navigation menu.
         */
        public function my_bootstrap_menu_wp_nav_menu($nav, $args)
        {

            //Return unchanged if there are no current settings for this menu or theme location
            if (!$this->has_current_settings($args))
                return $nav;

            //Get the nav menu markup
            $nav_menu_markup = new My_Bootstrap_Menu_Nav_Menu_Markup($this->settings);

            //Prefix the menu with the header bar etc.
            $prefix = $nav_menu_markup->get_navbar_prefix();

            //Append suffix and closing tags
            $suffix = $nav_menu_markup->get_navbar_suffix();

            //Combine all nav elements into the navbar
            $nav = $prefix . $nav . $suffix;

            return $nav;
        }

        /**
         * Checks if the current args: menu / theme location have any settings - and if those settings are set to true!
         * @param $args
         * @return bool
         */
        private function has_current_settings($args)
        {
            $menu_themes = My_Bootstrap_Menu_Funcs::get_menu_and_theme_name($args);

            //Get settings for menu first...as this takes precedence
            if (isset($menu_themes['menu'])) {
                if ($this->settings->load_options($menu_themes['menu']))
                    if ($this->settings->bootstrap_this_menu)
                        return true;     //Only return if this menu is set to true... else check the theme below
            }

            //If no menu options then load theme location options
            if (isset($menu_themes['theme'])) {
                if ($this->settings->load_options($menu_themes['theme']))
                    if ($this->settings->bootstrap_this_menu &&
                        (isset($menu_themes['menu']) || $this->settings->override_fallback_menu)
                    )
                        return true;    //Only return true if this theme location has a menu set OR the override fallback is set
            }

            //If no settings return false
            return false;
        }

    }
}