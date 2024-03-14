'use strict';

( function ( $ ) {
	$( '#fspGetAccessToken' ).on( 'click', function () {
		let link = $( '#fspModalPlurkAuthLink' ).val().trim();
		window.open( link , '', 'width=750, height=550' );
	} );

	$( '.fsp-modal-footer > #fspModalAddButton' ).on( 'click', function () {
		let requestToken = $('#plurkRequestToken').val().trim();
		let requestTokenSecret = $('#plurkRequestTokenSecret').val().trim();
		let verifier = $( '#plurkVerifier' ).val().trim();

		FSPoster.ajax( 'add_plurk_account', { 'requestToken': requestToken, 'requestTokenSecret':requestTokenSecret, 'verifier':verifier }, function (result) {
			accountAdded('', true);
		} );
	} );
} )( jQuery );