/*----------------------------------------------------------------------------
 * tr-color-settings.js
 *  Enables the color picker for the color settings screen
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2017-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *--------------------------------------------------------------------------*/

//Set up the color pickers to work with our text input field
jQuery(document).ready(function($){
	//
	//Roster Table Colors
	//
	$('#sp_main_text_color').wpColorPicker();
	
	$('#table_links_color').wpColorPicker();
	
	$('#table_title_color').wpColorPicker();
	
	$('#table_head_bkgd').wpColorPicker();
	$('#table_head_text').wpColorPicker();
	
    $('#table_even_row_bkgd').wpColorPicker();
	$('#table_even_row_text').wpColorPicker();
	
	$('#table_odd_row_bkgd').wpColorPicker();
	$('#table_odd_row_text').wpColorPicker();
	
	$('#table_border_color').wpColorPicker();
	
	//
	//Roster Table2 Colors
	//
	$('#table2_links_color').wpColorPicker();
	
	$('#table2_title_color').wpColorPicker();
	
    $('#table2_even_row_bkgd').wpColorPicker();
	$('#table2_even_row_text').wpColorPicker();
	
	$('#table2_jersey_bkgd').wpColorPicker();
	$('#table2_jersey_text').wpColorPicker();
	
	$('#table_even_row_bkgd').wpColorPicker();
	$('#table_even_row_text').wpColorPicker();
	
	$('#table2_odd_row_bkgd').wpColorPicker();
	$('#table2_odd_row_text').wpColorPicker();
	
	//
	//Player Profile & Team Gallery Colors
	//
	$('#sp_main_bkgd_color').wpColorPicker();
	
	$('#sp_bio_border_color').wpColorPicker();
	$('#sp_bio_header_color').wpColorPicker();
	$('#sp_bio_bkgd_color').wpColorPicker();
	$('#sp_bio_text_color').wpColorPicker();
	
	$('#gallery_links_color').wpColorPicker();
	
});