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
 * @var  VAPStatisticsWidget  $widget  The instance of the widget to be displayed.
 * @var  mixed                $data    The table rows data.
 * @var  mixed                $footer  The table footer.
 */
extract($displayData);

if (!isset($data))
{
	// do nothing in case of empty data
	return;
}

if (empty($data))
{
	// no redeemed coupons for the selected range of dates
	echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	return;
}

$currency = VAPFactory::getCurrency();

// get ordering
$ordering = $widget->getOrdering();

?>

<div class="widget-revenue-table">
	<table data-widget-id="<?php echo $widget->getID(); ?>">

		<thead>
			<tr>

				<!-- COUPON -->

				<th style="text-align: left;" class="<?php echo $ordering['column'] == 'coupon' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPMANAGERESERVATION21', 'coupon', $ordering['direction'], $ordering['column'], 'asc'); ?>
				</th>

				<!-- COUNT -->
				
				<th style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPORDERS', 'count', $ordering['direction'], $ordering['column'], 'desc'); ?>
				</th>

				<!-- TOTAL DISCOUNT -->
				
				<th style="text-align: right;" class="<?php echo $ordering['column'] == 'discount' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPMANAGEPACKAGE13', 'discount', $ordering['direction'], $ordering['column'], 'desc'); ?>
				</th>

				<!-- VALUE -->
				
				<th style="text-align: right;">
					<?php echo JText::translate('VAPMANAGECOUPON5'); ?>
				</th>

			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($data as $totals)
			{
				?>
				<tr>

					<!-- COUPON -->

					<td style="text-align: left;" class="<?php echo $ordering['column'] == 'coupon' ? 'sorted' : ''; ?>">
						<?php echo $totals['code']; ?>
					</td>

					<!-- COUNT -->
				
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
						<?php echo isset($totals['count']) ? $totals['count'] : 0; ?>
					</td>

					<!-- TOTAL DISCOUNT -->
					
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'discount' ? 'sorted' : ''; ?>">
						<?php echo $currency->format(isset($totals['discount']) ? $totals['discount'] : 0); ?>
					</td>

					<!-- VALUE -->
					
					<td style="text-align: right;">
						<?php
						if ($totals['type'] == 1)
						{
							echo $totals['value'] . '%';
						}
						else
						{
							// auto hide decimals when missing
							echo $currency->format($totals['value'], array('no_decimal' => true));
						}
						?>
					</td>

				</tr>
				<?php
			}
			?>
		</tbody>

		<tfoot>
			<tr>
				
				<!-- COUPON -->

				<td style="text-align: left;" class="<?php echo $ordering['column'] == 'coupon' ? 'sorted' : ''; ?>">&nbsp;</td>

				<!-- COUNT -->
				
				<td style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
					<?php echo $footer['count']; ?>
				</td>

				<!-- TOTAL DISCOUNT -->
				
				<td style="text-align: right;" class="<?php echo $ordering['column'] == 'discount' ? 'sorted' : ''; ?>">
					<?php echo $currency->format($footer['discount']); ?>
				</td>

				<!-- VALUE -->
				
				<td style="text-align: right;">&nbsp;</td>

			</tr>
		</tfoot>

	</table>
</div>
