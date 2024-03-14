<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Helpers;

defined('ABSPATH') or exit;

/**
 * WPNUX service helper.
 *
 * @since 1.1.2
 */
class WPNUXHelper
{
    /** @var string cache transient name */
    const TRANSIENT_NAME = 'wc_poynt_wpnux_data';

    /** @var string service option name */
    const OPTION_NAME = 'wpnux_export_data';

    /** @var array loaded service data */
    protected static $data;

    /**
     * Gets service data.
     *
     * @since 1.1.2
     *
     * @return array
     */
    public static function getData() : array
    {
        if (null !== static::$data) {
            return static::$data;
        }

        $cachedData = get_transient(static::TRANSIENT_NAME);

        if (false !== $cachedData) {
            static::$data = $cachedData;
        } else {
            static::$data = static::parseData();

            set_transient(static::TRANSIENT_NAME, static::$data, DAY_IN_SECONDS);
        }

        return static::$data;
    }

    /**
     * Parses service data from options table.
     *
     * @since 1.1.2
     *
     * @return array
     */
    protected static function parseData() : array
    {
        $data = [];

        if ($json = get_option(static::OPTION_NAME)) {
            $data = is_string($json) ? json_decode($json, true) : [];
        }

        return [
            'product'  => $data['_meta']['product'] ?? '',
            'hostname' => $data['_meta']['hostname'] ?? '',
        ];
    }

    /**
     * Determines if the current site is a MWP site.
     *
     * @since 1.2.0
     *
     * @return bool
     */
    public static function isMWPSite() : bool
    {
        $data = static::getData();
        $product = isset($data['product']) ? mb_strtolower((string) $data['product']) : null;

        return 'wpaas' === $product;
    }

    /**
     * Determines if the current site is a BH site.
     *
     * @since 1.1.2
     *
     * @return bool
     */
    public static function isBHSite() : bool
    {
        $data = static::getData();
        $product = isset($data['product']) ? mb_strtolower((string) $data['product']) : null;

        // note: the "ç" here is intentional
        return 'cpanel-bh' === $product || 'çpanel-bh' === $product;
    }

    /**
     * Determines if the current site is a cPanel site.
     *
     * @since 1.1.2
     *
     * @return bool
     */
    public static function isCPanelSite() : bool
    {
        $data = static::getData();
        $product = isset($data['product']) ? mb_strtolower((string) $data['product']) : '';

        return isset($data['hostname']) &&
            ('' === $product || 'cpanel' === $product) &&
            preg_match('/secureserver\.net$/', $data['hostname']);
    }
}
