<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 29/05/2015
 * Time: 10:41
 */

namespace My_Bootstrap_Menu_Plugin_Namespace {

    /*
     * The public class has everything that is needed for the frontend site where the menus will be bootstrapped.
     */

    class My_Bootstrap_Menu_Public extends My_Plugin_Public
    {

        function __construct($args)
        {

            //Create new settings
            $settings = new My_Plugin_Settings_Public($args);

            //Construct the parent
            parent::__construct($settings);

            //If there are settings saved, then load the filters
            $menu_filters = new My_Bootstrap_Menu_Nav_Menu_Filters($this->settings);

        }

        /**
         * Additional theme scripts/styles loaded for the admin site
         * function called: "additional_theme_scripts" will be called if exists at: wp_enqueue_scripts
         */
        public function additional_theme_scripts()
        {
            //Add the local css here
            $load_styles = $this->list_plugin_styles_to_load();
            if (!$load_styles || empty($load_styles)) {
                return;
            }

            if (isset($load_styles['load_bootstrap_custom_styles'])) {
                wp_register_style('my-bootstrap-menu-custom-styles', MY_BOOTSTRAP_MENU_PLUGIN_URL . '/inc/css/bootstrap.custom.css', null, rand(111, 9999));
                wp_enqueue_style('my-bootstrap-menu-custom-styles');
            }

            if (isset($load_styles['load_bootstrap_submenu_styles'])) {
                wp_register_style('my-bootstrap-submenu-styles', MY_BOOTSTRAP_MENU_PLUGIN_URL . '/inc/css/bootstrap.submenu.css', null, rand(111, 9999));
                wp_enqueue_style('my-bootstrap-submenu-styles');
            }

            if (isset($load_styles['load_bootstrap_custom_scripts'])) {
                wp_register_script('my-bootstrap-menu-custom-scripts', MY_BOOTSTRAP_MENU_PLUGIN_URL . '/inc/js/bootstrap.custom.js', array('jquery'), rand(111, 9999));
                wp_enqueue_script('my-bootstrap-menu-custom-scripts');
            }

            if (isset($load_styles['load_bootstrap_styles'])) {
                wp_register_style('bootstrap-responsive-css', MY_BOOTSTRAP_MENU_PLUGIN_URL . '/assets/bootstrap/css/bootstrap-responsive.css', null, '3.3.4', 'all');
                wp_enqueue_style('bootstrap-responsive-css');

                wp_register_style('bootstrap-css', MY_BOOTSTRAP_MENU_PLUGIN_URL . '/assets/bootstrap/css/bootstrap.css', array('bootstrap-responsive-css'), '3.3.4', 'all');
                wp_enqueue_style('bootstrap-css');

                wp_enqueue_script('jquery');
                wp_register_script('bootstrap-js', MY_BOOTSTRAP_MENU_PLUGIN_URL . '/assets/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.4', true);
                wp_enqueue_script('bootstrap-js');
            }

            if (isset($load_styles['submenu_caret_icon'])) {
                wp_enqueue_style( 'dashicons' );
            }

        }

        /**
         * For each menu/theme setting we need to check if any of them are asking for the bootstrap menu or custom css
         * @return bool
         */
        private function list_plugin_styles_to_load()
        {
            $menu_themes = My_Bootstrap_Menu_Funcs::list_menus_and_theme_locations();
            if (!$menu_themes)
                return false;

            $styles_to_check = array('load_bootstrap_styles' => true,
                                    'load_bootstrap_submenu_styles' => true,
                                    'load_bootstrap_custom_styles' => true,
                                    'load_bootstrap_custom_scripts' => true,
                                    'submenu_caret_icon' => true);
            $styles_to_load = array();

            //Load each setting... and check if bootstrap or custom css is required
            foreach ((array)$menu_themes as $menu_theme) {

                //Stop loading once all styles have been found
                if (empty($styles_to_check))
                    return $styles_to_load;

                //Load settings for each menu/theme
                if ($this->settings->load_options($menu_theme)) {

                    //If main setting is on...
                    if ($this->settings->bootstrap_this_menu) {

                        //For each style to check.. if true.. set the value and remove from check list...
                        foreach ($styles_to_check as $param_name => $use_this) {
                            if ($this->settings->$param_name) {
                                $styles_to_load[$param_name] = $use_this;
                                unset($styles_to_check[$param_name]);
                            }
                        }
                    }
                }
            }

            return $styles_to_load;
        }
    }
}