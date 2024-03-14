<?php

require_once dirname(__DIR__). '/functions/common.php';
require_once dirname(__FILE__). '/Shipday_Order_Management.php';

class Woo_Sync_Order
{
    public static function init(){
        if (!get_shipday_sync_status()) return;
        reset_shipday_sync_status();
        add_action('woocommerce_after_register_post_type', __CLASS__.'::sync');
    }
    public static function get_processing_orders() {
        $query = new WC_Order_Query( array(
            'status' => array('wc-processing'),
            'orderby' => 'date',
            'return' => 'ids',
        ) );
        return $query->get_orders();
    }

    public static function get_processing_orders_from_db() {
        global $wpdb;
        $orders = $wpdb->get_results(
            "SELECT order_id FROM {$wpdb->prefix}wc_order_stats ".
            "WHERE status='wc-processing'"
        );
        $ids = [];
        foreach ($orders as $order) {
            $ids[] = intval($order->order_id);
        }
        return $ids;
    }

    public static function sync(){
        $orders = self::get_processing_orders_from_db();
        foreach ( $orders as $order_id) {
            Shipday_Order_Management::process_and_send($order_id);
        }
    }

}
