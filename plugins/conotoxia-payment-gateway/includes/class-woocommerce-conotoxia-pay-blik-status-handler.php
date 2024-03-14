<?php

use CKPL\Pay\Model\Response\PaymentStatusResponseModel;
use WC_Gateway_Conotoxia_Pay_Logger as Logger;

class WC_Gateway_Conotoxia_Pay_Blik_Status_Handler
{

    /**
     * @return void
     */
    public function initialize(): void
    {
        add_action('wp_ajax_cx_check_blik_status', [$this, 'check_status']);
        add_action('wp_ajax_nopriv_cx_check_blik_status', [$this, 'check_status']);
    }

    /**
     * @return void
     */
    public function check_status(): void
    {
        if (empty($_POST['orderKey'])) {
            Logger::log('Could not receive order key when getting BLIK payment status.');
            $this->respond_with_problem();
            exit();
        }
        if (empty($_POST['orderId'])) {
            Logger::log('Could not receive order id when getting BLIK payment status.');
            $this->respond_with_problem();
            exit();
        }
        $payment_id = $this->resolve_payment_id(
            sanitize_text_field($_POST['orderId']),
            sanitize_text_field($_POST['orderKey'])
        );
        if (!$payment_id) {
            Logger::log('Could not resolve payment id when getting BLIK payment status.');
            $this->respond_with_problem();
            exit();
        }
        $gateway = new WC_Gateway_Conotoxia_Pay();
        try {
            $sdk = $gateway->initialize_conotoxia_pay();
            $payment_status_response = $sdk->payments()->getPaymentStatus($payment_id);
            $this->respond_with_status($this->resolve_status($payment_status_response));
        } catch (Exception $exception) {
            Logger::log(
                'Problem with getting status for payment with id %s: %s trace: %s',
                $payment_id,
                $exception->getMessage(),
                $exception->getTraceAsString()
            );
            $this->respond_with_problem();
        } finally {
            exit();
        }
    }

    /**
     * @param PaymentStatusResponseModel $payment_status_response
     * @return string
     */
    private function resolve_status(PaymentStatusResponseModel $payment_status_response): string
    {
        if ($payment_status_response->isConfirmed()) {
            return 'SUCCESS';
        } elseif (in_array(
            $payment_status_response->getStatus(),
            ['WAITING_FOR_NOTIFICATION', 'INITIATED', 'AUTHORIZATION_REQUESTED']
        )) {
            return 'WAITING';
        } else {
            return 'ERROR';
        }
    }

    /**
     * @param string $order_id
     * @param string $order_key
     * @return false|string
     */
    private function resolve_payment_id(string $order_id, string $order_key)
    {
        $order = wc_get_order(wc_get_order_id_by_order_key($order_key));
        return $this->is_valid_order($order, $order_id) ? $order->get_transaction_id() : false;
    }

    /**
     * @param bool|WC_Order|WC_Order_Refund $order
     * @param string $order_id
     * @return bool
     */
    private function is_valid_order($order, string $order_id): bool
    {
        if (!$order) {
            Logger::log('Could not find order when getting BLIK payment status.');
            return false;
        }
        if ($order->get_order_number() !== $order_id) {
            Logger::log(
                'Received invalid order id \'%s\' when getting BLIK payment status.',
                $order_id
            );
            return false;
        }
        if ($order->get_payment_method() !== Identifier::CONOTOXIA_PAY_BLIK) {
            Logger::log(
                'Order with id \'%s\' has invalid payment method \'%s\' when getting BLIK payment status.',
                $order->get_order_number(),
                $order->get_payment_method()
            );
            return false;
        }
        $payment_id = $order->get_transaction_id();
        if (!$payment_id || strpos($payment_id, 'PAY') !== 0) {
            Logger::log(
                'Order with id \'%s\' has invalid transaction id \'%s\'.',
                $order->get_order_number(),
                $payment_id
            );
            return false;
        }
        return true;
    }

    /**
     * @param string $status
     * @return void
     */
    private function respond_with_status(string $status): void
    {
        header('Content-Type: application/json');
        echo wp_json_encode(['status' => $status]);
    }

    /**
     * @return void
     */
    private function respond_with_problem(): void
    {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/problem+json');
        echo wp_json_encode([
            'title' => 'Bad Request',
            'status' => 400
        ]);
    }
}
