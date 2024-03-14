<?php

namespace Payever\Tests\Unit\Payever\Core\Http\MessageEntity;

use Payever\Sdk\Core\Http\MessageEntity\GetCurrenciesResultEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class GetCurrenciesResultEntityTest
 *
 * @see \Payever\Sdk\Core\Http\MessageEntity\GetCurrenciesResultEntity
 *
 * @package Payever\Tests\Unit\Payever\Core\Http\MessageEntity
 */
class GetCurrenciesResultEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'name' => 'US Dollar',
        'symbol' => 'USD',
        'code' => 'USD',
        'rate' => 1.3,
    );

    public function getEntity()
    {
        return new GetCurrenciesResultEntity();
    }
}
