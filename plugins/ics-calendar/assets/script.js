/*! ICS Calendar front end scripts https://icscalendar.com */


function r34ics_ajax_init() {
	jQuery(document).trigger('r34ics_ajax_start');

	jQuery(document).find('.r34ics-ajax-container').each(function() {
		r34ics_ajax_request(jQuery(this), 0);
	});

	jQuery(document).trigger('r34ics_ajax_end');
}


function r34ics_ajax_request(r34ics_elem, failures) {
	r34ics_elem.addClass('loading');
	if (typeof failures == 'undefined') { failures = 0; }
	jQuery.ajax({
		url: r34ics_ajax_obj.ajaxurl,
		data: {
			'action': 'r34ics_ajax',
			'r34ics_nonce': r34ics_ajax_obj.r34ics_nonce,
			'subaction': 'display_calendar',
			'args': r34ics_elem.data('args'),
		},
		dataType: 'text',
		type: 'POST',
		success: function(data) {
			// @todo Determine why data output is sometimes just '1'
			if (data == '1') {
				// Retry up to 5 times
				if (failures <= 5) {
					failures++;
					r34ics_ajax_request(r34ics_elem, failures);
				}
				else {
					console.error('ICS Calendar AJAX request failed for element #' + r34ics_elem.attr('id'));
				}
			}
			else {
				if (typeof r34ics_elem.data('args').debug != '' && parseInt(r34ics_elem.data('args').debug) > 0) {
					console.log('ICS Calendar AJAX request succeeded for element #' + r34ics_elem.attr('id'));
				}
				r34ics_elem.replaceWith(data);
				r34ics_init();
				// @todo Move to hook
				if (typeof r34icspro_init === 'function') { r34icspro_init(); }
				r34ics_show_hide_headers();
			}
		},
		error: function(errorThrown) {
			console.error(errorThrown);
		},
	});
}


function r34ics_boolean_check(val) {
	var check = String(val).trim().toLowerCase();
	if (check === '1' || check === 'true' || check === 'on') { return 1; }
	if (check === '0' || check === 'false' || check === 'off' || check === 'none') { return 0; }
	if (check === 'null' || check === '') { return null; }
	return parseInt(val) > 0;
}


