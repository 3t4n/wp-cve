<?php

namespace InstagramAPI\Model;

use InstagramAPI\Traits\ArrayLikeTrait;
use InstagramAPI\Traits\InitializerTrait;

/**
 * Class AbstractModel
 * @package InstagramAPI\Model
 */
abstract class AbstractModel implements \ArrayAccess
{
    use InitializerTrait, ArrayLikeTrait;

    /**
     * @var array
     */
    protected static $initPropertiesMap = [];

    /**
     * @return array
     */
    public static function getColumns()
    {
        return \array_keys(static::$initPropertiesMap);
    }
}