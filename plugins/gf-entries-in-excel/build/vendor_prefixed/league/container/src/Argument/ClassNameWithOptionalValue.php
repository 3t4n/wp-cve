<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 08-March-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\League\Container\Argument;

class ClassNameWithOptionalValue implements ClassNameInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var mixed
     */
    private $optionalValue;

    /**
     * @param string $className
     * @param mixed $optionalValue
     */
    public function __construct(string $className, $optionalValue)
    {
        $this->className = $className;
        $this->optionalValue = $optionalValue;
    }

    /**
     * @inheritDoc
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    public function getOptionalValue()
    {
        return $this->optionalValue;
    }
}
