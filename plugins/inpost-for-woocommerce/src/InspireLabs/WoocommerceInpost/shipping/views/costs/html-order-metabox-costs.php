<?php /** @var ShipX_Shipment_Model $shipment */

use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment_cost\ShipX_Shipment_Cost_Model; ?>

<?php


$calculated_charge_amount
    = $calculated_charge_amount_nc
    = $cod_charge_amount
    = $fuel_charge_amount
    = $insurance_charge_amount
    = $notification_charge_amount
    = '';

if ($shipment instanceof ShipX_Shipment_Model) {
    $cost = $shipment->getInternalData()->getShipmentCost();
} else {
    $cost = null;
}

if ($cost instanceof ShipX_Shipment_Cost_Model) {
    if (true !== $cost->isError()) {
        $calculated_charge_amount    = $cost->getCalculatedChargeAmount();
        $calculated_charge_amount_nc = $cost->getCalculatedChargeAmountNonCommission();
        $cod_charge_amount           = $cost->getCodChargeAmount();
        $fuel_charge_amount          = $cost->getFuelChargeAmount();
        $insurance_charge_amount     = $cost->getInsuranceChargeAmount();
        $notification_charge_amount  = $cost->getNotificationChargeAmount();
    }
}

?>

<ul class="shipment_costs_wrapper<?php echo null === $cost ? ' hidden' : '' ?>">

    <span style="font-weight: bold"><?php _e('Shipment costs:', 'woocommerce-inpost') ?> </span>
    <li>
        <ol style="list-style-type: none">
            <li>
                <span><?php _e('Calculated charge amount:', 'woocommerce-inpost') ?></span>
                <span id="calculated_charge_amount"> <?php echo esc_html( $calculated_charge_amount ); ?></span>
            </li>

            <li>
                <span><?php _e('Calculated charge amount (non commission):', 'woocommerce-inpost') ?></span>
                <span id="calculated_charge_amount_nc"><?php echo esc_html( $calculated_charge_amount_nc ); ?></span>
            </li>

            <li>
                <span><?php _e('COD charge amount:', 'woocommerce-inpost') ?></span>
                <span id="cod_charge_amount"> <?php echo esc_html( $cod_charge_amount ); ?></span>
            </li>

            <li>
                <span><?php _e('Fuel charge amount:', 'woocommerce-inpost') ?></span>
                <span id="fuel_charge_amount"> <?php echo esc_html( $fuel_charge_amount ); ?></span>
            </li>

            <li>
                <span><?php _e('Insurance charge amount:', 'woocommerce-inpost') ?></span>
                <span id="insurance_charge_amount"> <?php echo esc_html( $insurance_charge_amount ); ?></span>
            </li>

            <li>
                <span><?php _e('Notification charge amount:', 'woocommerce-inpost') ?></span>
                <span id="notification_charge_amount"> <?php echo esc_html( $notification_charge_amount ); ?></span>
            </li>
        </ol>
    </li>
</ul>

