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

				<h3 id="service_name" style="display:none;"></h3>

				<!-- SERVICE - Select -->

				<?php
				echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE32') . '*', 'service-new');
				
				// load services and group them
				$options = JHtml::fetch('vaphtml.admin.services', $strict = false, $blank = '', $group = true);

				if ($options)
				{
					// create dropdown attributes
					$params = array(
						'id'          => 'vap-services-sel',
						'group.items' => null,
						'list.select' => null,
						'list.attr'   => 'class="required"',
					);
					
					// render select
					echo JHtml::fetch('select.groupedList', $options, '', $params);
				}
				else
				{
					// no available services
					echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
				}

				echo $vik->closeControl();
				?>

				<!-- USE GLOBAL -->

				<?php
				$yes = $vik->initRadioElement('', JText::translate('JYES'), true, 'onclick="serviceGlobalValueChanged(1);"');
				$no  = $vik->initRadioElement('', JText::translate('JNO'), false, 'onclick="serviceGlobalValueChanged(0);"');

				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPMANAGESERVICE40'),
					'content' => JText::translate('VAPMANAGESERVICE40_HELP'),
				));

				echo $vik->openControl(JText::translate('VAPMANAGESERVICE40') . $help);
				echo $vik->radioYesNo('service_global', $yes, $no, false);
				echo $vik->closeControl();
				?>

				<!-- PRICE -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGESERVICE5'), 'service-global-child', array('style' => 'display: none;')); ?>
					<div class="input-prepend currency-field">
						<span class="btn"><?php echo VAPFactory::getCurrency()->getSymbol(); ?></span>

						<input type="number" id="service_rate" size="10" min="0" max="99999999" step="any" />
					</div>
				<?php echo $vik->closeControl(); ?>

				<!-- DURATION -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGESERVICE4'), 'service-global-child', array('style' => 'display: none;')); ?>
					<div class="input-append">
						<input type="number" id="service_duration" size="10" min="1" max="99999999" />
					
						<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
					</div>
				<?php echo $vik->closeControl(); ?> 

				<!-- SLEEP -->

				<?php
				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPMANAGESERVICE19'),
					'content' => JText::translate('VAPMANAGESERVICE19_DESC'),
				));

				echo $vik->openControl(JText::translate('VAPMANAGESERVICE19') . $help, 'service-global-child', array('style' => 'display: none;')); ?>
					<div class="input-append">
						<input type="number" id="service_sleep" size="10" min="-9999999" max="99999999" />
						
						<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
					</div>
				<?php echo $vik->closeControl(); ?> 

				<!-- DESCRIPTION -->

				<?php
				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPMANAGESERVICE3'),
					'content' => JText::translate('VAPDESCOVERRIDE_HELP'),
				));

				echo $vik->openControl(JText::translate('VAPMANAGESERVICE3') . $help, 'service-global-child', array('style' => 'display: none;'));
				// wrap editor within a form in order to avoid TinyMCE errors
				echo '<form>' . $vik->getEditor()->display('service_description', '', '100%', 550, 40, 20) . '</form>';
				echo $vik->closeControl();
				?> 

				<input type="hidden" id="service_id" value="0" />
				<input type="hidden" id="service_assoc_id" value="0" />

			</div>

		<?php echo $vik->bootEndTab(); ?>

		<?php echo $vik->bootAddTab('serempassoc', 'serempassoc_import', JText::translate('VAPMANAGEEMPLOYEE33')); ?>

			<div class="inspector-fieldset">

				<!-- DESCRIPTION -->

				<?php echo $vik->alert(JText::translate('VAPMANAGEEMPLOYEE33_DESC2'), 'info', false, ['style' => 'margin-top: 0;']); ?>

				<!-- URL -->

				<div>
					<select id="service_ical_url_type">
						<?php
						$options = [
							JHtml::fetch('select.option', 0, JText::translate('VAP_SELECT_USE_DEFAULT')),
							JHtml::fetch('select.option', 1, JText::translate('VAPMANAGECONFIG97')),
						];

						echo JHtml::fetch('select.options', $options);
						?>
					</select>
				</div>

				<div class="service_ical_url_type_1" style="display: none;">
					<input type="text" id="service_ical_url" placeholder="https://" style="margin-top: 10px;"/>
				</div>

			</div>

		<?php echo $vik->bootEndTab(); ?>

	<?php echo $vik->bootEndTabSet(); ?>

</div>

