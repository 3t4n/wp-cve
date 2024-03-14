<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Twig;

use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\SerializerInterface;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class SerializerRuntimeHelper
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    public function __construct(\WPPayVendor\JMS\Serializer\SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @param mixed $object
     */
    public function serialize($object, string $type = 'json', ?\WPPayVendor\JMS\Serializer\SerializationContext $context = null) : string
    {
        return $this->serializer->serialize($object, $type, $context);
    }
}
