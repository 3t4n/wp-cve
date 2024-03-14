<?php
/*
Plugin Name: WP Dash Message
Description: Add a welcome message dashboard widget and remove any WordPress dashboard widgets with this plugin.
Version: 1.1.2
Author: Aleksandar Arsovski
License: GPL2
*/

/*  Copyright 2011  Aleksandar Arsovski  (email : alek_ars@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Internationalization setup
$wpdwm_plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'wp-dash-message', false, $wpdwm_plugin_dir );


/*****************************************************************************************/
								/*Widget removal arrays*/
/*****************************************************************************************/

// Titles for all the widgets that can be removed from each dashboard
$rightnow_title = ' ' . __( 'Right Now', 'wp-dash-message' );
$plugins_title = ' ' . __( 'Plugins', 'wp-dash-message' );
$recentcomments_title = ' ' . __( 'Recent Comments', 'wp-dash-message' );
$incominglinks_title = ' ' . __( 'Incoming Links', 'wp-dash-message' );
$quickpress_title = ' ' . __( 'QuickPress', 'wp-dash-message' );
$recentdrafts_title = ' ' . __( 'Recent Drafts', 'wp-dash-message' );
$wordpressblog_title = ' ' . __( 'WordPress Blog', 'wp-dash-message' );
$otherwordpressnews_title = ' ' . __( 'Other WordPress News', 'wp-dash-message' );


// Network dashboard widget information array (used for checkboxes)
$meta_boxes_network = array(
	'network_dashboard_right_now' => $rightnow_title,
	'dashboard_plugins' => $plugins_title,
	'dashboard_primary' => $wordpressblog_title,
	'dashboard_secondary' => $otherwordpressnews_title
);
	
// Site dashboard widget information array (used for checkboxes)
$meta_boxes_site = array(
	//'dashboard_welcome_widget' => $welcome_title,
	'dashboard_right_now' => $rightnow_title,
	'dashboard_recent_comments' => $recentcomments_title,
	'dashboard_incoming_links' => $incominglinks_title,
	'dashboard_quick_press' => $quickpress_title,
	'dashboard_recent_drafts' => $recentdrafts_title,
	'dashboard_primary' => $wordpressblog_title ,
	'dashboard_secondary' => $otherwordpressnews_title
);
	
// Global dashboard widget information array (used for checkboxes)
$meta_boxes_global = array(
	//'dashboard_welcome_widget' => $welcome_title,
	'dashboard_primary' => $wordpressblog_title ,
	'dashboard_secondary' => $otherwordpressnews_title
);

/*****************************************************************************************/
/*****************************************************************************************/


// Hook for adding site level options menu in the settings menu bar
add_action( 'admin_menu', 'wpdwm_options_menu' );

// Hook for setting up the settings section in network-wide settings tab (for creating network-wide welcome messages and removing widgets from any dashboard)
add_action( 'wpmu_options', 'wpdwm_network_settings' );

// Hook for updating the network-wide welcome massage and widget removal data data
add_action( 'update_wpmu_options', 'wpdwm_save_network_settings' ) ;

// Hook calls function for registering/adding settings when admin area is accessed
add_action( 'admin_init', 'wpdwm_admin_init' );

// Remove widgets from site dashboard function
add_action( 'wp_dashboard_setup', 'wpdwm_remove_site_dash_widgets' );
	
// Remove widgets from network dashboard function
add_action( 'wp_network_dashboard_setup', 'wpdwm_remove_network_dash_widgets' );

// Remove widgets from global dashboard function
add_action( 'wp_user_dashboard_setup', 'wpdwm_remove_global_dashboard_widgets' );


/** Function for registering/adding setings 
 * wpdwm_admin_init function.
 * 
 * @access public
 * @return void
 */
