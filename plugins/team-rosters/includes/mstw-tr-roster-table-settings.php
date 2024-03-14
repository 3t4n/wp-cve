<?php
/*----------------------------------------------------------------------------
 * mstw-tr-roster-table-settings.php
 *	All functions for the MSTW Team Rosters Plugin's roster table [shortcode] settings.
 *	Loaded in mstw-tr-settings.php 
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

	function mstw_tr_roster_table_setup( ) {
		//mstw_log_msg( 'mstw_tr_roster_table_setup:' );
		$options = wp_parse_args( get_option( 'mstw_tr_options' ), 
		array_merge( mstw_tr_get_roster_table_defaults( ), mstw_tr_get_roster_table2_defaults( ) ) );
		
		mstw_tr_roster_table_section_setup( $options );
		mstw_tr_roster_table2_section_setup( $options );

	} //End: mstw_tr_roster_colors_setup()		

	function mstw_tr_roster_table_section_setup( $options ) {
		//mstw_tr_log_msg( 'mstw_tr_roster_table_section_setup:' );
		
		global $TEXT_DOMAIN;

		//$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		
		// Roster Table data fields/columns -- show/hide and labels
		$display_on_page = 'mstw-tr-roster-table';
		$section_id = 'mstw-tr-roster-table-settings'; //'mstw_tr_table_structure_settings';
		$instruct_callback = 'mstw_tr_roster_table_inst';
		$section_title = __( 'Roster Table Settings', $TEXT_DOMAIN );
		
		add_settings_section( $section_id, 
													$section_title, 
													$instruct_callback, 
													$display_on_page );

		$arguments = array(
			array( 	// Show/hide roster TITLE
				'type' => 'show-hide', 
				'id' => 'show_title',
				'name'	=> 'mstw_tr_options[show_title]',
				'value' => mstw_tr_safe_ref( $options, 'show_title', 0 ), 
				'title' => __( 'Show Roster Table Titles:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Titles will display as "Team Name Roster" (Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// Roster FORMAT
				'type' => 'select-option', 
				'id' => 'roster_type',
				'name'	=> 'mstw_tr_options[roster_type]',
				'value' => mstw_tr_safe_ref( $options, 'roster_type' ),
				'options' => array(	__( 'Custom', $TEXT_DOMAIN )=> 'custom', 
										__( 'Pro', $TEXT_DOMAIN ) => 'pro', 
										__( 'College', $TEXT_DOMAIN ) => 'college',
										__( 'High School', $TEXT_DOMAIN ) => 'high-school',
										__( 'Pro Baseball', $TEXT_DOMAIN ) => 'baseball-pro', 
										__( 'College Baseball', $TEXT_DOMAIN ) => 'baseball-college',
										__( 'High School Baseball', $TEXT_DOMAIN ) => 'baseball-high-school',
										),
				'title'	=> __( 'Roster Table Format:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: Custom, which is what you want if you are customizing the columns in the Data Fields & Columns tab.', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// ADD LINKS TO PROFILES
				'type' => 'checkbox', 
				'id' => 'links_to_profiles',
				'name'	=> 'mstw_tr_options[links_to_profiles]',
				'value' => mstw_tr_safe_ref( $options, 'links_to_profiles' ), 
				'title' => __( 'Add Links to Player Profiles:', $TEXT_DOMAIN ),
				'desc'	=> __( 'This setting applies to both roster tables and player galleries. Default: No Links (Unchecked)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// SORT BY FIELD
				'type' => 'select-option', 
				'id' => 'sort_order',
				'name'	=> 'mstw_tr_options[sort_order]',
				'value' => mstw_tr_safe_ref( $options, 'sort_order' ), 
				'options' => array(	__( 'Last Name', $TEXT_DOMAIN )=> 'alpha', 
									__( 'First Name', $TEXT_DOMAIN ) => 'alpha-first', 
									__( 'Number', $TEXT_DOMAIN ) => 'numeric'		
									),
				'title' => __( 'Sort Roster By:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: Last Name', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// SORT ORDER
				'type' => 'select-option', 
				'id' => 'sort_asc_desc',
				'name'	=> 'mstw_tr_options[sort_asc_desc]',
				'value' => mstw_tr_safe_ref( $options, 'sort_asc_desc' ), 
				'options' => array(	__( 'Ascending', $TEXT_DOMAIN )=> 'asc', 
									__( 'Descending', $TEXT_DOMAIN ) => 'desc'		
									),
				'title' => __( 'Sort Order:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: Ascending (1, 2, 3 ... or a, b, c ...)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// DISPLAY FORMAT for Player Names
				'type' => 'select-option', 
				'id' => 'name_format',
				'name'	=> 'mstw_tr_options[name_format]',
				'value' => mstw_tr_safe_ref( $options, 'name_format' ), 
				'options' => array(	__( 'Last, First', $TEXT_DOMAIN )=> 'last-first', 
									__( 'First Last', $TEXT_DOMAIN ) => 'first-last', 
									__( 'First Name Only', $TEXT_DOMAIN ) => 'first-only',
									__( 'Last Name Only', $TEXT_DOMAIN ) => 'last-only'		
									),
				'title' => __( 'Display Players By:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: Last, First', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
		
			array( 	// Player PHOTO WIDTH
				'type' => 'text', 
				'id' => 'table_photo_width',
				'name'	=> 'mstw_tr_options[table_photo_width]',
				'value' => mstw_tr_safe_ref( $options, 'table_photo_width' ), 
				'title' => __( 'Table Photo Width:', $TEXT_DOMAIN ),
				'desc'	=> __( 'In pixels. (Defaults to blank, which means the stylesheet setting will be used; 64px out of the box.)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// Player PHOTO HEIGHT
				'type' => 'text', 
				'id' => 'table_photo_height',
				'name'	=> 'mstw_tr_options[table_photo_height]',
				'value' => mstw_tr_safe_ref( $options, 'table_photo_height' ), 
				'title' => __( 'Table Photo Height:', $TEXT_DOMAIN ),
				'desc'	=> __( 'In pixels. (Defaults to blank, which means the stylesheet setting will be used; 64px out of the box.)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			
		);
		
		mstw_tr_build_settings_screen( $arguments );
		
	} //End: mstw_tr_roster_table_section_setup( )

	function mstw_tr_roster_table2_section_setup( $options ) {
		//mstw_log_msg( 'mstw_tr_roster_table2_section_setup:' );
			
		global $TEXT_DOMAIN;
		
		//$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		
		$dataFields = array(  __( 'Height Only', $TEXT_DOMAIN ) => 'height',
													__( 'Height/Weight', $TEXT_DOMAIN )	=> 'height-weight',
													__( 'Bats/Throws', $TEXT_DOMAIN )	=> 'bats-throws',
													__( 'Class/Year', $TEXT_DOMAIN ) => 'year-short',
													__( 'Class/Year (long format)', $TEXT_DOMAIN ) => 'year-long',
													__( 'Experience', $TEXT_DOMAIN ) => 'experience',
													__( 'Age', $TEXT_DOMAIN ) => 'age',
													__( 'Home Town', $TEXT_DOMAIN ) => 'home-town',
													__( 'Last School', $TEXT_DOMAIN ) => 'last-school',
													__( 'Country', $TEXT_DOMAIN ) => 'country',
													__( 'Bats/Throws', $TEXT_DOMAIN ) => 'bats-throws',
													__( 'Other Info', $TEXT_DOMAIN ) => 'other-info',						
												);
		
		// Roster Table data fields/columns -- show/hide and labels
		$display_on_page = 'mstw-tr-roster2-table';
		$section_id = 'mstw-tr-roster-table2-settings'; //'mstw_tr_table_2_structure_settings';
		$instruct_callback = 'mstw_tr_roster_table_2_inst';
		$section_title = __( 'Roster Table 2 Settings', $TEXT_DOMAIN );
		
		add_settings_section( $section_id, 
													$section_title, 
													$instruct_callback, 
													$display_on_page );

		$arguments = array(
			array( 	// Show/hide roster TITLE
				'type' => 'show-hide', 
				'id' => 'show_title_2',
				'name'	=> 'mstw_tr_options[show_title_2]',
				'value' => mstw_tr_safe_ref( $options, 'show_title_2' ), 
				'title' => __( 'Show Titles for Roster Table 2:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Titles will display as "Team Name Roster" (Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// Roster FORMAT
				'type' => 'select-option', 
				'id' => 'roster_type_2',
				'name'	=> 'mstw_tr_options[roster_type_2]',
				'value' => mstw_tr_safe_ref( $options, 'roster_type_2' ),
				'options' => array(	__( 'Custom', $TEXT_DOMAIN )=> 'custom', 
										__( 'Pro', $TEXT_DOMAIN ) => 'pro', 
										__( 'College', $TEXT_DOMAIN ) => 'college',
										__( 'High School', $TEXT_DOMAIN ) => 'high-school',
										__( 'Pro Baseball', $TEXT_DOMAIN ) => 'baseball-pro', 
										__( 'College Baseball', $TEXT_DOMAIN ) => 'baseball-college',
										__( 'High School Baseball', $TEXT_DOMAIN ) => 'baseball-high-school',
										),
				'title'	=> __( 'Roster Table Format:', $TEXT_DOMAIN ),
				'desc'	=> __( 'This setting has no affect on the shortcode display. It ONLY affects the player bios linked from the shortcode roster.', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// SORT BY FIELD
				'type' => 'select-option', 
				'id' => 'sort_order_2',
				'name'	=> 'mstw_tr_options[sort_order_2]',
				'value' => mstw_tr_safe_ref( $options, 'sort_order_2' ), 
				'options' => array(	__( 'Last Name', $TEXT_DOMAIN )=> 'alpha', 
									__( 'First Name', $TEXT_DOMAIN ) => 'alpha-first', 
									__( 'Number', $TEXT_DOMAIN ) => 'numeric'		
									),
				'title' => __( 'Sort Roster By:', $TEXT_DOMAIN ),
				'desc'	=> __( 'This setting is for the initial sort only. The user interface provides a control for sorting.', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// SORT ORDER
				'type' => 'select-option', 
				'id' => 'sort_asc_desc_2',
				'name'	=> 'mstw_tr_options[sort_asc_desc_2]',
				'value' => mstw_tr_safe_ref( $options, 'sort_asc_desc_2' ),
				'options' => array(	__( 'Ascending', $TEXT_DOMAIN )=> 'asc', 
									__( 'Descending', $TEXT_DOMAIN ) => 'desc'		
									),
				'title' => __( 'Sort Order:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: Ascending (1, 2, 3 ... or a, b, c ...)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// DISPLAY FORMAT for Player Names
				'type' => 'select-option', 
				'id' => 'name_format_2',
				'name'	=> 'mstw_tr_options[name_format_2]',
				'value' => mstw_tr_safe_ref( $options, 'name_format_2' ), 
				'options' => array(	__( 'Last, First', $TEXT_DOMAIN )=> 'last-first', 
									__( 'First Last', $TEXT_DOMAIN ) => 'first-last', 
									__( 'First Name Only', $TEXT_DOMAIN ) => 'first-only',
									__( 'Last Name Only', $TEXT_DOMAIN ) => 'last-only'		
									),
				'title' => __( 'Display Players By:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: First Last', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// FIRST/LEFTMOST DATA FIELD
				'type' => 'select-option', 
				'id' => 'data_field_1',
				'name'	=> 'mstw_tr_options[data_field_1]',
				'value' => mstw_tr_safe_ref( $options, 'data_field_1' ), 
				'options' => $dataFields,
				'title' => __( 'First(leftmost) Data Field:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: Class/year', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// Second/center data field
				'type' => 'select-option', 
				'id' => 'data_field_2',
				'name'	=> 'mstw_tr_options[data_field_2]',
				'value' => mstw_tr_safe_ref( $options, 'data_field_2' ), 
				'options' => $dataFields,
				'title' => __( 'Second(Center) Data Field:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: Hometown', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// Third/rightmost data field
				'type' => 'select-option', 
				'id' => 'data_field_3',
				'name'	=> 'mstw_tr_options[data_field_3]',
				'value' => mstw_tr_safe_ref( $options, 'data_field_3' ), 
				'options' => $dataFields,
				'title' => __( 'Third(Rightmost) Data Field:', $TEXT_DOMAIN ),
				'desc'	=> __( 'Default: Last School', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
		);
		
		mstw_tr_build_settings_screen( $arguments );
		
	} //End: mstw_tr_roster_table2_section_setup( )

	//-----------------------------------------------------------------	
	// 	Roster table settings section instructions	
	//	
	function mstw_tr_roster_table_inst( ) {
		//mstw_log_msg( 'mstw_tr_roster_table_inst:' );
		GLOBAL $TEXT_DOMAIN;
		
		echo '<p>' . __( 'These settings will apply to all the [mstw-tr-roster] shortcode tables, overriding the settings defaults. In most cases, these settings can be overridden by shortcode arguments.', $TEXT_DOMAIN ) .'</p>';
		
	} //End: mstw_tr_roster_table_inst()

	function mstw_tr_roster_table_2_inst( ) {
		//mstw_log_msg( 'mstw_tr_roster_table_2_inst:' );	
		GLOBAL $TEXT_DOMAIN;
		
		echo '<p>' . __( 'These settings will apply to all the [mstw-tr-roster-2] shortcode tables, overriding the settings defaults. In most cases, these settings can be overridden by shortcode arguments.', $TEXT_DOMAIN ) .'</p>';
		
	} //End: mstw_tr_roster_table_2_inst()