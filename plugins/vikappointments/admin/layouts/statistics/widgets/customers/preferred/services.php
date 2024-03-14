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
 */
extract($displayData);

if (!isset($data))
{
	// do nothing in case of empty data
	return;
}

if (!$data)
{
	echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	return ;
}

$currency = VAPFactory::getCurrency();

// get ordering
$ordering = $widget->getOrdering();

foreach ($data as $customer => $table)
{
	?>
	<h4 style="margin: 10px 0;"><?php echo $customer; ?></h4>

	<div class="widget-revenue-table">
		<table data-widget-id="<?php echo $widget->getID(); ?>">

			<thead>
				<tr>

					<!-- SERVICE -->

					<th style="text-align: left;" class="<?php echo $ordering['column'] == 'service' ? 'sorted' : ''; ?>">
						<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPMANAGERESERVATION4', 'service', $ordering['direction'], $ordering['column'], 'asc'); ?>
					</th>

					<!-- COUNT -->
					
					<th style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
						<?php echo JHtml::fetch('vaphtml.admin.customsort', 'VAPORDERS', 'count', $ordering['direction'], $ordering['column'], 'desc'); ?>
					</th>

					<?php
					if ($widget->hasFinanceAccess())
					{
						?>
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
						<?php
					}
					?>

				</tr>
			</thead>

			<tbody>
				<?php
				foreach ($table['body'] as $totals)
				{
					?>
					<tr>

						<!-- SERVICE -->

						<td style="text-align: left;" class="<?php echo $ordering['column'] == 'service' ? 'sorted' : ''; ?>">
							<?php echo $totals['service']; ?>
						</td>

						<!-- COUNT -->
					
						<td style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
							<?php echo isset($totals['count']) ? $totals['count'] : 0; ?>
						</td>

						<?php
						if ($widget->hasFinanceAccess())
						{
							?>
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
							<?php
						}
						?>

					</tr>
					<?php
				}
				?>
			</tbody>

			<tfoot>
				<tr>
					
					<!-- SERVICE -->

					<td style="text-align: left;" class="<?php echo $ordering['column'] == 'service' ? 'sorted' : ''; ?>">&nbsp;</td>

					<!-- COUNT -->
					
					<td style="text-align: right;" class="<?php echo $ordering['column'] == 'count' ? 'sorted' : ''; ?>">
						<?php echo isset($table['footer']['count']) ? $table['footer']['count'] : 0; ?>
					</td>

					<?php
					if ($widget->hasFinanceAccess())
					{
						?>
						<!-- TOTAL GROSS -->
						
						<td style="text-align: right;" class="<?php echo $ordering['column'] == 'total' ? 'sorted' : ''; ?>">
							<?php echo $currency->format(isset($table['footer']['total']) ? $table['footer']['total'] : 0); ?>
						</td>

						<!-- TOTAL TAX -->
						
						<td style="text-align: right;" class="<?php echo $ordering['column'] == 'tax' ? 'sorted' : ''; ?>">
							<?php echo $currency->format(isset($table['footer']['tax']) ? $table['footer']['tax'] : 0); ?>
						</td>

						<!-- TOTAL NET -->
						
						<td style="text-align: right;" class="<?php echo $ordering['column'] == 'net' ? 'sorted' : ''; ?>">
							<?php echo $currency->format(isset($table['footer']['net']) ? $table['footer']['net'] : 0); ?>
						</td>

						<!-- TOTAL DISCOUNT -->
						
						<td style="text-align: right;" class="<?php echo $ordering['column'] == 'discount' ? 'sorted' : ''; ?>">
							<?php echo $currency->format(isset($table['footer']['discount']) ? $table['footer']['discount'] : 0); ?>
						</td>
						<?php
					}
					?>

				</tr>
			</tfoot>

		</table>
	</div>
	<?php
}
