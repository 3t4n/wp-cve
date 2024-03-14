<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper;

use CoffeeCode\JsonMapper\Cache\ArrayCache;
use CoffeeCode\JsonMapper\Dto\NamedMiddleware;
use CoffeeCode\JsonMapper\Enums\TextNotation;
use CoffeeCode\JsonMapper\Exception\BuilderException;
use CoffeeCode\JsonMapper\Handler\FactoryRegistry;
use CoffeeCode\JsonMapper\Handler\PropertyMapper;
use CoffeeCode\JsonMapper\Middleware\Attributes\Attributes;
use CoffeeCode\JsonMapper\Middleware\CaseConversion;
use CoffeeCode\JsonMapper\Middleware\Constructor\Constructor;
use CoffeeCode\JsonMapper\Middleware\Debugger;
use CoffeeCode\JsonMapper\Middleware\DocBlockAnnotations;
use CoffeeCode\JsonMapper\Middleware\FinalCallback;
use CoffeeCode\JsonMapper\Middleware\NamespaceResolver;
use CoffeeCode\JsonMapper\Middleware\Rename\Mapping;
use CoffeeCode\JsonMapper\Middleware\Rename\Rename;
use CoffeeCode\JsonMapper\Middleware\TypedProperties;
use CoffeeCode\Psr\Log\LoggerInterface;
use CoffeeCode\Psr\SimpleCache\CacheInterface;

/**
 * @template T of \JsonMapper\JsonMapperInterface
 */
class JsonMapperBuilder
{
    /**
     * @psalm-var class-string<T>
     */
    protected $jsonMapperClassName = JsonMapper::class;
    /** @var PropertyMapper */
    protected $propertyMapper;
    /** @var CacheInterface */
    protected $defaultCache;
    /** @var NamedMiddleware[] */
    protected $namedMiddleware = [];

    public static function new(): JsonMapperBuilder
    {
        return new JsonMapperBuilder();
    }

    public function __construct()
    {
        $this->withPropertyMapper(new PropertyMapper())
            ->withDefaultCache(new ArrayCache());
    }

    public function build(): JsonMapperInterface
    {
        if (empty($this->namedMiddleware)) {
            throw BuilderException::forBuildingWithoutMiddleware();
        }

        /** @var JsonMapperInterface $mapper */
        $mapper = new $this->jsonMapperClassName();
        $mapper->setPropertyMapper($this->propertyMapper);
        foreach ($this->namedMiddleware as $namedMiddleware) {
            $mapper->push($namedMiddleware->getMiddleware(), $namedMiddleware->getName());
        }

        return $mapper;
    }

    /** @psalm-param class-string<T> $jsonMapperClassName */
    public function withJsonMapperClassName(string $jsonMapperClassName): JsonMapperBuilder
    {
        $reflectedClass = new \ReflectionClass($jsonMapperClassName);
        if (!$reflectedClass->implementsInterface(JsonMapperInterface::class)) {
            throw BuilderException::invalidJsonMapperClassName($jsonMapperClassName);
        }

        $this->jsonMapperClassName = $jsonMapperClassName;

        return $this;
    }

    public function withPropertyMapper(PropertyMapper $propertyMapper): JsonMapperBuilder
    {
        $this->propertyMapper = $propertyMapper;

        return $this;
    }

    public function withDefaultCache(CacheInterface $defaultCache): JsonMapperBuilder
    {
        $this->defaultCache = $defaultCache;

        return $this;
    }

    public function withDocBlockAnnotationsMiddleware(?CacheInterface $cache = null): JsonMapperBuilder
    {
        return $this->withMiddleware(
            new DocBlockAnnotations($cache ?: $this->defaultCache),
            DocBlockAnnotations::class
        );
    }

    public function withNamespaceResolverMiddleware(?CacheInterface $cache = null): JsonMapperBuilder
    {
        return $this->withMiddleware(
            new NamespaceResolver($cache ?: $this->defaultCache),
            NamespaceResolver::class
        );
    }

    public function withTypedPropertiesMiddleware(?CacheInterface $cache = null): JsonMapperBuilder
    {
        return $this->withMiddleware(
            new TypedProperties($cache ?: $this->defaultCache),
            TypedProperties::class
        );
    }

    public function withAttributesMiddleware(): JsonMapperBuilder
    {
        return $this->withMiddleware(new Attributes(), Attributes::class);
    }

    public function withRenameMiddleware(Mapping ...$mapping): JsonMapperBuilder
    {
        return $this->withMiddleware(new Rename(...$mapping), Rename::class);
    }

    public function withCaseConversionMiddleware(
        TextNotation $searchSeparator,
        TextNotation $replacementSeparator
    ): JsonMapperBuilder {
        return $this->withMiddleware(
            new CaseConversion($searchSeparator, $replacementSeparator),
            CaseConversion::class
        );
    }

    public function withDebuggerMiddleware(LoggerInterface $logger): JsonMapperBuilder
    {
        return $this->withMiddleware(new Debugger($logger), Debugger::class);
    }

    public function withFinalCallbackMiddleware(
        callable $callback,
        bool $onlyApplyCallBackOnTopLevel = true
    ): JsonMapperBuilder {
        return $this->withMiddleware(new FinalCallback($callback, $onlyApplyCallBackOnTopLevel), FinalCallback::class);
    }

    public function withObjectConstructorMiddleware(FactoryRegistry $factoryRegistry): JsonMapperBuilder
    {
        // @TODO Add the registry from the builder context?
        return $this->withMiddleware(
            new Constructor($factoryRegistry), // How to get logical fallback the factory registry
            Constructor::class
        );
    }

    public function withMiddleware(callable $middleware, ?string $name = null): JsonMapperBuilder
    {
        $fallbackName = is_object($middleware) ? get_class($middleware) : '<anonymous>';
        $this->namedMiddleware[] = new NamedMiddleware($middleware, $name ?: $fallbackName);

        return $this;
    }
}
