(function($, w) {
	'use strict';

	let opbXhr = null;

	let currency = null;

	let searchData = {};

	let shouldRefreshAfterBook = false;

	const servicesOptionsPool = {};

	// helper method used to fetch the options assigned to the requested service
	const getServiceOptions = (id_service) => {
		return new Promise((resolve, reject) => {
			if (servicesOptionsPool.hasOwnProperty(id_service)) {
				// return cached options
				resolve(servicesOptionsPool[id_service]);
				return;
			}

			if (UIAjax.isDoing()) {
				opbXhr.abort();
			}

			opbXhr = UIAjax.do(
				ONEPAGE_BOOKING_CONFIG.serviceOptionsUrl,
				{
					id_ser: id_service,
					mode: 'html',
				},
				(html) => {
					if (html) {
						html = $(html);
					}

					// cache result
					servicesOptionsPool[id_service] = html;

					// resolve promise
					resolve(html);
				},
				(error) => {
					// reject promise
					reject(error);
				}
			);
		});
	}

	// Helper method used to safely detach the registered options from the document.
	// If we replace an element from the document without detaching it first, the next
	// time we use it the JS interpreter might throw an exception.
	const detachServicesOptions = () => {
		for (let k in servicesOptionsPool) {
			if (!servicesOptionsPool.hasOwnProperty(k)) {
				continue;
			}

			if (servicesOptionsPool[k]) {
				servicesOptionsPool[k].detach();
			}
		}
	}

	// helper function used to fetch the currently selected date
	const getSelectedDate = (service) => {
		// find service container
		const container = $(service).closest('.vap-opb-container');

		// set min service date
		const minServiceDate = $(service).data('first-date');
		// set max service date
		const maxServiceDate = $(service).data('last-date');

		// get currently selected date
		const selectedDate = container.find('input.date-field').val();

		// make sure the selected date is between the specified range
		if (!selectedDate || selectedDate < minServiceDate || (maxServiceDate && selectedDate > maxServiceDate)) {
			// no specified date, use the first available one
			return setSelectedDate(minServiceDate, service);
		}

		// always recalculate the status of the prev/next links
		return setSelectedDate(selectedDate, service);
	}

	// helper function used to update the currently selected date
	const setSelectedDate = (date, service, refresh) => {
		if (date instanceof Date) {
			// extract military format from date object
			date = [
				date.getFullYear(),
				(date.getMonth() < 9 ? '0' : '') + (date.getMonth() + 1),
				(date.getDate() < 10 ? '0' : '') + date.getDate(),
			].join('-');
		}

		const min = $(service).data('first-date');
		const max = $(service).data('last-date');

		if (min > date || (max && date > max)) {
			// invalid date
			return false;
		}

		// find service container
		const container = $(service).closest('.vap-opb-container');

		// update selected date for later use
		container.find('input.date-field').val(date);

		/**
		 * Refresh date badge too
		 * 
		 * @since 1.0.6  Append a fixed time to make sure the hours are not automatically adjusted from UTC,
		 *               which could lead to a shifted date in the past/future.
		 */
		container.find('.selected-date-badge').html(new Date(date + 'T00:00:00').toLocaleDateString());
		
		if (min == date) {
			container.find('.prev-date-link').addClass('disabled');
		} else {
			container.find('.prev-date-link').removeClass('disabled');
		}

		if (max && max == date) {
			container.find('.next-date-link').addClass('disabled');
		} else {
			container.find('.next-date-link').removeClass('disabled');
		}

		if (refresh) {
			// trigger timeline refresh 
			container.find('button[data-role="timeline.request"]').trigger('click');
		}

		return date;
	}

	// helper function used to fetch the timezone selected by the user
	const getUserTimezone = (container) => {
		// find timezone dropdown
		const dropdown = $(container).find('.opb-timeline-timezone select');

		if (dropdown.length) {
			// dropdown found, fetch selected timezone
			return dropdown.select2('val');
		}

		// the timezone select is not a child of the specified container,
		// we need to go back to the module wrapper
		const contParent = $(container).closest('.vap-opb-container');

		// make sure the new parent is not equals to the passed container,
		// because "closest" might return the self element
		if (contParent.length && contParent.is(container) === false) {
			// retry with the new container
			return getUserTimezone(contParent);
		}

		// unable to find the timezone starting from the given container
		return undefined;
	}

	// helper function used to init the datepicker according to the service restrictions
	const initDatepicker = (service) => {
		const options = {};

		// set minimum date
		options.minDate = new Date($(service).data('first-date') + 'T00:00:00');

		if ($(service).data('last-date')) {
			// set maximum date
			options.maxDate = new Date($(service).data('last-date') + 'T00:00:00');
		}

		// date set date format
		options.dateFormat = 'yy-mm-dd';

		// set change callback
		options.onSelect = (date, instance) => {
			// force datepicker hiding in order to prevent delays that could
			// leave the datepicker open because of the destroy method
			$(instance.input).datepicker('hide');
			// refresh selected date (true: refresh timeline)
			setSelectedDate(date, service, true);
		}

		// init datepicker
		$(service).closest('.vap-opb-container')
			.find('input.date-field')
			.datepicker('destroy')
			.datepicker(options);
	}

	// helper function used to activate a specific panel
	const switchPanel = (container, panel) => {
		if (shouldRefreshAfterBook) {
			// make a refresh instead of accessing the next step
			if (ONEPAGE_BOOKING_CONFIG.currentUrl) {
				// prefer a redirect because the javascript refresh
				// might raise CORS errors
				document.location.href = ONEPAGE_BOOKING_CONFIG.currentUrl;
			} else {
				// fallback to native javascript refresh
				document.location.reload();
			}
			
			return;
		}

		// find any other panel and hide it
		$(container).find('.opb-step-wrapper')
			.not('.' + panel + '-box')
				.addClass('collapsed')
					.find('.opb-step-body')
						.slideUp();

		const activePanel = $(container).find('.opb-step-wrapper.' + panel + '-box');

		// activate the selected panel
		activePanel.removeClass('collapsed')
			.find('.opb-step-body')
				.slideDown();

		// wait until the animation is completed (approx. 500 ms)
		setTimeout(() => {
			// check whether the title of the selected step is out of the monitor
			let px_to_scroll = isBoxOutOfMonitor(activePanel.find('.opb-step-title'));

			if (px_to_scroll !== false) {
				// scroll the document to make the title visible
				$('html,body').animate({
					scrollTop: "+=" + (px_to_scroll - 15),
				}, {
					duration: 'fast',
				});
			}
		}, 500);
	}

	// helper function used to book an appointment
	const bookNowAction = (time, skipOptions) => {
		if (UIAjax.isDoing()) {
			return false;
		}

		let options = [];
		
		try
		{
			// extract selected options
			options = vapGetSelectedOptions();
		}
		catch (error)
		{
			if (error == 'MissingRequiredOptionException')
			{
				// do not proceed as the customer forgot to fill
				// one or more required fields
				return false;
			}

			// Proceed because the service doesn't own any option 
			// and the function vapGetSelectedOptions() hasn't been declared.
		}
		
		// remove required flag from options
		$('.option-required').removeClass('vapoptred');

		let addButton, loadingTarget;

		if (skipOptions) {
			addButton     = $(time).closest('.opb-time-slot-wrapper').find('button.book-now');
			loadingTarget = $(time).closest('.opb-time-slot-wrapper').find('.time-actions .loading-target');
		} else {
			addButton     = $(time).closest('.opb-time-slot-wrapper').find('button.add-cart');
			loadingTarget = $(time).closest('.opb-time-slot-wrapper').find('.opb-time-slot-extra .loading-target');
		}

		// hide button before the animation starts
		addButton.hide();

		// start the animation
		AnimationHandler.start(ONEPAGE_BOOKING_CONFIG.animationDuration, loadingTarget.show());

		let successful = false;

		// adds item into the cart
		OnePageBookingCart.add({
			id_ser:  searchData.id_ser,
			id_emp:  searchData.id_emp,
			date:    searchData.day,
			hour:    $(time).attr('data-hour'),
			min:     $(time).attr('data-min'),
			people:  searchData.people,
			options: options,
		}).then((resp) => {
			successful = true;

			// check whether a refresh is needed before accessing the next step
			shouldRefreshAfterBook = ONEPAGE_BOOKING_CONFIG.isRefreshNeededAfterBook;
		}).catch((error) => {
			if (error.statusText === 'abort') {
				// ignore error in case of abort
				return;
			}

			// register response HTTP error message
			alert(error.responseText || Joomla.JText._('VAP_OPB_GENERIC_ERROR'));
		}).finally(() => {
			// wait until the end of the animation
			AnimationHandler.end().then(() => {
				// hide loading box and display button again
				loadingTarget.hide().html('');
				addButton.show();

				if (!successful) {
					return;
				}

				// find time slot wrapper
				const wrapper = $(time).closest('.opb-time-slot-wrapper');
				// hide extras box
				wrapper.find('.opb-time-slot-extra').slideUp();

				// flag time slot as "booked"
				wrapper.addClass('time-slot-booked');

				// change button classes to prevent click event
				$(time).removeClass('clicked').addClass('in-cart');
				// replace button text
				$(time).text(Joomla.JText._('VAP_OPB_BOOKED_BUTTON'));

				const container = $(wrapper).closest('.vap-opb-container');

				// make the billing step clickable
				container.find('.opb-step-wrapper.billing-box')
					.addClass('clickable');

				// in case the shopping system is disabled, we should immediately
				// move to the next step (billing details)
				if (!ONEPAGE_BOOKING_CONFIG.isCartEnabled) {
					switchPanel(container, 'billing');
				}

				// show the button to access the next step
				container.find('.opb-step-wrapper.timeline-box .opb-next-button-box').show();
			}).catch(() => {
				// animation aborted
			});
		});
	}

	// helper function used to refresh the cart items
	const refreshCartItems = (container) => {
		// fetch cart item template
		const itemTemplate = $($('.opb-cart-item-tmpl').html());
		// find cart items container
		const cartWrapper = $(container).find('.opb-cart-container').html('');

		// iterate all cart items
		OnePageBookingCart.cart.forEach((item) => {
			// clone item template
			const tmpl = itemTemplate.clone();

			// set up item details
			tmpl.find('.item-details-main').html(item.details);

			// set up total cost
			if (item.totalcost > 0) {
				tmpl.find('.item-total').html(currency.format(item.totalcost));
			} else {
				tmpl.find('.item-total').remove();
			}

			// calculate checkout date time
			let checkout = new Date(item.checkin.replace(/\s/, 'T') + 'Z');
			checkout.setMinutes(checkout.getMinutes() + item.duration);
			// build check-out string
			let checkoutStr = checkout.toLocaleDateString([], {timeZone: getUserTimezone(container)}) + ' ' +
				getFormattedTime(checkout.getHours(), checkout.getMinutes(), ONEPAGE_BOOKING_CONFIG.timeFormat, getUserTimezone(container));
			// set up check-out details
			tmpl.find('.item-checkout').html(Joomla.JText._('VAPCHECKOUTAT').replace(/%s/, checkoutStr));

			// set up number of attendees
			if (item.people > 1) {
				tmpl.find('.item-people-inner').text(item.people);
			} else {
				tmpl.find('.item-people').remove();
			}

			// set up options
			if (item.options.length) {
				let options = $('<ul></ul>');

				item.options.forEach((option) => {
					// create option element
					const optElem = $('<li></li>');

					// create option title wrapper
					const optTitle = $('<div class="option-title"></div>');

					// append option quantity
					optTitle.append($('<small class="option-quantity"></small>').html(option.quantity + 'x'));

					// append option name
					optTitle.append($('<span class="option-name"></span>').html(option.name));

					// append option title
					optElem.append(optTitle);

					if (option.price > 0) {
						// append option price
						optElem.append($('<div class="option-price"></div>').html(currency.format(option.price)));
					}

					// append option element to list
					options.append(optElem);
				});

				// append options
				tmpl.find('.cart-item-options').html(options);
			} else {
				tmpl.find('.cart-item-options').remove();
			}

			// set up action button
			tmpl.find('button.cancel-appointment')
				.attr('data-checkin', item.checkin)
				.attr('data-service', item.id_service)
				.attr('data-employee', item.id_employee);

			// check if we are displaying the location info
			const locationBlock = tmpl.find('.item-details-main .cart-item-summary-location');

			if (locationBlock.length) {
				let href;

				if (navigator.isMac() || navigator.isiOS()) {
					// iPhone or Mac, open through native Maps app
					href = 'maps://?q=' + encodeURIComponent(locationBlock.text());
				} else if (navigator.isAndroid()) {
					// Android device, open through native Google Maps
					href = 'geo:0,0?q=' + encodeURIComponent(locationBlock.text());
				} else {
					// fallback to web Google Maps 
					href = 'https://maps.google.com/maps?q=' + encodeURIComponent(locationBlock.text());
				}

				// create link to see the address location on a map
				const locationInfoLink = $('<a target="_blank"></a>')
					.attr('href', href)
					.text(locationBlock.text());

				// replace location info text with the link
				locationBlock.html(locationInfoLink);
			}

			// include icons to main details
			tmpl.find('.item-details-main .cart-item-summary-service').prepend('<i class="fas fa-thumbtack"></i>');
			tmpl.find('.item-details-main .cart-item-summary-checkin').prepend('<i class="fas fa-calendar-check"></i>');
			tmpl.find('.item-details-main .cart-item-summary-location').prepend('<i class="fas fa-map-marker-alt"></i>');

			// display item into the cart
			cartWrapper.append(tmpl);
		});

		if (OnePageBookingCart.totalsHtml) {
			// refresh cart totals
			$(container).find('.opb-cart-totals')
				.html(OnePageBookingCart.totalsHtml)
					.find('.hasTooltip')
						.tooltip();
		}

		// toggle coupon code according to the cart total cost
		if (OnePageBookingCart.totalCost > 0) {
			$(container).find('.opb-cart-coupon').show();
		} else {
			$(container).find('.opb-cart-coupon').hide();

			// clear totals HTML too
			$(container).find('.opb-cart-totals').html('');
		}

		// toggle payments method according to the cart total gross
		if (OnePageBookingCart.totalGross > 0) {
			$(container).find('.opb-payments-list').show();
		} else {
			$(container).find('.opb-payments-list').hide();
		}
	}

	// helper function used to prevent the form submit
	const preventFormSubmit = (event) => {
		event.preventDefault();
		event.stopPropagation();
		return false;
	}

	$(function() {
		if (ONEPAGE_BOOKING_CONFIG.cart && ONEPAGE_BOOKING_CONFIG.cart.cart) {
			// inject cart details within cart handler
			OnePageBookingCart.cart       = ONEPAGE_BOOKING_CONFIG.cart.cart ? ONEPAGE_BOOKING_CONFIG.cart.cart : [];
			OnePageBookingCart.totalGross = ONEPAGE_BOOKING_CONFIG.cart.totalGross ? ONEPAGE_BOOKING_CONFIG.cart.totalGross : 0;
			OnePageBookingCart.totalCost  = ONEPAGE_BOOKING_CONFIG.cart.total ? ONEPAGE_BOOKING_CONFIG.cart.total : 0;
		}

		onInstanceReady(() => {
			// Delay execution until the currency has been declared.
			// This because the installed theme/template might load this script
			// before the currency instance.
			return typeof Currency !== 'undefined' && Currency.getInstance().symbol;
		}).then(() => {
			currency = Currency.getInstance();
		});

		// Flag used to check whether the ZIP of the user has been proprly validated.
		// The flag is automatically validated in case there is no ZIP field.
		let zipCodeValidated = ONEPAGE_BOOKING_CONFIG.zipCustomField ? false : true;

		// create custom fields validator
		const fieldsValidator = new VikFormValidator('.opb-custom-fields', 'vapinvalid');

		/**
		 * Overwrite getLabel method to properly access the
		 * label by using our custom layout.
		 *
		 * @param 	mixed  input  The input element.
		 *
		 * @param 	mixed  The label of the input.
		 */
		fieldsValidator.getLabel = (input) => {
			return $(input).closest('.cf-control').find('.cf-label *[id^="vapcf"]');
		}

		if (ONEPAGE_BOOKING_CONFIG.isCaptchaEnabled) {
			/**
			 * Add callback to validate whether the ReCAPTCHA quiz
			 * was completed or not.
			 *
			 * @return 	bool  True if completed, false otherwise.
			 */
			fieldsValidator.addCallback(() => {
				// get recaptcha elements
				const captcha = $('.vap-opb-container .opb-custom-fields .g-recaptcha').first();
				const iframe  = captcha.find('iframe').first();

				// get widget ID
				let widget_id = captcha.data('recaptcha-widget-id');

				// check if recaptcha instance exists
				// and whether the recaptcha was completed
				if (typeof grecaptcha !== 'undefined'
					&& widget_id !== undefined
					&& !grecaptcha.getResponse(widget_id)) {
					// captcha not completed
					iframe.addClass('vapinvalid');
					return false;
				}

				// captcha completed
				iframe.removeClass('vapinvalid');
				return true;
			});
		}

		if (ONEPAGE_BOOKING_CONFIG.zipCustomField) {
			// auto-trigger change on load to automatically validate the existing ZIP code, if any
			$('.vap-opb-container input[name="vapcf' + ONEPAGE_BOOKING_CONFIG.zipCustomField + '"]').on('change', function() { 
				// get selected ZIP code
				let zip = $(this).val();

				// reset ZIP status before validation
				zipCodeValidated = false;

				if (!zip) {
					// missing ZIP code, there's no need to interrogate the system
					return false;
				}

				UIAjax.do(
					ONEPAGE_BOOKING_CONFIG.validateZipUrl,
					{
						zip: zip,
					},
					(resp) => {
						// ZIP validated
						zipCodeValidated = true;
					},
					(err) => {
						if (err.responseText) {
							alert(err.responseText);
							// clear field value on error
							$(this).val('');
						}
					}
				);
			}).trigger('change');

			/**
			 * Add callback to validate the customer ZIP against the list
			 * of accepted ZIP codes.
			 *
			 * @return 	boolean  True if valid, false otherwise.
			 */
			fieldsValidator.addCallback(() => {
				const field = $('#vapcf' + ONEPAGE_BOOKING_CONFIG.zipCustomField);

				if (!zipCodeValidated) {
					fieldsValidator.setInvalid(field);
					return false;
				}

				fieldsValidator.unsetInvalid(field);
				return true;
			});
		}

		// render dropdowns with Select2 plugin
		$('.vap-opb-container .search-box select').select2({
			placeholder: Joomla.JText._('VAP_OPB_ANY_PLACEHOLDER'),
			allowClear: true,
			width: '100%',
		});

		// render dropdown elements with Select2 jQuery plugin
		$('.vap-opb-container .opb-custom-fields .cf-value select').each(function() {
			let option = $(this).find('option').first();

			let data = {
				// hide search bar in case the number of options is lower than 10
				minimumResultsForSearch: $(this).find('option').length >= 10 ? 1 : -1,
				// allow clear selection in case the value of the first option is empty
				allowClear: option.val() ? false : true,
				// take the whole space
				width: '100%',
			};

			if (data.allowClear && !$(this).prop('multiple')) {
				// set placeholder by using the option text
				data.placeholder = option.text();

				// unset the text from the option for a correct rendering
				option.text('');
			}

			$(this).select2(data);
		});

		$('.vap-opb-container .opb-step-wrapper.summary-box .opb-step-title').on('click', function(event) {
			const wrapper = $(this).closest('.opb-step-wrapper');

			wrapper.removeClass('clickable');

			if (!OnePageBookingCart.hasItems()) {
				// there are no items within the cart
				return false;
			}

			if (fieldsValidator.validate()) {
				// make step accessible
				wrapper.addClass('clickable');
				return true;
			}

			event.preventDefault();
			event.stopPropagation();

			// get first invalid input
			let input = $(fieldsValidator.form).find('.vapinvalid').filter('input,textarea,select').first();

			if (input.length == 0 || !input.is(':visible')) {
				// the input is not accessible, get the first invalid element
				input = $(fieldsValidator.form).find('.vapinvalid').first();
			}

			// animate page to the element found
			if (input.length) {
				$('html,body').stop(true, true).animate({
					scrollTop: $(input).offset().top - 100,
				}, {
					duration: 'medium',
				}).promise().done(() => {
					// try to focus the input
					$(input).focus();
				});
			}

			return false;
		});

		// switch tab when the step title gets clicked
		$('.vap-opb-container .opb-step-wrapper .opb-step-title').on('click', function() {
			const container = $(this).closest('.vap-opb-container');
			const wrapper   = $(this).closest('.opb-step-wrapper');

			if (wrapper.hasClass('clickable')) {
				// access panel only if it is clickable
				switchPanel(container, wrapper.data('step'));
			}
		});

		// switch tab when the step title gets clicked
		$('.vap-opb-container .opb-step-wrapper.summary-box .opb-step-title').on('click', function() {
			// refresh cart items while entering the summary box
			refreshCartItems($(this).closest('.opb-step-wrapper'));
		});

		// observe button click to access the next step
		$('.vap-opb-container button[data-role="booking.next"]').on('click', function() {
			// trigger "click" on the next wrapper to expand that step
			$(this).closest('.opb-step-wrapper')
				.next('.opb-step-wrapper')
					.find('.opb-step-title')
						.trigger('click');
		});

		if (OnePageBookingCart.hasItems()) {
			// find the step next to the timeline and auto-focus it
			$('.vap-opb-container .opb-step-wrapper.timeline-box')
				.next('.opb-step-wrapper')
					.addClass('clickable')
						.find('.opb-step-title')
							.trigger('click');

			// immediately show the button to access the billing/confirmation step 
			$('.vap-opb-container .opb-step-wrapper.timeline-box .opb-next-button-box').show();
		}

		// observe service change
		$('.vap-opb-container .opb-search-field.service-field select').on('change', function() {
			const mod_id = $(this).data('id');

			const id_service = parseInt($(this).val());

			// find employee box
			const empBox = $('#vap-opb-container' + mod_id).find('.opb-search-field.employee-field');

			// disable employee select
			empBox.find('select').prop('disabled', true);

			if (UIAjax.isDoing()) {
				opbXhr.abort();
			}
					
			opbXhr = UIAjax.do(
				ONEPAGE_BOOKING_CONFIG.serviceEmployeesUrl,
				{
					id_ser: id_service,
				},
				(resp) => {
					const serviceBox = $(this).find('option:selected');

					if (resp && resp.length) {
						let options = [];

						if (serviceBox.data('random')) {
							options.push($('<option></option>'));
						}

						resp.forEach((emp) => {
							options.push(
								$('<option></option>').val(emp.id).text(emp.nickname)
							);
						});

						// update employees dropdown
						empBox.find('select').html(options).attr('disabled', false).trigger('change.select2');

						// show employees box
						empBox.show();
					} else {
						// hide employees box
						empBox.hide();
						
						// clear employees dropdown
						empBox.find('select').html('');
					}

					let maxCapacity = parseInt(serviceBox.data('max-capacity'));
					let minPerRes   = parseInt(serviceBox.data('min-cap-res'));
					let maxPerRes   = parseInt(serviceBox.data('max-cap-res'));

					const peopleBox = $('#vap-opb-container' + mod_id).find('.opb-search-field.people-field');
					const peopleSelect = peopleBox.find('select')

					if (maxCapacity > 1 && maxPerRes > 1) {
						let people = [];

						for (let i = minPerRes; i <= maxPerRes; i++) {
							let peopleText;

							if (i > 1) {
								peopleText = Joomla.JText._('VAP_N_PEOPLE').replace(/%d/, i);
							} else {
								peopleText = Joomla.JText._('VAP_N_PEOPLE_1');
							}

							people.push(
								$('<option></option>').val(i).text(peopleText)
							);
						}

						let attendees = parseInt(peopleSelect.select2('val')) || 1;

						// update people dropdown
						peopleSelect.html(people);

						if (peopleSelect.find('option[value="' + attendees + '"]').length) {
							peopleSelect.select2('val', attendees);
						}

						peopleSelect.trigger('change.select2');

						// show people box
						peopleBox.show();
					} else {
						// hide people box
						peopleBox.hide();
						
						// clear people dropdown
						peopleSelect.html('');
					}

					// refresh datepicker options
					initDatepicker(serviceBox);
				},
				(error) => {
					if (error.statusText === 'abort') {
						// ignore error in case of abort
						return;
					}

					// register response HTTP error message
					alert(error.responseText || Joomla.JText._('VAP_OPB_GENERIC_ERROR'));
				}
			);
		});

		// observe button click to fetch the timeline
		$('.vap-opb-container button[data-role="timeline.request"]').on('click', function() {
			const parent = $(this).closest('.opb-step-wrapper.search-box');

			const serviceBox = parent.find('.service-field select option:selected');

			if (UIAjax.isDoing()) {
				opbXhr.abort();
			}

			// refresh datepicker configuration
			initDatepicker(serviceBox);

			// init search data
			searchData = {
				day:       getSelectedDate(serviceBox),
				id_emp:    parent.find('.employee-field select').val(),
				id_ser:    parent.find('.service-field select').val(),
				people:    parseInt(parent.find('.people-field select').val() || serviceBox.data('min-cap-res')),
				locations: [],
			};

			// detach all the services options for a correct rendering
			detachServicesOptions();

			// find timeline box
			const timelineBox = parent.closest('.vap-opb-container').find('.timeline-box');

			// add waiting animation
			AnimationHandler.start(ONEPAGE_BOOKING_CONFIG.animationDuration, timelineBox.find('.opb-timeline-container'));
			timelineBox.removeClass('collapsed').find('.opb-step-body').slideDown();

			opbXhr = UIAjax.do(
				ONEPAGE_BOOKING_CONFIG.timelineUrl,
				searchData,
				(data) => {
					getServiceOptions(searchData.id_ser).then((html) => {
						// preload options
					}).catch((error) => {
						// ignore errors...
					});

					const items = [];

					// extract service name from selected option
					let serviceName  = serviceBox.text();

					// fetch time slot template
					const timeTemplate = $($('.opb-time-slot-tmpl').html());

					if (data.timeline) {
						// iterate timeline shifts
						data.timeline.forEach((shift) => {

							// iterate shift time slots
							shift.forEach((slot) => {
								// check whether the booked item is contained within the cart
								let indexItemInCart = OnePageBookingCart.exists(slot.utc, searchData.id_ser, searchData.id_emp);;

								if (slot.status != 1 && indexItemInCart == -1 && ONEPAGE_BOOKING_CONFIG.hideUnavailable) {
									// skip unavailable time slot
									return true;
								}

								const item = $(timeTemplate).clone();

								// set service name
								item.find('.time-details-title').text(serviceName);

								/**
								 * Set check-in time.
								 * 
								 * @since 1.0.6  Append Zulu ID to treat the datetime as UTC.
								 */
								let checkinDate  = new Date(slot.checkin + 'Z');
								item.find('.time-details-clock-checkin').text(getFormattedTime(
									checkinDate.getHours(),
									checkinDate.getMinutes(),
									ONEPAGE_BOOKING_CONFIG.timeFormat,
									getUserTimezone(parent)
								));

								/**
								 * Set check-out time.
								 * 
								 * @since 1.0.6  Append Zulu ID to treat the datetime as UTC.
								 */
								let checkoutDate = new Date(slot.checkout + 'Z');
								item.find('.time-details-clock-checkout').text(getFormattedTime(
									checkoutDate.getHours(),
									checkoutDate.getMinutes(),
									ONEPAGE_BOOKING_CONFIG.timeFormat,
									getUserTimezone(parent)
								));

								if (slot.capacity > 1 && serviceBox.data('display-seats')) {
									let left = slot.capacity - slot.occupancy;

									let seats;

									if (left == 1) {
										seats = Joomla.JText._('VAP_OPB_N_SEATS_REMAINING_1');
									} else {
										seats = Joomla.JText._('VAP_OPB_N_SEATS_REMAINING').replace(/%d/, left);
									}

									// set remaining seats
									item.find('.time-details-seats').text(seats);
								} else {
									// remove remaining seats box
									item.find('.time-details-seats').remove();
								}

								const price = parseFloat(slot.price);

								// set price
								if (!isNaN(price) && price > 0) {

									// format price
									item.find('.time-price .time-price-total').html(currency.format(price));

									if (searchData.people > 1 && ONEPAGE_BOOKING_CONFIG.showPricePerPerson) {
										// format price per people
										item.find('.time-price .time-price-person span').html(currency.format(price / searchData.people));
									} else {
										// remove price per person
										item.find('.time-price .time-price-person').remove();
									}
								} else {
									// price not set, remove box
									item.find('.time-price').remove();
								}

								/**
								 * Register UTC time instead of the localized one.
								 * 
								 * @since 1.0.6
								 */
								let bookNowButton = item.find('.book-now')
									.attr('data-hour', checkinDate.getUTCHours())
									.attr('data-min', checkinDate.getUTCMinutes())
									.attr('data-checkin', slot.utc);

								// set book now button style
								if (indexItemInCart !== -1) {
									// register "booked" status
									item.addClass('time-slot-booked');

									bookNowButton.addClass('blue in-cart').text(Joomla.JText._('VAP_OPB_BOOKED_BUTTON'));
								} else if (slot.status == 1) {
									bookNowButton.addClass('green')
								} else {
									bookNowButton.addClass('red').prop('disabled', true);
								}

								items.push(item);
							});
						});
					}

					// check whether the day is fully booked and there are no times to display
					if (!items.length && !data.error) {
						data.error = Joomla.JText._('VAPFINDRESNOLONGERAVAILABLE');
					}

					if (data.error) {
						// display error message
						items.push($('<div class="error-message"></div>').html(data.error));
					}

					AnimationHandler.end().then(() => {
						// display timeline
						timelineBox.find('.opb-timeline-container').html(items);
					}).catch((err) => {
						// animation aborted
					});
				},
				(error) => {
					if (error.statusText === 'abort') {
						// ignore error in case of abort
						return;
					}

					// register response HTTP error message
					alert(error.responseText || Joomla.JText._('VAP_OPB_GENERIC_ERROR'));
				}
			);
		});

		$('.vap-opb-container .selected-date-wrapper').children().on('click', function() {
			$(this).prevAll('.date-field').datepicker('show');
		});
	
		// observe button click on prev/next date filters
		$('.vap-opb-container').find('.prev-date-link, .next-date-link').on('click', function() {
			if ($(this).hasClass('disabled')) {
				return false;
			}

			// find container
			const container = $(this).closest('.vap-opb-container');

			// find service option
			const service = container.find('.service-field select option:selected');

			// find last selected date
			let date = new Date(getSelectedDate(service) + 'T00:00:00');

			if ($(this).hasClass('prev-date-link')) {
				date.setDate(date.getDate() - 1);
			} else {
				date.setDate(date.getDate() + 1);
			}

			// refresh selected date (true: refresh timeline)
			setSelectedDate(date, service, true);
		});

		// observe button click to book an appointment
		$(document).on('click', '.vap-opb-container button.book-now.green', function() {
			if ($(this).hasClass('clicked')) {
				// button already clicked, ignore...
				return;
			}

			const parent = $(this).closest('.timeline-box');

			// removed clicked status from all buttons
			parent.find('button.book-now.clicked').removeClass('clicked').addClass('green');
			// register clicked status
			$(this).removeClass('green').addClass('clicked blue');

			// hide previously selected extras
			parent.find('.opb-time-slot-extra').hide();

			getServiceOptions(searchData.id_ser).then((options) => {
				if (!options) {
					// no options, deletegate book now action
					bookNowAction(this, true);
					return;
				}

				// find time slot options wrapper
				const optionsWrapper = $(this).closest('.opb-time-slot-wrapper').find('.opb-time-slot-extra');
				// find options container target
				const optionsTarget = optionsWrapper.find('.opb-time-slot-options');

				// append options to container
				options.appendTo(optionsTarget);

				// iterate all variations
				optionsTarget.find('.vapseropt-variations').each(function() {
					$(this).appendTo($(this).closest('.vapsersingoption'));
				});

				// auto-adjust "max" attribute of options with people-variable quantity 
				optionsTarget.find('.option-quantity.people-variable').each(function() {
					$(this).attr('max', searchData.people);

					if ($(this).hasClass('same-as-people')) {
						// option equals to the number of participants
						$(this).attr('min', searchData.people).val(searchData.people);
					} else {
						let val = parseInt($(this).val());

						if (isNaN(val) || val > searchData.people) {
							// the currently selected value exceeds the max threshold
							$(this).val(searchData.people);
						}
					}
				});

				// show inner container
				optionsTarget.find('.vapseroptionscont').show();
				// slide options
				optionsWrapper.slideDown();
			}).catch((error) => {
				// ignore errors...
			});
		});

		// observe button click to add an item into the cart
		$(document).on('click', '.vap-opb-container button.add-cart', function() {
			// find time slot button
			const timeSlot = $(this).closest('.opb-time-slot-wrapper').find('.book-now');
			// invoke action to book the appointment
			bookNowAction(timeSlot);
		});

		// observe mouse enter/leave on booked buttons
		$('.vap-opb-container .opb-timeline-container').on('mouseenter', 'button.in-cart', function() {
			$(this).removeClass('blue').addClass('red').text(Joomla.JText._('VAP_OPB_CANCEL_BUTTON'));
		}).on('mouseleave', 'button.in-cart', function() {
			$(this).removeClass('red').addClass('blue').text(Joomla.JText._('VAP_OPB_BOOKED_BUTTON'));
		}).on('click', 'button.in-cart', function() {
			$(this).hide();

			// start the animation
			AnimationHandler.start(ONEPAGE_BOOKING_CONFIG.animationDuration, $(this).next().show());

			// request appointment cancellation
			OnePageBookingCart.remove($(this).attr('data-checkin'), searchData.id_ser, searchData.id_emp).then(() => {
				// restore button classes
				$(this).removeClass('in-cart').removeClass('blue red').addClass('green');
				// replace button text
				$(this).text(Joomla.JText._('VAP_OPB_BOOK_NOW_BUTTON'));
			}).catch((error) => {
				if (!error || error.statusText === 'abort') {
					// ignore error in case of abort
					return;
				}

				// register response HTTP error message
				alert(error.responseText || Joomla.JText._('VAP_OPB_GENERIC_ERROR'));
			}).finally(() => {
				// wait until the end of the animation
				AnimationHandler.end().then(() => {
					// hide loading box and display button again
					$(this).next().hide().html('');
					$(this).show();

					// remove "booked" status from parent only after completing the animation
					if (!$(this).hasClass('in-cart')) {
						$(this).closest('.time-slot-booked').removeClass('time-slot-booked');
					}

					if (!OnePageBookingCart.hasItems()) {
						const container = $(this).closest('.vap-opb-container');

						// cart empty, do not access anymore the billing step
						container.find('.opb-step-wrapper.billing-box, .opb-step-wrapper.summary-box')
							.removeClass('clickable');

						// hide button to access the next step
						container.find('.opb-step-wrapper.timeline-box .opb-next-button-box').hide();
					}
				}).catch(() => {
					// animation aborted
				});
			});
		});

		// observe mouse enter/leave on booked buttons
		$('.vap-opb-container .opb-step-wrapper.summary-box').on('click', 'button.cancel-appointment', function() {
			// ask for a confirmation first
			if (!confirm(Joomla.JText._('VAP_OPB_ASK_CANCEL_CONFIRM'))) {
				return false;
			}

			$(this).hide();

			// start the animation
			AnimationHandler.start(ONEPAGE_BOOKING_CONFIG.animationDuration, $(this).next().show());

			let successful = false;

			// request appointment cancellation
			OnePageBookingCart.remove($(this).attr('data-checkin'), $(this).attr('data-service'), $(this).attr('data-employee')).then(() => {
				successful = true;
			}).catch((error) => {
				if (!error || error.statusText === 'abort') {
					// ignore error in case of abort
					return;
				}

				// register response HTTP error message
				alert(error.responseText || Joomla.JText._('VAP_OPB_GENERIC_ERROR'));
			}).finally(() => {
				// wait until the end of the animation
				AnimationHandler.end().then(() => {
					// hide loading box and display button again
					$(this).next().hide().html('');
					$(this).show();

					if (!successful) {
						return;
					}

					const container = $(this).closest('.vap-opb-container');

					if (OnePageBookingCart.hasItems()) {
						// refresh cart items
						refreshCartItems(container);
					} else {
						// cart empty, do not access anymore the billing step
						container.find('.opb-step-wrapper.billing-box, .opb-step-wrapper.summary-box')
							.removeClass('clickable');

						// hide button to access the next step
						container.find('.opb-step-wrapper.timeline-box .opb-next-button-box').hide();

						// auto focus search box
						switchPanel(container, 'search');
					}
				}).catch(() => {
					// animation aborted
				});
			});
		});

		// auto-submit coupon validation on press enter
		$('.vap-opb-container .opb-cart-coupon input').on('keyup', function(event) {
			if (event.keyCode == 13) {
				$(this).next('button').trigger('click');
			}
		});

		// observe button click used to redeem a coupon code
		$('.vap-opb-container .opb-cart-coupon button.redeem-coupon').on('click', function() {
			let couponCode = $(this).prev('input').val();

			if (!couponCode) {
				// missing coupon code
				return false;
			}

			$(this).css('visibility', 'hidden');

			// start the animation
			AnimationHandler.start(ONEPAGE_BOOKING_CONFIG.animationDuration, $(this).next().show());

			let errorMessage, successful;

			if (UIAjax.isDoing()) {
				opbXhr.abort();
			}

			// request appointment cancellation
			opbXhr = OnePageBookingCart.redeemCoupon(couponCode).then(() => {
				successful = true;
			}).catch((error) => {
				if (!error || error.statusText === 'abort') {
					// ignore error in case of abort
					return;
				}

				// register response HTTP error message
				errorMessage = error.responseText || Joomla.JText._('VAP_OPB_GENERIC_ERROR');
			}).finally(() => {
				// wait until the end of the animation
				AnimationHandler.end().then(() => {
					// hide loading box and display button again
					$(this).next().hide().html('');
					$(this).css('visibility', 'visible');

					if (errorMessage) {
						// display error message with a short delay
						setTimeout(() => {
							alert(errorMessage);
						}, 128);
						return;
					}

					if (!successful) {
						return;
					}

					// reset value
					$(this).prev('input').val('');

					// refresh cart items
					refreshCartItems($(this).closest('.vap-opb-container'));
				}).catch(() => {
					// animation aborted
				});
			});
		});

		// observe button click to access the next step
		$('.vap-opb-container button[data-role="booking.confirm"]').on('click', function() {
			// hide button
			$(this).hide();

			// start the animation
			AnimationHandler.start(ONEPAGE_BOOKING_CONFIG.animationDuration, $(this).next().show());

			// find parent form
			const form = $(this).closest('form');

			// register callback used to prevent the form submit
			$(document).off('submit', form, preventFormSubmit);
			$(document).on('submit', form, preventFormSubmit);

			// invoke submit to trigger all the attached callbacks
			form.trigger('submit');

			let landingPage, errorMessage;

			if (UIAjax.isDoing()) {
				opbXhr.abort();
			}

			const formData = new FormData(form[0]);

			// unset all the fields that might have been registered by the login form,
			// which could hijack the end-point to a different location
			formData.delete('option');
			formData.delete('task');
			formData.delete('return');

			// wrap request into a promise for a correct animation handling
			return new Promise((resolve, reject) => {
				// make request (use upload function to support file custom fields)
				opbXhr = UIAjax.upload(
					ONEPAGE_BOOKING_CONFIG.saveOrderUrl,
					formData,
					(data) => {
						// register landing page
						resolve(data);
					},
					(error) => {
						if (!error || error.statusText === 'abort') {
							// ignore error in case of abort
							return;
						}

						// register response HTTP error message
						reject(error.responseText || Joomla.JText._('VAP_OPB_GENERIC_ERROR'));
					}
				);
			}).then((data) => {
				landingPage = data;
			}).catch((error) => {
				errorMessage = error;
			}).finally(() => {
				// wait until the end of the animation
				AnimationHandler.end().then(() => {
					if (landingPage) {
						// auto-reach the landing page
						document.location.href = landingPage;
						return true;
					}

					// show button again
					$(this).next().hide();
					$(this).show();

					if (errorMessage) {
						// display error message
						alert(errorMessage);
					}
				}).catch(() => {
					// animation aborted
				});
			});
		});

		// render timezone dropdown
		$('.vap-opb-container .opb-timeline-timezone select').select2({
			allowClear: false,
			width: '100%',
		}).on('change', function() {
			// store timezone in a cookie for 1 month
			let date = new Date();
			date.setMonth(date.getMonth() + 1);

			document.cookie = 'vikappointments.user.timezone=' + $(this).val() + '; expires=' + date.toUTCString() + '; path=/';

			// trigger timeline refresh 
			$(this).closest('.vap-opb-container').find('button[data-role="timeline.request"]').trigger('click');
		});
	});
	
	/**
	 * Helper class used to handle cart requests. 
	 */
	const OnePageBookingCart = {
		/**
		 * The cart instance.
		 * 
		 * @var object
		 */
		cart: null,

		/**
		 * The cart total cost (no tax and no discounts).
		 * 
		 * @var float
		 */
		totalCost: 0.0,

		/**
		 * The cart total gross.
		 * 
		 * @var float
		 */
		totalGross: 0.0,

		/**
		 * The HTML cart totals fetched after every action.
		 * 
		 * @var string
		 */
		totalsHtml: '',

		/**
		 * Adds a new item within the cart.
		 * 
		 * @param 	object  data  The item data.
		 * 
		 * @return 	Promise
		 */
		add: function(data) {
			return new Promise((resolve, reject) => {
				UIAjax.do(
					ONEPAGE_BOOKING_CONFIG.addItemUrl,
					data,
					(resp) => {
						// register item within the cart
						this.cart.push(resp.item);

						// update total cost
						this.totalCost = resp.total;

						// update total gross
						this.totalGross = resp.totalGross;

						// update HTML cart totals
						this.totalsHtml = resp.totalsHtml;

						resolve(resp);
					},
					(error) => {
						reject(error);
					}
				);
			});
		},

		/**
		 * Removes the selected item from the cart.
		 * 
		 * @param 	string 	checkin      The check-in date of the appointments (UTC).
		 * @param 	int     id_service   The service ID of the appointment.
		 * @param	int     id_employee  The employee ID of the appointment.
		 * 
		 * @return 	Promise
		 */
		remove: function(checkin, id_service, id_employee) {
			return new Promise((resolve, reject) => {
				// make sure the appointment exists
				let index = this.exists(checkin, id_service, id_employee);

				if (index === -1) {
					// reject without error and save a request
					reject(null);
				}

				UIAjax.do(
					ONEPAGE_BOOKING_CONFIG.removeItemUrl,
					{
						checkin: checkin.replace(/T/, ' '),
						id_ser: id_service,
						id_emp: id_employee,
					},
					(resp) => {
						// remove item from cart
						this.cart.splice(index, 1);

						// update total cost
						this.totalCost = resp.total;

						// update total gross
						this.totalGross = resp.totalGross;

						// update HTML cart totals
						this.totalsHtml = resp.totalsHtml;

						resolve(resp);
					},
					(error) => {
						reject(error);
					}
				);
			});
		},

		/**
		 * Tries to apply the given coupon code.
		 * 
		 * @param 	string  code  The coupon code.
		 * 
		 * @return 	Promise
		 */
		redeemCoupon: function(code) {
			return new Promise((resolve, reject) => {
				UIAjax.do(
					ONEPAGE_BOOKING_CONFIG.redeemCouponUrl,
					{
						coupon: code,
					},
					(resp) => {
						// update total cost
						this.totalCost = resp.cart.total;

						// update total gross
						this.totalGross = resp.cart.totalGross;

						// update HTML cart totals
						this.totalsHtml = resp.totalsHtml;

						resolve(resp);
					},
					(error) => {
						reject(error);
					}
				);
			});
		},

		/**
		 * Checks whether the specified appointment is registered within the cart.
		 * 
		 * @param 	string 	checkin      The check-in date of the appointments (UTC).
		 * @param 	int     id_service   The service ID of the appointment.
		 * @param	int     id_employee  The employee ID of the appointment.
		 * 
		 * @return 	int     The index of the element if exists, -1 otherwise.
		 */
		exists: function(checkin, id_service, id_employee) {
			// make sure we have a correct value
			checkin = checkin.replace(/\s+/, 'T');

			for (let i = 0; i < this.cart.length; i++) {
				let item = this.cart[i];

				let sameCheckin  = checkin == item.checkin.replace(/\s+/, 'T');
				let sameService  = id_service == item.id_service;
				let sameEmployee = id_employee == item.id_employee || (id_employee <= 0 && item.id_employee <= 0);

				if (sameCheckin && sameService && sameEmployee) {
					return i;
				}
			}

			return -1;
		},

		/**
		 * Checks whether the cart has some items.
		 * 
		 * @return 	bool  True if not empty, false otherwise.
		 */
		hasItems: function() {
			return this.cart.length;
		}
	}
	
	/**
	 * Helper class used to handle the animation.
	 */
	const AnimationHandler = {
		/**
		 * A queue of registered promises.
		 * 
		 * @var Promise[]
		 */
		queue: [],

		/**
		 * Starts the animation.
		 * 
		 * @param 	integer  duration  The minimum duration of the animation (in ms).
		 * @param 	mixed 	 target    The HTML node in which the animation block will be set.
		 * 
		 * @return 	void
		 */ 
		start: function(duration, target) {
			if (duration === false) {
				// ignore animation
				this.threshold = false;
			} else {
				// set end animation date time
				this.threshold = new Date();
				this.threshold.setMilliseconds(duration);

				if (this.queue.length == 0) {
					// append animation HTML to the specified target
					$(target).html($('.vap-opb-loading-tmpl').first().html());
				} else {
					// cancel previously registered threads
					this.queue.forEach((thread) => {
						thread.state = false;
					});
				}
			}
		},

		/**
		 * Returns a promise to establish when the animation will end.
		 * 
		 * @return 	Promise
		 */
		end: function() {
			// create thread identifier
			let thread = {
				state: true
			};

			// register thread state
			this.queue.push(thread);

			// define callback to remove the thread from the queue
			const removeFromQueue = (item) => {
				const index = this.queue.indexOf(item);
				
				if (index !== -1) {
					this.queue.splice(index, 1);
				}
			}

			// wait until the animation is completed
			return onInstanceReady(() => {
				if (thread.state === false) {
					// animation aborted
					removeFromQueue(thread);
					throw 'AnimationStoppedException';
				}

				if (!this.threshold || this.threshold < new Date()) {
					// animation completed
					removeFromQueue(thread);
					return true;
				}

				// not yet completed
				return false;
			});
		},
	}
})(jQuery, window);