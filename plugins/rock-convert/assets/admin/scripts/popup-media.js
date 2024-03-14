jQuery( document ).ready( function ( $ ) {
	window.onload = function () {
		var txts = document.getElementsByTagName( 'TEXTAREA' );

		for ( var i = 0, l = txts.length; i < l; i++ ) {
			if ( /^[0-9]+$/.test( txts[ i ].getAttribute( 'maxlength' ) ) ) {
				var func = function () {
					var len = parseInt( this.getAttribute( 'maxlength' ), 10 );

					if ( this.value.length > len ) {
						this.value = this.value.substr( 0, len );
						return false;
					}
				};

				txts[ i ].onkeyup = func;
				txts[ i ].onblur = func;
			}
		}
	};

	setInterval( () => {
		$( '.convert-popup-box' ).css(
			'background-color',
			$( '#rock_convert_popup_color' ).val()
		);
		$( '.convert-popup-close' ).css(
			'color',
			$( '#rock_convert_popup_button_close_color' ).val()
		);
		$( '.convert-popup-btn' ).css(
			'background-color',
			$( '#rock_convert_popup_button_color' ).val()
		);
		$( '.convert-popup-btn' ).css(
			'color',
			$( '#rock_convert_popup_button_text_color' ).val()
		);
		$( '.popup-preview-title' ).css(
			'color',
			$( '#rock_convert_popup_title_color' ).val()
		);
		$( '.popup-preview-descricao' ).css(
			'color',
			$( '#rock_convert_popup_description_color' ).val()
		);
		$( '.popup-preview-descricao-ni' ).css(
			'color',
			$( '#rock_convert_popup_description_color' ).val()
		);
		let checkbox = document.getElementById(
			'rock_convert_popup_image_activate'
		);
		if ( checkbox != null && checkbox.checked ) {
			$( '.convert-popup' ).css( 'display', 'block' );
			$( '.convert-popup-ni' ).css( 'display', 'none' );
		} else {
			$( '.convert-popup' ).css( 'display', 'none' );
			$( '.convert-popup-ni' ).css( 'display', 'block' );
		}
	}, 100 );

	//commands to change preview text and color in real time
	var str = $( '#rock_convert_popup_title' ).val();
	$( '.popup-preview-title' ).text( str );
	str = $( '#rock_convert_popup_descricao' ).val();
	$( '.popup-preview-descricao' ).text( str );
	str = $( '#rock_convert_popup_descricao-ni' ).val();
	$( '.popup-preview-descricao-ni' ).text( str );

	$( '#rock_convert_popup_title' ).on( 'input', function () {
		$( '.popup-preview-title' ).text( $( this ).val() );
	} );
	$( '#rock_convert_popup_descricao' ).on( 'input', function () {
		$( '.popup-preview-descricao' ).text( $( this ).val() );
	} );
	$( '#rock_convert_popup_descricao-ni' ).on( 'input', function () {
		$( '.popup-preview-descricao-ni' ).text( $( this ).val() );
	} );

	jQuery( 'input#convert_popup_media' ).click( function ( e ) {
		e.preventDefault();
		var image_frame;
		if ( image_frame ) {
			image_frame.open();
		}
		// Define image_frame as wp.media object
		image_frame = wp.media( {
			title: 'Select Media',
			multiple: false,
			library: {
				type: 'image',
			},
		} );

		image_frame.on( 'close', function () {
			// On close, get selections and save to the hidden input
			// plus other AJAX stuff to refresh the image preview
			var selection = image_frame.state().get( 'selection' );
			var attachment = image_frame
				.state()
				.get( 'selection' )
				.first()
				.toJSON();
			var gallery_ids = new Array();
			var my_index = 0;
			selection.each( function ( attachment ) {
				gallery_ids[ my_index ] = attachment[ 'id' ];
				my_index++;
			} );
			var ids = gallery_ids.join( ',' );
			jQuery( 'input#rock_convert_popup_image' ).val( ids );
			$( '#rock_convert_popup_image_preview' )
				.attr( 'src', attachment.url )
				.css( 'width', '300px' );
			$( '#rock_convert_popup_image_preview' ).css( 'height', '400px' );
			$( '.popup-content' ).css( 'display', 'none' );
			$( '.popup-content-preview' ).css( 'display', 'flex' );
			$( '.popup-content-preview' ).css( 'flex-direction', 'column' );
			$( '.popup-content-select-image-hide' ).css( 'display', 'none' );
		} );

		image_frame.on( 'open', function () {
			// On open, get the id from the hidden input
			// and select the appropiate images in the media manager
			var selection = image_frame.state().get( 'selection' );
			var ids = jQuery( 'input#rock_convert_popup_image' )
				.val()
				.split( ',' );
			ids.forEach( function ( id ) {
				var attachment = wp.media.attachment( id );
				attachment.fetch();
				selection.add( attachment ? [ attachment ] : [] );
			} );
		} );

		image_frame.open();
	} );
} );
