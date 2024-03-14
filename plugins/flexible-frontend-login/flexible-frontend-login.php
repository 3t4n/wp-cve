<?php
/*
Plugin Name: Flexible Frontend Login
Plugin URI: http://www.flexibleplugins.com/frontend-login
Description: Adds a popup login form on any page or sidebar. Use shortcode [flexible-frontend-login] or template tag flexible_frontend_login() 
 Author: Henning Matthaei
Version: 1.0.5
Author URI: http://www.flexibleplugins.com
Text Domain: flexible-frontend-login
Domain Path: /languages
*/
	
/* Because it is more comfortable... */
define( 'FFL_PATH' , plugin_dir_path( __FILE__ ) );
define( 'FFL_URL' , plugins_url() . '/flexible-frontend-login' );
define( 'FFL_TEXTDOMAIN', 'flexible-frontend-login' );

/* Localize it! */
load_plugin_textdomain('flexible-frontend-login', false, 'flexible-frontend-login/languages');


require_once( FFL_PATH . '/includes/default-options.php' );
include( FFL_PATH . '/includes/styling.php' );
include( FFL_PATH . '/includes/output.php' );
include( FFL_PATH . '/includes/widget.php' );

/* Load options page only when in admin  */
if ( is_admin() )  require_once( FFL_PATH . '/includes/options-page.php' );


/* Store default options when plugin is activated */
register_activation_hook( __FILE__, 'paw_ffl_default_options' );

/* Add Settings link on Plugin's page*/
function paw_ffl_set_plugin_meta($links, $file) 
{
	$plugin = plugin_basename(__FILE__);
	// create link
	if ($file  == $plugin) {
		return array_merge(
		$links,
		array( sprintf( '<a href ="options-general.php?page=%s">%s</a>', 'options-general.php_flexible_frontend_login', __('Settings', 'flexible-frontend-login' ) ) )
		);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'paw_ffl_set_plugin_meta', 10, 2 );


/* Make sure that shortcode in widgets gets parsed */
if ( !is_admin() ) 
{
	add_filter( 'widget_text', 'do_shortcode', 11 ); 
	add_filter( 'widget_text', 'shortcode_unautop' );
}



?>
