<?php
 /*---------------------------------------------------------------------------
 *	mstw-tr-roster-tables-class.php
 *	Contains the classes for the MSTW League Manager Sport schedule table
 *  shortcodes [mstw_lm_sport_schedule] 
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2022-23 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. All rights 
 * 	reserved.
 *-------------------------------------------------------------------------*/

class MSTW_ROSTER_TABLE {
	
	public function __construct( ) {	
		remove_shortcode( 'mstw_tr_roster_2' );
		add_shortcode( 'mstw_tr_roster_2', array( $this, 'shortcodeHandler' ) );
		//Just a convenience - call it either way
		remove_shortcode( 'mstw-tr-roster-2' );
		add_shortcode( 'mstw-tr-roster-2', array( $this, 'shortcodeHandler' ) );
		
	}
	
	public function get_instance( ) {
		return $this; //return the object
	}
	
	//---------------------------------------------------------------------------
	// Handles the required shortcode inline arguments, builds the sort controls, 
	// then calls the (hidden) mstw-tr-roster-table shortcode.
	//
	function shortcodeHandler( $args, $content=null, $shortcode ) {
		//mstw_log_msg( "MSTW_ROSTER_TABLE.shortcodeHandler: $shortcode" );
		
		switch ( $shortcode ) {
			case 'mstw_tr_roster_2':
			case 'mstw-tr-roster-2':
				//
				// the team must be provided in the shortcode args; 
				//
				$teamSlug = $this -> safeGet( 'team', $args, null );
				//mstw_log_msg( "team = $teamSlug" );
				if ( null === $teamSlug ) {
					return '<h3>No team specified in shortcode.</h3>';
				}
				
				$omit = array( 'sort_order', 'no_controls' );
				
				$argsStr = $this -> create_args_str( $args, $omit );
				
				//mstw_log_msg( "args string = $argsStr" );
				
				// should this be null or custom??
				$rosterType = $this -> safeGet( 'roster_type', $args, null );
				
				// tableID allows multiple tables on the same page
				$tableID = $this -> safeGet( 'table_id', $args, mt_rand( 1000, 9999 ) );
				//mstw_log_msg( "tableID = $tableID" );
				
				// merge the shortcode arguments and the settings/options
				$attribs = $this -> processAtts( $args, $shortcode );
							
				ob_start( );
					
					?>
					<div class='mstw-tr-roster-table-container mstw-tr-roster-table-container-<?php echo $teamSlug ?>' id='mstw-tr-roster-table-container-<?php echo $tableID ?>'>
					
	
							<?php $noControls = $this -> safeGet( 'no_controls', $args, null );
							//mstw_log_msg( "noControls= $noControls" );
							
							if ( null === $noControls ) { ?>
								<!--<div class='mstw-tr-roster-title-controls mstw-tr-roster-title-controls-<?php //echo $teamSlug ?>'>-->
									<?php //echo $this -> build_roster_title( $teamSlug, $attribs ); ?>
									<div class='roster-sort-controls roster-sort-controls-<?php echo $teamSlug ?> MSTW-flex-row'>
										<?php echo $this -> build_roster_sort_controls( $teamSlug, $attribs, $args, $argsStr, $tableID ); ?>
									</div>
								<!-- </div> .mstw-tr-roster-title-controls -->
							<?php
							} ?>
						
						
						<div class= 'mstw-tr-roster-players mstw-tr-roster-players-<?php echo $teamSlug ?>' id='mstw-tr-roster-players-<?php echo $tableID ?>'>
						<?php
							
							// get the players
							$players = mstw_tr_build_player_list( $teamSlug, 'objects', $attribs );
						
							// build the html
							echo $this -> buildTableHTML( $teamSlug, $players, $attribs, $shortcode, $tableID );
							?>
						</div>
					</div> <?php //.mstw-tr-roster-table-container 

				return ob_get_clean();
				
				break;
				
			default:
				return "<h1>shortcode = $shortcode is not valid. Contact your system adminstrator.</h1>";
				break;
			
		} //End: switch( $shortcode )	
		
	} //End: shortcodeHandler( )
	
