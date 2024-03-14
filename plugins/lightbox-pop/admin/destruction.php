<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function xyz_lbx_network_destroy($networkwide) {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				lbx_destroy();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	lbx_destroy();
}

function lbx_destroy()
{
	global $wpdb;
	delete_option("xyz_lbx_html");
	delete_option("xyz_tinymce");
	if(get_option('xyz_credit_link')=="lbx")
	{
		update_option("xyz_credit_link", '0');
	}
	delete_option("xyz_lbx_top");
	delete_option("xyz_lbx_width");
	delete_option("xyz_lbx_height");
	delete_option("xyz_lbx_left");
	
	delete_option("xyz_lbx_enable");
	delete_option("xyz_lbx_showing_option");
	delete_option("xyz_lbx_adds_enable");
	delete_option("xyz_lbx_cache_enable");
	
	delete_option("xyz_lbx_delay");
	delete_option("xyz_lbx_page_count");
	delete_option("xyz_lbx_mode"); //page_count_only,both are other options
	delete_option("xyz_lbx_repeat_interval");
	delete_option("xyz_lbx_repeat_interval_timing");//hrs or  minute
	delete_option("xyz_lbx_z_index");
	delete_option("xyz_lbx_color");	
	delete_option("xyz_lbx_corner_radius");
	delete_option("xyz_lbx_width_dim");
	delete_option("xyz_lbx_height_dim");
	delete_option("xyz_lbx_left_dim");
	delete_option("xyz_lbx_top_dim");
	delete_option("xyz_lbx_border_color");
	delete_option("xyz_lbx_bg_opacity");
	delete_option("xyz_lbx_bg_color");
	delete_option("xyz_lbx_opacity");
	delete_option("xyz_lbx_border_width");
	delete_option("xyz_lbx_page_option");
	delete_option("xyz_lbx_close_button_option");
	delete_option("xyz_lbx_iframe");
	
	delete_option("xyz_lbx_positioning");
	delete_option("xyz_lbx_position_predefined");
	delete_option("xyz_lbx_display_position");
	
	delete_option("lightbox_installed_date");
	delete_option("xyz_lbx_display_user");
	
}

register_uninstall_hook(XYZ_LBX_PLUGIN_FILE,'xyz_lbx_network_destroy');


?>