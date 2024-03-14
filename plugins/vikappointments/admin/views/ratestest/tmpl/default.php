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
JHtml::fetch('vaphtml.assets.fontawesome');

$args = $this->args;

$vik = VAPApplication::getInstance();

?>

<div class="ratestest" style="padding: 10px;">

	<form action="index.php" method="post" name="adminForm" id="adminForm">

		<!-- MAIN -->

		<div class="row-fluid">

			<!-- SEARCH -->

			<div class="span6">
				<?php echo $vik->openEmptyFieldset(); ?>

					<!-- SERVICE - Select -->

					<?php echo $vik->openControl(JText::translate('VAPORDERSERVICE') . '*'); ?>
						<select name="id_service" id="vap-service-sel" class="required">
							<option></option>
							<?php
							foreach ($this->services as $group)
							{
								?>
								<optgroup label="<?php echo $group->name ? $group->name : JText::translate('VAPSERVICENOGROUP'); ?>">
									<?php
									foreach ($group->list as $service)
									{
										?>
										<option
											value="<?php echo $service->id; ?>"
											data-min="<?php echo $service->capacity[0]; ?>"
											data-max="<?php echo $service->capacity[1]; ?>"
											data-perpeople="<?php echo $service->priceperpeople; ?>"
											<?php echo ($args['id_service'] == $service->id ? 'selected="selected"' : ''); ?>
											><?php echo $service->name; ?></option>
										<?php
									}
									?>
								</optgroup>
								<?php
							}
							?>
						</select>
					<?php echo $vik->closeControl(); ?>

					<!-- EMPLOYEE - Select -->

					<?php echo $vik->openControl(JText::translate('VAPORDEREMPLOYEE')); ?>
						<select name="id_employee" id="vap-employee-sel" disabled>
							<option></option>
						</select>
					<?php echo $vik->closeControl(); ?>

					<!-- USER GROUPS - Select -->

					<?php
					$groups = array();
					$groups[] = JHtml::fetch('select.option', '', '');

					$groups = array_merge($groups, JHtml::fetch('user.groups', true));
					
					echo $vik->openControl(JText::translate('VAPUSERGROUPS')); ?>
						<select name="usergroup" id="vap-usergroup-sel">
							<?php echo JHtml::fetch('select.options', $groups); ?>
						</select>
					<?php echo $vik->closeControl(); ?>

					<!-- CHECKIN - Calendar -->

					<?php
					$attrs = array(
						'showTime' => 1,
						'class'    => 'required',
					);

					$date = $args['checkin'] ? $args['checkin'] : VikAppointments::now();
					
					echo $vik->openControl(JText::translate('VAPMANAGERESERVATION26') . '*');
					echo $vik->calendar($date, 'checkin', 'checkin', null, $attrs);
					echo $vik->closeControl();
					?>

					<!-- PEOPLE - Select -->

					<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION25')); ?>
						<select name="people" id="vap-people-sel" disabled>
							<option value="1">1</option>
						</select>
					<?php echo $vik->closeControl(); ?>

					<!-- DEBUG - Checkbox -->

					<?php
					$yes = $vik->initRadioElement('', '', $args['debug']);
					$no  = $vik->initRadioElement('', '', !$args['debug']);
					
					echo $vik->openControl('Debug:');
					echo $vik->radioYesNo('debug', $yes, $no, true);
					echo $vik->closeControl();
					?>

					<!-- SUBMIT - Button -->

					<?php echo $vik->openControl(''); ?>
						<button type="button" class="btn" onclick="submitRateForm(this);">
							<?php echo JText::translate('VAPTESTRATES'); ?>
						</button>
					<?php echo $vik->closeControl(); ?>

				<?php echo $vik->closeEmptyFieldset(); ?>
			</div>

			<!-- RESULT -->

			<div class="span6" id="result-wrap" style="display: none;">
				<?php echo $vik->openEmptyFieldset(); ?>

					<table class="rates-table table" id="rates-table" style="display: none;">
						<thead>
							<tr>
								<th><?php echo JText::translate('JGRID_HEADING_ID'); ?></th>
								<th><?php echo JText::translate('JDETAILS'); ?></th>
								<th style="text-align:center;"><?php echo JText::translate('VAPCHDISC'); ?></th>
							</tr>
						</thead>

						<tbody></tbody>

						<tfoot></tfoot>
					</table>

				<?php echo $vik->closeEmptyFieldset(); ?>
			</div>

		</div>

		<!-- DEBUG -->

		<div class="row-fluid">

			<div style="display: none;" id="debug-wrap">
				<?php echo $vik->openFieldset(JText::translate('VAPDEBUGRATESFIELDSET')); ?>

					<table class="rates-table table" id="debug-table">
						<thead>
							<tr>
								<th><?php echo JText::translate('JGRID_HEADING_ID'); ?></th>
								<th><?php echo JText::translate('JDETAILS'); ?></th>
								<th><?php echo JText::translate('ERROR'); ?></th>
							</tr>
						</thead>

						<tbody></tbody>

						<tfoot></tfoot>
					</table>

				<?php echo $vik->closeFieldset(); ?>
			</div>

		</div>

	</form>

