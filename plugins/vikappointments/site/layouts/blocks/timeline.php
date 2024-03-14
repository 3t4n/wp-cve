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

$id_service  = isset($displayData['id_service'])    ? $displayData['id_service']    : 0;
$id_employee = isset($displayData['id_employee'])   ? $displayData['id_employee']   : 0;
$hour        = isset($displayData['hour'])          ? $displayData['hour']          : null;
$min         = isset($displayData['min'])           ? $displayData['min']           : null;
$checkout    = isset($displayData['checkout'])      ? $displayData['checkout']      : 0;
$multi_tz    = isset($displayData['multitimezone']) ? $displayData['multitimezone'] : null;
$admin       = isset($displayData['admin'])         ? $displayData['admin']         : false;
$id_res      = isset($displayData['id_res'])        ? $displayData['id_res']        : false;

$vik = VAPApplication::getInstance();

$config = VAPFactory::getConfig();

if (is_null($multi_tz))
{
	// take value from configuration
	$multi_tz = $config->getBool('multitimezone');
}

/**
 * In case of multi-timezone, allow the customers to switch timezone.
 *
 * @since 1.7
 */
if ($multi_tz)
{
	// get user timezone
	$tz = VikAppointments::getUserTimezone();

	?>
	<div class="vap-user-timezone">
		<span><?php echo JText::translate('VAP_USER_CHANGE_TIMEZONE'); ?>&nbsp;</span>

		<?php
		$zones = array();

		foreach (timezone_identifiers_list() as $zone)
		{
			$parts = explode('/', $zone);

			$continent  = isset($parts[0]) ? $parts[0] : '';
			$city 		= (isset($parts[1]) ? $parts[1] : $continent) . (isset($parts[2]) ? '/' . $parts[2] : '');
			$city 		= ucwords(str_replace('_', ' ', $city));

			if (!isset($zones[$continent]))
			{
				$zones[$continent] = array();
			}

			$zones[$continent][] = JHtml::fetch('select.option', $zone, $city);
		}

		$params = array(
			'id'          => 'vap-timezone-sel',
			'group.items' => null,
			'list.select' => $tz->getName(),
		);

		echo JHtml::fetch('select.groupedList', $zones, 'timezone', $params);
		?>
	</div>

	<script>
		(function($) {
			$(function() {
				// render timezone dropdown
				$('#vap-timezone-sel').select2({
					allowClear: false,
					width: 250,
				});

				// register selected timezone as a cookie
				$('#vap-timezone-sel').on('change', function() {
					// store timezone in a cookie for 1 month
					var date = new Date();
					date.setMonth(date.getMonth() + 1);

					document.cookie = 'vikappointments.user.timezone=' + $(this).val() + '; expires=' + date.toUTCString() + '; path=/';

					// refresh timeline
					vapGetTimeline();
				});
			});
		})(jQuery);
	</script>
	<?php
}
?>

<div class="vaptimeline" id="vaptimeline">
				
</div>

<?php
JText::script('VAPWAITLISTADDED0');
?>

