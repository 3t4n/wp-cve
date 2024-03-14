<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\EventDispatcher;

use WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException;
use WPPayVendor\Psr\Container\ContainerInterface as PsrContainerInterface;
use WPPayVendor\Symfony\Component\DependencyInjection\ContainerInterface;
class LazyEventDispatcher extends \WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcher
{
    /**
     * @var PsrContainerInterface|ContainerInterface
     */
    private $container;
    /**
     * @param PsrContainerInterface|ContainerInterface $container
     */
    public function __construct($container)
    {
        if (!$container instanceof \WPPayVendor\Psr\Container\ContainerInterface && !$container instanceof \WPPayVendor\Symfony\Component\DependencyInjection\ContainerInterface) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('The container must be an instance of %s or %s (%s given).', \WPPayVendor\Psr\Container\ContainerInterface::class, \WPPayVendor\Symfony\Component\DependencyInjection\ContainerInterface::class, \is_object($container) ? \get_class($container) : \gettype($container)));
        }
        $this->container = $container;
    }
    /**
     * {@inheritdoc}
     */
    protected function initializeListeners(string $eventName, string $loweredClass, string $format) : array
    {
        $listeners = parent::initializeListeners($eventName, $loweredClass, $format);
        foreach ($listeners as &$listener) {
            if (!\is_array($listener[0]) || !\is_string($listener[0][0])) {
                continue;
            }
            if (!$this->container->has($listener[0][0])) {
                continue;
            }
            $listener[0][0] = $this->container->get($listener[0][0]);
        }
        return $listeners;
    }
}
