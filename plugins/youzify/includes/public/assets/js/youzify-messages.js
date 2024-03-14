( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Open Files Uploader
		 */
		var youzify_load_attachments = false;

		$( document ).on( 'click', '.youzify-upload-btn', function( e ) {

			// Load Attachments JS.
			if ( ! youzify_load_attachments ) {
				$( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-attachments.min.js' } ).appendTo( 'head' );
				$( '<link/>', { rel: 'stylesheet', href: Youzify.assets + 'css/youzify-attachments.min.css' } ).appendTo( 'head' );
				youzify_load_attachments = true;
			}

			var $form = $( this ).closest( 'form' );

			if ( $form.find( '.youzify-attachment-item' )[0] ) {
				return false;
			}

			$form.find( '.youzify-upload-attachments' ).click();

		    e.preventDefault();

		});

		/**
		 * Load Emojis JS.
		 */
    	$( document ).on( 'click', '.youzify-load-emojis', function() {
        	var form = $( this ).closest( 'form' );
	        $( this ).find( 'i' ).attr(  'class', 'fas fa-spin fa-spinner' );
        	$( this ).addClass( 'loading' );
	        $( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-emojionearea.min.js' } ).appendTo( 'head' );
	        $( '<link/>', { rel: 'stylesheet', href: Youzify.assets + 'css/youzify-emojionearea.min.css' } ).appendTo( 'head' );
	    });

	});


})( jQuery );