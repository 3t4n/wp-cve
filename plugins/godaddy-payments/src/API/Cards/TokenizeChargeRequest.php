<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Cards;

use GoDaddy\WooCommerce\Poynt\API;
use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractBusinessRequest;
use GoDaddy\WooCommerce\Poynt\Helpers\MoneyHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\StringHelper;
use WC_Order;

defined('ABSPATH') or exit;

/**
 * Tokenize charge request.
 *
 * @link https://docs.poynt.com/app-integration/poynt-collect/#charging-a-token
 *
 * @since 1.0.0
 */
class TokenizeChargeRequest extends AbstractBusinessRequest
{
    /** @var string sale (charge) action identifier */
    const ACTION_SALE = 'SALE';

    /** @var string authorization action identifier */
    const ACTION_AUTHORIZE = 'AUTHORIZE';

    /**
     * Tokenize charge request constructor.
     *
     * @since 1.0.0
     *
     * @param string $businessId the business ID
     */
    public function __construct(string $businessId)
    {
        parent::__construct($businessId);

        $this->path = "{$this->path}/cards/tokenize/charge";
    }

    /**
     * Sets authorization data.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order order object
     */
    public function setAuthorizeData(WC_Order $order)
    {
        $this->setOrderData($order);

        $this->data['authOnly'] = true;
        $this->data['partialAuthEnabled'] = false;
        $this->data['action'] = self::ACTION_AUTHORIZE;
    }

    /**
     * Sets sale (charge) data.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order order object
     */
    public function setSaleData(WC_Order $order)
    {
        $this->setOrderData($order);

        $this->data['authOnly'] = false;
        $this->data['partialAuthEnabled'] = false;
        $this->data['action'] = self::ACTION_SALE;
    }

    /**
     * Sets order data.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order order object
     */
    public function setOrderData(WC_Order $order)
    {
        $orderTotalInCents = MoneyHelper::convertDecimalToCents((float) $order->get_total());

        $references = [[
            'type'       => 'CUSTOM',
            'customType' => 'POYNT_COLLECT',
            'id'         => wp_generate_uuid4(),
        ]];

        if (PoyntHelper::shouldPushOrderDetailsToPoynt($order)) {
            $poyntOrderId = StringHelper::generateUuid4();
            $references[] = [
                'type' => 'POYNT_ORDER',
                'id'   => $poyntOrderId,
            ];
            $order->update_meta_data('_wc_poynt_order_remoteId', $poyntOrderId);
            $order->save();
        }

        $this->data = [
            'context' => [
                'businessId' => $this->businessId,
                'sourceApp'  => API::SOURCE_APP,
            ],
            'amounts' => [
                'transactionAmount' => $orderTotalInCents,
                'orderAmount'       => $orderTotalInCents,
                'currency'          => $order->get_currency(),
            ],
            'fundingSource' => [
                'cardToken'    => $order->payment->token,
                'entryDetails' => [
                    'customerPresenceStatus' => 'ECOMMERCE',
                    'entryMode'              => 'KEYED',
                ],
            ],
            'notes'      => $this->sanitizeNotes($order->description ?? ''),
            'references' => $references,
        ];
    }
}
