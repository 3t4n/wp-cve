(function( $ ) {
	'use strict';	

	const toggleShortcodeForm = () => {
		const shortcode = document.querySelector( '#acadp-shortcode-selector input[type=radio]:checked' ).value;

		document.querySelectorAll( '.acadp-shortcode-form' ).forEach(( el ) => {
			if ( el.dataset.shortcode === shortcode ) {
				el.hidden = false;
			} else {
				el.hidden = true;
			}
		});
	}

	const copyShortcode = () => {
		document.querySelector( '#acadp-shortcode' ).select();
		document.execCommand( 'copy' );	

		document.querySelector( '#acadp-modal-shortcode .acadp-status-text' ).hidden = false;	
	}

	/**
	 * Called when the page has loaded.
	 */
	$(function() {				

		// Accordion
		document.querySelectorAll( '.acadp-accordion-header' ).forEach(( el ) => {
			const accordionEl = el.closest( '.acadp-accordion' );

			el.addEventListener( 'click', ( event ) => {
				accordionEl.querySelectorAll( '.acadp-accordion-panel' ).forEach(( el ) => {
					el.classList.toggle( 'open' );
				});
			});			
		});

		// Modal
		document.querySelectorAll( '.acadp-button-modal' ).forEach(( buttonEl ) => {
			const modal = buttonEl.getAttribute( 'data-target' );

			let backdropEl = document.createElement( 'div' );
			backdropEl.id = 'acadp-backdrop';
			backdropEl.className = 'acadp';
			backdropEl.innerHTML = '<div class="acadp-modal-backdrop"></div>';

			buttonEl.addEventListener( 'click', () => {		
				document.body.appendChild( backdropEl );				
				document.querySelector( modal ).classList.add( 'open' );
			});
		});

		document.querySelectorAll( '.acadp-modal .acadp-button-close' ).forEach(( buttonEl ) => {
			buttonEl.addEventListener( 'click', () => {	
				document.querySelector( '#acadp-backdrop' ).remove();
				document.querySelector( '.acadp-modal.open' ).classList.remove( 'open' );
			});
		});
		
		// Tab: Shortcode Builder
		const shortcodeSelectorEl = document.querySelector( '#acadp-shortcode-selector' );

		if ( shortcodeSelectorEl !== null ) {
			// Toggle Shortcode Form
			shortcodeSelectorEl.querySelectorAll( 'input[type=radio]' ).forEach(( el ) => {
				el.addEventListener( 'change', ( event ) => {
					toggleShortcodeForm();
				});
			});

			toggleShortcodeForm();
		
			// Generate shortcode
			document.querySelector( '#acadp-button-shortcode-generate' ).addEventListener( 'click', ( event ) => { 
				event.preventDefault();			

				// Reset
				document.querySelector( '#acadp-modal-shortcode .acadp-status-text' ).hidden = true;

				// Shortcode
				const shortcode = shortcodeSelectorEl.querySelector( 'input[type=radio]:checked' ).value;

				// Attributes
				let obj = {};

				const formEl = document.querySelector( '#acadp-shortcode-form-' + shortcode );

				formEl.querySelectorAll( '.acadp-shortcode-field' ).forEach(( el ) => {
					let type  = el.getAttribute( 'type' ) || el.type;
					let key   = el.getAttribute( 'name' ) || el.name;				
					let value = el.value;	

					let def = 0;
					if ( el.hasAttribute( 'data-default' ) ) {
						def = el.dataset.default;
					}

					// Is a Checkbox?
					if ( 'checkbox' == type ) {
						value = el.checked ? 1 : 0;
					}

					// Add only if the user input differ from the global configuration
					if ( value != def ) {
						obj[ key ] = value;
					}
				});
				
				let attributes = shortcode;
				for ( let key in obj ) {
					if ( obj.hasOwnProperty( key ) ) {
						attributes += ( ' ' + key + '="' + obj[ key ] + '"' );
					}
				}

				// Insert Shortcode
				document.querySelector( '#acadp-shortcode' ).value = '[acadp_' + attributes + ']';
			});

			// Copy Shortcode
			document.querySelector( '#acadp-button-shortcode-copy' ).addEventListener( 'click', copyShortcode );
			document.querySelector( '#acadp-shortcode' ).addEventListener( 'focus', copyShortcode );
		}

		// Tab: Issues
		const issuesFormEl = document.querySelector( '#acadp-issues-form' );

		if ( issuesFormEl !== null ) {
			// Toggle checkboxes in the issues table list.
			issuesFormEl.querySelector( '#acadp-issues-toggle-all' ).addEventListener( 'change', ( event ) => {
				const isChecked = event.target.checked ? true : false;	

				issuesFormEl.querySelectorAll( '.acadp-form-checkbox' ).forEach(( el ) => {
					el.checked = isChecked;
				});
			});	

			// Validate the form.	
			issuesFormEl.addEventListener( 'submit', ( event ) => {
				let isChecked = issuesFormEl.querySelector( '.acadp-form-checkbox:checked' );	

				if ( ! isChecked ) {
					alert( acadp_admin.i18n.alert_required_issues );
					event.preventDefault();
					return false;
				}			
			});	
		}	
		
	});

})( jQuery );