	function buildTableHTML( $teamSlug, $players, $attribs, $shortcode, $tableID = '' ) {
		//mstw_log_msg( "MSTW_ROSTER_TABLE.buildTableHTML: shortcode= $shortcode" );
		
		$rosterType = $this -> safeGet( 'roster_type', $attribs, 'custom' );
		//mstw_log_msg( "orig roster_type = $rosterType" );
		$rosterType = ( mstw_tr_is_valid_roster_type( $rosterType ) ) ? $rosterType : 'custom';
		//mstw_log_msg( "new roster_type= $rosterType" );
		
		ob_start();

		//
		// include the right html if the 'use team colors' option is set
		// this is all hidden for use by the jQuery script
		//
		//$output .= mstw_tr_build_team_colors_html( $teamSlug, $attribs, 'table' );
		
		?>
		<ul class='mstw-tr-roster-player-list mstw-tr-roster-player-list-<?php echo $teamSlug ?> mstw-tr-roster-player-list-<?php echo $tableID ?>'>
			
			<!-- Why is this needed ?? -->
			<?php $team_class = 'mstw-tr-table_' . $teamSlug; ?>
			<div style='display:none' id='table-id'><?php echo $team_class ?></div>
		
			<?php
			// Loop through the players and make the rows
			foreach ( $players as $player ) {	
				$dataField1 = $this -> get_data_field( $player->ID, $rosterType, $attribs, 1 );
				//get_post_meta( $player->ID, 'player_year', true );
				//$dataField2 = get_post_meta( $player->ID, 'player_home_town', true );
				$dataField2 = $this -> get_data_field( $player->ID, $rosterType, $attribs, 2 );
				//$dataField3 = get_post_meta( $player->ID, 'player_last_school', true );
				$dataField3 = $this -> get_data_field( $player->ID, $rosterType, $attribs, 3 );
				
				?>
				<li class='mstw-tr-roster-player'>
					<div class='mstw-tr-roster-player-container mstw-tr-roster-player-container-<?php echo $teamSlug ?> MSTW-flex-row'>
				
					<?php //PRIMARY INFO COLUMN ?>
					<div class='mstw-tr-roster-player-details'>
						<div class='mstw-tr-roster-player-image'>
						<?php 
							// PHOTO COLUMN
							// 'profile' prevents link
							echo $this -> buildPlayerPhoto( $player, $teamSlug, $attribs );
						?>
						</div>
						
						<?php // PERTINENTS COLUMN - position, number & name ?>
						
						<div class='mstw-tr-roster-player-pertinents'>
						
							<div class='mstw-tr-roster-player-position MSTW-uppercase'>
								<?php echo $this -> get_player_position( $player ); ?>
							</div>
							
							<div class='mstw-tr-roster-player-number-name MSTW-uppercase'>
								<?php
								$playerName = $this -> buildPlayerName( $player, $teamSlug, $attribs, 0 );
								$playerNumber = get_post_meta( $player->ID, 'player_number', true );
								?>
								<span class='jersey'><?php echo $playerNumber ?></span><h3 class='player-name MSTW-uppercase'><?php echo $playerName ?> </h3>
							</div>
							
						</div> <!-- .mstw-tr-roster-player-pertinents -->
					</div> <!-- .mstw-tr-roster-player-details -->
					
					<?php // SECONDARY INFO COLUMN ?>
					<div class='mstw-tr-roster-player-other'>
						<div class='mstw-tr-roster-player-other-data'>							
							<span class='mstw-tr-player-data-1'><?php echo $dataField1 ?></span>
							<span class='mstw-tr-player-data-2'><?php echo $dataField2 ?></span>
							<span class='mstw-tr-player-data-3'><?php echo $dataField3 ?></span>						</div>
						<div class='mstw-tr-roster-player-bio'>
							<?php $playerLink = '<a href="' .  get_permalink( $player->ID ) . '?roster_type=' . $this -> safeGet( 'roster_type', $attribs, 'custom' ) . '&' . 'team=' . $teamSlug . '"'; ?>
							<?php //mstw_log_msg( $playerLink ) ?>
							<?php echo $playerLink ?>>> FULL BIO </a>  <!-- &#9654; -->
						</div>
					</div> <?php // .mstw=tr=roster-player-other-data ?>
					
					</div> <?php //mstw-tr-roster-player-container ?>
				</li> <?php //mstw-tr-roster-player ?>
	
			<?php
			} // end of foreach player or end of table content

		
		//</table> ???
		
		?>
		</ul> 
		
		<?php
		//return $output;
		return ob_get_clean( );
		
	} //End: buildTableHTML( )
	
