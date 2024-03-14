<?php

/**
 * compatibility message for pro version support for 
 * https://wordpress.org/plugins/woocommerce-product-price-based-on-countries/
 */
/**
 * this message will run when the customer is using our free version and this above plugin
 */
function pi_compatibility_message() {
    if(is_plugin_active( 'weight-based-shipping-for-woocommerce/plugin.php')){
    ?>
    <div class="alert alert-primary my-3">
    <h3>Alert: Weight based shipping method plugin</h3> 
    <p>Is not supported by the free version of Estimate delivery date plugin, Free version will not show an estimate date when user select Weight based shipping method</p>
    <h3>Use Pro version of Estimate shipping date plugin to show Estimate date when user select Weight based shipping method</h3> 
    <a style="font-size:16px; display:inline-block; padding:10px; border:1px solid #46b450; text-decoration:none; margin-right:15px; margin-top:10px;" target="_blank" href="<?php echo PI_EDD_BUY_URL; ?>"> Buy Pro version now</a>
    </div>
    <?php
    }
}
add_action( 'piso_edd_compatible_shipping_method', 'pi_compatibility_message' );