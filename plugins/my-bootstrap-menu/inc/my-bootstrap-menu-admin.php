<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 10/06/2015
 * Time: 22:26
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {

    class My_Bootstrap_Menu_Admin extends My_Plugin_Admin
    {

        /**
         * Creates the new Admin settings (i.e. with additional decoration of field input type, section, tabs etc.)
         * Then creates and displays on an Admin Settings Page...
         * @param $args *
         */
        function __construct($args)
        {
            $admin_settings = new My_Bootstrap_Menu_Admin_Settings($args);

            //Add the Admin Settings page config
            $args['parent_slug'] = My_Plugin_Settings_Page_Location::Appearance;
            $args['page_title'] = "My Bootstrap Menu Settings <div class='my_bootstrap_menu_logo'>B</div>"; //the logo 'B' is added here
            $args['page_icon_url'] = plugins_url('img/mcl_logo_small.png', __FILE__);
            $args['menu_title'] = 'My Bootstrap Menu';
            $args['summary_text'] = "Applies the <a target='_blank' href='http://getbootstrap.com/'>Bootstrap</a> theme to a menu - by name or by theme location. <span style='text-align: right'>Built by <a target='_blank' href='http://www.michaelcarder.com'>Michael Carder Ltd</a>, for more information see:  <a target='_blank' href='http://www.codetoolbox.net/wordpress/wordpress-plugins/my-bootstrap-menu/'>My Bootstrap Menu</a></span>";
            $args['user_capability'] = 'manage_options';
            $args['main_menu_icon_url'] = null;
            $args['main_menu_position'] = '1';

            parent::__construct($args, $admin_settings);
        }

        /**
         * Additional theme scripts/styles loaded for the admin site
         * function called: "additional_theme_scripts" will be called if exists, only on this admin page.
         */
        public function additional_theme_scripts()
        {
            //Add the local css here
            wp_enqueue_style('my-bootstrap-menu-admin-styles', plugins_url('/css/my-bootstrap-menu-admin-styles.css', __FILE__), array('my-plugin-styles'), rand(111, 9999));

            //Add the custom interface scripts
            wp_register_script('my-bootstrap-menu-admin-scripts', MY_BOOTSTRAP_MENU_PLUGIN_URL . '/inc/js/my-bootstrap-menu-admin.js', array('jquery'), rand(111, 9999));
            wp_enqueue_script('my-bootstrap-menu-admin-scripts');
        }
    }
}