<?php

namespace RabbitLoader\SDK;

class Exc extends \Exception
{
    public static function catch($e, $data = [])
    {
        error_log($e);
    }
}