	//
	//-----------------------------------------------------------------------------
	// buildPlayerPhoto - builds the player photo cell for roster tables
	//
	// ARGUMENTS
	// 	$player - the (inline) shortcode arguments
	//	$teamSlug - the shortcode (might process atts differently for each shortcode)
	//	$options - merged shortcode arguments and plugin settings
	//	$addProfileLink - add link to player profile
	//
	//	1. Use the player photo (thumbnail) if available
	//	2. Else use the team logo from the teams DB, if available,
	//	3. Else use the team logo in the theme's /team-rosters/images/ dir
	//	4. Else use the default-photo-team-slug.png from the plugin images dir
	//	5. Else use the default-photo.png (mystery player) from the plugin images dir
	//
	// RETURNS
	//	HTML for player photo cell
	//
	function buildPlayerPhoto( $player, $teamSlug, $options, $addProfileLink = 0 ) {
		//mstw_log_msg( "MSTW_ROSTER_TABLE.buildPlayerPhoto: teamSlug = $teamSlug" );
	
		// This is the default if nothing else can be found
		$photo_file_url = '';
		$photo_html = '';
		$logo_html = '';

		if ( has_post_thumbnail( $player->ID ) ) { 
			// Use the player's thumbnail (featured image) if available
			$photo_file_url = wp_get_attachment_thumb_url( get_post_thumbnail_id( $player->ID ) );
			$first_name = get_post_meta($player->ID, 'player_first_name', true );
			$last_name = get_post_meta($player->ID, 'player_last_name', true );
			$alt = "$first_name $last_name";
			$photo_html = "<img src='$photo_file_url' alt='$alt' />";
			
		} else {
			// Try to build a team logo
			$photo_html = mstw_tr_build_team_logo( $teamSlug );
		
		}
		
		if( !$photo_html ) {
			// Give up and use the "mystery man"
			$photo_file_url = plugins_url( ) . '/team-rosters/images/default-images/default-photo.png';
			$alt = __( 'No player photo found.', 'team-rosters' );
			$photo_html = "<img src='$photo_file_url' alt='$alt' />";
		}
			
		//
		// add the link to the player profile, always in Roster Table 2
		//
		
		$paramStr = '?roster_type=' . $options['roster_type'];
		if ( $teamSlug ) {
			$paramStr .= "&team=$teamSlug";
		}
		
		$ret_html = '<a href="' .  get_permalink( $player->ID ) . $paramStr . '" ';
		$ret_html .= '>' . $photo_html . '</a>';
		
		return $ret_html;
			
	} //End: buildPlayerPhoto()
	
	//-----------------------------------------------------------
	//	buildPlayerName: constructs a player name based on first, last, and 
	//	$options['name_format_2']
	//	ARGS: 
	//		$player - player CPT object (mstw_tr_player)
	//		$options - shortcode args and team roster settings merged
	//		$addProfileLink - include a like to the player profile
	//	RETURNS
	//		$player_name in the specified format
	//
	function buildPlayerName( $player, $teamSlug, $options, $addProfileLink = 0 ) {
		////mstw_log_msg( "MSTW_ROSTER_TABLE.buildPlayerName: teamSlug = $teamSlug" );
		
		$first_name = get_post_meta($player->ID, 'player_first_name', true );
		$last_name = get_post_meta($player->ID, 'player_last_name', true );
		
		switch ( $options['name_format_2'] ) { 
			case 'first-last':
				$player_name = "$first_name $last_name";
				break; 
			case 'first-only':
				$player_name = $first_name;
				break;
			case 'last-only':
				$player_name = $last_name;
				break;
			case 'last-first':
			default:
				$player_name = "$last_name, $first_name";
				break; 
		} 
		
		$player_html = $player_name;
		
		$paramStr = '?roster_type=' . $options['roster_type'];
		if ( $teamSlug ) {
			$paramStr .= "&team=$teamSlug";
		}
		
		$ret_html = '<a href="' .  get_permalink( $player->ID ) . $paramStr . '" ';
		$ret_html .= '>' . $player_html . '</a>';
		
		//if( $addProfileLink ) {
			//if ( $options['links_to_profiles'] ) {
				//$player_html = '<a href="' .  get_permalink( $player->ID ) . '?roster_type=' . $options['roster_type'] . '" ';
				//$player_html .= '>' . $player_name . '</a>';
			//}
		//}
	
		//return $player_html;
		return $ret_html;
		
	} //End: buildPlayerName()

