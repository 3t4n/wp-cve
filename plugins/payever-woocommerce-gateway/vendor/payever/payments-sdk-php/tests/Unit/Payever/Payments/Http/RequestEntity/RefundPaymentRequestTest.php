<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\RequestEntity;

use Payever\Sdk\Payments\Http\RequestEntity\RefundPaymentRequest;
use Payever\Tests\Unit\Payever\Core\Http\AbstractRequestEntityTest;

/**
 * Class RefundPaymentRequestTest
 *
 * @see \Payever\Sdk\Payments\Http\RequestEntity\RefundPaymentRequest
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\RequestEntity
 */
class RefundPaymentRequestTest extends AbstractRequestEntityTest
{
    protected static $scheme = array(
        'amount' => 100,
    );

    public function getEntity()
    {
        return new RefundPaymentRequest();
    }
}
