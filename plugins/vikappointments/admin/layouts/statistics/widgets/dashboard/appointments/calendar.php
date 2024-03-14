<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Layout variables
 * -----------------
 * @var  VAPStatisticsWidget  $widget  The instance of the widget to be displayed.
 * @var  mixed                $data    The table rows data.
 */
extract($displayData);

JHtml::fetch('vaphtml.assets.toast', 'bottom-right');

$vik    = VAPApplication::getInstance();
$config = VAPFactory::getConfig();

JText::script('JLIB_APPLICATION_SAVE_SUCCESS');
JText::script('VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_DRAG_ERR');

?>

<style>
	.dashboard-appointments-calendar-wrapper {
		width: 100%;
	}

	.dashboard-appointments-calendar {
		background: #fff;
		width: 100%;
		max-height: 550px;
		overflow-y: scroll;
		padding: 20px 0 0 0;
		box-sizing: border-box;
	}

	.dashboard-appointments-calendar table {
		width: 100%;
		border-collapse: collapse;
	}
	.dashboard-appointments-calendar table td {
		height: 60px;
		border-top: 1px solid #ccc;
		position: relative;
	}
	.dashboard-appointments-calendar table td.time-col {
		width: 1%;
		white-space: nowrap;
		padding: 0 15px 0 0;
		font-weight: bold;
		border: 0;
		vertical-align: top;
	}
	.dashboard-appointments-calendar table td.time-col > div {
		position: relative;
		transform: translateY(-50%);
	}
	.dashboard-appointments-calendar .event {
		height: 60px;
		position: absolute;
		z-index: 99;
		width: 100%;
		text-align: left;
		padding: 5px;
		box-sizing: border-box;
		border-left: 2px solid;
		overflow: hidden;
		cursor: pointer;
	}
	.dashboard-appointments-calendar .event > * {
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.dashboard-appointments-calendar .event .time-info {
		font-weight: bold;
		font-size: 14px;
	}
	.dashboard-appointments-calendar .event .customer-info {
		font-size: 12px;
	}
	.dashboard-appointments-calendar .event .service-info {
		font-size: 11px;
	}
	.dashboard-appointments-calendar table td.time-col .current-time-clock {
		position: absolute;
		z-index: 98;
		color: #d00;
	}
	.dashboard-appointments-calendar table td.events-col .current-time-line {
		position: absolute;
		width: 100%;
		border-bottom: 1px solid #d00;
		/* draw behind the events */
		z-index: 98;
	}
	.dashboard-appointments-calendar table td.events-col .current-time-line:before{
		content: '';	
		width: 8px;
		height: 8px;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border-radius: 4px;
		background-color: #d00;
		position: absolute;
		transform: translate(-50%,-45%);
		top: 0;
		left: 0;
	}
	.dashboard-appointments-calendar-wrapper .vapallcalhead {
		margin: 0 0 10px 0;
		border-radius: 0;
		font-size: 16px;
		line-height: 16px;
	}
</style>

<div class="canvas-align-top">

	<div class="dashboard-appointments-calendar-wrapper">

		<div class="vapallcalhead">
			<a href="javascript:void(0)" class="prev-date-btn" data-date="" disabled>
				<i class="fas fa-angle-double-left big"></i>
			</a>
			
			<span class="vaptitleyearsp curr-date-lbl">
				
			</span>
			
			<a href="javascript:void(0)" class="next-date-btn" data-date="" disabled>
				<i class="fas fa-angle-double-right big"></i>
			</a>
		</div>
	
		<div class="dashboard-appointments-calendar">
			<table class="vap-disable-selection"></table>
		</div>

	</div>
	
</div>

<script>

	(function($) {
		'use strict';

		// get widget table
		let table;

		/**
		 * Defines the ratio to scale the size of the elements.
		 *
		 * @var float
		 */
		let TABLE_SCALE_RATIO = 2.0;

		/**
		 * Check whether we should auto-scroll the calendar to
		 * the line representing the current line.
		 * Always on at the page load.
		 *
		 * Applies only in case the selected day is equals to 
		 * the current one.
		 *
		 * @var boolean
		 */
		let SHOULD_SCROLL = true;

		/**
		 * The timestamp registered at the last scroll, useful
		 * to avoid auto-scrolling the parent in case it was
		 * made in a short time.
		 *
		 * @var integer
		 */
		let LAST_SCROLL_TIME = 0;

		/**
		 * Flag used to check whether the user is currently
		 * dragging an event.
		 *
		 * @var boolean
		 */
		let DRAGGING_EVENT = false;

		/**
		 * Registers the timeout handler while dragging.
		 * Every time a new drag start, this timeout is
		 * cleared to avoid clearing the flag above in
		 * a wrong time.
		 *
		 * @var mixed
		 */
		let DRAG_TIMER;

		/**
		 * Checks whether the specified intervals collide.
		 *
		 * @param 	Date  start1  The initial date time of the first interval.
		 * @param 	Date  end1    The ending date time of the first interval.
		 * @param 	Date  start2  The initial date time of the second interval.
		 * @param 	Date  end1    The ending date time of the second interval.
		 *
		 * @return 	boolean 
		 */
		const checkIntersection = (start1, end1, start2, end2) => {
			return (start1 <= start2 && start2 <  end1)
				|| (start1 <  end2   && end2   <= end1)
				|| (start2 <  start1 && end1   <  end2);
		}

		/**
		 * Proxy used to speed up the usage of checkIntersection by passing
		 * 2 valid events.
		 *
		 * @param 	object  event1  The first event.
		 * @param 	object  event2  The second event.
		 *
		 * @return 	boolean 
		 */
		const checkEventsIntersection = (event1, event2) => {
		    return checkIntersection(
				new Date(event1.checkin.date),
				new Date(event1.checkout.date),
				new Date(event2.checkin.date),
				new Date(event2.checkout.date)
			);
		}

		/**
		 * Returns a list containing all the events that collide with the
		 * specified one.
		 *
		 * @param 	object  event  An object holding the event details.
		 * @param 	mixed   level  An optional threshold to obtain only the
		 *                         events on the left of the specified one.
		 *
		 * @return  array
		 */
		const countIntersections = (event, level) => {
			let list = [];

			table.find('.event').each(function() {
				let event2 = $(this).data('event');

				if (checkEventsIntersection(event, event2)) {
					if (typeof level === 'undefined' || parseInt($(this).data('index')) < level) {
						list.push(this);
					}
				}
			});

			return list;
		}

		/**
		 * Recursively adjusts the location and size of all the events that
		 * collide with the specified one.
		 *
		 * @param 	object  event  An object holding the event details.
		 *
		 * @return 	void
		 */
		const fixSiblingsCount = (event) => {
			let did = [];

			// recursive fix
			_fixSiblingsCount(event, did);
		}

		/**
		 * Recursively adjusts the location and size of all the events that
		 * collide with the specified one.
		 * @visibility protected
		 *
		 * @param 	object  event  An object holding the event details.
		 * @param 	array   did    An array containing all the events that
		 *                         have been already fixed, just to avoid
		 *                         increasing them more than once.
		 *
		 * @return 	void
		 */
		const _fixSiblingsCount = (event, did) => {
			let index = parseInt($(event).data('index'));

			let intersections = countIntersections($(event).data('event'), index);

			if (intersections.length) {
				intersections.forEach((e) => {
					let found = false;

					// make sure we didn't already fetch this event
					did.forEach((ei) => {
						found = found || $(e).is(ei);
					});

					if (!found) {
						// get counters
						let tmp   = parseInt($(e).data('siblings'));
						let index = parseInt($(e).data('index'));

						// adjust counter, size and position
						$(e).data('siblings', tmp + 1);
						$(e).css('width', 'calc(100% / ' + (tmp + 2) + ')');
						$(e).css('left', 'calc(calc(100% / ' + (tmp + 2) + ') * ' + (index) + ')');

						// flag event as already adjusted
						did.push(e);

						// recursively adjust the colliding events
						_fixSiblingsCount(e, did);
					}
				});
			}
		}

		/**
		 * Adds the specified event into the calendar.
		 *
		 * @param 	object  data  An object holding the event details.
		 *
		 * @return 	void
		 */
		const addCalendarEvent = (data) => {
			let td;

			// search the tr matching the hour of the event
			table.find('tr').each(function() {
				let elem = $(this).find('td').first();

				if (elem.data('hour') == data.checkin.hour) {
					td = elem.next();
					return false;
				}
			});

			if (!td) {
				return false;
			}

			// create event
			const event = $('<div class="event"></div>');
			event.attr('id', 'event-' + data.id);
			event.attr('data-order-id', data.id);
			event.data('event', data);

			// add event title (time)
			event.append($('<div class="time-info"></div>').text(data.checkin.time));
			
			// add event subtitle (customer name)
			if (data.purchaser_nominative) {
				event.append($('<div class="customer-info"></div>').text(data.purchaser_nominative));
			}

			// add event note (service)
			event.append(
				$('<div class="service-info"></div>').text(
					[data.service_name, data.employee_name].join(' - ')
				)
			);

			// calculate event offset from top
			let offset = (data.checkin.hour * 60 + data.checkout.min) * TABLE_SCALE_RATIO;
			// calculate the threshold that cannot be exceeded
			let ceil = 1440 * TABLE_SCALE_RATIO;

			// make sure the height doesn't exceed the ceil
			let height = Math.min(data.duration * TABLE_SCALE_RATIO, ceil - offset) - 1;

			// vertically locate and resize the event box
			event.css('top', (data.checkin.min * TABLE_SCALE_RATIO) + 'px');
			event.css('height', height + 'px');

			// set color according to the selected service
			let color = ('' + data.service_color).replace(/^#/, '');

			event.css('background-color', '#' + color + '80');
			event.css('border-left-color', '#' + color);

			// count number of events that intersect the appointment
			let intersections = countIntersections(data);

			let count = 0;

			// find the highest index position among the colliding events
			intersections.forEach((e) => {
				count = Math.max(count, parseInt($(e).data('index')) + 1);
			});

			// init siblings counter and index with the amount previously found
			event.data('siblings', count);
			event.data('index', count);

			// recursively adjust the counter of any other colliding event
			fixSiblingsCount(event);

			// locate and size the event before attaching it
			event.css('width', 'calc(100% / ' + (count + 1) + ')');
			event.css('left', 'calc(calc(100% / ' + (count + 1) + ') * ' + (count) + ')');

			// get container node
			let wrapper = table.closest('.dashboard-appointments-calendar');

			if (data.draggable) {
				// allow event drag&drop
				makeEventDraggable(event, wrapper);
			}

			// attach event to calendar
			td.append(event);
		}

		/**
		 * Configures the calendar by adding all the specified events.
		 *
		 * @param 	array  events  A list of events to append.
		 *
		 * @return 	void
		 */
		const setupCalendar = (events) => {
			if (!events.length) {
				// do nothing
				return;
			}

			// init events
			events.forEach((event) => {
				event.intersections = [];
			});

			// scan conflicts between times
			for (var i = 0; i < events.length - 1; i++) {
				for (var j = i + 1; j < events.length; j++) {
					let a = events[i];
					let b = events[j];

					if (checkEventsIntersection(a, b)) {
						a.intersections.push(b);
						b.intersections.push(a);
					}
				}
			}

			// sort events by conflicts and ascending time
			events.sort((a, b) => {
				let diff = a.intersections.length - b.intersections.length;

				if (diff == 0) {
					// same intersections, sort by check-in time
					diff = (a.checkin.hour * 60 + a.checkin.min) - (b.checkin.hour * 60 + b.checkin.min);
				}

				return diff;
			});

			// attach events to calendar one by one
			events.forEach((event) => {
				addCalendarEvent(event);
			});
		}

		/**
		 * Loads the available timeline for the specified event.
		 * Needed to understand what are the available slots that can
		 * be used to drag the events.
		 *
		 * @param 	object  data  The event data.
		 *
		 * @return 	Promise
		 */
		const getAvailableTimeline = (data) => {
			return new Promise((resolve, reject) => {
				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=findreservation.timelineajax'); ?>',
					{
						day:    data.checkin.ymd,
						id_emp: data.id_employee,
						id_ser: data.id_service,
						id_res: data.id,
						people: data.people,
					},
					(resp) => {
						// successful response, pass the timeline
						resolve(resp.timeline);
					},
					(err) => {
						// request failed, no available days
						reject(err.responseText);
					}
				)
			});
		}

		/**
		 * Updates the selected event with the specified check-in.
		 *
		 * @param 	object  data     The event data.
		 * @param 	Date    checkin  The new check-in.
		 *
		 * @return 	Promise
		 */
		const updateEventCheckin = (data, checkin) => {
			// convert check-in date in SQL format
			// "YYYY-mm-ddTHH:ii:ss.000Z" becomes "YYYY-mm-dd HH:ii:ss"
			let sqlDate = checkin.toISOString().replace(/\.[\d]+Z/, '').split('T').join(' ');

			return new Promise((resolve, reject) => {
				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=reservation.changecheckinajax'); ?>',
					{
						id:         data.id,
						checkin_ts: sqlDate,
					},
					(resp) => {
						// successful update
						resolve(resp);
					},
					(err) => {
						// request failed
						reject(err.responseText);
					}
				)
			});
		}

		/**
		 * Provides functions to handle the events dragging.
		 *
		 * @param 	mixed  node     The node to bind.
		 * @param 	mixed  wrapper  The scrollable container.
		 *
		 * @return 	void
		 */
		const makeEventDraggable = (node, wrapper) => {
			// temporary flag to track the scroll top of the container when we
			// start dragging an event
			let initialScrollTop;

			// quickly access the event data
			let eventData = $(node).data('event');

			// internal property used to load the availability timeline
			let timelineArray = null;

			$(node).draggable({
				// allow only vertical dragging
				axis: 'y',
				// do not allow scrolling, which is manually handled
				scroll: false,
				// do not drag outside the table
				containment: table,
				// move only every 5 minutes
				grid: [0, 5 * TABLE_SCALE_RATIO],
				// handle drag start
				start: function(event, ui) {
					// stop dashboard timer while dragging an event
					$.vapDashboard('stop');

					// register initial scroll top
					initialScrollTop = wrapper.scrollTop();

					// reset availability timeline
					timelineArray = null;

					getAvailableTimeline(eventData).then((timeline) => {
						// register availability timeline
						timelineArray = timeline;
					}).catch((err) => {
						timelineArray = [];
						// log the reason of the error
						console.error(err);
					});

					// we are currently dragging the event
					DRAGGING_EVENT = true;
					clearTimeout(DRAG_TIMER);
				},
				// handle drag stop
				stop: function(event, ui) {
					// calculate new time by subtracting the original position from the new one
					let diffMinutes = (ui.position.top - ui.originalPosition.top) / TABLE_SCALE_RATIO;

					// wait until the timeline is ready
					onInstanceReady(() => {
						if (timelineArray === null) {
							return false;
						}

						return true;
					}).then(() => {
						let available = false;

						// calculate new selected check-in
						let newCheckin = new Date(eventData.checkin.date);
						newCheckin.setMinutes(newCheckin.getMinutes() + diffMinutes);

						// scan all timeline levels, until we find a matching time (Array#some)
						timelineArray.some((level) => {
							// scan all level times, until we find a matching time (Array#some)
							level.some((timeBlock) => {
								// compare only in case the time slot is available
								if (timeBlock.status) {
									// create date instance
									let blockDateTime = new Date(timeBlock.checkin);

									// compare with the new check-in
									if (newCheckin.toISOString() == blockDateTime.toISOString()) {
										available = true;
									}
								}

								// in case of matching time slot, break the array
								return available;
							});

							// in case of matching time slot, break the array
							return available;
						});

						// create promise to handle dashboard restart
						new Promise((resolve, reject) => {
							if (available) {
								updateEventCheckin(eventData, newCheckin).then(() => {
									// event updated
									resolve();
								}).catch((err) => {
									// an error occurred during the update (use fetched error)
									reject(err);
								});
							} else {
								// immediately reject in case the time was not available (use generic message)
								reject(null);
							}
						}).then(() => {
							// display successful message
							ToastMessage.dispatch(Joomla.JText._('JLIB_APPLICATION_SAVE_SUCCESS'));

							// refresh widget contents
							$.vapWidgetDo('refresh', '<?php echo $widget->getID(); ?>');
						}).catch((err) => {
							// display the reason of the error in a toast
							ToastMessage.dispatch({
								text: err || Joomla.JText._('VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_DRAG_ERR'),
								status: 0,
								delay: 3000,
							});

							// restore previous time
							$(this).find('.time-info').text(eventData.checkin.time);

							// revert node at its initial position
							$(this).animate(ui.originalPosition);
						}).finally(() => {
							// restart dashboard timer
							$.vapDashboard('start');

							DRAG_TIMER = setTimeout(() => {
								// we have finished to drag the event
								DRAGGING_EVENT = false;
							}, 500);
						});
					});
				},
				// handle drag event
				drag: function(event, ui) {
					// obtain offset from the beginning of the parent table
					let offset = ui.offset.top - table.offset().top;

					// check if we are moving the element up
					if (ui.originalPosition.top > ui.position.top) {
						// check if we should auto-scroll up
						if (offset <= wrapper.scrollTop() - 20) {
							// yes, position of the element reached the current scroll
							// position of the visible canvas, scroll up a bit
							wrapper.scrollTop(wrapper.scrollTop() - 10);
						}
					} else {
						// moving down, check if we should auto-scroll down
						if (offset - wrapper.scrollTop() - 20 + $(this).outerHeight() >= wrapper.height()) {
							// yes, the position of the element plus its height reached the
							// total height of the visible canvas, scroll down a bit
							wrapper.scrollTop(wrapper.scrollTop() + 10);
						}
					}

					// since the scroll is manually handled, we need to apply the difference between the
					// current scroll top and the previous one, in order to have a correct position
					ui.position.top += wrapper.scrollTop() - initialScrollTop;

					// calculate new time by subtracting the original position from the new one
					let diffMinutes = (ui.position.top - ui.originalPosition.top) / TABLE_SCALE_RATIO;

					// refresh time on block
					$(this).find('.time-info').text(getFormattedTime(eventData.checkin.hour, eventData.checkin.min + diffMinutes));
				}
			});

			// When making a draggable element, its position is automatically set to "relative"
			// which definitely goes in conflict with the current structure. For this reason, 
			// we need to immediately revert its position.
			$(node).css('position', 'absolute');
		}

		/**
		 * Register callback to be executed before
		 * launching the update request.
		 *
		 * @param 	mixed 	widget  The widget selector.
		 * @param 	object  config  The widget configuration.
		 *
		 * @return 	void
		 */
		WIDGET_PREFLIGHTS[<?php echo $widget->getID(); ?>] = (widget, config) => {
			if (!table) {
				// register table instance
				table = $(widget).find('table');

				// internal flag used to switch type of scroll registration
				let withDebounce = false;

				// callback used to track the last scroll timestamp
				function calendarScrollMonitor() {
					// register scroll time when scroll starts/stops
					LAST_SCROLL_TIME = new Date().getTime();

					// unregister previous scroll event
					$(this).off('scroll');

					if (withDebounce) {
						// in case we registered the event with debounce, we probably stopped scrolling, 
						// so we need to bind again the plain callback
						$(this).on('scroll', calendarScrollMonitor);
					} else {
						// in case we registered the event without debounce, we probably started scrolling, 
						// so we need to bind the callback with a debounce to handle pseudo-stop
						$(this).on('scroll', VikTimer.debounce('calendarScrollMonitor', calendarScrollMonitor, 500));
					}

					// switch debounce flag
					withDebounce = !withDebounce;
				}

				// monitor the table scroll to prevent the auto-scroll in case the user is scrolling the mouse
				table.parent().on('scroll', calendarScrollMonitor);
			}

			// init date with selected value
			let pickedDate = getDateFromFormat(config.date, '<?php echo $config->get('dateformat'); ?>', true);

			if (!pickedDate || isNaN(pickedDate.getTime())) {
				// invalid date, use the current one
				pickedDate = new Date();
			}

			// refresh date on widget toolbar
			$(widget).find('.curr-date-lbl').text(
				pickedDate.toLocaleDateString([], {year: 'numeric', month: '2-digit', day: '2-digit'})
			);

			// check whether the table contains the current line before updating the contents
			let currentLine = table.find('.current-time-line');

			if (currentLine.length) {
				// yep, check whether the line is visible
				let wrapper = $(widget).find('.dashboard-appointments-calendar');

				// calculate the difference between the
				let diff = currentLine.offset().top - wrapper.offset().top;

				// auto-scroll only in case the current line is still visible and the last scroll was made at least 15 seconds ago
				SHOULD_SCROLL = diff >= 0 && diff <= wrapper.outerHeight() && (LAST_SCROLL_TIME + 15000) < new Date().getTime();
			}
		}

		/**
		 * Register callback to be executed after
		 * completing the update request.
		 *
		 * @param 	mixed 	widget  The widget selector.
		 * @param 	string 	data    The JSON response.
		 * @param 	object  config  The widget configuration.
		 *
		 * @return 	void
		 */
		WIDGET_CALLBACKS[<?php echo $widget->getID(); ?>] = (widget, data, config) => {
			// reset HTML
			table.html('');

			if (!data) {
				// do nothing in case of error
				return;
			}

			let now = new Date(data.now);

			let hourFomatter = new Date();
			hourFomatter.setMinutes(0);

			let currentLine;

			// build rows according to the fetched bounds
			for (let h = parseInt(data.min); h <= parseInt(data.max); h++) {
				let tr = $('<tr></tr>');

				// update hours
				hourFomatter.setHours(h);

				// add column that holds the time
				tr.append(
					$('<td class="time-col"></td>')
						.data('hour', h)
							.append(
								$('<div class="time-clock"></div>').text(hourFomatter.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit'}))
							)
				);

				// add column that holds the events
				tr.append(
					$('<td class="events-col"></td>')
				);

				if ((now.getHours() == h - 1 && now.getMinutes() >= 55) || (now.getHours() == h && now.getMinutes() <= 5)) {
					tr.find('.time-clock').hide();
				}

				if (data.today && h == now.getHours()) {
					// create div to display the current time (subtract 1px to ignore the border top)
					let currentTime = $('<div class="current-time-clock"></div>')
						.text(now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit'}))
						.css('top', (now.getMinutes() * TABLE_SCALE_RATIO - 1) + 'px');

					// add time to the matching row
					tr.find('td').first().append(currentTime);

					// create line to highlight the current time (subtract 1px to ignore the border top)
					currentLine = $('<div class="current-time-line"></div>')
						.css('top', (now.getMinutes() * TABLE_SCALE_RATIO - 1) + 'px');

					// add line to the matching row
					tr.find('td').last().append(currentLine);
				}

				// add row to table
				table.append(tr);
			}

			// scale the default height of the cells (subtract 3px to ignore the borders stroke)
			table.find('td').css('height', (60 * TABLE_SCALE_RATIO - 3) + 'px');

			if (currentLine && SHOULD_SCROLL) {
				// in case of current line, auto-reach it
				let wrapper = widget.find('.dashboard-appointments-calendar');
				// add current scroll to line top offset, subtract top offset of the container, since the line
				// owns an absolute position and subtract half of the wrapper height to have a centered line
				wrapper.scrollTop(wrapper.scrollTop() + currentLine.offset().top - wrapper.offset().top - wrapper.outerHeight() / 2);
			}

			// update toolbar arrows
			$(widget).find('.prev-date-btn').attr('data-date', data.prev).prop('disabled', false);
			$(widget).find('.next-date-btn').attr('data-date', data.next).prop('disabled', false);

			// setup the calendar with the received appointments
			setupCalendar(data.appointments);

			if (!$.vapDashboard('running')) {
				// restart the previously paused timer
				$.vapDashboard('start');
			}
		}

		$(function() {
			$('#widget-<?php echo $widget->getID(); ?>').find('.prev-date-btn, .next-date-btn').on('click', function() {
				if ($(this).prop('disabled')) {
					return false;
				}

				// temporarily stop the timer to avoid double refreshes
				$.vapDashboard('stop');

				// get arrow date
				let date = $(this).attr('data-date');
				// disable until the request completes
				$(this).prop('disabled', true);

				// update configuration and do refresh
				$.vapWidgetDo('set', {
					id: '<?php echo $widget->getID(); ?>',
					key: 'date',
					val: date,
				});
			});

			// display order details in a modal box
			$(document).on('click', '#widget-<?php echo $widget->getID(); ?> .event[data-order-id]', function() {
				if (DRAGGING_EVENT) {
					// prevent click in case we were dragging the event
					return false;
				}

				// get order ID
				const order_id = $(this).data('order-id');

				// update href to access the management page of the order
				let href = $('#orderinfo-edit-btn').attr('href');
				$('#orderinfo-edit-btn').attr('href', href.replace(/cid\[\]=[\d]*$/, 'cid[]=' + order_id));

				// create URL
				const url = 'index.php?option=com_vikappointments&view=orderinfo&tmpl=component&cid[]=' + order_id;
				// open modal
				$('#jmodal-orderinfo').vapJModal('open', url);
			});
		});
	})(jQuery);

</script>
