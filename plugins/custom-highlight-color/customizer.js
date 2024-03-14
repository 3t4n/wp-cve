/**
 * Customizer preview scripts.
 *
 * Contains handlers to make Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	wp.customize( 'custom_highlight_color', function( value ) {
		value.bind( function( to ) {
			// Update custom color CSS
			var style = $( '#custom-highlight-color' ),
			    color = style.data( 'color' ),
			    css = style.html();
			//css = css.replace( color, to );
			css = css.split( color ).join( to ); // css.replaceAll.
			style.html( css )
			     .data( 'color', to );
		} );
	} );
} )( jQuery );
