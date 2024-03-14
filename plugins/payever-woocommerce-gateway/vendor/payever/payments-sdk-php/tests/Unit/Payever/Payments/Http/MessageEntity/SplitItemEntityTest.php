<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\SplitItemEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class SplitItemEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\SplitItemEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class SplitItemEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'type' => 'type',
        'identifier' => 'identifier',
        'reference' => 'reference',
        'description' => 'description',
        'amount' => array(),
    );

    public static function getScheme()
    {
        $scheme = static::$scheme;

        $scheme['amount'] = SplitAmountEntityTest::getScheme();

        return $scheme;
    }

    public function getEntity()
    {
        return new SplitItemEntity();
    }
}