<script>

	var serValidator = new VikFormValidator('#inspector-service-employee-form');

	jQuery(function($) {
		$('#vap-services-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: '100%',
		});

		$('#service_ical_url_type').select2({
			allowClear: false,
			width: '100%',
		}).on('change', function() {
			if ($(this).val() == 1) {
				$('.service_ical_url_type_1').show();
			} else {
				$('.service_ical_url_type_1').hide();
			}
		});

		// use default settings when service change
		$('#vap-services-sel').on('change', function() {
			let id_service = parseInt($(this).val());

			resetServiceSettings(id_service);
		});

		$('#adminForm').on('submit', () => {
			const editor = Joomla.editors.instances.service_description;

			if (editor.onSave) {
				editor.onSave();
			}
		});
	});

	function fillServiceEmployeesForm(data) {
		let select = jQuery('#vap-services-sel');

		// clear selection
		select.select2('val', []);

		if (Array.isArray(data)) {
			// iterate all services inside select and fetch their status
			select.find('option').each(function() {
				let id = parseInt(jQuery(this).val());

				jQuery(this).prop('disabled', data.indexOf(id) !== -1);
			});

			// clear hidden input with service ID
			jQuery('#service_id').val(0);

			// hide service name
			jQuery('#service_name').hide().html('');

			// show service selection
			jQuery('.service-new').show();

			// service selection is required
			serValidator.registerFields(select);

			// clear data
			data = {};
		} else {
			// set current service ID
			jQuery('#service_id').val(data.id_service);

			// set service name 
			jQuery('#service_name').html(data.name).show();

			// hide service selection
			jQuery('.service-new').hide();

			// service selection is not required
			serValidator.unregisterFields(select);
		}

		// set assoc ID
		jQuery('#service_assoc_id').val(data.id ? data.id : 0);

		// set global status
		var globalInput = jQuery('input[name="service_global"]');

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

		serviceGlobalValueChanged(data.global);

		// set price
		if (data.rate === undefined) {
			data.rate = 0;
		}

		jQuery('#service_rate').val(data.rate);

		// set duration
		if (data.duration === undefined) {
			data.duration = 0;
		}

		jQuery('#service_duration').val(data.duration);

		// set sleep
		if (data.sleep === undefined) {
			data.sleep = 0;
		}

		jQuery('#service_sleep').val(data.sleep);

		// set description
		Joomla.editors.instances.service_description.setValue(data.description ? data.description : '');

		// set ical url
		if (data.ical_url === undefined) {
			data.ical_url = '';
		}

		jQuery('#service_ical_url').val(data.ical_url);
		jQuery('#service_ical_url_type').select2('val', data.ical_url ? 1 : 0).trigger('change');
	}

	function getServiceEmployeesData() {
		let service = extractServiceEmployeeData();

		let select = jQuery('#vap-services-sel');

		let id_service = select.select2('val');

		if (id_service) {
			// get selected option
			let option = select.find('option[value="' + id_service + '"]');

			// create service
			Object.assign(service, {
				id:         0,
				id_service: parseInt(id_service),
				name:       option.text(),
			});
		} else {
			// update existing service
			service.id = parseInt(jQuery('#service_assoc_id').val());

			service.id_service = parseInt(jQuery('#service_id').val());

			// get name from head
			service.name = jQuery('#service_name').html();
		}

		return service;
	}

	function extractServiceEmployeeData() {
		let data = {};

		// get global value
		if (jQuery('input[name="service_global"]').attr('type') == 'checkbox') {
			data.global = jQuery('input[name="service_global"]').is(':checked') ? 1 : 0;
		} else {
			data.global = parseInt(jQuery('input[name="service_global"]').val());
		}

		// get rate
		data.rate = parseFloat(jQuery('#service_rate').val());

		// get duration
		data.duration = parseInt(jQuery('#service_duration').val());

		// get sleep
		data.sleep = parseInt(jQuery('#service_sleep').val());

		// get description
		data.description = Joomla.editors.instances.service_description.getValue();

		// get ical url
		if (jQuery('#service_ical_url_type').val() == 1) {
			data.ical_url = jQuery('#service_ical_url').val();
		} else {
			data.ical_url = '';
		}

		return data;
	}

	function resetServiceSettings(id) {
		if (!SERVICES_LOOKUP.hasOwnProperty(id)) {
			return false;
		}

		let service = SERVICES_LOOKUP[id];

		jQuery('#service_rate').val(service.price);
		jQuery('#service_duration').val(service.duration);
		jQuery('#service_sleep').val(service.sleep);
	}

	function serviceGlobalValueChanged(is) {
		if (is) {
			jQuery('.service-global-child').hide();

			// get selected service
			let id_service = jQuery('#vap-services-sel').val();

			if (!id_service) {
				// get registered service
				id_service = jQuery('#service_id').val();
			}

			// reset settings to service values
			resetServiceSettings(parseInt(id_service));
		} else {
			jQuery('.service-global-child').show();
		}
	}

</script>
