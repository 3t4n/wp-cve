<?php /** @var ShipX_Shipment_Model $shipment */

use InspireLabs\WooInpost\EasyPack;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Model; ?>

<?php if ($shipment instanceof ShipX_Shipment_Model): ?>
<?php $inputDisabled = ' disabled '?>
    <label disabled style="display: block" for="reference_number" class="graytext">
        <?php _e('Insurance amount: ', 'woocommerce-inpost'); ?>
    </label>
<?php else: ?>
    <?php $inputDisabled = ''?>
    <label style="display: block" for="reference_number">
        <?php _e('Insurance amount: ', 'woocommerce-inpost'); ?>
    </label>
<?php endif?>

<?php if ($shipment instanceof ShipX_Shipment_Model && null !== $shipment->getInsurance()): ?>
    <input <?php echo esc_attr( $inputDisabled ); ?>
           class="insurance_amount"
           type="number"
           style=""
           value="<?php echo esc_attr( $shipment->getInsurance()->getAmount() ); ?>"
           placeholder="0.00"
           step="any"
           min="0"
           id="insurance_amounts"
           name="insurance_amounts[]">
<?php else: ?>

    <?php $insurance = get_post_meta( $order_id, '_easypack_parcel_insurance', true )
        ? get_post_meta( $order_id, '_easypack_parcel_insurance', true )
        : floatval( get_option('easypack_insurance_amount_default') ); ?>

    <input <?php echo esc_attr( $inputDisabled ); ?>class="insurance_amount"
           type="number" style=""
           value="<?php echo esc_attr( $insurance ); ?>"
           placeholder="0.00"
           step="any"
           min="0"
           id="insurance_amounts"
           name="insurance_amounts[]<?php echo esc_attr( $inputDisabled ); ?>">
<?php endif; ?>
