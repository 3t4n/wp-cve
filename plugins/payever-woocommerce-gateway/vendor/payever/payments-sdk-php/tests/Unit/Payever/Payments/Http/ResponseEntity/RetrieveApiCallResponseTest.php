<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\RetrieveApiCallResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Core\Http\MessageEntity\DynamicEntityTest;

/**
 * Class RetrieveApiCallResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\RetrieveApiCallResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class RetrieveApiCallResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => DynamicEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new RetrieveApiCallResponse();
    }
}
