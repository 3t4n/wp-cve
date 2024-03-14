<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\RequestEntity;

use Payever\Sdk\Payments\Http\RequestEntity\ShippingGoodsPaymentRequest;
use Payever\Tests\Unit\Payever\Core\Http\AbstractRequestEntityTest;

/**
 * Class ShippingGoodsPaymentRequestTest
 *
 * @see \Payever\Sdk\Payments\Http\RequestEntity\ShippingGoodsPaymentRequest
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\RequestEntity
 */
class ShippingGoodsPaymentRequestTest extends AbstractRequestEntityTest
{
    protected static $scheme = array(
        'reason' => 'reason',
        'amount' => 1.00,
        'shipping_details' => array(
            "shippingMethod" => "Flat rate"
        )
    );

    public function getEntity()
    {
        return new ShippingGoodsPaymentRequest();
    }
}
