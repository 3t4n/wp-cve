<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Middleware\Constructor;

use CoffeeCode\JsonMapper\Handler\FactoryRegistry;
use CoffeeCode\JsonMapper\Helpers\ScalarCaster;
use CoffeeCode\JsonMapper\JsonMapperInterface;
use CoffeeCode\JsonMapper\Middleware\AbstractMiddleware;
use CoffeeCode\JsonMapper\ValueObjects\PropertyMap;
use CoffeeCode\JsonMapper\Wrapper\ObjectWrapper;

class Constructor extends AbstractMiddleware
{
    /** @var FactoryRegistry */
    private $factoryRegistry;

    public function __construct(FactoryRegistry $factoryRegistry)
    {
        $this->factoryRegistry = $factoryRegistry;
    }

    public function handle(
        \stdClass $json,
        ObjectWrapper $object,
        PropertyMap $propertyMap,
        JsonMapperInterface $mapper
    ): void {
        if ($this->factoryRegistry->hasFactory($object->getName())) {
            return;
        }

        $reflectedConstructor = $object->getReflectedObject()->getConstructor();
        if (\is_null($reflectedConstructor) || $reflectedConstructor->getNumberOfParameters() === 0) {
            return;
        }

        $this->factoryRegistry->addFactory(
            $object->getName(),
            new DefaultFactory(
                $object->getName(),
                $reflectedConstructor,
                $mapper,
                new ScalarCaster(), // @TODO Copy current caster ??
                $this->factoryRegistry
            )
        );
    }
}