function r34ics_init() {

	// Custom event for callbacks
	jQuery(document).trigger('r34ics_init_start');

	// VIEW: ALL
	
	// Show calendar (hidden on load to avoid FOUC, with conditional style loading as of v.10.6)
	jQuery('.ics-calendar').animate({ opacity: 1}, 500);

	// Add .r34ics_phone class to body if we're on a phone screen size
	if (r34ics_is_phone()) { jQuery('body').addClass('r34ics_phone'); }

	// Handle individual event ICS downloads
	jQuery(document).on('click', '.r34ics_event_ics_download', function() {
		if (jQuery(this).data('eventdl-uid') != '') {
			var eventdl_uid = jQuery(this).data('eventdl-uid');
			var eventdl_feed_key = jQuery(this).data('eventdl-feed-key');
			var eventdl_form = jQuery(this).closest('form');
			// If we're in a lightbox, we need to find the form elsewhere on the page
			if (eventdl_form.length == 0) {
				jQuery('form.r34ics_event_ics_download_form').each(function() {
					if (jQuery(this).find('*[data-eventdl-uid="' + eventdl_uid + '"]').length > 0) {
						eventdl_form = jQuery(this);
					}
				});
			}
			if (eventdl_form.length > 0) {
				eventdl_form.find('input[name="r34ics-feed-key"]').val(eventdl_feed_key);
				eventdl_form.find('input[name="r34ics-uid"]').val(eventdl_uid);
				eventdl_form.submit();
			}
		}
		return false;
	});

	// Show/hide headers on mobile view when select menu changes
	jQuery(document).on('change', '.ics-calendar-select', function() {
		r34ics_show_hide_headers();
	});

	// Simulate click with Enter key press
	jQuery(document).on('keypress', '.ics-calendar *, .r34ics_lightbox *', function(e) {
		if (e.which == 13) {
			jQuery(this).trigger('click');
			// Maybe also reset focus
			if (jQuery(this).hasClass('ics-calendar-pagination') && jQuery(this).hasClass('prev')) {
				jQuery(document).find('.ics-calendar-pagination.prev:visible').focus();
			}
			else if (jQuery(this).hasClass('ics-calendar-pagination') && jQuery(this).hasClass('next')) {
				jQuery(document).find('.ics-calendar-pagination.next:visible').focus();
			}
		}
	});

	// Show/hide headers on HTML5 <details> tag toggle
	// Note: Can't apply dynamically because toggle event ONLY fires on details, not document
	jQuery('details').on('toggle', function() {
		if (jQuery(this).has('.ics-calendar')) { r34ics_show_hide_headers(); }
	});

	// Handle "toggle" functionality for event descriptions
	/*
	Note: .toggle class was changed to .r34ics_toggle in templates
	and CSS to work around a conflict with another plugin;
	however, the original class is retained here for flexibility.
	We are using jQuery(document) to account for dynamically-inserted elements.
	(Logic for .title element was added for tab-accessibility.)
	*/
	jQuery(document).on('click', '.ics-calendar.r34ics_toggle .event, .ics-calendar.toggle .event, .ics-calendar.r34ics_toggle .event .title, .ics-calendar.toggle .event .title', function(e) {
		e.stopPropagation();
		var elem = jQuery(this).hasClass('title') ? jQuery(this).parent() : jQuery(this);
		// No description -- do nothing
		if (elem.find('.descloc').length == 0) { return false; }
		// Lightbox
		if (jQuery('.r34ics_lightbox').length > 0 && elem.closest('.ics-calendar').hasClass('r34ics_toggle_lightbox')) {
			jQuery('.r34ics_lightbox .r34ics_lightbox_content').html(elem.find('.descloc').html());
			jQuery('.r34ics_lightbox').addClass('open');
			jQuery('.r34ics_lightbox_close').focus();
		}
		// Toggle in place
		else {
			if (elem.hasClass('open')) { elem.removeClass('open'); }
			else { elem.addClass('open'); }
		}
	});
	// Don't trigger toggle if we're clicking a link inside the event
	jQuery(document).on('click', '.ics-calendar.r34ics_toggle .event a, .ics-calendar.toggle .event a, .r34ics_lightbox .r34ics_lightbox_content', function(e) {
		e.stopPropagation();
	});
	// Initialize lightbox
	if (jQuery('.r34ics_lightbox').length > 0) {
		// Remove redundant instances (workaround to a user issue where a global variable to prevent redundancies failed)
		if (jQuery('.r34ics_lightbox').length > 1) {
			var i = 0; jQuery('.r34ics_lightbox').each(function() { if (i > 0) { jQuery(this).remove(); } i++; });
		}
		// Move the lightbox element from its original DOM position to the top of the body, so its z-index functions properly
		jQuery('.r34ics_lightbox').prependTo('body');
		// Lightbox close button functionality
		jQuery('.r34ics_lightbox .r34ics_lightbox_close').on('click', function() {
			jQuery('.r34ics_lightbox .r34ics_lightbox_content').html('');
			jQuery('.r34ics_lightbox').removeClass('open');
		});
	}

	// Make offsite links open in new tab
	jQuery('.ics-calendar:not(.sametab) a').each(function() {
		if (jQuery(this).attr('target') == '_blank') {
			jQuery(this).addClass('offsite-link');
		}
		else if (
				typeof jQuery(this).attr('href') != 'undefined' &&
				jQuery(this).attr('href').indexOf('http') == 0 &&
				jQuery(this).attr('href').indexOf('//'+location.hostname) == -1
		) {
			jQuery(this).addClass('offsite-link').attr('target','_blank');
		}
	});

	// Toggle color-coded multi-feed calendars
	jQuery('.ics-calendar-color-key-toggle').on('click', function() {
		var cal = jQuery(this).closest('.ics-calendar');
		var feedkey = jQuery(this).attr('data-feed-key');
		if (jQuery(this).prop('checked') == true) {
			cal.find('.event[data-feed-key=' + parseInt(feedkey) + '], .events *[data-feed-key=' + parseInt(feedkey) + ']').removeClass('hidden_in_main');
		}
		else {
			cal.find('.event[data-feed-key=' + parseInt(feedkey) + '], .events *[data-feed-key=' + parseInt(feedkey) + ']').addClass('hidden_in_main');
		}
		// Show/hide day and month headers (this is encapsulated in a function so we can trigger it separately)
		r34ics_show_hide_headers('#' + cal.attr('id'));
		// Uncheck the show/hide all button
		if (!jQuery(this).prop('checked')) {
			jQuery(this).parent().parent().siblings().find('.ics-calendar-color-key-toggle-all').each(function() {
				jQuery(this).prop('checked', false);
			});
		}
		// Check the show/hide button only if all are checked
		else {
			var all_siblings_checked = true;
			jQuery(this).parent().parent().siblings().find('.ics-calendar-color-key-toggle').each(function() {
				if (!jQuery(this).prop('checked')) { all_siblings_checked = false; }
			});
			if (all_siblings_checked) {
				jQuery(this).parent().parent().siblings().find('.ics-calendar-color-key-toggle-all').each(function() {
					jQuery(this).prop('checked', true);
				});
			}
		}
	});
	jQuery('.ics-calendar-color-key-toggle-all').on('click', function() {
		if (jQuery(this).prop('checked')) {
			jQuery(this).parent().parent().siblings().find('.ics-calendar-color-key-toggle').each(function() {
				if (!jQuery(this).prop('checked')) {
					jQuery(this).trigger('click');
				}
			});
		}
		else {
			jQuery(this).parent().parent().siblings().find('.ics-calendar-color-key-toggle').each(function() {
				if (jQuery(this).prop('checked')) {
					jQuery(this).trigger('click');
				}
			});
		}
	});

	// VIEW: WEEK
	// Outer section wrapper has classes .ics-calendar.layout-week

	if (jQuery('.ics-calendar.layout-week').length > 0) {
		// Week select interactivity
		jQuery('.ics-calendar.layout-week .ics-calendar-select').on('change', function() {
			var r34ics_cal = jQuery(this).closest('.ics-calendar');
			r34ics_cal.addClass('show-past-events');
			r34ics_cal.find('.ics-calendar-month-grid tbody tr').css('display','none');
			if (r34ics_is_phone() && !r34ics_cal.hasClass('nomobile')) {
				r34ics_cal.find('.ics-calendar-month-grid tbody tr.' + jQuery(this).val()).css('display','block');
				r34ics_cal.find('.ics-calendar-month-grid tbody tr.' + jQuery(this).val()).css('display','table-row');
			}
			else {
				r34ics_cal.find('.ics-calendar-month-grid tbody tr.' + jQuery(this).val()).css('display','table-row');
			}
		});
		// Show/hide past events on mobile
		jQuery('a[data-ics-calendar-action="show-past-events"]').on('click', function() {
			var r34ics_cal = jQuery(this).closest('.ics-calendar');
			if (!r34ics_cal.hasClass('show-past-events')) {
				r34ics_cal.addClass('show-past-events');
				// On week view, remove this from the DOM instead of showing toggle
				jQuery(this).remove();
			}
			else {
				r34ics_cal.removeClass('show-past-events');
				jQuery(this).text(ics_calendar_i18n.show_past_events);
			}
			// Don't jump!
			return false;
		});
		// Initial state
		jQuery('.ics-calendar.layout-week .ics-calendar-month-grid:not(.fixed_dates) tbody tr').addClass('remove');
		jQuery('.ics-calendar.layout-week .ics-calendar-month-grid.fixed_dates tbody tr').addClass('current-week');
		jQuery('.ics-calendar.layout-week .ics-calendar-month-grid:not(.fixed_dates) tbody td.today').parent().addClass('current-week').removeClass('remove');
		jQuery('.ics-calendar.layout-week .ics-calendar-month-grid:not(.fixed_dates) tbody td.today').parent().prev().addClass('previous-week').removeClass('remove');
		jQuery('.ics-calendar.layout-week .ics-calendar-month-grid:not(.fixed_dates) tbody td.today').parent().next().addClass('next-week').removeClass('remove');
		jQuery('.ics-calendar.layout-week .ics-calendar-month-grid:not(.fixed_dates) tbody tr.remove').remove();
		if (r34ics_is_phone()) {
			jQuery('.ics-calendar.layout-week:not(.nomobile) .ics-calendar-month-grid tbody tr.current-week').css('display','block');
			jQuery('.ics-calendar.layout-week.nomobile .ics-calendar-month-grid tbody tr.current-week').css('display','table-row');
		}
		else {
			jQuery('.ics-calendar.layout-week .ics-calendar-month-grid tbody tr.current-week').css('display','table-row');
		}
		jQuery('.ics-calendar.layout-week .ics-calendar-select').show();
		jQuery('.ics-calendar.layout-week .ics-calendar-week-wrapper:first-of-type').show();
		// Remove Show Past Events link if there *are* no past events
		// Note: .month_list_all class is an ICS Calendar Pro-only feature
		jQuery('.ics-calendar:not(.month_list_all).layout-week').each(function() {
			if (jQuery(this).find('.ics-calendar-week-wrapper:visible .past:not(.empty)').length == 0) {
				jQuery(this).find('.ics-calendar-past-events-toggle').remove();
			}
		});
	}

	// VIEW: LIST / BASIC
	// Outer section wrapper has classes .ics-calendar.layout-list or .ics-calendar.layout-basic

	if (jQuery('.ics-calendar.layout-list').length > 0) {
		jQuery('.ics-calendar.layout-list .descloc_toggle_excerpt').on('click', function() {
			jQuery(this).hide().siblings('.descloc_toggle_full').show();
		});
	}
	
	if (jQuery('.ics-calendar.layout-list .ics-calendar-pagination, .ics-calendar.layout-basic .ics-calendar-pagination').length > 0) {
		jQuery('.ics-calendar-paginate').on('click', function() {
			var container = jQuery(this).closest('.ics-calendar');
			var current = container.find('.ics-calendar-pagination:visible');
			var dir = jQuery(this).hasClass('prev') ? 'prev' : 'next';
			var next, next_next, offset;
			container.find('.ics-calendar-paginate').show();
			switch (dir) {
				case 'prev':
					next = current.prev();
					next_next = next.prev();
					break;
				case 'next':
				default:
					next = current.next();
					next_next = next.next();
					break;
			}
			if (next.length != 0) {
				current.hide(); next.show();
			}
			if (next_next.length == 0) {
				container.find('.ics-calendar-paginate.' + dir).hide();
			}
			r34ics_show_hide_headers();
			return false;
		});
		jQuery('.ics-calendar.layout-list, .ics-calendar.layout-basic').each(function() {
			jQuery(this).find('.ics-calendar-pagination:not(:first-child)').hide();
			jQuery('.ics-calendar-paginate.prev').hide();
		});
		r34ics_show_hide_headers();
	}

	// VIEW: MONTH
	// Outer section wrapper has classes .ics-calendar.layout-month

	if (jQuery('.ics-calendar.layout-month').length > 0) {
		// Month select interactivity
		jQuery('.ics-calendar.layout-month .ics-calendar-select').on('change', function() {
			var r34ics_cal = jQuery(this).closest('.ics-calendar');
			r34ics_cal.find('.ics-calendar-month-wrapper').hide();
			r34ics_cal.find('.ics-calendar-month-wrapper[data-year-month="' + jQuery(this).val() + '"]').show();
			// Update query string in address bar
			if (jQuery(this).closest('.ics-calendar.layout-month').hasClass('stickymonths')) {
				r34ics_qs_update('r34icsym', jQuery(this).val(), (jQuery(this).val() == jQuery(this).data('this-month')));
			}
			// Change arrow labels
			var r34ics_arrownav = r34ics_cal.find('.ics-calendar-arrow-nav');
			if (r34ics_arrownav.length > 0) {
				var r34ics_arrownav_prev = jQuery(this).find('option:selected').prev();
				if (r34ics_arrownav_prev.length > 0) {
					r34ics_arrownav.find('.prev').data('goto', r34ics_arrownav_prev.attr('value')).removeClass('inactive');
					r34ics_arrownav.find('.prev-text').text(r34ics_arrownav_prev.text());
				}
				else {
					r34ics_arrownav.find('.prev').data('goto', '').addClass('inactive');
					r34ics_arrownav.find('.prev-text').text('');
				}
				var r34ics_arrownav_next = jQuery(this).find('option:selected').next();
				if (r34ics_arrownav_next.length > 0) {
					r34ics_arrownav.find('.next').data('goto', r34ics_arrownav_next.attr('value')).removeClass('inactive');
					r34ics_arrownav.find('.next-text').text(r34ics_arrownav_next.text());
				}
				else {
					r34ics_arrownav.find('.next').data('goto', '').addClass('inactive');
					r34ics_arrownav.find('.next-text').text('');
				}
				var r34ics_arrownav_current = jQuery(this).find('option:selected');
				if (r34ics_arrownav_current.val() == r34ics_arrownav.find('.today').data('goto')) {
					r34ics_arrownav.find('.today').addClass('inactive');
				}
				else {
					r34ics_arrownav.find('.today').removeClass('inactive');
				}
			}
		});
		// Month previous/next arrow interactivity
		jQuery('.ics-calendar.layout-month .ics-calendar-arrow-nav > *').unbind().on('click', function() {
			if (jQuery(this).data('goto') != '') {
				var r34ics_cal = jQuery(this).closest('.ics-calendar');
				r34ics_cal.find('.ics-calendar-select').val(jQuery(this).data('goto')).trigger('change');
			}
			return false;
		});
		// Show/hide past events on mobile
		jQuery('a[data-ics-calendar-action="show-past-events"]').on('click', function() {
			var r34ics_cal = jQuery(this).closest('.ics-calendar');
			if (!r34ics_cal.hasClass('show-past-events')) {
				r34ics_cal.addClass('show-past-events');
				// Show toggle
				jQuery(this).text(ics_calendar_i18n.hide_past_events);
			}
			else {
				r34ics_cal.removeClass('show-past-events');
				jQuery(this).text(ics_calendar_i18n.show_past_events);
			}
			// Don't jump!
			return false;
		});
		// Show/hide past events toggle depending on selected month
		jQuery('.ics-calendar-select').on('change', function() {
			var r34ics_cal = jQuery(this).closest('.ics-calendar');
			// Always show if we're showing the full list (Pro only)
			if (r34ics_cal.hasClass('month_list_all')) {
				r34ics_cal.find('a[data-ics-calendar-action="show-past-events"]').show();
			}
			else if (jQuery(this).val() == jQuery(this).attr('data-this-month')) {
				r34ics_cal.find('a[data-ics-calendar-action="show-past-events"]').show();
			}
			else {
				r34ics_cal.find('a[data-ics-calendar-action="show-past-events"]').hide();
			}
		});
		// Initial state
		jQuery('.ics-calendar.layout-month .ics-calendar-select:not(.hidden), .ics-calendar.layout-month .ics-calendar-arrow-nav').show();
		jQuery('.ics-calendar.layout-month .ics-calendar-month-wrapper[data-year-month="' + jQuery('.ics-calendar-select').val() + '"]').show();
		// Set dropdown/display to requested month from query string (r34icsym)
		var r34icsym = r34ics_qs_val('r34icsym');
		if (r34icsym != null && jQuery('.ics-calendar.layout-month .ics-calendar-select option[value="' + r34icsym + '"]').length == 1) {
			jQuery('.ics-calendar.layout-month .ics-calendar-select').val(r34icsym).trigger('change');
		}
		// If r34icsym is not in query string, or if the requested month is not in the dropdown, default to current month
		else {
			jQuery('.ics-calendar.layout-month .ics-calendar-select').trigger('change');
		}
		// Remove Show Past Events link if there *are* no past events
		// Note: .month_list_all class is an ICS Calendar Pro-only feature
		jQuery('.ics-calendar:not(.month_list_all).layout-month').each(function() {
			if (jQuery(this).find('.ics-calendar-month-wrapper[data-is-this-month="1"] .past:not(.empty)').length == 0) {
				jQuery(this).find('.ics-calendar-past-events-toggle').remove();
			}
		});
		// Automatically jump to next month in mobile view if no events
		r34ics_maybe_skip_to_next_month();
	}

	// DEBUGGER
	jQuery(".r34ics_debug_toggle").on("click", function() {
		if (jQuery(".r34ics_debug_wrapper").hasClass("minimized")) { jQuery(".r34ics_debug_wrapper").removeClass("minimized"); }
		else { jQuery(".r34ics_debug_wrapper").addClass("minimized"); }
	});

	// Custom event for callbacks
	jQuery(document).trigger('r34ics_init_end');

}


