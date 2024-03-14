<?php
/*----------------------------------------------------------------------------
 * mstw-tr-player-cpt-admin.php
 *	This portion of the MSTW Schedules & Scoreboards Plugin admin handles the
 *		mstw_tr_player custom post type.
 *	It is loaded conditioned on admin_init hook in mstw-tr-admin.php 
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 
 //-----------------------------------------------------------------
 // Move the meta box ahead of the content (player bio)
 //
 add_action('edit_form_after_title', 'mstw_tr_build_player_screen' );

 if( !function_exists( 'mstw_tr_build_player_screen' ) ) {
	function mstw_tr_build_player_screen( ) {
		global $post, $wp_meta_boxes;
		
		// first make sure we're on the right screen ...
		if( get_post_type( $post ) == 'mstw_tr_player' ) {
			do_meta_boxes(get_current_screen( ), 'advanced', $post);
			unset( $wp_meta_boxes[get_post_type($post)]['advanced'] );
			echo "<p class='player-bio-admin-head'>" . __( 'Player Bio:', 'team-rosters' ) . "</p>";
		}
	}  //End: mstw_tr_build_player_screen
 }
 
 add_action( 'do_meta_boxes', 'mstw_tr_change_featured_image_box');
 
 if( !function_exists( 'mstw_tr_change_featured_image_box' ) ) {
	 function mstw_tr_change_featured_image_box( ) {
		$screen = get_current_screen( );
		//mstw_tr_log_msg( 'mstw_tr_change_featured_image_box with screen' );
		//mstw_tr_log_msg( $screen );
		if ( $screen and 'mstw_tr_player' == $screen->post_type ) {
			remove_meta_box( 'postimagediv', 'mstw_tr_player', 'side' );
			add_meta_box( 'postimagediv', __( 'Player Photo', 'team-rosters' ), 'post_thumbnail_meta_box', 'mstw_tr_player', 'side', 'default' );
		}
	 } //End: mstw_tr_change_featured_image_box( )
 }
 
 add_action( 'admin_head-post-new.php', 'mstw_tr_set_featured_image_text_filter' );
 add_action( 'admin_head-post.php', 'mstw_tr_set_featured_image_text_filter' );
 
 if( !function_exists( 'mstw_tr_set_featured_image_text_filter' ) ) {
	function mstw_tr_set_featured_image_text_filter( ) {
		$screen = get_current_screen( );
		
		//if( isset( $GLOBALS['post_type'] ) && $GLOBALS['post_type'] == 'mstw_tr_player' ) {
			add_filter( 'admin_post_thumbnail_html', 'mstw_tr_change_featured_image_link' );
		//}
		 
	} //End: mstw_tr_set_featured_image_text_filter( )
 }
 
 //add_filter( 'admin_post_thumbnail_html', 'mstw_tr_change_featured_image_link' );
 
  if( !function_exists( 'mstw_tr_change_featured_image_link' ) ) {
	 function mstw_tr_change_featured_image_link( $content ) {
		if ( get_post_type( ) == 'mstw_tr_player' ) {
			$content = str_replace( __( 'Set featured image' ), __( 'Set Player Photo', 'team-rosters' ), $content );
			
			$content = str_replace( __( 'Remove featured image' ), __( 'Remove Player Photo', 'team-rosters' ), $content );
		}
		return $content;
		
	 } //End: mstw_tr_change_featured_image_link( ) 
 }
 
 //-----------------------------------------------------------------
 // Add the meta box for the mstw_tr_player custom post type
 //
 add_action( 'add_meta_boxes_mstw_tr_player', 'mstw_tr_player_metaboxes' );
 if( !function_exists( 'mstw_tr_player_metaboxes' ) ) {
	function mstw_tr_player_metaboxes( ) {
			
		add_meta_box(	'mstw-tr-player-meta', 
						__('Player Data', 'team-rosters'), 
						'mstw_tr_create_player_screen', 
						'mstw_tr_player', 
						'advanced', 
						'high' );	
						
	} //End: mstw_tr_player_metaboxes( )
 }
 
 //-----------------------------------------------------------------
 // Build the meta box (controls) for the MSTW_TR_PLAYER CPT
 //
 if( !function_exists( 'mstw_tr_create_player_screen' ) ) {
	function mstw_tr_create_player_screen( $post ) {
		//mstw_tr_log_msg( 'mstw_tr_create_player_screen:' );
		
		$std_length = 128; //max length of text fields
		$std_size = 32;    //size of text box on edit screen
		
		// Want the settings for the field labels, which may be changed
		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		//mstw_tr_log_msg( 'options' );
		//mstw_tr_log_msg( $options );
	
		wp_nonce_field( plugins_url(__FILE__), 'mstw_tr_player_nonce' );
		
		$bats_list = array( 	__( '----', 'team-rosters' ) => 0, 
								__( 'R', 'team-rosters' ) 	=> 1,
								__( 'L', 'team-rosters' ) 	=> 2,
								__( 'B', 'team-rosters' ) 	=> 3, 
							);
							
		$throws_list = array( 	__( '----', 'team-rosters' ) => 0, 
								__( 'R', 'team-rosters' ) 	=> 1,
								__( 'L', 'team-rosters' ) 	=> 2, 
							);
		
		// Retrieve the metadata values if they exist
		// The first set are used in all formats
		//$first_name = get_post_meta( $post->ID, 'player_first_name', true );
		//$last_name  = get_post_meta( $post->ID, 'player_last_name', true );
		$number = get_post_meta( $post->ID, 'player_number', true );
		$height = get_post_meta( $post->ID, 'player_height', true );
		$weight = get_post_meta( $post->ID, 'player_weight', true );
		$position = get_post_meta( $post->ID, 'player_position', true );
		$positionLong = get_post_meta( $post->ID, 'player_position_long', true );
		
		// year is used in the high-school and college formats
		$year = get_post_meta( $post->ID, 'player_year', true );
		$yearLong = get_post_meta( $post->ID, 'player_year_long', true );
		
		// experience is used in the college and pro formats
		$experience = get_post_meta( $post->ID, 'player_experience', true );
		
		// age is used in the pro format only
		$age = get_post_meta( $post->ID, 'player_age', true );
		
		// home_town is used in the college format only
		$home_town = get_post_meta( $post->ID, 'player_home_town', true );
		
		// last_school is used in the college and pro formats
		$last_school = get_post_meta( $post->ID, 'player_last_school', true );
		
		// country is used in the pro format only
		$country = get_post_meta( $post->ID, 'player_country', true );
		
		// used in the baseball formats only
		$bats = get_post_meta( $post->ID, 'player_bats', true );
		$throws = get_post_meta( $post->ID, 'player_throws', true );
		
		// other info - this is a free-for-all spare
		$other = get_post_meta( $post->ID, 'player_other', true );
		
		// player photo - can upload from media library
		$player_photo = get_post_meta( $post->ID, 'player_photo', true );
		?>
		
		<table class="form-table">
		
		<?php
		$admin_fields = array ( 
						'player_first_name' => array (
							'type' 			=> 'text',
							'curr_value' 	=> get_post_meta( $post->ID, 'player_first_name', true ), //$first_name,
							'label' 		=> __( 'First Name:', 'team-rosters' ),
							'maxlength' 	=> $std_length,
							'size' 			=> $std_size,
							'desc' 			=> '',
							),
						'player_last_name' => array (
							'type' => 'text',
							'curr_value' => get_post_meta( $post->ID, 'player_last_name', true ),
							'label' =>  __( 'Last Name:', 'team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => '',
							),
						'player_number' => array (
							'type' => 'text',
							'curr_value' => $number,
							'label' => 'Jersey Number:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => '',
							),	
						'player_position' => array (
							'type' => 'text',
							'curr_value' => $position,
							'label' => 'Position (abbrev):',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => 'Used in all displays except the mstw_tr_roster_2 shortcode.',
							),
						'player_position_long' => array (
							'type' => 'text',
							'curr_value' => $positionLong,
							'label' => 'Position (Long format):', //$options['position_label'] . ':',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => 'Supports the mstw_tr_roster_2 shortcode roster display & only appears in that shortcode. Defaults to the position value above.',
							),
						'player_height' => array (
							'type' => 'text',
							'curr_value' => $height,
							'label' => 'Height:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => 'Suggest using 6-2 for 6 feet 2 inches.',
							),
						'player_weight' => array (
							'type' => 'text',
							'curr_value' => $weight,
							'label' => 'Weight:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => '',
							),
						'player_year' => array (
							'type' => 'text',
							'curr_value' => $year,
							'label' => 'Class/Year:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is intended for the player\'s year in school, for example, "So", (sophomore) "10" (10th grade)". It can be changed by changing the Year Label in the settings screen.', 'team-rosters' ),
							),
						'player_year_long' => array (
							'type' => 'text',
							'curr_value' => $yearLong,
							'label' => 'Class/Year (long format):',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is intended for the player\'s year or class in school, for example, "Redshirt Freshman or "Sophomore". It can be changed by changing the Long Year Label in the settings screen.', 'team-rosters' ),
							),
						'player_experience' => array (
							'type' => 'text',
							'curr_value' => $experience,
							'label' => 'Experience:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is intended to be the player\'s experience, for example, "3 years" or "2V" (2 varsity seasons). It can be changed by changing the Experience Label in the settings screen.', 'team-rosters' ),
							),
						'player_age' => array (
							'type' => 'text',
							'curr_value' => $age,
							'label' => 'Age:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => '',
							),
						'player_home_town' => array (
							'type' => 'text',
							'curr_value' => $home_town,
							'label' => $options['home_town_label'] . ':',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is typically combined with the last school in US college and high school rosters.', 'team-rosters' ),
							),
						'player_last_school' => array (
							'type' => 'text',
							'curr_value' => $last_school,
							'label' => 'Last School:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is typically combined with the home town in US college and high school rosters. It could be changed to "last team" in international/pro rosters.', 'team-rosters' ),
							),
						'player_country' => array (
							'type' => 'text',
							'curr_value' => $country,
							'label' => 'Country:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is intended for international teams, but it could changed to "State" for US college teams.', 'team-rosters' ),
							),
						'player_bats' => array (
							'type' => 'select-option',
							'options' => $bats_list,
							'curr_value' => $bats,
							'label' =>  __( 'Bats:', 'team-rosters' ),
							'desc' => __( 'This is a baseball specific field, but it could be used for cricket, say. It is combined with the "Throws" field in baseball specific formats.', 'team-rosters' ),
							),
						'player_throws' => array (
							'type' => 'select-option',
							'options' => $throws_list,
							'curr_value' => $throws,
							'label' =>  __( 'Throws:', 'team-rosters' ),
							'desc' => __( 'This is a baseball specific field. It is combined with the "Bats" field in baseball specific formats.', 'team-rosters' ),
							),
						'player_other' => array (
							'type' => 'text',
							'curr_value' => $other,
							'label' => 'Other Info:',
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is a spare. It is intended to be re-purposed by site admins.', 'team-rosters' ),
							),
						);
			mstw_tr_build_admin_edit_screen( $admin_fields );
		?>
		</table>
		
		<?php
	} //End: mstw_tr_create_player_screen()
 }

 //-----------------------------------------------------------------
 // SAVE THE MSTW_TR_PLAYER CPT META DATA
 //
 add_action( 'save_post_mstw_tr_player', 'mstw_tr_save_player_meta', 20, 2 );
 
 if( !function_exists( 'mstw_tr_save_player_meta' ) ) {
	function mstw_tr_save_player_meta( $post_id ) {
		
		//mstw_tr_log_msg( 'in mstw_tr_save_player_meta ...' );
		
		//
		//Just return on an autosave
		//
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
			
		//	
		// check that the post type is 'mstw_tr_player', if so, process the data
		//
		if( isset( $_POST['post_type'] ) ) {		
			if ( $_POST['post_type'] == 'mstw_tr_player' ) {
				update_post_meta( $post_id, 'player_first_name', 
						strip_tags( $_POST['player_first_name'] ) );
						
				update_post_meta( $post_id, 'player_last_name', 
						strip_tags( $_POST['player_last_name'] ) );
						
				update_post_meta( $post_id, 'player_number', 
						strip_tags( $_POST['player_number'] ) );
						
				update_post_meta( $post_id, 'player_position', 
						strip_tags( $_POST['player_position'] ) );
						
				update_post_meta( $post_id, 'player_position_long', 
						strip_tags( $_POST['player_position_long'] ) );	
						
				update_post_meta( $post_id, 'player_height', 
						//$_POST['player_height'] );
						strip_tags( $_POST['player_height'] ) );
						
				update_post_meta( $post_id, 'player_weight',  
						strip_tags( $_POST['player_weight'] ) );
						
				update_post_meta( $post_id, 'player_year',  
						strip_tags( $_POST['player_year'] ) );
						
				update_post_meta( $post_id, 'player_year_long',  
						strip_tags( $_POST['player_year_long'] ) );
						
				update_post_meta( $post_id, 'player_experience',
						strip_tags( $_POST['player_experience'] ) );
				
				update_post_meta( $post_id, 'player_age', 
						strip_tags( $_POST['player_age'] ) );
						
				update_post_meta( $post_id, 'player_home_town',
						strip_tags( $_POST['player_home_town'] ) );
						
				update_post_meta( $post_id, 'player_last_school',
						strip_tags( $_POST['player_last_school'] ) );
						
				update_post_meta( $post_id, 'player_country',
						strip_tags( $_POST['player_country'] ) );
						
				update_post_meta( $post_id, 'player_bats',
						strip_tags( $_POST['player_bats'] ) );
						
				update_post_meta( $post_id, 'player_throws',
						strip_tags( $_POST['player_throws'] ) );
						
				update_post_meta( $post_id, 'player_other',
						strip_tags( $_POST['player_other'] ) );
						
			} //End: if ( $_POST['post_type'] == 'mstw_tr_player' )
		} //End: if( isset( $_POST['post_type'] ) )
	} //End: function mstw_tr_save_player_meta
 }
 
 // ----------------------------------------------------------------
 // Set up the Team Roster 'view all' columns
 //
 add_filter( 'manage_edit-mstw_tr_player_columns',
			 'mstw_tr_edit_player_columns' ) ;
 
 if( !function_exists( 'mstw_tr_edit_player_columns' ) ) {
	function mstw_tr_edit_player_columns( $columns ) {

		$options = wp_parse_args( (array)get_option( 'mstw_tr_options' ), mstw_tr_get_data_fields_columns_defaults( ) );

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => $options['name_label'], //__( 'Player', 'team-rosters' ),
			
			'first-name' => __( 'First Name', 'team-rosters' ),
			'last-name' => __( 'Last Name', 'team-rosters' ),
			'number' => $options['number_label'], //__( 'Number', 'team-rosters' ),
			'position' => $options['position_label'], //__( 'Position', 'team-rosters' ),
			'height' => $options['height_label'], //__( 'Height', 'team-rosters' ),
			'weight' => $options['weight_label'], //__( 'Weight', 'team-rosters' ),
			'year' => $options['year_label'], //__( 'Year', 'team-rosters' ),
			'experience' => $options['experience_label'], //__( 'Experience', 'team-rosters' )
			'team' => __( 'Team(s)', 'team-rosters' ),
		);

		return $columns;
	} //End: mstw_tr_edit_player_columns( )
 }

 // ----------------------------------------------------------------
 // Display the View All Players table columns
 //
 add_action( 'manage_mstw_tr_player_posts_custom_column', 
			 'mstw_tr_manage_player_columns', 10, 2 );
 
 if( !function_exists( 'mstw_tr_manage_player_columns' ) ) {
	function mstw_tr_manage_player_columns( $column, $post_id ) {
		global $post;
		
		//mstw_tr_log_msg( 'column: ' . $column . " Post ID: " . $post_id );

		switch( $column ) {
			case 'team' :
				$taxonomy = 'mstw_tr_team';
				
				$edit_link = site_url( '/wp-admin/', null ) . 'edit-tags.php?taxonomy=mstw_tr_team&post_type=mstw_tr_player';
				
				$teams = get_the_terms( $post_id, $taxonomy );
				if ( is_array( $teams) ) {
					foreach( $teams as $key => $team ) {
						$teams[$key] =  '<a href="' . $edit_link . '">' . $team->name . '</a>';
					}
						echo implode( ' | ', $teams );
				}
				break;
				
			case 'first-name' :
				//printf( '%s', get_post_meta( $post_id, 'player_first_name', true ) );
				echo( get_post_meta( $post_id, 'player_first_name', true ) );
				break;
				
			case 'last-name' :
				printf( '%s', get_post_meta( $post_id, 'player_last_name', true ) );
				break;
			
			case 'number' :
				printf( '%s', get_post_meta( $post_id, 'player_number', true ) );
				break;
					
			case 'position' :
				printf( '%s', get_post_meta( $post_id, 'player_position', true ) );
				break;
				
			case 'position_long' :
				printf( '%s', get_post_meta( $post_id, 'player_position_long', true ) );
				break;
				
			case 'height' :
				printf( '%s', get_post_meta( $post_id, 'player_height', true ) );
				break;
				
			case 'weight' :
				printf( '%s', get_post_meta( $post_id, 'player_weight', true ) );
				break;

			case 'year' :
				printf( '%s', get_post_meta( $post_id, 'player_year', true ) );
				break;
				
			case 'experience' :
				printf( '%s', get_post_meta( $post_id, 'player_experience', true ) );
				break;
				
			/* Just break out of the switch statement for everything else. */
			default :
				break;
				
		} 
	} //End: mstw_tr_manage_player_columns( )
 }
	
 // ----------------------------------------------------------------
 // Sort the all players table on first name, last name, number, team(s)
 //
 add_filter( 'manage_edit-mstw_tr_player_sortable_columns', 
			 'mstw_tr_players_columns_sort');

 if( !function_exists( 'mstw_tr_players_columns_sort' ) ) {
	function mstw_tr_players_columns_sort( $columns ) {
		
		$custom = array(
			'first-name' => 'player_first_name',
			'last-name' 	=> 'player_last_name',
			'number' 	=> 'player_number',
		);
		
		return wp_parse_args( $custom, $columns );
		
	} //End: mstw_tr_players_columns_sort( )
 } 
 
 // ----------------------------------------------------------------
 // Filter the All Players screen based on Team (mstw_tr_team) taxonomy
 //
 add_action( 'restrict_manage_posts','mstw_tr_restrict_players_by_team' );

 if( !function_exists( 'mstw_tr_restrict_players_by_team' ) ) {
	function mstw_tr_restrict_players_by_team( ) {
		global $typenow;
		//global $wp_query;
		
		if( $typenow == 'mstw_tr_player' ) {
			
			$taxonomy_slugs = array( 'mstw_tr_team' );
			
			foreach ( $taxonomy_slugs as $tax_slug ) {
				//retrieve the taxonomy object for the tax_slug
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				
				$terms = get_terms( $tax_slug );
					
				//output the html for the drop down menu
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>" . __( 'Show All Teams', 'team-rosters') . "</option>";
				
				//output each select option line
				foreach ($terms as $term) {
					//check against the last $_GET to show the current selection
					if ( array_key_exists( $tax_slug, $_GET ) ) {
						$selected = ( $_GET[$tax_slug] == $term->slug ) ? ' selected="selected"' : '';
					}
					else {
						$selected = '';
					}
					echo '<option value=' . $term->slug . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
				}
				echo '</select>';
			}	
		}
	} //End: mstw_tr_restrict_players_by_team( )
 }
 
 //-----------------------------------------------------------------
 // Sort show all players by columns. See:
 // http://scribu.net/wordpress/custom-sortable-columns.html#comment-4732
 //
 add_filter( 'request', 'mstw_ss_players_column_order' );

 if( !function_exists( 'mstw_ss_players_column_order' ) ) {
	function mstw_ss_players_column_order( $vars ) {
		if ( isset( $vars['orderby'] ) ) {
			//mstw_tr_log_msg( 'in ... mstw_ss_players_column_order' . $vars['orderby'] );
			$custom = array();
			switch( $vars['orderby'] ) {
				case'player_number':
					$custom = array( 'meta_key' => 'player_number',
									 'orderby' => 'meta_value_num',
									 );
					//$vars = array_merge( $vars, $custom );
					break;
				case 'player_first_name':
					$custom = array( 'meta_key' => 'player_first_name',
										 //'orderby' => 'meta_value_num', // does not work
										 'orderby' => 'meta_value'
										 //'order' => 'asc' // don't use this; blocks toggle UI
										);
					//$vars = array_merge( $vars, $custom );
					break;
				case 'player_last_name':
					$custom = array( 'meta_key' => 'player_last_name',
										 //'orderby' => 'meta_value_num', // does not work
										 'orderby' => 'meta_value'
										 //'order' => 'asc' // don't use this; blocks toggle UI
										);
					//$vars = array_merge( $vars, $custom );
					break;
			}
			if( $custom ) 
				$vars = array_merge( $vars, $custom );
		}
		
		return $vars;
		
	} //End mstw_ss_players_column_order( )
 }
 
	//-------------------------------------------------------------
	// mstw_tr_player_add_help - outputs HTML for the contextual help area of the screen
	//		
	// ARGUMENTS:
	//	 None 
	//   
	// RETURNS:
	//	 Outputs HTML to the contextual help area of the screen
	//-------------------------------------------------------------
	
	function mstw_tr_player_add_help( ) {
		//mstw_tr_log_msg( "mstw_tr_player_add_help:" );
		
		$screen = get_current_screen( );
		
		//mstw_tr_log_msg( "screen ID: " . $screen -> base );
		// We are on the correct screen because we take advantage of the
		// load-* action ( in mstw-lm-admin.php, mstw_lm_admin_menu()
		
		mstw_tr_help_sidebar( $screen );
		
		if ( 'post' == $screen -> base ) {
			
		} else if ( 'edit' == $screen -> base ) {
			
		}
		
		$tabs = array( array(
						'title'    => __( 'Manage Players', 'team-rosters' ),
						'id'       => 'players-screen-help',
						'callback' => 'mstw_tr_players_screen_help',
						),
						array(
						'title'    => __( 'Add/Edit Player', 'team-rosters' ),
						'id'       => 'edit-player-help',
						'callback' => 'mstw_tr_players_screen_help',
						),
					 );
		
					 
		foreach( $tabs as $tab ) {
			$screen->add_help_tab( $tab );
		}
		
	} //End: mstw_tr_player_add_help( )
	
	function mstw_tr_players_screen_help( $screen, $tab ) {
		//mstw_tr_log_msg( "mstw_tr_all_players_content:" );
		
		if( !array_key_exists( 'id', $tab ) ) {
			return;
		}
			
		switch ( $tab['id'] ) {
			case 'players-screen-help':
				?>
				<p><?php _e( 'This screen provides a list of selected data fields for all players. The list may be filtered to show only one team using the Teams filter.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'To add a player, click the "Add New Player" button at the top of the screen. Players may also be added using the "Add Players to Roster" screen or the "CSV Import" screen.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'To edit a player, roll over the "Name" field and selecte "Edit".', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'To delete a player, roll over the "Name" field and selecte "Trash". Note that the player is moved to the trash BUT NOT REMOVED FROM THE DB. To delete the player from the DB, or to restore the player, click on the "Trash" link and delete selected players permanently or empty the trash.', 'team-rosters' ) ?></p>
				
				<p><a href="http://shoalsummitsolutions.com/tr-data-entry-players/" target="_blank"><?php _e( 'See the Data Entry - Players man page for more details.', 'team-rosters' ) ?></a></p>
				<?php				
				break;
				
			case 'edit-player-help':
				?>
				<p><?php _e( 'Title. The player title should always be entered. However, it does not appear anywhere on the front end.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'First Name and Last Name. At least one of these fields should be entered; otherwise, no name will appear on the front end.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'Use the Manage Teams metabox to add a player to one or more teams; otherwise, the player will not appear on the front end.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'Use the Manage Teams metabox to add a player to one or more teams; otherwise, the player will not appear on the front end.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'Use the Player Photo metabox to add a player photo from the Media Gallery.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'Use the Player Bio metabox to add a player profile/bio. Note that you can add HTML to this field to add photos, tables, links, etc.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'Delete a player by clicking the "Move to Trash" link in the Publish metabox. See the instructions on deleting players above.', 'team-rosters' ) ?></p>

				<p><a href="http://shoalsummitsolutions.com/tr-data-entry-players/" target="_blank"><?php _e( 'See the Data Entry - Players man page for more details.', 'team-rosters' ) ?></a></p>
				<?php				
				break;
				
			default:
				break;
		} //End: switch ( $tab['id'] )

	} //End: mstw_tr_all_players_content( )
	
	function mstw_tr_edit_player_help_content( $screen, $tab ) {
		//mstw_tr_log_msg( "mstw_tr_edit_player_help_content:" );
		
		if( !array_key_exists( 'id', $tab ) ) {
			return;
		}
			
		switch ( $tab['id'] ) {
			case 'update-games-overview':
				?>
				<p><?php _e( 'This screen allows updating the status of all games in a league and season.', 'team-rosters' ) ?></p>
				<p><?php _e( 'Select a LEAGUE and SEASON then press the Update Games Table button.', 'team-rosters' ) ?></p>
				<p><?php _e( 'Enter the status in information for each game.', 'team-rosters' ) ?></p>
				<p><a href="http://shoalsummitsolutions.com/lm-update-games/" target="_blank"><?php _e( 'See the Update Games man page for more details.', 'team-rosters' ) ?></a></p>
				<?php				
				break;
			
			default:
				break;
		} //End: switch ( $tab['id'] )

	} //End: mstw_tr_all_players_content( )