	//-----------------------------------------------------------------------------
	// get_player_position - returns the specified player's position string
	//
	// ARGUMENTS
	// 	$player - the player CPT (object)
	//
	// RETURNS
	//	The specified player's position - long format position if not empty, else short format
	//
	function get_player_position( $player ) {
		//mstw_log_msg( "MSTW_ROSTER_TABLE.get_player_position:" );
		
		$longPosition = get_post_meta( $player->ID, 'player_position_long', true );  
		
		$playerPosition = ( '' == $longPosition ) ? get_post_meta( $player->ID, 'player_position', true ) : $longPosition;
			; 
			
		return $playerPosition;
		
	} //End: get_player_position( )
	
	//-----------------------------------------------------------------------------
	// get_data_field - returns the specified data field for a player
	//
	// ARGUMENTS
	// 	$playerID - player CPT ID
	//	$rosterType - valid roster type (pro, baseball-pro, college, etc)
	//	$atts - blended settings and arguments
	//	$fldNbr - field to get 1, 2, or 3
	//
	// RETURNS
	//	The specified player's data field based on shortcode settings
	//
	function get_data_field( $playerID, $rosterType, $atts, $fldNbr = 1 ) {
		//mstw_log_msg( "MSTW_ROSTER_TABLE.get_data_field: rosterType = $rosterType" );
		
		global $TEXT_DOMAIN;
		
		$playerData = '';
		
		// converts fields in $atts to data fields
		$conversionTable = array ( 	'height' => 'player_height',
																'weight' => 'player_weight',
																'bats-throws' => '',
																'height-weight' => '',
																'year-short' => 'player_year',
																'year-long' => 'player_year_long',
																'experience' => 'player_experience',
																'age' => 'player_age',
																'home-town' => 'player_home_town',
																'last-school' => 'player_last_school',
																'country' => 'player_country',
																'other-info' => 'player_other',
																);
													
		//get the data field from the $fldNbr and the $atts
		// build the field name based on the specified field number
		$fieldKey = 'data_field_' . $fldNbr;
		
		if ( 'custom' == $rosterType ) {
			$fieldSlug = mstw_tr_safe_ref( $atts, $fieldKey );
		} else {
			$fieldSlug = $this -> get_field_key( $rosterType, $fldNbr );
		}
		//mstw_log_msg( "fieldSlug= $fieldSlug" );
		
		switch( $fieldSlug ) {
			case 'height-weight' :
				$height = ( get_post_meta($playerID, 'player_height', true ) ) ?: '--';
				$weight = ( get_post_meta($playerID, 'player_weight', true ) ) ?: '--';
				$fieldValue = "$height/$weight";
				break;
				
			case 'year-long':
				$dataField = $conversionTable[ $fieldSlug ];
				$fieldValue = get_post_meta( $playerID, $dataField, true );
				
				if ( '' == $fieldValue ) {
					$fieldValue = get_post_meta( $playerID, 'player_year', true );
				}
				break;
				
			case 'bats-throws':
				$btConversion = array( __( '--', $TEXT_DOMAIN ), 
															 __( 'R', $TEXT_DOMAIN ),
															 __( 'L', $TEXT_DOMAIN ),
															 __( 'B', $TEXT_DOMAIN )
														 );
				
				$bats = ( get_post_meta($playerID, 'player_bats', true ) ) ?: 0;
				$throws = ( get_post_meta($playerID, 'player_throws', true ) ) ?: 0;
				
				$fieldValue = $btConversion[$bats] . '/' . $btConversion[$throws];
				break;
				
			default:
				if ( array_key_exists( $fieldSlug, $conversionTable ) ) { 
					//get the specified data field for the player
					$dataField = $conversionTable[ $fieldSlug ];
					//do your thing
					$fieldValue = get_post_meta( $playerID, $dataField, true );
				} else {
					// Something is wrong. This is a bug!
					//mstw_log_msg( "$dataField is not valid" );
					$fieldValue = "--";
					
				}
				break;
			
		} //End: switch( $fieldSlug )
		
	return $fieldValue;
		
	} //End: get_data_field( )
	
