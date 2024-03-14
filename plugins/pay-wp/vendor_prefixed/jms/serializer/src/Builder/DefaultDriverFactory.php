<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Builder;

use WPPayVendor\Doctrine\Common\Annotations\Reader;
use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
use WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Metadata\Driver\AnnotationOrAttributeDriver;
use WPPayVendor\JMS\Serializer\Metadata\Driver\DefaultValuePropertyDriver;
use WPPayVendor\JMS\Serializer\Metadata\Driver\EnumPropertiesDriver;
use WPPayVendor\JMS\Serializer\Metadata\Driver\NullDriver;
use WPPayVendor\JMS\Serializer\Metadata\Driver\TypedPropertiesDriver;
use WPPayVendor\JMS\Serializer\Metadata\Driver\XmlDriver;
use WPPayVendor\JMS\Serializer\Metadata\Driver\YamlDriver;
use WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use WPPayVendor\JMS\Serializer\Type\Parser;
use WPPayVendor\JMS\Serializer\Type\ParserInterface;
use WPPayVendor\Metadata\Driver\DriverChain;
use WPPayVendor\Metadata\Driver\DriverInterface;
use WPPayVendor\Metadata\Driver\FileLocator;
use WPPayVendor\Symfony\Component\Yaml\Yaml;
final class DefaultDriverFactory implements \WPPayVendor\JMS\Serializer\Builder\DriverFactoryInterface
{
    /**
     * @var ParserInterface
     */
    private $typeParser;
    /**
     * @var bool
     */
    private $enableEnumSupport = \false;
    /**
     * @var PropertyNamingStrategyInterface
     */
    private $propertyNamingStrategy;
    /**
     * @var CompilableExpressionEvaluatorInterface
     */
    private $expressionEvaluator;
    public function __construct(\WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface $propertyNamingStrategy, ?\WPPayVendor\JMS\Serializer\Type\ParserInterface $typeParser = null, ?\WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface $expressionEvaluator = null)
    {
        $this->typeParser = $typeParser ?: new \WPPayVendor\JMS\Serializer\Type\Parser();
        $this->propertyNamingStrategy = $propertyNamingStrategy;
        $this->expressionEvaluator = $expressionEvaluator;
    }
    public function enableEnumSupport(bool $enableEnumSupport = \true) : void
    {
        $this->enableEnumSupport = $enableEnumSupport;
    }
    public function createDriver(array $metadataDirs, ?\WPPayVendor\Doctrine\Common\Annotations\Reader $annotationReader = null) : \WPPayVendor\Metadata\Driver\DriverInterface
    {
        if (\PHP_VERSION_ID < 80000 && empty($metadataDirs) && !\interface_exists(\WPPayVendor\Doctrine\Common\Annotations\Reader::class)) {
            throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException(\sprintf('To use "%s", either a list of metadata directories must be provided, the "doctrine/annotations" package installed, or use PHP 8.0 or later.', self::class));
        }
        /*
         * Build the sorted list of metadata drivers based on the environment. The final order should be:
         *
         * - YAML Driver
         * - XML Driver
         * - Annotations/Attributes Driver
         * - Null (Fallback) Driver
         */
        $metadataDrivers = [];
        if (\PHP_VERSION_ID >= 80000 || $annotationReader instanceof \WPPayVendor\Doctrine\Common\Annotations\Reader) {
            $metadataDrivers[] = new \WPPayVendor\JMS\Serializer\Metadata\Driver\AnnotationOrAttributeDriver($this->propertyNamingStrategy, $this->typeParser, $this->expressionEvaluator, $annotationReader);
        }
        if (!empty($metadataDirs)) {
            $fileLocator = new \WPPayVendor\Metadata\Driver\FileLocator($metadataDirs);
            \array_unshift($metadataDrivers, new \WPPayVendor\JMS\Serializer\Metadata\Driver\XmlDriver($fileLocator, $this->propertyNamingStrategy, $this->typeParser, $this->expressionEvaluator));
            if (\class_exists(\WPPayVendor\Symfony\Component\Yaml\Yaml::class)) {
                \array_unshift($metadataDrivers, new \WPPayVendor\JMS\Serializer\Metadata\Driver\YamlDriver($fileLocator, $this->propertyNamingStrategy, $this->typeParser, $this->expressionEvaluator));
            }
        }
        $driver = new \WPPayVendor\Metadata\Driver\DriverChain($metadataDrivers);
        $driver->addDriver(new \WPPayVendor\JMS\Serializer\Metadata\Driver\NullDriver($this->propertyNamingStrategy));
        if ($this->enableEnumSupport) {
            $driver = new \WPPayVendor\JMS\Serializer\Metadata\Driver\EnumPropertiesDriver($driver);
        }
        $driver = new \WPPayVendor\JMS\Serializer\Metadata\Driver\TypedPropertiesDriver($driver, $this->typeParser);
        if (\PHP_VERSION_ID >= 80000) {
            $driver = new \WPPayVendor\JMS\Serializer\Metadata\Driver\DefaultValuePropertyDriver($driver);
        }
        return $driver;
    }
}
