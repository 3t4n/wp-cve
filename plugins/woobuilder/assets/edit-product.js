/**
 * Created by shramee on 13/12/16.
 */
jQuery( function( $ ) {
	$( '.wp-editor-tabs' ).append(
		$( '<a/>' )
			.attr( 'href', wcProductBuilderLiveEditLink )
			.addClass( 'button pootle' )
			.html( 'Product Builder' )
	);
} );