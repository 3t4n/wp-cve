<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\ListPaymentOptionsCallEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;
use Payever\Tests\Unit\Payever\Core\Http\MessageEntity\ChannelSetEntityTest;

/**
 * Class ListPaymentOptionsCallEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\ListPaymentOptionsCallEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class ListPaymentOptionsCallEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'action' => 'get',
        'channel' => 'shopware',
        'channel_set' => array(),
        'type' => 'stub',
    );

    public static function getScheme()
    {
        $scheme = static::$scheme;

        $scheme['channel_set'] = ChannelSetEntityTest::getScheme();

        return $scheme;
    }

    public function getEntity()
    {
        return new ListPaymentOptionsCallEntity();
    }
}
