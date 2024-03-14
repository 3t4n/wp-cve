<?php
if (!function_exists('add_action')) die('Access denied');

add_action("admin_init", "wppano_AddMetabox");

function wppano_AddMetabox() {
	$posttypes = get_option('wppano_posttype');
	if( $posttypes && $posttypes['type'] ) 
		foreach ( $posttypes['type'] as $posttype )
			add_meta_box('customMeta', 'WP Pano', 'wppano_meta_add_new_hotspot', $posttype, 'normal', 'high');
}

function wppano_meta_add_new_hotspot() {
	global $post;
	$post_id = $post->ID;
	if ( function_exists ('pll_get_post') && $pll_post_id = pll_get_post( $post->ID , pll_default_language()) ) $post_id = $pll_post_id;
	$hotspots = wppano_get_hotspots_by_post_id($post_id);
	$vtourpath =  SITE_HOMEPATH . get_option('wppano_vtourpath') . '/';
	$js_url = $vtourpath . get_option('wppano_vtourjs');
	$xml_url = $vtourpath . get_option('wppano_vtourxml');
	$swf_url = $vtourpath . get_option('wppano_vtourswf');
	$post_types = get_option('wppano_posttype');
	$hs_styles = $post_types['hs_style'];
	$hs_style = $hs_styles[get_post_type($post_id)];
	
	require_once('admin-renderpano.php');

	// if ( is_file(get_home_path() . get_option('wppano_vtourpath') . '/' . get_option('wppano_vtourjs')) && 
		 // is_file(get_home_path() . get_option('wppano_vtourpath') . '/' . get_option('wppano_vtourxml')) && 
		 // is_file(get_home_path() . get_option('wppano_vtourpath') . '/' . get_option('wppano_vtourswf')) )
		// require_once('admin-renderpano.php');
		// else
		// require_once('admin-rendererror.php');
} ?>