<?php
/**
 * Plugin Name: Payment Gateway for Alipay, WeChat Pay and UnionPay (支付宝, 微信支付, 银联支付北美版)
 * Plugin URI: https://www.snappay.ca/ecommerce-payment-solutions/
 * Description: Easily accept Wechat Pay and Alipay in North America using SnapPay WooCommerce payment gateway. Customer pay in Chinese RMB and merchants receive Canadian dollars ($CAD) or US dollars ($USD).
 * Version: 2.3.3
 * Tested up to: 6.1
 * Author: SnapPay Inc.
 * Author URI: http://www.snappay.ca
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: snappay
 */

if (! defined ( 'ABSPATH' )){
	exit (); // Exit if accessed directly
}

define('C_WC_SNAPPAY_ID','wcsnappaygateway');
define('C_WC_SNAPPAY_DIR',rtrim(plugin_dir_path(__FILE__),'/'));
define('C_WC_SNAPPAY_URL',rtrim(plugin_dir_url(__FILE__),'/'));
define('C_WC_SNAPPAY_OPENAPI_HOST','https://open.snappay.ca/api/gateway');

function wc_snappay_log($message) {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}

add_action( 'plugins_loaded', 'wc_snappay_gateway_init' );
function wc_snappay_gateway_init() {
    if( !class_exists('WC_Payment_Gateway') ){
        return;
    } 
    require_once( plugin_basename( 'class-wc-snappay-gateway.php' ) );
    require_once( plugin_basename( 'snappay-sign-utils.php' ) );
    add_filter('woocommerce_payment_gateways', 'wc_snappay_add_gateway' ); //将我们的PHP类注册为WooCommerce支付网关
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_snappay_plugin_edit_link' );
}

function wc_snappay_add_gateway( $methods ) {
    $methods[] = 'WC_Snappay_Gateway';
    return $methods;
}

function wc_snappay_plugin_edit_link( $links ){
    return array_merge(
        array(
            'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section='.C_WC_SNAPPAY_ID) . '">'.__( 'Settings', 'snappay' ).'</a>'
        ),
        $links
    );
}

//Set hearbeat to check order status for WeChat pay
add_action( 'init', 'wc_snappay_init_heartbeat' );
function wc_snappay_init_heartbeat(){
    wp_enqueue_script('heartbeat');
}

add_filter( 'heartbeat_settings', 'wc_snappay_setting_heartbeat' );
function wc_snappay_setting_heartbeat( $settings ) {
    $settings['interval'] = 5;
    return $settings;
}

add_filter('heartbeat_received', 'wc_snappay_heartbeat_received', 10, 2);
add_filter('heartbeat_nopriv_received', 'wc_snappay_heartbeat_received', 10, 2 );
function wc_snappay_heartbeat_received($response, $data){
    if(!isset($data['orderId'])){
        return;
    }

    wc_snappay_log("wc_snappay_heartbeat_received.orderId: " . $data['orderId']);
	$gateway = new WC_Snappay_Gateway();
	$isCompleted = $gateway->is_order_completed($data['orderId']);
	wc_snappay_log("wc_snappay_heartbeat_received.isCompleted: " . $isCompleted);

    if($isCompleted){
        $response['status'] = 'SUCCESS';
    }

    return $response;
}

//Show pay type in edit order page for admin.
add_action( 'woocommerce_admin_order_data_after_billing_address', 'wc_snappay_custom_display_admin', 10, 1 );
function wc_snappay_custom_display_admin($order){
    $method = get_post_meta( $order->get_id(), '_payment_method', true );
    if($method != C_WC_SNAPPAY_ID){
        return;
    }
    $payType = get_post_meta( $order->get_id(), 'payType', true );
    $snappayOrderId = get_post_meta( $order->get_id(), 'snappayOrderId', true );
    echo '<p><strong>'.__( 'Pay Type' ).':</strong> ' . $payType . '</p>';
    echo '<p><strong>'.__( 'SnapPay Order Id' ).':</strong> ' . $snappayOrderId . '</p>';
}

?>