<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\RequestEntity;

use Payever\Sdk\Payments\Http\RequestEntity\RefundItemsPaymentRequest;
use Payever\Tests\Unit\Payever\Core\Http\AbstractRequestEntityTest;

/**
 * Class RefundItemsPaymentRequestTest
 *
 * @see \Payever\Sdk\Payments\Http\RequestEntity\RefundItemsPaymentRequest
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\RequestEntity
 */
class RefundItemsPaymentRequestTest extends AbstractRequestEntityTest
{
    protected static $scheme = array(
        'deliveryFee' => 100.1,
        'paymentItems' => [
            [
                'name' => 'Item 1',
                'identifier' => 'product1',
                'price' => 123.1,
                'quantity' => 1,
            ],
        ],
    );

    public function getEntity()
    {
        return new RefundItemsPaymentRequest();
    }
}
