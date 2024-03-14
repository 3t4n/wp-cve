<?php

namespace WPPayForm\Framework\Database\Orm;

use BadMethodCallException;
use WPPayForm\Framework\Support\Collection;

trait ResourceAbleTrait
{
    /**
     * Transforms a resource
     * 
     * @param  array  $excludes
     * @return mixed
     */
	public function toResource($excludes = [])
    {
    	if ($this instanceof Model) {
    		throw new BadMethodCallException(
    			'Implement ' . __FUNCTION__ .' method in you ' . get_class($this) . ' Model.'
    		);
    	}

    	$items = [];

        foreach ($this->all() as $item) {
            $items[] = $item->toResource($excludes);
        }

        $collection = new Collection($items);

        if ($this instanceof Collection) {
        	return $collection;
        }

        return $this->setCollection($collection);
    }

    /**
     * Filter the resource to exclude properties
     * 
     * @param  array $resource
     * @param  array $excludes
     * @return array
     */
    public function filterResource($resource, $excludes)
    {
        if ($excludes) {
            foreach ($resource as $key => $value) {
                if (in_array($key, $excludes)) {
                    unset($resource[$key]);
                }
            }
        }

        return $resource;
    }
}
