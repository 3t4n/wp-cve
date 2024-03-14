<?php

/****************************************************************
Plugin Name: GPP Slideshow
Plugin URI: http://graphpaperpress.com/plugins/gpp-slideshow/
Description: Create minimalist slideshows using a new Gallery post type
Version: 1.3.5
Author: Graph Paper Press
Author URI: http://graphpaperpress.com
License: GPL
****************************************************************/

/*-----------------------------------------------------------------------------------*/
/* Include Classes and Functions */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('gpp_gallery_init')) {

	// Adds Metaboxes UI
	require_once( 'gpp_init.php' );
	require_once( 'gpp_meta.php' );
	require_once( 'gpp_scripts.php' );
	require_once( 'gpp_widget.php' );
	require_once( 'gpp_functions.php' );
	// require_once( 'gpp_ecommerce.php' );

	/*-----------------------------------------------------------------------------------*/
	/* Initiate the plugin */
	/*-----------------------------------------------------------------------------------*/

	add_action('init', 'gpp_gallery_init');
	function gpp_gallery_init() {

		define ('GPP_GALLERY_PLUGIN_URL',WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'');
		define ('GPP_GALLERY_PLUGIN_DIR',WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)).'');

	}
}

/*-----------------------------------------------------------------------------------*/
/* Add message after activation */
/*-----------------------------------------------------------------------------------*/

if ( !get_option('gpp_gallery') && get_option('permalink_structure') <> '' ) {
	function gpp_gallery_warning() {
		echo "
		<div class='updated fade'><p><strong>".__('GPP Slideshow is almost ready.')."</strong> ".sprintf(__('You must <a href="%1$s">configure your options</a> and then <a href="%2$s">update your permalinks</a> for it to work.'), "edit.php?post_type=gallery&page=gallery-options", "options-permalink.php")."</p></div>
		";
	}
		add_action('admin_notices', 'gpp_gallery_warning');
		return;
}

/*-----------------------------------------------------------------------------------*/
/* Add settings link after activation */
/*-----------------------------------------------------------------------------------*/

function gpp_gallery_add_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

	if ($file == $this_plugin){
		$settings_link = '<a href="edit.php?post_type=gallery&page=gallery-options">'.__("Options", "Galleries Options").'</a>';
 		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'gpp_gallery_add_settings_link', 10, 2 );