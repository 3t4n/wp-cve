(function ( $ ) {
	"use strict";

	$(function () {

		// Duck out if the comment image options aren't visible
		if ( 0 === $( '#disable_comment_images_reloaded' ).length ) {
			return;
		}

		// Setup an event handler so we can notify the user whether or not the file type is valid
		$( '#comment_image_reloaded_toggle' ).on( 'click', function () {

			if ( confirm( cm_imgs.toggleConfirm ) ) {

				$( this).attr( 'disabled', 'disabled' );
				$( '#comment_image_reloaded_source' ).val( 'button' );
				$( '#publish' ).trigger( 'click' );

			}

		});

	});



})( jQuery );


