<?php

namespace DhlVendor;

/**
 * Template for delivary date.
 *
 * @package WPDesk\WooCommerceShipping\OrderMetaData;
 *
 * @var int $delivery_date
 */
?>
<div class="ups-delivery-time octolize-delivery-time"><small>
	<?php 
// Translators: delivery date.
echo \sprintf(\__('(Delivery Date: %1$s)', 'flexible-shipping-dhl-express'), $delivery_date);
// WPCS: XSS ok.
?>
</small></div>
<?php 
