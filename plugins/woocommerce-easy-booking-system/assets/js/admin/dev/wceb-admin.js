(function($) {
	$(document).ready(function() {

		$('.easy-booking-notice-close').on('click', function(e) {
			e.preventDefault();
			
			var $this = $(this),
				notice = $this.data('notice');

			var data = {
				action: 'wceb_hide_admin_notice',
				security: wceb_admin.hide_notice_nonce,
				notice: notice
			};

			$.ajax({
				url :  wceb_admin.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					$this.parents('.easy-booking-notice').hide();
				}
			});
			
		});

		// Script to update database.
		$( '.wceb-db-update' ).on( 'click', function(e) {
			e.preventDefault();
			
			var $this = $(this),
			    $response = $('.wceb-response'),
			    fullUpdate = $this.next('input[name="wceb-full-db-update"]').val();

			var data = {
				action: 'wceb_update_database',
				security: wceb_admin.hide_notice_nonce,
				full_update: fullUpdate
			};

			$this.fadeTo('400', '0.6');

			$response.html('');

			$.post( wceb_admin.ajax_url, data, function( response ) {

				if ( response !== "" ) {
					alert( response );
				}
				
				$this.stop(true).css('opacity', '1').parents('.easy-booking-notice').fadeOut();

			});
			
		});

		// Migrate add-ons to PRO version.
		$( '.wceb-init-booking-statuses' ).on( 'click', function(e) {
			e.preventDefault();
			
			var $this = $(this);

			var data = {
				action  : 'wceb_init_booking_statuses',
				security: wceb_admin.hide_notice_nonce
			};

			$this.fadeTo( '400', '0.6' );

			$.post( wceb_admin.ajax_url, data, function( response ) {

				if ( response !== "" ) {
					alert( response );
				}

				$this.stop(true).css( 'opacity', '1' );

			});
			
		});
		
	});
})(jQuery);