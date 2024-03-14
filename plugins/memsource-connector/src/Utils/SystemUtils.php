<?php

namespace Memsource\Utils;

class SystemUtils
{
    public static function getJsonUpdateFile(string $version): string
    {
        return self::getJsonConfigFolder() . DIRECTORY_SEPARATOR . "update-${version}.json";
    }

    public static function getJsonConfigFile(string $file): string
    {
        return self::getJsonConfigFolder() . DIRECTORY_SEPARATOR . $file;
    }

    public static function getJsonConfigFolder(): string
    {
        return MEMSOURCE_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'json';
    }

    public static function getSqlUpdateFile(string $version): string
    {
        return self::getSqlConfigFolder() . DIRECTORY_SEPARATOR . "update-${version}.sql";
    }

    public static function getSqlConfigFolder(): string
    {
        return MEMSOURCE_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'sql';
    }
}
