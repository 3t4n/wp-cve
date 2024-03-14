<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Helpers;

defined('ABSPATH') or exit;

use Exception;
use WP_Filesystem_Base;

/**
 * Common helper functions.
 */
class CommonHelper
{
    /**
     * Determines if a given value or values is the current page.
     *
     * // @TODO add strict type casting when min version is PHP 8
     *
     * @param array|string $path path to check
     * @return bool
     */
    public static function isCurrentPage($path) : bool
    {
        $path = ArrayHelper::wrap($path);

        return ArrayHelper::contains($path, self::getCurrentPage())
            || ArrayHelper::contains($path, (string) filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS, [
                'options' => ['default' => ArrayHelper::get($_GET, 'page')],
            ]));
    }

    /**
     * Gets the current page.
     *
     * @return string|mixed|null
     */
    public static function getCurrentPage()
    {
        return ArrayHelper::get($GLOBALS, 'pagenow');
    }

    /**
     * Gets the WordPress Filesystem instance.
     *
     * @return WP_Filesystem_Base|mixed
     * @throws Exception
     */
    public static function getFilesystem()
    {
        if (! $wp_filesystem = ArrayHelper::get($GLOBALS, 'wp_filesystem')) {
            throw new Exception('Unable to connect to the WordPress filesystem -- wp_filesystem global not found');
        }

        if (is_a($wp_filesystem, 'WP_Filesystem_Base') && is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->has_errors()) {
            throw new Exception(sprintf('Unable to connect to the WordPress filesystem with error: %s', $wp_filesystem->errors->get_error_message()));
        }

        return $wp_filesystem;
    }

    /**
     * Gets a localized date.
     *
     * @param string $format the PHP format used to display the date
     * @param int|false $timestamp optional timestamp with offset
     * @param bool $utc whether date is assumed UTC (only used if timestamp offset not provided)
     * @return string
     */
    public static function getLocalizedDate(string $format, $timestamp = false, bool $utc = false) : string
    {
        $localizedDate = date_i18n($format, $timestamp, $utc);

        return is_string($localizedDate) ? $localizedDate : '';
    }
}
