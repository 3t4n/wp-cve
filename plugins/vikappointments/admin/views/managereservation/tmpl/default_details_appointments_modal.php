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

$app = $this->reservation;

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$vik = VAPApplication::getInstance();

$default_tz = JFactory::getUser()->getTimezone();

$checkin  = new JDate($app->checkin_ts);
$checkout = new JDate(VikAppointments::getCheckout($app->checkin_ts, $app->duration));

if ($app->timezone && $app->timezone != $default_tz->getName())
{
	$tz = new DateTimeZone($app->timezone);

	// adjust check-in to employee timezone
	$checkin->setTimezone($tz);
	$checkout->setTimezone($tz);

	// display time adjusted to the employee timezone
	$checkin_tz_str = str_replace('_', ' ', $app->timezone) . "<br />"
		. $checkin->format(JText::translate('DATE_FORMAT_LC2'), $local = true);

	$checkout_tz_str = str_replace('_', ' ', $app->timezone) . "<br />"
		. $checkout->format(JText::translate('DATE_FORMAT_LC2'), $local = true);
}
else
{
	// display current timezone
	$checkin_tz_str = $checkout_tz_str = str_replace('_', ' ', $default_tz->getName());
}

// adjust time to local offset (of the current logged-in user)
$checkin->setTimezone($default_tz);
$checkout->setTimezone($default_tz);

?>

<div class="inspector-form" id="inspector-order-pack-form">

	<div class="inspector-fieldset">

		<h3><?php echo JText::translate('VAPCUSTFIELDSLEGEND1'); ?></h3>

		<!-- EMPLOYEE - Text -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION3')); ?>
			<select id="app_employee">
				<option value="<?php echo $app->id_employee; ?>"><?php echo $app->employee_name; ?></option>
			</select>

			<input type="hidden" name="id_employee" value="<?php echo $app->id_employee; ?>" />
		<?php echo $vik->closeControl(); ?>

		<!-- SERVICE - Text -->
		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION4')); ?>
			<input type="text" value="<?php echo $this->escape($app->service_name); ?>" readonly />
			<input type="hidden" name="id_service" value="<?php echo $app->id_service; ?>" />
		<?php echo $vik->closeControl(); ?>
		
		<!-- CHECK-IN - Text -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION26')); ?>
			<input type="text" value="<?php echo $this->escape($checkin->format(JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'), true)); ?>" class="hasTooltip" title="<?php echo $this->escape($checkin_tz_str); ?>" readonly />

			<input type="hidden" name="checkin_ts" value="<?php echo $checkin->toSql(); ?>" />
		<?php echo $vik->closeControl(); ?>
		
		<!-- CHECK-OUT - Text -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION42')); ?>
			<input type="text" value="<?php echo $this->escape($checkout->format(JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'), true)); ?>" class="hasTooltip" title="<?php echo $this->escape($checkout_tz_str); ?>" readonly />
		<?php echo $vik->closeControl(); ?>
		
		<!-- CHANGE DATA - Text -->

		<?php echo $vik->openControl(''); ?>
			<button type="button" class="btn" id="change-resdata-btn">
				<?php echo JText::translate('VAPMANAGERESERVATION7');?>
			</button>
		<?php echo $vik->closeControl(); ?>

		<!-- PEOPLE - Number -->

		<?php
		if ($this->service->max_capacity > 1)
		{
			echo $vik->openControl(JText::translate('VAPMANAGERESERVATION25')); ?>
				<input type="number" id="app_people" value="<?php echo $app->people; ?>" min="1" max="<?php echo $this->service->max_capacity; ?>" step="1" />
				<input type="hidden" name="people" value="<?php echo $app->people; ?>" />
			<?php echo $vik->closeControl(); 
		}
		?>

		<!-- SERVICE PRICE - Number -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION9')); ?>
			<div class="input-prepend input-append currency-field">
				<span class="btn">
					<?php echo $currency->getSymbol(); ?>
				</span>

				<input type="number" id="app_service_price" value="<?php echo $app->service_price; ?>" min="0" step="any" />
				
				<input type="hidden" name="service_price" value="<?php echo $app->service_price; ?>" />
				<input type="hidden" name="service_net" value="<?php echo $app->service_net; ?>" />
				<input type="hidden" name="service_tax" value="<?php echo $app->service_tax; ?>" />
				<input type="hidden" name="service_gross" value="<?php echo $app->service_gross; ?>" />
				<input type="hidden" name="service_discount" value="<?php echo $app->service_discount; ?>" />
				<input type="hidden" name="tax_breakdown" value="<?php echo $this->escape($app->tax_breakdown); ?>" />

				<button type="button" class="btn" onclick="openTestRatesModal();">
					<i class="fas fa-info-circle"></i>
				</button>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- DURATION - Number -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION10').':'); ?>
			<div class="input-append">
				<input type="number" id="app_duration" value="<?php echo $app->duration; ?>" min="1" step="any" />
				
				<input type="hidden" name="duration" value="<?php echo $app->duration; ?>" />
				<input type="hidden" name="sleep" value="<?php echo $app->sleep; ?>" />

				<span class="btn">
					<?php echo JText::translate('VAPSHORTCUTMINUTE'); ?>
				</span>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewReservation","key":"appointment","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the "Appointment" inspector.
		 *
		 * NOTE: retrieved from "onDisplayViewReservation" hook.
		 *
		 * @since 1.6.6
		 */
		if (isset($this->forms['appointment']))
		{
			echo $this->forms['appointment'];

			// unset details form to avoid displaying it twice
			unset($this->forms['appointment']);
		}
		?>

		<?php
		if ($this->recalculate)
		{
			// add input to inform the model that a revalidation of the appointment
			// should be applied before saving it
			?>
			<input type="hidden" name="validate_availability" value="1" /> 
			<?php
		}
		?>

	</div>

