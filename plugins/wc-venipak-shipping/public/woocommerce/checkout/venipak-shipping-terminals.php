<?php
/**
 * List of terminals
 */
?>

<tr class="wc-venipak-shipping-terminals">
    <td colspan="2">
        <div>
            <select name="venipak_pickup_point" id="venipak_pickup_point" class="venipak_pickup_point">
                <option value=""><?php _e( 'Select pickup point', 'woocommerce-shopup-venipak-shipping' ); ?></option>
            </select>
        </div>
        <div style="clear: both; padding-top: 15px; text-align: right;" id="selected-pickup-info"></div>
        <div id="venipak-map" style="display: none; height: 300px;"></div>
    </td>
    <script>
        window.venipakShipping.init();
    </script>
</tr>
