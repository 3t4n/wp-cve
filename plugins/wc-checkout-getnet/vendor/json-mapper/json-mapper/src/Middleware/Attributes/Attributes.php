<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Middleware\Attributes;

use CoffeeCode\JsonMapper\JsonMapperInterface;
use CoffeeCode\JsonMapper\Middleware\AbstractMiddleware;
use CoffeeCode\JsonMapper\ValueObjects\PropertyMap;
use CoffeeCode\JsonMapper\Wrapper\ObjectWrapper;

class Attributes extends AbstractMiddleware
{
    public function handle(
        \stdClass $json,
        ObjectWrapper $object,
        PropertyMap $propertyMap,
        JsonMapperInterface $mapper
    ): void {
        foreach ($object->getReflectedObject()->getProperties() as $property) {
            $attributes = $property->getAttributes(MapFrom::class);

            foreach ($attributes as $attribute) {
                /** @var MapFrom $mapFrom */
                $mapFrom = $attribute->newInstance();
                $source = $mapFrom->source;
                $target = $property->name;

                if ($source === $target) {
                    continue;
                }

                if (isset($json->$source)) {
                    $json->$target = $json->$source;
                    unset($json->$source);
                }
            }
        }
    }
}
