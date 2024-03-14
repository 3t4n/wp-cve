<?php

namespace Paygreen\Module\Subscriber;

use Paygreen\Module\Helper\WC_Paygreen_Payment_Order_Helper;
use Paygreen\Module\Helper\WC_Paygreen_Payment_Payment_Order_Helper;
use Paygreen\Module\WC_Paygreen_Payment_Api;
use Paygreen\Module\WC_Paygreen_Payment_Logger;

if (!defined( 'ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Operation_Subscriber
{
    /**
     * @return array[]
     */
    public static function get_subscribed_events()
    {
        return [
            'operation.refund' => ['handle_operation'],
        ];
    }

    /**
     * @param array $notification
     * @return int
     */
    public static function handle_operation(array $notification)
    {
        try {
            $operation_id = $notification['id'];
            $operation_status = $notification['status'];

            $client = WC_Paygreen_Payment_Api::get_paygreen_client();
            $response = $client->getOperation($operation_id);

            if (WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                $operation = json_decode($response->getBody()->getContents(), true)['data'];

                if ($operation_status === 'operation.refund') {
                    $payment_order_id = $operation['payment_order_id'];
                    $response = $client->getPaymentOrder($payment_order_id);

                    if (WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                        $payment_order = json_decode($response->getBody()->getContents(), true)['data'];

                        // Ligne ci-dessous à supprimer sur la prochaine version
                        $order_id = (int) WC_Paygreen_Payment_Payment_Order_Helper::get_order_id_from_reference($payment_order['reference']);
                        $wc_order = wc_get_order($order_id);
                        // Ligne ci-dessous à décommenter sur la prochaine version
                        //$wc_order = WC_Paygreen_Payment_Payment_Order_Helper::get_order_from_metadata($payment_order_id, $payment_order['metadata']);

                        if (!$wc_order) {
                            WC_Paygreen_Payment_Logger::warning('WC_Paygreen_Payment_Webhook_Controller::process_webhook - ' . $payment_order_id . ' - Order not found.');

                            return 200;
                        }

                        // A webhook might have modified the order while the intent was retrieved. This ensures we are reading the right status.
                        clean_post_cache($wc_order->get_id());
                        $wc_order = wc_get_order($wc_order->get_id());

                        if (!WC_Paygreen_Payment_Order_Helper::isOrderRefunded($wc_order)) {
                            $wc_order->add_order_note(
                                sprintf(
                                /* translators: $1%s operation amount */
                                    __( 'Paygreen refund successfully executed. Amount refunded : %1$s', 'paygreen-payment-gateway'),
                                    self::format_amount($notification['amount']) . '€'
                                )
                            );
                        }
                    } else {
                        WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Operation_Subscriber::handle_operation - ' . $payment_order_id . ' - Payment order not found.');
                    }
                } else {
                    WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Operation_Subscriber::handle_operation - ' . $operation_id . ' - Status mismatch : (webhook) ' . $operation_status . ' - (api) ' . $notification['status']);
                }
            } else {
                WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Operation_Subscriber::handle_operation - ' . $operation_id . ' - Operation not found');
            }
        } catch (\Exception $exception) {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Operation_Subscriber::handle_operation - Exception - ' . preg_replace("/\n/", '<br>', (string) $exception->getMessage() . '<br>' . $exception->getTraceAsString()));

            return 400;
        }

        return 200;
    }

    /**
     * @param int $amount
     *
     * @return float
     */
    private static function format_amount($amount)
    {
        return round($amount / 100, 2);
    }
}