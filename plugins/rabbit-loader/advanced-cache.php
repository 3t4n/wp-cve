<?php

/**
 * If you have deleted the plugin without using Standard WordPress un-installation methods, such as deleting file system folders etc, you need to clean the advanced-cache.php file's content.
 */
defined('ABSPATH') or die('not allowed');

if (!defined('RABBITLOADER_AC_PLUG_DIR')) {

    define('RABBITLOADER_AC_ABSPATH', '%%RABBITLOADER_AC_ABSPATH%%');

    //to ensure installed site is not manually copied from other folder
    if (ABSPATH == RABBITLOADER_AC_ABSPATH) {

        define('RABBITLOADER_AC_ACTIVE', true);
        define('RABBITLOADER_AC_PLUG_DIR', '%%RABBITLOADER_AC_PLUG_DIR%%');
        define('RABBITLOADER_AC_LOGGED_IN_COOKIE', '%%RABBITLOADER_AC_LOGGED_IN_COOKIE%%');
        define('RABBITLOADER_AC_CACHE_DIR', '%%RABBITLOADER_AC_CACHE_DIR%%');
        define('RABBITLOADER_AC_PLUG_VERSION', '%%RABBITLOADER_AC_PLUG_VERSION%%'); //should not be replaced from the automation script
        define('RABBITLOADER_AC_PLUG_ENV', '%%RABBITLOADER_AC_PLUG_ENV%%');
        try {
            include_once RABBITLOADER_AC_PLUG_DIR . "inc/core/core.php";
            include_once RABBITLOADER_AC_PLUG_DIR . "inc/core/util.php";
            include_once RABBITLOADER_AC_PLUG_DIR . "inc/core/integrations.php";
            include_once RABBITLOADER_AC_PLUG_DIR . "inc/util_wp.php";
            include_once RABBITLOADER_AC_PLUG_DIR . "inc/public.php";

            try {
                RabbitLoader_21_Public::process_incoming_request('ac');
            } catch (\Throwable $e) {
                RabbitLoader_21_Core::on_exception($e);
            } catch (Exception $e) {
                RabbitLoader_21_Core::on_exception($e);
            }
        } catch (Exception $e) {
        }
    }
}
