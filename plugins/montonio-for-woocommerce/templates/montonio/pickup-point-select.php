
<?php
/**
 * @var $chosenShippingMethodId string
 * @var $placeholder string
 * @var $pickupPoints array 
 */

$montonio_shipping_include_address = get_option( 'montonio_shipping_show_address' );
?>

<tr class="montonio-pickup-point">
    <td colspan="2" class="forminp">
        <div class="montonio-pickup-point-select-wrapper">
            <label for="<?php echo 'montonio-pickup-point-select-' . $chosenShippingMethodId; ?>"><?php echo __( 'Pickup point', 'montonio-for-woocommerce' ); ?> <abbr class="required" title="required">*</abbr></label>
            <select name="montonio_pickup_point" id="<?php echo 'montonio-pickup-point-select-' . $chosenShippingMethodId; ?>" data-placeholder="<?php echo $placeholder; ?>" class="montonio-pickup-point-select" style="width:100%">
                <option value=""><?php echo $placeholder ?></option>
                <?php foreach ( $pickupPoints as $pickupPointsCategory => $pickupPointsInCategory ) {
                    echo '<optgroup label="' . $pickupPointsCategory . '">';
                        foreach ( $pickupPointsInCategory as $pickupPointOption ) {
                            echo '<option value="' . $pickupPointOption->uuid . '">';
                            echo $pickupPointOption->name;
                            if ( $montonio_shipping_include_address === 'yes' && ! empty( $pickupPointOption->address ) ) {
                                echo ' - ' . $pickupPointOption->address;
                            }
                            echo '</option>';
                        }
                    echo '</optgroup>';
                } ?>
            </select>
        </div>
        <br />
    </td>
</tr>
