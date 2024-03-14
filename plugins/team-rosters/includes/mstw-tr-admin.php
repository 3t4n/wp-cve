<?php
/*
 *	This is the admin portion of the MSTW Team Rosters Plugin
 *	It is loaded conditioned on is_admin() 
 */

/*-----------------------------------------------------------------------------------
Copyright 2012-22  Mark O'Donnell  (email : mark@shoalsummitsolutions.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

Code from the CSV Importer plugin was modified under that plugin's 
GPLv2 (or later) license from Smackcoders. 

Code from the File_CSV_DataSource class was re-used unchanged under
that class's MIT license & copyright (2008) from Kazuyoshi Tlacaelel. 
-----------------------------------------------------------------------------------*/

// --------------------------------------------------------------------------------------
// Set-up Action and Filter Hooks for the Settings on the admin side
// --------------------------------------------------------------------------------------
//register_uninstall_hook(__FILE__, 'mstw_tr_delete_plugin_options');

// --------------------------------------------------------------------------------------
// Callback for: register_uninstall_hook(__FILE__, 'mstw_tr_delete_plugin_options')
// --------------------------------------------------------------------------------------
// It runs when the user deactivates AND DELETES the plugin. 
// It deletes the plugin options DB entry, which is an array storing all the plugin options
// --------------------------------------------------------------------------------------
//function mstw_tr_delete_plugin_options() {
//	delete_option('mstw_tr_options');
//

// ----------------------------------------------------------------
// Load the stuff admin needs
// This is called from the init hook in mstw-team-rosters.php
//

global $team_tax_admin;

if ( !is_admin( ) ) {

	die( __( 'You is no admin. You a cheater!', 'team-rosters' ) );
}
//
// Include the necessary files
//
include_once 'mstw-tr-player-cpt-admin.php';

include_once 'mstw-tr-team-roster-admin-class.php';

include_once 'mstw-tr-settings.php';

//include_once 'mstw-tr-new-settings-class.php';

//include_once 'mstw-tr-team-cpt-admin-class.php';

include_once 'mstw-tr-team-tax-admin-class.php';

//
// Need to to this up-front, or else actions & filters are not set in time
//
if ( class_exists( 'MSTW_TR_TEAM_TAX_ADMIN' ) ) {
	$team_tax_admin = new MSTW_TR_TEAM_TAX_ADMIN;
} else {
	mstw_tr_log_msg( "MSTW_TR_TEAM_TAX_ADMIN does not exist" );
}

if ( !empty ( $team_tax_admin ) ) {
	add_action( 'created_mstw_tr_team', 
				array( $team_tax_admin, 'create_team_meta' ), 10, 2 );
				
	add_action( 'edit_mstw_tr_team', 
					array( $team_tax_admin, 'edit_team_meta' ), 10, 2 );
					
	add_filter( 'manage_edit-mstw_tr_team_columns', array( $team_tax_admin,'manage_team_columns' ), 10, 2 );
	
	add_filter( 'manage_edit-mstw_tr_team_sortable_columns', array( $team_tax_admin, 'set_sortable_columns' ) );
	
	add_filter( 'manage_mstw_tr_team_custom_column', array( $team_tax_admin, 'fill_custom_columns' ), 10, 3 );
	
	add_filter( 'mstw_tr_team_row_actions', array( $team_tax_admin, 'team_row_actions' ) );
	
} //End: if ( !empty ( $team_tax_admin )

// ----------------------------------------------------------------	
// Add admin scripts: color picker, media manager, reset confirm dialog
//
add_action( 'admin_enqueue_scripts', 'mstw_tr_admin_enqueue_scripts' );	
	
// Add a menu item for the Admin pages
add_action('admin_menu', 'mstw_tr_register_menu_pages');

// initialize the admin UI. MOVE ALL THE OTHER ACTIONS TO mstw_tr_admin_init ??
add_action( 'admin_init', 'mstw_tr_admin_init' );
add_action( 'admin_notices', 'mstw_tr_admin_notice' );
//
// Hide the publishing actions on the edit and new CPT screens
//
add_action( 'admin_head-post.php', 'mstw_tr_hide_publishing_actions' );
add_action( 'admin_head-post-new.php', 'mstw_tr_hide_publishing_actions' );
//
// Hide the list icons on the CPT edit (all) screens
//
add_action( 'admin_head-edit.php', 'mstw_tr_hide_list_icons' );	
// 
// Remove Quick Edit Menu
//
add_filter( 'post_row_actions', 'mstw_tr_remove_quick_edit', 10, 2 );
// 
// Remove the Bulk Actions pull-down
//
add_filter( 'bulk_actions-edit-mstw_tr_player', 'mstw_tr_bulk_actions' );
//
// Add custom admin messages for CPTs (Adding/editting CPTs
//
add_filter('post_updated_messages', 'mstw_tr_updated_messages');
//
// Add custom admin bulk messages for CPTs (deleting & restoring CPTs)
//
add_filter( 'bulk_post_updated_messages', 'mstw_tr_bulk_post_updated_messages', 10, 2 );
//
// Add custom admin messages for adding/editting custom taxonomy terms
//
add_filter( 'term_updated_messages', 'mstw_tr_updated_term_messages');

