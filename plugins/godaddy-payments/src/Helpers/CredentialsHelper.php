<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Helpers;

use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Helpers for handling credentials.
 *
 * @since 1.0.0
 */
class CredentialsHelper
{
    /** @var string regular expression to match a UUID credential pattern */
    private static $uuidRegexMatch = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

    /** @var string RSA private key heading */
    private static $beginKey = '-----BEGIN RSA PRIVATE KEY-----';

    /** @var string RSA private key footer */
    private static $endKey = '-----END RSA PRIVATE KEY-----';

    /**
     * Formats a private key.
     *
     * @since 1.0.0
     *
     * @param string $value raw key value, may be wrapped or not in a RSA enclosure
     * @return string
     */
    public static function formatPrivateKey(string $value) : string
    {
        $value = ltrim($value);

        $formatted = '';

        if (! Framework\SV_WC_Helper::str_starts_with($value, self::$beginKey)) {
            $formatted .= self::$beginKey."\n";
        }

        $formatted .= rtrim($value);

        if (! Framework\SV_WC_Helper::str_ends_with($formatted, self::$endKey)) {
            $formatted .= "\n".self::$endKey;
        }

        return trim($formatted);
    }

    /**
     * Determines whether an application ID is valid.
     *
     * @since 1.0.0
     *
     * @param string $appId the application ID
     * @return bool
     */
    public static function isAppIdValid(string $appId) : bool
    {
        return ! empty($appId) && (bool) preg_match(self::$uuidRegexMatch, str_replace('urn:aid:', '', $appId));
    }

    /**
     * Determines whether a business ID is valid.
     *
     * @since 1.0.0
     *
     * @param string $businessId the business ID
     * @return bool
     */
    public static function isBusinessIdValid(string $businessId) : bool
    {
        return ! empty($businessId) && (bool) preg_match(self::$uuidRegexMatch, $businessId);
    }
}
