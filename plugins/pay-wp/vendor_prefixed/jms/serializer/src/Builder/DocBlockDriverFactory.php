<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Builder;

use WPPayVendor\Doctrine\Common\Annotations\Reader;
use WPPayVendor\JMS\Serializer\Metadata\Driver\DocBlockDriver;
use WPPayVendor\JMS\Serializer\Type\ParserInterface;
use WPPayVendor\Metadata\Driver\DriverInterface;
class DocBlockDriverFactory implements \WPPayVendor\JMS\Serializer\Builder\DriverFactoryInterface
{
    /**
     * @var DriverFactoryInterface
     */
    private $driverFactoryToDecorate;
    /**
     * @var ParserInterface|null
     */
    private $typeParser;
    public function __construct(\WPPayVendor\JMS\Serializer\Builder\DriverFactoryInterface $driverFactoryToDecorate, ?\WPPayVendor\JMS\Serializer\Type\ParserInterface $typeParser = null)
    {
        $this->driverFactoryToDecorate = $driverFactoryToDecorate;
        $this->typeParser = $typeParser;
    }
    public function createDriver(array $metadataDirs, ?\WPPayVendor\Doctrine\Common\Annotations\Reader $annotationReader = null) : \WPPayVendor\Metadata\Driver\DriverInterface
    {
        $driver = $this->driverFactoryToDecorate->createDriver($metadataDirs, $annotationReader);
        return new \WPPayVendor\JMS\Serializer\Metadata\Driver\DocBlockDriver($driver, $this->typeParser);
    }
}
