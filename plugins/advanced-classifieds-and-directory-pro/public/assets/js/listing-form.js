'use strict';

(function( $ ) {	

	/**
     * Init reCaptcha.
	 */
	const initReCaptcha = () => {
		if ( document.querySelector( '#acadp-form-control-recaptcha' ) !== null ) {			
			if ( acadp.recaptcha_listing > 0 ) {
				acadp.recaptchas['listing'] = grecaptcha.render( 'acadp-form-control-recaptcha', {
					'sitekey': acadp.recaptcha_site_key
				});
			}		
		} else {			
			acadp.recaptcha_listing = 0;			
		}
	}

	/**
	 * Init Datetime Picker.
	 */
	const initDatetimePicker = ( el ) => {
		if ( typeof flatpickr === undefined ) {
			return false;
		}

		flatpickr.l10ns.default.rangeSeparator = ' ' + acadp.i18n.search_form_daterange_separator + ' ';

		let config = {
			allowInput: true
		}

		if ( el.classList.contains( 'acadp-has-daterange' ) ) {
			config.mode = 'range';
		}

		if ( el.classList.contains( 'acadp-form-control-datetime-picker' ) ) {
			config.enableTime    = true;				
			config.enableSeconds = true;
			config.time_24hr     = true;
		}

		flatpickr( el, config );
	}

	/**
	 * Init Range Slider.
	 */
	const initRangeSlider = ( el ) => {
		const rangeEl  = el.querySelector( '.acadp-range-input' );
		const bubbleEl = el.querySelector( '.acadp-range-value' );

		const min = parseInt( rangeEl.min );
		const max = parseInt( rangeEl.max );

		const updateRange = () => {
			let value = Number( ( rangeEl.value - min ) * 100 / ( max - min ) );
			let position = 10 - ( value * 0.2 );

			bubbleEl.innerHTML = '<span>' + rangeEl.value + '</span>';
			bubbleEl.style.left = 'calc(' + value + '% + (' + position + 'px))';
		}

		rangeEl.addEventListener( 'input', updateRange );
		updateRange();
	}

	/**
	 * Check if the user has permission to upload images.
     *
	 * @return {boolean} True if can upload images, false if not.
	 */
	const canUploadImage = () => {		
		const limit = maxImagesCount();
		const uploaded = numImagesUploaded();	
		
		if ( ( limit > 0 && uploaded >= limit ) || document.querySelector( '#acadp-images-upload-status' ).classList.contains( 'is-uploading' ) ) {
			return false;
		}
		
		return true;		
	}
	
	/**
	 * Get the maximum number of images the user can upload per listing.
     *
	 * @return {number} Number of images.
	 */
	const maxImagesCount = () => {		
		let limit = document.querySelector( '#acadp-button-upload-image' ).dataset.limit;

		if ( typeof limit !== undefined && limit !== false ) {
  			limit = parseInt( limit );
		} else {
			limit = parseInt( acadp.maximum_images_per_listing );
		}
		
		return limit;		
	}
	
	/**
	 * Get the number of images the user has uploaded to the current listing.
     *
	 *  @return {number} Number of images.
	 */
	const numImagesUploaded = () => {
		return document.querySelectorAll( '.acadp-image-field' ).length;		
	}
	
	/**
	 *  Enable or disable image upload button
	 */
	const toggleImageUploadBtn = () => {		
		document.querySelector( '#acadp-button-upload-image' ).disabled = ! canUploadImage();			
	}	

	/**
	 *  Make images sortable.
	 */
	const sortImages = () => {		
		if ( ! $.fn.sortable ) {
			return false;
		}

		const $el = $( '#acadp-images tbody' );

		if ( $el.hasClass( 'ui-sortable' ) ) {
			$el.sortable( 'destroy' );
		}
		
		$el.sortable({ handle: '.acadp-handle' });		
		$el.disableSelection();
	}

	/**
	 *  Enable or disable submit button
	 */
	const disableSubmitBtn = ( bool ) => {	
		const formEl = document.querySelector( '#acadp-listing-form' );

		formEl.querySelectorAll( '.acadp-button-group .acadp-button' ).forEach(( el ) => {
			el.disabled = bool;
		});			
	}
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {		

		const formEl = document.querySelector( '#acadp-listing-form' );
		
		// ReCaptcha
		if ( window.isACADPReCaptchaLoaded ) {
			initReCaptcha();			
		} else {
			document.addEventListener( 'acadp.recaptcha.loaded', initReCaptcha );
		}		
		
		// Init Datetime picker		
		formEl.querySelectorAll( '.acadp-form-control-date-picker' ).forEach(( el ) => {
			initDatetimePicker( el );
		});

		formEl.querySelectorAll( '.acadp-form-control-datetime-picker' ).forEach(( el ) => {
			initDatetimePicker( el );
		});

		// Init Range slider	
		formEl.querySelectorAll( '.acadp-form-control-range-slider' ).forEach(( el ) => {
			initRangeSlider( el );
		});		
		
		// Init Map
		if ( acadp.map_service === 'osm' ) {
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/openstreetmap.js' );
		} else {
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/googlemap.js' );
		}

		// Form Validation
		if ( formEl !== null ) {
			let formSubmitted = false;

			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/validate.js' ).then(() => {
				ACADPInitForm( '#acadp-listing-form' );

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
					formEl.querySelector( '#acadp-form-error-image' ).hidden = true;

					if ( acadp.is_image_required > 0 ) {
						const count = numImagesUploaded();			
						if ( count == 0 ) {
							formEl.querySelector( '#acadp-panel-images' ).classList.add( 'is-invalid' );
							isFormValid = false;
						}
					}
				 
					let recaptchaResponse = null;
					if ( acadp.recaptcha_listing > 0 ) {	
						recaptchaResponse = grecaptcha.getResponse( acadp.recaptchas['listing'] );
			
						if ( recaptchaResponse.length == 0 ) {
							formEl.querySelector( '#acadp-form-control-recaptcha' ).classList.add( 'is-invalid' );
			
							formEl.querySelector( '#acadp-form-error-recaptcha' ).innerHTML = acadp.i18n.invalid_recaptcha;
							formEl.querySelector( '#acadp-form-error-recaptcha' ).hidden = false;
			
							grecaptcha.reset( acadp.recaptchas['listing'] );		
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
		
		// Load custom fields.
		const categoryEl = formEl.querySelector( '#acadp-form-control-category' );

		if ( categoryEl !== null ) {
			categoryEl.addEventListener( 'acadp.terms.change', ( event ) => {
				const customFieldsEl = formEl.querySelector( '#acadp-custom-fields-listings' );
				let fields = {};

				customFieldsEl.innerHTML = '<div class="acadp-spinner"></div>';
				disableSubmitBtn( true );

				// Build fields input from cache.
				let cached = customFieldsEl.getAttribute( 'data-cache' );

				if ( cached ) {
					cached = JSON.parse( cached );
				}

				for ( const key in cached ) {
					if ( cached.hasOwnProperty( key ) ) {
						fields[ key ] = cached[ key ];
					}
				}
			
				// Build fields input from current visible form fields.
				const current = $( formEl ).serializeArray();

				$.each( current, function() {
					if ( this.name.indexOf( 'acadp_fields' ) !== -1 ) {
						fields[ this.name ] = this.value;
					}
				});

				// Cache the new fields data.
				customFieldsEl.setAttribute( 'data-cache', JSON.stringify( fields ) );

				// Build query input from the fields object.
				let query = [];

				for ( const key in fields ) {
					if ( fields.hasOwnProperty( key ) ) {
						query.push( encodeURIComponent( key ) + '=' + encodeURIComponent( fields[ key ] ) );
					}
				}

				query = query.join( '&' );				
				
				let data = {
					'action': 'acadp_public_custom_fields_listings',
					'post_id': customFieldsEl.dataset.post_id,
					'terms': event.target.value,
					'cached_meta': query,
					'security': acadp.ajax_nonce
				}
				
				$.post( acadp.ajax_url, data, function( response ) {
					customFieldsEl.innerHTML = response;

					customFieldsEl.querySelectorAll( '.acadp-form-control-date-picker' ).forEach(( el ) => {
						initDatetimePicker( el );
					});

					customFieldsEl.querySelectorAll( '.acadp-form-control-datetime-picker' ).forEach(( el ) => {
						initDatetimePicker( el );
					});

					customFieldsEl.querySelectorAll( '.acadp-form-control-range-slider' ).forEach(( el ) => {
						initRangeSlider( el );
					});
					
					disableSubmitBtn( false );
				});			
			});	
		}

		// Trigger the file uploader when "Upload Image" button is clicked.
		formEl.querySelector( '#acadp-button-upload-image' ).addEventListener( 'click', ( event ) => { 
			if ( ! canUploadImage() ) {
				return false;
			}

			$( '#acadp-form-control-image' ).trigger( 'click' );
        });
		
		// Upload image.
		document.querySelector( "#acadp-form-control-image" ).addEventListener( 'change', ( event ) => {			
			const selected = event.target.files.length;
			if ( ! selected ) {
				return false;
			}			
			
			const limit = maxImagesCount();
			const uploaded = numImagesUploaded();
			const remaining = limit - uploaded;

			if ( limit > 0 && selected > remaining ) {
				alert( acadp.i18n.alert_upload_limit.replace( /%d/gi, remaining ) );
				return false;
			}

			const imagesContainerEl = formEl.querySelector( '#acadp-panel-images' );
			const uploadButtonEl = formEl.querySelector( '#acadp-button-upload-image' );
			const erroEl = formEl.querySelector( '#acadp-form-error-image' );
		
			erroEl.innerHTML = '';
			erroEl.hidden = true;

			imagesContainerEl.classList.remove( 'is-invalid' );
			imagesContainerEl.classList.add( 'is-uploading' );								
					
			let spinnerEl = document.createElement( 'div' );
			spinnerEl.className = 'acadp-spinner';

			uploadButtonEl.prepend( spinnerEl );
			
			uploadButtonEl.disabled = true;
			disableSubmitBtn( true );
							
			let options = {
				dataType: 'json',
				url: acadp.ajax_url,
        		success: function( json, statusText, xhr, $form ) {
					// Do extra stuff after submit.
					imagesContainerEl.classList.remove( 'is-uploading' );
					uploadButtonEl.querySelector( '.acadp-spinner' ).remove();
					
					$.each( json, function( key, value ) {							
						if ( value['error'] ) {
							erroEl.innerHTML = value['message'];
							erroEl.hidden = false;
						} else {
							let html = '<tr class="acadp-image-row acadp-border-0 acadp-border-b acadp-bg-white">' + 
								'<td class="acadp-handle acadp-border-0 acadp-cursor-pointer acadp-p-2 acadp-w-[20px] acadp-align-middle acadp-text-center md:acadp-p-3">' + 
									'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-inline-block acadp-flex-shrink-0">' + 
										'<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />' + 
									'</svg>' +          	
								'</td>' + 
								'<td class="acadp-image acadp-border-0 acadp-p-2 acadp-w-[100px] acadp-align-middle acadp-text-center md:acadp-p-3">' + 
									'<img src="' + value['url'] + '" class="acadp-inline-block acadp-w-full" alt="" />' + 
									'<input type="hidden" name="images[]" class="acadp-image-field" value="' + value['id'] + '" />' + 
								'</td>' + 
								'<td class="acadp-border-0 acadp-p-2 acadp-align-middle md:acadp-p-3">' + 
									'<div class="acadp-image-url acadp-font-medium">' + value['url'].split(/[\\/]/).pop() + '</div>' + 
									'<a href="javascript:void(0);" class="acadp-delete-image acadp-underline" data-attachment_id="' + value['id'] + '">' + acadp.i18n.button_label_delete + '</a>' + 
								'</td>' +                 
							'</tr>';

							$( '#acadp-images' ).append( html );
						}				
					});

					sortImages();
					toggleImageUploadBtn();
					disableSubmitBtn( false );
				},
				error: function( data ) {
					imagesContainerEl.classList.remove( 'is-uploading' );
					uploadButtonEl.querySelector( '.acadp-spinner' ).remove();

					toggleImageUploadBtn();
					disableSubmitBtn( false );
				}
    		}

    		// Submit form using 'ajaxSubmit'.
    		$( '#acadp-form-upload' ).ajaxSubmit( options );										 
		});	

		// Make images sortable.
		sortImages();
		
		// Delete the selected image.	
		$( formEl ).on( 'click', 'a.acadp-delete-image', ( event ) => {														 
            event.preventDefault();
								
			const el = event.target;
			
			let data = {
				'action': 'acadp_public_delete_attachment_listings',
				'attachment_id': el.getAttribute( 'data-attachment_id' ),
				'security': acadp.ajax_nonce
			}
			
			$.post( acadp.ajax_url, data, function( response ) {
				el.closest( 'tr' ).remove();
				document.querySelector( '#acadp-form-control-image' ).value = '';

				toggleImageUploadBtn();
			});			
		});		

	});

})( jQuery );
