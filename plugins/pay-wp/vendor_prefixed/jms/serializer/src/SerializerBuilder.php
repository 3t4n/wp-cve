<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer;

use WPPayVendor\Doctrine\Common\Annotations\AnnotationReader;
use WPPayVendor\Doctrine\Common\Annotations\CachedReader;
use WPPayVendor\Doctrine\Common\Annotations\PsrCachedReader;
use WPPayVendor\Doctrine\Common\Annotations\Reader;
use WPPayVendor\Doctrine\Common\Cache\FilesystemCache;
use WPPayVendor\JMS\Serializer\Accessor\AccessorStrategyInterface;
use WPPayVendor\JMS\Serializer\Accessor\DefaultAccessorStrategy;
use WPPayVendor\JMS\Serializer\Builder\DefaultDriverFactory;
use WPPayVendor\JMS\Serializer\Builder\DocBlockDriverFactory;
use WPPayVendor\JMS\Serializer\Builder\DriverFactoryInterface;
use WPPayVendor\JMS\Serializer\Construction\ObjectConstructorInterface;
use WPPayVendor\JMS\Serializer\Construction\UnserializeObjectConstructor;
use WPPayVendor\JMS\Serializer\ContextFactory\CallableDeserializationContextFactory;
use WPPayVendor\JMS\Serializer\ContextFactory\CallableSerializationContextFactory;
use WPPayVendor\JMS\Serializer\ContextFactory\DeserializationContextFactoryInterface;
use WPPayVendor\JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcher;
use WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use WPPayVendor\JMS\Serializer\EventDispatcher\Subscriber\DoctrineProxySubscriber;
use WPPayVendor\JMS\Serializer\EventDispatcher\Subscriber\EnumSubscriber;
use WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException;
use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
use WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\GraphNavigator\Factory\DeserializationGraphNavigatorFactory;
use WPPayVendor\JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface;
use WPPayVendor\JMS\Serializer\GraphNavigator\Factory\SerializationGraphNavigatorFactory;
use WPPayVendor\JMS\Serializer\Handler\ArrayCollectionHandler;
use WPPayVendor\JMS\Serializer\Handler\DateHandler;
use WPPayVendor\JMS\Serializer\Handler\EnumHandler;
use WPPayVendor\JMS\Serializer\Handler\HandlerRegistry;
use WPPayVendor\JMS\Serializer\Handler\HandlerRegistryInterface;
use WPPayVendor\JMS\Serializer\Handler\IteratorHandler;
use WPPayVendor\JMS\Serializer\Handler\StdClassHandler;
use WPPayVendor\JMS\Serializer\Naming\CamelCaseNamingStrategy;
use WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use WPPayVendor\JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use WPPayVendor\JMS\Serializer\Type\Parser;
use WPPayVendor\JMS\Serializer\Type\ParserInterface;
use WPPayVendor\JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory;
use WPPayVendor\JMS\Serializer\Visitor\Factory\JsonDeserializationVisitorFactory;
use WPPayVendor\JMS\Serializer\Visitor\Factory\JsonSerializationVisitorFactory;
use WPPayVendor\JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use WPPayVendor\JMS\Serializer\Visitor\Factory\XmlDeserializationVisitorFactory;
use WPPayVendor\JMS\Serializer\Visitor\Factory\XmlSerializationVisitorFactory;
use WPPayVendor\Metadata\Cache\CacheInterface;
use WPPayVendor\Metadata\Cache\FileCache;
use WPPayVendor\Metadata\MetadataFactory;
use WPPayVendor\Metadata\MetadataFactoryInterface;
use WPPayVendor\Symfony\Component\Cache\Adapter\FilesystemAdapter;
/**
 * Builder for serializer instances.
 *
 * This object makes serializer construction a breeze for projects that do not use
 * any special dependency injection container.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
final class SerializerBuilder
{
    /**
     * @var string[]
     */
    private $metadataDirs = [];
    /**
     * @var HandlerRegistryInterface
     */
    private $handlerRegistry;
    /**
     * @var bool
     */
    private $handlersConfigured = \false;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var bool
     */
    private $enableEnumSupport = \false;
    /**
     * @var bool
     */
    private $listenersConfigured = \false;
    /**
     * @var ObjectConstructorInterface
     */
    private $objectConstructor;
    /**
     * @var SerializationVisitorFactory[]
     */
    private $serializationVisitors;
    /**
     * @var DeserializationVisitorFactory[]
     */
    private $deserializationVisitors;
    /**
     * @var bool
     */
    private $deserializationVisitorsAdded = \false;
    /**
     * @var bool
     */
    private $serializationVisitorsAdded = \false;
    /**
     * @var PropertyNamingStrategyInterface
     */
    private $propertyNamingStrategy;
    /**
     * @var bool
     */
    private $debug = \false;
    /**
     * @var string
     */
    private $cacheDir;
    /**
     * @var Reader
     */
    private $annotationReader;
    /**
     * @var bool
     */
    private $includeInterfaceMetadata = \false;
    /**
     * @var DriverFactoryInterface
     */
    private $driverFactory;
    /**
     * @var SerializationContextFactoryInterface
     */
    private $serializationContextFactory;
    /**
     * @var DeserializationContextFactoryInterface
     */
    private $deserializationContextFactory;
    /**
     * @var ParserInterface
     */
    private $typeParser;
    /**
     * @var ExpressionEvaluatorInterface
     */
    private $expressionEvaluator;
    /**
     * @var AccessorStrategyInterface
     */
    private $accessorStrategy;
    /**
     * @var CacheInterface
     */
    private $metadataCache;
    /**
     * @var bool
     */
    private $docBlockTyperResolver;
    /**
     * @param mixed ...$args
     *
     * @return SerializerBuilder
     */
    public static function create(...$args) : self
    {
        return new static(...$args);
    }
    public function __construct(?\WPPayVendor\JMS\Serializer\Handler\HandlerRegistryInterface $handlerRegistry = null, ?\WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcherInterface $eventDispatcher = null)
    {
        $this->typeParser = new \WPPayVendor\JMS\Serializer\Type\Parser();
        $this->handlerRegistry = $handlerRegistry ?: new \WPPayVendor\JMS\Serializer\Handler\HandlerRegistry();
        $this->eventDispatcher = $eventDispatcher ?: new \WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcher();
        $this->serializationVisitors = [];
        $this->deserializationVisitors = [];
        if ($handlerRegistry) {
            $this->handlersConfigured = \true;
        }
        if ($eventDispatcher) {
            $this->listenersConfigured = \true;
        }
    }
    public function setAccessorStrategy(\WPPayVendor\JMS\Serializer\Accessor\AccessorStrategyInterface $accessorStrategy) : self
    {
        $this->accessorStrategy = $accessorStrategy;
        return $this;
    }
    private function getAccessorStrategy() : \WPPayVendor\JMS\Serializer\Accessor\AccessorStrategyInterface
    {
        if (!$this->accessorStrategy) {
            $this->accessorStrategy = new \WPPayVendor\JMS\Serializer\Accessor\DefaultAccessorStrategy($this->expressionEvaluator);
        }
        return $this->accessorStrategy;
    }
    public function setExpressionEvaluator(\WPPayVendor\JMS\Serializer\Expression\ExpressionEvaluatorInterface $expressionEvaluator) : self
    {
        $this->expressionEvaluator = $expressionEvaluator;
        return $this;
    }
    public function setTypeParser(\WPPayVendor\JMS\Serializer\Type\ParserInterface $parser) : self
    {
        $this->typeParser = $parser;
        return $this;
    }
    public function setAnnotationReader(\WPPayVendor\Doctrine\Common\Annotations\Reader $reader) : self
    {
        $this->annotationReader = $reader;
        return $this;
    }
    public function setDebug(bool $bool) : self
    {
        $this->debug = $bool;
        return $this;
    }
    public function setCacheDir(string $dir) : self
    {
        if (!\is_dir($dir)) {
            $this->createDir($dir);
        }
        if (!\is_writable($dir)) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('The cache directory "%s" is not writable.', $dir));
        }
        $this->cacheDir = $dir;
        return $this;
    }
    public function addDefaultHandlers() : self
    {
        $this->handlersConfigured = \true;
        $this->handlerRegistry->registerSubscribingHandler(new \WPPayVendor\JMS\Serializer\Handler\DateHandler());
        $this->handlerRegistry->registerSubscribingHandler(new \WPPayVendor\JMS\Serializer\Handler\StdClassHandler());
        $this->handlerRegistry->registerSubscribingHandler(new \WPPayVendor\JMS\Serializer\Handler\ArrayCollectionHandler());
        $this->handlerRegistry->registerSubscribingHandler(new \WPPayVendor\JMS\Serializer\Handler\IteratorHandler());
        if ($this->enableEnumSupport) {
            $this->handlerRegistry->registerSubscribingHandler(new \WPPayVendor\JMS\Serializer\Handler\EnumHandler());
        }
        return $this;
    }
    public function configureHandlers(\Closure $closure) : self
    {
        $this->handlersConfigured = \true;
        $closure($this->handlerRegistry);
        return $this;
    }
    public function addDefaultListeners() : self
    {
        $this->listenersConfigured = \true;
        $this->eventDispatcher->addSubscriber(new \WPPayVendor\JMS\Serializer\EventDispatcher\Subscriber\DoctrineProxySubscriber());
        if ($this->enableEnumSupport) {
            $this->eventDispatcher->addSubscriber(new \WPPayVendor\JMS\Serializer\EventDispatcher\Subscriber\EnumSubscriber());
        }
        return $this;
    }
    public function configureListeners(\Closure $closure) : self
    {
        $this->listenersConfigured = \true;
        $closure($this->eventDispatcher);
        return $this;
    }
    public function setObjectConstructor(\WPPayVendor\JMS\Serializer\Construction\ObjectConstructorInterface $constructor) : self
    {
        $this->objectConstructor = $constructor;
        return $this;
    }
    public function setPropertyNamingStrategy(\WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface $propertyNamingStrategy) : self
    {
        $this->propertyNamingStrategy = $propertyNamingStrategy;
        return $this;
    }
    public function setSerializationVisitor(string $format, \WPPayVendor\JMS\Serializer\Visitor\Factory\SerializationVisitorFactory $visitor) : self
    {
        $this->serializationVisitorsAdded = \true;
        $this->serializationVisitors[$format] = $visitor;
        return $this;
    }
    public function setDeserializationVisitor(string $format, \WPPayVendor\JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory $visitor) : self
    {
        $this->deserializationVisitorsAdded = \true;
        $this->deserializationVisitors[$format] = $visitor;
        return $this;
    }
    public function addDefaultSerializationVisitors() : self
    {
        $this->serializationVisitorsAdded = \true;
        $this->serializationVisitors = ['xml' => new \WPPayVendor\JMS\Serializer\Visitor\Factory\XmlSerializationVisitorFactory(), 'json' => new \WPPayVendor\JMS\Serializer\Visitor\Factory\JsonSerializationVisitorFactory()];
        return $this;
    }
    public function addDefaultDeserializationVisitors() : self
    {
        $this->deserializationVisitorsAdded = \true;
        $this->deserializationVisitors = ['xml' => new \WPPayVendor\JMS\Serializer\Visitor\Factory\XmlDeserializationVisitorFactory(), 'json' => new \WPPayVendor\JMS\Serializer\Visitor\Factory\JsonDeserializationVisitorFactory()];
        return $this;
    }
    /**
     * @param bool $include Whether to include the metadata from the interfaces
     *
     * @return SerializerBuilder
     */
    public function includeInterfaceMetadata(bool $include) : self
    {
        $this->includeInterfaceMetadata = $include;
        return $this;
    }
    /**
     * Sets a map of namespace prefixes to directories.
     *
     * This method overrides any previously defined directories.
     *
     * @param array <string,string> $namespacePrefixToDirMap
     *
     * @return SerializerBuilder
     *
     * @throws InvalidArgumentException When a directory does not exist.
     */
    public function setMetadataDirs(array $namespacePrefixToDirMap) : self
    {
        foreach ($namespacePrefixToDirMap as $dir) {
            if (!\is_dir($dir)) {
                throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('The directory "%s" does not exist.', $dir));
            }
        }
        $this->metadataDirs = $namespacePrefixToDirMap;
        return $this;
    }
    /**
     * Adds a directory where the serializer will look for class metadata.
     *
     * The namespace prefix will make the names of the actual metadata files a bit shorter. For example, let's assume
     * that you have a directory where you only store metadata files for the ``MyApplication\Entity`` namespace.
     *
     * If you use an empty prefix, your metadata files would need to look like:
     *
     * ``my-dir/MyApplication.Entity.SomeObject.yml``
     * ``my-dir/MyApplication.Entity.OtherObject.xml``
     *
     * If you use ``MyApplication\Entity`` as prefix, your metadata files would need to look like:
     *
     * ``my-dir/SomeObject.yml``
     * ``my-dir/OtherObject.yml``
     *
     * Please keep in mind that you currently may only have one directory per namespace prefix.
     *
     * @param string $dir             The directory where metadata files are located.
     * @param string $namespacePrefix An optional prefix if you only store metadata for specific namespaces in this directory.
     *
     * @return SerializerBuilder
     *
     * @throws InvalidArgumentException When a directory does not exist.
     * @throws InvalidArgumentException When a directory has already been registered.
     */
    public function addMetadataDir(string $dir, string $namespacePrefix = '') : self
    {
        if (!\is_dir($dir)) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('The directory "%s" does not exist.', $dir));
        }
        if (isset($this->metadataDirs[$namespacePrefix])) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('There is already a directory configured for the namespace prefix "%s". Please use replaceMetadataDir() to override directories.', $namespacePrefix));
        }
        $this->metadataDirs[$namespacePrefix] = $dir;
        return $this;
    }
    /**
     * Adds a map of namespace prefixes to directories.
     *
     * @param array <string,string> $namespacePrefixToDirMap
     *
     * @return SerializerBuilder
     */
    public function addMetadataDirs(array $namespacePrefixToDirMap) : self
    {
        foreach ($namespacePrefixToDirMap as $prefix => $dir) {
            $this->addMetadataDir($dir, $prefix);
        }
        return $this;
    }
    /**
     * Similar to addMetadataDir(), but overrides an existing entry.
     *
     * @return SerializerBuilder
     *
     * @throws InvalidArgumentException When a directory does not exist.
     * @throws InvalidArgumentException When no directory is configured for the ns prefix.
     */
    public function replaceMetadataDir(string $dir, string $namespacePrefix = '') : self
    {
        if (!\is_dir($dir)) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('The directory "%s" does not exist.', $dir));
        }
        if (!isset($this->metadataDirs[$namespacePrefix])) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException(\sprintf('There is no directory configured for namespace prefix "%s". Please use addMetadataDir() for adding new directories.', $namespacePrefix));
        }
        $this->metadataDirs[$namespacePrefix] = $dir;
        return $this;
    }
    public function setMetadataDriverFactory(\WPPayVendor\JMS\Serializer\Builder\DriverFactoryInterface $driverFactory) : self
    {
        $this->driverFactory = $driverFactory;
        return $this;
    }
    /**
     * @param SerializationContextFactoryInterface|callable $serializationContextFactory
     */
    public function setSerializationContextFactory($serializationContextFactory) : self
    {
        if ($serializationContextFactory instanceof \WPPayVendor\JMS\Serializer\ContextFactory\SerializationContextFactoryInterface) {
            $this->serializationContextFactory = $serializationContextFactory;
        } elseif (\is_callable($serializationContextFactory)) {
            $this->serializationContextFactory = new \WPPayVendor\JMS\Serializer\ContextFactory\CallableSerializationContextFactory($serializationContextFactory);
        } else {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException('expected SerializationContextFactoryInterface or callable.');
        }
        return $this;
    }
    /**
     * @param DeserializationContextFactoryInterface|callable $deserializationContextFactory
     */
    public function setDeserializationContextFactory($deserializationContextFactory) : self
    {
        if ($deserializationContextFactory instanceof \WPPayVendor\JMS\Serializer\ContextFactory\DeserializationContextFactoryInterface) {
            $this->deserializationContextFactory = $deserializationContextFactory;
        } elseif (\is_callable($deserializationContextFactory)) {
            $this->deserializationContextFactory = new \WPPayVendor\JMS\Serializer\ContextFactory\CallableDeserializationContextFactory($deserializationContextFactory);
        } else {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException('expected DeserializationContextFactoryInterface or callable.');
        }
        return $this;
    }
    public function enableEnumSupport(bool $enableEnumSupport = \true) : self
    {
        if ($enableEnumSupport && \PHP_VERSION_ID < 80100) {
            throw new \WPPayVendor\JMS\Serializer\Exception\InvalidArgumentException('Enum support can be enabled only on PHP 8.1 or higher.');
        }
        $this->enableEnumSupport = $enableEnumSupport;
        return $this;
    }
    public function setMetadataCache(\WPPayVendor\Metadata\Cache\CacheInterface $cache) : self
    {
        $this->metadataCache = $cache;
        return $this;
    }
    public function setDocBlockTypeResolver(bool $docBlockTypeResolver) : self
    {
        $this->docBlockTyperResolver = $docBlockTypeResolver;
        return $this;
    }
    public function build() : \WPPayVendor\JMS\Serializer\Serializer
    {
        $annotationReader = $this->annotationReader;
        if (null === $annotationReader && \class_exists(\WPPayVendor\Doctrine\Common\Annotations\AnnotationReader::class)) {
            $annotationReader = $this->decorateAnnotationReader(new \WPPayVendor\Doctrine\Common\Annotations\AnnotationReader());
        }
        if (null === $this->driverFactory) {
            $this->initializePropertyNamingStrategy();
            $this->driverFactory = new \WPPayVendor\JMS\Serializer\Builder\DefaultDriverFactory($this->propertyNamingStrategy, $this->typeParser, $this->expressionEvaluator instanceof \WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface ? $this->expressionEvaluator : null);
            $this->driverFactory->enableEnumSupport($this->enableEnumSupport);
        }
        if ($this->docBlockTyperResolver) {
            $this->driverFactory = new \WPPayVendor\JMS\Serializer\Builder\DocBlockDriverFactory($this->driverFactory, $this->typeParser);
        }
        $metadataDriver = $this->driverFactory->createDriver($this->metadataDirs, $annotationReader);
        $metadataFactory = new \WPPayVendor\Metadata\MetadataFactory($metadataDriver, null, $this->debug);
        $metadataFactory->setIncludeInterfaces($this->includeInterfaceMetadata);
        if (null !== $this->metadataCache) {
            $metadataFactory->setCache($this->metadataCache);
        } elseif (null !== $this->cacheDir) {
            $this->createDir($this->cacheDir . '/metadata');
            $metadataFactory->setCache(new \WPPayVendor\Metadata\Cache\FileCache($this->cacheDir . '/metadata'));
        }
        if (!$this->handlersConfigured) {
            $this->addDefaultHandlers();
        }
        if (!$this->listenersConfigured) {
            $this->addDefaultListeners();
        }
        if (!$this->serializationVisitorsAdded) {
            $this->addDefaultSerializationVisitors();
        }
        if (!$this->deserializationVisitorsAdded) {
            $this->addDefaultDeserializationVisitors();
        }
        $navigatorFactories = [\WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION => $this->getSerializationNavigatorFactory($metadataFactory), \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION => $this->getDeserializationNavigatorFactory($metadataFactory)];
        return new \WPPayVendor\JMS\Serializer\Serializer($metadataFactory, $navigatorFactories, $this->serializationVisitors, $this->deserializationVisitors, $this->serializationContextFactory, $this->deserializationContextFactory, $this->typeParser);
    }
    private function getSerializationNavigatorFactory(\WPPayVendor\Metadata\MetadataFactoryInterface $metadataFactory) : \WPPayVendor\JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface
    {
        return new \WPPayVendor\JMS\Serializer\GraphNavigator\Factory\SerializationGraphNavigatorFactory($metadataFactory, $this->handlerRegistry, $this->getAccessorStrategy(), $this->eventDispatcher, $this->expressionEvaluator);
    }
    private function getDeserializationNavigatorFactory(\WPPayVendor\Metadata\MetadataFactoryInterface $metadataFactory) : \WPPayVendor\JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface
    {
        return new \WPPayVendor\JMS\Serializer\GraphNavigator\Factory\DeserializationGraphNavigatorFactory($metadataFactory, $this->handlerRegistry, $this->objectConstructor ?: new \WPPayVendor\JMS\Serializer\Construction\UnserializeObjectConstructor(), $this->getAccessorStrategy(), $this->eventDispatcher, $this->expressionEvaluator);
    }
    private function initializePropertyNamingStrategy() : void
    {
        if (null !== $this->propertyNamingStrategy) {
            return;
        }
        $this->propertyNamingStrategy = new \WPPayVendor\JMS\Serializer\Naming\SerializedNameAnnotationStrategy(new \WPPayVendor\JMS\Serializer\Naming\CamelCaseNamingStrategy());
    }
    private function createDir(string $dir) : void
    {
        if (\is_dir($dir)) {
            return;
        }
        if (\false === @\mkdir($dir, 0777, \true) && \false === \is_dir($dir)) {
            throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException(\sprintf('Could not create directory "%s".', $dir));
        }
    }
    private function decorateAnnotationReader(\WPPayVendor\Doctrine\Common\Annotations\Reader $annotationReader) : \WPPayVendor\Doctrine\Common\Annotations\Reader
    {
        if (null !== $this->cacheDir) {
            $this->createDir($this->cacheDir . '/annotations');
            if (\class_exists(\WPPayVendor\Symfony\Component\Cache\Adapter\FilesystemAdapter::class)) {
                $annotationsCache = new \WPPayVendor\Symfony\Component\Cache\Adapter\FilesystemAdapter('', 0, $this->cacheDir . '/annotations');
                $annotationReader = new \WPPayVendor\Doctrine\Common\Annotations\PsrCachedReader($annotationReader, $annotationsCache, $this->debug);
            } elseif (\class_exists(\WPPayVendor\Doctrine\Common\Cache\FilesystemCache::class) && \class_exists(\WPPayVendor\Doctrine\Common\Annotations\CachedReader::class)) {
                $annotationsCache = new \WPPayVendor\Doctrine\Common\Cache\FilesystemCache($this->cacheDir . '/annotations');
                $annotationReader = new \WPPayVendor\Doctrine\Common\Annotations\CachedReader($annotationReader, $annotationsCache, $this->debug);
            }
        }
        return $annotationReader;
    }
}