function wpdwm_admin_init() {
	
	// Adding the dash message widget to the site level dashboard
	add_action( 'wp_dashboard_setup', 'wpdwm_add_dash_welcome_site' );
	
	// Adding the dash message widget to the global level dashboard (this is the dashboard that new users that don't belong to a site see by default)
	add_action( 'wp_user_dashboard_setup', 'wpdwm_add_dash_welcome_global' );
	
	// Site-level settings section
	add_settings_section(
		'wpdwm_dash_settings_page_main',
		'',
		'wpdwm_main_section_text',
		'wpdwm_dash_settings_page'
	);
	
	// Site-level dashboard message text entry field
	add_settings_field(
		'wpdwm_welcome_text',
		__( 'Dashboard Message Content', 'wp-dash-message' ),
		'wpdwm_site_level_entry_field',
		'wpdwm_dash_settings_page',
		'wpdwm_dash_settings_page_main'
	);
	
	// Site-level remove dashboard widgets settings
	add_settings_field(
		'wpdwm_site_dash_widgets',
		__( 'Remove Site Dashboard Widgets', 'wp-dash-message' ),
		'wpdwm_site_level_dash_widget_options',
		'wpdwm_dash_settings_page',
		'wpdwm_dash_settings_page_main'
	);
	
	// Dash message text entry option
	register_setting( 'wpdwm_site_options', 'wp_dash_message', 'wp_dash_message_validate' );
	
	// Removing site widgets option
	register_setting( 'wpdwm_site_options', 'wp_remove_site_widgets' );
	
	// Adding the dash message widget into the widget removal arrays
	global $meta_boxes_site, $meta_boxes_global, $user_identity;
	$welcome_title = ' ' . __( 'Welcome', 'wp-dash-message' ) . ', ' . $user_identity . ' ' . __( '(WP Dash Message)', 'wp-dash-message' );
	$meta_boxes_site = array_merge( array( 'dashboard_welcome_widget' => $welcome_title ), $meta_boxes_site );
	$meta_boxes_global = array_merge( array( 'dashboard_welcome_widget' => $welcome_title ), $meta_boxes_global );
}


// Add the Dash Welcome widget and place it at the top of the SITE dashboard
/**
 * wpdwm_add_dash_welcome_site function.
 * 
 * @access public
 * @return void
 */
function wpdwm_add_dash_welcome_site() {
	
	// Get user's data in order to display username in header
	global $user_identity;
	
	// Change second parameter to change the header of the widget
	wp_add_dashboard_widget(
		'dashboard_welcome_widget',
		__('Welcome', 'wp-dash-message' ) . ', ' . $user_identity,
		'wpdwm_dashboard_welcome_widget_function'
	);	

	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;
	
	// Get the regular dashboard widgets array 
	// (which has our new widget already but at the end)
	$wpdwm_normal_dashboard = $wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ];

	// Backup and delete our new dashbaord widget from the end of the array
	$wpdwm_dashboard_widget_backup = array( 'dashboard_welcome_widget' => $wpdwm_normal_dashboard[ 'dashboard_welcome_widget' ] );
	unset( $wpdwm_normal_dashboard[ 'dashboard_welcome_widget' ] );

	// Merge the two arrays together so our widget is at the beginning
	$wpdwm_sorted_dashboard = array_merge( $wpdwm_dashboard_widget_backup, $wpdwm_normal_dashboard );

	// Save the sorted array back into the original metaboxes
	$wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ] = $wpdwm_sorted_dashboard;
	
}


// Add the Dash Welcome widget and place it at the top of the GLOBAL dashboard
/**
 * wpdwm_add_dash_welcome_global function.
 * 
 * @access public
 * @return void
 */
function wpdwm_add_dash_welcome_global() {	
	
	// Get user's data in order to display username in header
	global $user_identity;
	
	// Change second parameter to change the header of the widget
	wp_add_dashboard_widget(
		'dashboard_welcome_widget',
		__('Welcome', 'wp-dash-message' ) . ', ' . $user_identity,
		'wpdwm_dashboard_welcome_widget_function'
	);	

	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;
	
	// Get the regular dashboard widgets array 
	// (which has our new widget already but at the end)
	$wpdwm_global_dashboard = $wp_meta_boxes[ 'dashboard-user' ][ 'normal' ][ 'core' ];

	// Backup and delete our new dashbaord widget from the end of the array
	$wpdwm_dashboard_widget_backup = array( 'dashboard_welcome_widget' => $wpdwm_global_dashboard[ 'dashboard_welcome_widget' ] );
	unset( $wpdwm_global_dashboard[ 'dashboard_welcome_widget' ] );

	// Merge the two arrays together so our widget is at the beginning
	$wpdwm_sorted_dashboard = array_merge( $wpdwm_dashboard_widget_backup, $wpdwm_global_dashboard );

	// Save the sorted array back into the original metaboxes
	$wp_meta_boxes[ 'dashboard-user' ][ 'normal' ][ 'core' ] = $wpdwm_sorted_dashboard;
	
}


// Create the function to output the contents of the new Dashboard Widget
/**
 * wpdwm_dashboard_welcome_widget_function function.
 * 
 * @access public
 * @return void
 */
