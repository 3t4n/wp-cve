<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Exception;

/**
 * RuntimeException for the Serializer.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class RuntimeException extends \RuntimeException implements \WPPayVendor\JMS\Serializer\Exception\Exception
{
    public static function noMetadataForProperty(string $class, string $prop) : self
    {
        return new \WPPayVendor\JMS\Serializer\Exception\RuntimeException(\sprintf('You must define a type for %s::$%s.', $class, $prop));
    }
}
