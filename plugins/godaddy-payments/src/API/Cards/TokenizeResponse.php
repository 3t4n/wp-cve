<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Cards;

use GoDaddy\WooCommerce\Poynt\API\Responses\AbstractResponse;
use GoDaddy\WooCommerce\Poynt\Gateways\CreditCardGateway;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Tokenization response.
 *
 * @since 1.0.0
 */
class TokenizeResponse extends AbstractResponse implements Framework\SV_WC_Payment_Gateway_API_Create_Payment_Token_Response
{
    /**
     * Gets the payment token.
     *
     * @link https://docs.poynt.com/app-integration/poynt-collect/#creating-a-token
     *
     * @since 1.0.0
     *
     * @return Framework\SV_WC_Payment_Gateway_Payment_Token
     * @throws Framework\SV_WC_API_Exception
     */
    public function get_payment_token() : Framework\SV_WC_Payment_Gateway_Payment_Token
    {
        if (empty($this->response_data->paymentToken)) {
            throw new Framework\SV_WC_API_Exception('Response payment token is missing');
        }

        // Poynt API uses a card type identifier for Amex that isn't expected by the Framework
        if (in_array($this->response_data->card->type, ['american_express', 'AMERICAN_EXPRESS'], true)) {
            $this->response_data->card->type = Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_AMEX;
        }

        return new Framework\SV_WC_Payment_Gateway_Payment_Token($this->response_data->paymentToken, [
            'type'      => 'credit_card',
            'last_four' => $this->response_data->card->numberLast4,
            'card_type' => Framework\SV_WC_Payment_Gateway_Helper::normalize_card_type($this->response_data->card->type),
            'exp_month' => str_pad($this->response_data->card->expirationMonth, 2, '0', STR_PAD_LEFT),
            'exp_year'  => Framework\SV_WC_Payment_Gateway_Helper::format_exp_year($this->response_data->card->expirationYear),
        ]);
    }

    /**
     * Determines whether the tokenization is approved.
     *
     * @since 1.0.0
     *
     * @return bool
     * @throws Framework\SV_WC_API_Exception
     */
    public function transaction_approved() : bool
    {
        $approved = 'ACTIVE' === $this->get_status_code();

        if ($approved && $this->avsResponseHasNoMatchResult()) {
            Framework\SV_WC_Helper::wc_add_notice(__("Your billing address doesn't match your payment card information. Please update the billing address to submit your order.", 'godaddy-payments'), 'error');

            throw new Framework\SV_WC_API_Exception('AVS has a NO_MATCH result');
        }

        return $approved;
    }

    /**
     * Flags that a tokenization response cannot be held (not applicable for this response).
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function transaction_held() : bool
    {
        return false;
    }

    /**
     * Gets the tokenization response status message.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_status_message() : string
    {
        return $this->response_data->message ?? '';
    }

    /**
     * Gets the tokenization response status code.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_status_code() : string
    {
        return $this->response_data->status ?? '';
    }

    /**
     * Gets the tokenization payment type.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_payment_type() : string
    {
        return CreditCardGateway::PAYMENT_TYPE_CREDIT_CARD;
    }

    /**
     * Gets the tokenization response user message (empty string for this response type).
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_user_message() : string
    {
        return '';
    }

    /**
     * Gets the tokenization response transaction ID (not applicable for this response type).
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_transaction_id() : string
    {
        return '';
    }

    /**
     * Determines whether the AVS response has a NO_MATCH result.
     *
     * @return bool
     */
    protected function avsResponseHasNoMatchResult() : bool
    {
        $avsResponse = $this->response_data->avsResponse ?? null;
        $addressResult = $avsResponse->addressResult ?? null;
        $postalCodeResult = $avsResponse->postalCodeResult ?? null;

        return in_array('NO_MATCH', [$addressResult, $postalCodeResult]);
    }
}
