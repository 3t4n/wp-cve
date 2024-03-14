<?php
/*
 Plugin Name: Site Launcher
 Plugin URI: http://www.wickedcleverlabs.com/site-launcher
 Description: Lets you set a date to launch or suspend your site automatically. Lets you choose which admins have access to the plugin settings. Generates beautiful Coming Soon and Site Suspended pages with customizable background image or color and and an optional message box that can also allow users to log in from the coming soon page. Alternatively allows you to redirect to a different URL. This plugin is based on the <a href="https://wordpress.org/plugins/underconstruction/" target="_blank">underConstruction</a> plugin. A complete description along with screenshots and usage instructions is <a href="http://www.wickedcleverlabs.com/site-launcher/" target="_blank">here</a>.
 Version: 0.9.4
 Author: Saill White
 Author URI: http://www.wickedcleverlabs.com/
 Text Domain: site-launcher
 */

/*
 This file is part of Site Launcher.
 Site Launcher is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, 
 (at your option) any later version.
 Site Launcher is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Site Launcher.  If not, see <http://www.gnu.org/licenses/>.
 */

class Site_Launcher
{
	var $installed_folder = "";
	var $main_options_page = "site_launcher_main_options";

	function __construct()
	{
		$this->installed_folder = basename( dirname(__FILE__) );
		// add scripts and styles
		add_action( 'admin_print_styles', array($this, 'load_admin_styles' ) );
		add_action( 'admin_print_scripts', array($this, 'output_admin_scripts' )  );
		wp_enqueue_style( 'wp-color-picker' );
		
		add_action( 'template_redirect', array( $this, 'override_wp' ) );
		//add_action( 'plugins_loaded', array($this, 'site_launcher_init_translation' ) );
		add_action( 'admin_init', array( $this, 'register_admin_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		
		register_activation_hook(__FILE__, array($this, 'activate'));
		register_deactivation_hook(__FILE__, array($this, 'deactivate'));
		
		//ajax
		add_action( 'wp_ajax_site_launcher_get_ip_address', array( $this, 'get_ip_address' ) );

	}

	function site_launcher()
	{
		$this->__construct();
	}


	function get_main_options_page()
	{
		return $this->main_options_page;
	}
	

	function output_admin_scripts()
	{
		$admin_js = "
		<script type=\"text/javascript\">
			WebFontConfig = {
			google: { families: [ 'Special+Elite::latin', 'Playfair+Display::latin', 'Griffy::latin', 'Indie+Flower::latin', 'Open+Sans::latin',  'Poiret+One::latin', 'Philosopher::latin', 'Orbitron::latin', 'Patua+One::latin', 'Limelight::latin', 'Ubuntu::latin', 'Roboto::latin', 'Raleway::latin', 'Roboto+Slab::latin' ] }
			};
			(function() {
			var wf = document.createElement('script');
			wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
			'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
			wf.type = 'text/javascript';
			wf.async = 'true';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(wf, s);
		})(); </script>";
		
		echo $admin_js;

	}


	function load_admin_styles()
	{
		wp_register_style( 'site-launcher-admin', WP_PLUGIN_URL .'/'.$this->installed_folder.'/site-launcher-admin.css' );
		wp_enqueue_style( 'site-launcher-admin' );
		
		$userID = get_current_user_id();
		$allowed_admins = get_option( 'site_launcher_allowed_admins' );
		// if plugin has been used and current user is not on the allowed admin list, hide this plugin
		if ( $allowed_admins !== false )
		{
			if ( ! in_array($userID, $allowed_admins ) )
			{
				wp_register_style( 'site-launcher-not-auth', WP_PLUGIN_URL .'/'.$this->installed_folder.'/site-launcher-not-auth.css' );
				wp_enqueue_style( 'site-launcher-not-auth' );
			}
		}
	}

	
	function init_translation()
	{
		load_plugin_textdomain( 'site-launcher', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	
	function plugin_links( $links, $file )
	{
		if ( $file == basename( dirname(__FILE__) ).'/'.basename(__FILE__) && function_exists("admin_url") )
		{
			//add settings 
			$manage_link = '<a href="'.admin_url( 'options-general.php?page='.$this->get_main_options_page() ).'">'.__( 'Settings' ).'</a>';
			array_unshift( $links, $manage_link );
		}
		return $links;
	}

	function show_admin()
	{
		require_once ( 'site-launcher-admin.php' );
	}

	
	function get_filenames( $dir )
	{
		$fullpathname = plugin_dir_path( __FILE__ ).'/images/'.$dir;
		$filelist = array();
		if (is_dir( $fullpathname )) {
			if ( $dh = opendir( $fullpathname ) ) {
				while ( ( $file = readdir( $dh ) ) !== false) {
					if ( ( strpos( $file, '.jpg' ) || strpos( $file, '.png' ) || strpos( $file, '.gif' ) ) && $file !== 'transparent.gif' ) $filelist[] = $file;
				}
				closedir( $dh );
			}
		}
		return $filelist;
	}
	
	function register_admin_scripts()
	{
		wp_register_script( 'site-launcher-js', WP_PLUGIN_URL.'/'.$this->installed_folder.'/site-launcher.dev.js' );
		wp_register_script( 'site-launcher-color-picker-js', WP_PLUGIN_URL.'/'.$this->installed_folder.'/site-launcher-color-picker.js', array( 'wp-color-picker' ), false, true );
	}
	
	function enqueue_admin_scripts()
	{
		wp_enqueue_script('scriptaculous');
		wp_enqueue_script( 'site-launcher-js' );
		wp_enqueue_script( 'site-launcher-color-picker-js' );
	}
	
	function admin_menu()
	{
		$userID = get_current_user_id();
		$allowed_admins = get_option( 'site_launcher_allowed_admins' );
		// if plugin has not yet been used - assumed current user is installing it.
		if ( $allowed_admins === false )
		{
			/* Register our plugin page */
			$page = add_options_page( 'Site Launcher Settings', 'Site Launcher', 'activate_plugins', $this->main_options_page, array($this, 'show_admin' ) );

			/* Using registered $page handle to hook script load */
			add_action( 'admin_print_scripts-'.$page, array($this, 'enqueue_admin_scripts' ) );
		}
		
		elseif ( ( is_user_logged_in() && in_array( $userID, $allowed_admins ) ) )
		{
			/* Register our plugin page */
			$page = add_options_page( 'Site Launcher Settings', 'Site Launcher', 'activate_plugins', $this->main_options_page, array($this, 'show_admin' ) );

			/* Using registered $page handle to hook script load */
			add_action( 'admin_print_scripts-'.$page, array($this, 'enqueue_admin_scripts' ) );
		}
		
	}
	

	function override_wp()
	{	
		if ( $this->get_plugin_mode() != 'live' && $this->get_plugin_mode() != 'site_scheduled_for_suspension' )
		{
			if ( ! is_user_logged_in() || ! current_user_can( 'read' ) )
			{
				if ( $this->get_plugin_mode() == 'coming_soon' )
				{
					$ip_array = get_option( 'site_launcher_ip_whitelist' );
				}
				elseif ( $this->get_plugin_mode() == 'site_suspended' )
				{
					$ip_array = get_option( 'site_launcher_ip_whitelist_suspended' );
				}
				
				if( !is_array($ip_array) )
				{
					$ip_array = array();
				}
				
			
				// if user is not on whitelist 
				if( !in_array( $_SERVER['REMOTE_ADDR'], $ip_array ) ){
					if ( $this->get_plugin_action() == 'redirect' ) {
						//send a 503 - service unavailable code
						header( 'HTTP/1.1 503 Service Unavailable' );
						header( 'Location: '.$this->get_redirect_url() );
					} else {
						//send a 503 - service unavailable code
						header( 'HTTP/1.1 503 Service Unavailable' );

						require_once ( 'site-launcher-display.php' );
						$options = get_option( 'site_launcher_display_options' );
						display_site_down_page( $options, $this->get_plugin_mode(), WP_PLUGIN_URL.'/'.$this->installed_folder.'/' );
						die();
					}
				}
			}
		}
	}



	function activate()
	{
		if (get_option( 'site_launcher_archive' ) )
		{
			//get all the options back from the archive
			$options = get_option( 'site_launcher_archive' );

			//put them back where they belong
			update_option( 'site_launcher_mode', $options['site_launcher_mode']);
			update_option( 'site_launcher_allowed_admins', $options['site_launcher_allowed_admins']);
			update_option( 'site_launcher_display_options', $options['site_launcher_display_options']);
			update_option( 'site_launcher_ip_whitelist', $options['site_launcher_ip_whitelist']);
			update_option( 'site_launcher_ip_whitelist_suspended', $options['site_launcher_ip_whitelist_suspended']);
			update_option( 'site_launcher_launch_date', $options['site_launcher_launch_date']);
			update_option( 'site_launcher_suspend_date', $options['site_launcher_suspend_date']);
			update_option( 'site_launcher_users_to_demote', $options['site_launcher_users_to_demote']);
			update_option( 'site_launcher_users_have_been_demoted', $options['site_launcher_users_have_been_demoted']);
			delete_option( 'site_launcher_archive' );
		}
	}

	function deactivate()
	{
		
		//get all the options. store them in an array
		$options = array();
		$options['site_launcher_mode'] = get_option( 'site_launcher_mode' );
		$options['site_launcher_allowed_admins'] = get_option( 'site_launcher_allowed_admins' );
		$options['site_launcher_display_options'] = get_option( 'site_launcher_display_options' );
		$options['site_launcher_ip_whitelist'] = get_option( 'site_launcher_ip_whitelist' );
		$options['site_launcher_ip_whitelist_suspended'] = get_option( 'site_launcher_ip_whitelist_suspended' );
		$options['site_launcher_launch_date'] = get_option( 'site_launcher_launch_date' );
		$options['site_launcher_suspend_date'] = get_option( 'site_launcher_suspend_date' );
		$options['site_launcher_users_to_demote'] = get_option( 'site_launcher_users_to_demote' );
		$options['site_launcher_users_have_been_demoted'] = get_option( 'site_launcher_users_have_been_demoted' );
		//store the options all in one record, in case we ever reactivate the plugin
		update_option( 'site_launcher_archive', $options);

		//delete the separate ones
		delete_option( 'site_launcher_mode' );
		delete_option( 'site_launcher_allowed_admins' );
		delete_option( 'site_launcher_display_options' );
		delete_option( 'site_launcher_ip_whitelist' );
		delete_option( 'site_launcher_ip_whitelist_suspended' );
		delete_option( 'site_launcher_launch_date' );
		delete_option( 'site_launcher_suspend_date' );
		delete_option( 'site_launcher_users_to_demote' );
		delete_option( 'site_launcher_users_have_been_demoted' );
	}


	
	function get_display_option( $in_option_name )
	{
		$display_option = false;
		
		if ( get_option( 'site_launcher_display_options' ) !== false )
		{
			$options = get_option( 'site_launcher_display_options' );
			$display_option = stripslashes( $options[ $in_option_name ] );
		}
		
		return $display_option;
		
	}
	
	function get_allowed_admins()
	{
		// if option has never been set, update with current user id
		if ( get_option( 'site_launcher_allowed_admins' ) === false )
		{
			$userID = get_current_user_id();
			$allowed_admins = array( $userID );
			update_option( 'site_launcher_allowed_admins', $allowed_admins );
		}
		else
		{
			$allowed_admins = get_option( 'site_launcher_allowed_admins' );
		}
		return $allowed_admins;
	}
	
	function get_ip_address()
	{
		echo $_SERVER['REMOTE_ADDR'];
		die();
		
	}
	
	function get_status_message()
	{
		if ( $this->get_plugin_mode() == 'live' )
		{
			$message = _e( 'Website is LIVE!', 'site-launcher' );
		}
		elseif ( $this->get_plugin_mode() == 'coming_soon' )
		{
			if ( $this->get_site_launch_date() == 'never' )
			{
				$message = _e( 'Website is coming soon.', 'site-launcher' );
			}
			elseif ( $this->get_site_launch_date() == 'now' )
			{
				$message = _e( 'Website has been launched!', 'site-launcher' );
			}
			else
			{
				$message = _e( 'Website is scheduled for launch on: ', 'site-launcher' ).date ( 'l F jS  Y, \a\t g:i A', $this->get_site_launch_date().'.' );
			}
		}

		elseif ( $this->get_plugin_mode() == 'site_suspended' )
		{
			if ( $this->get_site_suspend_date() == 'now' )
			{
				$message = _e( 'Website has been suspended!', 'site-launcher' );
			}
		}
		elseif ( $this->get_plugin_mode() == 'site_scheduled_for_suspension' )
		{
			$message = _e( 'Website is scheduled to be suspended on: ', 'site-launcher' ).date ( 'l F jS  Y, \a\t g:i A', $this->get_site_suspend_date().'.' );
		}
		
		return $message;
	}

	// always check mode AFTER dates have been set
	function get_plugin_mode()
	{
		if ( get_option( 'site_launcher_mode' ) ) 
		{
			if  ( get_option( 'site_launcher_mode' ) == 'coming_soon' &&  ( $this->get_site_launch_date() !==  'now' ) )
			{
				$mode = 'coming_soon';
			}
			elseif ( ( get_option( 'site_launcher_mode' ) == 'site_suspended'  ||  get_option( 'site_launcher_mode' ) == 'site_scheduled_for_suspension' ) && ( $this->get_site_suspend_date() === 'now' ) )
			{
				$mode = 'site_suspended';
				$users_have_been_demoted = get_option( 'site_launcher_users_have_been_demoted' );
				if ( $users_have_been_demoted  === 'no' ) $this->demote_users();
			}
			elseif ( ( get_option( 'site_launcher_mode' ) == 'site_suspended'  ||  get_option( 'site_launcher_mode' ) == 'site_scheduled_for_suspension' ) && ( $this->get_site_suspend_date() !== 'now' ) )
			{
				$mode = 'site_scheduled_for_suspension';
			}
			else
			{
				$mode = 'live';
			}	
		}
		else	//if it's not set yet
		{
			$mode = 'live';
		}
		
		return $mode;
	}
	
	// check whether to show page or redirect to given url
	function get_plugin_action()
	{
		if ( get_option( 'site_launcher_mode' ) && get_option( 'site_launcher_action' )  && $this->get_redirect_url() ) 
		{
			if  ( get_option( 'site_launcher_mode' ) == 'coming_soon' &&  ( $this->get_site_launch_date() !==  'now' ) )
			{
				$action = get_option( 'site_launcher_action' );
			}
			elseif ( ( get_option( 'site_launcher_mode' ) == 'site_suspended'  ||  get_option( 'site_launcher_mode' ) == 'site_scheduled_for_suspension' ) && ( $this->get_site_suspend_date() === 'now' ) )
			{
				$action = get_option( 'site_launcher_action_suspended' );
			}
			else
			{
				$action = 'show_page';
			}
		}
		else	//if mode or action are not set yet, or if url is malconfigured
		{
			$action = 'show_page';
		}
		
		return $action;
	}

	function get_redirect_url()
	{
		$url = '';
		if ( get_option( 'site_launcher_mode' ) == 'coming_soon' )
		{
			$url = get_option( 'site_launcher_redirect_url' );
		}
		elseif ( get_option( 'site_launcher_mode' ) == 'site_suspended'  ||  get_option( 'site_launcher_mode' ) == 'site_scheduled_for_suspension' )
		{
			$url = get_option( 'site_launcher_redirect_url_suspended' );
		}
		
		$url = filter_var( $url, FILTER_VALIDATE_URL );
		
		return $url;
	}

	function get_site_launch_date()
	{
		// date is julian, check against current date and return false if launch date is in the past
		if ( get_option( 'site_launcher_launch_date' ) !== false )
		{	
			if ( is_numeric( get_option( 'site_launcher_launch_date' ) ) ) 
			{
				$current_time = current_time( 'timestamp' ); // use the WordPress blog time function
				if ( $current_time > get_option( 'site_launcher_launch_date' ) )
				{
					$launch_date = 'now';
				}
				else
				{
					$launch_date = get_option( 'site_launcher_launch_date' );
				}
			}
			elseif ( get_option( 'site_launcher_launch_date' ) == 'now' )
			{
				$launch_date = 'now'; // 'never' if we're in manual mode
			}
			else
			{
				$launch_date = 'never'; // 'never' if we're in manual mode
			}
		}
		else
		{
			$launch_date = 'now'; // default to live
		}
		
		return $launch_date;
	}
		
	function get_site_suspend_date()
	{
		// date is julian, check against current date and return false if suspend date is in the past
		if ( get_option( 'site_launcher_suspend_date' ) !== false )
		{	
			if ( is_numeric( get_option( 'site_launcher_suspend_date' ) ) )
			{
				$current_time = current_time( 'timestamp' ); // use the WordPress blog time function
				if ( $current_time > get_option( 'site_launcher_suspend_date' ))
				{
					$suspend_date = 'now';
				}
				else
				{
					$suspend_date = get_option( 'site_launcher_suspend_date' );
				}
			}
			else
			{
				$suspend_date = get_option( 'site_launcher_suspend_date' ); 
			}
		}
		else
		{
			$suspend_date = 'never'; // default to live
		}
		
		return $suspend_date;
	}
	
	function demote_users()
	{
		$user_id_role_strings = get_option( 'site_launcher_users_to_demote' );
		$role = get_role( 'subscriber' );
		$role->remove_cap( 'read' );
		if ( is_array( $user_id_role_strings ) )
		{
			foreach ( $user_id_role_strings as $id_role )
			{
				$bits = explode( '_', $id_role );
				$user_id = $bits[0];
				wp_update_user( array( 'ID' => $user_id, 'role' => 'subscriber' ) );
			}
			update_option( 'site_launcher_users_have_been_demoted', 'yes' );
		}
	}
		
}



function site_launcher_plugin_delete()
{
	delete_option( 'site_launcher_archive' );
}




global $site_launcher_plugin;
$site_launcher_plugin = new Site_Launcher();


register_uninstall_hook( __FILE__, 'site_launcher_plugin_delete' );

add_filter( 'plugin_action_links', array( $site_launcher_plugin,'plugin_links' ), 10, 2 );

?>
