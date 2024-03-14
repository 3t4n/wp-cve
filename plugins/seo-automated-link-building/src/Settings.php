<?php
/**
 * Created by PhpStorm.
 * User: friedolin
 * Date: 27.01.19
 * Time: 14:52
 */

namespace SeoAutomatedLinkBuilding;


class Settings
{
    private static $domain = 'seo-automated-link-building';

    private static $defaults = [
        'blacklist' => '',
        'whitelist' => '',
        'posttypes' => '',
        'exclude' => '',
        'disableAdminTracking' => false,
        'disableStatistics' => false,
    ];

    public static function init()
    {
        $domain = static::$domain;
        add_option( "{$domain}_settings", json_encode(static::$defaults, JSON_UNESCAPED_UNICODE));
    }


    public static function save(array $values)
    {
        $domain = static::$domain;
        update_option( "{$domain}_settings", json_encode(array_merge(static::$defaults, $values), JSON_UNESCAPED_UNICODE));
    }

    public static function getRaw()
    {
        $domain = static::$domain;
        return array_merge(static::$defaults, json_decode(get_option( "{$domain}_settings"), true, 512, JSON_UNESCAPED_UNICODE));
    }

    private static function getLines($str)
    {
        $lines = explode("\n", $str);
        $trimmedLines = array_map(function($line) {
            return trim($line);
        }, $lines);
        $validUrls = array_filter($trimmedLines, function($line) {
            return !empty($line);
        });
        return array_values($validUrls);
    }

    public static function get()
    {
        $settings = static::getRaw();
        $settings['blacklist'] = static::getLines($settings['blacklist']);
        $settings['whitelist'] = static::getLines($settings['whitelist']);
        $settings['posttypes'] = static::getLines($settings['posttypes']);
        $settings['exclude'] = static::getLines($settings['exclude']);
        return $settings;
    }
}