// ----------------------------------------------------------------
// Register and define the settings
// ----------------------------------------------------------------
if( !function_exists( 'mstw_tr_admin_init' ) ) {
	function mstw_tr_admin_init( ){
		//mstw_log_msg( "mstw_tr_admin_init:" );
		// Settings for the fields and columns display and label controls.
		//update_option( 'mstw_tr_options', 'foo' );
		$options = mstw_tr_get_settings( );
		
		register_setting(
			'mstw_tr_settings',
			'mstw_tr_options',
			'mstw_tr_validate_settings'
		);
		
		// Storage for the TR team to SS team links.
		if ( false == get_option( 'mstw_tr_ss_team_links' ) ) {
			add_option( 'mstw_tr_ss_team_links' );
		}
		
		wp_register_style( 'players-screen-styles', plugins_url( 'css/mstw-tr-admin-styles.css', dirname( __FILE__ ) ) );
		//mstw_tr_log_msg( plugins_url( 'mstw-tr-admin-styles.css', __FILE__ ) );
		//mstw_tr_load_admin_styles( );
		
		//ob_start();
	
		
	} //End: mstw_tr_admin_init()
}

//----------------------------------------------------------------
// Hide the publishing actions on the edit and new CPT screens
// Callback for admin_head-post.php & admin_head-post-new.php actions
//
if ( !function_exists( 'mstw_tr_hide_publishing_actions' ) ) {
	function mstw_tr_hide_publishing_actions( ) {

		$post_type = mstw_tr_get_current_post_type( );
		
		//mstw_tr_log_msg( 'in ... mstw_tr_hide_publishing_actions' );
		//mstw_tr_log_msg( $post_type );
		
		if( $post_type == 'mstw_tr_player' ) {	
			?>
				<style type="text/css">
					#misc-publishing-actions,
					#minor-publishing-actions{
						display:none;
					}
					div.view-switch {
						display: none;
					
					}
					div.tablenav-pages.one-page {
						display: none;
					}
					
				</style>
			<?php					
		}
	} //End: mstw_tr_hide_publishing_actions( )
}

//----------------------------------------------------------------
// Hide the list icons on the CPT edit (all) screens
// Callback for admin_head-edit action
if ( !function_exists( 'mstw_tr_hide_list_icons' ) ) {
	function mstw_tr_hide_list_icons( ) {

		$post_type = mstw_tr_get_current_post_type( );
		//mstw_tr_log_msg( 'in ... mstw_tr_hide_list_icons' );
		//mstw_tr_log_msg( $post_type );
		
		if( $post_type == 'mstw_tr_player' ) {
			//echo '
			?>
				<style type="text/css">
					select#filter-by-date,
					div.view-switch {
						display: none;
					}
					
				</style>
			<?php
			//';
		}
	} //End: mstw_tr_hide_list_icons( )
}

//-----------------------------------------------------------------	
// mstw_tr_admin_enqueue_scripts - Add admin scripts and CSS stylesheets:
//		mstw-tr-admin-styles.css - basic admin screen styles
// 
//		datepicker & timepicker for brackets (datepicker as a dependency)
//		media-upload & another-media for loading team logos 
//		lm-add-games for the add games screen
//		lm-update-games for the update games screen
//		lm-manage-games for the Add/Edit Game (CPT) screen
//

