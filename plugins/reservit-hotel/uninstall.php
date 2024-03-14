<?php
/*
*      Reservit Hotel Best Price Uninstall
*      Version: 1.0
*      By Reservit
*
*      Contact: http://www.reservit.com/hebergement
*      Created: 2017
*
*      Copyright (c) 2017, Reservit. All rights reserved.
*
*      Licensed under the GPLv2 license - https://www.gnu.org/licenses/gpl-2.0.html
*
*/

//if uninstall.php is not called by WordPress, die
        if (!defined('WP_UNINSTALL_PLUGIN')) {
        die;
        }
        
        //deleting all plugin's options on uninstall
        global $wpdb;
        $optiontodelete = $wpdb->get_results("SELECT option_name FROM {$wpdb->prefix}options WHERE option_name LIKE 'rsvit%'");
        //print_r($optiontodelete);
        foreach ($optiontodelete as $option) {
            $optionname=$option->option_name;
            delete_option($optionname);
        }