<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Controller;

use Modular\ConnectorDependencies\Ares\Framework\Concerns\HasAttributes;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
/** @internal */
class Controller
{
    use HasAttributes;
    /**
     * Dynamically retrieve attributes on the controller.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }
    /**
     * Get the classBaseName without 'controller'
     *
     * @return string
     * @throws \ReflectionException
     */
    public static final function getTemplate() : string
    {
        $reflectionClass = new \ReflectionClass(static::class);
        return \str_replace('-controller', '', Str::snake($reflectionClass->getShortName(), '-')) . '-controller';
    }
    /**
     * Set Controller Data
     *
     * Set the Controller raw data for this Controller
     *
     * @param array $attributes
     * @return void
     */
    public final function attributesToArray(array $attributes)
    {
        $this->attributes = $attributes;
        $this->attributes = $this->addContentAttributesToArray($this->attributes);
        $this->attributes = $this->addMutatedAttributesToArray($this->attributes);
    }
    /**
     * Add post information to Attributes array
     *
     * @param array $attributes
     * @return array
     */
    private function addContentAttributesToArray(array $attributes) : array
    {
        global $post;
        // Only set data from $post to App class
        if (!empty($post)) {
            $attributes['post'] = $post;
        }
        return $attributes;
    }
}
