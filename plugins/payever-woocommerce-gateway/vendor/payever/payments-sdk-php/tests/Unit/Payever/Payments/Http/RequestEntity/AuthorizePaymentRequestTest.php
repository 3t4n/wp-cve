<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\RequestEntity;

use Payever\Sdk\Payments\Http\RequestEntity\AuthorizePaymentRequest;
use Payever\Tests\Unit\Payever\Core\Http\AbstractRequestEntityTest;

/**
 * Class AuthorizePaymentRequestTest
 *
 * @see \Payever\Sdk\Payments\Http\RequestEntity\AuthorizePaymentRequest
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\RequestEntity
 */
class AuthorizePaymentRequestTest extends AbstractRequestEntityTest
{
    protected static $scheme = array(
        'customer_id' => 'stub_customer_id',
        'invoice_id' => 'stub_invoice_id',
        'invoice_date' => self::DEFAULT_STUB_DATE,
    );

    public function getEntity()
    {
        return new AuthorizePaymentRequest();
    }
}
