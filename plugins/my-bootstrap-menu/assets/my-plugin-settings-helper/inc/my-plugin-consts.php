<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 2/06/2015
 * Time: 12:42
 */

namespace My_Bootstrap_Menu_Plugin_Namespace {

    /**
     * Class My_Plugin_Settings_Input_Type
     * A replacement for an enum...
     *  used to select the html form type for the config inputs
     */
    abstract class My_Plugin_Settings_Input_Type
    {
        const Text = 'text';
        const Number = 'number';
        const URL = 'url';
        const Email = 'email';
        const Select_Option = 'select';
        const Radio_Button = 'radio';
        const Checkbox = 'checkbox';
        const Button = 'button';
        const Multiline_Text = 'multiline_text';
        const Image_Select = 'image_select';
        const Dashicon_Select = 'dashicon_select';
        const Glyphicon_Select = 'glyphicon_select';
        const Colour_Select = 'colour_select'; //color for the americans...
        const About_Page = 'about_page';
        const Unique_Settings_Id = 'unique_settings_id';
    }

    /**
     * Class My_Plugin_Settings_Page_Location
     * Used to replace Enums required for storing the add_submenu_page or add_menu_page
     */
    abstract class My_Plugin_Settings_Page_Location
    {

        /**
         * Main Menu
         * https://codex.wordpress.org/Function_Reference/add_menu_page
         */
        const Main_Menu = 'admin.php';
        /**
         * Sub Menu types
         * https://codex.wordpress.org/Function_Reference/add_submenu_page
         */
        const Dashboard = 'index.php';
        const Posts = 'edit.php';
        const Media = 'upload.php';
        const Links = 'link-manager.php';
        const Pages = 'edit.php?post_type=page';
        const Comments = 'edit-comments.php';
        const Custom_Post_Types = 'edit.php?post_type=your_post_type';
        const Appearance = 'themes.php';
        const Plugins = 'plugins.php';
        const Users = 'users.php';
        const Tools = 'tools.php';
        const Settings = 'options-general.php';
        const Settings_Network_Admin = 'settings.php';
    }

    /**
     * Class My_Plugin_Notice_Type
     * Used to display Admin Notices on error, load, save etc.
     */
    abstract class My_Plugin_Notice_Type
    {
        const Error = 'error';
        const Update = 'updated';
    }
}