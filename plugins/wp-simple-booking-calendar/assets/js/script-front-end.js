$ = jQuery.noConflict();

$(document).ready(function($){

	/**
	 * Resizes the calendar to always have square dates
	 *
	 */
	function resize_calendar($calendars_wrapper) {

		/**
		 * Set variables
		 *
		 */
		var $months_wrapper = $calendars_wrapper.find('.wpsbc-calendars-wrapper');
		var $months_wrapper_width = $calendars_wrapper.find('.wpsbc-calendars');
		var calendar_min_width = $calendars_wrapper.data('min_width');
		var calendar_max_width = $calendars_wrapper.data('max_width');

		var $month_inner = $calendars_wrapper.find('.wpsbc-calendar-wrapper');

		/**
		 * Set the calendar months min and max width from the data attributes
		 *
		 */
		if ($calendars_wrapper.data('min_width') > 0)
			$calendars_wrapper.find('.wpsbc-calendar').css('min-width', calendar_min_width);

		if ($calendars_wrapper.data('max_width') > 0)
			$calendars_wrapper.find('.wpsbc-calendar').css('max-width', calendar_max_width)


		/**
		 * Set the column count
		 *
		 */
		var column_count = 0;

		if ($months_wrapper_width.width() < calendar_min_width * 2)
			column_count = 1;

		else if ($months_wrapper_width.width() < calendar_min_width * 3)
			column_count = 2;

		else if ($months_wrapper_width.width() < calendar_min_width * 4)
			column_count = 3;

		else if ($months_wrapper_width.width() < calendar_min_width * 6)
			column_count = 4;

		else
			column_count = 6;


		// Adjust for when there are fewer months in a calendar than columns
		if ($calendars_wrapper.find('.wpsbc-calendar').length <= column_count)
			column_count = $calendars_wrapper.find('.wpsbc-calendar').length;

		// Set column count
		$calendars_wrapper.attr('data-columns', column_count);


		/**
		 * Set the max-width of the calendars container that has a side legend
		 *
		 */
		if ($months_wrapper.hasClass('wpsbc-legend-position-side')) {

			$months_wrapper.css('max-width', 'none');
			$months_wrapper.css('max-width', $calendars_wrapper.find('.wpsbc-calendar').first().outerWidth(true) * column_count);

		}


		/**
		 * Handle the height of each date
		 *
		 */
		var td_width = $calendars_wrapper.find('td').first().width();

		$calendars_wrapper.find('td .wpsbc-date-inner, td .wpsbc-week-number').css('height', Math.ceil(td_width) + 1 + 'px');
		$calendars_wrapper.find('td .wpsbc-date-inner, td .wpsbc-week-number').css('line-height', Math.ceil(td_width) + 1 + 'px');

		var th_height = $calendars_wrapper.find('th').css('height', 'auto').first().height();
		$calendars_wrapper.find('th').css('height', Math.ceil(th_height) + 1 + 'px');

		/**
		 * Set calendar month height
		 *
		 */
		var calendar_month_height = 0;

		$month_inner.css('min-height', '1px');

		$month_inner.each(function () {

			if ($(this).height() >= calendar_month_height)
				calendar_month_height = $(this).height();

		});

		$month_inner.css('min-height', Math.ceil(calendar_month_height) + 'px');

		/**
		 * Show the calendars
		 *
		 */
		$calendars_wrapper.css('visibility', 'visible');

	}


	/**
	 * Refreshed the output of the calendar with the given data
	 *
	 */
	function refresh_calendar($calendar_container, current_year, current_month) {

		var $calendar_container = $calendar_container;

		if ($calendar_container.hasClass('wpsbc-is-loading'))
			return false;

		/**
		 * Prepare the calendar data
		 *
		 */
		var data = $calendar_container.data();

		data['action'] = 'wpsbc_refresh_calendar';
		data['current_year'] = current_year;
		data['current_month'] = current_month;

		/**
		 * Add loading animation
		 *
		 */
		$calendar_container.find('.wpsbc-calendar').append('<div class="wpsbc-overlay"><div class="wpsbc-overlay-spinner"><div class="wpsbc-overlay-bounce1"></div><div class="wpsbc-overlay-bounce2"></div><div class="wpsbc-overlay-bounce3"></div></div></div>');
		$calendar_container.addClass('wpsbc-is-loading');
		$calendar_container.find('select').attr('disabled', true);


		/**
		 * Make the request
		 *
		 */
		$.post(wpsbc.ajax_url, data, function (response) {

			$calendar_container.replaceWith(response);

			$('.wpsbc-container').each(function () {
				resize_calendar($(this));
			});

		});

	}

	/**
	 * Resize the calendars on page load
	 *
	 */
	$('.wpsbc-container').each(function () {
		resize_calendar($(this));
	});

	/**
	 * Resize the calendars on page resize
	 *
	 */
	$(window).on('resize', function () {

		$('.wpsbc-container').each(function () {
			resize_calendar($(this));
		});

	});


	/**
	 * Handles the navigation of the Previous button
	 *
	 */
	$(document).on('click', '.wpsbc-container .wpsbc-prev', function (e) {

		e.preventDefault();

		// Set container
		var $container = $(this).closest('.wpsbc-container');

		// Set the current year and month that are displayed in the calendar
		var current_month = $container.data('current_month');
		var current_year = $container.data('current_year');

		current_month -= 1;

		if (current_month < 1) {
			current_month = 12;
			current_year -= 1;
		}


		refresh_calendar($container, current_year, current_month);

	});

	/**
	 * Handles the navigation of the Next button
	 *
	 */
	$(document).on('click', '.wpsbc-container .wpsbc-next', function (e) {

		e.preventDefault();

		// Set container
		var $container = $(this).closest('.wpsbc-container');

		// Set the current year and month that are displayed in the calendar
		var current_month = $container.data('current_month');
		var current_year = $container.data('current_year');

		current_month += 1;

		if (current_month > 12) {
			current_month = 1;
			current_year += 1;
		}

		refresh_calendar($container, current_year, current_month);

	});

	/**
	 * Handles the navigation of the Month Selector for the Single Calendar
	 *
	 */
	$(document).on('change', '.wpsbc-container .wpsbc-select-container select', function () {

		// Set container
		var $container = $(this).closest('.wpsbc-container');

		var date = new Date($(this).val() * 1000);

		var year = date.getFullYear();
		var month = date.getMonth() + 1;

		refresh_calendar($container, year, month);

	});



	/**
     * Check if a calendar is hidden and wait for it to become visible. 
     * When it does, trigger a window resize to properly display the calendar.
     * 
     */
	var wpsbc_frontend_visible_calendars = $('.wpsbc-container:visible').length;
	function wpsbc_check_if_calendar_is_visible() {

		// If no calendars are embedded, exit
		if (!$('.wpsbc-container').length)
			return false;

		// If a calendar just became visible, trigger a resize
		if (wpsbc_frontend_visible_calendars != $('.wpsbc-container:visible').length) {
			$(window).trigger('resize');
			wpsbc_frontend_visible_calendars = $('.wpsbc-container:visible').length;
		}

		// If all calendars are visible, exit
		if ($('.wpsbc-container:visible').length == $('.wpsbc-container').length) {
			return false;
		}

		// Keep checking every 250ms
		setTimeout(wpsbc_check_if_calendar_is_visible, 250);

	}
	// Manually start the first check
	wpsbc_check_if_calendar_is_visible();

	/**
     * Elementor element resize
     * 
     */
    if ($('body').hasClass('elementor-editor-active')) {

        /**
         * Runs every 250 milliseconds to check if a calendar was just loaded
         * and if it was, trigger the window resize to show it
         *
         */
        setInterval(function () {

            $('.wpsbc-container-loaded').each(function () {

                if ($(this).attr('data-just-loaded') == '1') {
                    $(window).trigger('resize');
                    $(this).attr('data-just-loaded', '0');
                }

            });

        }, 250);

    }

});


