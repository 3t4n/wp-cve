<?php

namespace Paygreen\Module\Subscriber;

use Paygreen\Module\Helper\WC_Paygreen_Payment_Order_Helper;
use Paygreen\Module\Helper\WC_Paygreen_Payment_Payment_Order_Helper;
use Paygreen\Module\WC_Paygreen_Payment_Logger;

if (!defined( 'ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Payment_Order_Subscriber
{
    /**
     * @return array[]
     */
    public static function get_subscribed_events()
    {
        return [
            'payment_order.authorized' => ['handle_payment_order'],
            'payment_order.expired' => ['handle_payment_order'],
            'payment_order.canceled' => ['handle_payment_order'],
            'payment_order.refused' => ['handle_payment_order'],
            'payment_order.error' => ['handle_payment_order'],
            'payment_order.refunded' => ['handle_payment_order'],
        ];
    }

    /**
     * @param array $notification
     * @return int
     */
    public static function handle_payment_order(array $notification)
    {
        try {
            $payment_order_id = $notification['id'];
            $payment_order_status = WC_Paygreen_Payment_Payment_Order_Helper::get_payment_order_status($payment_order_id);

            if (!$payment_order_status) {
                return 200;
            }

            // Lignes ci-dessous à supprimer sur la prochaine version
            $order_id = (int) WC_Paygreen_Payment_Payment_Order_Helper::get_order_id_from_reference($notification['reference']);
            $wc_order = wc_get_order($order_id);
            // Ligne ci-dessous à décommenter sur la prochaine version
            //$wc_order = WC_Paygreen_Payment_Payment_Order_Helper::get_order_from_metadata($payment_order_id, $notification['metadata']);

            if (!$wc_order) {
                WC_Paygreen_Payment_Logger::warning('WC_Paygreen_Payment_Payment_Order_Subscriber:handle_payment_order - Order not found for payment order id : ' . $payment_order_id);

                return 'OK';
            }

            // A webhook might have modified the order while the intent was retrieved. This ensures we are reading the right status.
            clean_post_cache($wc_order->get_id());
            $wc_order = wc_get_order($wc_order->get_id());

            switch ($payment_order_status) {
                case 'authorized':
                case 'successed':
                    WC()->cart->empty_cart();

                    if (!WC_Paygreen_Payment_Order_Helper::isOrderPaid($wc_order)
                        && !WC_Paygreen_Payment_Order_Helper::isOrderCancel($wc_order)
                    ) {
                        $wc_order->payment_complete($payment_order_id);
                    }

                    break;
                case 'canceled':
                case 'expired':
                    WC()->cart->empty_cart();

                    if (!WC_Paygreen_Payment_Order_Helper::isOrderPaid($wc_order)
                        && !WC_Paygreen_Payment_Order_Helper::isOrderCancel($wc_order)
                    ) {
                        $wc_order->update_status('cancelled', __('Payment canceled', 'paygreen-payment-gateway'));
                    }

                    break;
                case 'refunded':
                    if (!WC_Paygreen_Payment_Order_Helper::isOrderRefunded($wc_order)) {
                        $wc_order->update_status('refunded', __('Payment refunded', 'paygreen-payment-gateway'));
                    }

                    break;
                case 'error':
                    if (!WC_Paygreen_Payment_Order_Helper::isOrderPaid($wc_order)
                        && !WC_Paygreen_Payment_Order_Helper::isOrderCancel($wc_order)
                        && !WC_Paygreen_Payment_Order_Helper::isOrderError($wc_order)
                    ) {
                        $wc_order->update_status('failed', __('Payment failed', 'paygreen-payment-gateway'));
                    }

                    break;
            }
        } catch (\Exception $exception) {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Subscriber::handle_payment_order - Exception - ' . preg_replace("/\n/", '<br>', (string) $exception->getMessage() . '<br>' . $exception->getTraceAsString()));

            return 400;
        }

        return 200;
    }
}