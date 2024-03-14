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

<div class="inspector-form" id="inspector-service-employee-form">

	<?php echo $vik->bootStartTabSet('serempassoc', array('active' => 'serempassoc_details')); ?>

		<?php echo $vik->bootAddTab('serempassoc', 'serempassoc_details', JText::translate('VAPCUSTFIELDSLEGEND1')); ?>

			<div class="inspector-fieldset">

				<h3 id="employee_name" style="display:none;"></h3>

				<!-- EMPLOYEE - Select -->

				<?php
				echo $vik->openControl(JText::translate('VAPMANAGESERVICE38') . '*', 'employee-new');
				
				// load employees and group them by status
				$options = JHtml::fetch('vaphtml.admin.employees', $strict = false, $blank = false, $group = true);

				if ($options)
				{
					// create dropdown attributes
					$params = array(
						'id'          => 'vap-employees-sel',
						'group.items' => null,
						'list.select' => null,
						'list.attr'   => 'multiple class="required"',
					);
					
					// render select
					echo JHtml::fetch('select.groupedList', $options, '', $params);
				}
				else
				{
					// no available employees
					echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
				}

				echo $vik->closeControl();
				?>

				<!-- USE GLOBAL -->

				<?php
				$yes = $vik->initRadioElement('', JText::translate('JYES'), true, 'onclick="employeeGlobalValueChanged(1);"');
				$no  = $vik->initRadioElement('', JText::translate('JNO'), false, 'onclick="employeeGlobalValueChanged(0);"');

				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPMANAGESERVICE40'),
					'content' => JText::translate('VAPMANAGESERVICE40_HELP'),
				));

				echo $vik->openControl(JText::translate('VAPMANAGESERVICE40') . $help);
				echo $vik->radioYesNo('employee_global', $yes, $no, false);
				echo $vik->closeControl();
				?>

				<!-- PRICE -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGESERVICE5'), 'employee-global-child', array('style' => 'display: none;')); ?>
					<div class="input-prepend currency-field">
						<span class="btn"><?php echo VAPFactory::getCurrency()->getSymbol(); ?></span>

						<input type="number" id="employee_rate" size="10" min="0" max="99999999" step="any" />
					</div>
				<?php echo $vik->closeControl(); ?>

				<!-- DURATION -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGESERVICE4'), 'employee-global-child', array('style' => 'display: none;')); ?>
					<div class="input-append">
						<input type="number" id="employee_duration" size="10" min="1" max="99999999" />
					
						<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
					</div>
				<?php echo $vik->closeControl(); ?> 

				<!-- SLEEP -->

				<?php
				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPMANAGESERVICE19'),
					'content' => JText::translate('VAPMANAGESERVICE19_DESC'),
				));

				echo $vik->openControl(JText::translate('VAPMANAGESERVICE19') . $help, 'employee-global-child', array('style' => 'display: none;')); ?>
					<div class="input-append">
						<input type="number" id="employee_sleep" size="10" min="-9999999" max="99999999" />
						
						<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
					</div>
				<?php echo $vik->closeControl(); ?> 

				<!-- DESCRIPTION -->

				<?php
				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPMANAGESERVICE3'),
					'content' => JText::translate('VAPDESCOVERRIDE_HELP'),
				));

				echo $vik->openControl(JText::translate('VAPMANAGESERVICE3') . $help, 'employee-global-child', array('style' => 'display: none;'));
				// wrap editor within a form in order to avoid TinyMCE errors
				echo '<form>' . $vik->getEditor()->display('employee_description', '', '100%', 550, 40, 20) . '</form>';
				echo $vik->closeControl();
				?> 

				<input type="hidden" id="employee_id" value="0" />
				<input type="hidden" id="employee_assoc_id" value="0" />

			</div>

		<?php echo $vik->bootEndTab(); ?>

		<?php echo $vik->bootAddTab('serempassoc', 'serempassoc_import', JText::translate('VAPMANAGEEMPLOYEE33')); ?>

			<div class="inspector-fieldset">

				<!-- DESCRIPTION -->

				<?php echo $vik->alert(JText::translate('VAPMANAGEEMPLOYEE33_DESC2'), 'info', false, ['style' => 'margin-top: 0;']); ?>

				<!-- URL -->

				<div>
					<select id="employee_ical_url_type">
						<?php
						$options = [
							JHtml::fetch('select.option', 0, JText::translate('VAP_SELECT_USE_DEFAULT')),
							JHtml::fetch('select.option', 1, JText::translate('VAPMANAGECONFIG97')),
						];

						echo JHtml::fetch('select.options', $options);
						?>
					</select>
				</div>

				<div class="employee_ical_url_type_1" style="display: none;">
					<input type="text" id="employee_ical_url" placeholder="https://" style="margin-top: 10px;"/>
				</div>

			</div>

		<?php echo $vik->bootEndTab(); ?>

	<?php echo $vik->bootEndTabSet(); ?>

</div>

