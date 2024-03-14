<?php
/*----------------------------------------------------------------------------
 * mstw-tr-settings.php
 *	All functions for the MSTW Team Rosters Plugin settings.
 *		Loaded conditioned on is_admin() in mstw-tr-admin.php 
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2021-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.

 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *--------------------------------------------------------------------------*/

	//-------------------------------------------------------------------------------
	// Render the display settings page
	//
	function mstw_tr_settings_page( ) {
		//mstw_log_msg( "mstw_tr_settings_page:" );
		
		global $pagenow;
		
		include_once 'mstw-tr-data-fields-columns-settings.php';
		include_once 'mstw-tr-roster-table-settings.php';
		include_once 'mstw-tr-roster-color-settings.php';
		include_once 'mstw-tr-player-profiles-galleries-settings.php';
		
		//mstw_tr_data_fields_columns_setup( );
		mstw_tr_data_fields_setup( );
		//mstw_tr_data_fields_center_setup( );
		
		mstw_tr_roster_table_setup( );
		
		mstw_tr_roster_colors_setup( );
		
		mstw_tr_bio_gallery_setup( );
		
		?>
		<!-- The settings screen main form; includes all tabs -->
		<div class="wrap">
			<h2><?php echo __( 'Team Rosters Plugin Settings', 'team-rosters') ?></h2>
			
			<?php 
			//Get or set the current tab - default to first/main settings tab
			$current_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'data-fields-columns-tab' );
			
			//Set-up the tabs; set the current tab as active
			mstw_tr_admin_tabs( $current_tab );  
			?>
			
			<form action="options.php" method="post" id="target">
			
			<?php 
				//settings_fields( 'mstw_tr_settings' );
				switch ( $current_tab ) {
					case 'data-fields-columns-tab':
						//Outputs nonce, action, and option_page fields for a settings page.
						settings_fields( 'mstw_tr_settings' );
						?>
						<div id='data-fields-left'>
							<?php do_settings_sections( 'mstw-tr-data-fields-labels' ); ?>
						</div>
						<div id='data-fields-center'>
							<?php do_settings_sections( 'mstw-tr-fields-show-hide' ); ?>
						</div>
						<div id='data-fields-right'>
						 <?php do_settings_sections( 'mstw-tr-fields-order' ); ?>
						</div>
						<?php
						$options_name = 'mstw_tr_options[reset]';
						break;
						
					case 'roster-table-tab':
						//Outputs nonce, action, and option_page fields for a settings page.
						settings_fields( 'mstw_tr_settings' );
						
						?>
						<div id='roster-table-settings-left'>
							<?php do_settings_sections( 'mstw-tr-roster-table' ); ?>
						</div>
						<div id='roster-table-settings-right'>
							<?php do_settings_sections( 'mstw-tr-roster2-table' ); ?>
						</div>
						<?php
						$options_name = 'mstw_tr_options[reset]';
						break;
						
					case 'roster-colors-tab':
						//Outputs nonce, action, and option_page fields for a settings page.
						settings_fields( 'mstw_tr_settings' );
						?>
						<div id='roster-table-settings-left'>
							<?php 
							do_settings_sections( 'mstw-tr-roster-colors' );
							?>
						</div>
						<div id='roster-table-settings-right' class="clearfix">
							<?php 
							do_settings_sections( 'mstw-tr-roster2-colors' );
							?>
						</div>
						<?php
						$options_name = 'mstw_tr_options[reset]';
						break;
						
					case 'bio-gallery-tab':
						settings_fields( 'mstw_tr_settings' );
						do_settings_sections( 'mstw-tr-bio-gallery' );
						$options_name = 'mstw_tr_options[reset]';
						break;
						
				}
				?>
				
			<table class="form-table">
				<!-- Add a spacer row -->
				<tr><td><input type='hidden' name='current_tab' id='current_tab' value='<?php echo $current_tab ?>' /></td></tr>
				<tr>
					<td>
						<input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'team-rosters' ) ?>" />
					
						<input type="submit" class="button-secondary" id="reset_btn" name="<?php echo $options_name ?>" onclick="tr_confirm_reset_defaults()" value="<?php _e( 'Reset Defaults', 'team-rosters' ) ?>" />
					</td>
				</tr>
			</table>
			<?php
			//} //End: if ( $pagenow == 'edit.php' && $_GET['page'] == 'mstw_tr_settings' )
			?>	
			</form>
		</div> <!-- <div .wrap> -->
	<?php
	} //End: mstw_tr_settings_page( )

	//-------------------------------------------------------------------------------
	// Create admin page tabs
	//
	function mstw_tr_admin_tabs( $current_tab = 'data-fields-columns-tab' ) {
		$tabs = array( 	'data-fields-columns-tab' => __( 'Roster Table Fields & Columns', 'team-rosters' ),
						'roster-table-tab' => __( 'Roster Tables', 'team-rosters' ),
						'roster-colors-tab' => __( 'Roster Table Colors', 'team-rosters' ),
						'bio-gallery-tab' => __( 'Player Profiles & Galleries', 'team-rosters' ),
						);
						
		echo '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $name ) {
			$class = ( $tab == $current_tab ) ? ' nav-tab-active' : '';
			//echo "<a class='nav-tab$class' href='edit.php?post_type=mstw_tr_player&page=mstw-tr-settings&tab=$tab'>$name</a>";
			echo "<a class='nav-tab$class' href='admin.php?page=mstw-tr-settings&tab=$tab'>$name</a>";
		}
		echo '</h2>';
	} //End: mstw_tr_admin_tabs( )
		

	//-------------------------------------------------------------------------------
	// HELP SCREENS
	//
	// Add help to settings screen
	// callback for load-$settings_page action
	//	
	function mstw_tr_settings_help( ) {
		//mstw_log_msg( "mstw_tr_settings_help:" );
		
		$screen = get_current_screen( );
		//mstw_log_msg( $screen );
		
		// All TR help screen have the same sidebar
		mstw_tr_help_sidebar( $screen );
		
		$tabs = array(
			array(
				'title'    => __( 'Data Fields & Columns', 'team-rosters' ),
				'id'       => 'data-fields-columns-help',
				'callback'  => 'mstw_tr_data_fields_columns_help'
				),
			array(
				'title'    => __( 'Roster Tables', 'team-rosters' ),
				'id'       => 'roster-tables-help',
				'callback'  => 'mstw_tr_roster_tables_help'
				),
			array(
				'title'		=> __( 'Roster Table Colors', 'team-rosters' ),
				'id'		=> 'roster-table-colors-help',
				'callback'	=> 'mstw_tr_roster_table_colors_help'
				),
			array(
				'title'		=> __( 'Player Profiles & Galleries', 'team-rosters' ),
				'id'		=> 'player-profiles-galleries-help',
				'callback'	=> 'mstw_tr_player_profiles_galleries_help'
				),
		);

		foreach( $tabs as $tab ) {
			$screen->add_help_tab( $tab );
		}
			
	} //End: mstw_tr_settings_help()


	//----------------------------------------------------------------------------
	// help tab content
	//
	function mstw_tr_data_fields_columns_help( ) {
		$help = '<h3><strong>' . __( 'Data Fields & Columns Settings:', 'team-rosters' ) . '</strong></h3>' .
				'<p>' . __('This screen controls the visibility of data fields/columns, the order of columns (in roster tables), and the field/column labels, for the roster front end displays. ', 'team-rosters' ) . "</p>\n" .
				'<p>' . __('Note that these settings apply to ALL roster and player displays (tables, profiles, galleries) on the site. To control individual displays by team, set the corresponding arguments in the shortcodes.', 'team-rosters' ) . "</p>\n" .
				'<p><a href="http://shoalsummitsolutions.com/tr-data-fields-columns/" target="_blank">' . __( 'See the Team Rosters Users Manual for more documentation.', 'team-rosters' ) . "</a></p>\n";
		echo $help;
	} //End:mstw_tr_data_fields_columns_help( )

	function mstw_tr_roster_tables_help( ) {
		$help = '<h3><strong>' . __( 'Roster Tables Settings:', 'team-rosters' ) . '</strong></h3>' .
				'<p>' . __('This screen provides controls for fields in the roster tables (mstw-tr-roster) and roster tables version 2 (mstw-tr-roster-2) shortcodes, related primarily to data display and display formats.', 'team-rosters' ) . "</p>\n" .
				'<p>' . __('Note that these settings apply to ALL roster tables/roster tables v2 on the site. To control individual tables by team, set the corresponding arguments in the shortcodes.', 'team-rosters' ) . "</p>\n" .
				'<p><a href="http://shoalsummitsolutions.com/tr-roster-tables/" target="_blank">' . __( 'See the Team Rosters Users Manual for more documentation.', 'team-rosters' ) . "</a></p>\n";
				
		echo $help;
	} //End: mstw_tr_roster_tables_help( )
		
	
	function mstw_tr_roster_table_colors_help( ) {
		$help = '<h3><strong>' . __( 'Roster Table Color Settings:', 'team-rosters' ) . '</strong></h3>' .
				'<p>' .  __('This screen controls the colors for the roster tables/roster tables version 2. Note that these settings apply to ALL roster tables/roster tables v2 on a site.', 'team-rosters' ) . "</p>\n" .
				'<p>' . sprintf( __('It is recommended that you use custom stylesheets (CSS) to control the colors of all front end displays. %sSee the Team Rosters users manual for more information on customization by team%s.', 'team-rosters' ), '<a href="http://shoalsummitsolutions.com/tr-customizing/" target="_blank">', '</a>' ) . "</p>\n" .
				'<p>' . sprintf( __('Examples of using CSS to customize the team roster displays may be found on %sthe MSTW Development Site%s.', 'team-rosters' ), '<a href="http://dev.shoalsummitsolutions.com/test-roster-plugin/" target="_blank">', '</a>' ) . "</p>\n" .
				'<p><a href="http://shoalsummitsolutions.com/tr-roster-table-colors/" target="_blank">' . __( 'See the Team Rosters Users Manual for more information on these settings.', 'team-rosters' ) . 
				"</a></p>\n";
				
		echo $help;
	} //End: mstw_tr_roster_table_colors_help( )

	
	function mstw_tr_player_profiles_galleries_help( ) {
		$help = '<h3><strong>' . __( 'Player Profiles & Galleries Settings:', 'team-rosters' ) . '</strong></h3>' .
				'<p>' . __('This screen provides controls for some data elements and colors of the player profiles and galleries. ', 'team-rosters' ) . "</p>\n" .
				'<p>' . __('Note that these settings apply to ALL player profiles and galleries on the site. There are a number of other ways to customize the displays for individual teams, including shortcode arguments and custom stylesheets (CSS). See the Users Manual (link below) for more information.', 'team-rosters' ) . "</p>\n" .
				'<p><a href="http://shoalsummitsolutions.com/tr-player-profiles-galleries/" target="_blank">' . __( 'See the Team Rosters Users Manual for more documentation.', 'team-rosters' ) . "</a></p>\n";
		echo $help;
	} //End: mstw_tr_player_profiles_galleries_help( )

	
	//-------------------------------------------------------------------------------
	//
	// VALIDATION FUNCTIONS
	//
	//-------------------------------------------------------------------------------
	// Validate the user data entries in Display (fields/data) tab
	//
	function mstw_tr_validate_settings( $input ) {
		//mstw_log_msg( 'mstw_tr_validate_settings:' );
		//mstw_log_msg( '$_POST = ' );
		//mstw_log_msg( $_POST );
		//mstw_log_msg( '$input = ' );
		//mstw_log_msg( $input );
		
		// only replace existing settings with valid $input values
		$output = mstw_tr_get_settings( );
		
		if( array_key_exists( 'team', $output ) ) {
			unset( $output['team'] );
		}
		
		// Get current tab so we know what fields to validate and save
		// Default to first/main settings tab
		$current_tab = ( isset( $_POST['current_tab'] ) ) ? $_POST['current_tab'] : 'data-fields-columns-tab';

		//check if the reset button was pressed and confirmed
		//array_key_exists() returns true for null, isset does not
		if ( array_key_exists( 'reset', $input ) ) {
			//mstw_log_msg( "OK, we are looking to reset defaults current_tab = $current_tab" );
			if( $input['reset'] == 'Resetting Defaults' ) {
				// reset to defaults
				switch( $current_tab ) {
					case 'data-fields-columns-tab':
						$output = array_merge( $output, mstw_tr_get_data_fields_columns_defaults( ) );
						$msg = __( 'Rpster Table data fields & columns settings reset to defaults.', 'team-rosters');
						break;
					case 'roster-table-tab':
						$output = array_merge( $output, 
																	 mstw_tr_get_roster_table_defaults( ),
																	 mstw_tr_get_roster_table2_defaults( ) 
																	);
						$msg = __( 'Roster table settings reset to defaults.', 'team-rosters');
						break;
					case 'roster-colors-tab':
						$output = array_merge( $output, mstw_tr_get_roster_table_colors_defaults( ) );
						$msg = __( 'Roster table color settings reset to defaults.', 'team-rosters');
						break;
					case 'bio-gallery-tab':
						$output = array_merge( $output, mstw_tr_get_bio_gallery_defaults( ) );
						$msg = __( 'Player profile & gallery settings reset to defaults.', 'team-rosters');
						break;	
				}
				
				mstw_tr_add_admin_notice( 'mstw-tr-admin-notice','updated', $msg );
			}
			else {
				// Don't change nuthin'
				mstw_tr_add_admin_notice( 'mstw-tr-admin-notice', 'updated', 'Settings reset to defaults canceled.' );
			}
		}
		else {
			switch ( $current_tab ) {
				case 'data-fields-columns-tab':
					foreach( $input as $key => $value ) {
						$output[$key] = ( sanitize_text_field( $input[$key] ) == $input[$key] ) ? $input[$key] : $output[$key];
					}
					
					$msg = __( 'Data fields & columns settings updated.', 'mstw-team-roster' );
					break;
					
				case 'roster-table-tab':
					// checkboxes are unique
					$output['links_to_profiles'] = isset( $input['links_to_profiles'] ) and $input['links_to_profiles'] == 1 ? 1 : 0;

					foreach( $input as $key => $value ) {
						switch( $key ) {
							case 'table_photo_width':
							case 'table_photo_height':
								// check numbers ... blanks or positive integers
								if( $input[$key] == '' ) {
									$output[$key] = '';
								}
								else if ( $input[$key] != '' and 
										( intval( $input[$key] ) <= 0 or
										  (string)$input[$key] != (string)intval( $input[$key] ) or
										  $input[$key] != abs( $input[$key] ) ) 
										) {	
									// set error message and don't change settings
									$msg = sprintf( __( 'Error with %s = \'%s\'. Reset to previous value.', 'mstw-team-roster' ), $key, $input[$key] );
									mstw_tr_add_admin_notice( 'mstw-tr-admin-notice', 'error', $msg );
								} else {
									$output[$key] = abs( intval( $input[$key] ) );
								}
								break;
							default:
								$output[$key] = ( sanitize_text_field( $input[$key] ) == $input[$key] ) ? $input[$key] : $output[$key];
								break;
						}
					}
					
					$msg = __( 'Roster tables settings updated.', 'mstw-team-roster' );
					break;
					
				case 'roster-colors-tab':
					//mstw_log_msg( "validating ... $current_tab" );
					
					// checkboxes are unique
					$output['use_team_colors'] = isset( $input['use_team_colors'] ) and $input['use_team_colors'] == 1 ? 1 : 0;
					
					foreach( $input as $key => $value ) {
						switch( $key ) {
							case 'use_team_colors':
								// handled this one above
								break;
							default:
								// all the color settings
								$sanitized_color = mstw_tr_sanitize_hex_color( $input[$key] );
								// decide what to do - save new setting 
								// or display error & revert to last setting
								if ( isset( $sanitized_color ) ) {
									// blank input is valid
									$output[$key] = $sanitized_color;
								}
								else  {
									// there's an error. Reset to the last stored value ...
									// don't need to do this but $output[$key] = $options[$key];
									// and add error message
									$msg = sprintf( __( 'Error: %s reset to the default.', 'mstw-team-roster' ), $key );
									mstw_tr_add_admin_notice( 'mstw-tr-admin-notice', 'error', $msg );
								}
								break;
						}
						
					}
							
					$msg = __( 'Roster table colors settings updated.', 'mstw-team-roster' );
					break;
					
				case 'bio-gallery-tab':
					//mstw_log_msg( 'validating ... $current_tab= bio-gallery-tab' );
					// checkboxes are unique
					$output['sp_show_title'] = isset( $input['sp_show_title'] ) and $input['sp_show_title'] == 1 ? 1 : 0;
					$output['sp_use_team_colors'] = isset( $input['sp_use_team_colors'] ) and $input['sp_use_team_colors'] == 1 ? 1 : 0;
					
					foreach( $input as $key => $value ) {
						switch( $key ) {
							case 'sp_show_title':
							case 'sp_show_logo':  //This is legacy JIC do nothing
							case 'sp_use_team_colors':
								//These checkboxes have been handled above
								//Can't do it here!
								break;
								
							case 'sp_content_title':  // text settings
							
								$output[$key] = ( sanitize_text_field( $input[$key] ) == $input[$key] ) ? $input[$key] : $output[$key];
								break;
							
							case 'sp_image_width':
							case 'sp_image_height':
								// check numbers ... blanks or positive integers
								if( $input[$key] == '' ) {
									$output[$key] = '';
								}
								else if ( $input[$key] != '' and 
										( intval( $input[$key] ) <= 0 or
										  (string)$input[$key] != (string)intval( $input[$key] ) or
										  $input[$key] != abs( $input[$key] ) ) 
										) {	
									// set error message and don't change settings
									$msg = sprintf( __( 'Error with %s = \'%s\'. Reset to previous value.', 'mstw-team-roster' ), $key, $input[$key] );
									mstw_tr_add_admin_notice( 'mstw-tr-admin-notice', 'error', $msg );
								} else {
									$output[$key] = abs( intval( $input[$key] ) );
								}
								break;	

							default: //color settings
								$sanitized_color = mstw_tr_sanitize_hex_color( $input[$key] );
								// decide what to do - save new setting 
								// or display error & revert to last setting
								if ( isset( $sanitized_color ) ) {
									// blank input is valid
									$output[$key] = $sanitized_color;
								}
								else  {
									// there's an error. Reset to the last stored value ...
									// don't need to do this but $output[$key] = $options[$key];
									// and add error message
									$msg = sprintf( __( 'Error: %s reset to the default.', 'mstw-team-roster' ), $key );
									mstw_tr_add_admin_notice( 'mstw-tr-admin-notice', 'error', $msg );
								}
								break;
						
						} //End: switch( $key )
					} //foreach( $input as $key
					
					$msg = __( 'Player profiles & galleries settings updated.', 'mstw-team-roster' );
					break;
					
			} // End: switch( $current_tab )
			
			// set updated message
			
			mstw_tr_add_admin_notice( 'mstw-tr-admin-notice', 'updated', $msg );
			
		} // End: else validate options, not reset
		
		return apply_filters( 'mstw_tr_sanitize_options', $output, $input );	
		
	} //End: mstw_tr_validate_settings( )

	//-------------------------------------------------------------------------------
	// mstw_tr_sanitize_hex_color - validates/sanitizes (3 or 6 digit) hex colors
	//		Returns input string if valid hex color (or ''); returns null otherwise		
	//

	function mstw_tr_sanitize_hex_color( $color ) {
		// the empty string is ok
		if ( '' === $color )
			return '';

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;
		
		// return null if input $color is not valid
		return null;
		
	} //End: mstw_tr_sanitize_hex_color()