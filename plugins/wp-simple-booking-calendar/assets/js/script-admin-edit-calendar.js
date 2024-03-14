/**
 * This is the object that stores all calendar data when editing a calendar
 *
 * Its structure is by year, month, day basis. Eg: wpsbc_calendar_data[2018][1][21]
 *
 */
var wpsbc_calendar_data = {};

jQuery(function ($) {

	/**
	 * Retrieves an array with the query arguments found in a given string
	 *
	 */
	function get_query_args(string) {

		var query_arr = string.replace('?', '').split('&');
		var query_params = [];

		for (var q = 0, q_query_arr = query_arr.length; q < q_query_arr; q++) {

			var q_arr = query_arr[q].split('=');
			query_params[q_arr[0]] = q_arr[1];

		}

		return query_params;

	}

	/**
	 * Resizes the calendar to always have square dates
	 *
	 */
	function resize_calendar($calendars_wrapper) {

		var td_width = $calendars_wrapper.find('td').first().width();

		$calendars_wrapper.find('td .wpsbc-date-inner, td .wpsbc-week-number').css('height', td_width + 'px');
		$calendars_wrapper.find('td .wpsbc-date-inner, td .wpsbc-week-number').css('line-height', td_width + 'px');

		$calendars_wrapper.css('visibility', 'visible');

	}


	/**
	 * Refreshed the output of the calendar with the given data
	 *
	 */
	function refresh_calendar($calendar_container, current_year, current_month) {

		var $calendar_container = $calendar_container;
		var $calendar_editor = $('#wpsbc-calendar-editor');

		if ($calendar_container.hasClass('wpsbc-is-loading'))
			return false;

		/**
		 * Prepare the calendar data
		 *
		 */
		var data = $calendar_container.data();

		data['action'] = 'wpsbc_refresh_calendar_editor';
		data['current_year'] = current_year;
		data['current_month'] = current_month;
		data['calendar_data'] = JSON.stringify(wpsbc_calendar_data);

		/**
		 * Add loading animation
		 *
		 */
		$calendar_container.find('.wpsbc-calendar').append('<div class="wpsbc-overlay"><div class="wpsbc-overlay-spinner"><div class="wpsbc-overlay-bounce1"></div><div class="wpsbc-overlay-bounce2"></div><div class="wpsbc-overlay-bounce3"></div></div></div>');
		$calendar_container.addClass('wpsbc-is-loading');
		$calendar_container.find('select').attr('disabled', true);

		$('#wpsbc-calendar-editor').append('<div class="wpsbc-overlay"><div class="wpsbc-overlay-spinner"><div class="wpsbc-overlay-bounce1"></div><div class="wpsbc-overlay-bounce2"></div><div class="wpsbc-overlay-bounce3"></div></div></div>');

		/**
		 * Make the request
		 *
		 */
		$.post(ajaxurl, data, function (response) {

			response = JSON.parse(response);

			$calendar_container.replaceWith(response.calendar);
			$calendar_editor.replaceWith(response.calendar_editor);

			resize_calendar($('.wpsbc-container[data-id="' + data['id'] + '"]'));
			refresh_calendar_dates();

		});

	}


	/**
	 * Updates the calendar legend items icons of each date from the 
	 * data found in wpsbc_calendar_data for the legend items
	 *
	 */
	function refresh_calendar_dates() {

		var $calendar_container = $('.wpsbc-container');

		var year = $calendar_container.data('current_year');
		var month = $calendar_container.data('current_month');

		if (typeof wpsbc_calendar_data[year] == 'undefined')
			return false;

		if (typeof wpsbc_calendar_data[year][month] == 'undefined')
			return false;

		for (day in wpsbc_calendar_data[year][month]) {

			if (typeof wpsbc_calendar_data[year][month][day] == 'undefined')
				continue;

			if (typeof wpsbc_calendar_data[year][month][day]['legend_item_id'] == 'undefined')
				continue;

			var $legend_item_selector = $('.wpsbc-calendar-date-legend-item select[data-year="' + year + '"][data-month="' + month + '"][data-day="' + day + '"]');

			$calendar_container.find('[data-year="' + year + '"][data-month="' + month + '"][data-day="' + day + '"] .wpsbc-legend-item-icon')
				.attr('class', 'wpsbc-legend-item-icon wpsbc-legend-item-icon-' + $legend_item_selector.val())
				.attr('data-type', $legend_item_selector.find('option:selected').data('type'));

		}

	}


	/**
	 * Callback function that is triggered on changes made to input, textarea, select, etc.
	 * fields from the calendar editor
	 *
	 */
	function calendar_editor_field_change($input) {

		/**
		 * Exit if the input does not have the needed data values
		 *
		 */
		if (typeof $input.data('year') == 'undefined')
			return false;

		if (typeof $input.data('month') == 'undefined')
			return false;

		if (typeof $input.data('day') == 'undefined')
			return false;

		if (typeof $input.data('name') == 'undefined')
			return false;

		/**
		 * Sanitize the data values and set them as variables
		 *
		 */
		var year = parseInt($input.data('year'));
		var month = parseInt($input.data('month'));
		var day = parseInt($input.data('day'));
		var name = $input.data('name');

		// Update data
		update_calendar_data(year, month, day, name, $input.val());

	}


	/**
	 * Updates the calendar data object with new data from the provided field
	 *
	 */
	function update_calendar_data(year, month, day, field_name, field_value) {

		if (typeof field_name == 'undefined')
			return false;

		if (typeof field_value == 'undefined')
			return false;

		/**
		 * Create the object for each date layer if needed
		 *
		 */
		if (typeof wpsbc_calendar_data[year] == 'undefined')
			wpsbc_calendar_data[year] = {};

		if (typeof wpsbc_calendar_data[year][month] == 'undefined')
			wpsbc_calendar_data[year][month] = {};

		if (typeof wpsbc_calendar_data[year][month][day] == 'undefined')
			wpsbc_calendar_data[year][month][day] = {};

		/**
		 * Set the value for the current date
		 *
		 */
		wpsbc_calendar_data[year][month][day][field_name] = field_value;

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
	 * Handles the navigation of the Month Selector
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
	 * Updates the calendar wpsbc_calendar_data object when doing changes in the
	 * calendar editor
	 *
	 */
	$(document).on('change', '#wpsbc-calendar-editor select', function () {

		calendar_editor_field_change($(this));

	});

	$(document).on('keyup', '#wpsbc-calendar-editor input, #wpsbc-calendar-editor textarea', function () {

		calendar_editor_field_change($(this));

	});


	/**
	 * Handle legend item select change in edit caledndar screen
	 *
	 */
	$(document).on('change', '.wpsbc-calendar-date-legend-item select', function () {

		$(this).siblings('div').find('.wpsbc-legend-item-icon')
			.attr('class', 'wpsbc-legend-item-icon wpsbc-legend-item-icon-' + $(this).val())
			.attr('data-type', $(this).find('option:selected').data('type'));

		$(this).closest('.wpsbc-calendar-date-legend-item')
			.attr('class', 'wpsbc-calendar-date-legend-item wpsbc-calendar-date-legend-item-' + $(this).val());

		refresh_calendar_dates();

	});

	/**
	 * Handles the saving of the calendar by making an AJAX call to the server
	 * with the wpsbc_calendar_data.
	 *
	 * Upon success refreshes the page and adds a success message
	 *
	 */
	$(document).on('click', '.wpsbc-save-calendar', function (e) {

		e.preventDefault();

		var form_data = $(this).closest('form').serialize();

		var data = {
			action: 'wpsbc_save_calendar_data',
			form_data: form_data,
			calendar_data: JSON.stringify(wpsbc_calendar_data),
			current_year: $('.wpsbc-container').data('current_year'),
			current_month: $('.wpsbc-container').data('current_month'),
			wpsbc_token: $("#wpsbc_token").val()
		}

		// Disable all buttons and show loading spinner
		$('.wpsbc-wrap-edit-calendar input, .wpsbc-wrap-edit-calendar select, .wpsbc-wrap-edit-calendar textarea').attr('disabled', true);
		$(this).siblings('.wpsbc-save-calendar-spinner').css('visibility', 'visible');

		$.post(ajaxurl, data, function (response) {

			if (typeof response != 'undefined')
				window.location.replace(response);

		});

	});

});