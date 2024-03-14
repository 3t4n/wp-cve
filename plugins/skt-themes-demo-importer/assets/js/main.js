jQuery( function ( $ ) {
	'use strict';

	$( '.js-skt-import-data' ).on( 'click', function () {

		// Reset response div content.
		$( '.js-skt-ajax-response' ).empty();

		// Prepare data for the AJAX call
		var data = new FormData();
		data.append( 'action', 'SKT_import_demo_data' );
		data.append( 'security', skt.ajax_nonce );
		data.append( 'selected', $( '#SKT__demo-import-files' ).val() );
		if ( $('#SKT__content-file-upload').length ) {
			data.append( 'content_file', $('#SKT__content-file-upload')[0].files[0] );
		}
		if ( $('#SKT__widget-file-upload').length ) {
			data.append( 'widget_file', $('#SKT__widget-file-upload')[0].files[0] );
		}
		if ( $('#SKT__customizer-file-upload').length ) {
			data.append( 'customizer_file', $('#SKT__customizer-file-upload')[0].files[0] );
		}

		// AJAX call to import everything (content, widgets, before/after setup)
		ajaxCall( data );

	});

	function ajaxCall( data ) {
		$.ajax({
			method:     'POST',
			url:        skt.ajax_url,
			data:       data,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$( '.js-skt-ajax-loader' ).show();
			}
		})
		.done( function( response ) {

			if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
				ajaxCall( data );
			}
			else if ( 'undefined' !== typeof response.message ) {
				$( '.js-skt-ajax-response' ).append( '<p>' + response.message + '</p>' );
				$( '.js-skt-ajax-loader' ).hide();
			}
			else {
				$( '.js-skt-ajax-response' ).append( '<div class="notice-error  is-dismissible"><p>' + response + '</p></div>' );
				$( '.js-skt-ajax-loader' ).hide();
			}
		})
		.fail( function( error ) {
			$( '.js-skt-ajax-response' ).append( '<div class="notice-error  is-dismissible"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>' );
			$( '.js-skt-ajax-loader' ).hide();
		});
	}

	// Switch preview images on select change event, but only if the img element .js-skt-preview-image exists.
	// Also switch the import notice (if it exists).
	$( '#SKT__demo-import-files' ).on( 'change', function(){
		if ( $( '.js-skt-preview-image' ).length ) {

			// Attempt to change the image, else display message for missing image.
			var currentFilePreviewImage = skt.import_files[ this.value ]['import_preview_image_url'] || '';
			$( '.js-skt-preview-image' ).prop( 'src', currentFilePreviewImage );
			$( '.js-skt-preview-image-message' ).html( '' );

			if ( '' === currentFilePreviewImage ) {
				$( '.js-skt-preview-image-message' ).html( skt.texts.missing_preview_image );
			}
		}

		// Update import notice.
		var currentImportNotice = skt.import_files[ this.value ]['import_notice'] || '';
		$( '.js-skt-themes-demo-import-notice' ).html( currentImportNotice );
	});

});