function wpdwm_dashboard_welcome_widget_function() {
	
	// Display the site level widget entry first...
	$site_message = get_option( 'wp_dash_message' );
	echo apply_filters( 'the_content', $site_message[ 'message' ] );
	
	// Display the network level widget entry second...
	$network_message = get_site_option( 'wp_dash_message_network', '', true );
	
	if( $network_message != '' ) {
		echo $network_message;
	} ?>
	
	<!--CSS for Widget-->
	<style>
		#dashboard_welcome_widget {
			background: lightYellow;
			color: #555;
			border: 2px solid #E6DB55;
		}
		#dashboard_welcome_widget .hndle {
			background-image: -webkit-linear-gradient( top, #FDFBAF, #FAF66C );
			background-image: -moz-linear-gradient( top,  #FDFBAF,  #FAF66C);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#FDFBAF', endColorstr='#FAF66C' );
		}
		#dashboard_welcome_widget h3 {
			text-shadow: white 0 1px 0;
			box-shadow: 0 1px 0 yellow;
		}
	</style><?php
}

/** Options page
 * wpdwm_options_menu function.
 * 
 * @access public
 * @return void
 */
function wpdwm_options_menu() {
	// Parameters for options: 1. site header name 2. setting menu bar name
	// 3. capability (decides whether user has access) 4. menu slug 5. options page function
	add_options_page(
		__( 'Dashboard Message Options', 'wp-dash-message' ),
		__( 'Dashboard Message', 'wp-dash-message' ),
		'manage_options',
		'wpdwm_options',
		'wpdwm_dash_settings_page'
	);
}

/** Site level options page set-up
 * wpdwm_dash_settings_page function.
 * settings page
 * @access public
 * @return void
 */
function wpdwm_dash_settings_page() {
	
	// Determines if user has permission to access options and if they don't error message is displayed
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have the permission to modify the custom dashboard message box.', 'wp-dash-message' ) );
	}?>
	
	<!-- Set up the page and populate it with the options section and save button -->
	<div class="wrap">
		<h2><?php _e( 'Dashboard Message', 'wp-dash-message' ) ?></h2>
		<form method="post" action="options.php"><?php
			settings_fields( 'wpdwm_site_options' );
			do_settings_sections( 'wpdwm_dash_settings_page' ); ?>
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</form>
	</div>
	<?php
}


// **Not used** (text function for text box area)
function wpdwm_main_section_text() { }


// **Not used** (text function for text box area) 
function wpdwm_remove_widgets_text() { }


/** Sets up the site level entry field
 * wpdwm_site_level_entry_field function.
 * 
 * @access public
 * @return void
 */
