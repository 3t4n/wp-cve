( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Init.
		var offset = 300,
			offset_opacity = 1200,
			scroll_top_duration = 700,
			$back_to_top = $( '.youzify-scrolltotop' );

		// Hide or Show the "back to top" link.
		$( window ).scroll( function() {

			// show/hide scroll button.
			( $( this ).scrollTop() > offset ) ?
			$back_to_top.addClass( 'youzify-is-visible') :
			$back_to_top.removeClass( 'youzify-is-visible youzify-fade-out' );

			// if user on the top of page disappear.
			if ( $(this).scrollTop() > offset_opacity ) {
				$back_to_top.addClass( 'youzify-fade-out' );
			}

		});

		// Smooth scroll to top.
		$back_to_top.on( 'click', function( e ) {
			e.preventDefault();
			$( 'body,html' ).animate( { scrollTop: 0 }, scroll_top_duration	);
		});

	});

})( jQuery );