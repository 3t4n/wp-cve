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
 * @var  object   calendar     An object holding the calendar details.
 * @var  integer  id_service   The service ID.
 * @var  integer  id_employee  The employee ID.
 */
extract($displayData);

$no_day_char = JText::translate('VAPRESNODAYCHAR');

$config = VAPFactory::getConfig();

// display legend bar
if ($config->getBool('legendcal'))
{ 
	$legend_arr = array('green', 'yellow', 'red', 'blue', 'grey');

	?>
	<div class="vap-calendar-legend-box">
		<ul class="vap-cal-legend">
			<?php
			foreach ($legend_arr as $color)
			{
				?> 
				<li>
					<span class="vap-cal-box-<?php echo $color; ?>"></span>
					&nbsp;<?php echo JText::translate('VAPCALENDARLEGEND' . strtoupper($color)); ?>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php
}
?>

<div class="vapallcaldiv">
	<?php
	foreach ($calendar->months as $month)
	{
		?>
		<div class="vapcalendardiv">

			<!-- MONTH TABLE -->

			<table class="vapcaltable">

				<thead class="vaptheadcal">

					<!-- MONTH NAME - YEAR -->

					<tr>
						<td colspan="7" style="text-align: center;">
							<?php echo $month->name->long . ' - ' . $month->year; ?>
						</td>
					</tr>

					<!-- DAYS OF THE WEEK -->

					<tr>
						<?php
						foreach ($calendar->head as $day)
						{
							?>
							<th class="vapthtabcal"><?php echo $day->name->short; ?></th>
							<?php
						}
						?>
					</tr>

				</thead>

				<!-- DAYS OF THE MONTH -->
				
				<tbody class="vaptbodycal">
					
					<?php
					foreach ($month->days as $rows)
					{
						?>
						<tr>
							<?php
							foreach ($rows as $day)
							{
								if ($day)
								{
									// use "closed" class by default
									$class = 'vaptdgrey';

									if (!$day->closed)
									{
										// fetch class based on availability
										switch ($day->available)
										{
											case 0:
												// closed
												$class = 'vaptdred';
												break;

											case 1:
												// available
												$class = 'vaptdgreen';
												break;

											case 2:
												// partially available
												$class = 'vaptdyellow';
												break;
										}
									}

									/**
									 * Fetch whether the current day owns any active rates.
									 *
									 * @since 1.6.2
									 */
									$active_rates = VAPSpecialRates::getRatesOnDay($id_service, $day->date);

									if ($active_rates)
									{
										// extract class suffix based on rates (use "calendar" caller)
										$class .= ' ' . VAPSpecialRates::extractClass($active_rates, 'calendar');
									}
									?>
									<td class="vaptdday <?php echo $class; ?>" data-day="<?php echo $day->date; ?>">
										<a href="javascript:void(0)">
											<div class="vapdivday">
												<?php echo $day->day; ?>
											</div>
										</a>
									</td>
									<?php
								}
								else
								{
									?>
									<td class="vaptdnoday">
										<div class="vapdivday"><?php echo $no_day_char; ?></div>
									</td>
									<?php
								}
							}
							?>
						</tr>
						<?php
					}
					?>

				</tbody>

			</table>

		</div>
		<?php
	}
	?>
</div>

<script>

	jQuery(function($) {
		$('.vaptdday[data-day]').on('click', function() {
			// remove selection from any other cell
			$('.vaptdday').removeClass('vaptdselected');
			// auto-select clicked cell
			$(this).addClass('vaptdselected');

			// load timeline
			vapGetTimeline($(this).data('day'));
		});
	});

</script>
