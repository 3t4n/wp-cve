<?php

namespace SmashBalloon\YoutubeFeed\Vendor\DI\Definition\Source;

use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\EntryReference;
use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\ObjectDefinition;
use SmashBalloon\YoutubeFeed\Vendor\DI\Definition\ObjectDefinition\MethodInjection;
/**
 * Reads DI class definitions using reflection.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Autowiring implements DefinitionSource
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition($name)
    {
        if (!\class_exists($name) && !\interface_exists($name)) {
            return null;
        }
        $definition = new ObjectDefinition($name);
        // Constructor
        $class = new \ReflectionClass($name);
        $constructor = $class->getConstructor();
        if ($constructor && $constructor->isPublic()) {
            $definition->setConstructorInjection(MethodInjection::constructor($this->getParametersDefinition($constructor)));
        }
        return $definition;
    }
    /**
     * Read the type-hinting from the parameters of the function.
     */
	private function getParametersDefinition(\ReflectionFunctionAbstract $constructor)
	{
		$parameters = [];
		foreach ($constructor->getParameters() as $index => $parameter) {
			// Skip optional parameters
			if ($parameter->isOptional()) {
				continue;
			}
			if (version_compare(phpversion(), '8.0.0') >= 0) {
				$parameterClass = $parameter->getType() && ! $parameter->getType()->isBuiltin() ? new \ReflectionClass($parameter->getType()->getName()) : null;
			} else {
				$parameterClass = $parameter->getClass();
			}
			if ($parameterClass) {
				$parameters[$index] = new EntryReference($parameterClass->getName());
			}
		}
		return $parameters;
	}
}
