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

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   integer  $id_emp    The employee ID.
 * @var   integer  $id_ser    The service ID.
 * @var   array    $table     The associative array containing all the available times.
 * @var   integer  $max_rows  The maximum number of available times per day.
 * @var   mixed    $prev_day  The day to use after clicking "prev" arrow. Null if the button is disabled.
 * @var   string   $next_day  The day to use after clicking "next" arrow.
 * @var   integer  $itemid    The current Item ID.
 */

/**
 * The maximum number of times that should
 * be (initially) visible for each day.
 *
 * @var integer
 */
$times_per_rows = 4;

/**
 * Since the table of an employee may be empty,
 * we need to display at least the minimum number
 * of times for each column.
 *
 * @see $times_per_rows
 */
$max_rows = max(array($max_rows, $times_per_rows));

// build base URI
$base = "index.php?option=com_vikappointments&view=employeesearch&id_employee={$id_emp}&id_service={$id_ser}";

if ($itemid)
{
	$base .= "&Itemid={$itemid}";
}

$config = VAPFactory::getConfig();

$tz = VikAppointments::getUserTimezone();

?>

<div class="emp-avail-table">

	<!-- TABLE HEADING (days and arrows) -->

	<div class="avail-table-head">

		<!-- LEFT ARROW TO SEE PREVIOUS 4 DAYS -->

		<div class="table-head-left-arrow">
			<?php
			if ($prev_day)
			{
				?>
				<a href="javascript: void(0);" onclick="loadOtherTableTimes(<?php echo $id_emp; ?>, '<?php echo $prev_day; ?>');"><i class="fas fa-chevron-left"></i></a>
				<?php
			}
			else
			{
				?>
				<i class="fas fa-chevron-left"></i>
				<?php
			}
			?>
		</div>

		<!-- CURRENT DAYS -->

		<div class="table-head-center">
			<?php
			foreach (array_keys($table) as $day)
			{
				$date = JFactory::getDate($day);

				/**
				 * The dates are always displayed using the UTC timezone, since there are no times set.
				 *
				 * @since 1.7
				 */
				?>
				<div class="table-head-day">
					<div class="day-name"><?php echo JHtml::fetch('date', $date, 'D', 'UTC'); ?></div>
					<div class="day-desc"><?php echo JHtml::fetch('date', $date, 'j M', 'UTC'); ?></div>
				</div>
				<?php
			}
			?>
		</div>

		<!-- RIGHT ARROW TO SEE NEXT 4 DAYS -->

		<div class="table-right-arrow">
			<a href="javascript: void(0);" onclick="loadOtherTableTimes(<?php echo $id_emp; ?>, '<?php echo $next_day; ?>');"><i class="fas fa-chevron-right"></i></a>
		</div>

	</div>

	<!-- TABLE BODY (times) -->

	<div class="avail-table-body" id="avail-tbody<?php echo $id_emp; ?>">

		<div class="table-body-arrow-col">&nbsp;</div>

		<div class="avail-table-body-cols">
			<?php
			foreach ($table as $date => $timeline)
			{
				$count = 0;
				?>
				<div class="avail-table-day-col">
					<?php
					// make sure we have a timeline to parse
					if ($timeline)
					{
						// iterate all timeline levels
						foreach ($timeline as $times)
						{
							foreach ($times as $slot)
							{
								if (!$slot->isAvailable())
								{
									// skip time in case it is not available
									continue;
								}

								// fetch time slot visibility
								$hidden = $count < $times_per_rows ? '' : ' hidden';
								$count++;

								// get hour and minutes
								$hour = (int) $slot->checkin('G');
								$min  = (int) $slot->checkin('i');

								// build details URL
								$url = JRoute::rewrite($base . "&date={$date}&hour={$hour}&min={$min}"); 
								?>
								<div class="table-body-free-slot timetable-slot<?php echo $hidden; ?>">
									<a href="<?php echo $url; ?>">
										<?php echo $slot->checkin($config->get('timeformat'), $tz); ?>
									</a>
								</div>
								<?php
							}
						}
					}

					// display empty remaining slot
					for ($count; $count < $max_rows; $count++)
					{
						// fetch time slot visibility
						$hidden = $count < $times_per_rows ? '' : ' hidden';
						?>
						<div class="table-body-empty-slot timetable-slot<?php echo $hidden; ?>">--</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>

		<div class="table-body-arrow-col">&nbsp;</div>

	</div>

	<?php
	if ($max_rows > $times_per_rows)
	{
		?>
		<!-- TABLE FOOTER (show more link) -->

		<div class="avail-table-footer">
			<a href="javascript: void(0);" onclick="showMoreTimesFromTable(<?php echo $id_emp; ?>, this);">
				<?php echo JText::translate('VAPSHOWMORETIMES'); ?>
			</a>
		</div>
		<?php
	}
	?>

</div>
