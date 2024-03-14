<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\ListPaymentsCallEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class ListPaymentsCallEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\ListPaymentsCallEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class ListPaymentsCallEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'currency' => 'EUR',
        'state' => 'active',
        'limit' => 100,
    );

    public function getEntity()
    {
        return new ListPaymentsCallEntity();
    }
}
