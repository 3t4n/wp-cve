<?php

require_once dirname( __DIR__ ) . '/functions/common.php';

function get_shipday_pickup_delivery_times(WC_Order $order) {

	if(is_plugin_active('woo-delivery/coderockz-woo-delivery.php') || is_plugin_active('coderockz-woocommerce-delivery-date-time-pro/coderockz-woo-delivery.php')) {
		require_once dirname( __FILE__ ) . '/Coderocks_Woo_Delivery.php';
		$date_picker_object = new Coderocks_Woo_Delivery($order->get_id());
	} else if(is_plugin_active('order-delivery-date-for-woocommerce/order_delivery_date.php') ||
	          is_plugin_active('order-delivery-date/order_delivery_date.php')){
		require_once dirname( __FILE__ ) . '/Order_Delivery_Date_Shipday.php';
		$date_picker_object = new Order_Delivery_Date_Shipday($order->get_id());
	} else if (is_plugin_active('woocommerce-delivery-area-pro/woocommerce-delivery-area-pro.php')) {
        require_once dirname( __FILE__ ) . '/Delivery_Area_Pro.php';
        $date_picker_object = new Delivery_Area_Pro($order->get_id());
    }

	if (!isset($date_picker_object)) return array();

	$times = array();
	if ($date_picker_object->has_delivery_date()) $times["expectedDeliveryDate"] = $date_picker_object->get_delivery_date();
	if ($date_picker_object->has_delivery_time()) $times["expectedDeliveryTime"] = $date_picker_object->get_delivery_time();
	if ($date_picker_object->has_pickup_time())  $times["expectedPickupTime"] = $date_picker_object->get_pickup_time();
	return $times;
}

function get_shipday_datetime_plugins() {
    $plugins = array();
    if (is_plugin_active('coderockz-woocommerce-delivery-date-time-pro/coderockz-woo-delivery.php')) $plugins[] = 'CodeRockz Woo Delivery Pro';
    if (is_plugin_active('woo-delivery/coderockz-woo-delivery.php')) $plugins[] = 'CodeRockz Woo Delivery';
    if (is_plugin_active('order-delivery-date-for-woocommerce/order_delivery_date.php')) $plugins[] = 'Tyche Order Delivery Date';
    if (is_plugin_active('order-delivery-date/order_delivery_date.php')) $plugins[] = 'Tyche Order Delivery Date Pro';
    if (is_plugin_active('woocommerce-delivery-area-pro/woocommerce-delivery-area-pro.php')) $plugins[] = 'WooCommerce Delivery Area Pro';
    return $plugins;
}

function get_shipday_datetime_timezone() {
    if (is_plugin_active('woo-delivery/coderockz-woo-delivery.php')) return (new Coderockz_Woo_Delivery_Helper())->get_the_timezone();
    if (is_plugin_active('order-delivery-date-for-woocommerce/order_delivery_date.php') ||
        is_plugin_active('order-delivery-date/order_delivery_date.php')
    ) return wp_timezone_string();
    return wp_timezone_string();
}

?>