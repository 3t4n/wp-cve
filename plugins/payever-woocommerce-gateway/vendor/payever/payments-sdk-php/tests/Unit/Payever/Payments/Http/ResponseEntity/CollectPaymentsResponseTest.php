<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\CollectPaymentsResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\PaymentCallEntityTest;

/**
 * Class CollectPaymentsResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\CollectPaymentsResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class CollectPaymentsResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => PaymentCallEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new CollectPaymentsResponse();
    }
}
