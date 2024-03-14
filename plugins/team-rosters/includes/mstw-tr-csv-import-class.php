<?php
/* ------------------------------------------------------------------------
 * 	MSTW Team Rosters CSV Importer Class
 *		- Modified from CSVImporter by Denis Kobozev (d.v.kobozev@gmail.com)
 *		- All rights flow through under GNU GPL.
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 *--------------------------------------------------------------------------*/
 
 if( !class_exists( 'MSTW_TR_ImporterPlugin' ) ) {
	class MSTW_TR_ImporterPlugin {
		var $defaults = array(
			'csv_post_title'      => null,
			'csv_post_post'       => null,
			'csv_post_type'       => null,
			'csv_post_excerpt'    => null,
			'csv_post_date'       => null,
			'csv_post_tags'       => null,
			'csv_post_categories' => null,
			'csv_post_author'     => null,
			'csv_post_slug'       => null,
			'csv_post_parent'     => 0,
		);

		var $log = array();

		//
		// process_option checks/cleans up the $_POST values
		//
		function process_option( $name, $default, $params, $is_checkbox ) {
			//checkboxes which if unchecked do not return values in $_POST
			if ( $is_checkbox and !array_key_exists( $name, $params ) ) {
				$params[ $name ] = $default;	
			}
			
			if ( array_key_exists( $name, $params ) ) {
				$value = stripslashes( $params[ $name ] );
			} elseif ( $is_checkbox ) {
				//deal with unchecked checkbox value
			
			} else {
				$value = null;
			}
			
			return $value;
			
		} //End function process_option()

		//
		// The CSV Importer's admin screen
		//
		function form( ) {			
			//mstw_tr_log_msg( "MSTW_TR_ImporterPlugin.form:" );
			
			// check & cleanup the returned $_POST values
			$submit_value = $this->process_option( 'submit', 0, $_POST, false );
			$import_team = $this->process_option( 'csv_import_team', 0, $_POST, false );
			//mstw_tr_log_msg( "import team: $import_team" );
			
			//$csv_teams_import = $this->process_option( 'csv_teams_import', 0, $_POST, false );
			$move_photos = $this->process_option( 'csv_move_photos', 0, $_POST, true );
			
			if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
				$this->post( compact( 'submit_value', 'import_team', 'move_photos' ) );
			}

			// start form HTML {{{
			?>

			<div class="wrap">
				<?php //echo get_screen_icon(); ?>
				<h2><?php _e( 'Import CSV Files', 'team-rosters' ) ?></h2>
				
				<p class='mstw-lm-admin-instructions'>
				  <?php _e( 'Read the contextual help tab on the top right of this screen.', 'team-rosters' ) ?> 
				</p>

				<!-- TEAMS import form -->
				<form class="add:the-list: validate" method="post" enctype="multipart/form-data" action="">
					
					<table class='form-table'>
					<thead><tr><th><?php _e( 'Teams', 'team-rosters' ) ?></th></tr></thead>
						
						<tr>  <!-- CSV file selection field -->
							<td><label for="csv_teams_import"><?php _e( 'Teams CSV file:', 'team-rosters' ); ?></label></td>
							<td><input name="csv_teams_import" id="csv_teams_import" type="file" value="" aria-required="true" />
							<br/>
							<span class='description' >Select the CSV teams file to import.</span></td>
						</tr>
						
						<tr> <!-- Submit button -->
						<td colspan="2" class="submit tr-action-button"><input type="submit" class="button" name="submit" value="<?php _e( 'Import Teams', 'team-rosters' ); ?>"/></td>
						</tr>
					
					</table>
				</form> <!--End: Teams import form -->
				
				<!--<div id = "roster-progress">
				  <img src = "/wp-includes/js/thickbox/loadingAnimation.gif" />
				  <p class='mstw-lm-admin-instructions'><?php //_e( 'Processing ...', 'team-rosters' )?></p>
				</div> -->
				
				
				<!-- PLAYERS import form -->
				<?php $args = array(	
								'show_option_all'    => 'Select a team ...',
								'show_option_none'   => '',
								'orderby'            => 'name', 
								'order'              => 'ASC',
								'show_count'         => 0,
								'hide_empty'         => 0, 
								'child_of'           => 0,
								'exclude'            => '',
								'echo'               => 1,
								'selected'           => $import_team,
								'hierarchical'       => 0, 
								'name'               => 'csv_import_team',
								'id'                 => 'csv_import_team',
								'class'              => 'postform',
								'depth'              => 0,
								'tab_index'          => 0,
								'taxonomy'           => 'mstw_tr_team',
								'hide_if_empty'      => false
								); ?>				
				
				<form class="add:the-list: validate" method="post" enctype="multipart/form-data">

					<table class='form-table'>
						<thead>
							<tr><th colspan=2>
								<?php _e( 'Players', 'team-rosters' ) ?>
								<br/>
								<span class='description' style='font-weight: normal'><?php printf( __( 'The importer will use the "player-teams" column in the CSV file to assign teams to a player if that column is not empty.%s Otherwise, the player will be assigned to the team selected in the "Select Team to Import" dropdown. %sOtherwise, the player will be imported but will not be assigned to a team.', 'team-rosters' ), '<br/>', '<br/>' ) ?></span>
							</th></tr>
						</thead>	
									
						<tbody>
							<tr>  <!-- Team (to import) selection field -->
								<td><label for="csv_import_team"><?php _e( 'Select Team to Import:', 'team-rosters' ) ?></label></td>
								<td><?php wp_dropdown_categories( $args ) ?>
								<br/>
								<span class='description' ><?php _e( 'This team will be used as the default if there is no entry for a player in the player_teams column.', 'team-rosters' ) ?></span>
								</td>
							</tr>
							<tr>
								<td><label for="csv_move_photos"><?php _e( 'Move Player Photos:', 'team-rosters') ?></label></td>
								<td><input name="csv_move_photos" id="csv_move_photos" type="checkbox" value="1" />
								<br/>
								<span class='description' ><?php _e( 'If checked, photo files will be imported from their current locations to the media library.If unchecked, photo files will remain in their current locations.', 'team-rosters' ) ?></span>
								</td>
							</tr>
							<tr> <!-- CSV file selection field -->
								<td><label for="csv_players_import"><?php _e( 'Players CSV file:', 'team-rosters') ?></label></td>
								<td><input name="csv_players_import" id="csv_players_import" type="file" value="" aria-required="true" />
								<br/>
								<span class='description' ><?php _e( 'Select the CSV players file to import.', 'team-rosters' ) ?></span>
								</td>
							</tr>
							<tr> <!-- Submit button -->
								<td colspan="2" class="submit tr-action-button"><input type="submit" class="button" name="submit" value="Import Players" /></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div><!-- end wrap -->

			<?php
			// end form HTML }}}

		} //End of function form( )

		function print_messages() {
			//mstw_tr_log_msg( "MSTW_TR_ImporterPlugin.print_messages:" );
			//mstw_tr_log_msg( $this -> log );
			
			if ( !empty( $this->log ) ) { ?>

				<div class="wrap">
				
				<?php if (!empty($this->log['error'])): ?>
					<div class="error">
						<?php foreach ($this->log['error'] as $error): ?>
							<p><?php echo $error; ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($this->log['notice'])): ?>
					<div class="updated fade">
						<?php foreach ($this->log['notice'] as $notice): ?>
							<p><?php echo $notice; ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				
				</div><!-- end wrap -->

				<?php
				
				$this->log = array();
				
			} //End: if ( !empty( $this->log ) )
			
		} //End print_messages( )

		//
		// Handle post submission
		//
		function post( $options ) {
			//mstw_tr_log_msg( "MSTW_TR_ImporterPlugin.post:" );
			//mstw_tr_log_msg( $options );
			
			if ( !$options ) {
				mstw_tr_log_msg( 'Houston, we have a problem ... no $options' );
				return;
			}
			
			switch( $options['submit_value'] ) {
				case __( 'Import Teams', 'team-rosters' ):
					$file_id = 'csv_teams_import';
					//$msg_str is only used in summary messages
					$msg_str = array( __( 'team', 'team-rosters' ), __( 'teams', 'team-rosters' ) );
					break;
					
				case __( 'Import Players', 'team-rosters' ):
					$file_id = 'csv_players_import';
					//$msg_str is only used in summary messages
					$msg_str = array( __( 'player', 'team-rosters' ), __( 'players', 'team-rosters' ) );
					break;
					
				default:
					mstw_tr_log_msg( 'Error encountered in post() method. $submit_value = ' . $submit_value . '. Exiting' );
					return;
					break;
			}
			
			if ( !class_exists( 'MSTW_CSV_DataSource' ) ) {
				require_once 'MSTWDataSource.php';
			}
			
			$time_start = microtime(true);
			$csv = new MSTW_CSV_DataSource;
			
			$file = $_FILES[$file_id]['tmp_name'];
			if ( !$this->stripBOM( $file ) ) {
				$this -> print_messages( );
				return;
			}

			if ( !$csv -> load( $file ) ) {
				//mstw_tr_log_msg( "logging failure to load file" );
				$this->log['error'][] = sprintf( __( 'Failed to load file %s, aborting.', 'team-rosters' ), $file );
				$this->print_messages();
				return;
			}

			// pad shorter rows with empty values
			$csv -> symmetrize( );

			$skipped = 0;
			$imported = 0;
			$comments = 0;
			foreach ( $csv -> connect( ) as $csv_data ) {
				if ( empty( $csv_data ) or !$csv_data ) {
					mstw_tr_log_msg( 'No CSV data. $csv_data is empty.' );
				}
				
				//Insert the custom fields, which is most everything
				switch( $file_id ) {
					case 'csv_teams_import': 
						if ( $this->create_team_taxonomy_term( $csv_data, $options, $imported+1 ) ) {
							$imported++;
						}
						else {
							$skipped++;
						}
						break;
						
					case 'csv_players_import':
						// First try to create the post from the row
						// IS $IMPORTED+1 RIGHT HERE? OR JUST $IMPORTED?
						if ( $post_id = $this->create_post( $csv_data, $options, $imported+1 ) ) {
							$imported++;
							$this->create_player_fields( $post_id, $csv_data, $options );
						} 
						else {
							$skipped++;
						}
						break;
						
					default:
						mstw_tr_log_msg( 'Oops, something went wrong with file ID: ' . $file_id );
						break;
				}
			}

			if (file_exists($file)) {
				@unlink($file);
			}

			$exec_time = microtime(true) - $time_start;
			
			//add notice if any records were skipped
			if ( $skipped ) {
				$format = _n( 'Skipped %1$s %2$s.', 'Skipped %1$s %3$s', $skipped, 'team-rosters' );
				$admin_notice = sprintf( $format, $skipped, $msg_str[0], $msg_str[1] );
				$this->log['error'][] = "<b>{$admin_notice}</b>";
				//$this->log['error'][] = '<b>' . sprintf( _n( 'Skipped %s %s. See man page for possible causes.', 'team-rosters' ), $skipped, $term_msg ) . '</b>';
				//$sample = sprintf( _n('You have %d taco.', 'You have %d tacos.', $number, 'plugin-domain'), $number );
			}
			
			//always add notice for records imported and elapsed time
			$format = _n( 'Imported %1$s %2$s in %4$.2f seconds.', 'Imported %1$s %3$s in %4$.2f seconds.', $imported, 'team-rosters' );
			$admin_notice = sprintf( $format, $imported, $msg_str[0], $msg_str[1], $exec_time );
			$this->log['notice'][] = "<b>{$admin_notice}</b>";
			//sprintf( __('You have %d tacos', 'plugin-domain'), $number );
			
			$this->print_messages();
			
		} //End: post( )
		
		//
		// Create a new post (CPT) based on submit button pressed
		//
		function create_post( $data, $options ) {	
			//mstw_tr_log_msg( 'MSTW_TR_ImporterPlugin.create_post:' );
			//mstw_tr_log_msg( $data );
			//mstw_tr_log_msg( $options );
			
			$data = array_merge( $this->defaults, $data );
			
			// figure out what custom post type we're importing
			switch ( $options[ 'submit_value'] ) {
				case __( 'Import Players', 'team-rosters' ) :
					$type = 'mstw_tr_player';
					//this is used to add_action/remove_action below
					$save_suffix = 'player_meta';
					
					// need a player title to proceed
					if ( isset( $data['player_title'] ) && !empty( $data['player_title'] ) ) {
						$temp_title = $data['player_title'];	
					} 
					else { 
						//no title in CSV, figure it out from first & last names
						$temp_title = '';
						$temp_first_name = '';
						$temp_last_name = '';
						
						if( isset( $data['player_first_name'] ) and $data['player_first_name'] != '' ) {
							$temp_title = $data['player_first_name'];
						}
						if( isset( $data['player_last_name'] ) and $data['player_last_name'] != '' ) {
							if( $temp_title ) {
								$temp_title .= ' ' . $data['player_last_name'];
							}
						}
						
						$temp_title = ( $temp_title ) ? $temp_title :  __( 'No first or last name.', 'team-rosters' );

					}
			
					
					// slug should come from CSV file; else will default to sanitize_title()
					$temp_slug = ( isset( $data['player_slug'] ) && !empty( $data['player_slug'] ) ) ? $data['player_slug'] : sanitize_title( $temp_title, __( 'No title imported', 'team-rosters' ) );

					break;
					
				default:
					mstw_tr_log_msg( 'Whoa horsie ... submit_value = ' . $options[ 'submit_value'] );
					$this->log['error']["type-{$type}"] = sprintf(
						__( 'Unknown import type "%s".', 'team-rosters' ), $type );
					return false;
					break;
					
			}
			
			$new_post = array(
				'post_title'   => convert_chars( $temp_title ),
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => $type,
				'post_name'    => $temp_slug,
			);
			
			//
			// create the post
			//
			remove_action( 'save_post_' . $type, 'mstw_tr_save_' . $save_suffix, 20, 2 );
			$post_id = wp_insert_post( $new_post );
			add_action( 'save_post_' . $type, 'mstw_tr_save_' . $save_suffix, 20, 2 );
			
			// Add the specified team taxonomy, if provided
			if ( $post_id ) {
				//mstw_tr_log_msg( "Trying to add import_team tax" );
				//mstw_tr_log_msg( "for player " . convert_chars( $temp_title ) );
				if ( array_key_exists( 'import_team', $options ) && !empty( $options['import_team' ] ) && $options['import_team' ] > 0 ) {
					$term = get_term_by( 'id', $options['import_team'], 'mstw_tr_team' );
					wp_set_object_terms( $post_id, $term->slug, 'mstw_tr_team');
					
				}
			}
			
			return $post_id;
			
		} //End create_post()
		
		//-------------------------------------------------------------
		//	Add the fields from a row of CSV player data to a newly created post
		//-------------------------------------------------------------
		function create_player_fields( $post_id, $data, $options ) {
			
			$bats_list = array(  __( '----', 'team-rosters' )  => 0,
								 '0'								=> 0,
								 __( 'R', 'team-rosters' ) 	=> 1,
								 __( 'r', 'team-rosters' ) 	=> 1,
								 '1'								=> 1,
								 __( 'L', 'team-rosters' ) 	=> 2,
								 __( 'l', 'team-rosters' ) 	=> 2,
								 '2'								=> 2,
								 __( 'B', 'team-rosters' ) 	=> 3,
								 __( 'b', 'team-rosters' ) 	=> 3,
								 '3'								=> 3,
								);
							
			$throws_list = array( __( '----', 'team-rosters' ) => 0,
								  '0'								=> 0,			
								  __( 'R', 'team-rosters' ) 	=> 1,
								  __( 'r', 'team-rosters' ) 	=> 1,
								  '1'								=> 1,
								  __( 'L', 'team-rosters' ) 	=> 2, 
								  __( 'l', 'team-rosters' ) 	=> 2,
								  '2'								=> 2,
								);
			
			foreach ( $data as $k => $v ) {
				switch ( strtolower( $k ) ) {
					case 'player_title':
					case 'player_slug':
						//added in create_post(); nothing else to do here
						break;
						
					case 'player_bio':
						$player_bio = ( isset( $data['player_bio'] ) ) ? $data['player_bio'] : '';
						//post content is set to '' when post is created
						//	so do nothing is $data['player_bio'] is blank
						if( $player_bio ) {
							$player_bio_update = array( 'ID' => $post_id,
														'post_content' => wpautop( convert_chars( $player_bio ) ),
													  );
							wp_update_post( $player_bio_update );
						}
						break;
						
					case 'player_throws':
						//Need to switch indices
						$throws = ( array_key_exists( $v, $throws_list ) and $throws_list[ $v ] ) ? $throws_list[ $v ] : 0 ;
						$ret = update_post_meta( $post_id, $k, $throws );
						break;
						
					case 'player_bats':
						//Need to switch indices
						$bats = ( array_key_exists( $v, $bats_list ) and $bats_list[ $v ] ) ? $bats_list[ $v ] : 0 ;
						//$k = strtolower( $k );
						$ret = update_post_meta( $post_id, $k, $bats );
						break;
						
					// "NORMAL" player data
					case 'player_first_name':
					case 'player_last_name':
					case 'player_number':
					case 'player_position':
					case 'player_position_long': 
					case 'player_height':	
					case 'player_weight':
					case 'player_year':	
					case 'player_year_long':	
					case 'player_experience':
					case 'player_age':
					case 'player_home_town':
					case 'player_last_school':
					case 'player_country':
					case 'player_other':
						$k = strtolower( $k );
						$ret = update_post_meta( $post_id, $k, $v );
						break;
						
					case 'player_teams':
						if( !empty( $v ) ) {
							//build team(s) from the player_teams column
							
							//array_filter() removes empty strings from array
							//	created by str_getcsv()
							$teams_array = array_filter( str_getcsv( $v, ';', '"' ) );
							
							wp_set_object_terms( $post_id, $teams_array, 'mstw_tr_team', true );
				
						} 
						break;
						
					case 'player_photo':
						if( !empty( $v ) ) {
							if( array_key_exists( 'move_photos', $options ) and $options['move_photos'] ) {
								// Going to move photos from another server
								
								//Try to download player photo
								$temp_photo = download_url( $v );
								
								//Check for errors downloading
								if( is_wp_error( $temp_photo ) ) {
									mstw_tr_log_msg( "Error downloading: $v" );
									mstw_tr_log_msg( $temp_photo );
								}
								else {
									//Sucessfully downloaded file
									$file_array = array( 'name' => basename( $v ),
														'tmp_name' => $temp_photo,
													  );
									//Try to add file to media library & attach to player (CPT)
									$id = media_handle_sideload( $file_array, 0 );
									
									//Check for sideload errors
									if( is_wp_error( $id ) ) {
										mstw_tr_log_msg( "Error loading file to media library: $temp_photo" );
										mstw_tr_log_msg( $id );	
									} 
									else {
										//Success
										$post_meta_id = set_post_thumbnail( $post_id, $id );
										
										if( $post_meta_id === false ) {
											mstw_tr_log_msg( "Failed to set thumbnail for post $post_id" );	
										}
									}
									
								}
							}
							else {
								// Going to use photos already on this server
								$thumbnail_id = $this->find_attachment_id_from_url( $v );
								if( $thumbnail_id and $thumbnail_id != -1 ) {
									if( set_post_thumbnail( $post_id, $thumbnail_id ) === false ) {
										mstw_tr_log_msg( 'Failed to set_post_thumbnail. Post= ' . $post_id . ' thumbnail= ' . $thumbnail_id );
									}
								}
								else {
									mstw_tr_log_msg( 'No file found in the media library: ' . $thumbnail_id );
								}	
							}
						} 
						break;
						
					default:
						// bad column header
						mstw_tr_log_msg( 'Unrecognized game data field: ' . $k );
						break;
						
				}
			}
		} //End of function create_player_fields()
		
		//-------------------------------------------------------------
		//	find_attachment_id_from_url - returns an attachment ID given it's URL
		//
		//	ARGUMENTS:
		//		$url - a file URL
		//
		//	RETURN: attachment ID if one is found, -1 otherwise
		//
		//-----------------------------------------------------------
		function find_attachment_id_from_url( $url ) {
			
			// Split the $url into two pars with the wp-content directory as the separator
			$parsed_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_HOST ), $url );
			
			// Get the host of the current site and the host of the $url, ignoring www
			$this_host = str_ireplace( 'www.', '', parse_url( home_url( ), PHP_URL_HOST ) );
			$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

			// Return nothing if there aren't any $url parts or if the current host and $url host do not match
			if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
				$retval = -1;
			}
			else {
				// Now we're going to quickly search the DB for any attachment GUID with a partial path match
				// Example: /uploads/2013/05/test-image.jpg
				global $wpdb;

				$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );
		
				// Returns -1 if no attachment is found
				$retval = ( isset( $attachment) ) ? $attachment[0] : -1;
			}
			
			return $retval;
			
			
		} //End: find_attachment_id_from_url( )
		
		//
		// Add a new term to the team (custom) taxonomy
		//
		function create_team_taxonomy_term( $data, $options, $imported ) {
			//mstw_tr_log_msg( "MSTW_TR_ImporterPlugin:create_team_taxonomy_term:" );
			//mstw_tr_log_msg( "data:" );
			//mstw_tr_log_msg( $data );
			//mstw_tr_log_msg( "options:" );
			//mstw_tr_log_msg( $options );
			
			$retval = 0;
			
			$team_name = ( array_key_exists( 'team_name', $data ) ) ? $data['team_name'] : '' ;
			$team_slug = ( array_key_exists( 'team_slug', $data ) ) ? $data['team_slug'] : '' ;
			$team_description = ( array_key_exists( 'team_description', $data ) ) ? $data['team_description'] : '' ;		
		
			$ss_team_link = ( array_key_exists( 'ss_team_link', $data ) ) ? $data['ss_team_link'] : '' ;
			$lm_team_link = ( array_key_exists( 'lm_team_link', $data ) ) ? $data['lm_team_link'] : '' ;
			
			//check if team name & team slug is specified
			if( $team_slug != '' && $team_name != '' ) {
				//sanitize slug - JIC
				$team_slug = sanitize_title( $team_slug );
				
				$args = array( 'description' => $team_description,
							   'slug'		 => $team_slug,
							  );
				$result = wp_insert_term( $data['team_name'], 'mstw_tr_team', $args );	
			}
			
			//team slug not specified, try to create it from team name
			else if ( $team_name != '' ) {
				//create slug
				$team_slug = sanitize_title( $team_name );
				
				$args = array( 'description' => $team_description,
							   'slug'		 => $team_slug,
							  ); 
						  
				$result = wp_insert_term( $team_name, 'mstw_tr_team', $args );
			}
			
			else if ( $team_slug != '' ) {
				//sanitize slug - JIC
				$team_slug = sanitize_title( $team_slug );
				
				$args = array( 'description' => $team_description,
							   'slug'		 => $team_slug,
							  );
						  
				$result = wp_insert_term( $team_slug, 'mstw_tr_team', $args );			
			}
			
			//no slug and no name so bag it
			else {
				$result = new WP_Error( 'oops', __( 'No team name or slug found.', 'team-rosters' ) );
				
			}
			
			if ( is_wp_error( $result ) ) {
				mstw_tr_log_msg( 'Error inserting term ... ' );
				mstw_tr_log_msg( $result );
				$retval = 0;
			}
			else {
				//mstw_tr_log_msg( 'Term inserted ... ID= ' . $result['term_id'] );
				//
				// If it exists, the SS team link will be added as term meta data 
				// in favor of any LM team link
				//
				if( '' != $ss_team_link ) {
					// Need to do this because slug might change (if duplicate)
					//$term = get_term( $result['term_id'], 'mstw_tr_team' );
					//$team_slug = $term->slug;
					
					add_term_meta( $result['term_id'], 'tr_team_link', $ss_team_link );
					
					add_term_meta( $result['term_id'], 'tr_link_source', 'mstw_ss_team' );
					
				} else if ( '' != $lm_team_link ) {
					
					add_term_meta( $result['term_id'], 'tr_team_link', $lm_team_link );
					
					add_term_meta( $result['term_id'], 'tr_link_source', 'mstw_lm_team' );
					
				}
				
				$retval = 1;
			}
			
			return $retval;
			
		} //End: create_team_taxonomy_term( )

		//
		// Delete BOM from UTF-8 file.
		//
		function stripBOM( $fname ) {
			//mstw_tr_log_msg( "MSTW_TR_ImporterPlugin.stripBOM:" );
			
			$res = @fopen( $fname, 'rb' );
			
			if ( false !== $res ) {
				$bytes = fread($res, 3);
				if ($bytes == pack('CCC', 0xef, 0xbb, 0xbf)) {
					$this->log['notice'][] = 'Getting rid of byte order mark...';
					fclose($res);

					$contents = file_get_contents( $fname );
					if ( false === $contents ) {
						trigger_error( 'Failed to get file contents.', E_USER_WARNING );
					}
					$contents = substr($contents, 3);
					$success = file_put_contents($fname, $contents);
					
					if (false === $success) {
						trigger_error( 'Failed to put file contents.', E_USER_WARNING );
					}
					
				} else {
					fclose( $res );
					
				}
				
				return 1;
				
			} else {
				$fname = ( '' == $fname ) ? 'No file specified' : $fname ;
				$this->log['error'][] = sprintf( __( 'Failed to open file: %s.', 'team-rosters' ), $fname );
				
				return 0;
				
			}
			
		} //End: stripBOM( )
		
		//-------------------------------------------------------------------------------
		// HELP SCREENS
		//
		// Add help to settings screen
		// callback for load-$csv_import_page action in mstw-tr-admin.php
		//	
		function add_help( ) {
			//mstw_tr_log_msg( "MSTW_TR_ImporterPlugin.add_help:" );
			
			$screen = get_current_screen( );
			//mstw_tr_log_msg( $screen );
			
			// All TR help screen have the same sidebar
			mstw_tr_help_sidebar( $screen );
			
			$tabs = array(
				array(
					'title'     => __( 'CSV Import', 'team-rosters' ),
					'id'        => 'csv-import-help',
					'callback'  => array( $this, 'csv_import_help_content' ),
					),
			);

			foreach( $tabs as $tab ) {
				$screen->add_help_tab( $tab );
			}
			
		} //End: add_help( )
		
		function csv_import_help_content ( ) {
			//mstw_tr_log_msg( "MSTW_TR_ImporterPlugin.add_help:" );
			?>
			<p><?php _e( 'This screen allows the import of teams and players from files in CSV format. Sample file formats are available in the Users Manual (link below).', 'team-rosters' ) ?></p>
			
			<p><?php _e( 'To import teams, simply choose the CSV file and click "Import Teams".', 'team-rosters' ) ?></p>
			
			<p><?php _e( 'To import players, first select the CSV file containing the players, then you have some options:.', 'team-rosters' ) ?></p>
			
			<ul>
			<li><?php _e( 'Select an existing team. Players will be addeded to that team.', 'team-rosters' ) ?></li>
			<li><?php _e( 'Don\'t elect an existing team, and provide the team(s) in the player_teams column of the CSV file. This allows players to be added to multiple teams (or no team) using one CSV file.', 'team-rosters' ) ?></li>
			<li><?php _e( 'Choose whether you want the player photos (provided in the CSV file) to be moved to the Media Library. If you are moving teams from a different site, you probably want to do this. If you are moving players on the same site, you probably do not want to do this since it will create duplicate image files in the Media Library.', 'team-rosters' ) ?></li>
			</ul>
			
			<p><?php _e( 'NOTE THAT IT CAN TAKE A SIGNFICANT AMOUNT OF TIME TO IMPORT PLAYERS. In fact, if the CSV file is too large, WordPress process can time out at the server. If so, simply divide the players up across two or more CSV files.', 'team-rosters' ) ?></p>
			
			<p><a href="http://shoalsummitsolutions.com/loading-rosters-from-csv-files-v-4-0/" target="_blank"><?php _e( 'See the plugin Users Manual on shoalsummitsolutions.com', 'team-rosters' ) ?></a></p>
			
			<?php
			
		} //End: csv_import_help_content( )
	
		
		
	} //End: class MSTW_TR_ImporterPlugin
 }
 ?>