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

$currency = VAPFactory::getCurrency();

?>

<h3><?php echo JText::translate('VAPMANAGERESERVATION13'); ?></h3>

<div class="order-fields">

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"payment.start","type":"field"} -->

	<?php
	// plugins can use the "payment.start" key to introduce custom
	// HTML before all the payment lines
	if (isset($this->addons['payment.start']))
	{
		echo $this->addons['payment.start'];

		// unset details form to avoid displaying it twice
		unset($this->addons['payment.start']);
	}
	?>

	<!-- Net -->

	<div class="order-field total-net">

		<label>
			<?php echo JText::translate('VAPINVTOTAL'); ?>
		</label>

		<div class="order-field-value">
			<b><?php echo $currency->format($this->order->totals->net); ?></b>
		</div>

	</div>

	<?php
	if ($this->order->totals->payCharge > 0)
	{
		?>
		<!-- Payment Charge -->

		<div class="order-field payment-charge">

			<label>
				<?php echo JText::translate('VAPINVPAYCHARGE'); ?>
			</label>

			<div class="order-field-value">
				<b><?php echo $currency->format($this->order->totals->payCharge); ?></b>
			</div>

		</div>
		<?php
	}
	
	if ($this->order->totals->tax > 0)
	{
		?>
		<!-- Taxes -->

		<div class="order-field total-tax">

			<label>
				<?php echo JText::translate('VAPINVTAXES'); ?>
			</label>

			<div class="order-field-value">
				<b><?php echo $currency->format($this->order->totals->tax); ?></b>
			</div>

		</div>
		<?php
	}
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"payment.init","type":"field"} -->

	<?php
	// plugins can use the "payment.init" key to introduce custom
	// HTML after the total net and the taxes
	if (isset($this->addons['payment.init']))
	{
		echo $this->addons['payment.init'];

		// unset details form to avoid displaying it twice
		unset($this->addons['payment.init']);
	}
	?>

	<!-- Paid -->

	<div class="order-field total-paid">

		<label>
			<?php
			if ($this->order->payment)
			{
				$name = $this->order->payment->name;
				$icon = $this->order->payment->icon;

				?><i class="<?php echo $icon; ?> hasTooltip" title="<?php echo $this->escape($name); ?>" style="margin-right:4px;"></i><?php
			}

			echo JText::translate('VAPORDERPAID');
			?>
		</label>

		<div class="order-field-value">
			<b><?php echo $currency->format($this->order->totals->paid); ?></b>
		</div>

	</div>

	<?php
	if ($this->order->totals->due > 0)
	{
		?>
		<!-- Due -->

		<div class="order-field total-due">

			<label><?php echo JText::translate('VAPORDERDUE'); ?></label>

			<div class="order-field-value">
				<b><?php echo $currency->format($this->order->totals->due); ?></b>
			</div>

		</div>
		<?php
	}
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"payment.total","type":"field"} -->
	
	<?php
	// plugins can use the "payment.total" key to introduce custom
	// HTML before the grand total
	if (isset($this->addons['payment.total']))
	{
		echo $this->addons['payment.total'];

		// unset details form to avoid displaying it twice
		unset($this->addons['payment.total']);
	}
	?>

	<!-- Total Cost -->

	<div class="order-field total-cost">

		<label><?php echo JText::translate('VAPORDERDEPOSIT'); ?></label>

		<div class="order-field-value">
			<b><?php echo $currency->format($this->order->totals->gross); ?></b>
		</div>

	</div>

</div>

<?php
if ($this->order->coupon)
{
	$title = JText::translate('VAPMANAGERESERVATION21');

	if ($this->order->coupon->amount)
	{
		$title .= ' : ';

		if ($this->order->coupon->type == 1)
		{
			$title .= $this->order->coupon->amount . '%';
		}
		else
		{
			$title .= $currency->format($this->order->coupon->amount);
		}
	}
	?>
	<!-- Coupon -->

	<div class="coupon-box">

		<span class="coupon-code">
			<i class="fas fa-ticket-alt hasTooltip" title="<?php echo $this->escape($title); ?>"></i>
			<b><?php echo $this->order->coupon->code; ?></b>
		</span>

		<?php
		if ($this->order->totals->discount)
		{
			?>
			<span class="coupon-amount">
				<?php echo $currency->format($this->order->totals->discount); ?>
			</span>
			<?php
		}
		?>

	</div>
	<?php
}

if ($this->order->invoice)
{
	?>
	<hr />

	<!-- Invoice -->

	<div class="invoice-record">

		<!-- Invoice Number -->

		<div class="invoice-id">
			<b><?php echo $this->order->invoice->inv_number; ?></b>
		</div>

		<!-- Invoice Creation Date -->

		<div class="invoice-date">
			<?php echo JHtml::fetch('date', $this->order->invoice->createdon, JText::translate('DATE_FORMAT_LC2')); ?>
		</div>

		<!-- Invoice File -->

		<div class="invoice-download">
			<a href="<?php echo $this->order->invoice->uri; ?>" target="_blank">
				<i class="fas fa-file-pdf"></i>
			</a>
		</div>

	</div>
	<?php
}
?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"payment.middle","type":"field"} -->

<?php
// plugins can use the "payment.middle" key to introduce custom
// HTML between the coupon code and the payment logs
if (isset($this->addons['payment.middle']))
{
	echo $this->addons['payment.middle'];

	// unset details form to avoid displaying it twice
	unset($this->addons['payment.middle']);
}

if ($this->order->log)
{
	?>
	<div class="logs-box">
		<pre><?php echo $this->order->log; ?></pre>
	</div>
	<?php
}
?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"payment.bottom","type":"field"} -->

<?php
// plugins can use the "payment.bottom" key to introduce custom
// HTML after the payment logs
if (isset($this->addons['payment.bottom']))
{
	echo $this->addons['payment.bottom'];

	// unset details form to avoid displaying it twice
	unset($this->addons['payment.bottom']);
}
