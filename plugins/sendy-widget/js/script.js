( function ( $ ) {
	$( function () {
		$( document ).on( 'submit', '.sendy-subscribe-form', function () {
			var email_id = $(this).find( '.subscriber-email' ).val();
			var filter = /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;
			console.log(email_id);
			valid = String( email_id ).search( filter ) != -1;

			if ( !valid ) {

				alert( 'Please enter a valid email address' );

				return false;
			} else {
				return true;
			}


		} )

	} )
} )( jQuery );