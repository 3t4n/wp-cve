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
 * @var  object    calendar     An object holding the calendar details.
 * @var  integer   id_service   The service ID.
 * @var  integer   id_employee  The employee ID.
 * @var  string    date         The selected check-in date.
 * @var  int|null  hour         The selected check-in hour.
 * @var  int|null  min          The selected check-in minutes.
 */
extract($displayData);

$vik = VAPApplication::getInstance();

$config = VAPFactory::getConfig();

// get all the days with a valid timeline
$open_days = array_filter($calendar->days, function($day)
{
	return $day->timeline;
});

?>

<div id="weeklycal-wrapper">

	<style>
		/* hide default timeline when using this calendar layout */
		#vaptimeline {
			display: none !important;
			visibility: hidden !important;
		}
	</style>

	<div class="emp-avail-table weekly-calendar">

		<!-- TABLE HEADING (days and arrows) -->

		<div class="avail-table-head">

			<!-- LEFT ARROW TO SEE PREVIOUS DAYS -->

			<div class="table-head-left-arrow">
				<?php
				if ($calendar->prev)
				{
					?>
					<a href="javascript:void(0)" onclick="vapGetTimeline('<?php echo $calendar->prev; ?>');">
						<i class="fas fa-chevron-left"></i>
					</a>
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
				foreach ($calendar->days as $day)
				{
					?>
					<div class="table-head-day" data-date="<?php echo $day->date; ?>">
						<div class="day-name"><?php echo JHtml::fetch('date', $day->date, 'D', 'UTC'); ?></div>
						<div class="day-desc"><?php echo JHtml::fetch('date', $day->date, 'j M', 'UTC'); ?></div>
					</div>
					<?php
				}
				?>
			</div>

			<!-- RIGHT ARROW TO SEE NEXT DAYS -->

			<div class="table-right-arrow">
				<?php
				if ($calendar->next)
				{
					?>
					<a href="javascript:void(0)" onclick="vapGetTimeline('<?php echo $calendar->next; ?>');">
						<i class="fas fa-chevron-right"></i>
					</a>
					<?php
				}
				else
				{
					?>
					<i class="fas fa-chevron-right"></i>
					<?php
				}
				?>
			</div>

		</div>

		<!-- TABLE BODY (times) -->

		<div class="avail-table-body">

			<div class="avail-table-body-cols">
				<?php
				if ($open_days)
				{
					foreach ($calendar->days as $day)
					{
						?>
						<div class="avail-table-day-col vaptimeline" data-date="<?php echo $day->date; ?>">
							<?php
							// make sure we have a timeline to parse
							if ($day->timeline)
							{
								echo $day->timeline->display();
							}
							?>
						</div>
						<?php
					}
				}
				else
				{
					// get first available error
					$errors = array_filter($calendar->days, function($day)
					{
						return !empty($day->timelineError);
					});

					// no available days, display error message
					?>
					<div class="closed-warn">
						<?php
						if ($errors)
						{
							// display a descriptive error message
							echo array_shift($errors)->timelineError;
						}
						else
						{
							// generic "Closed" message
							echo strtoupper(JText::translate('VAPEDITEMPLOYEE17'));
						}
						?>
					</div>
					<?php
				}
				?>
			</div>

		</div>

	</div>

	<script>

		(function($) {

			$(function() {
				// always display the button to subscribe into the waiting list
				$('#vapwaitlistbox').show();

				// after picking a time slot, adjust the selected date, since it may be different
				// than the one stored within the hidden input field
				$('a[onclick*="vapTimeClicked"]').on('timeline.afterpicktime', function(event) {
					var date = $(this).closest('[data-date]').data('date');

					$('#vapdayselected').val(date);
					$('#vapconfdayselected').val(date);
				});

				// rewrite the default function used to reload the timeline
				window.vapGetTimeline = (date) => {
					const input = $('#vapdayselected');

					if (typeof date === 'undefined') {
						// get first date within the table
						date = $('.weekly-calendar [data-date]').first().data('date');

						if (!input.val()) {
							// no selected date, register it to bypass this check for later calls
							input.val(date);
							
							return false;
						}
					}

					// always update currently selected date
					input.val(date);

					/**
					 * @see views/employeesearch/tmpl/default.php
					 */
					LAST_TIMESTAMP_USED = null;
					
					/**
					 * @see layouts/blocks/checkout.php
					 */
					isTimeChoosen = false;

					// prepare timeline data
					let data = {
						day:       date,
						id_emp:    <?php echo (int) $id_employee; ?>,
						id_ser:    <?php echo (int) $id_service; ?>,
						people:    jQuery('#vapserpeopleselect').val(),
						locations: [],
					};

					// Make a request to refresh the price according to the new parameters.
					UIAjax.do(
						'<?php echo VAPApplication::getInstance()->ajaxUrl('index.php?option=com_vikappointments&task=employeesearch.refreshprice'); ?>',
						data,
						(result) => {
							// refresh the service price
							vapUpdateServiceRate(result.rate);
						}
					);

					// unset hours and minutes as the checkin day has changed
					jQuery('#vapconfhourselected').val('');
					jQuery('#vapconfminselected').val('');

					// update input hidden holding the selected number of participants
					jQuery('#vappeopleselected').val(data.people);

					// flag used to check whether all the locations are selected
					let all = true;

					jQuery('.vap-empsearch-locval').each(function() {
						if (jQuery(this).is(':checked')) {
							data.locations.push(jQuery(this).val());
						} else {
							// at least one not selected
							all = false;
						}
					});

					if (all) {
						// all locations are selected, so we can ignore this filter
						delete data.locations;
					}

					UIAjax.do(
						'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=calendarweek.availtableajax'); ?>',
						data,
						(resp) => {
							// self-replace the HTML of this layout
							$('#weeklycal-wrapper').replaceWith(resp);

							<?php
							/**
							 * If hours and minutes are set, try to pre-select
							 * the specified block.
							 *
							 * @since 1.6
							 */
							if (isset($date) && isset($hour) && isset($min)): ?>
								$('.vaptimeline[data-date="<?php echo $date; ?>"]')
									.find('.vap-timeline-block[data-hour="<?php echo (int) $hour; ?>"][data-min="<?php echo (int) $min; ?>"]')
									.closest('a')
									.trigger('click');
							<?php endif; ?>
						},
						(err) => {
							// do nothing on error
						}
					);
				}
			});

		})(jQuery);

	</script>
</div>