<script>

	jQuery(function($) {
		// auto-load timeline in case a date was selected
		vapGetTimeline();
	});

	<?php
	/**
	 * In case of concurrent requests, the confirmation form
	 * may be overwritten when the first request made ends afters
	 * the second one.
	 * So, we should abort any connections every time we request
	 * for a new timeline.
	 *
	 * @since 1.6.2
	 */
	?>
	var TIMELINE_XHR = null;

	function vapGetTimeline(date) {
		if (TIMELINE_XHR !== null) {
			// abort previous request
			TIMELINE_XHR.abort();
		}

		if (typeof date === 'undefined') {
			// recover stored date
			date = jQuery('#vapdayselected').val();

			if (!date) {
				// no selected date
				return false;
			}

			// auto-select clicked cell
			jQuery('.vaptdday[data-day="' + date + '"]').addClass('vaptdselected');
		} else {
			// register selected date
			jQuery('#vapdayselected').val(date);
		}

		/**
		 * @see views/employeesearch/tmpl/default.php
		 */
		LAST_TIMESTAMP_USED = date;
		
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
			admin:     <?php echo (int) $admin; ?>,
			id_res:    <?php echo (int) $id_res; ?>,
			locations: [],
		};

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

		let error = false, aborted = false;

		new Promise((resolve, reject) => {
			TIMELINE_XHR = UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=employeesearch.timelineajax'); ?>',
				data,
				(resp) => {
					resolve(resp);
				},
				(err) => {
					reject(err);
				}
			);
		}).then((resp) => {
			if (!resp.error) {
				// render timeline through the helper function
				vapRenderTimeline(resp.timeline, resp.html, resp.rate, data);
			} else {
				// register availability error
				error = resp.error;
			}
		}).catch((err) => {
			if (err.statusText === 'abort') {
				aborted = false;
			} else if (typeof err.responseText !== 'undefined') {
				// register response HTTP error message
				error = err.responseText || Joomla.JText._('VAPWAITLISTADDED0');
			} else {
				error = true;
				// we are probably handling an exception
				console.error(err);	
			}
		}).finally(() => {
			TIMELINE_XHR = null;

			// do not go ahead in case we aborted the request
			if (aborted === true) {
				return;
			}

			if (error !== false) {
				if (typeof error === 'string') {
					// Fill timeline box with fetched error message.
					// Wrap error in a <div> for individual styling.
					jQuery('#vaptimeline').html(
						jQuery('<div class="timeline-error"></div>').html(error)
					);
				}

				// unset selected date on error
				jQuery('#vapdayselected').val('');

				// hide wait list button
				jQuery('#vapwaitlistbox').hide();
				// hide add cart button
				jQuery('#vapadditembutton').hide();
			}

			// animate only in case the timeline is not visible
			var px_to_scroll = isBoxOutOfMonitor(jQuery('#vaptimeline'), 60);
				
			if (px_to_scroll !== false) {
				jQuery('html,body').animate({scrollTop: "+=" + px_to_scroll}, {duration:'normal'});
			}
		});
	}

	var HOUR_MIN_SELECTED = false;

	function vapRenderTimeline(timeline, html, newRate, request) {
		let eventArgs = {
			timeline: timeline,
			html:     html,
			newRate:  newRate,
			request:  request,
		};

		// inject received parameters within the event to dispatch
		var event = jQuery.Event('timeline.beforerender');
		event.params = eventArgs;
		
		// trigger event before rendering the timeline
		jQuery(window).trigger(event);

		// fill timeline with fetched HTML
		jQuery('#vaptimeline').html(html);

		// update base cost
		if (newRate && typeof vapUpdateServiceRate !== 'undefined') {
			/**
			 * @see views/employeesearch/tmpl/default_filterbar.php
			 */
			vapUpdateServiceRate(newRate);
		}

		var at_least_one_open   = false;
		var at_least_one_closed = false;

		// iterate timeline levels
		timeline.forEach((level) => {
			// iterate level times
			level.forEach((time) => {
				at_least_one_open   = at_least_one_open   || (time.status == 1);
				at_least_one_closed = at_least_one_closed || (time.status == 0);
			});
		});

		// display "add to cart" button only if the timeline
		// reports at least an available slot
		if (at_least_one_open) {
			jQuery('#vapadditembutton').show();
		} else {
			jQuery('#vapadditembutton').hide();
		}

		// display "add to waiting list" button only if the
		// timeline reports at least an occupied slot
		if (at_least_one_closed) {
			jQuery('#vapwaitlistbox').show();
		} else {
			jQuery('#vapwaitlistbox').hide();
		}

		<?php
		/**
		 * If hours and minutes are set, try to pre-select
		 * the specified block.
		 *
		 * @since 1.6
		 */
		if (!is_null($hour) && !is_null($min))
		{
			?>
			if (!HOUR_MIN_SELECTED) {

				var hour = <?php echo (int) $hour; ?>;
				var min  = <?php echo (int) $min; ?>;

				<?php
				// check whether the check-out selection is supported
				if ($checkout)
				{
					// used for dropdown layout
					?>
					jQuery('#vap-checkin-sel option').each(function() {
						if (hour == jQuery(this).data('hour') && min == jQuery(this).data('min')) {
							// trigger click of selected option
							jQuery('#vap-checkin-sel').val(jQuery(this).val()).trigger('change');
							return false;
						}
					});
					<?php
				}
				else
				{
					// used for any other timeline layout
					?>
					jQuery('.vap-timeline-block').each(function() {
						if (hour == jQuery(this).data('hour') && min == jQuery(this).data('min')) {
							// invoke vapTimeClicked() function
							jQuery(this).closest('a').trigger('click');
							return false;
						}
					});
					<?php
				}
				?>

				// pre-select time block only once
				HOUR_MIN_SELECTED = true;
			}
			<?php
		}
		?>

		// inject received parameters within the event to dispatch
		var event = jQuery.Event('timeline.afterrender');
		event.params = eventArgs;
		
		// trigger event after rendering the timeline
		jQuery(window).trigger(event);
	}

	function vapTimeClicked(hour, min, slot)
	{
		let eventArgs = {
			hour: hour,
			min:  min,
		};

		// inject received parameters within the event to dispatch
		var event = jQuery.Event('timeline.beforepicktime');
		event.params = eventArgs;
		
		// trigger event before picking a time slot
		jQuery(slot).trigger(event);

		// get new rate as string
		var newRate = '' + jQuery(slot).find('.vaptlblock1').data('rate');

		if (newRate.length && typeof vapUpdateServiceRate !== 'undefined') {
			/**
			 * Dispatch rate update only if the data is set.
			 *
			 * @see views/employeesearch/tmpl/default_filterbar.php
			 */
			vapUpdateServiceRate(parseFloat(newRate));
		}
		
		jQuery('#vapconfempselected').val(<?php echo $id_employee; ?>);
		jQuery('#vapconfserselected').val(<?php echo $id_service; ?>);
		jQuery('#vapconfdayselected').val(jQuery('#vapdayselected').val());
		jQuery('#vapconfhourselected').val(hour);
		jQuery('#vapconfminselected').val(min);
		jQuery('#vapconfpeopleselected').val(jQuery('#vappeopleselected').val());
		
		jQuery('.vaptlblock1').removeClass('vaptimeselected');
		jQuery(slot).find('.vaptlblock1').addClass('vaptimeselected');
		
		var opt_div = jQuery('.vapseroptionscont');

		if (opt_div.length > 0) {
			opt_div.slideDown();
		}
		
		var rec_div = jQuery('.vaprecurrencediv');

		if (rec_div.length > 0) {
			rec_div.slideDown();
		}
		
		isTimeChoosen = true;

		// inject received parameters within the event to dispatch
		var event = jQuery.Event('timeline.afterpicktime');
		event.params = eventArgs;
		
		// trigger event after picking a time slot
		jQuery(slot).trigger(event);
	}

</script>

