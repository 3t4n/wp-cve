<?php

namespace Rockschtar\WordPress\ColoredAdminPostList\Enums;

class Option
{
    const INSTALLED = "capl_installed";
    const VERSION = "capl-version";

    public static function all(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}
