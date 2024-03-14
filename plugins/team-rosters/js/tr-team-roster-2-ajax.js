// tr-team-roster-2-ajax.js
// JavaScript to sort team roster - mstw-tr-roster-2 shortcode (mstw-tr-roster-tables-class.php)
//

jQuery(document).ready( function( $ ) {
	
	$('.tr-sort-submit').click( handleSortButtonClick );
	
});

function handleSortButtonClick( ) {
	var tableID    = event.target.id;
	var rosterTeam = event.target.name;
	
	//consoleLogMsg( 'handleSortButtonClick event.target.id (tableID): ' + tableID );
	//consoleLogMsg( 'handleSortButtonClick event.target.name (rosterTeam): ' + rosterTeam );
	
	var argsStr = document.getElementById('args_' + tableID ).value;
	var e = document.getElementById("tr-sort-menu_" + tableID );
	var sortValue = e.value;

	var data = {
		  'action'        : 'sort_roster', //same for all
		  'real_action'   : 'sort_roster',
		  'team'          : rosterTeam, 
		  'table_id'      : tableID,
		  'args_str'      : argsStr,
		  'sort_value'	  : sortValue
		  };
		  
	jQuery.post( mstw_tr_sort_roster_2_ajax.ajaxurl, data, function( response ) {
		
		var object = jQuery.parseJSON( response );
		
		if ( '' != object.error && window.console ) {
				console.log( object.error );

		} else if ( object.hasOwnProperty( 'html') && object.html ) {
			
			var replaceElement = "#mstw-tr-roster-players-" + object.table_id;
			
			jQuery( "#mstw-tr-roster-players-" + object.table_id ).replaceWith( object.html );
			
		}
		
	});
	
} //End: function handleSortButtonClick( )

function consoleLogMsg( msg ) {
	if (window.console) {
		console.log( msg );
	}
} //End: function consoleLogMsg( )
	