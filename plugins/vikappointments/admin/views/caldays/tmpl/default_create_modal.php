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

JHtml::fetch('vaphtml.assets.select2');

$vik = VAPApplication::getInstance();

?>

<form action="index.php?option=com_vikappointments&task=reservation.add" method="post" id="newAppForm" class="inspector-form">

	<div class="inspector-fieldset">

		<!-- SERVICE - Select -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION4') . '*'); ?>
			<select name="id_ser" id="vap-service-select">
				
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- TIMELINE - Select -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION26') . '*'); ?>
			<select id="vap-checkin-select">
				
			</select>

			<p id="vap-checkin-error" style="display: none; color: #b00;"></p>
		<?php echo $vik->closeControl(); ?>

	</div>

	<input type="hidden" name="day" value="<?php echo JDate::getInstance($this->calendar->start)->format('Y-m-d'); ?>" />
	<input type="hidden" name="id_emp" value="" />
	<input type="hidden" name="hour" value="" />
	<input type="hidden" name="min" value="" />

	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="task" value="reservation.add" />
	<input type="hidden" name="from" value="caldays" />

</form>

<?php
JText::script('VAPFINDRESNODAYEMPLOYEE');
?>

<script>

	jQuery(function($) {
		$('button[data-role="reservation.create"]').on('click', function() {
			// extract hour and minute
			var hm = $('#vap-checkin-select').select2('val');

			if (!hm) {
				return false;
			}

			hm = hm.split(':');

			var form = $('#newAppForm');

			// inject hour and minute within the form
			form.find('input[name="hour"]').val(hm[0]);
			form.find('input[name="min"]').val(hm[1]);

			form.submit();
		});

		$('#vap-service-select, #vap-checkin-select').select2({
			allowClear: false,
			width: 300,
		});

		$('#vap-service-select').on('change', function() {
			let id_employee = $('#newAppForm').find('input[name="id_emp"]').val();
			let id_service  = $(this).val();
			let time        = $('#vap-checkin-select').val();

			if (time) {
				// fetch selected time in minutes format
				time = time.split(':');

				time = parseInt(time[0]) * 60 + parseInt(time[1]);
			}

			$('#vap-checkin-select').prop('disabled', true);

			// load timeline
			getEmployeeServiceTimeline(id_employee, id_service).then((timeline) => {
				fillTimelineDropdown(timeline.timeline, time);
			}).catch((error) => {
				// something went wrong...
				setTimelineError(error.responseText ? error.responseText : Joomla.JText._('VAPCONNECTIONLOSTERROR'));
			});
		});

	});

	function fillServicesDropdown(services, id_employee) {
		// register employee ID
		jQuery('#newAppForm').find('input[name="id_emp"]').val(id_employee);

		// get select
		var select = jQuery('#vap-service-select');

		// reset options
		var html = '';

		// iterate services
		for (var i = 0; i < services.length; i++) {
			html += '<option value="' + services[i].id + '">' + services[i].name + '</option>\n';
		}

		// replace options
		select.html(html);

		// always select first value
		select.select2('val', select.select2('val'));

		// disable dropdown in case the employee doesn't own any services
		select.prop('disabled', services.length == 0);

		// disable check-in dropdown
		jQuery('#vap-checkin-select').prop('disabled', true);

		// get selected service
		return select.select2('val');
	}

	function fillTimelineDropdown(timeline, value) {
		// hide error
		jQuery('#vap-checkin-error').hide().html('');

		// reset options
		var html = '';

		var found = false;

		// iterate timeline
		for (var i = 0; i < timeline.length; i++) {
			// scan minutes of working shift
			timeline[i].forEach((time) => {
				// create Date instance with check-in time
				let dt = new Date(time.checkin);

				let h = dt.getHours();
				let m = dt.getMinutes();

				// format time
				var str = getFormattedTime(h, m, '<?php echo VAPFactory::getConfig()->get('timeformat'); ?>');

				// build option
				html += '<option value="' + h + ':' + m + '"' + (time.status != 1 ? ' disabled="disabled"' : '') + '>' + str + '</option>\n';

				// find closest value
				if (found === false && (h * 60 + m) >= value) {
					found = h + ':' + m;
				}
			});
		}

		// get select
		var select = jQuery('#vap-checkin-select');

		// build options
		select.prop('disabled', false).html(html);

		if (found) {
			// select value found
			select.select2('val', found);
		} else {
			// select first value otherwise
			select.select2('val', select.select2('val'));
		}

		if (!timeline.length) {
			setTimelineError(Joomla.JText._('VAPFINDRESNODAYEMPLOYEE'));
		}
	}

	function setTimelineError(error) {
		jQuery('#vap-checkin-select').prop('disabled', true);
		jQuery('#vap-checkin-error').html(error).show();
	}

</script>
