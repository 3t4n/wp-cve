<?php /** @var ShipX_Shipment_Model $shipment */

use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model; ?>
<p>
<?php if ( $shipment instanceof ShipX_Shipment_Model ): ?>
    <label disabled style="display: block" for="commercial_product_identifier"
           class="graytext">
		<?php _e( 'Commercial product identifier: ', 'woocommerce-inpost' ); ?>
    </label>
<?php else: ?>
    <label disabled style="display: block" for="commercial_product_identifier" class="">
		<?php _e( 'Commercial product identifier: ', 'woocommerce-inpost' ); ?>
    </label>
<?php endif ?>

<?php if ( $shipment instanceof ShipX_Shipment_Model
           && null !== $shipment->getCommercialProductIdentifier()
): ?>
    <input disabled class="commercial_product_identifier"
           type="text"
           style=""
           value="
<?php echo $shipment->getCommercialProductIdentifier(); ?>"
           id="commercial_product_identifier"
           name="commercial_product_identifier">
<?php else: ?>
    <input class="commercial_product_identifier"
           type="text"
           style=""
           value="
<?php echo esc_attr( $commercial_product_identifier ) ?>"
           id="commercial_product_identifier"
           name="commercial_product_identifier">
<?php endif; ?>
</p>