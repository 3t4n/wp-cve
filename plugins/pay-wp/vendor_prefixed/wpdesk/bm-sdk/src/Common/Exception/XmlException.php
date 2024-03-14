<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Exception;

final class XmlException extends \RuntimeException
{
    public static function xmlBodyContainsError(string $content) : self
    {
        return new self(\sprintf('%s: %s', 'Returned XML contains error: ', $content));
    }
    public static function xmlGeneralError(string $content) : self
    {
        return new self(\sprintf('%s: %s', 'Received error instead of XML: ', $content));
    }
    public static function xmlParseError(\Throwable $exception) : self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }
}
