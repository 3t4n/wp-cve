/*----------------------------------------------------------------------------
 * tr-manage-teams.js
 * JavaScript manage teams screen (mstw-tr-team-tax-admin-class.php)
 * when a SS or LM team is selected, update the new team name & slug
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
	// when the SS team control is changed, update the team name
	//	and slug fields, and reset the LM team control
	//
	$( '#ss_team_link' ).change( function( event ) {
		//console.log( 'SS team changed ... id= ' + event.target.id );
		//console.log( 'value= ' + event.target.value );
		
		var selected_text = $( "#ss_team_link option:selected" ).text( );
		//console.log( "selected text= " + selected_text );
		
		var selected_value = $( "#ss_team_link option:selected" ).val( );
		//console.log( "selected value= " + selected_value );
		
		if ( $("#tag-name") .length ) {
			$("#tag-name").val( selected_text );
			$("#tag-slug").val( selected_value );
		}
		
		$("#lm_team_link").val( -1 );
	
	});
	
	//
	// when the LM team control is changed, update the team name
	//	and slug fields, and reset the SS team control
	//
	$( '#lm_team_link' ).change( function( event ) {
		//console.log( 'LM team changed ... id= ' + event.target.id );
		//console.log( 'value= ' + event.target.value );
		
		var selected_text = $( "#lm_team_link option:selected" ).text( );
		//console.log( "selected text= " + selected_text );
		
		var selected_value = $( "#lm_team_link option:selected" ).val( );
		//console.log( "selected value= " + selected_value );
		
		if ( $("#tag-name") .length ) {
			$("#tag-name").val( selected_text );
			$("#tag-slug").val( selected_value );
		}
		
		$("#ss_team_link").val( -1 );
	
	});

	//
	//  Display the progress bar while processing the Update Roster request
	//
	/*
	$( '.tr-action-button' ).click( function( event ) {
		//alert( 'tr-manage-rosters.js tourney changed ... id= ' + event.target.id );
		//console.log( "submit pressed" );
		
		$('#roster-progress').show(  );
		//$('#edit-teams').hide( );
		//delay( 3000 );
		
		
		
	});
	*/
});