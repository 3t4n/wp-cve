( function( $ ) {
	//"use strict";
	$(document).ready(function ($) {
		var $flyoutMenu = $( '.w2w-button' );
		

		// Click on the menu head icon.
		$flyoutMenu.on( 'click', function( e ) {
			e.preventDefault();
			$('.w2w-nav-panel').toggleClass( 'active' );
		} );
		$(window).click(function() {
			$('.w2w-nav-panel').removeClass("active");
		});
		$flyoutMenu.click(function(event){
			event.stopPropagation();
		});
	});
	
	$('#billing_country_field').on('change', function() {
		console.log('OK');
		// Get country code
		var country_code = $(this).find('#billing_country').val();

		// Match country code with Vietnam
		if(country_code == 'VN') {
			$('.woocommerce-billing-fields__field-wrapper').addClass('active');
		} else {
			$('.woocommerce-billing-fields__field-wrapper').removeClass('active');
		}
	})
} )( jQuery );