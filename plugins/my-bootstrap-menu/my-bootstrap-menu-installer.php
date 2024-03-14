<?php
/**
 * Created by PhpStorm.
 * User: Michael Carder
 * Date: 29/06/2015
 * Time: 10:59
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {

    require_once(MY_BOOTSTRAP_MENU_PLUGIN_ASSETS . '/my-plugin-settings-helper/my-plugin-installer.php');

    /**
     * My_Plugin_Installer wraps all the checks required before running activate/deactivate/uninstall.
     *  also adds the functions to the correct hooks
     * Class My_Bootstrap_Menu_Installer
     */
    class My_Bootstrap_Menu_Installer extends My_Plugin_Installer
    {

        public function __construct($args)
        {
            parent::__construct($args);
        }

        public static function activate()
        {
            //Do something on activate
            global $MY_BOOTSTRAP_MENU_DEBUG;
            if (isset($MY_BOOTSTRAP_MENU_DEBUG)) $MY_BOOTSTRAP_MENU_DEBUG->MSG('Activating Plugin');
        }

        public static function deactivate()
        {
            global $MY_BOOTSTRAP_MENU_DEBUG;
            if (isset($MY_BOOTSTRAP_MENU_DEBUG)) $MY_BOOTSTRAP_MENU_DEBUG->MSG('Deactivating Plugin');
            //Do something on deactivate
        }

        public static function uninstall()
        {
            global $MY_BOOTSTRAP_MENU_DEBUG;
            if (isset($MY_BOOTSTRAP_MENU_DEBUG)) $MY_BOOTSTRAP_MENU_DEBUG->MSG('Uninstalling Plugin');
            //Do something on uninstall

        }
    }

}