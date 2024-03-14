<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Builders;

use CoffeeCode\JsonMapper\Handler\FactoryRegistry;
use CoffeeCode\JsonMapper\Handler\PropertyMapper;
use CoffeeCode\JsonMapper\Helpers\IScalarCaster;

class PropertyMapperBuilder
{
    /** @var FactoryRegistry|null */
    private $classFactoryRegistry;
    /** @var FactoryRegistry|null */
    private $nonInstantiableTypeResolver;
    /** @var IScalarCaster|null */
    private $scalarCaster;

    public static function new(): PropertyMapperBuilder
    {
        return new PropertyMapperBuilder();
    }

    public function build(): PropertyMapper
    {
        return new PropertyMapper($this->classFactoryRegistry, $this->nonInstantiableTypeResolver, $this->scalarCaster);
    }

    public function withClassFactoryRegistry(FactoryRegistry $classFactoryRegistry): PropertyMapperBuilder
    {
        $this->classFactoryRegistry = $classFactoryRegistry;

        return $this;
    }

    public function withNonInstantiableTypeResolver(FactoryRegistry $nonInstantiableTypeResolver): PropertyMapperBuilder
    {
        $this->nonInstantiableTypeResolver = $nonInstantiableTypeResolver;

        return $this;
    }

    public function withScalarCaster(IScalarCaster $scalarCaster): PropertyMapperBuilder
    {
        $this->scalarCaster = $scalarCaster;

        return $this;
    }
}
