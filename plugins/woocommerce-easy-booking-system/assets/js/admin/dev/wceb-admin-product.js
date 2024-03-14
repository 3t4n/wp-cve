(function($) {
	$(document).ready(function() {

		$('input#_bookable').change( function() {

			if ( $(this).is(':checked') ) {
				$('.show_if_bookable').show();
			} else {
				$('.show_if_bookable').hide();
				$('input.variation_is_bookable').attr('checked', false).change();
			}

			if ( $('.bookings_tab').is('.active') ) {
				$( 'ul.wc-tabs li:visible' ).eq(0).find( 'a' ).click();
			}

		}).change();

		$( '#variable_product_options' ).on( 'change', 'input.variation_is_bookable', function () {
			$( this ).closest( '.woocommerce_variation' ).find( '.show_if_variation_bookable' ).hide();
			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( '.woocommerce_variation' ).find( '.show_if_variation_bookable' ).show();
			}
		}).change();

		// Simple and variable parent products
		$('#booking_product_data').find('.booking_dates').on( 'change', function() {

			var dates = $(this).val();

			if ( dates === 'global' ) {
				dates = wceb_admin_product.number_of_dates;
			}

			if ( dates === 'two' ) {
				$('.show_if_two_dates').show();
			} else {
				$('.show_if_two_dates').hide();
			}

			// Maybe override with variation values
			$('#variable_product_options').find('.booking_dates').change();

		}).change();

		// Variations
		$( '#variable_product_options' ).on( 'change', '.booking_dates', function() {

			var $this   = $(this),
				dates   = $this.val(),
				$parent = $this.parents('.booking_variation_data');

			if ( dates === 'parent' ) {

				dates = $('#booking_product_data').find('.booking_dates').val();

				if ( dates === 'global' ) {
					dates = wceb_admin_product.number_of_dates;
				}

			}

			if ( dates === 'two' ) {
				$parent.find('.show_if_two_dates').show();
			} else {
				$parent.find('.show_if_two_dates').hide();
			}
			
		}).change();

		$( '#woocommerce-product-data' ).on( 'woocommerce_variations_loaded', function() {
			$('#variable_product_options').find('input.variation_is_bookable').change();
			$('#variable_product_options').find('.booking_dates').change();
		});

	});
})(jQuery);