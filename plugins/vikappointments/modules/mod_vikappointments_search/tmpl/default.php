<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_search
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

JHtml::fetch('vaphtml.sitescripts.calendar', '#vapcalendarmod' . $module_id . ':input');

$last_values = $references['lastValues'];
$services    = $references['services'];
$employees   = $references['employees'];

$itemid = $params->get('itemid', null);

$has_employees = $has_random = false;

$advancedSelect = (int) $params->get('advselect', 1)

?>

<div class="moduletablevikapp vapmainsearchmod <?php echo $params->get('orientation', 'vertical'); ?>">

	<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=serviceslist' . ($itemid ? '&Itemid=' . $itemid : '')); ?>" method="post" name="vapmodulesearch">
		
		<div class="vapformfieldsetmod">

			<div class="vapsearchinputdivmod">
				<label class="vapsearchinputlabelmod" for="vapcalendarmod<?php echo $module_id; ?>"><?php echo JText::translate('VAPDATE'); ?></label>
				<div class="vapsearchentryinputmod">
					<input class="vapsearchdatemod" type="text" value="<?php echo htmlentities($last_values['date']); ?>" id="vapcalendarmod<?php echo $module_id; ?>" name="date" size="20" />
				</div>
			</div>

			<div class="vapsearchinputdivmod">
				<label class="vapsearchinputlabelmod" for="vapserselmod<?php echo $module_id; ?>"><?php echo JText::translate('VAPSERVICE'); ?></label>
				<div class="vapsearchentryselectmod">
					<select id="vapserselmod<?php echo $module_id; ?>" class="<?php echo $advancedSelect ? '' : 'form-select'; ?>">
						<?php
						foreach ($services as $group)
						{
							if (!empty($group['id']))
							{
								?>
								<optgroup label="<?php echo htmlspecialchars($group['name']); ?>">
								<?php
							}

							foreach ($group['list'] as $s)
							{
								/** 
								 * Hide employees list in case the pre-selected service does not
								 * allow the selection of the employees.
								 *
								 * @since 1.4.1
								 */
								if ($s['id'] == $last_values['id_ser'])
								{
									$has_employees = $s['choose_emp'];
									$has_random    = $s['random_emp'];
								}

								?>
						 		<option
						 			value="<?php echo (int) $s['id']; ?>"
						 			<?php echo $s['id'] == $last_values['id_ser'] ? 'selected="selected"' : ''; ?>
						 			data-random="<?php echo (int) $s['random_emp']; ?>"
						 			data-mindate="<?php echo htmlspecialchars($s['mindate']); ?>"
						 			data-maxdate="<?php echo htmlspecialchars($s['maxdate']); ?>"
						 			data-url="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=servicesearch&id_service=' . $s['id'] . ($itemid ? '&Itemid=' . $itemid : '')); ?>"
						 		>
						 			<?php echo $s['name']; ?>
						 		</option>
						 		<?php
						 	}
							
							if (!empty($group['id']))
							{
								?>
								</optgroup>
								<?php
							}
						}
						?>
					</select>
				</div>
			</div>

			<div class="vapsearchinputdivmod">
				<div id="vapemprowmod<?php echo $module_id; ?>" style="<?php echo (!count($employees) || !$has_employees ? 'display: none;' : ''); ?>">
					<label class="vapsearchinputlabelmod" for="vapempselmod<?php echo $module_id; ?>"><?php echo JText::translate('VAPEMPLOYEE'); ?></label>
					<div class="vapsearchentryselectmod">
						<select name="id_employee" id="vapempselmod<?php echo $module_id; ?>" class="<?php echo $advancedSelect ? '' : 'form-select'; ?>">
							<?php
							if ($has_random)
							{
								if ($advancedSelect)
								{
									?><option></option><?php
								}
								else
								{
									?><option value="0"><?php echo JText::translate('VAPANYEMP'); ?></option><?php
								}
							}

							foreach ($employees as $e)
							{
								?>
								<option value="<?php echo (int) $e['id']; ?>" <?php echo $e['id'] == $last_values['id_emp'] ? 'selected="selected"' : ''; ?>><?php echo $e['nickname']; ?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
			</div>

			<div class="vapsearchinputdivmod mod-booknow">
				<button type="submit" class="vap-btn blue vapsearchsubmitmod"><?php echo JText::translate('VAPFINDAPPOINTMENT'); ?></button>
			</div>

		</div>
		
	</form>

</div>

<?php
JText::script('VAPANYEMP');
?>

<script>

	(function($) {
		'use strict';

		const afterServiceChange = () => {
			const select = $('#vapserselmod<?php echo $module_id; ?>');
			const option = select.find('option:selected');

			if (!option.length) {
				return false;
			}

			// update form action with rewritten URL of the selected service
			document.vapmodulesearch.action = option.data('url');

			// create the new minimum and maximum selectable dates
			const minDate = new Date(option.data('mindate'));
			const maxDate = new Date(option.data('maxdate'));

			// refresh datepicker configuration
			$('#vapcalendarmod<?php echo $module_id; ?>').datepicker('option', 'minDate', minDate);
			$('#vapcalendarmod<?php echo $module_id; ?>').datepicker('option', 'maxDate', maxDate);
		}

		const closingPeriods = <?php echo json_encode(VikAppointments::getClosingPeriods()); ?>;
		const closingDays 	 = <?php echo json_encode(VikAppointments::getClosingDays()); ?>;

		// checks whether the specified date is closed for the given service
		const isClosingPeriod = (date, id_service) => {
			return closingPeriods.some((period) => {
				// make sure the service is assigned to this closing period
				if (period.services.length && period.services.indexOf(id_service) === -1) {
					// the selected service doesn't match this closing period
					return false;
				}

				// construct delimiters
				let startDate = new Date(period.start + 'T00:00:00');
				let endDate   = new Date(period.end + 'T23:59:59');

				// check whether the date is contained within the closing period
				return startDate <= date && date < endDate;
			});
		}

		// checks whether the specified date is closed for the given service
		const isClosingDay = (date, id_service) => {
			return closingDays.some((cd) => {
				// make sure the service is assigned to this closing day
				if (cd.services.length && cd.services.indexOf(id_service) === -1) {
					// the selected service doesn't match this closing period
					return false;
				}

				// construct closing date
				let _d = new Date(cd.ts + 'T00:00:00');

				if (cd.freq == 0) {
					// single day
					if (_d.getDate() == date.getDate() && _d.getMonth() == date.getMonth() && _d.getFullYear() == date.getFullYear()) {
						return true;
					}
				} else if (cd.freq == 1) {
					// weekly
					if (_d.getDay() == date.getDay()) {
						return true;
					}
				} else if (cd.freq == 2) {
					// monthly
					if (_d.getDate() == date.getDate()) {
						return true;
					} 
				} else if (cd.freq == 3) {
					// yearly
					if (_d.getDate() == date.getDate() && _d.getMonth() == date.getMonth()) {
						return true;
					} 
				}

				return false;
			});
		}

		$(function() {
			// adjust configuration according to the selected service
			afterServiceChange();

			<?php
			if ($advancedSelect)
			{
				?>
				$('#vapserselmod<?php echo $module_id; ?>, #vapempselmod<?php echo $module_id; ?>').select2({
					placeholder: Joomla.JText._('VAPANYEMP'),
					allowClear: true,
					width: '100%',
				});
				<?php
			}
			?>

			$('#vapserselmod<?php echo $module_id; ?>').on('change', function() {
				$('#vapempselmod<?php echo $module_id; ?>').prop('disabled', true);
						
				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=modules.serviceemployees'); ?>',
					{
						id_ser: $(this).val(),
					},
					(resp) => {
						if (resp && resp.length) {
							let options = [];

							if ($(this).find('option:selected').data('random')) {
								const emptyOption = $('<option></option>');

								if (<?php echo (int) !$advancedSelect; ?>) {
									emptyOption.text(Joomla.JText._('VAPANYEMP')).val(0);
								}

								options.push(emptyOption);
							}

							resp.forEach((emp) => {
								options.push(
									$('<option></option>').val(emp.id).text(emp.nickname)
								);
							});

							$('#vapempselmod<?php echo $module_id; ?>').html(options).attr('disabled', false);
							$('#vapempselmod<?php echo $module_id; ?>').trigger('change.select2');

							$('#vapemprowmod<?php echo $module_id; ?>').show();
						} else {
							$('#vapemprowmod<?php echo $module_id; ?>').hide();
							$('#vapempselmod<?php echo $module_id; ?>').html('');
						}
					}
				);

				// refresh form action
				afterServiceChange();
			});

			// add callback to toggle the closing days
			$('#vapcalendarmod<?php echo $module_id; ?>').datepicker('option', 'beforeShowDay', (date) => {
				// get selected service ID
				let id_service = $('#vapserselmod<?php echo $module_id; ?>').val();

				if (isClosingDay(date, id_service) || isClosingPeriod(date, id_service)) {
					return [false, ''];
				}

				return [true, ''];
			});
		});
	})(jQuery);

</script>