	//
	//-----------------------------------------------------------------------------
	// get_field_key - returns the specified data field for a player
	//
	// ARGUMENTS
	//	$rosterType - valid roster type NOT custom
	//	$fldNbr - field to get 1, 2, or 3
	//
	// RETURNS
	//	The appropriate field slug
	//
	function get_field_key( $rosterType, $fldNbr ) {
		//mstw_log_msg( "get_field_key:" );
		
		$conversionTable = array(  'high-school'	=> 
																	array( 'year-long', 'height-weight', '' ),
															 'baseball-high-school' => 
																	array( 'year-long', 'height-weight', 'bats-throws' ),
															 'college' 	=> 
																	array( 'year-long', 'home-town', 'last-school'),
															 'baseball-college' => 
																	array( 'year-long', 'home-town', 'last-school'),
															 'pro'  => 
																	array ( 'experience', 'last-school', 'country'),
															 'baseball-pro' => 
																	array ( 'experience', 'last-school', 'country'),
														);
														
			return $conversionTable[$rosterType][$fldNbr-1];
		
	} //End: get_field_key( )

	//
	//-----------------------------------------------------------------------------
	// processAtts - squares away the atts passed into the shortcode and the 
	//		shortcode options (settings) 
	//
	// ARGUMENTS
	// 	$atts      - the (inline) shortcode arguments
	//	$shortcode - the shortcode (might process atts differently for each shortcode)
	//
	// RETURNS
	//	$attributes - merged and processed atts and options
	//
	function processAtts( $atts, $shortcode ) {
		//mstw_log_msg( "MSTW_ROSTER_TABLE.processAtts: shortcode= $shortcode" );
		//mstw_log_msg( "Passed atts: " );
		//mstw_log_msg( $atts );
		
		switch( $shortcode ) {
			case 'mstw_tr_roster_2' :
				//
				// the team must be provided in the shortcode args; 
				//
				/*
				$team = $this -> safeGet( 'team', $atts, null );
				if ( null === $team ) {
					return '<h3>No team specified in shortcode.</h3>';
				}
				*/
				
				//
				// the roster type comes from the shortcode args; defaults to 'custom'
				//
				$roster_type = $this -> safeGet( 'roster_type', $atts, 'custom' );
				
				//mstw_log_msg( "Passed atts[roster_type]: " . $atts[ 'roster_type' ] );
				
				if ( $roster_type != 'custom' ) {
					$roster_type = ( mstw_tr_is_valid_roster_type( $atts['roster_type'] ) ) ? $atts['roster_type'] : 'custom';
				}
				
				// get the options set in the admin screen
				$options = get_option( 'mstw_tr_options' );
				
				// and merge them with the defaults
				$args = wp_parse_args( $options, mstw_tr_get_defaults( ) );
				
				// then merge the arguments passed to the shortcode 
				$attribs = shortcode_atts( $args, $atts );

				// if a specific roster_type is specified, it takes priority over all
				// including the other shortcode args
				if( 'custom' != $roster_type ) {
					$fields = mstw_tr_get_fields_by_roster_type( $roster_type );
					$attribs = wp_parse_args( $fields, $attribs );
				}
				break;
				
			default:
				return ( "<h1>shortcode $shortcode does not exist. Contact your system adminstrator. </h1>" );
				break;
			
		} //End: switch( $shortcode ) {
			
		//mstw_log_msg( "Returning attributes:" );
		//mstw_log_msg( $attribs );
			
		return $attribs;
		
	} //End: processAtts( )
	
		
	//--------------------------------------------------------------------------------------
	// build_roster_sort_control - Returns the HTML for a team roster sort controls
	//
	//	ARGUMENTS: 
	//		$team: team slug
	//		$attribs - shortcode args merges with the plugin settings
	//		$args: arguments passed to plugin
	//		$argStr: (modified) arg string passed to AJAX
	//		$tableID: hidden so AJAX can process the right table
	//
	//	RETURNS:
	//		HTML for roster table
	//    	$teamSlug $args, $argsStr, $tableID

