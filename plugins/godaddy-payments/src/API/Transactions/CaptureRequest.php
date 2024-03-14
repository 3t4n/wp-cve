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
 * Capture request.
 *
 * @link https://docs.poynt.com/app-integration/poynt-collect/refunds.html#capture-an-auth-sale
 *
 * @since 1.0.0
 */
class CaptureRequest extends AbstractBusinessRequest
{
    /**
     * Capture request constructor.
     *
     * @since 1.0.0
     *
     * @param string $businessId the business ID
     * @param string $transactionId the transaction ID
     */
    public function __construct(string $businessId, string $transactionId)
    {
        parent::__construct($businessId);

        $this->path = "{$this->path}/transactions/{$transactionId}/capture";
    }

    /**
     * Sets the capture request data.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order
     */
    public function setCaptureData(WC_Order $order)
    {
        $orderTotalInCents = MoneyHelper::convertDecimalToCents((float) $order->get_total());
        $this->data = [
            'amounts' => [
                'currency'          => $order->get_currency(),
                'orderAmount'       => $orderTotalInCents,
                'tipAmount'         => 0,
                'transactionAmount' => $orderTotalInCents,
            ],
            'context' => [
                'sourceApp' => $this->getContextSourceApp(),
            ],
        ];
    }
}
