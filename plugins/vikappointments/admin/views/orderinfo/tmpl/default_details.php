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

$config = VAPFactory::getConfig();

// get default user timezone
$default_tz = JFactory::getUser()->getTimezone();

// get first appointment
$app = $this->order->appointments[0];
?>

<h3>
	<?php
	echo JText::translate('VAPAPPOINTMENT');

	if ($this->back)
	{
		?>
		<a href="<?php echo base64_decode($this->back); ?>">
			<i class="fas fa-sign-out-alt fa-flip-horizontal"></i>
		</a>
		<?php
	}
	?>
</h3>

<div class="order-fields">

	<!-- Order ID -->

	<div class="order-field">

		<label>
			<?php echo JText::translate('VAPMANAGERESERVATION1'); ?>
		</label>

		<div class="order-field-value">
			<b><?php echo $this->order->id . '-' . $this->order->sid; ?></b>

			<?php
			$creation = JText::sprintf(
				'VAPRESLISTCREATEDTIP',
				JHtml::fetch('date', $this->order->createdon, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat')),
				$this->order->author ? $this->order->author->name : JText::translate('VAPRESLISTGUEST')
			);

			?>
			<i class="fas fa-calendar-check hasTooltip" title="<?php echo $this->escape($creation); ?>" style="margin-left:4px;"></i>

			<?php
			// plugins can use the "details.id" key to introduce custom
			// HTML next to the order number
			if (isset($this->addons['details.id']))
			{
				echo $this->addons['details.id'];

				// unset details form to avoid displaying it twice
				unset($this->addons['details.id']);
			}
			?>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"details.id","type":"field"} -->

	</div>

	<!-- Status -->

	<div class="order-field">

		<label><?php echo JText::translate('VAPMANAGERESERVATION12'); ?></label>

		<div class="order-field-value">
			<?php
			echo JHtml::fetch('vaphtml.status.display', $this->order->status);

			$comment = '';

			// show remaining time available to accept the reservation
			if ($this->order->statusRole == 'PENDING')
			{
				if ($this->order->locked_until > time())
				{
					$comment = JText::sprintf(
						'VAPRESEXPIRESIN',
						VikAppointments::formatTimestamp($config->get('timeformat'), $this->order->locked_until)
					);
				}
			}
			else
			{
				// get last status in history
				$history = $this->order->history;
				$status = end($history);

				if ($status)
				{
					// include status comment
					$comment = nl2br($status->comment) . '<br /><br />'
						. JHtml::fetch('date', $status->createdon, JText::translate('DATE_FORMAT_LC6'));
				}
			}

			if ($comment)
			{
				?><i class="fas fa-question-circle hasTooltip" title="<?php echo $this->escape($comment); ?>" style="margin-left:4px;"></i><?php
			}

			// plugins can use the "details.status" key to introduce custom
			// HTML next to the order status
			if (isset($this->addons['details.status']))
			{
				echo $this->addons['details.status'];

				// unset details form to avoid displaying it twice
				unset($this->addons['details.status']);
			}
			?>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"details.status","type":"field"} -->

	</div>

	<!-- Service -->

	<div class="order-field">

		<label><?php echo JText::translate('VAPMANAGERESERVATION4'); ?></label>

		<div class="order-field-value">
			<span class="badge badge-info"><?php echo $app->service->name; ?></span>

			<?php
			// display the contents of the last modified note
			if ($this->order->notes && $this->order->notes[0]->content)
			{
				$note = $this->order->notes[0];

				// take the contents of the notes by stripping any HTML code
				$content = strip_tags((string) $note->content);
				// display at most 200 characters
				if (mb_strlen($content, 'UTF-8') > 200)
				{
					$content = rtrim(mb_substr($content, 0, 200, 'UTF-8'), '. ') . '...';
				}
				// append the last modified date
				$content .= "\n\n" . JHtml::fetch('date', VAPDateHelper::isNull($note->modifiedon) ? $note->createdon : $note->modifiedon, JText::translate('DATE_FORMAT_LC6'));
				?>
				<i class="fas fa-sticky-note hasTooltip" title="<?php echo $this->escape(nl2br($content)); ?>"></i>
				<?php
			}

			// plugins can use the "details.service" key to introduce custom
			// HTML next to the appointment service
			if (isset($this->addons['details.service']))
			{
				echo $this->addons['details.service'];

				// unset details form to avoid displaying it twice
				unset($this->addons['details.service']);
			}
			?>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"details.service","type":"field"} -->

	</div>

	<!-- Employee -->

	<div class="order-field">

		<label><?php echo JText::translate('VAPMANAGERESERVATION3'); ?></label>

		<div class="order-field-value">
			<span class="badge badge-important"><?php echo $app->employee->name; ?></span>

			<?php
			// plugins can use the "details.employee" key to introduce custom
			// HTML next to the appointment employee
			if (isset($this->addons['details.employee']))
			{
				echo $this->addons['details.employee'];

				// unset details form to avoid displaying it twice
				unset($this->addons['details.employee']);
			}
			?>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"details.employee","type":"field"} -->

	</div>

	<!-- Check-in -->

	<div class="order-field">

		<label><?php echo JText::translate('VAPMANAGERESERVATION26'); ?></label>

		<div class="order-field-value">
			<?php
			if ($app->employee->checkin->timezone && $app->employee->checkin->timezone != $default_tz->getName())
			{
				// display time adjusted to the employee timezone
				$tz_str = str_replace('_', ' ', $app->employee->checkin->timezone) . "<br />"
					. $app->employee->checkin->lc2;
			}
			else
			{
				$tz_str = '';
			}
			?>

			<b><?php echo $app->checkin->lc3; ?></b>

			<?php
			if ($tz_str)
			{
				?><i class="fas fa-globe-americas hasTooltip" title="<?php echo $this->escape($tz_str); ?>" style="margin-left:4px;"></i><?php
			}

			// plugins can use the "details.checkin" key to introduce custom
			// HTML next to the appointment check-in
			if (isset($this->addons['details.checkin']))
			{
				echo $this->addons['details.checkin'];

				// unset details form to avoid displaying it twice
				unset($this->addons['details.checkin']);
			}
			?>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"details.checkin","type":"field"} -->

	</div>

	<!-- Check-out -->

	<div class="order-field">

		<label><?php echo JText::translate('VAPMANAGERESERVATION42'); ?></label>

		<div class="order-field-value">
			<?php
			if ($app->employee->checkout->timezone && $app->employee->checkout->timezone != $default_tz->getName())
			{
				// display time adjusted to the employee timezone
				$tz_str = str_replace('_', ' ', $app->employee->checkout->timezone) . "<br />"
					. $app->employee->checkout->lc2;
			}
			else
			{
				$tz_str = '';
			}

			$checkout = new JDate($app->checkout->utc);
			$checkout->setTimezone(new DateTimeZone($app->checkout->timezone));
			?>

			<b><?php echo $checkout->format($config->get('timeformat'), $local = true); ?></b>

			<?php
			echo ' (' . VikAppointments::formatMinutesToTime($app->duration) . ')';

			if ($tz_str)
			{
				?><i class="fas fa-globe-americas hasTooltip" title="<?php echo $this->escape($tz_str); ?>" style="margin-left:4px;"></i><?php
			}
			
			// plugins can use the "details.checkout" key to introduce custom
			// HTML next to the appointment check-out
			if (isset($this->addons['details.checkout']))
			{
				echo $this->addons['details.checkout'];

				// unset details form to avoid displaying it twice
				unset($this->addons['details.checkout']);
			}
			?>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"details.checkout","type":"field"} -->

	</div>

	<?php
	if ($app->people > 1)
	{
		?>
		<!-- People -->

		<div class="order-field">

			<label><?php echo JText::translate('VAPMANAGERESERVATION25'); ?></label>

			<div class="order-field-value">
				<b><?php echo $app->people; ?></b>&nbsp;
				<i class="fas fa-male" style="margin-right: 1px;"></i><i class="fas fa-male"></i>

				<?php
				// plugins can use the "details.people" key to introduce custom
				// HTML next to the number of participants
				if (isset($this->addons['details.people']))
				{
					echo $this->addons['details.people'];

					// unset details form to avoid displaying it twice
					unset($this->addons['details.people']);
				}
				?>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"details.people","type":"field"} -->

		</div>
		<?php
	}
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"details.custom","type":"field"} -->

	<?php
	// plugins can use the "details.custom" key to introduce custom
	// HTML at the end of the block
	if (isset($this->addons['details.custom']))
	{
		echo $this->addons['details.custom'];

		// unset details form to avoid displaying it twice
		unset($this->addons['details.custom']);
	}
	?>

</div>
