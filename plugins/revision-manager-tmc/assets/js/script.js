jQuery( document ).ready( function( $ ){

	//	=================================
	//	CLICK TEMPLATE MINIATURE
	//	to replace wp_editor content

	$( "[data-template-url]:not(.disabled)" ).click( function( event ){

		var templateButton = $(this);

        event.preventDefault();

        templateButton.addClass( 'loading' );

        $.get( templateButton.attr( 'data-template-url' ), null, function( data ){

            templateButton.removeClass( 'loading' );

            var editorWrapper = $( '#wp-notifications_content__0-wrap' );

            if( editorWrapper ){

                if( editorWrapper.hasClass( 'tmce-active' ) ){

                    tinymce.get( 'notifications_content__0' ).setContent( data );

                }

                if( editorWrapper.hasClass( 'html-active' ) ) {

                    $( '#notifications_content__0' ).val( data );

                }

            }

        }, 'html' );

	} );

	//	==========================================
	//	QUICK MAIL TEST
	//	------------------------------------------


	$( "[data-quick-test-action]" ).prop( 'disabled', false ).click( function( event ){

		event.preventDefault(); //  Block sending form data

		var thisButton 		= $( this );
		var targetInput 	= $( thisButton.attr( 'data-target-input-id' ) );
		var titleInput 		= $( thisButton.attr( 'data-title-input-id' ) );
		var contentInput 	= $( thisButton.attr( 'data-content-textarea-id' ) );
		var contentTinymce 	= $( thisButton.attr( 'data-content-tinymce-id' ) );

		var buttonDefHtml   = thisButton.html();
		var buttonLoadHtml  = buttonDefHtml + ' <div class="spinner is-active" style="margin-right: 0;"></div>';

		thisButton.html( buttonLoadHtml ).prop( 'disabled', true );

		var data = {
			'action': 	    thisButton.attr( 'data-quick-test-action' ),
			'emailTarget': 	targetInput.val(),
			'emailSubject': titleInput.val(),
			'emailContent': contentTinymce.hasClass( 'tmce-active' ) ? tinyMCE.activeEditor.getContent() : contentInput.val()
		};

		jQuery.post( ajaxurl, data, function( response ) {

		    console.log( response );

			thisButton.html( buttonDefHtml ).prop( 'disabled', false );

		});

	});

});