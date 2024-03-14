(function( $ ) {
	'use strict';		

	/**
	 * Init Datetime Picker.
	 */
	const initDatetimePicker = ( el ) => {
		if ( typeof flatpickr === undefined ) {
			return false;
		}

		let config = {
			allowInput: true
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
		};

		rangeEl.addEventListener( 'input', updateRange );
		updateRange();
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
		};
		
		$el.sortable({ handle: '.acadp-handle' });		
		$el.disableSelection();
	}
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {

		// Init Datetime picker		
		document.querySelectorAll( '.acadp-form-control-date-picker' ).forEach(( el ) => {
			initDatetimePicker( el );
		});

		document.querySelectorAll( '.acadp-form-control-datetime-picker' ).forEach(( el ) => {
			initDatetimePicker( el );
		});

		// Init Range slider	
		document.querySelectorAll( '.acadp-form-control-range-slider' ).forEach(( el ) => {
			initRangeSlider( el );
		});	

		// Init Map
		if ( acadp_admin.map_service === 'osm' ) {
			ACADPLoadScript( acadp_admin.plugin_url + 'admin/assets/js/openstreetmap.js' );
		} else {
			ACADPLoadScript( acadp_admin.plugin_url + 'admin/assets/js/googlemap.js' );
		}
		
		// Load custom fields.
		document.querySelector( '#acadp-form-control-category' ).addEventListener( 'acadp.terms.change', ( event ) => {	
			const customFieldsEl = document.querySelector( '#acadp-custom-fields-listings' );					
			let fields = {};					

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
			const formEl  = customFieldsEl.closest( 'form' );
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

			let spinnerEl = document.createElement( 'div' );
			spinnerEl.className = 'acadp-spinner-overlay';
			spinnerEl.innerHTML = '<div class="acadp-spinner"></div>';

			const rootEl = customFieldsEl.closest( '.acadp' );
			rootEl.appendChild( spinnerEl );

			let data = {
				'action': 'acadp_custom_fields_listings',
				'post_id': customFieldsEl.dataset.post_id,
				'terms': event.target.value,
				'cached_meta': query,
				'security': acadp_admin.ajax_nonce
			};
			
			$.post( ajaxurl, data, function( response ) {
				rootEl.querySelector( '.acadp-spinner-overlay' ).remove();
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
			});			
		});	
		
		// Upload image.		
		document.querySelector( '#acadp-button-upload-image' ).addEventListener( 'click', ( event ) => { 
            event.preventDefault(); 
			
            ACADPMediaUploader(( json ) => {
				let html = '<tr class="acadp-image-row acadp-border-0 acadp-bg-white">' + 
					'<td class="acadp-handle acadp-border-0 acadp-border-b acadp-border-solid acadp-border-gray-200 acadp-cursor-pointer acadp-p-2 acadp-w-7 acadp-align-middle acadp-text-center md:acadp-p-3">' + 
						'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-inline-block acadp-flex-shrink-0">' + 
							'<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />' + 
						'</svg>' +          	
					'</td>' + 
					'<td class="acadp-image acadp-border-0 acadp-border-b acadp-border-solid acadp-border-gray-200 acadp-p-2 acadp-w-16 acadp-align-middle acadp-text-center md:acadp-p-3">' + 
						'<img src="' + json.url + '" class="acadp-inline-block acadp-w-full" alt="" />' + 
						'<input type="hidden" name="images[]" class="acadp-image-field" value="' + json.id + '" />' + 
					'</td>' + 
					'<td class="acadp-border-0 acadp-border-b acadp-border-solid acadp-border-gray-200 acadp-p-2 acadp-align-middle md:acadp-p-3">' + 
						'<div class="acadp-image-url acadp-font-medium">' + json.url.split(/[\\/]/).pop() + '</div>' + 
						'<div class="acadp-flex acadp-gap-1 acadp-items-center">' + 
						'<a href="post.php?post=' + json.id + '&action=edit" target="_blank">' + acadp_admin.i18n.button_label_edit + '</a>' + 
						'<span class="acadp-text-muted acadp-text-sm">/</span>' + 
						'<a href="javascript:void(0);" class="acadp-delete-image" data-attachment_id="' + json.id + '">' + acadp_admin.i18n.button_label_delete + '</a>' + 
						'</div>' + 
					'</td>' +                 
				'</tr>';
			
				$( '#acadp-images' ).append( html );
				
				sortImages();
			}); 
        });
		
		// Make images sortable.
		sortImages();
		
		// Delete the selected image.	
		$( '#acadp-images' ).on( 'click', 'a.acadp-delete-image', ( event ) => {														 
            event.preventDefault();
								
			const el = event.target;
			
			let data = {
				'action': 'acadp_delete_attachment',
				'attachment_id': el.dataset.attachment_id,
				'security': acadp_admin.ajax_nonce
			}

			el.closest( 'tr' ).remove();
			
			$.post( ajaxurl, data, function( response ) {
				// console.log( response );
			});			
		});	
		
	});

})( jQuery );
