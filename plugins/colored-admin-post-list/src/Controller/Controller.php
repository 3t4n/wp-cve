<?php

namespace Rockschtar\WordPress\ColoredAdminPostList\Controller;

trait Controller
{
    public static function init(): self
    {
        static $instance = null;

        $class = get_called_class();

        if ($instance === null) {
            $instance = new $class();
        }
        return $instance;
    }
}
