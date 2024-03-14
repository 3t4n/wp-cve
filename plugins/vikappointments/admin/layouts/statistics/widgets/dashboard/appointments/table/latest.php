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

/**
 * Layout variables
 * -----------------
 * @var  VAPStatisticsWidget  $widget        The instance of the widget to be displayed.
 * @var  array                $appointments  The table rows.
 */
extract($displayData);

if (empty($appointments))
{
	// nothing to display
	echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	return;
}

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$use_cost = false;

// check whether the appointments include a cost
for ($i = 0; $i < count($appointments) && !$use_cost; $i++)
{
	$use_cost = $appointments[$i]['total_cost'] > 0;
}

?>

<div class="widget-revenue-table narrow">
	<table data-widget-id="<?php echo $widget->getID(); ?>">

		<thead>
			<tr>

				<!-- ID -->

				<th style="text-align: left;" width="10%" class="nowrap">
					<?php echo JText::translate('VAPMANAGERESERVATION1'); ?>
				</th>

				<!-- CHECK-IN -->
				
				<th style="text-align: left;" width="20%">
					<?php echo JText::translate('VAPMANAGERESERVATION26'); ?>
				</th>

				<!-- SERVICE -->
				
				<th style="text-align: left;" width="25%">
					<?php echo JText::translate('VAPMANAGERESERVATION4'); ?>
				</th>

				<!-- CUSTOMER -->
				
				<th style="text-align: left;" width="25%" class="hidden-phone">
					<?php echo JText::translate('VAPMANAGERESERVATION38'); ?>
				</th>

				<!-- TOTAL -->
				
				<?php
				if ($use_cost)
				{
					?>
					<th style="text-align: left;" width="10%" class="hidden-phone">
						<?php echo JText::translate('VAPMANAGERESERVATION9'); ?>
					</th>
					<?php
				}
				?>

				<!-- STATUS -->
				
				<th style="text-align: left;" width="20%">
					<?php echo JText::translate('VAPMANAGERESERVATION12'); ?>
				</th>

			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($appointments as $order)
			{
				?>
				<tr data-order-id="<?php echo $order['id']; ?>">

					<!-- ID -->

					<td style="text-align: left;" class="nowrap">
						<div>
							<?php echo $order['id']; ?>

							<span class="actions-group">
								<a href="index.php?option=com_vikappointments&amp;view=printorders&amp;tmpl=component&amp;cid[]=<?php echo $order['id']; ?>" target="_blank">
									<i class="fas fa-print"></i>
								</a>
							</span>
						</div>

						<div class="td-secondary hidden-phone">
							<?php echo JHtml::fetch('date.relative', $order['createdon'], null, null, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat')); ?>
						</div>
					</td>

					<!-- CHECK-IN -->

					<td style="text-align: left;">
						<div>
							<a href="javascript:void(0)" data-order-id="<?php echo $order['id']; ?>">
								<?php echo JHtml::fetch('date', $order['checkin_ts'], JText::translate('DATE_FORMAT_LC3')); ?>
							</a>
						</div>

						<div class="td-secondary">
							<span class="checkin-time">
								<i class="fas fa-sign-in-alt"></i>
								<?php echo JHtml::fetch('date', $order['checkin_ts'], $config->get('timeformat')); ?>
							</span>

							<span class="checkout-time">
								<i class="fas fa-sign-in-alt"></i>
								<?php echo JHtml::fetch('date', VikAppointments::getCheckout($order['checkin_ts'], $order['duration']), $config->get('timeformat')); ?>
							</span>
						</div>
					</td>

					<!-- SERVICE -->

					<td style="text-align: left;">
						<div>
							<?php echo $order['service_name']; ?>
						</div>

						<div class="td-secondary">
							<?php
							echo $order['employee_name'];

							if ($order['people'] > 1)
							{
								?>
								<span class="td-pull-right">
									<?php echo $order['people']; ?>
									<i class="fas fa-male"></i><i class="fas fa-male" style="margin-left: 1px;"></i>
								</span>
								<?php
							}
							?>
						</div>
					</td>

					<!-- CUSTOMER -->

					<td style="text-align: left;" class="hidden-phone">
						<div>
							<?php
							if ($order['id_user'] > 0)
							{
								?>
								<a href="javascript:void(0)" data-customer-id="<?php echo $order['id_user']; ?>">
									<?php echo $order['purchaser_nominative']; ?>
								</a>
								<?php
							}
							else
							{
								echo $order['purchaser_nominative'] ? $order['purchaser_nominative'] : $order['purchaser_mail'];
							}
							?>
						</div>

						<?php
						if ($order['purchaser_phone'])
						{
							?>
							<div class="td-secondary">
								<?php echo $order['purchaser_phone']; ?>
							</div>
							<?php
						}
						?>
					</td>

					<!-- TOTAL -->

					<?php
					if ($use_cost)
					{
						?>
						<td style="text-align: left;" class="hidden-phone">
							<?php echo $currency->format($order['total_cost']); ?>
						</td>
						<?php
					}
					?>

					<!-- STATUS -->

					<td style="text-align: left;">
						<span class="status-hndl" style="cursor:pointer;" data-id="<?php echo $order['id']; ?>" data-status="<?php echo $order['status']; ?>">
							<?php echo JHtml::fetch('vaphtml.status.display', $order['status']); ?>
						</span>
					</td>

				</tr>
				<?php
			}
			?>
		</tbody>

	</table>
</div>