</div>

<?php
JText::script('VAPPAYMENTPOSOPT1');
JText::script('VAPCONNECTIONLOSTERROR');
JText::script('VAPBASECOST');
JText::script('VAPCOSTPP');
JText::script('VAPFINALCOST');
JText::script('VAPDEBUGRATESFOOTER');
?>

<script>

	var validator = new VikFormValidator('#adminForm');
	var EMPLOYEES_POOL = {};

	var EMPLOYEE_ID = 0;
	
	jQuery(function($) {

		$('#vap-service-sel').select2({
			placeholder: '--',
			allowClear: false,
			width: '90%',
		});

		$('#vap-usergroup-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: '90%',
		});

		initEmployeeDropdown();

		initPeopleDropdown();

		//

		$('#vap-service-sel').on('change', function() {
			var sel = $(this).find('option:selected');

			var min = parseInt(sel.data('min'));
			var max = parseInt(sel.data('max'));
			var id  = parseInt($(this).val());

			buildPeopleDropdown(min, max);

			buildEmployeeDropdown(id);
		});

		function initEmployeeDropdown() {
			$('#vap-employee-sel').select2({
				placeholder: Joomla.JText._('VAPPAYMENTPOSOPT1'),
				allowClear: true,
				width: '90%',
			});

			if (EMPLOYEE_ID > 0) {
				$('#vap-employee-sel').select2('val', EMPLOYEE_ID);
				// unset EMPLOYEE ID to avoid re-using every time the service is changed
				EMPLOYEE_ID = 0;
			}
		}

		function initPeopleDropdown() {
			$('#vap-people-sel').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: '90%',
			});
		}

		function buildPeopleDropdown(min, max) {
			var options = '';

			for (var i = min; i <= max; i++) {
				options += '<option value="' + i + '">' + i + '</option>\n';
			}

			$('#vap-people-sel').html(options);
			$('#vap-people-sel').attr('disabled', min < max ? false : true);
			
			$('#vap-people-sel').select2('destroy');
			initPeopleDropdown();
		}

		function buildEmployeeDropdown(id) {
			$('#vap-employee-sel').attr('disabled', true);

			if (EMPLOYEES_POOL.hasOwnProperty(id)) {
				renderEmployeeDropdown(EMPLOYEES_POOL[id]);
				return;
			}

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=service.employeesajax'); ?>',
				{
					id_ser: id,
					all: true,
				},
				(obj) => {
					EMPLOYEES_POOL[id] = obj;

					renderEmployeeDropdown(EMPLOYEES_POOL[id]);
				},
				(err) => {
					console.log(err);
					alert(Joomla.JText._('VAPCONNECTIONLOSTERROR'));
				}
			);
		}

		function renderEmployeeDropdown(employees) {
			var options = '<option></option>';

			for (var i = 0; i < employees.length; i++) {
				options += '<option value="'+employees[i].id+'" data-rate="'+employees[i].rate+'">'+employees[i].nickname+'</option>\n';
			}

			$('#vap-employee-sel').html(options);
			$('#vap-employee-sel').attr('disabled', false);
			
			$('#vap-employee-sel').select2('destroy');
			initEmployeeDropdown();
		}

		// fill data with user states
		<?php if ($args['id_service'] > 0) { ?>
			$('#vap-service-sel').trigger('change');
			$('#vap-people-sel').select2('val', <?php echo $args['people']; ?>);
			EMPLOYEE_ID = <?php echo (int) $args['id_employee']; ?>;
		<?php } ?>

	});

	function submitRateForm(btn) {
		jQuery('#result-wrap').show();

		jQuery('#rates-table').hide();
		jQuery('#debug-wrap').hide();

		if (!validator.validate()) {
			return false;
		}

		jQuery(btn).attr('disabled', true);

		UIAjax.do(
			'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=rate.testajax'); ?>',
			jQuery('#adminForm').serialize(),
			(obj) => {
				// for debug purposes
				console.log(obj);

				var rates = [];
				if (obj[1].hasOwnProperty('rates')) {
					rates = obj[1].rates;
				}

				buildRatesTable(obj[0], obj[1].basecost, rates);

				if (obj[1].hasOwnProperty('debug')) {
					buildDebugTable(obj[1].debug);
				}

				jQuery(btn).attr('disabled', false);
			},
			(err) => {
				console.log(err);
				alert(Joomla.JText._('VAPCONNECTIONLOSTERROR'));

				jQuery(btn).attr('disabled', false);
			}
		);
	}

	function buildRatesTable(finalCost, baseCost, rates) {
		var table = jQuery('#rates-table');
		var tbody = '';
		var tfoot = '';

		// table body
		tbody += createTableRow('', Joomla.JText._('VAPBASECOST'), baseCost);

		var attrs = {class: 'rate-child'};

		for (var i = 0; i < rates.length; i++) {
			var details = rates[i].name;
			if (rates[i].description.length) {
				details = [details, rates[i].description];
			}

			tbody += createTableRow(rates[i].id, details, rates[i].charge, attrs);
		}

		// table footer
		var guests = 1;

		var perpeople = parseInt(jQuery('#vap-service-sel option:selected').data('perpeople'));
		var capacity  = parseInt(jQuery('#vap-service-sel option:selected').data('max'));

		if (perpeople == 1 && capacity > 1) {
			// cost per person
			tfoot += createTableRow('', Joomla.JText._('VAPCOSTPP'), finalCost);
			guests = parseInt(jQuery('#vap-people-sel').val());
		}

		tfoot += createTableRow('', Joomla.JText._('VAPFINALCOST'), finalCost * guests, {class: 'final-cost'});

		// build table and show
		table.find('tbody').html(tbody);
		table.find('tfoot').html(tfoot);

		table.show();
	}

	function buildDebugTable(rows) {
		var table = jQuery('#debug-table');
		var tbody = '';
		var tfoot = '';

		for (var i = 0; i < rows.length; i++) {
			var details = rows[i].name;
			if (rows[i].description.length) {
				details += '<div><small>' + rows[i].description + '</small></div>'
			}

			tbody += '<tr>\n'+
				'<td class="debug-id">' + rows[i].id + '</td>\n'+
				'<td class="debug-details">' + details + '</td>\n'+
				'<td class="debug-error">' + rows[i].error + '</td>\n'+
			'</tr>\n';
		}

		tfoot += '<tr><td colspan="3"><i class="fas fa-life-ring" style="margin-right: 5px;"></i> ' + Joomla.JText._('VAPDEBUGRATESFOOTER') + '</td></tr>';

		// build table and show
		table.find('tbody').html(tbody);
		table.find('tfoot').html(tfoot);
		jQuery('#debug-wrap').show();
	}

	function createTableRow(id, details, cost, attrs) {
		var _attrs = '';

		if (attrs) {
			for (var k in attrs) {
				_attrs += ' ' + k + '="' + attrs[k] + '"';
			}
		}

		var details_html = details;

		if (typeof details != 'string') {
			// we have an array containing the name and the description

			details_html = details[0];
			details_html += '<div><small>' + details[1] + '</small></div>';
		}

		return '<tr' + _attrs + '>\n'+
			'<td class="rate-id">' + id + '</td>\n'+
			'<td class="rate-details">' + details_html + '</td>\n'+
			'<td class="rate-price">' + Currency.getInstance().format(cost) + '</td>\n'+
		'</tr>\n';
	}

</script>
