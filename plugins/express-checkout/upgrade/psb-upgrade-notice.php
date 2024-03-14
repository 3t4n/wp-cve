<?php
$psb_path = 'smart-paypal-checkout-for-woocommerce/smart-paypal-checkout-for-woocommerce.php';
$psb_install_link = wp_nonce_url(
        add_query_arg(
                array(
                    'action' => 'install-plugin',
                    'plugin' => dirname($psb_path),
                ),
                admin_url('update.php')
        ),
        'install-plugin_' . dirname($psb_path)
);

$psb_activate_link = wp_nonce_url(
        add_query_arg(
                array(
                    'action' => 'activate',
                    'plugin' => $psb_path,
                ),
                admin_url('plugins.php')
        ),
        'activate-plugin_' . $psb_path
);
?>

<tr class="plugin-update-tr active notice-warning notice-alt"  id="pec-migrate-notice" data-dismiss-nonce="<?php echo esc_attr(wp_create_nonce('pec-upgrade-notice-dismiss')); ?>">
    <td colspan="4" class="plugin-update colspanchange">
        <div class="notice notice-error inline update-message notice-alt is-dismissible">
            <div class='pec-notice-title pec-notice-section'>
                <p><strong>Action Required: Switch to Smart PayPal Checkout For WooCommerce</strong></p>
            </div>
            <div class='pec-notice-content pec-notice-section'>
                <p>PayPal highly recommend upgrading to <a href="https://wordpress.org/plugins/smart-paypal-checkout-for-woocommerce/" target="_blank">Smart PayPal Checkout For WooCommerce</a>, the latest, fully supported extension that includes all of the features of PayPal Checkout and more.</p>
            </div>
            <div class='pec-notice-buttons pec-notice-section hidden'>

                <a id="pec-install-paypal-payments" href="<?php echo $psb_install_link; ?>" class="button button-primary">Upgrade to Smart PayPal Checkout For WooCommerce now</a>

                <a id="pec-activate-paypal-payments" href="<?php echo $psb_activate_link; ?>" class="button button-primary">Activate Smart PayPal Checkout For WooCommerce now</a>
                <a href="https://wordpress.org/plugins/smart-paypal-checkout-for-woocommerce/" target="_blank" class="button woocommerce-save-button">Learn more</a>
            </div>
        </div>
    </td>
</tr>
