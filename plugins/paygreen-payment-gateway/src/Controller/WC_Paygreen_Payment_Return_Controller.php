<?php

namespace Paygreen\Module\Controller;

use Paygreen\Module\Exception\WC_Paygreen_Payment_Exception;
use Paygreen\Module\Helper\WC_Paygreen_Payment_Order_Helper;
use Paygreen\Module\Helper\WC_Paygreen_Payment_Payment_Order_Helper;
use Paygreen\Module\WC_Paygreen_Payment_Gateway;
use Paygreen\Module\WC_Paygreen_Payment_Logger;
use WC_Order;
use WP_Error;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WC_Paygreen_Payment_Return_Controller class.
 *
 * Handles in-checkout AJAX calls, related to Payment Orders.
 */
class WC_Paygreen_Payment_Return_Controller
{
    public function __construct()
    {
        add_action('wc_ajax_wc_paygreen_payment_return_controller', [$this, 'process']);
    }

    /**
     * Handle payment return validation.
     *
     * @return void
     * @throws WC_Paygreen_Payment_Exception
     */
    public function process()
    {
        try {
            WC_Paygreen_Payment_Logger::info('WC_Paygreen_Payment_Return_Controller::process - start');

            if (!isset($_GET['po_id'])) {
                throw new WC_Paygreen_Payment_Exception(
                    'WC_Paygreen_Payment_Return_Controller::process - Missing mandatory payment order id.',
                    __('missing-mandatory-payment-order-id', 'paygreen-payment-gateway'),
                    'missing-mandatory-payment-order-id'
                );
            }

            WC_Paygreen_Payment_Logger::info('WC_Paygreen_Payment_Return_Controller::process - ' . $_GET['po_id'] . ' - processing');

            if (!isset($_GET['nonce']) || !wp_verify_nonce(sanitize_key($_GET['nonce']), 'wc_paygreen_payment_return_controller')) {
                throw new WC_Paygreen_Payment_Exception(
                    'WC_Paygreen_Payment_Return_Controller::process - Missing nonce, CSRF token validation has failed.',
                    __('csrf-verification-failed', 'paygreen-payment-gateway'),
                    'csrf-verification-failed'
                );
            }

            if (isset($_GET['order_id']) && absint($_GET['order_id'])) {
                $order_id = absint($_GET['order_id']);
            } else {
                $order_id = false;
            }

            $wc_order = wc_get_order($order_id);

            if (!$wc_order) {
                throw new WC_Paygreen_Payment_Exception(
                    'WC_Paygreen_Payment_Return_Controller::process - Order not found for id : ' . $order_id,
                    __('invalid-woocommerce-order-id', 'paygreen-payment-gateway'),
                    'invalid-woocommerce-order-id'
                );
            }

            // Set order status
            $this->handle_order($wc_order);

            // Redirect customer to thank you page
            $redirect_url = esc_url_raw(wp_unslash($_GET['redirect_to']));
            wp_safe_redirect($redirect_url);

            WC_Paygreen_Payment_Logger::info('WC_Paygreen_Payment_Return_Controller::process - ' . $_GET['po_id'] . ' - end');

            exit();
        } catch (WC_Paygreen_Payment_Exception $exception) {
            WC_Paygreen_Payment_Logger::error($exception->getMessage());

            // Redirect customer to failure page
            $failure_url = esc_url_raw(wp_unslash($this->get_failure_url($exception->get_localized_message_id())));

            wp_safe_redirect($failure_url);

            exit();
        }
    }

    /**
     * Executed between the "Checkout" and "Thank you" pages, this
     * method updates orders based on the status of associated payment order.
     *
     * @param WC_Order $wc_order The order which is in a transitional state.
     * @return void|WP_Error
     * @throws \Exception
     * @since 0.0.0
     */
    public function handle_order($wc_order) {
        $payment_method = $wc_order->get_payment_method();

        if (strpos($payment_method, WC_Paygreen_Payment_Gateway::ID) !== 0) {
            // If not a paygreen payment method, a payment order would not be available
            throw new WC_Paygreen_Payment_Exception(
                'WC_Paygreen_Payment_Return_Controller::handle_order - Invalid payment method : ' . $payment_method,
                __('invalid-payment-method', 'paygreen-payment-gateway'),
                'invalid-payment-method'
            );
        }

        $payment_order_id = $wc_order->get_meta('_paygreen_payment_order_id');

        if ($payment_order_id) {
            $payment_order_status = WC_Paygreen_Payment_Payment_Order_Helper::get_payment_order_status($payment_order_id);

            if (!$payment_order_status) {
                throw new WC_Paygreen_Payment_Exception(
                    'WC_Paygreen_Payment_Return_Controller::handle_order - ' . $payment_order_id . ' - Payment order status validation has failed',
                    __('payment-order-not-found', 'paygreen-payment-gateway'),
                    'payment-order-not-found'
                );
            }

            if (!in_array($payment_order_status, ['authorized', 'successed', 'error'])) {
                throw new WC_Paygreen_Payment_Exception(
                    'WC_Paygreen_Payment_Return_Controller::handle_order - ' . $payment_order_id . ' - Invalid payment order status : ' . $payment_order_status,
                    __('payment-order-invalid-status', 'paygreen-payment-gateway'),
                    'payment-order-invalid-status'
                );
            }

            // A webhook might have modified the order while the intent was retrieved. This ensures we are reading the right status.
            clean_post_cache($wc_order->get_id());
            $wc_order = wc_get_order($wc_order->get_id());

            if (!$wc_order->has_status('pending') || $wc_order->has_status('failed')) {
                // If the order is not in a pending or failed state, we don't need to do anything.
                WC_Paygreen_Payment_Logger::debug('WC_Paygreen_Payment_Return_Controller::handle_order - ' . $payment_order_id . ' - If the order is not in a pending or failed state, we do not need to do anything. Status : ' . $payment_order_status);

                return;
            }

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
                case 'error':
                    if (!WC_Paygreen_Payment_Order_Helper::isOrderPaid($wc_order)
                        && !WC_Paygreen_Payment_Order_Helper::isOrderCancel($wc_order)
                        && !WC_Paygreen_Payment_Order_Helper::isOrderError($wc_order)
                    ) {
                        $wc_order->update_status('failed', __('Payment error', 'paygreen-payment-gateway'));
                    }

                    break;
            }

            $wc_order->add_order_note(
                sprintf(
                /* translators: $1%s payment order status */
                    __('Paygreen fetch new status (%1$s)', 'paygreen-payment-gateway'),
                    $payment_order_status
                )
            );
        } else {
            throw new WC_Paygreen_Payment_Exception(
                'WC_Paygreen_Payment_Return_Controller::handle_order - Payment order id not found for order id : ' . $wc_order->get_id(),
                __('payment-order-id-not-found-in-order', 'paygreen-payment-gateway'),
                'payment-order-id-not-found-in-order'
            );
        }
    }

    /**
     * @param string $message_id
     * @return string
     */
    private function get_failure_url($message_id)
    {
        return add_query_arg(
            [
                'pgaction' => 'wc_paygreen_payment_failure',
                'nonce' => wp_create_nonce('wc_paygreen_payment_failure'),
                'message_id' => $message_id,
            ],
            get_home_url()
        );
    }
}