function r34ics_is_phone() {
	return window.innerWidth <= 782;
}


function r34ics_maybe_skip_to_next_month() {
	if (r34ics_is_phone() || jQuery('.ics-calendar.layout-month[data-month-table-list-toggle="list"]').length > 0) {
		jQuery('.ics-calendar:not(.nomobile).layout-month').each(function() {
			// Only change if this month has no/no more events, and next month *does* have events
			if	(
					jQuery(this).find('.ics-calendar-month-wrapper:visible').find('.no_events, .no_additional_events').length > 0 &&
					jQuery(this).find('.ics-calendar-month-wrapper:visible').next().find('.no_events').length == 0
				)
			{
				var r34ics_cal_select = jQuery(this).closest('.ics-calendar').find('.ics-calendar-select');
				var next_val = r34ics_cal_select.find('option[selected]').next().val();
				r34ics_cal_select.val(next_val).trigger('change');
			}
		});
	}
}


function r34ics_phone_day_headers() {
	if (r34ics_is_phone() && typeof r34ics_days_of_week_map != 'undefined') {
		jQuery('.ics-calendar-month-grid thead th').each(function() {
			var day_string = jQuery(this).text();
			if (typeof r34ics_days_of_week_map[day_string] != 'undefined') {
				jQuery(this).data('orig-str', day_string);
				jQuery(this).text(r34ics_days_of_week_map[day_string]);
			}
		});
	}
	else {
		jQuery('.ics-calendar-month-grid thead th').each(function() {
			if (jQuery(this).data('orig-str') != '') {
				jQuery(this).text(jQuery(this).data('orig-str'));
			}
		});
	}
}

