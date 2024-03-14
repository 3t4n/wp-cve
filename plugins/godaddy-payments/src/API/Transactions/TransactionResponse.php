<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Transactions;

use GoDaddy\WooCommerce\Poynt\API\Responses\AbstractResponse;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\MoneyHelper;
use GoDaddy\WooCommerce\Poynt\Webhooks\PoyntTransactionWebhookHandler;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Get single transaction response.
 *
 * @since 1.3.1
 */
class TransactionResponse extends AbstractResponse
{
    /**
     * Gets the current transactionId.
     *
     * @since 1.3.0
     *
     * @return string|null transactionId
     */
    public function getTransactionId()
    {
        return $this->__get('id');
    }

    /**
     * Gets the current transaction action.
     *
     * @since 1.3.0
     *
     * @return string status e.g. 'REFUND', 'SALE', 'CHARGE'
     */
    public function getAction()
    {
        return $this->__get('action');
    }

    /**
     * Gets the poyntOrderId.
     *
     * @since 1.3.0
     *
     * @return string|null poyntOrderId
     */
    public function getPoyntOrderId()
    {
        $remoteOrderReference = current(ArrayHelper::where(($this->__get('references') ?? []), function ($value) {
            return 'POYNT_ORDER' === $value->type;
        }));

        return $remoteOrderReference->id ?? null;
    }

    /**
     * Gets the Funding source type.
     *
     * @since 1.3.0
     *
     * @return string|null poyntOrderId
     */
    public function getFundingSourceType()
    {
        $fundingSource = $this->__get('fundingSource');

        return $fundingSource->type ?? null;
    }

    /**
     * Gets the transaction status.
     *
     * @since 1.3.0
     *
     * @return string|null transaction status
     */
    public function getStatus()
    {
        return $this->__get('status');
    }

    /**
     * Gets the transaction parent id.
     *
     * @since 1.3.0
     *
     * @return string|null transaction parent id
     */
    public function getParentId()
    {
        return $this->__get('parentId');
    }

    /**
     * Gets the transaction full amount.
     *
     * @since 1.3.0
     *
     * @return int transaction full amount
     */
    public function getTotalAmount()
    {
        return $this->response_data->amounts->transactionAmount ?? 0;
    }

    /**
     * Gets the transaction tip amount.
     *
     * @since 1.3.0
     *
     * @return int transaction tip amount
     */
    public function getTipAmount()
    {
        return $this->response_data->amounts->tipAmount ?? 0;
    }

    /**
     * Gets the transaction cashback amount.
     *
     * @since 1.3.0
     *
     * @return int transaction cashback amount
     */
    public function getCashbackAmount()
    {
        return $this->response_data->amounts->cashbackAmount ?? 0;
    }

    /**
     * Gets the transaction parent type from the given response body data.
     *
     * @since 1.3.0
     *
     * @return string payment type
     */
    public function getParentType() : string
    {
        // find the link data that matches this transaction's parentId
        $parentLinkValues = current(ArrayHelper::where(($this->__get('links') ?? []), function ($value) {
            return $this->__get('parentId') === $value->href;
        }));

        switch ($parentLinkValues->rel ?? '') {
            case 'CAPTURE':
                $parentType = 'capture';
                break;
            case 'REFUND':
                $parentType = 'refund';
                break;
            case 'SALE':
                $parentType = 'sale';
                break;
            default:
                $parentType = 'payment';
        }

        return $parentType;
    }

    /**
     * Gets the payment token type.
     *
     * @since 1.3.0
     *
     * @return array card details
     */
    public function getPaymentDetails() : array
    {
        $fundingSource = $this->__get('fundingSource');

        if (! $fundingSource) {
            return [];
        }

        $expiryMonth = str_pad($fundingSource->card->expirationMonth ?? '', 2, '0', STR_PAD_LEFT);
        $expiryYear = Framework\SV_WC_Payment_Gateway_Helper::format_exp_year($fundingSource->card->expirationYear ?? '');

        return [
            'card_type'            => Framework\SV_WC_Payment_Gateway_Helper::normalize_card_type($fundingSource->card->type ?? ''),
            'card_expiry_date'     => $expiryYear.'-'.$expiryMonth,
            'charge_captured'      => PoyntTransactionWebhookHandler::TRANSACTION_CAPTURED_STATUS === $this->__get('status') ? 'yes' : 'no',
            'authorization_amount' => Framework\SV_WC_Helper::number_format(MoneyHelper::convertCentsToDecimal($this->response_data->amounts->transactionAmount ?? 0)),
            'account_four'         => $fundingSource->card->numberLast4 ?? '',
        ];
    }
}
