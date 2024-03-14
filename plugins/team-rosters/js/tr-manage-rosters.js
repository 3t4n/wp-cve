/*----------------------------------------------------------------------------
 * tr-manage-rosters.js
 * JavaScript manage rosters screen (mstw-tr-team-roster-admin-class.php)
 * when the team control is changed, update the current_team (WP option)
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2017-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *--------------------------------------------------------------------------*/

jQuery(document).ready( function( $ ) {
	//alert( "Doc is ready" );
	//console.log( "Doc is ready" );
	
	//
	// when the team control is changed, update the current_team (WP option) 
	//
	$( '#current-team' ).change( function( event ) {
		//alert( 'tr-manage-rosters.js tourney changed ... id= ' + event.target.id );
		//console.log( 'tr-manage-rosters.js tourney changed ... id= ' + event.target.id );
		//alert( 'tourney: ' + this.value );
		
		var data = {
			  'action'        : 'team_rosters', //same for all
			  'real_action'   : 'change_current_team',
			  'page'          : 'manage_rosters',
			  'current_team'  : event.target.value
			  };
			  
		jQuery.post( ajaxurl, data, function( response ) {
			//alert( 'Got this from the server: ' + response );
			var object = jQuery.parseJSON( response );
			
			if ( '' != object.error ) {
				alert( object.error );
			}
			else {
				//alert( "Current team updated" );
			}
			
		});
	
	});
	
	//
	//  Display the progress bar while processing the Update Roster request
	//
	/*
	$( '.tr-action-button' ).click( function( event ) {
		//alert( 'tr-manage-rosters.js tourney changed ... id= ' + event.target.id );
		console.log( "submit pressed" );
		
		$('#roster-progress').show(  );
		//$('#edit-teams').hide( );
		//delay ( 3000 );
		$('html, body').animate({
        scrollTop: $("#roster-progress").offset().top
    }, 2000);
	
	});
	*/

});