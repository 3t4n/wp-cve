'use strict';

(function( $ ) {

	/**
     * Init reCaptcha.
	 */
	const initReCaptcha = () => {
		if ( document.querySelector( '#acadp-form-control-recaptcha' ) !== null ) {			
			if ( acadp.recaptcha_registration > 0 ) {
				acadp.recaptchas['registration'] = grecaptcha.render( 'acadp-form-control-recaptcha', {
					'sitekey': acadp.recaptcha_site_key
				});
			}	
		} else {			
			acadp.recaptcha_registration = 0;			
		}
	}

	/**
	 * Called when the page has loaded.
	 */
	$(function() {	

		const formEl = document.querySelector( '#acadp-register-form' );
		let formSubmitted = false;

		if ( formEl !== null ) {
			// ReCaptcha
			if ( window.isACADPReCaptchaLoaded ) {
				initReCaptcha();			
			} else {
				document.addEventListener( 'acadp.recaptcha.loaded', initReCaptcha );
			}	

			// Form Validation
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/validate.js' ).then(() => {
				ACADPInitForm( '#acadp-register-form' );

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
			
					let recaptchaResponse = null;
					if ( acadp.recaptcha_registration > 0 ) {	
						recaptchaResponse = grecaptcha.getResponse( acadp.recaptchas['registration'] );
			
						if ( recaptchaResponse.length == 0 ) {
							document.querySelector( '#acadp-form-control-recaptcha' ).classList.add( 'is-invalid' );
			
							document.querySelector( '#acadp-form-error-recaptcha' ).innerHTML = acadp.i18n.invalid_recaptcha;
							document.querySelector( '#acadp-form-error-recaptcha' ).hidden = false;
			
							grecaptcha.reset( acadp.recaptchas['registration'] );		
							isFormValid = false;
						} else {
							formEl.querySelector( '#acadp-form-control-recaptcha' ).classList.remove( 'is-invalid' );
			
							formEl.querySelector( '#acadp-form-error-recaptcha' ).innerHTML = '';
							formEl.querySelector( '#acadp-form-error-recaptcha' ).hidden = true;
						}		
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
				});
			});
		}

	});

})( jQuery );
