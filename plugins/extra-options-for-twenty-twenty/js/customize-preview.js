// Refactor this when you have the time. DRY DRY...

( function( $, api, _ ) {
	var els, elsKeys, elHTML, creditsHTML, copyrightEl, poweredEl, creditsOn;

	creditsOn = _2020FooterExtras.enabled;
	copyrightEl = $( 'p.footer-copyright' );
	poweredEl = $( 'p.powered-by-wordpress' );

	els = { 
		credits: copyrightEl, 
		powered: poweredEl
	}

	elsKeys = Object.keys( els );

	elHTML = function( el ) {
		if ( ! el.length ) {
			return '';
		}

		return el.html().replace( /\s+/g, ' ').trim();
	};

	creditsHTML = {};

	elsKeys.forEach( function( el ) {
		var currentEl = els[ el ];

		if ( creditsOn ) {
			currentEl.html( _2020FooterExtras[ el ] )
		}
		
		api( 'footer_' + el, function( value ) {
			value.bind( function( to ) {
				currentEl.html( to );
			} );
		} );

		creditsHTML[ el ] = elHTML( currentEl );
	} );

	api.bind( 'preview-ready', function() {
		api.preview.bind( 'active', function() {

			if ( ! creditsOn ) {
				elsKeys.forEach( function( el ) {
					els[ el ].find( '.customize-partial-edit-shortcut' ).hide();
				} );
			}

			api.preview.bind( 'footer-credits-enabled', function( enabled ) {
				api.preview.send( 'footer-credits-html', enabled ? creditsHTML : false );
			} );

		} );
	} );

}( jQuery, wp.customize, _ ) );