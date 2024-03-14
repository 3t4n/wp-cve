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

<?php echo $vik->openFieldset($date->format(JText::translate('DATE_FORMAT_LC')), 'form-horizontal'); ?>

<div style="overflow-x: scroll;">
	<table class="vap-workday-calendar" cellspacing="0" style="width: 99%;">
		<thead>
			<tr>
				<th width="7%">&nbsp;</th>
				<?php
				foreach ($this->calendar->employees as $e)
				{
					?>
					<th width="12%">
						<?php echo $e->nickname; ?>
					</th>
					<?php
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
					foreach ($this->calendar->employees as $e)
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

						$rects = $e->calendar->getIntersections($bounds[0], $bounds[1]);

						$td_data = array(
							'cell-from' => $h * 60,
							'cell-to'   => ($h + 1) * 60,
							'cell-day'	=> $date->format('N', true) % 7,
							'cell-date' => $date->format($date_format, true),
							'cell-emp'	=> $e->id,
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
					}
					?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>

<?php echo $vik->closeFieldset(); ?>

<?php
JText::script('VAPCONNECTIONLOSTERROR');
?>

<script>

	jQuery(function($) {
		$('td.time-empty').on('click', function() {
			// finc children with data
			var div = $(this).find('.time-empty');

			// extract data from cell
			var id_employee = parseInt(div.data('cell-emp'));
			var time        = parseInt(div.data('cell-from'));

			// open inspector
			vapOpenInspector('newapp-inspector');

			// obtain services assigned to the employee
			getEmployeeServices(id_employee).then((services) => {
				// fill services dropdown
				var id_service = fillServicesDropdown(services, id_employee);

				// load timeline
				getEmployeeServiceTimeline(id_employee, id_service).then((timeline) => {
					fillTimelineDropdown(timeline.timeline, time);
				}).catch((error) => {
					// something went wrong...
					setTimelineError(error.responseText ? error.responseText : Joomla.JText._('VAPCONNECTIONLOSTERROR'));
				});
			}).catch((error) => {
				// something went wrong, dismiss modal on failure
				<?php echo $vik->bootDismissModalJS(); ?>
			});
		});
	});

	let EMPLOYEES_SERVICES_LOOKUP = {};

	const getEmployeeServices = (id_employee) => {
		// create promise
		return new Promise((resolve, reject) => {
			// check if we already searched for the specified employee
			if (EMPLOYEES_SERVICES_LOOKUP.hasOwnProperty(id_employee)) {
				// resolve promise with cached services
				resolve(EMPLOYEES_SERVICES_LOOKUP[id_employee]);
			}

			// then make request
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=employee.servicesajax'); ?>',
				{
					id_emp: id_employee,
				},
				(resp) => {
					// cache result for later usage
					EMPLOYEES_SERVICES_LOOKUP[id_employee] = resp;
					// resolve promise
					resolve(resp);
				},
				(error) => {
					// reject with specified error
					reject(error);
				}
			);
		});
	};

	const getEmployeeServiceTimeline = (id_employee, id_service) => {
		// create promise
		return new Promise((resolve, reject) => {
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=findreservation.timelineajax'); ?>',
				{
					id_emp: id_employee,
					id_ser: id_service,
					day:    '<?php echo JDate::getInstance($day)->format('Y-m-d'); ?>',
				},
				(resp) => {
					// resolve promise
					resolve(resp);
				},
				(error) => {
					// reject with specified error
					reject(error);
				}
			);
		});
	};

</script>
