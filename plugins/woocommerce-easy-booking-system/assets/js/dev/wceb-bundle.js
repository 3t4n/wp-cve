(function($) {

	$(document).ready(function() {

		wceb.dateFormat            = wceb_object.booking_dates;
		wceb.firstDate             = parseInt( wceb_object.first_date );
		wceb.bookingMin            = parseInt( wceb_object.min );
		wceb.bookingMax            = wceb_object.max === '' ? '' : parseInt( wceb_object.max );
		wceb.bookingDuration       = parseInt( wceb_object.booking_duration );
		wceb.priceHtml             = wceb_object.prices_html;

		$bookingPrice = $('.booking_price');
		$body         = $('body');
		$bundle_wrap  = $('.bundle_form .bundle_data');
		$bundle_price = $bundle_wrap.find('.bundle_price');
		$picker_wrap  = $('.wceb_picker_wrap');
		$reset_dates  = $picker_wrap.find('a.reset_dates');

		function updateBundle() {

			// Get selected items
			var children = wceb.get.childrenIds();

			// Variable bundled items
			$('.cart[data-type="variable"]').each( function() {

				var $this = $(this);
				var $priceText = $this.find( '.wceb-price-format' );
				var variation_data = $this.data( 'product_variations' );

				// Get selected variation data
				$.each( variation_data, function( index, data ) {

					var variationID = data.variation_id;

					// If variation is selected
					if ( typeof children[variationID] !== 'undefined' ) {

						// Variation data
						current_variation = variation_data[index];

						// Trigger custom "found_variation" event
						$this.trigger( 'wceb_variation_found', [current_variation, $this] );

						// Hide "/ day" or "/ night" if variation is not bookable
						( ! current_variation.is_bookable ) ? $priceText.hide() : $priceText.html( wceb.priceHtml ).show();

					}

				});

			});

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

			// Store newly selected items
			$reset_dates.attr( 'data-ids', JSON.stringify( children ) );

			if ( action === 'init' ) {
				resetPickers();
			} else if ( action === 'update' ) {
				maybeRecalculateBookingPrice();
			}

			$('.single_add_to_cart_button').addClass( 'date-selection-needed' );

		}

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

			$('.wceb_picker_wrap').find( '.wceb_error' ).remove();
			
			// Reset pickers
			wceb.pickers.reset();

			// Clear session
			wceb.clearBookingSession();
			
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
				
			}

		}

		/**
		* Update bundle total price when selection changes when dates are not selected.
		**/
		function updateBundlePrice( totals ) {

			totalBundlePrice = totals.price;
			totalBundleRegularPrice = totals.regular_price;
			
			$bookingPrice.attr( 'data-booking_price', totalBundlePrice );
			$bookingPrice.attr( 'data-booking_regular_price', totalBundleRegularPrice );

			var additional_costs = wceb.get.additionalCosts();

			// Hide default bundle price
			$bundle_price.remove();

			if ( $('.cart').find('input[name="quantity"]').length ) {
				var qty = parseFloat( $('.cart').find('input[name="quantity"]').val() );
			} else {
				var qty = 1;
			}

			var price           = parseFloat( ( totalBundlePrice + additional_costs ) * qty  );
			var regularPrice    = parseFloat( ( totalBundleRegularPrice + additional_costs ) * qty  );

			var formatted_price = '<span class="woocommerce-Price-amount amount">' + wceb.formatPrice( price ) + wceb_object.price_suffix + '</span>';

			if ( price !== regularPrice ) {
				var formatted_price = '<del><span class="woocommerce-Price-amount amount">' + wceb.formatPrice( regularPrice ) + wceb_object.price_suffix + '</span></del> <ins><span class="woocommerce-Price-amount amount">' + wceb.formatPrice( price ) + wceb_object.price_suffix + '</span></ins>';
			}

			// Update booking price with (maybe) additional costs
			$bookingPrice.find('.price').html( formatted_price );

		}

		$bundle_wrap.on( 'woocommerce-product-bundle-show', function( e) {

			$picker_wrap.slideDown( 200 );

			// Update calendars
			updateBundle();

			e.stopPropagation();

		});

		$bundle_wrap.on( 'woocommerce-product-bundle-hide', function() {
			$picker_wrap.hide();
		});

		// Update totals
		$bundle_wrap.on( 'woocommerce-product-bundle-updated-totals', function( event, bundle ) {

			// Don't update price if dates are selected
			if ( ( wceb.dateFormat === 'two' && wceb.checkIf.datesAreSet() ) || ( wceb.dateFormat === 'one' && wceb.checkIf.dateIsSet( 'start' ) ) ) {
				return;
			}

			var totals = bundle.api.get_bundle_totals();

			// Update totals
			updateBundlePrice( totals );

		});

		$body.on('reset_image', '.variations_form', function( e, variation ) {
			$(this).find( '.wceb-price-format' ).hide();
		});

	});

})(jQuery);