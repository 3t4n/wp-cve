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

$vik = VAPApplication::getInstance();

?>

<div class="vaptimeline" id="vaptimeline">
				
</div>

<div class="vaptimeline-hover-tip" style="display:none;">
	<i class="fas fa-life-ring"></i>&nbsp;
	<?php echo JText::translate('VAPTIMELINEHOVERTIP'); ?>
</div>

<?php
JText::script('VAPCONNECTIONLOSTERROR');
JText::script('VAP_N_PEOPLE_1');
JText::script('VAP_N_PEOPLE');
JText::script('VAPSTATUSCLOSURE');
?>

<script>

	/* GROUP INFO EVENTS */

	var SLOT_HOVER_TIMEOUT 	= null;
	var SLOT_CURRENT_TARGET = null;
	var SLOT_AJAX_HANDLE 	= null;

	jQuery(function($) {
		// attempt to refresh timeline
		vapGetTimeline();

		// iterate each RED block to support view details action
		$('#vaptimeline').on('click', '.vaptlblock0', function() {
			vapViewDetails($(this).data('hour'), $(this).data('min'), this);
		});

		// implement hover/leave events for time slots with a number of seats
		$('#vaptimeline').on('mouseenter mouseleave', '.vap-timeline-block[data-seats]', function(event) {
			let seats = $(this).data('seats');

			if (isNaN(seats) || !seats) {
				return false;
			}

			if (event.type == 'mouseenter') {
				// register timer to load time slots
				SLOT_HOVER_TIMEOUT = setTimeout(() => {
					if (this == SLOT_CURRENT_TARGET) {
						return;
					}

					openSmartOverlay(this);
				}, 1000);
			} else {
				// reset slot loader
				clearTimeout(SLOT_HOVER_TIMEOUT);
			}
		});

		$(window).on('beforeunload click', () => {
			// close overlay in order to safely abort any pending
			// AJAX request before leaving the page
			closeSmartOverlay();
		});

		$(window).on('resize', function() {
			calculateOverlayPosition($('.smart-overlay'));
		});
	});

	function vapGetTimeline(date) {
		if (typeof date === 'undefined') {
			// recover stored date
			date = jQuery('#vapdayselected').val();

			if (!date) {
				// no selected date
				return false;
			}

			// auto-select clicked cell
			jQuery('.vaptdday[data-day="' + date + '"]').addClass('vaptdselected');
		} else {
			// register selected date
			jQuery('#vapdayselected').val(date);
		}

		// prepare timeline data
		let data = {
			day:    date,
			id_emp: <?php echo (int) $this->filters['id_emp']; ?>,
			id_ser: <?php echo (int) $this->filters['id_ser']; ?>,
			id_res: <?php echo (int) $this->filters['id_res']; ?>,
			people: jQuery('input[name="people"]').val(),
		};

		new Promise((resolve, reject) => {
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=findreservation.timelineajax'); ?>',
				data,
				(resp) => {
					resolve(resp);
				},
				(err) => {	
					reject(err);
				}
			);
		}).then((resp) => {
			// file timeline with fetched HTML
			jQuery('#vaptimeline').html(resp.html);
		}).catch((err) => {
			// display response error message
			jQuery('#vaptimeline').html(err.responseText);
			// unset selected date
			jQuery('#vapdayselected').val('');
		}).finally(() => {
			// animate only in case the timeline is not visible
			var px_to_scroll = isBoxOutOfMonitor(jQuery('#vaptimeline'), 60);
				
			if (px_to_scroll !== false) {
				jQuery('html,body').animate({scrollTop: "+=" + px_to_scroll}, {duration:'normal'});
			}

			// count slots with number of seats
			let count = jQuery('.vap-timeline-block[data-seats]');

			if (count.length) {
				// display "hover" label
				jQuery('.vaptimeline-hover-tip').show();
			} else {
				// hide "hover" label
				jQuery('.vaptimeline-hover-tip').hide();
			}
		});
	}

	function vapTimeClicked(hour, min, slot)
	{
		// inject selected hours and minutes
		document.adminForm.hour.value = hour;
		document.adminForm.min.value  = min;

		// check whether the employee ID is selected
		if (!jQuery('select[name="id_emp"]').val()) {
			// attempt to find parent employee ID
			const parent = jQuery(slot).closest('.vaptimeline-empblock[data-id]');

			if (parent.length) {
				// overwrite employee ID with the clicked one
				jQuery('#adminForm').append('<input type="hidden" name="id_emp" value="' + parent.data('id') + '" />');
			}
		}

		// fetch task based on reservation ID set in request
		let task = '<?php echo (int) $this->filters['id_res'] ? 'edit' : 'add'; ?>';

		// submit task
		Joomla.submitbutton('reservation.' + task);
	}

	function vapViewDetails(hour, min, slot) {
		let id_emp = parseInt(jQuery('select[name="id_emp"]').val());

		// check whether the employee ID is selected
		if (isNaN(id_emp) || !id_emp) {
			// attempt to find parent employee ID
			id_emp = jQuery(slot).closest('.vaptimeline-empblock[data-id]').data('id');
		}

		let date = jQuery('#vapdayselected').val();
		
		UIAjax.do(
			'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=findreservation.appointmentsajax'); ?>',
			{
				id_emp: id_emp,
				date:   date,
				hour:   hour,
				min:    min,
			},
			(resp) => {
				// map records to obtain only a list of reservations ID
				let ids = resp.map((elem) => { return elem.id; });

				// display modal
				displayDetailsView(ids);
			},
			(err) => {
				alert(err.responseText || Joomla.JText._('VAPCONNECTIONLOSTERROR'));
			}
		);
	}

	function openSmartOverlay(slot) {
		closeSmartOverlay();

		SLOT_CURRENT_TARGET = slot;

		var html = '<div class="smart-overlay-loading">\n'+
			'<i class="fas fa-circle-notch fa-spin fa-3x" style="font-size: 32px;"></i>\n'+
		'</div>';

		jQuery('#adminForm').append('<div class="smart-overlay">' + html + '</div>');

		var overlay = jQuery('.smart-overlay');

		calculateOverlayPosition(overlay);

		overlay.on('click', (event) => {
			event.preventDefault();
			event.stopPropagation();
		});

		loadSmartOverlayData(overlay);
	}

	function calculateOverlayPosition(overlay) {
		if (!SLOT_CURRENT_TARGET) {
			return;
		}

		var offset = jQuery(SLOT_CURRENT_TARGET).offset();

		var left = offset.left;

		if (left + overlay.width() >= jQuery(window).width() - 5) {
			left = jQuery(window).width() - overlay.width() - 5;
		}

		overlay.css('top', (offset.top - overlay.height() - 5) + 'px');
		overlay.css('left', left + 'px');
	}

	function loadSmartOverlayData(overlay) {
		var id_emp = <?php echo (int) $this->filters['id_emp']; ?>;

		if (!id_emp) {
			// fallback to obtain the employee ID from the timeline
			id_emp = jQuery(SLOT_CURRENT_TARGET).closest('.vaptimeline-empblock').data('id');
		}

		var date = jQuery('#vapdayselected').val();
		var hour = jQuery(SLOT_CURRENT_TARGET).data('hour');
		var min  = jQuery(SLOT_CURRENT_TARGET).data('min');

		// get cached record, if any
		var cache = VAPTempCache.get([id_emp, date, hour, min]);

		if (cache) {
			// fill smart overlay with cached data
			fillSmartOverlay(cache, overlay);
			return;
		}

		SLOT_AJAX_HANDLE = UIAjax.do(
			'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=findreservation.appointmentsajax'); ?>',
			{
				id_emp: id_emp,
				date:   date,
				hour:   hour,
				min:    min,
			},
			(resp) => {
				fillSmartOverlay(resp, overlay);
				// cache result for later use
				VAPTempCache.set([id_emp, date, hour, min], resp);
			},
			(err) => {
				if (err.statusText != 'abort') {
					console.log(err, err.responseText);
					alert(Joomla.JText._('VAPCONNECTIONLOSTERROR'));
				}

				// close overlay on error
				closeSmartOverlay();
			}
		);
	}

	function fillSmartOverlay(obj, overlay) {
		// reset overlay HTML
		overlay.html('');

		var _guest  = Joomla.JText._('VAP_N_PEOPLE_1');
		var _guests = Joomla.JText._('VAP_N_PEOPLE');

		obj.forEach((app) => {
			// create overlay record
			let record = jQuery('<div class="overlay-record"></div>');

			// create record ID column
			let recordId = jQuery('<div class="record-column col-id"></div>').html('#' + app.id);
			record.append(recordId);
			
			// create custom name column
			let recordCust = jQuery('<div class="record-column col-name"></div>');
			if (app.closure == 1) {
				recordCust.html(Joomla.JText._('VAPSTATUSCLOSURE').toUpperCase());
			} else {
				recordCust.html(app.purchaser_nominative || app.purchaser_phone || app.purchaser_mail);
			}
			record.append(recordCust);

			// create last column
			let recordThird = jQuery('<div class="record-column col-count"></div>');
			
			if (app.id_service == <?php echo $this->filters['id_ser']; ?>) {
				// same service, display number of people
				if (app.people > 1) {
					recordThird.html(_guests.replace(/%d/, app.people));
				} else {
					recordThird.html(_guest);
				}
			} else {
				// display service name
				recordThird.html(app.service_name);
			}

			record.append(recordThird);

			// append record to averlay
			overlay.append(record);
		});

		calculateOverlayPosition(overlay);
	}

	function closeSmartOverlay() {
		jQuery('.smart-overlay').remove();
		SLOT_CURRENT_TARGET = null;

		if (SLOT_AJAX_HANDLE) {
			SLOT_AJAX_HANDLE.abort();
			SLOT_AJAX_HANDLE = null;
		}
	}

</script>
