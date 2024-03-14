<?php
namespace BDroppy\Models;

if ( ! defined( 'ABSPATH' ) ) exit;

class Order extends BaseModel
{
    public static $table = 'dropshipping_orders';

    const STATUS_FAILED = 2000;
    const STATUS_NOAVAILABILITY = 2001;
    const STATUS_BOOKED = 5;
    const STATUS_CONFIRMED = 2;
    const STATUS_WORKING_ON = 3001;
    const STATUS_READY = 3002;
    const STATUS_DISPATCHED = 3;

    public static function get2($limit = 0,$offset =0 )
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table;
        $query  = "SELECT * FROM $table_name ";
        if ( $limit > 0 ) {
            $query .= ' LIMIT ' . $limit;
        }
        if ( $offset > 0 ) {
            $query .= ' OFFSET ' . $offset;
        }
        return $wpdb->get_results( $query, OBJECT_K );
    }

    public static function WooCommerceOrderId($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table;
        $query  = "SELECT * FROM $table_name  where wc_order_id = '$id'";

        return $wpdb->get_results( $query, ARRAY_A );
    }


}