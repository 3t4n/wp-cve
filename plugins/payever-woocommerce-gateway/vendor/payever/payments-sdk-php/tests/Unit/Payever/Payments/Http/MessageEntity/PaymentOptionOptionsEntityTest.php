<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\PaymentOptionOptionsEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class PaymentOptionOptionsEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\PaymentOptionOptionsEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class PaymentOptionOptionsEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'currencies' => array('EUR', 'USD'),
        'countries' => array('DE', 'US'),
        'actions' => array('purchase'),
    );

    public function getEntity()
    {
        return new PaymentOptionOptionsEntity();
    }
}
