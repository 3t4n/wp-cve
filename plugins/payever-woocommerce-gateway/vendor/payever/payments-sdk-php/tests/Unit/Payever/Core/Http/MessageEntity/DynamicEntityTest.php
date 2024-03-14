<?php

namespace Payever\Tests\Unit\Payever\Core\Http\MessageEntity;

use Payever\Sdk\Core\Http\MessageEntity\DynamicEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class DynamicEntityTest
 *
 * @see \Payever\Sdk\Core\Http\MessageEntity\DynamicEntity
 *
 * @package Payever\Tests\Unit\Payever\Core\Http\MessageEntity
 */
class DynamicEntityTest extends AbstractMessageEntityTest
{
    /**
     * Dynamic entity should should accept arbitrary scheme
     *
     * @var array
     */
    protected static $scheme = array(
        'dynamic_field' => 'value',
        'dynamic_field2' => 150.5,
        'field_is_not_declared' => true,
    );

    public function getEntity()
    {
        return new DynamicEntity();
    }
}
