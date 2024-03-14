<?php

namespace FluentSupport\App\Modules;

/**
 *  IntegrationSettingsModule class is responsible for getting/setting Settings related to integration
 * @package FluentSupport\App\Modules
 *
 * @version 1.0.0
 */
class IntegrationSettingsModule
{
    private static $integrations = [];

    /**
     * getSettings will return settings for a specific integration key
     * @param $integrationKey
     * @param false $withFields
     * @return false
     */
    public static function getSettings($integrationKey, $withFields = false)
    {
        $integrationClasses = self::$integrations;

        $class = (isset($integrationClasses[$integrationKey])) ? $integrationClasses[$integrationKey] : false;

        if(!$class) {
            return false;
        }

        return $class->getSettings($withFields);
    }

    /**
     * saveSettings save integration settings by integration_key
     * @param $integrationKey
     * @param $settings
     * @return false || object
     */
    public static function saveSettings($integrationKey, $settings)
    {
        $integrationClasses = self::$integrations;

        $class = (isset($integrationClasses[$integrationKey])) ? $integrationClasses[$integrationKey] : false;

        if(!$class) {
            return false;
        }

        return $class->saveSettings($settings);
    }

    /**
     * addIntegration add integration classes into the self property list
     * @param $class
     */
    public static function addIntegration($class)
    {
        self::$integrations[$class->getKey()] = $class;
    }

}
