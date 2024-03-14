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
<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"packages.start","type":"field"} -->

<?php
// plugins can use the "packages.start" key to introduce custom
// HTML before the packages list
if (isset($this->addons['packages.start']))
{
	echo $this->addons['packages.start'];

	// unset details form to avoid displaying it twice
	unset($this->addons['packages.start']);
}
?>

<table class="order-status-table">

	<thead>
		<tr>

			<!-- Order Number -->

			<th width="20%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION1'); ?>
			</th>

			<!-- Used App. -->
			
			<th width="10%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGEPACKORDER16'); ?>
			</th>
			
			<!-- Order Total -->
			
			<th width="10%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION9'); ?>
			</th>

			<!-- Status -->
			
			<th width="10%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION12'); ?>
			</th>
			
			<!-- Toggle -->
			
			<th width="2%" style="text-align: right;">
				<a href="javascript:void(0);" id="packages-toggle-all">
					<i class="fas fa-toggle-off medium-big"></i>
				</a>
			</th>

		</tr>
	</thead>

	<tbody>
		
		<?php
		foreach ($this->packages as $order)
		{
			?>
			<tr>
				
				<!-- Order Number, Creation Date -->

				<td>
					<div class="td-primary">
						<?php echo $order->id . '-' . $order->sid; ?>
					</div>

					<div class="td-secondary">
						<?php echo JHtml::fetch('date', $order->createdon, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat')); ?>
					</div>
				</td>

				<!-- Used Appointments -->

				<td>
					<div class="td-primary">
						<?php echo $order->usedApp . '/' . $order->totalApp; ?>
					</div>
				</td>

				<!-- Total -->

				<td>
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
					<?php echo JHtml::fetch('vaphtml.status.display', $order->status); ?>
				</td>

				<!-- Toggle -->

				<td style="text-align: right;">
					<a href="javascript:void(0);" class="packages-res-toggle" data-id="<?php echo $order->id; ?>">
						<i class="fas fa-chevron-right medium-big"></i>
					</a>
				</td>

			</tr>

			<tr class="track-comment" id="pack-order-details-<?php echo $order->id; ?>" style="display:none;">
				
				<!-- Packages -->

				<td colspan="5">

					<?php
					if ($order->packages)
					{
						?>
						<ul class="items-list">
							<?php
							foreach ($order->packages as $pack)
							{
								?>
								<li class="item-row">

									<div class="item-row-details">

										<div class="item-name"><?php echo $pack->name; ?></div>

										<div class="item-redeemed"><?php echo JText::sprintf('VAPREDEEMEDPACKAGES', $pack->usedApp, $pack->totalApp); ?></div>
										
										<div class="item-quantity">x<?php echo $pack->quantity; ?></div>

										<div class="item-price"><?php echo $currency->format($pack->totals->gross); ?></div>
									
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
		?>

	</tbody>

	<?php
	if ($this->packCount > 1)
	{
		?>
		<tfoot>
			<tr>
				<td>
					<b><?php echo JText::plural('VAP_N_PACKAGES', $this->packCount); ?></b>
				</td>

				<td style="text-align: right;">
					<?php echo JText::translate('VAPMANAGERESERVATION9'); ?>
				</td>

				<td>
					<b style="font-size: larger;"><?php echo $currency->format($this->packTotal); ?></b>
				</td>

				<td>&nbsp;</td>

				<td>&nbsp;</td>
			</tr>
		</tfoot>
		<?php
	}
	?>

</table>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"packages.nav","type":"field"} -->

<?php
// plugins can use the "packages.nav" key to introduce custom
// HTML between the packages list and the pagination
if (isset($this->addons['packages.nav']))
{
	echo $this->addons['packages.nav'];

	// unset details form to avoid displaying it twice
	unset($this->addons['packages.nav']);
}
?>

<?php
if ($this->packNav)
{
	echo '<br />' . $this->packNav;
}
?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"packages.end","type":"field"} -->

<?php
// plugins can use the "packages.end" key to introduce custom
// HTML after the packages list
if (isset($this->addons['packages.end']))
{
	echo $this->addons['packages.end'];

	// unset details form to avoid displaying it twice
	unset($this->addons['packages.end']);
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
				$('.packages-res-toggle').each(function() {
					toggleDetails(this, toggle);
				});
			}
		}

		const toggleDetails = (link, status) => {
			var id = $(link).data('id');

			if (($(link).find('i').hasClass('fa-chevron-right') && status !== 0) || status == 1) {
				// open
				$(link).find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');

				$('#pack-order-details-' + id).show();
			} else {
				// close
				$(link).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');

				$('#pack-order-details-' + id).hide();
			}

			if (status == undefined) {
				var open = $('.packages-res-toggle i.fa-chevron-down').length;

				if (open > 0) {
					// at least a record open
					toggleAll($('#packages-toggle-all')[0], 1);
				} else {
					// all records closed
					toggleAll($('#packages-toggle-all')[0], 0);
				}
			}
		}

		$('#packages-toggle-all').on('click', function() {
			toggleAll(this);
		});

		$('.packages-res-toggle').on('click', function() {
			toggleDetails(this);
		});
	});

</script>
