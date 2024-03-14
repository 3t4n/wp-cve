<?php
/* ------------------------------------------------------------------------
 * 	MSTW Team Rosters Admin Class
 *	UI for the MSTW Team Rosters Plugin Manage Rosters screen
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2017-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *--------------------------------------------------------------------------*/
 
class MSTW_TR_TEAM_ROSTERS_ADMIN {
	// $data_fields array elements
	const DF_TITLE      = 0;
	const DF_SIZE       = 1;
	const DF_MAXLENGTH  = 2;
	const DF_FIELD_TYPE = 3;
	
	// DF_FIELD_TYPES TYPE
	const DF_TYPE_TXT   = 1;
	const DF_TYPE_BAT   = 2;
	const DF_TYPE_THR   = 3;
	
	const NEW_PLAYER_ROWS  = 20;
	const EDIT_ROSTER_ROWS = 20;  //20;
	
	private static $data_fields; //= array ( );
		
	function __construct( ) {
		//mstw_log_msg( " MSTW_TR_TEAM_ROSTERS_ADMIN.__construct" );
		
		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		
		self::$data_fields = array( 		
					'player_first_name' => array( __( 'First Name', 'mstw-bracket-builder' ), 16, 32, 1 ), 
					'player_last_name' => array( __( 'Last Name', 'mstw-bracket-builder' ), 16, 32, 1 ), 
					'player_number' => array( $options['number_label'], 3, 32, 1 ),
					'player_position' => array( $options['position_label'], 4, 32, 1 ),
					'player_height' => array( $options['height_label'], 3, 32, 1 ),
					'player_weight' => array( $options['weight_label'], 3, 32, 1 ),
					'player_year' => array( $options['year_label'], 4, 32, 1 ),
					'player_experience' => array( $options['experience_label'], 3, 32, 1 ),
					'player_age' => array( $options['age_label'], 3, 32, 1 ),
					'player_last_school' => array( $options['last_school_label'], 16, 32, 1 ),
					'player_home_town' => array( $options['home_town_label'], 16, 32, 1 ),
					'player_country' => array( $options['country_label'], 16, 32, 1 ),
					'player_bats' => array( __( 'Bats', 'mstw-bracket-builder' ), 3, 32, 2 ),
					'player_throws' => array( __( 'Throws', 'mstw-bracket-builder' ), 3, 32, 3 ),
					'player_other' => array( $options['other_info_label'], 16, 32, 1 ),
					);
						
	} //End __constuct( )
	
	//
	// The first to functions are here only because I can't pass arguments
	// to the callbacks in add_submenu_page
	//
	
	//-------------------------------------------------------------
	//	add_players_screen - callback for add_submenu_page() in mstw-tr-admin.php
	//	  ARGUMENTS: 
	//		None.
	//	  RETURNS: 
	//		None. Passes control to screen_builder to do the work
	//
	function add_players_screen( ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.add_players_screen:" );
		$this -> screen_builder( 0 );
		
	} //End: add_players_screen( )
	
	//-------------------------------------------------------------
	//	edit_roster_screen - callback for add_submenu_page() in mstw-tr-admin.php
	//	  ARGUMENTS: 
	//		None.
	//	  RETURNS: 
	//		None. Passes control to screen_builder to do the work
	//
	function edit_roster_screen(  ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.edit_roster_screen:" );
		$this -> screen_builder( 1 );
		
	} //End: edit_roster_screen( )
	
	//-------------------------------------------------------------
	//	screen_builder - callback for add_submenu_page() in mstw-tr-admin.php
	//		builds the UI for the Manage Roster & Add Players admin screens
	//	  ARGUMENTS: 
	//		$edit: 0(add) or 1(edit)
	//	  RETURNS: 
	//		None. outputs the HTML to the display
	//
	function screen_builder( $edit = 1 ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.screen_builder:" );
		
		static $screen_titles = array( "Add Players", "Edit Roster" ); 
	
		//
		// We do the heavy lifting in the post( ) method
		//
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			
			$submit_value = array_key_exists( 'submit', $_POST ) ? stripslashes( $_POST[ 'submit' ] ) : null;
			
			$this->post( compact( 'submit_value' ) );
			
		}
		?>
		
		<!-- begin main wrapper for page content -->
		<div class="wrap">
		
		<h1><?php _e( $screen_titles[$edit], 'team-rosters' )?></h1>
		
		<p class='mstw-lm-admin-instructions'>
		 <?php _e( 'Read the contextual help tab on the top right of this screen.', 'team-rosters' ) ?> 
		</p>
		