// Update the address bar with a new query string value
// Note: Assumes key does not exist as a substring at the end of any other keys!
function r34ics_qs_update(key, val, remove) {
	if (history.pushState && val != null) {
		var qs, re;
		// We only want to remove this item, not update it
		if (remove == true) {
			// Check if it's actually present in the current query string first
			if (location.search.indexOf(key + '=') != -1) {
				re = new RegExp(key + '=[^&]*','g');
				qs = location.search.replace(re, '');
				// Strip the trailing ampersand if present
				if (qs.lastIndexOf('&') == qs.length - 1) {
					qs = qs.slice(0, -1);
				}
			}
		}
		// There is no query string; create it
		else if (location.search == '') {
			qs = '?' + key + '=' + val;
		}
		// This item is in the query string already; update it
		else if (location.search.indexOf(key + '=') != -1) {
			re = new RegExp(key + '=[^&]*','g');
			qs = location.search.replace(re, key + '=' + val);
		}
		// This item is not in the query string; append it
		else {
			// There's already a trailing ampersand
			if (location.search.lastIndexOf('&') == location.search.length - 1) {
				qs = location.search + key + '=' + val;
			}
			// There is not already a trailing ampersand
			else {
				qs = location.search + '&' + key + '=' + val;
			}
		}
		window.history.pushState({}, document.title, qs);
	}
}

