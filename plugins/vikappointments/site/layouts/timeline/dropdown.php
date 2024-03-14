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
 * @var  VAPAvailabilityTimeline  $timeline  The timeline to render.
 */
extract($displayData);

$time_format = VAPFactory::getConfig()->get('timeformat');

$tz = VikAppointments::getUserTimezone();

// get timeline date
$date = $timeline->getDate();

?>

<!-- CHECKIN -->

<select id="vap-checkin-sel" onchange="checkinSelectValueChanged(this);">

	<option></option>

	<?php
	$link = 0;
	foreach ($timeline as $times)
	{
		$link++;
		foreach ($times as $time)
		{	
			if ($time->isAvailable())
			{
				// get hour and minutes
				$hour = (int) $time->checkin('G');
				$min  = (int) $time->checkin('i');

				/**
				 * Hide time slots that exceed the midnight.
				 * The "hidden" class should do the trick for select2 plugin.
				 *
				 * @since 1.6.2
				 */
				$should_hide = $time->checkin('Y-m-d') != $date ? 'hidden' : '';
				?>
				<option 
					value="<?php echo $time->checkin('Y-m-d H:i'); ?>"
					class="<?php echo $time->checkout('Y-m-d H:i'); ?>"
					data-hour="<?php echo $hour; ?>"
					data-min="<?php echo $min; ?>"
					data-link="<?php echo $link; ?>"
					data-checkout="<?php echo $checkout; ?>"
					data-checkout-date="<?php echo $time->checkout($time_format, $tz);; ?>"
				><?php echo $time->checkin($time_format, $tz); ?></option>
				<?php
			}
			else
			{
				$link++;
			}
		}
	}
	?>

</select>

<!-- CHECKOUT -->

<select id="vap-checkout-sel" disabled="disabled" style="margin-left: 10px;" onchange="checkoutSelectValueChanged(this);">
	<option></option>
</select>

<script>

	jQuery('#vap-checkin-sel, #vap-checkout-sel').select2({
		minimumResultsForSearch: -1,
		placeholder: '--',
		allowClear: false,
		width: 150,
	});

</script>
