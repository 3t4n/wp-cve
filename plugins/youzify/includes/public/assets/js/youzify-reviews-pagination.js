( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Get Page Number
		function find_page_number( el ) {
			el.find( '.youzify-page-symbole' ).remove();
			return parseInt( el.text() );
		}

		// Get Comments Page
		$( document ).on( 'click', '.youzify-reviews-nav-links a', function( e ) {

			e.preventDefault();

			var main_content = $( this ).closest( '.youzify-user-reviews' );

            $( 'html, body' ).animate( {
                scrollTop: main_content.offset().top - 150
            }, 1000 );

			$.ajax( {
				url: Youzify.ajax_url,
				type: 'post',
				data: {
					action: 'youzify_reviews_pagination',
					user_id: Youzify.displayed_user_id,
					base:  $( this ).closest( '.youzify-pagination' ).attr( 'data-base' ),
					page: find_page_number( $( this ).clone() ),
					per_page: $( this ).closest( '.youzify-pagination' ).attr( 'data-per-page' )
				},
				beforeSend: function() {
					main_content.remove();
					$( document ).scrollTop();
					$( '#youzify-main-reviews .youzify-loading' ).show();
				},
				success: function( html ) {
					$( '#youzify-main-reviews .youzify-loading' ).hide();
					$( '#youzify-main-reviews' ).append( html );
				}
			})

		});

	});

})( jQuery );