<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       uninstall.php
 * @date       15.04.2018
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.6
 * @link       https://felixtz.de/
 ********************************************************************/

if (!defined('WP_UNINSTALL_PLUGIN')) exit ();

mpp_uninstall();

function mpp_uninstall()
{
    global $wpdb;

    $table_names = ['mp_pollinfos', 'mp_polls', 'mp_templates', 'mp_settings', 'mp_locklist'];
    if (sizeof($table_names) > 0) {
        foreach ($table_names as $table_name) {
            $table = $wpdb->prefix . $table_name;
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
    }
}