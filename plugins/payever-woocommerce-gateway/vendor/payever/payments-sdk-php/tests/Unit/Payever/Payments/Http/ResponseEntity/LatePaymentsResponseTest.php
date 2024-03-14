<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\LatePaymentsResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\PaymentCallEntityTest;

/**
 * Class LatePaymentsResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\LatePaymentsResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class LatePaymentsResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => PaymentCallEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new LatePaymentsResponse();
    }
}