function mstw_tr_admin_enqueue_scripts( $hook_suffix ) {
	//mstw_tr_log_msg( "mstw_tr_admin_enqueue_scripts:" );
	//mstw_tr_log_msg( "hook_suffix: $hook_suffix" );
	
	global $typenow;
	global $pagenow;
	global $current_screen;
	//mstw_tr_log_msg( "pagenow: $pagenow" );
	//mstw_tr_log_msg( "typenow: $typenow" );
	
	//mstw_tr_log_msg( 'enqueing: ' . plugins_url( 'css/mstw-tr-admin-styles.css',dirname( __FILE__ ) ) );
	wp_enqueue_style( 'tr-admin-styles', plugins_url( 'css/mstw-tr-admin-styles.css', dirname( __FILE__ ) ), array(), false, 'all' );
	
	// This function loads in the required media files for the media manager.
	//wp_enqueue_media();
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery');
	
	wp_enqueue_media();
	
	//mstw_tr_log_msg( ' enqueing script: ' . plugins_url( 'team-rosters/js/tr-another-media.js' ) );
	wp_enqueue_script( 'another-media', plugins_url( 'team-rosters/js/tr-another-media.js' ), null, false, true );
	
	wp_enqueue_style('thickbox');
	
	//
	// If it's the Manage Rosters screen, enqueue the script to change the team
	//	
	if ( 'team-rosters_page_manage-team-rosters' == $hook_suffix ||
		 'team-rosters_page_add-players-screen' == $hook_suffix ||
		 'team-rosters_page_mstw-tr-csv-import' == $hook_suffix ) {
		
		//mstw_tr_log_msg( 'enqueueing script: ' . plugins_url( 'js/tr-manage-rosters.js', dirname( __FILE__ ) ) );
		
		wp_enqueue_script( 'tr-manage-rosters', 
						   plugins_url( 'js/tr-manage-rosters.js', dirname( __FILE__ ) ), 
						   array( ), 
						   false, true );
	}
	
	//
	// If it's the Manage Teams screen, enqueue the script to load team
	//	
	if ( ( 'edit-tags.php' == $hook_suffix || 'term.php' == $hook_suffix ) && 'mstw_tr_player' == $typenow ) {
		
		//mstw_tr_log_msg( 'enqueueing script: ' . plugins_url( 'js/tr-manage-teams.js', dirname( __FILE__ ) ) );
		
		wp_enqueue_script( 'tr-manage-teams', 
						   plugins_url( 'js/tr-manage-teams.js', dirname( __FILE__ ) ), 
						   array( ), 
						   false, true );

	}
	
	if ( 'edit-tags.php' == $hook_suffix && 'mstw_tr_player' == $typenow ) {
		//mstw_tr_log_msg( 'enqueueing script: ' . plugins_url( 'js/tr-manage-teams.js', dirname( __FILE__ ) ) );
		
		wp_enqueue_script( 'tr-load-teams', 
						   plugins_url( 'js/tr-load-teams.js', dirname( __FILE__ ) ), 
						   array( ), 
						   false, true );
	}
	
	//
	// If it's the settings screen, enqueue the color settings &
	//	the confirm reset scripts
	//
	if ( 'team-rosters_page_mstw-tr-settings' == $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
		
		wp_enqueue_script( 	'mstw-tr-color-picker', 
							plugins_url( 'team-rosters/js/tr-color-settings.js' ), array( 'wp-color-picker' ), 
							false, true ); 
							
		wp_enqueue_script( 	'mstw-tr-confirm-reset', 
							plugins_url( 'team-rosters/js/tr-confirm-reset.js' ), array( 'wp-color-picker' ), 
							false, true ); 
	}

} //End: mstw_tr_admin_enqueue_scripts()


// ----------------------------------------------------------------
// Remove Quick Edit Menu	
//
if( !function_exists( 'mstw_tr_remove_quick_edit' ) ) {
	function mstw_tr_remove_quick_edit( $actions, $post ) {
		if( $post->post_type == 'mstw_tr_player' ) {
			unset( $actions['inline hide-if-no-js'] );
			unset( $actions['view'] );
		}
		return $actions;
	} //End: mstw_tr_remove_quick_edit()
}

// ----------------------------------------------------------------
// Remove the Bulk Actions pull-down
//
if( !function_exists( 'mstw_tr_bulk_actions' ) ) {	
	function mstw_tr_bulk_actions( $actions ){
		unset( $actions['edit'] );
		return $actions;
	} //End: mstw_tr_bulk_actions()
}
	
// ----------------------------------------------------------------
// Add a filter the All Teams screen based on the Leagues Taxonomy
//add_action('restrict_manage_posts','mstw_tr_restrict_manage_posts');

function mstw_tr_restrict_manage_posts( ) {
	global $typenow;

	if ( $typenow=='mstw_tr_player' ){
		// Trying to find current selection
		$selected = isset( $_REQUEST[$mstw_tr_team] ) ? $_REQUEST[$mstw_tr_team] : '';
			
		$args = array(
					'show_option_all' => 'All Teams',
					'taxonomy' => 'mstw_tr_team',
					'name' => 'mstw_tr_team',
					'orderby' => 'name',
					'selected' => $_GET['mstw_tr_team'],
					'show_count' => true,
					'hide_empty' => true,
					);
		wp_dropdown_categories( $args );
	}
}