// Get the value for a given key in the query string
function r34ics_qs_val(key) {
	var arr = location.search.replace('?','').split('&'), params = [], item, i;
	for (i = 0; i < arr.length; i++) {
		item = arr[i].split('=');
		params[item[0]] = item[1];
	}
	// Return sanitized value
	return jQuery('<div>').text(params[key]).html();
}


function r34ics_show_hide_headers(elem) {
	if (typeof elem == 'undefined' || elem == null) { elem = '.ics-calendar'; }
	// First we restore all of the headers we may be hiding
	jQuery(elem + ' .ics-calendar-list-wrapper .ics-calendar-date, ' + elem + ':not(.monthnav-compact) .ics-calendar-label, ' + elem + ' .ics-calendar-month-grid .day').show().removeClass('nomobile').removeClass('hidden_in_list');
	// In list view, hide/show the day header
	if (jQuery('.ics-calendar.layout-list').length > 0) {
		jQuery(elem + ' .ics-calendar-list-wrapper .ics-calendar-date').each(function() {
			if (jQuery(this).next('dl').find('.event:visible').length == 0) {
				jQuery(this).hide();
			}
			else {
				jQuery(this).show();
			}
		});
		// And also hide/show the month header
		jQuery(elem + ' .ics-calendar-list-wrapper .ics-calendar-label').each(function() {
			if (jQuery(this).siblings('.ics-calendar-date-wrapper').children('.ics-calendar-date:visible').length == 0) {
				jQuery(this).hide();
			}
			else {
				jQuery(this).show();
			}
		});
	}
	// In month view list (phone breakpoint), hide the day header
	// Also applies to Pro in month view with table/list toggle set to list
	if (jQuery('body.r34ics_phone .ics-calendar.layout-month').length > 0 || jQuery(elem).data('month-table-list-toggle') == 'list') {
		jQuery(elem + ' .ics-calendar-month-grid .events').each(function() {
			if (jQuery(this).find('.event:visible').length == 0) {
				jQuery(this).siblings('.day').addClass('nomobile').addClass('hidden_in_list');
			}
			else {
				jQuery(this).siblings('.day').removeClass('nomobile').removeClass('hidden_in_list');
			}
		});
		// And also hide/show the month header
		jQuery(elem + ' .ics-calendar-month-wrapper .ics-calendar-month-grid').each(function() {
			if (jQuery(this).find('.event:visible').length == 0) {
				jQuery(this).siblings('.ics-calendar-label').addClass('nomobile').addClass('hidden_in_list');
			}
			else {
				jQuery(this).siblings('.ics-calendar-label').removeClass('nomobile').removeClass('hidden_in_list');
			}
		});
	}
}


