'use strict';

window.isACADPReCaptchaLoaded = false;

if ( ! window.ACADPLoadScript ) { 
	// Load script files.
	var ACADPLoadScript = ( url, type = null ) => {
		return new Promise(( resolve, reject ) => { 
			const filename = url.substring( url.lastIndexOf( '/' ) + 1, url.lastIndexOf( '.' ) );
			const id = 'acadp-script-' + filename;

			if ( document.querySelector( '#' + id ) !== null ) {
				resolve();
				return false;
			}

			const script = document.createElement( 'script' );

			script.id    = id;
			script.src   = url;
			script.defer = true;

			if ( type !== null ) {
				script.type = type;	
			}		

			script.onload  = () => resolve();
			script.onerror = () => reject();

			document.body.appendChild( script );
		});
	}
}

(function( $ ) {	
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {

		// Load the required script files.
		document.querySelectorAll( '.acadp-require-js' ).forEach(( el ) => {
			const script = el.dataset.script;			
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/' + script + '.js' );			
		});
		
		if ( document.querySelector( 'acadp-dropdown-terms' ) !== null ) {
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/select.js', 'module' );
		}

		if ( typeof acadp_blocks !== 'undefined' ) { // Is a block editor?
			ACADPLoadScript( acadp.plugin_url + 'public/assets/js/map.js' );
		}

		// Cookie Consent.
		if ( acadp.show_cookie_consent ) {
			document.querySelectorAll( '.acadp-button-cookie-consent' ).forEach(( buttonEl ) => {
				buttonEl.addEventListener( 'click', ( event ) => {					
					buttonEl.querySelector( 'svg' ).classList.add( 'acadp-animate-spin' );
					buttonEl.disabled = true;
					
					let data = {
						'action': 'acadp_set_cookie',
						'security': acadp.ajax_nonce
					}
		
					$.post( 
						acadp.ajax_url, 
						data, 
						function( response ) {
							if ( ! response.success ) {
								return false;
							}

							acadp.show_cookie_consent = false;

							document.querySelectorAll( '.acadp-cookie-consent' ).forEach(( cookieEl ) => {
								cookieEl.remove();
							});

							document.dispatchEvent( new CustomEvent( 'acadp.cookie.consent' ) );	
						}
					);
				});
			});
		}

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

	});

})( jQuery );

// Load reCAPTCHA explicitly.
function acadp_on_recaptcha_load() {
	if ( ! acadp.recaptcha_site_key ) {	
		return false;
	}
	
	window.isACADPReCaptchaLoaded = true;
	document.dispatchEvent( new CustomEvent( 'acadp.recaptcha.loaded' ) );	
}