		<?php
		mstw_tr_admin_notice( );
		
		//
		// Build the HTML UI/form
		//
		$current_team = mstw_tr_get_current_team( );
		//mstw_log_msg( 'Current Team: ' . $current_team );
		
		// DEBUG
		//$team_obj = get_term_by( 'slug', $current_team, 'mstw_tr_team', OBJECT );
		//$team_name = ( $team_obj ) ? $team_obj->name : $team;
		//mstw_log_msg( "Team: $team_name" );
		
		
		$paged = ( array_key_exists( 'paged', $_GET ) ) ? absint( $_GET['paged'] ) : 1;
		
		//mstw_log_msg( "paged: " . $paged ) ;
		//mstw_log_msg( "page: " . get_query_var( 'page' ) );
		
		if ( $edit ) {
			$args = array ( 'posts_per_page' => self::EDIT_ROSTER_ROWS,
							'paged'          => $paged,
							'post_type'      => 'mstw_tr_player',
							'post_status'    => 'publish',
							'mstw_tr_team'   => $current_team,
							'meta_key'		 => 'player_last_name',
							'orderby'        => 'meta_value',
							'order'          => 'ASC',
							'fields'         => 'ids',
						  );

			$players_list = new WP_Query( $args );
			
		}
		?>
		
		<form id="manage-roster" class="add:the-list: validate" method="post" enctype="multipart/form-data" action="">
		
			<div class="alignleft actions mstw-tr-controls">
				<?php
				$teams = $this -> build_team_select( $current_team, 'current-team', $edit );
				
				// No teams found
				// This should not happen if $edit = 0. Can Add Players to the DB but
				// Not assign a team
				if ( -1 == $teams ) { 
				?>
					<h1 class='mstw-lm-admin-instructions'>
					  <?php _e( 'Create a team before editting it\'s roster.', 'mstw-bracket-builder' );
					  ?>
					</h1>
					</div> <!-- .alignleft actions mstw-lm-controls -->
					</form> <!-- #bracket-builder -->
					<?php return; ?>
					
				<?php
				} else {
					//echo admin_url( 'admin.php?page=manage-team-rosters' );
					
					if ( $edit ) {
						// Don't want button on the Add Games Screen
						// Nothing to do if team is changed (ajax still fires)
					    ?>
						<a href="<?php  echo admin_url( 'admin.php?page=manage-team-rosters' )?>" class="button mstw-lm-control-button"><?php _e( 'Change Team', 'mstw-bracket-builder' ) ?></a>
						
						<?php
						// Don't need pagination on the Add Games Screen
						$this -> build_pagination_links( $paged, $players_list -> max_num_pages );
						
						// No button on Add Teams Screen, so don't need warning
						?>
			   
						<br/><p class="description">
						  <?php _e( 'Caution! This button will update the table with the selected team roster WITHOUT SAVING any changes. Use the Update Roster button at the bottom of the screen to save any changes.', 'team-rosters' ) ?>
						<br/></p>
					
					<?php
					} else {
						// Just want to get the spacing right, really
						?>
						<br/><p class="description">
						  <?php _e( 'Enter players for the selected team. No data will be processed on or after the first row with blank first and last names.', 'team-rosters' ) ?>
						<br/></p>
						<?php
						
					}//End: if ( $edit )
					
				} //End: if ( -1 == $teams )
				?>
			
			</div>
			
			<table id="edit-teams" class="wp-list-table widefat auto striped posts" >
			
			  <?php 
			  $this -> build_roster_table_header( );
			  if ( $edit ) {
				$this -> build_roster_table_body( $players_list );
				$submit_button_label = __( 'Update Roster', 'team-rosters' );
				
			  } else {
				$this -> build_add_players_table_body( );
				$submit_button_label = __( 'Add Players', 'team-rosters' );
				
			  }
			  
			  ?>
			  
			  <!-- Submit button -->
			  <tbody>
			  <tr> 
			   <td colspan="2" class="submit tr-action-button">
			   <?php submit_button( $submit_button_label, 'primary', 'submit' ) ?>
			   
			   <td colspan="9"><p class="submit">
			    <?php
				if ( $edit ) {
					$this -> build_pagination_links( $paged, $players_list -> max_num_pages );
				}
				?>
			   
			   </p></td>
			  </tr>
			  </tbody>
			  
			</table> 
			
		</form>
		
		<?php
		
