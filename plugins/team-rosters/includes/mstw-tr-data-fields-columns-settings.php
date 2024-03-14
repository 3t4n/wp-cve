<?php
/*----------------------------------------------------------------------------
 * mstw-tr-data-fields-columns-settings.php
 *	All functions for the MSTW Team Rosters Plugin's data fields & columns settings.
 *	Loaded by mstw-tr-settings.php 
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-23 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
// 	This first function is here for potential future page re-org	
//		
	function mstw_tr_data_fields_setup( ) {
		//mstw_tr_log_msg( 'mstw_tr_data_fields_setup:' );
		
		mstw_tr_data_fields_left_setup( );
		mstw_tr_data_fields_center_setup( );
		mstw_tr_data_fields_right_setup( );
		
	}
	
	function mstw_tr_data_fields_left_setup( ) {
		//mstw_tr_log_msg( 'mstw_tr_data_fields_left_setup:' );	
		
		global $TEXT_DOMAIN;
		
		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		
		// Roster Table data fields/columns -- show/hide and labels
		
		$display_on_page   = 'mstw-tr-data-fields-labels';
		$page_section      = 'mstw-tr-fields-labels';
		$instruct_callback = null; //'mstw_tr_data_fields_inst';
		$section_title     = __( 'Data Fields Labels', $TEXT_DOMAIN );
		
		add_settings_section( $page_section, 
													$section_title, 
													$instruct_callback, 
													$display_on_page );

		$arguments = array(
			
			array( 	// Player PHOTO LABEL
				'type' => 'text', 
				'id' => 'photo_label',
				'name'	=> 'mstw_tr_options[photo_label]',
				'value' => mstw_tr_safe_ref( $options, 'photo_label' ), 
				'title' => __( 'Photo Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Photo)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// NAME column label
				'type' => 'text', 
				'id' => 'name_label',
				'name'	=> 'mstw_tr_options[name_label]',
				'value' => mstw_tr_safe_ref( $options, 'name_label' ), 
				'title' => __( 'Name Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Name)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// NUMBER column label
				'type' => 'text', 
				'id' => 'number_label',
				'name'	=> 'mstw_tr_options[number_label]',
				'value' => mstw_tr_safe_ref( $options, 'number_label' ),
				'title'	=> __( 'Number Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Nbr)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// POSITION column label
				'type' => 'text', 
				'id' => 'position_label',
				'name'	=> 'mstw_tr_options[position_label]',
				'value' => mstw_tr_safe_ref( $options, 'position_label' ),
				'title'	=> __( 'Position Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Pos)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// HEIGHT column label
				'type' => 'text', 
				'id' => 'height_label',
				'name'	=> 'mstw_tr_options[height_label]',
				'value' => mstw_tr_safe_ref( $options, 'height_label' ),
				'title'	=> __( 'Height Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Ht)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// WEIGHT column label
				'type' => 'text', 
				'id' => 'weight_label',
				'name'	=> 'mstw_tr_options[weight_label]',
				'value' => mstw_tr_safe_ref( $options, 'weight_label' ),
				'title'	=> __( 'Weight Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Wt)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
		
			array( 	// YEAR column label
				'type' => 'text', 
				'id' => 'year_label',
				'name'	=> 'mstw_tr_options[year_label]',
				'value' => mstw_tr_safe_ref( $options, 'year_label' ),
				'title'	=> __( 'Year Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Year)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// EXPERIENCE column label
				'type' => 'text', 
				'id' => 'experience_label',
				'name'	=> 'mstw_tr_options[experience_label]',
				'value' => mstw_tr_safe_ref( $options, 'experience_label' ),
				'title'	=> __( 'Experience Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Exp)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// AGE column label
				'type' => 'text', 
				'id' => 'age_label',
				'name'	=> 'mstw_tr_options[age_label]',
				'value' => mstw_tr_safe_ref( $options, 'age_label' ),
				'title'	=> __( 'Age Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Age)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// HOME TOWN column label
				'type' => 'text', 
				'id' => 'home_town_label',
				'name'	=> 'mstw_tr_options[home_town_label]',
				'value' => mstw_tr_safe_ref( $options, 'home_town_label' ),
				'title'	=> __( 'Home Town Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Home Town)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// LAST SCHOOL column label
				'type' => 'text', 
				'id' => 'last_school_label',
				'name'	=> 'mstw_tr_options[last_school_label]',
				'value' => mstw_tr_safe_ref( $options, 'last_school_label' ),
				'title'	=> __( 'Last School Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Last School)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// COUNTRY column label
				'type' => 'text', 
				'id' => 'country_label',
				'name'	=> 'mstw_tr_options[country_label]',
				'value' => mstw_tr_safe_ref( $options, 'country_label' ),
				'title'	=> __( 'Country Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Country)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// BATS/THROWS column label
				'type' => 'text', 
				'id' => 'bats_throws_label',
				'name'	=> 'mstw_tr_options[bats_throws_label]',
				'value' => mstw_tr_safe_ref( $options, 'bats_throws_label' ),
				'title'	=> __( 'Bats/Throws Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Bat/Thw)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// OTHER INFO column label
				'type' => 'text', 
				'id' => 'other_info_label',
				'name'	=> 'mstw_tr_options[other_info_label]',
				'value' => mstw_tr_safe_ref( $options, 'other_info_label' ),
				'title'	=> __( 'Other Info Label:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Other)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
		);
		
		mstw_tr_build_settings_screen( $arguments );
		
	} //End: mstw_tr_data_fields_left_setup() 
	
	function mstw_tr_data_fields_center_setup( ) {
		//mstw_tr_log_msg( 'mstw_tr_data_fields_center_setup:' );	
		
		global $TEXT_DOMAIN;
		
		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		
		
		// Roster Table data fields/columns -- show/hide
		
		$display_on_page   = 'mstw-tr-fields-show-hide';
		$page_section      = 'mstw-tr-fields-show-hide';
		$instruct_callback = null; //'mstw_tr_data_fields_inst';
		$section_title     = __( 'Visibility', $TEXT_DOMAIN );
		
		add_settings_section( $page_section, 
													$section_title, 
													$instruct_callback, 
													$display_on_page );
		
		$arguments = array(
			array( 	// Show/hide player PHOTOS
				'type' => 'show-hide', 
				'id' => 'show_photos',
				'name'	=> 'mstw_tr_options[show_photos]',
				'value' => mstw_safe_get( 'show_photos', $options, 0 ), 
				'title'	=> null, // __( 'Show Player Photos:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide NAME column
				'type' => 'show-hide', 
				'id' => 'show_name',
				'name'	=> 'mstw_tr_options[show_name]',
				'value' => 1, //mstw_tr_safe_ref( $options, 'show_number' ), 
				'title' => null, // __( 'Show Name:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Always shown.)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide NUMBER column
				'type' => 'show-hide', 
				'id' => 'show_number',
				'name'	=> 'mstw_tr_options[show_number]',
				'value' => mstw_tr_safe_ref( $options, 'show_number' ), 
				'title' => null, // __( 'Show Number:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Show)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide POSITION column
				'type' => 'show-hide', 
				'id' => 'show_position',
				'name'	=> 'mstw_tr_options[show_position]',
				'value' => mstw_tr_safe_ref( $options, 'show_position' ), 
				'title' => null, //__( 'Show Position:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Show)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide HEIGHT column
				'type' => 'show-hide', 
				'id' => 'show_height',
				'name'	=> 'mstw_tr_options[show_height]',
				'value' => mstw_tr_safe_ref( $options, 'show_height' ), 
				'title'	=> null, // __( 'Show Height:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Show)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide WEIGHT column
				'type' => 'show-hide', 
				'id' => 'show_weight',
				'name'	=> 'mstw_tr_options[show_weight]',
				'value' => mstw_tr_safe_ref( $options, 'show_weight' ), 
				'title'	=> null, // __( 'Show Weight:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Show)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide YEAR column
				'type' => 'show-hide', 
				'id' => 'show_year',
				'name'	=> 'mstw_tr_options[show_year]',
				'value' => mstw_tr_safe_ref( $options, 'show_year' ), 
				'title'	=> null, // __( 'Show Year:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide EXPERIENCE column
				'type' => 'show-hide', 
				'id' => 'show_experience',
				'name'	=> 'mstw_tr_options[show_experience]',
				'value' => mstw_tr_safe_ref( $options, 'show_experience' ), 
				'title'	=> null, // __( 'Show Experience:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide AGE column
				'type' => 'show-hide', 
				'id' => 'show_age',
				'name'	=> 'mstw_tr_options[show_age]',
				'value' => mstw_tr_safe_ref( $options, 'show_age' ), 
				'title'	=> null, // __( 'Show Age:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide HOME TOWN column
				'type' => 'show-hide', 
				'id' => 'show_home_town',
				'name'	=> 'mstw_tr_options[show_home_town]',
				'value' => mstw_tr_safe_ref( $options, 'show_home_town' ), 
				'title'	=> null, // __( 'Show Home Town:', $TEXT_DOMAIN ),
				'desc' =>	__( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide LAST SCHOOL column
				'type' => 'show-hide', 
				'id' => 'show_last_school',
				'name'	=> 'mstw_tr_options[show_last_school]',
				'value' => mstw_tr_safe_ref( $options, 'show_last_school' ), 
				'title'	=> null, // __( 'Show Last School:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide COUNTRY column
				'type' => 'show-hide', 
				'id' => 'show_country',
				'name'	=> 'mstw_tr_options[show_country]',
				'value' => mstw_tr_safe_ref( $options, 'show_country' ), 
				'title'	=> null, // __( 'Show Country:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide BATS/THROWS column
				'type' => 'show-hide', 
				'id' => 'show_bats_throws',
				'name'	=> 'mstw_tr_options[show_bats_throws]',
				'value' => mstw_tr_safe_ref( $options, 'show_bats_throws' ), 
				'title'	=> null, // __( 'Show Bats/Throws:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Show/hide OTHER INFO column
				'type' => 'show-hide', 
				'id' => 'show_other_info',
				'name'	=> 'mstw_tr_options[show_other_info]',
				'value' => mstw_tr_safe_ref( $options, 'show_other_info' ), 
				'title'	=> null, // __( 'Show Other Info:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: Hide)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
		);
		
		mstw_tr_build_settings_screen( $arguments );
		
	} //End: mstw_tr_data_fields_center_setup()

	function mstw_tr_data_fields_right_setup( ) {
		//mstw_tr_log_msg( 'mstw_tr_data_fields_right_setup:' );	
		
		global $TEXT_DOMAIN;
		
		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		
		
		// Roster Table data fields/columns -- order
		$display_on_page   = 'mstw-tr-fields-order';
		$page_section      = 'mstw-tr-fields-order';
		$instruct_callback = null; //'mstw_tr_data_fields_inst';
		$section_title     = __( 'Order', $TEXT_DOMAIN );
		
		add_settings_section( $page_section, 
													$section_title, 
													$instruct_callback, 
													$display_on_page );

		$arguments = array(
			array( 	//Order player PHOTOS
				'type' => 'text', 
				'id' => 'order_photo',
				'name'	=> 'mstw_tr_options[order_photo]',
				'value' => mstw_safe_get( 'order_photo', $options, 1 ), 
				'title'	=> null, // __( 'Show Player Photos:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 1)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order NAME column
				'type' => 'text', 
				'id' => 'order_name',
				'name'	=> 'mstw_tr_options[order_name]',
				'value' => mstw_safe_get( 'order_name', $options, 2 ), 
				'title' => null, // __( 'Show Name:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 2)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order NUMBER column
				'type' => 'text', 
				'id' => 'order_number',
				'name'	=> 'mstw_tr_options[order_number]',
				'value' => mstw_safe_get( 'order_number', $options, 3 ), 
				'title' => null, // __( 'Show Number:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 3)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order POSITION column
				'type' => 'text', 
				'id' => 'order_position',
				'name'	=> 'mstw_tr_options[order_position]',
				'value' => mstw_safe_get( 'order_position', $options, 4 ), 
				'title' => null, //__( 'Show Position:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 4)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order HEIGHT column
				'type' => 'text', 
				'id' => 'order_height',
				'name'	=> 'mstw_tr_options[order_height]',
				'value' => mstw_safe_get( 'order_height', $options, 5 ), 
				'title'	=> null, // __( 'Show Height:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 5)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order WEIGHT column
				'type' => 'text', 
				'id' => 'order_weight',
				'name'	=> 'mstw_tr_options[order_weight]',
				'value' => mstw_safe_get( 'order_weight', $options, 6 ), 
				'title'	=> null, // __( 'Show Weight:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 6)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order YEAR column
				'type' => 'text', 
				'id' => 'order_year',
				'name'	=> 'mstw_tr_options[order_year]',
				'value' => mstw_safe_get( 'order_year', $options, 7 ), 
				'title'	=> null, // __( 'Show Year:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 7)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order EXPERIENCE column
				'type' => 'text', 
				'id' => 'order_experience',
				'name'	=> 'mstw_tr_options[order_experience]',
				'value' => mstw_safe_get( 'order_experience', $options, 8 ), 
				'title'	=> null, // __( 'Show Experience:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 8)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order AGE column
				'type' => 'text', 
				'id' => 'order_age',
				'name'	=> 'mstw_tr_options[order_age]',
				'value' => mstw_safe_get( 'order_age', $options, 9 ),
				'title'	=> null, // __( 'Show Age:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 9)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order HOME TOWN column
				'type' => 'text', 
				'id' => 'order_home_town',
				'name'	=> 'mstw_tr_options[order_home_town]',
				'value' => mstw_safe_get( 'order_home_town', $options, 10 ),
				'title'	=> null, // __( 'Show Home Town:', $TEXT_DOMAIN ),
				'desc' =>	__( '(Default: 10)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order LAST SCHOOL column
				'type' => 'text', 
				'id' => 'order_last_school',
				'name'	=> 'mstw_tr_options[order_last_school]',
				'value' => mstw_safe_get( 'order_last_school', $options, 11 ),
				'title'	=> null, // __( 'Show Last School:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 11)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order COUNTRY column
				'type' => 'text', 
				'id' => 'order_country',
				'name'	=> 'mstw_tr_options[order_country]',
				'value' => mstw_safe_get( 'order_country', $options, 12 ), 
				'title'	=> null, // __( 'Show Country:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 12)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	//Order BATS/THROWS column
				'type' => 'text', 
				'id' => 'order_bats_throws',
				'name'	=> 'mstw_tr_options[order_bats_throws]',
				'value' => mstw_safe_get( 'order_bats_throws', $options, 13 ), 
				'title'	=> null, // __( 'Show Bats/Throws:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 13)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
			array( 	// Order OTHER INFO column
				'type' => 'text', 
				'id' => 'order_other_info',
				'name'	=> 'mstw_tr_options[order_other_info]',
				'value' => mstw_safe_get( 'order_other_info', $options, 14 ), 
				'title'	=> null, // __( 'Show Other Info:', $TEXT_DOMAIN ),
				'desc'	=> __( '(Default: 14)', $TEXT_DOMAIN ),
				'page' => $display_on_page,
				'section' => $page_section,
			),
			
		);
		
		mstw_tr_build_settings_screen( $arguments );
		
	} //End: mstw_tr_data_fields_right_setup()

	
	
//-----------------------------------------------------------------	
// 	Colors table section instructions	
//	
	if( !function_exists( 'mstw_tr_data_fields_inst' ) ) {
		function mstw_tr_data_fields_inst( ) {
			echo '<p>' . __( 'Field Labels. ', $TEXT_DOMAIN ) .'</p>';
		} //End: mstw_tr_data_fields_inst()
	}