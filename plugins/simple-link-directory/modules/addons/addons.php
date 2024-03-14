<?php
define( 'sld_addon_url', plugin_dir_url(__FILE__) );
define( 'sld_SCRIPT_DEBUG', true );
add_action('admin_menu', 'sld_addon_page', 999);
function sld_addon_page(){
	add_submenu_page(
  		'edit.php?post_type=sld',
  		esc_html('AddOns'),
  		esc_html('AddOns'),
  		'manage_options',
  		'sld-addons-page',
  		'sld_addon_page_cb'
  	);
}

function sld_addon_page_cb(){
	require_once('admin_ui2.php');
}