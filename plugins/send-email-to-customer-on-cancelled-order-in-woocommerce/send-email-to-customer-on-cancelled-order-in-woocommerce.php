<?php
/*
 * Plugin Name: Send email to customer on cancelled order in WooCommerce
 * Plugin URI: https://bobysuh.com
 * Description: Sending email to customer on cancelled order in WooCommerce
 * Author: Laura Díaz
 * Version: 1.1
 * Author URI: https://diariodeunafriki.com
 * Text Domain: send-email-to-customer-on-cancelled-order-in-woocommerce
 * Domain Path: /languages
 * WC requires at least: 3.0
 * WC tested up to: 3.6.2
 * License: GPLv2+
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function send_email_to_customer_on_cancelled_order_in_woocommerce() {
    load_plugin_textdomain( 'send-email-to-customer-on-cancelled-order-in-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'send_email_to_customer_on_cancelled_order_in_woocommerce' );

add_action('woocommerce_order_status_changed', 'seccow_send_email', 10, 4 );
function seccow_send_email( $order_id, $old_status, $new_status, $order ){
    if ( $new_status == 'cancelled' || $new_status == 'failed' ){
        $wc_emails = WC()->mailer()->get_emails(); // Obtener todas las instancias de WC_emails
        $email_cliente = $order->get_billing_email(); // Email del cliente
    }

    if ( $new_status == 'cancelled' ) {
        // Cambiar el destinatario de la instancia
        $wc_emails['WC_Email_Cancelled_Order']->recipient .= ',' . $email_cliente;
        // Enviar email desde la instancia
        $wc_emails['WC_Email_Cancelled_Order']->trigger( $order_id );
    } 
    elseif ( $new_status == 'failed' ) {
        // Cambiar el destinatario de la instancia
        $wc_emails['WC_Email_Failed_Order']->recipient .= ',' . $email_cliente;
        // Enviar email desde la instancia
        $wc_emails['WC_Email_Failed_Order']->trigger( $order_id );
    } 
}

?>