<?php
/*---------------------------------------------------------------------------
 *	mstw-tr-roster-table.php
 *		Code for the mstw-roster-table shortcode
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2015-23 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 *-------------------------------------------------------------------------*/

 // --------------------------------------------------------------------------------------
 // Add the table shortcode handler, which will create the a Team Roster table on the user side.
 // Handles the displayshortcode parameters, and display settings if there were any, 
 // then calls mstw_tr_build_roster_table() to create the output
 // --------------------------------------------------------------------------------------
 
function mstw_tr_roster_table_handler( $atts = null, $content = null, $shortcode_tag ){
	//mstw_log_msg( "mstw_tr_roster_table_handler: $shortcode_tag" );
	//mstw_log_msg( $atts );
	
	//
	// the roster type comes from the shortcode args; defaults to 'custom'
	//
	if ( is_array( $atts ) && array_key_exists( 'roster_type', $atts ) ) {
		$roster_type = ( mstw_tr_is_valid_roster_type( $atts['roster_type'] ) ) 
					 ? $atts['roster_type'] : 'custom';
	} else {
		$roster_type = 'custom';
	}
	
	//
	// the team comes from the shortcode args; must be provided
	//
	if ( is_array( $atts ) && array_key_exists( 'team', $atts ) ) {
		$team = $atts['team'];
	} else {
		return '<h3>No team specified in shortcode.</h3>';
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
		$fields  = mstw_tr_get_fields_by_roster_type( $roster_type );
		$attribs = wp_parse_args( $fields, $attribs );
	}
	
	if ( 'mstw-tr-roster' == $shortcode_tag or  'mstw_tr_roster' == $shortcode_tag ) {
		return mstw_tr_build_roster_table( $team, $roster_type, $attribs );
	} 
	
} //End: mstw_tr_roster_table_handler( )
 
 // --------------------------------------------------------------------------------------
 // Called by:	mstw_tr_table_handler
 // Builds the Team Roster table as a string (to replace the [shortcode] in a page or post).
 // Loops through the Player Custom posts in the "team" category and formats them 
 // into a pretty table.
 // --------------------------------------------------------------------------------------
	function mstw_tr_build_roster_table( $team, $roster_type, $attribs ) {
		//mstw_log_msg( "mstw_tr_build_roster_table: team= $team roster_type= $roster_type" );
		
		$fieldOrder = mstw_get_order_keys( $attribs );
		
		$output = "";
			
		// Set the roster table format. If default in [shortcode] atts, 
		// then use the default setting from admin page.
		
		if ( $attribs['show_title'] == 1 ) {
			//Set the title color
			
			$term_obj = get_term_by( 'slug', $team, 'mstw_tr_team', OBJECT );
			
			$team_name = ( $term_obj ) ? $term_obj->name : $team;
			
			$team_class = 'mstw-tr-roster-title mstw-tr-roster-title_' . $team;
			
			$title_h1 = "<h1 class='$team_class'>\n"; 
			
			$output .= $title_h1 . $team_name . ' Roster' . '</h1>';
		}
		
		$players = mstw_tr_build_player_list( $team, 'objects', $attribs );
		
		if( $players ) {
			// Make table of players
			// Start with the table header
			
			//
			// include the right html if the 'use team colors' option is set
			// this is all hidden for use by the jQuery script
			//
			$output .= mstw_tr_build_team_colors_html( $team, $attribs, 'table' );
			
			$team_class = 'mstw-tr-table_' . $team;
			
			$output .= "<div class='mstw-tr-scroll-wrapper'>";
			
			$output .= "<table id='$team_class' class='mstw-tr-table $team_class'>\n";
			
			$output .= "<div style='display:none' id='table-id'>$team_class</div>";
			
			$test_output = mstw_tr_build_roster_table_header( $roster_type, $attribs, $fieldOrder );
			//mstw_log_msg( $test_output );
			
			$output .= $test_output;

			// Loop through the players and make the rows
			foreach( $players as $post ) {
				$row_string = mstw_tr_build_roster_table_row( $roster_type, $post, $team, $attribs, $fieldOrder );
				$output .= $row_string;
			} // end of foreach post or end of table content
			
			$output .= '</table>';
			$output .= '</div>'; //scroll-wrapper
		}
		else { // No players were found
		
			$output = sprintf( __( "%sNo players found on team: '%s'%s", 'team-rosters' ), '<h1>', $team, '</h1>' );
			
		}
		
		return $output;
		
	}
	
	//-----------------------------------------------------------
	// mstw_get_order_keys - returns a sorted array order keys & values
	//	ARGUMENTS:
	//		$attribs: array of options or settings for the shortcode 
	//								the order keys start with 'order', e.g. order_name
	//	RETURNS:
	//		$orderKeys: a sorted array of order keys and values, e.g., 'order_name' => 1
	//
	function mstw_get_order_keys( $attribs ) {
		//mstw_log_msg( "mstw_get_order_keys:" );
		
		$orderKeys = array( );
		
		foreach ( $attribs as $key => $value ) {
			if ( strpos( $key, 'order' ) === 0 ) {
				$orderKeys[ $key ] = $value;
			}
		}
		
		asort( $orderKeys );
		
		return $orderKeys;
		
	} //End: mstw_get_order_keys( )
		
		
	//-----------------------------------------------------------
	// mstw_tr_build_roster_table_row - returns the HTML for a roster table row
	//	ARGUMENTS:
	//		$roster_type: pre-formatted roster type ('college', 'baseball-pro', etc.)
	//		$post: the player CPT (object)
	//		$team: the player's team (slug)
	//		$attribs: array of options or settings for the shortcode 
	//								the order keys start with 'order', e.g. order_name
	//		$fieldOrder: array of fields in the order displayed (if $attribs[show_field]==1)
	//	RETURNS:
	//		$row_string: HTML for header row starting with '<thead></tr>'
	//	
	function mstw_tr_build_roster_table_row( $roster_type, $post, $team, $attribs, $fieldOrder ) {
		//mstw_log_msg( "mstw_tr_build_roster_table_row:" );
		
		$row_tr = '<tr>';
		$row_td = '<td>';
		
		// create the row
		$row_string = $row_tr;
		
		foreach( $fieldOrder as $field => $order ) {
			switch( $field ) {
				case 'order_photo':
					if ( $attribs['show_photos'] ) {
						$row_string .= '<td>' . mstw_tr_build_player_photo( $post, $team, $attribs, 'table' ) . '</td>';
					}
					break;
					
				case 'order_number':
					if ( $attribs['show_number'] ) {
						$row_string .= mstw_tr_add_player_number( $post, "<td class='tr-table-nbr'>" );
					}
					break;
					
				case 'order_name':	
					// ALWAYS SHOW PLAYER'S NAME 
					$row_string .= '<td>' . mstw_tr_build_player_name( $post, $attribs, 'table', $team  ) . '</td>';  
					break;
					
				case 'order_position':
					if ( $attribs['show_position'] ) {
						$row_string .= $row_td . get_post_meta( $post->ID, 'player_position', true ) . '</td>';
					}
					break;
					
				case 'order_bats_throws':
					if ( $attribs['show_bats_throws'] ) {
						$row_string .= $row_td . mstw_tr_build_bats_throws( $post ) . '</td>';	
					}	
					break;
				
				//
				// if showing both HEIGHT & WEIGHT, they are combined into one column
				// all pre-defined types show both height and weight
				//	
				case 'order_height':
					if ( $attribs['show_height'] && $attribs['show_weight'] ) {
						$row_string .=  $row_td . get_post_meta( $post->ID, 'player_height', true ) . "/" . get_post_meta( $post->ID, 'player_weight', true ) . '</td>';

					} else {
						// HEIGHT column
						if ( 'custom' == $roster_type && $attribs['show_height'] ) {
							$row_string .= $row_td . get_post_meta( $post->ID, 'player_height', true ) . '</td>';
						}
						
					}
					break;
				
				case 'order_weight':
					if ( 'custom' == $roster_type && $attribs['show_weight'] && !$attribs['show_height'] ) {
						$row_string .=  $row_td . get_post_meta( $post->ID, 'player_weight', true ) . '</td>';
					}
					break;
					
				case 'order_year':
					if ( $attribs['show_year'] ) {
						$row_string =  $row_string . $row_td . get_post_meta( $post->ID, 'player_year', true ) . '</td>';
					}
					break;
					
				case 'order_age':
					if ( $attribs['show_age'] ) {
						$row_string .=  $row_td . get_post_meta( $post->ID, 'player_age', true ) . '</td>';
					}
					break;
	
				case 'order_experience':
					if ( $attribs['show_experience'] ) {
						$row_string =  $row_string . $row_td . get_post_meta( $post->ID, 'player_experience', true ) . '</td>';
					}
					break;
				
				case 'order_home_town':
					if ( $attribs['show_home_town'] ) {
						if( false !== strpos( $roster_type, 'college' ) ) {
							$row_string .=  $row_td . get_post_meta( $post->ID, 'player_home_town', true ) . ' (' . get_post_meta( $post->ID, 'player_last_school', true ) . ') </td>';
						}
						else if ( 'custom' == $roster_type ) {
							$row_string .= $row_td . get_post_meta( $post->ID, 'player_home_town', true )  . '</td>';
						}
					}
					break;
					
				case 'order_last_school':
					if ( $attribs['show_last_school'] ) {
						if( false !== strpos( $roster_type, 'pro' ) ) {
							$row_string .= $row_td . get_post_meta( $post->ID, 'player_last_school', true ) . 
				' (' . get_post_meta( $post->ID, 'player_country', true ) . ') </td>';
						}
						else if ( 'custom' == $roster_type ) {
							$row_string .= $row_td . get_post_meta( $post->ID, 'player_last_school', true )  . '</td>';
						}
					}
					break;
					
				case 'order_country':
					if ( $attribs['show_country'] and 'custom' == $roster_type ) {
						$row_string .= $row_td . get_post_meta( $post->ID, 'player_country', true )  . '</td>';
					}
					break;
					
				case 'order_other_info':
					if ( $attribs['show_other_info'] and 'custom' == $roster_type ) {
						$row_string .= $row_td . get_post_meta( $post->ID, 'player_other', true ) .'</td>';
					}
					break;

				default:
					mstw_log_msg( "mstw_tr_build_roster_table_row: bad field= $field" );
					break;
					
			} //End: switch( $field)
		} //End: foreach( $fieldOrder as $field => $order ) {
		
		$row_string .= '</tr>';
		
		return $row_string;
		
	} //End: mstw_tr_build_roster_table_row( )
 
 
	//-----------------------------------------------------------
	// mstw_tr_build_roster_table_header - returns a sorted array order keys & values
	//	ARGUMENTS:
	//		$roster_type: the roster type (slug)
	//		$attribs: array of options or settings for the shortcode 
	//								the order keys start with 'order', e.g. order_name
	//		$fieldOrder: array of fields in the order displayed (if $attribs[show_field]==1)
	//	RETURNS:
	//		$ret_html: HTML for header row starting with '<thead></tr>'
	//	
	function mstw_tr_build_roster_table_header( $roster_type, $args, $fieldOrder ) {
		//mstw_log_msg( "mstw_tr_build_roster_table_header:" );
		//leave this open and check on styles from the admin settings
		
		$ret_html = '<thead><tr>';
			
		foreach( $fieldOrder as $field => $order ) {
			switch( $field ) {
				case 'order_photo':
					if ( $args['show_photos'] ) {
						$ret_html .= '<th id="photo">' . $args['photo_label'] . '</th>';
					}
					break;
					
				case 'order_number':
					if ( $args['show_number'] ) {	
						// all this fuss is so the sorted triangle is set via javascript
						$sorted = ( 'numeric' == $args['sort_order'] ) ? 'sorted' : 'sortable';
						if ( 'sortable' == $sorted ) {
							$sort_order = 'desc';	
						} else {
							$sort_order = ( 'desc' == $args['sort_asc_desc'] ) ? 'desc' : 'asc';	
						}
						
						$th_class = "class='$sorted $sort_order'";
						
						$ret_html .= "<th id='nbr' $th_class>" . $args['number_label'] . " <span class='sorting-indicator'></span></th>";
						
					} //End: if (show_number)
					break;
					
				case 'order_name':
					//
					// Always show the NAME column
					//
					$sorted = ( 'numeric' == $args['sort_order'] ) ? 'sortable' : 'sorted';
					if ( 'sortable' == $sorted ) {
						$sort_order = 'desc';
							
					} else {
						$sort_order = ( 'desc' == $args['sort_asc_desc'] ) ? 'desc' : 'asc';
							
					}
						
					$th_class = "class='$sorted $sort_order'";
							
					$ret_html .= "<th id='name' $th_class>" . $args['name_label'] . " <span class='sorting-indicator'></span></th>";
					break;
					
				case 'order_position':
					if ( $args['show_position'] ) {
						$ret_html .= '<th id="position">' .$args['position_label'] . '</th>';
					}
					break;
					
				case 'order_bats_throws':		
					if ( $args['show_bats_throws'] ) {
						$ret_html .= '<th id="bats-throws">' . $args['bats_throws_label'] . '</th>';
					}
					break;
				
				//
				// if showing both HEIGHT & WEIGHT, they are combined into one column
				// all pre-defined types show both height and weight
				//	
				case 'order_height':
					if ( $args['show_height'] && $args['show_weight'] ) {
						$ret_html .= '<th id="height">' . $args['height_label'] . '/' . $args['weight_label'] . '</th>';

					} else {
						// HEIGHT column
						if ( 'custom' == $roster_type && $args['show_height'] ) {
							$ret_html .= '<th id="height">' . $args['height_label'] . '</th>';
						}
					}
					break;
				
				case 'order_weight':
					// WEIGHT column
					if ( 'custom' == $roster_type && $args['show_weight'] && !$args['show_height'] ) {
						$ret_html .= '<th id="weight">' . $args['weight_label'] . '</th>';
					}
					break;
					
				case 'order_year':
					if ( $args['show_year'] ) {
						$ret_html .= '<th id="year">' . $args['year_label'] . '</th>';
					}
					break;
					
				case 'order_age':
					if ( $args['show_age'] ) {
						$ret_html .= '<th id="age">' . $args['age_label'] . '</th>';
					}
					break;
	
				case 'order_experience':
					if ( $args['show_experience'] ) {
						$ret_html .= '<th id="experience">' . $args['experience_label'] . '</th>';
					}
					break;
				
				case 'order_home_town':
					if ( $args['show_home_town'] ) {
						if( false !== strpos( $roster_type, 'college' ) ) {
							$ret_html .= '<th id="home-town">' . $args['home_town_label'] . ' ('. $args['last_school_label'] . ')' . '</th>';
						}
						else if ( 'custom' == $roster_type ) {
							$ret_html .= '<th id="home-town">' . $args['home_town_label'] . '</th>';
						}
					}
					break;
					
				case 'order_last_school':
					if ( $args['show_last_school'] ) {
						if( false !== strpos( $roster_type, 'pro' ) ) {
							$ret_html .= '<th id="last-school">' . $args['last_school_label'] 
												. ' ('. $args['country_label'] 
												. ')' . '</th>';	
						}
						else if ( 'custom' == $roster_type ) {
							$ret_html .= '<th id="last-school">' . $args['last_school_label'] . '</th>';
						}
					}
					break;
					
				case 'order_country':
					if ( $args['show_country'] and 'custom' == $roster_type ) {
						$ret_html .= '<th id="country">' . $args['country_label'] . '</th>';
					}
					break;
					
				case 'order_other_info':
					if ( $args['show_other_info'] and 'custom' == $roster_type ) {
						$ret_html .= '<th id="other-info">' . $args['other_info_label'] . '</th>';
					}
					break;

				default:
					mstw_log_msg( "mstw_tr_build_roster_table_header: bad field= $field" );
					break;
				
			} //End: switch( $field ) {
				
		} //End: foreach( $field_order as $field => $order ) {
			
		$ret_html .= '</tr></thead>';
		
		return $ret_html;
		
	} //End: mstw_tr_build_roster_table_header()
		
 
 if( !function_exists( 'mstw_tr_add_player_number' ) ) {
	 function mstw_tr_add_player_number( $post, $row_td ) {
		return "<td class='roster-table-nbr'><div class='roster-table-nbr'>" . get_post_meta( $post->ID, 'player_number', true ) . '</div></td>';
	 } //End: mstw_tr_add_player_number()
 }
 
 if( !function_exists( 'mstw_tr_add_player_name' ) ) {
	function mstw_tr_add_player_name( $post, $args ) {
		
		switch( $args['name_format'] ) {
			case 'first-last':
				$player_name = get_post_meta( $post->ID, 'player_first_name', true ) . " " . 
				get_post_meta( $post->ID, 'player_last_name', true );
				break;
			case 'first-only':
				$player_name = get_post_meta( $post->ID, 'player_first_name', true );
				break;	
			case 'last-only':
				$player_name = get_post_meta( $post->ID, 'player_last_name', true );
				break;
			default: //It's going to be last, first
				$player_name = get_post_meta( $post->ID, 'player_last_name', true ) . ', ' . 
				get_post_meta( $post->ID, 'player_first_name', true );
				break;
		}
		
		if ( $args['links_to_profiles'] ) {
			$player_html = '<a href="' .  get_permalink($post->ID) . '?roster_type=' . $args['roster_type'] . '" ';
			$player_html .= '>' . $player_name . '</a>';
		}
		else {
			$player_html = $player_name;
		}
		
		return '<td>' . $player_html . '</td>';
		
	 } //End: mstw_tr_add_player_name()
 }