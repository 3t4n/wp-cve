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
$appointment = $this->appointment;

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$dispatcher = VAPFactory::getEventDispatcher();

$forms = array();

foreach (array('before', 'top', 'appointment', 'options', 'notes', 'bottom', 'after') as $location)
{
	/**
	 * Trigger event to let the plugins add custom HTML contents within the block of
	 * the booked service.
	 *
	 * @param 	string  $location  The HTML will be always placed after the specified location (@since 1.7).
	 * @param 	object 	$event     The appointment details (changed from array @since 1.7).
	 * @param 	object 	$order     The purchased order (changed from array @since 1.7).
	 *
	 * @return 	string 	The HTML to display.
	 *
	 * @since 	1.6.6
	 */
	$html = array_filter($dispatcher->trigger('onDisplayOrderServiceSummary', array($location, $appointment, $this->order)));

	// display all returned blocks, separated by a new line
	$forms[$location] = implode("\n", $html);
}

?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayOrderServiceSummary","type":"sitepage","key":"before"} -->

<?php
// display custom HTML before the summary block
echo $forms['before'];
?>

<!-- CONTENT -->

<div class="vaporderdetailsbox">

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayOrderServiceSummary","type":"sitepage","key":"top"} -->

	<?php
	// display custom HTML at the beginning of the appointment
	echo $forms['top'];
	?>

	<!-- APPOINTMENT -->

	<div class="vapordercontentinfoleft">

		<h3 class="vaporderheader"><?php echo JText::translate('VAPORDERTITLE2'); ?></h3>

		<div class="vapordercontentinfo">
			<div class="vaporderinfo">
				<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERBEGINDATETIME'); ?></span>
				<span class="vaporderinfo-value">
					<?php
					echo $appointment->customerCheckin->lc2;

					if ($config->getBool('multitimezone') && $appointment->customerCheckin->timezone)
					{
						if (preg_match("/^(europe|africa|asia)/i", $appointment->customerCheckin->timezone, $match))
						{
							// fetch the globe face matching the selected timezone
							$icon = 'globe-' . strtolower(end($match));
						}
						else
						{
							// fallback to americas side
							$icon = 'globe-americas';
						}

						?>
						<i class="fas fa-<?php echo $icon; ?> hasTooltip" title="<?php echo $this->escape($appointment->customerCheckin->timezone); ?>"></i>
						<?php
					}
					?>
				</span>
			</div>

			<div class="vaporderinfo">
				<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERENDDATETIME'); ?></span>
				<span class="vaporderinfo-value"><?php echo $appointment->customerCheckout->lc2; ?></span>
			</div>

			<?php
			if ($appointment->viewEmp)
			{
				?>
				<div class="vaporderinfo">
					<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDEREMPLOYEE'); ?></span>
					<span class="vaporderinfo-value"><?php echo $appointment->employee->name; ?></span>
				</div>
				<?php
			}
			?>

			<div class="vaporderinfo">
				<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERSERVICE'); ?></span>
				<span class="vaporderinfo-value">
					<?php
					echo $appointment->service->name;

					if ($appointment->totals->gross > 0)
					{
						// display service total gross next to the name
						echo ' ' . $currency->format($appointment->totals->gross);
					}

					// then display the appointment duration
					echo ' (' . VikAppointments::formatMinutesToTime($appointment->duration) . ')';
					?>
				</span>
			</div>

			<?php
			if ($appointment->people > 1)
			{
				?>
				<div class="vaporderinfo">
					<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPSUMMARYPEOPLE'); ?></span>
					<span class="vaporderinfo-value"><?php echo $appointment->people; ?></span>
				</div>
				<?php
			}
			?>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayOrderServiceSummary","type":"sitepage","key":"appointment"} -->

			<?php
			// display custom HTML within the appointment details block
			echo $forms['appointment'];
			?>
		</div>

	</div>

	<!-- OPTIONS -->

	<?php
	if ($appointment->options)
	{
		?>
		<div class="vapordercontentinforight">

			<h3 class="vaporderheader"><?php echo JText::translate('VAPORDERTITLE3'); ?></h3>

			<div class="vapordercontentinfo">
				<?php
				foreach ($appointment->options as $opt)
				{
					?>
					<div class="vaporderinfo">
						<?php
						if ($opt->multiple)
						{
							// display selected quantity
							?>
							<small class="option-quantity">
								<?php echo $opt->quantity . 'x '; ?>
							</small>
							<?php
						}
						?>

						<b class="option-name">
							<?php
							// display option full name
							echo $opt->fullName;
							?>
						</b>
						
						<?php
						if ($opt->price != 0)
						{
							// display option cost
							?>
							<span class="option-price">
								<?php echo $currency->format($opt->price); ?>
							</span>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayOrderServiceSummary","type":"sitepage","key":"options"} -->

				<?php
				// display custom HTML within the options details block
				echo $forms['options'];
				?>
			</div>

		</div>
		<?php
	}
	?>

	<!-- CANCELLATION -->

	<?php
	// Display the cancellation button of this single appointment only in case we are not
	// visiting a multi-order page.
	if (count($this->order->appointments) > 1)
	{
		// make sure this appointment can be cancelled
		if ($appointment->canUserCancel)
		{
			// create cancellation URI
			$cancel_uri = JRoute::rewrite("index.php?option=com_vikappointments&task=order.cancel&id={$appointment->id}&sid={$appointment->sid}&parent={$this->order->id}&Itemid={$this->itemid}", false);
			?>
			<div class="vapordercancdiv">
				<button type="button" class="vap-btn red" onClick="vapCancelButtonPressed('<?php echo $cancel_uri; ?>');">
					<i class="far fa-calendar-times"></i>
					<?php echo JText::translate('VAPORDERCANCBUTTON'); ?>
				</button>
			</div>
			<?php
		}
	}
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayOrderServiceSummary","type":"sitepage","key":"notes"} -->

	<?Php
	// display custom HTML before the user notes
	echo $forms['notes'];
	?>

	<!-- USER NOTES -->

	<?php
	// do not display notes in case of order with a single appointments, because
	// in this case the notes are displayed at the end of the box
	if (count($this->order->appointments) > 1)
	{
		// get all the user notes assigned to this appointment
		$notes = $this->order->getUserNotes($appointment->id);

		// iterate all notes, if any
		foreach ($notes as $note)
		{
			// keep track of the current note
			$this->itemNote = $note;

			// display the user notes block through an apposite template
			// to take advantage of reusability
			echo $this->loadTemplate('usernote_block');
		}
	}
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayOrderServiceSummary","type":"sitepage","key":"bottom"} -->

	<?Php
	// display custom HTML at the end of the appointment
	echo $forms['bottom'];
	?>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayOrderServiceSummary","type":"sitepage","key":"after"} -->

<?php
// display custom HTML after the summary block
echo $forms['after'];
