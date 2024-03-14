<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\CancelPaymentResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\CancelPaymentResultEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\PaymentCallEntityTest;

/**
 * Class CancelPaymentResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\CancelPaymentResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class CancelPaymentResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => PaymentCallEntityTest::getScheme(),
            'result' => CancelPaymentResultEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new CancelPaymentResponse();
    }
}
