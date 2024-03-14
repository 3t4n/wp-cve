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

$vik 	= VAPApplication::getInstance();
$config = VAPFactory::getConfig();

$date_format = $config->get('dateformat');

$opening = VikAppointments::getOpeningTime();
$closing = VikAppointments::getClosingTime();

$day = $this->calendar->start;

$cal = $this->calendar->calendar;

$cell_height_pixel = 60;

/**
 * Use date string instead of UNIX timestamp.
 *
 * @since 1.6.3
 */
$date = new JDate($day);

// display only relevant hours
$opening['hour'] = $cal->getMinimumHour($opening['hour']);

if ($opening['hour'] > 0)
{
	// decrease by one in order to have a wider range
	$opening['hour']--;
}

$closing['hour'] = $cal->getMaximumHour($closing['hour']);

if ($closing['hour'] < 23)
{
	// increase by one in order to have a wider range
	$closing['hour']++;
}

$tz = JFactory::getUser()->getTimezone();

?>

<?php echo $vik->openFieldset($date->format('F Y'), 'form-horizontal'); ?>

	<table class="vap-workday-calendar" cellspacing="0">
		<thead>
			<tr>
				<th width="7%">&nbsp;</th>
				<?php
				for ($i = 0; $i < 7; $i++)
				{
					?>
					<th class="head-date" width="12%">
						<div data-cell-date="<?php echo $date->format($date_format); ?>">
							<?php echo $date->format('D d'); ?>
						</div>
					</th>
					<?php

					$date->modify('+1 day');
				}
				?>
			</tr>
		</thead>

		<tbody>
			<?php
			$end = ($closing['min'] == 0 ? $closing['hour'] - 1 : $closing['hour']);
			for ($h = $opening['hour']; $h <= $end; $h++)
			{
				?>
				<tr>
					<td style="text-align: right;">
						<?php echo JHtml::fetch('vikappointments.min2time', $h * 60, true); ?>
					</td>
					<?php
					/**
					 * Use date string instead of UNIX timestamp.
					 *
					 * @since 1.6.3
					 */
					$date = new JDate($day);
					
					for ($i = 0; $i < 7; $i++)
					{
						$td_class = array();

						$bounds = array();

						/**
						 * Create date by using the current user timezone, because
						 * the calendar wrapper treats the dates in UTC.
						 *
						 * @since 1.7
						 */
						$_date = new JDate($date->format('Y-m-d H:i:s'), $tz);

						$_date->modify($h . ':00:00');
						$bounds[] = $_date->format('Y-m-d H:i:s');

						$_date->modify(($h + 1) . ':00:00');
						$bounds[] = $_date->format('Y-m-d H:i:s');

						$rects = $cal->getIntersections($bounds[0], $bounds[1]);

						$td_data = array(
							'cell-from' => $h * 60,
							'cell-to'   => ($h + 1) * 60,
							'cell-day'	=> $date->format('N', true) % 7,
							'cell-date' => $date->format($date_format, true),
						);

						// calculate divs to display
						$divs = array();

						if ($rects)
						{
							// Iterate the rects as there may be multiple cells within the same block.
							// For example, there may be the following appointments:
							// - from 14:00 to 15:30
							// - from 15:30 to 17:00
							// So, the closing div of the first appointment and the opening div of
							// the second appointment should share the same cell.
							foreach ($rects as $app)
							{
								// do not fill if not needed
								$class  = '';
								$height = '';
								$bg 	= '';
								$label 	= '&nbsp;';
								$margin = 0;

								$use_label = false;

								if ($app->startsAt($h))
								{
									$class  = 'time-starts';
									$shift  = $app->startHM() - $h * 60;
									$margin = $shift * ($cell_height_pixel / 60); // height pixel / max minutes (ratio)

									// in case the checkout is at midnight, we need to
									// return 24 * 60 (1440) in order to calculate the
									// height properly
									$end_hm = $app->endH() > 0 ? $app->endHM() : 1440;

									$class .= ' time-ends';
									$top 	= $end_hm - $h * 60;

									if ($app->isSameDay())
									{
										$diff = abs($top - $shift);
									}
									else
									{
										// appointment between 2 days
										$diff = 24 * 60 - $app->startHM();
										$class .= ' trim-end';
									}

									$height = $diff * ($cell_height_pixel / 60); // height pixel / max minutes (ratio)
									$height += ceil($height / $cell_height_pixel) - 1; // includes 1 px for each border that the div covers
									$height = "height: {$height}px;";

									$use_label = true;
								}
								else if (!$app->isSameDay() && $h == 0)
								{
									// display the remaining box of an appointment
									// that started on the previous day.
									$class  = 'time-starts trim-start time-ends';
									$top 	= $app->endHM() - $h * 60;

									$height = $top * ($cell_height_pixel / 60); // height pixel / max minutes (ratio)
									$height += ceil($height / $cell_height_pixel) - 1; // includes 1 px for each border that the div covers
									$height = "height: {$height}px;";
								}
								else if ($app->endsAt($h))
								{
									$class  = 'time-ends';
									$shift  = $app->endHM() - $h * 60;
									$margin = ($cell_height_pixel - $shift * ($cell_height_pixel / 60)) * -1; // height pixel / max minutes (ratio)
								}
								else if ($app->containsAt($h))
								{
									$class  = 'time-contains';
									$margin = 0;
								}

								// get box data through calendar rect handler
								$data = $app->getDisplayData($use_label);

								$label = $data['label'];
								unset($data['label']);

								$bg = $data['background'];
								unset($data['background']);

								// merge app data with cell data
								$data = array_merge($data, $td_data);

								// push div within the list
								$divs[] = array(
									'class' 		=> $class,
									'height'		=> $height,
									'margin' 		=> $margin,
									'label'			=> $label,
									'background' 	=> $bg,
									'data'			=> $data,
								);

								$td_class[] = $class;
							}
						}
						else
						{
							$divs[] = array(
								'class' 		=> 'time-empty',
								'height'		=> '',
								'margin' 		=> 0,
								'label'			=> '&nbsp;',
								'background' 	=> '',
								'data'			=> $td_data,
							);

							$td_class[] = 'time-empty';
						}

						$td_class = array_unique($td_class);

						if (count($td_class) > 1)
						{
							// time-starts + time-ends
							$td_class = 'time-contains';
						}
						else
						{
							// use only the specified class
							$td_class = $td_class[0];
						}

						?>
						<td class="<?php echo $td_class; ?>" style="position: relative;">
							<?php
							foreach ($divs as $div)
							{
								$data_str = '';
								foreach ($div['data'] as $k => $v)
								{
									if (is_array($v))
									{
										$v = implode(',', $v);
									}

									$data_str .= " data-{$k}=\"{$v}\"";
								}

								?>
								<div 
									<?php echo $data_str; ?>
									class="<?php echo $div['class']; ?>"
									style="position: absolute; width:100%;
									top: <?php echo $div['margin']; ?>px;
									<?php echo $div['background']; ?>
									<?php echo $div['height']; ?>"
								>
									<div class="time-box-label">
										<?php echo $div['label']; ?>
									</div>
								</div>
								<?php
							}
							?>
						</td>
						<?php
						$date->modify('+1 day');
					}
					?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

<?php echo $vik->closeFieldset(); ?>

<script>
	
	jQuery(function($) {
		$('td.time-empty, th.head-date').on('click', function() {
			document.adminForm.date.value = $(this).find('div').data('cell-date');
			document.adminForm.mode.value = 'day';

			document.adminForm.submit();
		});
	});

</script>
