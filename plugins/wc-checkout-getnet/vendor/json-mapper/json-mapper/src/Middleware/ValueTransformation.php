<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Middleware;

use CoffeeCode\JsonMapper\JsonMapperInterface;
use CoffeeCode\JsonMapper\ValueObjects\PropertyMap;
use CoffeeCode\JsonMapper\Wrapper\ObjectWrapper;

class ValueTransformation extends AbstractMiddleware
{
    /** @var callable */
    private $mapFunction;

    /** @var bool */
    private $includeKey;

    public function __construct(callable $mapFunction, bool $includeKey = false)
    {
        $this->mapFunction = $mapFunction;
        $this->includeKey = $includeKey;
    }

    public function handle(
        \stdClass $json,
        ObjectWrapper $object,
        PropertyMap $propertyMap,
        JsonMapperInterface $mapper
    ): void {
        $mapFunction = $this->mapFunction;

        foreach ((array) $json as $key => $value) {
            if ($this->includeKey) {
                $json->$key = $mapFunction($key, $value);
                continue;
            }

            $json->$key = $mapFunction($value);
        }
    }
}
