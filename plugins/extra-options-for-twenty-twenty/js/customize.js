( function( api ) {

	api.bind( 'ready', function() {
		api( 'enable_footer_creds_option' ).bind( function( value ) {
			api.previewer.send( 'footer-credits-enabled', value );
		} );

		api.bind( 'ready', function() {
			api.previewer.bind( 'footer-credits-html', function( creditsHTML ) {
				var copyrightEl, poweredEl;
				
				copyrightEl = api( 'footer_credits' );
				poweredEl = api( 'footer_powered' );

				if ( false !== creditsHTML ) {
					if ( copyrightEl.get() !== creditsHTML.credits ) {
						copyrightEl.set( creditsHTML.credits );
					}

					if ( poweredEl.get() !== creditsHTML.powered ) {
						poweredEl.set( creditsHTML.powered );
					}
				}

			} );
		} );
	} );

}( wp.customize ) );