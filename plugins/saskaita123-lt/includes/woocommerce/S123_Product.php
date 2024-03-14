<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 *
 * Class Description: Detect when user has purchased product
 */

namespace S123\Includes\Woocommerce;

use S123\Includes\Base\S123_Options;
use S123\Includes\Requests\S123_ApiRequest;

if (!defined('ABSPATH')) exit;

class S123_Product
{
    use S123_Options;

    /**
     * API request object
     *
     */
    private $apiRequest;

    /**
     * Order status when to generate invoice
     *
     */
    private $orderStatus;

    public function __construct(S123_ApiRequest $api = null)
    {
        $this->apiRequest = $api ?: new S123_ApiRequest();
        $this->orderStatus = $this->s123_get_option('use_order_status');
    }

    public function s123_register()
    {
        add_action('woocommerce_order_status_changed', array($this, 's123_createInvoice'), 99, 3);
    }

    /*
    * Create invoices after order status change
    */
    public function s123_createInvoice($order_id, $old_status, $new_status)
    {
        if (!$order_id) {
            return null;
        }

        if ($new_status === $this->orderStatus || $new_status === 'completed' && empty($this->orderStatus)) {
            // Get an instance of the WC_Order object
            $order = wc_get_order($order_id);
            $invoiceObj = new S123_Invoice($order, $this->apiRequest);

            $invoice = $invoiceObj->s123_buildInvoice();
            if ($invoice === null) {
                return null;
            }

            // if: create invoice
            // else if: update invoice
            if (!$order->get_meta('_invoice_generated')) {
                $response = $this->apiRequest->s123_makeRequest($this->apiRequest->getApiUrl('invoice'), $invoice, 'POST');

                // Flag the action as done
                $this->processStoreResponse($order, $response);
            } else if ($order->get_meta('_generated_invoice_id')) {
                $invoiceId = $order->get_meta('_generated_invoice_id');

                $response = $this->apiRequest->s123_makeRequest($this->apiRequest->getApiUrl('invoice') . '/' . $invoiceId, $invoice, 'PATCH');

                $this->processUpdateResponse($order, $invoice, $response);
            }

            return $invoice;
        } else {
            return null;
        }
    }

    private function processStoreResponse($order, $response)
    {
        // Flag the action as done
        if ($response['code'] === 200) {
            $order->update_meta_data('_invoice_generated', true);
            // save generated id for updating invoice
            $order->update_meta_data('_generated_invoice_id', $response['body']['data']['id']);
            $order->add_order_note(__('Invoice was generated at app.invoice123.com', 's123-invoices'), false, true);
            $order->save();
        } else {
            $message = $this->errorMessage($response);
            $order->add_order_note($message);
        }
    }

    public function processUpdateResponse($order, $invoice, $response)
    {
        if ($response['code'] === 200) {
            $order->add_order_note(__('Invoice was updated at app.invoice123.com', 's123-invoices'), false, true);
            $order->save();
        } elseif ($response['code'] === 404) {
            // if invoice not found while trying to update it, create a new one
            $order->delete_meta_data('_generated_invoice_id');
            $order->save();

            $response = $this->apiRequest->s123_makeRequest($this->apiRequest->getApiUrl('invoice'), $invoice, 'POST');

            $this->processStoreResponse($order, $response);
        } else {
            $message = $this->errorMessage($response);
            $order->add_order_note($message);
        }
    }

    /*
    * Format error message for order notes
    */
    private function errorMessage($response): string
    {
        $string = __('If you see this message, your invoice has not been generated, you can send this message to Invoice123 support', 's123-invoices') . '. ';
        $errorMessage = $response['body']['error'];

        if ($errorMessage) {
            $string .= 'Error message: ' . $errorMessage['message'] . ' ';

            if ($errorMessage['errors']) {
                foreach ($errorMessage['errors'] as $key => $error) {
                    $string .= $key . ' => ' . json_encode($error, JSON_UNESCAPED_UNICODE) . ' ';
                }
            }
        } else {
            $string .= 'Error code: ' . $response['code'];
        }

        return $string;
    }
}