<?php

namespace UpsFreeVendor\Octolize\Ups\RestApi;

trait ObjectProperties
{
    private function prepare_object_properties($object) : array
    {
        $properties = [];
        $methods = \get_class_methods($object);
        foreach ($methods as $method) {
            if (\strpos($method, 'get') === 0) {
                $property = \substr($method, 3);
                $value = $object->{$method}();
                if (\is_array($value)) {
                    $value = $this->prepare_array_properties($value);
                } elseif (\is_object($value)) {
                    $value = $this->prepare_object_properties($value);
                } else {
                    $value = (string) $value;
                }
                if (!empty($value)) {
                    $properties[$property] = $value;
                }
            }
        }
        return $properties;
    }
    private function prepare_array_properties($array) : array
    {
        $properties = [];
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $properties[$key] = $this->prepare_array_properties($value);
            } elseif (\is_object($value)) {
                $properties[$key] = $this->prepare_object_properties($value);
            } else {
                if (!empty($value)) {
                    $properties[$key] = $value;
                }
            }
        }
        return $properties;
    }
}
