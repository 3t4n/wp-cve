<?php
/*
	Plugin Name: MSTW Team Rosters
	Plugin URI: http://wordpress.org/extend/plugins/team-rosters/
	Description: Creates roster tables, player galleries, and single player pages.
	Version: 4.6
	Author: Mark O'Donnell
	Author URI: http://shoalsummitsolutions.com
	Text Domain: team-rosters
	Domain Path: /lang
*/

/*---------------------------------------------------------------------------
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-23 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
 
 // set up the text domain to be used globally
 $TEXT_DOMAIN = 'team-rosters';

 //-----------------------------------------------------------------
 // Initialize the plugin
 //
 add_action( 'init', 'mstw_tr_init' );

 function mstw_tr_init( ) {
	//mstw_log_msg( 'mstw_tr_init:' );
	//
	// Utility functions for the MSTW family of plugins
	//
	require_once ( plugin_dir_path( __FILE__ ) . 'includes/mstw-utility-functions.php' );
	
	//------------------------------------------------------------------------
	// Utility functions specific to Team Rosters [functions are wrapped]
	//
	require_once ( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-utility-functions.php' );
	
	//------------------------------------------------------------------------
	// Functions for mstw-roster-table shortcode
	//
	include_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-roster-table.php' );
	
	//------------------------------------------------------------------------
	// Class for mstw-roster-table-2 shortcode (and maybe mstw-roster-table someday)
	//
	include_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-roster-tables-class.php' );
	$rosters_class = new MSTW_ROSTER_TABLE;
	
	//------------------------------------------------------------------------
	// Functions for MSTW roster gallery shortcode
	//
	require_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-roster-gallery.php' );
	
	//------------------------------------------------------------------------
	// REGISTER THE MSTW TEAM ROSTERS CUSTOM POST TYPES & TAXONOMIES
	//	mstw_tr_player, mstw_tr_team
	//
	include_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-cpts.php' );
	mstw_tr_register_cpts( );
	
	//-----------------------------------------------------------------
	// find the single-player template in the plugin's directory
	//
	add_filter( "single_template", "mstw_tr_single_player_template" );
	
	//-----------------------------------------------------------------
	// find the taxonomy_team template in the plugin's directory
	//
	add_filter( "taxonomy_template", "mstw_tr_taxonomy_team_template" );

	//-----------------------------------------------------------------
	// If on an admin screen, load the admin functions (gotta have 'em)
	//
	if ( is_admin( ) ) {
		include_once ( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-admin.php' );
	}

 } //End: mstw_tr_init( )
 
 // ----------------------------------------------------------------
 // add ajax action for the manage rosters screen
 // mstw_bb_ajax_callback( ) is in mstw-bb-admin.php
 //
 add_action( 'wp_ajax_team_rosters', 'mstw_tr_ajax_callback' );

 //-----------------------------------------------------------------
 // add the shortcodes (priority 99 == last)
 //
 add_action( 'init', 'mstw_tr_add_shortcodes', 99 );	
	
 function mstw_tr_add_shortcodes( ) {
	//mstw_log_msg( 'mstw_tr_add_shortcodes:' );
	 
	remove_shortcode( 'mstw-tr-gallery' );
	add_shortcode( 'mstw-tr-gallery', 'mstw_tr_roster_gallery_handler' );
	
	remove_shortcode( 'mstw_tr_gallery' );
	add_shortcode( 'mstw_tr_gallery', 'mstw_tr_roster_gallery_handler' );
	
	remove_shortcode( 'mstw-tr-roster' );
	add_shortcode( 'mstw-tr-roster', 'mstw_tr_roster_table_handler' );
	
	remove_shortcode( 'mstw_tr_roster' );
	add_shortcode( 'mstw_tr_roster', 'mstw_tr_roster_table_handler' );
	
 }

 // ----------------------------------------------------------------
 // On activation, check the version of WP and set up the 'mstw_tr'
 //		roles and capabilites
 //
 register_activation_hook( __FILE__, 'mstw_tr_register_activation_hook' );	

 function mstw_tr_register_activation_hook( ) {
	// Need mstw-tr-utility-functions.php for mstw_tr_log_msg() 
	require_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-utility-functions.php' );
	
	// In this file
	mstw_tr_check_wp_version( '4.0' ); 
	
	// This is not currently used; just good practice
	update_option( 'mstw_team_rosters_activated', 1 );
	
	mstw_tr_add_user_roles( );
	
 } //End: mstw_tr_register_activation_hook()

	
 // ----------------------------------------------------------------
 // Create the mstw_tr_admin role on activation
 //
 if( !function_exists( '' ) ) {
	function mstw_tr_check_wp_version( $version = '4.0' ) {
		//mstw_log_msg( 'mstw_tr_check_wp_version:' );
		
		global $wp_version;
		
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );

		if ( version_compare( $wp_version, $version, "<" ) ) {

			// plugin shouldn't be active, but just in case ...
			if( is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
			}
				
			$die_msg = sprintf( __( '%s requires WordPress %s or higher, and has been deactivated! Please upgrade WordPress and try again.', 'team-rosters' ), $plugin_data['Name'], $version );
			
			die( $die_msg );

		}
	} //End: mstw_tr_check_wp_version()
 }
 
 //------------------------------------------------------------------------
 // Creates the MSTW Team Roster roles and adds the MSTW capabilities
 // to those roles as well as the WP administrator and editor roles
 //
 if( !function_exists( 'mstw_tr_add_user_roles' ) ) {
	function mstw_tr_add_user_roles( ) {
		//mstw_log_msg( 'mstw_tr_add_user_roles:' );
		//
		// mstw_admin role - can do everything in all MSTW plugins
		//
		
		//This allows a reset of capabilities for development
		remove_role( 'mstw_admin' );
		
		$role = 	add_role( 'mstw_admin',
							  __( 'MSTW Admin', 'team-rosters' ),
							  array( 'manage_mstw_plugins'  => true,
									 'edit_posts' => true
									 //true allows; use false to deny
									) 
							 );
							 
		// add_role() failed, so try to get it					 
		if( null == $role ) {
			$role = get_role( 'mstw_admin' );
		}
								 
		if ( $role != null ) {
			$role->add_cap( 'view_mstw_menus' );
			$role->add_cap( 'edit_mstw_tr_settings' );
			$role->add_cap( 'manage_tr_teams' );
			mstw_tr_add_caps( $role, null, 'player', 'players' );
		}
		else 
			mstw_tr_log_msg( "Oops, failed to add MSTW Admin role. Already exists?" );
		
		//
		// mstw_tr_admin role - can do everything in Schedules & Scoreboards plugin
		//
		
		//This allows a reset of capabilities for development
		remove_role( 'mstw_tr_admin' );
		
		$role = 	add_role( 'mstw_tr_admin',
							  __( 'MSTW Team Rosters Admin', 'mstw-schedules-scoreboards' ),
							  array( 'manage_mstw_schedules'  => true, 
									  'read' => true
									  //true allows; use false to deny
									) 
							 );
		
		if ( $role != null ) {
			$role->add_cap( 'view_mstw_tr_menus' );
			$role->add_cap( 'edit_mstw_tr_settings' );
			$role->add_cap( 'manage_tr_teams' );
			mstw_tr_add_caps( $role, null, 'player', 'players' );
		}
		else {
			mstw_tr_log_msg( "Oops, failed to add MSTW Schedules & Scoreboards Admin role. Already exists?" );
		}
	
		//
		// site admins can play freely
		//
		$role = get_role( 'administrator' );
		$role->add_cap( 'view_mstw_tr_menus' );
		$role->add_cap( 'edit_mstw_tr_settings' );
		$role->add_cap( 'manage_tr_teams' );
		mstw_tr_add_caps( $role, null, 'player', 'players' );
		
		//
		// site editors can play freely
		//
		$role = get_role( 'editor' );
		$role->add_cap( 'view_mstw_tr_menus' );
		$role->add_cap( 'edit_mstw_tr_settings' );
		$role->add_cap( 'manage_tr_teams' );
		mstw_tr_add_caps( $role, null, 'player', 'players' );
		
	} //End: mstw_tr_add_user_roles( )
}

//------------------------------------------------------------------------
// Adds the MSTW capabilities to either the $role_obj or $role_name using
//		the custom post type names (from the capability_type arg in
//		register_post_type( )
//
//	ARGUMENTS:
//		$role_obj: a WP role object to which to add the MSTW capabilities. Will
//					be used of $role_name is none (the default)
//		$role_name: a WP role name to which to add the MSTW capabilities. Will
//					be used if present (not null)
//		$cpt: the custom post type for the capabilities 
//				( map_meta_cap is set in register_post_type() )
//		$cpt_s: the plural of the custom post type
//				( $cpt & $cpt_s must match the capability_type argument
//					in register_post_type( ) )
//	RETURN: none
//
if( !function_exists( 'mstw_tr_add_caps' ) ) {
	function mstw_tr_add_caps( $role_obj = null, $role_name = null, $cpt, $cpt_s ) {
		//mstw_log_msg( 'mstw_tr_add_caps:' );
		
		$cap = array( 'edit_', 'read_', 'delete_' );
		$caps = array( 'edit_', 'edit_others_', 'publish_', 'read_private_', 'delete_', 'delete_published_', 'delete_others_', 'edit_private_', 'edit_published_' );
		
		if ( $role_name != null ) {
			$role_obj = get_role( $role_name );
		}
		
		if( $role_obj != null ) {
			//'singular' capabilities
			foreach( $cap as $c ) {
				$role_obj -> add_cap( $c . $cpt );
			}
			
			//'plural' capabilities
			foreach ($caps as $c ) {
				$role_obj -> add_cap( $c . $cpt_s );
			}
			
			$role_obj -> add_cap( 'read' );
		}
		else {
			$role_name = ( $role_name == null ) ? 'null' : $role_name;
			mstw_tr_log_msg( 'Bad args passed to mstw_tr_add_caps( ). $role_name = ' . $role_name . ' and $role_obj = null' );
		}
		
	} //End: mstw_tr_add_caps( )
}
	
 //-----------------------------------------------------------------
 // filter the single_player template. first look for single-player.php 
 //	in the current theme directory, just in case a user wants to get fancy,
 // then look in the plugin's /theme-templates directory
 //
 // filter is now part of the init action - mstw_tr_init()
 // add_filter( "single_template", "mstw_tr_single_player_template", 11 );
 //
 
 function mstw_tr_single_player_template( $single_template ) {
	//mstw_log_msg( 'mstw_tr_single_player_template:' );
	global $post;
		
	if ( $post->post_type == 'mstw_tr_player' ) {
		
		$custom_single_template = get_stylesheet_directory( ) . '/single-player.php';
		$plugin_single_template = dirname( __FILE__ ) . '/theme-templates/single-player.php';
		
		if ( file_exists( $custom_single_template ) ) {
			$single_template = $custom_single_template;
		}
		else if ( file_exists( $plugin_single_template ) ) {
			$single_template = $plugin_single_template;
		}
		
	}
		 
	return $single_template;
		 
 } //End: mstw_tr_single_player_template()	
 
 //-----------------------------------------------------------------
 // Filter the player gallery template ... 
 // First look for taxonomy-team.php in the current theme directory, 
 // just in case a user wants to get fancy, then look in the plugin's 
 // theme-templates directory
 //
 // Filter is now part of the init action - mstw_tr_init()
 // add_filter( "taxonomy_template", "mstw_tr_taxonomy_team_template" );
 //
 
 function mstw_tr_taxonomy_team_template( $template ) {
	 //mstw_log_msg( 'mstw_tr_taxonomy_team_template:' );

	if ( 'mstw_tr_team' == get_query_var( 'taxonomy' ) ) {	
		$custom_taxonomy_template = get_stylesheet_directory( ) . '/taxonomy-team.php';
		$plugin_taxonomy_template = dirname( __FILE__ ) . '/theme-templates/taxonomy-team.php';
		
		if ( file_exists( $custom_taxonomy_template ) ) {
			$template = $custom_taxonomy_template;
		}
		else if ( file_exists( $plugin_taxonomy_template ) ) {
			$template = $plugin_taxonomy_template;
		}	
	}
		 
	return $template;
		 
 } //End: mstw_tr_taxonomy_team_template( )	

//------------------------------------------------------------------------
// Add some links to the plugins page
//
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'mstw_tr_plugin_action_links', 10, 2 );

function mstw_tr_plugin_action_links( $links, $file ) {
	//mstw_log_msg( "mstw_tr_plugin_action_links: file= $file" );
	
	$this_plugin = plugin_basename( __FILE__ );

	if ( $file == $this_plugin ) {
		
		$site_url = site_url( '/wp-admin/admin.php?page=mstw-lm-settings' );

		$settings_link = "<a href='$site_url'>Settings</a>";
	
		array_unshift( $links, $settings_link );
	}
	
	return $links;
}
	
 // ----------------------------------------------------------------
 // Add the CSS code to the header
 //
 add_filter( 'wp_head', 'mstw_tr_add_css');
		
function mstw_tr_add_css( ) {
	//mstw_log_msg( "mstw_tr_add_css:" );
	
	$options = get_option( 'mstw_tr_options' );
	
	echo '<style type="text/css">';
	//
	// Roster Table settings
	//
	echo ".mstw-tr-table thead tr th { \n";
		echo mstw_tr_build_css_rule( $options, 'table_head_text', 'color' );
		echo mstw_tr_build_css_rule( $options, 'table_head_bkgd', 'background-color' );
	echo "} \n";
	
	echo "h1.mstw-tr-roster-title { \n";
		echo mstw_tr_build_css_rule( $options, 'table_title_color', 'color' );		
	echo "} \n";
	
	echo '.mstw-tr-table tbody tr:nth-child(odd) td {';//'tr.mstw-tr-odd {';
		echo mstw_tr_build_css_rule( $options, 'table_odd_row_text', 'color' );
		echo mstw_tr_build_css_rule( $options, 'table_odd_row_bkgd', 'background-color' );
	echo '}';
	
	echo '.mstw-tr-table tbody tr:nth-child(even) td {' ; //'tr.mstw-tr-even {';
		echo mstw_tr_build_css_rule( $options, 'table_even_row_text', 'color' );
		echo mstw_tr_build_css_rule( $options, 'table_even_row_bkgd', 'background-color' );
	echo '}';
	
	echo ".mstw-tr-table tbody tr:nth-child(even) td a, 
		  .mstw-tr-table tbody tr:nth-child(odd) td a	{ \n";
		echo mstw_tr_build_css_rule( $options, 'table_links_color', 'color' );
	echo "} \n";
	
	echo '.mstw-tr-table tbody tr td,
		 .mstw-tr-table tbody tr td {';
		echo mstw_tr_build_css_rule( $options, 'table_border_color', 'border-top-color' );
		echo mstw_tr_build_css_rule( $options, 'table_border_color', 'border-bottom-color' );
		echo mstw_tr_build_css_rule( $options, 'table_border_color', 'border-left-color' );
		echo mstw_tr_build_css_rule( $options, 'table_border_color', 'border-right-color' );
	echo '}';
	
	echo '.mstw-tr-table tbody tr td img {';
		echo mstw_tr_build_css_rule( $options, 'table_photo_width', 'width', 'px' );
		echo mstw_tr_build_css_rule( $options, 'table_photo_height', 'height', 'px' );
	echo '}';
	
	//
	// Roster Table 2 Settings
	//
	echo "div.roster-sort-controls h1.mstw-tr-roster-title { \n";
		echo mstw_tr_build_css_rule( $options, 'table2_title_color', 'color' );		
	echo "} \n";
	
	echo "div.mstw-tr-roster-player-bio a, div.mstw-tr-roster-player-number-name h3.player-name a { \n";
		echo mstw_tr_build_css_rule( $options, 'table2_links_color', 'color' );
	echo "} \n";
	
	echo '.mstw-tr-roster-player-pertinents .mstw-tr-roster-player-number-name span.jersey {';
		echo mstw_tr_build_css_rule( $options, 'table2_jersey_text', 'color' );
		echo mstw_tr_build_css_rule( $options, 'table2_jersey_bkgd', 'background-color' );
	echo '}';
	
	echo 'li.mstw-tr-roster-player:nth-child(even) div.mstw-tr-roster-player-container {' ; 
		echo mstw_tr_build_css_rule( $options, 'table2_even_row_text', 'color' );
		echo mstw_tr_build_css_rule( $options, 'table2_even_row_bkgd', 'background-color' );
	echo '}';
	
	echo 'li.mstw-tr-roster-player:nth-child(odd) div.mstw-tr-roster-player-container {' ;
		echo mstw_tr_build_css_rule( $options, 'table2_odd_row_text', 'color' );
		echo mstw_tr_build_css_rule( $options, 'table2_odd_row_bkgd', 'background-color' );
	echo '}';

	//
	// Player Profile Settings
	//
	echo ".player-header { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_main_bkgd_color', 'background-color' );
	echo "} \n";
	
	echo "#player-name-nbr { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
	echo "} \n";
	
	echo "table#player-info-table { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
	echo "} \n";
	
	echo ".player-bio { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_bio_border_color', 'border-color' );
	echo '}';
	
	echo ".player-bio h1, .player-bio h2, .player-bio h3 { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_bio_header_color', 'color' );
	echo "}\n";
	
	echo ".player-bio { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_bio_text_color', 'color' );
	echo "}\n";
	
	echo ".player-bio { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_bio_bkgd_color', 'background-color' );
	echo "}\n";
	
	echo "h1.player-head-title, .player-team-title { \n";
		echo mstw_tr_build_css_rule( $options, 'table_title_color', 'color' );
	echo "}\n";
	
	echo "h1.mstw_tr_roster_title { \n";
		echo mstw_tr_build_css_rule( $options, 'table_title_color', 'color' );
	echo "}\n";
	
	echo "div#player-photo img, div#team-logo img { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_image_width', 'width', 'px' );
		echo mstw_tr_build_css_rule( $options, 'sp_image_height', 'height', 'px' );
	echo "}\n";
	
	echo "table#player-info { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
	echo "}\n";
	
	//
	// Player Gallery Settings
	//
	echo ".player-tile { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_main_bkgd_color', 'background-color' );
	echo "} \n";
	
	//echo ".player-tile { \n";
	//	echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
	//echo "} \n";
	
	echo ".player-tile img { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_image_width', 'width', 'px' );
		echo mstw_tr_build_css_rule( $options, 'sp_image_height', 'height', 'px' );
	echo "} \n";
	
	echo ".player-name-number { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
		
	echo "} \n";
	
	echo ".player-name-number .player-name a:link, .player-name-number .player-name a:visited { \n";
		echo mstw_tr_build_css_rule( $options, 'gallery_links_color', 'color' );
	echo "}\n";
	
	echo ".player-info-container table.player-info { \n";
		echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
	echo "}\n";
	
	
	
	echo '</style>';
	
}
	
// ----------------------------------------------------------------
// Set up localization (internationalization)

	add_action( 'init', 'mstw_tr_load_localization' );
		
	function mstw_tr_load_localization( ) {
		//mstw_log_msg( "mstw_tr_load_localization:" );
		
		load_plugin_textdomain( 'team-rosters', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		
	}

// ----------------------------------------------------------------
// Want to show player post type on category pages
//
// THIS THING IS NOT RIGHT!!
//
	add_filter( 'pre_get_posts', 'mstw_tr_get_posts' );

	function mstw_tr_get_posts( $query ) {
		//mstw_log_msg( "mstw_tr_get_posts:" );

		if( !is_admin( ) ) {
		// Need to check the need for this first conditional ... someday
		if ( is_category( ) && $query->is_main_query( ) )
			$query->set( 'post_type', array( 'post', 'mstw_tr_player' ) ); 
			if ( is_tax( 'mstw_tr_team' ) && $query->is_main_query( ) ) {
				// We are on the player gallery page ...
				// So set the sort order based on the admin settings
				$options = get_option( 'mstw_tr_options' );
			
				// Need the team slug to set query
				$uri_array = explode( '/', $_SERVER['REQUEST_URI'] );	
				$team_slug = $uri_array[sizeof( $uri_array )-2];
				
				// sort alphabetically by last name ascending by default
				$query->set( 'post_type', 'mstw_tr_player' );
				$query->set( 'mstw_tr_team' , $team_slug );
				$query->set( 'orderby', 'meta_value' );  
				$query->set( 'meta_key', 'player_last_name' );   
				$query->set( 'order', 'ASC' );
				
				if ( array_key_exists( 'tr_pg_sort_order', (array)$options ) ) {
					if ( $options['tr_pg_sort_order'] == 'numeric' ) {
						// sort by number ascending
						$query->set( 'post_type', 'mstw_tr_player' );
						$query->set( 'mstw_tr_team' , $team_slug );
						$query->set( 'orderby', 'meta_value_num' );    
						$query->set( 'meta_key', 'player_number' );     
						$query->set( 'order', 'ASC' );
					}	 
				}
			}
		}
	}  //End: mstw_tr_get_posts()

// ----------------------------------------------------------------
// Deactivate, request upgrade, and exit if WP version is not right

	//add_action( 'admin_init', 'mstw_tr_requires_wp_ver' );

	function mstw_tr_requires_wp_ver() {
		//mstw_log_msg( "mstw_tr_requires_wp_ver:" );
		global $wp_version;
		
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );

		if ( version_compare($wp_version, "4.0", "<" ) ) {
			if( is_plugin_active($plugin) ) {
				deactivate_plugins( $plugin );
				wp_die( "'".$plugin_data['Name']."' requires WordPress 3.4.2 or higher, and has been deactivated! 
					Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
			}
		}
	}

 // ----------------------------------------------------------------
 // Load the CSS styles and Java scripts
 //
 add_action( 'wp_enqueue_scripts', 'mstw_tr_enqueue_styles' );

 function mstw_tr_enqueue_styles( ) {	
	//mstw_log_msg( "mstw_tr_enqueue_styles:" );
	
	// Find the full path to the plugin's CSS file
	$plugin_stylesheet = dirname( __FILE__ ) . '/css/mstw-tr-styles.css';
	
	// If stylesheet exists, which it should, enqueue the style
	if ( file_exists( $plugin_stylesheet ) ) {	
		$plugin_style_url = plugins_url( '/css/mstw-tr-styles.css', __FILE__ );
		//mstw_log_msg( "stylesheet_url: $plugin_stylesheet" );
		//wp_register_style( 'mstw_tr_style', $plugin_style_url );
		wp_enqueue_style( 'mstw_tr_style', $plugin_style_url );
	}

	// Check if a custom stylesheet exists in the current theme's directory;
	// if so, enqueue it too. it MUST be named mstw-tr-custom-styles.css
	$custom_stylesheet = get_stylesheet_directory( ) . '/mstw-tr-custom-styles.css';
	
	if ( file_exists( $custom_stylesheet ) ) {
		$custom_stylesheet_url = get_stylesheet_directory_uri( ) . '/mstw-tr-custom-styles.css';
		wp_register_style( 'mstw_tr_custom_style', $custom_stylesheet_url );
		wp_enqueue_style( 'mstw_tr_custom_style' );
	}
	
	// for the sort arrows in roster tables on the front end
	wp_enqueue_style( 'dashicons' );
	
	//
	// Enqueue the front side javascript for loading team colors on the front side
	//
	if( !is_admin( ) ) {
		// For loading team colors on the front side
		wp_enqueue_script(  'tr-load-team-colors', 
							plugins_url( 'team-rosters/js/tr-load-team-colors.js' ), 
							array( 'jquery' ), 
							false, 
							true 
						 );
	
		// For sorting the roster tables on the front side
		wp_enqueue_script(  'tr-sort-roster-table', 
							plugins_url( 'team-rosters/js/tr-sort-roster-table.js' ), 
							array( 'jquery' ), 
							false, 
							true 
						 );
						 
		// For changing the player on the single player page template
		wp_enqueue_script(  'tr-select-player', 
							plugins_url( 'team-rosters/js/tr-select-player.js' ), 
							array( 'jquery' ), 
							false, 
							true 
						 );
						 
		// For changing the sorting of team-roster-2 shortcode rosters
		wp_enqueue_script(  'tr-sort-roster-2', 
							plugins_url( 'team-rosters/js/tr-team-roster-2-ajax.js' ), 
							array( 'jquery' ), 
							false, 
							true 
						 );
		
		//wp_localize_script( 'multi-team-schedule-ajax', 'mstw_multi_team_schedule_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );		
		wp_localize_script( 'tr-sort-roster-2', 'mstw_tr_sort_roster_2_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
						 
						 
	} //End: if( !is_admin( ) )
		
 } //End: mstw_tr_enqueue_styles( )

 // First want to make sure thumbnails are active in the theme before adding them via the 
 //	register_post_type call in the 'init' action
 //
 add_action( 'after_setup_theme', 'mstw_tr_add_feat_img' );
	
function mstw_tr_add_feat_img( ) {
	//mstw_log_msg( "mstw_tr_add_feat_img:" );
	if ( !current_theme_supports( 'post-thumbnails' ) ) {
		add_theme_support( 'post-thumbnails' );
	}
}

//------------------------------------------------------------------------------
//	mstw_tr_build_css_rule - builds css rules
//		Arguments:
//			$options: array of options (settings DB)
//			$option_key: key for options in array 
//			$css_base: base for css rule (e.g. 'background-color' )
//			$suffix: string to add on end of rule (E.G. 'px' at the end of width)
//		Returns:
//			css rule "css_base:options[option_key]; \n"
//				or "" on an error	
//	
function mstw_tr_build_css_rule( $options, $option_key, $css_base, $suffix='' ) {
	//mstw_log_msg( "mstw_tr_build_css_rule:" );
	
	if ( isset( $options[$option_key] ) and !empty( $options[$option_key] ) ) {
		return $css_base . ":" . $options[$option_key] . $suffix . "; \n";	
	} 
	else {
		return "";
	}
} //end: mstw_tr_build_css_rule()

// ----------------------------------------------------------------
// add ajax action for sorting the league roster in mstw-roster-table-2 shortcode
//
add_action( 'wp_ajax_sort_roster', 'mstw_tr_sort_roster_ajax_callback' );
add_action( 'wp_ajax_nopriv_sort_roster', 'mstw_tr_sort_roster_ajax_callback' );
 
//-----------------------------------------------------------------
// mstw_tr_sort_roster_ajax_callback - sorts the roster in mstw-tr-roster-2 shortcode
//
//	ARGUMENTS: 
//		None. AJAX post is global.
//	
//	RETURNS:
//		$response: JSON response to the AJAX post (including error messages)
//
function mstw_tr_sort_roster_ajax_callback ( ) {
	//mstw_log_msg( 'mstw_tr_sort_roster_ajax_callback:' );

	//global $wpdb;  //this provides access to the WP DB ??

	$response = array( 
								'response' => 'sorted',
								'table_id' => '',
								'html'     => '',
								'error'    => ''
								);

	if ( array_key_exists( 'real_action', $_POST ) ) {
		
		$action = $_POST['real_action'];

		switch( $action ) {
			case 'sort_roster':
				$team = $_POST[ 'team' ];
				
				$table_id = $_POST[ 'table_id' ];
				$response[ 'table_id' ] = $table_id;
				
				$args_str = stripslashes( $_POST[ 'args_str' ] );
				
				$sort_value = $_POST[ 'sort_value' ];

				$html = do_shortcode( "[mstw_tr_roster_2 $args_str table_id=$table_id no_controls=1 sort_order=$sort_value ]" );
				
				$response[ 'html' ] = $html;
				break;
				
			default:
				mstw_log_msg( "Error: Invalid action, $action, on page: " . $_POST['page'] );
				$response['error'] = __( 'AJAX Error: invalid action.', 'mstw-league-manager' );
				break;
		}
		
	} else {
		$response['error'] = __( 'AJAX Error: no action found.', 'mstw-league-manager' );
		
	}

	echo json_encode( $response );

	wp_die( ); //gotta have this to keep server straight

} //End: mstw_lm_ml_ajax_callback( )