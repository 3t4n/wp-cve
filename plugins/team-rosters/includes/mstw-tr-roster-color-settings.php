<?php
/*----------------------------------------------------------------------------
 * mstw-tr-roster-color-settings.php
 *	All functions for the MSTW Team Rosters Plugin's roster table [shortcode] color settings.
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
		

	function mstw_tr_roster_colors_setup( ) {
		//mstw_log_msg( 'mstw_tr_roster_colors_setup:' );
		mstw_tr_table_colors_section_setup( );
		mstw_tr_table2_colors_section_setup( );

	} //End: mstw_tr_roster_colors_setup()
	
	
	//-----------------------------------------------------------------	
	// 	Table color controls section	
	//
	function mstw_tr_table_colors_section_setup( ) {
		//mstw_log_msg( 'mstw_tr_table_colors_section_setup:' );
	
		global $TEXT_DOMAIN;
			
		// Roster Table data fields/columns -- show/hide and labels
		$display_on_page = 'mstw-tr-roster-colors';
		$section_id  = 'mstw_tr_table_color_settings';
		$instruct_callback = 'mstw_tr_table_color_inst';
		$section_title = __( 'Roster Table Color Settings', $TEXT_DOMAIN );

		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );

		add_settings_section(
			$section_id,
			$section_title,
			$instruct_callback,
			$display_on_page
			);
			
		$arguments = array( 
			array( 	// USE TEAM COLORS
				'type' => 'checkbox', 
				'id' => 'use_team_colors',
				'name'	=> 'mstw_tr_options[use_team_colors]',
				'value' => mstw_tr_safe_ref( $options, 'use_team_colors', 0 ), 
				'title' => __( 'Use Team Colors:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
				'desc' => __( 'Use the colors from the Teams table in MSTW Schedules & Scoreboards. IGNORED if the S&S Teams DB (v4.0+)is not found. Note: There is a separate setting for player profiles and galleries.', $TEXT_DOMAIN ),
			),
			array( 	// ROSTER TITLE COLOR
				'type' => 'text', 
				'id' => 'table_title_color',
				'name'	=> 'mstw_tr_options[table_title_color]',
				'value' => mstw_tr_safe_ref( $options, 'table_title_color' ), 
				'title' => __( 'Roster Table Title:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// ROSTER TABLE LINKS COLOR
				'type' => 'text', 
				'id' => 'table_links_color',
				'name'	=> 'mstw_tr_options[table_links_color]',
				'value' => mstw_tr_safe_ref( $options, 'table_links_color' ), 
				'title' => __( 'Roster Table Links:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// TABLE HEADER BACKGROUND
				'type' => 'text', 
				'id' => 'table_head_bkgd',
				'name'	=> 'mstw_tr_options[table_head_bkgd]',
				'value' => mstw_tr_safe_ref( $options, 'table_head_bkgd' ), 
				'title' => __( 'Table Header Background:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// TABLE HEADER TEXT
				'type' => 'text', 
				'id' => 'table_head_text',
				'name'	=> 'mstw_tr_options[table_head_text]',
				'value' => mstw_tr_safe_ref( $options, 'table_head_text' ), 
				'title' => __( 'Table Header Text:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// EVEN ROW BACKGROUND
				'type' => 'text', 
				'id' => 'table_even_row_bkgd',
				'name'	=> 'mstw_tr_options[table_even_row_bkgd]',
				'value' => mstw_tr_safe_ref( $options, 'table_even_row_bkgd' ), 
				'title' => __( 'Even Row Background:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// EVEN ROW TEXT
				'type' => 'text', 
				'id' => 'table_even_row_text',
				'name'	=> 'mstw_tr_options[table_even_row_text]',
				'value' => mstw_tr_safe_ref( $options, 'table_even_row_text' ), 
				'title' => __( 'Even Row Text:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// ODD ROW BACKGROUND
				'type' => 'text', 
				'id' => 'table_odd_row_bkgd',
				'name'	=> 'mstw_tr_options[table_odd_row_bkgd]',
				'value' => mstw_tr_safe_ref( $options, 'table_odd_row_bkgd' ), 
				'title' => __( 'Odd Row Background:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// ODD ROW TEXT
				'type' => 'text', 
				'id' => 'table_odd_row_text',
				'name'	=> 'mstw_tr_options[table_odd_row_text]',
				'value' => mstw_tr_safe_ref( $options, 'table_odd_row_text' ), 
				'title' => __( 'Odd Row Text:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// TABLE BORDERS
				'type' => 'text', 
				'id' => 'table_border_color',
				'name'	=> 'mstw_tr_options[table_border_color]',
				'value' => mstw_tr_safe_ref( $options, 'table_border_color' ), 
				'title' => __( 'Table Borders:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
		);
		
		mstw_tr_build_settings_screen( $arguments );
			
	} //End: mstw_tr_table_colors_section_setup
	
	
	//-----------------------------------------------------------------	
	// 	Table2 color controls section	
	//
	function mstw_tr_table2_colors_section_setup( ) {
		//mstw_log_msg( 'mstw_tr_roster2_colors_section_setup:' );
	
		global $TEXT_DOMAIN;
		
		// Roster Table data fields/columns -- show/hide and labels
		$display_on_page = 'mstw-tr-roster2-colors';
		$section_id  = 'mstw_tr_table2_color_settings';
		$instruct_callback = 'mstw_tr_table2_color_inst';
		$section_title = __( 'Roster Table 2 Color Settings', $TEXT_DOMAIN );

		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );

		add_settings_section(
			$section_id,
			$section_title,
			$instruct_callback,
			$display_on_page
			);
			
		$arguments = array( 
			/*array( 	// USE TEAM COLORS
				'type' => 'checkbox', 
				'id' => 'use_team_colors',
				'name'	=> 'mstw_tr_options[use_team_colors]',
				'value' => mstw_tr_safe_ref( $options, 'use_team_colors', 0 ), 
				'title' => __( 'Use Team Colors:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
				'desc' => __( 'Use the colors from the Teams table in MSTW Schedules & Scoreboards. IGNORED if the S&S Teams DB (v4.0+)is not found. Note: There is a separate setting for player profiles and galleries.', $TEXT_DOMAIN ),
			),
			*/
			array( 	// ROSTER TITLE COLOR
				'type' => 'text', 
				'id' => 'table2_title_color',
				'name'	=> 'mstw_tr_options[table2_title_color]',
				'value' => mstw_tr_safe_ref( $options, 'table2_title_color' ), 
				'title' => __( 'Roster 2 Table Title:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			
			array( 	// ROSTER TABLE LINKS COLOR
				'type' => 'text', 
				'id' => 'table2_links_color',
				'name'	=> 'mstw_tr_options[table2_links_color]',
				'value' => mstw_tr_safe_ref( $options, 'table2_links_color' ), 
				'title' => __( 'Roster Table Links:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			
			array( 	// JERSEY NUMBER BLOCK BACKGROUND
				'type' => 'text', 
				'id' => 'table2_jersey_bkgd',
				'name'	=> 'mstw_tr_options[table2_jersey_bkgd]',
				'value' => mstw_tr_safe_ref( $options, 'table2_jersey_bkgd' ), 
				'title' => __( 'Jersey Number Background:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
		
			array( 	// JERSEY NUMBER BLOCK TEXT
				'type' => 'text', 
				'id' => 'table2_jersey_text',
				'name'	=> 'mstw_tr_options[table2_jersey_text]',
				'value' => mstw_tr_safe_ref( $options, 'table2_jersey_text' ), 
				'title' => __( 'Jersey Number Text:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			
			array( 	// EVEN ROW BACKGROUND
				'type' => 'text', 
				'id' => 'table2_even_row_bkgd',
				'name'	=> 'mstw_tr_options[table2_even_row_bkgd]',
				'value' => mstw_tr_safe_ref( $options, 'table2_even_row_bkgd' ), 
				'title' => __( 'Even Row Background:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// EVEN ROW TEXT
				'type' => 'text', 
				'id' => 'table2_even_row_text',
				'name'	=> 'mstw_tr_options[table2_even_row_text]',
				'value' => mstw_tr_safe_ref( $options, 'table2_even_row_text' ), 
				'title' => __( 'Even Row Text:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			
			array( 	// ODD ROW BACKGROUND
				'type' => 'text', 
				'id' => 'table2_odd_row_bkgd',
				'name'	=> 'mstw_tr_options[table2_odd_row_bkgd]',
				'value' => mstw_tr_safe_ref( $options, 'table2_odd_row_bkgd' ), 
				'title' => __( 'Odd Row Background:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			array( 	// ODD ROW TEXT
				'type' => 'text', 
				'id' => 'table2_odd_row_text',
				'name'	=> 'mstw_tr_options[table2_odd_row_text]',
				'value' => mstw_tr_safe_ref( $options, 'table2_odd_row_text' ), 
				'title' => __( 'Odd Row Text:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			/*
			//Table2 has no borders
			//
			array( 	// TABLE BORDERS
				'type' => 'text', 
				'id' => 'table_border_color',
				'name'	=> 'mstw_tr_options[table_border_color]',
				'value' => mstw_tr_safe_ref( $options, 'table_border_color' ), 
				'title' => __( 'Table Borders:', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $section_id,
			),
			*/
		);
		
		mstw_tr_build_settings_screen( $arguments );
			
	} //End: mstw_tr_roster2_colors_section_setup( )


	//-----------------------------------------------------------------	
	// 	Roster table colors section instructions	
	//	

	function mstw_tr_table_color_inst( ) {
		//mstw_log_msg( 'mstw_tr_table_color_inst:' );
		
		global $TEXT_DOMAIN;
		
		echo '<p>' . __( 'These settings will apply to ALL the roster tables [mstw-roster-table], overriding the default styles. However they can be overridden by more specific stylesheet rules for specific teams. See the plugin documentation for more details.', $TEXT_DOMAIN ) . '</p>';
	
	} //End: mstw_tr_table_color_inst()
	
	//-----------------------------------------------------------------	
	// 	Roster table colors section instructions	
	//	

	function mstw_tr_table2_color_inst( ) {
		//mstw_log_msg( 'mstw_tr_table2_color_inst:' );
		
		global $TEXT_DOMAIN;
		
		echo '<p>' . __( 'These settings will apply to ALL the table 2 roster tables [mstw-tr-roster-2], overriding the default styles. However they can be overridden by more specific stylesheet rules for specific teams. See the plugin documentation for more details. NOTE: this shortcode does not support team colors and does not have borders.', $TEXT_DOMAIN ) . '</p>';
	
	} //End: mstw_tr_table2_color_inst()

?>