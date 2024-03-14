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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$worktime = $this->worktime;

$vik = VAPApplication::getInstance();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">
	
	<?php echo $vik->openCard(); ?>

		<!-- MAIN -->

		<div class="span6">
			<?php echo $vik->openEmptyFieldset(); ?>

				<!-- DAY -->

				<?php
				$options = JHtml::fetch('vikappointments.days');

				// add special option
				$options[] = JHtml::fetch('select.option', -1, JText::translate('VAPMANAGEWD9'));

				$day = $worktime->ts == -1 ? $worktime->day : -1;

				echo $vik->openControl(JText::translate('VAPMANAGEWD2') . '*'); ?>
					<select name="day">
						<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $day); ?>
					</select>
				<?php echo $vik->closeControl(); ?>

				<!-- DATE -->

				<?php
				$control = array();
				$control['style'] = $day == -1 ? '' : 'display:none;';

				$attr = array();
				$attr['class'] = $day == -1 ? 'required' : '';

				$date = $worktime->ts != -1 ? JDate::getInstance($worktime->tsdate) : '';

				echo $vik->openControl('', 'special-day-field', $control);
				echo $vik->calendar($date, 'date', 'vap-date', null, $attr);
				echo $vik->closeControl();
				?>

				<!-- CLOSED -->

				<?php
				$yes = $vik->initRadioElement('', JText::translate('JYES'), $worktime->closed, 'onclick="closedValueChanged(1);"');
				$no  = $vik->initRadioElement('', JText::translate('JNO'), !$worktime->closed, 'onclick="closedValueChanged(0);"');

				echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE22'));
				echo $vik->radioYesNo('closed', $yes, $no, false);	
				echo $vik->closeControl(); ?>

				<!-- FROM -->

				<?php
				$times = JHtml::fetch('vikappointments.times', array(
					'step'  => 5,
					'value' => 'int',
				));

				echo $vik->openControl(JText::translate('VAPMANAGEWD3')); ?>
					<select name="fromts" class="time-select" <?php echo $worktime->closed ? 'disabled' : ''; ?>>
						<?php echo JHtml::fetch('select.options', $times, 'value', 'text', $worktime->fromts); ?>
					</select>
				<?php echo $vik->closeControl(); ?>

				<!-- TO -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEWD4')); ?>
					<select name="endts" class="time-select" <?php echo $worktime->closed ? 'disabled' : ''; ?>>
						<?php echo JHtml::fetch('select.options', $times, 'value', 'text', $worktime->endts); ?>
					</select>
				<?php echo $vik->closeControl(); ?>

				<!-- LOCATION -->

				<?php
				$locations = JHtml::fetch('vaphtml.admin.locations', $worktime->id_employee, $placeholder = '', $group = true);

				echo $vik->openControl(JText::translate('VAPMANAGEWD7'));

				$attrs = array();

				if ($worktime->closed)
				{
					$attrs['disabled'] = true;
				}

				$params = array(
					'id' 			=> 'vap-location-sel',
					'list.attr' 	=> $attrs,
					'group.items' 	=> null,
					'list.select'	=> $worktime->id_location,
				);

				echo JHtml::fetch('select.groupedList', $locations, 'id_location', $params);

				echo $vik->closeControl(); ?>

			<?php echo $vik->closeEmptyFieldset(); ?>
		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $worktime->id; ?>" />
	<input type="hidden" name="id_service" value="<?php echo $worktime->id_service; ?>" />
	<input type="hidden" name="id_employee" value="<?php echo $worktime->id_employee; ?>" />

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<script>

	jQuery(function($) {
		$('select[name="day"], .time-select').select2({
			allowClear: false,
			width: 200,
		});

		$('#vap-location-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: 300,
		});

		$('select[name="day"]').on('change', function() {
			if ($(this).val() == -1) {
				$('.special-day-field').show();
				validator.registerFields('#vap-date');
			} else {
				$('.special-day-field').hide();
				validator.unregisterFields('#vap-date');
			}
		});
	});

	function closedValueChanged(is) {
		jQuery('.time-select, #vap-location-sel').prop('disabled', is ? true : false);
	}

	// validate

	var validator = new VikFormValidator('#adminForm');

	validator.addCallback((form) => {
		let fromField = jQuery('select[name="fromts"]');
		let toField   = jQuery('select[name="endts"]');

		let from = parseInt(fromField.val());
		let to   = parseInt(toField.val());

		if (from >= to && fromField.prop('disabled') == false) {
			form.setInvalid(fromField.add(toField));

			return false;
		}

		form.unsetInvalid(fromField.add(toField));

		return true;
	});

	Joomla.submitbutton = function(task) {
		if (task.indexOf('save') !== -1) {
			if (validator.validate()) {
				Joomla.submitform(task, document.adminForm);    
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}
	
</script>
