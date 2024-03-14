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

$config = VAPFactory::getConfig();

$times = JHtml::fetch('vikappointments.times', array(
	'step'  => 5,
	'value' => 'int',
));

?>

<div class="inspector-form" id="inspector-wd-form">

	<div class="inspector-fieldset">
		<h3><!-- fieldset title --></h3>

		<input type="hidden" data-id="wd_day" value="" />

		<!-- DATE - Calendar -->

		<?php
		echo $vik->openControl(JText::translate('VAPMANAGEWD2'), 'spday-mode conditional-mode');
		echo $vik->calendar('', 'wd_date');
		echo $vik->closeControl();
		?>
	
		<!-- CLOSED - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', JText::translate('JYES'), false, 'onclick="vapClosedValueChanged(1);"');
		$no  = $vik->initRadioElement('', JText::translate('JNO'), true, 'onclick="vapClosedValueChanged(0);"');

		echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE22'));
		echo $vik->radioYesNo('wd_closed', $yes, $no, false);	
		echo $vik->closeControl();
		?>

		<div style="display:none;" id="time-repeat">

			<div class="inspector-repeatable-head">
				<span class="time-summary">
					<span class="badge badge-info from"></span>
					<span class="badge badge-info to"></span>
				</span>

				<span>
					<a href="javascript: void(0);" class="wd-edit-time no-underline">
						<i class="fas fa-pen-square big ok"></i>
					</a>

					<a href="javascript: void(0);" class="wd-trash-time no-underline">
						<i class="fas fa-minus-square big no"></i>
					</a>
				</span>
			</div>

			<div class="inspector-repeatable-body">

				<div class="time-control">

					<!-- FROM -->

					<?php
					echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE14')); ?>
						<select class="time-select" data-id="wd_from">
							<?php echo JHtml::fetch('select.options', $times); ?>
						</select>
					<?php echo $vik->closeControl(); ?>

					<!-- TO -->

					<?php
					echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE15')); ?>
						<select class="time-select" data-id="wd_to">
							<?php echo JHtml::fetch('select.options', $times); ?>
						</select>
					<?php echo $vik->closeControl(); ?>

				</div>

				<input type="hidden" data-id="wd_id" value="0" />

			</div>

		</div>

		<div class="inspector-repeatable-container">
			
		</div>

		<!-- ADD TIME -->

		<?php echo $vik->openControl(''); ?>
			<button type="button" class="btn" id="wd-add-time"><?php echo JText::translate('VAPMANAGEWD8'); ?></button>
		<?php echo $vik->closeControl(); ?>

	</div>

</div>

