<?php
/*
Plugin Name: WhatConverts
Plugin URI: http://wordpress.org/extend/plugins/whatconverts/
Description: Enables <a href="https://www.whatconverts.com/">WhatConverts</a> on all pages. To setup, 1) Navigate to the plugin settings, 'Settings' > 'WhatConverts', 2) Add your Profile ID from <a href="http://app.whatconverts.com/">WhatConverts</a>.
Version: 1.0.6
Author: WhatConverts
Author URI: https://www.whatconverts.com/
*/
	  
function activate_whatconverts() {
    $whatconverts_profile_id = get_option('whatconverts_profile_id');
    $whatconverts_footer_load = get_option('whatconverts_footer_load');
	if (empty($whatconverts_profile_id)) {
        add_option('whatconverts_profile_id', '00000');
        add_option('whatconverts_footer_load', '1');
    }
}

function deactive_whatconverts() {
    //delete_option('whatconverts_profile_id');
}

function admin_init_whatconverts() {
	register_setting('whatconverts', 'whatconverts_profile_id');
	register_setting('whatconverts', 'whatconverts_footer_load');
}

function admin_menu_whatconverts() {
	add_options_page('WhatConverts', 'WhatConverts', 'manage_options', 'whatconverts', 'options_page_whatconverts');
}

function options_page_whatconverts() {
    include( plugin_dir_path( __FILE__ ).'options.php');
}

function whatconverts() {
	$whatconverts_profile_id = get_option('whatconverts_profile_id');
	$whatconverts_footer_load = get_option('whatconverts_footer_load');
    $whatconverts_footer_status = $whatconverts_footer_load == 1 ? true : false;

    //Check if user is editing page using Thrive Themes.
    $whatconverts_thrive_themes_editing = isset($_GET['tve']) && $_GET['tve'] === 'true' ? true : false;

    //Check if user is editing page using Divi.
    $whatconverts_divi_editing = isset($_GET['et_fb']) && $_GET['et_fb'] == 1 ? true : false;

	if ($whatconverts_profile_id != '00000' && !$whatconverts_thrive_themes_editing && !$whatconverts_divi_editing)
		wp_enqueue_script( 'whatconverts-tracking-script', '//s.ksrndkehqnwntyxlhgto.com/' . $whatconverts_profile_id . '.js', array(), '', $whatconverts_footer_status );
}

register_activation_hook(__FILE__, 'activate_whatconverts');
register_deactivation_hook(__FILE__, 'deactive_whatconverts');

if (is_admin()) {
	add_action('admin_init', 'admin_init_whatconverts');
	add_action('admin_menu', 'admin_menu_whatconverts');
}

if (!is_admin()) {
	add_action('wp_enqueue_scripts', 'whatconverts');
}
?>