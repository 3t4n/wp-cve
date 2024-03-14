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

if (!empty($displayData['cart']))
{
	// use given cart instance
	$cart = $displayData['cart'];
}
else
{
	// load current cart instance
	$cart = JModelVAP::getInstance('cart');
}

// rely on the user key because in case of guests, the attributes will be
// filled with a NULL value, causing a failure with both isset and empty
if (array_key_exists('user', $displayData))
{
	// use given customer object
	$user = $displayData['user'];
}
else
{
	// load customer details
	$user = VikAppointments::getCustomer();
}

if (!$cart || $cart->getTotalCost() == 0)
{
	// do not proceed with totals in case the total cost of the booked appointments is 0,
	// because the seller if probably offering services without cost
	return;
}

$currency = VAPFactory::getCurrency();

/**
 * Let the cart calculates all the discounts.
 *
 * @since 1.7
 */
$discountMap = $cart->getTotalDiscountPerOffer();
?>

<div class="vap-cart-summary-gtotal">

	<?php
	/**
	 * The system now supports multiple discounts. Iterate all the registered discounts and print
	 * them one by one. Pre-installed discounts, such as the coupon code and the user credit are
	 * trated in a different one, so that we can display further details.
	 *
	 * @since 1.7
	 */
	foreach ($discountMap as $discTitle => $discAmount)
	{
		?>
		<div class="vapsummarycoupondiv">

			<div class="vapsummarycouponrightdiv">

				<span class="vapsummarycoupontitle">
					<?php 
					if ($discTitle == 'coupon')
					{
						// extract coupon data from discount
						$coupon = $cart->getDiscount($discTitle)->get('couponData');

						$tip = $coupon->code;

						if ($coupon->percentot == 1)
						{
							$tip .= ' : ' . $coupon->value . '%';
						}

						// display coupon label
						echo JText::translate('VAPSUMMARYCOUPON');
						?>
						<i class="fas fa-info-circle hasTooltip" title="<?php echo $tip; ?>"></i>
						<?php
					}
					else if ($discTitle == 'credit')
					{
						// fetch user credit text
						if ($discAmount < $user->credit)
						{
							// still some credit available for the user, since the total cost of
							// the order was lower than the remaining used credit
							$tip = JText::sprintf(
								'VAPUSERCREDITUSED',
								$currency->format($user->credit),
								$currency->format($discAmount)
							);
						}
						else
						{
							// the system used all the remaining used credit, since the total cost
							// the order was equals or higher than the remaining balance
							$tip = JText::sprintf(
								'VAPUSERCREDITFINISHED',
								$currency->format($user->credit)
							);
						}

						// display generic discount label
						echo JText::translate('VAPSUMMARYDISCOUNT');
						?>
						<i class="fas fa-info-circle hasTooltip" title="<?php echo $tip; ?>"></i>
						<?php
					}
					else
					{
						// display given discount title
						echo $discTitle;
					}
					?>
				</span>

				<span class="vapsummarycouponvalue" id="vapsummarycolcoupon">
					<?php
					// multiply the discount per -1 in order to display a negative value
					echo $currency->format($discAmount * -1);
					?>
				</span>

			</div>

		</div>
		<?php
	}

	// get order taxes
	$tax = $cart->getTotalTax();

	// display net and tax only whether the taxes are applied
	if ($tax)
	{
		?>
		<div class="vapsummarytotaldiv total-net">
			<span class="vapsummarytottitle">
				<?php echo JText::translate('VAPINVTOTAL'); ?>
			</span>

			<span class="vapsummarytotprice" id="cart-total-net">
				<?php echo $currency->format($cart->getTotalNet()); ?>
			</span>
		</div>

		<div class="vapsummarytotaldiv total-tax">
			<span class="vapsummarytottitle">
				<?php echo JText::translate('VAPINVTAXES'); ?>
			</span>
			
			<span class="vapsummarytotprice" id="cart-total-tax">
				<?php echo $currency->format($tax); ?>
			</span>
		</div>
		<?php
	}
	?>
	
	<div class="vapsummarytotaldiv total-gross">
		<span class="vapsummarytottitle">
			<?php echo JText::translate('VAPSUMMARYTOTAL'); ?>
		</span>
		
		<span class="vapsummarytotprice" id="cart-total-gross">
			<?php echo $currency->format($cart->getTotalGross()); ?>
		</span>
	</div>

</div>
