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

$order = $this->orderDetails;

$currency = VAPFactory::getCurrency();

?>

<div class="vap-print-order-wrapper">

	<!-- HEAD -->

	<div class="vap-print-box">

		<!-- ORDER NUMBER -->

		<div class="vap-print-field">
			<span class="vap-print-label"><?php echo JText::translate('VAPMANAGERESERVATION1'); ?></span>
			<span class="vap-print-value">
				<?php echo $order->id . ' - ' . $order->sid; ?>
			</span>
		</div>

		<!-- ORDER STATUS -->

		<div class="vap-print-field">
			<span class="vap-print-label"><?php echo JText::translate('VAPMANAGERESERVATION12'); ?></span>
			<span class="vap-print-value">
				<?php echo JHtml::fetch('vaphtml.status.display', $order->status); ?>
			</span>
		</div>

		<!-- CREATION DATE -->

		<div class="vap-print-field">
			<span class="vap-print-label"><?php echo JText::translate('VAPINVDATE'); ?></span>
			<span class="vap-print-value">
				<?php echo JHtml::fetch('date', $order->createdon, JText::translate('DATE_FORMAT_LC2')); ?>
			</span>
		</div>

		<!-- PAYMENT -->

		<?php
		if ($order->payment)
		{
			?>
			<div class="vap-print-field">
				<span class="vap-print-label"><?php echo JText::translate('VAPMANAGERESERVATION13'); ?></span>
				<span class="vap-print-value">
					<?php echo $order->payment->name; ?>
				</span>
			</div>
			<?php
		}
		?>

		<!-- TOTAL -->

		<?php
		if ($order->totals->gross > 0)
		{
			?>
			<div class="vap-print-field">
				<span class="vap-print-label"><?php echo JText::translate('VAPMANAGERESERVATION9'); ?></span>
				<span class="vap-print-value">
					<?php echo $currency->format($order->totals->gross); ?>
				</span>
			</div>
			<?php
		}
		?>

		<!-- COUPON -->

		<?php
		if ($order->coupon)
		{ 
			?>
			<div class="vap-print-field">
				<span class="vap-print-label"><?php echo JText::translate('VAPMANAGERESERVATION21'); ?></span>
				<span class="vap-print-value">
					<?php
					echo $order->coupon->code . ' : ';

					if ($order->coupon->type == 1)
					{
						echo $order->coupon->amount . '%';
					}
					else
					{
						$currency->format($order->coupon->amount);
					}
					?>
				</span>
			</div>
			<?php
		}
		?>
		
	</div>

	<!-- CUSTOMER DETAILS -->

	<?php
	if ($order->hasFields)
	{
		?>
		<div class="vap-print-box">
			<?php
			foreach ($order->displayFields as $k => $v)
			{
				?>
				<div class="vap-print-field">
					<span class="vap-print-label"><?php echo $k; ?></span>
					<span class="vap-print-value"><?php echo nl2br($v); ?></span>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>

	<!-- APPOINTMENTS -->

	<?php
	if ($order->appointments)
	{
		?>
		<div class="vap-print-box">
			<?php
			foreach ($order->appointments as $app)
			{
				?>
				<div class="vap-print-item">

					<!-- APPOINTMENT DETAILS -->

					<div class="print-item-details">

						<!-- SERVICE -->

						<div>
							<b><?php echo $app->service->name; ?></b>

							<?php
							if ($app->people > 1)
							{
								echo ' (x' . JText::plural('VAP_N_PEOPLE', $app->people) . ')';
							}

							echo ' - ';
							?>

							<b><?php echo $app->employee->name; ?></b>
						</div>

						<!-- CHECK-IN -->

						<div>
							<b><?php echo $app->checkin->lc2; ?></b>
							
							<?php
							if (VAPFactory::getConfig()->getBool('multitimezone'))
							{
								echo ' (' . $app->checkin->timezone . ')';
							}

							echo ' - ';
							?>

							<b><?php echo VikAppointments::formatMinutesToTime($app->duration); ?></b>
						</div>

						<!-- LOCATION -->

						<?php
						if ($app->location)
						{
							?>
							<div>
								<?php echo $app->location->text; ?>
							</div>
							<?php
						}
						?>

					</div>

					<!-- APPOINTMENT OPTIONS -->

					<?php
					if ($app->options)
					{
						?>
						<div class="print-item-optionslist">

							<?php
							foreach ($app->options as $opt)
							{
								?>
								<div class="print-item-option">

									<!-- QUANTITY -->

									<span class="opt-quantity">
										<?php echo $opt->quantity; ?><small>x</small>
									</span>

									<!-- NAME -->

									<span class="opt-name">
										<?php echo $opt->fullName; ?>
									</span>

									<!-- COST -->

									<span class="opt-total">
										<?php echo $currency->format($opt->totals->gross); ?>
									</span>

								</div>
								<?php
							}
							?>

						</div>
						<?php
					}
					?>

					<!-- APPOINTMENT TOTALS -->

					<?php
					if ($app->totals->grossOpt > 0)
					{
						?>
						<div class="print-item-totals">

							<!-- TOTAL -->

							<div class="vap-total-row">
								<span class="vap-print-label"><?php echo JText::translate('VAPINVGRANDTOTAL'); ?></span>
								<span class="vap-print-amount">
									<?php echo $currency->format($app->totals->grossOpt); ?>
								</span>
							</div>

						</div>
						<?php
					}
					?>

				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>

	<!-- ORDER TOTAL -->

	<?php
	if ($order->totals->gross > 0)
	{
		?>
		<div class="vap-print-box">
			
			<!-- TOTAL NET -->

			<div class="vap-total-row">
				<span class="vap-print-label"><?php echo JText::translate('VAPINVTOTAL'); ?></span>
				<span class="vap-print-amount">
					<?php echo $currency->format($order->totals->net); ?>
				</span>
			</div>

			<!-- PAYMENT CHARGE -->

			<?php		
			if ($order->totals->payCharge != 0)
			{
				?>
				<div class="vap-total-row">
					<span class="vap-print-label"><?php echo JText::translate('VAPINVPAYCHARGE'); ?></span>
					<span class="vap-print-amount">
						<?php echo $currency->format($order->totals->payCharge); ?>
					</span>
				</div>
				<?php
			}
			?>

			<!-- TAXES -->
			
			<?php
			if ($order->totals->tax != 0)
			{
				?>
				<div class="vap-total-row">
					<span class="vap-print-label"><?php echo JText::translate('VAPINVTAXES'); ?></span>
					<span class="vap-print-amount">
						<?php echo $currency->format($order->totals->tax); ?>
					</span>
				</div>
				<?php
			}
			?>

			<!-- DISCOUNT -->

			<?php
			if ($order->totals->discount > 0)
			{
				?>
				<!-- DISCOUNT -->
				<div class="vap-total-row">
					<span class="vap-print-label"><?php echo JText::translate('VAPMANAGEPACKAGE13'); ?></span>
					<span class="vap-print-amount">
						<?php echo $currency->format($order->totals->discount * -1); ?>
					</span>
				</div>
				<?php
			}
			?>

			<!-- GRAND TOTAL -->

			<div class="vap-total-row">
				<span class="vap-print-label"><?php echo JText::translate('VAPINVGRANDTOTAL'); ?></span>
				<span class="vap-print-amount">
					<?php echo $currency->format($order->totals->gross); ?>
				</span>
			</div>

		</div>
		<?php
	}
	?>

</div>
