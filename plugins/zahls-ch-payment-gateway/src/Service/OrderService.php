<?php

namespace ZahlsPaymentGateway\Service;

use Zahls\Models\Response\Transaction;

class OrderService
{
    /**
     * @param Transaction $zahlsTransaction
     */
    public function handleTransactionStatus($order, $subscriptions, $zahlsTransaction, $newTransactionStatus, $preAuthId) {
        switch ($newTransactionStatus) {
            case \Zahls\Models\Response\Transaction::WAITING:
                $newTransactionsStatus = 'on-hold';
                $newTransactionsStatus = $this->getCustomTransactionStatus($newTransactionsStatus);

                if ($order->get_status() === $newTransactionsStatus) {
                    break;
                }

                $order->update_status($newTransactionsStatus, __('Awaiting payment', 'wc-zahls-gateway'));
                break;
            case \Zahls\Models\Response\Transaction::CONFIRMED:
                $this->setOrderPaid($order, $zahlsTransaction->getPayment()['brand']);
                break;
            case \Zahls\Models\Response\Transaction::AUTHORIZED:
                foreach ($subscriptions as $subscription) {
                    $subscription->update_meta_data('zahls_auth_transaction_id', $preAuthId);
                    $subscription->update_meta_data('zahls_payment_method', $zahlsTransaction->getPayment()['brand']);
                    $subscription->save();
                }

                // An order with amount 0 is considered as paid if the authorization is successful
				if ((int)$order->get_total('edit') === 0) {
                    $this->setOrderPaid($order, $zahlsTransaction->getPayment()['brand']);
                }
                break;
            case \Zahls\Models\Response\Transaction::REFUNDED:
				if ($zahlsTransaction->getAmount())
                $newTransactionsStatus = 'refunded';				
                $newTransactionsStatus = $this->getCustomTransactionStatus($newTransactionsStatus);
                if ($order->get_status() === $newTransactionsStatus) {
                    break;
                }
                $order->update_status($newTransactionsStatus, __('Payment was fully refunded', 'wc-zahls-gateway'));
                break;
//                    case \Zahls\Models\Response\Transaction::PARTIALLY_REFUNDED:
//                        if ($order->get_status() === 'refunded') {
//                            break;
//                        }
//                        $order->update_status('refunded', __('Payment was partially refunded', 'wc-zahls-gateway'));
//                        break;
            case \Zahls\Models\Response\Transaction::CANCELLED:
            case \Zahls\Models\Response\Transaction::EXPIRED:
            case \Zahls\Models\Response\Transaction::DECLINED:
                $newTransactionsStatus = 'cancelled';
                $newTransactionsStatus = $this->getCustomTransactionStatus($newTransactionsStatus);

                if ($order->has_status(['processing', 'completed', $newTransactionsStatus])) {
                    break;
                }

                $order->update_status($newTransactionsStatus, __('Payment was cancelled by the customer', 'wc-zahls-gateway'));
                break;
            case \Zahls\Models\Response\Transaction::ERROR:
                $newTransactionsStatus = 'failed';
                $newTransactionsStatus = $this->getCustomTransactionStatus($newTransactionsStatus);
                if ($order->has_status(['processing', 'completed', $newTransactionsStatus])) {
                    break;
                }
                $order->update_status($newTransactionsStatus, __('An error occured while processing this payment', 'wc-zahls-gateway'));
                break;
        }
    }

    private function setOrderPaid($order, $paymentBrand) {
        if ($order->has_status(['processing', 'completed'])) {
            return;
        }
        $order->update_meta_data('zahls_payment_method', $paymentBrand);
        $order->payment_complete();
        $order->save();
        // Remove cart
        WC()->cart->empty_cart();
    }

    private function getCustomTransactionStatus($status) {
        return apply_filters('woo_zahls_custom_transaction_status_' . $status, $status);
    }
}