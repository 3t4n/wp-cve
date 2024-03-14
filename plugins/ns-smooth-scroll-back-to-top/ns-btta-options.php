<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function ns_activate_set_back_to_top_options()
{
    add_option('ns_btta_font_awsome', '<i class="fa fa-arrow-up"></i>');
    add_option('ns_btta_background', '#FFFFFF');
    add_option('ns_btta_background_hover', '#000000');
    add_option('ns_btta_text_color', '#000000');
    add_option('ns_btta_text_color_hover', '#FFFFFF');
    add_option('ns_btta_border_color', '#000000');
    add_option('ns_btta_border_color_hover', '#FFFFFF');
    add_option('ns_btta_speed', '800');
    add_option('ns_btta_position', 4);          
}

register_activation_hook( __FILE__, 'ns_activate_set_back_to_top_options');



function ns_btta_register_options_group()
{
    register_setting('ns_btta_options_group', 'ns_btta_font_awsome');
    register_setting('ns_btta_options_group', 'ns_btta_background');
    register_setting('ns_btta_options_group', 'ns_btta_background_hover');
    register_setting('ns_btta_options_group', 'ns_btta_text_color');
    register_setting('ns_btta_options_group', 'ns_btta_text_color_hover');
    register_setting('ns_btta_options_group', 'ns_btta_border_color');
    register_setting('ns_btta_options_group', 'ns_btta_border_color_hover');  
    register_setting('ns_btta_options_group', 'ns_btta_speed');
    register_setting('ns_btta_options_group', 'ns_btta_position');   
}
 
add_action ('admin_init', 'ns_btta_register_options_group');

?>