<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin;

trait Tools
{
    public function require_wp_core_file(string $path)
    {
        require_once ABSPATH . $path;
    }
}
