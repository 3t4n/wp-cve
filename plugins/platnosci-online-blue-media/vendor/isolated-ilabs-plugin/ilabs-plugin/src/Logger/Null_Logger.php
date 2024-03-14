<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Logger;

class Null_Logger implements Logger_Interface
{
    public function log($log)
    {
    }
    public function error(string $message, array $args = null, string $context = null)
    {
    }
}
