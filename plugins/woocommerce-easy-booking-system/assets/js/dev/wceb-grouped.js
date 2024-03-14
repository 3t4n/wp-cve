(function($) {

	$(document).ready(function() {

		wceb.dateFormat            = wceb_object.booking_dates;
		wceb.firstDate             = parseInt( wceb_object.first_date );
		wceb.bookingMin            = parseInt( wceb_object.min );
		wceb.bookingMax            = wceb_object.max === '' ? '' : parseInt( wceb_object.max );
		wceb.bookingDuration       = parseInt( wceb_object.booking_duration );
		wceb.priceHtml             = wceb_object.prices_html;

		$pickerWrap   = $('.wceb_picker_wrap');
		$reset_dates  = $pickerWrap.find('a.reset_dates');
		$bookingPrice = $('.booking_price');

		$pickerWrap.hide();

		wceb.pickers.init();
		
		$('.cart').on( 'change', '.quantity input.qty, .wc-grouped-product-add-to-cart-checkbox', function() {

			var children = wceb.get.childrenIds();

			// Get previously selected items
			var prev_items = $reset_dates.attr( 'data-ids' );

			if ( prev_items !== "" ) {

				// Parse JSON to object
				prev_items = JSON.parse( prev_items );

				// Compare previously selected and currently selected item IDS, if they are different = init
				 if ( true === hasUpdatedSelection( Object.keys( children ), Object.keys( prev_items ) ) ) {
				 	action = 'init';
				 } else {

					// Loop through each selected item to see if quantity has changed (to avoid triggering ajax request twice because of PB)
					$.each( children, function( id, quantity ) {

						if ( prev_items[id] !== quantity ) {
							action = 'update'; // If quantity has changed = update price
							return false;
						} else {
							action = false; // If nothing has changed = do nothing
							return true;
						}

					});

				}

			} else {
				action = 'init'; // First selection = init
			}

			// Store selected ids and quantity
			$reset_dates.attr( 'data-ids', JSON.stringify( children ) );

			if ( action === 'init' ) {
				resetPickers();
			} else if ( action === 'update' ) {
				maybeRecalculateBookingPrice();
			}

		});

		/**
		* Check if bundle selection has been updated.
		**/
		function hasUpdatedSelection( current, previous ) {

            current.sort(); 
            previous.sort(); 
              
            if ( current.length != previous.length ) {
                return true; 
            }
              
            for ( var i = 0; i < current.length; i++ ) {

                if ( current[i] != previous[i] ) {
                    return true; 
                }

            }

            return false;

        } 

        /**
		* Reset pickers and clear booking session.
		**/
		function resetPickers() {

			// Reset pickers
			wceb.pickers.reset();

			wceb.clearBookingSession();
			$reset_dates.hide();

			updateTotalPrice();
			
		}

		/**
		* If dates are selected, trigger ajax request to recalculate price, otherwise reset pickers.
		**/
		function maybeRecalculateBookingPrice() {

			if ( wceb.dateFormat === 'two' && wceb.checkIf.datesAreSet() ) {
				wceb.setPrice();
			} else if ( wceb.dateFormat === 'one' && wceb.checkIf.dateIsSet( 'start' ) ) {
				wceb.picker.set();
			} else {
				resetPickers();
			}

		}

		function updateTotalPrice() {

			var $this      = $(this),
				quantities = [];

			totalGroupedPrice = 0;
			totalGroupedRegularPrice = 0;

			var children = wceb.get.childrenIds();

			$.each( children, function( id, qty ) {

				var price = wceb_object.product_price[id];
				var regular_price = wceb_object.product_regular_price[id];

				if ( qty > 0 ) {
					totalGroupedPrice += parseFloat( price * qty );

					if ( regular_price !== '' ) {
						totalGroupedRegularPrice += parseFloat( regular_price * qty );
					} else {
						totalGroupedRegularPrice += parseFloat( price * qty );
					}

				}

				quantities.push( qty );

			});

			// Get highest quantity selected
			max_qty = Math.max.apply( Math, quantities );

			// Hide date inputs if no quantity is selected
			( max_qty > 0 ) ? $pickerWrap.slideDown( 200 ) : $pickerWrap.hide();
			
			// Update data-booking_price attribute
			$bookingPrice.attr('data-booking_price', totalGroupedPrice );

			// Update total price, maybe including addons
			var formatted_total = wceb.formatPrice( wceb.get.basePrice() );
			var formatted_price = '<span class="woocommerce-Price-amount amount">' + formatted_total + '</span>';

			// If product is on sale
			if ( totalGroupedPrice !== totalGroupedRegularPrice ) {

				// Update data-booking_regular_price attribute
				$bookingPrice.attr('data-booking_regular_price', totalGroupedRegularPrice );

				// Update total regular price, maybe including addons
				var formatted_regular_price = wceb.formatPrice( wceb.get.regularPrice() );
				var formatted_price = '<del><span class="woocommerce-Price-amount amount">' + formatted_regular_price + '</span></del> <ins><span class="woocommerce-Price-amount amount">' + formatted_total + '</span></ins>';
				
			}

			// Update price
			$bookingPrice.html( '<span class="price">' + formatted_price + '</span>' );

		}

	});

})(jQuery);