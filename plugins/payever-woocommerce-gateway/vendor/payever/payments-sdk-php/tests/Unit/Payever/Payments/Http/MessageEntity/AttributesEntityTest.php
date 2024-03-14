<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\AttributesEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class AttributesEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\AttributesEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class AttributesEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'weight' => 100,
        'dimensions' => array(),
    );

    public static function getScheme()
    {
        $scheme = static::$scheme;

        $scheme['dimensions'] = DimensionsEntityTest::getScheme();

        return $scheme;
    }

    public function getEntity()
    {
        return new AttributesEntity();
    }
}
