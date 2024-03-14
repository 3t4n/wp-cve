<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\SplitAmountEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class SplitAmountEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\SplitAmountEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class SplitAmountEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'value' => 'value',
        'currency' => 'EUR',
    );

    public function getEntity()
    {
        return new SplitAmountEntity();
    }
}
