'use strict';

( function ( $ ) {
	let doc  = $( document );
	let data = {}

	doc.ready( function () {
		doc.on( 'click', '#fspVerifyBtn', function () {
			data.email 		= $('#fspEmail').val().trim();
			data.found_from = $( '#fspMarketingStatistics' ).val();

			FSPoster.ajax( 'verify_app', data, function ( res ) {
				if ( res[ 'status' ] === 'ok' )
				{
					$( '.fsp-box-container > .fsp-card.fsp-box' ).html( FSPoster.htmlspecialchars_decode( res[ 'html' ] ) );
				}
			} );
		} ).on( 'click', '#fspInstallBtn', function () {
			data.otp_code = $( '#fspOtpCode' ).val().trim();

			FSPoster.ajax( 'activate_app', data, function ( res ) {
				FSPoster.toast( res[ 'msg' ], 'success' );
				FSPoster.loading( true );

				window.location.reload();
			} );
		} );
	} );
} )( jQuery );