jQuery(window).on('load', function() {

	// Initialize ICS Calendar functionality if the initial DOM contains a calendar
	// AJAX-loaded calendars also fire off this function as needed
	if (jQuery('.ics-calendar').length > 0) {
		r34ics_init();
	}

	// Show/hide headers on initial load
	r34ics_show_hide_headers();
	
	// Adjust day names in headers for mobile
	r34ics_phone_day_headers();

	// Show/hide headers when user clicks anything, if the page loaded with a hidden calendar, and a calendar is visible after click
	if (jQuery('.ics-calendar').not(':visible').length > 0) {
		jQuery('body *').on('click', function() {
				if (jQuery('.ics-calendar').filter(':visible').length > 0) { r34ics_show_hide_headers(); }
		});
	}
	
	// AJAX
	if (jQuery(document).find('.r34ics-ajax-container').length > 0) {
		r34ics_ajax_init();
	}

});


jQuery(window).on('resize', function() {

	// Add/remove .r34ics_phone class on body
	if (r34ics_is_phone()) { jQuery('body').addClass('r34ics_phone'); } else { jQuery('body').removeClass('r34ics_phone'); }

	// Show/hide headers on resize
	r34ics_show_hide_headers();

	// Adjust day names in headers for mobile
	r34ics_phone_day_headers();

	// Automatically jump to next month in mobile view if no events
	r34ics_maybe_skip_to_next_month();

});


jQuery(document).on('r34ics_init_end', function() {

});
