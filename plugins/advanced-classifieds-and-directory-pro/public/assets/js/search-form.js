'use strict';

(function( $ ) {
	
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
	 * Called when the page has loaded.
	 */
	$(function() {	
		
		document.querySelectorAll( '.acadp-search-form' ).forEach(( el ) => {
			const formEl = el.querySelector( 'form' );	

			// Init Datetime picker		
			formEl.querySelectorAll( '.acadp-form-control-date-picker' ).forEach(( el ) => {
				initDatetimePicker( el );
			});

			formEl.querySelectorAll( '.acadp-form-control-datetime-picker' ).forEach(( el ) => {
				initDatetimePicker( el );
			});

			// Load custom fields of the selected category in the search form
			const categoryEl = formEl.querySelector( '.acadp-category-field' );

			if ( categoryEl !== null ) {
				categoryEl.addEventListener( 'acadp.terms.change', ( event ) => {
					const customFieldsEl = formEl.querySelector( '.acadp-custom-fields' );					
					if ( customFieldsEl === null ) {
						return false;
					}

					let fields = {};						

					let spinnerEl = document.createElement( 'div' );
					spinnerEl.className = 'acadp-spinner';					
					formEl.querySelector( '.acadp-button-group' ).appendChild( spinnerEl );

					// Build fields input from cache
					let cached = formEl.dataset.cache;

					if ( cached ) {
						cached = JSON.parse( cached );
					}

					for ( const key in cached ) {
						if ( cached.hasOwnProperty( key ) ) {
							fields[ key ] = cached[ key ];
						}
					}
				
					// Build fields input from current visible form fields
					const current = $( formEl ).serializeArray();

					$.each( current, function() {
						if ( this.name.indexOf( 'cf' ) !== -1 ) {
							fields[ this.name ] = this.value;
						}
					});

					// Cache the new fields data 
					formEl.setAttribute( 'data-cache', JSON.stringify( fields ) );

					// Build query input from the fields object
					let query = [];

					for ( const key in fields ) {
						if ( fields.hasOwnProperty( key ) ) {
							query.push( encodeURIComponent( key ) + '=' + encodeURIComponent( fields[ key ] ) );
						}
					}

					query = query.join( '&' );					
					
					let data = {
						'action': 'acadp_custom_fields_search',
						'term_id': event.target.value,
						'style': formEl.dataset.style,
						'cached_meta': query,
						'security': acadp.ajax_nonce
					}
					
					$.post( acadp.ajax_url, data, function( response ) {
						formEl.querySelector( '.acadp-button-group .acadp-spinner' ).remove();

						formEl.querySelectorAll( '.acadp-form-group-custom-field' ).forEach(( el ) => {
							el.remove();
						});

						customFieldsEl.insertAdjacentHTML( 'beforebegin', response );

						formEl.querySelectorAll( '.acadp-form-control-date-picker' ).forEach(( el ) => {
							initDatetimePicker( el );
						});

						formEl.querySelectorAll( '.acadp-form-control-datetime-picker' ).forEach(( el ) => {
							initDatetimePicker( el );
						});
					});			
				});	
			}
		});

	});

})( jQuery );
