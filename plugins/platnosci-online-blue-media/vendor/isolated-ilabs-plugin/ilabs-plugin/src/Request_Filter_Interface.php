<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin;

interface Request_Filter_Interface
{
    public function filter($key, $value);
}
