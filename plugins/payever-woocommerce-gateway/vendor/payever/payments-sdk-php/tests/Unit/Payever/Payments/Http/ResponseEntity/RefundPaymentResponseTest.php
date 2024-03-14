<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\RefundPaymentResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\PaymentCallEntityTest;

/**
 * Class RefundPaymentResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\RefundPaymentResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class RefundPaymentResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => PaymentCallEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new RefundPaymentResponse();
    }
}
