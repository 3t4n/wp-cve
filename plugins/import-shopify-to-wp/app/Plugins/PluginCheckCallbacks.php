<?php

namespace S2WPImporter\Plugins;

use S2WPImporter\Traits\AjaxTrait;

/**
 * PluginCheckCallbacks
 */
class PluginCheckCallbacks
{
    public static function getCallback($slug)
    {
        $cb = [
            'all-in-one-seo-pack' => function () {
                return defined('AIOSEO_PHP_VERSION_DIR');
            },
            'coming-soon' => function () {
                return defined('SEEDPROD_PLUGIN_PATH');
            },
            'wpforms-lite' => function () {
                return defined('WPFORMS_PLUGIN_DIR');
            },
            'optinmonster' => function () {
                return defined('OMAPI_FILE');
            },
            'google-analytics-for-wordpress' => function () {
                return class_exists('MonsterInsights_Lite');
            },
            'wp-mail-smtp' => function () {
                return function_exists('wp_mail_smtp');
            },
            'trustpulse-api' => function () {
                return defined('TRUSTPULSE_PLUGIN_VERSION');
            },
        ];

        return $cb[$slug];
    }
}
