<?php

defined( 'ABSPATH' ) || exit;

if ( !function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
global $jr_dks_plugin_data;
$jr_dks_plugin_data = get_plugin_data( JR_DKS__FILE__ );
$jr_dks_plugin_data['slug'] = basename( dirname( JR_DKS__FILE__ ) );

global $jr_dks_path;
$jr_dks_path = plugin_dir_path( JR_DKS__FILE__ );
/**
* Return Plugin's full directory path with trailing slash
* 
* Local XAMPP install might return:
*	C:\xampp\htdocs\wpbeta\wp-content\plugins\jonradio-multiple-themes/
*
*/
function jr_dks_path() {
	global $jr_dks_path;
	return $jr_dks_path;
}

global $jr_dks_plugin_basename;
$jr_dks_plugin_basename = plugin_basename( JR_DKS__FILE__ );
/**
* Return Plugin's Basename
* 
* For this plugin, it would be:
*	jonradio-display-kitchen-sink/jonradio-display-kitchen-sink.php
*
*/
function jr_dks_plugin_basename() {
	global $jr_dks_plugin_basename;
	return $jr_dks_plugin_basename;
}

/*	Check all situations where Plugin is running in
	Unsupported Environments.
*/
$incompat = '';
if ( version_compare( ( $wp_ver = get_bloginfo( 'version' ) ), '3.1', '<' ) ) {
	$incompat .= '<p>This plugin requires at least Version 3.1 of Wordpress. You are running Version ' . $wp_ver . '</p>';
}
if ( version_compare( phpversion(), '5', '<' ) ) {
	$incompat .= '<p>This plugin requires at least Version 5 of PHP. You are running Version ' . phpversion() . '</p>';;
}
if ( !empty( $incompat ) ) {
	function jr_dks_incompat() {
		/*	Need to do this on an Action hook to allow explanatory output,
			that will actually be seen,
			hopefully on a wp_die() after the Deactivation is complete.
		*/
		global $jr_dks_incompat, $jr_dks_plugin_basename;
		echo $jr_dks_incompat;
		deactivate_plugins( $jr_dks_plugin_basename );
		wp_die();
	}
	$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	if ( is_ssl() ) {
		$url = 'https://' . $url;
	} else {
		$url = 'http://' . $url;
	}
	/*	Remove any "activate=true" query
	*/
	$url = str_ireplace( 
		array( '&activate=true', 'activate=true&', '?activate=true', '&activate-multi=true', 'activate-multi=true&', '?activate-multi=true' ),
		array( '', '', '?', '', '', '?' ),
		$url
		);
	global $jr_dks_incompat;
	$jr_dks_incompat = '<h1>Fatal Error - ' . $jr_dks_plugin_data['Name'] . '</h1>'
		. $incompat
		. '<p>Plugin is being deactivated. <a href="'
		. $url
		. '">Click here</a> to continue.</p>';
	
	if ( version_compare( $wp_ver, '1.2.1', '<' ) ) {
		jr_dks_incompat();
	} else {
		if ( version_compare( $wp_ver, '2.0.11', '<' ) ) {
			$hook = 'admin_head';
		} else {
			if ( function_exists( 'is_network_admin' ) && is_network_admin() ) {
				$hook = 'network_admin_notices';
			} else {
				$hook = 'admin_notices';
			}
		}
		add_action( $hook, 'jr_dks_incompat' );
		return;
	}
}

/*	Compatible Versions of PHP and WordPress
*/
add_action( 'plugins_loaded', 'jr_dks_set_metadata' );

function jr_dks_set_metadata() {
	/*	Force Kitchen Sink to be displayed for the current User on the current Site.
		Set both User Setting and the Cookie.
		
		Faster to just check the relevant User Setting on every Admin panel,
		than try to determine when the WordPress Page/Post Editor is being used.
		
		But first, be sure User is logged in.
	*/	
	if ( 0 !== ( $user_id = get_current_user_id() ) ) {
		$settings_default = 'editor=tinymce&hidetb=1';
		$cookie_name = 'wp-settings-1';
		if ( isset( $_COOKIE[$cookie_name] ) ) {
			$cookie_value = str_ireplace( 'hidetb=0', 'hidetb=1', $_COOKIE[$cookie_name] );
		} else {
			$cookie_value = $settings_default;
		}
		/*	Determine Path off Domain to WordPress Address, not Site Address, for Cookie Path value.
			Which, confusingly enough, is site_url().
		*/
		setcookie( $cookie_name, $cookie_value, strtotime( '+1 year' ), parse_url( site_url(), PHP_URL_PATH ) . '/', $_SERVER['SERVER_NAME'] );

		global $wpdb;
		$editor_settings_name = $wpdb->prefix . 'user-settings';
		$wp_user_settings = get_user_meta( $user_id, $editor_settings_name, TRUE );
		if ( empty( $wp_user_settings ) ) {
			$settings = $settings_default;
			$update = TRUE;
		} else {
			parse_str( $wp_user_settings, $settings_array );
			if ( $update = ( ( !isset( $settings_array['hidetb'] ) ) || ( '0' === $settings_array['hidetb'] ) ) ) {
				$settings_array['hidetb'] = '1';
				$settings = build_query( $settings_array );
			}
		}
		if ( $update ) {
			/*	Build the Query and Save It
			*/	
			update_user_meta( $user_id, $editor_settings_name, $settings );
		}
	}
}

add_filter( 'tiny_mce_before_init', 'jr_dks_remove_kitchen_sink_icon' );
 
function jr_dks_remove_kitchen_sink_icon( $args ) {
	if ( version_compare( get_bloginfo( 'version' ), '3.8.99', '>' ) ) {
		$args_key = 'toolbar1';
	} else {
		$args_key = 'theme_advanced_buttons1';
	}
	if ( FALSE !== ( $icons = explode( ',', $args[$args_key] ) ) ) {
		if ( FALSE !== ( $icons_key = array_search( 'wp_adv', $icons ) ) ) {
			unset( $icons[$icons_key] );
			if ( empty( $icons ) ) {
				$args[$args_key] = '';
			} else {				
				$args[$args_key] = implode( ',', $icons );
			}
		}
	}
	return $args;
}

add_action( 'init', 'jr_dks_init' );
function jr_dks_init() {	
	if ( is_plugin_active_for_network( jr_dks_plugin_basename() ) ) {
		/*	Plugin is Network Activated
		*/
		if ( is_network_admin() ) {
			/*	Currently on a Network Admin panel.
			*/
			global $jr_dks_plugin_data;
			/*	Add Network Admin Settings page for Plugin
			*/
			add_submenu_page( 'settings.php', $jr_dks_plugin_data['Name'], 'Kitchen Sink', 'manage_network_options', 'jr_dks_network_settings', 'jr_dks_settings_page' );
			
			/* Add Link to the plugin's entry on the Network Admin "Plugins" Page, for easy access
			*/
			add_filter( 'network_admin_plugin_action_links_' . jr_dks_plugin_basename(), 'jr_dks_plugin_network_action_links', 10, 1 );
			
			/**
			* Creates Settings link right on the Network Plugins Page entry.
			*
			* Helps the user understand where to go immediately upon Activation of the Plugin
			* by creating entries on the Plugins page, right beside Deactivate and Edit.
			*
			* @param	array	$links	Existing links for our Plugin, supplied by WordPress
			* @param	string	$file	Name of Plugin currently being processed
			* @return	string	$links	Updated set of links for our Plugin
			*/
			function jr_dks_plugin_network_action_links( $links ) {
				/*	The "page=" query string value must be equal to the slug
					of the Settings admin page.
				*/
				array_unshift( $links, '<a href="' . get_bloginfo('wpurl') . '/wp-admin/network/settings.php?page=jr_dks_network_settings' . '">Network Settings</a>' );
				return $links;
			}	
		} else {
			/*	On a Site Admin panel in a WordPress Network (Multisite),
				and Plugin is Network Activated.
				
				Add entry for the plugin on the each site's Admin "Plugins" Page, 
				when Network Activated and not normally shown.
			*/
			add_action( 'pre_current_active_plugins', 'jr_dks_show_plugin' );
			
			function jr_dks_show_plugin() {
				global $wp_list_table, $jr_dks_plugin_data;	
				$wp_list_table->items[jr_dks_plugin_basename()] = $jr_dks_plugin_data;
				uasort( $wp_list_table->items, 'jr_dks_sort_plugins' );
				return;
			}
			
			function jr_dks_sort_plugins( $a, $b ) {
				return strcasecmp( $a['Name'], $b['Name'] );
			}
			
			/**
			* Creates Settings entry right on the Plugins Page entry.
			*
			* Helps the user understand where to go immediately upon Activation of the Plugin
			* by creating entries on the Plugins page, right beside Deactivate and Edit.
			*
			* @param	array	$links	Existing links for our Plugin, supplied by WordPress
			* @param	string	$file	Name of Plugin currently being processed
			* @return	string	$links	Updated set of links for our Plugin
			*/
			function jr_dks_plugin_action_links( $links ) {
				/*	Delete existing Links and replace with "Settings"
					as a link to Plugin Settings page and
					"Network Activated" (not a link).
					The "page=" query string value must be equal to the slug
					of the Settings admin page.
				*/
				return array( 
					'<a href="' 
						. get_bloginfo( 'wpurl' ) 
						. '/wp-admin/options-general.php?page=jr_dks_settings' 
						. '">Settings</a>',
					'Network Activated'
					);
			}	
		}
	} else {
		/*	Plugin is not Network Activated,
			so this must be a Site Admin panel,
			but don't know if this is a Single Site
			or part of a WordPress Network (Multisite).
		*/
		
		/**
		* Creates Settings entry right on the Plugins Page entry.
		*
		* Helps the user understand where to go immediately upon Activation of the Plugin
		* by creating entries on the Plugins page, right beside Deactivate and Edit.
		*
		* @param	array	$links	Existing links for our Plugin, supplied by WordPress
		* @param	string	$file	Name of Plugin currently being processed
		* @return	string	$links	Updated set of links for our Plugin
		*/
		function jr_dks_plugin_action_links( $links ) {
			// The "page=" query string value must be equal to the slug
			// of the Settings admin page.
			array_unshift( $links, '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=jr_dks_settings' . '">Settings</a>' );
			return $links;
		}
	}
	
	if ( !is_network_admin() ) {
		/*	Everything here is for all but Network Admin panels.
		
			Regular (non-Network) Admin page
		*/
		add_action( 'admin_menu', 'jr_dks_admin_menu' );
		
		/*	Add Link to the plugin's entry on the Admin "Plugins" Page, for easy access
		*/
		add_filter( 'plugin_action_links_' . jr_dks_plugin_basename(), 'jr_dks_plugin_action_links', 10, 1 );
		
		/**
		* Add Admin Menu item for plugin
		* 
		* Plugin needs its own Page in the Settings section of the Admin menu.
		*
		*/
		function jr_dks_admin_menu() {
			//  Add Settings Page for this Plugin
			global $jr_dks_plugin_data;
			add_options_page( $jr_dks_plugin_data['Name'], 'Kitchen Sink', 'manage_options', 'jr_dks_settings', 'jr_dks_settings_page' );
			add_pages_page( $jr_dks_plugin_data['Name'], 'Kitchen Sink', 'manage_options', 'jr_dks_settings', 'jr_dks_settings_page' );
			add_posts_page( $jr_dks_plugin_data['Name'], 'Kitchen Sink', 'manage_options', 'jr_dks_settings', 'jr_dks_settings_page' );
			add_users_page( $jr_dks_plugin_data['Name'], 'Kitchen Sink', 'manage_options', 'jr_dks_settings', 'jr_dks_settings_page' );	
		}		
	}
	require_once( plugin_dir_path( JR_DKS__FILE__ ) . 'includes/admin-settings.php' );
}
?>