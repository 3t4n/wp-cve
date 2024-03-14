<?php

namespace Rockschtar\WordPress\ColoredAdminPostList\Enums;

use ReflectionClass;

class DefaultColor
{
    const DRAFT = "#FCE3F2";
    const PENDING = "#87C5D6";
    const FUTURE = "#C6EBF5";
    const PRIVATE = "#F2D46F";
    const PUBLISH = "transparent";

    public static function all(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
