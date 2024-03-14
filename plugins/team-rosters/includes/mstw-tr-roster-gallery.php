<?php
/*---------------------------------------------------------------------------
 *	mstw-tr-roster-gallery.php
 *		Code for the mstw-roster-gallery shortcode
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
// Add the gallery shortcode handler, which will create the a Team Gallery on the user side.
// Handles the shortcode parameters, if there were any, 
// then calls mstw_tr_build_gallery( ) to create the output
// --------------------------------------------------------------------------------------

 if( !function_exists( 'mstw_tr_roster_gallery_handler' ) ) {
	function mstw_tr_roster_gallery_handler( $atts ) {	
		//mstw_log_msg( 'mstw_tr_roster_gallery_handler: ' );
		//mstw_log_msg( 'player gallery shortcode arguments=' );
		//mstw_log_msg( $atts );
		
		//
		// the roster type comes from the shortcode args; defaults to 'custom'
		//
		if ( array_key_exists( 'roster_type', $atts ) ) {
			$roster_type = ( mstw_tr_is_valid_roster_type( $atts['roster_type'] ) ) 
							 ? $atts['roster_type'] : 'custom';
		} else {
			$roster_type = 'custom';
		}
		
		//
		// the team comes from the shortcode args; must be provided
		//
		if ( array_key_exists( 'team', $atts ) ) {
			$team = $atts['team'];
		} else {
			return '<h3>No team specified in shortcode.</h3>';
		}
		
		// get the options set in the admin screen
		$options = get_option( 'mstw_tr_options' );
		
		// and merge them with the defaults
		$args = wp_parse_args( $options, mstw_tr_get_defaults( ) );
		
		// then merge the parameters passed to the shortcode 
		$attribs = shortcode_atts( $args, $atts );
		
		// if a specific roster_type is specified, it takes priority over all
		// including the other shortcode args
		if( 'custom' != $roster_type ) {
			$fields = mstw_tr_get_fields_by_roster_type( $roster_type );
			$attribs = wp_parse_args( $fields, $attribs );
		}
		return mstw_tr_build_gallery( $team, $roster_type, $attribs );
	}
 }
?>