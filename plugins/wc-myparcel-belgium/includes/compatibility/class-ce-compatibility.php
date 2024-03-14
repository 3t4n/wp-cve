<?php

namespace WPO\WC\MyParcelBE\Compatibility;

use WC_Order;
use WPO\WC\MyParcelBE\Compatibility\Order as WCX_Order;

/**
 * Class for compatibility with the ChannelEngine plugin.
 *
 * @see     https://wordpress.org/plugins/channelengine-woocommerce
 * @see     https://github.com/channelengine/woocommerce
 * @package WPO\WC\MyParcelBE\Compatibility
 */
class WCMPBE_ChannelEngine_Compatibility
{
    /**
     * Add the created Track & Trace code and set shipping method to bpost in ChannelEngine's meta data
     *
     * @param WC_Order $order
     * @param          $data
     */
    public static function updateMetaOnExport(WC_Order $order, $data)
    {
        if (! class_exists('Channel_Engine')) {
            return;
        }

        try {
            if (WCX_Order::get_meta($order, '_shipping_ce_track_and_trace')) {
                return;
            }
        } catch (\Exception $e) { }

        WCX_Order::update_meta_data($order, '_shipping_ce_track_and_trace', $data);

        $deliveryOptions = json_decode($order->get_meta('_myparcelbe_delivery_options'), true);
        $carrierName     = ($deliveryOptions) ? $deliveryOptions['carrier'] ?? 'bpost' : 'bpost';

        if ('postnl' === $carrierName) {
            WCX_Order::update_meta_data($order, '_shipping_ce_shipping_method', 'PostNL');
            WCX_Order::update_meta_data($order, '_shipping_ce_shipping_method_other', '');

            return;
        }

        WCX_Order::update_meta_data($order, '_shipping_ce_shipping_method', 'Other');
        WCX_Order::update_meta_data($order, '_shipping_ce_shipping_method_other', $carrierName);
    }
}