<script>

	var inspectorMode = 'weekday';

	var wdValidator = new VikFormValidator('#inspector-wd-form');

	jQuery(function($) {
		let form = $('#inspector-wd-form');

		$(form).find('#wd-add-time').on('click', () => {
			// start at 8:00, end at 13:00 by defaul
			let wd = {
				from: 480,
				to: 780,
			};

			// get latest created time
			let repeatable = $('.inspector-repeatable').last();

			if (repeatable.length) {
				// recover latest time
				let hm = parseInt(repeatable.find('select[data-id="wd_to"]').val());

				// start from previous times
				wd.from = hm;
				// ends one hour after the start time
				wd.to = hm + 60;
			}

			// create time form
			vapAddTimeForm(wd);
		});

		wdValidator.addCallback((validator) => {
			let ok = true;
			let closed;

			// get closure
			if (form.find('input[name="wd_closed"]').attr('type') == 'checkbox') {
				closed = jQuery('input[name="wd_closed"]').is(':checked') ? 1 : 0;
			} else {
				closed = parseInt(jQuery('input[name="wd_closed"]').val());
			}

			// validate working times only in case the day is not closed
			if (!closed) {
				// iterate forms
				form.find('.inspector-repeatable').each(function() {
					let fromField = $(this).find('select[data-id="wd_from"]');
					let toField   = $(this).find('select[data-id="wd_to"]');

					let fields = $(fromField).add(toField);

					if (parseInt(fromField.val()) >= parseInt(toField.val())) {
						ok = false;
						// invalid time, flag field as invalid
						validator.setInvalid(fields);
					} else {
						// all fine, unset invalid flag
						validator.unsetInvalid(fields);
					}
				});
			}

			if (inspectorMode == 'spday') {
				// validate date too in case of special day
				let dateField = form.find('#wd_date');

				if (!dateField.val()) {
					ok = false;
					// missing date, flag field as invalid
					validator.setInvalid(dateField);
				} else {
					// all fine, unset invalid flag
					validator.unsetInvalid(dateField);
				}
			}

			return ok;
		});
	});

	function vapAddTimeForm(wd) {
		let form = jQuery('#inspector-wd-form');

		// get repeatable form of the inspector
		var repeatable = jQuery(form).find('#time-repeat');
		// clone the form
		var clone = jQuery('<div class="inspector-repeatable"></div>')
			.append(repeatable.clone().html());

		let fromSelect = clone.find('select[data-id="wd_from"]');

		// set up "from" time
		if (typeof wd.from !== 'undefined') {
			// make sure the time is supported
			if (fromSelect.find('option[value="' + wd.from + '"]').length) {
				fromSelect.val(wd.from);
			}
		}

		let toSelect = clone.find('select[data-id="wd_to"]');

		// set up "to" time
		if (typeof wd.to !== 'undefined') {
			// make sure the time is supported
			if (toSelect.find('option[value="' + wd.to + '"]').length) {
				toSelect.val(wd.to);
			}
		}

		// refresh head every time from and to select value changes
		jQuery(fromSelect).add(toSelect).on('change', function() {
			vapRefreshSummaryTime(clone);

			// auto-update "to" select with from time in case of invalid times
			if (jQuery(this).is(fromSelect) && parseInt(fromSelect.val()) > parseInt(toSelect.val())) {
				toSelect.select2('val', fromSelect.val());
			}

			// then give the focus to "to" select
			toSelect.select2('focus');
		});

		// set up summary head
		vapRefreshSummaryTime(clone);

		// set up ID
		if (typeof wd.id !== 'undefined') {
			clone.find('input[data-id="wd_id"]').val(wd.id);

			// auto-collapse existing blocks
			clone.addClass('collapsed');
		}

		// handle delete button
		clone.find('.wd-trash-time').on('click', () => {
			if (confirm(Joomla.JText._('VAPSYSTEMCONFIRMATIONMSG'))) {
				clone.remove();

				if (wd.id) {
					// register working day to delete
					form.append('<input type="hidden" data-id="wd_delete" value="' + wd.id + '" />');
				}
			}
		});

		// handle edit button
		clone.find('.wd-edit-time').on('click', () => {
			clone.toggleClass('collapsed');
		});

		// render dropdowns
		clone.find('select.time-select').select2({
			allowClear: false,
		});

		// append the clone to the document
		jQuery('.inspector-repeatable-container').append(clone);

		// start by focusing "from" select
		fromSelect.select2('focus');
	}

	function vapClosedValueChanged(is) {
		jQuery('.inspector-repeatable')
			.find('select,input,button')
				.add('#wd-add-time')
					.prop('disabled', is);

		if (is) {
			jQuery('.inspector-repeatable').add('#wd-add-time').hide();
		} else {
			jQuery('.inspector-repeatable').add('#wd-add-time').show();
		}
	}

	function vapRefreshSummaryTime(block) {
		// extract from-to times
		let from = parseInt(block.find('select[data-id="wd_from"]').val());
		let to   = parseInt(block.find('select[data-id="wd_to"]').val());

		// format from-to times
		let from_f = getFormattedTime(Math.floor(from / 60), from % 60, '<?php echo $config->get('timeformat'); ?>');
		let to_f   = getFormattedTime(Math.floor(to / 60), to % 60, '<?php echo $config->get('timeformat'); ?>');

		// set badge within block head
		block.find('.time-summary').find('.from').text(from_f);
		block.find('.time-summary').find('.to').text(to_f);
	}

	function setWorkingDayInspectorMode(mode) {
		inspectorMode = mode;

		jQuery('.conditional-mode').not('.' + mode + '-mode').hide();
		jQuery('.' + mode + '-mode').show();
	}

	function fillWorkingDayForm(data) {
		let form = jQuery('#inspector-wd-form');

		// fetch common data
		let wd = {};

		data.forEach((tmp) => {
			// look for a closure
			wd.closed = (wd.closed || parseInt(tmp.closed)) ? true : false;
			// retrieve day of the week
			wd.day = tmp.day;
			// retrieve special day
			wd.date = tmp.date;
		});

		// update closed status
		var closedInput = form.find('input[name="wd_closed"]');

		if (wd.closed === undefined) {
			wd.closed = false;
		}

		if (closedInput.attr('type') == 'checkbox') {
			closedInput.prop('checked', wd.closed);
		} else {
			closedInput.val(wd.closed ? 1 : 0);
		}

		if (inspectorMode == 'weekday') {
			// update week day
			if (typeof wd.day === 'undefined') {
				wd.day = 1;
			}

			form.find('input[data-id="wd_day"]').val(wd.day);
		} else if (inspectorMode == 'spday') {
			// update special date
			if (typeof wd.date === 'undefined') {
				wd.date = '';
			}

			// update data-alt-value too for MooTools compliance
			form.find('#wd_date').val(wd.date).attr('data-alt-value', wd.date);
		}

		// clear existing working times
		form.find('.inspector-repeatable-container').html('');

		if (data.length && data[0].id !== undefined) {
			// set up working times
			data.forEach((tmp) => {
				vapAddTimeForm(tmp);
			});
		}

		// clear all deleted working days on open
		jQuery('#inspector-wd-form').find('input[data-id="wd_delete"]').remove();

		// trigger "closed" change
		vapClosedValueChanged(wd.closed);
	}

	function getWorkingDayData() {
		let form = jQuery('#inspector-wd-form');

		let json = [];

		// load common data
		let data = {};
		
		switch (inspectorMode) {
			case 'weekday':
				// get day of the week
				data.day = parseInt(form.find('input[data-id="wd_day"]').val());
				break;

			case 'spday':
				// get date
				data.date = form.find('#wd_date').val();
				// obtain date in military format (false to avoid instantiating a date object)
				data.ymd = getDateFromFormat(data.date, '<?php echo $config->get('dateformat'); ?>', false);
				// trim separator from date
				data.ymd = data.ymd ? data.ymd.replace(/[^0-9]/g, '') : '';
				break;				
		}

		// get closure
		if (form.find('input[name="wd_closed"]').attr('type') == 'checkbox') {
			data.closed = jQuery('input[name="wd_closed"]').is(':checked') ? 1 : 0;
		} else {
			data.closed = parseInt(jQuery('input[name="wd_closed"]').val());
		}

		// iterate forms
		let times = form.find('.inspector-repeatable').each(function() {
			let wd = Object.assign({}, data);

			// retrieve from time
			wd.from = parseInt(jQuery(this).find('select[data-id="wd_from"]').val());

			// retrieve to time
			wd.to = parseInt(jQuery(this).find('select[data-id="wd_to"]').val());

			// retrieve record ID
			wd.id = parseInt(jQuery(this).find('input[data-id="wd_id"]').val());

			json.push(wd);
		});

		if (times.length == 0 && data.closed) {
			// no given times, pass what we found
			data.id = 0;

			json.push(data);
		}

		// sort times by ascending "from"
		json.sort((a, b) => {
			return a.from > b.from;
		});

		return json;
	}

	function getDeletedWorkingDays() {
		let list = [];

		jQuery('#inspector-wd-form')
			.find('input[data-id="wd_delete"]')
				.each(function() {
					list.push(parseInt(jQuery(this).val()));
				});

		return list;
	}

</script>