function wpdwm_site_level_entry_field() {
	
	// Get the site message entry
	$site_message = get_option( 'wp_dash_message' );
	
	// Get the widget removal options
	$WP_remove_option = get_option( 'wp_remove_site_widgets' );
	$WP_remove_site_option = get_site_option( 'wp_remove_site_widgets_N' ); ?>
		
	<!-- Creates the site level entry field and populates it with whatever is currently displayed on the widget site message wise -->
	<!-- The textarea is disabled if the widget is disabled on the site level -->
	<textarea id="wpdwm_welcome_text" name="wp_dash_message[message]" rows="15" cols="70"><?php echo $site_message[ 'message' ]; ?></textarea>
	<br /><?php
	
	// Shows the "HTML allowed" message if the widget hasn't been disabled on the site level dashboard
	if( !isset( $WP_remove_option[ 'dashboard_welcome_widget' ] ) && !isset( $WP_remove_site_option[ 'dashboard_welcome_widget' ] ) ) { ?>
		<span><?php
			_e( 'HTML allowed', 'wp-dash-message' ) ?>
		</span>
		<br /><?php
	}
	// Show the following message instead if the dashboard message widget is disabled through the network administrator options
	elseif( isset( $WP_remove_site_option[ 'dashboard_welcome_widget' ] ) ) { ?>
		<span class="description">
			<font color='FF6666'><?php
			_e( 'The network administrator has deactivated the dashboard message widget. Please
			contact your network administrator if you wish to have the dashboard message
			widget re-activated.', 'wp-dash-message' ) ?>
			</font>
		</span><?php
	}
	// Show the following message instead if the dashboard message widget is disabled through the site level administration options
	elseif( isset( $WP_remove_option[ 'dashboard_welcome_widget' ] ) ) { ?>
		<span class="description">
			<font color='FF6666'><?php 
			_e( 'One of the site administrators has deactivated the dashboard message widget. If
			you wish to re-activate the dashboard widget, simply deselect the appropriate checkbox
			in the section below and click on the "Save Changes" button.', 'wp-dash-message' ) ?>
			</font>
		</span><?php
	}

}


/** Validation/clean-up of message. "trim" removes all spaces before and after text body. Returns the validated entry
 * wp_dash_message_validate function.
 * 
 * @access public
 * @param mixed $input
 * @return $newinput -- Validated entry
 */
function wp_dash_message_validate($input) {
	$newinput[ 'message' ] =  trim( $input[ 'message' ] );
	return $newinput;
}


/** Set up network level entry field in settings tab and populate field with current network-wide dashboard widget message
 ** Set up the widget removal checkboxes
 * wpdwm_network_settings function.
 * 
 * @access public
 * @return void
 */
function wpdwm_network_settings() {
	
	// Get the network level dashboard message entry
	$network_message = get_site_option( 'wp_dash_message_network', '', true );
	
	// Get the widget removal options
	$WP_remove_network_option = get_site_option( 'wp_remove_network_widgets' );
	$WP_remove_site_option = get_site_option( 'wp_remove_site_widgets_N' );
	$WP_remove_global_option = get_site_option( 'wp_remove_global_widgets' );
	
	// Globalize widget details
	global $meta_boxes_network, $meta_boxes_site, $meta_boxes_global; ?>
	
	
	<!-- Set up textarea for dashboard message and notifications on its status -->
	<h3><?php _e( 'Dashboard Message', 'wp-dash-message' ) ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e( 'Network-Level Dashboard Message', 'wp-dash-message' ) ?></th>
			<td>
				<!-- Network level entry field populated with whatever is currently displayed on the widget network message wise -->
				<!-- The textarea is disabled if the widget is disabled on the site level and global level dashboards -->
	            <textarea class="large-text" cols="45" rows="5" id="wp_dash_message_network" 
				name="wp_dash_message_network"><?php echo $network_message; ?></textarea><?php


				// Show the "HTML allowed" message if the site level or global level dashboard message widget is enabled
				if( !isset( $WP_remove_site_option[ 'dashboard_welcome_widget' ] ) || !isset( $WP_remove_global_option[ 'dashboard_welcome_widget' ] ) ) { ?>
					<span><?php _e( 'HTML allowed', 'wp-dash-message' ) ?></span><br /><?php
				} 
				// Show disable notice if the widget is disabled on both the site and global level dashboards
				elseif( isset( $WP_remove_site_option[ 'dashboard_welcome_widget' ] ) && isset( $WP_remove_global_option[ 'dashboard_welcome_widget' ] ) ) { ?>
					<span class="description">
						<font color='FF6666'><?php
						_e( 'One of the site administrators has deactivated both the site level and 
	                	global level dashboard message widget instances. If you wish to re-activate
	                	the dashboard widget, simply deselect the appropriate checkboxes in the section
	                	below and click on the "Save Changes" button.', 'wp-dash-message' ) ?>
						</font>
					</span><?php
				} // end of if statement...


				// Notice if the dashboard message widget is disabled from the site dashboard
				if( isset( $WP_remove_site_option[ 'dashboard_welcome_widget' ] ) && !isset( $WP_remove_global_option[ 'dashboard_welcome_widget' ] ) ) { ?>
					<span class="description"><?php
	            		_e( 'NOTICE: The dashbaord message widget is DIASABLED from the SITE
	            		dashboard (most commonly seen by site users).', 'wp-dash-message' ) ?>
					</span>
					<br /><?php
				}
				// Notice if the dashboard message widget is disabled from the global dashboard
				elseif( !isset( $WP_remove_site_option[ 'dashboard_welcome_widget' ] ) && isset( $WP_remove_global_option[ 'dashboard_welcome_widget' ] ) ) { ?>
					<span class="description"><?php
	            		_e( 'NOTICE: The dashbaord message widget is DIASABLED from the GLOBAL
	               		dashboard (most commonly seen by blog/site-less users).', 'wp-dash-message' ) ?>
					</span>
					<br /><?php
				} //end of if statement...
				?>
			</td>
		</tr>
	</table>
	
	<br />
	
	<!-- Set up network dashboard widget removal checkboxes -->
	<table class="form-table" cellspacing="0" cellpadding="0">
		<tr valign="top"> 
			<th scope="row" rowspan="5"><?php _e( 'Remove Network Dashboard Widgets', 'wp-dash-message' ) ?></th>
			<td><?php
				foreach($meta_boxes_network as $meta_box => $title) { ?>
					<input type='checkbox' name='wp_remove_network_widgets[<?php echo $meta_box; ?>]'
					value='Removed' <?php isset( $WP_remove_network_option[$meta_box] ) ? checked( $WP_remove_network_option[$meta_box], 'Removed', true ) : NULL; ?> /><?php	echo $title; ?><br><?php
				} ?>
			</td>
		</tr>
		<tr>
			<td>
				<span class="description"><?php
					_e( 'Select all widgets you would like to remove from the network dashboard and
					click on the "Save Changes" button.', 'wp-dash-message' ) ?>
				</span>
			</td>
		</tr>
	</table>

	<br />
	
	<!-- Set up site dashboard widget removal checkboxes -->
	<table class="form-table" cellspacing="0" cellpadding="0">
		<tr valign="top"> 
			<th scope="row" rowspan="8"><?php _e( 'Remove Site Dashboard Widgets', 'wp-dash-message' ) ?></th>
			<td><?php
				foreach($meta_boxes_site as $meta_box => $title) { ?>
					<input type='checkbox' name='wp_remove_site_widgets_N[<?php echo $meta_box; ?>]'
					value='Removed' <?php isset( $WP_remove_site_option[$meta_box] ) ? checked( $WP_remove_site_option[$meta_box], 'Removed', true ) : NULL; ?> /><?php echo $title; ?><br><?php
				} ?>
			</td>
		</tr>
		<tr>
			<td>
				<span class="description"><?php
					_e( 'Select all widgets you would like to remove from the site dashboard and
					click on the "Save Changes" button.', 'wp-dash-message' ) ?>
				</span>
				
				<br />

				<span class="description"><?php
					_e( 'NOTE: Site dashboard widgets disabled here cannot be re-enabled by a site
					admin through the site-level "Dashboard Message" settings page.', 'wp-dash-message' ) ?>
				</span>
			</td>
		</tr>
	</table>
	
	<br />
	
	<!-- Set up global dashboard widget removal checkboxes -->
	<table class="form-table" cellspacing="0" cellpadding="0">
		<tr valign="top"> 
			<th scope="row" rowspan="8"><?php _e( 'Remove Global Dashboard Widgets', 'wp-dash-message' ) ?></th>
			<td><?php
				foreach($meta_boxes_global as $meta_box => $title) { ?>
					<input type='checkbox' name='wp_remove_global_widgets[<?php echo $meta_box; ?>]'
					value='Removed' <?php isset( $WP_remove_global_option[$meta_box] ) ? checked( $WP_remove_global_option[$meta_box], 'Removed', true ) : NULL; ?> /><?php echo $title; ?><br><?php
				} ?>
			</td>
		</tr>
		<tr>
			<td>
				<span class="description"><?php
					_e( 'Select all widgets you would like to remove from the global dashboard 
					(typically seen by blog/site-less new users) and click on the "Save Changes"
					button.', 'wp-dash-message' ) ?>
				</span>
			</td>
		</tr>
	</table><?php
	
}


/** Updates the network-wide dash message entry and the widget removal checkboxes status
 * wpdwm_save_network_settings function.
 * 
 * @access public
 * @return void
 */
 function wpdwm_save_network_settings() {
 
 	// Apply filters to the dashboard network message
	$network_message = stripslashes( $_POST[ 'wp_dash_message_network' ] );
	$filtered_network_message = apply_filters( 'the_content', trim( $network_message ) ); 
	
	// Update the network dash message entry with the filtered version
	update_site_option( 'wp_dash_message_network', $filtered_network_message );
	
	// Update the network, site, and global dashboard  widget removal checkbox statuses
	update_site_option( 'wp_remove_network_widgets', isset( $_POST[ 'wp_remove_network_widgets' ] ) ? $_POST[ 'wp_remove_network_widgets' ] : NULL );
	update_site_option( 'wp_remove_site_widgets_N', isset( $_POST[ 'wp_remove_site_widgets_N' ] ) ? $_POST[ 'wp_remove_site_widgets_N' ] : NULL );
	update_site_option( 'wp_remove_global_widgets', isset( $_POST[ 'wp_remove_global_widgets' ] ) ? $_POST[ 'wp_remove_global_widgets' ] : NULL );
	
}


/** Removes all site level dashboard widgets that were checked off in the site and network level options
 * wpdwm_remove_site_dash_widgets function.
 * 
 * @access public
 * @return void
 */
function wpdwm_remove_site_dash_widgets() {
	
	// Globalize the meta boxes array
	global $meta_boxes_site;
	
	// Get the site level and network level dashboard widget removal settings
	$WP_remove_option = get_option( 'wp_remove_site_widgets' );
	$WP_remove_site_option = get_site_option( 'wp_remove_site_widgets_N' );
	
	// Loop through all IDs
	foreach( $meta_boxes_site as $meta_box => $title )
	{
		// If the ID is marked as removed by site or network level setting...
		if( isset( $WP_remove_option[ $meta_box ] ) || isset( $WP_remove_site_option[$meta_box] ) ) {
			remove_meta_box( $meta_box, 'dashboard', 'normal' );
			remove_meta_box( $meta_box, 'dashboard', 'side' );
		}
	}
}


/** Removes all network level dashboard widgets that were checked off in the network level options
 * wpdwm_remove_network_dash_widgets function.
 * 
 * @access public
 * @return void
 */
function wpdwm_remove_network_dash_widgets() {

	// Globalize the meta boxes array
	global $meta_boxes_network;
	
	// Get the network level dashboard widget removal settings
	$WP_remove_network_option = get_site_option( 'wp_remove_network_widgets' );
 	
 	// Loop through all IDs
 	foreach( $meta_boxes_network as $meta_box => $title )
	{
		// If the ID is marked as removed by network level setting...
		if( isset( $WP_remove_network_option[ $meta_box ] ) ) {
			remove_meta_box( $meta_box, 'dashboard-network', 'normal' );
			remove_meta_box( $meta_box, 'dashboard-network', 'side' );
		}
	}
}


/** Removes all global level dashboard widgets that were checked off in the network level options
 * wpdwm_remove_global_dashboard_widgets function.
 * 
 * @access public
 * @return void
 */
function wpdwm_remove_global_dashboard_widgets() {

	// Globalize the meta boxes array
	global $meta_boxes_global;
	
	// Get the network level dashboard widget removal settings
	$WP_remove_global_option = get_site_option( 'wp_remove_global_widgets' );
	
	// Loop through all IDs
	foreach( $meta_boxes_global as $meta_box => $title )
	{
		// If the ID is marked as removed by network level setting, remove it from the array
		if( isset( $WP_remove_global_option[$meta_box] ) ) {
			remove_meta_box( $meta_box, 'dashboard-user', 'normal' );
		}
  	}
}


/** Sets up the site level dashboard widget removal checkboxes
 * wpdwm_site_level_dash_widget_options function.
 * 
 * @access public
 * @return void
 */
function wpdwm_site_level_dash_widget_options() {

	global $meta_boxes_site;
	
	// Get the site level option for checkbox status
	$WP_remove_option = get_option( 'wp_remove_site_widgets' );
	
	// Get the network level option
	$WP_remove_site_option = get_site_option( 'wp_remove_site_widgets_N' );
	
	// Set up site dashboard widget removal checkboxes
	foreach( $meta_boxes_site as $meta_box => $title ) {
		// If the given dashboard widget is disabled through the network settings, remove it from the checkbox options in the site level settings page
		if ( !isset( $WP_remove_site_option[ $meta_box ] ) ) { ?>
			<input id='<?php echo $meta_box; ?>' type='checkbox' name='wp_remove_site_widgets[<?php echo $meta_box; ?>]'
			value='Removed' <?php isset( $WP_remove_option[$meta_box] ) ? checked( $WP_remove_option[ $meta_box ], 'Removed', true ) : NULL; ?> /><?php echo $title; ?><br><?php
		}
	} ?>
	
	<!-- Short info sentence -->
	<span class="description"><?php
		_e( 'Select all widgets you would like to remove from the site dashboard
		and click on the "Save Changes" button.', 'wp-dash-message' ) ?>
	</span><?php
	
	// Add another info message if multisite support is enabled
	if ( is_multisite() ) { ?>
		<br />

		<span class="description"><?php
			_e( 'NOTE: If widgets have been disabled through the "Network Settings"
			they can only be reactivated by a network admin and will not appear on
			this settings page.', 'wp-dash-message' ) ?>
		</span><?php
	}
}