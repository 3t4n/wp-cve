<?php
/* No direct access */
if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );
 function install_cssadmin_theme() {if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();}
    add_option('_colorthemeadmin','default','');
    add_option('_colorthemeadmintop','off','');}
    function uninstall_cssadmin_theme() {if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();}
    delete_option('_colorthemeadmin');
    delete_option('_colorthemeadmintop');
    }
