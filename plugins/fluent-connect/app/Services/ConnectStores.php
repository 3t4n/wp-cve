<?php

namespace FluentConnect\App\Services;

class ConnectStores
{
    private static $triggerProviders = [];
    private static $triggers = [];

    private static $actionProviders = [];
    private static $actions = [];

    public static function addTriggerProvider($provider, $info)
    {
        if (!isset(self::$triggerProviders[$provider])) {
            self::$triggerProviders[$provider] = $info;
        }

        if (!isset(self::$triggers[$provider])) {
            self::$triggers[$provider] = [];
        }
    }

    public static function addTrigger($provider, $triggerName, $class)
    {
        if (!isset(self::$triggers[$provider])) {
            return false;
        }

        self::$triggers[$provider][$triggerName] = $class;
    }

    public static function getAllTriggerCats()
    {
        return self::$triggers;
    }

    public static function getTriggerProviders($withTriggers = false)
    {
        $providers = self::$triggerProviders;

        if (!$withTriggers) {
            return $providers;
        }


        foreach (self::getAllTriggerCats() as $providerName => $triggers) {

            if(!isset($providers[$providerName])) {
                continue;
            }

            $providers[$providerName]['triggers'] = [];

            foreach ($triggers as $triggerName => $triggerClassName) {
                if (class_exists($triggerClassName)) {
                    $class = new $triggerClassName();
                    $providers[$providerName]['triggers'][$triggerName] = $class->getInfo();
                }
            }
        }

        return $providers;

    }

    public static function getTriggerClass($provider, $triggerName)
    {
        if (isset(self::$triggers[$provider][$triggerName])) {
            $class = self::$triggers[$provider][$triggerName];
            if (class_exists($class)) {
                return new $class();
            }
        }

        return null;
    }

    public static function addActionProvider($provider, $info)
    {
        if (!isset(self::$actionProviders[$provider])) {
            self::$actionProviders[$provider] = $info;
        }

        if (!isset(self::$actions[$provider])) {
            self::$actions[$provider] = [];
        }
    }

    public static function addAction($provider, $actionName, $class)
    {
        if (!isset(self::$actions[$provider])) {
            return false;
        }

        self::$actions[$provider][$actionName] = $class;
    }

    public static function getAllActionsCats()
    {
        return self::$actions;
    }

    public static function getActionProviders($withActions = false)
    {
        $providers = self::$actionProviders;

        if (!$withActions) {
            return $providers;
        }


        foreach (self::getAllActionsCats() as $providerName => $actions) {

            if(!isset($providers[$providerName])) {
                continue;
            }

            $providers[$providerName]['actions'] = [];

            foreach ($actions as $actionName => $actionClassName) {
                if (class_exists($actionClassName)) {
                    $class = new $actionClassName();
                    $providers[$providerName]['actions'][$actionName] = $class->getInfo();
                }
            }
        }

        return $providers;

    }

    public static function getActionClass($provider, $actionName)
    {
        if (isset(self::$actions[$provider][$actionName])) {
            $class = self::$actions[$provider][$actionName];
            if (class_exists($class)) {
                return new $class();
            }
        }

        return null;
    }

}
