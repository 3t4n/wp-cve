<?php

namespace Paygreen\Module\Helper;

use WC_Order;

if (!defined('ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Order_Helper
{
    /**
     * @param WC_Order $wc_order
     *
     * @return bool
     */
    public static function isOrderPaid($wc_order)
    {
        return $wc_order->is_paid();
    }

    /**
     * @param WC_Order $wc_order
     *
     * @return bool
     */
    public static function isOrderError($wc_order)
    {
        return $wc_order->has_status('failed');
    }

    /**
     * @param WC_Order $wc_order
     *
     * @return bool
     */
    public static function isOrderRefunded($wc_order)
    {
        return $wc_order->has_status('refunded');
    }

    /**
     * @param WC_Order $wc_order
     *
     * @return bool
     */
    public static function isOrderCancel($wc_order)
    {
        return $wc_order->has_status('cancelled');
    }
}