<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity;

use Payever\Sdk\Payments\Http\ResponseEntity\GetTransactionResponse;
use Payever\Tests\Unit\Payever\Core\Http\AbstractResponseEntityTest;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\GetTransactionResultEntityTest;

/**
 * Class GetTransactionResponseTest
 *
 * @see \Payever\Sdk\Payments\Http\ResponseEntity\GetTransactionResponse
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\ResponseEntity
 */
class GetTransactionResponseTest extends AbstractResponseEntityTest
{
    public static function getScheme()
    {
        return array(
            'result' => GetTransactionResultEntityTest::getScheme(),
        );
    }

    public function getEntity()
    {
        return new GetTransactionResponse();
    }
}
