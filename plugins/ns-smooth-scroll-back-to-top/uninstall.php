<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}


delete_option('ns_btta_position');
delete_option('ns_btta_speed');
delete_option('ns_btta_border_color_hover');
delete_option('ns_btta_border_color');
delete_option('ns_btta_text_color_hover');
delete_option('ns_btta_text_color');
delete_option('ns_btta_background_hover');
delete_option('ns_btta_background');
delete_option('ns_btta_font_awsome');
?>