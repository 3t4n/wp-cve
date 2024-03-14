<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Transactions;

use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractBusinessRequest;
use GoDaddy\WooCommerce\Poynt\Helpers\MoneyHelper;
use WC_Order;

defined('ABSPATH') or exit;

/**
 * Refund request.
 *
 * @link https://docs.poynt.com/app-integration/poynt-collect/refunds.html#refund-a-sale
 *
 * @since 1.0.0
 */
class RefundRequest extends AbstractBusinessRequest
{
    /**
     * Refund request constructor.
     *
     * @since 1.0.0
     *
     * @param string $businessId the business ID
     */
    public function __construct(string $businessId)
    {
        parent::__construct($businessId);

        $this->path = "{$this->path}/transactions";
    }

    /**
     * Sets the refund request data.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order
     * @param string $transactionId either the refund or the capture transaction ID
     */
    public function setRefundData(WC_Order $order, string $transactionId)
    {
        $totalAmountInCents = MoneyHelper::convertDecimalToCents((float) $order->refund->amount);

        $this->data = [
            'action'   => 'REFUND',
            'parentId' => $transactionId,
            'id'       => wp_generate_uuid4(),
            'context'  => [
                'businessId' => $this->businessId,
                'sourceApp'  => $this->getContextSourceApp(),
            ],
            'fundingSource' => [
                'type' => 'CREDIT_DEBIT',
            ],
            'amounts' => [
                'transactionAmount' => $totalAmountInCents,
                'orderAmount'       => $totalAmountInCents,
                'currency'          => $order->get_currency(),
            ],
            'notes' => $this->sanitizeNotes($order->refund->reason ?? ''),
        ];
    }
}
