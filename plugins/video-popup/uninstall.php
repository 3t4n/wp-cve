<?php

defined( 'ABSPATH' ) or die( ':)' );

/* Uninstall Plugin */

// if not uninstalled plugin
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit(); // out!


/*esle:
	if uninstalled plugin, this options will be deleted
*/

delete_option('vp_gs_op_editor_style');
delete_option('vp_green_bg_menu');
delete_option('vp_gs_op_remove_boder');

delete_option('vp_al_op_video_url');
delete_option('vp_al_op_autoplay');
delete_option('vp_al_op_cookie');

delete_option('vp_al_op_logged_in');
delete_option('vp_al_op_display');
delete_option('vp_al_op_display_custom');

delete_option('vp_al_op_d_remove_border');
delete_option('vp_al_op_yt_mute');

delete_option('vp_extension_update_checker');
delete_option('vp_extension_update_checker_102');
delete_option('vp_extension_update_checker_103');
delete_option('vp_extension_update_checker_104');
delete_option('vp_extension_update_checker_105');
delete_option('vp_extension_update_checker_106');