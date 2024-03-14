'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		FSPoster.upgrade( 'Purchase premium version to access all features.', true, function () {
			window.history.back();
		} );
	} );
} )( jQuery );
