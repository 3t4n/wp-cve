<?php

namespace App\Models;

use WC_Customer;
use WC_Order;

class Order
{
    /**
     * Order statusses
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_ON_HOLD = 'on-hold';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_FAILED = 'failed';

    /**
     * Get order
     * @param $id Customer id
     * @return array
     */
    public static function get($id)
    {
        $resp = null;
        $order = wc_get_order($id);

        if (!$order) {
            return null;
        }

        try {
            $resp = self::map($order);
        } catch (\Exception $e) {
            // continue
        }

        return $resp;
    }

    /**
     * Map order
     *
     * @param WC_Order $order
     * @return array
     */
    public static function map($order)
    {
        if (!($order instanceof WC_Order)) {
            throw new \Exception('First argument must be instance of WC_Order');
        }

        $data = $order->get_data();

        $line_items = [];
        foreach ($order->get_items() as $key => $item) {
            $line_items[] = $item->get_data();
        }

        $customer = new WC_Customer($order->get_customer_id());

        $data['line_items'] = $line_items;
        $data['customer'] = $customer->get_data();

        return $data;
    }
}
