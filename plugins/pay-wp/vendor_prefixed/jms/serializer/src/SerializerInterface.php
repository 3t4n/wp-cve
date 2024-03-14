<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer;

use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
/**
 * Serializer Interface.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface SerializerInterface
{
    /**
     * Serializes the given data to the specified output format.
     *
     * @param mixed $data
     *
     * @throws RuntimeException
     */
    public function serialize($data, string $format, ?\WPPayVendor\JMS\Serializer\SerializationContext $context = null, ?string $type = null) : string;
    /**
     * Deserializes the given data to the specified type.
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public function deserialize(string $data, string $type, string $format, ?\WPPayVendor\JMS\Serializer\DeserializationContext $context = null);
}
