<?php
/*
Plugin Name: Simple Membership Form Shortcode
Description: Simple Membership Addon to generate form shortcode for specific membership level.
Plugin URI: https://simple-membership-plugin.com/
Author: wp.insider
Author URI: https://simple-membership-plugin.com/
Version: 1.1
*/

define('SWPM_FORM_SHORTCODE_VERSION', '1.1' );
define('SWPM_FORM_SHORTCODE_PATH', dirname(__FILE__) . '/');
define('SWPM_FORM_SHORTCODE_URL', plugins_url('',__FILE__));

add_action('swpm_after_main_admin_menu', 'swpm_form_sc_do_admin_menu');

function swpm_form_sc_do_admin_menu($menu_parent_slug){
    add_submenu_page($menu_parent_slug, __("Form Shortcode", 'simple-membership'), __("Form Shortcode", 'simple-membership'), 'manage_options', 'swpm-form-shortcode', 'swpm_form_shortcode_admin_interface'); 
}

function swpm_form_shortcode_admin_interface(){
    require_once(SWPM_FORM_SHORTCODE_PATH . 'views/shortcode_generator.php');
}