</div>

<?php
JText::script('VAP_AJAX_GENERIC_ERROR');
?>

<script>

	(function($) {

		const loadAvailableEmployees = () => {
			return new Promise((resolve, reject) => {
				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=reservation.employeespreviewajax'); ?>',
					{
						id_employee: <?php echo (int) $app->id_employee; ?>,
						id_service: <?php echo (int) $app->id_service; ?>,
						checkin_ts: '<?php echo $checkin->toSql(); ?>',
						people: $('#app_people').val(),
					},
					(resp) => {
						resolve(resp);
					},
					(err) => {
						reject(err);
					}
				);
			});
		};

		$(function() {
			$('#app_employee').select2({
				allowClear: false,
				width: '100%',
			});

			$('#change-resdata-btn').on('click', () => {
				// reach task to edit the reservation data
				Joomla.submitbutton('findreservation.edit');
			});

			$('#apply-rate-btn').on('click', () => {
				// update service price with rate found
				jQuery('#app_service_price').val(window.vapRatesTestTrace.finalcostperuser);
				// auto-dismiss modal
				jQuery('#jmodal-ratestest').modal('hide');
			});

			loadAvailableEmployees().then((employees) => {
				const select = $('#app_employee');
				select.html('');

				employees.forEach((employee) => {
					select.append(
						$('<option></option>')
							.val(employee.id)
							.text(employee.name)
							.prop('disabled', !employee.status)
					);
				});

				select.select2('val', $('input[name="id_employee"]').val());
			}).catch((err) => {
				// do nothing on error
			});
		});
	})(jQuery);

	function initAppointmentData() {
		// restore input with saved data
		jQuery('#app_employee').select2('val', jQuery('input[name="id_employee"]').val());
		jQuery('#app_people').val(jQuery('input[name="people"]').val());
		jQuery('#app_service_price').val(jQuery('input[name="service_price"]').val());
		jQuery('#app_duration').val(jQuery('input[name="duration"]').val());
	}

	function commitAppointmentChanges() {
		let data = {};

		// fetch selected employee
		data.id_employee = parseInt(jQuery('#app_employee').val());

		// fetch selected number of people (cannot be lower than 1)
		data.people = parseInt(jQuery('#app_people').val());
		data.people = isNaN(data.people) ? 1 : Math.max(1, data.people);

		// fetch selected price (cannot be lower than 0)
		data.price = parseFloat(jQuery('#app_service_price').val());
		data.price = isNaN(data.price) ? 0 : Math.max(0, data.price);

		// fetch selected duration (cannot be lower than 1)
		data.duration = parseInt(jQuery('#app_duration').val());
		data.duration = isNaN(data.duration) ? 1 : Math.max(1, data.duration);

		// fetch discount (cannot be lower than 0)
		data.discount = parseFloat(jQuery('input[name="service_discount"]').val());
		data.discount = isNaN(data.discount) ? 0 : Math.max(0, data.discount);

		// copy typed data into hidden fields
		jQuery('input[name="id_employee"]').val(data.id_employee);
		jQuery('input[name="people"]').val(data.people);
		jQuery('input[name="service_price"]').val(data.price);
		jQuery('input[name="duration"]').val(data.duration);

		const currency = Currency.getInstance();

		// calculate base cost by multiplying the price by the selected
		// number of people and subtracting the discount, if any
		<?php if ($this->service->priceperpeople) { ?>
			data.price = currency.multiply(data.price, data.people);
		<?php } ?>

		price = Math.max(0, currency.diff(data.price, data.discount));

		// create promise to load the costs of the package (tax, net, gross...)
		return new Promise((resolve, reject) => {
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=tax.testajax'); ?>',
				{
					id_tax:  <?php echo (int) $app->id; ?>,
					amount:  price,
					id_user: jQuery('#vap-users-select').val(),
					langtag: '<?php echo $app->langtag; ?>',
					subject: 'service',
				},
				(bill) => {
					// update service base prices
					jQuery('input[name="service_net"]').val(bill.net);
					jQuery('input[name="service_tax"]').val(bill.tax);
					jQuery('input[name="service_gross"]').val(bill.gross);
					jQuery('input[name="tax_breakdown"]').val(JSON.stringify(bill.breakdown));

					// assign fetched prices to item object
					Object.assign(data, bill);

					// rename breakdown property
					data.tax_breakdown = data.breakdown;
					delete data.breakdown;

					resolve(data);
				},
				(err) => {
					reject(err.responseText || Joomla.JText._('VAP_AJAX_GENERIC_ERROR'));
				}
			);
		});
	}

	function openTestRatesModal() {
		// delete any previously registered trace
		delete window.vapRatesTestTrace;

		// open rates modal
		vapOpenJModal('ratestest', null, true);

		// wait until trace object is filled in by the iframe
		onInstanceReady(() => {
			if (typeof window.vapRatesTestTrace === 'undefined') {
				return false;
			}

			return window.vapRatesTestTrace;
		}).then((data) => {
			// disable "apply" button in case the current price is equals to the 
			// final cost fetched by the test rates modal
			let disabled = parseFloat(jQuery('#app_service_price').val()) === parseFloat(data.finalcostperuser);
			jQuery('#apply-rate-btn').prop('disabled', disabled);
		});
	}

</script>
