var wceb = {

	// General settings
	productType  : wceb_object.product_type,
	calcMode     : wceb_object.calc_mode, // Days or Nights
	maxOption    : new Date( wceb_object.last_date + 'T00:00:00' ), // December 31st of max year
	firstWeekday : wceb_object.first_weekday,
	allowDisabled: wceb_object.allow_disabled, // Allow disabled dates inside booking period

	// Checking functions
	checkIf: {
		isDate     : null,
		isDay      : null,
		isArray    : null,
		isObject   : null,
		isDisabled : null,
		dateIsSet  : null,
		datesAreSet: null
	},

	get: {
		firstAvailableDate: null,
		basePrice         : null,
		additionalCosts   : null,
		childrenIds       : null,
		minAndMax         : null,
		closestDisabled   : null,
		closest           : null
	},

	createDateObject    : null,
	clearBookingSession : null,
	applyBookingDuration: null,
	formatPrice         : null,
	setPrice            : null,

	// Picker functions
	picker: {
		close: null,
		set  : null
	},

	// Pickers functions
	pickers: {
		init       : null,
		render     : null,
		reset      : null,
		clearSecond: null,
		set        : null
	}

};

(function($) {

	$(document).ready( function() {

		// Fix to force http/https for ajax requests.
		wceb_ajax_url = ( location.protocol === 'https:' ? 'https:' : 'http:' ) + wceb_object.ajax_url;

		$body               = $('body');
		$cart               = $('.cart');
		$qty_input          = $cart.find('input[name="quantity"]');
		$booking_price      = $('.booking_price');
		$add_to_cart_button = $('.single_add_to_cart_button');
		$reset_dates        = $('a.reset_dates');

		product_id = $('input[name="add-to-cart"], button[name="add-to-cart"]').val();
		$variation_input = $('.variations_form').find('input[name="variation_id"]');

		// Start picker
		$inputStart = $('.wceb_datepicker_start').pickadate();
		pickerStart = $inputStart.pickadate('picker');
		pickerStartItem = pickerStart.component.item;

		// End picker
		$inputEnd   = $('.wceb_datepicker_end').pickadate();
		pickerEnd   = $inputEnd.pickadate('picker');
		pickerEndItem   = pickerEnd.component.item;

		var selectedDates = {
			startFormat: null,
			endFormat  : null
		};

		/**
		* Check if is date (date object)
		*/
		wceb.checkIf.isDate = function( date ) {
			return ( date instanceof Date );
		}

		/**
		* Check if is weekday (1,2,3,4,5,6,7)
		*/
		wceb.checkIf.isDay = function( date ) {
			return ( ! isNaN( date ) && ( date >= 1 && date <= 7 ) );
		}

		/**
		* Check if is array ([1,0,2016])
		*/
		wceb.checkIf.isArray = function( date ) {
			return ( date instanceof Array );
		}

		/**
		* Check if is an object and not a date (from: [1,0,2016]; to: [1,0,2016])
		*/
		wceb.checkIf.isObject = function( date ) {
			return ( ( typeof date === 'object' ) && ! ( date instanceof Date ) );
		}

		/**
		* Check if the date is disabled
		*/
		wceb.checkIf.isDisabled = function( disabled, dateToEnable ) {

			if ( typeof disabled === 'undefined' ) {
				return false;
			}

		 	var d = false;

		 	var timeToEnable = dateToEnable.pick;

			$.each( disabled, function( index, dateObject ) {

				// [year, month, date, type]
				if ( wceb.checkIf.isArray( dateObject ) ) {

					dateObject = new Date( dateObject[0], dateObject[1], dateObject[2] );

					if ( timeToEnable === dateObject.getTime() ) {
						d = true;
						return;
					}

				// { from: [year, month, date], to: [year, month, date], type: type }
				} else if ( wceb.checkIf.isObject( dateObject ) ) {

					start = new Date( dateObject['from'][0], dateObject['from'][1], dateObject['from'][2] );
					end   = new Date( dateObject['to'][0], dateObject['to'][1], dateObject['to'][2] );

					if ( timeToEnable >= start.getTime() && timeToEnable <= end.getTime() ) {
						d = true;
						return;
					}

				// 1, 2, 3, 4, 5, 6, 7
				} else if ( wceb.checkIf.isDay( dateObject ) ) {

					var day = dateToEnable.day;

					if ( wceb.firstWeekday === '1' && day === 0 ) {
						day = 7;
					} else if ( wceb.firstWeekday !== '1' ) { // If first weekday is Sunday, add 1 day (because date object day starts at 0 and JS calendar start at 1)
						day += 1;
					}

					if ( dateObject === day ) {
						d = true;
						return;
					}

				// Date object
				} else if ( wceb.checkIf.isDate( dateObject ) ) { 

					if ( timeToEnable === dateObject.getTime() ) {
						d = true;
						return;
					}

				}

			});

			return d;

		}

		/**
		* Check if a date is set
		*/
		wceb.checkIf.dateIsSet = function( date ) {

			// If the calendar is specified, get the selected date corresponding
			if ( date === 'start' ) {
				var date = pickerStart.get('select');
			} else if ( date === 'end' ) {
				var date = pickerEnd.get('select');
			}

			return ( typeof date !== 'undefined' && date !== null );
		}

		/**
		* Check if both dates (start and end) are set
		*/
		wceb.checkIf.datesAreSet = function() {
			var startSelected = pickerStart.get('select'),
				endSelected   = pickerEnd.get('select');

			return ( ( startSelected !== null && typeof startSelected !== 'undefined' ) && ( endSelected !== null && typeof endSelected !== 'undefined' ) );
		}

		/**
		* Get the first available date
		*/
		wceb.get.firstAvailableDates = function() {

			var dates = {};
			var firstDay = + parseInt( wceb.firstDate );

			if ( firstDay <= 0 ) {
				var firstDay = false;
			}

			// Get first available date
			var first = wceb.createDateObject( false, firstDay );

			// Get start picker disabled dates
			var disabled = pickerStartItem.disable;

			// If first available date is disabled, check the next date until one is available
			while ( true === wceb.checkIf.isDisabled( disabled, first ) ) {
				var first = wceb.createDateObject( first.obj, 1 );
			}

			dates['start'] = first;

			if ( wceb.dateFormat === 'two' ) {

				var startFirst = new Date( first.pick );

				// Get end picker first available date
				var endFirst = wceb.createDateObject( startFirst, wceb.bookingMin );

				// Get end picker disabled dates
				var endDisabled = pickerEndItem.disable;

				// If end picker first available date is disabled, check the next date until one is available
				while ( true === wceb.checkIf.isDisabled( endDisabled, endFirst ) ) {
					var endFirst = wceb.createDateObject( endFirst.obj, 1 );
				}

				dates['end'] = endFirst;

			}

			return dates;

		}

		/**
		* Get product booking price (price + maybe addons + maybe quantity)
		*/
		wceb.get.basePrice = function() {

			var product_price = parseFloat( $booking_price.attr( 'data-booking_price' ) );

			// If dates are not set, get price + addons * qty, otherwise get stored price (calculated in backend)
			if ( ( wceb.dateFormat === 'two' && ! wceb.checkIf.datesAreSet() ) || ( wceb.dateFormat === 'one' && ! wceb.checkIf.dateIsSet( 'start' ) ) ) {

				var qty         = ( $qty_input.length ) ? parseFloat( $qty_input.val() ) : 1,
					addon_costs = wceb.get.additionalCosts(),
					total_price = parseFloat( ( addon_costs + product_price ) * qty );

			} else {

				var total_price = product_price;

			}

			return total_price;

		}

		/**
		* Get produt booking regular price, multiplied by quantity selected
		*/
		wceb.get.regularPrice = function() {

			var product_price = parseFloat( $booking_price.attr( 'data-booking_regular_price' ) );

			// If dates are not set, get price + addons * qty, otherwise get stored price (calculated in backend)
			if ( ( wceb.dateFormat === 'two' && ! wceb.checkIf.datesAreSet() ) || ( wceb.dateFormat === 'one' && ! wceb.checkIf.dateIsSet( 'start' ) ) ) {

				var qty         = ( $qty_input.length ) ? parseFloat( $qty_input.val() ) : 1,
					addon_costs = wceb.get.additionalCosts(),
					total_price = parseFloat( ( addon_costs + product_price ) * qty );

			} else {

				var total_price = product_price;

			}

			return total_price;

		}

		/**
		* WooCommerce Product Add-ons compatibility
		*/
		wceb.get.additionalCosts = function( format, context ) {

			// Context can be price or raw-price
			if ( typeof context === 'undefined' ) {
				var context = 'price';
			}

			var total = 0;
			var costs = {};
			
			if ( wceb.productType === 'bundle' && format === 'each' ) {
				$selector = $('.product').find('form.cart').find('.cart');
			} else if ( wceb.productType === 'bundle' && format !== 'each' ) {
				$selector = $('.product').find('.cart.bundle_data');
			} else {
				$selector = $('.product').find('form.cart');
			}
			
			$selector.each( function() {

				$( this ).find( '.wc-pao-addon-field' ).each( function() {

					var addon_cost = 0;
					var $this      = $(this);
					var $parent    = $this.parents('.cart');

					if ( typeof $parent.data( 'bundle_id' ) !== 'undefined' ) {

						// Item ID for bundled products
						var id        = $parent.data( 'product_id' );
						var bundle_id = $parent.data( 'bundled_item_id' );
					
					}

					if ( $this.is( '.wc-pao-addon-custom-price' ) ) {

						addon_cost = $this.val();

					} else if ( $this.is( '.wc-pao-addon-input-multiplier' ) ) {

						// Avoid converting empty strings to 0.
						if ( '' !== $this.val() ) {
							$this.val( Math.ceil( $this.val() ) );
						}

						addon_cost = $this.data( context ) * $this.val();

					} else if ( $this.is( '.wc-pao-addon-checkbox, .wc-pao-addon-radio' ) ) {

						if ( $this.is(':checked') ) {
							addon_cost = $this.data( context );
						}

					} else if ( $this.is( '.wc-pao-addon-image-swatch-select, .wc-pao-addon-select' ) ) {

						if ( $this.val() ) {
							// Get selected value index
							var index  = $this.prop('selectedIndex') - 1; // Remove 1 because of the "none" option.
							addon_cost = $this.find( 'option:selected' ).data( context );
						}

					} else {

						if ( $this.val() ) {
							addon_cost = $this.data( context );
						}

					}

					total += addon_cost;

					// Not bundle products
					if ( typeof id === 'undefined' ) {
						var variation_id = $variation_input.val();
						var id = ( typeof variation_id !== 'undefined' ) ? variation_id : product_id;
					}

					if ( typeof id !== 'undefined' && id !== '' && $this.val() != "" ) {

						// Get field name
						var inputName = $this.attr( 'name' );

						// Remove addon- prefix and brackets
						var addonFieldName = inputName.replace( / *\[[^\]]*]/, '' ).replace( 'addon-', '' );

						// Tweak for bundled items: replace bundle item ID with product ID
						if ( typeof bundle_id !== 'undefined' ) {
							var addonFieldName = addonFieldName.replace( bundle_id + '-', id + '-' );
						}

						// Tweak for select inputs, pass the index
						if ( typeof index !== 'undefined' ) {

							var obj = {};
							obj[index] = addon_cost;
							costs[addonFieldName] = obj;

						} else {

							if ( costs.hasOwnProperty( addonFieldName ) ) {
								costs[addonFieldName].push( addon_cost );
						    } else {
						    	costs[addonFieldName] = [addon_cost];
						    }

					    }

					}

				});

			});

			// If format is specified to "Array" return array, otherwise return total addon costs
			return ( format === 'each' ) ? costs : total;

		}

		wceb.get.childrenIds = function() {
			var children = {};

			// Get IDs
			if ( wceb.productType === 'grouped' ) {

				var productChildren = wceb_object.children;

				$.each( productChildren, function( index, child ) {

					$child_input = $('input[name="quantity[' + child + ']"]');

					// Sold individually products
					if ( $child_input.is( '.wc-grouped-product-add-to-cart-checkbox' ) ) {

						if ( $child_input.is( ':checked' ) ) {
							quantity = 1;
						} else {
							quantity = 0;
						}

					} else {
						quantity = $child_input.val();
					}

					if ( quantity > 0 ) {
						children[child] = quantity;
					}

				});

			}  else if ( wceb.productType === 'bundle' ) {

				var $bundle_data = $('.cart.bundle_data');
				var item_id      = $bundle_data.data('bundle_id');

				if ( $bundle_data.find( 'input[name="quantity"]').length ) {
					var bundle_qty = $bundle_data.find( 'input[name="quantity"]').val();
				} else {
					var bundle_qty = 1;
				}

				// Store parent ID
				children[item_id] = bundle_qty;

				var $bundled_items = $body.find('.bundled_product .cart');

				$bundled_items.each( function() {

					$this     = $(this);
					optional  = $this.data('optional');
					bundle    = $this.data('bundled_item_id');
					child     = $this.data('product_id');
					variation = $this.find('input[name="bundle_variation_id_' + bundle + '"]').val();
					quantity  = $this.find('.bundled_qty').val();

					var id = ( typeof variation === 'undefined' ) ? child : variation;

					if ( optional === 'yes' ) {

						var checked = $('input[name="bundle_selected_optional_' + bundle + '"]').is(':checked');

						if ( false === checked ) {
							quantity = 0;
						}

					}

					if ( id !== '' && quantity > 0 ) {

						children[id] = quantity;

					}

				});

			}

			return children;

		}

		/**
		* Get min and max from a given date, 'operator' depends on the picker set when the function is called (plus or minus)
		*/
		wceb.get.minAndMax = function( disabledDate, operator ) {

            var selectedMinDate = new Date( disabledDate.year, disabledDate.month, disabledDate.date ); // Selected date
            var selectedMaxDate = new Date( disabledDate.year, disabledDate.month, disabledDate.date ); // Selected date
            
			var firstAvailableDates = wceb.get.firstAvailableDates();
			var firstAvailableDate  = operator === 'minus' ? firstAvailableDates['start'].obj : firstAvailableDates['end'].obj; // First available date

			// After setting the end date, if there is no maximum booking duration
			if ( operator === 'minus' && wceb.bookingMax === '' ) {
				// Set min to the first available date
				var selectedMinDate = firstAvailableDate;
			}

			// After setting start date
			if ( operator === 'plus' ) {

				selectedMinDate.setDate( selectedMinDate.getDate() + wceb.bookingMin );

				if ( wceb.bookingMax !== '' ) {
					selectedMaxDate.setDate( selectedMaxDate.getDate() + wceb.bookingMax );
				}

			// After setting end date (reverse min and max)
			} else {

				selectedMaxDate.setDate( selectedMaxDate.getDate() - wceb.bookingMin );

				// If a maxium booking duration is set
				if ( wceb.bookingMax !== '' ) {
					selectedMinDate.setDate( selectedMinDate.getDate() - wceb.bookingMax );
				}

			}
			
			// Check if minimum date is not inferior to the first available date
			if ( firstAvailableDate > selectedMinDate ) {
				selectedMinDate = firstAvailableDate; // If it is, set minimum to first available day
			}

			// If no maximum booking duration is set, set it to false
			if ( operator === 'plus' && wceb.bookingMax === '' ) {
				selectedMaxDate = false;
			}

			// Set maximum to maximum option (max year) if false
			if ( ! selectedMaxDate || wceb.maxOption < selectedMaxDate ) {
				selectedMaxDate = wceb.maxOption;
			}

			var minAndMax = {};
	 		minAndMax['min'] = selectedMinDate;
	 		minAndMax['max'] = selectedMaxDate;

			return minAndMax;

		}

		/**
		* Get the closest disabled date from a given date, 'direction' depends on the picker set when the function is called ('inferior' or 'superior')
		*/
		wceb.get.closestDisabled = function( time, picker, otherPicker, direction ) {
			var selectedDate = new Date( time ), // Get Selected date
				selectedDay  = selectedDate.getDay(); // Get selected day (1, 2, 3, 4, 5, 6, 7)

			if ( wceb.firstWeekday !== '1' ) { // If first weekday is Sunday, add 1 day (because date object day starts at 0 and JS calendar start at 1)
				selectedDay += 1;
			}

			var pickerDisabled      = picker.get('disable'),
				otherPickerDisabled = otherPicker.get('disable'),
				disabled            = wceb.allowDisabled === 'no' ? pickerDisabled.concat( otherPickerDisabled ) : pickerDisabled,
				disabledTime        = [];

			$.each( disabled, function( index, date ) {

				// [year, month, day, type]
				if ( wceb.checkIf.isArray( date ) ) {

					// Backward compatibility - Availability Check | Disable dates
					if ( typeof date[3] === 'undefined' ) {
						date[3] = date['type'];
					}

					if ( date[3] === 'booked' || wceb.allowDisabled === 'no' ) {
						var getDate = new Date( date[0], date[1], date[2] );
						disabledTime.push( getDate.getTime() );
					}

				// { from: date, to: date, type: type }
				} else if ( wceb.checkIf.isObject( date ) ) {

					var getDate = direction === 'superior' ? new Date( date.from[0], date.from[1], date.from[2] ) : new Date( date.to[0], date.to[1], date.to[2] );
					
					if ( date.type === 'booked' || wceb.allowDisabled === 'no' ) {
						disabledTime.push( getDate.getTime() );
					}

				// Date object
				} else if ( wceb.checkIf.isDate( date ) ) {

					disabledTime.push( date.getTime() );

				// 1, 2, 3, 4, 5, 6, 7
				} else if ( wceb.allowDisabled === 'no' && wceb.checkIf.isDay( date ) ) {

					if ( direction === 'superior' ) {

						var interval = Math.abs( selectedDay - date );

						if ( interval === 0 )
							interval = 7;

						if ( date < selectedDay && interval !== 7 )
							interval = 7 - interval;

						var nextDisabledDay = selectedDate.setDate( selectedDate.getDate() + interval );
						
						disabledTime.push( nextDisabledDay );
						selectedDate = new Date( time ); // Reset selected date

					} else if ( direction === 'inferior' ) {

						var interval = Math.abs( selectedDay - date );

						if ( interval === 0 )
							interval = 7;

						if ( selectedDay < date && interval !== 7 )
							interval = 7 - interval;
						
						previousDisabledDay = selectedDate.setDate( selectedDate.getDate() - interval );
						disabledTime.push( previousDisabledDay );

					}

				}

			});
			
			disabledTime.sort();
			var closestDisabled = wceb.get.closest( disabledTime, time, direction );

			return closestDisabled;

		}

		/**
		* Get the closest date from a given date
		*/
		wceb.get.closest = function( arr, closestTo, direction ) {

			minClosest = false;

		    for ( var i = 0; i < arr.length; i++ ) { // Loop the array

		    	if ( direction === 'superior' ) {

		    		if ( arr[i] > closestTo ) { // Check if it's higher than the date
			    		minClosest = arr[i];
			    		break;
			    	} else {
			    		minClosest = false;
			    	}

		    	} else if ( direction === 'inferior' ) {

		    		if ( arr[i] < closestTo ) { // Check if it's lower than the date
			    		minClosest = arr[i];
			    	}

		    	}

		    }

		    return minClosest;
			
		}

		/**
		* Create date object ({date, day, month, object, pick, year})
		*/
		wceb.createDateObject = function( date, add ) {
			var dateObject = {};

			// If not date, get current date
			if ( ! date ) {
				var date = new Date();
			}

			// Maybe add days
			if ( add ) {
				date.setDate( date.getDate() + add );
			}

			// Create infinity object
			if ( date === 'infinity' ) {
				var dateObject = {
					date : Infinity,
					day  : Infinity,
					month: Infinity,
					obj  : Infinity,
					pick : Infinity,
					year : Infinity
				}

				return dateObject;
			}

			// Check if is valid date
			if ( ! wceb.checkIf.isDate( date ) ) {
				return dateObject;
			}

			// Set date to 00:00
			date.setHours(0,0,0,0);

			// Create date object
			var dateObject = {
				date : date.getDate(),
				day  : date.getDay(),
				month: date.getMonth(),
				obj  : date,
				pick : date.getTime(),
				year : date.getFullYear()
			}

			return dateObject;
		}

		/**
		* Clear session
		*/
		wceb.clearBookingSession = function() {

			$booking_price.find('.price').html('');
			$('.booking_details').html('');
			$add_to_cart_button.addClass( 'date-selection-needed' );

		}

		/**
		* Apply booking duration after settings one of the pickers
		*/
		wceb.applyBookingDuration = function( picker, pickerItem, selected ) {

			var alreadyDisabled = pickerItem.disable, // Get already disabled dates
				thingToCheck    = picker === 'end' ? pickerItem.max : pickerItem.min;

			// Get selected date on the other datepicker
			var selectedDate = new Date( selected.year, selected.month, selected.date );

			// Get last, current and next month duration (in days), relative to the current view
			var view                   = pickerItem.view, // Get current view on the current picker
				lastDayOfPreviousMonth = new Date( view.year, view.month, 0 ).getDate(), // Number of days of last month (relative to view)
				lastDayOfTheMonth      = new Date( view.year, view.month + 1, 0 ).getDate(), // Number of days of viewed month
				lastDayOfNextMonth     = new Date( view.year, view.month + 2, 0 ).getDate(); // Number of days of next month (relative to view)

			// Get the total of days to disable dates (3 months)
			var remainingDays = parseInt( lastDayOfPreviousMonth + lastDayOfTheMonth + lastDayOfNextMonth );

			// Difference between the selected day and the view (in days)
			var diff = Math.abs( Math.round( ( view.pick - selected.pick ) / 86400000 ) );

			// Number of days to start counting
			var diffMinus = picker === 'end' ? parseInt( diff - lastDayOfPreviousMonth ) : parseInt( diff - lastDayOfTheMonth - lastDayOfNextMonth );

			// Number of days to end counting
			var diffPlus = picker === 'end' ? parseInt( diff - lastDayOfPreviousMonth + remainingDays ) : parseInt( diff - lastDayOfNextMonth + remainingDays );
			
			if ( picker === 'end' && diff < lastDayOfPreviousMonth ) {
				var diffMinus = wceb.bookingDuration;
				var diffPlus  = parseInt( diff + lastDayOfTheMonth + lastDayOfNextMonth );
			}

			if ( picker === 'start' && diffMinus < lastDayOfNextMonth ) {
				var diffMinus = wceb.bookingDuration;
				var diffPlus  = parseInt( diff + lastDayOfTheMonth + lastDayOfPreviousMonth );
			}

			if ( diffMinus < 0 ) {
				diffMinus = 0;
			}

			if ( diffMinus < wceb.bookingDuration ) {

				diffMinus = wceb.bookingDuration;

			} else {

				var j;
				var multiples = [];

				// Get the closest multiple of the booking duration
				for ( j = 0; j <= diffMinus; j+= wceb.bookingDuration ) {
					multiples.push( j );
				}

				var diffMinus = multiples.slice(-1)[0]; // Get last value

			}

			if ( wceb.calcMode === 'days' ) {
				diffMinus -= 1;
			}

			var i;
			var enabled = [1,2,3,4,5,6,7]; // Disable every day

			first = false;

			for ( i = diffMinus; i <= diffPlus; i+= wceb.bookingDuration ) {

				var baseSelectedDate = new Date( selected.year, selected.month, selected.date ); // Selected date in the other picker

				if ( picker === 'start' ) {
					baseSelectedDate.setDate( selectedDate.getDate() - i ); // Remove booking duration
				} else if ( picker === 'end' ) {
					baseSelectedDate.setDate( selectedDate.getDate() + i ); // Add booking duration
				}

				dateToEnable = wceb.createDateObject( baseSelectedDate );

				// If the date is before the minimum set or after the maximum set, stop
				if ( ( picker === 'end' && dateToEnable.obj > thingToCheck.obj ) || ( picker === 'start' && dateToEnable.obj < thingToCheck.obj ) ) {
					break;
				}

				// Check if the date is disabled
				if ( typeof alreadyDisabled !== 'undefined' && alreadyDisabled.length > 0 ) {
					var d = wceb.checkIf.isDisabled( alreadyDisabled, dateToEnable );
				}

				// If it is disabled, don't enable it
				if ( true === d ) {
					continue;
				}
				
				enabled.push( [dateToEnable.year, dateToEnable.month, dateToEnable.date, 'inverted'] ); // add 'inverted' to enable date
			}

			pickerItem.disable = alreadyDisabled.concat( enabled ); // Merge arrays

			return false;
		}

		/**
		* Format price
		*/
		wceb.formatPrice = function( price ) {

			formatted_price = accounting.formatMoney( price, {
				symbol 		: wceb_object.currency_format_symbol,
				decimal 	: wceb_object.currency_format_decimal_sep,
				thousand	: wceb_object.currency_format_thousand_sep,
				precision 	: wceb_object.currency_format_num_decimals,
				format		: wceb_object.currency_format
			} );

			return formatted_price;

		}

		/**
		* Ajax request to calculate and return price, and store booking session
		*/
		wceb.setPrice = function() {

			var variation_id = $variation_input.val();

			children = wceb.get.childrenIds();
			
			selectedDates = {};

			var format = $.fn.pickadate.defaults.format;

			// Start date
			selectedDates['startFormat'] = pickerStart.get('select', 'yyyy-mm-dd'); // yyyy-mm-dd

			// End date
			selectedDates['endFormat'] = pickerEnd.get('select', 'yyyy-mm-dd'); // yyyy-mm-dd

			// WooCommerce Product Add-ons compatibility
			var additionalCost = wceb.get.additionalCosts( 'each', 'raw-price' );

			var data = {
				security       : document.getElementsByName('_wceb_nonce')[0].value,
				product_id     : $('input[name="add-to-cart"], button[name="add-to-cart"]').val(),
				quantity       : $qty_input.val(),
				variation_id   : variation_id,
				children       : children,
				start_format   : selectedDates['startFormat'],
				end_format     : selectedDates['endFormat'],
				additional_cost: additionalCost
			};

			if ( typeof wc_pb_bundle_scripts !== 'undefined' && typeof wc_pb_bundle_scripts[data.product_id] !== 'undefined' ) {

				var bundle = wc_pb_bundle_scripts[data.product_id],
					bundle_configuration = bundle.api.get_bundle_configuration();

				$.each( bundle_configuration, function ( id, bundle_data ) {

					if ( bundle_data['quantity'] > 0 ) {

						data['bundle_quantity_' + id] = bundle_data['quantity'];

						if ( typeof bundle_data['variation_id'] !== 'undefined' ) {
							data['bundle_variation_id_' + id] = bundle_data['variation_id'];
						}

						var $bundled_item_cart = $('body').find('.cart[data-bundled_item_id="' + id + '"]');

						if ( $bundled_item_cart.data( 'optional' ) === 'yes' || $bundled_item_cart.data( 'optional' ) === 1 ) {
							data['bundle_selected_optional_' + id] = 'yes';
						}

					}

				});
				
			}

			$('form.cart, form.bundle_form').fadeTo('400', '0.6').css( 'cursor', 'wait' );

			$.post( wceb_ajax_url.toString().replace( '%%endpoint%%', 'set_booking_session' ), data, function( response ) {

				$('.woocommerce-error, .woocommerce-message').remove();
				fragments = response.fragments;
				error     = response.error;

				// If error, reset pickers
				if ( error ) {

					$('.wceb_picker_wrap').prepend( '<div class="wceb_error woocommerce-error">' + error + '</div>' );

					// Reset pickers
					wceb.pickers.reset();

					$reset_dates.hide();

					// Unblock
					$('form.cart, form.bundle_form').fadeTo( 0, '1' ).css( 'cursor', 'auto' );

					return false;

				}

				if ( fragments ) {

					$.each( fragments, function( key, value ) {
						$( key ).replaceWith( value );
					});
					
					// Multiply booking price by quantity selected
					var new_price = wceb.formatPrice( fragments.booking_price );
					var price_html = '<span class="woocommerce-Price-amount amount">' + new_price + '</span>' + wceb_object.price_suffix;

					// If the product is on sale
					if ( fragments.booking_regular_price !== '' ) {
						var new_regular_price = wceb.formatPrice( fragments.booking_regular_price );
						var price_html = '<del><span class="woocommerce-Price-amount amount">' + new_regular_price + wceb_object.price_suffix + '</span></del> <ins><span class="woocommerce-Price-amount amount">' + new_price + wceb_object.price_suffix + '</span></ins>';
						$booking_price.attr('data-booking_regular_price', fragments.booking_regular_price );
					} else {
						$booking_price.attr('data-booking_regular_price', fragments.booking_price );
					}

					// Update price
					$booking_price.attr('data-booking_price', fragments.booking_price )
								       .find('.price').html( price_html );

				}

				$body.trigger( 'update_price', [ data, response ] );

				// Unblock
				$('form.cart, form.bundle_form').fadeTo( 0, '1' ).css( 'cursor', 'auto' );
			
			});
		}

		/**
		* Set picker (one date only)
		*/
		wceb.picker.set = function() {
			
			var variation_id = $variation_input.val();

			children = wceb.get.childrenIds();

			selectedDates['startFormat'] = pickerStart.get('select', 'yyyy-mm-dd'); // yyyy-mm-dd

			var data = {
				security       : document.getElementsByName('_wceb_nonce')[0].value,
				product_id     : $('input[name="add-to-cart"], button[name="add-to-cart"]').val(),
				quantity       : $qty_input.val(),
				variation_id   : variation_id,
				children       : children,
				start_format   : selectedDates['startFormat'],
				additional_cost: wceb.get.additionalCosts( 'each', 'raw-price' )
			};
			
			if ( typeof wc_pb_bundle_scripts !== 'undefined' && typeof wc_pb_bundle_scripts[data.product_id] !== 'undefined' ) {

				var bundle = wc_pb_bundle_scripts[data.product_id],
					bundle_configuration = bundle.api.get_bundle_configuration();

				$.each( bundle_configuration, function ( id, bundle_data ) {

					if ( bundle_data['quantity'] > 0 ) {

						data['bundle_quantity_' + id] = bundle_data['quantity'];

						if ( typeof bundle_data['variation_id'] !== 'undefined' ) {
							data['bundle_variation_id_' + id] = bundle_data['variation_id'];
						}

						var $bundled_item_cart = $('body').find('.cart[data-bundled_item_id="' + id + '"]');

						if ( $bundled_item_cart.data( 'optional' ) === 'yes' || $bundled_item_cart.data( 'optional' ) === 1 ) {

							data['bundle_selected_optional_' + id] = 'yes';
						}

					}

				});
				
			}

			$('form.cart, form.bundle_form').fadeTo('400', '0.6').css( 'cursor', 'wait' );

			$.post( wceb_ajax_url.toString().replace( '%%endpoint%%', 'set_booking_session' ), data, function( response ) {

				$('.woocommerce-error, .woocommerce-message').remove();
				fragments = response.fragments;
				error    = response.error;

				if ( error ) {

					$('.wceb_picker_wrap').prepend( '<div class="wceb_error woocommerce-error">' + error + '</div>' );

					// Reset pickers
					wceb.pickers.reset();

					$reset_dates.hide();

					// Unblock
					$('form.cart, form.bundle_form').fadeTo( 0, '1' ).css( 'cursor', 'auto' );

					return false;

				}

				if ( fragments ) {

					$.each(fragments, function(key, value) {
						$(key).replaceWith(value);
					});

					if ( fragments.booking_price ) {

						// Multiply booking price by quantity selected
						var new_price = wceb.formatPrice( fragments.booking_price );
						var price_html = '<span class="woocommerce-Price-amount amount">' + new_price + '</span>' + wceb_object.price_suffix;

						// If the product is on sale
						if ( fragments.booking_regular_price !== '' ) {
							var new_regular_price = wceb.formatPrice( fragments.booking_regular_price );
							var price_html = '<del><span class="woocommerce-Price-amount amount">' + new_regular_price + '</span></del> <ins><span class="woocommerce-Price-amount amount">' + new_price + '</span></ins>';
							$booking_price.attr('data-booking_regular_price', fragments.booking_regular_price );
						}

						// Update price
						$booking_price.attr('data-booking_price', fragments.booking_price )
									       .find('.price').html( price_html );

					}

				}

				$body.trigger( 'update_price', [ data, response ] );

				// Unblock
				$('form.cart, form.bundle_form').fadeTo( 0, '1' ).css( 'cursor', 'auto' );
			
			});
		}

		/**
		* Open the second picker when selecting a date
		*/
		wceb.picker.close = function( picker, secondPicker ) {

			// Bug fix
			$( document.activeElement ).blur();

			if ( wceb.dateFormat === 'two' ) {

				var thisSet   = picker.get('select'),
					secondSet = secondPicker.get('select');

				// Open other picker if current picker is set and other not
				if ( wceb.checkIf.dateIsSet( thisSet ) && ! wceb.checkIf.dateIsSet( secondSet ) ) {
					setTimeout( function() { secondPicker.open(); }, 250 );
				}

			}

		}

		/**
		* Init or reset pickers
		*/
		wceb.pickers.init = function() {

			// Reset disabled dates
			pickerStartItem.disable = [];

			if ( wceb.dateFormat === 'two' ) {
				pickerEndItem.disable   = [];
			}

			var firstAvailableDates = wceb.get.firstAvailableDates();

			var firstDay = firstAvailableDates['start'];

			var minObject = firstDay,
				max       = wceb.createDateObject( wceb.maxOption ),
				view      = wceb.createDateObject( new Date( minObject.year, minObject.month, 1 ) );

			pickerStartItem.clear     = null;
			pickerStartItem.select    = undefined;
			pickerStartItem.min       = minObject;
			pickerStartItem.max       = max;
			pickerStartItem.highlight = minObject;
			pickerStartItem.view      = view;

			pickerStart.$node.val('');

			if ( wceb.dateFormat === 'two' ) {

				var endFirstDay = firstAvailableDates['end'];

				var endMinObject = endFirstDay,
					endView      = wceb.createDateObject( new Date( endMinObject.year, endMinObject.month, 1 ) );
			
				pickerEndItem.clear     = null;
				pickerEndItem.select    = undefined;
				pickerEndItem.min       = endMinObject;
				pickerEndItem.max       = max;
				pickerEndItem.highlight = endMinObject;
				pickerEndItem.view      = endView;

				pickerEnd.$node.val('');

			}

			$add_to_cart_button.addClass( 'date-selection-needed' );

			return false;

		}

		/**
		* Renders pickers and triggers event
		*/
		wceb.pickers.render = function( ids ) {

			$body.trigger( 'pickers_init', ids );

			pickerStart.render();
			pickerEnd.render();
			
			$body.trigger( 'after_pickers_init', ids );

		}

		/**
		* Inits and renders pickers
		*/
		wceb.pickers.reset = function() {

			wceb.pickers.init();

			if ( wceb.productType === 'variable' ) {
				var variation_id = $variation_input.val();
				var variation = {};
				variation['variation_id'] = variation_id;
				wceb.pickers.render( variation );
			} else if ( wceb.productType === 'grouped' || wceb.productType === 'bundle' ) {
				var ids = $reset_dates.attr('data-ids');
				
				if ( ids !== "" ) {
					ids = JSON.parse( ids );
				}
				
				wceb.pickers.render( ids );
				
			} else {
				wceb.pickers.render();
			}

		}

		/**
		* Clear the other picker
		*/
		wceb.pickers.clearSecond = function( picker, secondPicker, secondPickerObject ) {

			var secondPickerItem = secondPickerObject.component.item,
				firstAvailableDates = wceb.get.firstAvailableDates(),
				min  = firstAvailableDates[secondPicker],
				max  = wceb.createDateObject( wceb.maxOption ),
				view = wceb.createDateObject( new Date( min.year,min.month,01 ) );

			secondPickerItem.disable = [];
			secondPickerItem.min     = min;
			secondPickerItem.max     = max;

			if ( secondPickerObject.get('select') === null ) {
				secondPickerItem.highlight = min;
				secondPickerItem.view      = view;
			}

			$body.trigger( 'clear_' + picker + '_picker', secondPickerItem );

			secondPickerObject.render();

		}

		/**
		* Set the other picker and call Ajax function if both pickers are set
		*/
		wceb.pickers.set = function( picker, pickerObject, secondPickerObject, secondPickerItem ) {

			var selectedObject       = pickerObject.get('select'), // Array [year,month,date,day,obj,pick]
				secondSelectedObject = secondPickerObject.get('select'); // Array [year,month,date,day,obj,pick]

			if ( selectedObject === null ) {
				return;
			}

			var direction = picker === 'start' ? 'superior' : 'inferior',
				calc      = picker === 'start' ? 'plus' : 'minus';

			var selectedTimestamp = selectedObject.pick; // Unix timestamp

			var minAndMax = wceb.get.minAndMax( selectedObject, calc ),
				min       = minAndMax.min,
				max       = minAndMax.max;

			var thingToSet = picker === 'start' ? max : min;

			// If no maximum date is set, set max to maximum year
			if ( ! max ) {
				max = wceb.maxOption;
			}

			// Get the closest disabled date
			var closestDisabled = wceb.get.closestDisabled( selectedTimestamp, secondPickerObject, pickerObject, direction );

			// If a date is disabled, maybe set it as min and/or max (depending on calendar)
			if ( closestDisabled ) {

				var closestAvailable = new Date( closestDisabled ); // Convert to date

				if ( ( picker === 'start' && closestDisabled < thingToSet ) || ( picker === 'end' && closestDisabled > thingToSet ) ) {
					var thingToSet = closestAvailable;
				}

			}

			var min  = picker === 'start' ? wceb.createDateObject( min ) : wceb.createDateObject( thingToSet ),
				max  = picker === 'start' ? wceb.createDateObject( thingToSet ) : wceb.createDateObject( max ),
				view = wceb.createDateObject( new Date( min.year, min.month, 1 ) );

			secondPickerItem.min  = min;
			secondPickerItem.max  = max;
			secondPickerItem.view = view;

			// If other picker is not set
			if ( typeof secondSelectedObject === 'undefined' || secondSelectedObject === null ) {
				secondPickerItem.highlight = min;
			}

			$body.trigger('set_' + picker + '_picker', [secondPickerItem, selectedTimestamp] );

			secondPickerObject.render();

			// If both pickers are set
			if ( wceb.checkIf.datesAreSet() ) {
				wceb.setPrice(); // Ajax request to calculate price and store session data
			}

		}

		pickerStart.on({
			render: function() {

				// Display picker title
				pickerStart.$root.find('.picker__header').prepend('<div class="picker__title">' + wceb_object.start_text + '</div>');

			},
			set: function( startTime ) {

				// If picker is cleared
				if ( typeof startTime.clear !== 'undefined' && startTime.clear === null ) {

					if ( wceb.dateFormat === 'two' ) {
						// Reset min, max and disabled dates on other picker
						wceb.pickers.clearSecond( 'start', 'end', pickerEnd );

						if ( ! wceb.checkIf.dateIsSet( 'end' ) ) {
							$reset_dates.hide();
						}
					}

					selectedDates['startFormat'] = null;

					$body.trigger( 'clear_start_date' );

				}

				// If picker is set
				if ( wceb.dateFormat === 'two' && wceb.checkIf.dateIsSet( startTime.select ) ) {
					wceb.pickers.set( 'start', pickerStart, pickerEnd, pickerEndItem );
					$reset_dates.show();
				} else if ( wceb.dateFormat === 'one' && wceb.checkIf.dateIsSet( startTime.select ) ) {
					wceb.picker.set();
				}
				
			},
			close: function() {
				wceb.picker.close( pickerStart, pickerEnd );
			}
		});

		pickerEnd.on({
			render: function() {

				// Display picker title
				pickerEnd.$root.find('.picker__header').prepend('<div class="picker__title">' + wceb_object.end_text + '</div>');
				
			},
			set: function( endTime ) {

				// If picker is cleared
				if ( typeof endTime.clear !== 'undefined' && endTime.clear === null ) {

					// Reset min, max and disabled dates on other picker
					wceb.pickers.clearSecond( 'end', 'start', pickerStart );

					if ( ! wceb.checkIf.dateIsSet( 'start' ) ) {
						$reset_dates.hide();
					}

					selectedDates['endFormat'] = null;

					$body.trigger( 'clear_end_date' );

				}

				// If picker is set
				if ( wceb.dateFormat === 'two' && wceb.checkIf.dateIsSet( endTime.select ) ) {
					wceb.pickers.set( 'end', pickerEnd, pickerStart, pickerStartItem );
					$reset_dates.show();
				}

				return false;
				
			},
			close: function() {
				wceb.picker.close( pickerEnd, pickerStart );
			}
		});

		$body.on('pickers_init', function( e, variation ) {

			var firstAvailableDates = wceb.get.firstAvailableDates();

			var first = firstAvailableDates['start'];

			pickerStartItem.view = wceb.createDateObject( new Date( first.year,first.month,01 ) ); // First day of the first available date month
			pickerStartItem.highlight = first; // First available date

			if ( wceb.dateFormat === 'two' ) {

				var endFirst = firstAvailableDates['end'];

				pickerEndItem.view = wceb.createDateObject( new Date( endFirst.year,endFirst.month,01 ) ); // First day of the first available date month
				pickerEndItem.highlight = endFirst; // First available date
				pickerEndItem.min = endFirst; // First available date

			}

			return false;
		});

		/**
		* Before rendering the start picker
		*/
		pickerStart.on( 'before_rendering', function() {

			if ( wceb.dateFormat === 'two' ) {

				var selected = pickerEnd.get('select'); // Get selected date on the End picker

				startPickerDisabled = pickerStartItem.disable; // Store already disabled dates

				if ( wceb.checkIf.dateIsSet( selected ) && wceb.bookingDuration > 1  ) {
					wceb.applyBookingDuration( 'start', pickerStartItem, selected );
				}

			}

		});

		/**
		* After rendering the start picker
		*/
		pickerStart.on( 'after_rendering', function() {

			if ( wceb.dateFormat === 'two' ) {
				pickerStartItem.disable = startPickerDisabled; // Reset disabled dates
			}

		});

		/**
		* Before rendering the end picker
		*/
		pickerEnd.on( 'before_rendering', function() {

			var selected = pickerStart.get('select'); // Get selected date on the Start picker

			endPickerDisabled = pickerEndItem.disable; // Store already disabled dates

			if ( wceb.checkIf.dateIsSet( selected ) && wceb.bookingDuration > 1 ) {
				wceb.applyBookingDuration( 'end', pickerEndItem, selected );
			}

		});

		/**
		* After rendering the end picker
		*/
		pickerEnd.on( 'after_rendering', function() {
			pickerEndItem.disable = endPickerDisabled; // Reset disabled dates
		});

		/**
		* Update booking price when changing product quantity
		*/
		$cart.on('change', 'input[name="quantity"]', function( e ) {

			if ( wceb.dateFormat === 'two' && wceb.checkIf.datesAreSet() ) {
				wceb.setPrice();
			} else if ( wceb.dateFormat === 'one' && wceb.checkIf.dateIsSet( 'start' ) ) {
				wceb.picker.set();
			} else {
				var formatted_total = wceb.formatPrice( wceb.get.basePrice() );
				$booking_price.find('.price .amount').html( formatted_total );

				formatted_regular_price = wceb.formatPrice( wceb.get.regularPrice() );
				$booking_price.find('.price del .amount').html( formatted_regular_price );
			}

			e.stopPropagation();

		});

		$body.on( 'update_price', function() {
			$add_to_cart_button.removeClass( 'date-selection-needed' );
		});

		/**
		* WooCommerce Product Add-ons compatibility
		*/
		$cart.on( 'updated_addons', function() {

			if ( wceb.dateFormat === 'two' && wceb.checkIf.datesAreSet() ) {
				wceb.setPrice();
			} else if ( wceb.dateFormat === 'one' && wceb.checkIf.dateIsSet( 'start' ) ) {
				wceb.picker.set();
			} else {
				var formatted_total = wceb.formatPrice( wceb.get.basePrice() );
				$booking_price.find('.price .amount').html( formatted_total );

				formatted_regular_price = wceb.formatPrice( wceb.get.regularPrice() );
				$booking_price.find('.price del .amount').html( formatted_regular_price );
			}
			
		});

		$body.on( 'clear_start_date clear_end_date', function() {

			// Clear session
			wceb.clearBookingSession();

		});


		$add_to_cart_button.on( 'click', function(e) {

			$this = $(this);

		    if ( $this.is( '.disabled,.date-selection-needed' ) && ! $this.hasClass( 'wc-variation-selection-needed' ) && ! $this.hasClass( 'wc-variation-is-unavailable' ) ) {

		        e.preventDefault();
		        window.alert( wceb_object.select_dates_message );
		        e.stopPropagation();

		    }

		});

		$reset_dates.on( 'click', function(e) {
			e.preventDefault();

			// Reset pickers
			wceb.pickers.reset();

			// Clear session
			wceb.clearBookingSession();

			$(this).hide();
			
		}).hide();

	});

}(jQuery));