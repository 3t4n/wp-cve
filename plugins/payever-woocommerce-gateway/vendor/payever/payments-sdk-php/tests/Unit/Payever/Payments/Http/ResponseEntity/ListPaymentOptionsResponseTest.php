<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\ListPaymentOptionsResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\ListPaymentOptionsCallEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\ListPaymentOptionsResultEntityTest;

/**
 * Class ListPaymentOptionsResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\ListPaymentOptionsResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class ListPaymentOptionsResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => ListPaymentOptionsCallEntityTest::getScheme(),
            'result' => array(
                ListPaymentOptionsResultEntityTest::getScheme(),
            ),
        );
    }

    public function getEntity()
    {
        return new ListPaymentOptionsResponse();
    }
}
