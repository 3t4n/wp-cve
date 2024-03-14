<?php

namespace Avecdo\Woocommerce\Classes;

use Avecdo\SDK\Classes\Helpers;

class Option
{
    public static function get($name)
    {
        return get_option(self::getFullName($name));
    }

    public static function update($name, $value)
    {
        return update_option(self::getFullName($name), $value);
    }

    private static function getFullName($name)
    {
        switch (self::getVersion()) {
            case 1:
                return 'avecdo_' . $name;
            case 2:
                return 'avecdo_v2_' . $name;
        }
        return null;
    }

    public static function getVersion()
    {
        $headers = Helpers::getAllHeaders();
        if (key_exists('user-agent', $headers)) {
            switch ($headers['user-agent']) {
                case 'avecdo (+https://avecdo.com)':
                case 'avecdo/1.0 (+https://avecdo.com)':
                    return 1;
                case 'avecdo/2.0 (+https://avecdo.com)':
                    return 2;
            }
        }

        return get_option('avecdo_version');
    }
}
