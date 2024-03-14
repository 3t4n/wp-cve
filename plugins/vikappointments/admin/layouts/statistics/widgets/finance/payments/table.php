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

if (empty($data))
{
	// do nothing in case of empty data
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

				<!-- PAYMENT -->

				<th style="text-align: left;" class="<?php echo $ordering['column'] == 'payname' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPMANAGERESERVATION13', 'payname', $ordering['direction'], $ordering['column'], 'asc'); ?>
				</th>

				<!-- COUNT -->
				
				<th style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPORDERS', 'count', $ordering['direction'], $ordering['column'], 'desc'); ?>
				</th>

				<!-- TOTAL GROSS -->
				
				<th style="text-align: right;" class="<?php echo $ordering['column'] == 'total' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPTOTALGROSS', 'total', $ordering['direction'], $ordering['column'], 'desc'); ?>
				</th>

				<!-- TOTAL TAX -->
				
				<th style="text-align: right;" class="<?php echo $ordering['column'] == 'tax' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPTOTALTAX', 'tax', $ordering['direction'], $ordering['column'], 'desc'); ?>
				</th>

				<!-- TOTAL NET -->
				
				<th style="text-align: right;" class="<?php echo $ordering['column'] == 'net' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPTOTALNET', 'net', $ordering['direction'], $ordering['column'], 'desc'); ?>
				</th>

				<!-- TOTAL DISCOUNT -->
				
				<th style="text-align: right;" class="<?php echo $ordering['column'] == 'discount' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPMANAGEPACKAGE13', 'discount', $ordering['direction'], $ordering['column'], 'desc'); ?>
				</th>

				<!-- PAYMENT CHARGE -->
				
				<th style="text-align: right;" class="<?php echo $ordering['column'] == 'payment' ? 'sorted' : ''; ?>">
					<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPINVPAYCHARGE', 'payment', $ordering['direction'], $ordering['column'], 'desc'); ?>
				</th>

			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($data as $totals)
			{
				?>
				<tr>

					<!-- PAYMENT -->

					<td style="text-align: left;" class="<?php echo $ordering['column'] == 'payname' ? 'sorted' : ''; ?>">
						<?php echo $totals['name']; ?>
					</td>

					<!-- COUNT -->
				
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
						<?php echo isset($totals['count']) ? $totals['count'] : 0; ?>
					</td>

					<!-- TOTAL GROSS -->
					
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'total' ? 'sorted' : ''; ?>">
						<?php echo $currency->format(isset($totals['total']) ? $totals['total'] : 0); ?>
					</td>

					<!-- TOTAL TAX -->
					
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'tax' ? 'sorted' : ''; ?>">
						<?php echo $currency->format(isset($totals['tax']) ? $totals['tax'] : 0); ?>
					</td>

					<!-- TOTAL NET -->
					
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'net' ? 'sorted' : ''; ?>">
						<?php echo $currency->format(isset($totals['net']) ? $totals['net'] : 0); ?>
					</td>

					<!-- TOTAL DISCOUNT -->
					
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'discount' ? 'sorted' : ''; ?>">
						<?php echo $currency->format(isset($totals['discount']) ? $totals['discount'] : 0); ?>
					</td>

					<!-- PAYMENT CHARGE -->
					
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'payment' ? 'sorted' : ''; ?>">
						<?php echo $currency->format(isset($totals['payment']) ? $totals['payment'] : 0); ?>
					</td>

				</tr>
				<?php
			}
			?>
		</tbody>

		<tfoot>
			<tr>
				
				<!-- PAYMENT -->

				<td style="text-align: left;" class="<?php echo $ordering['column'] == 'payname' ? 'sorted' : ''; ?>">&nbsp;</td>

				<!-- COUNT -->
				
				<td style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
					<?php echo $footer['count']; ?>
				</td>

				<!-- TOTAL GROSS -->
				
				<td style="text-align: right;" class="<?php echo $ordering['column'] == 'total' ? 'sorted' : ''; ?>">
					<?php echo $currency->format($footer['total']); ?>
				</td>

				<!-- TOTAL TAX -->
				
				<td style="text-align: right;" class="<?php echo $ordering['column'] == 'tax' ? 'sorted' : ''; ?>">
					<?php echo $currency->format($footer['tax']); ?>
				</td>

				<!-- TOTAL NET -->
				
				<td style="text-align: right;" class="<?php echo $ordering['column'] == 'net' ? 'sorted' : ''; ?>">
					<?php echo $currency->format($footer['net']); ?>
				</td>

				<!-- TOTAL DISCOUNT -->
				
				<td style="text-align: right;" class="<?php echo $ordering['column'] == 'discount' ? 'sorted' : ''; ?>">
					<?php echo $currency->format($footer['discount']); ?>
				</td>

				<!-- PAYMENT CHARGE -->
				
				<td style="text-align: right;" class="<?php echo $ordering['column'] == 'payment' ? 'sorted' : ''; ?>">
					<?php echo $currency->format($footer['payment']); ?>
				</td>

			</tr>
		</tfoot>

	</table>
</div>
