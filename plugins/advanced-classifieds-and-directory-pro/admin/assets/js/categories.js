(function( $ ) {
	'use strict';	

	const resetImageField = () => {
		document.querySelector( '#acadp-categories-image-id' ).value = '';
		document.querySelector( '#acadp-categories-image-wrapper' ).innerHTML = '';					
		
		document.querySelector( '#acadp-button-categories-remove-image' ).hidden = true;
		document.querySelector( '#acadp-button-categories-upload-image' ).hidden = false;
	}

	/**
	 * Called when the page has loaded.
	 */
	$(function() {
		
		// Upload Image.
		document.querySelector( '#acadp-button-categories-upload-image' ).addEventListener( 'click', ( event ) => { 
            event.preventDefault(); 

            ACADPMediaUploader(( json ) => {
				document.querySelector( '#acadp-categories-image-id' ).value = json.id;
				document.querySelector( '#acadp-categories-image-wrapper' ).innerHTML = '<img src="' + json.url + '" alt="" />';
				
				document.querySelector( '#acadp-button-categories-upload-image' ).hidden = true;
				document.querySelector( '#acadp-button-categories-remove-image' ).hidden = false;
			}); 
        });
		
		// Delete Image.	
		document.querySelector( '#acadp-button-categories-remove-image' ).addEventListener( 'click', ( event ) => {														 
            event.preventDefault();
			
			const id = parseInt( document.querySelector( '#acadp-categories-image-id' ).value );			
			if ( id > 0 ) {				
				let data = {
					'action': 'acadp_delete_attachment',
					'attachment_id': id,
					'security': acadp_admin.ajax_nonce
				}
				
				resetImageField();

				$.post( ajaxurl, data, function( response ) {
					// console.log( response );
				});				
			};			
		});
		
		// Reset Image Field.
		$( document ).ajaxComplete(function( event, xhr, settings ) {			
			if ( document.querySelector( '#acadp-categories-image-id' ) !== null ) {				
				const queryStringArr = settings.data.split( '&' );
			   
				if ( $.inArray( 'action=add-tag', queryStringArr ) !== -1 ) {
					const response = $( xhr.responseXML ).find( 'term_id' ).text();
					if ( '' != response ) {
						resetImageField();
					}
				};			
			};			
		});	
		
	});

})( jQuery );
