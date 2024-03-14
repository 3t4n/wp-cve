jQuery( function($) {

	/**
	 * Handle the Uninstall Plugin button
	 *
	 */
	$(document).on( 'click', '#wpsbc-uninstaller-button', function(e) {

		if( ! $('#wpsbc-uninstaller-confirmation').is(':visible') ) {

			e.preventDefault();

			$('#wpsbc-uninstaller-confirmation').fadeIn( 300 );
			$(this).attr( 'disabled', true );

			return false;

		} else {

			if( ! confirm( 'Are you sure you wish to remove all WP Simple Booking Calendar related data?' ) )
				return false;

		}

	});

	/**
	 * Track the value of the confirmation field to match the word REMOVE
	 * before letting the user click the Uninstall button
	 *
	 */
	$(document).on( 'keyup', function() {

		if( ! $('#wpsbc-uninstaller-confirmation').is(':visible') )
			return false;

		if( $('#wpsbc-uninstaller-confirmation-field').val() == 'REMOVE' )
			$('#wpsbc-uninstaller-button').attr( 'disabled', false );
		else
			$('#wpsbc-uninstaller-button').attr( 'disabled', true );

	});

});