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

?>

<div class="vapallcaldiv">
	<?php
	foreach ($this->calendar->months as $month)
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
						foreach ($this->calendar->head as $day)
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
										<div class="vapdivday">/</div>
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
