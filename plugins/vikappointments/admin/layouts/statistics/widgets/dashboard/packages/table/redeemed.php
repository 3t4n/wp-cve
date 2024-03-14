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
 * @var  VAPStatisticsWidget  $widget    The instance of the widget to be displayed.
 * @var  array                $packages  The table rows.
 */
extract($displayData);

if (empty($packages))
{
	// nothing to display
	echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	return;
}

$config = VAPFactory::getConfig();

?>

<div class="widget-revenue-table narrow">
	<table data-widget-id="<?php echo $widget->getID(); ?>">

		<thead>
			<tr>

				<!-- ID -->

				<th style="text-align: left;" width="1%" class="nowrap">
					<?php echo JText::translate('VAPMANAGECUSTOMER1'); ?>
				</th>

				<!-- DATE -->
				
				<th style="text-align: left;" width="25%">
					<?php echo JText::translate('VAPMANAGEPACKORDER17'); ?>
				</th>

				<!-- CUSTOMER -->
				
				<th style="text-align: left;" width="25%">
					<?php echo JText::translate('VAPMANAGEPACKORDER6'); ?>
				</th>

				<!-- PACKAGE -->
				
				<th style="text-align: left;" width="25%" class="hidden-phone">
					<?php echo JText::translate('VAPMANAGEPACKORDER18'); ?>
				</th>

				<!-- USAGES -->
				
				<th style="text-align: center;" width="10%">
					<?php echo JText::translate('VAPMANAGEPACKORDER16'); ?>
				</th>

			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($packages as $order)
			{
				?>
				<tr>

					<!-- ID -->

					<td style="text-align: left;">
						<?php echo $order['id']; ?>
					</td>

					<!-- DATE -->

					<td style="text-align: left;">
						<a href="javascript:void(0);" data-package-id="<?php echo $order['id']; ?>">
							<?php echo JHtml::fetch('date.relative', $order['modifiedon'], null, null, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat')); ?>
						</a>
					</td>

					<!-- CUSTOMER -->

					<td style="text-align: left;">
						<?php
						if ($order['id_user'] > 0)
						{
							?>
							<a href="javascript:void(0);" data-customer-id="<?php echo $order['id_user']; ?>">
								<?php echo $order['purchaser_nominative']; ?>
							</a>
							<?php
						}
						else
						{
							echo $order['purchaser_nominative'] ? $order['purchaser_nominative'] : $order['purchaser_mail'];
						}
						?>
					</td>

					<!-- PACKAGE -->

					<td style="text-align: left;" class="hidden-phone">
						<?php echo $order['package_name']; ?>
					</td>

					<!-- USAGES -->

					<td style="text-align: center;">
						<?php echo $order['used_app'] . '/' . $order['num_app']; ?>
					</td>

				</tr>
				<?php
			}
			?>
		</tbody>

	</table>
</div>

