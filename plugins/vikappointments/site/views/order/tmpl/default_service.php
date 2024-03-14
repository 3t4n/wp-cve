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

// we are inside a foreach, extract the current element of the cycle
$appointments = $this->groupServices;

?>

<div class="vaporderboxcontent">

	<!-- BOX TITLE -->

	<div class="vap-order-first">
		<h3 class="vaporderheader vap-head-first"><?php echo $appointments[0]->service->name; ?></h3>
	</div>

	<?php
	// Iterate all the appointments that belong to this service,
	// that might occur in case of a multi-order.
	foreach ($appointments as $appointment)
	{
		// keep track of the current appointment
		$this->appointment = $appointment;

		// increase counter
		$this->count++;

		// display the appointment details and the purchased options
		echo $this->loadTemplate('appointment');
	}

	// check whether the cancellation is enabled
	if (VAPFactory::getConfig()->getBool('enablecanc') && $this->order->statusRole == 'APPROVED')
	{ 
		$count = count($this->order->appointments);

		if ($count == 1)
		{
			// cancel the booked appointment
			$canc_all_text = 'VAPORDERCANCBUTTON';
		}
		else
		{
			// cancel all the appointments
			$canc_all_text = 'VAPORDERCANCALLBUTTON';
		}

		// display the global cancellation button only at the end of the list, after
		// checking the possibility
		if ($this->count == $count && $this->canUserCancelAll)
		{
			?>
			<div class="vapordercancdiv vapcancallbox">
				<button type="button" class="vap-btn red" onClick="vapCancelButtonPressed('<?php echo $this->cancelURI; ?>');">
					<i class="far fa-calendar-times"></i>
					<?php echo JText::translate($canc_all_text); ?>
				</button>
			</div>
			<?php
		}
	}
	?>

</div>
