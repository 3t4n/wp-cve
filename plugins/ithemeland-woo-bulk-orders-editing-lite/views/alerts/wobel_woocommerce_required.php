<?php
function wobel_woocommerce_required_error()
{
    $class = 'notice notice-error';
    $message = esc_html__('"iThemeland WooCommerce Bulk Orders Editing Lite" Plugin needs "WooCommerce" Plugin, Please Install/Activate that.');
    printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
}

add_action('admin_notices', 'wobel_woocommerce_required_error');
