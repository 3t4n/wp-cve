<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 8/06/2015
 * Time: 18:24
 */

namespace My_Bootstrap_Menu_Plugin_Namespace {


    /**
     * A series for static functions for accessing Wordpress menu information and plugin data.
     * Class My_Bootstrap_Menu_Funcs
     */
    class My_Bootstrap_Menu_Funcs
    {


        /**
         * Returns a list of all menus
         * @return array
         */
        public static function list_all_menu_names()
        {
            $menu_names = false;
            $menus = get_terms('nav_menu', array('hide_empty' => true));
            foreach ($menus as $menu) {
                $menu_names[] = $menu->name;
            }
            return $menu_names;
        }

        /**
         * Return all themes registered for this site
         * @return array|bool
         */
        public static function list_all_theme_location_names()
        {
            $theme_locations = false;
            $themes = get_registered_nav_menus();

            foreach ($themes as $location => $menu_key) {

                $theme_locations[] = $location;
            }
            return $theme_locations;
        }


        /**
         * Lists all the menus and themes so the user can choose a unique value for the setting
         * @return string
         * @internal param string $first menu string optional
         */
        public static function list_menus_and_theme_locations()
        {
            //List all menus
            $menus_and_themes = self::list_all_menu_names();
            if (!$menus_and_themes) //If no menus return False
                return $menus_and_themes;


            //List all themes
            $themes = self::list_all_theme_location_names();
            if ($themes != false)
                $menus_and_themes = array_merge($menus_and_themes, $themes);

            return $menus_and_themes;
        }

        public static function get_menu_name_at_location($theme_location)
        {
            $menu_at_location = self::get_menu_at_location($theme_location);
            if (isset($menu_at_location) && is_object($menu_at_location) && property_exists($menu_at_location, 'name'))
                return $menu_at_location->name;
        }

        public static function get_menu_at_location($theme_location)
        {
            //Get all menu locations
            $menu_locations = get_nav_menu_locations();
            if (!array_key_exists($theme_location, $menu_locations))
                return;
            $menu_id_at_location = $menu_locations[$theme_location];
            if ($menu_id_at_location > 0)
                return wp_get_nav_menu_object($menu_id_at_location);
        }

        public static function get_theme_with_menu($menu_name)
        {
            $menu_locations = get_nav_menu_locations();
            $menu_obj = wp_get_nav_menu_object($menu_name)->term_id;
            $menu_id = $menu_obj->term_id;
            foreach ($menu_locations as $location => $menu_key) {
                if ($menu_key == $menu_id) {
                    return $location;
                }
            }
        }

        public static function get_menu_and_theme_name($args)
        {
            $menu_theme = array();

            //Get the Menu name and Theme location
            if (isset($args->menu) && $args->menu != '') {
                //For WP 4.3 -> returns the menu as a menu object!!
                if(is_object($args->menu)){
                    $menu = $args->menu->name;
                } else {
                    $menu = $args->menu;
                }
                //Return both Menu Name and Theme Location
                $menu_theme['menu'] = $menu; //menu name is set
                if (isset($args->theme_location)) {
                    $menu_theme['theme'] = $args->theme_location; //both values are set
                } else {
                    $menu_theme['theme'] = self::get_theme_with_menu($menu_theme['menu']); //only menu name is set, so get theme (if available!)
                }

            } elseif (isset($args->theme_location) && $args->theme_location != '') {
                $menu_theme['menu'] = self::get_menu_name_at_location($args->theme_location); //only theme is set so get the name at that theme location
                $menu_theme['theme'] = $args->theme_location; //Theme is set
            }
            //Return menu themes and names
            return $menu_theme;
        }

        public static function startsWith($source, $prefix)
        {
            return strncmp($source, $prefix, strlen($prefix)) == 0;
        }

        public static function endsWith($source, $suffix)
        {

            return substr_compare($source, $suffix, -strlen($suffix)) == 0;
        }
    }
}