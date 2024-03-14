<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Helpers;

use Exception;
use GoDaddy\WooCommerce\Poynt\API\GatewayAPI;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use stdClass;
use WC_Order;

defined('ABSPATH') or exit;

/**
 * Helpers for handling credentials.
 *
 * @since 1.3.0
 */
class PoyntHelper
{
    /** @var string store device activated status */
    const STATUS_ACTIVATED = 'ACTIVATED';

    /** @var array Terminal Serial begins with */
    const POYNT_SMART_TERMINAL_SERIAL_BEGINS = ['P6', 'SN', 'PEMU'];

    /** @var string store device type - terminal */
    const TYPE_TERMINAL = 'TERMINAL';

    /** @var array local shipping method ids */
    const IN_PERSON_SHIPPING_METHOD_IDS = ['local_pickup', 'local_pickup_plus', 'gdp_local_delivery'];

    /**
     * Verify if the Credit Card payment gateway is configured in the gateway settings.
     *
     * @since 1.3.0
     *
     * @return bool
     */
    public static function isGDPConfigured() : bool
    {
        $combinedAppId = self::getCredentials('appId');

        if (! $combinedAppId || ! is_string($combinedAppId)) {
            return false;
        }

        $appIdPieces = explode('=', $combinedAppId);
        $businessId = current($appIdPieces);
        $appId = end($appIdPieces);

        return (bool) CredentialsHelper::isAppIdValid($appId) && CredentialsHelper::isBusinessIdValid($businessId);
    }

    /**
     * Verify if the Credit Card payment gateway is configured and connected with the API properly.
     *
     * @since 1.3.0
     *
     * @return bool
     */
    public static function isGDPConnected() : bool
    {
        if (! self::isGDPConfigured() || ! self::getBusinessId() || ! self::getAppId()) {
            return false;
        }

        return true;
    }

    /**
     * Returns credentials from Credit Card settings by key.
     *
     * @since 1.3.0
     *
     * @param string $credentialKey settings key to get data from
     * @return string|bool string or false if nothing is found
     */
    public static function getCredentials(string $credentialKey)
    {
        if (! $creditCardOptions = get_option('woocommerce_'.Plugin::CREDIT_CARD_GATEWAY_ID.'_settings', false)) {
            return false;
        }

        $environment = $creditCardOptions['environment'] ?? '';

        if (Plugin::ENVIRONMENT_PRODUCTION !== $environment) {
            $credentialKey = $environment.ucfirst($credentialKey);
        }

        return $creditCardOptions[$credentialKey] ?? false;
    }

    /**
     * Returns environment from Credit Card settings.
     *
     * @since 1.3.0
     *
     * @return string
     */
    public static function getEnvironment()
    {
        if (! $creditCardOptions = get_option('woocommerce_'.Plugin::CREDIT_CARD_GATEWAY_ID.'_settings', false)) {
            return '';
        }

        return $creditCardOptions['environment'] ?? '';
    }

    /**
     * Returns Poynt businessId from wp_options.
     *
     * @since 1.3.0
     *
     * @return string|null
     */
    public static function getBusinessId()
    {
        return get_option('wc_poynt_businessId', null);
    }

    /**
     * Returns Poynt appId from wp_options.
     *
     * @since 1.3.0
     *
     * @return string|null
     */
    public static function getAppId()
    {
        return get_option('wc_poynt_appId', null);
    }

    /**
     * Returns Poynt privateKey from wp_options.
     *
     * @since 1.3.0
     *
     * @return string|null
     */
    public static function getPrivateKey()
    {
        return self::getCredentials('privateKey');
    }

    /**
     * Determines if the site has any Poynt smart terminal devices activated in the configurations.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    public static function hasPoyntSmartTerminalActivated() : bool
    {
        return (bool) defined('PAYINPERSON_TERMINAL_ACTIVATED') ? PAYINPERSON_TERMINAL_ACTIVATED : get_option('wc_poynt_payinperson_terminal_activated', false);
    }

    /**
     * Determines if the device is a Poynt smart terminal and is active.
     *
     * @since 1.3.0
     *
     * @param stdClass|mixed|null $device device object
     * @return bool
     * @throws Exception
     */
    public static function isActivePoyntSmartTerminal($device) : bool
    {
        if (! isset($device->status)) {
            return false;
        }

        $modelMatched = false;

        foreach (static::POYNT_SMART_TERMINAL_SERIAL_BEGINS as $startingSerial) {
            if (! isset($device->serialNumber) || ! Framework\SV_WC_Helper::str_starts_with($device->serialNumber, $startingSerial)) {
                continue;
            }
            $modelMatched = true;
        }

        return $modelMatched
            && isset($device->type)
            && static::TYPE_TERMINAL === $device->type
            && $device->status === static::STATUS_ACTIVATED;
    }

    /**
     * Returns true if the supplied order meets the criteria to be pushed to the Poynt API.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order
     * @return bool
     * @throws Exception
     */
    public static function shouldPushOrderDetailsToPoynt(WC_Order $order) : bool
    {
        if (
            ! self::isGDPConnected()
            || ! self::hasPoyntSmartTerminalActivated()
            || ! WCHelper::orderHasShippingMethods($order, self::IN_PERSON_SHIPPING_METHOD_IDS)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Returns the Gateway API object.
     *
     * @since 1.3.0
     *
     * @return bool|GatewayAPI
     */
    public static function getGatewayAPI()
    {
        $businessId = static::getBusinessId();
        $appId = static::getAppId();
        $privateKey = static::getPrivateKey();
        $environment = static::getEnvironment();

        // ensure we have the minimum requirements to be connected to the API
        if (! $businessId || ! $appId || ! $privateKey) {
            return false;
        }

        return new GatewayAPI($appId, $businessId, $privateKey, $environment);
    }
}
