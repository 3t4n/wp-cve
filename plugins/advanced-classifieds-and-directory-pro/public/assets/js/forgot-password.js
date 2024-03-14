'use strict';

(function( $ ) {

	/**
	 * Called when the page has loaded.
	 */
	$(function() {	

		const formEl = document.querySelector( '#acadp-forgot-password-form' );
		let formSubmitted = false;

		if ( formEl !== null ) {
			// Form Validation.
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/validate.js' ).then(() => {			
				ACADPInitForm( '#acadp-forgot-password-form' );

				formEl.addEventListener( 'submit', ( event ) => {					
					if ( formSubmitted ) {
						return false;
					}

					formSubmitted = true;

					// The isFormValid boolean respresents all inputs that can
					// be validated with the Constraint Validation API.
					let isFormValid = ACADPCheckValidity( formEl );

					// Prevent form submission if any of the validation checks fail.
					if ( ! isFormValid ) {
						event.preventDefault();
						formSubmitted = false;
					}
					
					// Set the focus to the first invalid input.
					const firstInvalidInputEl = formEl.querySelector( '.is-invalid' );										
					if ( firstInvalidInputEl !== null ) {
						$( 'html, body' ).animate({
							scrollTop: $( firstInvalidInputEl ).offset().top - 50
						}, 500 );				
					}
				});
			});
		}

	});

})( jQuery );
