( function ( $ ) {

    $( document ).ready( function () {
	// reset options to defaults if needed
	$( document ).on( 'click', '.reset_mc_settings', function () {
	    return confirm( mcArgsSettings.resetToDefaults );
	} );

    } );

} )( jQuery );