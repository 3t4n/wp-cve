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
 * Charge payment response.
 *
 * @link https://docs.poynt.com/app-integration/poynt-collect/#charging-a-token
 *
 * @since 1.0.0
 */
class TokenizeChargeResponse extends AbstractResponse implements Framework\SV_WC_Payment_Gateway_API_Authorization_Response
{
    /** @var string authorized status code */
    const AUTHORIZED_STATUS = 'AUTHORIZED';

    /** @var string captured status code */
    const CAPTURED_STATUS = 'CAPTURED';

    /** @var string declined status code */
    const DECLINED_STATUS = 'DECLINED';

    /** @var Framework\SV_WC_Payment_Gateway_API_Response_Message_Helper instance */
    private $messagesHelper;

    /**
     * Tokenize charge response constructor.
     *
     * @since 1.0.0
     *
     * @param string $rawResponseJson the JSON response data
     */
    public function __construct(string $rawResponseJson)
    {
        parent::__construct($rawResponseJson);

        $this->messagesHelper = new Framework\SV_WC_Payment_Gateway_API_Response_Message_Helper();
    }

    /**
     * Gets the charge payment authorization code.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_authorization_code() : string
    {
        return $this->response_data->processorResponse->approvalCode ?? '';
    }

    /**
     * Determines whether the charge payment transaction was approved.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function transaction_approved() : bool
    {
        return self::CAPTURED_STATUS === $this->get_status_code();
    }

    /**
     * Determines whether the charge payment transaction was held.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function transaction_held() : bool
    {
        return self::AUTHORIZED_STATUS === $this->get_status_code();
    }

    /**
     * Gets the charge payment transaction status message.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_status_message() : string
    {
        return $this->response_data->processorResponse->statusMessage ?? '';
    }

    /**
     * Gets the charge payment transaction status code.
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
     * Gets the charge payment transaction ID.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_transaction_id() : string
    {
        return $this->response_data->id ?? '';
    }

    /**
     * Gets the payment type.
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
     * Gets the charge payment transaction user message.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_user_message() : string
    {
        if (self::DECLINED_STATUS === $this->get_status_code()) {
            return $this->messagesHelper->get_user_message('decline');
        }

        return '';
    }

    /**
     * Gets the address validation check result.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_avs_result() : string
    {
        // no-op: implemented from parent
        return '';
    }

    /**
     * Gets the CSC check result.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_csc_result() : string
    {
        // no-op: implemented from parent
        return '';
    }

    /**
     * Determines whether the CSV check was successful.
     *
     * @since 1.0.0
     */
    public function csc_match()
    {
    }
}
