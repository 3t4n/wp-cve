<?php

namespace FedExVendor;

/**
 * Template for days to arrival.
 *
 * @package WPDesk\WooCommerceShipping\OrderMetaData
 *
 * @var int $days_to_arrival_date
 */
?>
<div class="ups-delivery-time octolize-delivery-time"><small>
	<?php 
if (0 === \intval($days_to_arrival_date)) {
    \_e('(Delivery Days: 0 days)', 'flexible-shipping-fedex');
    // WPCS: XSS ok.
} else {
    // Translators: time in transit.
    echo \sprintf(\_n('(Delivery Days: %1$d day)', '(Delivery Days: %1$d days)', $days_to_arrival_date, 'flexible-shipping-fedex'), $days_to_arrival_date);
    // WPCS: XSS ok.
}
?>
</small></div>
<?php 
