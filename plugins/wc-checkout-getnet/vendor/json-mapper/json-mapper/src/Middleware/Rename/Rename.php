<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Middleware\Rename;

use CoffeeCode\JsonMapper\JsonMapperInterface;
use CoffeeCode\JsonMapper\Middleware\AbstractMiddleware;
use CoffeeCode\JsonMapper\ValueObjects\PropertyMap;
use CoffeeCode\JsonMapper\Wrapper\ObjectWrapper;

class Rename extends AbstractMiddleware
{
    /** @var Mapping[] */
    private $mapping;

    public function __construct(Mapping ...$mapping)
    {
        $this->mapping = $mapping;
    }

    public function addMapping(string $class, string $from, string $to): void
    {
        $this->mapping[] = new Mapping($class, $from, $to);
    }

    public function handle(
        \stdClass $json,
        ObjectWrapper $object,
        PropertyMap $propertyMap,
        JsonMapperInterface $mapper
    ): void {
        $mapping = \array_filter($this->mapping, static function ($map) use ($object) {
            return $map->getClass() === $object->getName();
        });
        foreach ($mapping as $map) {
            $from = $map->getFrom();
            $to = $map->getTo();

            if (isset($json->$from)) {
                $json->$to = $json->$from;
                unset($json->$from);
            }
        }
    }
}
