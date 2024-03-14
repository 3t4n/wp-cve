/*----------------------------------------------------------------------
 * tr-sort-roster-table: sets the css when team colors have been selected
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2020-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
	
	jQuery( 'th' ).click( function( ) {
		var col = jQuery(this).parent().children().index(jQuery(this));
		sortTable( this );
		
	} );
	
} );

function sortTable( clicked ) {
  var n, numeric, tableID, table, rows; 
	var dir, switching, shouldSwitch, switchcount = 0;
	var i, x, y, x1, y1;
	
  n = jQuery( clicked ).parent( ).children( ).index( jQuery( clicked ));

  numeric = false;

  if ( jQuery( clicked ).attr( "id" ) == "nbr" ) {
		numeric = true;
  }

	//
	// FIRST GET THE TABLE ID THEN THE TABLE
	//
  tableID = jQuery( clicked ).closest('table').attr('id');
	table = document.getElementById( tableID );
	
  //Set the sorting direction to ascending:
  dir = "asc"; 
	switching = true;
	
  /*
  *  Make a loop that will continue until
  *  no switching has been done:
  */
  while( switching ) {
		//start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*
		 * Loop through all table rows (except the
     * first, which contains table headers):
		 */
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      // Get the two elements you want to compare,
      // one from current row and one from the next:
			// we will compare the text but switch the dom elements if necessary
			// so we need two variables
			x = rows[i].getElementsByTagName("TD")[n];
			x1 = x.textContent;
			y = rows[i + 1].getElementsByTagName("TD")[n];
			y1 = y.textContent;
			if ( numeric ) {
				x1 = Number( x1 );
				x1 = isNaN( x1) ? 0 : x1;
				y1 = Number( y1 );
				y1 = isNaN( y1 ) ? 0 : y1;
				
			}
				
			// check if the two rows should switch place,
			// based on the direction, asc or desc:
			if (dir == "asc") {
				if ( x1 > y1 ) {
					shouldSwitch = true;
					break;
				} 
					
			} else { // if (dir == "desc") {
				if ( x1 < y1 ) {
					shouldSwitch = true;
					break;
				} 
								
			} //End: if ( dir == "asc" ) else ( dir == "desc" )
				
		} //End: for
		
		if ( shouldSwitch ) {
			// If a switch has been marked, make the switch
			// and mark that a switch has been done:
			rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			switching = true;
			//Each time a switch is done, increase this count by 1:
			switchcount ++; 
			
		} else {
			// If no switching has been done AND the direction is "asc",
			// set the direction to "desc" and run the while loop again.
			if (switchcount == 0 && dir == "asc") {
				dir = "desc";
				switching = true;
			
			}
		
		} //End: if ( shouldSwitch ) else
		
  } //End: while
	
	jQuery( "table." + tableID + " th" ).each( function( index ) {
		var header = jQuery( this );

		if ( index == n ) {
			header.removeClass( );
			header.addClass( "sorted" );
			header.addClass( dir );
			
		} else if ( header.hasClass( 'sortable' ) ||  header.hasClass( 'sorted' ) ) {
			header.removeClass( );
			header.addClass( "sortable" );
			header.addClass( "desc" );
			
		}
		
	} );
	
} //End: sortTable( )
 