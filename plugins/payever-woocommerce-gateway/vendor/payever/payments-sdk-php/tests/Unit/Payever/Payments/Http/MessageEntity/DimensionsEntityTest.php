<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\DimensionsEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class DimensionsEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\DimensionsEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class DimensionsEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'height' => 100.1,
        'width' => 100.1,
        'length' => 100.1,
    );

    public function getEntity()
    {
        return new DimensionsEntity();
    }
}
