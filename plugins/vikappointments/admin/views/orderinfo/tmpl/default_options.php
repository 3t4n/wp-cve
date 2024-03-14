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

// get first appointment
$app = $this->order->appointments[0];

$currency = VAPFactory::getCurrency();

?>

<h3><?php echo JText::translate('VAPMENUOPTIONS'); ?></h3>

<div class="order-items-cart">

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"options.start","type":"field"} -->

	<?php
	// plugins can use the "options.start" key to introduce custom
	// HTML before the options list
	if (isset($this->addons['options.start']))
	{
		echo $this->addons['options.start'];

		// unset details form to avoid displaying it twice
		unset($this->addons['options.start']);
	}
	
	foreach ($app->options as $option)
	{
		?>
		<div class="cart-item-record">

			<div class="cart-item-details">
				
				<div class="cart-item-name">
					<span class="item-prod-name"><?php echo $option->name; ?></span>

					<?php
					if ($option->varName)
					{
						?><span class="item-option-name badge badge-info"><?php echo $option->varName; ?></span><?php
					}
					?>
				</div>

				<div class="cart-item-quantity">
					<?php
					if ($option->multiple || $option->quantity > 1)
					{
						echo 'x' . $option->quantity;
					}
					?>
				</div>

				<div class="cart-item-price">
					<?php echo $currency->format($option->totals->gross); ?>
				</div>

			</div>

		</div>
		<?php
	}
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"options.end","type":"field"} -->

	<?php
	// plugins can use the "options.end" key to introduce custom
	// HTML next to the purchased options
	if (isset($this->addons['options.end']))
	{
		echo $this->addons['options.end'];

		// unset details form to avoid displaying it twice
		unset($this->addons['options.end']);
	}
	?>

</div>
