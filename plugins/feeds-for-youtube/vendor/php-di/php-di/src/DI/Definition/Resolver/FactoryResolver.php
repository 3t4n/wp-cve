<?php

namespace SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Resolver;

use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Definition;
use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Exception\DefinitionException;
use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\FactoryDefinition;
use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Helper\DefinitionHelper;
use SmashBalloon\YoutubeFeed\Vendor\DI\Invoker\FactoryParameterResolver;
use SmashBalloon\YoutubeFeed\Vendor\Interop\Container\ContainerInterface;
use SmashBalloon\YoutubeFeed\Vendor\Invoker\Exception\NotCallableException;
use SmashBalloon\YoutubeFeed\Vendor\Invoker\Exception\NotEnoughParametersException;
use SmashBalloon\YoutubeFeed\Vendor\Invoker\Invoker;
use SmashBalloon\YoutubeFeed\Vendor\Invoker\ParameterResolver\AssociativeArrayResolver;
use SmashBalloon\YoutubeFeed\Vendor\Invoker\ParameterResolver\NumericArrayResolver;
use SmashBalloon\YoutubeFeed\Vendor\Invoker\ParameterResolver\ResolverChain;
/**
 * Resolves a factory definition to a value.
 *
 * @since 4.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FactoryResolver implements DefinitionResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Invoker|null
     */
    private $invoker;
    /**
     * @var DefinitionResolver
     */
    private $resolver;
    /**
     * The resolver needs a container. This container will be passed to the factory as a parameter
     * so that the factory can access other entries of the container.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, DefinitionResolver $resolver)
    {
        $this->container = $container;
        $this->resolver = $resolver;
    }
    /**
     * Resolve a factory definition to a value.
     *
     * This will call the callable of the definition.
     *
     * @param FactoryDefinition $definition
     *
     * {@inheritdoc}
     */
    public function resolve(Definition $definition, array $parameters = [])
    {
        if (!$this->invoker) {
            $parameterResolver = new ResolverChain([new AssociativeArrayResolver(), new FactoryParameterResolver($this->container), new NumericArrayResolver()]);
            $this->invoker = new Invoker($parameterResolver, $this->container);
        }
        $callable = $definition->getCallable();
        try {
            $providedParams = [$this->container, $definition];
            $extraParams = $this->resolveExtraParams($definition->getParameters());
            $providedParams = \array_merge($providedParams, $extraParams);
            return $this->invoker->call($callable, $providedParams);
        } catch (NotCallableException $e) {
            // Custom error message to help debugging
            if (\is_string($callable) && \class_exists($callable) && \method_exists($callable, '__invoke')) {
                throw new DefinitionException(\sprintf('Entry "%s" cannot be resolved: factory %s. Invokable classes cannot be automatically resolved if autowiring is disabled on the container, you need to enable autowiring or define the entry manually.', $definition->getName(), $e->getMessage()));
            }
            throw new DefinitionException(\sprintf('Entry "%s" cannot be resolved: factory %s', $definition->getName(), $e->getMessage()));
        } catch (NotEnoughParametersException $e) {
            throw new DefinitionException(\sprintf('Entry "%s" cannot be resolved: %s', $definition->getName(), $e->getMessage()));
        }
    }
    /**
     * {@inheritdoc}
     */
    public function isResolvable(Definition $definition, array $parameters = [])
    {
        return \true;
    }
    private function resolveExtraParams(array $params)
    {
        $resolved = [];
        foreach ($params as $key => $value) {
            if ($value instanceof DefinitionHelper) {
                // As per ObjectCreator::injectProperty, use '' for an anonymous sub-definition
                $value = $value->getDefinition('');
            }
            if (!$value instanceof Definition) {
                $resolved[$key] = $value;
            } else {
                $resolved[$key] = $this->resolver->resolve($value);
            }
        }
        return $resolved;
    }
}