//add_action( 'request', 'mstw_tr_request' );
function mstw_tr_request( $request ) {
	//mstw_tr_log_msg( 'in ... mstw_tr_request' );
	//mstw_tr_log_msg( $request );
	
	if ( is_admin( ) && $GLOBALS['PHP_SELF'] == '/wp-admin/edit.php' && isset( $request['post_type'] ) && $request['post_type']=='mstw_tr_player' ) {
		$request['term'] = get_term( $request['mstw_tr_player'], 'mstw_tr_team', OBJECT )->name;
	}
	return $request;
}

function mstw_tr_register_menu_pages( ) {
	//mstw_tr_log_msg( 'including mstw-tr-import-class' );
	
	include_once 'mstw-tr-csv-import-class.php';
	
	//Top Level Menu
	if( mstw_tr_user_has_plugin_rights( 'tr' ) ) {
		$main_menu_slug = 'team-rosters-page';
		//
		// Main Team Rosters Page (provides getting started summary)
		//
		$main_menu_page = add_menu_page( 
			__( 'Team Rosters', 'team-rosters' ),  //$page_title, 
			__( 'Team Rosters', 'team-rosters' ),  //$menu_title, 
		   'read',                                 //$capability,
		   $main_menu_slug,                        //menu page slug
		   'mstw_tr_team_rosters_page',		       //callback function	   
		   plugins_url( 'images/mstw-admin-menu-icon.png', dirname( __FILE__ ) ),   //$menu_icon
		   "58.75" //menu position
			);
			
			 
		//   
		// Teams
		//
		global $team_tax_admin;
		//mstw_tr_log_msg( "team_tax_admin:" );
		//mstw_tr_log_msg( $team_tax_admin );
		
		if ( !empty( $team_tax_admin ) ) {
			$teams_page = add_submenu_page( 
				$main_menu_slug,  //parent page
				__( 'Teams', 'mstw-bracket-builder' ), //page title
				__( 'Teams', 'mstw-bracket-builder' ), //menu title
				'read', // Capability required to see this option.
				'edit-tags.php?taxonomy=mstw_tr_team&post_type=mstw_tr_player',  // Slug name to refer to this menu
				null
				); // Callback to output content
				
			add_action( "load-edit-tags.php", array( $team_tax_admin, 'add_help' ) );
			
			
		}
		else {
			mstw_tr_log_msg( 'mstw_tr_register_menu_pages: MSTW_TR_TEAM_TAX_ADMIN class is empty.' );
		}
		
		
		//
		// Manage Players
		//
		$players_page = add_submenu_page( 
							$main_menu_slug, // parent page
							__( 'All Players', 'team-rosters' ), //page title
							__( 'Manage Players', 'team-rosters' ), //menu title
							'read', // Capability required to see this option.
							'edit.php?post_type=mstw_tr_player', // Slug name to refer to this menu
							null							
							); // Callback to output content
						
		add_action( 'admin_print_styles-' . $players_page, 'mstw_tr_load_admin_styles');
		
		add_action( "load-edit.php",     'mstw_tr_add_help' );
		add_action( "load-post.php",     'mstw_tr_add_help' );
		add_action( "load-post-new.php", 'mstw_tr_add_help' );
		
		
		if ( class_exists( 'MSTW_TR_TEAM_ROSTERS_ADMIN' ) ) {
			$team_rosters_admin = new MSTW_TR_TEAM_ROSTERS_ADMIN;
			
			//
			// Add Players
			//
			$add_players_page = 
				add_submenu_page( 	
					$main_menu_slug, //parent page  
					__( 'Add Players', 'team-rosters' ), //page title
					__( 'Add Players to Rosters', 'team-rosters' ), //menu title
					'read', // Capability required to see this option.
					'add-players-screen', // Slug name to refer to this menu
					array( $team_rosters_admin, 'add_players_screen' )  // Callback to output content							
				);
				
			add_action( "load-$add_players_page", array( $team_rosters_admin, 'add_help' ) );
			
			//
			// Edit Team Rosters
			//
			$manage_rosters_page = 
				add_submenu_page( 	
					$main_menu_slug, //parent page  
					__( 'Edit Rosters', 'team-rosters' ), //page title
					__( 'Edit Rosters', 'team-rosters' ), //menu title
					'read', // Capability required to see this option.
					'manage-team-rosters', // Slug name to refer to this menu
					array( $team_rosters_admin, 'edit_roster_screen' )  // Callback to output content							
				);
				
			add_action( "load-$manage_rosters_page", array( $team_rosters_admin, 'add_help' ) );
			
		} else {
			mstw_tr_log_msg( 'mstw_tr_register_menu_pages: MSTW_TR_TEAM_ROSTERS_ADMIN class does not exist' );
			
		} //End: if ( class_exists( 'MSTW_TR_TEAM_ROSTERS_ADMIN' ) ) {  
		
			
		//
		// Settings
		//
		$settings_page = add_submenu_page( 	
							$main_menu_slug,  //parent slug
							__( 'Settings', 'team-rosters' ),   //page title
							__( 'Settings', 'team-rosters' ),  //menu title
							'read',  //user capability required to access
							'mstw-tr-settings',  //unique menu slug
							'mstw_tr_settings_page'  //callback to display page
							);	

		add_action( 'option_page_capability_mstw_tr_settings', 'mstw_tr_set_option_page_capabilities' );
							
		//
		// Load the settings help
		//
		add_action( "load-$settings_page", 'mstw_tr_settings_help' );
		
		//
		// New Settings
		//
		/*if ( class_exists( 'MSTW_TR_SETTINGS' ) ) {
			$plugin = new MSTW_TR_SETTINGS;
			
			$new_settings_page = add_submenu_page(
							$main_menu_slug,  //parent slug
							__( 'New TR Settings', 'mstw-team-rosters' ),   //page title
							__( 'New TR Settings', 'mstw-team-rosters' ),  //menu title
							'read',  //user capability required to access
							'mstw-new-tr-settings',  //unique menu slug
							array( $plugin, 'form' )  //callback to display page
							);
			add_action( "load-$new_settings_page", array( $plugin, 'add_help' ) );
			
		} 
		else {
			mstw_log_msg( 'MSTW_TR_SETTINGS class does not exist' );
			
		}//End: if ( class_exists( 'MSTW_LM_UPDATE_RECORDS' ) ) {
		*/
		
		//
		// CSV Import
		//
		$plugin = new MSTW_TR_ImporterPlugin;
		
		$csv_import_page = add_submenu_page(	
							$main_menu_slug, 				//menu_slug
							'Import Roster from CSV File',	//page title
							'CSV Import',			//menu title
							'read',
							'mstw-tr-csv-import',
							array( $plugin, 'form' )
						);
						
		//
		// Load the CSV Import help
		//
		add_action( "load-$csv_import_page", array( $plugin, 'add_help' ) );				
							
	} //End: if( mstw_tr_user_has_plugin_rights( )
		
} //End: mstw_tr_register_menu_pages()
	
 //-----------------------------------------------------------------------
 // Enqueue admin styles - only if on players admin page
 //
 if( !function_exists( 'mstw_tr_load_admin_styles' ) ) {
	function mstw_tr_load_admin_styles( ) {
		//mstw_tr_log_msg( ' loading players screen styles' );
		wp_enqueue_style( 'players-screen-styles' );
	} //End: mstw_tr_load_admin_styles( )
 }
 
 
 //-----------------------------------------------------------------------
 // Add custom admin messages for CPTs (Adding/editting CPTs
 //
 if( !function_exists( 'mstw_tr_updated_messages' ) ) {
	function mstw_tr_updated_messages( $messages ) {

		$messages['mstw_tr_player'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Player updated.', 'team-rosters' ),
			2 => __( 'Custom field updated.', 'team-rosters'),
			3 => __( 'Custom field deleted.', 'team-rosters' ),
			4 => __( 'Player updated.', 'team-rosters' ),
			5 => __( 'Player restored to revision', 'team-rosters' ),
			6 => __( 'Player published.', 'team-rosters' ),
			7 => __( 'Player saved.', 'team-rosters' ),
			8 => __( 'Player submitted.', 'team-rosters' ),
			9 => __( 'Player scheduled for publication.', 'team-rosters' ),
			10 => __( 'Player draft updated.', 'team-rosters' ),
		);
		
		return $messages;
		
	} //End: mstw_tr_updated_messages( )
 }
 
 //-----------------------------------------------------------------------
 // Add custom admin bulk messages for CPTs (deleting & restoring CPTs)
 //
 if( !function_exists( 'mstw_tr_bulk_post_updated_messages' ) ) {
	function mstw_tr_bulk_post_updated_messages( $messages, $bulk_counts ) {

		$messages['mstw_tr_player'] = array(
			'updated'   => _n( '%s player updated.', '%s players updated.', $bulk_counts['updated'], 'team-rosters' ),
			'locked'    => _n( '%s player not updated, somebody is editing it.', '%s players not updated, somebody is editing them.', $bulk_counts['locked'], 'team-rosters' ),
			'deleted'   => _n( '%s player permanently deleted.', '%s players permanently deleted.', $bulk_counts['deleted'], 'team-rosters' ),
			'trashed'   => _n( '%s player moved to the Trash.', '%s players moved to the Trash.', $bulk_counts['trashed'], 'team-rosters' ),
			'untrashed' => _n( '%s player restored from the Trash.', '%s players restored from the Trash.', $bulk_counts['untrashed'], 'team-rosters' ),
		);
		
		return $messages;
		
	} //End: mstw_tr_bulk_post_updated_messages( )
 }
 
 //-----------------------------------------------------------------------
 // Add custom admin messages for adding/editting custom taxonomy terms
 //
 if( !function_exists( 'mstw_tr_updated_term_messages' ) ) {
	function mstw_tr_updated_term_messages( $messages ) {
		//mstw_tr_log_msg( 'in mstw_tr_updated_term_messages ... ' );
		//mstw_tr_log_msg( $messages );
		
		$messages['mstw_tr_team'] = array(
					0 => '',
					1 => __( 'Team added.', 'team-rosters' ),
					2 => __( 'Team deleted.', 'team-rosters' ),
					3 => __( 'Team updated.', 'team-rosters' ),
					4 => __( 'Team not added.', 'team-rosters' ),
					5 => __( 'Team not updated.', 'team-rosters' ),
					6 => __( 'Teams deleted.', 'team-rosters' ),
				);
									
		//mstw_tr_log_msg( $messages );
		
		return $messages;
		
	} //End: mstw_tr_updated_term_messages( )
 }
 
 //-----------------------------------------------------------------------
 // Filter the capability so user that can see the setting menu can edit the settings
 //
 if( !function_exists( 'mstw_tr_set_option_page_capabilities' ) ) {
	function mstw_tr_set_option_page_capabilities( $capability ) {
		//mstw_tr_log_msg( 'in mstw_tr_set_option_page_capabilities ...' );
		//mstw_tr_log_msg( '$capability before = ' . $capability );
		$capability = 'edit_mstw_tr_settings';
		//mstw_tr_log_msg( '$capability after = ' . $capability );
		return $capability; //'edit_mstw_tr_settings'; //
	} //End mstw_tr_set_option_page_capabilities()
 }
 
 //-----------------------------------------------------------------
 // mstw_tr_ajax_callback - callback for ALL AJAX posts in the plugin
 //
 //	ARGUMENTS: 
 //		None. AJAX post is global.
 //	
 //	RETURNS:
 //		$response: JSON response to the AJAX post (including error messages)
 //
 function mstw_tr_ajax_callback ( ) {
	//mstw_tr_log_msg( 'mstw_tr_ajax_callback:' );
	
	global $wpdb;  //this provides access to the WP DB
	
	//mstw_tr_log_msg( 'received data: $_POST[]' );
	//mstw_tr_log_msg( $_POST );
	
	if ( array_key_exists( 'real_action', $_POST ) ) {
		
		$action = $_POST['real_action'];
		//mstw_tr_log_msg( 'action= ' . $action );
		
		switch( $action ) {
			case 'change_current_team':
				$response = mstw_tr_ajax_change_team( );
				break;
				
			case 'add_lm_team':
			case 'add_ss_team':
				$response = mstw_tr_ajax_add_team( $action );
				break;
				
			default:
				mstw_tr_log_msg( "Error: Invalid action, $action, on page: " . $_POST['page'] );
				$response['error'] = __( 'AJAX Error: invalid action.', 'team-rosters' );
				break;
		}
		
	} else {
		mstw_tr_log_msg( "AJAX Error: no action found." );
		$response['error'] = __( 'AJAX Error: no action found.', 'team-rosters' );
	}
	
	//mstw_tr_log_msg( $response );
	echo json_encode( $response );
	
	wp_die( ); //gotta have this to keep server straight
	
 } //End: mstw_tr_ajax_callback( )
 
 //-----------------------------------------------------------------
 // mstw_tr_ajax_change_team - builds response when league select-option is changed.  
 //		Builds seasons list, and teams list if needed.
 //
 //	ARGUMENTS: 
 //		None. AJAX post is global.
 //	
 //	RETURNS:
 //		$response: HTML for the options list(s) or error message.
 //
 function mstw_tr_ajax_change_team( ) {
	//mstw_tr_log_msg( 'in mstw_tr_ajax_change_team ...' );
	//$_POST should be global
	
	$response = array( 'response'      => 'team',
					   'current_team'  => '',
					   'error'         => ''
					 );
		
	if ( array_key_exists( 'current_team', $_POST ) ) {
		
		$current_team = $_POST['current_team'];
		
		mstw_tr_set_current_team( $current_team );
		
		$response['current_team'] = $current_team;
		
	} else {
		// we've got a problem
		mstw_tr_log_msg( "AJAX Error: No league provided to handler." ); 
		
		$response['error'] = __( 'AJAX Error: No league provided to handler.', 'team-rosters' );	
		
	} //End: if ( array_key_exists( 'current_team', $_POST ) )
	
	return $response;
	
 } //End: mstw_tr_ajax_change_team( )
 
 //-----------------------------------------------------------------
 // mstw_tr_ajax_add_team - builds response when add SS or LM team 
 //		select-option is changed on the Manage Teams screen  
 //		Returns the Team Name.
 //
 //	ARGUMENTS: 
 //		$team_source: mstw_lm_team or mstw_ss_team
 //	
 //	RETURNS:
 //		$response: The team name and source (mstw_ss_team or mstw_lm_team)
 //
 function mstw_tr_ajax_add_team( ) {
	//mstw_tr_log_msg( 'in mstw_tr_ajax_add_team ...' );
	//$_POST should be global
	
	$response = array( 'response'      => 'team',
					   'current_team'  => '',
					   'error'         => ''
					 );
		
	if ( array_key_exists( 'current_team', $_POST ) ) {
		
		$current_team = $_POST['current_team'];
		
		mstw_tr_set_current_team( $current_team );
		
		$response['current_team'] = $current_team;
		
	} else {
		// we've got a problem
		mstw_tr_log_msg( "AJAX Error: No league provided to handler." ); 
		
		$response['error'] = __( 'AJAX Error: No league provided to handler.', 'team-rosters' );	
		
	} //End: if ( array_key_exists( 'current_team', $_POST ) )
	
	return $response;
	
 } //End: mstw_tr_ajax_add_team( )
 
 
 function mstw_tr_add_help( ) {
	//mstw_tr_log_msg( 'mstw_tr_add_help:' );
	
	$screen = get_current_screen( );
	//mstw_tr_log_msg( $screen );
	//mstw_tr_log_msg( "mstw_tr_add_help: action: " . $screen -> action );
	//mstw_tr_log_msg( "mstw_tr_add_help: base: " . $screen -> base );
	//mstw_tr_log_msg( "mstw_tr_add_help: post_type: " . $screen -> post_type );
	//mstw_tr_log_msg( "mstw_tr_add_help: taxonomy: " . $screen -> taxonomy );
	
	if ( 'edit-tags' == $screen -> base  &&  'mstw_tr_team' == $screen -> taxonomy ) {
		//mstw_tr_log_msg( "Load help for the MSTW Teams Taxonomy Page" );
		
	} else if ( 'post' == $screen -> base  &&  'mstw_tr_player' == $screen -> post_type ) {
		//mstw_tr_log_msg( "Load help for the Add/Edit Player Page" );
		mstw_tr_player_add_help( );
		
		
	} else if ( 'edit' == $screen -> base  &&  'mstw_tr_player' == $screen -> post_type ) {
		//mstw_tr_log_msg( "Load help for the Players Page" );
		mstw_tr_player_add_help( );
		
	}
	
	else {
	
		/*
		mstw_tr_log_msg( "mstw_tr_add_help: action: " . $screen -> action );
		mstw_tr_log_msg( "mstw_tr_add_help: base: " . $screen -> base );
		mstw_tr_log_msg( "mstw_tr_add_help: post_type: " . $screen -> post_type );
		mstw_tr_log_msg( "mstw_tr_add_help: taxonomy: " . $screen -> taxonomy );
		*/
	}
	
	 
 } //End: mstw_tr_add_help( )
 
