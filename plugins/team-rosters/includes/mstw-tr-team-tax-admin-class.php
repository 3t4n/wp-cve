<?php
/*---------------------------------------------------------------------------
 *	mstw-tr-team-tax-admin-class.php
 *		Adds data fields to the default taxonomy window
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2015-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
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

 class MSTW_TR_TEAM_TAX_ADMIN {
	//
	// Used for both Add and Edit team screens
	//
	private static $team_link_params = array ( 
					'ss_team_link' => 
						array( 
							'post_type' => 'mstw_ss_team',
							'title'   => 'MSTW Schedules & Scoreboards',
							'warning' => 'Install the MSTW Schedules & Scoreboards plugin',
							),
					'lm_team_link' =>
						array( 
							'post_type' => 'mstw_lm_team',
							'title' => 'MSTW League Manager',
							'warning' => 'Install the MSTW League Manager plugin',
							),
					);
	 
	//-----------------------------------------------------------------
    // Constructor - add needed actions
    //
    public function __construct( ) {
		//-----------------------------------------------------------------
		// Add the meta boxes for the mstw_lm_team custom post type
		//
		//add_action( 'add_meta_boxes_mstw_lm_team', 
						//array( $this, 'add_team_metaboxes' ) );
				
		//----------------------------------------------------------------------
		// Add MSTW SS team link to team taxonomy add & edit screens
		// 
		add_action( 'mstw_tr_team_add_form_fields',
					array( $this, 'team_add_form' ), 10, 2 );
					
		add_action ( 'mstw_tr_team_edit_form_fields', 
					 array( $this, 'team_edit_form' ), 10, 2 );
		
					
    } //End: __contruct( )
 
	 // ----------------------------------------------------------------
	 // Remove the row actions
	 //	
	function team_row_actions( $actions ) { 
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.team_row_actions:' );
		
		unset( $actions['inline hide-if-no-js'] );
		unset( $actions['view'] );
		//unset( $actions['delete'] );
		//unset( $actions['edit'] );
		
		return $actions;

	} //End: team_row_actions( )

	//----------------------------------------------------------------------
	// Add MSTW SS team & MSTW LM team links to team taxonomy add & edit screens
	// 
	function team_add_form( ) {
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.team_add_form:' );
		global $hook_suffix;
		global $pagenow;
		//mstw_tr_log_msg( "hook_suffix: $hook_suffix" );
		//mstw_tr_log_msg( "pagenow: $pagenow" );
		
		$screen = get_current_screen( );
		//mstw_tr_log_msg( $screen );
		
		$params = self::$team_link_params;
		
		?>
		<h2>Load Team From:</h2>
		<?php
		foreach ($params as $key => $value ) {
			if( !post_type_exists( $value['post_type'] ) ) {
			?>
			  <div class="form-field">
				<p class="plugin-not-installed"><?php _e( $value['warning'] , 'team-rosters' )?></p>
			  </div>
			  
			<?php
			} else { 
				$id   = $key;
				if ( $key ){
					
				}
				?>
				
				<div class="form-field">
				  <label for=<?php echo $id ?>><?php _e( $value['title'] , 'team-rosters' ) ?></label>
				  
				  <select id='<?php echo $key ?>' name='<?php echo $key ?>' class='mstw-tr-tax-select-team' >
				    <?php
					$options = $this -> build_teams_list( $value['post_type'] );
					
					foreach( $options as $k=>$v ) { 
						$selected = selected( -1, $v, false );
						?>
						<option value='<?php echo $v ?>' <?php echo $selected?>><?php echo $k ?></option>
					<?php } ?>
				  </select>
				
				</div> <!-- .form-field -->
				<?php
				
			}
		} //End: foreach()

		//do_action( 'create_mstw_tr_team', 20, 18 );
		
	} //End: team_add_form( )
	
	//-----------------------------------------------------------------
	// team_edit_form - adds the team links to the edit taxonomy term form
	//
	//	ARGUMENTS: 
	//	  $team_obj: term object of the team being editted
	//	
	//	RETURNS:
	//	  None. Outputs the HTML to the display.
	//
	function team_edit_form( $team_obj ) {
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.team_edit_form:' );
		
		global $hook_suffix;
		global $pagenow;
		//mstw_tr_log_msg( "hook_suffix: $hook_suffix" );
		//mstw_tr_log_msg( "pagenow: $pagenow" );
		
		$screen = get_current_screen( );
		//mstw_tr_log_msg( $screen );
		
		$params = self::$team_link_params;
		
		$team_id =   $team_obj -> term_id;
		//mstw_tr_log_msg( "team_id: $team_id" );
		$team_slug = $team_obj -> slug;
		//mstw_tr_log_msg( "team_slug: $team_slug" );
		
		$tr_team_link = get_term_meta( $team_id, 'tr_team_link', true );
		//mstw_tr_log_msg( "ss_team_link: $ss_team_link" );
		
		$tr_link_source = get_term_meta( $team_id, 'tr_link_source', true );
		//mstw_tr_log_msg( "lm_team_link: $lm_team_link" );
		
		?>
		<tr class="form-field">
		  <th scope="row">
			MSTW Plugin Links:
		  </th>
		  
		  <?php 
		  // The second cell of the main table row contains its own table
		  ?>
		  <td>
		    <table>
		      <tr>
		<?php
		
		foreach ($params as $key => $value ) {
			if( !post_type_exists( $value['post_type'] ) ) {
			?>
			  <td>
				<p class="plugin-not-installed"><?php _e( $value['warning'] , 'team-rosters' )?></p>
			  </td>
			  
			<?php
			} else { 
				$id = $key;
				?>
				<td>
				  <select id='<?php echo $key ?>' name='<?php echo $key ?>' class='mstw-tr-tax-select-team' >
				    <?php
					$options = $this -> build_teams_list( $value['post_type'] );
					
					foreach( $options as $k => $v ) { 
						if ( $tr_link_source == $value['post_type'] ) {
							$selected = selected( $tr_team_link, $v, false );
						} else {
							$selected = '';
						}
						?>
						<option value='<?php echo $v ?>' <?php echo $selected ?>><?php echo $k ?></option>
					<?php } ?>
				  </select>
				  
				  <?php _e( $value['title'] , 'team-rosters' ) ?>
				
				</td> <!-- .form-field -->
				<?php
				
			}
		} //End: foreach()
		?>
		      </tr>
		    </table>
		  </td>
		</tr>
		
		<?php
	} //End: team_edit_form( )
 
	//----------------------------------------------------------------------
	// manage_team_columns
	//
	function manage_team_columns( $columns ) {
	//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.manage_team_columns:' );
	
	$new_columns = array(
        'cb' 			  => '<input type="checkbox" />',
        'name' 			  => __( 'Team Name', 'team-rosters' ),
		'posts' 		  => __( 'Players', 'team-rosters' ),
		'slug' 			  => __( 'Slug', 'team-rosters' ),
        'team-source'     => __( 'Team Source', 'team-rosters' ),
		//'description'   => __('Description', 'team-rosters' ),
        );
	
	return $new_columns;
	
	} //End: manage_team_columns()
	
	//-----------------------------------------------------------------
	// set_sortable_columns - filter to change sortable columns
	//
	//	ARGUMENTS: 
	//	  $columns: array of sortable columns
	//	
	//	RETURNS:
	//	  $columns array modifies so only name is sortable
	//
	function set_sortable_columns( $columns ) {
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.set_sortable_columns:' );
		//mstw_tr_log_msg( $columns );
		
		unset( $columns['slug'] );
		unset( $columns['posts'] );
		
		return $columns;
		
	} //End: set_sortable_columns( )

	//-----------------------------------------------------------------
	// Fill the data in the Data Source custom column
	//
	function fill_custom_columns( $out, $column_name, $team_id ) {
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.fill_custom_columns:' );
		//mstw_tr_log_msg( '$team_id= ' . $team_id );
		//mstw_tr_log_msg( '$column_name= ' . $column_name );
		
		// set the default return value
		$out = __( 'None', 'team-rosters' );
		
		// load team metadata
		$team_obj = get_term( $team_id, 'mstw_tr_team', OBJECT );
		
		if ( !$team_obj ) {
			return $out;
		}
		
		$team_id = $team_obj->term_id;
		$team_slug = $team_obj->slug;
		

		// check for a link to an SS team
		$link_source = get_term_meta( $team_id, 'tr_link_source', true );
		$team_link   = get_term_meta( $team_id, 'tr_team_link', true );
		
		// we have a link
		if ( '' != $link_source && -1 != $link_source ) {
			$src =  ( "mstw_ss_team" == $link_source ) ? "SS" : "LM";
			
			// try to follow it
			$team_obj = get_page_by_path( $team_link, OBJECT, $link_source );
			if ( $team_obj ) {
				$team_name = $team_obj -> post_title;
				$out = "$src - " . $team_obj -> post_title;
			}
			
		}
		
		return $out;    
	
	} //End: fill_custom_columns( )
	
	
	function create_team_meta( $term_id, $tt_id ){
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.create_team_meta:' );
		//mstw_tr_log_msg( "term_id= $term_id || tt_id= $tt_id" ); 
		
		//mstw_tr_log_msg( $_POST );
		
		$key = 'ss_team_link';
		if ( array_key_exists( $key, $_POST ) && ( -1 != $_POST[ $key ] ) ) {
			
			$res = update_term_meta( $term_id, 'tr_team_link', $_POST[ $key ] );
			
			$res = update_term_meta( $term_id, 'tr_link_source', 'mstw_ss_team' );
			
			return;
					
		}  
			
		$key = 'lm_team_link';
		if ( array_key_exists( $key, $_POST ) && ( -1 != $_POST[ $key ] ) ) {
			
			$res = update_term_meta( $term_id, 'tr_team_link', $_POST[ $key ] );
			
			$res = update_term_meta( $term_id, 'tr_link_source', 'mstw_lm_team' );
			
			return;
					
		} 
		 
	} //End: created_team( )
	
	//-----------------------------------------------------------------
	// edit_team_meta - callback to save term meta data
	//
	//	ARGUMENTS: 
	//	  $term_id: ID of the editted term (int)
	//	  $tt_id: Term taxonomy ID (int)
	//	
	//	RETURNS:
	//	  None. Updates the term_meta based on arguments and $_POST
	//
	function edit_team_meta( $term_id, $tt_id ) {
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.edit_team_meta:' );
		//mstw_tr_log_msg( '$term_id= ' . $term_id );
		//mstw_tr_log_msg( '$tt_id= ' . $tt_id );
		
		//mstw_tr_log_msg( $_POST );
		
		$key = 'ss_team_link';
		if ( array_key_exists( $key, $_POST ) && ( -1 != $_POST[ $key ] ) ) {
			
				update_term_meta( $term_id, 'tr_team_link', $_POST[ $key ] );
				update_term_meta( $term_id, 'tr_link_source', 'mstw_ss_team' );
					
		} else {
		
			$key = 'lm_team_link';
			if ( array_key_exists( $key, $_POST ) && ( -1 != $_POST[ $key ] ) ) {
				
				update_term_meta( $term_id, 'tr_team_link', $_POST[ $key ] );
				update_term_meta( $term_id, 'tr_link_source', 'mstw_lm_team' );
						
			} else {
				
				update_term_meta( $term_id, 'tr_team_link', -1 );
				update_term_meta( $term_id, 'tr_link_source', '' );
			}
		}
		
	} //End: edit_team_meta( )
	
	//-------------------------------------------------------------
	//
	// HELP FUNCTIONS
	//
	//-------------------------------------------------------------
	
	//-------------------------------------------------------------
	// add_help - outputs HTML for the contextual help area of the screen
	//		
	// ARGUMENTS:
	//	 None 
	//   
	// RETURNS:
	//	 Outputs HTML to the contextual help area of the screen
	//-------------------------------------------------------------
	
	function add_help( ) {
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.add_help:' );
		
		$screen = get_current_screen( );
		//mstw_tr_log_msg( "add_help: screen" );
		//mstw_tr_log_msg( $screen );
		
		//Check screen because "load-edit-tags.php action is generic
		// (mstw-tr-admin.php, mstw_tr_register_menu_pages( )
		
		if ( "edit-mstw_tr_team" == $screen -> id ) {
		
			mstw_tr_help_sidebar( $screen );
			
			$tabs = array( array(
							'title'    => __( 'Teams', 'team-rosters' ),
							'id'       => 'manage-teams-help',
							'callback'  => array( $this, 'add_help_tab' ),
							),
							array(
							'title'    => __( 'Edit Team', 'team-rosters' ),
							'id'       => 'edit-team-help',
							'callback'  => array( $this, 'add_help_tab' ),
							),
						 );
						 
			foreach( $tabs as $tab ) {
				$screen->add_help_tab( $tab );
			}
			
		} //End: if ( "edit-mstw_tr_team" == $screen -> id )
		
	} //End: add_help( )
	
	//-------------------------------------------------------------
	// add_help_tab - outputs HTML for the contextual help area of the screen
	//		
	// ARGUMENTS:
	//	 $screen: screen (object) to which $tab will be added
	//	 $tab: array of tab info - title, id, callback
	//   
	// RETURNS:
	//	 Outputs HTML to the contextual help area of the screen
	//-------------------------------------------------------------
	function add_help_tab( $screen, $tab ) {
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.add_help_tab:' );
		
		if( !array_key_exists( 'id', $tab ) ) {
			return;
		}
			
		switch ( $tab['id'] ) {
			case 'manage-teams-help':
				?>
				<p><?php _e( 'This screen provides management (add, edit, delete) of teams.', 'team-rosters' ) ?></p>
				<p><?php _e( 'Each team may be linked to a team in the MSTW Schedules & Scoreboards or the MSTW League Manager database. These links will allow team logos to be pulled from the database, and team colors for links  the Schedules & Scoreboard', 'team-rosters' ) ?></p>
				<p><?php _e( 'Teams may be added on this page. They may also be added in bulk via the CSV Import screen.', 'team-rosters' ) ?></p>
				<p><?php _e( 'Roll over a team name, and select "Edit" to modify the data for an existing team." ', 'team-rosters' ) ?></p>
				<p><?php _e( 'Roll over a team name, and select "Delete" to remove a team. Any players assigned to the team will be removed from the team, but will remain in the players database." ', 'team-rosters' ) ?></p>
				
				<p><a href="http://shoalsummitsolutions.com/tr-data-entry-teams/" target="_blank"><?php _e( 'See the Data Entry - Teams man page for more details.', 'mstw-league-manager' ) ?></a></p>
				
				<?php				
				break;
				
			case 'edit-team-help':
				?>
				<p><?php _e( 'Use this screen to modify the information for an existing team.', 'team-rosters' ) ?></p>
				
				<p><a href="http://shoalsummitsolutions.com/tr-data-entry-teams/" target="_blank"><?php _e( 'See the Data Entry - Teams man page for more details.', 'mstw-league-manager' ) ?></a></p>
				
				
				
				<?php				
				break;
			
			default:
				break;
		} //End: switch ( $tab['id'] )

	} //End: add_help_tab( )
	
	//-------------------------------------------------------------
	//
	// UTILITY FUNCTIONS
	//
	//-------------------------------------------------------------
	
	// ------------------------------------------------------------------------------
	// build_teams_list - Builds an array of teams in team CPT 
	//	title=>slug pairs for use in a select-option control
	//
	//	ARGUMENTS: 
	//		None
	//	RETURNS:
	//		An array of team title=>ID pairs OR
	//		Empty array of no teams exist
	//
	function build_teams_list( $post_type ) {
		//mstw_tr_log_msg( 'MSTW_TR_TEAM_TAX_ADMIN.build_teams_list:' );
		
		$teams = get_posts( array(  'numberposts' => -1,
									'post_type'   => $post_type,
									'orderby'     => 'title',
									'order'       => 'ASC' 
									)
							);	
							
		$options = array( );
			
		if( $teams ) {
			$options['----'] = -1;
			
			foreach( $teams as $team ) {
				$options[ get_the_title( $team->ID ) ] = get_post( $team->ID )->post_name;
			}
		}
		
		return $options;
	
	} //End: build_teams_list( )

} //End: class MSTW_TR_TEAM_TAX_ADMIN 