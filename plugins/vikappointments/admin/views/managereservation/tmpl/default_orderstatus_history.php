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

$reservation = $this->reservation;

$config  = VAPFactory::getConfig();
$handler = VAPOrderStatus::getInstance();

$dt_format = JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat');
// use also the seconds
$dt_format = str_replace(':i', ':i:s', $dt_format);

// Get all reservation status codes.
// NOTE: Do not merge the order status of the parent order, because the
// table might display duplicate records.
$tracks = $handler->getOrderTrack($reservation->id, $locale = true);

?>

<table class="order-status-table">

	<thead>
		<tr>
			<th style="text-align: left;"><?php echo JText::translate('VAPORDERSTATUS'); ?></th>
			<th style="text-align: center;"><?php echo JText::translate('VAPMANAGERESERVATION33'); ?></th>
			<th style="text-align: center;"><?php echo JText::translate('VAPREFERER'); ?></th>
			<th style="text-align: center;"><?php echo JText::translate('VAPREMOTEADDR'); ?></th>
			<th style="text-align: center;"><?php echo JText::translate('VAPMANAGERESERVATION37'); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php

		if (count($tracks))
		{
			foreach ($tracks as $track)
			{
				?>
				<tr>
					<td style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.status.display', $track->statusCode); ?>
					</td>

					<td style="text-align: center;">
						<?php echo $track->createdby ? $track->name . ' (' . $track->username . ')' : strtolower(JText::translate('VAPRESLISTGUEST')); ?>
					</td>
					
					<td style="text-align: center;">
						<?php echo JText::translate($track->client ? 'JADMINISTRATOR' : 'JSITE'); ?>
					</td>

					<td style="text-align: center;">
						<?php echo $track->ip; ?>
					</td>

					<td style="text-align: center;">
						<span class="hasTooltip" title="<?php echo JHtml::fetch('date', $track->createdon, $dt_format); ?>">
							<?php echo JHtml::fetch('date.relative', $track->createdon, null, null, $dt_format); ?>
						</span>
					</td>
				</tr>
				<?php

				if ($track->comment)
				{
					?>
					<tr class="track-comment">
						<td colspan="5" style="text-align: left;">
							<span style="width: calc(100% - 24px); display: inline-block; margin-right: 6px;">
								<?php echo $track->comment; ?>
							</span>

							<?php
							if ($track->id_order != $this->reservation->id)
							{
								?>
								<i class="fas fa-link hasTooltip" title="<?php echo $this->escape(JText::translate('VAPPARENTORDER')); ?>"></i>
								<?php
							}
							?>
						</td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr class="track-warning">
				<td colspan="5">
					<?php echo JText::translate('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</td>
			</tr>
			<?php
		}
		?>

	</tbody>

</table>