//-------------------------------------------------------------------------------
// mstw_tr_get_current_post_type - get the current post type in the WordPress Admin
//		ARGUMENTS: 
//			None. But uses global variables: $post, $typenow, and $current_screen 
//		RETURNS: post type on success or null on failure		
//
// Thanks to Brad Vincent for this function
// http://themergency.com/wordpress-tip-get-post-type-in-admin/
//
function mstw_tr_get_current_post_type( ) {
	
	global $post, $typenow, $current_screen;
 
	if ( $post and $post->post_type ) // see note below snippet
		return $post->post_type;
 
	elseif( $typenow )
		return $typenow;
 
	elseif( $current_screen and $current_screen->post_type )
		return $current_screen->post_type;
 
	elseif( isset( $_REQUEST['post_type'] ) )
		return sanitize_key( $_REQUEST['post_type'] );
		
	else
		return null;
		
} //End: mstw_tr_get_current_post_type( )

//-------------------------------------------------------------------------------
// mstw_tr_user_has_plugin_rights - check if the CURRENT USER has 
//							Schedules & Scoreboards admin rights
//		ARGUMENTS: 	$plugin - plugin abbreviation - 'tr', 'ss', 'cs', 'ls', //							  'csvx', etc.
//		RETURNS: 	true if the current user has rights
//				 	false otherwise	
//
function mstw_tr_user_has_plugin_rights( $plugin = 'ss' ) {
	
	if ( current_user_can( 'edit_others_posts' ) or  //WP admins and editors
		 current_user_can( 'view_mstw_menus' ) or    //MSTW admins
		 current_user_can( 'view_mstw_' . $plugin . '_menus' )    //plugin admins
		 ) {
		return true;
	}
	return false;
	
} //End: mstw_tr_user_has_plugin_rights( )


