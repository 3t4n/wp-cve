<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\ListPaymentsResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\ListPaymentsCallEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\ListPaymentsResultEntityTest;

/**
 * Class ListPaymentsResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\ListPaymentsResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class ListPaymentsResponseTest extends AbstractResponseEntityTest
{
    protected static $scheme = array(
        'redirect_url' => 'https://sandbox.payever.de/some/path',
        'call' => array(),
        'result' => array(),
    );

    public static function getScheme()
    {
        $scheme = static::$scheme;

        $scheme['call'] = ListPaymentsCallEntityTest::getScheme();
        $scheme['result'] = array(
            ListPaymentsResultEntityTest::getScheme(),
        );

        return $scheme;
    }

    public function getEntity()
    {
        return new ListPaymentsResponse();
    }
}