<script>

	var empValidator = new VikFormValidator('#inspector-service-employee-form');

	jQuery(function($) {
		$('#vap-employees-sel').select2({
			width: '100%',
		});

		$('#employee_ical_url_type').select2({
			allowClear: false,
			width: '100%',
		}).on('change', function() {
			if ($(this).val() == 1) {
				$('.employee_ical_url_type_1').show();
			} else {
				$('.employee_ical_url_type_1').hide();
			}
		});

		$('#adminForm').on('submit', () => {
			const editor = Joomla.editors.instances.employee_description;

			if (editor.onSave) {
				editor.onSave();
			}
		});
	});

	function fillServiceEmployeesForm(data) {
		let select = jQuery('#vap-employees-sel');

		// clear selection
		select.select2('val', []);

		if (Array.isArray(data)) {
			// iterate all employees inside select and fetch their status
			select.find('option').each(function() {
				let id = parseInt(jQuery(this).val());

				jQuery(this).prop('disabled', data.indexOf(id) !== -1);
			});

			// clear hidden input with employee ID
			jQuery('#employee_id').val(0);

			// hide employee name
			jQuery('#employee_name').hide().html('');

			// show employee selection
			jQuery('.employee-new').show();

			// employee selection is required
			empValidator.registerFields(select);

			// clear data
			data = {};
		} else {
			// set current employee ID
			jQuery('#employee_id').val(data.id_employee);

			// set employee name 
			jQuery('#employee_name').html(data.nickname).show();

			// hide employee selection
			jQuery('.employee-new').hide();

			// employee selection is not required
			empValidator.unregisterFields(select);
		}

		// set assoc ID
		jQuery('#employee_assoc_id').val(data.id ? data.id : 0);

		// set global status
		var globalInput = jQuery('input[name="employee_global"]');

		if (data.global === undefined) {
			data.global = 1;
		} else {
			data.global = parseInt(data.global);
		}

		if (globalInput.attr('type') == 'checkbox') {
			globalInput.prop('checked', data.global);
		} else {
			globalInput.val(data.global ? 1 : 0);
		}

		employeeGlobalValueChanged(data.global);

		// set price
		if (data.rate === undefined) {
			// use default service price
			data.rate = parseFloat(jQuery('input[name="price"]').val());
		}

		jQuery('#employee_rate').val(data.rate);

		// set duration
		if (data.duration === undefined) {
			// use default service duration
			data.duration = parseInt(jQuery('input[name="duration"]').val());
		}

		jQuery('#employee_duration').val(data.duration);

		// set sleep
		if (data.sleep === undefined) {
			// use default service sleep
			data.sleep = parseInt(jQuery('input[name="sleep"]').val());
		}

		jQuery('#employee_sleep').val(data.sleep);

		// set description
		Joomla.editors.instances.employee_description.setValue(data.description ? data.description : '');

		// set ical url
		if (data.ical_url === undefined) {
			data.ical_url = '';
		}

		jQuery('#employee_ical_url').val(data.ical_url);
		jQuery('#employee_ical_url_type').select2('val', data.ical_url ? 1 : 0).trigger('change');
	}

	function getServiceEmployeesData() {
		let employees = [];

		let data = extractServiceEmployeeData();

		let select = jQuery('#vap-employees-sel');

		select.select2('val').forEach((id) => {
			// get selected option
			let option = select.find('option[value="' + id + '"]');

			// create employee
			let tmp = Object.assign({
				id:          0,
				id_employee: parseInt(id),
				nickname:    option.text(),
			}, data);

			employees.push(tmp);
		});

		if (employees.length == 0) {
			data.id = parseInt(jQuery('#employee_assoc_id').val());

			data.id_employee = parseInt(jQuery('#employee_id').val());

			// get name from head
			data.nickname = jQuery('#employee_name').html();

			if (data.id_employee && !isNaN(data.id_employee)) {
				employees.push(data);
			}
		}

		return employees;
	}

	function extractServiceEmployeeData() {
		let data = {};

		// get global value
		if (jQuery('input[name="employee_global"]').attr('type') == 'checkbox') {
			data.global = jQuery('input[name="employee_global"]').is(':checked') ? 1 : 0;
		} else {
			data.global = parseInt(jQuery('input[name="employee_global"]').val());
		}

		if (!data.global) {
			// get rate
			data.rate = parseFloat(jQuery('#employee_rate').val());

			// get duration
			data.duration = parseInt(jQuery('#employee_duration').val());

			// get sleep
			data.sleep = parseInt(jQuery('#employee_sleep').val());

			// get description
			data.description = Joomla.editors.instances.employee_description.getValue();
		}

		// get ical url
		if (jQuery('#employee_ical_url_type').val() == 1) {
			data.ical_url = jQuery('#employee_ical_url').val();
		} else {
			data.ical_url = '';
		}

		return data;
	}

	function employeeGlobalValueChanged(is) {
		if (is) {
			jQuery('.employee-global-child').hide();
		} else {
			jQuery('.employee-global-child').show();
		}
	}

</script>
