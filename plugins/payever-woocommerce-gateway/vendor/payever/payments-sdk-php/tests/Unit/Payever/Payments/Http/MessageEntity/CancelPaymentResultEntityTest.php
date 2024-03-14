<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\CancelPaymentResultEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;
use Payever\Tests\Unit\Payever\Core\Http\MessageEntity\ChannelSetEntityTest;

/**
 * Class CancelPaymentResultEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\CancelPaymentResultEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class CancelPaymentResultEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'id' => 'stub',
        'status' => 'success',
        'color_state' => 'red',
        'merchant_name' => 'stub_merchant',
        'customer_name' => 'stub_customer',
        'payment_type' => 'sofort',
        'last_action' => 'cancel',
        'created_at' => self::DEFAULT_STUB_DATE,
        'updated_at' => self::DEFAULT_STUB_DATE,
        'channel' => 'stub_channel',
        'channel_set' => array(),
        'reference' => 'stub_reference',
        'items' => array(),
        'amount' => 100.5,
        'currency' => 'EUR',
        'fee' => 10,
        'total' => 110.5,
        'address' => array(),
        'payment_details' => array(),
    );

    public static function getScheme()
    {
        $scheme = static::$scheme;

        $scheme['channel_set'] = ChannelSetEntityTest::getScheme();
        $scheme['address'] = AddressEntityTest::getScheme();
        $scheme['payment_details'] = PaymentDetailsEntityTest::getScheme();

        return $scheme;
    }

    public function getEntity()
    {
        return new CancelPaymentResultEntity();
    }
}
