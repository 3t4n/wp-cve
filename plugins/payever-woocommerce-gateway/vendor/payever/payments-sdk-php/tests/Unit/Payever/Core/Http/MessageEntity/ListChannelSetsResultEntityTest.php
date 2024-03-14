<?php

namespace Payever\Tests\Unit\Payever\Core\Http\MessageEntity;

use Payever\Sdk\Core\Http\MessageEntity\ListChannelSetsResultEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class ListChannelSetsResultEntityTest
 *
 * @see \Payever\Sdk\Core\Http\MessageEntity\ListChannelSetsResultEntity
 *
 * @package Payever\Tests\Unit\Payever\Core\Http\MessageEntity
 */
class ListChannelSetsResultEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'id' => 'stub',
        'channel_type' => 'shopware',
        'channel_sets' => array(),
        'enabled' => true,
    );

    public static function getScheme()
    {
        $scheme = static::$scheme;

        $scheme['channel_sets'] = array(ChannelSetEntityTest::getScheme());

        return $scheme;
    }

    public function getEntity()
    {
        return new ListChannelSetsResultEntity();
    }
}
