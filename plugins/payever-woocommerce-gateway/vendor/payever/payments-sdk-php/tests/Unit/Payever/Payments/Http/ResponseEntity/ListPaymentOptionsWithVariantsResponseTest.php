<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\ListPaymentOptionsWithVariantsResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\ListPaymentOptionsCallEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\ListPaymentOptionsVariantsResultEntityTest;

class ListPaymentOptionsWithVariantsResponseTest extends AbstractMessageEntityTest
{
    public static function getScheme()
    {
        return array(
            'call' => ListPaymentOptionsCallEntityTest::getScheme(),
            'result' => array(
                ListPaymentOptionsVariantsResultEntityTest::getScheme(),
            ),
        );
    }
    public function getEntity()
    {
        return new ListPaymentOptionsWithVariantsResponse();
    }
}
