(function( $ ) {
	'use strict';	

	const toggleFields = () => {
		const type = document.querySelector( '#acadp-form-control-type' ).value;

		document.querySelectorAll( '.acadp-conditional-fields' ).forEach(( el ) => {
			if ( el.classList.contains( 'acadp-field-type-' + type ) ) {
				el.hidden = false;
			} else {
				el.hidden = true;
			}
		});
	}

	/**
	 * Called when the page has loaded.
	 */
	$(function() {

		const fieldsEl = document.querySelector( '#acadp-field-details' );

		if ( fieldsEl !== null ) {
			// Toggle Fields.
			fieldsEl.querySelector( '#acadp-form-control-type' ).addEventListener( 'change', ( event ) => {	
				toggleFields();
			});	
			
			toggleFields();
		}
		
	});
	
})( jQuery );
