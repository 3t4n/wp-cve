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
 * @var  array                $users   The table rows.
 */
extract($displayData);

if (empty($users))
{
	// nothing to display
	echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	return;
}

?>

<div class="widget-revenue-table narrow">
	<table data-widget-id="<?php echo $widget->getID(); ?>">

		<thead>
			<tr>

				<!-- ID -->

				<th style="text-align: left;">
					<?php echo JText::translate('VAPMANAGECUSTOMER1'); ?>
				</th>

				<!-- NAME -->
				
				<th style="text-align: left;">
					<?php echo JText::translate('VAPMANAGECUSTOMER2'); ?>
				</th>

				<!-- COUNTRY -->
				
				<th style="text-align: center;">
					<?php echo JText::translate('VAPMANAGECUSTOMER5'); ?>
				</th>

			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($users as $user)
			{
				?>
				<tr>

					<!-- ID -->

					<td style="text-align: left;">
						<?php echo $user['id']; ?>
					</td>

					<!-- NAME -->

					<td style="text-align: left;">
						<div>
							<a href="javascript:void(0);" data-customer-id="<?php echo $user['id']; ?>">
								<?php echo $user['billing_name']; ?>
							</a>
						</div>

						<div>
							<small><?php echo $user['billing_mail']; ?></small>
						</div>
					</td>

					<!-- COUNTRY -->

					<td style="text-align: center;">
						<?php
						if ($user['country_code'])
						{
							echo JHtml::fetch('vaphtml.site.flag', $user['country_code']);
						}
						?>
					</td>

				</tr>
				<?php
			}
			?>
		</tbody>

	</table>
</div>

