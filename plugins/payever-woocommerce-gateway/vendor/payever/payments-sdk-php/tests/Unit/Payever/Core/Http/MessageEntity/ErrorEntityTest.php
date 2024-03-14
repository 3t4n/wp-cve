<?php

namespace Payever\Tests\Unit\Payever\Core\Http\MessageEntity;

use Payever\Sdk\Core\Http\MessageEntity\ErrorEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class ErrorEntityTest
 *
 * @see \Payever\Sdk\Core\Http\MessageEntity\ErrorEntity
 *
 * @package Payever\Tests\Unit\Payever\Core\Http\MessageEntity
 */
class ErrorEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'message' => 'Error occured'
    );

    public function testEntity()
    {
        $this->assertEntityScheme($this->getEntity(), false);
    }

    public function getEntity()
    {
        return new ErrorEntity(static::getScheme());
    }

    public function testStringPayload()
    {
        $message = 'error_message';
        $entity = new ErrorEntity($message);

        $this->assertEquals($message, $entity->getMessage());
    }
}
