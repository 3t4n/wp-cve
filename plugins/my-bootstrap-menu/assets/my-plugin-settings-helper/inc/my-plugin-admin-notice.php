<?php
/*
 * My_Plugin_Admin_Notice
 * Michael Carder
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {


    class My_Plugin_Admin_Notice
    {
        public static $option_group_page_name;

        function __construct($option_group_page_name)
        {
            static::$option_group_page_name = $option_group_page_name;
        }

        public static function add_settings_error($code, $msg, $type = My_Plugin_Notice_Type::Error )
        {
            //Append inline to the type to ensure the settings errors don't get moved under h2
            add_action('admin_notices', function() use ($code, $msg, $type) {
                add_settings_error(My_Plugin_Admin_Notice::$option_group_page_name, $code, $msg, $type . " inline");
            } );
        }


    }

}
