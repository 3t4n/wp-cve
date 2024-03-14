'use strict';

(function( $ ) {

	/**
     * Initialize ReCaptcha.
	 */
	const initReCaptcha = () => {
		// Contact form.
		if ( document.querySelector( '#acadp-contact-form-control-recaptcha' ) !== null ) {			
			if ( acadp.recaptcha_contact > 0 ) {
				acadp.recaptchas['contact'] = grecaptcha.render( 'acadp-contact-form-control-recaptcha', {
					'sitekey': acadp.recaptcha_site_key
				});
			}		
		} else {			
			acadp.recaptcha_contact = 0;			
		}

		// Report form.
		if ( document.querySelector( '#acadp-report-abuse-form-control-recaptcha' ) !== null ) {			
			if ( acadp.recaptcha_report_abuse > 0 ) {
				acadp.recaptchas['report_abuse'] = grecaptcha.render( 'acadp-report-abuse-form-control-recaptcha', {
					'sitekey': acadp.recaptcha_site_key
				});
			}		
		} else {			
			acadp.recaptcha_report_abuse = 0;			
		}
	}

	/**
     * Init video.
	 */
	const initVideo = () => {
		document.querySelectorAll( '.acadp-iframe-video' ).forEach(( el ) => {
			el.setAttribute( 'src', el.dataset.src );
		});
	}

	/**
     * Toggle favourites.
	 */
	const toggleFavourites = ( buttonEl ) => {		
		buttonEl.querySelector( 'svg' ).classList.add( 'acadp-animate-spin' );
		buttonEl.disabled = true;

		let data = {
			'action': 'acadp_public_add_remove_favorites',
			'post_id': parseInt( acadp.post_id ),
			'security': acadp.ajax_nonce
		}
		
		$.post( acadp.ajax_url, data, function( response ) {
			document.querySelector( '.acadp-button-add-to-favourites' ).classList.toggle( 'acadp-hidden' );
			document.querySelector( '.acadp-button-remove-from-favourites' ).classList.toggle( 'acadp-hidden' );	
			
			buttonEl.querySelector( 'svg' ).classList.remove( 'acadp-animate-spin' );
			buttonEl.disabled = false;
		});
	}
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {			
		
		// Slick slider
		if ( $.fn.slick ) {			
			let $carousel = $( '.acadp-slider-for' ).slick({
				rtl: ( parseInt( acadp.is_rtl ) ? true : false ),
  				asNavFor: '.acadp-slider-nav',
				arrows: false,
  				fade: true,
				slidesToShow: 1,
  				slidesToScroll: 1,
				adaptiveHeight: true
			});

			// Magnific popup
			if ( $.fn.magnificPopup ) { 
				$carousel.magnificPopup({
					type: 'image',
					delegate: 'div:not(.slick-cloned) img',
					gallery: {
						enabled: true
					},
					callbacks: {
						elementParse: function( item ) {
							item.src = item.el.attr( 'src' );
						},
						open: function() {
							const current = $carousel.slick( 'slickCurrentSlide' );
							$carousel.magnificPopup( 'goTo', current );
						},
						beforeClose: function() {
							$carousel.slick( 'slickGoTo', parseInt( this.index ) );
						}
					}
				});
			}
		
			// Slick
			$( '.acadp-slider-nav' ).slick({
				rtl: ( parseInt( acadp.is_rtl ) ? true : false ),
				asNavFor: '.acadp-slider-for',
				nextArrow: '<div class="acadp-slider-next"><span aria-hidden="true">&#10095;</span></div>',
				prevArrow: '<div class="acadp-slider-prev"><span aria-hidden="true">&#10094;</span></div>',
  				focusOnSelect: true,
				slidesToShow: 5,
				slidesToScroll: 1,
				infinite: false,
				responsive: [{
					breakpoint: 1024,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 1,
					}
				}, {
					breakpoint: 600,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 1
					}
				}]
			});		
		}

		// Magnific popup
		if ( $.fn.magnificPopup ) {		
			$( '.acadp-image-popup' ).magnificPopup({
				type: 'image',
				mainClass: 'acadp'
			}); 
		}
		
		// Init ReCaptcha.
		if ( window.isACADPReCaptchaLoaded ) {
			initReCaptcha();
		} else {
			document.addEventListener( 'acadp.recaptcha.loaded', initReCaptcha );
		}

		// Init map.
		if ( acadp.map_service === 'osm' ) {
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/openstreetmap.js' );
		} else {
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/googlemap.js' );
		}

		// Init video.
		if ( acadp.show_cookie_consent ) {
			document.addEventListener( 'acadp.cookie.consent', initVideo );
		} else {
			initVideo();
		}	

		// Request login.
		document.querySelectorAll( '.acadp-button-require-login' ).forEach(( buttonEl ) => {
			buttonEl.addEventListener( 'click', () => {	
				alert( acadp.i18n.alert_required_login );			 
			});	
		});

	   	// Toggle favourites.
		const addToFavouritesEl = document.querySelector( '.acadp-button-add-to-favourites' );
		const removeFromFavouritesEl = document.querySelector( '.acadp-button-remove-from-favourites' );

		if ( addToFavouritesEl !== null ) {
			addToFavouritesEl.addEventListener( 'click', ( event ) => {
				toggleFavourites( event.target );											   
			});
		}
		
		if ( removeFromFavouritesEl !== null ) {
			removeFromFavouritesEl.addEventListener( 'click', ( event ) => {
				toggleFavourites( event.target );											   
			});
		}
		
		// Form Validation.
		ACADPLoadScript( acadp.plugin_url + 'public/assets/js/validate.js' ).then(() => {
			// Report form
			const reportFormEl = document.querySelector( '#acadp-report-abuse-form' );

			if ( reportFormEl !== null ) {
				ACADPInitForm( '#acadp-report-abuse-form' );

				// Handle form submit validation via JS instead.
				reportFormEl.addEventListener( 'submit', ( event ) => {
					event.preventDefault();

					// Get the form element that was submitted.
					const formEl = event.target;

					// Reset error fields
					formEl.querySelector( '.acadp-form-status' ).innerHTML = '';

					// The isFormValid boolean respresents all inputs that can
					// be validated with the Constraint Validation API.
					let isFormValid = ACADPCheckValidity( formEl );
				
					// Fields that cannot be validated with the Constraint Validation API need
					// to be validated manually.
					let recaptchaResponse = null;
					if ( acadp.recaptcha_report_abuse > 0 ) {	
						recaptchaResponse = grecaptcha.getResponse( acadp.recaptchas['report_abuse'] );
			
						if ( recaptchaResponse.length == 0 ) {
							formEl.querySelector( '#acadp-report-abuse-form-control-recaptcha' ).classList.add( 'is-invalid' );

							formEl.querySelector( '#acadp-report-abuse-form-error-recaptcha' ).innerHTML = acadp.i18n.invalid_recaptcha;
							formEl.querySelector( '#acadp-report-abuse-form-error-recaptcha' ).hidden = false;

							grecaptcha.reset( acadp.recaptchas['report_abuse'] );		
							isFormValid = false;
						} else {
							formEl.querySelector( '#acadp-report-abuse-form-control-recaptcha' ).classList.remove( 'is-invalid' );

							formEl.querySelector( '#acadp-report-abuse-form-error-recaptcha' ).innerHTML = '';
							formEl.querySelector( '#acadp-report-abuse-form-error-recaptcha' ).hidden = true;
						}		
					}

					// Prevent form submission if any of the validation checks fail.
					if ( ! isFormValid ) {
						return false;
					}

					// Validation success. Post via AJAX.
					const submitButtonEl = formEl.querySelector( '.acadp-button-submit' );					
					
					let spinnerEl = document.createElement( 'div' );
					spinnerEl.className = 'acadp-spinner';

					submitButtonEl.prepend( spinnerEl );
					submitButtonEl.disabled = true;
							
					let data = {
						'action': 'acadp_public_report_abuse',
						'post_id': parseInt( acadp.post_id ),
						'message': formEl.querySelector( '#acadp-report-abuse-form-control-message' ).value,
						'g-recaptcha-response': recaptchaResponse,
						'security': acadp.ajax_nonce
					}

					const dateEl = formEl.querySelector( '.acadp-date-field' );
					if ( dateEl !== null ) {
						data.date = dateEl.querySelector( 'input' ).value;
					}

					const magicFieldEl = formEl.querySelector( '.acadp-magic-field' );
					if ( magicFieldEl !== null ) {
						const fieldName = magicFieldEl.querySelector( 'input' ).name;
						data[ fieldName ] = magicFieldEl.querySelector( 'input' ).value;
					}

					$.post( acadp.ajax_url, data, function( response ) {
						if ( 1 == response.error ) {
							formEl.querySelector( '.acadp-form-status' ).innerHTML = '<div class="acadp-text-error">' + response.message + '</div>';
						} else {
							formEl.querySelector( '#acadp-report-abuse-form-control-message' ).innerHTML = '';
							formEl.querySelector( '.acadp-form-status' ).innerHTML = '<div class="acadp-text-success">' + response.message + '</div>';
						}
				
						if ( acadp.recaptcha_report_abuse > 0 ) {
							grecaptcha.reset( acadp.recaptchas['report_abuse'] );
						}					
						
						submitButtonEl.querySelector( '.acadp-spinner' ).remove();
						submitButtonEl.disabled = false;
					}, 'json' );																			  
				});	
			}	

			// Contact form
			const contactFormEl = document.querySelector( '#acadp-contact-form' );

			if ( contactFormEl !== null ) {			
				ACADPInitForm( '#acadp-contact-form' );

				// Handle form submit validation via JS instead.
				contactFormEl.addEventListener( 'submit', ( event ) => {
					event.preventDefault();

					// Get the form element that was submitted.
					const formEl = event.target;	
					
					// Reset error fields
					formEl.querySelector( '.acadp-form-status' ).innerHTML = '';

					// The isFormValid boolean respresents all inputs that can
					// be validated with the Constraint Validation API.
					let isFormValid = ACADPCheckValidity( formEl );
				
					let recaptchaResponse = null;
					if ( acadp.recaptcha_contact > 0 ) {	
						recaptchaResponse = grecaptcha.getResponse( acadp.recaptchas['contact'] );
			
						if ( recaptchaResponse.length == 0 ) {
							formEl.querySelector( '#acadp-contact-form-control-recaptcha' ).classList.add( 'is-invalid' );

							formEl.querySelector( '#acadp-contact-form-error-recaptcha' ).innerHTML = acadp.i18n.invalid_recaptcha;
							formEl.querySelector( '#acadp-contact-form-error-recaptcha' ).hidden = false;

							grecaptcha.reset( acadp.recaptchas['contact'] );		
							isFormValid = false;
						} else {
							formEl.querySelector( '#acadp-contact-form-control-recaptcha' ).classList.remove( 'is-invalid' );

							formEl.querySelector( '#acadp-contact-form-error-recaptcha' ).innerHTML = '';
							formEl.querySelector( '#acadp-contact-form-error-recaptcha' ).hidden = true;
						}			
					}

					// Prevent form submission if any of the validation checks fail.
					if ( ! isFormValid ) {					
						// Set the focus to the first invalid input.
						const firstInvalidInputEl = formEl.querySelector( '.is-invalid' );
						if ( firstInvalidInputEl !== null ) {
							$( 'html, body' ).animate({
								scrollTop: $( firstInvalidInputEl ).offset().top - 50
							}, 500 );				
						}	

						return false;
					}
					
					// Validation success. Post via AJAX.
					const submitButtonEl = formEl.querySelector( '.acadp-button-submit' );	
					
					let spinnerEl = document.createElement( 'div' );
					spinnerEl.className = 'acadp-spinner';

					submitButtonEl.prepend( spinnerEl );					
					submitButtonEl.disabled = true;

					let data = {
						'action': 'acadp_public_send_contact_email',
						'post_id': parseInt( acadp.post_id ),
						'name': formEl.querySelector( '#acadp-contact-form-control-name' ).value,
						'email': formEl.querySelector( '#acadp-contact-form-control-email' ).value,
						'message': formEl.querySelector( '#acadp-contact-form-control-message' ).value,
						'g-recaptcha-response': recaptchaResponse,
						'security': acadp.ajax_nonce
					}

					const phoneEl = formEl.querySelector( '#acadp-contact-form-control-phone' );
					if ( phoneEl !== null ) {
						data.phone = phoneEl.value;
					}

					const copyEl = formEl.querySelector( '#acadp-contact-form-control-send_copy' );
					if ( copyEl !== null ) {
						data.send_copy = copyEl.checked ? 1 : 0;
					}

					const dateEl = formEl.querySelector( '.acadp-date-field' );
					if ( dateEl !== null ) {
						data.date = dateEl.querySelector( 'input' ).value;
					}

					const magicFieldEl = formEl.querySelector( '.acadp-magic-field' );
					if ( magicFieldEl !== null ) {
						const fieldName = magicFieldEl.querySelector( 'input' ).name;
						data[ fieldName ] = magicFieldEl.querySelector( 'input' ).value;
					}

					$.post( acadp.ajax_url, data, function( response ) {
						if ( 1 == response.error ) {
							formEl.querySelector( '.acadp-form-status' ).innerHTML = '<div class="acadp-text-error">' + response.message + '</div>';
						} else {
							formEl.querySelector( '#acadp-contact-form-control-message' ).innerHTML = '';
							formEl.querySelector( '.acadp-form-status' ).innerHTML = '<div class="acadp-text-success">' + response.message + '</div>';
						}
				
						if ( acadp.recaptcha_contact > 0 ) {
							grecaptcha.reset( acadp.recaptchas['contact'] );
						}						
						
						submitButtonEl.querySelector( '.acadp-spinner' ).remove();
						submitButtonEl.disabled = false;
					}, 'json' );
				});					
			}
		});		
		
		// Show phone number.
		const phoneNumberEl = document.querySelector( '.acadp-link-show-phone-number' );

		if ( phoneNumberEl !== null ) {
			phoneNumberEl.addEventListener( 'click', ( event ) => {
				event.target.style.display = 'none';
				document.querySelector( '.acadp-phone-number' ).style.display = '';
			});	
		}	

	});

})( jQuery );
  