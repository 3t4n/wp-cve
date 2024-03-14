<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\RemindPaymentResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\PaymentCallEntityTest;

/**
 * Class RemindPaymentResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\RemindPaymentResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class RemindPaymentResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => PaymentCallEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new RemindPaymentResponse();
    }
}
