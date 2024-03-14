<?php /** @var ShipX_Shipment_Model $shipment */

use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model; ?>
<p>
<?php if ( $shipment instanceof ShipX_Shipment_Model ): ?>
    <label disabled style="display: block" for="reference_number"
           class="graytext">
		<?php _e( 'Reference number: ', 'woocommerce-inpost' ); ?>
    </label>
<?php else: ?>
    <label disabled style="display: block" for="reference_number" class="">
		<?php _e( 'Reference number: ', 'woocommerce-inpost' ); ?>
    </label>
<?php endif ?>

<?php if ( $shipment instanceof ShipX_Shipment_Model
           && null !== $shipment->getReference()
): ?>
    <input disabled class="reference_number"
           type="text"
           style=""
           value="
<?php echo $shipment->getReference(); ?>"
           id="reference_number"
           name="reference_number">
<?php else:

    $default_ref_number = isset($_GET['post']) ? $_GET['post'] : $_GET['id'];

    $ref_number = get_post_meta( $order_id, '_reference_number', true )
                ? get_post_meta( $order_id, '_reference_number', true )
                : $default_ref_number;
    ?>
    <input class="reference_number"
           type="text"
           style=""
           value="
<?php echo esc_attr( $ref_number ) ?>"
           id="reference_number"
           name="reference_number">
<?php endif; ?>
</p>