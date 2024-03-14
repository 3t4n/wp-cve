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

$currency = VAPFactory::getCurrency();

$dispatcher = VAPFactory::getEventDispatcher();

$forms = array();

foreach (array('before', 'top', 'actions', 'order', 'payment', 'fields', 'bottom', 'after') as $location)
{
	/**
	 * Trigger event to let the plugins add custom HTML contents within the order details box.
	 *
	 * @param 	string  $location  The HTML will be always placed after the specified location.
	 * @param 	object  $order     The object holding the order details.
	 *
	 * @return 	string  The HTML to display.
	 *
	 * @since 	1.7
	 */
	$html = array_filter($dispatcher->trigger('onDisplayOrderDetails', array($location, $order)));

	// display all returned blocks, separated by a new line
	$forms[$location] = implode("\n", $html);
}

?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayOrderDetails","type":"sitepage","key":"before"} -->

<?php
// display custom HTML before the summary block
echo $forms['before'];
?>

<div class="vaporderboxcontent">

	<!-- BOX TITLE -->
				
	<div class="vap-order-first">

		<h3 class="vaporderheader vap-head-first"><?php echo JText::translate('VAPORDERTITLE1'); ?></h3>

		<?php
		// check whether the customer is allowed to print the orders
		if (VAPFactory::getConfig()->getBool('printorders'))
		{
			?>
			<div class="vap-printable">
				<a 
					href="<?php echo 'index.php?option=com_vikappointments&task=order.doprint&id=' . $order->id . '&sid=' . $order->sid; ?>&tmpl=component" 
					target="_blank"
					title="<?php echo $this->escape(JText::translate('VAPORDERPRINTACT')); ?>"
				>
					<i class="fas fa-print"></i>
				</a>
			</div>
			<?php
		}

		// check whether we should display the link to download the invoice
		if ($order->invoice)
		{
			?>
			<div class="vap-printable">
				<a
					href="<?php echo $order->invoice->uri; ?>"
					target="_blank"
					title="<?php echo $this->escape(JText::translate('VAPORDERINVOICEACT')); ?>"
				>
					<i class="fas fa-file-pdf"></i>
				</a>
			</div>
			<?php
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayOrderDetails","type":"sitepage","key":"actions"} -->

		<?php
		// display custom HTML within the actions toolbar
		echo $forms['actions'];
		?>

	</div>

	<!-- LEFT SIDE -->

	<div class="vaporderboxleft">

		<div class="vapordercontentinfo">

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayOrderDetails","type":"sitepage","key":"top"} -->

			<?php
			// display custom HTML at the beginning of the order details
			echo $forms['top'];
			?>

			<div class="vaporderinfo">
				<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERNUMBER'); ?></span>
				<span class="vaporderinfo-value"><?php echo $order->id; ?></span>
			</div>

			<div class="vaporderinfo">
				<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERKEY'); ?></span>
				<span class="vaporderinfo-value"><?php echo $order->sid; ?></span>
			</div>

			<div class="vaporderinfo">
				<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERSTATUS'); ?></span>
				<span class="vaporderinfo-value">
					<?php
					echo JHtml::fetch('vaphtml.status.display', $order->status);

					/**
					 * Check whether the user is able to self-confirm the appointment.
					 * 
					 * @since 1.7.1
					 */
					if (VikAppointments::canUserApproveOrder($order))
					{
						// display a tooltip to inform the user that the appointment should
						// be confirmed by clicking the apposite link received via e-mail
						JHtml::fetch('bootstrap.tooltip', '.status-help');

						?>
						<i class="fas fa-question-circle status-help" title="<?php echo $this->escape(JText::translate('VAP_ORDER_APPROVE_HELP')); ?>"></i>
						<?php
					}
					?>
				</span>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayOrderDetails","type":"sitepage","key":"order"} -->

			<?php
			// display custom HTML within the order section, after the status
			echo $forms['order'];
			?>

			<br clear="all" />
				
			<?php
			if ($order->payment)
			{
				?>
				<div class="vaporderinfo">
					<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERPAYMENT'); ?></span>
					<span class="vaporderinfo-value">
						<?php
						echo $order->payment->name;

						if ($order->totals->payCharge > 0)
						{
							echo ' (' . $currency->format($order->totals->payCharge + $order->totals->payTax) . ')';
						}
						?>
					</span>
				</div>

				<?php
				if ($this->payment && $order->statusRole == 'PENDING')
				{
					?>
					<div class="vaporderinfo">
						<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERRESERVATIONCOST'); ?></span>
						<span class="vaporderinfo-value"><?php echo $currency->format($this->payment['total_to_pay']); ?></span>
					</div>
					<?php
				}
			}

			if ($order->totals->gross > 0)
			{
				?>
				<div class="vaporderinfo">
					<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERDEPOSIT'); ?></span>
					<span class="vaporderinfo-value">
						<?php
						echo $currency->format($order->totals->gross);

						if ($order->coupon)
						{
							// display the coupon code next to the total gross
							echo ' (' . $order->coupon->code . ')';
						}
						?>
					</span>
				</div>

				<?php
				if ($order->totals->paid > 0)
				{
					?>
					<div class="vaporderinfo">
						<span class="vaporderinfo-lbl"><?php echo JText::translate('VAPORDERTOTALPAID'); ?></span>
						<span class="vaporderinfo-value"><?php echo $currency->format($order->totals->paid); ?></span>
					</div>
					<?php
				}
			}
			?>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayOrderDetails","type":"sitepage","key":"payment"} -->

			<?php
			// display custom HTML within the payment section
			echo $forms['payment'];
			?>

		</div>

		<?php
		/**
		 * In case of PENDING status, display a countdown to inform the users that they need
		 * to pay/confirm their appointments within the specified range of time.
		 *
		 * @since 1.7
		 */
		if ($order->statusRole == 'PENDING' && VAPFactory::getConfig()->getBool('showcountdown'))
		{
			// the template will be shown only if explicitly enabled from the configuration
			echo $this->loadTemplate('countdown');
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayOrderDetails","type":"sitepage","key":"bottom"} -->

		<?php
		// display custom HTML at the end of the order details
		echo $forms['bottom'];
		?>

	</div>

	<!-- RIGHT SIDE -->

	<div class="vaorderboxright">
		<?php
		foreach ($order->displayFields as $key => $val)
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
		<!-- {"rule":"customizer","event":"onDisplayOrderDetails","type":"sitepage","key":"fields"} -->

		<?php
		// display custom HTML within the user fields section
		echo $forms['fields'];
		?>
	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayOrderDetails","type":"sitepage","key":"after"} -->

<?php
// display custom HTML after the summary block
echo $forms['after'];
