<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\CreatePaymentResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\CreatePaymentCallEntityTest;

/**
 * Class CreatePaymentResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\CreatePaymentResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class CreatePaymentResponseTest extends AbstractResponseEntityTest
{
    protected static $scheme = array(
        'redirect_url' => 'https://sandbox.payver.de/pay/id',
        'call' => array(),
    );

    public static function getScheme()
    {
        $scheme = static::$scheme;

        $scheme['call'] = CreatePaymentCallEntityTest::getScheme();

        return $scheme;
    }

    public function getEntity()
    {
        return new CreatePaymentResponse();
    }
}
