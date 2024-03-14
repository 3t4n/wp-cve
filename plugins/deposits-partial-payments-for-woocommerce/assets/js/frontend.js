jQuery(document).on('change', ".awcdp-deposits-wrapper input[name='awcdp_deposit_option']", function (e) {
  e.preventDefault();
  $container = jQuery( this ).closest( '.awcdp-deposits-wrapper' );

  if( jQuery(this).val() == 'yes' ){
    $container.find( '.awcdp-deposits-payment-plans, .awcdp-deposits-description' ).slideDown( 200 );
  } else {
    $container.find( '.awcdp-deposits-payment-plans, .awcdp-deposits-description' ).slideUp( 200 );
  }

});


jQuery( document ).ready( function() {
  $container = jQuery( '.awcdp-deposits-wrapper' );
  if ( jQuery( 'input[name="awcdp_deposit_option"]:checked' ).val() == 'no' ) {
		$container.find( '.awcdp-deposits-payment-plans, .awcdp-deposits-description' ).slideUp( 200 );
		// jQuery( '.awcdp-deposits-description' ).slideUp( 200 );
	}
});
