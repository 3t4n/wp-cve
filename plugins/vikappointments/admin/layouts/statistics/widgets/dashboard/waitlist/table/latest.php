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
 * @var  string               $layout  The type of layout to render (latest or incoming).
 */
extract($displayData);

if (empty($users))
{
	// nothing to display
	echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	return;
}

$config = VAPFactory::getConfig();

$today    = JDate::getInstance()->format('Y-m-d');
$tomorrow = JDate::getInstance('+1 day')->format('Y-m-d');

?>

<div class="widget-revenue-table narrow">
	<table data-widget-id="<?php echo $widget->getID(); ?>">

		<thead>
			<tr>

				<!-- DATE -->
				
				<th style="text-align: left;" width="30%">
					<?php echo JText::translate($layout == 'latest' ? 'VAPMANAGESUBSCRORD3' : 'VAPMANAGEWAITLIST3'); ?>
				</th>

				<!-- SERVICE -->
				
				<th style="text-align: left;" width="30%">
					<?php echo JText::translate('VAPMANAGERESERVATION4'); ?>
				</th>

				<!-- CUSTOMER -->
				
				<th style="text-align: left;" width="30%">
					<?php echo JText::translate('VAPMANAGERESERVATION38'); ?>
				</th>

			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($users as $user)
			{
				?>
				<tr>

					<!-- DATE -->

					<td style="text-align: left;">
						<?php
						if ($layout == 'latest')
						{
							echo JHtml::fetch('date.relative', $user['created_on'], null, null, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'));
						}
						else
						{
							if ($today == $user['timestamp'])
							{
								echo JText::translate('VAPTODAY');
							}
							else if ($tomorrow == $user['timestamp'])
							{
								echo JText::translate('VAPTOMORROW');
							}
							else
							{
								// display in UTC because there's no time to adjust
								echo JHtml::fetch('date', $user['timestamp'], JText::translate('DATE_FORMAT_LC1'), 'UTC'); 
							}
						}
						?>
					</td>

					<!-- SERVICE -->

					<td style="text-align: left;">
						<div>
							<?php echo $user['service_name']; ?>
						</div>

						<?php
						if ($user['employee_name'])
						{
							?>
							<div>
								<small><?php echo $user['employee_name']; ?></small>
							</div>
							<?php
						}
						?>
					</td>

					<!-- CUSTOMER -->

					<td style="text-align: left;">
						<?php echo $user['user_name'] ? $user['user_name'] : $user['email']; ?>

						<div>
							<a href="mailto:<?php echo $this->escape($user['email']); ?>" title="<?php echo $this->escape($user['email']); ?>" class="hasTooltip">
								<i class="fas fa-envelope"></i>
							</a>

							<?php
							if ($user['phone_number'])
							{
								?>
								<a href="tel:<?php echo $this->escape($user['phone_number']); ?>" title="<?php echo $this->escape($user['phone_number']); ?>" class="hasTooltip" style="margin-left:4px;">
									<i class="fas fa-phone"></i>
								</a>
								<?php
							}
							?>
						</div>
					</td>

				</tr>
				<?php
			}
			?>
		</tbody>

	</table>
</div>

