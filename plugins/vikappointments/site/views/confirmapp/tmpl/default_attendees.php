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

/**
 * Count the total number of attendees and, in case it is higher than 1, 
 * display the custom fields to collect the information of the other guests.
 *
 * @since 1.7
 */
$attendees = VAPCartUtils::getAttendees($this->cart->getItemsList());

if ($attendees > 1 && VAPCustomFieldsRenderer::hasRepeatableFields($this->customFields))
{
	// iterate fields for any other attendee (excluded the first one)
	for ($attendee = 1; $attendee < $attendees; $attendee++)
	{
		?>
		<div class="vapcompleteorderdiv">

			<!-- TITLE -->

			<h3 class="vap-confirmapp-h3"><?php echo JText::sprintf('VAP_N_ATTENDEE', $attendee + 1); ?></h3>

			<!-- FIELDS -->

			<div class="vapcustomfields attendee-fieldset">
				<?php
				// render the custom fields form by using the apposite helper
				echo VAPCustomFieldsRenderer::displayAttendee($attendee, $this->customFields);
				?>
			</div>

		</div>
		<?php
	}
}
