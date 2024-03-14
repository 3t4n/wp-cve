<?php

/**
 * Save order details related to the plugin in the DB.
 *
 * Author:          Uriahs Victor
 * Created on:      26/10/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Models
 */
namespace Lpac_DPS\Models;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Models\BaseModel ;
/**
 * Class OrderDetails.
 */
class OrderDetails extends BaseModel
{
    /**
     * Save our delivery data to the DB.
     *
     * @param array $order_data The order data to save.
     * @return void
     * @since 1.0.0
     */
    public function saveOrderData( array $order_data ) : void
    {
        $order_data = apply_filters( 'dps_order_data', $order_data );
        $order_id = $order_data['order_id'];
        $order_type = $order_data['order_type'];
        $date = $order_data['date'];
        $time = $order_data['time'];
        $order = wc_get_order( $order_id );
        $order->update_meta_data( 'lpac_dps_order_type', $order_type );
        if ( !empty($date) ) {
            $order->update_meta_data( "lpac_dps_{$order_type}_date", $date );
        }
        if ( !empty($time) ) {
            $order->update_meta_data( "lpac_dps_{$order_type}_time", $time );
        }
        $order->save();
    }

}