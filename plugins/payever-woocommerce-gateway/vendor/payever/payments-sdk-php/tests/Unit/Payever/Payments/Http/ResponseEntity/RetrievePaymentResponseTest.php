<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\RetrievePaymentResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\RetrievePaymentResultEntityTest;

/**
 * Class RetrievePaymentResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\RetrievePaymentResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class RetrievePaymentResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'result' => RetrievePaymentResultEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new RetrievePaymentResponse();
    }
}
