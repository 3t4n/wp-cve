/*----------------------------------------------------------------------------
 * tr-load-teams.js
 * JavaScript to remove the annoying team selection in the "Load Team From:"
 * HTML form added to the Manage Teams admin screen. This is a minor annoyance
 * with no functional implications. So this script can be eliminated without
 * changing the plugin's performance.
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2017-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *--------------------------------------------------------------------------*/

jQuery(document).ready( function( $ ) {
	
	$( '#submit' ).click( function( event ) {
		//console.log( 'submit.click' );
		$("#ss_team_link").val( -1 );
		$("#lm_team_link").val( -1 );
		
	});

});