		return;

	} //End screen_builder()
	
	//-------------------------------------------------------------
	//	build_roster_table_header - builds roster (players) table header row
	//  ARGS:
	//	 None
	//
	//  RETURN:
	//	 Outputs header for table, starting with <thead>
	//
	function build_roster_table_header( ) {
		//mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN.build_roster_table_header:' );
		
		$data_fields = self::$data_fields;
		
		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		
		//mstw_log_msg( 'data_fields' );
		//mstw_log_msg( $data_fields );
		?>
		
		<thead>
		  
		  <tr>
		  <?php foreach ( $data_fields as $data_field ) { ?>
		   <th><?php _e( $data_field[0], 'team-rosters' ) ?></th>
		   
		  <?php } ?>
		   
		  </tr>
		</thead>
					
		<?php
	} //End: build_roster_table_header( )
	
	//-------------------------------------------------------------
	//	build_roster_table_body - builds roster table body (the players) for 
	//		$team (CPT Object)
	// 	ARGS: 
	//		$team: team as a term Do we need this?
	//		$players - WP_Query object containing the players
	//	RETURNS:
	//		Outputs players table, starting with <tbody>
	//
	function build_roster_table_body( $players ) {
		//mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN:build_roster_table_body:' );
		
		// These are mostly for the front end displays
		//$options = wp_parse_args( mstw_tr_get_options( ), mstw_tr_get_defaults( ) );
		//mstw_log_msg( "options:" );
		//mstw_log_msg( $options );
		
		$row_nbr = 1;
		
		if ( $players -> have_posts( ) ) : 
			while ( $players -> have_posts( ) ) : 
				
				$players -> the_post( );
				
				//mstw_log_msg( "current title: " . get_the_title( ) );
				//mstw_log_msg( "current ID: " . get_the_ID( ) );
				
				$player = get_post( get_the_ID( ) );
				
				//mstw_log_msg( $player );
				
				$this -> build_table_row( $player, $row_nbr );
				
				$row_nbr++;

			endwhile;
			
		endif;
		
		wp_reset_postdata( );
		
		return;
		
	} //End: build_roster_table_body( )
	
	//-------------------------------------------------------------
	//	build_add_players_table_body - builds roster table body (the players) 
	//		for the edit roster screen
	// 	ARGS: 
	//		$team: team as a term Do we need this?
	//		$players - WP_Query object containing the players
	//	RETURNS:
	//		Outputs players table HTML to the display, starting with <tbody>
	//
	function build_add_players_table_body( ) {
		//mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN:build_add_players_table_body:' );
		
		// Add blank rows for new players
		for ( $i = 0; $i < self::NEW_PLAYER_ROWS; $i++ ) {
			
			$this -> build_table_row( null, $i + 1 );

		}
		
	} //End: build_add_players_table_body( )
	
	//-------------------------------------------------------------
	//	build_table_row - builds a roster table row for a player
	//	ARGS:
	//		$player: player as mstw_tr_player CPT object
	//	RETURNS:
	//		Outputs the table row for a player 
	//
	function build_table_row( $player =  null, $row_nbr = 0 ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN:build_table_row: " );
		
		//mstw_log_msg( $player );
		
		static $blank_player = array (
						'player_first_name' => array( '', 16, 32, 1 ), 
						'player_last_name' => array( '', 16, 32, 1 ), 
						'player_number' => array( '', 3, 32, 1 ),
						'player_position' => array( '', 4, 32, 1 ),
						'player_height' => array( '', 3, 32, 1 ),
						'player_weight' => array( '', 3, 32, 1 ),
						'player_year' => array( '', 4, 32, 1 ),
						'player_experience' => array( '', 3, 32, 1 ),
						'player_age' => array( '', 3, 32, 1 ),
						'player_last_school' => array( '', 16, 32, 1 ),
						'player_home_town' => array( '', 16, 32, 1 ),
						'player_country' => array( '', 16, 32, 1 ),
						'player_bats' => array(0, 3, 32, 2 ),
						'player_throws' => array( 0, 3, 32, 3 ),
						'player_other' => array( '', 16, 32, 1 ),
						);
						
		?>
		<tr>
		<?php
		
		if ( null === $player ) {
			//mstw_log_msg( "Building a blank row" );
			?>
			<input type="hidden" name="<?php echo $this -> make_tag( "player_slug", $row_nbr ) ?>" value="<?php echo $this -> make_tag( '-1', $row_nbr ) ?>"/>
			<?php
			foreach ( $blank_player as $slug => $value ) {
				switch ( $value[self::DF_FIELD_TYPE] ) {
					case self::DF_TYPE_BAT:
					case self::DF_TYPE_THR:
						$this -> build_select_cell( $value[0], $this -> make_tag( $slug, $row_nbr ), $value[self::DF_FIELD_TYPE] );
						break;
						
					case self::DF_TYPE_TXT:
					default:
						$this -> build_text_cell( $value[0], $this -> make_tag( $slug, $row_nbr ), $value[self::DF_SIZE], $value[self::DF_MAXLENGTH] );
						break;
						
				} //End: switch
				
			} // End: foreach( $blank_player 
			
		} else {
			//$tag = make_tag( $player -> post_name, $row_nbr );
			?>
			
			<input type="hidden" name="<?php echo $this -> make_tag( "player_slug", $row_nbr ) ?>" value="<?php echo $this -> make_tag( $player -> post_name, $row_nbr ) ?>"/>
			
			<?php
			foreach( self::$data_fields as $slug => $value ) {
				switch ( $value[self::DF_FIELD_TYPE] ) {
					case self::DF_TYPE_BAT:
					case self::DF_TYPE_THR:
						$this -> build_select_cell( get_post_meta( $player -> ID, $slug, true ),$this -> make_tag( $slug, $row_nbr ), $value[self::DF_FIELD_TYPE] );
						break;
						
					case self::DF_TYPE_TXT:
					default:
						$this -> build_text_cell( get_post_meta( $player -> ID, $slug, true ), $this -> make_tag( $slug, $row_nbr ), $value[self::DF_SIZE], $value[self::DF_MAXLENGTH] );
						break;
						
				} //End: switch
				
			} //End: foreach(
			
		}  //End: if ( null === $player )
		?>
		
		</tr>
		
	<?php	
	}
	
	//-------------------------------------------------------------
	//	build_text_cell - builds text cell in a table with a text control
	//	ARGS:
	//		$value: text to display in the input text control
	//		$tag: id and name tags for the control
	//		$size: size for the control
	//		$maxlength: maxlength of input text allowed
	//	RETURNS:
	//		Outputs the table cell starting with <td> and at input text control  
	//
	function build_text_cell( $value = '', $tag = 'tr_text', $size = 16, $maxlength = 32 ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN:build_text_cell: " );

		?>
		<td>
		  <input type='text' size='<?php echo $size ?>' maxlength = '<?php echo $maxlength ?>' id="<?php echo $tag?>" name="<?php echo $tag?>" value="<?php echo $value ?>" />
		</td>
		
		<?php
	} //End: build_text_cell
	
	//-------------------------------------------------------------
	//	build_select_cell - builds bats & throws cells in a table with a 
	//		select-option control
	//	ARGS:
	//		$curr_val: current selection in option list
	//		$tag: id and name tags for the control
	//		$type: control type DF_TYPE_BAT | DF_TYPE_THR
	//	RETURNS:
	//		Outputs the table cell starting with <td> and at input text control  
	//
	function build_select_cell( $current_value = '', $tag = 'tr_text', $type = self::DF_TYPE_THR ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN:build_text_cell: " );
		static $bats_throws_fields = array ( 
								self::DF_TYPE_BAT => array ( 0 => '---', 1 => 'R', 2 => 'L', 3 => 'B' ),
								self::DF_TYPE_THR => array ( 0 => '---', 1 => 'R', 2 => 'L' ),
								);
								
		$options = ( self::DF_TYPE_BAT == $type ) ? $bats_throws_fields[ $type ] : $bats_throws_fields[ self::DF_TYPE_THR ];
			
		?>
		
		<td>
		  <select id="<?php echo $tag?>" name="<?php echo $tag?>">
		    <?php foreach ( $options as $key => $value ) { ?>
				<option value = "<?php echo $key ?>" <?php selected( $current_value, $key, true )?> > <?php echo $value ?> </option>
			<?php } ?>
		  </select>
		</td>
		
		<?php
	} //End: build_text_cell
	
	//-------------------------------------------------------------
	//	build_pagination_links - builds the pagination links html
	//	ARGUMENTS:
	//		$paged: current page number
	//		$max_num_pages: maximum number of pages (in query result)
	//	RETURNS:
	//		Outputs pagination links HTML to display 
	//
	function build_pagination_links( $page, $max_num_pages ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN:build_pagination_links: " );
					
		$big = 999999999; 
		
		$args = array(
				'base'     => str_replace( $big, '%#%', get_pagenum_link( $big, false ) ),
				'format'   => '?paged=%#%',
				'current'  => $page, //max( 1, get_query_var('paged') ),
				'total'    => $max_num_pages,
				'mid_size' => 2,
				'end_size' => 1,
			);
		
		?>
		<span class="tr-paginate-links">
		  <?php echo paginate_links( $args ); ?>
		</span>
		
	<?php
	} //End: build_pagination_links( )

	/*-------------------------------------------------------------------------
	 *
	 *   UTILITIES
	 *
	 *-------------------------------------------------------------------------*/
	 
	//-------------------------------------------------------------------------
	// make_tag - makes a css tag (id & name) for a form element
	//	This is done in multiple places, so if we ever want to change ...
	//
	//	ARGUMENTS: 
	//	  $base: the string to which "_" . $suffix will be appended
	//	  $suffix: the suffix (string)
	//
	//	RETURNS:
	//	  The string "$base_$suffix"
	//
	function make_tag( $base, $suffix ) {
		//mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN:make_tag' );
		
		return $base . "_" . $suffix;
		
	} //End: make_tag( )
	
	function get_tag_base( $tag, $separator = "_" ) {
		//mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN:get_tag_base' );
		//mstw_log_msg( "tag: $tag" );
		//mstw_log_msg( "separator: $separator" );
		
		$us_pos = strrpos( $tag, $separator );
		//mstw_log_msg( "us_pos: $us_pos" );
		
		if ( false == $us_pos ) {
			return false;
			
		} else {
			$base = substr( $tag, 0, $us_pos );
			return $base;
		}
		
	} //End: get_tag_base( )
	
	//-------------------------------------------------------------------------
	// build_team_select - builds a select-option control for teams 
	//
	//	ARGUMENTS: 
	//	  $current_team: team that will be selected
	//	  $css_tag: name & id attribute of control
	//	  $edit: 1 (edit roster) or 0 (add players)
	//
	//	RETURNS:
	//	  Outputs the HTML control and returns the number of teams found
	//	  Otherwise, returns -1 if no teams are found
	//
	function build_team_select( $current_team, $css_tag = '', $edit = 1 ) {
		//mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN:build_team_select' );
		
		//For testing
		//return -1;
		
		$team_list = $this -> build_team_list( );
		
		if ( $team_list ) {
			?>
			<select name='<?php echo $css_tag ?>' id='<?php echo $css_tag ?>' >
			<?php
			if ( !$edit ) { 
			?>
				<option value=-1>----</option>
			<?php 
			}
			
			foreach ( $team_list as $slug => $name ) {
				$selected = selected( $slug, $current_team, false );
				?>
				<option value=<?php echo "$slug $selected" ?>><?php echo $name ?> </option>
			<?php		
			}
			?>
			</select>
			
			<?php
			return count( $team_list );
			
		}
		else {
			return -1;
		}
		
	} //End: build_team_select( )
	
	
	//-------------------------------------------------------------------------
	// build_team_list - creates list of teams 
	//
	//	ARGUMENTS: 
	//	  None
	//
	//	RETURNS:
	//	  Associative array of teams in slug => name format, 
	//	  or '' if no teams exist
	//
	function build_team_list( ) {
		//mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN:build_team_list:' );
		
		$args = array (
			'taxonomy'   => 'mstw_tr_team',
			'hide_empty'  => false,
			'orderby'         => 'title',
			);
			
		$teams = get_terms( $args );
		
		if ( $teams ) {
			$team_list = array( );
			
			foreach ( $teams as $team ) {
				$team_list[ $team -> slug ] = $team -> name;	
			}
			
			return $team_list;
			
		} else {
			//mstw_log_msg( 'build_team_list: no teams found' );
			return '';
			
		}
		
	} //End: build_team_list( )
	
	
	/*-------------------------------------------------------------------------
	 *
	 *   DATA
	 *
	 *------------------------------------------------------------------------*/
	
	//-------------------------------------------------------------
	// post - handles POST submissions - this is the heavy lifting
	//-------------------------------------------------------------------------
	// post - processes posted data from admin form(s) 
	//
	//	ARGUMENTS: 
	//	  $options: option (control) selected, usually a button 
	//	  $_POST is a auto global)
	//
	//	RETURNS:
	//	  None. Updates database from $_POST and posts admin messages 
	//	 	to the UI.
	//
	//-------------------------------------------------------------
	function post( $options ) {
		//mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN.post:' );
		//mstw_log_msg( $options );
		//mstw_log_msg( $_POST );
		
		if ( !$options ) {
			mstw_tr_add_admin_notice( 'error', __( 'Problem encountered updating games. Exiting.', 'team-rosters' ) );
			mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN.post: Problem encountered updating games. Exiting.' );
			return;
		}
		
		/*
		 *  THIS IS FOR DEBUGGING
		 */
		//return;
		
		//if ( 'No' == $_POST['are_you_sure'] ) {
		//	mstw_tr_add_admin_notice( 'updated', __( 'Update Roster cancelled.', 'team-rosters' ) );
		//	return;
		//}
		
		switch( $options['submit_value'] ) {
			
			case __( 'Update Roster', 'team-rosters' ):
			case __( 'Add Players', 'team-rosters' ):
				//mstw_log_msg( 'Updating Roster ...' );

				//if ( true ) { // for testing
				if ( !array_key_exists( 'current-team', $_POST ) ) {
					mstw_log_msg( 'MSTW_TR_TEAM_ROSTERS_ADMIN.post: No team specified for roster update? ' );
					mstw_tr_add_admin_notice( 'error',  __( 'Error: No team specified for roster update.', 'team-rosters' ) );
					
				} else {
					$team_slug = array_shift( $_POST ); //return 1st value and remove 1st element
					$playerArray = $_POST;
					//mstw_log_msg( "current-team= $team_slug" );
					//mstw_log_msg( $playerArray );
					
					//return;

					
					//$team_slug = each( $_POST )['value'];
					//mstw_log_msg( "team slug: $team_slug" );
					
					// Count for admin message
					$nbr_updated = 0;
					$nbr_created = 0;
					
					$nbrFields = sizeof( self::$data_fields );
					
					//DEBUGGING CONVENIENCE
					//$fakeCount = 0;
					
					foreach ( $playerArray as $key => $value ) {
						//DEBUGGING CONVENIENCE
						//if ( $fakeCount > 2 ) {
							//break;
						//}
						if ( 'player_slug' == $this -> get_tag_base( $key ) ) {
							//mstw_log_msg( "Found possible player data set " );
							
							$player_slug = $this -> get_tag_base( $value );
							//mstw_log_msg( "player_slug: $player_slug" );
							
							$playerData = array_slice( $playerArray, 1, $nbrFields );
							//mstw_log_msg( "playerData:" );
							//mstw_log_msg( $playerData );
							
							$playerArray = array_slice( $playerArray, $nbrFields + 1 );
							//mstw_log_msg( "new playerArray:" );
							//mstw_log_msg( $playerArray );
							
							/*
							need to reset $playerArray for next go round 
							for ( $i = 0; $i < $nbrFields+1; $i++ ) {
								unset( 
							}*/
							
							$result = $this -> process_player( $player_slug, $team_slug, $playerData );
							
							//DEBUGGING CONVENIENCE
							//$fakeCount++;
							
							//continue;
							
							//mstw_log_msg( "process_player returned: $result" );
							
							switch ( $result ) {
								case 1:
									$nbr_created++;
									break;
									
								case 2:
									$nbr_updated++;
									break;
									
								case 0:
									// had a processing error, but keep going
									break;
								
								case -1:								
								default: // we're done
									$result = -1;
									break;
									
							}
							
						} else {
							$result = -1;
							continue;
							mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.post: No player slug found. Skipping." );
							
						}
						
						if ( -1 == $result ) {
							break;
						}
						
					} //End: foreach ( $_POST as $key => $value ) {
					
					//
					// Done. Display the admin messages.
					//
					//mstw_log_msg( "$nbr_updated players updated" );
					if ( $nbr_updated ) {
						mstw_tr_add_admin_notice( 'updated', "$nbr_updated players updated." );
					}
					
					//mstw_log_msg( "$nbr_created players created" );
					if ( $nbr_created ) {
						mstw_tr_add_admin_notice( 'updated', "$nbr_created players created." );
					}
					
				} //End: if ( !array_key_exists( 'current-team', $_POST ) )
				break;
					
			default:
				// Nothing else just yet
				mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.post: received unknown " . $options['submit_value'] );
				break;

		} //End switch
		

	} //End: post( )
	
	//-------------------------------------------------------------------------
	// process_player - creates a player CPT and/or updates the meta data
	//
	//  ARGUMENTS: 
	//	  $player_slug: player to process (create & update or just update)
	//	  $team_slug:   team to add player to
	//	  $playerData: player data fields
	//
	//	RETURNS:
	//	  Returns -1, 0, 1, or 2 ??
	//
	function process_player( $player_slug, $team_slug, $playerData ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.process_player:" );
		//mstw_log_msg( "player_slug: $player_slug team_slug: $team_slug" );
		
		// $_POST is a auto global
		
		$retval = -1;
		
		$first = array_values( $playerData )[0];
		$last  = array_values( $playerData )[1];
		//mstw_log_msg( "name: $first $last" );
		
		//$first = array_slice( $playerData, 0, 1 ); //trim( each( $_POST )['value'] );
		//$last  = array_slice( $playerData, 1, 1 ); //trim( each( $_POST )['value'] );
			
		if ( -1 ==  $player_slug ) {
			// Create a new player
			if ( $first ) {
				$player_title = ( $last ) ? "$first $last" : $first;
	
			} else if ( $last ) {
				$player_title = $last;
				
			} else {
				$player_title = '';
				
			}
			//mstw_log_msg( "player_title: $player_title" );
			
				
			if ( $player_title ) {
				$player_id  = $this -> create_player( $player_title, $team_slug );
				
				// Need to check for errors
				if ( $player_id ) {
					$player_slug = '';
					$retval = $this -> update_player( $player_id, $player_slug, $team_slug, $first, $last, $playerData );
					
				} else {
					$retval = 0;
					
				}
				
			} else {
				// This should happen on the first empty row
				//mstw_log_msg( "No first or last. We are done!" );
				$retval = -1;
			}
		
		} else if ( $player_slug ) {
			//mstw_log_msg( "Updating player: $player_slug" );
			$player_id = 0;
			$retval = $this -> update_player( $player_id, $player_slug, $team_slug, $first, $last, $playerData );
			
			$retval = ( $retval ) ? 2 : 0;
			
			//$nbr_updated++;
			
		} else {
			// This is just to record possible errors
			// mstw_log_msg( $this -> get_tag_base( $elt['key'] ) );
			
		} //End: if ( -1 ==  $player_slug ) )
			
		return $retval;
		
	} //End: process_player( )
	
	//-------------------------------------------------------------------------
	// create_player - creates a new player (CPT)
	//	  Uses the $_POST[] auto global for the player data	
	//
	//	ARGUMENTS: 
	//	  $player_title: becomes the post title and is sanitized to be slug
	//		(normally provided as "First Last")
	//	  $team_slug:   team (taxonomy) slug
	//
	//	RETURNS:
	//	  New player ID on success or 0 on failure
	//
	function create_player( $player_title, $team_slug ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.create_player:" );
		
		$args = array( 'post_title'  => $player_title,
									 'post_type'   => 'mstw_tr_player',
									 'post_status' => 'publish',
								 );
					
		remove_action( 'save_post_mstw_tr_player', 'mstw_tr_save_player_meta', 20, 2 );
		
		$plyr_id = wp_insert_post( $args );
		
		add_action( 'save_post_mstw_tr_player', 'mstw_tr_save_player_meta', 20, 2 );
		
		if ( $plyr_id && ( -1 != $team_slug ) ) {
			$ret = wp_set_object_terms( $plyr_id, $team_slug, 'mstw_tr_team', false );
		}
		
		return $plyr_id; 
		
	} //End: create_player( )
	
	//-------------------------------------------------------------------------
	// update_player - updates player (CPT) database with player data
	//	  Uses the $_POST[] auto global for the player data	
	//
	//	ARGUMENTS: 
	//	  $player_id:   player CPT ID (used if $player_slug == '')
	//	  $player_slug: player CPT slug (used if not null string)
	//	  $team_slug:   team (taxonomy) slug
	//	  $first_name:  player first name
	//	  $last_name:   player last name
	//		$playerData:  player data - array of all player data fields
	//
	//  NOTE: the keys are appended with the row number from the add players screen
	//				ergo, the tricksy stuff with strcmp
	//
	//	RETURNS:
	//	  1 on success; 0 on failure
	//
	function update_player( $player_id, $player_slug, $team_slug, $first_name, $last_name, $playerData ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.update_player:" );
		//mstw_log_msg( "player id: $player_id , slug: $player_slug , team: $team_slug" );

		if ( '' != $player_slug ) {
			$player_obj = get_page_by_path( $player_slug, OBJECT, 'mstw_tr_player' );
		
			//if( true ) { //for testing
			if ( $player_obj == null ) {
				mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.update_player: Player update failed: $first_name $last_name" );
				mstw_tr_add_admin_notice( 'error', "Player update failed: $first_name $last_name" );
				$player_id = 0;
			
			} else {
				$player_id = $player_obj -> ID;
			}
		
		}
		
		if ( 0 != $player_id ) {
			foreach( $playerData as $key => $value ) {
				foreach ( self::$data_fields as $fieldName => $nonsense ) {
					if ( false !== strpos( $key, $fieldName ) ) {
						//mstw_log_msg( "updating $player_id , field $fieldName , value $value" );
						update_post_meta( $player_id, $fieldName, $value );
						break;
					}
				}
			}
		} //End: if ( 0 != $player_id ) {
			
		return 1;
		
	} //End: update_player( )
	
	/*-------------------------------------------------------------------------
	 *
	 *   HELP
	 *
	 *------------------------------------------------------------------------*/

	//-------------------------------------------------------------------------
	// add_help - Contextual help callback. Action set in mstw_tr_admin.php 
	//
	//	ARGUMENTS: 
	//	  None
	//
	//	RETURNS:
	//	  None. Adds help sidebar and help tab to $screen object
	//	
	function add_help( ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.add_help:" );
		
		$screen = get_current_screen( );
		// We are on the correct screen because we take advantage of the
		// load-* action ( in mstw-lm-admin.php, mstw_lm_admin_menu()
		
		if ( 'team-rosters_page_manage-team-rosters' == $screen -> id ) {
			$callback = "edit_rosters_help_content";
			$title = 'Edit Rosters';
			$id  = 'edit-rosters-help';
			
		} else if ( 'team-rosters_page_add-players-screen' == $screen -> id ) {
			// screen-> id should be team-rosters_page_manage-team-rosters
			$callback = "add_players_help_content";
			$title = 'Add Players to Rosters';
			$id  = 'add-players-help';
			
		}
		
		mstw_tr_help_sidebar( $screen );
		
		$screen -> add_help_tab( array(
							'id'  => $id,
							'title' => __( $title, 'team-rosters' ),
							'callback' => array( $this, $callback )
							)
						);  
			
	} //End: add_help( )
	
	//-------------------------------------------------------------------------
	// edit_rosters_help_content - creates the HTML for the manage rosters screen
	//		context sensitive help tab
	//
	//	ARGUMENTS: 
	//	  None.
	//
	//	RETURNS:
	//	  None. Outputs HTML to display
	//
	function edit_rosters_help_content( ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.edit_rosters_help_content:" );
		
				?>
				<p><?php _e( 'Use this screen to edit the players on a roster in bulk - up to 20 at time. First use the drop-down menu to select the team roster to be edited.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'While players can be edited on this screen much faster than the Manage Players screen, there are some restrictions. The Player Title, Player Slug, Player Team(s), Player Photo, and Player Profile fields cannot be edited on this screen. Use the Manage Players screen.', 'team-rosters' ) ?></p>
				
				<p>See the <a href="http://shoalsummitsolutions.com/category/users-manuals/tr-plugin/" target="_blank">MSTW Team Rosters users manual</a> for more details.</p>
				
				<?php				

	} //End: edit_rosters_help_content( )
	
	//-------------------------------------------------------------------------
	// add_players_help_content - creates the HTML for the manage rosters screen
	//		context sensitive help tab
	//
	//	ARGUMENTS: 
	//	  None.
	//
	//	RETURNS:
	//	  None. Outputs HTML to display
	//
	function add_players_help_content( ) {
		//mstw_log_msg( "MSTW_TR_TEAM_ROSTERS_ADMIN.add_players_help_content:" );
		
				?>
				<p><?php _e( 'Use this screen to add players to rosters in bulk - up to 20 at time. First use the drop-down menu to select the team to which to add players.', 'team-rosters' ) ?></p>
				
				<p><?php _e( 'While players can be added on this screen much faster than the Manage Players screen, there are the following restrictions:', 'team-rosters' ) ?></p>
				
				<ul>
				  <li><?php _e( 'The Player Title will be set to "First_Name Last_Name.', 'team-rosters' ) ?> </li>
				  <li><?php _e( 'The Player Slug will be set to "first_name-last_name.', 'team-rosters' ) ?> </li>
				  <li><?php _e( 'Each Player will be added to only the selected team. Use the Manage Players screen to add a player to muliple teams.', 'team-rosters' ) ?> </li>
				  <li><?php _e( 'Use the Manage Players screen to add Player Photos and Player Profiles.', 'team-rosters' ) ?> </li>
				</ul>
				
				<p><a href="http://shoalsummitsolutions.com/category/users-manuals/tr-plugin/" target="_blank"><?php _e( 'See the MSTW Team Rosters users manual for more details.', 'team-rosters' ) ?></a></p>
				
				<?php				

	} //End: add_players_help_content( )
	
	
} //End: class MSTW_TR_TEAM_ROSTERS_ADMIN
?>