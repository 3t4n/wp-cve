<?php

namespace HQRentalsPlugin\HQRentalsQueries;

abstract class HQRentalsQueriesBaseClass
{
    abstract public function getAllMetaKey();

    abstract public function allToFrontEnd();

    abstract public function fillModelWithPosts($posts);

    public function getSingleProperty($property, $default = '')
    {
        return empty($property) ? $default : $property;
    }

    public function parseObject($properties, $objectToParse)
    {
        $object = new \stdClass();
        foreach ($properties as $property) {
            if (is_array($property)) {
                $object->{$property['property_name']} = $property['values'];
            } else {
                $object->{$property} = $this->getSingleProperty($objectToParse->{$property});
            }
        }
        return $object;
    }
}
