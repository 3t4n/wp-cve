'use strict';

( function ( $ ) {
	$( '.fsp-modal-footer > #fspModalAddButton' ).on( 'click', function () {
		let token = $( '#fspBotToken' ).val().trim();

		FSPoster.ajax( 'add_telegram_bot', { 'token': token }, function () {
			accountAdded('', true);
		} );
	} );
} )( jQuery );