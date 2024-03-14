<?php
//
//	<HEAD>
//

// Reuse the customized user <head> for admin.
require_once( dirname(__FILE__) . '/../common/head.php' );

// Hook it.
add_action( 'admin_head', 'favicon_head' );


//
//	OPTIONS PAGE
//
require_once( dirname(__FILE__) . '/options.php' );

/**
  * @uses add_options_page()
  * @since 0.1
  */
function favicon_menu() {
	// Append to the admin 'settings' menu.
	add_options_page(__( 'WP Favicon Options', WPF_DOMAIN ), __( 'WP Favicon', WPF_DOMAIN ), 8, __FILE__, 'favicon_options');
}

// hook it.
add_action( 'admin_menu', 'favicon_menu' );


//
//	PLUGIN CUSTOM LINKS
//
/** Register 'Settings' link to the plugin from plugin page.
  * @param array $links the arrays of plugin links
  * @param string $file the plugin filename as index
  * @return array
  * @uses array_merge
  * @since 0.1
  */
function set_plugin_meta($links, $file) {

	$plugin = WPF_DOMAIN . '/' . WPF_DOMAIN . '.php';
	$menu   = plugin_basename(__FILE__);

	// create link
	if ($file == $plugin) {
		return array_merge(
			$links,
			array( sprintf( '<a href="options-general.php?page=%s">%s</a>', $menu, __('Settings') ) )
		);
	}
 
	return $links;
}

// Hook it.
add_filter( 'plugin_row_meta', 'set_plugin_meta', 10, 2 );


//
//	INIT
//
function favicon_admin_init() {
	// Load translation domain
	load_plugin_textdomain( WPF_DOMAIN, false, 'wp-favicon/languages' );
}

// Hook it.
add_action( 'admin_init', 'favicon_admin_init' );
?>