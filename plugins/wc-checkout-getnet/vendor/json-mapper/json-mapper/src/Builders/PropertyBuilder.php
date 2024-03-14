<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Builders;

use CoffeeCode\JsonMapper\Enums\Visibility;
use CoffeeCode\JsonMapper\ValueObjects\ArrayInformation;
use CoffeeCode\JsonMapper\ValueObjects\Property;
use CoffeeCode\JsonMapper\ValueObjects\PropertyType;

class PropertyBuilder
{
    /** @var string */
    private $name;
    /** @var bool */
    private $isNullable;
    /** @var Visibility */
    private $visibility;
    /** @var PropertyType[] */
    private $types = [];

    private function __construct()
    {
    }

    public static function new(): self
    {
        return new self();
    }

    public function build(): Property
    {
        return new Property(
            $this->name,
            $this->visibility,
            $this->isNullable,
            ...$this->types
        );
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setTypes(PropertyType ...$types): self
    {
        $this->types = $types;
        return $this;
    }

    public function addType(string $type, ArrayInformation $arrayInformation): self
    {
        $this->types[] = new PropertyType($type, $arrayInformation);
        return $this;
    }

    public function addTypes(PropertyType ...$types): self
    {
        $this->types = array_merge($this->types, $types);
        return $this;
    }

    public function setIsNullable(bool $isNullable): self
    {
        $this->isNullable = $isNullable;
        return $this;
    }

    public function setVisibility(Visibility $visibility): self
    {
        $this->visibility = $visibility;
        return $this;
    }

    public function hasAnyType(): bool
    {
        return count($this->types) !== 0;
    }
}
