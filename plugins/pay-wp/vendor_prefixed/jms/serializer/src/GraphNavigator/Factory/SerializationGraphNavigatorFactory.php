<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\GraphNavigator\Factory;

use WPPayVendor\JMS\Serializer\Accessor\AccessorStrategyInterface;
use WPPayVendor\JMS\Serializer\Accessor\DefaultAccessorStrategy;
use WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcher;
use WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\GraphNavigator\SerializationGraphNavigator;
use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\Handler\HandlerRegistryInterface;
use WPPayVendor\Metadata\MetadataFactoryInterface;
final class SerializationGraphNavigatorFactory implements \WPPayVendor\JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;
    /**
     * @var HandlerRegistryInterface
     */
    private $handlerRegistry;
    /**
     * @var AccessorStrategyInterface
     */
    private $accessor;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var ExpressionEvaluatorInterface
     */
    private $expressionEvaluator;
    public function __construct(\WPPayVendor\Metadata\MetadataFactoryInterface $metadataFactory, \WPPayVendor\JMS\Serializer\Handler\HandlerRegistryInterface $handlerRegistry, ?\WPPayVendor\JMS\Serializer\Accessor\AccessorStrategyInterface $accessor = null, ?\WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcherInterface $dispatcher = null, ?\WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface $expressionEvaluator = null)
    {
        $this->metadataFactory = $metadataFactory;
        $this->handlerRegistry = $handlerRegistry;
        $this->accessor = $accessor ?: new \WPPayVendor\JMS\Serializer\Accessor\DefaultAccessorStrategy();
        $this->dispatcher = $dispatcher ?: new \WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcher();
        $this->expressionEvaluator = $expressionEvaluator;
    }
    public function getGraphNavigator() : \WPPayVendor\JMS\Serializer\GraphNavigatorInterface
    {
        return new \WPPayVendor\JMS\Serializer\GraphNavigator\SerializationGraphNavigator($this->metadataFactory, $this->handlerRegistry, $this->accessor, $this->dispatcher, $this->expressionEvaluator);
    }
}
