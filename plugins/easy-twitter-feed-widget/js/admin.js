/*
 * WSNB Admin v1.0
 * DesignOrbital.com
 *
 * Copyright (c) 2013-2014 DesignOrbital.com
 *
 * License: GNU General Public License v2 or later
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 */

( function( $ ) {

	/** Document Ready */
	$( document ).ready( function() {

		// Easytabs
		$( '#do-etfw-tabs' ).easytabs({
			defaultTab: ( $.cookie( 'do-etfw-tab' ) != 'undefined' )? $.cookie( 'do-etfw-tab' ) : 'li#tab-2',
			updateHash: false
		});
		$( '#do-etfw-tabs' ).bind( 'easytabs:after', function() {			
			$activeTab = $( '.do-etfw-tabs-container' ).find( 'li.active' );
			$.cookie( 'do-etfw-tab', 'li#' + $activeTab.attr( 'id' ), { path: '/' } );
		});
		
	} );

} )( jQuery );