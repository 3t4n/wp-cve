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
 * Capture payment response.
 *
 * @since 1.0.0
 */
class CaptureResponse extends AbstractResponse implements Framework\SV_WC_Payment_Gateway_API_Response
{
    /** @var string captured status code */
    const CAPTURED_STATUS = 'CAPTURED';

    /** @var string partially captured status code */
    const PARTIALLY_CAPTURED_STATUS = 'PARTIALLY_CAPTURED';

    /**
     * Determines whether the capture payment transaction is approved.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function transaction_approved() : bool
    {
        return in_array($this->get_status_code(), [self::CAPTURED_STATUS, self::PARTIALLY_CAPTURED_STATUS]);
    }

    /**
     * Determines whether the capture payment transaction is held.
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
     * Gets the capture payment status message.
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
     * Gets the capture payment status code.
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
     * Gets the capture payment transaction ID.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_transaction_id() : string
    {
        // use the deep link ID if available
        $href = $this->response_data->links[0]->href ?? null;

        if (is_string($href)) {
            return $href;
        }

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
     * Gets the capture response user message.
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
