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

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"appointments.start","type":"field"} -->

<?php
// plugins can use the "appointments.start" key to introduce custom
// HTML before the appointments list
if (isset($this->addons['appointments.start']))
{
	echo $this->addons['appointments.start'];

	// unset details form to avoid displaying it twice
	unset($this->addons['appointments.start']);
}
?>

<table class="order-status-table">

	<thead>
		<tr>

			<!-- Order Number -->

			<th width="20%" style="text-align: left;" class="hidden-phone">
				<?php echo JText::translate('VAPMANAGERESERVATION1'); ?>
			</th>
			
			<!-- Check-in -->
			
			<th width="20%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION26'); ?>
			</th>
			
			<!-- Service -->
			
			<th width="15%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION4'); ?>
			</th>
			
			<!-- Order Total -->
			
			<th width="10%" style="text-align: left;" class="hidden-phone">
				<?php echo JText::translate('VAPMANAGERESERVATION9'); ?>
			</th>

			<!-- Status -->
			
			<th width="10%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION12'); ?>
			</th>
			
			<!-- Toggle -->
			
			<th width="2%" style="text-align: right;" class="hidden-phone">
				<a href="javascript:void(0);" id="appointments-toggle-all">
					<i class="fas fa-toggle-off medium-big"></i>
				</a>
			</th>

		</tr>
	</thead>

	<tbody>
		
		<?php
		foreach ($this->appointments as $order)
		{
			foreach ($order->appointments as $app)
			{
				?>
				<tr>
					
					<!-- Order Number, Creation Date -->

					<td class="hidden-phone">
						<div class="td-primary">
							<?php echo $app->id . '-' . $order->sid; ?>
						</div>

						<div class="td-secondary">
							<?php echo JHtml::fetch('date', $order->createdon, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat')); ?>
						</div>
					</td>

					<!-- Check-in, Check-out -->

					<td>
						<div class="td-primary">
							<?php echo JHtml::fetch('date', $app->checkin->utc, JText::translate('DATE_FORMAT_LC1')); ?>
						</div>

						<div class="td-secondary">
							<span class="checkin-time">
								<i class="fas fa-sign-in-alt"></i>
								<?php echo JHtml::fetch('date', $app->checkin->utc, $config->get('timeformat')); ?>
							</span>

							<span class="checkin-time" style="margin-left: 4px;">
								<i class="fas fa-sign-out-alt"></i>
								<?php echo JHtml::fetch('date', $app->checkout->utc, $config->get('timeformat')); ?>
							</span>
						</div>
					</td>

					<!-- Service, Employee -->

					<td>
						<div class="td-primary">
							<?php echo $app->service->name; ?>
						</div>

						<div class="td-secondary">
							<?php echo $app->employee->name; ?>
						</div>
					</td>

					<!-- Total -->

					<td class="hidden-phone">
						<div class="td-primary">
							<?php echo $currency->format($order->totals->gross); ?>
						</div>

						<div class="td-secondary">
							<?php
							if ($order->totals->due)
							{
								// display remaining balance
								echo JText::translate('VAPORDERDUE') . ': ' . $currency->format($order->totals->due);
							}
							?>
						</div>
					</td>

					<!-- Status -->

					<td>
						<?php echo JHtml::fetch('vaphtml.status.display', $app->status); ?>
					</td>

					<!-- Toggle -->

					<td class="hidden-phone" style="text-align: right;">
						<a href="javascript:void(0);" class="appointments-res-toggle" data-id="<?php echo $app->id; ?>">
							<i class="fas fa-chevron-right medium-big"></i>
						</a>
					</td>

				</tr>

				<tr class="track-comment hidden-phone" id="order-details-<?php echo $app->id; ?>" style="display:none;">
					
					<!-- Options -->

					<td colspan="6">

						<?php
						if ($app->options)
						{
							?>
							<ul class="items-list">
								<?php
								foreach ($app->options as $opt)
								{
									?>
									<li class="item-row">

										<div class="item-row-details">

											<div class="item-name"><?php echo $opt->fullName; ?></div>
											
											<div class="item-quantity">x<?php echo $opt->quantity; ?></div>

											<div class="item-price"><?php echo $currency->format($opt->totals->gross); ?></div>
										
										</div>

									</li>
									<?php
								}
								?>
							</ul>
							<?php
						}
						else
						{
							echo $vik->alert(JText::translate('VAPRESNOEXTRAOPTIONS'));
						}
						?>

					</td>

				</tr>
				<?php
			}
		}
		?>

	</tbody>

	<?php
	if ($this->appCount > 1)
	{
		?>
		<tfoot>
			<tr>
				<td>
					<b><?php echo JText::plural('VAP_N_RESERVATIONS', $this->appCount); ?></b>
				</td>

				<td class="hidden-phone">&nbsp;</td>

				<td style="text-align: right;" class="hidden-phone">
					<?php echo JText::translate('VAPMANAGERESERVATION9'); ?>
				</td>

				<td>
					<b style="font-size: larger;"><?php echo $currency->format($this->appTotal); ?></b>
				</td>

				<td colspan="2" class="hidden-phone">&nbsp;</td>
			</tr>
		</tfoot>
		<?php
	}
	?>

</table>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"appointments.nav","type":"field"} -->

<?php
// plugins can use the "appointments.nav" key to introduce custom
// HTML between the appointments list and the pagination
if (isset($this->addons['appointments.nav']))
{
	echo $this->addons['appointments.nav'];

	// unset details form to avoid displaying it twice
	unset($this->addons['appointments.nav']);
}
?>

<?php
if ($this->appNav)
{
	echo '<br />' . $this->appNav;
}
?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"appointments.end","type":"field"} -->

<?php
// plugins can use the "appointments.end" key to introduce custom
// HTML after the appointments list
if (isset($this->addons['appointments.end']))
{
	echo $this->addons['appointments.end'];

	// unset details form to avoid displaying it twice
	unset($this->addons['appointments.end']);
}
?>

<script>

	jQuery(function($) {
		const toggleAll = (link, status) => {
			var toggle = 0;

			if ($(link).find('i').hasClass('fa-toggle-off') || status == 1) {
				// open
				$(link).find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');

				toggle = 1;
			} else {
				// close
				$(link).find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
			}

			if (status == undefined) {
				$('.appointments-res-toggle').each(function() {
					toggleDetails(this, toggle);
				});
			}
		}

		const toggleDetails = (link, status) => {
			var id = $(link).data('id');

			if (($(link).find('i').hasClass('fa-chevron-right') && status !== 0) || status == 1) {
				// open
				$(link).find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');

				$('#order-details-' + id).show();
			} else {
				// close
				$(link).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');

				$('#order-details-' + id).hide();
			}

			if (status == undefined) {
				var open = $('.appointments-res-toggle i.fa-chevron-down').length;

				if (open > 0) {
					// at least a record open
					toggleAll($('#appointments-toggle-all')[0], 1);
				} else {
					// all records closed
					toggleAll($('#appointments-toggle-all')[0], 0);
				}
			}
		}

		$('#appointments-toggle-all').on('click', function() {
			toggleAll(this);
		});

		$('.appointments-res-toggle').on('click', function() {
			toggleDetails(this);
		});
	});

</script>
