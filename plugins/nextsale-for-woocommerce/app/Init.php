<?php

namespace App;

use App\Utils\Helper;

final class Init
{
    /**
     * Get services
     * @return array
     */
    public static function getServices()
    {
        $services = [
            Pages\Admin::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\Notice::class
        ];

        if (Helper::isAuthGranted()) {
            $services = array_merge($services, [
                Base\PlatformCheck::class,
                Routes\Api::class,
                Hooks\Handler::class
            ]);
        }

        return $services;
    }

    /**
     * Register the services from getServices array
     * @return array
     */
    public static function registerServices()
    {
        foreach (self::getServices() as $class) {
            $service = self::instantiate($class);

            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    /**
     * Instantiate a class
     * @param class
     * @return class $object
     */
    private static function instantiate($class)
    {
        return new $class;
    }
}
