<?php
/*---------------------------------------------------------------------------
 *	mstw-tr-cpts.php
 *		Registers the custom post types & taxonomy for MSTW Team Rosters
 *		mstw_tr_player, mstw_tr_team
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
 *-------------------------------------------------------------------------*/
 
// ----------------------------------------------------------------
// Register the MSTW Team Rosters Custom Post Types & Taxonomies
// 		mstw_tr_player, mstw_tr_team
//
if( !function_exists( 'mstw_tr_register_cpts' ) ) {
	function mstw_tr_register_cpts( ) {

		$menu_icon_url = plugins_url( 'images/mstw-admin-menu-icon.png', dirname( __FILE__ ) );
		
		$capability = 'read';
			
		//-----------------------------------------------------------------------
		// register mstw_tr_player custom post type
		//
		$args = array(
			'label'				=> __( 'Players', 'team-rosters' ),
			'description'		=> __( 'CPT for Players in MSTW Team Rosters Plugin', 'team-rosters' ),
			
			'public' 			=> true,
			//'exclude_from_search'	=> true, //default is opposite value of public
			'publicly_queryable'	=> true, //default is value of public
			'show_ui'			=> true, //default is value of public
			'show_in_nav_menus'	=> false, //default is value of public
			//going to build own admin menu
			'show_in_menu'		=> false, //default is value of show_ui
			'show_in_admin_bar' => false, //default is value of show_in_menu
			//only applies if show_in_menu is true
			//'menu_position'		=> 25, //25 is below comments, which is the default
			'menu_icon'     	=> null, //$menu_icon_url,
			
			//'capability_type'	=> 'post' //post is the default
			//'capabilities'		=> null, //array default is constructed from capability_type
			//'map_meta_cap'	=> null, //null is the default
			
			//'hierarchical'	=> false, //false is the default
			
			'rewrite' 			=> array(
				'slug' 			=> 'player',
				'with_front' 	=> false,
			),

			
			'supports' 			=> array( 'title', 'editor', 'thumbnail' ),
			
			//post is the default capability type
			'capability_type'	=> array( 'player', 'players' ), 
			
			'map_meta_cap' 		=> true,  //null is the default
										
			//'register_meta_box_cb'	=> no default for this one
			
			'taxonomies' => 	array( 'mstw_tr_team' ),
			
			// Note that is interacts with exclude_from_search
			//'has_archive'		=> false, //false is the default
			
			'query_var' 		=> true, //post_type is default mstw_tr_player
			'can_export'		=> true, //default is true
			
			'labels' 			=> array(
										'name' => __( 'Players', 'team-rosters' ),
										'singular_name' => __( 'Player', 'team-rosters' ),
										'all_items' => __( 'Players', 'team-rosters' ),
										'add_new' => __( 'Add New Player', 'team-rosters' ),
										'add_new_item' => __( 'Add Player', 'team-rosters' ),
										'edit_item' => __( 'Edit Player', 'team-rosters' ),
										'new_item' => __( 'New Player', 'team-rosters' ),
										//'View Player' needs a custom page template that is of no value. ???
										'view_item' => __( 'View Player', 'team-rosters' ),
										'search_items' => __( 'Search Players', 'team-rosters' ),
										'not_found' => __( 'No Players Found', 'team-rosters' ),
										'not_found_in_trash' => __( 'No Players Found In Trash', 'team-rosters' ),
										)
			);
			
		register_post_type( 'mstw_tr_player', $args);
		
		//
		// Register the team taxonomy ... acts like a tag
		//
		$labels = array( 
					'name' => __( 'Manage Teams', 'team-rosters' ),
					'singular_name' =>  __( 'Team', 'team-rosters' ),
					'search_items' => __( 'Search Teams', 'team-rosters' ),
					'popular_items' => null, //removes tagcloud __( 'Popular Teams', 'team-rosters' ),
					'all_items' => __( 'All Teams', 'team-rosters' ),
					'parent_item' => null,
					'parent_item_colon' => null,
					'edit_item' => __( 'Edit Team', 'team-rosters' ), 
					'update_item' => __( 'Update Team', 'team-rosters' ),
					'add_new_item' => __( 'Add New Team', 'team-rosters' ),
					'new_item_name' => __( 'New Team Name', 'team-rosters' ),
					'separate_items_with_commas' => __( 'Add Player to one or more Teams (separate Teams with commas).', 'team-rosters' ),
					'add_or_remove_items' => __( 'Add or Remove Teams', 'team-rosters' ),
					'choose_from_most_used' => __( 'Choose from the most used teams', 'team-rosters' ),
					'not_found' => __( 'No Teams Found', 'team-rosters' ),
					'menu_name'  => __( 'Teams', 'team-rosters' ),
				  );
				  
		$args = array( 
				//'label'				=> 'MSTW Teams', //overridden by $labels->name
				'labels'				=> $labels,
				'public'				=> true,
				'show_ui'				=> true,
				'show_in_nav_menus'		=> true,
				'show_in_menu'			=> true,
				'show_tagcloud'			=> false,
				//'meta_box_cb'			=> null, provide callback fcn for meta box display
				'show_admin_column'		=> true, //allow automatic creation of taxonomy column in associated post-types table.
				'hierarchical' 			=> false, //behave like tags
				//'update_count_callback'	=> '',
				'query_var' 			=> true, 
				'rewrite' 				=> array(
												'slug' 			=> 'team',
												'with_front' 	=> false,
												),
				//'capabilities'			=> array( ),
				'capabilities'			=> array(
												'manage_terms' => 'manage_tr_teams',
												'edit_terms' => 'manage_tr_teams',
												'delete_terms' => 'manage_tr_teams',
												'assign_terms' => 'manage_tr_teams',
												),
				//'sort'					=> null,
			);
			
		register_taxonomy( 'mstw_tr_team', 'mstw_tr_player', $args );
		register_taxonomy_for_object_type( 'mstw_tr_team', 'mstw_tr_player' );
		
		//
		// Register the meta data for the mstw_tr_team taxonomy
		//
		register_meta( 'term', 'tr_team_link', null );
		register_meta( 'term', 'tr_link_source', null );
		
		//add_action( 'created_mstw_tr_team', 
		//			'mstw_tr_test_created_team', 10, 2 );
		

	} //End: mstw_tr_register_cpts( )
}
?>