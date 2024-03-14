<?php
/*
Uninstall Clean
Plugin: Recent Posts Widget Advanced
Since: 0.2.1
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$option_name = 'widget_widget-recent-posts-in-category';
delete_option($option_name);
$option_name = 'kgmarp_option_name';
delete_option($option_name);