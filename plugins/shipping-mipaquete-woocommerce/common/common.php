<?php
add_action('init', function () {
register_post_status('wc-mi-paquete', array(
    'label' => 'Generar envíos con mipaquete.com (Este proceso tarda entre 30 y 60 segundos, no recargues la página)',
    'public'                    => true,
    'exclude_from_search'       => false,
    'show_in_admin_all_list'    => true,
    'show_in_admin_status_list' => true,
    'label_count' => _n_noop('Envios generados a traves de mipaquete.com <span class="count">(%s)</span>',
                             'Envios generados a través de mipaquete.com <span class="count">(%s)</span>'
                            ),
) );
}, 10 );

// Remove "shipping" section from cart page only
add_filter('woocommerce_cart_needs_shipping', 'filterCartNeedsShipping');
function filterCartNeedsShipping($needsShipping){
    return is_cart() ? false : $needsShipping;
}
function cartCustomShippingMessageRow(){
    if (!WC()->cart->needs_shipping()) :
        $shippingMessage = __("Calcula tu envío al finalizar la compra.", "woocommerce");
        ?>
        <tr class="shipping">
            <th id="th-shipping">
                <?php _e('Shipping', 'woocommerce'); ?>
            </th>
            <td class="message" data-title="<?php esc_attr_e('Shipping', 'woocommerce'); ?>">
                <em>
                    <?php echo $shippingMessage; ?>
                </em>
            </td>
        </tr>
    <?php endif;
}
function displayOrderMipaqueteMetaLocation($order) //$order all info order woocommerce
{
    // get data location
    $locationCode = returnGetLocations($order->get_billing_city());
    $cityMipaquete = $locationCode[0];
    $stateMipaquete = $locationCode[1];
    // compatibility with WC +3
    $orderId = method_exists($order, 'get_id') ? $order->get_id() : $order->id;
    echo '<p><strong>'.__('Ciudad', 'woocommerce').':</strong> ' . $cityMipaquete . '</p>';
    echo '<p><strong>'.__('Departamento', 'woocommerce').':</strong> ' . $stateMipaquete . '</p>';
}

function wpbAdminNoticeWarn(){
    echo '<div class="notice notice-warning is-dismissible">
    <p>Puedes acceder a los tutoriales de MI PAQUETE en el siguiente
    <a href="https://www.mipaquete.com/conecta-tu-tiendavirtual/tienda-en-woocommerce" target="_blank" rel="noopener">link</a></p>
    </div>';
}
add_action('admin_notices', 'wpbAdminNoticeWarn');
add_action('woocommerce_admin_order_data_after_billing_address', 'displayOrderMipaqueteMetaLocation', 10, 1);
// Add a custom shipping message row
add_action('woocommerce_cart_totals_before_order_total', 'cartCustomShippingMessageRow');
add_filter('wc_order_statuses', function ($estados) {
$estados['wc-mi-paquete'] = 'Generar envíos con mipaquete.com
(Este proceso tarda entre 30 y 60 segundos, no recargues la página)';
return $estados;
}, 10, 1);
    
?>
