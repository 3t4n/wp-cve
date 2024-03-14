<?php
/*
Plugin Name:  Conditional Tags Shortcode
Version:      0.2
Author:       Hassan Derakhshandeh
Description:  A shortcode to display content based on context.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

function conditional_tags_shortcode( $atts, $content ) {

	foreach ( $atts as $key => $value ) {
		/* normalize empty attributes */
		if ( is_int( $key ) ) {
			$key = $value;
			$value = true;
		}

		$reverse_logic = false;
		if ( substr( $key, 0, 4 ) == 'not_' ) {
			$reverse_logic = true;
			$key = substr( $key, 4 );
		}

		// the conditional tag parameters
		$values = ( true === $value ) ? null : array_filter( explode( ',', $value ) );

		// check the condition
		if ( preg_match( '/has_term_(.*)/', $key, $matches ) ) {
			$result = has_term( $values, $matches[1] );
		} elseif ( function_exists( $key ) ) {
			$result = call_user_func( $key, $values );
		}

		if ( ! isset( $result ) )
			return '';
		if ( $result !== $reverse_logic ) {
			return do_shortcode( $content );
		}
	}

	return '';
}
add_shortcode( 'if', 'conditional_tags_shortcode' );