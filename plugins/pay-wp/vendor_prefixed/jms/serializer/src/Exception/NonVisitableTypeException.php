<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Exception;

use function get_debug_type;
final class NonVisitableTypeException extends \WPPayVendor\JMS\Serializer\Exception\RuntimeException
{
    /**
     * @param mixed $data
     * @param array{name: string} $type
     * @param RuntimeException|null $previous
     *
     * @return NonVisitableTypeException
     */
    public static function fromDataAndType($data, array $type, ?\WPPayVendor\JMS\Serializer\Exception\RuntimeException $previous = null) : self
    {
        return new self(\sprintf('Type %s cannot be visited as %s', \get_debug_type($data), $type['name']), 0, $previous);
    }
}
