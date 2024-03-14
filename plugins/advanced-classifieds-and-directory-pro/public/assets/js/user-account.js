'use strict';

(function( $ ) {

	// Toggle password fields.
	const togglePasswordFields = () => {
		const formEl = document.querySelector( '#acadp-user-account-form' );
		const togglePasswordEl = formEl.querySelector( '#acadp-form-control-change_password' );

		const isChecked = togglePasswordEl.checked;

		formEl.querySelectorAll( '.acadp-form-group-password' ).forEach(( el ) => {
			el.hidden = ! isChecked;
			el.querySelector( 'input[type=password]' ).disabled = ! isChecked;
		});	
	}

	/**
	 * Called when the page has loaded.
	 */
	$(function() {	

		const formEl = document.querySelector( '#acadp-user-account-form' );
		let formSubmitted = false;

		if ( formEl !== null ) {
			// Toggle password fields.
			formEl.querySelector( '#acadp-form-control-change_password' ).addEventListener( 'change', ( event ) => {
				togglePasswordFields( event );			
			});

			// Form Validation.
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/validate.js' ).then(() => {
				ACADPInitForm( '#acadp-user-account-form' );

				// Handle form submit validation via JS instead.
				formEl.addEventListener( 'submit', ( event ) => {
					if ( formSubmitted ) {
						return false;
					}
			
					formSubmitted = true;
			
					// The isFormValid boolean respresents all inputs that can
					// be validated with the Constraint Validation API.
					let isFormValid = ACADPCheckValidity( formEl );
				
					// Fields that cannot be validated with the Constraint Validation API need
					// to be validated manually.
					const isPasswordValid = ACADPValidatePassword( formEl );
					if ( ! isPasswordValid ) {
						isFormValid = isPasswordValid;
					}
			
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
				})
			});
		}

	});

})( jQuery );
