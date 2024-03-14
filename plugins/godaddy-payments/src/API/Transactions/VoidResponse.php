<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Transactions;

use GoDaddy\WooCommerce\Poynt\API\Responses\AbstractResponse;
use GoDaddy\WooCommerce\Poynt\Gateways\CreditCardGateway;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Void payment response.
 *
 * @since 1.0.0
 */
class VoidResponse extends AbstractResponse implements Framework\SV_WC_Payment_Gateway_API_Response
{
    /**
     * Flags transactions as always approved.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function transaction_approved() : bool
    {
        return true;
    }

    /**
     * Flags transactions as never held.
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
     * Gets the void payment status message.
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
     * Gets the void payment status code.
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
     * Gets the void payment transaction ID.
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
     * Gets the void payment type.
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
     * Gets the void payment response user message (empty string for this response type).
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_user_message() : string
    {
        return '';
    }
}
