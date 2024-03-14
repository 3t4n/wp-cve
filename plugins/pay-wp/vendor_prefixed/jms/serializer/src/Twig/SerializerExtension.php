<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Twig;

use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\SerializerInterface;
use WPPayVendor\Twig\TwigFilter;
use WPPayVendor\Twig\TwigFunction;
/**
 * Serializer helper twig extension
 *
 * Basically provides access to JMSSerializer from Twig
 */
class SerializerExtension extends \WPPayVendor\JMS\Serializer\Twig\SerializerBaseExtension
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    public function __construct(\WPPayVendor\JMS\Serializer\SerializerInterface $serializer, string $serializationFunctionsPrefix = '')
    {
        $this->serializer = $serializer;
        parent::__construct($serializationFunctionsPrefix);
    }
    /**
     * @return TwigFilter[]
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
     */
    public function getFilters()
    {
        return [new \WPPayVendor\Twig\TwigFilter($this->serializationFunctionsPrefix . 'serialize', [$this, 'serialize'])];
    }
    /**
     * @return TwigFunction[]
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
     */
    public function getFunctions()
    {
        return [new \WPPayVendor\Twig\TwigFunction($this->serializationFunctionsPrefix . 'serialization_context', '\\JMS\\Serializer\\SerializationContext::create')];
    }
    public function serialize(object $object, string $type = 'json', ?\WPPayVendor\JMS\Serializer\SerializationContext $context = null) : string
    {
        return $this->serializer->serialize($object, $type, $context);
    }
}
