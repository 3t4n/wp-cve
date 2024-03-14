<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Requests;

use GoDaddy\WooCommerce\Poynt\Helpers\WPNUXHelper;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Order;

defined('ABSPATH') or exit;

/**
 * Abstract Business request object.
 *
 * @since 1.0.0
 */
abstract class AbstractBusinessRequest extends AbstractRequest
{
    /** @var string the business ID */
    protected $businessId = '';

    /**
     * Business request constructor.
     *
     * @since 1.0.0
     *
     * @param string $businessId the business ID
     */
    public function __construct(string $businessId)
    {
        $this->businessId = $businessId;

        $this->path = "/businesses/{$businessId}";
    }

    /**
     * Sets C2 verification data to the request.
     *
     * @since 1.1.0
     *
     * @param WC_Order $order related order
     */
    public function setVerificationData(WC_Order $order)
    {
        if (! isset($this->data['fundingSource'])) {
            $this->data['fundingSource'] = [];
        }

        if (! isset($this->data['fundingSource']['verificationData'])) {
            $this->data['fundingSource']['verificationData'] = [];
        }

        // only post code is required, add more details as available
        if ($order->has_billing_address()) {
            $this->data['fundingSource']['verificationData'] = [
                'cardHolderBillingAddress' => [
                    'line1'       => $order->get_billing_address_1(),
                    'line2'       => $order->get_billing_address_2(),
                    'city'        => $order->get_billing_city(),
                    'territory'   => $order->get_billing_state(),
                    'postalCode'  => $order->get_billing_postcode(),
                    'countryCode' => $order->get_billing_country(),
                ],
            ];
        } elseif ($postCode = $order->get_billing_postcode()) {
            $this->data['fundingSource']['verificationData'] = [
                'cardHolderBillingAddress' => [
                    'postalCode' => $postCode,
                ],
            ];
        }
    }

    /**
     * Sets C2 receipt data to the request.
     *
     * @since 1.1.0
     *
     * @param WC_Order $order
     */
    public function setReceiptData(WC_Order $order)
    {
        // @TODO should this become a setting? if true, Poynt would send a receipt {@unfulvio-godaddy 2021-04-21}
        // $this->data['emailReceipt'] = true;

        // required field
        $this->data['receiptEmailAddress'] = $order->get_billing_email();

        // @TODO should add also an optional `receiptPhone` field - however, Poynt expects a phone number broken down in properties with country code, area code and local number, but WooCommerce doesn't provide a clear separation of these entities {@unfulvio-godaddy 2020-03-31}
    }

    /**
     * Sets the shipping address to the request.
     *
     * @since 1.2.1
     *
     * @param WC_Order $order
     */
    public function setShippingAddress(WC_Order $order)
    {
        if (! $order->has_shipping_address()) {
            return;
        }

        $this->data['shippingAddress'] = [
            'line1'       => $order->get_shipping_address_1(),
            'line2'       => $order->get_shipping_address_2(),
            'city'        => $order->get_shipping_city(),
            'territory'   => $order->get_shipping_state(),
            'postalCode'  => $order->get_shipping_postcode(),
            'countryCode' => $order->get_shipping_country(),
        ];
    }

    /**
     * Sanitizes the given note for the Poynt API.
     *
     * @TODO this might be deprecated once support for non latin characters is confirmed in Poynt Collect {FN 2021-02-26}
     *
     * @since 1.0.0
     *
     * @param mixed $notes
     *
     * @return string
     */
    protected function sanitizeNotes($notes) : string
    {
        return is_string($notes) ? trim(Framework\SV_WC_Helper::str_to_ascii($notes)) : '';
    }

    /**
     * Gets the value for {transaction.context.sourceApp} parameter.
     *
     * @since 1.1.2
     */
    protected function getContextSourceApp() : string
    {
        if (WPNUXHelper::isBHSite()) {
            return 'poynt-for-woo-bh';
        }

        if (WPNUXHelper::isCPanelSite()) {
            return 'poynt-for-woo-cpanel';
        }

        return 'poynt-for-woo';
    }
}
