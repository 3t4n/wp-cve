<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Builder;

use WPPayVendor\Doctrine\Common\Annotations\Reader;
use WPPayVendor\JMS\Serializer\Exception\LogicException;
use WPPayVendor\Metadata\Driver\DriverInterface;
final class CallbackDriverFactory implements \WPPayVendor\JMS\Serializer\Builder\DriverFactoryInterface
{
    /**
     * @var callable
     * @phpstan-var callable(array $metadataDirs, Reader|null $reader): DriverInterface
     */
    private $callback;
    /**
     * @phpstan-param callable(array $metadataDirs, Reader|null $reader): DriverInterface $callable
     */
    public function __construct(callable $callable)
    {
        $this->callback = $callable;
    }
    public function createDriver(array $metadataDirs, ?\WPPayVendor\Doctrine\Common\Annotations\Reader $reader = null) : \WPPayVendor\Metadata\Driver\DriverInterface
    {
        $driver = \call_user_func($this->callback, $metadataDirs, $reader);
        if (!$driver instanceof \WPPayVendor\Metadata\Driver\DriverInterface) {
            throw new \WPPayVendor\JMS\Serializer\Exception\LogicException('The callback must return an instance of DriverInterface.');
        }
        return $driver;
    }
}
