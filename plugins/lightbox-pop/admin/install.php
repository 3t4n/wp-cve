<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function xyz_lbx_network_install($networkwide) {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				lbx_install();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	lbx_install();
}

function lbx_install()
{
	global $wpdb;
	if(get_option('xyz_credit_link')=="")
	{
		add_option("xyz_credit_link", '0');
	}
	
	$lightbox_installed_date = get_option('lightbox_installed_date');
	if ($lightbox_installed_date=="") {
		$lightbox_installed_date = time();
		update_option('lightbox_installed_date', $lightbox_installed_date);
	}
	
	add_option("xyz_tinymce", '1');

add_option("xyz_lbx_enable", '1');
add_option("xyz_lbx_showing_option",'0,0,0');
add_option("xyz_lbx_adds_enable", '1');
add_option("xyz_lbx_cache_enable", '0');

	add_option("xyz_lbx_html", 'Hello world.');
	add_option("xyz_lbx_top", '25');
	add_option("xyz_lbx_width", '50');
	add_option("xyz_lbx_height", '50');
	add_option("xyz_lbx_left", '25');
	add_option("xyz_lbx_right", '25');
	add_option("xyz_lbx_bottom", '25');
	add_option("xyz_lbx_display_position", '1');
	add_option("xyz_lbx_delay", '0');
	add_option("xyz_lbx_page_count", '1');
	add_option("xyz_lbx_mode", 'delay_only'); //page_count_only,both are other options
	add_option("xyz_lbx_repeat_interval", '1');
	add_option("xyz_lbx_repeat_interval_timing", '1');//hrs or  minute
	add_option("xyz_lbx_z_index",'10000');
	add_option("xyz_lbx_color",'#000000');	
	add_option("xyz_lbx_corner_radius",'5');
	add_option("xyz_lbx_width_dim",'%');
	add_option("xyz_lbx_height_dim",'%');
		add_option("xyz_lbx_right_dim",'%');
		add_option("xyz_lbx_bottom_dim",'%');
		add_option("xyz_lbx_left_dim",'%');
		add_option("xyz_lbx_top_dim",'%');
	add_option("xyz_lbx_border_color",'#cccccc');
	add_option("xyz_lbx_bg_color",'#ffffff');
	add_option("xyz_lbx_opacity",'60');
	add_option("xyz_lbx_border_width",'10');
	add_option("xyz_lbx_page_option",'1');
	add_option("xyz_lbx_close_button_option",'0');
	add_option("xyz_lbx_iframe",'1');
	
	add_option("xyz_lbx_positioning",'1');
	add_option("xyz_lbx_position_predefined","1");
	add_option("xyz_lbx_bg_opacity",'60');
	add_option("xyz_lbx_display_user",'0');	
	
	$version=get_option('xyz_lbx_free_version');
	$currentversion=xyz_lbx_plugin_get_version();
	if($version=="")
	{
		add_option("xyz_lbx_free_version", $currentversion);
	}
	else
	{
	
		update_option('xyz_lbx_free_version', $currentversion);
	}
	
}
//register_activation_hook(XYZ_LBX_PLUGIN_FILE,'lbx_install');
register_activation_hook( XYZ_LBX_PLUGIN_FILE ,'xyz_lbx_network_install');

?>
