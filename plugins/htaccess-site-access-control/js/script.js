jQuery( document ).ready(function(){
	jQuery( '#wpsos .pass-check' ).hide();
	jQuery( '#wpsos input.enable' ).click( function( e ) {
		if( jQuery( e.target ).is( ':checked') ){
			jQuery( '#wpsos .pass-check' ).slideDown();
		}
	});
	
	jQuery( '#wpsos input.disable').click( function( e ){
		var enabled = false;
		jQuery( '#wpsos input.enable' ).each( function( i, el ){
			if( jQuery( el ).is( ':checked' ) ){
				enabled = true;
			}
		});
		if( !enabled ){
			jQuery( '#wpsos .pass-check' ).slideUp();
		}
	});
	
	if( jQuery( '#wpsos .not-working').length ){
		jQuery( '#wpsos input' ).prop( "disabled", true ).css( 'opacity', '0.4' );
	}
});