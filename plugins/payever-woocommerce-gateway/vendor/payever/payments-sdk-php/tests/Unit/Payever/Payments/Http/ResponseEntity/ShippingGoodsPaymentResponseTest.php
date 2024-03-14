<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\ShippingGoodsPaymentResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\PaymentCallEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\ShippingGoodsPaymentResultEntityTest;

/**
 * Class ShippingGoodsPaymentResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\ShippingGoodsPaymentResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class ShippingGoodsPaymentResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => PaymentCallEntityTest::getScheme(),
            'result' => ShippingGoodsPaymentResultEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new ShippingGoodsPaymentResponse();
    }
}
