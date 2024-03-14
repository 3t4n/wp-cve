<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Concerns;

use Modular\ConnectorDependencies\Illuminate\Contracts\Support\Arrayable;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
/** @internal */
trait HasAttributes
{
    /**
     * @var
     */
    protected $attributes = [];
    /**
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixed
     */
    protected function getAttribute($key)
    {
        if (!$key) {
            return null;
        }
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key);
        }
        return null;
    }
    /**
     * Get array Attribute
     *
     * @return array
     */
    public final function getAttributes() : array
    {
        return $this->attributes;
    }
    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param string $key
     * @return bool
     */
    protected function hasGetMutator($key)
    {
        return \method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }
    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @return mixed
     */
    protected function mutateAttribute($key)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}();
    }
    /**
     * Get all of the attribute mutator methods.
     *
     * @param mixed $class
     * @return array
     */
    protected static function getMutatorMethods($class)
    {
        \preg_match_all('/(?<=^|;)get([^;]+?)Attribute(;|$)/', \implode(';', \get_class_methods($class)), $matches);
        return $matches[1];
    }
    /**
     * Set an attribute of all magic values.
     *
     * @param array $attributes
     * @return array
     */
    protected final function addMutatedAttributesToArray(array $attributes) : array
    {
        $mutatedAttributes = $this->getMutatorMethods($this);
        foreach ($mutatedAttributes as $key) {
            $name = Str::snake($key);
            // Next, we will call the mutator for this attribute so that we can get these
            // mutated attribute's actual values. After we finish mutating each of the
            // attributes we will return this final array of the mutated attributes.
            $attributes[$name] = $this->getAttribute($key);
        }
        return $attributes;
    }
    /**
     * Get the value of an attribute using its mutator for array conversion.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function mutateAttributeForArray($key, $value)
    {
        $value = $this->mutateAttribute($key, $value);
        return $value instanceof Arrayable ? $value->toArray() : $value;
    }
}
