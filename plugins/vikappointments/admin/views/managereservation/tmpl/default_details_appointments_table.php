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

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$vik = VAPApplication::getInstance();

$default_tz = JFactory::getUser()->getTimezone();

?>

<style>
	.order-items-table tr.app-option-child:not(#order-app-opt-fieldset-0) td {
		border: 0;
		padding-top: 0;
	}
	.order-items-table tr.app-option-child td:first-child {
		padding-left: 30px;
	}
</style>

<table class="table order-items-table">

	<thead>

		<tr>

			<!-- SERVICE -->

			<th width="30%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION4'); ?>
			</th>

			<!-- CHECK-IN -->

			<th width="25%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION26'); ?>
			</th>

			<!-- NET -->

			<th width="10%" style="text-align: right;">
				<?php echo JText::translate('VAPMANAGEPACKAGE3'); ?>
			</th>

			<!-- TAX -->

			<th width="10%" style="text-align: right;">
				<?php echo JText::translate('VAPTAXFIELDSET'); ?>
			</th>

			<!-- GROSS -->

			<th width="12%" style="text-align: right;">
				<?php echo JText::translate('VAPMANAGEPACKORDER5'); ?>
			</th>

			<!-- EDIT -->

			<th width="1%" style="text-align: center;">&nbsp;</th>

		</tr>
		
	</thead>

	<tbody>

		<?php
		foreach ($this->reservation->items as $i => $app)
		{
			$checkin  = new JDate($app->checkin_ts);

			if ($app->timezone && $app->timezone != $default_tz->getName())
			{
				// adjust check-in to employee timezone
				$checkin->setTimezone(new DateTimeZone($app->timezone));

				// display time adjusted to the employee timezone
				$tz_str = str_replace('_', ' ', $app->timezone) . "<br />"
					. $checkin->format(JText::translate('DATE_FORMAT_LC2'), $local = true);
			}
			else
			{
				$tz_str = '';
			}

			// adjust time to local offset (of the current logged-in user)
			$checkin->setTimezone($default_tz);
			?>
			<tr id="order-app-fieldset-<?php echo $i; ?>">

				<!-- APPOINTMENT -->

				<td data-column="service">
					<div class="td-primary">
						<?php echo $app->service_name; ?>
					</div>

					<div class="td-secondary">
						<?php echo $app->employee_name; ?>
					</div>
				</td>

				<!-- CHECK-IN -->

				<td data-column="checkin">
					<div class="td-primary nowrap">
						<?php
						echo $checkin->format(JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'), $local = true);

						if ($tz_str)
						{
							?>
							<div class="td-pull-right">
								<i class="fas fa-globe-americas hasTooltip" title="<?php echo $this->escape($tz_str); ?>"></i>
							</div>
							<?php
						}
						?>
					</div>

					<div class="td-secondary">
						<span class="td-pull-left">
							<i class="fas fa-stopwatch"></i>
							<span data-column="duration">
								<?php echo VikAppointments::formatMinutesToTime($app->duration); ?>
							</span>
						</span>

						<?php
						// do not show in case of a service with single participant
						if ($app->people > 1 || (!empty($this->service) && $this->service->max_capacity > 1))
						{
							?>
							<span class="td-pull-right">
								<span data-column="people">
									<?php echo $app->people; ?>
								</span>
								<i class="fas fa-male"></i><i class="fas fa-male" style="margin-left: 1px;"></i>
							</span>
							<?php
						}
						?>
					</div>
				</td>

				<!-- NET -->

				<td data-column="net" style="text-align: right;">
					<?php
					if ($this->multiOrder)
					{
						echo $currency->format($app->total_net);
					}
					else
					{
						echo $currency->format($app->service_net);
					}
					?>
				</td>

				<!-- TAX -->

				<td data-column="tax" style="text-align: right;">
					<?php
					if ($this->multiOrder)
					{
						echo $currency->format($app->total_tax);
					}
					else
					{
						echo $currency->format($app->service_tax);
					}
					?>
				</td>

				<!-- GROSS -->

				<td data-column="gross" style="text-align: right;">
					<?php
					if ($this->multiOrder)
					{
						echo $currency->format($app->total_cost);
					}
					else
					{
						echo $currency->format($app->service_gross);
					}
					?>
				</td>

				<!-- EDIT -->

				<td data-column="edit" style="text-align: center;">
					<?php
					if (!$this->multiOrder)
					{
						?>
						<a href="javascript:void(0)" onclick="vapOpenAppointmentCard('<?php echo $i; ?>');" class="no-underline">
							<i class="fas fa-pen-square big"></i>
						</a>
						<?php
					}
					else
					{
						// cannot edit service from the details page of a multi-order
						?>
						<a class="disabled no-underline hasTooltip" title="<?php echo $this->escape(JText::translate('VAP_MULTIORDER_EDITSERVICE_DISABLED')); ?>">
							<i class="fas fa-pen-square big"></i>
						</a>
						<?php
					}
					?>
				</td>

			</tr>
			<?php
		}

		// display options too in case we are editing a single appointment
		if (!$this->multiOrder)
		{
			foreach ($this->reservation->options as $i => $opt)
			{
				// calculate price per unit
				$opt->inc_price /= $opt->quantity;

				?>
				<tr class="app-option-child" id="order-app-opt-fieldset-<?php echo $i; ?>">

					<!-- OPTION -->

					<td data-column="option" colspan="2">
						<div>
							<small data-column="quantity"><?php echo $opt->quantity; ?>x</small>

							<span class="td-primary"><?php echo $opt->name; ?></span>
						</div>

						<?php
						if (!empty($opt->var_name))
						{
							?>
							<div class="td-secondary">
								<?php echo $opt->var_name; ?>
							</div>
							<?php
						}
						?>

						<input type="hidden" name="option_json[]" value="<?php echo $this->escape(json_encode($opt)); ?>" />
					</td>

					<!-- NET -->

					<td data-column="net" style="text-align: right;">
						<?php echo $currency->format($opt->net); ?>
					</td>

					<!-- TAX -->

					<td data-column="tax" style="text-align: right;">
						<?php echo $currency->format($opt->tax); ?>
					</td>

					<!-- GROSS -->

					<td data-column="gross" style="text-align: right;">
						<?php echo $currency->format($opt->gross); ?>
					</td>

					<!-- EDIT -->

					<td data-column="edit" style="text-align: center;">
						<a href="javascript:void(0)" onclick="vapOpenAppOptionCard('<?php echo $i; ?>');" class="no-underline">
							<i class="fas fa-pen-square big"></i>
						</a>
					</td>

				</tr>
				<?php
			}
		}
		?>

	</tbody>

	<tfoot>

		<?php
		if ($this->reservation->discount > 0 || $this->reservation->coupon_str)
		{
			?>
			<tr class="order-discount">
				<td colspan="6" style="text-align: right;">
					<span>
						<?php
						if ($this->reservation->coupon_str)
						{
							// extract coupon data
							$coupon = explode(';;', $this->reservation->coupon_str);

							if ($coupon[2] == 0)
							{
								// no discount, we probably have a tracking coupon
								$coupon = $coupon[0];
							}
							else if ($coupon[1] == 1)
							{
								// percentage amount
								$coupon = $coupon[0] . ' : ' . $coupon[2] . '%';
							}
							else
							{
								// fixed amount
								$coupon = $coupon[0] . ' : ' . $currency->format($coupon[2]);
							}
							?>
							<i class="fas fa-info-circle hasTooltip" title="<?php echo $this->escape($coupon); ?>"></i>
							<?php
						}

						echo JText::translate('VAPMANAGEPACKAGE13');
						?>
					</span>

					<b><?php echo $currency->format($this->reservation->discount * -1); ?></b>
				</td>
			</tr>
			<?php
		}
		?>

		<!-- TOTALS -->

		<tr class="order-totals">
			
			<td colspan="6" style="text-align: right;">
				<div data-column="total_net" style="<?php echo $this->reservation->total_net == 0 ? 'display:none;' : ''; ?>">
					<span><?php echo JText::translate('VAPINVTOTAL'); ?></span>

					<b><?php echo $currency->format($this->reservation->total_net); ?></b>

					<input type="hidden" name="total_net" value="<?php echo $this->reservation->total_net; ?>" />
				</div>

				<div data-column="payment_charge" style="<?php echo $this->reservation->payment_charge == 0 ? 'display:none;' : ''; ?>">
					<span><?php echo JText::translate('VAPINVPAYCHARGE'); ?></span>

					<b><?php echo $currency->format($this->reservation->payment_charge); ?></b>

					<input type="hidden" name="payment_tax" value="<?php echo $this->reservation->payment_tax; ?>" />
					<input type="hidden" name="payment_charge" value="<?php echo $this->reservation->payment_charge; ?>" />
				</div>

				<div data-column="total_tax" style="<?php echo $this->reservation->total_tax == 0 ? 'display:none;' : ''; ?>">
					<span><?php echo JText::translate('VAPINVTAXES'); ?></span>

					<b><?php echo $currency->format($this->reservation->total_tax); ?></b>

					<input type="hidden" name="total_tax" value="<?php echo $this->reservation->total_tax; ?>" />
				</div>

				<div data-column="total_cost">
					<span><?php echo JText::translate('VAPINVGRANDTOTAL'); ?></span>

					<b><?php echo $currency->format($this->reservation->total_cost); ?></b>

					<input type="hidden" name="total_cost" value="<?php echo $this->reservation->total_cost; ?>" />
				</div>
			</td>

		</tr>

		<!-- ACTIONS -->

		<tr class="order-actions">

			<td colspan="6">
				<?php
				if (!$this->multiOrder)
				{
					?>
					<div class="pull-right">
						<button type="button" class="btn btn-primary" id="add-app-option">
							<?php echo JText::translate('VAP_ADD_EXTRA'); ?>
						</button>
					</div>
					<?php
				}
				?>

				<div class="pull-left">
					<?php
					if ($this->reservation->discount > 0)
					{
						?>
						<button type="button" class="btn" id="remove-discount">
							<?php echo JText::translate('VAP_REM_DISCOUNT'); ?>
						</button>

						<div class="remove-discount-undo" style="display:none;">
							<button type="button" class="btn" id="remove-discount-undo">
								<?php echo JText::translate('VAP_REM_DISCOUNT_UNDO'); ?>
							</button>

							<?php
							// add tooltip
							echo $vik->createPopover(array(
								'title'   => JText::translate('VAP_REM_DISCOUNT'),
								'content' => JText::translate('VAP_DISC_CHANGE_INFO'),
							));
							?>
						</div>

						<input type="hidden" name="remove_discount" value="0" />
						<?php
					}
					else
					{
						?>
						<button type="button" class="btn" id="add-discount">
							<?php echo JText::translate('VAP_ADD_DISCOUNT'); ?>
						</button>

						<div style="display:none;">
							<?php
							// get supported coupon codes
							$coupons = JHtml::fetch('vaphtml.admin.coupons', $blank = false, $group = true);

							// include placeholder and option to enter a manual discount
							$default = [
								0 => [
									JHtml::fetch('select.option', '', ''),
									JHtml::fetch('select.option', 'manual', '- Manual -'),
								]
							];

							// join actions and coupon codes
							$coupons = array_merge($default, $coupons);

							// create dropdown attributes
							$params = array(
								'id'          => 'vap-coupon-sel',
								'group.items' => null,
								'list.select' => null,
							);
							
							// render select
							echo JHtml::fetch('select.groupedList', $coupons, 'add_discount', $params);

							// add tooltip
							echo $vik->createPopover(array(
								'title'   => JText::translate('VAP_ADD_DISCOUNT'),
								'content' => JText::translate('VAP_DISC_CHANGE_INFO'),
							));
							?>
						</div>
						<?php
					}
					?>
				</div>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewReservation","key":"appointments","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Appointments" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewReservation" hook.
				 *
				 * @since 1.6.6
				 */
				if (isset($this->forms['appointments']))
				{
					echo $this->forms['appointments'];

					// unset details form to avoid displaying it twice
					unset($this->forms['appointments']);
				}
				?>
			</td>

		</tr>
		
	</tfoot>

</table>

<?php
JText::script('VAPFILTERSELECTCOUPON');
JText::script('VAP_MANUAL_DISCOUNT_PROMPT');
JText::script('VAPSHORTCUTMINUTE');
?>

<script>

	jQuery(function($) {
		$('#vap-coupon-sel').select2({
			placeholder: Joomla.JText._('VAPFILTERSELECTCOUPON'),
			allowClear: true,
			width: 250,
		});

		$('#vap-coupon-sel').on('change', function() {
			if ($(this).val() !== 'manual') {
				return;
			}

			const data = {
				value:     0,
				percentot: 2,
			};

			// manual option, ask for a discount
			let input = prompt(Joomla.JText._('VAP_MANUAL_DISCOUNT_PROMPT'));

			// check whether a percentage discount was specified
			if (input.match(/\%$/)) {
				data.percentot = 1;
				input = input.replace(/\%$/, '');
			}

			// replace comma separator for decimals
			input = input.replace(/,/g, '.');

			// register discount value
			data.value = parseFloat(input);

			if (isNaN(data.value)) {
				// invalid amount, unset option
				$(this).val('');
				return;
			}

			$('#adminForm').find('input[name^="manual_discount["]').remove();
			$('#adminForm').append('<input type="hidden" name="manual_discount[value]" value="' + data.value + '" />');
			$('#adminForm').append('<input type="hidden" name="manual_discount[percentot]" value="' + data.percentot + '" />');
		});

		$('#add-discount').on('click', function() {
			$(this).hide();
			$(this).next().slideDown();
		});

		$('#remove-discount').on('click', function() {
			$(this).hide();
			$('.remove-discount-undo').show();

			$('input[name="remove_discount"]').val(1);
		});

		$('#remove-discount-undo').on('click', function() {
			$('.remove-discount-undo').hide();
			$('#remove-discount').show();

			$('input[name="remove_discount"]').val(0);
		});
	});

	function vapAddAppOptionCard(data) {
		let index = OPTIONS_COUNT++;

		SELECTED_OPTION = 'order-app-opt-fieldset-' + index;

		// create table row
		const row = jQuery('<tr id="' + SELECTED_OPTION + '" class="app-option-child"></tr>');

		// append option
		row.append(
			'<td data-column="option" colspan="2">\n' +
				'<div>\n' +
					'<small data-column="quantity"></small>\n' +
					'<span class="td-primary"></span>\n' +
				'</div>\n' +
				'<div class="td-secondary"></div>\n' +
				'<input type="hidden" name="option_json[]" />\n' +
			'</td>'
		);

		// append net
		row.append('<td data-column="net" style="text-align: right;"></td>');

		// append tax
		row.append('<td data-column="tax" style="text-align: right;"></td>');

		// append gross
		row.append('<td data-column="gross" style="text-align: right;"></td>');

		// append edit
		row.append(
			'<td data-column="edit" style="text-align: center;">\n' +
				'<a href="javascript:void(0)" onclick="vapOpenAppOptionCard(' + index + ')" class="no-underline">\n' +
					'<i class="fas fa-pen-square big"></i>\n' +
				'</a>\n' +
			'</td>'
		);

		jQuery('#order-appointments-table table tbody').append(row);

		return row;
	}

	function vapRefreshAppOptionCard(row, data) {
		// refresh quantity
		row.find('small[data-column="quantity"]').html(data.quantity + 'x');

		// update option name and variation
		const option = row.find('td[data-column="option"]');
		option.find('.td-primary').html(data.name);
		option.find('.td-secondary').html(data.var_name || '');

		currency = Currency.getInstance();

		// update net
		row.find('td[data-column="net"]').html(currency.format(data.net));

		// update tax
		row.find('td[data-column="tax"]').html(currency.format(data.tax));

		// update gross
		row.find('td[data-column="gross"]').html(currency.format(data.gross));
	}

	function vapRefreshAppointmentRow(row, data) {
		row.find('span[data-column="people"]').html(data.people);
		row.find('span[data-column="duration"]').html(data.duration + ' ' + Joomla.JText._('VAPSHORTCUTMINUTE'));

		currency = Currency.getInstance();

		// update net
		row.find('td[data-column="net"]').html(currency.format(data.net));

		// update tax
		row.find('td[data-column="tax"]').html(currency.format(data.tax));

		// update gross
		row.find('td[data-column="gross"]').html(currency.format(data.gross));
	}

	function getInputTotals() {
		const data = {};

		[
			'total_net',
			'total_tax',
			'total_cost',
			'payment_charge',
			'payment_tax',
		].forEach((name) => {
			// update input hidden
			data[name] = parseFloat(jQuery('input[name="' + name + '"]').val());
		});

		return data;
	}

	function updateInputTotals(data) {
		const totals   = jQuery('.order-totals');
		const currency = Currency.getInstance();

		for (let k in data) {
			if (data.hasOwnProperty(k)) {
				let num = parseFloat(data[k]);
				jQuery('input[name="' + k + '"]').val(num.toFixed(2));

				// get price columns
				let column = totals.find('[data-column="' + k + '"]');
				
				column.find('b').html(currency.format(data[k]));

				if (num == 0 && k != 'total_cost') {
					column.hide();
				} else {
					column.show();
				}
			}
		}
	}

</script>