//-----------------------------------------------------------------
//
// MSTW TEAM ROSTERS QUICK START PAGE (hooked to the main menu item)
//
//-----------------------------------------------------------------
function mstw_tr_team_rosters_page( ) {
//mstw_tr_log_msg( 'mstw_tr_team_rosters_page:' );

global $pagenow;

?>
<div class="wrap">
	<h2><?php _e( 'Team Rosters - Quick Start', 'team-rosters') ?></h2>
	<h3>GETTING STARTED</h3>
	<ol>
	<li><a href="<?php echo admin_url( '/edit-tags.php?taxonomy=mstw_tr_team&post_type=mstw_tr_player' ) ?>">TEAMS</a>. <?php _e('At least one team must exist before anything can be displayed via the shortcodes on the front end. Teams can be entered on this screen, or can be imported in bulk using the CSV IMPORT screen described below.', 'team-rosters' ) ?></li>
	<li><a href="<?php echo admin_url( '/edit.php?post_type=mstw_tr_player' ) ?>">MANAGE PLAYERS</a>. <?php _e( 'After creating one or more teams, players must be added to them. Players may be added, edited, and deleted on this screen. However, there are faster ways to add players in bulk. See ADD PLAYERS TO ROSTERS and CSV IMPORT below.', 'team-rosters' )?></li>
	<li><a href="<?php echo admin_url( '/admin.php?page=add-players-screen' ) ?>">ADD PLAYERS TO ROSTERS</a>.<?php _e( 'Multiple players may be added to a roster via this screen. While the same data as on the MANAGE PLAYERS screen must be entered, it can be entered more quickly on this screen.', 'team-rosters' )?></li>
	<li><a href="<?php echo admin_url( '/admin.php?page=manage-team-rosters' ) ?>">EDIT ROSTERS</a>. <?php _e( 'Entire rosters may be edited or updated via this screen. While the same data as on the MANAGE PLAYERS screen must be entered, it can entered more quickly on this screen.', 'team-rosters' )?></li>
	<li><a href="<?php echo admin_url( '/admin.php?page=mstw-tr-settings' ) ?>">SETTINGS</a>. <?php _e( 'Provides a rich set of controls for ROSTER TABLES, ROSTER GALLERIES, and SINGLE PLAYER PROFILES.', 'team-rosters' )?></li>
	<li><a href="<?php echo admin_url( '/admin.php?page=mstw-tr-csv-import' ) ?>">CSV IMPORT</a>. <?php _e( 'Provides the ability to upload Teams and Players (including player photos) from CSV formatted files. Note that these CSV files can generated from previous version of MSTW Team Rosters using the MSTW CSV Exporter plugin, or created by hand using an editor. (Excel works great.)', 'team-rosters' )?></li>
	
	</ol>
	
	<h3>DISPLAYING TEAM ROSTERS</h3>
	<p><?php printf( __( 'Team Rosters may be displayed in two formats: roster tables and roster galleries. Roster tables are displayed using the shortcode %1$s[mstw-tr-roster team=team-slug]%2$s Roster Galleries my be displayed using the shortcode %1$s[mstw-tr-gallery team=team-slug]%2$s or via the %3$staxonomy_team.php%4$s template. See the %5$sshortcodes man page%6$s for complete details.', 'team-rosters' ), '<blockquote><code>', '</code></blockquote>', '<strong><code>', '</code></strong>', '<a href="http://shoalsummitsolutions.com/tr-shortcodes/">', '</a>' ) ?>
	</p>
	
</div>

<?php	
} //End: mstw_tr_team_rosters_page( )  
 