(function($) {
	$(document).ready(function() {

		const maxYear         = new Date( wceb_calendar_reports.last_date + 'T00:00:00' ),
		      bookingMode     = wceb_calendar_reports.booking_mode,
		      bookingsPerDate = wceb_calendar_reports.bookings;

		$('.wceb_reports_datepicker').pickadate({

			today           : false,
			clear           : false,
			close           : false,
			selectYears     : true,
  			selectMonths    : true,
  			showWeekdaysFull: true,
  			min             : 0,
  			max             : maxYear,
  			klass           : {
  				picker: 'picker picker-' + bookingMode
  			},
			onRender: function() {

				const $pickerDay = this.$root.find( '.picker__day' );

				$.each( bookingsPerDate, function( date, bookings ) {

					const dateTime = new Date( date ).setHours( 0,0,0,0 );

					const $day = $pickerDay.filter( function() {
						const pick = $(this).data('pick');
						return pick === dateTime;
					});

					$day.parent( 'td' ).append( '<span class="bookings-container"></span>' );
					
					$.each( bookings, function( index, booking ) {

						const product   = booking[0];
						const className = 'booking' + ( true === booking[1] ? ' start' : '' ) + ( true === booking[2] ? ' end' : '' );
						
						// Calculate top offset: Item height is 26px. First item has 40px top offset.
						const topOffset = index == 1 ? 40 : 14 + ( index * 26 );

						$day.siblings( '.bookings-container' ).append( '<span class="' + className + '" style="top:' + topOffset + 'px" title="' + product + '">' + product + '</span>');

					});

				});

				this.open( false );

			}

		});
		
	});

})(jQuery);