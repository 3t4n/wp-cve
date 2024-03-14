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

$order = $this->order;

$dispatcher = VAPFactory::getEventDispatcher();

foreach ($order->attendees as $i => $attendee)
{
	$forms = array();

	foreach (array('top', 'actions', 'bottom') as $location)
	{
		/**
		 * Trigger event to let the plugins add custom HTML contents within the attendee box.
		 *
		 * @param 	string   $location  The HTML will be always placed after the specified location.
		 * @param 	array    $attendee  The attendee details array.
		 * @param 	integer  $index     The current attendee number.
		 *
		 * @return 	string   The HTML to display.
		 *
		 * @since 	1.7
		 */
		$html = array_filter($dispatcher->trigger('onDisplayAttendeeDetails', array($location, $attendee, $i)));

		// display all returned blocks, separated by a new line
		$forms[$location] = implode("\n", $html);
	}

	// do not display the attendee block in case of empty fields
	if (empty($attendee['display']) && !array_filter($forms))
	{
		continue;
	}

	?>
	<div class="vaporderboxcontent">

		<!-- BOX TITLE -->
					
		<div class="vap-order-first">

			<h3 class="vaporderheader vap-head-first"><?php echo JText::sprintf('VAP_N_ATTENDEE', $i + 2); ?></h3>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayAttendeeDetails","type":"sitepage","key":"actions"} -->

			<?php
			// display custom HTML within the actions toolbar
			echo $forms['actions'];
			?>

		</div>

		<!-- LEFT SIDE -->

		<div class="vaporderboxleft">
			<div class="vapordercontentinfo">

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayAttendeeDetails","type":"sitepage","key":"top"} -->

				<?php
				// display custom HTML at the beginning of the order details
				echo $forms['top'];
				?>

				<?php
				foreach ($attendee['display'] as $key => $val)
				{
					?>
					<div class="vaporderinfo">
						<span class="vaporderinfo-lbl"><?php echo $key; ?></span>
						<span class="vaporderinfo-value"><?php echo nl2br($val); ?></span>
					</div>
					<?php
				}
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayAttendeeDetails","type":"sitepage","key":"bottom"} -->

				<?php
				// display custom HTML at the beginning of the order details
				echo $forms['bottom'];
				?>

			</div>
		</div>

	</div>
	<?php
}
