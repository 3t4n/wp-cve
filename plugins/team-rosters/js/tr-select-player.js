/*----------------------------------------------------------------------
 * tr-select-player: updates player in select player dropdown on the
 *		single player page templater
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2022-23 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 *
 *---------------------------------------------------------------------*/
 
 jQuery(document).ready( function( ) {
	//console.log( "tr-select-player:" );
	
	jQuery( '#player-select' ) . change ( function( event ) {
		var old_player = jQuery( '#current-player' ).attr( 'value' );
		var new_player = event.target.value;
		
		jQuery( '#current-player' ).attr( 'value', new_player );
		
		var action = jQuery( '#single-player-profile' ).attr( 'action' );
		
		action = action.replace( old_player, new_player );
		
		jQuery( '#single-player-profile' ).attr( 'action', action );
		
	} );
	
});