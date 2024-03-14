<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes;

use S123\Includes\Base\S123_Enqueue;
use S123\Includes\Base\S123_I18n;
use S123\Includes\Pages\S123_ApiKey;
use S123\Includes\Pages\S123_Checkout;
use S123\Includes\Pages\S123_InvoiceSettings;
use S123\Includes\Pages\S123_Settings;
use S123\Includes\Woocommerce\I123_OrderEmail;
use S123\Includes\Woocommerce\I123_Warehouse;
use S123\Includes\Woocommerce\S123_Product;

if (!defined('ABSPATH')) exit;

final class S123_Init
{
    /**
     * Store all classes with actions inside array
     * @return array
     */
    public static function s123_get_services(): array
    {
        return [
            S123_Settings::class,
            S123_Enqueue::class,
            S123_Product::class,
            S123_ApiKey::class,
            S123_InvoiceSettings::class,
            S123_I18n::class,
            S123_Checkout::class,
            I123_OrderEmail::class,
            I123_Warehouse::class,
        ];
    }

    /**
     * Initialize classes with hooks
     */
    public static function s123_register_services()
    {
        foreach (self::s123_get_services() as $class) {
            $service = self::s123_instantiate($class);

            if (method_exists($service, 's123_register')) {
                $service->s123_register();
            }
        }
    }

    private static function s123_instantiate($class)
    {
        return new $class();
    }
}
