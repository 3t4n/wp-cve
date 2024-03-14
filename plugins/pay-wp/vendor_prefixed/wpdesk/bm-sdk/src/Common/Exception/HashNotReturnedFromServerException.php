<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Exception;

final class HashNotReturnedFromServerException extends \WPPayVendor\BlueMedia\Common\Exception\HashException
{
    public static function noHash() : self
    {
        return new self('No hash received from server! Check your serviceID.');
    }
}
