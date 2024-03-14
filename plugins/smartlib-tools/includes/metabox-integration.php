<?php


if (!defined('SMARTLIB_THEME_INFO')) {

    if (!class_exists('RWMB_Core')) {

        //Load metabox plugin file

        require_once(plugin_dir_path(dirname(__FILE__)) . 'vendor/meta-box/meta-box.php');

    }


    require_once(plugin_dir_path(dirname(__FILE__)) .'config/metabox-fields.php');


    add_filter('admin_init', 'smartlib_tools_register_meta_boxes');

    /**
     * Build metaboxes based on config file with global $meta_boxes
     *
     * @param $meta_boxes
     */


    function smartlib_tools_register_meta_boxes($meta_boxes)
    {
        global $meta_boxes;


        if (!class_exists('RW_Meta_Box'))
            return;
        foreach ($meta_boxes as $meta_box) {
            new RW_Meta_Box($meta_box);
        }


    }

    if (!class_exists('RWMB_Loader')) {
        abstract class RWMB_Loader
        {

        }
    }
}