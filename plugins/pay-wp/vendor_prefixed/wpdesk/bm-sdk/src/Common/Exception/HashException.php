<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Exception;

class HashException extends \RuntimeException
{
    public static function wrongHashError() : self
    {
        return new self('Received wrong hash!');
    }
}