	function build_roster_sort_controls( $team, $attribs, $args, $argsStr = '', $tableID = '' ) {
		//mstw_log_msg( "MSTW_ROSTER_TABLE.build_roster_sort_control: team: $team , argsStr: $argsStr, tableID: $tableID" );
		//mstw_log_msg( $args );
		
		$domain = 'team-rosters';

		// jersey # | name | position | hometown | class-year 
		$choices = array( 'numeric' => __( 'Jersey Number', $domain ),
											'alpha-first' => __( 'First Name', $domain ),
											'alpha-last' => __( 'Last Name', $domain ),
											'hometown' => __( 'Hometown', $domain ),
											'class-year' => __( 'Class/Year', $domain ),
										);
										
	
		$currentSortOrder = $this -> safeGet( 'sort_order', $args, 'numeric' );
			
		ob_start( ); 
			echo $this -> build_roster_title( $team, $attribs );
			?>
			<form id='tr-sort-controls' class='MSTW-flex-row' >
				<input type='hidden' id='roster-team' value='<?php echo $team ?>'/>
				<input type="hidden" id='tableID' value="<?php echo $tableID ?>" />
				<input type="hidden" id='args_<?php echo $tableID ?>' value="<?php echo $argsStr ?>" />
				
				<div class='tr-sort-menu'> 
					<select name='tr-sort-menu' id='tr-sort-menu_<?php echo $tableID ?>'>
						<?php 
						foreach( $choices as $slug => $label ) {
							$selected = ( $currentSortOrder == $slug ) ? 'selected="selected"' : '';
							?>
							<option value=<?php echo "$slug $selected" ?>> <?php echo $label ?></option>
							<?php
						}
						?>
					</select> 						
				</div>
				
				<div class='tr-sort-button'>
					<input type='button' class='secondary tr-sort-submit' id='<?php echo $tableID ?>' name='<?php echo $team ?>' value=<?php _e( 'Sort Roster', 'team-rosters' ) ?>/>
				</div> 
				
			</form>

		<?php	
		//mstw_log_msg( $html );

		//return $html;
		return ob_get_clean( );
			
	} //End: build_roster_sort_controls( )
	
		//
	//-----------------------------------------------------------------------------
	// build_roster_title - builds roster title html
	//
	// ARGUMENTS
	//	$teamSlug - the roster's team (slug)
	//	$attribs - merged shortcode arguments and plugin settings
	//
	// RETURNS
	//	HTML roster's title
	//
	function build_roster_title( $teamSlug, $attribs ) {
		//mstw_log_msg( "MSTW_ROSTER_TABLE.build_roster_title: teamSlug = $teamSlug" );
		
		$titleHTML = '';
		
		$show_title = $this -> safeGet( 'show_title_2', $attribs, 0 );
		
		if ( $show_title ) {
			$term_obj = get_term_by( 'slug', $teamSlug, 'mstw_tr_team', OBJECT );
			
			$team_name = ( $term_obj ) ? $term_obj->name : $teamSlug;
			
			$team_class = 'mstw-tr-roster-title mstw-tr-roster-title_' . $teamSlug;
			
			$roster = __( 'Roster', 'team-rosters' );
		
			$titleHTML = "<h1 class='$team_class'> $team_name $roster </h1>";
		}
		
		return $titleHTML;
		
	} //End: build_roster_title( )
	
	
	//
	// Convenience function to convert args array passed to shortcode into 
	// args string passed to shortcode
	//
	function create_args_str( $args, $omit = array( ) ) {
		//mstw_log_msg( 'MSTW_MULTI_TEAM_SCHEDULE.create_args_str:' );
	
		$argsStr = '';
		
		foreach ( $args as $key => $value ) {
			if ( !in_array( $key, $omit ) ) {
				$argsStr .= " $key='$value' ";
			}
		} //End: foreach( $args )

		return $argsStr;
		
	} //End: create_args_str( )
	
	//---------------------------------------------------------------------------
	// Convenience function to safely get value (needle) that may or may not exist
	//	from an array (haystack). returns default if necessary
	//
	function safeGet( $needle, $haystack, $default = '' ) {
		//mstw_log_msg( "MSTW_LM_SCHEDULE_TABLE.safeGet: needle= $needle" );
		
		if ( is_array( $haystack ) ) {
			if ( array_key_exists( $needle, $haystack ) ) {
				return $haystack[ $needle ];
			}
		} 
		
		return $default;

	} //End: safeGet( )

} //End: class MSTW_ROSTER_TABLE {
?>